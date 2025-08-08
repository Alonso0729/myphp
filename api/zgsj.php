<?php

date_default_timezone_set('Asia/Shanghai');

/** ====================== 可选：强制公开地址 ======================
 * 强烈建议填成你要给播放器看的对外地址（含脚本路径）：
 *   例：const ZGSJ_PUBLIC_BASE = 'http://123.45.6.7:8080/zgsj.php';
 * 留空则自动用 SERVER_ADDR:SERVER_PORT 推算。
CCTV1综合频道,http://IP:port/zgsj.php?id=G_CCTV-1-HQ
CCTV2财经频道,http://IP:port/zgsj.php?id=G_CCTV-2-HQ
CCTV3综艺频道,http://IP:port/zgsj.php?id=G_CCTV-3-HQ
CCTV4中文国际,http://IP:port/zgsj.php?id=G_CCTV-4-HQ
CCTV5体育频道,http://IP:port/zgsj.php?id=G_CCTV-5-HQ
CCTV5+体育赛事,http://IP:port/zgsj.php?id=G_CCTV-5PLUS-HQ
CCTV6电影频道,http://IP:port/zgsj.php?id=G_CCTV-6-HQ
CCTV7国防军事,http://IP:port/zgsj.php?id=G_CCTV-7-HQ
CCTV8电视剧频道,http://IP:port/zgsj.php?id=G_CCTV-8-HQ
CCTV9纪录片频道,http://IP:port/zgsj.php?id=G_CCTV-9-HQ
CCTV10科教频道,http://IP:port/zgsj.php?id=G_CCTV-10-HQ
CCTV11戏曲频道,http://IP:port/zgsj.php?id=G_CCTV-11-HQ
CCTV12社会与法,http://IP:port/zgsj.php?id=G_CCTV-12-HQ
CCTV13新闻频道,http://IP:port/zgsj.php?id=G_CCTV-13-HQ
CCTV14少儿频道,http://IP:port/zgsj.php?id=G_CCTV-14-HQ
CCTV15音乐频道,http://IP:port/zgsj.php?id=G_CCTV-15-HQ
CCTV16奥林匹克,http://IP:port/zgsj.php?id=G_CCTV-16-HQ
CCTV17农业农村,http://IP:port/zgsj.php?id=G_CCTV-17-HQ
CCTV4K,http://IP:port/zgsj.php?id=G_CCTV-4K-HD
北京卫视4K,http://IP:port/zgsj.php?id=G_BEIJING-4K
CGTN新闻,http://IP:port/zgsj.php?id=G_CCTV-NEWS
广东卫视高清,http://IP:port/zgsj.php?id=G_GUANGDONG-HQ
江苏卫视超清,http://IP:port/zgsj.php?id=G_JIANGSU-HQ
浙江卫视超清,http://IP:port/zgsj.php?id=G_ZHEJIANG-HQ
湖南卫视超清,http://IP:port/zgsj.php?id=G_HUNAN-HQ
东方卫视超清,http://IP:port/zgsj.php?id=G_DONGFANG-HQ
北京卫视超清,http://IP:port/zgsj.php?id=G_BEIJING-HQ
深圳卫视高清,http://IP:port/zgsj.php?id=G_SHENZHEN-HQ
辽宁卫视,http://IP:port/zgsj.php?id=G_LIAONING-HQ
安徽卫视,http://IP:port/zgsj.php?id=G_ANHUI-HQ
山东卫视超清,http://IP:port/zgsj.php?id=G_SHANDONG-HQ
黑龙江卫视高清,http://IP:port/zgsj.php?id=G_HEILONGJIANG-HQ
天津卫视,http://IP:port/zgsj.php?id=G_TIANJIN-HQ
广西卫视,http://IP:port/zgsj.php?id=G_GUANGXI-HQ
东南卫视高清,http://IP:port/zgsj.php?id=G_DONGNAN-HQ
甘肃卫视,http://IP:port/zgsj.php?id=G_GANSU-HQ
贵州卫视高清,http://IP:port/zgsj.php?id=G_GUIZHOU-HQ
海南卫视,http://IP:port/zgsj.php?id=G_HAINAN-HQ
河北卫视高清,http://IP:port/zgsj.php?id=G_HEBEI-HQ
河南卫视高清,http://IP:port/zgsj.php?id=G_HENAN-HQ
湖北卫视高清,http://IP:port/zgsj.php?id=G_HUBEI-HQ
吉林卫视高清,http://IP:port/zgsj.php?id=G_JILIN-HQ
江西卫视高清,http://IP:port/zgsj.php?id=G_JIANGXI-HQ
康巴卫视,http://IP:port/zgsj.php?id=G_KANGBA
安多卫视,http://IP:port/zgsj.php?id=G_ANDUO
兵团卫视,http://IP:port/zgsj.php?id=G_BINGTUAN
内蒙古卫视,http://IP:port/zgsj.php?id=G_NEIMENGGU
宁夏卫视,http://IP:port/zgsj.php?id=G_NINGXIA
农林卫视,http://IP:port/zgsj.php?id=G_NONGLIN
三沙卫视,http://IP:port/zgsj.php?id=G_SANSHA-HQ
陕西卫视,http://IP:port/zgsj.php?id=G_SHANXI-HQ
四川卫视,http://IP:port/zgsj.php?id=G_SICHUAN-HQ
西藏卫视,http://IP:port/zgsj.php?id=G_XIZANG
新疆卫视,http://IP:port/zgsj.php?id=G_XINJIANG
延边卫视,http://IP:port/zgsj.php?id=G_YANBIAN
云南卫视,http://IP:port/zgsj.php?id=G_YUNNAN-HQ
重庆卫视,http://IP:port/zgsj.php?id=G_CHONGQING-HQ
北京冬奥纪实高清,http://IP:port/zgsj.php?id=G_BEIJINGJS-HD
家庭理财,http://IP:port/zgsj.php?id=G_JIATINGLC
金鹰卡通,http://IP:port/zgsj.php?id=G_JINYING
卡酷少儿,http://IP:port/zgsj.php?id=G_KAKU
女性时尚,http://IP:port/zgsj.php?id=G_NVXINGSS
天元围棋,http://IP:port/zgsj.php?id=G_TIANYUANWQ
炫动卡通,http://IP:port/zgsj.php?id=G_XUANDONG
优漫卡通,http://IP:port/zgsj.php?id=G_YOUMAN
CETV-1高清,http://IP:port/zgsj.php?id=G_CETV-1-HQ
CETV-2,http://IP:port/zgsj.php?id=G_CETV-2
CETV-3,http://IP:port/zgsj.php?id=G_CETV-3
CETV-4,http://IP:port/zgsj.php?id=G_CETV-4
 */
 
