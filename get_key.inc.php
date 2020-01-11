<?php
//获取加密密钥

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//禁止浏览器缓存
header('Cache-Control:no-cache,must-revalidate');   
header('Pragma:no-cache');   
header("Expires:0"); 

if (!empty($_G['cache']['plugin']['bl_login']['ak'])){
	
    define("AK", $_G['cache']['plugin']['bl_login']['ak']);
	define("SK", $_G['cache']['plugin']['bl_login']['sk']);
} else {
	showmessage('缺少ak', '',[],['alert'=>'error']);
}




if(empty($_COOKIE['sid'])){
	//没有sid
	
	showmessage('非法请求.get_key', '',[],['alert'=>'error']);
	
	
} else {
	
	header("Content-Type:application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");  //跨域
	//有sid
	$data = [                            
            "appkey" => AK ,
            "build" => 5220000 ,
            "mobi_app" => "android" ,
            "platform" => "android" ,
            "ts" => time()
        ];
		
	$sid = $_COOKIE['sid'];
	$data["sign"] = get_sign($data);
	$data = post("https://passport.bilibili.com/api/oauth2/getKey",$data,"sid=$sid",0);
	
	$data = json_decode($data,true);
        
 
        if ($data['code'] === 0) {
                echo json_encode($data['data']);
        } else {
                showmessage("未知错误.get_key\n".var_export($data,true), '',[],['alert'=>'error']);
        }

}



function get_sign($params) {
        $_data = array();
        ksort($params);
        reset($params);
        foreach ($params as $k => $v) {
            // rawurlencode 返回的转义数字必须为大写( 如%2F )
            $_data[] = $k . '=' . rawurlencode($v);
        }
        $_sign = implode('&', $_data);
        //print_r($_sign);
        return strtolower(md5($_sign . SK));
    }

function post($url,$data,$cookies = null,$header = 0,$return = 1) {
      
        $useragnt = "Mozilla/5.0 BiliDroid/5.22.0 (bbcallen@gmail.com)";
        
        $lo = curl_init();
        curl_setopt($lo, CURLOPT_URL, $url);
        curl_setopt($lo, CURLOPT_HEADER, $header);
        $headers = [
            "Device-ID: Bj4Ialg7AmBWZlMwTH5Mfkx5GCFAIkZzEW1VNgQxBDEDMHBAcEQjGipWbg0_Cj8KOAs",
            "Content-Type: application/x-www-form-urlencoded; charset=utf-8",
        ]; //鬼知道我在干什么
        curl_setopt($lo, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($lo, CURLOPT_COOKIE, $cookies);
        curl_setopt($lo, CURLOPT_RETURNTRANSFER, $return);
        curl_setopt($lo, CURLOPT_USERAGENT, $useragnt);
        curl_setopt($lo, CURLOPT_POST, 1);
        curl_setopt($lo, CURLOPT_POSTFIELDS,http_build_query($data));
        curl_setopt($lo, CURLOPT_FOLLOWLOCATION, 1);
        //允许跳转
        curl_setopt($lo, CURLOPT_MAXREDIRS, 3);
        //允许跳转次数
        curl_setopt($lo, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($lo, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($lo);
        //print_r($data);
        curl_close($lo);
        return $data;
}