<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if (empty($_G['cache']['plugin']['bl_login']['ak']) or empty($_G['cache']['plugin']['bl_login']['sk'])){
	showmessage('缺少ak，sk', '',[],['alert'=>'error']);
} 

require_once DISCUZ_ROOT."/source/plugin/bl_login/class/main.class.php";


if ($_GET['fh'] == $_G['formhash']){
  
  if ($_G['uid']) {
	 $user_api = new b_user();
	 $user_api->_delete($_G['uid']);
	 foreach ($_COOKIE as $k => $v) {
        setcookie($k, null, -1,'/');
     }
	 showmessage('账户还未绑定或者解除绑定成功', $_G['siteurl'] ,[],['alert'=>'right']);
  } else {
	 showmessage('请先登录', '', [], ['login' => true]);
  }

} else {
  showmessage('非法请求.delete', '',[],['alert'=>'error']);
}
