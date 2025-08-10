<?php
error_reporting(0);
$liveid = isset($_GET['id'])?$_GET['id']:'ahtv';
$tv = [
    //安徽卫视https://console.ahsx.ahtv.cn/web/cms/rmt0087_html/1/1st/dszb/18758.shtml
    'ahtv' => '安徽卫视',
    'jjshtv' => '安徽经济生活',
    'ggtv' => '安徽公共',
    'nykjtv' => '安徽农业科教',
    'ystv' => '安徽影视',
    'zytytv' => '安徽综艺体育',
    'gjtv' => '安徽国际',
];
function linkToMd5($liveId) {
    $currentTime = dechex(time() + 15552000);
    $url = "https://live.ahsx.ahtv.cn/live/" . $liveId . '.m3u8';
    $key = 'Mol7sFvPPAs';
    $url = $url . '?txSecret=' . md5($key . $liveId . $currentTime) . "&txTime=" . $currentTime;
    return $url;
}
if(!empty($tv[$liveid])) {
    $playurl = linkToMd5($liveid);
    header("Location: $playurl");
}
?>