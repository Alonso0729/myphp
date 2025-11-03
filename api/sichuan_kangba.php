<?php
$m3u8 = json_decode(file_get_contents('https://mapi.kangbatv.com/api/v1/channel_detail.php?channel_id=17'),1)[0]['m3u8'];
header("location:".$m3u8);
//echo $m3u8;
?>