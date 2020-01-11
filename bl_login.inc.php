<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


if (empty($_G['cache']['plugin']['bl_login']['ak']) or empty($_G['cache']['plugin']['bl_login']['sk'])){
	showmessage('缺少ak，sk', '',[],['alert'=>'error']);
} 

setcookie('sid', null ,-1,'/');
setcookie('JSESSIONID', null ,-1,'/');

if ($_G['uid']) {
	
	$mid = DB::fetch_first('select mid from %t where uid = %d', ['common_bl_login_connect', $_G['uid']]) ?: 0;
	if ($mid != 0){
		$_url = "plugin.php?id=bl_login%3Adelete&fh={$_G['formhash']}";
		showmessage("您已绑定B站账户<br><a href=\"{$_url}\">解除绑定</a>", '',[],['alert'=>'right']);
	}
	
}


include template('bl_login:login');
//print_r($_G);



