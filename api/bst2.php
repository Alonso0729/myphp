<?php
// 添加频道映射关系（使用拼音缩写作为键名）
$channel_map = [
    // 央视
    'cctv1' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/111128@BESTV.SMG.SMG'], //CCTV-1
    'cctv2' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000036@BESTV.SMG.SMG'], //CCTV-2
    'cctv3' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1369028@BESTV.SMG.SMG'], //CCTV-3
    'cctv4' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000037@BESTV.SMG.SMG'], //CCTV-4
    'cctv5' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1369029@BESTV.SMG.SMG'], //CCTV-5
    'cctv5p' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/6000068@BESTV.SMG.SMG'], //CCTV-5+
    'cctv6' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1369030@BESTV.SMG.SMG'], //CCTV-6
    'cctv7' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000038@BESTV.SMG.SMG'], //CCTV-7
    'cctv8' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1369033@BESTV.SMG.SMG'], //CCTV-8
    'cctv9' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3949783@BESTV.SMG.SMG'], //CCTV-9
    'cctv10' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3949784@BESTV.SMG.SMG'], //CCTV-10
    'cctv11' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/6000053@BESTV.SMG.SMG'], //CCTV-11
    'cctv12' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000040@BESTV.SMG.SMG'], //CCTV-12
    'cctv13' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1358@BESTV.SMG.SMG'], //CCTV-13
    'cctv14' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3949788@BESTV.SMG.SMG'], //CCTV-14
    'cctv15' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3874@BESTV.SMG.SMG'], //CCTV-15
    'cctv16' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/6000061@BESTV.SMG.SMG'], //CCTV-16
    'cctv17' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000041@BESTV.SMG.SMG'], //CCTV-17
    'cetv1' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3138605@BESTV.SMG.SMG'], //CETV-1
    'cetv2' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/79471@BESTV.SMG.SMG'], //CETV-2
    'cetv4' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/6876519@BESTV.SMG.SMG'], //CETV-4
    'cgtn' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1354@BESTV.SMG.SMG'], //CGTN
    
    // 卫视
    'ahws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3540416@BESTV.SMG.SMG'], //安徽卫视
    'bjws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/181361@BESTV.SMG.SMG'], //北京卫视
    'cqws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3840707@BESTV.SMG.SMG'], //重庆卫视
    'dnws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3540417@BESTV.SMG.SMG'], //东南卫视
    'gsws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/6000067@BESTV.SMG.SMG'], //甘肃卫视
    'gdws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/181359@BESTV.SMG.SMG'], //广东卫视
    'gxws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000045@BESTV.SMG.SMG'], //广西卫视
    'gzwsw' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/4252663@BESTV.SMG.SMG'], //贵州卫视
    'hnws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/4252684@BESTV.SMG.SMG'], //海南卫视
    'hbws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/100000002@BESTV.SMG.SMG'], //河北卫视
    'henws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000044@BESTV.SMG.SMG'], //河南卫视
    'hljws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/181356@BESTV.SMG.SMG'], //黑龙江卫视
    'hubws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/911989@BESTV.SMG.SMG'], //湖北卫视
    'hunws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/181358@BESTV.SMG.SMG'], //湖南卫视
    'jlws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000046@BESTV.SMG.SMG'], //吉林卫视
    'jsws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/111129@BESTV.SMG.SMG'], //江苏卫视
    'jxws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3468921@BESTV.SMG.SMG'], //江西卫视
    'lnws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3450001@BESTV.SMG.SMG'], //辽宁卫视
    'nxws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/160782@BESTV.SMG.SMG'], //宁夏卫视
    'qhws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/67026@BESTV.SMG.SMG'], //青海卫视
    'sdws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/911992@BESTV.SMG.SMG'], //山东卫视
    'sxws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1344@BESTV.SMG.SMG'], //山西卫视
    'szws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/181362@BESTV.SMG.SMG'], //深圳卫视
    'scws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3840706@BESTV.SMG.SMG'], //四川卫视
    'tjws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/3450000@BESTV.SMG.SMG'], //天津卫视
    'xzws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/77574@BESTV.SMG.SMG'], //西藏卫视
    'ynws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000043@BESTV.SMG.SMG'], //云南卫视
    'zjws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/111130@BESTV.SMG.SMG'], //浙江卫视
    
    // 上海
    'dfws' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/111131@BESTV.SMG.SMG'], //东方卫视
    'hhxd' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/4641019@BESTV.SMG.SMG'], //哈哈炫动
    'ly' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1368437@BESTV.SMG.SMG'], //乐游
    'jsxt' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/4492970@BESTV.SMG.SMG'], //金色学堂
    'fztd' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/4492967@BESTV.SMG.SMG'], //法治天地
    'dfcj' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/6880079@BESTV.SMG.SMG'], //东方财经
    'hxjc' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1367710@BESTV.SMG.SMG'], //欢笑剧场
    'dsjc' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1555881@BESTV.SMG.SMG'], //都市剧场
    'dmxc' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1555886@BESTV.SMG.SMG'], //动漫秀场
    'shss' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1555893@BESTV.SMG.SMG'], //生活时尚
    'yxfy' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1555894@BESTV.SMG.SMG'], //游戏风云
    
    // 其他
    'zgtq' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/63734@BESTV.SMG.SMG'], //中国天气
    'bjkkse' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/6000052@BESTV.SMG.SMG'], //北京卡酷少儿
    'gdjjkt' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/484998@BESTV.SMG.SMG'], //广东嘉佳卡通
    'hnjyjs' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/4766929@BESTV.SMG.SMG'], //湖南金鹰纪实
    'hnklcd' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/4766930@BESTV.SMG.SMG'], //湖南快乐垂钓
    'hncpd' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/6000049@BESTV.SMG.SMG'], //湖南茶频道
    'jtlc' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/1872767@BESTV.SMG.SMG'], //家庭理财
    'jscftx' => ['g' => 1, 't' => 3, 'c' => 'Umai:CHAN/5000047@BESTV.SMG.SMG'], //江苏财富天下
    
    // 点播
    'jxbsc' => ['g' => 1, 't' => 1, 'c' => '14016258'] //京剧《白蛇传》
];