const ZGSJ_PUBLIC_BASE = '';

function zgsj_cache_dir(): string
{
    $dir = __DIR__ . '/zgsjcache';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir;
}

function getZgsjCache(string $key): array
{
    $path = zgsj_cache_dir() . "/zgsj_{$key}.cache.json";
    if (!is_file($path)) return ['found' => false];
    $arr = json_decode((string)@file_get_contents($path), true);
    if (!is_array($arr)) return ['found' => false];
    if (!isset($arr['expire']) || $arr['expire'] < time()) return ['found' => false];
    return $arr;
}

function setZgsjCache(string $key, string $value, int $ttlSeconds): void
{
    $payload = ['value' => $value, 'expire' => time() + $ttlSeconds];
    @file_put_contents(zgsj_cache_dir() . "/zgsj_{$key}.cache.json", json_encode($payload, JSON_UNESCAPED_SLASHES));
}


function selfScript(): string
{
    if (ZGSJ_PUBLIC_BASE !== '') return ZGSJ_PUBLIC_BASE;

    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme = $https ? 'https' : 'http';

    $addr = $_SERVER["SERVER_NAME"];
    $port = (string)($_SERVER['SERVER_PORT'] ?? '80');
    $needPort = ($scheme === 'http' && $port !== '80')
        || ($scheme === 'https' && $port !== '443');

    $script = $_SERVER['SCRIPT_NAME'] ?? '/zgsj.php';
    return $scheme . '://' . $addr . ($needPort ? ':' . $port : '') . $script;
}


function hostHeaderFromUrl(string $url): string
{
    $p = parse_url($url);
    if (!$p || empty($p['host'])) return '';
    return isset($p['port']) ? ($p['host'] . ':' . $p['port']) : $p['host'];
}


