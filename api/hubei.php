<?php

/*
湖北卫视,http://192.168.1.200/myphp/hubei.php?id=hbws
湖北综合,http://192.168.1.200/myphp/hubei.php?id=hbzh
湖北经视,http://192.168.1.200/myphp/hubei.php?id=hbjs
湖北教育,http://192.168.1.200/myphp/hubei.php?id=hbjy
湖北影视,http://192.168.1.200/myphp/hubei.php?id=hbys
湖北生活,http://192.168.1.200/myphp/hubei.php?id=hbsh
湖北垄上,http://192.168.1.200/myphp/hubei.php?id=hbls
*/

$id = $_GET['id'];
$u = get_m3u8_url($id);
$c = send_request($u['url'], null, null, null, $ct);
$c = replace_ts_urls($u['url'], $c);
echo_content($ct, $c);



function get_m3u8_url($id)
{
    $r = load_from_cache($id);
    if ($r) {
        send_heartbeat($r);
        return $r;
    }

    $r = get_m3u8_url_from_web($id);

    save_to_cache($id, $r);
    return $r;
}

function get_m3u8_url_from_web($id) {
    $u = 'https://m.hbtv.com.cn/9hbfmtv';
    $c = send_request($u, null, null, null, $ct, $cookie_list);
    $client_info = get_cookies($cookie_list, ['client-id', 'aa-look', 'client-token']);
    $cookies = get_cookies($cookie_list, ['acw_tc']);
    preg_match('/stream: "([^"]+?'.$id.'[^"]+?)"/s', $c, $m);
    $s = $m[1];

    send_heartbeat($client_info);

    $cookie = "client-id={$client_info['client-id']}; ".
        "aa-look={$client_info['aa-look']}; ".
        "client-token={$client_info['client-token']}; ".
        "acw_tc={$cookies['acw_tc']}";
    $u2 = "https://m.hbtv.com.cn/get_cdn_9hbfm?url=$s&client-id={$client_info['client-id']}";
    $c = send_request($u2, $u, ['x-requested-with: XMLHttpRequest'], $cookie);
    $j = json_decode($c, true);
    $p = $j['data'];
    $client_info['url'] = $p;

    return $client_info;
}

function get_cookies($cookie_list, $cookie_names) {
    $r = [];
    foreach ($cookie_names as $name) {
        foreach ($cookie_list as $cookie) {
            if (strpos($cookie, $name) !== false) {
                preg_match("/$name\s+(.+)/", $cookie, $m);
                $r[$name] = $m[1];
                break;
            }
        }
    }
    return $r;
}

function save_to_cache($id, $array)
{
    array_to_file($array, "hb_cjy_sj_cache_$id.txt");
}

function load_from_cache($id)
{
    $cacheFile = "hb_cjy_sj_cache_$id.txt";
    $expireSeconds = 60 * 60 * 2;
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expireSeconds)) {
        file_to_array($cacheFile, $a);
        return $a;
    } else {
        return null;
    }
}

function file_to_array($filename, &$array) {
    $array = [];
    if (file_exists($filename)) {
        $handle = fopen($filename, 'r');
        if (flock($handle, LOCK_SH)) { // 共享锁，允许其他进程读但禁止写
            $data = file_get_contents($filename);
            $array = unserialize($data);
            flock($handle, LOCK_UN);
        }
        fclose($handle);
    }
    return true;
}

function array_to_file($array, $filename) {
    $dir = dirname($filename);
    if (!is_dir($dir) && is_writable(dirname($dir))) {
        if (!@mkdir($dir, 0755, true))
            return false;
    }
    $data = serialize($array);
    file_put_contents($filename, $data, LOCK_EX);
    return true;
}

function send_request($url, $referer = null, $http_header = null, $cookie = null,
                      &$content_type = null, &$cookie_list = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36';
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    if ($referer)
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    if ($http_header)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
    if ($cookie)
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    if (func_num_args() > 5)
        curl_setopt($ch, CURLOPT_COOKIEFILE, '');
    $res = curl_exec($ch);
    if (func_num_args() > 4)
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    if (func_num_args() > 5)
        $cookie_list = curl_getinfo($ch, CURLINFO_COOKIELIST);
    curl_close($ch);
    return $res;
}

function replace_ts_urls($m3u8_url, $m3u8_content)
{
    $dest_ts_path = dirname($m3u8_url)."/";
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($dest_ts_path) {
            if (!is_absolute_url($matches[1]))
                $ts = $dest_ts_path.$matches[1];
            else
                $ts = $matches[1];
            return $ts;
        },
        $m3u8_content
    );
}

function is_absolute_url($url) {
    return stripos($url, 'http:') === 0 || stripos($url, 'https:') === 0;
}

function echo_content($content_type, $content)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: $content_type");
    echo $content;
}

