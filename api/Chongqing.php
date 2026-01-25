<?php
function main($item) {
    $id = $item['id'] ?? '';
    $c = $item['c'] ?? '';
    $a = $item['a'] ?? '';
    $v = $item['v'] ?? '';
    
    if (isset($item['url']) && !$id) {
        $queryString = parse_url($item['url'], PHP_URL_QUERY);
        if ($queryString) {
            parse_str($queryString, $params);
            $id = $params['id'] ?? '';
            $c = $params['c'] ?? '';
            $a = $params['a'] ?? '';
            $v = $params['v'] ?? '';
        }
    }
    
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $base = $protocol . '://' . $host . $script;
    
    if ($a && $v) {
        $m3u8Content = implode("\r\n", [
            '#EXTM3U',
            '#EXT-X-VERSION:7',
            '#EXT-X-INDEPENDENT-SEGMENTS',
            '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="audio",NAME="audio",URI="' . $base . '?id=' . urlencode($id) . '&c=' . urlencode($c) . '&a=' . urlencode($a) . '"',
            '#EXT-X-STREAM-INF:BANDWIDTH=4000000,AUDIO="audio"',
            $base . '?id=' . urlencode($id) . '&c=' . urlencode($c) . '&v=' . urlencode($v)
        ]);
        
        return ['m3u8' => $m3u8Content, 'player' => 3];
    } else {
        $basePath = 'http://tencent.live.cbncdn.cn/__cl/cg:live/__c/' . urlencode($id) . '/__op/default/__f/' . urlencode($c) . '/';
        $timeStamp = floor(time() / 10) - 4;
        $tsCount = 3;
        $tsParam = $a ?: $v ?: '';
        
        $tsList = [];
        for ($i = 0; $i < $tsCount; $i++) {
            $tsList[] = '#EXTINF:10,' . "\r\n" . $basePath . $tsParam . '/' . ($timeStamp + $i) . '.ts';
        }
        $tsListStr = implode("\r\n", $tsList);
        
        $m3u8Content = implode("\r\n", [
            '#EXTM3U',
            '#EXT-X-VERSION:3',
            '#EXT-X-TARGETDURATION:11',
            '#EXT-X-MEDIA-SEQUENCE:' . $timeStamp,
            $tsListStr,
            ''
        ]);
        
        return $m3u8Content;
    }
}

if (php_sapi_name() === 'cli') {
    echo "This script is designed for web access.\n";
} else {
    $params = [
        'id' => $_GET['id'] ?? '',
        'c' => $_GET['c'] ?? '',
        'a' => $_GET['a'] ?? '',
        'v' => $_GET['v'] ?? '',
    ];
    
    if (empty($params['id']) && isset($_GET['url'])) {
        $params['url'] = $_GET['url'];
    }
    
    $result = main($params);
    
    header('Content-Type: application/vnd.apple.mpegurl');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    
    if (is_array($result) && isset($result['m3u8'])) {
        echo $result['m3u8'];
    } else {
        echo $result;
    }
}
