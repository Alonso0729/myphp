<?php
//用本地php生成频道

$php = 'https://tv.alonso0729.dpdns.org/api/kylintv.php';
$u = 'https://api.kylintv.com/api/v1/channels_list?type=2&after=0&limit=300';
$c = file_get_contents($u, false, stream_context_create(array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
    )
)));
$j = json_decode($c);
$r = [];
foreach ($j->data->items as $i) {
    if ($i->playback->type == 'm3u8')
        $r[] = trim($i->title, ' ').','.$php.'?'.$i->playback->playback;
}
file_put_contents('Channel.txt', implode("\r\n", $r));
?>