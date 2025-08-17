<?php
//需建cache文件夹！
//代理了切片！
//播不了可能是官方没在直播。
//国外服务器有的行，有的不行。

    // 北斗融媒
    $tsUrl = isset($_GET['u']) ? $_GET['u'] : '';
    if(!$tsUrl)
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 'ysjpd';
//        $id = 'ysjpd';
        $cache = new Cache(1800,"cache/");
        $playURL = $cache->get("bdrm_lnyun_".$id."_cache");
        if(!$playURL)
        {

//            $prikey = "MIIBVAIBADANBgkqhkiG9w0BAQEFAASCAT4wggE6AgEAAkEAllpOo0kGVEkvFSxFo8gh4kS8/2r63JVHtmfmpcXMFTMMsBLCLD2UlKx5GlWYvoGKQQHYUaKNPOpq15kcy0VLewIDAQABAkBNY5xYdaz5U1YVutz5iXjPY3w4qBMJ2Ri5bc+NgjsiqX2QwTYn3A3oeRffbFPAAOQHImd1a+QL9OLmN0vzSwx5AiEA+A5dVaI5vhj91duWbllxe5A5a8uBD8Va5pyIcPPA2BUCIQCbKvFBo068ShpqBSMWuLgV2rlc4mNCrwlpihaFaBppTwIgYeAxHa/j/skXp0F8qs/qAipXLdxfcVya0HGlOIRFbD0CIQCFljTaU6RnikyvVfjdiO5DMmk/RFA8isFJsW6uL+/9FQIgZ9iE9+tlg3U6mPAWvnGplrUlOHJBQQ7fB1HGDhiNmw0=";
            $prikey = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAOJqGLjxxzgHtkmT' .'oON4WPgfhqmwrt13oMEFLIQKgdZa5/H916WzbBDkoSalSLQpOevGL3nKiU+ECZqL' .'3QD+J0TYBttNup/qA+d9fU4njxRu8eXZ5LYwl7TzxMsTIyf89Xh7OCjP6pthxVql' .'1tphX5GZrqMNZbOMiw/rK8Pg/PCBAgMBAAECgYAaAQN74lD2L3SROMJmvcDCJqTJ'.'woAi8YVmBdkaBTbqTqCLG5Nz9Yp42jlj/eG+x2lemfGD9G4W0txjgqLMZWRO/Xp6'.'WHs+7+L2IX1DfduhJcnFjGkv4WWgwgVUskasZhR41tMVqpTcgkzB+PELuw2NaJjY'.'yiyR9FNw3xSQPes60QJBAP4qWbep+06AfIuOA66AiHe7axN53HKOQTzjw6Cn4yRs'
                .'J45L+F7YpMtAzcabolNnFpJCvBKiO6MpfW0WvHhAQH0CQQDkDHewvlKI4PsrgHM2'.'u0D90EymbBCmaoOKmARu//TcUOURHOBKE2N8QJZWVjwZN/aV4mrFY2IdO/vFwydc'.'61NVAkEAj67vYzXz/NAEGHyjNi4xd8Z65Nq6NgSXes2j1Rmz/e4qenYWJcBBgSnU'.'apenL5ESoIKbgck2/6k/38C/sRdZnQJAQimz2B1/yKKtfJOJ2ck+M+VpN6eGtSGW'.'BHHSZ3nvSrRVoT9le1hgtr3uYCIo0ZBBBH9qRtZsstqiU2ApXXYQ+QJABGF7U0R5'.'W81CPtN1qTt+cU4pDsvIIhvv35BM2Nk15Cj62HPkLQmwvZK1F+7n4gDQXLWqekoM'.'Rk87yN4rDV9Waw==';
            $prikey = "-----BEGIN PRIVATE KEY-----\n".wordwrap($prikey, 64, "\n", true) . "\n-----END PRIVATE KEY-----";

            $ids = [
                "lnws"=>["c077b260424404846285cba1e1759280", 'NDEyNjcxNzg1NzY2MDc3490E578144B71624745B39DD0DC0D1AC'],
                "dspd"=>["10d3de0d03c62e85a1a281bbde8b6952", 'ODI0NTYxNzg1NzY2MDc3F0B1FD729E76A90CA24FAAE02357F59D'],
                "typd"=>["e0bb9a7fd9afa954658bc50d0681cd49", 'NDIzNjcxNzg1NzY2MDc326E12A5421D7907E1675C0BB8EB3E96C'],
                "shpd"=>["078ce87dcf5384d51e4655cb962fda18", 'MzI0NjkxNzg1NzY2MDc3A6C5F5DC5AAD1598E6E413EB4C226089'],
                "qspd"=>["854e7044de9fef5163ae36fabb72de56", 'NTI0NTkxNzg1NzY2MDc38F221A56ADA818918271C956FBA074EE'],
                "ysjpd"=>["918510749a0f319ec12ff695b1c95230", 'MDI0NjkxNzg1NzY2MDc35199C51477AAD1DF17E160A2EB30213B'],
                "bfpd"=>["8e95535378bd3e5f7494bc23ab1cb117", 'NjE0NjcxNzg1NzY2MDc3EA435FC58A7F614BAE16828C0AADDB97'],
                "yjgw"=>["c577efa61117f1ee9687592aa1fd49e8", 'OTI0NTcxNzg1NzY2MDc381F5CC52B2DED40EAE6156756891C92C'],
//                "yxjj"=>"494f71cfefc14edce030d05baa676c03",
                "xdm"=>["7ff8ce0d226f2eb92e332be0cb13b406", 'MDE0NTkxNzg1NzY2MDc305F5C582D565E4C0A3722551C3C591BB'],
                "jtlc"=>["7e29bde4f41ca08642b7fc3ce4eb1ae4", 'ODEyMzUxNzg1NzY2MDc3D85948DEB26600EB95049D334A9550A7'],
                "ydds"=>["fb3cf5af7cd3bcbde56c280cad2e64cb", 'ODE0NTcxNzg1NzY2MDc3E6EDF6FAA3A2C8E669D8427660736FA2'],
//                "cctv1"=>"4353",
//                "cctv2"=>"c7a6e75b95d4b4551b37c752a5e9a4ac",
//                "cctv4"=>"0f0ad1e877f89c0ba2bb4a9d721da0eb",
//                "cctv7"=>"07a90238f466a5a7c3625e3741bc8dac",
//                "cctv9"=>"3f8a02e486bff27c9266e3106937cb53",
//                "cctv10"=>"6bb7ec668de61fbf34140fc6e57cca54",
//                "cctv11"=>"2e8d46836ac8454f9582dfb66cb64979",
//                "cctv12"=>"a4a71d1e6fc073d3f25de6e8396fe0dc",
//                "cctv13"=>"4f63dea3410297f056b58593ca819648",
//                "cctv14"=>"e91db452b0aa5e2628d4afe96870f020",
//                "cctv15"=>"eaf6944208c45b5e5b42a9876c46c717",
//                "cctv17"=>"12f90dab9adf16ae92327d9932b59e78",
            ];
//            $groupIds = ["d27a327bd4a067a40769be7374c4116c","d91158f1dc87e3b10ee20cf9fbd36390"];
//            $groupId = strstr($id,"cctv")?$groupIds[0]:$groupIds[1];
            $groupId = 'd91158f1dc87e3b10ee20cf9fbd36390';
            $bstrURL = "https://bdrm.bdy.lnyun.com.cn/cloud/apis/live/api/domain/getGroupDomain?groupId=$groupId&shows=1";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $bstrURL);                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                'token: ',