function send_heartbeat($client_info)
{
    static $ws;
    $h = [
        'Upgrade' => 'websocket',
        'Origin' => 'https://m.hbtv.com.cn',
        'Cache-Control' => 'no-cache',
        'Accept-Language' => 'zh-CN,zh;q=0.9',
        'Pragma' => 'no-cache',
        'Connection' => 'Upgrade',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
        'Sec-Websocket-Extensions' => 'permessage-deflate; client_max_window_bits',
    ];
    $ws = new NetTCP('wss://remote-wa.cjyun.org.cn/liveweb', $h);
    $p = json_encode([
        'client_id' => $client_info['client-id'],
        'aa_look' => $client_info['aa-look'],
        'client_token' => $client_info['client-token'],
    ]);
    $ws->send($p);
//    write_log('ws.recv: '.bin2hex($ws->recv()));
}

//function write_log($msg) {
//    $logFile = 'hb_cjy_sj_log.txt';
//    $message = date('[Y-m-d H:i:s]') . " $msg\n";
//    file_put_contents($logFile, $message, FILE_APPEND);
//}

//https://blog.csdn.net/qq_22183039/article/details/128780018
/**
 * NetTCP 支持ws，wss、ssl、tcp
 * @author 尹雪峰
 * @date 2022年6月6日
 * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
 */
class NetTCP {

    private $socket_uri = "wss://www.xforms.cn";
    private $isRev      = false; //是否开启错误提醒
    private $socket     = null;
    //默认设置参数
    private $options    = [
        'context'       => null,
        'filter'        => ['text', 'binary'],
        'fragment_size' => 4096,
        'headers'       => null,
        'logger'        => null,
        'origin'        => null,
        'persistent'    => false,
        'return_obj'    => false,
        'timeout'       => 5,
    ];
    //默认信息编码设置
    private $opcodes    = [
        'continuation'  => 0,
        'text'          => 1,
        'binary'        => 2,
        'close'         => 8,
        'ping'          => 9,
        'pong'          => 10,
    ];

