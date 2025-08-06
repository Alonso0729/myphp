<?php
$auth_key = json_decode(file_get_contents("https://api-cms.lsxrmtzx.com/v1/mobile/channel/play_auth?stream=https:%2F%2Flive-stream.lsxrmtzx.com%2Flive%2Fvideo.m3u8"),1)['data']['auth_key'];
$m3u8 = "https://live-stream.lsxrmtzx.com/live/video.m3u8?auth_key=".$auth_key;
header("location:".$m3u8);
//echo $m3u8;
?>