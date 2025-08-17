<?php
$id = $_GET['id'];
$u = 'https://www.lasatv.cn/cms/home/content?id='.$id;
$c = file_get_contents($u);
$j = json_decode($c);
$r = $j->exdata->lives[0]->m3u8_url;
$r = str_replace("https:", "http:", $r);
header('Access-Control-Allow-Origin: *');
header('Location: '.$r);



/* “拉萨综合频道”的播放成功概率高，其他不行？
拉萨藏语综合频道,id=1989993
拉萨综合频道,id=1021641
拉萨文化旅游频道,id=1021657
拉萨综合广播,id=1029029
*/