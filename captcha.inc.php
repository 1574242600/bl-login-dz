<?php
//验证码
//这个文件是完全独立的

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



header("Content-Type:image/jpeg;charset=UTF-8"); //设置文件头
//禁止浏览器缓存
header('Cache-Control:no-cache,must-revalidate');   
header('Pragma:no-cache');   
header("Expires:0"); 


if(empty($_COOKIE['sid'])){
	//没有sid,获取
	
	$data = get("https://passport.bilibili.com/captcha",null,1);
	//print_r($data);
	list($header, $img) = explode("\r\n\r\n", $data);
    preg_match("/sid=(.*?);/i", $header, $cookies);
    $sid = $cookies[1];
	
	$data = get("https://passport.bilibili.com/captcha","sid=$sid",1);
	list($header, $img) = explode("\r\n\r\n", $data);
    preg_match("/JSESSIONID=(.*?);/i", $header, $cookies);
	$JSESSIONID = $cookies[1];
	
	// 别问我为什么要请求两次
	//不知道为什么，只请求一次，验证码第一次登录，必定错误
	
	
	setcookie('sid', $sid,time()+120);
	setcookie('JSESSIONID', $JSESSIONID,time()+120);
	
	echo $img;
	
} else {
	//有sid ，刷新验证码，JSESSIONID
	$sid = $_COOKIE['sid'];
	$data = get("https://passport.bilibili.com/captcha","sid=$sid",1);
	
	list($header, $img) = explode("\r\n\r\n", $data);
    preg_match("/JSESSIONID=(.*?);/i", $header, $cookies);
	
	
	$JSESSIONID = $cookies[1];
	setcookie('JSESSIONID', $JSESSIONID,time()+120);
	//print_r($cookies);
	
	echo $img;
}


function get($url,$cookies,$is_header = 0) {
        $useragnt = "Mozilla/5.0 BiliDroid/5.22.0 (bbcallen@gmail.com)";
        $header = $is_header;
        $return = 1 ;
        $lo = curl_init();
        curl_setopt($lo, CURLOPT_URL, $url);
        
        //die(self::$ip) ;
        curl_setopt($lo, CURLOPT_HTTPHEADER,$headers);
        
        curl_setopt($lo, CURLOPT_HEADER, $header);
        curl_setopt($lo, CURLOPT_COOKIE, $cookies);
        curl_setopt($lo, CURLOPT_RETURNTRANSFER, $return);
        curl_setopt($lo, CURLOPT_USERAGENT, $useragnt);
        curl_setopt($lo, CURLOPT_FOLLOWLOCATION, 1);
        //允许跳转
        curl_setopt($lo, CURLOPT_MAXREDIRS, 3);
        //允许跳转次数
        curl_setopt($lo, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($lo, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($lo);
        curl_close($lo);
        return $data;
}