SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cm_action_log
-- ----------------------------
DROP TABLE IF EXISTS `cm_action_log`;
CREATE TABLE `cm_action_log` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '执行会员id',
  `module` varchar(30) NOT NULL DEFAULT 'admin' COMMENT '模块',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '行为',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '执行的URL',
  `ip` char(30) NOT NULL DEFAULT '' COMMENT '执行行为者ip',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='行为日志表';

-- ----------------------------
-- Records of cm_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for cm_admin
-- ----------------------------
DROP TABLE IF EXISTS `cm_admin`;
CREATE TABLE `cm_admin` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `leader_id` mediumint(8) NOT NULL DEFAULT '1',
  `username` varchar(20) DEFAULT '0',
  `nickname` varchar(40) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `email` varchar(80) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员信息';

-- ----------------------------
-- Records of cm_admin
-- ----------------------------

-- ----------------------------
-- Table structure for cm_user_app
-- ----------------------------
DROP TABLE IF EXISTS `cm_user_app`;
CREATE TABLE `cm_user_app` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) DEFAULT '0' COMMENT '商户id',
  `key` varchar(32) DEFAULT '' COMMENT 'API验证KEY',
  `sitename` varchar(30) NOT NULL DEFAULT '' COMMENT '站点名称',
  `domain` varchar(100) NOT NULL DEFAULT '' COMMENT '商户验证域名',
  `daily` decimal(12,3) NOT NULL DEFAULT '20000.000' COMMENT '日限访问（超过就锁）',
  `secretkey` text COMMENT '商户请求RSA私钥',
  `auth_ips` text COMMENT '请求授权IP',
  `role` int(4) NOT NULL DEFAULT '0' COMMENT '角色1-普通商户,角色2-特约商户',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商户API状态,0-禁用,1-锁,2-正常',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_domain_unique` (`id`,`domain`,`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商户信息表';

-- ----------------------------
-- Records of cm_user_app
-- ----------------------------
INSERT INTO `cm_user_app` VALUES ('1', '100001', '772ae1d32322f49508307b2f31a0107f', '小红帽', 'https://www.redcap.cn', '20000.000', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtJKWdvG8MDILqwcoR721+pTT8ClC+5vq60pfXQAFsoIt8E6oQsDgMIdvp6FP2YjCeTJrr9MjQoC7t8yXO+liau70bMNbds/wg8arh+8jIHYDNIu4nFHlDTdk9y72xWAQixnGT3F/zSoLWv8LvrmfOHDSByD+/RPeiS04/GwVr/SLlbxSp+Rf7ano//5CD9XjD6jVz7IwBcurmqrqenRujNBDAZOncKbKhWfs3AdWhj4iQZeptYtHo3NXc+s3ehdqgEt6qukAENBApx1ROYAyZG6O2b4okzWW+rrJeDWdNKeixyw4nQjtINR/t82cH8xMTSky41N3N7L2eB0tAc/PhQIDAQAB', '192.168.31.239,127.0.0.1,47.107.247.7', '2', '1', '1541787044', '1544368481');

-- ----------------------------
-- Table structure for cm_article
-- ----------------------------
DROP TABLE IF EXISTS `cm_article`;
CREATE TABLE `cm_article` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `author` char(20) NOT NULL DEFAULT 'admin' COMMENT '作者',
  `title` char(40) NOT NULL DEFAULT '' COMMENT '文章名称',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `content` text COMMENT '文章内容',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面图片id',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件id',
  `img_ids` varchar(200) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_index` (`id`,`title`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章表';

-- ----------------------------
-- Records of cm_article
-- ----------------------------

-- ----------------------------
-- Table structure for cm_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `cm_auth_group`;
CREATE TABLE `cm_auth_group` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '用户组名称',
  `describe` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(1000) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='权限组表';

-- ----------------------------
-- Records of cm_auth_group
-- ----------------------------
INSERT INTO `cm_auth_group` VALUES ('1', '1', '', '超级管理员', '拥有至高无上的权利', '1', '超级权限', '1541001599', '1538323200');
INSERT INTO `cm_auth_group` VALUES ('2', '2', '', '管理员', '主要管理者，事情很多，权力很大', '1', '1,2,3,4,5,9,10,11,15,16,32,41,42,17,18,19,43,44,45,20,21,22,23,24,25,26,27,28,29', '1544365067', '1538323200');
INSERT INTO `cm_auth_group` VALUES ('3', '0', '', '编辑', '负责编辑文章和站点公告', '1', '1,15,16,17,32', '1544360098', '1540381656');

-- ----------------------------
-- Table structure for cm_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `cm_auth_group_access`;
CREATE TABLE `cm_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组授权表';

-- ----------------------------
-- Records of cm_auth_group_access
-- ----------------------------
INSERT INTO `cm_auth_group_access` VALUES ('2', '2', '1', '1540793071', '1540793071');
INSERT INTO `cm_auth_group_access` VALUES ('3', '3', '1', '1540800597', '1540800597');

-- ----------------------------
-- Table structure for cm_balance
-- ----------------------------
DROP TABLE IF EXISTS `cm_balance`;
CREATE TABLE `cm_balance` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) NOT NULL COMMENT '商户ID',
  `enable` decimal(12,3) unsigned DEFAULT '0.000' COMMENT '可用余额(已结算金额)',
  `disable` decimal(12,3) unsigned DEFAULT '0.000' COMMENT '冻结金额(待结算金额)',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '账户状态 1正常 0禁止操作',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cash_index` (`id`,`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商户资产表';

