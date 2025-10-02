<?php
   
    $id = $_GET['id']-1; // 1 = 资讯  2 = 中文  3 = 香港

    $public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCsMwDFK9QtawzMO7Df8clvO/gmVbpJ10CRz8uvq2pH4+700yURMgHWiMsIsUza0MPEgIffCAFtn4n0wk+arEcm2/vQd+5ebe0urgpGjl64SST0BxMSNgOWHCmKe7jVNWx6OusSUasDD+ieXChjjMDAaoH0Qx2g2Lg5Zu00abJv+QIDAQAB';
    $public_key = "-----BEGIN PUBLIC KEY-----\n".wordwrap($public_key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
    $public_key_a = openssl_pkey_get_public($public_key);
   
    $ticket = '';
   
    if(openssl_public_encrypt('$2k4Yk84Mo0O!',$encrypt_data,$public_key_a))
    {
        $ticket = base64_encode($encrypt_data);
    }

    $headers = array(
        'fengshows-client: app(android,5010208);23',
        'User-Agent: okhttp/3.11.0'
    );
    $bstrURL = "https://m.fengshows.com/api/v3/live?live_type=tv&page=1&page_size=15";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $bstrURL);                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $data = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($data);

    $vid = $json[$id]->_id;

    // 不知道是否为动态ID
    // $vid = '7c96b084-60e1-40a9-89c5-682b994fb680';
    //7c96b084-60e1-40a9-89c5-682b994fb680  资讯台
    //f7f48462-9b13-485b-8101-7b54716411ec  中文台
    //15e02d92-1698-416c-af2f-3e9a872b4d78  香港台

    /**
     *  获取token
     *  token 有一定时效性，建议cache而非频繁登陆，容易ban id
     */

    $loginData = [
		//主力号码:phone=13256889895&pwd=Fan2345678";备用号码1:phone=13389247903&pwd=Llxxcc198,备用号码2:phone=13955036885&pwd=make123456MAKE
        "phone"=>"13256889895", // 手机号码登陆方式，这里是你的手机号
        "password"=>"Fan234567", // 密码
        "ticket"=>$ticket,
        "code"=>"86"

    ];
   
    $headers[] = 'Content-Type: application/json; charset=utf-8'; // 增加 content-type
    $bstrURL = "https://m.fengshows.com/user/oauth/login";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $bstrURL);                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST,TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($loginData));
    $data = curl_exec($ch);
   
    curl_close($ch);
    $json = json_decode($data);
    $token = $json->data->token;  
    array_pop($headers); // 移除 content-type 头部
    $headers[] = 'token: '.$token;  // 加入 token 头

    // token 更新接口
    // https://m.fengshows.com/user/oauth/update
    // 使用cache的时候

    // 获取播放链接
    $bstrURL = 'https://m.fengshows.com/api/v3/hub/live/auth-url?live_id='.$vid.'&live_qa=fhd'; // fhd需要提交登陆token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $bstrURL);                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $data = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($data);
    $liveURL = $json->data->live_url;
   
    header("location:". $liveURL);

?>