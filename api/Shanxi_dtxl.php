<?php
$id=isset($_GET['id'])?$_GET['id']:'xwzh';
$n = [
'xwzh' => 16,//��ͬ�����ۺ�
'ggpd' => 8,//��ͬ����
'mdsh' => 9,//��ͬú������
];
$m3u8 = json_decode(file_get_contents('http://mapi.dtradio.com.cn/api/v1/channel.php?channel_id='.$n[$id]))[0]->m3u8;
header('location:'.$m3u8);
?>