<?php
// 简单的TV频道代理脚本
// 使用: your-domain.com/proxy.php?id=0 (或 1, 2)

$id = $_GET['id'] ?? '';

// 检查ID是否有效
if (!in_array($id, ['0', '1', '2'])) {
    die('请使用 ?id=0 或 ?id=1 或 ?id=2');
}

// 目标URL
$url = 'http://p.ytelc.com/169l/xizang/xizang.php?id=' . $id;

// 获取内容
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

$content = curl_exec($ch);
curl_close($ch);

// 如果获取失败
if (!$content) {
    die('无法获取频道内容');
}

// 修复相对路径
$content = str_replace('src="/', 'src="http://p.ytelc.com/', $content);
$content = str_replace('href="/', 'href="http://p.ytelc.com/', $content);

// 输出内容
echo $content;
?>