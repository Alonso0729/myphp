<?php
/**
 * http://10.80.110.200:5080/shtv.php?m3u
 * http://10.80.110.200:5080/shtv.php?txt
 * 增加txt列表和**播放 2025.11.04
 * 
 * 上海电视直播 IPTV 列表生成器（by 戴小白2025年11月3日）
 * --------------------------------------------------
 * 📺 功能简介：
 *   - 从 Bestv 官方 API 获取实时播放地址
 *   - 自动生成 IPTV M3U 播放列表（含频道名、LOGO、tvg **、分组）
 *   - 启用本地缓存机制（默认 60 秒）减少 API 请求频率
 *
 * --------------------------------------------------
 * 💡 使用方法：
 *   将本文件保存为 `shanghai.php` 并放入 Web 根目录（如 /www/wwwroot/）
 *   浏览器或 IPTV 播放器访问：
 *      ▶ http://你的域名/shanghai.php
 *
 *   输出内容：
 *      → 标准 M3U 播放列表，可直接导入 IPTV 播放器、
 *        智能电视、机顶盒、VLC、Kodi、PotPlayer 等。
 *
 * --------------------------------------------------
 * ⚙️ 推荐运行环境：
 *   - ✅ iStoreOS (基于 OpenWrt) 内置 Web Server + PHP (FastCGI)
 *   - ✅ OpenWrt / FriendlyWrt / 飞牛系统 (FeiniuOS) 自带 web-php 插件
 *   - ✅ Linux (Debian / Ubuntu / Armbian / Alpine) + Nginx / Lighttpd / Apache
 *   - ✅ Docker 容器（PHP 官方镜像 + 挂载此文件）
 *
 * --------------------------------------------------
 * 🧩 PHP 版本兼容性：
 *   - 支持 PHP 7.2 ~ 8.3（推荐 PHP 7.4 / 8.1）
 *   - 仅依赖 PHP 内置函数，无需 cURL
 *   - 若提示 “Call to undefined function json_decode()”，请启用 json 扩展
 *   - 可运行于 php-fpm、php-cgi、web-php、php-cli 等模式
 *
 * --------------------------------------------------
 * 🧠 功能亮点总结：
 *   ✅ 实时抓取官方直播源
 *   ✅ 智能缓存机制（默认 60 秒）
 *   ✅ 自动生成 IPTV 播放列表
 *   ✅ 内嵌 LOGO、EPG、频道**
 *   ✅ 低资源占用，适配 ARMv8 架构
 *   ✅ 无需数据库与额外依赖
 *   ✅ 完美兼容 iStoreOS / OpenWrt / 飞牛系统 / Armbian
 *
 * --------------------------------------------------
 * 🛰️ EPG **：
 *   已嵌入电子节目单地址：
 *      x-tvg-url="https://epg.iill.top/e.xml"
 *
 * --------------------------------------------------
 * 🧾 播放列表结构：
 *   #EXTINF:-1 tvg-id="频道ID" tvg-name="频道名"
 *   tvg-logo="Logo地址" group-title="分组",频道显示名
 *   播放地址
 *
 * --------------------------------------------------
 * 📁 文件说明：
 *   cache/          —— 缓存目录（自动创建）
 *   shanghai.php    —— 主脚本（本文件）
 *
 * --------------------------------------------------
 * 🧱 测试通过环境：
 *   ✅ iStoreOS 22.x / PHP 8.1 (web-php)
 *   ✅ OpenWrt 23.05 / PHP 7.4 / uhttpd + php-mod
 *   ✅ 飞牛系统 FeiniuOS 1.3 / PHP 8.2
 *   ✅ Armbian 24.x / PHP 8.3 + Nginx
 *
 * --------------------------------------------------
 * ⚖️ 免责声明（Legal Notice）：
 *   1️⃣ 本项目仅供个人学习、研究与测试用途；
 *   2️⃣ 所有内容来源于互联网公开资源（Bestv 官方接口）；
 *   3️⃣ 本脚本不存储、转发、或篡改任何视频内容；
 *   4️⃣ 请勿将本脚本及其输出内容用于商业用途或任何侵权行为；
 *   5️⃣ 若涉及版权或侵权问题，请**原版权方或作者以删除；
 *   6️⃣ 使用者需自行承担因使用本脚本所产生的全部责任；
 *   ⚠️ 若不同意以上声明，请立即停止使用并删除本文件。
 *
 * --------------------------------------------------
 * 🙏 鸣谢（Acknowledgements）：
 *   🎨 EPG 数据源、TV logo提供者：yang-1989
 *   💬 脚本原思路来源：bbs.livecodes.vip 论坛原创作者
 *   🌌 优化与启发贡献：星河（xuejing665）
 *
 *   特别感谢以上贡献者及社区热心用户长期分享与技术交流！
 *
 * --------------------------------------------------
 * ✨ 作者：by 戴小白2025年11月3日（优化 & 规范整理）
 * 🕓 更新日期：2025-11-03
 * --------------------------------------------------
 */

