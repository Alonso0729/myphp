<?php
error_reporting(0);
$n = [
'yqxwzh' => 10,//��Ȫ�����ۺ�
'yqkj' => 11,//��Ȫ�ƽ�
];
$id = isset($_GET['id'])?$_GET['id']:'yqxwzh';
$m3u8 = json_decode(file_get_contents("https://mapi.yqrtv.com/api/v1/channel.php?&channel_id=".$n[$id]),1)[0]['m3u8'];
header("location:".$m3u8);
//echo $m3u8;
?>