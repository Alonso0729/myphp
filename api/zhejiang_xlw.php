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