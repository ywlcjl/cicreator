/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Version : 50538
 Source Host           : localhost
 Source Database       : cicreator

 Target Server Version : 50538
 File Encoding         : utf-8

 Date: 12/21/2015 17:31:21 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '登录名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `is_root` tinyint(1) NOT NULL DEFAULT '0' COMMENT '最高管理权限,1为是,0为普通',
  `admin_permission` text COMMENT '权限字符集',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理员状态:0停用,1启用',
  `login_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后登录时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `admin`
-- ----------------------------
BEGIN;
INSERT INTO `admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1', '1|2', '1', '2015-12-21 12:46:55', '2015-08-22 21:37:31', '2014-08-10 15:49:21'), ('2', 'ywlcjl', '4d24c6126e9e208f42fb2cfc480dfdb9', '0', '1|2', '1', '2015-12-16 12:30:02', '2015-09-01 18:30:06', '2015-09-01 18:29:57');
COMMIT;

-- ----------------------------
--  Table structure for `admin_permission`
-- ----------------------------
DROP TABLE IF EXISTS `admin_permission`;
CREATE TABLE `admin_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '权限名称',
  `desc_txt` varchar(255) NOT NULL DEFAULT '' COMMENT '权限代号',
  `is_root` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态:0无效,1有效',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `admin_permission`
-- ----------------------------
BEGIN;
INSERT INTO `admin_permission` VALUES ('1', '系统权限', '管理后台设置,管理员设置.', '1', '1', '2015-12-18 12:03:42', '2012-03-26 11:00:55'), ('2', '文章权限', '文章,分类,附件设置.', '1', '1', '2015-12-18 12:03:51', '2012-03-26 11:01:03');
COMMIT;

-- ----------------------------
--  Table structure for `article`
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `author` varchar(50) NOT NULL DEFAULT '' COMMENT '作者名称',
  `source` varchar(50) NOT NULL DEFAULT '',
  `cover_pic` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `desc_txt` text COMMENT '文章描述',
  `content` text COMMENT '文章内容',
  `hop_link` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转链接',
  `top` tinyint(4) NOT NULL DEFAULT '0' COMMENT '推荐',
  `article_category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `post_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发表日期',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `article_category`
-- ----------------------------
DROP TABLE IF EXISTS `article_category`;
CREATE TABLE `article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父分类id',
  `hop_link` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转链接',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新日期',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `article_category`
-- ----------------------------
BEGIN;
INSERT INTO `article_category` VALUES ('1', '默认分类', '0', '', '0', '1', '2015-12-18 16:38:37', '2012-05-28 13:24:20');
COMMIT;

-- ----------------------------
--  Table structure for `attach`
-- ----------------------------
DROP TABLE IF EXISTS `attach`;
CREATE TABLE `attach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名称',
  `orig_name` varchar(255) DEFAULT '' COMMENT '上传前文件名称',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `type` varchar(10) NOT NULL DEFAULT '' COMMENT '$status$png:png|jpg:jpg|jpe:jpe|jpeg:jpeg|gif:gif',
  `article_id` int(11) NOT NULL DEFAULT '0' COMMENT '$id$article',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `example`
-- ----------------------------
DROP TABLE IF EXISTS `example`;
CREATE TABLE `example` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `desc_txt` text,
  `price` float NOT NULL DEFAULT '0' COMMENT '$max$',
  `article_category` int(11) NOT NULL DEFAULT '0' COMMENT '$id$article',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '$array$0:停用|1:启用',
  `post_time` date NOT NULL DEFAULT '0000-00-00',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `setting`
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL COMMENT '键',
  `value` varchar(255) NOT NULL DEFAULT '' COMMENT '值',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态:0禁用,1启用',
  `txt` varchar(255) NOT NULL DEFAULT '' COMMENT '说明',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `setting`
-- ----------------------------
BEGIN;
INSERT INTO `setting` VALUES ('1', 'attach_thumb_width', '400', '1', '附件图片缩略图宽度', '2015-08-22 22:19:23', '0000-00-00 00:00:00'), ('2', 'attach_thumb_height', '400', '1', '附件图片缩略图高度', '2015-08-22 22:19:27', '0000-00-00 00:00:00'), ('3', 'attach_quality', '90', '1', '图片质量', '2015-08-22 22:19:10', '0000-00-00 00:00:00');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
