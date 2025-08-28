<?php
date_default_timezone_set("Asia/Shanghai");

$type    = empty($_GET['type']) ? "nojson" : trim($_GET['type']);
$id      = empty($_GET['id']) ? "773420" : trim($_GET['id']); // 直播间ID（短号/长号都行）
$quality = empty($_GET['qn']) ? 10000 : intval($_GET['qn']);  // 清晰度，10000原画，400高清，250流畅

// 获取真实房间 ID
function get_real_room_id($id) {
    $api = "https://api.live.bilibili.com/room/v1/Room/room_init?id={$id}";
    $data = json_decode(file_get_contents($api), true);
    if ($data && $data['code'] === 0) {
        return $data['data']['room_id'];
    }
    return $id;
}

// 获取B站直播流列表（FLV）
function get_bili_streams($room_id, $qn = 10000, $platform = 'web') {
    $api = "https://api.live.bilibili.com/room/v1/Room/playUrl?cid={$room_id}&qn={$qn}&platform={$platform}&https_url_req=0&ptype=8&protocol=0,1&format=0,1&codec=0";
    $data = json_decode(file_get_contents($api), true);
    $urls = [];
    if ($data && $data['code'] === 0) {
        foreach ($data['data']['durl'] as $d) {
            $urls[] = $d['url'];
        }
    }
    return $urls;
}

// 测试延迟，选最快
function select_best_url($urls) {
    $best = null;
    $bestTime = PHP_INT_MAX;
    foreach ($urls as $url) {
        $start = microtime(true);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        @curl_exec($ch);
        $time = microtime(true) - $start;
        curl_close($ch);
        if ($time < $bestTime) {
            $bestTime = $time;
            $best = $url;
        }
    }
    return $best;
}

// 主流程
$real_room_id = get_real_room_id($id);
$stream_list  = get_bili_streams($real_room_id, $quality);

if (!empty($stream_list)) {
    $best_url = select_best_url($stream_list) ?: $stream_list[0];

    if ($type === "json") {
        echo json_encode([
            'room_id' => $real_room_id,
            'quality' => $quality,
            'best'    => $best_url,
            'all'     => $stream_list
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } else {
        header("Location: {$best_url}");
    }
} else {
    echo "无法获取直播流，可能是未开播或需要登录";
}