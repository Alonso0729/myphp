<?php
$id = $_GET['id'];
$p = get_m3u8_url_from_web($id);
header('Access-Control-Allow-Origin: *');
header('Location: '.$p);



function get_m3u8_url_from_web($id) {
    $o = get_channel($id);
    $u = "http://aikanvod.miguvideo.com/video/p/live.jsp?user=guest&channel=$id&isEncrypt=1";
    $u .= '&appVersion=927&channalNo=2';
    $u .= '&categoryid='.$o->categoryid;
    $u .= '&playbillid='.$o->playbillid;
    $c = file_get_contents($u);
    preg_match('/source src="(.*?)"/', $c, $m);
    $p = $m[1];
    if ($p == '') {
        $cookies = [];
        foreach ($http_response_header as $h)
            if (preg_match('/Set-Cookie:\s*(.*?);/i', $h, $m) === 1)
                $cookies[] = $m[1];
        $cookieHeader = implode('; ', $cookies);
        $doc = new DOMDocument();
        @$doc->loadHTML($c);
        $k = '';
        $maxLen = 0;
        foreach ($doc->getElementsByTagName('input') as $input) {
            if ($input->getAttribute('id') === 'posterPicAll')
                continue;
            $inputValue = $input->getAttribute('value');
            if ($inputValue && $maxLen < strlen($inputValue)) {
                $maxLen = strlen($inputValue);
                $k = $inputValue;
            }
        }
        $channel = $doc->getElementById('channel')->getAttribute('value');
        $channel = rawurlencode(encryptPwd($channel, $k));
        $channelcode = $doc->getElementById('channelcode')->getAttribute('channelcode');
        $channelcode = rawurlencode(encryptPwd($channelcode, $k));
        $zjChannelOfOutStatus = $doc->getElementById('zjChannelOfOutStatus')->getAttribute('value');
        $zjChannelOfOutStatus = rawurlencode($zjChannelOfOutStatus);
        $u2 = "http://aikanvod.miguvideo.com/video/p/live.jsp?vt=9&type=1&user=guest&channel=$channel&isEncrypt=1&channelcode=$channelcode";
        $u2 .= "&zjChannelOfOutStatus=$zjChannelOfOutStatus";
        $c = file_get_contents($u2, false, stream_context_create(array(
            'http' => array(
                'header'  =>
                    "Referer: $u\r\n".
                    "epgsession: \r\n".
                    "location: \r\n".
                    "Cookie: $cookieHeader\r\n".
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36\r\n"
            )
        )));
        $j = json_decode($c);
        $p = $j->liveUrl;
    }
    return $p;
}

