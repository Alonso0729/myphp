<?php
$e = main(
    $_GET['g'],
    $_GET['t'],
    $_GET['c'],
    isset($_GET['playseek']) ? $_GET['playseek'] : null,
    $l
);
if ($e == '')
    locate($l);
else
    echo $e;



function main($group, $type, $code, $playseek, &$play_url)
{
    $response_msg = '';
    for ($i = 1; $i <= 1; $i++) {
        foreach (hosts_get() as $host) {
            list($u, $p) = intf_get($host, $group, $type, $code);
            $c = @file_get_contents($u, false, stream_context_create(array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ),
                'http' => array(
                    'method' => 'POST',
//                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => $p,
                )
            )));
            if ($c === false)
                continue;
            $play_url = url_get($playseek, $type, $c, $response_code, $response_msg);
            switch ($response_code) {
                case 0:
                    return '';
                case -4014://防盗链服务错误
                    break;
                case -4020://直播频道配置异常
                    break 3;
                default:
                    break 3;
            }
        }
    }
    return $response_msg;
}

function intf_get($host, $group, $type, $code)
{
    $f = 'https://%s/ps/OttService/Auth';
    $u = sprintf($f, $host);
    $f = 'UserID=%s&UserToken=%s&TVID=$$%s&UserGroup=$TerOut_%d&ItemType=%d&ItemCode=%s';
    $p = sprintf($f, user_get_id(), user_get_token(), tvid_get(), $group, $type, $code);
    return [$u, $p];
}

//function type_get()
//{
//    return mt_rand(2, 4);
//}

function tvid_get()
{
    return random_string(4, 16);
}

function user_get_token()
{
    return random_string(1, 16);
}

function user_get_id()
{
//    return '023909999999999';
    return random_string(1, 16);
}

function random_string($min, $max)
{
    $r = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0,
        mt_rand($min, $max));
    return $r;
}

function hosts_get()
{
    return [
        'fjdxzpps.bestv.com.cn',

//        'ltzxps.bbtv.cn',

//        '139.224.116.50',
//        'dangbeisdkps.bestv.com.cn',
//        'dangbeisdkaaa.bestv.com.cn',
//        'b2cv3replay.bestv.com.cn',
//        'b2cv3wxmini.bestv.com.cn',
//        'b2cv3epg.bestv.com.cn',
//        'b2cv3aaa.bestv.com.cn',
//        'b2cv3ps.bestv.com.cn',
//        'b2cv3up.bestv.com.cn',
//        'b2cv3wag.bestv.com.cn',
//        '2cv3qhaaa.bestv.com.cn',
    ];
}

function url_get($playseek, $type, $content, &$response_code, &$response_msg)
{
    $j = json_decode($content);
    $response_code = $j->Response->Header->RC;
    $response_msg = $j->Response->Header->RM;
    if ($response_code != 0)
        return '';
    if ($playseek !== null) {
        $r = $j->Response->Body->LookBackUrl;
        if ($r == '')
            $r = $j->Response->Body->PlayURL;
    } else {
        $r = $j->Response->Body->PlayURL;
    }
//    if ($type > 1) {
    if (true) {
        $a = parse_url($r);
        $h = $a["host"];
        $p = substr($a["path"], 1);
        $q = $a["query"];
        parse_str($q, $a);
        $auth = $a["AuthInfo"];
        $r = strtok($r, '?');
        $a = explode("/", $r);
        $a[2] = get_play_ip() . ':8114/' . $a[2];
        $r = implode("/", $a);
        $r = sprintf('%s?AuthInfo=%s&FvSeid=a&Provider_id=%s&Pcontent_id=%s',
            $r, $auth, $h, $p);

//        $q = parse_url($r, PHP_URL_QUERY);
//        parse_str($q, $a);
//        $r = sprintf('%s?se=%s&ct=%s', strtok($r, '?'), $a['se'], $a['ct']);

//        $r = sprintf('%s', strtok($r, '?'));
    } else {
//        $r = sprintf('%s?_BitRate=6000', strtok($r, '?'));
    }
    if ($type <= 1) {
        $r .= '&_BitRate=6000';
    }
    if ($playseek !== null) {
        $z = new DateTimeZone('Asia/Shanghai');
        $s = strtok($playseek, '-');
        $starttime = DateTime::createFromFormat('YmdHis', $s, $z)->getTimestamp();
        $e = strtok('-');
        $endtime = DateTime::createFromFormat('YmdHis', $e, $z)->getTimestamp();
        $p = "starttime=$starttime&endtime=$endtime";
        $c = strpos($r, '?') === false ? '?' : '&';
        $r .= $c . $p;
    }
    return $r;
}