-- ----------------------------
-- Records of cm_balance
-- ----------------------------
INSERT INTO `cm_balance` VALUES ('1', '100001', '0.000', '0.000', '1', '1541787044', '1542617892');
-- ----------------------------
-- Table structure for cm_balance_cash
-- ----------------------------
DROP TABLE IF EXISTS `cm_balance_cash`;
CREATE TABLE `cm_balance_cash` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `cash_no` varchar(80) NOT NULL DEFAULT '' COMMENT '取现记录单号',
  `amount` decimal(12,3) NOT NULL DEFAULT '0.000' COMMENT '取现金额',
  `account` int(2) NOT NULL DEFAULT '0' COMMENT '取现账户（关联商户结算账户表）',
  `remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '取现说明',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '取现状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '申请时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '处理时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cash_index` (`id`,`uid`,`cash_no`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户账户取现记录';

-- ----------------------------
-- Records of cm_balance_cash
-- ----------------------------

-- ----------------------------
-- Table structure for cm_balance_change
-- ----------------------------
DROP TABLE IF EXISTS `cm_balance_change`;
CREATE TABLE `cm_balance_change` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `type` varchar(20) NOT NULL DEFAULT 'enable' COMMENT '资金类型',
  `preinc` decimal(12,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '变动前金额',
  `increase` decimal(12,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '增加金额',
  `reduce` decimal(12,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '减少金额',
  `suffixred` decimal(12,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '变动后金额',
  `remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '资金变动说明',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `change_index` (`id`,`uid`,`type`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户资产变动记录表';

-- ----------------------------
-- Records of cm_balance_change
-- ----------------------------

-- ----------------------------
-- Table structure for cm_banker
-- ----------------------------
DROP TABLE IF EXISTS `cm_banker`;
CREATE TABLE `cm_banker` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT COMMENT '银行ID',
  `name` varchar(80) NOT NULL DEFAULT '' COMMENT '银行名称',
  `remarks` varchar(140) NOT NULL DEFAULT '' COMMENT '备注',
  `default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认账户,0-不默认,1-默认',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '银行可用性',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='系统支持银行列表';

-- ----------------------------
-- Records of cm_banker
-- ----------------------------
INSERT INTO `cm_banker` VALUES ('1', '支付宝', '支付宝即时到账', '1', '1', '1535983287', '1535983287');
INSERT INTO `cm_banker` VALUES ('2', '工商银行', '工商银行两小时到账', '0', '1', '1535983287', '1535983287');
INSERT INTO `cm_banker` VALUES ('3', '农业银行', '农业银行两小时到账', '0', '1', '1535983287', '1535983287');

-- ----------------------------
-- Table structure for cm_config
-- ----------------------------
DROP TABLE IF EXISTS `cm_config`;
CREATE TABLE `cm_config` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置标题',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `value` text COMMENT '配置值',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置选项',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '配置说明',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `conf_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='基本配置表';

-- ----------------------------
-- Records of cm_config
-- ----------------------------
INSERT INTO `cm_config` VALUES ('1', 'seo_title', '网站标题', '1', '1', '0', '聚合支付', '', '', '1', '1378898976', '1545131346');
INSERT INTO `cm_config` VALUES ('8', 'email_port', 'SMTP端口号', '1', '8', '1', '2', '1:25,2:465', '如：一般为 25 或 465', '1', '1378898976', '1545131349');
INSERT INTO `cm_config` VALUES ('2', 'seo_description', '网站描述', '2', '3', '0', '聚合支付', '', '网站搜索引擎描述，优先级低于SEO模块', '1', '1378898976', '1545131346');
INSERT INTO `cm_config` VALUES ('3', 'seo_keywords', '网站关键字', '2', '4', '0', '聚合支付', '', '网站搜索引擎关键字，优先级低于SEO模块', '1', '1378898976', '1542443678');
INSERT INTO `cm_config` VALUES ('4', 'app_index_title', '首页标题', '1', '2', '0', '小红帽科技|草帽聚合支付|Cmpay聚合支付', '', '', '1', '1378898976', '1545131346');
INSERT INTO `cm_config` VALUES ('5', 'app_domain', '网站域名', '1', '5', '0', 'caomao.com', '', '网站域名', '1', '1378898976', '1545131346');
INSERT INTO `cm_config` VALUES ('6', 'app_copyright', '版权信息', '2', '6', '0', '© 2018聚合支付. ', '', '版权信息', '1', '1378898976', '1545131346');
INSERT INTO `cm_config` VALUES ('7', 'email_host', 'SMTP服务器', '3', '7', '1', '2', '1:smtp.163.com,2:smtp.aliyun.com,3:smtp.qq.com', '如：smtp.163.com', '1', '1378898976', '1545131349');
INSERT INTO `cm_config` VALUES ('9', 'send_email', '发件人邮箱', '1', '9', '1', 'me@iredcap.cn', '', '', '1', '1378898976', '1545131349');
INSERT INTO `cm_config` VALUES ('10', 'send_nickname', '发件人昵称', '1', '10', '1', '小红帽', '', '', '1', '1378898976', '1545131349');
INSERT INTO `cm_config` VALUES ('11', 'email_password', '邮箱密码', '1', '11', '1', 'xxxxxx', '', '', '1', '1378898976', '1545131349');
INSERT INTO `cm_config` VALUES ('12', 'rsa_public_key', '平台数据公钥', '2', '6', '0', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxV1hB4NP1NFgEM0mrx34z8gJMPBIhvDjAJcnMozk3jmUY9PkB7lZyfD6Fb+Xq21jIPX5zF4ggeYoK5keUH6TW9eJEr5JOqDl2YgKAdLfxLuJ4r8X1S3wflVp2/BURIbP1VGh6qNAxS3o8miL7x5BZ+jOhs4/LCq8YkncZioui5eAQ+/BoE++uM5IeSWZEVf8JsGo+MrOG2E/eOqetrB08Tm68igM6OMbKr05HKupcZm63zzDIHRJGKRjvdFjVoVznGsAC3phyh3bzYrjxykH00mLyw39/77MiBMp/uWVMh6wwiAjY2B25IKXXGCd0JSYvlpJWtCKbxlcAGDWSWkS0wIDAQAB', '', '平台数据公钥（RSA 2048）', '1', '1378898976', '1545131349');
INSERT INTO `cm_config` VALUES ('13', 'rsa_private_key', '平台数据私钥', '2', '6', '0', 'MIIEpAIBAAKCAQEAxV1hB4NP1NFgEM0mrx34z8gJMPBIhvDjAJcnMozk3jmUY9PkB7lZyfD6Fb+Xq21jIPX5zF4ggeYoK5keUH6TW9eJEr5JOqDl2YgKAdLfxLuJ4r8X1S3wflVp2/BURIbP1VGh6qNAxS3o8miL7x5BZ+jOhs4/LCq8YkncZioui5eAQ+/BoE++uM5IeSWZEVf8JsGo+MrOG2E/eOqetrB08Tm68igM6OMbKr05HKupcZm63zzDIHRJGKRjvdFjVoVznGsAC3phyh3bzYrjxykH00mLyw39/77MiBMp/uWVMh6wwiAjY2B25IKXXGCd0JSYvlpJWtCKbxlcAGDWSWkS0wIDAQABAoIBAFeeoB/8vOlHVrW+zii6Tqa4MNRoKFq4AJ9Xe5BmmojJ2UYEYNzI/cK4V95l44i4lGSirxZ6x0XEDxtj6+BigTsp0fHfRpVfrwtG6OJsYultNMbUfVkn/venJcr9w/t0OjqC9jY76dpgCmXr4gvzS6g848tXLxaFloKwNcepfGZ9wQb8Kt+5ONzn3BUcczu4DhuWfkt6oQ4j1KPl0UIdLZ7tevG1guUUr15p6VGsvQtMh4U7Lct/+0XUp4chut6fvoAIbEHnAE8rkAZBjrICwsYKNANNBEgVhtn5sK12RVZdUEd3vBWry9YOk1dgsEmi+chqQFlD18bO5/phIXEpK4kCgYEA7mugHzBcr53tSJVwh4IkyXQOs+gW5wSqbjHhTafN29w4qOJ9ZAxELogz4gQ25Yn95l1gpOY0cyH5x6QHsPFuJJBJp9sEiGplYSsCalK1qJaQewvAMd1Ctqk5A67QHgE/4xh+id9l+e1a9SKNqg3X3X1QdLddzwoq0i1Oj407KnUCgYEA0+rLqcJC0swSIchWpWLKQ/kgu093CXVvDoTugWPuHi4Ua49/9nPv0zSjMX5GXzGZ7CAQca/Gwg24R6bvc8wgwe9OYf8/ILQ3XUHmZJIHMXD/HuZqBMn/Swu62MJalOYTOsKp4hxNvxJkZPpku6gr5C611LaOsbE6iQDyeqmtzycCgYAeVGClNxDDYnK6BhCvnFWzrujj6AVp1AUeSYggydT9QBGRImbTIGBYDwmSmfil0J0U/hH6SDKp5suQowQ7dSsOybAlA06bT/Wfm8oN3oGvdZ/hl0gWz8/ZzsMq/cUJ3BzVdds7DMk7Nv+YKZId7O7mBTgD8QOk/+UcoZjZ2ByLtQKBgQCPP99OMJfVQMdc+LzBbWdGzYf3tj7EMRLSYL+MzY0v73w0PTuF0FckkSdjlHVjcfcXa5FSGD0l/fo8zTZ+M1VNY0O78LuuksP+EUb5YtDj9fsu2xh9hkJBa3txfOeYUXJcPSxzQSi46Wjd7XjcdVC+HWkikgkhSqlD5VUD3+Ey7wKBgQDtarpiVV19/IWiRbKy7rKJcG1HnezqfoA7outJK6yG7ne1vTjkGD/BLTSJm032htPFRmrwxhDOz0EilCjCz+ID2iPWKzhiZpf5yZ/qoFrFdofNWhLyAzNzxDhAZbcVG6ebjkMfHj84sChenGk31HfuplMD0GBe8DlC7UGerxCu1A==', '', '平台数据私钥（RSA 2048）', '1', '1378898976', '1545131349');

-- ----------------------------
-- Table structure for cm_menu
-- ----------------------------
DROP TABLE IF EXISTS `cm_menu`;
CREATE TABLE `cm_menu` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(100) NOT NULL DEFAULT '100' COMMENT '排序',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `module` char(20) NOT NULL DEFAULT '' COMMENT '模块',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `is_hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `icon` char(30) NOT NULL DEFAULT '' COMMENT '图标',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 COMMENT='基本菜单表';

-- ----------------------------
-- Records of cm_menu
-- ----------------------------
INSERT INTO `cm_menu` VALUES ('1', '0', '100', '控制台', 'admin', '/', '0', 'console', '1', '1544365211', '1539583897');
INSERT INTO `cm_menu` VALUES ('2', '0', '100', '系统设置', 'admin', 'System', '0', 'set', '1', '1540800845', '1539583897');
INSERT INTO `cm_menu` VALUES ('3', '2', '100', '基本设置', 'admin', 'System', '0', 'set-fill', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('4', '3', '100', '网站设置', 'admin', 'System/website', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('5', '3', '100', '邮件服务', 'admin', 'System/email', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('6', '3', '100', '行为日志', 'admin', 'Log/index', '0', 'flag', '1', '1540563678', '1540563678');
INSERT INTO `cm_menu` VALUES ('7', '6', '100', '获取日志列表', 'admin', 'Log/getList', '1', '', '1', '1540566783', '1540566783');
INSERT INTO `cm_menu` VALUES ('8', '6', '100', '删除日志', 'admin', 'Log/logDel', '1', '', '1', '1540566819', '1540566819');
INSERT INTO `cm_menu` VALUES ('9', '6', '100', '清空日志', 'admin', 'Log/logClean', '1', '', '1', '1540566849', '1540566849');
INSERT INTO `cm_menu` VALUES ('10', '2', '100', '权限设置', 'admin', 'Admin', '0', 'set-sm', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('11', '10', '100', '管理员设置', 'admin', 'Admin/index', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('12', '11', '100', '获取管理员列表', 'admin', 'Admin/userList', '1', 'user', '1', '1540485169', '1540484869');
INSERT INTO `cm_menu` VALUES ('13', '11', '100', '新增管理员', 'admin', 'Admin/userAdd', '1', 'user', '1', '1540485182', '1540485125');
INSERT INTO `cm_menu` VALUES ('14', '11', '100', '编辑管理员', 'admin', 'Admin/userEdit', '1', 'user', '1', '1540485199', '1540485155');
INSERT INTO `cm_menu` VALUES ('15', '11', '100', '删除管理员', 'admin', 'AdminuserDel', '1', 'user', '1', '1540485310', '1540485310');
INSERT INTO `cm_menu` VALUES ('16', '10', '100', '角色管理', 'admin', 'Admin/group', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('17', '16', '100', '获取角色列表', 'admin', 'Admin/groupList', '1', '', '1', '1540485432', '1540485432');
INSERT INTO `cm_menu` VALUES ('18', '16', '100', '新增权限组', 'admin', 'Admin/groupAdd', '1', '', '1', '1540485531', '1540485488');
INSERT INTO `cm_menu` VALUES ('19', '16', '100', '编辑权限组', 'admin', 'Admin/groupEdit', '1', '', '1', '1540485515', '1540485515');
INSERT INTO `cm_menu` VALUES ('20', '16', '100', '删除权限组', 'admin', 'Admin/groupDel', '1', '', '1', '1540485570', '1540485570');
INSERT INTO `cm_menu` VALUES ('21', '10', '100', '菜单管理', 'admin', 'Menu/index', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('22', '21', '100', '获取菜单列表', 'admin', 'Menu/getList', '1', '', '1', '1540485652', '1540485632');
INSERT INTO `cm_menu` VALUES ('23', '21', '100', '新增菜单', 'admin', 'Menu/menuAdd', '1', '', '1', '1540534094', '1540534094');
INSERT INTO `cm_menu` VALUES ('24', '21', '100', '编辑菜单', 'admin', 'Menu/menuEdit', '1', '', '1', '1540534133', '1540534133');
INSERT INTO `cm_menu` VALUES ('25', '21', '100', '删除菜单', 'admin', 'Menu/menuDel', '1', '', '1', '1540534133', '1540534133');
INSERT INTO `cm_menu` VALUES ('26', '2', '100', '我的设置', 'admin', 'Admin/profile', '0', '', '1', '1540486245', '1539583897');
INSERT INTO `cm_menu` VALUES ('27', '26', '100', '基本资料', 'admin', 'System/profile', '0', '', '1', '1540557980', '1539583897');
INSERT INTO `cm_menu` VALUES ('28', '26', '100', '修改密码', 'admin', 'System/changePwd', '0', '', '1', '1540557985', '1539583897');
INSERT INTO `cm_menu` VALUES ('29', '0', '100', '支付设置', 'admin', 'Pay', '0', 'senior', '1', '1540483267', '1539583897');
INSERT INTO `cm_menu` VALUES ('30', '29', '100', '支付产品', 'admin', 'Pay/index', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('31', '30', '100', '获取支付产品列表', 'admin', 'Pay/getCodeList', '1', '', '1', '1545461560', '1545458869');
INSERT INTO `cm_menu` VALUES ('32', '30', '100', '新增支付产品', 'admin', 'Pay/addCode', '1', '', '1', '1545461705', '1545458888');
INSERT INTO `cm_menu` VALUES ('33', '30', '100', '编辑支付产品', 'admin', 'Pay/editCode', '1', '', '1', '1545461713', '1545458915');
INSERT INTO `cm_menu` VALUES ('34', '30', '100', '删除产品', 'admin', 'Pay/delCode', '1', '', '1', '1545461745', '1545458935');
INSERT INTO `cm_menu` VALUES ('35', '29', '100', '支付渠道', 'admin', 'Pay/channel', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('36', '35', '100', '获取渠道列表', 'admin', 'Pay/getChannelList', '1', '', '1', '1545461798', '1545458953');
INSERT INTO `cm_menu` VALUES ('37', '35', '100', '新增渠道', 'admin', 'Pay/addChannel', '1', '', '1', '1545461856', '1545458977');
INSERT INTO `cm_menu` VALUES ('38', '35', '100', '编辑渠道', 'admin', 'Pay/editChannel', '1', '', '1', '1545461863', '1545458992');
INSERT INTO `cm_menu` VALUES ('39', '35', '100', '删除渠道', 'admin', 'Pay/delChannel', '1', '', '1', '1545461870', '1545459004');
INSERT INTO `cm_menu` VALUES ('40', '29', '100', '渠道账户', 'admin', 'Pay/account', '1', '', '1', '1545461878', '1545459058');
INSERT INTO `cm_menu` VALUES ('41', '40', '100', '获取渠道账户列表', 'admin', 'Pay/getAccountList', '1', '', '1', '1545462265', '1545459152');
INSERT INTO `cm_menu` VALUES ('42', '40', '100', '新增账户', 'admin', 'Pay/addAccount', '1', '', '1', '1545462273', '1545459180');
INSERT INTO `cm_menu` VALUES ('43', '40', '100', '编辑账户', 'admin', 'Pay/editAccount', '1', '', '1', '1545462279', '1545459194');
INSERT INTO `cm_menu` VALUES ('44', '40', '100', '删除账户', 'admin', 'Pay/delAccount', '1', '', '1', '1545462286', '1545459205');
INSERT INTO `cm_menu` VALUES ('45', '29', '100', '银行管理', 'admin', 'Pay/bank', '0', '', '1', '1540822566', '1540822549');
INSERT INTO `cm_menu` VALUES ('46', '45', '100', '获取银行列表', 'admin', 'Pay/getBankList', '1', '', '1', '1545462167', '1545459107');
INSERT INTO `cm_menu` VALUES ('47', '45', '100', '新增银行', 'admin', 'Pay/addBank', '1', '', '1', '1545462178', '1545459243');
INSERT INTO `cm_menu` VALUES ('48', '45', '100', '编辑银行', 'admin', 'Pay/editBank', '1', '', '1', '1545462220', '1545459262');
INSERT INTO `cm_menu` VALUES ('49', '45', '100', '删除银行', 'admin', 'Pay/delBank', '1', '', '1', '1545462230', '1545459277');
INSERT INTO `cm_menu` VALUES ('50', '0', '100', '内容管理', 'admin', 'Article', '0', 'template', '1', '1540484655', '1539583897');
INSERT INTO `cm_menu` VALUES ('51', '50', '100', '文章管理', 'admin', 'Article/index', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('52', '51', '100', '获取文章列表', 'admin', 'Article/getList', '1', 'lis', '1', '1540485927', '1540484939');
INSERT INTO `cm_menu` VALUES ('53', '51', '100', '新增文章', 'admin', 'Article/add', '1', '', '1', '1540486058', '1540486058');
INSERT INTO `cm_menu` VALUES ('54', '51', '100', '编辑文章', 'admin', 'Article/edit', '1', '', '1', '1540486097', '1540486097');
INSERT INTO `cm_menu` VALUES ('55', '51', '100', '删除文章', 'admin', 'Article/del', '1', '', '1', '1545462411', '1545459431');
INSERT INTO `cm_menu` VALUES ('56', '50', '100', '公告管理', 'admin', 'Article/notice', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('57', '56', '100', '获取公告列表', 'admin', 'Article/getNoticeList', '1', '', '1', '1545462441', '1545459334');
INSERT INTO `cm_menu` VALUES ('58', '56', '100', '新增公告', 'admin', 'Article/addNotice', '1', '', '1', '1545462453', '1545459346');
INSERT INTO `cm_menu` VALUES ('59', '56', '100', '编辑公告', 'admin', 'Article/editNotice', '1', '', '1', '1545462460', '1545459368');
INSERT INTO `cm_menu` VALUES ('60', '56', '100', '删除公告', 'admin', 'Article/delNotice', '1', '', '1', '1545462468', '1545459385');
INSERT INTO `cm_menu` VALUES ('61', '0', '100', '商户管理', 'admin', 'User', '0', 'user', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('62', '61', '100', '商户列表', 'admin', 'User/index', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('63', '62', '100', '获取商户列表', 'admin', 'User/getList', '1', '', '1', '1540486400', '1540486400');
INSERT INTO `cm_menu` VALUES ('64', '62', '100', '新增商户', 'admin', 'User/add', '1', '', '1', '1540533973', '1540533973');
INSERT INTO `cm_menu` VALUES ('65', '62', '100', '商户修改', 'admin', 'User/edit', '1', '', '1', '1540533993', '1540533993');
INSERT INTO `cm_menu` VALUES ('66', '62', '100', '删除商户', 'admin', 'User/del', '1', '', '1', '1545462902', '1545459408');
INSERT INTO `cm_menu` VALUES ('67', '61', '100', '提现记录', 'admin', 'Balance/paid', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('68', '67', '100', '获取提现记录', 'admin', 'Balance/paidList', '1', '', '1', '1545462677', '1545458822');
INSERT INTO `cm_menu` VALUES ('69', '67', '100', '提现编辑', 'admin', 'Balance/editPaid', '1', '', '1', '1545462708', '1545458822');
INSERT INTO `cm_menu` VALUES ('70', '67', '100', '提现删除', 'admin', 'Balance/delPaid', '1', '', '1', '1545462715', '1545458822');
INSERT INTO `cm_menu` VALUES ('71', '61', '100', '商户账户', 'admin', 'Account/index', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('80', '71', '100', '商户账户列表', 'admin', 'Account/getList', '1', '', '1', '1545462747', '1545459501');
INSERT INTO `cm_menu` VALUES ('81', '71', '100', '新增商户账户', 'admin', 'Account/add', '1', '', '1', '1545462827', '1545459501');
INSERT INTO `cm_menu` VALUES ('82', '71', '100', '编辑商户账户', 'admin', 'Account/edit', '1', '', '1', '1545462815', '1545459501');
INSERT INTO `cm_menu` VALUES ('83', '71', '100', '删除商户账户', 'admin', 'Account/del', '1', '', '1', '1545462874', '1545459501');
INSERT INTO `cm_menu` VALUES ('84', '61', '100', '商户资金', 'admin', 'Balance/index', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('85', '84', '100', '商户资金列表', 'admin', 'Balance/getList', '1', '', '1', '1545462951', '1545459501');
INSERT INTO `cm_menu` VALUES ('86', '84', '100', '商户资金明细', 'admin', 'Balance/details', '1', '', '1', '1545462997', '1545459501');
INSERT INTO `cm_menu` VALUES ('87', '84', '100', '获取商户资金明细', 'admin', 'Balance/getDetails', '1', '', '1', '1545462997', '1545459501');
INSERT INTO `cm_menu` VALUES ('88', '61', '100', '商户API', 'admin', 'Api/index', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('89', '87', '100', '商户API列表', 'admin', 'Api/getList', '1', '', '1', '1545463054', '1545459501');
INSERT INTO `cm_menu` VALUES ('90', '87', '100', '编辑商户API', 'admin', 'Api/edit', '1', '', '1', '1545463065', '1545459501');
INSERT INTO `cm_menu` VALUES ('91', '61', '100', '商户认证', 'admin', 'User/auth', '0', '', '1', '1542882201', '1542882201');
INSERT INTO `cm_menu` VALUES ('92', '90', '100', '商户认证列表', 'admin', 'getlist', '1', '', '1', '1545459501', '1545459501');
INSERT INTO `cm_menu` VALUES ('93', '90', '100', '编辑商户认证', 'admin', 'getlist', '1', '', '1', '1545459501', '1545459501');
INSERT INTO `cm_menu` VALUES ('94', '0', '100', '订单管理', 'admin', 'Orders', '0', 'form', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('95', '94', '100', '交易列表', 'admin', 'Orders/index', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('96', '95', '100', '获取交易列表', 'admin', 'Orders/getList', '1', '', '1', '1545463214', '1539583897');
INSERT INTO `cm_menu` VALUES ('97', '94', '100', '交易详情', 'admin', 'Orders/details', '1', '', '1', '1545463268', '1545459549');
INSERT INTO `cm_menu` VALUES ('98', '94', '100', '退款列表', 'admin', 'Orders/refund', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('99', '94', '100', '商户统计', 'admin', 'Orders/user', '0', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('100', '99', '100', '获取商户统计', 'admin', 'Orders/userList', '1', '', '1', '1539584897', '1539583897');
INSERT INTO `cm_menu` VALUES ('101', '94', '100', '渠道统计', 'admin', 'Orders/channel', '0', '', '1', '1544362599', '1539583897');
INSERT INTO `cm_menu` VALUES ('102', '101', '100', '获取渠道统计', 'admin', 'Orders/channelList', '1', '', '1', '1544362599', '1539583897');

-- ----------------------------
-- Table structure for cm_notice
-- ----------------------------
DROP TABLE IF EXISTS `cm_notice`;
CREATE TABLE `cm_notice` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL DEFAULT '',
  `author` varchar(30) DEFAULT '' COMMENT '作者',
  `content` text COMMENT '公告内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '公告状态,0-不展示,1-展示',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='公告表';

-- ----------------------------
-- Records of cm_notice
-- ----------------------------
INSERT INTO `cm_notice` VALUES ('1', '一个正经的标题', 'admin', '我特么是个通知，没啥好通知的，就是测试一下O不Ok!\n', '1', '1542704381', '1544366752');

-- ----------------------------
-- Table structure for cm_orders
-- ----------------------------
DROP TABLE IF EXISTS `cm_orders`;
CREATE TABLE `cm_orders` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `puid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '代理ID',
  `uid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '商户id',
  `trade_no` varchar(30) NOT NULL DEFAULT '' COMMENT '交易订单号',
  `out_trade_no` varchar(30) NOT NULL DEFAULT '' COMMENT '商户订单号',
  `subject` varchar(64) NOT NULL DEFAULT '' COMMENT '商品标题',
  `body` varchar(256) NOT NULL DEFAULT '' COMMENT '商品描述信息',
  `channel` varchar(30) NOT NULL DEFAULT '' COMMENT '交易方式(wx_native)',
  `cnl_id` int(3) NOT NULL DEFAULT '0' COMMENT '支付通道ID',
  `extra` text COMMENT '特定渠道发起时额外参数',
  `amount` decimal(12,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '订单金额,单位是元,12-9保留3位小数',
  `income` decimal(12,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '实付金额',
  `user_in` decimal(12,3) NOT NULL DEFAULT '0.000' COMMENT '商户收入',
  `agent_in` decimal(12,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '代理收入',
  `platform_in` decimal(12,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '平台收入',
  `currency` varchar(3) NOT NULL DEFAULT 'CNY' COMMENT '三位货币代码,人民币:CNY',
  `client_ip` varchar(32) NOT NULL DEFAULT '' COMMENT '客户端IP',
  `return_url` varchar(128) NOT NULL DEFAULT '' COMMENT '同步通知地址',
  `notify_url` varchar(128) NOT NULL DEFAULT '' COMMENT '异步通知地址',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单状态:0-已取消-1-待付款，2-已付款',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no_index` (`out_trade_no`,`trade_no`,`uid`,`channel`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1000000001 DEFAULT CHARSET=utf8 COMMENT='交易订单表';

-- ----------------------------
-- Records of cm_orders
-- ----------------------------

-- ----------------------------
-- Table structure for cm_orders_notify
-- ----------------------------
DROP TABLE IF EXISTS `cm_orders_notify`;
CREATE TABLE `cm_orders_notify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_status` int(3) unsigned NOT NULL DEFAULT '404',
  `result` varchar(300) NOT NULL DEFAULT '' COMMENT '请求相响应',
  `times` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '请求次数',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易订单通知表';

-- ----------------------------
-- Records of cm_orders_notify
-- ----------------------------


-- ----------------------------
-- Table structure for cm_pay_account
-- ----------------------------
DROP TABLE IF EXISTS `cm_pay_account`;
CREATE TABLE `cm_pay_account` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT COMMENT '账号ID',
  `cnl_id` bigint(10) NOT NULL DEFAULT '0' COMMENT '所属渠道ID',
  `co_id` text COMMENT '支持的方式(有多个)',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '渠道账户名称',
  `rate` decimal(4,3) NOT NULL DEFAULT '0.000' COMMENT '渠道账户费率',
  `urate` decimal(4,3) NOT NULL DEFAULT '0.998',
  `grate` decimal(4,3) NOT NULL DEFAULT '0.998',
  `daily` decimal(12,3) NOT NULL COMMENT '当日限额',
  `single` decimal(12,3) NOT NULL DEFAULT '0.000' COMMENT '单笔限额',
  `timeslot` text COMMENT '交易时间段',
  `param` text COMMENT '账户配置参数,json字符串',
  `remarks` varchar(128) DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '账户状态,0-停止使用,1-开放使用',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='支付渠道账户表';

-- ----------------------------
-- Records of cm_pay_account
-- ----------------------------
INSERT INTO `cm_pay_account` VALUES ('1', '1', '6,1', '微信扫码支付', '0.006', '0.998', '0.996', '20000.000', '2000.000', '{\"start\":\"0:0\",\"end\":\"12:30\"}', '{\"mch_id\":\"\",\"mch_key\":\"\",\"app_id\":\"\",\"app_key\":\"\"}', 'XX微信支付', '1', '1535983487', '1545980754');
INSERT INTO `cm_pay_account` VALUES ('2', '2', '0', '官方支付宝', '0.006', '0.998', '0.996', '200000.000', '3000.000', '{\"start\":\"0:0\",\"end\":\"23:30\"}', '{\"app_id\":\"\",\"private_key\":\"\",\"public_key\":\"\"}', 'XX支付宝', '1', '1543082772', '1545142376');
INSERT INTO `cm_pay_account` VALUES ('3', '3', '0', '官方QQ支付', '0.006', '0.998', '0.996', '200000.000', '5000.000', '{\"start\":\"0:0\",\"end\":\"23:30\"}', '{\"mch_id\":\"\",\"app_id\":\"\",\"mch_key\":\"\"}', 'XXQQ钱包', '1', '1544264384', '1545142415');
INSERT INTO `cm_pay_account` VALUES ('4', '4', '0', '官方Paypal支付', '0.006', '0.998', '0.996', '200000.000', '6000.000', '{\"start\":\"0:0\",\"end\":\"23:30\"}', '{\"client_id\":\"\",\"client_secret\":\"\"}', 'XXPaypal', '1', '1544368985', '1545923960');
INSERT INTO `cm_pay_account` VALUES ('5', '1', '6,1', 'XX商户支付账号', '0.006', '0.998', '0.996', '500000.000', '2000.000', '{\"start\":\"0:0\",\"end\":\"23:30\"}', '{\"mch_id\":\"\",\"mch_key\":\"\",\"app_id\":\"\",\"app_key\":\"\"}', 'XX小程序支付', '1', '1545143235', '1545980411');
INSERT INTO `cm_pay_account` VALUES ('6', '6', '0', '测试账户', '0.006', '0.998', '0.998', '1000000.000', '2000.000', '{\"start\":\"0:0\",\"end\":\"23:0\"}', '{\"mer_id\":\"商户支付号\",\"mch_key\":\"商户支付KEY\",\"app_id\":\"商户应用号\",\"app_key\":\"应用KEY\"}', '测试账户', '1', '1545833295', '1545833295');

-- ----------------------------
-- Table structure for cm_pay_channel
-- ----------------------------
DROP TABLE IF EXISTS `cm_pay_channel`;
CREATE TABLE `cm_pay_channel` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT COMMENT '渠道ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '支付渠道名称',
  `action` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名称,如:Wxpay用于分发处理支付请求',
  `urate` decimal(4,3) NOT NULL DEFAULT '0.998' COMMENT '默认商户分成',
  `grate` decimal(4,3) NOT NULL DEFAULT '0.998' COMMENT '默认代理分成',
  `timeslot` text COMMENT '交易时间段',
  `return_url` varchar(255) NOT NULL DEFAULT '' COMMENT '同步地址',
  `notify_url` varchar(255) NOT NULL DEFAULT '' COMMENT '异步地址',
  `remarks` varchar(128) DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '渠道状态,0-停止使用,1-开放使用',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='支付渠道表';

-- ----------------------------
-- Records of cm_pay_channel
-- ----------------------------
INSERT INTO `cm_pay_channel` VALUES ('1', '微信支付', 'Wxpay', '0.998', '0.996', '{\"start\":\"6:0\",\"end\":\"23:0\"}', 'https://pay.iredcap.cn/callback/wxpay', 'https://pay.iredcap.cn/notify/wxpay', '官方微信支付', '1', '1535983487', '1545146107');
INSERT INTO `cm_pay_channel` VALUES ('2', '支付宝', 'Alipay', '0.998', '0.996', '{\"start\":\"6:0\",\"end\":\"23:0\"}', 'https://pay.iredcap.cn/callback/alipay', 'https://pay.iredcap.cn/notify/alipay', '官方支付宝', '1', '1543082772', '1545142376');
INSERT INTO `cm_pay_channel` VALUES ('3', 'QQ支付', 'Qpay', '0.998', '0.996', '{\"start\":\"6:0\",\"end\":\"23:0\"}', 'https://pay.iredcap.cn/callback/qpay', 'https://pay.iredcap.cn/notify/qpay', '官方QQ支付', '1', '1544264384', '1545142415');
INSERT INTO `cm_pay_channel` VALUES ('4', 'Paypal支付', 'Paypal', '0.998', '0.996', '{\"start\":\"8:0\",\"end\":\"23:59\"}', 'https://pay.iredcap.cn/callback/paypal', 'https://pay.iredcap.cn/notify/paypal', 'Paypal支付', '1', '1544368985', '1545415845');
INSERT INTO `cm_pay_channel` VALUES ('5', 'XX微信支付', 'Xpay', '0.998', '0.998', '{\"start\":\"0:0\",\"end\":\"23:0\"}', 'https://pay.iredcap.cn/callback/xxpay', 'https://pay.iredcap.cn/notify/xxpay', 'XX微信支付', '1', '1545417463', '1545923720');
INSERT INTO `cm_pay_channel` VALUES ('6', '汇畅支付', 'Hcpay', '0.998', '0.998', '{\"start\":\"0:0\",\"end\":\"23:0\"}', 'https://www.baidu.com', 'https://www.baidu.com', 'https://www.baidu.com', '1', '1545832842', '1545923712');

-- ----------------------------
-- Table structure for cm_pay_code
-- ----------------------------
DROP TABLE IF EXISTS `cm_pay_code`;
CREATE TABLE `cm_pay_code` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT COMMENT '渠道ID',
  `cnl_id` text,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `code` varchar(30) NOT NULL DEFAULT '' COMMENT '支付方式代码,如:wx_native,qq_native,ali_qr;',
  `remarks` varchar(128) DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '方式状态,0-停止使用,1-开放使用',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='交易方式表';

-- ----------------------------
-- Records of cm_pay_code
-- ----------------------------
INSERT INTO `cm_pay_code` VALUES ('1', '1', '微信原生扫码支付', 'wx_native', '微信扫码支付', '1', '1535983487', '1545417484');
INSERT INTO `cm_pay_code` VALUES ('2', '2', '支付宝扫码', 'ali_qr', '支付宝扫码', '1', '1544173543', '1545420586');
INSERT INTO `cm_pay_code` VALUES ('3', '3', 'QQ扫码', 'qq_native', 'QQ扫码', '1', '1544264177', '1545980274');
INSERT INTO `cm_pay_code` VALUES ('4', '4', 'Paypal支付', 'pp_web', '返回支付地址', '1', '1544363537', '1545141309');
INSERT INTO `cm_pay_code` VALUES ('5', '1', '微信公众号支付', 'wx_jsapi', '微信公众号支付，返回数据包', '1', '1545142906', '1545143662');
INSERT INTO `cm_pay_code` VALUES ('6', '1', '微信小程序支付', 'wx_mini', '微信小程序支付，返回支付包', '1', '1545143540', '1545975942');
INSERT INTO `cm_pay_code` VALUES ('7', null, 'XX银联扫码', 'unionpay', 'XXXX', '1', '1545832802', '1545832802');

-- ----------------------------
-- Table structure for cm_transaction
-- ----------------------------
DROP TABLE IF EXISTS `cm_transaction`;
CREATE TABLE `cm_transaction` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) DEFAULT '0' COMMENT '商户id',
  `order_no` varchar(80) DEFAULT NULL COMMENT '交易订单号',
  `amount` decimal(12,3) DEFAULT '0.000' COMMENT '交易金额',
  `platform` tinyint(1) DEFAULT NULL COMMENT '交易平台:1-支付宝,2-微信',
  `platform_number` varchar(200) DEFAULT NULL COMMENT '交易平台交易流水号',
  `status` tinyint(1) DEFAULT NULL COMMENT '交易状态',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_index` (`order_no`,`platform`,`uid`,`amount`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易流水表';

-- ----------------------------
-- Records of cm_transaction
-- ----------------------------

-- ----------------------------
-- Table structure for cm_user
-- ----------------------------
DROP TABLE IF EXISTS `cm_user`;
CREATE TABLE `cm_user` (
  `uid` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '商户uid',
  `puid` mediumint(8) NOT NULL DEFAULT '0',
  `account` varchar(50) NOT NULL DEFAULT '' COMMENT '商户邮件',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '商户名称',
  `auth_code` varchar(32) DEFAULT NULL COMMENT '8位安全码，注册时发送跟随邮件',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '商户登录密码',
  `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `qq` varchar(10) NOT NULL DEFAULT '' COMMENT 'QQ',
  `is_agent` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '代理商',
  `is_verify` tinyint(1) NOT NULL  DEFAULT '0' COMMENT '验证账号',
  `is_verify_phone` tinyint(1) NOT NULL DEFAULT '0' COMMENT '验证手机',
  `last_login_ip` varchar(32) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time`  int(10) unsigned NOT NULL COMMENT '最后登录时间',
  `reg_ip` varchar(24) NOT NULL DEFAULT '' COMMENT '注册IP',
  `reg_salt` varchar(32) NOT NULL DEFAULT '9ftFMQnnPWhDrXAPhUUoB4SMJFBRJ9iq' COMMENT '注册加密盐',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商户状态,0-未激活,1-使用中,2-禁用',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `user_name_unique` (`account`,`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=100002 DEFAULT CHARSET=utf8 COMMENT='商户信息表';

-- ----------------------------
-- Records of cm_user
-- ----------------------------
INSERT INTO `cm_user` VALUES ('100001', '0', 'nouser@iredcap.cn', 'Nouser', '8f421396f3ad805ed015b68323a6b2fd', '7a563bd96286403ca906e99727283cd5', '18078687485', '702154416', '1', '1', '1','117.181.163.101','1541787044','117.181.163.101','9ftFMQnnPWhDrXAPhUUoB4SMJFBRJ9iq', '1', '1541787044', '1545300565');

-- ----------------------------
-- Table structure for cm_user_account
-- ----------------------------
DROP TABLE IF EXISTS `cm_user_account`;
CREATE TABLE `cm_user_account` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) NOT NULL COMMENT '商户ID',
  `bank_id` mediumint(8) NOT NULL DEFAULT '1' COMMENT '开户行(关联银行表)',
  `account` varchar(250) NOT NULL DEFAULT '' COMMENT '开户号',
  `address` varchar(250) NOT NULL DEFAULT '' COMMENT '开户所在地',
  `remarks` varchar(250) NOT NULL DEFAULT '' COMMENT '备注',
  `default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认账户,0-不默认,1-默认',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商户结算账户表';

-- ----------------------------
-- Records of cm_user_account
-- ----------------------------
INSERT INTO `cm_user_account` VALUES ('1', '100001', '1', '18078687485', '支付宝', '', '1', '1', '1543302498', '1544368012');

-- ----------------------------
-- Table structure for cm_user_auth
-- ----------------------------
DROP TABLE IF EXISTS `cm_user_auth`;
CREATE TABLE `cm_user_auth` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) NOT NULL COMMENT '商户ID',
  `realname` varchar(30) NOT NULL DEFAULT '1' COMMENT '开户行(关联银行表)',
  `sfznum` varchar(18) NOT NULL DEFAULT '' COMMENT '开户号',
  `card` text COMMENT '认证详情',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商户认证信息表';

-- ----------------------------
-- Records of cm_user_auth
-- ----------------------------
INSERT INTO `cm_user_auth` VALUES ('1', '100001', '马大哈', '554612198802051515', '[\"\\/uploads\\/userauth\\/100001\\/20181122\\/22203963968.jpg\",\"\\/uploads\\/userauth\\/100001\\/20181122\\/22204161001.jpg\"]', '2', '1542896443', '1544365792');

-- ----------------------------
-- Table structure for cm_user_profit
-- ----------------------------
DROP TABLE IF EXISTS `cm_user_profit`;
CREATE TABLE `cm_user_profit` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) NOT NULL COMMENT '商户ID',
  `cnl_id` int(10) unsigned NOT NULL DEFAULT '0',
  `urate` decimal(4,3) unsigned NOT NULL DEFAULT '0.000',
  `grate` decimal(4,3) unsigned NOT NULL DEFAULT '0.000',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户分润表';