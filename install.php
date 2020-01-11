<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


//没得错，就一个表
$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_common_bl_login_connect` (
  `mid` int(15) NOT NULL COMMENT 'B站uid',
  `uid` int(10) NOT NULL,
  `at` char(32) NOT NULL COMMENT 'access_token',
  `rt` char(32) NOT NULL COMMENT 'refresh_token',
  PRIMARY KEY (`mid`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='bilibili和本站的账户的对应关系';

EOF;


runquery($sql);
$finish = true;