    /**
     * 构造函数
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param unknown $config
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    public function __construct($socket_uri = null, $headers = null){
        if(!empty($socket_uri)){
            $this->socket_uri = $socket_uri;
        }
        if(!empty($headers)){
            $this->options['headers'] = $headers;
        }
        if(!$this->isConnect()){
            $this->connect();
        }
    }

    /**
     * 析构函数
     * @author 尹雪峰
     * @date 2022年6月7日
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    public function __destruct(){
        if ($this->isConnect() && get_resource_type($this->socket) !== 'persistent stream') {
            fclose($this->socket);
        }
        $this->socket   = null;
    }

    /**
     * 发送信息
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param unknown $payload
     * @param string $opcode
     * @param bool $masked
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    public function send($payload, $opcode = 'text', $masked = true){
        if (!$this->isConnect()){
            $this->connect();
        }
        if (!in_array($opcode, array_keys($this->opcodes))){
            $this->show(-1, "Bad opcode '{$opcode}'.  Try 'text' or 'binary'.");
        }
        $payload_chunks = str_split($payload, $this->options['fragment_size']);
        $frame_opcode   = $opcode;
        for ($index     = 0; $index < count($payload_chunks); ++$index) {
            $chunk      = $payload_chunks[$index];
            $final      = $index == count($payload_chunks) - 1;
            $this->sendFragment($final, $chunk, $frame_opcode, $masked);
            $frame_opcode = 'continuation';
        }
    }

    public function recv(){
        return $this->read();
    }

    /**
     * 发送信息
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param unknown $message
     * @param string $res
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function connect(){
        $url_parts          = parse_url($this->socket_uri);
        $scheme             = $url_parts['scheme'];
        $host               = $url_parts['host'];
        $user               = isset($url_parts['user']) ? $url_parts['user'] : '';
        $pass               = isset($url_parts['pass']) ? $url_parts['pass'] : '';
        $port               = isset($url_parts['port']) ? $url_parts['port'] : ($scheme === 'wss' ? 443 : 80);
        $path               = isset($url_parts['path']) ? $url_parts['path'] : '/';
        $query              = isset($url_parts['query'])    ? $url_parts['query'] : '';
        $fragment           = isset($url_parts['fragment']) ? $url_parts['fragment'] : '';

        $path_with_query    = $path;
        if (!empty($query)) {
            $path_with_query .= '?' . $query;
        }
        if (!empty($fragment)) {
            $path_with_query .= '#' . $fragment;
        }

        if (!in_array($scheme, ['ws', 'wss'])) {
            $this->show(-1, "Url should have scheme ws or wss, not '{$scheme}' from URI '{$this->socket_uri}'.");
        }
        $host_uri           = ($scheme === 'wss' ? 'ssl' : 'tcp') . '://' . $host;

        if (isset($this->options['context']) && !empty($this->options['context'])) {
            if (@get_resource_type($this->options['context']) === 'stream-context') {
                $context = $this->options['context'];
            } else {
                $this->show(-1, "Stream context in \$options['context'] isn't a valid context.");
            }
        } else {
            $context    = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
        }

        $persistent     = true;
        $errno          = null;
        $errstr         = null;
        $persistent     = $this->options['persistent'] === true;
        $flags          = STREAM_CLIENT_CONNECT;
        $flags          = $persistent ? $flags | STREAM_CLIENT_PERSISTENT : $flags;

        //取消证书认证，避免https无法正常使用
        stream_context_set_option($context, 'ssl', 'verify_peer_name', false);
        stream_context_set_option($context, 'ssl', 'verify_peer', false);
        stream_context_set_option($context, 'ssl', 'verify_host', false);

        $this->socket = stream_socket_client(
            "{$host_uri}:{$port}",
            $errno,
            $errstr,
            $this->options["timeout"],
            $flags,
            $context
        );

        restore_error_handler();
        if($this->isConnect()){
            if(!$persistent || ftell($this->socket) == 0){
                stream_set_timeout($this->socket, $this->options["timeout"]);
                $key        = $this->generateKey();
                $headers = [
                    'Host'                  => $host . ":" . $port,
                    'User-Agent'            => 'websocket-client-php',
                    'Connection'            => 'Upgrade',
                    'Upgrade'               => 'websocket',
                    'Sec-WebSocket-Key'     => $key,
                    'Sec-WebSocket-Version' => '13',
                ];
                if ($user || $pass) {
                    $headers['authorization'] = 'Basic ' . base64_encode($user . ':' . $pass);
                }
                if (isset($this->options['origin'])) {
                    $headers['origin'] = $this->options['origin'];
                }
                if (isset($this->options['headers'])) {
                    $headers = array_merge($headers, $this->options['headers']);
                }
                $header = "GET " . $path_with_query . " HTTP/1.1\r\n" . implode("\r\n",
                        array_map(function ($key, $value) {
                            return "$key: $value";
                        },
                            array_keys($headers),
                            $headers
                        )
                    )."\r\n\r\n";

                $matches    = array();
                $this->write($header);
                $response   = stream_get_line($this->socket, 1024, "\r\n\r\n");
                $address    = "{$scheme}://{$host}{$path_with_query}";
                if (!preg_match('#Sec-WebSocket-Accept:\s(.*)$#mUi', $response, $matches)) {
                    $this->show(-1, "Connection to '{$address}' failed: Server sent invalid upgrade response: {$response}");
                }
                $keyAccept  = trim($matches[1]);
                $expectedResonse = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
                if ($keyAccept !== $expectedResonse) {
                    $this->show(-1, "Server sent bad upgrade response");
                }
            }else{
                $this->show(-1, "网络连接异常，请稍后重试");
            }
        }else{
            $this->show(-1, "网络连接异常，请稍后重试");
        }
    }

    /**
     * 判断是否连接
     * @author 尹雪峰
     * @date 2022年6月6日
     * @return boolean
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function isConnect(){
        return $this->socket &&
            (get_resource_type($this->socket) == 'stream' || get_resource_type($this->socket) == 'persistent stream');
    }

    /**
     * Receive one message.
     * Will continue reading until read message match filter settings.
     * Return Message instance or string according to settings.
     */
    private function sendFragment($final, $payload, $opcode, $masked){
        $data       = '';
        $byte_1     = $final ? 0b10000000 : 0b00000000; // Final fragment marker.
        $byte_1     |= $this->opcodes[$opcode]; // Set opcode.
        $data       .= pack('C', $byte_1);
        $byte_2     = $masked ? 0b10000000 : 0b00000000; // Masking bit marker.
        $payload_length = strlen($payload);
        if ($payload_length > 65535) {
            $data   .= pack('C', $byte_2 | 0b01111111);
            $data   .= pack('J', $payload_length);
        } elseif ($payload_length > 125) {
            $data   .= pack('C', $byte_2 | 0b01111110);
            $data   .= pack('n', $payload_length);
        } else {
            $data   .= pack('C', $byte_2 | $payload_length);
        }
        if ($masked) {
            $mask   = '';
            for ($i = 0; $i < 4; $i++) {
                $mask .= chr(rand(0, 255));
            }
            $data   .= $mask;
            for ($i = 0; $i < $payload_length; $i++) {
                $data .= $payload[$i] ^ $mask[$i % 4];
            }
        } else {
            $data   .= $payload;
        }
        $this->write($data);
    }

    /**
     * 写入数据信息
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param string $data
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function write($data){
        @fwrite($this->socket, $data);
    }

    private function read(){
        $r = @fread($this->socket, 4096);
        return $r;
    }

    /**
     * 输入信息
     * @author 尹雪峰
     * @date 2022年6月6日
     * @param unknown $code
     * @param unknown $msg
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function show($code, $msg, $data = null){
        $value = array(
            "code"  =>$code,
            "msg"   =>$msg,
        );
        if(!empty($data)){
            $value["data"] = $data;
        }
        if($this->isRev){
            echo json_encode($value, JSON_UNESCAPED_UNICODE);
            die();
        }else{
            return $value;
        }
    }

    /**
     * 获取websocket key值
     * @author 尹雪峰
     * @date 2022年6月6日
     * @return string
     * @Copyright (C) 2020-2030 YinXueFeng. All Rights Reserved.
     */
    private function generateKey(){
        $key = '';
        for ($i = 0; $i < 16; $i++) {
            $key .= chr(rand(33, 126));
        }
        return base64_encode($key);
    }
}