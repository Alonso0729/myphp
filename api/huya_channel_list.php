<?php
//虎牙一起看实时 id 列表 2025.11.05

error_reporting(0);
header('Content-Type: application/x-javascript; charset=utf-8');
date_default_timezone_set("Asia/Shanghai");
$php = $_GET['url']; 

define('LF', "\n");
$cache_dir = __DIR__ . '/cache';
if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);
$cache_file = $cache_dir . '/huyaList.tmp';
$cache_ttl = 8; // 缓存时间（分钟）

if (empty($php)){
    $php = ($_SERVER['HTTPS']==='on'?"https":"http")."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $php = preg_replace('/\/index\.php$/i', '', $php);
}else{
    if (!preg_match('/^\s*https?:\/\//i', $php)) $php = "http://".trim($php);
}

if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_ttl*60)) {
    $data = file_get_contents($cache_file);
    $data = preg_replace('/,.*?:\/\/.*?\?/ui', ",$php?", $data);
    die($data);
}

// 需要过滤的房间名称（直接在此添加/删除过滤项）
$filterNames = [
    '欢迎来到我的直播间',
    '我是一颗小虎牙'
];

// 替换规则：键为目标名称，值为需要匹配的关键词列表（新增规则直接加一行）
$replaceRules = [
    '周星驰' => ['星爷', '周星驰', '周星星'],
    '林正英' => ['英叔', '林正英'],
    '王晶' => ['王晶'],
    '周润发' => ['发哥', '周润发'],
    '刘德华' => ['华仔', '刘德华'],
    '成龙' => ['成龙'],
    '梁家辉' => ['梁家辉'],
    '007电影' => ['邦德'],
    '洪金宝·' => ['洪金宝·'],
];

// API分类列表（需要调整分类或链接时直接修改此处）
$apis = [
    1 => [2067, 2213, "虎牙.电影"],     
    2 => [2079, 2227, "虎牙.电视剧"], 
    3 => [6871, 6767, "虎牙.最新"], 
    4 => [6879, 6775, "虎牙.up主"], 
    5 => [1011, 1137, "虎牙.综艺s"], 
    6 => [6861, 6761, "虎牙.动漫"],  
];

// 启用输出缓冲，优化输出处理
ob_start();

echo "-=☆☆☆☆☆ 更新于 ".date('Y-m-d H:i:s')." ◎ 间隔 $cache_ttl 分钟更新 ☆☆☆☆☆=-".LF.LF.
     "虎牙一起看,#group#".LF.LF;
//foreach ($apis as $genre => $url) {
foreach ($apis as $key => $ids) {
    $i=0;
    $out = '';
    do{
        $i++;
        $url = "https://live.huya.com/liveHttpUI/getTmpLiveList?iTmpId={$ids[0]}&iPageNo={$i}&iPageSize=120&iLibId={$ids[1]}&iGid=2135";
        // 初始化curl并设置参数
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true, // 结果返回而非直接输出
            CURLOPT_TIMEOUT => 10, // 超时时间10秒
            CURLOPT_SSL_VERIFYPEER => false, // 跳过SSL证书验证（视环境调整）
            CURLOPT_SSL_VERIFYHOST => false
        ]);
       
        // 执行请求并获取响应
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch); // 关闭curl资源
       
        // 跳过请求失败的情况
        if ($error || !$response) {
            continue;
        }
       
        // 解析JSON响应
        $data = json_decode($response, true);
        // 跳过解析失败或无数据的情况
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['vList'])) {
            continue;
        }
       
        // 输出分类标题（格式：分类名,#genre#）
        if ($i==1) echo "{$ids[2]},#genre#" . LF.LF;
       
        // 遍历每个房间数据
        foreach ($data['vList'] as $item) {
            // 确保必要字段存在
            if (isset($item['sIntroduction'], $item['lProfileRoom'])) {
                $roomName = $item['sIntroduction']; // 原始房间名
                $profileRoom = (string)$item['lProfileRoom']; // 房间ID
                
                // 根据替换规则处理房间名
                $roomNameR = '';
                foreach ($replaceRules as $target => $keywords) {
                    // 生成正则模式（匹配任意关键词，忽略大小写）
                    $pattern = '/(' . implode('|', array_map('preg_quote', $keywords)) . ')/i';
                    // 匹配到则替换为目标名称，并跳出循环（避免重复替换）
                    if (preg_match($pattern, $roomName)) {
                        //$roomName = $target;
                        $roomNameR = $target;
                        break;
                    }
                }
                
                // 过滤不需要的房间名
                if (in_array($roomName, $filterNames)) {
                    continue;
                }
                
                // 生成房间链接并输出（格式：房间名,链接）
                $link = "$php?id=$profileRoom";
                //echo "$roomName,$link" . LF;
                if (!empty($roomNameR)) $out = "$roomNameR,$link".LF. $out;
                else $out .= "$roomName,$link" . LF;
            }
        }
       
        // 分类间用空行分隔
        //echo LF;   
        $out .= LF; 
    }while (!empty($data['vList']) && $data['iTotal']!=0 && $i<5);
    echo $out;
}

// 处理输出：去除末尾多余换行后再补一个
$output = ob_get_clean();
file_put_contents($cache_file, $output);
echo trim($output, LF) . LF;
?>