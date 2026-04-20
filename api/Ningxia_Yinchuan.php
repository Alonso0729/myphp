<?php

/*
银川公共
nxyctv.php?id=0
银川生活
nxyctv.php?id=1
银川文体
nxyctv.php?id=2
*/

$liveid = $_GET['id'] ?? "0";
$url = "https://www.ycen.com.cn/";
$content = getContent($url);
$result = smartSubstrOffset($content,"source".$liveid,14,"\"  type",0);
//$result = str_replace("\\","",$result);
//echo $result;
header("Location: ".$result);
exit;

function getContent($url) {
   
    // 初始化cURL会话
    $ch = curl_init();
       
        // 基础请求头
    $baseHeaders = [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8',
        'Connection: keep-alive'
    ];
   
    // 设置cURL选项
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,      // 将响应作为字符串返回
        CURLOPT_FOLLOWLOCATION => true,      // 跟随重定向
        CURLOPT_MAXREDIRS => 10,              // 最大重定向次数
                CURLOPT_AUTOREFERER => true,       // 自动设置Referer
        CURLOPT_TIMEOUT => 60,               // 超时时间（秒）
        CURLOPT_CONNECTTIMEOUT => 15,        // 连接超时时间
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        CURLOPT_SSL_VERIFYPEER => false,     // 不验证SSL证书（仅测试环境使用）
        CURLOPT_SSL_VERIFYHOST => 0,         // 不验证SSL主机名
        CURLOPT_ENCODING => '',              // 接受所有编码
        CURLOPT_HEADER => false
    ];
   
    curl_setopt_array($ch, $options);
   
    // 执行cURL请求
    $response = curl_exec($ch);
   
    // 检查是否发生错误
    if ($response === false) {
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        //curl_close($ch);
        return "cURL错误 #{$errno}: {$error}";
    }
   
    // 获取HTTP状态码
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //curl_close($ch);
   
    if ($httpCode !== 200) {
        return "HTTP错误: 状态码 {$httpCode}";
    }
   
    if (empty($response)) {
        return "错误: 获取到的内容为空";
    }
   
    return $response;
}

function smartSubstrOffset($str, $startStr, $startOffset = 0, $endStr = '', $endOffset = 0) {
    if (empty($str)) {
        return '';
    }
   
    $strLength = strlen($str);
   
    // ========== 处理起始位置 ==========
    if (empty($startStr)) {
        // 没有起始字符串，从偏移量开始
        $startPos = $startOffset;
        if ($startPos < 0) {
            $startPos = 0;
        } elseif ($startPos > $strLength) {
            $startPos = $strLength;
        }
    } else {
        // 查找起始字符串的位置
        $startPos = strpos($str, $startStr);
        if ($startPos === false) {
            return '';
        }
        
        // 应用起始偏移量
        $startPos += $startOffset;
        
        // 边界检查
        if ($startPos < 0) {
            $startPos = 0;
        } elseif ($startPos > $strLength) {
            $startPos = $strLength;
        }
    }
   
    // ========== 处理终止位置 ==========
    if (empty($endStr)) {
        // 没有终止字符串，使用起始位置 + 终止偏移量
        $endPos = $startPos + $endOffset;
    } else {
        // 从起始位置开始查找终止字符串
        $endPos = strpos($str, $endStr, $startPos);
        if ($endPos === false) {
            // 终止字符串未找到，截取到字符串末尾
            $endPos = $strLength;
        } else {
            // 应用终止偏移量
            $endPos += $endOffset;
        }
    }
   
    // 边界检查
    if ($endPos < $startPos) {
        $endPos = $startPos;
    } elseif ($endPos > $strLength) {
        $endPos = $strLength;
    }
   
    // ========== 返回结果 ==========
    return substr($str, $startPos, $endPos - $startPos);
}


?>