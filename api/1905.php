<?php
$url = "https://m.1905.com/m/xl/live/";
preg_match("|video:'(.*?)'|",file_get_contents($url),$p);
header("Access-Control-Allow-Origin: *");
header("Location: ".$p[1]);
?>