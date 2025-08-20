<?php
// TV频道代理脚本
// 使用方法: your-domain.com/proxy.php?id=0 (或 1, 2)

// 设置错误报告 - 生产环境建议关闭显示
error_reporting(E_ALL);
ini_set('display_errors', 0); // 改为0以隐藏错误信息
ini_set('log_errors', 1); // 记录错误到日志而不是显示

// 设置响应头
header('Content-Type: text/html; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 目标网站URL
$base_url = 'http://p.ytelc.com/169l/xizang/xizang.php';

// 获取ID参数
$id = isset($_GET['id']) ? $_GET['id'] : '';

// 验证ID参数
if (!in_array($id, ['0', '1', '2'])) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid ID parameter. Only 0, 1, 2 are allowed.',
        'usage' => 'Use ?id=0, ?id=1, or ?id=2'
    ]);
    exit;
}

// 构建完整URL
$target_url = $base_url . '?id=' . $id;

// 使用cURL获取内容
function fetchContent($url) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1'
        ]
    ]);
    
    $content = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        return [
            'success' => false,
            'error' => 'cURL Error: ' . $error,
            'http_code' => $http_code
        ];
    }
    
    return [
        'success' => true,
        'content' => $content,
        'http_code' => $http_code
    ];
}

// 获取远程内容
$result = fetchContent($target_url);

if (!$result['success']) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to fetch content',
        'details' => $result['error'],
        'target_url' => $target_url
    ]);
    exit;
}

if ($result['http_code'] !== 200) {
    http_response_code($result['http_code']);
    echo json_encode([
        'error' => 'Remote server returned HTTP ' . $result['http_code'],
        'target_url' => $target_url
    ]);
    exit;
}

// 处理内容 - 替换相对路径为绝对路径
$content = $result['content'];
$domain = 'http://p.ytelc.com';

// 替换相对链接和资源路径
$patterns = [
    '/src="\/([^"]*)"/' => 'src="' . $domain . '/$1"',
    '/href="\/([^"]*)"/' => 'href="' . $domain . '/$1"',
    '/action="\/([^"]*)"/' => 'action="' . $domain . '/$1"',
    '/url\(\/([^)]*)\)/' => 'url(' . $domain . '/$1)',
];

foreach ($patterns as $pattern => $replacement) {
    $content = preg_replace($pattern, $replacement, $content);
}

// 添加一些基本的错误处理JavaScript
$additional_script = '
<script>
// 基本的错误处理
window.addEventListener("error", function(e) {
    console.log("页面加载出错:", e.message);
});

// 如果需要，可以在这里添加更多的JavaScript处理逻辑
</script>
';

// 在</body>标签前插入额外的脚本
if (strpos($content, '</body>') !== false) {
    $content = str_replace('</body>', $additional_script . '</body>', $content);
} else {
    $content .= $additional_script;
}

// 输出内容
echo $content;

// 记录访问日志（可选） - 仅在可写环境中执行
if (is_writable('.')) {
    try {
        $log_entry = date('Y-m-d H:i:s') . " - ID: {$id} - IP: " . $_SERVER['REMOTE_ADDR'] . " - User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        file_put_contents('access.log', $log_entry, FILE_APPEND | LOCK_EX);
    } catch (Exception $e) {
        // 静默忽略日志写入错误
        error_log("Log write failed: " . $e->getMessage());
    }
}

?>