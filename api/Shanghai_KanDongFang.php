<?php
class SignatureGenerator
{
    const SECRET_KEY = "C8F5954G8B61A93EDT4594BB8C318852";
    const SECRET_MAP = array(
        "0" => "0e4dac5a9587862b0706c5fd2465c0de",
        "1" => "d61ebbbb86f1434da9f70d549acc2a51",
        "2" => "0944fdccd6a0592c26498b53cf5ca564",
        "3" => "5ebcded7a926a8aecef837da950e901d",
        "4" => "3aaaf179684e76891b7eefe879c60dbd",
        "5" => "a9b8892ec540ac8014bab7e31419846b",
        "6" => "1946523d695215c891b4db1baac57606",
        "7" => "08ec7a511efbcf961bc0a4db771fe775",
        "8" => "813566ca8ef495a773869937adfc37bc",
        "9" => "705a32e119c381f76a079528fdb98660",
        "10" => "5b555a8cf74ecbebe022634ac4475e9e",
        "11" => "b9e16f7cf90b4bad33e4247ae9dbb675",
        "12" => "8767c472b0d54fa8de350bca92e5c403",
        "13" => "70d688e91cbdc0c57c0d1eac2a059b95",
        "14" => "d7fc2bdc9da814880e684cdb96f087fc",
        "15" => "402e00375ae5e821ef993f1e33f46149",
        "16" => "1992fa692dc6208065b01d5f5accf4d9",
        "17" => "9b64148ddbfe80305e7f7f5aa709cdf3",
        "18" => "a9d02865540436d7bf1c362e475f44fa",
        "19" => "8f5202ae0c05cf9d2db991aa1c8ebdee",
        "20" => "78ae59b3fb7363e0ac3aa77e9736b70b",
        "21" => "4584bb877e2130e9182984d93fbfd11c",
        "22" => "2ff1cac36d3c32d05ab1c6a15027bc3a",
        "23" => "7e4fa45d670e3e57a89af229d100f1ae",
        "24" => "6de82fdbffa640f61b2ad94b7dee042e",
        "25" => "f5fda1a2b25cc96bdb84f9adb1d588c4",
        "26" => "45e017b5f10a8a7fdeef88c58d072301",
        "27" => "6ed9f5536c2b2629738ac3af8556cdbe",
        "28" => "f24588cbbe0761afa9814fd53533b256",
        "29" => "4dba9e56114a833d558751761eed701a",
        "30" => "d29341713aea7c6b7bf75a276aa87e55",
        "31" => "fa3ab58376c82628b83f4d9ba551f25f",
        "32" => "3a893a448ffc6ee22907de39aab533d9",
        "33" => "bef3a51e337d2acec41b4bcb7cfd6053",
        "34" => "591cae3bd6f0dbed5a6f24f93a35e6e2",
        "35" => "63b4282077391fd9f93edf1b07c75bbb",
        "36" => "5c06682220931ed92f934ea7fbe3732a",
        "37" => "c1d0f242fa25a6f028140e92ea57caa3",
        "38" => "08dd8053eb956944e11b99230a677ff2",
        "39" => "4bbbaef52ecb05a10bd9a38984465320",
        "40" => "725f772c39531cc7d955c1e88837e5ea",
        "41" => "1bf10c1041c200ef8ced85300ebb25b5",
        "42" => "e10bdefd8c215d429f5a8a4559c9fe58",
        "43" => "87ca992923d167a273e89c357e530aed",
        "44" => "2949296af9f6dcfd5762327338ebb2e6",
        "45" => "51ead2e3602df8f03ae41568e93d5c06",
        "46" => "cf38d47ae6bfaad40e6ff9d3a72c31b7",
        "47" => "748e3069dfa76af8ed2f483bb92ea0f4",
        "48" => "f2fc9fda5ba50ec797890beb3847238f",
        "49" => "35ba34bf50504247d468bb08fa71b40d",
        "50" => "351726b36f8d30c49d689019e971f8d5",
        "51" => "cababace4c27ada5b7195b9cfaf3cf4c",
        "52" => "ebbc3adb201dfc9377a4ea208eba25ba",
        "53" => "6352ef314727a9a49cdc87e40b382677",
        "54" => "8966f920da5493871ccd7577db412e73",
        "55" => "2e86693d8cdca2b6390e862f8f9c214a",
        "56" => "bf62322fc11eb63b13fb364b7558f00b",
        "57" => "133ad3ddc84e17ed730d61f2108cb5c2",
        "58" => "2051149a8576f535945e23555f01d2d5",
        "59" => "7f7dd361f0c3fcf8592e0b50bcf31335",
        "60" => "5602082d8d339cee58783034e9b7faad",
        "61" => "c3e0183e9c3849a897f76d843319ca0b",
        "62" => "ad3273685fa8a446a442419b247378d6",
        "63" => "743f063788861728c7e3b29b01b3861c",
        "64" => "17b9eadd0a31dbf0fbf7d2c9d5bc2b3b",
        "65" => "52f335d66a28a5050143cdcd8c0f544e",
        "66" => "88448fb63e44e00557c4cdf85c54b3c0",
        "67" => "c6c83221c08d5ba7050213226e58e109",
        "68" => "6d51f5f61279ab7814f6aed73e0450b3",
        "69" => "244d95ac06d362afa637bf7d56a015f5",
        "70" => "8edc1ee68eb912f403e4f044d6630c49",
        "71" => "4c31b954aaa5ccff2a2beb9431623c3e",
        "72" => "8faa3e493ccebdc078b982ddd7a01d20",
        "73" => "27c611db9a4242a8a51562a8844a9e98",
        "74" => "94ee89f9ad2ae75e184a625a4aaa3066",
        "75" => "b2e18bde35433f8da4ec5bf524ad0692",
        "76" => "70f838d9b31a3b90636b3c6a6a9f52ef",
        "77" => "47716fc9e9ce1381859b5457f7f2f1eb",
        "78" => "93359a14fe077d49db706fb5f0f703ba",
        "79" => "31b30b04d3c97e3f580e5a5804ba9a10",
        "80" => "175570f0f15996b727ed5bd22066b67d",
        "81" => "e7c5b336a5987b63fa8f4fa2750cc1c1",
        "82" => "c00b255fd552e080bdeb7cc686f72d63",
        "83" => "58c51cf571c422a8084e8e7adb015e87",
        "84" => "4fd5e52dcad89d8257cb43f02ac7eac6",
        "85" => "5675f0b9b1c1b00551fdd8a975e0d1fe",
        "86" => "15eff27e3713649a2435cfc6979c1165",
        "87" => "e5709ef71ef3ceba79128175c1ba1ae6",
        "88" => "621ebd0a9d2645776b3e85637b139bca",
        "89" => "4c9c6e4b0a3391cfcadd150b934d9de7",
        "90" => "a1b678c015db6b46e862f544f2d25d9a",
        "91" => "13971b09f82c240ba1dad9b3008ea8dc",
        "92" => "3a3fa967a4c78b61d42f2c0849072ee4",
        "93" => "5c50d50829c7f4cebb56f87c023045da",
        "94" => "695d6346826764d87aa584af0fd6ae2d",
        "95" => "2a0fa8cf1b716bbe3ad4cc54e2c57008",
        "96" => "ed48ac7bbd19339eed8f64765e411d8b",
        "97" => "eaf3a42b175b37e71e3938467efd1364",
        "98" => "4bef874b98ab7d98c3a987a7d7888dfa",
        "99" => "3bd43e4b28686bd00ddf315a118f21d0",
        "100" => "21d808a1a75f3beac8cdb4687fadaf77",
        "101" => "992085b473b89b0986f4ba1575455517",
        "102" => "a5aa999161591bcedb718c5849875887",
        "103" => "f840bfecf28d895d4af2839e81daf14a",
        "104" => "49fcd050fe904ccb68c7cf31915aed9a",
        "105" => "fa7c32d36e46c7212d4dc09baf66ba9a",
        "106" => "bc990fdba3702f1c8cd1faa8132eb2c8",
        "107" => "bc4e8a5c49877b45148d362b20f241cd",
        "108" => "23f7fb6dd2727db47812c48ecb249e7c",
        "109" => "0f3999fe03f161ad28a3bff82a66b115",
        "110" => "64d03a57051a806f4340ee5d3f1a09d0",
        "111" => "d6caa0ce09268ca50e52952411e5ce00",
        "112" => "b98da9c24541a55d07c82f0931aedca3",
        "113" => "08896fbf73f9d5e738a830912221d148",
        "114" => "6f8b5abd0f6a7050f9b2818182025bcb",
        "115" => "eda65c095cc33a08267d77db4da8ec24",
        "116" => "a2f536de09b35784d92f2c86777a9438",
        "117" => "ada645029e1d1e26e9c17a5cbdfbbc19",
        "118" => "38dd7ba07750aa680d61c7ab15b86ebd",
        "119" => "b55d7a9a1e00fd05165cbec779929b3b"
    );

