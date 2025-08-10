<?php

function generateUUID() {
    $randomBytes = random_bytes(16);
    return bin2hex($randomBytes); // 32位（8字符十六进制）
}

function main($id) {
    $host = "zwebl02.cztv.com";
    $pathname = "/live/channel{$id}1080P.m3u8"; // 使用 $id 动态生成 pathname
    $t = time();
    $uid = 0;
    $uuid = generateUUID();
    $uuid = str_split($uuid);
    $uuid[12] = "4";
    $uuid[16] = "a";
    $uuid = implode("", $uuid);
    $key = "CHWr9VybUeBZE1VB";
    $r = "{$pathname}-{$t}-{$uuid}-{$uid}-{$key}";
    $p = md5($r);
    $auth_key = "https://{$host}{$pathname}?auth_key={$t}-{$uuid}-{$uid}-{$p}";
    echo $auth_key;
}

// 调用 main 函数并传入 id 参数
/**
* 01浙江卫视
* 02钱江都市
* 03经济生活
* 04影视科教
* 06民生休闲
* 07新闻频道
* 08少儿频道
* 10浙江国际
* 11好易购
* 12之江记录
*/
main({$id});