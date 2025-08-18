<?php
//https://apphhplushttps.sxrtv.com//television/live_wap_650.html
$id = $_GET['id'];
//$id = 'q8RVWgs';
$u = 'https://dyhhplus.sxrtv.com/apiv4.5/api/m3u8_notoken?channelid='.$id.'&site=53';
$c = file_get_contents($u);
$j = json_decode($c);
$p = $j->data->address;
header('Access-Control-Allow-Origin: *');
header('Location: '.$p);