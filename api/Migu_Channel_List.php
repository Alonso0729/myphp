<?php
//获取咪咕视频全部节目ID的PHP代码，改自https://www.right.com.cn/FORUM/forum.php?mod=viewthread&tid=8262702&highlight=%E5%92%AA%E5%92%95%E8%8E%B7%E5%8F%96%E5%85%A8%E9%83%A8
/*
代码支持一次输出所有频道名和对应的id，也支持单个分类输出。

使用方法:
id为空（miguid.php?id=）时，输出所有分类的所有频道名和对应的id；id为3（miguid.php?id=3）时，只输出央视所有频道名和对应的id
*/

header('Content-Type: text/plain; charset=UTF-8');

// 分类ID到pid的映射
$n = array(
    '1' => 'e7716fea6aa1483c80cfc10b7795fcb8', // 热门
    '2' => '7538163cdac044398cb292ecf75db4e0', // 体育
    '3' => 'a5f78af9d160418eb679a6dd0429c920', // 央视
    '4' => '0847b3f6c08a4ca28f85ba5701268424', // 卫视
    '5' => '855e9adc91b04ea18ef3f2dbd43f495b', // 地方
    '6' => '10b0d04cb23d4ac5945c4bc77c7ac44e', // 影视
    '7' => 'c584f67ad63f4bc983c31de3a9be977c', // 新闻
    '8' => 'af72267483d94275995a4498b2799ecd', // 教育
    '9' => 'e76e56e88fff4c11b0168f55e826445d', // 熊猫
    '10' => '192a12edfef04b5eb616b878f031f32f', // 综艺
    '11' => 'fc2f5b8fd7db43ff88c4243e731ecede', // 少儿
    '12' => 'e1165138bdaa44b9a3138d74af6c6673', // 纪实
    //'13' => '72504196e156468b873a39734f0af7db', // 印象天下x
);

// 分类ID到类别名称的映射
$categoryNames = array(
    '1' => '热门',
    '2' => '体育',
    '3' => '央视',
    '4' => '卫视',
    '5' => '地方',
    '6' => '影视',
    '7' => '新闻',
    '8' => '教育',
    '9' => '熊猫',
    '10' => '综艺',
    '11' => '少儿',
    '12' => '纪实',
    //'13' => '印象天下'//x
);

$id = isset($_GET['id']) ? $_GET['id'] : '';

// 如果不指定id，则输出所有分类的所有频道
if (empty($id)) {
        foreach ($n as $categoryId => $vomsId) {
        $categoryName = isset($categoryNames[$categoryId]) ? $categoryNames[$categoryId] : "未知分类($categoryId)";
        echo "=== $categoryName ===\n";
        
        $url = "http://program-sc.miguvideo.com/live/v2/tv-data/" . $vomsId;
        $info = file_get_contents($url);
        
        if ($info === FALSE) {
            echo "获取数据失败\n\n";
            continue;
        }
        
        $data = json_decode($info, true);
        
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['body']['dataList'])) {
            echo "解析JSON失败\n\n";
            continue;
        }
        
        foreach ($data['body']['dataList'] as $channel) {
            if (isset($channel['name']) && isset($channel['pID'])) {
                echo $channel['name'] . ',' . $channel['pID'] . "\n";
            }
        }
        echo "\n";
    }
} else {
    // 如果指定了id，只输出该分类的频道
    if (isset($n[$id])) {
        $url = "http://program-sc.miguvideo.com/live/v2/tv-data/" . $n[$id];
        $info = file_get_contents($url);
        
        if ($info === FALSE) {
            echo "获取数据失败";
            exit;
        }
        
        $data = json_decode($info, true);
        
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['body']['dataList'])) {
            echo "解析JSON失败";
            exit;
        }
        
        echo "=== $categoryNames[$id] ===\n";
        
        foreach ($data['body']['dataList'] as $channel) {
            if (isset($channel['name']) && isset($channel['pID'])) {
                echo $channel['name'] . ',' . $channel['pID'] . "\n";
            }
        }
    } else {
        echo "无效的ID";
    }
}
?>