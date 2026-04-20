<?php
$channelId = isset($_GET['id']) ? $_GET['id'] : '306';

$apiUrl = "https://wyedit.ahwanyun.cn/api/system/cmsLiveStream/list?appId=123&liveReleaseStatus=2&source=2&clientType=ios&clientVersion=1.0.2&liveType=4";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36'
]);

$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    echo json_encode([]);
    exit;
}

try {
    $data = json_decode($response, true);
    $channels = isset($data['rows']) ? $data['rows'] : [];
    
    $targetChannel = null;
    foreach ($channels as $channel) {
        if ($channel['id'] == $channelId) {
            $targetChannel = $channel;
            break;
        }
    }
    
    if (!$targetChannel || !isset($targetChannel['liveM3u8Url'])) {
        echo json_encode([]);
        exit;
    }
    
    header('Location: ' . $targetChannel['liveM3u8Url']);
    
} catch (Exception $e) {
    echo json_encode([]);
}
?>