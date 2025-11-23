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
//        'ltzxps.bbtv.cn',

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
    if ($type > 1) {
        $q = parse_url($r, PHP_URL_QUERY);
        parse_str($q, $a);
        $r = sprintf('%s?se=%s&ct=%s', strtok($r, '?'), $a['se'], $a['ct']);
//        $r = sprintf('%s', strtok($r, '?'));
    } else {
        $r = sprintf('%s?_BitRate=6000', strtok($r, '?'));
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

function locate($url)
{
    header('Access-Control-Allow-Origin: *');
    header('Location: ' . $url);
}