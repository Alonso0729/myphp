<?php
$id=$_GET[id];
$ids = array(
"sxws" => "Shan1XiTV&live1",//ɽ������
"hh" => "HuangHeNews&live3",//�ƺӵ���̨
"jjkj" => "Shan1XiFinance&live3",//������Ƽ�Ƶ��
"sxys" => "Shan1XiFilm&live2",//ɽ��Ӱ��Ƶ��
"shfz" => "Shan1XiEdu&live3",//����뷨��Ƶ��
);
$data = htmlspecialchars(file_get_contents("http://sns1.sxrtv.com/player/player.php?channel=".$ids[$id]));
preg_match("|url: '(.*?)',|i",$data,$playurl);
$pam = explode("&",$ids[$id]);
$playurl = str_replace('Shan1XiTV',$pam[0],$playurl[1]);
$playurl = str_replace('live1',$pam[1],$playurl);
$playurl = str_replace('512000','800000',$playurl);
header('Location:'.htmlspecialchars_decode($playurl));
?>