function get_channel($id) {
    $d = [
        'c74966f7109bc090b8b9f234bec5884f' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '56d0b140b3d4fbed614e851a06329981'],
        '1434bac241017d9eb89485bc80491bf6' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', 'c0f86ad0d93e8282aba159c228304ebb'],
        '5099c074952bff8ba34c81acbaa33ea7' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '79335cd3c614253f7acd1bc6d2c7611a'],
        '76d49a7fe74ad80d13a65e566fbaff03' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '544ae8f89f0aff95c8cf99926ea38322'],
        'db976f7874fa07b13dff464bd707b0f2' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '3c062d5f0f7d6340a237dfef0c60fd11'],
        '49ffa6abb7be07ff004933081062887a' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '6b29a8fc6e56dda03aab8b6e96629906'],
        '9c33ef39a294f72a3913b1fddc93f9f7' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '5a4f475be4596859383e0d3c16aace42'],
        '998130516f61f987a32f5b05e9058ee9' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', 'e465311dd326789cb1f1615e2bdfe70d'],
        '58b791dae3e0541536bd15d22419bf65' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '8abf99c2e7a258266d2170533129001a'],
        'a48b656035c62d77e272ea987b5690f6' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '5c5bda4ae0c5c7a76bdecc9326452af4'],
        '50c2aba67e0291cd497bf36f377fbbff' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '326f4ffa865ca5f8559626b5317d2721'],
        '69f3e78a39ec1f0d65f2ddb8d98a12a6' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '290327665a6721d4d016a36b9fe7b9dd'],
        '6a822ccee011fee0d96a46bed23bf8d6' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', 'eb26ae9741f3f06e1fa2b186b2e599dc'],
        '7619bb0ac390807471fc508a34eeea40' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', 'dcedc672d598470087036a85768f52bc'],
        '6c55832ac08e5223cbf79aac6dec5d98' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', 'c20c3ab72791f45df797fb7194c49c65'],
        '452cc8ca57be9f52bfd55f60de90d02c' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', 'b635e60c430a50164aa04db5bc15ff2d'],
        '4a755c47b7a7321bd9edcefce8923e16' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '5f2c56702d397cccddcff0d8d3fe53ac'],
        '45de6fabfc0d9dfd41de6ad5022d8fce' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '2ba9988c24bc2b1131fb31d1f98cffff'],
        '66e1470c453536359985b7b9b8222742' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', 'fe7d5aca1c970c12a1bb8ef927ad93c9'],
        '2b367141a90690336f104070714145c9' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '409a3425bb7ae9ef0725df438963d1f1'],
        'ee049d44093ce170db64b51eac1d2f78' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '3053a6bc6bd892606fe4cf4b673a2c00'],
        '3c4159bb5d8e37eed4f459dd2d270871' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', 'c85bd4071ea3e66c2acfc411f0121432'],
        'ca968e30d75e97aee66814d079acd6fd' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '903ceda1961f632073e6871e8a079df0'],
        '65c5ecaf4809d2ecadab18660c3ac6eb' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '2d63d213d5283085d807028a6da2b7be'],
        '70cf3f0edac1667cd62bf1186e45f206' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', 'f0fa89150614154e5b4f599400ab2f05'],
        '6f19caa8cc3798293fb98195ece4c1e6' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '86406f49865e16766047eb11f4834cc5'],
        '75fe4a331b8f71e5638eec115904f943' => ['8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac', '9e5fdd36a5632f85a453eadb847edea3'],
        'dbf745c3a8859380dbb257998e223015' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', '98727fa99dcc2c7f39ff022e45db0829'],
        '1929b19850d7a3046952b8d9bd198b23' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', '1665f7b3ca368ccb492b424ffbb2b59d'],
        'ce95a4ed1526e2d1e6c4629a20682f2f' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', '94fec65601a48bcb43f77bed767497f3'],
        '72e0faff01af71d26ea8ba4c62d4c23e' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', 'e280441ad72ecbba5d07fef88900453a'],
        '30fcb62a15de6e22fbc4efde97cb6791' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', '117c4f827275d1645f8014021bb349a9'],
        'ff120dae8b23e2cb5a7b8c7c65bd4123' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', '00ba5cb83617f24e0985b53869c2311a'],
        '414ce03a3640f44a4db96f6d485be5dc' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', '3d9842a3cdb33b065971d95051b01b08'],
        '87759623cbae83d727b06f6f0040c71d' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', 'fafc9789aba36a33bbe96c61ebcdf6ee'],
        '631c36ff41a50d7a9891023938d7359d' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', 'ace1284708ac8c86c821ad37598ee612'],
        'e5007c41aa833a230096acbaa762b5b9' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', '2eea07b4a55b8020afb0cb138ba2bbf7'],
        'e1f45a56760a590902a1a2ef4859667f' => ['catauto2000212604', '0'],
        'b756ac65ac87ef1a7f622bbaaede61ad' => ['2d8341b5da1da09170dbc160d4e127364818a78a8d7d79bf4ef46cefc16c9e46', '0e1049131502f0edf4595a3d4e05847b'],
        '98395285f1edf12ae051f6e22728faca' => ['catauto2000212604', '0'],
        '4403927ae89dbd01ffe02435d43ccb04' => ['catauto2000216345', '0'],
        '61173763da81b7fd481c139cd937baa4' => ['87b941f09225e9202f259a18172f8f829743b684c3b5046b4dd094102328f168', '569dda36a531d69daec83a7ed638397f'],
        '1856b9463ef16955a84bdcb5d228a705' => ['87b941f09225e9202f259a18172f8f829743b684c3b5046b4dd094102328f168', 'd24309a29ec6aadcaeb4a753e141d870'],
        '7ac18dfabc3748a867e2dc5d3e5a2cf0' => ['catauto2000216345', '0'],
        '15d1c8a8183b145e6745be48d7059f76' => ['408ca95d2cebc045766172391f0071198b30d94c07787caed68439e28d8bc2cf', '3161ba6a7c4baf1ffdf36567d60631a5'],
        '44a344a64dc596ddcf881cafb07ea0bd' => ['408ca95d2cebc045766172391f0071198b30d94c07787caed68439e28d8bc2cf', '7fdb42fce0370878ebebd16522cde55c'],
        'e6895ae6ea914ba368da69e487408ecd' => ['408ca95d2cebc045766172391f0071198b30d94c07787caed68439e28d8bc2cf', '60db76ed6d9e96b6de2694bbe2534214'],
        '3247643c60213697372e32d226c8478a' => ['408ca95d2cebc045766172391f0071198b30d94c07787caed68439e28d8bc2cf', 'ab21a65723eb97f214c6792a962912fb'],
        '046fd4eaec6d314a4982fc4172cba1cb' => ['catauto2000216278', '0'],
        '332b32f046c3b56d5f2b46b195f1e02d' => ['408ca95d2cebc045766172391f0071198b30d94c07787caed68439e28d8bc2cf', '8850918157c93ca0c2a5736dd30659db'],
        '008c639dafcd273f4000ce8bcbce45f9' => ['408ca95d2cebc045766172391f0071198b30d94c07787caed68439e28d8bc2cf', '61ef92a4e331342597040c6137e6baca'],
        'ee28a5df59d890bf587bef808ba74bd4' => ['408ca95d2cebc045766172391f007119106f2061e11d4e572b787b4a16cd2045', 'b6107d039c2e3220aede1e911b8ee3da'],
        'cd6c31618254f5c2aad8333bc2e3502e' => ['catauto2000216277', '0'],
        'a55d5f8ab3b8842010392956097c991b' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', '029114b7ca7b8b4df20bc263f17b93a0'],
        'b8e11cd84e1d5499396d0e0490ae64e1' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', 'a3a10138b7831152bf0b1e52be901f26'],
        '2162d220637a98d7f7eb2e2bd805ec9e' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', '96f9c9fc27170a402ddbc060e1b3db77'],
        '0469f423ebaf7a3ade269dcc87121f58' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', '8fe39340816e818ef7da3523eee7709c'],
        '25505275d0c1a786d886b68cd8af56f5' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', 'c2c2d24b31873e7590f7bd34079951e5'],
        '59ae2678f9fa0c2fc411b2830ec45bd2' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', 'a4758c79264a6db008445790308ebc09'],
        'd451ec9b7b06cf6c004fcfdc62ced933' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', 'f26f2290bfb3b79194f39ad10a9b9939'],
        'c4382c98e155adb1e474f027288d5919' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', '17b85450a0fa17eeca7381e90604455c'],
        '23131da84b625220c07232ae17d67d00' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', ''],
        '3bd5ca39cf59f5766ee6fd2e4af5645c' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', '335a95b4c9407d4345c9a115355f3e8e'],
        'cdfe84bb4e5336529b9ed93c43e357d7' => ['c46c5c12c59430871e8f7a2be8efccd74818a78a8d7d79bf4ef46cefc16c9e46', 'e75a3ce57d22c6aa55e81a2b30e86676'],
        '017ef627d5468a6c50491c559ed64ed7' => ['68c836d028ab8ede6077294b0b9a45a1a3016164f1e415c164025379e8acc8ac', '1f6f3c0f81e3ac02cc9635c088e60648'],
        'a09b6f2eb7db171693f6636d9c62449c' => ['4bcec0a603d552b7dbb6f6b41d893af7890889e21d16f8101e69c48bae179f5f', 'cb4168feeb042b36efc73ded3eab735b'],
        'ebee4991bd7da2a07f23e847a99817a6' => ['catauto2000396916', '0'],
        '1fd79da6a57f5d4a4873ef446fca278d' => ['8920104821592a21463f746a3f8590aa8b30d94c07787caed68439e28d8bc2cf', 'd8e64d07cb1551404ae79a7cc6393b34'],
        '741b500636fcae187d092162c40b8406' => ['8920104821592a21463f746a3f8590aa8b30d94c07787caed68439e28d8bc2cf', '06a49d203373ec07b661ba89435fae6e'],
        '2a547789f6b782394fb20f3adef2f9af' => ['8920104821592a21463f746a3f8590aa8b30d94c07787caed68439e28d8bc2cf', '29c4925fbb665a804f0f6eb98fcf4d99'],
        'b46acd33cd3059ff2d4dc4ebecfabcd1' => ['8920104821592a21463f746a3f8590aa8b30d94c07787caed68439e28d8bc2cf', '0f484bac5d3a97970244edd81d73721b'],
        'ae798c1af622b8f51f4b99eb82968383' => ['8920104821592a21463f746a3f8590aa8b30d94c07787caed68439e28d8bc2cf', 'c4c473fea17f962ba3179d114991523d'],
        '7a44b203aa4d4aab2f4485c818a16157' => ['catauto2000391355', '0'],
        'f77dcfe77101c1e564f29fec9e1ab4a7' => ['catauto2000396779', '0'],
    ];
    $r = new StdClass();
    $r->categoryid = $d[$id][0];
    $r->playbillid = $d[$id][1];
    return $r;
}

function encryptPwd($txt, $publicKey) {
    // 格式化公钥（确保包含PEM头尾）
    if (strpos($publicKey, '-----BEGIN PUBLIC KEY-----') === false) {
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
            chunk_split($publicKey, 64, "\n") .
            "-----END PUBLIC KEY-----";
    }
    // 加载公钥
    $keyResource = openssl_pkey_get_public($publicKey);
    // 加密数据
    $encrypted = '';
    openssl_public_encrypt($txt, $encrypted, $keyResource);
    if (PHP_VERSION_ID < 80000)
        openssl_pkey_free($keyResource);
    // 返回Base64编码结果
    return base64_encode($encrypted);
}