    public static function calculate_secret($timestamp)
    {
        $index = (77 + $timestamp) % 100;
        return isset(self::SECRET_MAP[(string)$index]) ? self::SECRET_MAP[(string)$index] : self::SECRET_MAP["0"];
    }

    public static function md5_hash($text)
    {
        return md5($text);
    }

    public static function generate_sign($params_dict)
    {
        $sorted_keys = array_keys($params_dict);
        sort($sorted_keys);

        $param_parts = array();
        foreach ($sorted_keys as $key) {
            if ($key == 'sign') continue;

            $value = (string)$params_dict[$key];
            if (empty($value) || $value == 'null') continue;
            if (($value[0] == '{' && substr($value, -1) == '}') ||
                ($value[0] == '[' && substr($value, -1) == ']')
            ) continue;

            $param_parts[] = $key . "=" . $value;
        }

        $sign_string = implode('&', $param_parts) . self::SECRET_KEY;
        return self::md5_hash($sign_string);
    }

    public static function generate_header_sign($path, $timestamp)
    {
        $secret = self::calculate_secret($timestamp);
        // echo $secret;
        $params = array(
            "userId" => "0",
            "channelId" => "199999",
            "time" => (string)$timestamp,
            "path" => $path,
            "secret" => $secret
        );

        ksort($params);
        $sign_string = implode('&', array_map(function ($k, $v) {
            return $k . "=" . $v;
        }, array_keys($params), $params));

        $sign = self::md5_hash($sign_string);
        return array($sign, $secret);
    }
}