//                'backos: phone',
//                'Referer: https://bdrm.bdy.lnyun.com.cn',
////                'sign: MzI0NTkxNzU0MTI2NTQ2197CDD89911C059939D9263C1039B49F+',
//                'sign: MzEzNDkxNzUzOTQ1MTQ38649C646875E4FDB93964FC6E4B17044+',
//                'User-Agent: okhttp/4.10.0',
//            ));
            $data = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($data);
            
            foreach($json->data as $tvlst)
            {
                if($tvlst->id == $ids[$id][0])
                {
                    $pull = json_decode($tvlst->pull);
                    break;
                }
            }

            if($pull)
            {
                $playURL = $pull->m3u8;
                $bstrURL = "https://bdrm.bdy.lnyun.com.cn/cloud/apis/live/api/domain/getOauth?domainId=".$ids[$id][0];
                $bstrURL .= '&version=3&sign='.$ids[$id][1];
//                    'MDI0NjkxNzg1NzY2MDc35199C51477AAD1DF17E160A2EB30213B'
//                    'MTI0NTcxNzUzOTQ1MTU32CFAE823424338D22DB322143FB19124'
                ;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $bstrURL);                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                    'token: ',
//                    'backos: phone',
//                    'Referer: https://bdrm.bdy.lnyun.com.cn',
////                    'sign: NDIzNDUxNzU0MjAzMTM2450476D501D9072034DEC964E339AF25+',
//                    'sign: MTI0NTcxNzUzOTQ1MTU3CA56B235E910380A57674FA8539E1148+',
//                    'User-Agent: okhttp/4.10.0',
//                ));
                curl_setopt($ch, CURLOPT_POST, true);
