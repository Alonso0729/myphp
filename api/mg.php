<?php
error_reporting(0);
$n = [
    'cctv1' => '608807420', //CCTV1综合
    'cctv2' => '631780532', //CCTV2财经
    'cctv3' => '624878271', //CCTV3综艺
    'cctv4' => '631780421', //CCTV4中文国际
    'cctv4a' => '608807416', //CCTV4美洲
    'cctv4o' => '608807419', //CCTV4欧洲
    'cctv5' => '641886683', //CCTV5体育
    'cctv5p' => '641886773', //CCTV5+体育赛事
    'cctv6' => '624878396', //CCTV6电影
    'cctv7' => '673168121', //CCTV7国防军事
    'cctv8' => '624878356', //CCTV8电视剧
    'cctv9' => '673168140', //CCTV9纪录
    'cctv10' => '624878405', //CCTV10科教
    'cctv11' => '667987558', //CCTV11戏曲
    'cctv12' => '673168185', //CCTV12社会与法
    'cctv13' => '608807423', //CCTV13新闻
    'cctv14' => '624878440', //CCTV14少儿
    'cctv15' => '673168223', //CCTV15音乐
    'cctv17' => '673168256', //CCTV17农业农村
    'dfws' => '651632648', //东方卫视
    'jsws' => '623899368', //江苏卫视
    'gdws' => '608831231', //广东卫视
    'jxws' => '783847495', //江西卫视
    'hnws' => '790187291', //河南卫视
    'sxws' => '738910838', //陕西卫视
    'dwqws' => '608917627', //大湾区卫视
    'hubws' => '947472496', //湖北卫视
    'jlws' => '947472500', //吉林卫视
    'qhws' => '947472506', //青海卫视
    'dnws' => '849116810', //东南卫视
    'hinws' => '947472502', //海南卫视
    'hixws' => '849119120', //海峡卫视
    'ymkt' => '626064703', //优漫卡通
    'jjkt' => '614952364', //嘉佳卡通
];
$id = isset($_GET['id']) ? $_GET['id'] : 'cctv1';
$bstrURL = "https://webapi.miguvideo.com/gateway/playurl/v3/play/playurl?contId={$n[$id]}&channelId=0132_10010001005";

$live = json_decode(get_data($bstrURL), 1)['body']['urlInfo']['url'];
$uas = parse_url($live);
parse_str($uas["query"], $arr);
$puData = str_split($arr['puData']);
$ProgramID = str_split($n[$id]);
$Program = str_split('yzwxcdwbgh');
$s = count($puData);
$arr_key = [];
for ($v = 0; $v < $s / 2; $v++) {
    $arr_key[] = $puData[$s - $v - 1];
    $arr_key[] = $puData[$v];
    switch ($v) {
        case 1:
        case 2:
        case 4:
            $arr_key[] = arrkey($v);
            break;
        case 3:
            $arr_key[] = $Program[$ProgramID[1]];
            break;
        }
    }
$ddCalcu = join($arr_key);

$p = $live . "&ddCalcu=" . $ddCalcu . '&sv=10000&crossdomain=www&ct=h5';

$live = get_data($p, $headers);
//$host = parse_url($live)['host'];
//$playurl = preg_replace("|{$host}:443|","hlstxmgsplive.miguvideo.com",$live);

header('Location: ' . $live);
//echo $$live;
exit;

function arrkey($v) {
    $put = ['z', 'y', '0', 'z'];
    return $put[$v - 1];
    }
function get_data($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
    }
?>