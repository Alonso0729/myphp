<?php 
$id=$_GET[id];
$ids = array(
"zhpd"=>"5c7f7072e4b01c17db18fbd5",//综合频道
"xwpd"=>"5c7f6f73e4b01c17db18fbd3",//新闻频道
"fzpd"=>"5c7f7097e4b01c17db18fbd7",//法制频道
"jspd"=>"5c7f70b7e4b01c17db18fbd9",//竞赛频道
"yspd"=>"5c7f70dce4b01c17db18fbdb",//影视频道
"ngds"=>"5c7f70fee4b01c17db18fbdd",//南国都市4k
);
$url = 'https://channel.gztv.com/channelf/viewapi/player/channelVideo?id='.$ids[$id].'&commentFrontUrl=https://comment.gztv.com/commentf'; 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$output = curl_exec($ch);
$str = htmlspecialchars($output);
curl_close($ch);
preg_match("/var standardUrl='(.*?)';
		    var secondId/i",$str,$u);
$u = explode('//', $u[1]);
$play='http://'.$u[1];
$url1 = htmlspecialchars_decode($play);
header('Location:'.$url1);
?>