<?php

/*
安徽卫视,anhuisx.php?id=ahwssx
安徽经济生活,anhuisx.php?id=jjshtv
安徽影视,anhuisx.php?id=ystv
安徽公共,anhuisx.php?id=ggtv
安徽农业科教,anhuisx.php?id=nykjtv
安徽综艺体育,anhuisx.php?id=zytytv
安徽国际,anhuisx.php?id=gjtv
安庆新闻综合,anhuisx.php?id=aqpd
黄山新闻综合,http://hslive.hsnewsnet.com/lsdream/hve9Wjs/1000/live.m3u8
亳州综合,https://zbbf2.ahbztv.com/live/416.m3u8
六安综合,anhuisx.php?id=latv
淮北新闻综合,anhuisx.php?id=hbxw
宿州新闻综合,anhuisx.php?id=szxw
芜湖新闻综合,anhuisx.php?id=whxw
宣城综合,anhuisx.php?id=xctv
蚌埠新闻,anhuisx.php?id=bbtv
*/

$l = com_mediacloud_app_newsmodule_utils_encodeUrl(
    'https://live.ahsx.ahtv.cn/live',
    $_GET['id']
);
header('Access-Control-Allow-Origin: *');
header('Location: '.$l);



function com_mediacloud_app_newsmodule_utils_encodeUrl($str, $str2) {
    Intrinsics_checkNotNullParameter($str, "liveDomain");
    Intrinsics_checkNotNullParameter($str2, LiveMediaPlayBackInvoker_Url());
    $str3 = AppFactoryGlobalConfig_ServerAppConfigInfo_OtherConfig_tx_auth_key();
    $hexString = Long_toHexString((System_currentTimeMillis() + EFFECTIVETIME()) / TimeConstants_SEC());
    $MD5Encode = MD5Util_MD5Encode($str3 . $str2 . $hexString, "utf-8");
    Intrinsics_checkNotNullExpressionValue($MD5Encode, "MD5Encode(md5, 'utf-8')");
    $str4 = $str . '/' . $str2 . ".m3u8?txSecret=" . $MD5Encode . "&txTime=" . $hexString;
    Log_e("encodeUrl: ", $str4);
    return $str4;
}

function Log_e(...$values) {

}

function MD5Util_MD5Encode($s, $f) {
    return md5($s);
}

function TimeConstants_SEC() {
    return 1000;
}

function EFFECTIVETIME() {
    return 7200000;
}

function System_currentTimeMillis() {
    return round(microTime(true) * 1000);
}

function Long_toHexString($long) {
    return strtoupper(dechex(floor($long)));
}

function AppFactoryGlobalConfig_ServerAppConfigInfo_OtherConfig_tx_auth_key() {
    return '3PyAeRCExDGfWezz6pGs';
}

function LiveMediaPlayBackInvoker_Url() {
    return 'url';
}

function Intrinsics_checkNotNullParameter(...$values) {

}

function Intrinsics_checkNotNullExpressionValue(...$values) {

}