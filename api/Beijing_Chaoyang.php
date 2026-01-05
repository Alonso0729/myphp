<?php

/*
朝阳新闻综合,cy.php?id=60
朝阳教育,cy.php?id=61
*/

$id = $_GET['id'];

$data = round(microtime(true) * 1000);
$blockSize = 16; // AES块大小固定为16字节
$dataLength = strlen($data);
$paddingLength = $blockSize - ($dataLength % $blockSize);
$paddedData = $data . str_repeat("\0", $paddingLength);
$e = openssl_encrypt(
    $paddedData,
    'aes-256-cbc',
    'BkOcnMPiGKh9WkmPftgZBMVM2gWw33v0',
    OPENSSL_ZERO_PADDING,
    'fReE9dQL0PPuEaey'//'fReE9dQL0PPuEaey3exlx4sQbtxLk7H2'
);
$a = '{"timestamp":'.$data.',"encryption":"'.$e.'"}';
$u = 'https://cyrm.app.cygbdst.com:1443/api/gettvlivebyid';
$p = 'tvliveid='.$id;
//$u = 'https://cyrm.app.cygbdst.com:1443/api/gettvlivelsit';
//$p = '';
$c = file_get_contents($u, false, stream_context_create(array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
    ),
    'http' => array(
        'method' => 'POST',
        'header' =>
            "authorization: $a\r\n".
            "User-Agent: Mozilla/5.0 (Linux; Android 12; SM-A5560 Build/V417IR; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/101.0.4951.61 Mobile Safari/537.36\r\n".
            "Accept: */*\r\n".
            "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $p
    )
)));
$j = json_decode($c);
header('Access-Control-Allow-Origin: *');
header('Location: '.$j->data->path);