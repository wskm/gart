-- MySQL dump 10.11
--
-- Host: localhost    Database: gart
-- ------------------------------------------------------
-- Server version	5.0.51b-community-nt-log
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `art_adminloginlog`
--

DROP TABLE IF EXISTS `art_adminloginlog`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_adminloginlog` (
  `logid` smallint(6) NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `uname` char(15) NOT NULL default '',
  `adminid` tinyint(1) NOT NULL default '0',
  `groupid` smallint(6) unsigned NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  `password` char(32) NOT NULL default '',
  `logintime` int(10) unsigned NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`logid`)
) ENGINE=MyISAM AUTO_INCREMENT=199 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_adminsessions`
--

DROP TABLE IF EXISTS `art_adminsessions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_adminsessions` (
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `adminid` smallint(6) unsigned NOT NULL default '0',
  `uname` char(20) NOT NULL default '',
  `gotime` int(10) NOT NULL default '0',
  `logintime` int(10) unsigned NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `art_adminsessions`
--



--
-- Table structure for table `art_articlemessages`
--

DROP TABLE IF EXISTS `art_articlemessages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_articlemessages` (
  `mid` mediumint(8) unsigned NOT NULL auto_increment,
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `message` text NOT NULL,
  `pagetype` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`mid`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;


--
-- Table structure for table `art_articles`
--

DROP TABLE IF EXISTS `art_articles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_articles` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `cid` smallint(6) unsigned NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL default '0',
  `uname` char(15) NOT NULL default '',
  `kindid` tinyint(1) NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `title` char(80) NOT NULL default '',
  `titlestyle` varchar(100) NOT NULL default '',
  `cover` varchar(255) NOT NULL default '',
  `summary` varchar(255) NOT NULL default '',
  `digest` tinyint(1) NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `replies` mediumint(8) unsigned NOT NULL default '0',
  `replystate` tinyint(1) NOT NULL default '1',
  `tags` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `fromname` varchar(255) NOT NULL default '',
  `fromurl` varchar(255) NOT NULL default '',
  `kiss` smallint(6) unsigned NOT NULL default '0',
  `bury` smallint(6) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`aid`),
  KEY `aid` (`aid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;


--
-- Table structure for table `art_articletags`
--

DROP TABLE IF EXISTS `art_articletags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_articletags` (
  `aid` int(10) unsigned NOT NULL default '0',
  `name` char(20) NOT NULL,
  KEY `id` USING BTREE (`aid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;


--
-- Table structure for table `art_attachments`
--

DROP TABLE IF EXISTS `art_attachments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_attachments` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `cid` smallint(6) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `uploadtime` int(10) unsigned NOT NULL default '0',
  `filename` char(100) NOT NULL default '',
  `filetype` char(50) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `filepath` char(100) NOT NULL default '',
  `width` smallint(6) unsigned NOT NULL default '0',
  `downloads` mediumint(8) NOT NULL default '0',
  `isimage` tinyint(1) unsigned NOT NULL default '0',
  `isthumb` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`),
  KEY `pid` (`id`),
  KEY `uid` (`uid`),
  KEY `dateline` (`uploadtime`,`isimage`,`downloads`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;


--
-- Table structure for table `art_category`
--

DROP TABLE IF EXISTS `art_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_category` (
  `cid` smallint(6) unsigned NOT NULL auto_increment,
  `parentid` smallint(6) unsigned NOT NULL,
  `name` varchar(50) NOT NULL default '',
  `cover` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `displaysort` smallint(6) NOT NULL default '0',
  `isnav` tinyint(1) NOT NULL default '0',
  `navkey` varchar(255) NOT NULL default '',
  `tpllist` varchar(255) NOT NULL default 'category',
  `tplshow` varchar(255) NOT NULL default 'news',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_comments`
--

DROP TABLE IF EXISTS `art_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `uname` char(15) NOT NULL default '',
  `anonym` char(15) NOT NULL default '',
  `ip` char(15) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  `message` text NOT NULL,
  `kiss` mediumint(8) unsigned NOT NULL default '0',
  `detail` mediumtext NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`dateline`),
  KEY `aid` (`aid`,`dateline`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_filterword`
--

DROP TABLE IF EXISTS `art_filterword`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_filterword` (
  `id` smallint(6) NOT NULL auto_increment,
  `word` varchar(255) NOT NULL default '',
  `replace` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_friendlinks`
--

DROP TABLE IF EXISTS `art_friendlinks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_friendlinks` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `logo` varchar(255) NOT NULL default '',
  `displaysort` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `show_order` (`displaysort`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;


--
-- Table structure for table `art_nav`
--

DROP TABLE IF EXISTS `art_nav`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_nav` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `color` varchar(20) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `target` tinyint(1) NOT NULL default '0',
  `displaysort` tinyint(3) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_sessions`
--

DROP TABLE IF EXISTS `art_sessions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_sessions` (
  `sid` char(8) NOT NULL default '',
  `ip` char(15) NOT NULL default '',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `uname` char(15) NOT NULL default '',
  `groupid` smallint(6) unsigned NOT NULL default '0',
  `styleid` smallint(6) unsigned NOT NULL default '0',
  `viewtime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `sid` (`sid`),
  KEY `uid` (`uid`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_settings`
--

DROP TABLE IF EXISTS `art_settings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_settings` (
  `variable` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `art_settings`
--

INSERT INTO `art_settings` VALUES ('webName','base','Test');
INSERT INTO `art_settings` VALUES ('webUrl','base','');
INSERT INTO `art_settings` VALUES ('webBaseUrl','base','');
INSERT INTO `art_settings` VALUES ('webTitle','base','');
INSERT INTO `art_settings` VALUES ('webDescription','base','');
INSERT INTO `art_settings` VALUES ('webKeywords','base','Gart,WskmPHP,Wskm');
INSERT INTO `art_settings` VALUES ('webStatus','base','1');
INSERT INTO `art_settings` VALUES ('webCloseReason','base','');
INSERT INTO `art_settings` VALUES ('vcodeSave','vcode','0');
INSERT INTO `art_settings` VALUES ('vcodeType','vcode','0');
INSERT INTO `art_settings` VALUES ('vcodeLength','vcode','4');
INSERT INTO `art_settings` VALUES ('onlineHold','base','900');
INSERT INTO `art_settings` VALUES ('styleId','sys','1');
INSERT INTO `art_settings` VALUES ('regGroupId','reg','7');
INSERT INTO `art_settings` VALUES ('adminStyleId','sys','2');
INSERT INTO `art_settings` VALUES ('friendLinkType','base','0');
INSERT INTO `art_settings` VALUES ('unameProtect','reg','');
INSERT INTO `art_settings` VALUES ('isRules','reg','0');
INSERT INTO `art_settings` VALUES ('regRulesText','reg','');
INSERT INTO `art_settings` VALUES ('isWaterMark','img','1');
INSERT INTO `art_settings` VALUES ('waterMarkType','img','1');
INSERT INTO `art_settings` VALUES ('waterMarkPosition','img','9');
INSERT INTO `art_settings` VALUES ('timeZone','base','8');
INSERT INTO `art_settings` VALUES ('articleStatus','article','1');
INSERT INTO `art_settings` VALUES ('articleReplyState','article','1');
INSERT INTO `art_settings` VALUES ('imagethumbwidth','img','320');
INSERT INTO `art_settings` VALUES ('imagethumbheight','img','200');
INSERT INTO `art_settings` VALUES ('articlePageType','article','0');
INSERT INTO `art_settings` VALUES ('articleUrlAbsolute','article','0');
INSERT INTO `art_settings` VALUES ('attachValidUser','attach','0');
INSERT INTO `art_settings` VALUES ('attachValidReferer','attach','0');
INSERT INTO `art_settings` VALUES ('groupId','base','5');
INSERT INTO `art_settings` VALUES ('language','base','zh');
INSERT INTO `art_settings` VALUES ('emailProtect','reg','');
INSERT INTO `art_settings` VALUES ('timeFormats','base','Y-m-d H:i\nY/m/d H:i\nH:i Y-m-d\nH:i Y/m/d');
INSERT INTO `art_settings` VALUES ('commentStatus','article','1');
INSERT INTO `art_settings` VALUES ('pageFooter','base','');
INSERT INTO `art_settings` VALUES ('attachMaxSize','attach','3145728');
INSERT INTO `art_settings` VALUES ('emailType','mail','1');
INSERT INTO `art_settings` VALUES ('emailHost','mail','smtp.test.com');
INSERT INTO `art_settings` VALUES ('emailFrom','mail','test@test.com');
INSERT INTO `art_settings` VALUES ('emailFromName','mail','Admin');
INSERT INTO `art_settings` VALUES ('emailUserName','mail','username');
INSERT INTO `art_settings` VALUES ('emailPassword','mail','***');
INSERT INTO `art_settings` VALUES ('emailPort','mail','25');
INSERT INTO `art_settings` VALUES ('emailCharset','mail','utf-8');
INSERT INTO `art_settings` VALUES ('emailLanguage','mail','zh');
INSERT INTO `art_settings` VALUES ('emailDelimiter','mail','0');
INSERT INTO `art_settings` VALUES ('urlMode','base','URLMODE_NONE');
INSERT INTO `art_settings` VALUES ('commentVote','article','1');
INSERT INTO `art_settings` VALUES ('editor','article','ckeditor');
INSERT INTO `art_settings` VALUES ('isGzip','base','0');
INSERT INTO `art_settings` VALUES ('popBgShow','style','1');
INSERT INTO `art_settings` VALUES ('popBgColor','style','#000000');
INSERT INTO `art_settings` VALUES ('isSwitchTheme','style','1');
INSERT INTO `art_settings` VALUES ('isHtml','base','0');
INSERT INTO `art_settings` VALUES ('isNewsKiss','article','1');
INSERT INTO `art_settings` VALUES ('isVcode','base','0');
INSERT INTO `art_settings` VALUES ('pollLevel','article','0');
INSERT INTO `art_settings` VALUES ('indexPollCount','style','2');
INSERT INTO `art_settings` VALUES ('isEmailVerify','reg','1');
INSERT INTO `art_settings` VALUES ('sendEmailVerify','reg','0');
INSERT INTO `art_settings` VALUES ('loginFailedCount','login','5');
INSERT INTO `art_settings` VALUES ('loginFailedHold','login','1800');
INSERT INTO `art_settings` VALUES ('isAllowReg','reg','1');
INSERT INTO `art_settings` VALUES ('replyWait','article','10');

--
-- Table structure for table `art_tags`
--

DROP TABLE IF EXISTS `art_tags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_tags` (
  `tagid` mediumint(8) unsigned NOT NULL auto_increment,
  `tagname` char(20) NOT NULL default '',
  `close` tinyint(1) NOT NULL default '0',
  `count` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tagid`),
  KEY `tagname` (`tagname`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_themes`
--

DROP TABLE IF EXISTS `art_themes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_themes` (
  `styleid` smallint(6) unsigned NOT NULL auto_increment,
  `issys` tinyint(1) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `name` varchar(15) NOT NULL default '',
  `color` varchar(255) NOT NULL default '',
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`styleid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `art_themes`
--

INSERT INTO `art_themes` VALUES (1,1,'default','default','#FFFFFF',0);
INSERT INTO `art_themes` VALUES (2,1,'white','white','#FFFFFF',1);

--
-- Table structure for table `art_usergroups`
--

DROP TABLE IF EXISTS `art_usergroups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_usergroups` (
  `groupid` smallint(6) unsigned NOT NULL auto_increment,
  `groupname` char(30) NOT NULL default '',
  `adminid` tinyint(3) NOT NULL default '0',
  `type` enum('member','other','inner') NOT NULL default 'member',
  `accesslevel` tinyint(3) unsigned NOT NULL default '0',
  `isvisit` tinyint(1) NOT NULL default '1',
  `isarticle` tinyint(1) NOT NULL default '0',
  `isarticlefree` tinyint(1) NOT NULL default '0',
  `isupload` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `art_usergroups`
--

INSERT INTO `art_usergroups` VALUES (1,'Admin',1,'inner',150,1,1,1,1);
INSERT INTO `art_usergroups` VALUES (2,'Editor',2,'inner',100,1,1,1,1);
INSERT INTO `art_usergroups` VALUES (3,'VIP',0,'inner',90,1,1,1,1);
INSERT INTO `art_usergroups` VALUES (4,'NoAccess',0,'inner',0,0,0,0,0);
INSERT INTO `art_usergroups` VALUES (5,'Visitors',0,'inner',1,1,0,0,0);
INSERT INTO `art_usergroups` VALUES (6,'VerifiedMember',0,'inner',5,1,0,0,0);
INSERT INTO `art_usergroups` VALUES (7,'Member',0,'member',10,1,1,0,0);

--
-- Table structure for table `art_userprotected`
--

DROP TABLE IF EXISTS `art_userprotected`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_userprotected` (
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `uname` char(15) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `username` (`uname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_users`
--

DROP TABLE IF EXISTS `art_users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_users` (
  `uid` mediumint(8) unsigned NOT NULL auto_increment,
  `uname` char(15) NOT NULL default '',
  `email` char(40) NOT NULL default '',
  `password` char(32) NOT NULL default '',
  `salt` char(8) NOT NULL default '',
  `adminid` tinyint(1) NOT NULL default '0',
  `groupid` smallint(6) unsigned NOT NULL default '0',
  `sex` tinyint(1) NOT NULL default '0',
  `createip` char(15) NOT NULL default '',
  `createtime` int(10) unsigned NOT NULL default '0',
  `replycount` mediumint(8) unsigned NOT NULL default '0',
  `lastreplytime` int(10) unsigned NOT NULL default '0',
  `lastip` char(15) NOT NULL default '',
  `lastvisit` int(10) NOT NULL default '0',
  `timeformat` tinyint(1) NOT NULL default '0',
  `timeoffset` char(4) NOT NULL default '99',
  `birthday` date NOT NULL default '0000-00-00',
  `emailverify` tinyint(1) NOT NULL default '0',
  `sendemail` tinyint(1) NOT NULL default '1',
  `showemail` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`uname`),
  KEY `email` (`email`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_plugins`
--

DROP TABLE IF EXISTS `art_plugins`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_plugins` (
  `pluginid` smallint(6) unsigned NOT NULL auto_increment,
  `plugintitle` varchar(50) NOT NULL default '',
  `pluginname` varchar(50) NOT NULL default '',
  `ismanage` tinyint(1) NOT NULL default '0',
  `isnav` tinyint(1) NOT NULL default '0',
  `copyright` varchar(255) NOT NULL default '',
  `version` varchar(15) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `hook` text NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`pluginid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_caches`
--

DROP TABLE IF EXISTS `art_caches`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_caches` (
  `keyid` char(16) NOT NULL default '',
  `value` mediumtext NOT NULL,
  `expire` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`keyid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_polls`
--

DROP TABLE IF EXISTS `art_polls`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_polls` (
  `pollid` mediumint(8) NOT NULL auto_increment,
  `title` char(80) NOT NULL default '',
  `ismore` tinyint(1) NOT NULL default '0',
  `hits` mediumint(8) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `expire` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pollid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_pollsoptions`
--

DROP TABLE IF EXISTS `art_pollsoptions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_pollsoptions` (
  `optionid` mediumint(10) unsigned NOT NULL auto_increment,
  `pollid` mediumint(8) unsigned NOT NULL default '0',
  `total` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(80) NOT NULL default '',
  `detail` mediumtext NOT NULL,
  PRIMARY KEY  (`optionid`),
  KEY `aid` (`pollid`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_announce`
--

DROP TABLE IF EXISTS `art_announce`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_announce` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `author` varchar(15) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  `message` text NOT NULL,
  `displaysort` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `timespan` (`dateline`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_loginlog`
--

DROP TABLE IF EXISTS `art_loginlog`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_loginlog` (
  `ip` char(15) NOT NULL default '',
  `count` tinyint(1) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `art_ad`
--

DROP TABLE IF EXISTS `art_ad`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_ad` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) character set gbk NOT NULL default '',
  `typeid` smallint(6) unsigned NOT NULL default '0',
  `args` text character set gbk NOT NULL,
  `code` text character set gbk NOT NULL,
  `begintime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `displaysort` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

INSERT INTO `art_ad` VALUES (1,'Head',1,'a:1:{s:5:\"style\";s:4:\"code\";}','',1292947200,0,0,0);
INSERT INTO `art_ad` VALUES (2,'Foot',1,'a:1:{s:5:\"style\";s:4:\"code\";}','',1292947200,0,0,0);
INSERT INTO `art_ad` VALUES (3,'Article_middle',1,'a:1:{s:5:\"style\";s:4:\"code\";}','',1292947200,0,0,0);

--
-- Table structure for table `art_adtype`
--

DROP TABLE IF EXISTS `art_adtype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `art_adtype` (
  `typeid` smallint(6) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`typeid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `art_adtype`
--

INSERT INTO `art_adtype` VALUES (1,'Default');