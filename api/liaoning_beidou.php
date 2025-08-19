<?php
//代理了切片！
//国外服务器有的行，有的不行。
//当前若无直播，则回放最近的。
//当前若无直播，且无节目可回放，则播放失败。
//缓存文件夹lnbdy_cache会自动创建，若无权限则手工创建。
if (need_m3u8($id, $ts_url, $r)) {
    if (get_m3u8_url_and_referrer($id, $u, $r)) {
        $c = get_content(true, $id, $u, $r, $ct, $code);
        if ($code == 200)
            $c = replace_ts_urls($id, $r, $u, $c);
        echo_content($ct, $c, $code);
    }
} else {
    $c = get_content(false, $id, $ts_url, $r, $ct, $code);
    echo_content($ct, $c, $code);
}



function get_channel($id) {
    $d = [
        //北斗融媒
        'lnws' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', 'c077b260424404846285cba1e1759280', 'NDEyNjcxNzg1NzY2MDc3490E578144B71624745B39DD0DC0D1AC'], //辽宁卫视
        'lnds' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', '10d3de0d03c62e85a1a281bbde8b6952', 'ODI0NTYxNzg1NzY2MDc3F0B1FD729E76A90CA24FAAE02357F59D'], //辽宁都市
        'lnys' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', '918510749a0f319ec12ff695b1c95230', 'MDI0NjkxNzg1NzY2MDc35199C51477AAD1DF17E160A2EB30213B'], //辽宁影视
        'lnjy' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', '854e7044de9fef5163ae36fabb72de56', 'NTI0NTkxNzg1NzY2MDc38F221A56ADA818918271C956FBA074EE'], //辽宁教育青少
        'lnsh' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', '078ce87dcf5384d51e4655cb962fda18', 'MzI0NjkxNzg1NzY2MDc3A6C5F5DC5AAD1598E6E413EB4C226089'], //辽宁生活
        'lnty' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', 'e0bb9a7fd9afa954658bc50d0681cd49', 'NDIzNjcxNzg1NzY2MDc326E12A5421D7907E1675C0BB8EB3E96C'], //辽宁体育
        'lnbf' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', '8e95535378bd3e5f7494bc23ab1cb117', 'NjE0NjcxNzg1NzY2MDc3EA435FC58A7F614BAE16828C0AADDB97'], //辽宁北方
        'lnyj' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', 'c577efa61117f1ee9687592aa1fd49e8', 'OTI0NTcxNzg1NzY2MDc381F5CC52B2DED40EAE6156756891C92C'], //辽宁宜佳购物
        'lnxdm' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', '7ff8ce0d226f2eb92e332be0cb13b406', 'MDE0NTkxNzg1NzY2MDc305F5C582D565E4C0A3722551C3C591BB'], //辽宁新动漫
        'lnjtlc' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', '7e29bde4f41ca08642b7fc3ce4eb1ae4', 'ODEyMzUxNzg1NzY2MDc3D85948DEB26600EB95049D334A9550A7'], //辽宁家庭理财
        'lnyd' => ['bdrm', 'd91158f1dc87e3b10ee20cf9fbd36390', 'fb3cf5af7cd3bcbde56c280cad2e64cb', 'ODE0NTcxNzg1NzY2MDc3E6EDF6FAA3A2C8E669D8427660736FA2'], //辽宁移动电视
        //抚顺
        'fszh' => ['fsgbdst', '586ae47ad6f2db5866babc99cf8d3617', 'b2326b3d482e30a9d95d63e09fc3f460', 'NjE0NTYxNzg1NzY2MDc3F9358EB2E403FBC9E62F8E46CCA62D57'], //抚顺综合
        'fsjy' => ['fsgbdst', '586ae47ad6f2db5866babc99cf8d3617', 'c0d75ae269a96b4e8a8f67fd1b4a316e', 'NTE0NjkxNzg1NzY2MDc344718B3A053EF59ADCAAF54BA54ECCBA'], //抚顺教育
        'qyzh' => ['qingyuan', '0b83732a7b69fbd61caad074a4d8de08', '907b5c520cb34f92ac0997542431076f', 'NzIzNDUxNzg1NzY2MDc3B0CDF36D3C0B73EBF88F4B52049C5026'], //清原综合
        //铁岭
        'dbszh' => ['diaobingshan', '084e85c0775691975c6f5d87ff29c535', '06686835e532339822ba87250359062e', 'NDE0NTYxNzg1NzY2MDc37B6B5591736F197379363AB7EFB0144B'], //调兵山综合
        'ctzh' => ['changtu', '8c58ac19aacc3b4079768d870f8f9bc6', '77b08c94f5f06f2e0f81c34e048b88a5', 'MzE0NjkxNzg1NzY2MDc31A0F502874629CE2BC4385BF396ED6E3'], //昌图综合
        'xfzh' => ['xifeng', 'cdce0e6bf7f498d9ee50a7506699dca0', '1328caa0b1af409eb5df9e6219e372ca', 'MDE0NjcxNzg1NzY2MDc33705B6970357D97DC504452A48DAA35C'], //西丰综合
        'kyzh' => ['kaiyuan', '6305e714b752d5a3e99700a62bfc03bb', 'fb21b09f0ff234cf9bd875f28c6e292b', 'MDEzNTkxNzg1NzY2MDc3389E50D0FFD1A612AC5DFCC07433EBE1'], //开原综合
        //沈阳
        'fkxwzh' => ['faku', '201021a06e6577b49da709306e58da94', 'a3b23d35ddaa48b2a83857137b6fedac', 'NjEzNjkxNzg1NzY2MDc34AB60D4F26CFC950FFC23CD1355B2B93'], //法库新闻综合频道
        //朝阳
        'cyxdst' => ['chaoyang', '0c519f8ab44ab2342a80e909047c2729', 'ab980e1e8b434701a3fe30b47d500d3b', 'NjIzNDYxNzg1NzY2MDc39E4F4CDA88E2E4AEA43397D245711379'], //朝阳县广播电视台
        'bpxwzh' => ['beipiao', 'fd4201c5dd19d72ed944acf010b3913f', '5a229d456caa4cdba4e93e36bf20678a', 'MTIzNjcxNzg1NzY2MDc321E7A25C0BAE61D29AE1DC2B5BD00480'], //北票新闻综合频道
        'kzzh' => ['kazuo', '29d3fb5c2f279587d4d1cd76dca7055b', '1eabde0f69c94bdce63705656c922220', 'MjIzNjkxNzg1NzY2MDc34A6915247FE0AD832970501D00F3D8A9'], //喀左综合
        //阜新
        'fmhyzh' => ['fumeng', '7cfd84127e0e9491d47589a2c5f8d54a', '60c458b8acdd039e1884f63babb769ac', 'OTEyNTYxNzg1NzY2MDc31A63270EA1377F89E48C43C255413DE7'], //阜蒙汉语综合
        'zwzh' => ['zhangwu', 'a4c21afece4a6f3b22076713562dfb42', '0e0574ed6004404eb7742344ffff9851', 'MDE0NjcxNzg1NzY2MDc3D2D65CFB0F17079E5C42B314C7C64695'], //彰武综合
        //葫芦岛
        'xczh' => ['xingcheng', '0ccdf323fb5e0e67b978f7d5405aea09', '02f5583da51cec4a7aaf80e1d0734c88', 'MDIzNjcxNzg1NzY2MDc33B733E585D864608742EA1CF4C35DB22'], //兴城综合
        'szzh' => ['suizhong', '7434b92fc4eaff622bffbdecdc776a3f', '88c54422a27a82ac15a0cb62e1120335', 'MDI0NTgxNzg1NzY2MDc3981A6537EF948A1BCAB49E29A52BDCB8'], //绥中综合
        //大连
        'wfdxwzh' => ['wafangdian', '8f0774048509accaac5c495bf08145f2', '045011f3c0914ac1a96f3279a753c98c', 'NTE0NjcxNzg1NzY2MDc38782188ED1AB2AE2532333DCB4CF5E3F'], //瓦房店新闻综合频道
        'zhzh' => ['zhuanghe', 'e043ba0420ee7367ed8f58ba4ecc0080', '8891fc9a1d2753a6bfc86faf57e2912a', 'MDI0NjcxNzg1NzY2MDc3877904318E674F3EBA9832EA0FF8F4E6'], //庄河综合
        //丹东
        'dgxdg' => ['donggang', 'b8d29ac77a60922d079dcf608df440d8', '4afb2cf41d8242408b770e83b0c828b8', 'OTI0NTcxNzg1NzY2MDc350AC8979A3572E5BA25FC954F64976EE'], //东港新东港频道
        'kdzh' => ['kuandian', '5d677d377b5f9e4649430fd715dff056', 'b12940ec52807af72f86af4ad50234b9', 'MjE0NjcxNzg1NzY2MDc364537B85B6677C1D4944792FC9CE6E28'], //宽甸综合
        //辽阳
        'lyxwzh' => ['lys', '81c5990fafee3dbd9015b7a80f78bc38', '3ab65e5440d63a760a326fdc99fc2284', 'NjEyNjcxNzg1NzY2MDc30A195BC67848DA945E75B5FA988747B2'], //辽阳新闻综合
        'lyshsh' => ['lys', '81c5990fafee3dbd9015b7a80f78bc38', '1608db4a6543faf21a43262953cb3844', 'NDEzNjgxNzg1NzY2MDc3A317CB4F08D985FC6E0EA5A5FED388F6'], //辽阳社会生活
        //营口
        'ykxwzh' => ['yingkou', '019eb9aaf5c03f5fa47f1c861abe1bc1', '1b2dc76c9a4980391cf779302ca5a0c5', 'MjI0NTcxNzg1NzY2MDc30E5745DEC694F1CF65CFA3B8D32BAD02'], //营口新闻综合
        'yklh' => ['yingkou', '019eb9aaf5c03f5fa47f1c861abe1bc1', '70bdc7636d61e36fcabee748519d8734', 'NDI0NjcxNzg1NzY2MDc30256ACC81D5DAB68722CD9CCF601B661'], //营口辽河文化生活
    ];

    $r = new stdClass();
    $r->type = $d[$id][0];
    $r->groupId = $d[$id][1];
    $r->domainId = $d[$id][2];
    $r->sign = $d[$id][3];
    return $r;
}

