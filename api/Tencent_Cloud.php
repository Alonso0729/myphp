<?php
/**
 * 腾讯云直播 - PHP版
 * 转换自酷9JS脚本（jsjiami.v7解混淆还原版）
 *
 * 用法: ?id=<ts文件baseUrl前缀>
 * 例如: ?id=https://xxx.com/live/channelXXX
 *
 * 逻辑：
 * 1. 请求 baseUrl-*.ts 获取真实m3u8
 * 2. 解析起始序号、分片时长、分片数量
 * 3. 根据当前时间推算当前序号
 * 4. 构造虚假m3u8返回给播放器
 */

error_reporting(0);

$baseUrl = isset($_GET['id']) ? trim($_GET['id']) : '';
if (empty($baseUrl)) {
    header('HTTP/1.1 400 Bad Request');
    die('缺少id参数');
}

// ── 第一步：请求真实m3u8 ──────────────────────────────────
$m3u8Url = $baseUrl . '-*.ts';
$ch = curl_init($m3u8Url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_FOLLOWLOCATION => true,
]);
$body = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if (!$body || $code !== 200) {
    header('HTTP/1.1 502 Bad Gateway');
    die('获取m3u8失败');
}

// ── 第二步：解析起始序号、分片时长、分片数量 ─────────────
// 起始序号：往前退10片确保流畅
$startSeq = 0;
if (preg_match('/#EXT-X-MEDIA-SEQUENCE:(\d+)/', $body, $m)) {
    $startSeq = intval($m[1]) - 10;
}

// 所有分片时长
preg_match_all('/#EXTINF:([\d.]+),/', $body, $dm);
$durations = array_map('floatval', $dm[1]);
$count     = count($durations);

if ($count === 0) {
    header('HTTP/1.1 502 Bad Gateway');
    die('m3u8解析失败');
}

$duration = round(array_sum($durations) / $count, 2);

// ── 第三步：根据当前时间推算当前序号 ─────────────────────
// JS版有startTime缓存，PHP版每次用当前时间直接算
// 等价于：curSeq ≈ startSeq + round(已过去时间 / duration)
// 因为没有缓存的startTime，直接用当前序号即可：
// startSeq已经是真实m3u8的MEDIA-SEQUENCE-10，当前就是起点
$startTime = time();
$elapsed   = 0; // 首次请求，elapsed=0
$curSeq    = $startSeq + round($elapsed / $duration);

// ── 第四步：构造并输出虚假m3u8 ───────────────────────────
$targetDur = ceil($duration);
$segments  = '';
for ($i = 0; $i < $count; $i++) {
    $segments .= '#EXTINF:' . $duration . ",\n";
    $segments .= $baseUrl . '-' . ($curSeq + $i) . ".ts\n";
}

$m3u8 = "#EXTM3U\n"
      . "#EXT-X-VERSION:3\n"
      . "#EXT-X-ALLOW-CACHE:NO\n"
      . "#EXT-X-MEDIA-SEQUENCE:{$curSeq}\n"
      . "#EXT-X-TARGETDURATION:{$targetDur}\n"
      . $segments;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/vnd.apple.mpegurl');
header('Cache-Control: no-cache');
echo $m3u8;