function resolveUrl(string $base, string $ref): string
{
    if (preg_match('#^https?://#i', $ref)) return $ref;
    if (strpos($ref, '//') === 0) {
        $bp = parse_url($base);
        $scheme = $bp['scheme'] ?? 'http';
        return $scheme . ':' . $ref;
    }
    $bp = parse_url($base);
    $scheme = $bp['scheme'] ?? 'http';
    $host = $bp['host'] ?? '';
    $port = isset($bp['port']) ? ':' . $bp['port'] : '';
    $bpath = $bp['path'] ?? '/';

    if (strpos($ref, '/') === 0) {
        return $scheme . '://' . $host . $port . $ref;
    }


    $baseDir = rtrim(substr($bpath, 0, strrpos($bpath, '/') + 1), '/') . '/';


    $path = $baseDir . $ref;
    $parts = [];
    foreach (explode('/', $path) as $seg) {
        if ($seg === '' || $seg === '.') continue;
        if ($seg === '..') {
            array_pop($parts);
            continue;
        }
        $parts[] = $seg;
    }
    $norm = '/' . implode('/', $parts);
    return $scheme . '://' . $host . $port . $norm;
}


function D(): string
{
    return "fUw8mWqp0ih8KRPzmgHnoL4fx0X0N78o4Od6D4lFTV32S7uVa2mYSZjRofquHHU16gkpjvInD07bKwb05S0hlJYfYMmUyQprvoEqM2I8Dfe2oX0MTAJEg8DYDul6lJqg";
}

function generateRandomMobile(): string
{
    $prefixes = [
        "134", "135", "136", "137", "138", "139",
        "150", "151", "152", "157", "158", "159",
        "182", "183", "184", "187", "188",
        "130", "131", "132", "155", "156", "185", "186",
        "176", "177", "178",
        "170", "171", "172", "173", "174", "175", "179",
    ];
    $prefix = $prefixes[random_int(0, count($prefixes) - 1)];
    $suffix = str_pad((string)random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    return $prefix . $suffix;
}

function generateUpperMac(string $sep): string
{
    $parts = [];
    for ($i = 0; $i < 6; $i++) {
        $b = random_int(0, 255);
        $parts[] = strtoupper(sprintf('%02X', $b));
    }
    return implode($sep, $parts);
}

function generateAuth(int $j, int $expire): string
{
    $strD = D();
    $maxIdx = strlen($strD) - 19;
    if ($maxIdx < 1) $maxIdx = 1;
    $start = (int)($j % $maxIdx);
    $sub = substr($strD, $start, 20);
    $toHash = $sub . (string)$expire;
    $hash = hash('sha256', $toHash);
    return sprintf('%s_%d_%d', $hash, $j, $expire);
}

function formatTimestamp(int $ts): string
{
    return date('YmdHis', $ts);
}


function getZgsjPlayUrl(string $id): string
{
    $cacheKey = $id;


    $c = getZgsjCache($cacheKey);
    if (!empty($c['value']) && !empty($c['expire']) && $c['expire'] >= time()) {
        return (string)$c['value'];
    }


    $mac = generateUpperMac(':');
    $userid = generateRandomMobile();
    $timestamp = time();

    $playAuthToken = generateAuth($timestamp, 120);
    $gTime = formatTimestamp($timestamp);


    $url = sprintf(
        'http://hecmcc-gvp-zy.playauth.gitv.tv/itv/playauth?' .
        'playAuthToken=%s&gAreaId=CHN&gAppChannel=DEFAULT&gSoftTermId=SMART_SPEAKER' .
        '&gManufacturer=samsung&gModel=SM-G9810&gMac=%s&gStbId=unknown&gOsType=android' .
        '&gOsVersion=9&gApiLevel=29&gAppVersionCode=1431&gAppVersionName=1.4.31' .
        '&gDowngrade=NONEMERGENCY&gRomVersion=DEFAULT&gProductLineCode=GVP' .
        '&gTimestamp=%s&gRequests=1',
        $playAuthToken,
        $mac,
        $gTime
    );


    $payload = [
        'contentId' => $id,
        'playUrl' => sprintf(
            'http://hegd-livod.ali-cdn.gitv.tv/gitv_live/%s/%s.m3u8?gMac=%s&partnerCode=HB_CMCC_GVP_ZY',
            $id, $id, $mac
        ),
        'stbId' => 'unknown',
        'userId' => $userid,
    ];
    $json = json_encode($payload, JSON_UNESCAPED_SLASHES);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'User-Agent: okhttp/3.8.1',
            'Content-Type: application/json',
            'Accept: */*',
            'Host: hecmcc-gvp-zy.playauth.gitv.tv',
            'Connection: keep-alive',
        ],

    ]);

    $resp = curl_exec($ch);
    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        http_response_code(502);
        exit("zgsj playauth request failed: $err");
    }
    curl_close($ch);

    $data = json_decode($resp, true);

    $playUrl = '';
    if (is_array($data) && isset($data['data']['playUrl'])) {
        $playUrl = (string)$data['data']['playUrl'];
    }

    if ($playUrl !== '') {
        setZgsjCache($cacheKey, $playUrl, 2 * 3600);
    }

    return $playUrl;
}

