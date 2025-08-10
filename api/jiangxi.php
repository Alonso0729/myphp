<?php
date_default_timezone_set('Asia/Shanghai');
$id = isset($_GET['id'])?$_GET['id']:'1';
$n = [
        '1' => 'tv_jxtv1.m3u8',//江西卫视
        '2' => 'tv_jxtv2.m3u8',//江西都市
        '3' => 'tv_jxtv3_hd.m3u8',//江西经视
        '4' => 'tv_jxtv4.m3u8',//江西影视
        '5' => 'tv_jxtv5.m3u8',//江西公共.
        '6' => 'tv_jxtv6.m3u8',//江西少儿
        '7' => 'tv_jxtv7.m3u8',//江西新闻
        '8' => 'tv_jxtv8.m3u8',//江西移动
        '9' => 'tv_fsgw.m3u8',//江西风尚购物
        '10' => 'tv_taoci.m3u8',//江西陶瓷频道
        '11' => 'ganyun_jiujiang1.m3u8',//九江一套
        '12' => 'ganyun_jiujiang2.m3u8',//九江二套
        '13' => 'ganyun_jiujiang3.m3u8',//九江三套
        '14' => 'ganyun_shangrao1.m3u8',//上饶综合
        '15' => 'ganyun_shangrao2.m3u8',//上饶经济旅游
        '16' => 'ganyun_px_xwzh.m3u8',//萍乡综合
        '17' => 'ganyun_px_ggpd.m3u8',//萍乡公共
        '21' => 'ganyun_lushan.m3u8',//庐山电视台
        '22' => 'ganyun_jgs.m3u8',//井冈山综合
        '23' => 'ganyun_xiushui01.m3u8',//修水一套
        '24' => 'ganyun_duchang.m3u8',//都昌新闻
        '25' => 'ganyun_yongxiu.m3u8',//永修一套
        '26' => 'ganyun_pengze.m3u8',//彭泽一套
        '27' => 'ganyun_ningdu.m3u8',//宁都综合
        '28' => 'ganyun_guangfeng.m3u8',//广丰综合
        '29' => 'ganyun_yugan.m3u8',//余干综合
        '30' => 'ganyun_wanzai.m3u8',//万载综合
        '31' => 'ganyun_wuning.m3u8',//武宁新闻
        '32' => 'ganyun_guangchu此字符被系统屏蔽.m3u8',//广昌综合
        '33' => 'ganyun_tonggu.m3u8',//铜鼓综合
        '34' => 'ganyun_yanshan.m3u8',//铅山综合
        '35' => 'ganyun_fenyi_tv.m3u8',//分宜综合
   ];  
header("Content-Type:application/vnd.apple.mpegurl");
header("Content-Disposition:inline;filename={$n[$id]}");   
if($id>10){
        $url = "http://live02.jxtvcn.com.cn/live-jxtvcn/".$n[$id]."?source=pc&t=t&token=t";
        $Modified=(gmdate('D, d M Y H:i:s', time()+28800).' CST');
        $header = array(
                'Origin:http://www.jxntv.cn',
                'Referer:http://www.jxntv.cn/live/',
                'Host:live02.jxtvcn.com.cn',
                'If-Modified-Since:'.$Modified,
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.289 Safari/537.36'
        );        
        $burl='http://live02.jxtvcn.com.cn/live-jxtvcn/';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $d = curl_exec($ch);
        curl_close($ch);
        print_r(preg_replace("/(.*?.ts)/i", $burl."$1",$d));
}else {
        $url = "https://yun-live.jxtvcn.com.cn/live-jxtv/".$n[$id]."?source=pc&t=t&token=t";
        $Modified=(gmdate('D, d M Y H:i:s', time()+28800).' CST');
        $headers = array(
                'Origin:https://www.jxntv.cn',
                'Referer:https://www.jxntv.cn/live/',
                'Host:yun-live.jxtvcn.com.cn',
                'If-Modified-Since:'.$Modified,
                //'X-FORWARDED-FOR:'.$ip,
                //'CLIENT-IP:'.$ip,
                //'X-Real-IP:'.$ip,        
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.289 Safari/537.36'
        );        
        $burl='https://yun-live.jxtvcn.com.cn/live-jxtv/';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $d = curl_exec($ch);
        curl_close($ch);
        print_r(preg_replace("/(.*?.ts)/i", $burl."$1",$d));
}        
function curl($url,$header = array(), $type = 0, $post_data = '', $redirect = true) {
        $ip = rand_ips();
        $header = array(
            'X-FORWARDED-FOR: '.$ip,
            'CLIENT-IP: '.$ip,
            'X-Real-IP: '.$ip,
            'Origin: https://www.jxntv.cn',
            'Referer: https://www.jxntv.cn/live/',
            'Connection: Keep-Alive',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.0.0',
            'Content-Type: application/json; charset=utf-8',
            );               
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if (empty($header) == false) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        if ($type == 1) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        }
        if ($redirect == false) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $return = curl_exec($curl);
        curl_close($curl);
                return json_decode($return, true);
    }
        
function generate_password($length = 8){
        $chars = "ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678oOLl9gqVvUuI1";
        $password = '';
        for($i=0;$i < $length; $i++){
                $n = strlen($chars);
                $password .= $chars[mt_rand(0, strlen($chars)-1)];
        }
        return $password;
}
?>