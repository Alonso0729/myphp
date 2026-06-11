<?php
/**
 * 看看新闻直播代理 - 最终版（智能 TS 代理 + 共享锁缓存 for 都市频道）
 * 用法：http://your-domain/kankan.php?id=shds  或 ?id=hhxd
 */
error_reporting(0);
date_default_timezone_set('Asia/Shanghai');

$id = $_GET['id'] ?? 'shds';
$channelMap = [
    'dfws' => 1, 'shxwzh' => 2, 'shds' => 4, 'dycj' => 5,
    'hhxd' => 9, 'wxty' => 10, 'mdy' => 11, 'xjs' => 12,
];
if (!isset($channelMap[$id])) die('无效频道ID');
$channelId = $channelMap[$id];

// ==================== 处理 TS 代理请求 ====================
if (isset($_GET['ts_url'])) {
    $ts_url = urldecode($_GET['ts_url']);
    // 仅都市频道需要代理（其他频道的 TS 不会进入此分支，因为 m3u8 中直接输出绝对地址）
    // 但为了安全，仍调用 proxy_ts（该函数已包含缓存锁）
    proxy_ts($ts_url);
    exit;
}

// ==================== 1. 获取带签名的 live_url ====================
function getnonce($len = 8) {
    $base36 = base_convert(mt_rand() / mt_getrandmax(), 10, 36);
    return substr(str_pad($base36, $len, '0', STR_PAD_LEFT), -$len);
}

$t = time();
$nonce = getnonce(8);
$version = '2.41.9';
$secret = '28c8edde3d61a0411511d3b1866f0636';
$signStr = "Api-Version=v1&channel_id={$channelId}&nonce={$nonce}&platform=pc&timestamp={$t}&version={$version}&{$secret}";
$sign = md5(md5($signStr));

$clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
elseif (isset($_SERVER['HTTP_CLIENT_IP'])) $clientIp = $_SERVER['HTTP_CLIENT_IP'];

$headers = [
    "Api-Version: v1", "Nonce: {$nonce}", "M-Uuid: VHLlGDrc12uWR0P1fxuQD",
    "Platform: pc", "Version: {$version}", "Timestamp: {$t}", "Sign: {$sign}",
    "Origin: https://live.kankanews.com", "Referer: https://live.kankanews.com/",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.5845.97 Safari/537.36 SE 2.X MetaSr 1.0",
    "X-Forwarded-For: {$clientIp}", "Client-Ip: {$clientIp}",
];

$apiUrl = "https://kapi.kankanews.com/content/pc/tv/channel/detail?channel_id={$channelId}";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl, CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => $headers,
    CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_TIMEOUT => 10,
]);
$resp = curl_exec($ch);
curl_close($ch);
if (!$resp) die('API请求失败');
$data = json_decode($resp, true);
if (empty($data['result']['live_address'])) die('未找到直播地址');
$encrypted = $data['result']['live_address'];

// RSA 解密
function rsaDecrypt($str) {
    $pubKey = "-----BEGIN PUBLIC KEY-----\nMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDP5hzPUW5RFeE2xBT1ERB3hHZI\nVotn/qatWhgc1eZof09qKjElFN6Nma461ZAwGpX4aezKP8Adh4WJj4u2O54xCXDt\nwzKRqZO2oNZkuNmF2Va8kLgiEQAAcxYc8JgTN+uQQNpsep4n/o1sArTJooZIF17E\ntSqSgXDcJ7yDj5rc7wIDAQAB\n-----END PUBLIC KEY-----";
    $key = openssl_pkey_get_public($pubKey);
    if (!$key) return false;
    $chunkSize = openssl_pkey_get_details($key)['bits'] / 8;
    $encData = base64_decode($str);
    $result = '';
    for ($i = 0; $i < strlen($encData); $i += $chunkSize) {
        $chunk = substr($encData, $i, $chunkSize);
        openssl_public_decrypt($chunk, $dec, $key);
        $result .= $dec;
    }
    return $result;
}
$liveUrl = rsaDecrypt($encrypted);
if (!$liveUrl) die('解密失败');

// ==================== 2. 通用函数：获取 m3u8 内容（带请求头）====================
function fetchM3u8($url, &$respHeaders = null) {
    $ch = curl_init($url);
    $reqHeaders = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.5845.97 Safari/537.36 SE 2.X MetaSr 1.0',
        'Accept: */*', 'Accept-Language: zh-CN,zh;q=0.9',
        'Origin: https://live.kankanews.com', 'Referer: https://live.kankanews.com/',
        'Connection: keep-alive',
    ];
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 15, CURLOPT_HTTPHEADER => $reqHeaders,
        CURLOPT_HEADERFUNCTION => function($ch, $header) use (&$respHeaders) {
            if (preg_match('/^([^:]+):\s*(.+)$/', $header, $m))
                $respHeaders[strtolower($m[1])] = trim($m[2]);
            return strlen($header);
        },
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

