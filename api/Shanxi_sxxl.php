<?php
error_reporting(0);
$n = [
"sxws" => "q8RVWgs",//ɽ������
"sxjj" => "4j01KWX",//ɽ������
"sxys" => "Md571Kv",//ɽ��Ӱ��
"sxshfz" => "p4y5do9",//ɽ������뷨��
"sxwtsh" => "Y00Xezi",//ɽ����������
"sxhh" => "lce1mC4",//ɽ���ƺ�
'ty1' => 'customa', //̫ԭ�����ۺ�
'dt1' => 'customb', //��ͬ�����ۺ�
'yq1' => 'customc', //��Ȫ�����ۺ�
'cz1' => 'customd', //���������ۺ�
'jc1' => 'custome', //���������ۺ�
'sz1' => 'customf', //˷�������ۺ�
'jz1' => 'customg', //�����ۺ�
'yc1' => 'customh', //�˳������ۺ�
'xz1' => 'customi', //�����ۺ�
'lf1' => 'customj', //�ٷ������ۺ�
'll1' => 'customk', //���������ۺ�
];
$id = isset($_GET['id'])?$_GET['id']:'sxws';
$url = "https://dyhhplus.sxrtv.com/apiv4.5/api/m3u8_notoken?channelid=".$n[$id];
$playurl = json_decode(file_get_contents($url),1)['data']['address'];
header("location:".$playurl);
//echo $playurl;
?>