<?php
/**
 * YY Live Stream Final Proxy (v3.0)
 * 使用官方 WAP 接口 + 动态 TS 转发
 */

error_reporting(0); // 屏蔽非致命错误干扰输出
set_time_limit(0);  // 防止流传输中断
ini_set('memory_limit', '128M');
date_default_timezone_set("Asia/Shanghai");

// ---------------------------------------------------------
// 配置区域
// ---------------------------------------------------------
const CACHE_DIR = __DIR__ . '/yycache';
const CACHE_TIME = 1800; // 缓存30分钟 (直播地址变动较快，建议设短一点)
const DEFAULT_ID = '34229877'; // 默认频道

// ---------------------------------------------------------
// 路由逻辑
// ---------------------------------------------------------

$id = isset($_GET['id']) ? preg_replace('/[^0-9]/', '', $_GET['id']) : DEFAULT_ID;
if (empty($id)) $id = DEFAULT_ID;

// 模式1: TS 分片代理 (当 URL 带有 ts 参数时)
if (isset($_GET['ts'])) {
    $tsUrl = base64_decode($_GET['ts']);
    if (filter_var($tsUrl, FILTER_VALIDATE_URL)) {
        proxyTs($tsUrl);
    } else {
        header("HTTP/1.1 400 Bad Request");
        exit;
    }
    exit;
}

// 模式2: 主播放列表 (M3U8)
// 1. 获取真实播放地址
$playUrl = getRealUrl($id);

if (!$playUrl) {
    header("HTTP/1.1 404 Not Found");
    exit("直播未开始或接口失效");
}

// 2. 重写 M3U8 内容以通过本地代理
serveM3u8($playUrl);


// ---------------------------------------------------------
// 核心函数
// ---------------------------------------------------------

/**
 * 获取 YY 真实直播流地址 (使用 WAP 接口)
 */
function getRealUrl($rid) {
    $cacheFile = CACHE_DIR . "/room_{$rid}.json";
    
    // 尝试读缓存
    if (file_exists($cacheFile)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (isset($data['time'], $data['url']) && time() - $data['time'] < CACHE_TIME) {
            return $data['url'];
        }
    }

    // WAP 端接口 (无需复杂签名)
    // source=wapyy 是关键，模拟手机网页端
    $apiUrl = "https://interface.yy.com/hls/new/get/{$rid}/{$rid}/1200?source=wapyy&callback=jsonp3";
    
    $headers = [
        "Referer: https://wap.yy.com/",
        "User-Agent: Mozilla/5.0 (Linux; Android 10; Mobile) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.181 Mobile Safari/537.36",
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $res = curl_exec($ch);
    curl_close($ch);

    // 处理 JSONP 返回格式: jsonp3({...})
    if ($res && preg_match('/jsonp3\((.*)\)/', $res, $matches)) {
        $json = json_decode($matches[1], true);
        if (isset($json['hls']) && !empty($json['hls'])) {
            $realUrl = $json['hls'];
            
            // 确保缓存目录存在
            if (!is_dir(CACHE_DIR)) mkdir(CACHE_DIR, 0755, true);
            
            file_put_contents($cacheFile, json_encode([
                'time' => time(),
                'url' => $realUrl
            ]));
            return $realUrl;
        }
    }
    return null;
}

/**
 * 读取 M3U8 并重写内部链接
 */
function serveM3u8($url) {
    $m3u8Content = curlGet($url);
    
    if (!$m3u8Content) {
        header("HTTP/1.1 502 Bad Gateway");
        exit("Failed to fetch playlist");
    }

    // 获取当前脚本地址
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $currentScript = "$protocol://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]";

    // 计算 Base URL (用于解析相对路径)
    $baseUrl = dirname($url) . '/';

    $lines = explode("\n", $m3u8Content);
    
    header("Content-Type: application/vnd.apple.mpegurl");
    header("Access-Control-Allow-Origin: *");
    header("Cache-Control: no-cache");
    // 禁用下载，让浏览器直接播
    header("Content-Disposition: inline");

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        if ($line[0] === '#') {
            echo $line . "\n";
        } else {
            // 这是一个 TS 文件的路径
            // 1. 补全为绝对路径
            if (strpos($line, 'http') !== 0) {
                $realTs = $baseUrl . $line;
            } else {
                $realTs = $line;
            }

            // 2. 构造代理链接
            // 将真实 TS 地址 Base64 编码后传给自己
            echo $currentScript . "?ts=" . base64_encode($realTs) . "\n";
        }
    }
}

/**
 * 代理 TS 文件下载
 */
function proxyTs($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // 直接输出到浏览器，不存内存
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    // 关键：伪造 Referer 和 UA，骗过 CDN 防盗链
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Referer: https://wap.yy.com/",
        "User-Agent: Mozilla/5.0 (Linux; Android 10; Mobile) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.181 Mobile Safari/537.36"
    ]);

    // 透传 Content-Type
    header("Content-Type: video/mp2t");
    header("Access-Control-Allow-Origin: *");
    
    curl_exec($ch);
    curl_close($ch);
}

/**
 * 简单的 Curl GET 辅助函数
 */
function curlGet($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Referer: https://wap.yy.com/",
        "User-Agent: Mozilla/5.0 (Linux; Android 10; Mobile) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.181 Mobile Safari/537.36"
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>
