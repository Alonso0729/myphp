<?php
$id = isset($_GET['channel']) ? $_GET['channel'] : '';
if ($id) {
    $id  = strtolower($id);

    $ids = [
        '深圳卫视' => 'AxeFRth',
        '深圳少儿' => '1SIQj6s',
        '深圳财经' => '3vlcoxP',
        '深圳电视剧' => '4azbkoY',
        '宜和购物' => 'BJ5u5k2',
        '深圳都市' => 'ZwxzUXr',
        '深圳国际' => 'sztvgjpd',
        '深圳移动' => 'wDF6KJ3',
        '深圳卫视4k' => 'R77mK1v'

    ];
    $key = "bf9b2cab35a9c38857b82aabf99874aa96b9ffbb";
    $dectime = dechex(time()+7200);
    $rate = "500";
    $path = '/'.$ids[$id].'/'.$rate.'/'.pathname($ids[$id]).'.m3u8';

    $sign = md5($key.$path.$dectime);
    $liveURL = $path."?sign={$sign}&t={$dectime}";
    $liveURL = $_SERVER['PHP_SELF'] . '?m3u8=' . rawurlencode($liveURL);

    header('Access-Control-Allow-Origin: *');
    header("Location: $liveURL");
    die;
}

$m3u8 = isset($_GET['m3u8']) ? $_GET['m3u8'] : '';
if ($m3u8) {
    $c = get($m3u8);
    $p = $_SERVER['PHP_SELF'] . '?ts=' . dirname($m3u8) . '/';
    $c = preg_replace('/^(?=.+\.ts)/m', $p, $c);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/vnd.apple.mpegurl');
    echo $c;
    die;
}

$ts = isset($_GET['ts']) ? $_GET['ts'] : '';
if ($ts) {
    $c = get($ts);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: video/MP2T');
    echo $c;
    die;
}



function get($path) {
    $url = 'https://sztv-hls.sztv.com.cn' . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_REFERER,'https://www.sztv.com.cn/');
//    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.6261.95 Safari/537.36');
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function pathname($e)
{
    date_default_timezone_set('Asia/Shanghai');
    $o = strtotime('today')*1000;


    $a=0;
    $r=0;
    $d=-1;
    $p=0;
    $l=0;

    for($a=0;$a<strlen($e);$a++)
    {
        $p = ord($e[$a]);
        $r = $r + $p;
        if($d!=-1)
        {
            $l=$l+($d-$p);
        }
        $d = $p;
    }

    $r = $r + $l;
    $s = base_convert($r,10,36);
    $c = base_convert($o,10,36);
    $u = 0;
    for($a=0;$a<strlen($c);$a++)
    {
        $u = $u + ord($c[$a]);
    }


    $c = substr($c, 5) . substr($c, 0, 5);
    $f = abs($u - $r);
    $c = strrev($s) . $c;
    $g = substr($c,0,4);
    $w = substr($c,4);
    $b = date('w') % 2;


    $m = [];

    for ($a = 0; $a < strlen($e); $a++) {

        if ($a % 2 == $b) {
            $index = $a % strlen($c);
            $m[] = $c[$index];
        } else {
            $hIndex = $a - 1;
            if ($hIndex >= 0 ) {
                $h = $e[$hIndex];
                $v = strpos($g, $h);
                if ($v === false) {
                    $m[] = $h;
                } else {
                    $m[] = $w[$v];
                }
            } else {
                $gIndex = $a % strlen($g);
                $m[] = $g[$gIndex];
            }
        }

    }
    $result = strrev(base_convert($f, 10, 36)) . implode('', $m);
    $result = substr($result, 0, strlen($e));
    return $result;
}




/*
深圳卫视4K,sz.php?channel=%E6%B7%B1%E5%9C%B3%E5%8D%AB%E8%A7%864K
深圳卫视,sz.php?channel=%E6%B7%B1%E5%9C%B3%E5%8D%AB%E8%A7%86
深圳都市频道,sz.php?channel=%E6%B7%B1%E5%9C%B3%E9%83%BD%E5%B8%82
深圳电视剧频道,sz.php?channel=%E6%B7%B1%E5%9C%B3%E7%94%B5%E8%A7%86%E5%89%A7
深圳财经频道,sz.php?channel=%E6%B7%B1%E5%9C%B3%E8%B4%A2%E7%BB%8F
深圳少儿频道,sz.php?channel=%E6%B7%B1%E5%9C%B3%E5%B0%91%E5%84%BF
深圳移动电视,sz.php?channel=%E6%B7%B1%E5%9C%B3%E7%A7%BB%E5%8A%A8
深圳宜和购物频道,sz.php?channel=%E5%AE%9C%E5%92%8C%E8%B4%AD%E7%89%A9
深圳国际频道,sz.php?channel=%E6%B7%B1%E5%9C%B3%E5%9B%BD%E9%99%85
*/