error_reporting(0);
header('Content-Type: text/json;charset=UTF-8');
date_default_timezone_set("Asia/Shanghai");

// === 基本配置 ===
$id = $_GET['id'];
$m3u = isset($_GET['m3u']) ? 1 : 0 ; //输出 m3u 列表
$dl = isset($_GET['dl']) ? 0 : 1 ; //生成** 列表

$cache_dir = __DIR__ . '/cache';
if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);
$cache_file = $cache_dir . '/bestv_channels.json';
$cache_ttl = 60; // 缓存时间（秒）
$api_url = 'https://bp-api.bestv.cn/cms/api/live/channels';

// === 频道** ===
$m3u_epg_logo = [
    //'epg' => 'https://epg.iill.top/e.xml', 
    //'logo' => 'https://epg.iill.top/logo/', 
    'epg' => 'https://010.112114.xyz/pp.xml', 
    'logo' => 'https://010.112114.xyz/logo/', 
    'dfws' => ['id'=>'2030','logo'=>'东方卫视4K.png','group'=>'上海台'],
    'wxty' => ['id'=>'1605','logo'=>'五星体育.png','group'=>'上海台'],
    'dycj' => ['id'=>'21','logo'=>'第一财经.png','group'=>'上海台'],
    'xwzh' => ['id'=>'20','logo'=>'上海新闻.png','group'=>'上海台'],
    'dspd' => ['id'=>'18','logo'=>'上海都市.png','group'=>'上海台'],
    'xjs'  => ['id'=>'1600','logo'=>'新纪实.png','group'=>'上海台'],
    'mdy'  => ['id'=>'1601','logo'=>'魔都眼.png','group'=>'上海台'],
    'ash' => ['id'=>'2029','logo'=>'爱上海.png','group'=>'上海台'],
]; 

/*/ === 获取 API 数据 ===
$context = stream_context_create([
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
    'http' => ['method' => 'POST','header' => "Content-Type: application/json\r\n",'content' => '{}','timeout' => 5]
]);
*/
function get($url){
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => '{}',
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
        CURLOPT_TIMEOUT => 5,    
    ]);
    $context = curl_exec($ch);
    curl_close($ch);
    return $context;
}

if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_ttl)) {
    $json = file_get_contents($cache_file);
} else {
    //$json = @file_get_contents($api_url, false, $context);
    $json = get($api_url);
    if ($json) file_put_contents($cache_file, $json);
}

if (!$json) {
    exit("#EXTM3U\n# Error: 无法获取 Bestv API 数据\n");
}

$data = json_decode($json);
if (!isset($data->dt)) {
    exit("#EXTM3U\n# Error: Bestv 数据结构异常\n");
}

// === 输出 M3U/txt 播放列表 ===
$php = ($_SERVER['HTTPS']==='on'?"https":"http")."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$php = preg_replace('/\/index\.php$/i', '', $php);
$out_txt = "上海频道,#genre#\n\n";
$out_m3u = "#EXTM3U x-tvg-url=" . $m3u_epg_logo['epg'] ."\n";
foreach ($m3u_epg_logo as $key => $ch) {
    if (!is_array($ch)) continue;
    $playurl = '';
    foreach ($data->dt as $item) {
        if (!empty($id)){
            $id = is_numeric($id) ? $id : $m3u_epg_logo[$id]['id'];
            if ($item->id == $id) {
                header('location: '.$item->channelUrl );
            }
            continue;
        }
        if ($item->id == $ch['id']) {
            //$name = $item->channelName;
            $name = preg_replace('/\..*/','',$ch['logo']);
            $playurl = $dl ? "$php?id=".$ch['id'] : $item->channelUrl;
            $out_txt .= "$name,$playurl\n";
            $out_m3u  .= '#EXTINF:-1'.
            ' tvg-id="' . $name . 
            '" tvg-name="' . $name . 
            '" tvg-logo="' . $m3u_epg_logo['logo'].$ch['logo'] .
            '" group-title="' . $ch['group'] . '",'. $name . "\n".
            $playurl . "\n\n";
        }
    }
}
if ($m3u) die($out_m3u);
else die($out_txt);
?>
