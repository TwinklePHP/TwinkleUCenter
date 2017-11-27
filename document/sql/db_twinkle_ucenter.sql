
-- ----------------------------
-- Table structure for user_address
-- ----------------------------
DROP TABLE IF EXISTS `user_address`;
CREATE TABLE `user_address` (
  `address_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `email` varchar(120) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `first_name` varchar(120) NOT NULL DEFAULT '' COMMENT '联系人',
  `last_name` varchar(120) NOT NULL DEFAULT '' COMMENT '联系人',
  `region_id` int(10) NOT NULL DEFAULT '0' COMMENT '国家ID',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省分',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `addressline1` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `addressline2` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `zip_code` varchar(64) NOT NULL DEFAULT '' COMMENT '邮编',
  `tel` varchar(64) NOT NULL DEFAULT '' COMMENT '联系电话',
  `card_number` varchar(64) NOT NULL DEFAULT '' COMMENT '身份证号',
  `phone_code` varchar(16) NOT NULL DEFAULT '' COMMENT '国家区号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `up_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`address_id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_extend
-- ----------------------------
DROP TABLE IF EXISTS `user_extend`;
CREATE TABLE `user_extend` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_lang` char(2) NOT NULL DEFAULT 'en' COMMENT '最后登录语言',
  `last_login_plat` tinyint(3) NOT NULL DEFAULT '0' COMMENT '最后登录平台',
  `order_count` int(10) NOT NULL DEFAULT '0' COMMENT '下单次数',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_info
-- ----------------------------
DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `email` varchar(120) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '用户密码',
  `salt` varchar(32) NOT NULL DEFAULT '' COMMENT '加点盐',
  `first_name` varchar(120) NOT NULL DEFAULT '' COMMENT '用户名',
  `last_name` varchar(120) NOT NULL DEFAULT '' COMMENT '用户名',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户性别:0未知,1男,2女',
  `msn` varchar(60) NOT NULL DEFAULT '' COMMENT 'msn号',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '联系电话',
  `is_validated` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否验证邮箱',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `lang` char(2) NOT NULL DEFAULT 'en' COMMENT '注册语言',
  `plat` tinyint(3) NOT NULL DEFAULT '1' COMMENT '注册平台,1pc,2m,3ios,4android',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uniq_email` (`email`),
  KEY `idx_validated` (`is_validated`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_login_log
-- ----------------------------
DROP TABLE IF EXISTS `user_login_log`;
CREATE TABLE `user_login_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `lang` char(2) NOT NULL DEFAULT 'en' COMMENT '登录语言',
  `plat` tinyint(3) NOT NULL DEFAULT '1' COMMENT '登录平台',
  `ip` varchar(64) NOT NULL DEFAULT '' COMMENT '登录IP',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_open
-- ----------------------------
DROP TABLE IF EXISTS `user_open`;
CREATE TABLE `user_open` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `site_name` varchar(32) NOT NULL DEFAULT '' COMMENT '第三方网站标识',
  `open_id` varchar(64) NOT NULL DEFAULT '' COMMENT '第三方登录ID，如fb_uid',
  `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT 'access_token',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uniq_user_site` (`user_id`,`site_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
