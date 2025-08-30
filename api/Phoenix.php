<?php

function curl($bstrURL, $headers, $postData)
{
$ch = curl_init($bstrURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, $postData ? 1 : 0);
    if ($postData) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function base64_url_decode($data)
{
    return base64_decode(strtr($data, '-_', '+/'));
}

function validateJWT($jwt)
{
    // Split the token into header, payload, and signature parts
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        return false; // Invalid token format
    }

    // Decode header and payload
    $header = json_decode(base64_url_decode($parts[0]), true);
    $payload = json_decode(base64_url_decode($parts[1]), true);

    // Check expiration
    if (isset($payload['exp']) && $payload['exp'] < time() + 3600) {
        return false; // Token expires in 1 hour
    }
    return true; // Token is valid
}

function getToken($prefix, $user, $pwd)
{
    $token_file = "fengshows_token.txt";
    if (file_exists($token_file)) {
        $contents = file_get_contents("fengshows_token.txt");
        if (validateJWT($contents)) {
            return $contents;
        }
    }
    $headers = [
        "Content-Type: application/json"
    ];
    $body = [
        "code" => $prefix,
        "keep_alive" => false,
        "password" => $pwd,
        "phone" => $user,
    ];
    $ret = curl("https://m.fengshows.com/api/v3/mp/user/login", $headers, json_encode($body));
    $json = json_decode($ret);
    if ($json->message == "ok") {
        $token = $json->data->token;
        file_put_contents("fengshows_token.txt", $token);
        return $token;
    } else {
        echo $json->message;
        die();
    }
}

$phonePrefix = "86"; // 大陆 "86" 箱港 "852" 米国 "1"
$phone = "13256889895";
$pwd = "Fan2345678";

//备用号码1:phone=13389247903&pwd=Llxxcc198,备用号码2:phone=13955036885&pwd=make123456MAKE

$channels = [
    "info" => "7c96b084-60e1-40a9-89c5-682b994fb680",
    "cn" => "f7f48462-9b13-485b-8101-7b54716411ec",
    "hk" => "15e02d92-1698-416c-af2f-3e9a872b4d78",
];

$id = isset($_GET['id']) ? $_GET['id'] : 'cn';
$chid = $channels[$id];

$url = "https://m.fengshows.com/api/v3/hub/live/auth-url?live_id=$chid&live_qa=fHD";
$headers = [
    "User-Agent: Mozilla/5.0 (Linux; Android 10; SM-G960U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.181 Mobile Safari/537.36",
    "Referer: https://m.fengshows.com/",
    "Token: " . getToken($phonePrefix, $phone, $pwd),
    "Origin: https://m.fengshows.com",
];
$live = curl($url, $headers, null);
$liveInfo = json_decode($live, true);
$url = $liveInfo["data"]["live_url"];

header('location:' . $url);