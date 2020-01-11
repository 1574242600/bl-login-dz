<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_bl_login {

	function global_login_extra() {
		global $_G;
		return  '<div class="fastlg_fm y" style="margin-right: 10px; padding-right: 10px"><p><a href="plugin.php?id=bl_login"><img src="source/plugin/bl_login/template/img/bili_login.gif" class="vm" alt="bilibili登录"></a></p></div>';
		 
		 
		 
	} 

}