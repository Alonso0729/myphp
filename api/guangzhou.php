<?php
// ===== 映射表：id => stationNumber =====
$stationMap = [
    "zhonghe" => 31, // 广州综合
    "xinwen"  => 32, // 广州新闻
    "jingsai" => 35, // 广州竞赛
    "yingshi" => 36, // 广州影视
    "fazhi"   => 34, // 广州法治
    "shenghuo"=> 33  // 广州南国都市
];

// 获取传入的 id 参数
$id = $_GET['id'] ?? '';
if (!isset($stationMap[$id])) {
    http_response_code(400);
    exit("Invalid id");
}

// 获取目标 stationNumber
$stationNumber = $stationMap[$id];

// 请求接口
$url = "https://gzbn.gztv.com:7443/plus-cloud-manage-app/liveChannel/queryLiveChannelList?type=1";
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false
]);
$response = curl_exec($ch);
curl_close($ch);

// 解析 JSON
$data = json_decode($response, true);
if (empty($data['data'])) {
    http_response_code(500);
    exit("No data");
}

// 查找并输出 httpBackUrl
foreach ($data['data'] as $item) {
    if (($item['stationNumber'] ?? null) == $stationNumber) {
        exit($item['httpBackUrl'] ?? '');
    }
}

http_response_code(404);
exit("Not found");