// 检查是否有频道ID参数
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $channel_id = $_GET['id'];
    if (isset($channel_map[$channel_id])) {
        $params = $channel_map[$channel_id];
        $_GET['g'] = $params['g'];
        $_GET['t'] = $params['t'];
        $_GET['c'] = $params['c'];
    } else {
        // 如果频道不存在，返回错误信息
        header('Content-Type: application/json');
        echo json_encode([
            'error' => '频道ID不存在',
            'available_channels' => array_keys($channel_map)
        ]);
        exit;
    }
}

// 原有的参数检查
if (!isset($_GET['g']) || !isset($_GET['t']) || !isset($_GET['c'])) {
    // 如果没有参数，显示可用频道列表
    header('Content-Type: text/html; charset=utf-8');
    echo "<h3>百视通直播频道列表</h3>";
    echo "<p>使用方法:</p>";
    echo "<ul>";
    echo "<li>通过频道ID: bst.php?id=频道ID</li>";
    echo "<li>通过原始参数: bst.php?g=1&t=3&c=频道代码</li>";
    echo "</ul>";
    echo "<h4>可用频道:</h4>";
    foreach ($channel_map as $id => $params) {
        // 从注释中提取频道名称（如果有）
        $comment = '';
        if (isset($params['comment'])) {
            $comment = $params['comment'];
        }
        echo "<p><a href='bst.php?id=" . urlencode($id) . "'>" . $id . "</a> " . $comment . "</p>";
    }
    exit;
}

$e = main(
    $_GET['g'],
    $_GET['t'],
    $_GET['c'],
    isset($_GET['playseek']) ? $_GET['playseek'] : null,
    $l
);
if ($e == '')
    locate($l);
else
    echo $e;

// 原有的函数保持不变...
function main($group, $type, $code, $playseek, &$play_url)
{
    $response_msg = '';
    for ($i = 1; $i <= 1; $i++) {
        foreach (hosts_get() as $host) {
            list($u, $p) = intf_get($host, $group, $type, $code);
            $c = @file_get_contents($u, false, stream_context_create(array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ),
                'http' => array(
                    'method' => 'POST',
//                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => $p,
                )
            )));
            if ($c === false)
                continue;
            $play_url = url_get($playseek, $type, $c, $response_code, $response_msg);
            switch ($response_code) {
                case 0:
                    return '';
                case -4014://防盗链服务错误
                    break;
                case -4020://直播频道配置异常
                    break 3;
                default:
                    break 3;
            }
        }
    }
    return $response_msg;
}

