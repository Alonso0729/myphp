<?php
$url = 'http://live.miguvideo.com/live/v2/tv-data/a5f78af9d160418eb679a6dd0429c920';
$a = json_decode(file_get_contents($url), true)['body']['liveList'];
$result = array();

for ($i = 0; $i < count($a); $i++) {
    $n = $a[$i]['name'];
    $vomsID = $a[$i]['vomsID'];
    $iurl = 'http://live.miguvideo.com/live/v2/tv-data/' . $vomsID;
   
    $content = @file_get_contents($iurl);
    if ($content === false) {
        continue;
    }
   
    $b = json_decode($content, true);
   
    if (!isset($b['body']['dataList']) || !is_array($b['body']['dataList'])) {
        continue;
    }
   
    $dataList = $b['body']['dataList'];
   
    for ($j = 0; $j < count($dataList); $j++) {
        if (!isset($dataList[$j]['name']) || !isset($dataList[$j]['pID'])) {
            continue;
        }
        
        $list1 = $dataList[$j]['name'];
        $list2 = $dataList[$j]['pID'];
        $key = $list1 . '_' . $list2;
        
        if (!isset($result[$key])) {
            $result[$key] = [
                'name' => $list1,
                'id' => $list2
            ];
        }
    }
}

$cctv_channels = [];
$satellite_channels = [];
$panda_channels = [];
$other_channels = [];

foreach ($result as $item) {
    $name = $item['name'];
   
    if (strpos($name, '熊猫') !== false) {
        $panda_channels[] = $item;
        continue;
    }
   
    if (preg_match('/CCTV(\d+)/', $name, $matches)) {
        $num = (int)$matches[1];
        if ($num >= 1 && $num <= 17) {
            $cctv_channels[] = [
                'name' => $name,
                'id' => $item['id'],
                'num' => $num
            ];
            continue;
        }
    }
   
    if (strpos($name, '卫视') !== false) {
        $satellite_channels[] = $item;
        continue;
    }
   
    $other_channels[] = $item;
}

usort($cctv_channels, function($a, $b) {
    return $a['num'] - $b['num'];
});

usort($satellite_channels, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

usort($other_channels, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

usort($panda_channels, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});


$final_result = array_merge($cctv_channels, $satellite_channels, $other_channels, $panda_channels);

foreach ($final_result as $item) {
    echo "'" . $item['name'] . "' => '" . $item['id'] . "', //" . $item['name'] . "<br>";
}
?>