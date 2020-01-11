<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if (empty($_G['cache']['plugin']['bl_login']['ak']) or empty($_G['cache']['plugin']['bl_login']['sk'])){
	showmessage('缺少ak，sk', '',[],['alert'=>'error']);
} 

//ini_set("display_errors", "On");
//error_reporting(E_ALL);
require_once DISCUZ_ROOT."/source/plugin/bl_login/class/curl.class.php";
require_once DISCUZ_ROOT."/source/plugin/bl_login/class/main.class.php";

$access_token = $_GET['access_token'];
$refresh_token = $_GET['refresh_token'];
$sign = $_GET['sign'];
$_sign = md5(md5($access_token.$refresh_token."ljgkasgjkahiqokw@$#@#$$")); //后面的字符串随便改 与login里一样就行

if ($sign == $_sign){
	
	    $user_api = new b_user($access_token,$refresh_token);
	    $data = $user_api ->get_user_info();
		
	    if ($data['mid'] > 0){ //判断b站账户是否存在
		  $uid = $user_api ->get_bbs_uid($data['mid']);
		  
		  if ($uid > 0){//判断是否与B站账户关联
		      if ($_G['uid']){ //判断是否登录
			    showmessage('该bilibili账户已绑定本站，请解绑后重试','plugin.php?id=bl_login',[],['alert'=>'error']);
			  } else {
				$user_api->bbs_login($uid);
			    header("Location: member.php?mod=register&referer={$_G['siteurl']}");
			    die();
			  }
		  } else {
			  
			  if ($_G['uid']){
			    $insert = [
				 'uid' => $_G['uid'],
				 'mid' => $data['mid'],
				 'at' => $access_token,
				 'rt' => $refresh_token,
				];
				
			    $user_api ->_add($insert);
			    showmessage("绑定成功", $_G['siteurl'],[],['alert'=>'right']);
				 
			  } else {
				$url = "member.php?mod=register&referer=".urlencode("{$_G['siteurl']}plugin.php?id=bl_login:jump&access_token={$access_token}&refresh_token={$refresh_token}&sign={$sign}");
				showmessage('该bilibili账户还未绑定本站，注册后将自动绑定',$url,[],['alert'=>'info']);
				
			  }
		  }
		
	    } else {
			showmessage("未知错误.jump/n".var_export($data,true), '',[],['alert'=>'error']);
		}
} else {
    showmessage('非法请求.jump', '',[],['alert'=>'error']);
}



 