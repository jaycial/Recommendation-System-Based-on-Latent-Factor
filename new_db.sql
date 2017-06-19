/*
 Navicat Premium Data Transfer

 Source Server         : itjuzi
 Source Server Type    : MySQL
 Source Server Version : 50633
 Source Host           : 192.168.10.10
 Source Database       : new_db

 Target Server Type    : MySQL
 Target Server Version : 50633
 File Encoding         : utf-8

 Date: 06/19/2017 10:52:44 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `tbl_news`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_news`;
CREATE TABLE `tbl_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `news_url` varchar(255) DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `source_time` datetime DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `content` text CHARACTER SET utf8,
  `from` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `abstract` text CHARACTER SET utf8,
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6823 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `tbl_rel_user_like_news`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_rel_user_like_news`;
CREATE TABLE `tbl_rel_user_like_news` (
  `rel_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `news_id` int(11) DEFAULT NULL,
  `like_status` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  PRIMARY KEY (`rel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `tbl_result_martix`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_result_martix`;
CREATE TABLE `tbl_result_martix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `score` double DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `tbl_user`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
