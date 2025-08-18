<?php

date_default_timezone_set('Asia/Shanghai');
$id = $_GET['channel']?:"深圳卫视";
$id  = strtolower($id);

$hosts = "https://sztv-hls.sztv.com.cn";

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
$liveURL = $hosts.$path."?sign={$sign}&t={$dectime}";

header("location: $liveURL");

function pathname($e)
{

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

?>
