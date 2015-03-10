<?php !defined('IN_ART') && exit('Access Denied');

$sql="

DROP TABLE IF EXISTS `art_feedback`;
CREATE TABLE `art_feedback` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `author` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `ip` char(15) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

";
runSql($sql);


?>