function need_m3u8(&$id, &$ts_url, &$referrer)
{
    $id = isset($_GET['id']) ? $_GET['id'] : 'lnws';
    $ts_url = isset($_GET['ts']) ? $_GET['ts'] : null;
    $referrer = isset($_GET['ref']) ? $_GET['ref'] : null;
    return $ts_url === null;
}

function get_m3u8_url_and_referrer($id, &$m3u8_url, &$referrer, $force_from_intf = false)
{
    if (!$force_from_intf) {
        $r = load_from_cache($id, $m3u8_url, $referrer);
        if ($r)
            return $r;
    }

    $r = get_m3u8_url_and_referrer_from_intf($id, $m3u8_url, $referrer, $is_live);

    if ($is_live)
        save_to_cache($id, $m3u8_url, $referrer);
    else
        save_to_cache($id, '', $referrer);
    return $r;
}

function get_m3u8_url_and_referrer_from_intf($id, &$m3u8_url, &$referrer, &$is_live) {
    $c = get_channel($id);

    $is_live = get_is_live($c, $vod_url_without_auth);
    if ($is_live)
        $live_url_without_auth = get_live_url_without_auth($c);
    $u = $is_live ? $live_url_without_auth : $vod_url_without_auth;

    get_key_and_referrer($c, $k, $referrer);

    $m3u8_url = calc_m3u8_url($u, $k);
    return true;
}

