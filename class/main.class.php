<?php
//bilibili api

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


if (
!empty($_G['cache']['plugin']['bl_login']['ak']) or !empty($_G['cache']['plugin']['bl_login']['sk'])){
    define("AK", $_G['cache']['plugin']['bl_login']['ak']);
    define("SK", $_G['cache']['plugin']['bl_login']['sk']);
} else {
	showmessage('缺少ak，sk', '',[],['alert'=>'error']);
}

class b_user {
	static private $at = null ; //access_token
	static private $rt = null ;  //refresh_token
	
    function __construct($at = null,$rt = null) {
		if (is_null(self::$at) or is_null(self::$rt)) {
            self::$at = $at ;
			self::$rt = $rt ;
        }
    }
	
	function get_bbs_uid($mid){
		$uid = DB::fetch_first('select uid from %t where mid = %d', ['common_bl_login_connect', $mid]) ?: 0;
		$uid = $uid['uid'];
		return $uid ;
	}
	
	function bbs_login($uid){
		require_once DISCUZ_ROOT.'/source/function/function_member.php';  
		$discuz = C::app();  
        $discuz->init();  
        $member = getuserbyuid($uid);  
        setloginstatus($member, 604800); 
		
		
	}
	
	function _add($insert){
		DB::insert('common_bl_login_connect',$insert,true);
		return true ;
		
	}
	
	function _delete($uid){
		DB::delete('common_bl_login_connect',['uid' => $uid],0,true);
        return true ;
	}
	
	function get_user_info(){
		$data = [
		    "access_key" => self::$at ,
            "appkey" => AK ,
			"build" => 5220000 ,
            "mobi_app" => "android" ,
            "platform" => "android" ,
            "ts" => time()
        ];
		
		
		$data["sign"] = self::get_sign($data);
		$url = "https://account.bilibili.com/api/myinfo/v2?".http_build_query($data);
		$data = _curl::get($url,"");
		//echo $data;
		return json_decode($data,true);
		
	}
	
	
	
	private static function get_sign($params) {
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
	
}




class b_login {


    function login($userid,$pwd,$captcha = false) {
		
        $cookies = 'sid=' . $_COOKIE['sid'] . ';JSESSIONID=' . $_COOKIE['JSESSIONID'];
        $data = [
            "appkey" => AK ,
            "build" => 5220000 ,
            "username" => $userid ,
            "password" => $pwd,
            "mobi_app" => "android" ,
            "platform" => "android" ,
            "ts" => time()
        ];

        if ($captcha) {
            $data["captcha"] = $captcha;
        }

        $data["sign"] = $this->get_sign($data);

        //print_r($data);
        $data = _curl::post("https://passport.bilibili.com/api/v2/oauth2/login",$data,$cookies);
        return $data ;
    }



    

    private function get_sign($params) {
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
	
/*    这两个函数大概都没用了，因为加密在前端了

	private function get_rsa_pwd($loginkey,$pwd) {   //这个函数大概没用了
        $hash = $loginkey['hash'];
        $loginkey = openssl_pkey_get_public($loginkey['key']);
        if ($loginkey) {
            openssl_public_encrypt($hash . $pwd ,$rsa_pwd,$loginkey);
            return base64_encode($rsa_pwd);
        }
            return false ;
    }

    private function get_loginkey() {
        $data = [                            
            "appkey" => AK ,
            "build" => 5220000 ,
            "mobi_app" => "android" ,
            "platform" => "android" ,
            "ts" => time()
        ];
        $data["sign"] = $this->get_sign($data);
        $data = _curl::post("https://passport.bilibili.com/api/oauth2/getKey",$data,'sid='.$_COOKIE['sid'].';',0);
        $data = json_decode($data,true);
        
 
        if ($data['code'] === 0) {
                return $data['data'];
        } else {
                return $data;
        }

    }
*/
	
}