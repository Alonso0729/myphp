<?php
$id = $_GET['id'];
$u = 'http://116.63.202.100/api/app/v1/data/page?page_id=1954791273765273601';
$c = file_get_contents($u);
$j = json_decode($c);
foreach ($j->data->data as $i) {
    foreach ($i as $j) {
        if ($j->id == $id) {
            header('Access-Control-Allow-Origin: *');
            header('Location: ' . $j->src);
            break 2;
        }
    }
}

/*
新闻综合,tl.php?id=1955568312843763713
教育科技,tl.php?id=1957378555043119105
*/