function calc_m3u8_url($m3u8_url_without_auth, $key)
{
    $t = time() + 1800;
    $p = parse_url($m3u8_url_without_auth, PHP_URL_PATH);
    $md5 = md5("$p-$t-0-0-$key");
    $s = strpos($m3u8_url_without_auth, '?') === false ? '?' : '&';
    return $m3u8_url_without_auth.$s."auth_key=$t-0-0-$md5";
}

function get_key_and_referrer($channel, &$key, &$referrer)
{
    $u = "https://{$channel->type}.bdy.lnyun.com.cn/cloud/apis/live/api/domain/getOauth"
        ."?domainId={$channel->domainId}";
    $u .= "&version=3&sign={$channel->sign}";
    $c = send_request($u, null, '');
    $j = json_decode($c);
    if ($j->code != 200)
        die('getOauth failed: '.$c);
    $private_key =
        '-----BEGIN PRIVATE KEY-----'."\n".
        'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAOJqGLjxxzgHtkmT'."\n".
        'oON4WPgfhqmwrt13oMEFLIQKgdZa5/H916WzbBDkoSalSLQpOevGL3nKiU+ECZqL'."\n".
        '3QD+J0TYBttNup/qA+d9fU4njxRu8eXZ5LYwl7TzxMsTIyf89Xh7OCjP6pthxVql'."\n".
        '1tphX5GZrqMNZbOMiw/rK8Pg/PCBAgMBAAECgYAaAQN74lD2L3SROMJmvcDCJqTJ'."\n".
        'woAi8YVmBdkaBTbqTqCLG5Nz9Yp42jlj/eG+x2lemfGD9G4W0txjgqLMZWRO/Xp6'."\n".
        'WHs+7+L2IX1DfduhJcnFjGkv4WWgwgVUskasZhR41tMVqpTcgkzB+PELuw2NaJjY'."\n".
        'yiyR9FNw3xSQPes60QJBAP4qWbep+06AfIuOA66AiHe7axN53HKOQTzjw6Cn4yRs'."\n".
        'J45L+F7YpMtAzcabolNnFpJCvBKiO6MpfW0WvHhAQH0CQQDkDHewvlKI4PsrgHM2'."\n".
        'u0D90EymbBCmaoOKmARu//TcUOURHOBKE2N8QJZWVjwZN/aV4mrFY2IdO/vFwydc'."\n".
        '61NVAkEAj67vYzXz/NAEGHyjNi4xd8Z65Nq6NgSXes2j1Rmz/e4qenYWJcBBgSnU'."\n".
        'apenL5ESoIKbgck2/6k/38C/sRdZnQJAQimz2B1/yKKtfJOJ2ck+M+VpN6eGtSGW'."\n".
        'BHHSZ3nvSrRVoT9le1hgtr3uYCIo0ZBBBH9qRtZsstqiU2ApXXYQ+QJABGF7U0R5'."\n".
        'W81CPtN1qTt+cU4pDsvIIhvv35BM2Nk15Cj62HPkLQmwvZK1F+7n4gDQXLWqekoM'."\n".
        'Rk87yN4rDV9Waw=='."\n".
        '-----END PRIVATE KEY-----';
    openssl_private_decrypt(base64_decode($j->msg),$key, $private_key);
    openssl_private_decrypt(base64_decode(json_decode($j->data)->refer),$referrer, $private_key);
    if (stripos($referrer, "http") !== 0)
        $referrer = "http://".$referrer;
}

