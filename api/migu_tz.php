<?php
$id = $_GET['id'];
$p = get_m3u8_url_from_web($id);
header('Access-Control-Allow-Origin: *');
header('Location: '.$p);



function get_m3u8_url_from_web($id) {
    $u = "http://aikanvod.miguvideo.com/video/p/live.jsp?user=guest&channel=$id&isEncrypt=1";
    $u .= '&appVersion=927&channalNo=2';
    $u .= '&categoryid=8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac';
    $c = file_get_contents($u);
    preg_match('/source src="(.*?)"/', $c, $m);
    $p = $m[1];
    if ($p == '') {
        $cookies = [];
        foreach ($http_response_header as $h)
            if (preg_match('/Set-Cookie:\s*(.*?);/i', $h, $m) === 1)
                $cookies[] = $m[1];
        $cookieHeader = implode('; ', $cookies);
        $doc = new DOMDocument();
        @$doc->loadHTML($c);
        $k = '';
        $maxLen = 0;
        foreach ($doc->getElementsByTagName('input') as $input) {
            if ($input->getAttribute('id') === 'posterPicAll')
                continue;
            $inputValue = $input->getAttribute('value');
            if ($inputValue && $maxLen < strlen($inputValue)) {
                $maxLen = strlen($inputValue);
                $k = $inputValue;
            }
        }
        $channel = $doc->getElementById('channel')->getAttribute('value');
        $channel = rawurlencode(encryptPwd($channel, $k));
        $channelcode = $doc->getElementById('channelcode')->getAttribute('channelcode');
        $channelcode = rawurlencode(encryptPwd($channelcode, $k));
        $zjChannelOfOutStatus = $doc->getElementById('zjChannelOfOutStatus')->getAttribute('value');
        $zjChannelOfOutStatus = rawurlencode($zjChannelOfOutStatus);
        $u2 = "http://aikanvod.miguvideo.com/video/p/live.jsp?vt=9&type=1&user=guest&channel=$channel&isEncrypt=1&channelcode=$channelcode";
        $u2 .= "&zjChannelOfOutStatus=$zjChannelOfOutStatus";
        $c = file_get_contents($u2, false, stream_context_create(array(
            'http' => array(
                'header'  =>
                    "Referer: $u\r\n".
                    "epgsession: \r\n".
                    "location: \r\n".
                    "Cookie: $cookieHeader\r\n".
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36\r\n"
            )
        )));
        $j = json_decode($c);
        $p = $j->liveUrl;
    }
    return $p;
}

function encryptPwd($txt, $publicKey) {
    // 格式化公钥（确保包含PEM头尾）
    if (strpos($publicKey, '-----BEGIN PUBLIC KEY-----') === false) {
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
            chunk_split($publicKey, 64, "\n") .
            "-----END PUBLIC KEY-----";
    }
    // 加载公钥
    $keyResource = openssl_pkey_get_public($publicKey);
    // 加密数据
    $encrypted = '';
    openssl_public_encrypt($txt, $encrypted, $keyResource);
    if (PHP_VERSION_ID < 80000)
        openssl_pkey_free($keyResource);
    // 返回Base64编码结果
    return base64_encode($encrypted);
}