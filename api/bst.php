<?php
$e = main($_GET['g'], $_GET['t'], $_GET['c'], $l);
if ($e == '')
    locate($l);
else
    echo $e;



function main($group, $type, $code, &$play_url)
{
    $response_msg = '';
    for ($i = 1; $i <= 10; $i++) {
        foreach (hosts_get() as $host) {
            list($u, $p) = intf_get($host, $group, $type, $code);
            $c = @file_get_contents($u, false, stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
//                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => $p,
                )
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
        '139.224.116.50',
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
        $q = parse_url($r, PHP_URL_QUERY);
        parse_str($q, $a);
        $r = sprintf('%s?se=%s&ct=%s', strtok($r, '?'), $a['se'], $a['ct']);
    } else {
        $r = sprintf('%s?_BitRate=4000', strtok($r, '?'));
    }
    return $r;
}

function locate($url)
{
    header('Access-Control-Allow-Origin: *');
    header('Location: ' . $url);
}

/*
央视,#genre#
CCTV-1HD,bst.php?g=1&t=2&c=Umai:CHAN/111128@BESTV.SMG.SMG
CCTV-2HD,bst.php?g=1&t=2&c=Umai:CHAN/5000036@BESTV.SMG.SMG
CCTV-3HD,bst.php?g=29&t=2&c=Umai:CHAN/1369028@BESTV.SMG.SMG
CCTV-4,bst.php?g=1&t=2&c=Umai:CHAN/1349@BESTV.SMG.SMG
CCTV-5HD,bst.php?g=29&t=2&c=Umai:CHAN/1369029@BESTV.SMG.SMG
CCTV-5+HD,bst.php?g=29&t=2&c=Umai:CHAN/6000068@BESTV.SMG.SMG
CCTV-6HD,bst.php?g=29&t=2&c=Umai:CHAN/1369030@BESTV.SMG.SMG
CCTV-7,bst.php?g=1&t=2&c=Umai:CHAN/1352@BESTV.SMG.SMG
CCTV-8HD,bst.php?g=29&t=2&c=Umai:CHAN/1369033@BESTV.SMG.SMG
CCTV-9HD,bst.php?g=29&t=2&c=Umai:CHAN/5000039@BESTV.SMG.SMG
CCTV-10,bst.php?g=1&t=2&c=Umai:CHAN/1355@BESTV.SMG.SMG
CCTV-11,bst.php?g=1&t=2&c=Umai:CHAN/1356@BESTV.SMG.SMG
CCTV-12,bst.php?g=1&t=2&c=Umai:CHAN/1357@BESTV.SMG.SMG
CCTV-13HD,bst.php?g=1&t=2&c=Umai:CHAN/6000054@BESTV.SMG.SMG
CCTV-13,bst.php?g=1&t=2&c=Umai:CHAN/1358@BESTV.SMG.SMG
CCTV-14,bst.php?g=1&t=2&c=Umai:CHAN/1359@BESTV.SMG.SMG
CCTV-15,bst.php?g=1&t=2&c=Umai:CHAN/3874@BESTV.SMG.SMG
CCTV-16HD,bst.php?g=29&t=2&c=Umai:CHAN/6000061@BESTV.SMG.SMG
CCTV-17HD,bst.php?g=29&t=2&c=Umai:CHAN/5000041@BESTV.SMG.SMG

卫视,#genre#
安徽卫视HD,bst.php?g=1&t=2&c=Umai:CHAN/3540416@BESTV.SMG.SMG
北京卫视HD,bst.php?g=29&t=2&c=Umai:CHAN/181361@BESTV.SMG.SMG
北京卫视,bst.php?g=1&t=2&c=Umai:CHAN/1326@BESTV.SMG.SMG
广东深圳卫视HD,bst.php?g=1&t=2&c=Umai:CHAN/181362@BESTV.SMG.SMG
黑龙江卫视,bst.php?g=1&t=2&c=Umai:CHAN/1343@BESTV.SMG.SMG
湖北卫视,bst.php?g=1&t=2&c=Umai:CHAN/1341@BESTV.SMG.SMG
湖南卫视HD,bst.php?g=1&t=2&c=Umai:CHAN/181358@BESTV.SMG.SMG
江苏卫视HD,bst.php?g=1&t=2&c=Umai:CHAN/111129@BESTV.SMG.SMG
江西卫视HD,bst.php?g=1&t=2&c=Umai:CHAN/3468921@BESTV.SMG.SMG
辽宁卫视HD,bst.php?g=1&t=2&c=Umai:CHAN/3450001@BESTV.SMG.SMG
山东卫视,bst.php?g=1&t=2&c=Umai:CHAN/1330@BESTV.SMG.SMG
上海东方卫视HD,bst.php?g=1&t=2&c=Umai:CHAN/111131@BESTV.SMG.SMG
天津卫视HD,bst.php?g=1&t=2&c=Umai:CHAN/3450000@BESTV.SMG.SMG
浙江卫视HD,bst.php?g=1&t=2&c=Umai:CHAN/111130@BESTV.SMG.SMG

上海,#genre#
第一财经HD,bst.php?g=1&t=2&c=Umai:CHAN/1346062@BESTV.SMG.SMG
第一财经,bst.php?g=1&t=2&c=Umai:CHAN/1314@BESTV.SMG.SMG
东方财经HD,bst.php?g=1&t=2&c=Umai:CHAN/6880079@BESTV.SMG.SMG
东方财经,bst.php?g=1&t=2&c=Umai:CHAN/1383@BESTV.SMG.SMG
东方购物-1,bst.php?g=1&t=2&c=Umai:CHAN/648549@BESTV.SMG.SMG
都市剧场,bst.php?g=1&t=2&c=Umai:CHAN/1366@BESTV.SMG.SMG
都市频道,bst.php?g=1&t=2&c=Umai:CHAN/1318@BESTV.SMG.SMG
哈哈炫动HD,bst.php?g=29&t=2&c=Umai:CHAN/4641019@BESTV.SMG.SMG
哈哈炫动,bst.php?g=1&t=2&c=Umai:CHAN/1324@BESTV.SMG.SMG
欢笑剧场,bst.php?g=1&t=2&c=Umai:CHAN/1376@BESTV.SMG.SMG
七彩戏剧HD,bst.php?g=29&t=2&c=Umai:CHAN/6880440@BESTV.SMG.SMG
七彩戏剧,bst.php?g=1&t=2&c=Umai:CHAN/1374@BESTV.SMG.SMG
外语频道HD,bst.php?g=1&t=2&c=Umai:CHAN/3778907@BESTV.SMG.SMG
新闻综合HD,bst.php?g=29&t=2&c=Umai:CHAN/1346060@BESTV.SMG.SMG
新闻综合,bst.php?g=1&t=2&c=Umai:CHAN/1312@BESTV.SMG.SMG
游戏风云HD,bst.php?g=1&t=2&c=Umai:CHAN/1555894@BESTV.SMG.SMG

百视通,#genre#
百视通1,bst.php?g=1&t=2&c=Umai:CHAN/123222@BESTV.SMG.SMG
百视通2,bst.php?g=1&t=2&c=Umai:CHAN/123223@BESTV.SMG.SMG
百视通3,bst.php?g=1&t=2&c=Umai:CHAN/123224@BESTV.SMG.SMG
百视通4,bst.php?g=1&t=2&c=Umai:CHAN/123225@BESTV.SMG.SMG
百视通5,bst.php?g=1&t=2&c=Umai:CHAN/123226@BESTV.SMG.SMG
百视通6,bst.php?g=1&t=2&c=Umai:CHAN/123227@BESTV.SMG.SMG
百视通7,bst.php?g=1&t=2&c=Umai:CHAN/123228@BESTV.SMG.SMG

点播,#genre#
京剧《白蛇传》,bst.php?g=1&t=1&c=14016258
*/