function get_live_url_without_auth($channel)
{
    $live_url_without_auth = '';
    $u = "https://{$channel->type}.bdy.lnyun.com.cn/cloud/apis/live/api/domain/getGroupDomain"
        ."?groupId={$channel->groupId}&shows=1";
    $c = send_request($u);
    $j = json_decode($c);
    if ($j->code != 200)
        die('getGroupDomain failed: '.$c);
    foreach ($j->data as $i) {
        if ($i->id == $channel->domainId) {
            $live_url_without_auth = json_decode($i->pull)->m3u8;
            break;
        }
    }
    return $live_url_without_auth;
}

function get_is_live($channel, &$vod_url_without_auth)
{
    $is_live = true;
    $vod_url_without_auth = '';
    $date = new DateTime('now', new DateTimeZone('Asia/Shanghai'));
    for ($day = 0; $day <= 1; $day++) {
        if ($day > 0)
            $date->sub(new DateInterval('P' . $day . 'D'));
        $times = $date->format('Y-m-d');
        $u = "https://{$channel->type}.bdy.lnyun.com.cn/cloud/apis/live/api/program/getNewProgram"
            ."?domainId={$channel->domainId}&times=$times";
        $c = send_request($u);
        $j = json_decode($c);
        if ($j->code != 200)
            die('getNewProgram failed: '.$c);
        if (count($j->data) > 0) {
            $epg = $j->data[0];
            $today = $day === 0;
            if ($today) {
                $t = $date->getTimestamp();
                for ($i = 0; $i < count($epg->startTimeStamp); $i++) {
                    $stopped = strpos($epg->name[$i], '停播') !== false;
                    if (!$stopped) {
                        $vod_url_without_auth = $epg->pullDomain[$i];
                    } else {
                        if ($epg->startTimeStamp[$i] <= $t && $t <= $epg->endTimeStamp[$i]) {
                            $is_live = false;
                            break;
                        }
                    }
                }
            } else {
                for ($i = count($epg->startTimeStamp) - 1; $i >= 0; $i--) {
                    $stopped = strpos($epg->name[$i], '停播') !== false;
                    if (!$stopped) {
                        $vod_url_without_auth = $epg->pullDomain[$i];
                        break;
                    }
                }
            }
            if (!$is_live && empty($vod_url_without_auth))
                continue;
            else
                break;
        } else
            break;
    }
    return $is_live;
}