function md5AndAESEncryptParams($data)
{
    $key = 'UITN25LMUQC436IM';
    if (empty($data)) return false;
    $blockSize = 16;
    $dataLength = strlen($data);
    $paddingLength = $blockSize - ($dataLength % $blockSize);
    $dataPadded = $data . str_repeat(chr($paddingLength), $paddingLength);
    $encryptStr = openssl_encrypt(
        $dataPadded, // 传入补全后的数据
        'AES-128-ECB', // 保持和原解密一致的算法
        $key, // 保持相同密钥
        OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, // 保持和原解密一致的选项
        '' // ECB 模式不需要初始化向量（IV），传空字符串即可
    );
    return base64_encode($encryptStr);
}

// 修正函数名：ASE → AES
function md5AndAESDecryptParams($encryptedData)
{
    $key = 'UITN25LMUQC436IM';
    $encrypted = base64_decode($encryptedData);
    if (empty($encrypted)) return false;

    $decryptStr = openssl_decrypt(
        $encrypted,
        'AES-128-ECB',
        $key,
        OPENSSL_RAW_DATA | OPENSSL_NO_PADDING
    );

    // 去除PKCS7补位
    $paddingLength = ord(substr($decryptStr, -1));
    $decryptStr = substr($decryptStr, 0, -$paddingLength);
    return $decryptStr;
}

