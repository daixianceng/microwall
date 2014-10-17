-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2014 年 10 月 17 日 04:41
-- 服务器版本: 5.5.27
-- PHP 版本: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `blog`
--

-- --------------------------------------------------------

--
-- 表的结构 `mw_authassignment`
--

CREATE TABLE IF NOT EXISTS `mw_authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mw_authassignment`
--

INSERT INTO `mw_authassignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('Administrator', '1', NULL, 'N;');

-- --------------------------------------------------------

--
-- 表的结构 `mw_authitem`
--

CREATE TABLE IF NOT EXISTS `mw_authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`),
  KEY `type` (`type`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mw_authitem`
--

INSERT INTO `mw_authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Administrator', 2, 'Administrator', NULL, 'N;'),
('Author', 2, 'Author', NULL, 'N;'),
('createAuthorUser', 1, '添加作者角色用户', 'return $params[''role''] === ''Author'';', 'N;'),
('createCategory', 0, '添加分类', NULL, 'N;'),
('createPage', 0, '添加页面', NULL, 'N;'),
('createPost', 0, '写文章', NULL, 'N;'),
('createUser', 0, '添加用户', NULL, 'N;'),
('editAuthorUser', 1, '编辑作者角色用户', 'return $params[''role''] === ''Author'';', 'N;'),
('editCategory', 0, '编辑分类', NULL, 'N;'),
('Editor', 2, 'Editor', NULL, 'N;'),
('editOwnPost', 1, '编辑自己的文章', 'return Yii::app()->user->id === $params[''userId''];', 'N;'),
('editOwnUser', 1, '编辑自己的资料', 'return Yii::app()->user->id === $params[''userId''];', 'N;'),
('editPage', 0, '编辑页面', NULL, 'N;'),
('editPost', 0, '编辑文章', NULL, 'N;'),
('editUser', 0, '编辑用户', NULL, 'N;'),
('openCreateUser', 0, '打开添加用户页面', NULL, 'N;'),
('recycleOwnPost', 1, '把自己的文章放入回收站', 'return Yii::app()->user->id === $params[''userId''];', 'N;'),
('recyclePage', 0, '把页面放入回收站', NULL, 'N;'),
('recyclePost', 0, '把文章放入回收站', NULL, 'N;'),
('removeAuthorUser', 1, '删除作者角色用户', 'return $params[''role''] === ''Author'';', 'N;'),
('removeCategory', 0, '移除分类', NULL, 'N;'),
('removeComment', 0, '移除评论', NULL, 'N;'),
('removeOwnPost', 1, '彻底删除自己的文章', 'return Yii::app()->user->id === $params[''userId''];', 'N;'),
('removePage', 0, '移除页面', NULL, 'N;'),
('removePost', 0, '彻底删除文章', NULL, 'N;'),
('removeUser', 0, '删除用户', 'return Yii::app()->user->id !== $params[''userId''];', 'N;'),
('setWebsite', 0, '设置网站', NULL, 'N;');

-- --------------------------------------------------------

--
-- 表的结构 `mw_authitemchild`
--

CREATE TABLE IF NOT EXISTS `mw_authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  KEY `parent` (`parent`),
  KEY `parent_2` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `mw_authitemchild`
--

INSERT INTO `mw_authitemchild` (`parent`, `child`) VALUES
('Editor', 'createAuthorUser'),
('Administrator', 'createCategory'),
('Editor', 'createCategory'),
('Administrator', 'createPage'),
('Editor', 'createPage'),
('Administrator', 'createPost'),
('Author', 'createPost'),
('Editor', 'createPost'),
('Administrator', 'createUser'),
('createAuthorUser', 'createUser'),
('Editor', 'editAuthorUser'),
('Administrator', 'editCategory'),
('Editor', 'editCategory'),
('Author', 'editOwnPost'),
('Author', 'editOwnUser'),
('Editor', 'editOwnUser'),
('Administrator', 'editPage'),
('Editor', 'editPage'),
('Administrator', 'editPost'),
('Editor', 'editPost'),
('editOwnPost', 'editPost'),
('Administrator', 'editUser'),
('editAuthorUser', 'editUser'),
('editOwnUser', 'editUser'),
('Administrator', 'openCreateUser'),
('Editor', 'openCreateUser'),
('Author', 'recycleOwnPost'),
('Administrator', 'recyclePage'),
('Editor', 'recyclePage'),
('Administrator', 'recyclePost'),
('Editor', 'recyclePost'),
('recycleOwnPost', 'recyclePost'),
('Editor', 'removeAuthorUser'),
('Administrator', 'removeCategory'),
('Editor', 'removeCategory'),
('Administrator', 'removeComment'),
('Editor', 'removeComment'),
('Author', 'removeOwnPost'),
('Administrator', 'removePage'),
('Editor', 'removePage'),
('Administrator', 'removePost'),
('Editor', 'removePost'),
('removeOwnPost', 'removePost'),
('Administrator', 'removeUser'),
('removeAuthorUser', 'removeUser'),
('Administrator', 'setWebsite');

