<?php
//本PHP首发于直播源论坛https://bbs.livecodes.vip/
date_default_timezone_set("Asia/Shanghai");

$id = empty($_GET['id']) ? "34229877" : trim($_GET['id']);

// --- cache settings ---
$cacheDir   = __DIR__ . '/yycache';
$cacheFile  = $cacheDir . '/playurl_' . preg_replace('/[^A-Za-z0-9_\-]/','',$id) . '.json';
$cacheTTL   = 2 * 3600; // 2 hours

// ensure cache directory exists
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

// try to load from cache
$playUrl = null;
if (file_exists($cacheFile)) {
    $cache = json_decode(file_get_contents($cacheFile), true);
    if (isset($cache['timestamp'], $cache['playUrl'])
        && (time() - $cache['timestamp'] < $cacheTTL)
    ) {
        // cache hit
        $playUrl = $cache['playUrl'];
    }
}

// if cache miss, fetch a new one
if (empty($playUrl)) {
    $ref      = strtotime('2001-01-01 00:00:00 UTC');
    $now      = time();
    $interval = $now - $ref;
    $token    = md5($id . $id . $interval . 'eDpJVWU$hL+Jv``$0Z');
    $apiurl   = "http://yyapp-data.yy.com/live/hls/auth/{$id}/{$id}"
              . "?coderate=8000&timestamp={$interval}&token={$token}";

    $headers = [
        'Host: yyapp-data.yy.com',
        'User-Agent: YYMobile/126 CFNetwork/3826.500.131 Darwin/24.5.0'
    ];
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $apiurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    $data = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($data, true);
    if (isset($json['data']) && filter_var($json['data'], FILTER_VALIDATE_URL)) {
        $playUrl = $json['data'];

        // write cache
        file_put_contents($cacheFile, json_encode([
            'timestamp' => time(),
            'playUrl'   => $playUrl
        ]));
    } else {
        header('HTTP/1.1 502 Bad Gateway');
        exit("Failed to get playUrl from auth API");
    }
}

// now fetch the actual HLS and stream it
$path = parse_url($playUrl, PHP_URL_PATH);
$last = basename($path);

$curl = curl_init($playUrl);
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 0,
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_HTTPHEADER     => [
        'Referer: media-proxy.yy.com',
        'User-Agent: AppleCoreMedia/1.0.0.22F76 (iPad; U; CPU OS 18_5 like Mac OS X; zh_cn)',
        'Accept: */*',
        'Host: media-proxy.yy.com',
        'Connection: keep-alive'
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

header("Content-Type: application/vnd.apple.mpegurl");
header('Content-Disposition: attachment; filename="' . $last . '"');
echo $response;
