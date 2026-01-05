<?php

// 模拟 ku9.request 函数，使用 cURL 发送 HTTP 请求
function ku9_request($url, $method = "GET", $headers = [], $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回响应内容而不直接输出
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // 遵循重定向

    $request_headers = [];
    foreach ($headers as $key => $value) {
        $request_headers[] = "$key: $value";
    }
    if (!empty($request_headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    }

    if ($method === "POST") {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }

    $response_body = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'body' => $response_body,
        'status_code' => $http_code
    ];
}


// PHP版本的 find_channel_url 函数 (与之前相同)
function find_channel_url($js_content, $channel_id) {
    // Attempt to parse the 'tvdata' JSON block
    if (preg_match('/var tvdata\s*=\s*(\[\s*\{.*?\}\s*\])/is', $js_content, $json_match)) {
        try {
            $json_str = $json_match[1];
            $json_str = preg_replace("/(\w+):/i", '"$1":', $json_str);
            $json_str = str_replace("'", '"', $json_str);
            $json_str = preg_replace('/,\s*\}/', '}', $json_str);
            $json_str = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json_str);

            $tvdata = json_decode($json_str, true);

            if (is_array($tvdata)) {
                foreach ($tvdata as $group) {
                    if (isset($group['tvlist']) && is_array($group['tvlist'])) {
                        foreach ($group['tvlist'] as $channel) {
                            if (isset($channel['id']) && (int)$channel['id'] === (int)$channel_id && isset($channel['vurl'])) {
                                return $channel['vurl'];
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Error handling for JSON parsing
        }
    }

    // Fallback: Regex for id and vurl
    if (preg_match_all('/\{\s*id:\s*(\d+).*?vurl:\s*["\'](.*?)["\']/is', $js_content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            if ((int)$match[1] === (int)$channel_id) {
                return $match[2];
            }
        }
    }

    return '';
}

// PHP版本的 main 函数 (修改了 ku9.request 的调用)
function main($item) {
    $channel_map = [
        // ========== 中央频道 ==========
        'cctv1' => 1,    // CCTV-1 综合
        'cctv2' => 2,    // CCTV-2 财经
        'cctv3' => 3,    // CCTV-3 综艺
        'cctv4' => 4,    // CCTV-4 国际
        'cctv5' => 5,    // CCTV-5 体育
        'cctv6' => 6,    // CCTV-6 电影
        'cctv7' => 7,    // CCTV-7 国防军事
        'cctv8' => 8,    // CCTV-8 电视剧
        'cctv9' => 9,    // CCTV-9 记录
        'cctv10' => 10,  // CCTV-10 科教
        'cctv13' => 13,  // CCTV-13 新闻
        'cctv16' => 16,  // CCTV-16 奥林匹克
        'cctv5p' => 17,  // CCTV-5+ 体育赛事

        // ========== 地方卫视 ==========
        'bjws' => 18,    // 北京卫视
        'ahws' => 19,    // 安徽卫视
        'dnws' => 20,    // 东南卫视
        'gsws' => 21,    // 甘肃卫视
        'gdws' => 22,    // 广东卫视
        'gxws' => 23,    // 广西卫视
        'gzws' => 24,    // 贵州卫视
        'hnws' => 25,    // 海南卫视
        'hbws' => 26,    // 河北卫视
        'hnws2' => 27,   // 河南卫视
        'hljws' => 28,   // 黑龙江卫视
        'hubws' => 29,   // 湖北卫视
        'hunws' => 30,   // 湖南卫视
        'jlws' => 31,    // 吉林卫视
        'jsws' => 32,    // 江苏卫视
        'jxws' => 33,    // 江西卫视
        'lnws' => 34,    // 辽宁卫视
        'qhws' => 35,    // 青海卫视
        'sdws' => 36,    // 山东卫视
        'szws' => 37,    // 深圳卫视
        'scws' => 38,    // 四川卫视
        'tjws' => 39,    // 天津卫视
        'ynws' => 40,    // 云南卫视
        'zjws' => 41,    // 浙江卫视
        'cqws' => 42,    // 重庆卫视
        'dfws' => 43,    // 东方卫视

        // ========== 影视频道 ==========
        'khdy1' => 131,  // 科幻电影①
        'khdy2' => 132,  // 科幻电影②
        'khdy3' => 133,  // 科幻电影③
        'gpdy1' => 134,  // 港片电影①
        'gpdy2' => 135,  // 港片电影②
        'gpdy3' => 136,  // 港片电影③
        'cjdy' => 138,   // 超级英雄
        'mrxl' => 139,   // 末日系列
        'mwdy' => 140,   // 漫威电影
        'jxdz' => 141,   // 精选动作
        'jsdy' => 142,   // 惊悚电影
        'gwdz' => 143,   // 国外动作
        'gnxj' => 144,   // 国内喜剧
        'gndz' => 145,   // 国内动作
        'zxc' => 231,    // 周星驰电影
        'lzy' => 233,    // 林正英电影
        'wj' => 235,     // 王晶港风电影

        // ========== 综艺小品 ==========
        'stml' => 57,    // 沈腾马丽经典小品
        'sb' => 53,      // 宋小宝经典小品
        'jl' => 54,      // 贾玲经典小品
        'jdjp' => 55,    // 经典小品下饭必备
        'zbs' => 56,     // 赵本山经典小品
        // ========== 来自454833436禁止非法传播后果自负 ==========
    ];

    $channel_name = isset($item['id']) ? strtolower(trim($item['id'])) : '';

    if (empty($channel_name)) {
        return ['error' => '请指定频道名称，如: ?id=cctv1 或 ?id=zxc'];
    }

    if (!isset($channel_map[$channel_name])) {
        return ['error' => '未知频道名称'];
    }

    $channel_id = $channel_map[$channel_name];
    $js_url = 'https://zxbv5123.xymjzxyey.com/assets/js/tv.js';

    // 调用模拟的 ku9_request 函数
    $res = ku9_request($js_url, "GET", [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ]);

    if (!isset($res['body']) || $res['body'] === false) {
        return ['error' => '无法获取最新的频道数据'];
    }
    $js_content = $res['body'];

    $vurl = find_channel_url($js_content, $channel_id);

    if (empty($vurl)) {
        return ['error' => '未找到该频道的直播源'];
    }

    return ['url' => $vurl];
}

// 处理 HTTP 请求
if (isset($_GET['id'])) {
    $item = ['id' => $_GET['id']];
    $result = main($item);

    if (isset($result['url'])) {
        header('Location: ' . $result['url'], true, 302); // 302 重定向
        exit;
    } else {
        // 如果出错，返回错误信息
        header('Content-Type: text/plain; charset=utf-8');
        echo "错误: " . ($result['error'] ?? '未知错误');
        exit;
    }
} else {
    // 如果没有提供 id 参数，返回使用说明
    header('Content-Type: text/plain; charset=utf-8');
    echo "请提供频道ID。用法: http://您的服务器域名/bzb.php?id=cctv1\n";
    echo "可用频道ID列表:\n";
    echo "中央频道: cctv1, cctv2, ..., cctv16, cctv5p\n";
    echo "地方卫视: bjws, ahws, ..., dfws\n";
    echo "影视频道: khdy1, gpdy1, ..., zxc, lzy, wj\n";
    echo "综艺小品: stml, sb, jl, jdjp, zbs\n";
}

?>