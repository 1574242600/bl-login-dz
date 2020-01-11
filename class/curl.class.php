<?php
//curl 请求类
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class _curl
{

    static function post($url,$data,$cookies = null,$header = 0,$return = 1) {
      
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

    static function get($url,$cookies) {
        $useragnt = "Mozilla/5.0 BiliDroid/5.22.0 (bbcallen@gmail.com)";
        $header = 0 ;
        $return = 1 ;
        $lo = curl_init();
        curl_setopt($lo, CURLOPT_URL, $url);
        
        //die(self::$ip) ;
        //curl_setopt($lo, CURLOPT_HTTPHEADER,$headers);
        
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

    
}