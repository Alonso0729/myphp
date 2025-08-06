<?php
header('Content-Type: application/json; charset=utf-8');
// 自动生成当前目录下php代理 播放列表，要求php代码开头以 // 标注文件的名称，用于列表中的分类，频道列表数组必须以 $n命名
$files = scandir('.');  
$file_self = end(explode("/", $_SERVER['PHP_SELF']));
$serv = (isset($_SERVER['HTTPS'])&& $_SERVER['HTTPS']==='on'?"https":"http")."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$i = 0;
foreach ($files as $file) {    
    if (is_file($file)) {  // 检查条目是否为文件
                $extension = pathinfo($file, PATHINFO_EXTENSION);                 
        if ($file == $file_self||$extension !== "php") continue; 
        $i++;
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . $file;
        $data = file_get_contents($filePath);
        $data = txt2UTF_8($data);
        preg_match('/\$n\s*=\s*\[(.*?)\s*\]\s*;/s', $data, $n);  //正则提取频道数组        
        //preg_match('/\$\w+\s*=\s*(array\s*\(|\[)(.*?)(\)|\])/s', $data, $n);
        if (empty($n[1])) preg_match('/\$n\s*=\s*array\s*\(\s*(.*?)\s*\)\s*;/s', $data, $n); 
        if (!empty($n[1]) && strpos($n[1], "=>") !== false){                
                preg_match('/\/\/([^\n\r]*)/', $data, $tv);  //提取文件中 //后的字符
                $newserv = preg_replace_callback('/\/([^\/]+)\.php$/', function($matches) use ($file) {
                                return '/' . $file;        }, $serv);                        
                        $outxt .= "\n".strtoupper(trim($tv[1])).",#genre#\n";
                        $outxt .= sz2list($n[1], $newserv); 
        }else{
                $outxt .= "\n文件".($i)." $file"." 未找到频道数据 . . .\n";
        }                
    }
}
echo "$i 个php文件\n".$outxt; 
function sz2list($szData, $serv_php){
        $lines = explode("\n", $szData);
        if (empty($serv_php))  $serv_php = (isset($_SERVER['HTTPS'])&& $_SERVER['HTTPS']==='on'?"https":"http")."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        if (strpos($serv_php, "/index.php") != false)  $serv_php = str_replace("/index.php","", $serv_php);                
        foreach ($lines as $line) {
                $line = trim($line);  
        $line = preg_replace("/[,'\"]/", "", $line);
                if (empty($line))   continue;  
                if (strpos($line, "//") === false){  //没有 //注释 的直接列表
                        list($ch, $num) = explode("=>", $line);                         
                        if (strpos($num, "genre") !== false){
                                //$output .= "\n$ch,#genre#\n\n";
                                $output .= "\n//$ch\n\n";
                                continue;                                
                        }else        $url =  "$serv_php?id=".trim($num);
                }else{        
                        $linearr = explode("//", $line);
                        $ids = $linearr[0];
                        $ch = end($linearr);                
                        if (empty($ids)){
                                if (!empty($ch) && mb_strlen(trim($ch))<12 && (strpos($ch,"http")===false)){
                                        //$output .= "\n$ch,#genre#\n\n";
                                        $output .= "$line\n";
                                        continue;
                                }else continue;
                        }
                        list($id, $num) = explode("=>", $ids);                         
                        if (strpos($num, "genre") !== false){
                                //$output .= "\n$ch,#genre#\n\n";
                                $output .= "\n//$ch\n\n";
                                continue;                                
                        }else $url =  "$serv_php?id=$id";
                }
                $output .= trim($ch).",$url\n";
        }
        return $output;
}
function txt2UTF_8($data){
    $encoding = mb_detect_encoding($data, 'UTF-8, GBK, BIG5');   // 检测文本的编码 
    if ($encoding != 'UTF-8') {  // 如果检测到的编码不是UTF-8，就将文本转换为UTF-8
        $data = mb_convert_encoding($data, 'UTF-8', $encoding);
    }
    //echo "中文编码为：$encoding";  exit;    
    return $data;
}
?>