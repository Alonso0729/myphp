<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'sccdtv1';
$n = [
'sccdtv1' =>[563,'cdtv1','CDTV1'], //CDTV-1 新闻综合频道
'sccdtv2' =>[562,'cdtv2','CDTV2'], //CDTV-2 经济资讯频道
'sccdtv3' =>[561,'cdtv3','CDTV3'], //CCDTV-3 都市生活频道
'sccdtv4' =>[560,'cdtv4','CDTV4'], //CDTV-4 影视文艺频道
'sccdtv5' =>[559,'cdtv5','CDTV5'], //CDTV-5 公共频道
'sccdtv6' =>[558,'cdtv6','CDTV6'], //CDTV-6 少儿频道
'sccddj' =>[592,'dangjiao','dangjiao'], //蓉城先锋
'sccdtv8' =>[595,'cdtv8','CDTV8'], //每日购物
];
$m = [
'sccdgb1' =>[570,'fm998','FM998'], //FM99.8 新闻频率
'sccdgb2' =>[569,'fm914','FM914'], //FM91.4 交通频率
'sccdgb3' =>[568,'fm1056','fm1056'], //FM105.6 经济频率
'sccdgb4' =>[567,'fm882','fm882'], //FM88.2 成都故事广播
'sccdgb5' =>[566,'fm946','fm946'], //FM94.6 文化休闲频率
];
$l = [
'sccdgb6' =>[565,'dujiangyanfm','dujiangyanfm'], //FM93.5 都江堰人民广播电台
'sccdpj1' =>[571,'pujiang','4'], //蒲江电视台
'sccddy1' =>[572,'dayi','4'], //大邑广播电视台
'sccdjt1' =>[573,'jintang','4'], //金堂电视台
'sccdql1' =>[575,'qionglai','4'], //邛崃电视台综合频道
'sccdpz1' =>[576,'pengzhou','4'], //彭州市广播电视台
'sccddjy1' =>[577,'dangjiao','4'], //都江堰电视台
'sccdjy1' =>[578,'jianyang','4'], //简阳市广播电视台
'sccdpd1' =>[580,'pidu','4'], //郫都区广播电视台
'sccdsl1' =>[581,'shuangliu','4'], //双流区广播电视台
'sccdwj1' =>[582,'wenjiang','4'], //温江电视台
'sccdxd1' =>[583,'xindu','4'], //新都区广播电视台
'sccdqbj1' =>[584,'qingbaijiang','4'], //青白江融媒体中心
'sccdch1' =>[586,'chenghua','4'], //成华有线电视
'sccdwh1' =>[587,'wuhou','4'], //武侯电视
'sccdjn1' =>[588,'jntv','4'], //金牛区有线电视台
'sccdqy1' =>[589,'qingyang','4'], //青羊区融媒体中心
'sccdjj1' =>[590,'jinjiang','4'], //锦江电视
'sccdgx1' =>[591,'gaoxin','gaoxin'], //高新电视台
];
if(!!$n[$id]){
$url='https://cstvweb.cdmp.candocloud.cn/live/getLiveUrl?url=https://cdn1.cditv.cn/'.$n[$id][1].'high/'.$n[$id][2].'High.flv/playlist.m3u8';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_REFERER,'https://www.cditv.cn/show/4845-'.$n[$id][0].'.html');
$playurl= curl_exec($ch);   
curl_close($ch);
header('Location:'.json_decode($playurl)->data->url);
}
if(!!$m[$id]){
$url='https://cstvweb.cdmp.candocloud.cn/live/getLiveUrl?url=https://cdn1.cditv.cn/'.$m[$id][1].'/'.$m[$id][2].'.flv/playlist.m3u8';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_REFERER,'https://www.cditv.cn/show/4843-'.$m[$id][0].'.html');
$playurl= curl_exec($ch);   
curl_close($ch);
header('Location:'.json_decode($playurl)->data->url);
}
if(!!$l[$id]){
$url='https://cstvweb.cdmp.candocloud.cn/live/getLiveUrl?url=https://quxian.pull.cditv.cn/live/'.$l[$id][2].'.flv/playlist.m3u8';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_REFERER,'https://www.cditv.cn/show/4844-'.$l[$id][0].'.html');
$playurl= curl_exec($ch);   
curl_close($ch);
header('Location:'.json_decode($playurl)->data->url);
}
?>