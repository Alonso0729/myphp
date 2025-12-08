<?php
$apiUrl = 'https://www.jnjyapp.cn/index.php?m=api&c=live&a=live_url';
$headers = [
    'User-Agent: Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Safari/537.36',
    'Referer: https://www.jnjyapp.cn/live/live_tv.html'
];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    curl_close($ch);
    exit;
}
curl_close($ch);
$data = json_decode($response, true);
if (isset($data['code']) && $data['code'] == 200 && isset($data['data'])) {
    $videoUrl = $data['data'];
    header('Location: ' . $videoUrl);
    exit;
} else {
    echo '请求失败: ' . htmlspecialchars($response);
}
?>