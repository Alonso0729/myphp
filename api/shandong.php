<?php
function aesEncrypt($plainText, $key) {
    if (empty($plainText)) {
        return "";
    }
    $iv = "0000000000000000"; // 16 字节的 IV
$cipherText = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($cipherText);
}

function aesDecrypt($cipherText, $key, $ivHex) {
    // 将 32 字节的十六进制 IV 转换为 16 字节的二进制 IV
$iv = hex2bin($ivHex);
    $cipherText = base64_decode($cipherText);
    $plainText = openssl_decrypt($cipherText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return $plainText;
}

function getchData($channelCode) {
    $mxpx = "FMRVPTYUGRXZTKBZ";
    $q = "MLZWBUJLILAPLQXN";
    $t = round(microtime(true) * 1000); // 获取当前时间戳（毫秒）
$postData = aesEncrypt('{"channelMark":' . $channelCode . '}', $q);
    $p = md5($channelCode . $t . $mxpx);
    $apiurl = "https://feiying.litenews.cn/api/v1/auth/exchange?t=" . $t . "&s=" . $p;
    // 设置请求头
$headers = [
        'Content-Type: text/plain', // 确保与 Node.js 一致
'Referer: https://v.iqilu.com/', // 确保与 Node.js 一致
'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36' // 添加 User-Agent
];

    // 初始化 cURL
$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiurl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // 直接发送原始数据
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true); // 在 HTTP 错误时返回 false
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 忽略 SSL 证书验证（仅用于调试）
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 忽略 SSL 主机名验证（仅用于调试）
// 执行请求
$response = curl_exec($ch);

    // 检查 cURL 错误
if ($response === false) {
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        echo "cURL 请求失败，错误代码: " . $errno . "\n";
        echo "错误信息: " . $error . "\n";
        curl_close($ch);
        return;
    }
    curl_close($ch);
    // 如果返回数据为空，直接退出
if (empty($response)) {
        echo "API 返回数据为空，请检查请求参数或网络连接。\n";
        return;
    }

    // 解密响应数据
$oop = aesDecrypt($response, $q, '30303030303030303030303030303030');


    // 检查解密后的数据是否有效
if (empty($oop)) {
        echo "解密失败或返回数据为空。\n";
        return;
    }

    // 使用正则表达式匹配 URL
preg_match_all('/https?:\/\/[^\s"\']+/', $oop, $matches);

    // 检查是否匹配到 URL
if (!empty($matches[0])) {
        $u = $matches[0][0];
        echo $u ;
    } else {
        echo "未找到匹配的 URL。\n";
    }
}

/**
* 山东卫视  24581 (播放器需要设置referer:https://v.iqilu.com/)
* 齐鲁频道 24584
* 体育频道 24587
* 生活频道 24596
* 综艺频道 24593
* 新闻频道 24602  (播放器需要设置referer:https://v.iqilu.com/)
* 农科频道 24599
* 文旅频道 24590
* 少儿频道 24605  (播放器需要设置referer:https://v.iqilu.com/)
*/
$channelCode = "24584";
getchData($channelCode);

?>