<?php
$e = main($_GET['g'], $_GET['t'], $_GET['c'], $l);
if ($e == '')
    locate($l);
else
    echo $e;



function main($group, $type, $code, &$play_url)
{
    $response_msg = '';
    for ($i = 1; $i <= 1; $i++) {
        foreach (hosts_get() as $host) {
            list($u, $p) = intf_get($host, $group, $type, $code);
            $c = file_get_contents($u.'?'.$p, false, stream_context_create(array(
//                'http' => array(
//                    'method' => 'POST',
////                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
//                    'content' => $p,
//                )
            )));
            if ($c === false)
                continue;
            $play_url = url_get($type, $c, $response_code, $response_msg);
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
    $f = 'http://%s/ps/OttService/Auth';
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
    return '023909999999999';
//    return random_string(1, 16);
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
        'ltzxps.bbtv.cn',

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

function url_get($type, $content, &$response_code, &$response_msg)
{
    $j = json_decode($content);
    $response_code = $j->Response->Header->RC;
    $response_msg = $j->Response->Header->RM;
    if ($response_code != 0)
        return '';
//    $r = $j->Response->Body->LookBackUrl;
//    if ($r == '')
        $r = $j->Response->Body->PlayURL;
    if ($type > 1) {
//        $q = parse_url($r, PHP_URL_QUERY);
//        parse_str($q, $a);
//        $r = sprintf('%s?se=%s&ct=%s', strtok($r, '?'), $a['se'], $a['ct']);
        $r = sprintf('%s', strtok($r, '?'));
    } else {
        $r = sprintf('%s?_BitRate=6000', strtok($r, '?'));
    }
    return $r;
}

function locate($url)
{
    header('Access-Control-Allow-Origin: *');
    header('Location: ' . $url);
}

/*
东方卫视,bst.php?g=1&t=2&c=Umai:CHAN/111131@BESTV.SMG.SMG
东方购物上星,bst.php?g=1&t=2&c=Umai:CHAN/6880080@BESTV.SMG.SMG
金色学堂,bst.php?g=1&t=2&c=Umai:CHAN/4492970@BESTV.SMG.SMG
法治天地,bst.php?g=1&t=2&c=Umai:CHAN/1373@BESTV.SMG.SMG
东方财经,bst.php?g=1&t=2&c=Umai:CHAN/6880079@BESTV.SMG.SMG
欢笑剧场,bst.php?g=1&t=2&c=Umai:CHAN/1367710@BESTV.SMG.SMG
都市剧场,bst.php?g=1&t=2&c=Umai:CHAN/1555881@BESTV.SMG.SMG
动漫秀场,bst.php?g=1&t=2&c=Umai:CHAN/1555886@BESTV.SMG.SMG
生活时尚,bst.php?g=1&t=2&c=Umai:CHAN/1555893@BESTV.SMG.SMG
游戏风云,bst.php?g=1&t=2&c=Umai:CHAN/1555894@BESTV.SMG.SMG

CCTV-1,bst.php?g=1&t=2&c=Umai:CHAN/111128@BESTV.SMG.SMG
CCTV-2,bst.php?g=1&t=2&c=Umai:CHAN/5000036@BESTV.SMG.SMG
CCTV-3,bst.php?g=1&t=2&c=Umai:CHAN/1369028@BESTV.SMG.SMG
CCTV-4,bst.php?g=1&t=2&c=Umai:CHAN/1349@BESTV.SMG.SMG
CCTV-5,bst.php?g=1&t=2&c=Umai:CHAN/1369029@BESTV.SMG.SMG
CCTV-5+,bst.php?g=1&t=2&c=Umai:CHAN/6000068@BESTV.SMG.SMG
CCTV-6,bst.php?g=1&t=2&c=Umai:CHAN/1369030@BESTV.SMG.SMG
CCTV-7,bst.php?g=1&t=2&c=Umai:CHAN/1352@BESTV.SMG.SMG
CCTV-8,bst.php?g=1&t=2&c=Umai:CHAN/1369033@BESTV.SMG.SMG
CCTV-9,bst.php?g=1&t=2&c=Umai:CHAN/5000039@BESTV.SMG.SMG
CCTV-10,bst.php?g=1&t=2&c=Umai:CHAN/3949784@BESTV.SMG.SMG
CCTV-11,bst.php?g=1&t=2&c=Umai:CHAN/6000053@BESTV.SMG.SMG
CCTV-12,bst.php?g=1&t=2&c=Umai:CHAN/5000040@BESTV.SMG.SMG
CCTV-13,bst.php?g=1&t=2&c=Umai:CHAN/6000054@BESTV.SMG.SMG
CCTV-14,bst.php?g=1&t=2&c=Umai:CHAN/3949788@BESTV.SMG.SMG
CCTV-15,bst.php?g=1&t=2&c=Umai:CHAN/6000055@BESTV.SMG.SMG
CCTV-16,bst.php?g=1&t=2&c=Umai:CHAN/6000061@BESTV.SMG.SMG
CCTV-17,bst.php?g=1&t=2&c=Umai:CHAN/5000041@BESTV.SMG.SMG
CGTN英语,bst.php?g=1&t=2&c=Umai:CHAN/1354@BESTV.SMG.SMG
CETV-1,bst.php?g=1&t=2&c=Umai:CHAN/3138605@BESTV.SMG.SMG
CETV-2,bst.php?g=1&t=2&c=Umai:CHAN/79471@BESTV.SMG.SMG
CETV-4,bst.php?g=1&t=2&c=Umai:CHAN/79472@BESTV.SMG.SMG

百视通直播-30,bst.php?g=1&t=2&c=Umai:CHAN/123222@BESTV.SMG.SMG
百视通直播-31,bst.php?g=1&t=2&c=Umai:CHAN/123223@BESTV.SMG.SMG
百视通直播-32,bst.php?g=1&t=2&c=Umai:CHAN/123224@BESTV.SMG.SMG
百视通直播-33,bst.php?g=1&t=2&c=Umai:CHAN/123225@BESTV.SMG.SMG
百视通直播-34,bst.php?g=1&t=2&c=Umai:CHAN/123226@BESTV.SMG.SMG
百视通直播-35,bst.php?g=1&t=2&c=Umai:CHAN/123227@BESTV.SMG.SMG
百视通直播-36,bst.php?g=1&t=2&c=Umai:CHAN/123228@BESTV.SMG.SMG
百视通直播-37,bst.php?g=1&t=2&c=Umai:CHAN/2197671@BESTV.SMG.SMG
百视通直播-38,bst.php?g=1&t=2&c=Umai:CHAN/2197672@BESTV.SMG.SMG
百视通直播-39,bst.php?g=1&t=2&c=Umai:CHAN/2197673@BESTV.SMG.SMG
百视通直播-40,bst.php?g=1&t=2&c=Umai:CHAN/2197674@BESTV.SMG.SMG

BesTV华语影院,bst.php?g=1&t=2&c=Umai:CHAN/3992540@BESTV.SMG.SMG
BesTV星光影院,bst.php?g=1&t=2&c=Umai:CHAN/3992541@BESTV.SMG.SMG
BesTV全球大片,bst.php?g=1&t=2&c=Umai:CHAN/3992543@BESTV.SMG.SMG
BesTV热门剧场,bst.php?g=1&t=2&c=Umai:CHAN/3992538@BESTV.SMG.SMG
BesTV谍战剧场,bst.php?g=1&t=2&c=Umai:CHAN/3992539@BESTV.SMG.SMG
BesTV青春动漫,bst.php?g=1&t=2&c=Umai:CHAN/3992536@BESTV.SMG.SMG
BesTV宝宝动画,bst.php?g=1&t=2&c=Umai:CHAN/3992535@BESTV.SMG.SMG
BesTV戏曲精选,bst.php?g=1&t=2&c=Umai:CHAN/3992530@BESTV.SMG.SMG
BesTV热门综艺,bst.php?g=1&t=2&c=Umai:CHAN/3992529@BESTV.SMG.SMG
BesTV健康养生,bst.php?g=1&t=2&c=Umai:CHAN/3992544@BESTV.SMG.SMG
BesTV百变课堂,bst.php?g=1&t=2&c=Umai:CHAN/3992537@BESTV.SMG.SMG
BesTV看天下精选,bst.php?g=1&t=2&c=Umai:CHAN/3992546@BESTV.SMG.SMG
BesTV电竞天堂,bst.php?g=1&t=2&c=Umai:CHAN/3992531@BESTV.SMG.SMG

安徽卫视,bst.php?g=1&t=2&c=Umai:CHAN/3540416@BESTV.SMG.SMG
北京卫视,bst.php?g=1&t=2&c=Umai:CHAN/181361@BESTV.SMG.SMG
兵团卫视,bst.php?g=1&t=2&c=Umai:CHAN/126389@BESTV.SMG.SMG
重庆卫视,bst.php?g=1&t=2&c=Umai:CHAN/3840707@BESTV.SMG.SMG
东南卫视,bst.php?g=1&t=2&c=Umai:CHAN/3540417@BESTV.SMG.SMG
甘肃卫视,bst.php?g=1&t=2&c=Umai:CHAN/6000067@BESTV.SMG.SMG
广东卫视,bst.php?g=1&t=2&c=Umai:CHAN/181359@BESTV.SMG.SMG
广西卫视,bst.php?g=1&t=2&c=Umai:CHAN/5000045@BESTV.SMG.SMG
贵州卫视,bst.php?g=1&t=2&c=Umai:CHAN/4252663@BESTV.SMG.SMG
海南卫视,bst.php?g=1&t=2&c=Umai:CHAN/4252684@BESTV.SMG.SMG
河北卫视,bst.php?g=1&t=2&c=Umai:CHAN/100000002@BESTV.SMG.SMG
河南卫视,bst.php?g=1&t=2&c=Umai:CHAN/5000044@BESTV.SMG.SMG
黑龙江卫视,bst.php?g=1&t=2&c=Umai:CHAN/181356@BESTV.SMG.SMG
湖北卫视,bst.php?g=1&t=2&c=Umai:CHAN/911989@BESTV.SMG.SMG
湖南卫视,bst.php?g=1&t=2&c=Umai:CHAN/181358@BESTV.SMG.SMG
吉林卫视,bst.php?g=1&t=2&c=Umai:CHAN/5000046@BESTV.SMG.SMG
江苏卫视,bst.php?g=1&t=2&c=Umai:CHAN/111129@BESTV.SMG.SMG
江西卫视,bst.php?g=1&t=2&c=Umai:CHAN/3468921@BESTV.SMG.SMG
辽宁卫视,bst.php?g=1&t=2&c=Umai:CHAN/3450001@BESTV.SMG.SMG
内蒙古卫视,bst.php?g=1&t=2&c=Umai:CHAN/79469@BESTV.SMG.SMG
宁夏卫视,bst.php?g=1&t=2&c=Umai:CHAN/160782@BESTV.SMG.SMG
青海卫视,bst.php?g=1&t=2&c=Umai:CHAN/67026@BESTV.SMG.SMG
山东卫视,bst.php?g=1&t=2&c=Umai:CHAN/911992@BESTV.SMG.SMG
山西卫视,bst.php?g=1&t=2&c=Umai:CHAN/1344@BESTV.SMG.SMG
陕西卫视,bst.php?g=1&t=2&c=Umai:CHAN/7105@BESTV.SMG.SMG
深圳卫视,bst.php?g=1&t=2&c=Umai:CHAN/181362@BESTV.SMG.SMG
四川卫视,bst.php?g=1&t=2&c=Umai:CHAN/3840706@BESTV.SMG.SMG
天津卫视,bst.php?g=1&t=2&c=Umai:CHAN/3450000@BESTV.SMG.SMG
西藏卫视,bst.php?g=1&t=2&c=Umai:CHAN/77574@BESTV.SMG.SMG
新疆卫视,bst.php?g=1&t=2&c=Umai:CHAN/126387@BESTV.SMG.SMG
云南卫视,bst.php?g=1&t=2&c=Umai:CHAN/5000043@BESTV.SMG.SMG
浙江卫视,bst.php?g=1&t=2&c=Umai:CHAN/111130@BESTV.SMG.SMG

广东嘉佳卡通,bst.php?g=1&t=2&c=Umai:CHAN/484998@BESTV.SMG.SMG
湖南金鹰卡通,bst.php?g=1&t=2&c=Umai:CHAN/79467@BESTV.SMG.SMG
湖南金鹰纪实,bst.php?g=1&t=2&c=Umai:CHAN/4766929@BESTV.SMG.SMG
湖南快乐垂钓,bst.php?g=1&t=2&c=Umai:CHAN/4766930@BESTV.SMG.SMG
湖南茶频道,bst.php?g=1&t=2&c=Umai:CHAN/6000049@BESTV.SMG.SMG
江苏财富天下,bst.php?g=1&t=2&c=Umai:CHAN/5000047@BESTV.SMG.SMG
家庭理财,bst.php?g=1&t=2&c=Umai:CHAN/1872767@BESTV.SMG.SMG

好享购物,bst.php?g=1&t=2&c=Umai:CHAN/1800368@BESTV.SMG.SMG
央广购物,bst.php?g=1&t=2&c=Umai:CHAN/1168794@BESTV.SMG.SMG
京剧《白蛇传》,bst.php?g=85&t=1&c=14016258
*/