function save_to_cache($id, $m3u8_url, $referrer)
{
    $a['m3u8_url'] = $m3u8_url;
    $a['referrer'] = $referrer;
    array_to_file($a, "lnbdy_cache/$id.txt");
}

function load_from_cache($id, &$m3u8_url, &$referrer)
{
    $cacheFile = "lnbdy_cache/$id.txt";
    $expireSeconds = 1800;
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expireSeconds)) {
        file_to_array($cacheFile, $a);
        $m3u8_url = $a['m3u8_url'];
        $referrer = $a['referrer'];
        return !empty($m3u8_url);
    } else {
        $m3u8_url = '';
        $referrer = '';
        return false;
    }
}

function file_to_array($filename, &$array) {
    $array = [];
    if (file_exists($filename)) {
        $handle = fopen($filename, 'r');
        if (flock($handle, LOCK_SH)) { // 共享锁，允许其他进程读但禁止写
            $data = file_get_contents($filename);
            $array = unserialize($data);
            flock($handle, LOCK_UN);
        }
        fclose($handle);
    }
    return true;
}

function array_to_file($array, $filename) {
    $dir = dirname($filename);
    if (!is_dir($dir) && is_writable(dirname($dir))) {
        if (!@mkdir($dir, 0755, true))
            return false;
    }
    $data = serialize($array);
    file_put_contents($filename, $data, LOCK_EX);
    return true;
}

function get_content($is_m3u8, $id, $url, $referrer,
                     &$response_content_type, &$response_http_code)
{
    load_from_cache($id, $url_cache, $referrer_cache);
    if ($referrer != $referrer_cache && !empty($referrer_cache))
        $referrer = $referrer_cache;

    $r = send_request($url, $referrer, null, $response_content_type, $response_http_code);
    if ($response_http_code == 403) {
        $b = get_m3u8_url_and_referrer($id, $new_m3u8_url, $new_referrer, true);
        if ($b) {
            if ($is_m3u8) {
                if ($new_m3u8_url != $url || $new_referrer != $referrer)
                    $r = send_request($new_m3u8_url, $new_referrer, null, $response_content_type, $response_http_code);
            } else {
                if ($new_referrer != $referrer)
                    $r = send_request($url, $new_referrer, null, $response_content_type, $response_http_code);
            }
        }
    }
    return $r;
}

function send_request($url, $referrer = null, $post_data = null,
                      &$response_content_type = null, &$response_http_code = null)
{
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER  => false,
    );
    if ($referrer !== null)
        $options[CURLOPT_REFERER] = $referrer;
    if ($post_data !== null) {
        $options[CURLOPT_POST] = true;
        if (!empty($post_data))
            $options[CURLOPT_POSTFIELDS] = $post_data;
    }
    curl_setopt_array($ch, $options);
    $res = curl_exec($ch);
    if (func_num_args() > 3)
        $response_content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    if (func_num_args() > 4)
        $response_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $res;
}

function replace_ts_urls($id, $referrer, $m3u8_url, $m3u8_content)
{
    $dest_ts_path = dirname($m3u8_url)."/";
    $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    $self_part = "$protocol://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($self_part, $dest_ts_path, $id, $referrer) {
            if (!is_absolute_url($matches[1]))
                $ts = $dest_ts_path.$matches[1];
            else
                $ts = $matches[1];
            $ts = rawurlencode($ts);
            return "$self_part?id=$id&ref=$referrer&ts=$ts";
        },
        $m3u8_content
    );
}

function is_absolute_url($url) {
    return stripos($url, 'http:') === 0 || stripos($url, 'https:') === 0;
}

function echo_content($content_type, $content, $http_code)
{
    http_response_code($http_code);
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: $content_type");
    echo $content;
}