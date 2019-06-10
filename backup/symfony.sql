/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100113
Source Host           : localhost:3306
Source Database       : symfony

Target Server Type    : MYSQL
Target Server Version : 100113
File Encoding         : 65001

Date: 2017-04-27 14:55:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for js
-- ----------------------------
DROP TABLE IF EXISTS `js`;
CREATE TABLE `js` (
  `corporation_permissions_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(20) COLLATE utf8_bin NOT NULL,
  `menu_des` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `menu_parent` int(11) DEFAULT NULL,
  `menu_link` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `menu_sort` int(5) NOT NULL,
  `menu_show` int(11) NOT NULL,
  `menu_code` varchar(120) COLLATE utf8_bin NOT NULL,
  `delete_flg` int(11) NOT NULL,
  PRIMARY KEY (`corporation_permissions_menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='菜单权限表';

-- ----------------------------
-- Records of js
-- ----------------------------
INSERT INTO `js` VALUES ('1', '博客显示', '博客', '0', 'blog/show', '0', '1', 'MTQ0NjExNTAxNg==', '0');
INSERT INTO `js` VALUES ('2', '博文添加', '博客', '0', 'blog/add', '0', '1', 'MTQ0NjExNTA0Mg==', '0');
INSERT INTO `js` VALUES ('3', '博文删除', '博客', '0', 'blog/del', '0', '1', 'MTQ0NjExNTA2Nw==', '0');
INSERT INTO `js` VALUES ('4', '博文修改', '博客', '0', 'blog/update', '0', '1', '', '0');
INSERT INTO `js` VALUES ('5', 'SQL审核显示', 'SQL审核', '0', 'inception/show', '0', '1', '', '0');
INSERT INTO `js` VALUES ('6', 'SQL审核执行', 'SQL审核', '0', null, '0', '1', '', '0');
INSERT INTO `js` VALUES ('7', '日常办公显示', '日常办公', '0', 'tool/show', '0', '1', '', '0');
INSERT INTO `js` VALUES ('8', '天气', '日常办公', '0', 'tool/weather', '0', '1', '', '0');
INSERT INTO `js` VALUES ('9', '时间', '日常办公', '0', 'tool/unixtime', '0', '1', '', '0');
INSERT INTO `js` VALUES ('10', '记事本', '日常办公', '0', 'tool/nodebook', '0', '1', '', '0');
INSERT INTO `js` VALUES ('11', '正则表达式', '日常办公', '0', null, '0', '1', '', '0');
INSERT INTO `js` VALUES ('12', 'Unix时间', '日常办公', '0', 'tool/unixtime', '0', '1', '', '0');
INSERT INTO `js` VALUES ('13', '运行PHP', '日常办公', '0', 'tool/runphp', '0', '1', '', '0');
INSERT INTO `js` VALUES ('14', '在线翻译', '日常办公', '0', 'tool/translate', '0', '1', '', '0');
INSERT INTO `js` VALUES ('15', 'Json加密解密', '日常办公', '0', 'tool/json', '0', '1', '', '0');

-- ----------------------------
-- Table structure for js_invitation_no
-- ----------------------------
DROP TABLE IF EXISTS `js_invitation_no`;
CREATE TABLE `js_invitation_no` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invitation_no` int(11) NOT NULL COMMENT '邀请码',
  `user_id` int(11) NOT NULL,
  `is_del` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '是否被使用 0未使用  1使用',
  `role_id` int(11) DEFAULT NULL COMMENT '权限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of js_invitation_no
-- ----------------------------

-- ----------------------------
-- Table structure for js_system_logs
-- ----------------------------
DROP TABLE IF EXISTS `js_system_logs`;
CREATE TABLE `js_system_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `content` varchar(2000) NOT NULL,
  `function_name` varchar(100) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `operation_type` varchar(1000) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=684 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of js_system_logs
-- ----------------------------
INSERT INTO `js_system_logs` VALUES ('648', 'role:1', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2016-10-02 23:56:06', '用户登录', '1', '用户登录IP：127.0.0.1');
INSERT INTO `js_system_logs` VALUES ('649', 'role:1登录系统', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2016-10-03 00:09:41', '用户登录', '1', '用户登录IP：127.0.0.1');
INSERT INTO `js_system_logs` VALUES ('650', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-26 13:51:37', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('652', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-26 15:12:31', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('653', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-26 16:01:52', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('654', '用户退出:liuxue5213', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-26 16:23:19', '用户退出', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('655', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-26 16:25:15', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('656', '用户退出:liuxue5213', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-26 17:10:45', '用户退出', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('657', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 09:41:28', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('658', '用户退出:liuxue5213', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 09:42:43', '用户退出', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('659', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 13:00:17', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('660', '用户退出:liuxue5213', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 13:00:26', '用户退出', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('661', '用户登录:wintel', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 13:35:59', '用户登录', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('662', '用户退出:wintel', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 13:37:34', '用户退出', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('666', '用户登录:wintel', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 13:50:32', '用户登录', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('667', '用户退出:wintel', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 13:50:46', '用户退出', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('668', '用户登录:wintel', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 13:51:07', '用户登录', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('669', '用户退出:wintel', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 13:55:51', '用户退出', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('670', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 13:56:22', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('671', '用户退出:liuxue5213', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 13:58:46', '用户退出', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('672', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 13:59:05', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('673', '用户退出:liuxue5213', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 14:01:52', '用户退出', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('674', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 14:02:07', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('675', '用户退出:liuxue5213', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 14:05:18', '用户退出', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('676', '用户登录:liuxue5213', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 14:05:47', '用户登录', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('677', '用户退出:liuxue5213', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 14:14:40', '用户退出', '1', '::1');
INSERT INTO `js_system_logs` VALUES ('678', '用户登录:wintel', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 14:14:56', '用户登录', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('679', '用户退出:wintel', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 14:15:32', '用户退出', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('680', '用户登录:wintel', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 14:15:52', '用户登录', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('681', '用户退出:wintel', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 14:41:42', '用户退出', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('682', '用户登录:wintel', 'UserBundle\\Controller\\DefaultController::postLoginAction', '2017-04-27 14:41:54', '用户登录', '7', '::1');
INSERT INTO `js_system_logs` VALUES ('683', '用户退出:wintel', 'UserBundle\\Controller\\DefaultController::logoutAction', '2017-04-27 14:51:27', '用户退出', '7', '::1');

-- ----------------------------
-- Table structure for js_system_menus
-- ----------------------------
DROP TABLE IF EXISTS `js_system_menus`;
CREATE TABLE `js_system_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `parent_id` int(11) NOT NULL,
  `menu_link` varchar(255) COLLATE utf8_bin NOT NULL,
  `menu_sort` int(5) NOT NULL,
  `is_del` tinyint(1) NOT NULL DEFAULT '0',
  `menu_type` varchar(50) COLLATE utf8_bin NOT NULL,
  `menu_code` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='菜单权限表';

-- ----------------------------
-- Records of js_system_menus
-- ----------------------------
INSERT INTO `js_system_menus` VALUES ('24', '博客', '0', 'blog/index', '1', '0', 'index', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('25', 'SQL审核', '0', 'sql/index', '2', '0', 'index', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('26', '日常办公', '0', 'work/index', '3', '0', 'type', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('27', '系统设置', '0', 'setting/index', '5', '0', 'index', 'fa fa-cog');
INSERT INTO `js_system_menus` VALUES ('28', '用户管理', '0', 'info/index', '4', '0', '', 'fa user-circle');
INSERT INTO `js_system_menus` VALUES ('35', '天气', '24', 'tool/weather', '0', '0', '', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('36', '时间', '25', 'tool/unixtime', '0', '0', '', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('37', '记事本', '26', 'tool/nodebook', '0', '0', '', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('38', '正则表达式', '25', 'tool/preg', '0', '0', '', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('39', '在线翻译', '28', 'tool/translate', '0', '0', '', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('40', 'Json加密解密', '25', 'tool/json', '0', '0', '', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('41', '运行PHP', '26', 'tool/php', '0', '0', '', 'fa fa-link');
INSERT INTO `js_system_menus` VALUES ('42', '聊天室', '0', '', '0', '0', '', 'fa wechat ');
INSERT INTO `js_system_menus` VALUES ('43', '账户激活', '27', 'setting/account/open', '0', '0', '', 'fa ');

-- ----------------------------
-- Table structure for js_system_options
-- ----------------------------
DROP TABLE IF EXISTS `js_system_options`;
CREATE TABLE `js_system_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '配置选项名',
  `value` text COLLATE utf8_unicode_ci COMMENT '配置选项值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_option_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统配置选项表';

-- ----------------------------
-- Records of js_system_options
-- ----------------------------
INSERT INTO `js_system_options` VALUES ('1', 'website_keywords', '芽丝内容管理框架,基础开发版,芽丝网,YʌS Network');
INSERT INTO `js_system_options` VALUES ('2', 'company_address', '');
INSERT INTO `js_system_options` VALUES ('3', 'website_title', '芽丝内容管理框架 (YASCMF)');
INSERT INTO `js_system_options` VALUES ('4', 'company_telephone', '800-168-8888');
INSERT INTO `js_system_options` VALUES ('5', 'company_full_name', '芽丝网络科技有限公司');
INSERT INTO `js_system_options` VALUES ('6', 'website_icp', '鄂ICP备15014910号');
INSERT INTO `js_system_options` VALUES ('7', 'system_version', '5.2');
INSERT INTO `js_system_options` VALUES ('8', 'page_size', '15');
INSERT INTO `js_system_options` VALUES ('9', 'system_logo', '/assets/img/yas_logo.png');
INSERT INTO `js_system_options` VALUES ('10', 'picture_watermark', '/assets/img/yas_logo.png');
INSERT INTO `js_system_options` VALUES ('11', 'company_short_name', '芽丝网');
INSERT INTO `js_system_options` VALUES ('12', 'system_author', '豆芽丝');
INSERT INTO `js_system_options` VALUES ('13', 'system_author_website', 'http://douyasi.com');
INSERT INTO `js_system_options` VALUES ('14', 'is_watermark', '0');

-- ----------------------------
-- Table structure for js_system_roles
-- ----------------------------
DROP TABLE IF EXISTS `js_system_roles`;
CREATE TABLE `js_system_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0 禁用   1启用',
  `create_date` datetime DEFAULT NULL,
  `remark` varchar(1000) DEFAULT NULL,
  `menu_ids` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of js_system_roles
-- ----------------------------
INSERT INTO `js_system_roles` VALUES ('1', 'admin', '0', '1', '2016-09-30 15:08:52', '后台管理员', '24,25,26,27,28');
INSERT INTO `js_system_roles` VALUES ('3', 'anonymous', '0', '1', '2016-09-30 15:09:32', '访客', '27');
INSERT INTO `js_system_roles` VALUES ('4', 'develop', '0', '1', '2016-09-30 15:09:52', '开发人员', null);
INSERT INTO `js_system_roles` VALUES ('5', 'normal', '0', '1', '2016-09-30 15:10:45', '日常使用者', null);

-- ----------------------------
-- Table structure for js_system_users
-- ----------------------------
DROP TABLE IF EXISTS `js_system_users`;
CREATE TABLE `js_system_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '登录用户名',
  `nickname` varchar(255) NOT NULL COMMENT '显示昵称',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `url` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL COMMENT '创建日期',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态  0禁用  1正常',
  `last_login_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '最后登录时间',
  `is_open_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否激活使用  0 未激活',
  PRIMARY KEY (`id`),
  KEY `user_login_key` (`username`),
  KEY `user_nicename` (`nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of js_system_users
-- ----------------------------
INSERT INTO `js_system_users` VALUES ('1', 'liuxue5213', 'liuxue5213', '$2y$10$OVxoWdKJxpc.6MAsNQbOt.DqMiMHPPWeGcHOsy/HGWL.mTkxjaoCW', '956149307@qq.com', 'johnscott1989', '2016-10-03 00:09:41', '1', '2017-04-27 14:05:47', '1');
INSERT INTO `js_system_users` VALUES ('7', 'wintel', 'JSID1493271293', '$2y$10$OB7Kb2ZIKQwZ4.kxSKsZ/O3zKBAhZTAClgU9LBZJkOP9I4LzK1LsK', '956149307@qq.com', '', '2017-04-27 13:34:53', '1', '2017-04-27 14:41:54', '0');

-- ----------------------------
-- Table structure for js_system_user_role
-- ----------------------------
DROP TABLE IF EXISTS `js_system_user_role`;
CREATE TABLE `js_system_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `menuid` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of js_system_user_role
-- ----------------------------
INSERT INTO `js_system_user_role` VALUES ('1', '1', '1', '0');
INSERT INTO `js_system_user_role` VALUES ('10', '3', '7', '0');
