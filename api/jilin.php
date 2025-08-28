<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'jlws';
$n = [
     'jlws' => 2, //吉林卫视
     'jlds' => 3, //都市频道
     'jlsh' => 4, //生活频道
     'jlys' => 5, //影视频道
     'jlxc' => 6, //乡村频道
     'jlggxw' => 7, //公共·新闻频道
     'jlzywh' => 8, //综艺·文化频道
     'jldbxq' => 9, //东北戏曲频道
     ];
$ch = curl_init('https://clientapi.jlntv.cn/broadcast/list?page=1&size=10000&type=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER,['User-Agent: Mozilla/5.0 (Windows NT 6.1)']);
$res = curl_exec($ch);
curl_close($ch);
$res = str_replace('"','',$res);
$str = xxtea_decrypt(base64_decode($res), "5b28bae827e651b3");
$json = json_decode($str, 1);
foreach ($json['data'] as $v) {
        if ($v['data']['id'] == $n[$id]) {
           $m3u8 = $v['data']['streamUrl'];
           header("Location: $m3u8");
           //echo $m3u8;
           exit;
           }
        }

/*
function xxtea_encrypt($str, $key) {
        if ($str == "") return "";
        $v = str2long($str, true);
        $k = str2long($key, false);
        if (count($k) < 4) {
                for ($i = count($k); $i < 4; $i++) {
                        $k[$i] = 0;
                }
        }
        $n = count($v) - 1;
        $z = $v[$n];
        $y = $v[0];
        $delta = 0x9E3779B9;
        $q = floor(6 + 52 / ($n + 1));
        $sum = 0;
        while (0 < $q--) {
                $sum = int32($sum + $delta);
                $e = $sum >> 2 & 3;
                for ($p = 0; $p < $n; $p++) {
                        $y = $v[$p + 1];
                        $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
                        $z = $v[$p] = int32($v[$p] + $mx);
                }
                $y = $v[0];
                $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
                $z = $v[$n] = int32($v[$n] + $mx);
        }
        return long2str($v, false);
}
*/

function xxtea_decrypt($str, $key) {
        if ($str == "") {
                return "";
        }
        $v = str2long($str, false);
        $k = str2long($key, false);
        if (count($k) < 4) {
                for ($i = count($k); $i < 4; $i++) {
                        $k[$i] = 0;
                }
        }
        $n = count($v) - 1;
        $z = $v[$n];
        $y = $v[0];
        $delta = 0x9E3779B9;
        $q = floor(6 + 52 / ($n + 1));
        $sum = int32($q * $delta);
        while ($sum != 0) {
                $e = $sum >> 2 & 3;
                for ($p = $n; $p > 0; $p--) {
                        $z = $v[$p - 1];
                        $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
                        $y = $v[$p] = int32($v[$p] - $mx);
                }
                $z = $v[$n];
                $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
                $y = $v[0] = int32($v[0] - $mx);
                $sum = int32($sum - $delta);
        }
        return long2str($v, true);
}
function int32($n) {
        while ($n >= 2147483648) $n -= 4294967296;
        while ($n <= -2147483649) $n += 4294967296;
        return (int)$n;
}
function str2long($s, $w) {
        $v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
        $v = array_values($v);
        if ($w) {
                $v[count($v)] = strlen($s);
        }
        return $v;
}
function long2str($v, $w) {
        $len = count($v);
        $n = ($len - 1) << 2;
        if ($w) {
                $m = $v[$len - 1];
                if (($m < $n - 3) || ($m > $n))
                          return false;
                $n = $m;
        }
        $s = array();
        for ($i = 0; $i < $len; $i++) {
                $s[$i] = pack("V", $v[$i]);
        }
        if ($w) {
                return substr(join('', $s), 0, $n);
        } else {
                return join('', $s);
        }
}
?>