// 将相对 URL 转为绝对 URL
function toAbsoluteUrl($base, $relative) {
    if (preg_match('/^https?:\/\//i', $relative)) return $relative;
    $parts = parse_url($base);
    $baseDir = dirname($parts['path']) . '/';
    if ($relative[0] === '/') $relative = ltrim($relative, '/');
    return $parts['scheme'] . '://' . $parts['host'] . $baseDir . $relative;
}

// 递归解析 m3u8，返回扁平化的内容（仅保留 TS 行和必要标签，去除嵌套）
function flattenM3u8($url, $depth = 0) {
    if ($depth > 5) return '';
    $content = fetchM3u8($url);
    if (!$content) return '';
    $lines = explode("\n", $content);
    $output = [];
    $i = 0;
    while ($i < count($lines)) {
        $line = rtrim($lines[$i]);
        if (strpos($line, '#EXT-X-STREAM-INF') !== false) {
            $i++;
            if ($i >= count($lines)) break;
            $subUrl = trim($lines[$i]);
            $subUrl = toAbsoluteUrl($url, $subUrl);
            $subContent = flattenM3u8($subUrl, $depth + 1);
            if ($subContent) $output[] = $subContent;
        } else {
            $output[] = $line;
        }
        $i++;
    }
    return implode("\n", $output);
}

// ==================== 3. 获取最终 TS 列表 ====================
$flatContent = flattenM3u8($liveUrl);
if (!$flatContent) die('无法解析 m3u8 流');

// ==================== 4. 处理每一行：补全相对路径，决定是否代理 TS ====================
$scriptName = $_SERVER['SCRIPT_NAME'];
$lines = explode("\n", $flatContent);
$newLines = [];
$baseUrl = $liveUrl; // 用于解析相对路径

foreach ($lines as $line) {
    $line = rtrim($line);
    if (strpos($line, '#') === 0 || $line === '') {
        $newLines[] = $line;
        continue;
    }
    $absolute = toAbsoluteUrl($baseUrl, $line);
    // 判断是否需要代理（都市频道的 TS 包含 token 或 wsSession）
    if (strpos($absolute, '?token=') !== false || strpos($absolute, 'wsSession') !== false) {
        $proxyUrl = $scriptName . '?ts_url=' . urlencode($absolute);
        $newLines[] = $proxyUrl;
    } else {
        $newLines[] = $absolute; // 其他频道直连
    }
}
$finalM3u8 = implode("\n", $newLines);

// ==================== 5. 输出 m3u8（禁止缓存）====================
header('Content-Type: application/vnd.apple.mpegurl');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
echo $finalM3u8;
exit;

// ==================== 6. TS 代理函数（仅用于都市频道，带共享锁缓存）====================
function proxy_ts($ts_url) {
    $cache_dir = __DIR__ . '/ts_cache';
    if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);
    $cache_key = md5($ts_url);
    $cache_file = $cache_dir . '/' . $cache_key . '.ts';
    $cache_ttl = 3600; // 缓存1小时

    // 尝试读取缓存（共享锁）
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_ttl) {
        $fp = fopen($cache_file, 'rb');
        if ($fp && flock($fp, LOCK_SH)) {
            $data = '';
            while (!feof($fp)) $data .= fread($fp, 8192);
            flock($fp, LOCK_UN);
            fclose($fp);
            if ($data) {
                header('Content-Type: video/MP2T');
                header('Cache-Control: max-age=3600');
                echo $data;
                exit;
            }
        }
        if ($fp) fclose($fp);
    }

    // 缓存未命中，请求源站 TS
    $headers = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.5845.97 Safari/537.36 SE 2.X MetaSr 1.0',
        'Accept: */*', 'Accept-Language: zh-CN,zh;q=0.9',
        'Origin: https://live.kankanews.com', 'Referer: https://live.kankanews.com/',
        'Connection: keep-alive',
    ];
    $ch = curl_init($ts_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($data === false || $httpCode !== 200) {
        http_response_code(502);
        echo "无法获取 TS 分片";
        exit;
    }

    // 写入缓存（独占锁）
    $fp = fopen($cache_file, 'wb');
    if ($fp && flock($fp, LOCK_EX)) {
        fwrite($fp, $data);
        flock($fp, LOCK_UN);
        fclose($fp);
    } else {
        if ($fp) fclose($fp);
    }

    header('Content-Type: video/MP2T');
    header('Cache-Control: max-age=3600');
    echo $data;
    exit;
}
?>