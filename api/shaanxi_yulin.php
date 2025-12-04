<?php
$id = $_GET['id'];//榆林综合-186023和榆林公共-186022
header("Location: https:".openssl_decrypt(json_decode(str_replace(["(", ")"], "",file_get_contents("http://api.juyun.tv/juxian/api/is_pay_tv.jsp?id=".$id)))->address,"AES-128-ECB",base64_decode('ji0e3G8RR/JrBULhyaJUdg=='),0),true,302);
?>