-- --------------------------------------------------------

--
-- 表的结构 `mw_category`
--

CREATE TABLE IF NOT EXISTS `mw_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '类名',
  `slug` varchar(20) NOT NULL COMMENT '索引名',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '注释',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='文章分类' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mw_comment`
--

CREATE TABLE IF NOT EXISTS `mw_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '评论者',
  `mail` varchar(40) NOT NULL COMMENT '邮箱',
  `website` varchar(255) NOT NULL DEFAULT '' COMMENT '网站',
  `content` text NOT NULL COMMENT '内容',
  `post_id` int(10) unsigned NOT NULL COMMENT '文章id',
  `reply_id` int(10) unsigned DEFAULT NULL COMMENT '回复id',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`reply_id`),
  KEY `post_id_2` (`post_id`),
  KEY `reply_id` (`reply_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='评论表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mw_page`
--

CREATE TABLE IF NOT EXISTS `mw_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('local','link') NOT NULL COMMENT '类型',
  `title` varchar(255) NOT NULL COMMENT '页面名称',
  `slug` varchar(80) DEFAULT NULL COMMENT 'url索引名',
  `content` text NOT NULL COMMENT '内容',
  `date_publish` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布日期',
  `date_update` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  `date_trash` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` enum('1001','1010','1011') NOT NULL DEFAULT '1001' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `date` (`date_publish`,`sort`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `date_update` (`date_update`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='page' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mw_post`
--

CREATE TABLE IF NOT EXISTS `mw_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '文章名',
  `slug` char(80) DEFAULT NULL COMMENT '标题索引',
  `description` text NOT NULL COMMENT '文章描述',
  `content` text NOT NULL COMMENT '文章内容',
  `date_publish` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `date_update` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `date_trash` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  `author` int(10) unsigned NOT NULL COMMENT '作者id',
  `category` int(10) unsigned NOT NULL COMMENT '分类id',
  `pic` varchar(20) NOT NULL DEFAULT '' COMMENT '文章主图片',
  `status` enum('1001','1010','1011') NOT NULL DEFAULT '1001' COMMENT '文章状态，1001:公开;1010:草稿;1011:回收站',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '访问量',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `date_publish` (`date_publish`,`date_update`,`author`,`category`,`status`),
  KEY `category` (`category`),
  KEY `author` (`author`),
  KEY `status` (`status`),
  KEY `date_update` (`date_update`),
  KEY `date_publish_2` (`date_publish`),
  KEY `slug_2` (`slug`),
  KEY `date_trash` (`date_trash`),
  KEY `views` (`views`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mw_user`
--

CREATE TABLE IF NOT EXISTS `mw_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL COMMENT '用户名',
  `password` char(40) NOT NULL COMMENT '被加密的密码',
  `mail` varchar(40) NOT NULL DEFAULT '' COMMENT 'email',
  `nickname` varchar(40) NOT NULL COMMENT '昵称',
  `avatar` varchar(40) NOT NULL COMMENT '头像',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '一句话介绍',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `password` (`password`),
  KEY `name_2` (`name`),
  KEY `password_2` (`password`),
  KEY `mail` (`mail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户列表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `mw_user`
--

INSERT INTO `mw_user` (`id`, `name`, `password`, `mail`, `nickname`, `avatar`, `description`) VALUES
(1, 'admin', 'e6890cdfdf8da23ef5ea11318e106a0394fa605e', '', 'Administrator', 'default/administrator.png', '');

--
-- 限制导出的表
--

--
-- 限制表 `mw_authassignment`
--
ALTER TABLE `mw_authassignment`
  ADD CONSTRAINT `mw_authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `mw_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `mw_authitemchild`
--
ALTER TABLE `mw_authitemchild`
  ADD CONSTRAINT `mw_authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `mw_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mw_authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `mw_authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `mw_comment`
--
ALTER TABLE `mw_comment`
  ADD CONSTRAINT `mw_comment_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `mw_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mw_comment_ibfk_5` FOREIGN KEY (`post_id`) REFERENCES `mw_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mw_comment_ibfk_6` FOREIGN KEY (`reply_id`) REFERENCES `mw_comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `mw_post`
--
ALTER TABLE `mw_post`
  ADD CONSTRAINT `mw_post_ibfk_1` FOREIGN KEY (`category`) REFERENCES `mw_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mw_post_ibfk_2` FOREIGN KEY (`author`) REFERENCES `mw_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
