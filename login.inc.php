<?php
// 登录入口文件

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//ini_set("display_errors", "On");
//error_reporting(E_ALL);

require_once DISCUZ_ROOT."/source/plugin/bl_login/class/curl.class.php";
require_once DISCUZ_ROOT."/source/plugin/bl_login/class/main.class.php";



$username = $_POST['username'];
$password = $_POST['password'];
$captcha = $_POST['captcha'];


$login_api = new b_login;

$data = $login_api->login($username,$password,$captcha);
$data = json_decode($data,true);

if($data['code'] === 0){
	
	$access_token = $data['data']['token_info']['access_token'];
	$refresh_token = $data['data']['token_info']['refresh_token'];
	$sign = md5(md5($access_token.$refresh_token."ljgkasgjkahiqokw@$#@#$$")); //后面的字符串随便改 与jump里一样就行
	header("Location: plugin.php?id=bl_login:jump&access_token={$access_token}&refresh_token={$refresh_token}&sign={$sign}");
    exit;
	
} else {
	
	if($data['code'] === -629){
	    header("Location: {$_G['siteurl']}plugin.php?id=bl_login&error=2");
        exit;
	}
	
	if($data['code'] === -105){
		header("Location: {$_G['siteurl']}plugin.php?id=bl_login&error=1");
        exit;
	}
	
	showmessage("未知错误:\n".var_export($data,true), '',[],['alert'=>'error']);
	
}