//                curl_setopt($ch, CURLOPT_HEADER, true);
                $data = curl_exec($ch);
//                die($data);
                curl_close($ch);
                $json = json_decode($data);
            
                openssl_private_decrypt(base64_decode($json->msg),$auth_key,$prikey);
                openssl_private_decrypt(base64_decode(json_decode($json->data)->refer),$refer,$prikey);
            
                $ts = time()+1800;
                $playURL .= "?auth_key=$ts-0-0-".md5(parse_url($playURL,PHP_URL_PATH)."-$ts-0-0-$auth_key")."|||".$refer;
                $cache->put("bdrm_lnyun_".$id."_cache",$playURL);
            }
        }

        $re = explode("|||",$playURL);
        $playURL = $re[0];
        $refer = $re[1];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $playURL);                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,["Referer: http://$refer"]);
        curl_setopt($ch, CURLOPT_POST, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = preg_replace('/(.*?)_(.*?.ts)/i',$_SERVER['PHP_SELF']."?ref=$refer&u=$1/bdrm/$1_$2",$data);
        header("Content-Type: application/vnd.apple.mpegURL");
        header("Content-Disposition: filename=$id.m3u8");
        echo $data;
        
    }
    else
    {
        $refer = $_GET['ref'];
        $playURL = "https://".$tsUrl;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $playURL);                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,["Referer: http://$refer"]);
        $data = curl_exec($ch);
        curl_close($ch);
        header("Content-Type: binary/octet-stream");
        echo $data;
    }




    // 以下缓存类来自互联网，请确保cache目录存在以及读写权限 //
    class Cache {

        private $cache_path;
        private $cache_expire;
        public function __construct($exp_time=3600,$path="cache/"){
            $this->cache_expire=$exp_time;
            $this->cache_path=$path;
        }

        private function fileName($key){  return $this->cache_path.md5($key); }
        public function put($key, $data){

            $values = serialize($data);
            $filename = $this->fileName($key);    
            $file = fopen($filename, 'w');
            if ($file){

                fwrite($file, $values);
                fclose($file);
            }
            else return false;
        }

        public function get($key){

            $filename = $this->fileName($key);

            if (!file_exists($filename) || !is_readable($filename)){ return false; }

            if ( time() < (filemtime($filename) + $this->cache_expire) ) {

                $file = fopen($filename, "r");

                if ($file){

                    $data = fread($file, filesize($filename));
                    fclose($file);
                    return unserialize($data);
                }
                else return false;

            }
            else return false;
        }
    }
?>