function intf_get($host, $group, $type, $code)
{
    $f = 'https://%s/ps/OttService/Auth';
    $u = sprintf($f, $host);
    $f = 'UserID=%s&UserToken=%s&TVID=$$%s&UserGroup=$TerOut_%d&ItemType=%d&ItemCode=%s';
    $p = sprintf($f, user_get_id(), user_get_token(), tvid_get(), $group, $type, $code);
    return [$u, $p];
}

//function type_get()
//{
//    return mt_rand(2, 4);
//}

function tvid_get()
{
    return random_string(4, 16);
}

function user_get_token()
{
    return random_string(1, 16);
}

function user_get_id()
{
//    return '023909999999999';
    return random_string(1, 16);
}

function random_string($min, $max)
{
    $r = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0,
        mt_rand($min, $max));
    return $r;
}

function hosts_get()
{
    return [
        'fjdxzpps.bestv.com.cn',

//        'ltzxps.bbtv.cn',

//        '139.224.116.50',
//        'dangbeisdkps.bestv.com.cn',
//        'dangbeisdkaaa.bestv.com.cn',
//        'b2cv3replay.bestv.com.cn',
//        'b2cv3wxmini.bestv.com.cn',
//        'b2cv3epg.bestv.com.cn',
//        'b2cv3aaa.bestv.com.cn',
//        'b2cv3ps.bestv.com.cn',
//        'b2cv3up.bestv.com.cn',
//        'b2cv3wag.bestv.com.cn',
//        '2cv3qhaaa.bestv.com.cn',
    ];
}

function url_get($playseek, $type, $content, &$response_code, &$response_msg)
{
    $j = json_decode($content);
    $response_code = $j->Response->Header->RC;
    $response_msg = $j->Response->Header->RM;
    if ($response_code != 0)
        return '';
    if ($playseek !== null) {
        $r = $j->Response->Body->LookBackUrl;
        if ($r == '')
            $r = $j->Response->Body->PlayURL;
    } else {
        $r = $j->Response->Body->PlayURL;
    }
//    if ($type > 1) {
    if (true) {
        $a = parse_url($r);
        $h = $a["host"];
        $p = substr($a["path"], 1);
        $q = $a["query"];
        parse_str($q, $a);
        $auth = $a["AuthInfo"];
        $r = strtok($r, '?');
        $a = explode("/", $r);
        $a[2] = get_play_ip() . ':8114/' . $a[2];
        $r = implode("/", $a);
        $r = sprintf('%s?AuthInfo=%s&FvSeid=a&Provider_id=%s&Pcontent_id=%s',
            $r, $auth, $h, $p);

//        $q = parse_url($r, PHP_URL_QUERY);
//        parse_str($q, $a);
//        $r = sprintf('%s?se=%s&ct=%s', strtok($r, '?'), $a['se'], $a['ct']);

//        $r = sprintf('%s', strtok($r, '?'));
    } else {
//        $r = sprintf('%s?_BitRate=6000', strtok($r, '?'));
    }
    if ($type <= 1) {
        $r .= '&_BitRate=6000';
    }
    if ($playseek !== null) {
        $z = new DateTimeZone('Asia/Shanghai');
        $s = strtok($playseek, '-');
        $starttime = DateTime::createFromFormat('YmdHis', $s, $z)->getTimestamp();
        $e = strtok('-');
        $endtime = DateTime::createFromFormat('YmdHis', $e, $z)->getTimestamp();
        $p = "starttime=$starttime&endtime=$endtime";
        $c = strpos($r, '?') === false ? '?' : '&';
        $r .= $c . $p;
    }
    return $r;
}

function get_play_ip()
{
    return '110.89.160.' . mt_rand(69, 84);
}

function locate($url)
{
    header('Access-Control-Allow-Origin: *');
    header('Location: ' . $url);
}