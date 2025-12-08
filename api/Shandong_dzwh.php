<?php
$id = $_GET['id'];
$n = [
    //德州
'dzxwzh' => ['dztv', 'dHZsLTE3OS0x'], //德州新闻综合
'dzjjsh' => ['dztv', 'dHZsLTE3OS0y'], //德州经济生活
'dztw' => ['dztv', 'dHZsLTE3OS05'], //德州图文
    //威海
'whxwzh' => ['weihai', 'dHZsLTE1Ny0x'], //威海新闻综合
'whdssh' => ['weihai', 'dHZsLTE1Ny0z'], //威海都市生活
'whhy' => ['weihai', 'dHZsLTE1Ny0xMg'], //威海海洋
'whhczh' => ['whhccm', 'dHZsLTIxMy01'], //威海环翠综合

];
// API URL
$apiUrl = "https://iapp.{$n[$id][0]}.tv/share/{$n[$id][1]}.html";
$htmlContent = file_get_contents($apiUrl);
$pattern = '/<source\s+src="([^"]+\.m3u8[^"]*)"[^>]*>/i';
preg_match($pattern, $htmlContent, $matches);
$playUrl = $matches[1];
header('Location: ' . $playUrl);