function zgsjRequestGet(string $finalUrl): string
{
    $hostHeader = hostHeaderFromUrl($finalUrl);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $finalUrl,
        CURLOPT_HTTPGET => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array_values(array_filter([
            'User-Agent: com.gitv.tv.gvp/1.4.31 (Linux;Android 13) ExoPlayerLib/2.17.1',
            $hostHeader ? ('Host: ' . $hostHeader) : null,
        ])),

    ]);

    $resp = curl_exec($ch);
    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        http_response_code(502);
        exit("zgsjRequestGet failed: $err");
    }

    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code < 200 || $code >= 300) {
        http_response_code(502);
        exit("requestGet responseCode: $code");
    }
    return (string)$resp;
}


function handleZgsjTsRequest(string $ts): void
{
    $tsUrl = base64_decode($ts);
    if (!preg_match('#^https?://#i', $tsUrl)) {
        http_response_code(400);
        exit('invalid ts url');
    }

    header('Content-Type: video/MP2T');
    header('Transfer-Encoding: chunked');

    ignore_user_abort(true);
    set_time_limit(0);

    $hostHeader = hostHeaderFromUrl($tsUrl);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $tsUrl,
        CURLOPT_HTTPGET => true,
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_WRITEFUNCTION => function ($ch, $data) {
            echo $data;
            @ob_flush();
            flush();
            return strlen($data);
        },
        CURLOPT_HTTPHEADER => array_values(array_filter([
            'User-Agent: com.gitv.tv.gvp/1.4.31 (Linux;Android 13) ExoPlayerLib/2.17.1',
            $hostHeader ? ('Host: ' . $hostHeader) : null,
            'Connection: keep-alive',
        ])),

    ]);

    $ok = curl_exec($ch);
    if ($ok === false) {
        if (!headers_sent()) header('HTTP/1.1 502 Bad Gateway');
    }
    curl_close($ch);
    exit;
}


function handleZgsjMainRequest(string $id): void
{
    $finalUrl = getZgsjPlayUrl($id);
    if ($finalUrl === '') {
        http_response_code(502);
        exit('empty playUrl');
    }

    $m3u8 = zgsjRequestGet($finalUrl);
    $selfBase = selfScript();

    $rewritten = preg_replace_callback(
        '/(?mi)^(?!#)([^\\r\\n]*?\\.ts(?:\\?[^\\r\\n]*)?)\\s*$/',
        function ($m) use ($finalUrl, $selfBase) {
            $seg = trim($m[1]);
            $abs = resolveUrl($finalUrl, $seg);
            return $selfBase . '?ts=' . base64_encode($abs);
        },
        $m3u8
    );

    header('Content-Type: application/vnd.apple.mpegurl');
    header('Content-Disposition: attachment;filename=' . $id . '.m3u8');
    echo $rewritten ?? $m3u8;
    exit;
}


$ts = $_GET['ts'] ?? null;
$id = $_GET['id'] ?? "G_BEIJING-4K";

if ($ts) {
    handleZgsjTsRequest($ts);
} elseif ($id) {
    handleZgsjMainRequest($id);
}