function get_live_streams($id)
{
    $url = "https://bp-api.bestv.cn/cms/api/live/channels/v3";
    $timestamp = round(microtime(true) * 1000);

    // 构造请求体原始数据
    $payload = array(
        "channelid" => "199999",
        "devid" => "1899999",
        "time" => date("YmdHis"),
    );
    $payload["sign"] = SignatureGenerator::generate_sign($payload);
    $encryptedPayload = md5AndAESEncryptParams(json_encode($payload));
    $st = json_encode(array(
        "params" => $encryptedPayload
    ));
    list($header_sign) = SignatureGenerator::generate_header_sign("/cms/api/live/channels/v3", $timestamp);

    // 构造请求头（修正 content-type 匹配请求体，若接口要求 JSON 需调整 $st 格式）
    $headers = array(
        'user-agent: bestv app android 5007 xiaomi',
        // 若接口接收加密字符串（非 JSON），建议改为 application/x-www-form-urlencoded 或 text/plain
        'content-type: application/json',
        'time:' . $timestamp,
        'sign:' . $header_sign,
        'channelId: 199999',
        'userId: 0'
    );

    // 初始化 curl
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => true, // 明确开启 POST 请求（核心）
        CURLOPT_POSTFIELDS => $st, // 传入 POST 请求体数据
        CURLOPT_RETURNTRANSFER => true, // 不直接输出，返回结果给变量
        CURLOPT_TIMEOUT => 10, // 请求超时时间
        CURLOPT_SSL_VERIFYPEER => false, // 关闭 SSL 证书验证（仅测试环境使用，生产环境建议开启）
        CURLOPT_FOLLOWLOCATION => true, // 跟随重定向
        CURLOPT_FAILONERROR => true, // 遇到 HTTP 错误时终止请求
    ));

    // 执行 curl 请求并捕获异常
    $resp = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch); // 获取 curl 请求过程中的错误信息

    // 关闭 curl 资源
    curl_close($ch);

    $response = md5AndAESDecryptParams($resp);

    // 1. 排查 curl 请求错误
    if ($curl_error) {
        error_log("CURL 请求失败: " . $curl_error);
        return null;
    }

    // // 2. 排查 HTTP 状态码错误（不再提前 exit，保证逻辑执行）
    // if ($http_code !== 200 || empty($response)) {
    //     error_log("API请求失败: HTTP " . $http_code . "，返回结果：" . $response);
    //     return null;
    // }

    // 3. 解析 JSON 响应数据
    $response_data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON解析错误: " . json_last_error_msg() . "，原始响应：" . $response);
        return null;
    }

    // echo $response;
    // exit;

    // 4. 查找目标频道并返回播放地址
    $channel_list = isset($response_data['dt']) ? $response_data['dt'] : array();
    foreach ($channel_list as $channel) {
        if (isset($channel['id']) && $channel['id'] == $id) {
            // 修复：使用传入的 $id 替换硬编码的 199999（如需保留原逻辑可改回）
            //  $channel_url = $channel['channelUrl'] 
            // $channel_url = $channel['channelUrl'] . "&time=" . $timestamp .
            //     "&action=TP&channelIdNew=" . $id . "&userId=0&sign=" . $header_sign;
            return $channel['channelUrl'];
        }
    }

    // 5. 未找到目标频道
    error_log("未找到ID为 " . $id . " 的频道");
    return null;
}

$channels_data = array(
    array("num" => 1, "channelName" => "东方卫视", "id" => 2030),
    array("num" => 2, "channelName" => "新闻综合", "id" => 20),
    array("num" => 3, "channelName" => "都市频道", "id" => 18),
    array("num" => 4, "channelName" => "新纪实", "id" => 1600),
    array("num" => 5, "channelName" => "五星体育", "id" => 1605),
    array("num" => 6, "channelName" => "魔都眼", "id" => 1601),
    array("num" => 7, "channelName" => "爱上海", "id" => 2029),
    array("num" => 8, "channelName" => "第一财经", "id" => 21)
);

// 处理请求
if (!isset($_GET['id'])) {
    // 无id参数，显示频道列表
    header('Content-Type: text/plain; charset=utf-8');
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
        "://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

    foreach ($channels_data as $channel) {
        echo $channel['channelName'] . "," . $current_url . "?id=" . $channel['id'] . "\n";
    }
} else {
    // 有id参数，获取直播流并跳转
    $id = intval($_GET['id']);
    $channel_url = get_live_streams($id);

    if ($channel_url) {
        header("Location: " . $channel_url, true, 302);
        exit();
    } else {
        header("HTTP/1.0 404 Not Found");
        header('Content-Type: text/plain; charset=utf-8');
        echo "错误：无法获取频道流地址，请稍后重试";
    }
}
