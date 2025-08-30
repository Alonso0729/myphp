<?php
$id = isset($_GET['id'])?$_GET['id']:'fhzx';
$n = [
  'fhzx' => '011dac7f-d5cf-4c71-92c5-f77e465a1e4f',
  'fhzw' => '62ba669b-9fe0-4d03-bf86-cc79f3f6948b',
  'fhhk' => 'aa85d05d-7fe0-4452-a503-aa43d14873a4',
];
$ch = curl_init('http://m.fengshows.com/api/v3/live?live_type=tv');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$res = curl_exec($ch);
curl_close($ch);
$d = json_decode($res);
for($i=0;$i<4;$i++){
  if($n[$id] == $d[$i] -> _id) { $burl = $d[$i] -> live_url_fhd;};
}
$n = preg_split('/.flv/',basename($burl))[0];
$hexTime = dechex(time());
$hash = md5("obb9Lxyv5C/live/".$n.$hexTime);
//$playurl = 'http://tlive.fengshows.cn/live/'.$n.'.m3u8?txSecret='.$hash.'&txTime='.$hexTime;
//$playurl = 'http://dispatch.fengshows.cn:8383/live/'.$n.'.m3u8?txSecret='.$hash.'&txTime='.$hexTime;
$playurl = 'https://tlive.fengshows.com/live/'.$n.'.m3u8?txSecret='.$hash.'&txTime='.$hexTime;
header('Location:'.$playurl);
//echo $playurl;
?>