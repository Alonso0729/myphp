<?php
$id=isset($_GET['id'])?$_GET['id']:'tyxwzh';
$n = [
"tyxwzh" => "49VAfrw",//̫ԭ�����ۺ�
"tyjjsh" => "u8BmT6h",//̫ԭ��������
"tysjfz" => "phsry3e",//̫ԭ��̷���
"tyys" => "J4EX72D",//̫ԭӰ��
"tywt" => "rk8Z088",//̫ԭ����
"tyblg" => "iancgyD",//̫ԭ���ֹ�
"tycssh" => "i88rmGU",//̫ԭ����
"tyjy" => "g4XtSCF",//̫ԭ����
];
$t = time();
$token = md5($t.$n[$id].'cutvLiveStream|Dream2017');
$bstrURL = "http://hls-api.sxtygdy.com/getCutvHlsLiveKey?t=".$t."&token=".$token."&id=".$n[$id];
$p = file_get_contents($bstrURL);
$m3u8 = 'http://tytv-hls.sxtygdy.com/'.$n[$id].'/500/'.$p.'.m3u8';
header('Location:'.$m3u8);
//echo $m3u8;
?>