function get_play_ip()
{
    return '110.89.160.' . mt_rand(69, 84);
}

function locate($url)
{
    header('Access-Control-Allow-Origin: *');
    header('Location: ' . $url);
}

/*
央视,#genre#
CCTV-1,bst.php?g=1&t=3&c=Umai:CHAN/111128@BESTV.SMG.SMG
CCTV-2,bst.php?g=1&t=3&c=Umai:CHAN/5000036@BESTV.SMG.SMG
CCTV-3,bst.php?g=1&t=3&c=Umai:CHAN/1369028@BESTV.SMG.SMG
CCTV-4,bst.php?g=1&t=3&c=Umai:CHAN/5000037@BESTV.SMG.SMG
CCTV-5,bst.php?g=1&t=3&c=Umai:CHAN/1369029@BESTV.SMG.SMG
CCTV-5+,bst.php?g=1&t=3&c=Umai:CHAN/6000068@BESTV.SMG.SMG
CCTV-6,bst.php?g=1&t=3&c=Umai:CHAN/1369030@BESTV.SMG.SMG
CCTV-7,bst.php?g=1&t=3&c=Umai:CHAN/5000038@BESTV.SMG.SMG
CCTV-8,bst.php?g=1&t=3&c=Umai:CHAN/1369033@BESTV.SMG.SMG
CCTV-9,bst.php?g=1&t=3&c=Umai:CHAN/3949783@BESTV.SMG.SMG
CCTV-10,bst.php?g=1&t=3&c=Umai:CHAN/3949784@BESTV.SMG.SMG
CCTV-11,bst.php?g=1&t=3&c=Umai:CHAN/6000053@BESTV.SMG.SMG
CCTV-12,bst.php?g=1&t=3&c=Umai:CHAN/5000040@BESTV.SMG.SMG
CCTV-13,bst.php?g=1&t=3&c=Umai:CHAN/1358@BESTV.SMG.SMG
CCTV-14,bst.php?g=1&t=3&c=Umai:CHAN/3949788@BESTV.SMG.SMG
CCTV-15,bst.php?g=1&t=3&c=Umai:CHAN/3874@BESTV.SMG.SMG
CCTV-16,bst.php?g=1&t=3&c=Umai:CHAN/6000061@BESTV.SMG.SMG
CCTV-17,bst.php?g=1&t=3&c=Umai:CHAN/5000041@BESTV.SMG.SMG
CETV-1,bst.php?g=1&t=3&c=Umai:CHAN/3138605@BESTV.SMG.SMG
CETV-2,bst.php?g=1&t=3&c=Umai:CHAN/79471@BESTV.SMG.SMG
CETV-4,bst.php?g=1&t=3&c=Umai:CHAN/6876519@BESTV.SMG.SMG
CGTN,bst.php?g=1&t=3&c=Umai:CHAN/1354@BESTV.SMG.SMG

卫视,#genre#
安徽卫视,bst.php?g=1&t=3&c=Umai:CHAN/3540416@BESTV.SMG.SMG
北京卫视,bst.php?g=1&t=3&c=Umai:CHAN/181361@BESTV.SMG.SMG
重庆卫视,bst.php?g=1&t=3&c=Umai:CHAN/3840707@BESTV.SMG.SMG
东南卫视,bst.php?g=1&t=3&c=Umai:CHAN/3540417@BESTV.SMG.SMG
甘肃卫视,bst.php?g=1&t=3&c=Umai:CHAN/6000067@BESTV.SMG.SMG
广东卫视,bst.php?g=1&t=3&c=Umai:CHAN/181359@BESTV.SMG.SMG
广西卫视,bst.php?g=1&t=3&c=Umai:CHAN/5000045@BESTV.SMG.SMG
贵州卫视,bst.php?g=1&t=3&c=Umai:CHAN/4252663@BESTV.SMG.SMG
海南卫视,bst.php?g=1&t=3&c=Umai:CHAN/4252684@BESTV.SMG.SMG
河北卫视,bst.php?g=1&t=3&c=Umai:CHAN/100000002@BESTV.SMG.SMG
河南卫视,bst.php?g=1&t=3&c=Umai:CHAN/5000044@BESTV.SMG.SMG
黑龙江卫视,bst.php?g=1&t=3&c=Umai:CHAN/181356@BESTV.SMG.SMG
湖北卫视,bst.php?g=1&t=3&c=Umai:CHAN/911989@BESTV.SMG.SMG
湖南卫视,bst.php?g=1&t=3&c=Umai:CHAN/181358@BESTV.SMG.SMG
吉林卫视,bst.php?g=1&t=3&c=Umai:CHAN/5000046@BESTV.SMG.SMG
江苏卫视,bst.php?g=1&t=3&c=Umai:CHAN/111129@BESTV.SMG.SMG
江西卫视,bst.php?g=1&t=3&c=Umai:CHAN/3468921@BESTV.SMG.SMG
辽宁卫视,bst.php?g=1&t=3&c=Umai:CHAN/3450001@BESTV.SMG.SMG
宁夏卫视,bst.php?g=1&t=3&c=Umai:CHAN/160782@BESTV.SMG.SMG
青海卫视,bst.php?g=1&t=3&c=Umai:CHAN/67026@BESTV.SMG.SMG
山东卫视,bst.php?g=1&t=3&c=Umai:CHAN/911992@BESTV.SMG.SMG
山西卫视,bst.php?g=1&t=3&c=Umai:CHAN/1344@BESTV.SMG.SMG
深圳卫视,bst.php?g=1&t=3&c=Umai:CHAN/181362@BESTV.SMG.SMG
四川卫视,bst.php?g=1&t=3&c=Umai:CHAN/3840706@BESTV.SMG.SMG
天津卫视,bst.php?g=1&t=3&c=Umai:CHAN/3450000@BESTV.SMG.SMG
西藏卫视,bst.php?g=1&t=3&c=Umai:CHAN/77574@BESTV.SMG.SMG
云南卫视,bst.php?g=1&t=3&c=Umai:CHAN/5000043@BESTV.SMG.SMG
浙江卫视,bst.php?g=1&t=3&c=Umai:CHAN/111130@BESTV.SMG.SMG

上海,#genre#
东方卫视,bst.php?g=1&t=3&c=Umai:CHAN/111131@BESTV.SMG.SMG
哈哈炫动,bst.php?g=1&t=3&c=Umai:CHAN/4641019@BESTV.SMG.SMG
乐游,bst.php?g=1&t=3&c=Umai:CHAN/1368437@BESTV.SMG.SMG
金色学堂,bst.php?g=1&t=3&c=Umai:CHAN/4492970@BESTV.SMG.SMG
法治天地,bst.php?g=1&t=3&c=Umai:CHAN/4492967@BESTV.SMG.SMG
东方财经,bst.php?g=1&t=3&c=Umai:CHAN/6880079@BESTV.SMG.SMG
欢笑剧场,bst.php?g=1&t=3&c=Umai:CHAN/1367710@BESTV.SMG.SMG
都市剧场,bst.php?g=1&t=3&c=Umai:CHAN/1555881@BESTV.SMG.SMG
动漫秀场,bst.php?g=1&t=3&c=Umai:CHAN/1555886@BESTV.SMG.SMG
生活时尚,bst.php?g=1&t=3&c=Umai:CHAN/1555893@BESTV.SMG.SMG
游戏风云,bst.php?g=1&t=3&c=Umai:CHAN/1555894@BESTV.SMG.SMG

娱乐,#genre#
中国天气,bst.php?g=1&t=3&c=Umai:CHAN/63734@BESTV.SMG.SMG

北京,#genre#
北京卡酷少儿,bst.php?g=1&t=3&c=Umai:CHAN/6000052@BESTV.SMG.SMG

广东,#genre#
广东嘉佳卡通,bst.php?g=1&t=3&c=Umai:CHAN/484998@BESTV.SMG.SMG

湖南,#genre#
湖南金鹰纪实,bst.php?g=1&t=3&c=Umai:CHAN/4766929@BESTV.SMG.SMG
湖南快乐垂钓,bst.php?g=1&t=3&c=Umai:CHAN/4766930@BESTV.SMG.SMG
湖南茶频道,bst.php?g=1&t=3&c=Umai:CHAN/6000049@BESTV.SMG.SMG

辽宁,#genre#
家庭理财,bst.php?g=1&t=3&c=Umai:CHAN/1872767@BESTV.SMG.SMG

江苏,#genre#
江苏财富天下,bst.php?g=1&t=3&c=Umai:CHAN/5000047@BESTV.SMG.SMG

点播,#genre#
京剧《白蛇传》,bst.php?g=1&t=1&c=14016258
*/