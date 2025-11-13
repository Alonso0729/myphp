<?php

/*
CCTV-1HD,bst.php?code=Umai:CHAN/111128@BESTV.SMG.SMG
CCTV-2HD,bst.php?code=Umai:CHAN/5000036@BESTV.SMG.SMG
CCTV-5+HD,bst.php?code=Umai:CHAN/6000068@BESTV.SMG.SMG
CCTV-13HD,bst.php?code=Umai:CHAN/6000054@BESTV.SMG.SMG
安徽卫视HD,bst.php?code=Umai:CHAN/3540416@BESTV.SMG.SMG
广东深圳卫视HD,bst.php?code=Umai:CHAN/181362@BESTV.SMG.SMG
江苏卫视HD,bst.php?code=Umai:CHAN/111129@BESTV.SMG.SMG
江西卫视HD,bst.php?code=Umai:CHAN/3468921@BESTV.SMG.SMG
辽宁卫视HD,bst.php?code=Umai:CHAN/3450001@BESTV.SMG.SMG
东方卫视HD,bst.php?code=Umai:CHAN/111131@BESTV.SMG.SMG
天津卫视HD,bst.php?code=Umai:CHAN/3450000@BESTV.SMG.SMG
浙江卫视HD,bst.php?code=Umai:CHAN/111130@BESTV.SMG.SMG
东方财经HD,bst.php?code=Umai:CHAN/6880079@BESTV.SMG.SMG
游戏风云HD,bst.php?code=Umai:CHAN/1555894@BESTV.SMG.SMG
CCTV-7,bst.php?code=Umai:CHAN/1352@BESTV.SMG.SMG
CCTV-10,bst.php?code=Umai:CHAN/1355@BESTV.SMG.SMG
CCTV-11,bst.php?code=Umai:CHAN/1356@BESTV.SMG.SMG
CCTV-12,bst.php?code=Umai:CHAN/1357@BESTV.SMG.SMG
CCTV-13,bst.php?code=Umai:CHAN/1358@BESTV.SMG.SMG
CCTV-14,bst.php?code=Umai:CHAN/1359@BESTV.SMG.SMG
CCTV-15,bst.php?code=Umai:CHAN/3874@BESTV.SMG.SMG
北京卫视,bst.php?code=Umai:CHAN/1326@BESTV.SMG.SMG
黑龙江卫视,bst.php?code=Umai:CHAN/1343@BESTV.SMG.SMG
湖北卫视,bst.php?code=Umai:CHAN/1341@BESTV.SMG.SMG
山东卫视,bst.php?code=Umai:CHAN/1330@BESTV.SMG.SMG
第一财经,bst.php?code=Umai:CHAN/1314@BESTV.SMG.SMG
东方购物-1,bst.php?code=Umai:CHAN/648549@BESTV.SMG.SMG
哈哈炫动,bst.php?code=Umai:CHAN/1324@BESTV.SMG.SMG
新闻综合,bst.php?code=Umai:CHAN/1312@BESTV.SMG.SMG
*/

$e = main($_GET['code'], $l);
if ($e == '')
    locate($l);
else
    echo $e;



function main($code, &$play_url)
{
    $response_msg = '';
    for ($i = 1; $i <= 10; $i++) {
        foreach (hosts_get() as $host) {
            $u = intf_get($host, $code);
            $c = file_get_contents($u);
            $play_url = url_get($c, $response_code, $response_msg);
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

function intf_get($host, $code)
{
    $f = 'http://%s.bestv.com.cn/ps/OttService/Auth?UserID=%s&UserToken=%s&TVID=$$%s&UserGroup=$TerOut_1&ItemType=2&ItemCode=%s';
    $r = sprintf($f, $host, user_get_id(), user_get_token(), tvid_get(), $code);
    return $r;
}

function tvid_get()
{
    return random_strings(4, 16);
}

function user_get_token()
{
    return random_strings(1, 16);
}

function user_get_id()
{
    return random_strings(1, 16);
}

function random_strings($min, $max)
{
    $r = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0,
        mt_rand($min, $max));
    return $r;
}

function hosts_get()
{
    return [
        'b2cv3replay',
        'b2cv3wxmini',
        'b2cv3epg',
        'b2cv3aaa',
        'b2cv3ps'
    ];
}

function url_get($content, &$response_code, &$response_msg)
{
    $j = json_decode($content);
    $response_code = $j->Response->Header->RC;
    $response_msg = $j->Response->Header->RM;
    if ($response_code != 0)
        return '';
    $r = $j->Response->Body->PlayURL;
    $q = parse_url($r, PHP_URL_QUERY);
    parse_str($q, $a);
    $r = sprintf('%s?se=%s&ct=%s', strtok($r, '?'), $a['se'], $a['ct']);
    return $r;
}

function locate($url)
{
    header('Access-Control-Allow-Origin: *');
    header('Location: ' . $url);
}