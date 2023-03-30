/*
Navicat MySQL Data Transfer

Source Server         : PROD_100
Source Server Version : 50560
Source Host           : 10.0.0.100:3306
Source Database       : nombremania

Target Server Type    : MYSQL
Target Server Version : 50560
File Encoding         : 65001

Date: 2017-01-13 00:04:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `SESSKEY` varchar(32) NOT NULL DEFAULT '',
  `EXPIRY` int(11) unsigned NOT NULL DEFAULT '0',
  `DATA` text NOT NULL,
  PRIMARY KEY (`SESSKEY`),
  KEY `EXPIRY` (`EXPIRY`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET FOREIGN_KEY_CHECKS=1;
