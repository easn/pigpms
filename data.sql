/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50619
Source Host           : localhost:3306
Source Database       : newdian

Target Server Type    : MYSQL
Target Server Version : 50619
File Encoding         : 65001

Date: 2016-01-17 13:03:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pigcms_access_token_expires`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_access_token_expires`;
CREATE TABLE `pigcms_access_token_expires` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `access_token` varchar(700) NOT NULL,
  `expires_in` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_access_token_expires
-- ----------------------------
INSERT INTO `pigcms_access_token_expires` VALUES ('3', 'DHnDy7Cgi_GQazsg4cnH5KlXVGbMyVdcnlIEeh9awXqTlwhMG3In7NSJUX2VTyrwx9vPH2ugNzkTOpzKU3jdCcRNe321flYpOeKXCEiw1NwIAEeAFALDH', '1453014168');

-- ----------------------------
-- Table structure for `pigcms_activity_recommend`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_activity_recommend`;
CREATE TABLE `pigcms_activity_recommend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modelId` int(11) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `info` varchar(2000) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `qcode` varchar(200) DEFAULT NULL COMMENT '活动二维码本地路径',
  `qcode_starttime` int(11) NOT NULL COMMENT '二维码开始生效时间',
  `token` char(50) DEFAULT NULL,
  `model` char(20) DEFAULT NULL,
  `is_rec` tinyint(1) NOT NULL,
  `ucount` int(11) NOT NULL,
  `time` int(11) DEFAULT NULL,
  `price` varchar(50) NOT NULL COMMENT '价格',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖类型',
  `original_price` varchar(50) NOT NULL COMMENT '原价',
  PRIMARY KEY (`id`),
  KEY `modelId` (`modelId`,`token`,`model`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='推荐活动';

-- ----------------------------
-- Records of pigcms_activity_recommend
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_admin`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_admin`;
CREATE TABLE `pigcms_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` char(20) NOT NULL,
  `pwd` char(32) NOT NULL,
  `realname` char(20) NOT NULL,
  `phone` char(20) NOT NULL,
  `email` char(20) NOT NULL,
  `qq` char(20) NOT NULL,
  `last_ip` bigint(20) NOT NULL,
  `last_time` int(11) NOT NULL,
  `login_count` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_admin
-- ----------------------------
INSERT INTO `pigcms_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '', '', '', '1851908996', '1453003859', '50', '1');

-- ----------------------------
-- Table structure for `pigcms_adver`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_adver`;
CREATE TABLE `pigcms_adver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `url` varchar(200) NOT NULL,
  `pic` varchar(50) NOT NULL,
  `bg_color` varchar(30) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_adver
-- ----------------------------
INSERT INTO `pigcms_adver` VALUES ('1', '首页幻灯片1', 'http://d.xins.cc/category/26', 'adver/2015/07/55af8341ace1c.png', '#ebebeb', '1', '1', '1437565761');
INSERT INTO `pigcms_adver` VALUES ('2', '广告1', 'http://d.xins.cc/wap/home.php?id=2646', 'adver/2015/07/55b73b7de3676.jpg', '', '3', '1', '1438071677');
INSERT INTO `pigcms_adver` VALUES ('3', '广告2', 'http://d.xins.cc/wap/home.php?id=2647', 'adver/2015/07/55b73859c6b38.jpg', '', '3', '1', '1438070873');
INSERT INTO `pigcms_adver` VALUES ('5', '你猜', 'http://d.xins.cc/wap/good.php?id=4018&platform=1', 'adver/2015/07/55b736f3ade80.png', '', '2', '1', '1438070515');
INSERT INTO `pigcms_adver` VALUES ('6', '首页幻灯片2', 'http://d.xins.cc/category/4', 'adver/2015/06/5579206440bd3.png', '#fbe339', '1', '1', '1434001508');
INSERT INTO `pigcms_adver` VALUES ('7', '首页幻灯片3', 'http://d.xins.cc/category/7', 'adver/2015/06/55791da932c2c.png', '#4385f5', '1', '1', '1434000847');
INSERT INTO `pigcms_adver` VALUES ('8', '广告1', 'http://d.xins.cc/index.php?c=activity&a=index', 'adver/2015/07/55af11057bfcd.jpg', '#F2E6DA', '4', '1', '1448456702');
INSERT INTO `pigcms_adver` VALUES ('9', '广告1', 'http://d.xins.cc/account.php', 'adver/2015/07/55af5caf9b074.gif', '', '5', '1', '1437555887');
INSERT INTO `pigcms_adver` VALUES ('12', '今日推荐-广告1', 'http://d.xins.cc/goods/9.html', 'adver/2015/05/555efe7caa45b.jpg', '', '7', '1', '1432288892');
INSERT INTO `pigcms_adver` VALUES ('13', '首页幻灯片右侧广告-1', 'http://d.xins.cc/goods/10.html', 'adver/2015/05/555f043ce121f.png', '', '8', '1', '1432290364');
INSERT INTO `pigcms_adver` VALUES ('14', '首页幻灯片右侧广告-2', 'http://d.xins.cc/goods/11.html', 'adver/2015/05/555f04512b6c3.png', '', '8', '1', '1432290385');
INSERT INTO `pigcms_adver` VALUES ('15', '首页幻灯片4', 'http://d.xins.cc/category/19', 'adver/2015/06/55791d64028a2.png', '#c3fbfb', '1', '1', '1434000739');
INSERT INTO `pigcms_adver` VALUES ('16', '今日推荐-广告2', 'http://d.xins.cc/goods/12.html', 'adver/2015/05/5563de912e533.jpg', '', '7', '1', '1432608401');
INSERT INTO `pigcms_adver` VALUES ('17', '今日推荐-广告3', 'http://d.xins.cc/goods/13.html', 'adver/2015/05/5563dee021d8f.jpg', '', '7', '1', '1432608480');
INSERT INTO `pigcms_adver` VALUES ('18', '今日推荐-广告4', 'http://d.xins.cc/goods/14.html', 'adver/2015/05/5563df3586e25.jpg', '', '7', '1', '1432608565');
INSERT INTO `pigcms_adver` VALUES ('25', '时尚女装', 'http://d.xins.cc/wap/good.php?id=2', 'adver/2015/07/55b7345505d6f.png', '', '2', '1', '1438069844');
INSERT INTO `pigcms_adver` VALUES ('27', '广告1', 'http://testdian.weihubao.com/', 'adver/2015/07/55af2e946656a.jpg', '', '9', '1', '1437544084');
INSERT INTO `pigcms_adver` VALUES ('28', '活动中间广告1', 'http://d.xins.cc/', 'adver/2015/07/55af7ab35f142.jpg', '', '11', '1', '1437563571');
INSERT INTO `pigcms_adver` VALUES ('29', '最好广告', 'http://d.xins.cc/category/19', 'adver/2015/07/55af82c4ebf42.png', '', '1', '1', '1437816277');
INSERT INTO `pigcms_adver` VALUES ('30', '互动娱乐电商', 'http://d.xins.cc/category/4', 'adver/2015/07/55af82f924fb3.png', '', '1', '1', '1437565689');
INSERT INTO `pigcms_adver` VALUES ('31', 'lbs', 'http://d.xins.cc/wap', 'adver/2015/07/55b73700c357e.png', '', '2', '1', '1438070528');
INSERT INTO `pigcms_adver` VALUES ('32', '我要送礼', 'http://d.xins.cc/index.php?c=activity&a=index', 'adver/2015/08/55bc7ae7ce478.png', '', '13', '1', '1438415591');
INSERT INTO `pigcms_adver` VALUES ('33', '降价拍', 'http://d.xins.cc/index.php?c=activity&a=index', 'adver/2015/08/55bc7b05f170a.png', '', '13', '1', '1438415621');
INSERT INTO `pigcms_adver` VALUES ('34', '一元夺宝', 'http://d.xins.cc/index.php?c=activity&a=index', 'adver/2015/08/55bc7b205d36a.png', '', '13', '1', '1438415648');
INSERT INTO `pigcms_adver` VALUES ('35', '众筹', 'http://d.xins.cc/index.php?c=activity&a=index', 'adver/2015/08/55bc7b6b5e9f1.png', '', '13', '1', '1438415723');
INSERT INTO `pigcms_adver` VALUES ('36', '限时秒杀', 'http://d.xins.cc/index.php?c=activity&a=index', 'adver/2015/08/55bc7c09a6bf9.png', '', '13', '1', '1438415881');
INSERT INTO `pigcms_adver` VALUES ('37', '超级砍价', 'http://d.xins.cc/index.php?c=activity&a=index', 'adver/2015/08/55bc7c211c0b2.png', '', '13', '1', '1438415904');
INSERT INTO `pigcms_adver` VALUES ('38', '活动中间广告1', 'http://d.xins.cc/', 'adver/2015/07/55af7ab35f142.jpg', '', '10', '1', '1437563571');

-- ----------------------------
-- Table structure for `pigcms_adver_category`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_adver_category`;
CREATE TABLE `pigcms_adver_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` char(20) NOT NULL,
  `cat_key` char(20) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_adver_category
-- ----------------------------
INSERT INTO `pigcms_adver_category` VALUES ('1', 'pc-首页幻灯片', 'pc_index_slide');
INSERT INTO `pigcms_adver_category` VALUES ('2', 'wap-首页头部幻灯片(6)', 'wap_index_slide_top');
INSERT INTO `pigcms_adver_category` VALUES ('3', 'wap-首页热门品牌下方广告（2）', 'wap_index_brand');
INSERT INTO `pigcms_adver_category` VALUES ('4', 'pc-登陆页广告位', 'pc_login_pic');
INSERT INTO `pigcms_adver_category` VALUES ('5', 'pc-公用头部右侧广告位（1）', 'pc_index_top_right');
INSERT INTO `pigcms_adver_category` VALUES ('8', 'pc-首页幻灯片-右侧广告', 'pc_index_slide_right');
INSERT INTO `pigcms_adver_category` VALUES ('9', 'pc-活动页头部幻灯片（6）', 'pc_activity_slider');
INSERT INTO `pigcms_adver_category` VALUES ('10', 'pc-活动页今日推荐（1）', 'pc_activity_rec');
INSERT INTO `pigcms_adver_category` VALUES ('11', 'pc-活动页热门活动（4）', 'pc_activity_hot');
INSERT INTO `pigcms_adver_category` VALUES ('12', 'pc-活动页附近活动（4）', 'pc_activity_nearby');
INSERT INTO `pigcms_adver_category` VALUES ('13', 'pc-首页活动广告', 'pc_index_activity');

-- ----------------------------
-- Table structure for `pigcms_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_attachment`;
CREATE TABLE `pigcms_attachment` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `from` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为上传，1为导入，2为收藏',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为图片，1为语音，2为视频',
  `file` varchar(100) NOT NULL COMMENT '文件地址',
  `size` bigint(20) NOT NULL COMMENT '尺寸，byte字节',
  `width` int(11) NOT NULL COMMENT '图片宽度',
  `height` int(11) NOT NULL COMMENT '图片高度',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户IP地址',
  `agent` varchar(1024) NOT NULL COMMENT '用户浏览器信息',
  PRIMARY KEY (`pigcms_id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='附件表';

-- ----------------------------
-- Records of pigcms_attachment
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_attachment_user`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_attachment_user`;
CREATE TABLE `pigcms_attachment_user` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(11) NOT NULL COMMENT 'UID',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `from` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为上传，1为导入，2为收藏',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为图片，1为语音，2为视频',
  `file` varchar(100) NOT NULL COMMENT '文件地址',
  `size` bigint(20) NOT NULL COMMENT '尺寸，byte字节',
  `width` int(11) NOT NULL COMMENT '图片宽度',
  `height` int(11) NOT NULL COMMENT '图片高度',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户IP地址',
  `agent` varchar(1024) NOT NULL COMMENT '用户浏览器信息',
  PRIMARY KEY (`pigcms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员附件表';

-- ----------------------------
-- Records of pigcms_attachment_user
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_bank`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_bank`;
CREATE TABLE `pigcms_bank` (
  `bank_id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0启用 1禁用',
  PRIMARY KEY (`bank_id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='银行';

-- ----------------------------
-- Records of pigcms_bank
-- ----------------------------
INSERT INTO `pigcms_bank` VALUES ('1', '中国工商银行', '1');
INSERT INTO `pigcms_bank` VALUES ('2', '中国农业银行', '1');
INSERT INTO `pigcms_bank` VALUES ('3', '中国银行', '1');
INSERT INTO `pigcms_bank` VALUES ('4', '中国建设银行', '1');
INSERT INTO `pigcms_bank` VALUES ('5', '交通银行', '1');

-- ----------------------------
-- Table structure for `pigcms_bond_record`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_bond_record`;
CREATE TABLE `pigcms_bond_record` (
  `bond_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `order_no` varchar(50) NOT NULL DEFAULT '0' COMMENT '主订单号',
  `transaction_no` varchar(50) NOT NULL DEFAULT '0' COMMENT '交易单号',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品最终供货商id',
  `wholesale_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品经销商',
  `add_time` varchar(50) NOT NULL DEFAULT '0' COMMENT '记录生成时间',
  `status` tinyint(6) NOT NULL DEFAULT '0' COMMENT '状态  0 进行中 1 交易完成 2 退款',
  `deduct_bond` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '扣除的保证金',
  `residue_bond` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '剩余的保证金',
  PRIMARY KEY (`bond_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_bond_record
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_business_hour`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_business_hour`;
CREATE TABLE `pigcms_business_hour` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `is_open` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_business_hour
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_certification`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_certification`;
CREATE TABLE `pigcms_certification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `certification_info` text NOT NULL COMMENT '认证信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_certification
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_comment`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_comment`;
CREATE TABLE `pigcms_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论时间',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单表ID,对产品评论时，要加订单ID，其它为0',
  `relation_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联评论ID，例：产品ID，店铺ID等',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `store_id` int(11) NOT NULL COMMENT '店铺id',
  `score` tinyint(1) NOT NULL DEFAULT '5' COMMENT '满意度，1-5，数值越大，满意度越高',
  `logistics_score` tinyint(1) NOT NULL DEFAULT '5' COMMENT '物流满意度，1-5，数值越大，满意度越高',
  `description_score` tinyint(1) NOT NULL DEFAULT '5' COMMENT '描述相符，1-5，数值越大，满意度越高',
  `speed_score` tinyint(1) NOT NULL DEFAULT '5' COMMENT '发货速度，1-5，数值越大，满意度越高',
  `service_score` tinyint(1) NOT NULL DEFAULT '5' COMMENT '服务态度，1-5，数值越大，满意度越高',
  `type` enum('PRODUCT','STORE') NOT NULL DEFAULT 'PRODUCT' COMMENT '评论的类型，PRODUCT:对产品评论，STORE:对店铺评论',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，主要用于审核评论，1：通过审核，0：未通过审核',
  `has_image` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有图片，1为有，0为没有',
  `content` text NOT NULL COMMENT '评论内容',
  `reply_number` int(11) NOT NULL DEFAULT '0' COMMENT '回复数',
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记，1：删除，0：未删除',
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `relation_id` (`relation_id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='评论表';

-- ----------------------------
-- Records of pigcms_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_comment_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_comment_attachment`;
CREATE TABLE `pigcms_comment_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '评论表ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为图片，1为语音，2为视频',
  `file` varchar(100) DEFAULT NULL COMMENT '文件地址',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小，byte字节数',
  `width` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '图片宽度',
  `height` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '图片高度',
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `cid` (`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='评论附件表';

-- ----------------------------
-- Records of pigcms_comment_attachment
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_comment_reply`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_comment_reply`;
CREATE TABLE `pigcms_comment_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复时间',
  `cid` int(11) unsigned NOT NULL COMMENT '评论表ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `content` text COMMENT '回复内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，主要用于审核评论，1：通过审核，0：未通过审核',
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记，1：删除，0：未删除',
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `cid` (`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='评论回复表';

-- ----------------------------
-- Records of pigcms_comment_reply
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_comment_tag`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_comment_tag`;
CREATE TABLE `pigcms_comment_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '评论表ID',
  `tag_id` int(11) NOT NULL DEFAULT '0' COMMENT '系统标签表ID',
  `relation_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联评论ID，例：产品ID，店铺ID等',
  `type` enum('PRODUCT','STORE') NOT NULL DEFAULT 'PRODUCT' COMMENT '评论的类型，PRODUCT:对产品评论，STORE:对店铺评论',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，主要用于审核评论，1：通过审核，0：未通过审核',
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除标记，1：删除，0：未删除',
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `cid` (`cid`) USING BTREE,
  KEY `tag_id` (`tag_id`,`relation_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_comment_tag
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_common_data`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_common_data`;
CREATE TABLE `pigcms_common_data` (
  `pigcms_id` int(5) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL DEFAULT '' COMMENT '字段名',
  `value` varchar(500) NOT NULL DEFAULT '' COMMENT '字段值',
  `bak` varchar(100) DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`pigcms_id`),
  UNIQUE KEY `key` (`key`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='公用数据';

-- ----------------------------
-- Records of pigcms_common_data
-- ----------------------------
INSERT INTO `pigcms_common_data` VALUES ('1', 'store_qty', '20', '店铺数');
INSERT INTO `pigcms_common_data` VALUES ('2', 'drp_seller_qty', '0', '分销商数');
INSERT INTO `pigcms_common_data` VALUES ('3', 'product_qty', '19', '商品数');

-- ----------------------------
-- Table structure for `pigcms_company`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_company`;
CREATE TABLE `pigcms_company` (
  `company_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '公司id',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名',
  `province` varchar(30) NOT NULL DEFAULT '' COMMENT '省',
  `city` varchar(30) NOT NULL DEFAULT '' COMMENT '市',
  `area` varchar(30) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(500) NOT NULL DEFAULT '' COMMENT '地址',
  PRIMARY KEY (`company_id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_company
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_config`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_config`;
CREATE TABLE `pigcms_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(150) NOT NULL COMMENT '多个默认值用|分隔',
  `value` text NOT NULL,
  `info` varchar(20) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `tab_id` varchar(20) NOT NULL DEFAULT '0' COMMENT '小分组ID',
  `tab_name` varchar(20) NOT NULL COMMENT '小分组名称',
  `gid` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `gid` (`gid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='配置表';

-- ----------------------------
-- Records of pigcms_config
-- ----------------------------
INSERT INTO `pigcms_config` VALUES ('1', 'site_name', 'type=text&validate=required:true', '微店系统', '网站名称', '网站的名称', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('2', 'site_url', 'type=text&validate=required:true,url:true', 'http://d.xins.cc', '网站网址', '请填写网站的网址，包含（http://域名）', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('3', 'site_logo', 'type=image&validate=required:true,url:true', 'http://d.xins.cc/upload/images/000/000/001/564664b573a64.png', '网站LOGO', '请填写LOGO的网址，包含（http://域名）', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('4', 'site_qq', 'type=text&validate=qq:true', '12345', '联系QQ', '前台涉及到需要显示QQ的地方，将显示此值！', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('5', 'site_email', 'type=text&validate=email:true', '12345@qq.com', '联系邮箱', '前台涉及到需要显示邮箱的地方，将显示此值！', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('6', 'site_icp', 'type=text', '', 'ICP备案号', '可不填写。放置于大陆的服务器，需要网站备案。', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('7', 'seo_title', 'type=text&size=80&validate=required:true', '微店系统', 'SEO标题', '一般不超过80个字符！', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('8', 'seo_keywords', 'type=text&size=80', '微店系统,微信商城,粉丝营销,微信商城运营', 'SEO关键词', '一般不超过100个字符！', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('9', 'seo_description', 'type=textarea&rows=4&cols=93', '微店系统是帮助商家在微信上搭建微信商城的平台，提供店铺、商品、订单、物流、消息和客户的管理模块，同时还提供丰富的营销应用和活动插件。', 'SEO描述', '一般不超过200个字符！', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('10', 'site_footer', 'type=textarea&rows=6&cols=93', '', '网站底部信息', '可填写统计、客服等HTML代码，代码前台隐藏不可见！！', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('11', 'register_check_phone', 'type=radio&value=1:验证|0:不验证', '0', '验证手机', '注册时是否发送短信验证手机号码！请确保短信配置成功。', '0', '', '1', '0', '0');
INSERT INTO `pigcms_config` VALUES ('12', 'register_phone_again_time', 'type=text&size=10&validate=required:true', '60', '注册短信间隔时间', '注册再次发送短信的间隔时间', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('13', 'theme_user_group', '', 'default', '', '', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('14', 'trade_pay_cancel_time', 'type=text&size=10&validate=required:true', '30', '默认自动取消订单时间', '默认自动取消订单时间，填0表示关闭该功能', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('15', 'trade_pay_alert_time', 'type=text&size=10&validate=required:true', '20', '默认自动催付订单时间', '默认自动催付订单时间，填0表示关闭该功能', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('16', 'trade_sucess_notice', 'type=radio&value=1:通知|0:不通知', '1', '支付成功是否通知用户', '支付成功是否通知用户', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('17', 'trade_send_notice', 'type=radio&value=1:通知|0:不通知', '1', '发货是否通知用户', '发货是否通知用户', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('18', 'trade_complain_notice', 'type=radio&value=1:通知|0:不通知', '1', '维权通知是否通知用户', '维权通知是否通知用户', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('19', 'ucenter_page_title', 'type=text&size=80&validate=required:true,maxlength:50', '会员主页', '默认页面名称', '如果店铺没有填写页面名称，默认值', '0', '会员主页', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('20', 'ucenter_bg_pic', 'type=text&size=80&validate=required:true', 'default_ucenter.jpg', '默认背景图', '如果店铺没有上传背景图，默认值', '0', '会员主页', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('21', 'ucenter_show_level', 'type=radio&value=1:显示|0:不显示', '1', '默认是否显示等级', '店铺在没有修改之前，默认是否显示等级', '0', '会员主页', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('22', 'ucenter_show_point', 'type=radio&value=1:显示|0:不显示', '1', '默认是否显示积分', '店铺在没有修改之前，默认是否显示积分', '0', '会员主页', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('23', 'wap_site_url', 'type=text&size=80&validate=required:true', 'http://d.xins.cc/wap', '手机版网站网址', '手机版网站网址，可使用二级域名', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('24', 'theme_wap_group', 'type=select&value=default:默认|theme1:其他', 'default', '平台商城模板', '选择非“默认模板”选项后“平台商城首页内容”设置将无法生效', '0', '', '11', '0', '0');
INSERT INTO `pigcms_config` VALUES ('25', 'wx_token', 'type=text', 'vdian17588com_test', '公众号消息校验Token', '公众号消息校验Token', '0', '', '13', '0', '1');
INSERT INTO `pigcms_config` VALUES ('26', 'wx_appsecret', 'type=text', '7354cc4d5844b74035923f198c765f77', '网页授权AppSecret', '网页授权AppSecret', '0', '', '13', '0', '1');
INSERT INTO `pigcms_config` VALUES ('27', 'wx_appid', 'type=text', 'wxf0fab7791babac85', '网页授权AppID', '网页授权AppID', '0', '', '13', '0', '1');
INSERT INTO `pigcms_config` VALUES ('28', 'wx_componentverifyticket', 'type=text', 'ticket@@@y4Jc-SeSBcK4MNMzVkjgI_4Ijg-v0I33e_qi-39pjzeccyUyEh3_T7izO7Zd3tRBPBsyK-6RytP3xVvKmJOOEw', '', '', '0', '', '0', '0', '1');
INSERT INTO `pigcms_config` VALUES ('29', 'orderid_prefix', 'type=text&size=20', 'VD', '订单号前缀', '用户看到的订单号 = 订单号前缀+订单号', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('30', 'pay_alipay_open', 'type=radio&value=1:开启|0:关闭', '1', '开启', '', 'alipay', '支付宝', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('31', 'pay_alipay_name', 'type=text&size=80', 'pigcms', '帐号', '', 'alipay', '支付宝', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('32', 'pay_alipay_pid', 'type=text&size=80', 'pigcms', 'PID', '', 'alipay', '支付宝', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('33', 'pay_alipay_key', 'type=text&size=80', 'pigcms', 'KEY', '', 'alipay', '支付宝', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('34', 'pay_tenpay_open', 'type=radio&value=1:开启|0:关闭', '0', '开启', '', 'tenpay', '财付通', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('35', 'pay_tenpay_partnerid', 'type=text&size=80', 'pigcms', '商户号', '', 'tenpay', '财付通', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('36', 'pay_tenpay_partnerkey', 'type=text&size=80', 'pigcms', '密钥', '', 'tenpay', '财付通', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('37', 'pay_yeepay_open', 'type=radio&value=1:开启|0:关闭', '0', '开启', '', 'yeepay', '银行卡支付（易宝）', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('38', 'pay_yeepay_merchantaccount', 'type=text&size=80', 'pigcms', '商户编号', '', 'yeepay', '银行卡支付（易宝）', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('39', 'pay_yeepay_merchantprivatekey', 'type=text&size=80', 'pigcms', '商户私钥', '', 'yeepay', '银行卡支付（易宝）', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('40', 'pay_yeepay_merchantpublickey', 'type=text&size=80', 'pigcms', '商户公钥', '', 'yeepay', '银行卡支付（易宝）', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('41', 'pay_yeepay_yeepaypublickey', 'type=text&size=80', 'pigcms', '易宝公钥', '', 'yeepay', '银行卡支付（易宝）', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('42', 'pay_yeepay_productcatalog', 'type=text&size=80', '1', '商品类别码', '', 'yeepay', '银行卡支付（易宝）', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('43', 'pay_allinpay_open', 'type=radio&value=1:开启|0:关闭', '0', '开启', '', 'allinpay', '银行卡支付（通联）', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('44', 'pay_allinpay_merchantid', 'type=text&size=80', 'pigcms', '商户号', '', 'allinpay', '银行卡支付（通联）', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('45', 'pay_allinpay_merchantkey', 'type=text&size=80', 'pigcms', 'MD5 KEY', '', 'allinpay', '银行卡支付（通联）', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('46', 'pay_weixin_open', 'type=radio&value=1:开启|0:关闭', '1', '开启', '', 'weixin', '微信支付', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('47', 'pay_weixin_appid', 'type=text&size=80', 'wxf33668d58442ff6e', 'Appid', '微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看。', 'weixin', '微信支付', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('48', 'pay_weixin_mchid', 'type=text&size=80', '1242856102', 'Mchid', '受理商ID，身份标识', 'weixin', '微信支付', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('49', 'pay_weixin_key', 'type=text&size=80', '8DD5C65B16849B9319D8FB8B2712D96E', 'Key', '商户支付密钥Key。审核通过后，在微信发送的邮件中查看。', 'weixin', '微信支付', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('50', 'wx_encodingaeskey', 'type=text', 'KKgybUkzUqrBGwCTgnAhKmqJmrzfZajJUnZenBZEVQN', '公众号消息加解密Key', '公众号消息加解密Key', '0', '', '13', '0', '1');
INSERT INTO `pigcms_config` VALUES ('51', 'wechat_appid', 'type=text&validate=required:true', 'wxf33668d58442ff6e', 'AppID', 'AppID', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('52', 'wechat_appsecret', 'type=text&validate=required:true', 'c1f6681b1f655fb2f2c1dc75c5a99b1e', 'AppSecret', 'AppSecret', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('53', 'bbs_url', 'type=text&validate=required:false', 'http://d.xins.cc/', '交流论坛网址', '商家用于交流的论坛网址，需自行搭建', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('54', 'user_store_num_limit', 'type=text&size=20', '0', '开店数限制', '用户最大开店数限制', '0', '每个用户最多可开店数限制，0为不限', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('55', 'sync_login_key', '', 'KKgybUkzUqrBGwCTgnAhKmqJmrzfZajJUnZenBZEVQN', '', '', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('56', 'is_check_mobile', '', '0', '手机号验证', '手机号验证', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('57', 'readme_title', 'type=text', '微商城代理销售服务和结算协议', '开店协议标题', '开店协议标题', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('58', 'readme_content', 'type=textarea&rows=20&cols=93', '在向消费者销售及向供应商采购的过程中，分销商需遵守：\r\n\r\n1 分销商必须严格履行对消费者的承诺，分销商不得以其与供应商之间的约定对抗其对消费者的承诺,如果分销商与供应商之间的约定不清或不能覆盖分销商对消费者的销售承诺，风险由分销商自行承担；分销商与买家出现任何纠纷，均应当依据淘宝相关规则进行处理；\r\n\r\n2 分销商承诺其最终销售给消费者的分销商品零售价格符合与供应商的约定；\r\n\r\n3 在消费者（买家）付款后，分销商应当及时向供应商支付采购单货款，否则7天后系统将关闭采购单交易，分销商应当自行承担因此而发生的交易风险；\r\n\r\n4 分销商应当在系统中及时同步供应商的实际产品库存，无论任何原因导致买家拍下后无货而产生的纠纷，均应由分销商自行承担风险与责任；\r\n\r\n5 分销商承诺分销商品所产生的销售订单均由分销平台相应的的供应商供货，以保证分销商品品质；\r\n\r\n6 分销商有义务确认消费者（买家）收货地址的有效性；\r\n\r\n7 分销商有义务在买家收到货物后，及时确认货款给供应商。如果在供应商发出货物30天后，分销商仍未确认收货，则系统会自动确认收货并将采购单对应的货款支付给供应商。', '开店协议内容', '用户开店前必须先阅读并同意该协议', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('59', 'max_store_drp_level', 'type=text&size=10', '3', '排他分销最大级别', '允许排他分销最多级别', '0', '', '12', '0', '1');
INSERT INTO `pigcms_config` VALUES ('60', 'open_store_drp', 'type=radio&value=1:开启|0:关闭', '1', '排他分销', '', '0', '', '12', '0', '1');
INSERT INTO `pigcms_config` VALUES ('61', 'open_platform_drp', 'type=radio&value=1:开启|0:关闭', '1', '全网分销', '', '0', '', '12', '0', '1');
INSERT INTO `pigcms_config` VALUES ('62', 'platform_mall_index_page', 'type=page&validate=required:true,number:true', '3', '平台商城首页内容', '选择一篇微页面作为平台商城首页的内容', '0', '', '11', '1', '1');
INSERT INTO `pigcms_config` VALUES ('63', 'platform_mall_open', 'type=radio&value=1:开启|0:关闭', '1', '是否开启平台商城', '如果不开启平台商城，则首页将显示为宣传介绍页面！否则显示平台商城', '0', '', '11', '2', '1');
INSERT INTO `pigcms_config` VALUES ('64', 'theme_index_group', '', 'default', '', '', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('65', 'wechat_qrcode', 'type=image&validate=required:true,url:true', 'http://d.xins.cc/upload/images/000/000/001/563b8dbaa8692.jpg', '公众号二维码', '您的公众号二维码', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('66', 'wechat_name', 'type=text&validate=required:true', '乐尚生活服务', '公众号名称', '公众号的名称', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('67', 'wechat_sourceid', 'type=text&validate=required:true', 'gh_357c34d87702', '公众号原始id', '公众号原始id', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('68', 'wechat_id', 'type=text&validate=required:true', 'leshanglife2015', '微信号', '微信号', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('69', 'wechat_token', 'type=text&validate=required:true', '6bfed0e5d265dfb68985209a746c89ab', '微信验证TOKEN', '微信验证TOKEN', '0', '', '8', '0', '0');
INSERT INTO `pigcms_config` VALUES ('70', 'wechat_encodingaeskey', 'type=text', 'iB31OmvVmA6Kqt90hOHpoxeOZ70swkjdToWGlW46f7n', 'EncodingAESKey', '公众号消息加解密Key,在使用安全模式情况下要填写该值，请先在管理中心修改，然后填写该值，仅限服务号和认证订阅号', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('71', 'wechat_encode', 'type=select&value=0:明文模式|1:兼容模式|2:安全模式', '0', '消息加解密方式', '如需使用安全模式请在管理中心修改，仅限服务号和认证订阅号', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('72', 'web_login_show', 'type=select&value=0:两种方式|1:仅允许帐号密码登录|2:仅允许微信扫码登录', '0', '用户登录电脑网站的方式', '用户登录电脑网站的方式', '0', '', '2', '0', '1');
INSERT INTO `pigcms_config` VALUES ('73', 'store_pay_weixin_open', 'type=radio&value=1:开启|0:关闭', '0', '开启', '', 'store_weixin', '商家微信支付', '7', '0', '1');
INSERT INTO `pigcms_config` VALUES ('74', 'im_appid', '', '', '', '', '0', '', '0', '0', '1');
INSERT INTO `pigcms_config` VALUES ('75', 'im_appkey', '', '', '', '', '0', '', '0', '0', '1');
INSERT INTO `pigcms_config` VALUES ('76', 'attachment_upload_type', 'type=select&value=0:保存到本服务器|1:保存到又拍云', '0', '附件保存方式', '附件保存方式', 'base', '基础配置', '14', '0', '1');
INSERT INTO `pigcms_config` VALUES ('77', 'attachment_up_bucket', 'type=text&size=50', 'pigcms22', 'BUCKET', 'BUCKET', 'upyun', '又拍云', '14', '0', '1');
INSERT INTO `pigcms_config` VALUES ('78', 'attachment_up_form_api_secret', 'type=text&size=50', 'qFD6DLf02lRwAjvveQVgyjh90Y0=', 'FORM_API_SECRET', 'FORM_API_SECRET', 'upyun', '又拍云', '14', '0', '1');
INSERT INTO `pigcms_config` VALUES ('79', 'attachment_up_username', 'type=text&size=50', 'pigcms', '操作员用户名', '操作员用户名', 'upyun', '又拍云', '14', '0', '1');
INSERT INTO `pigcms_config` VALUES ('80', 'attachment_up_password', 'type=text&size=50', 'pigcms123456', '操作员密码', '操作员密码', 'upyun', '又拍云', '14', '0', '1');
INSERT INTO `pigcms_config` VALUES ('81', 'attachment_up_domainname', 'type=text&size=50', 'pigcms22.b0.upaiyun.com', '云存储域名', '云存储域名 不包含http://', 'upyun', '又拍云', '14', '0', '1');
INSERT INTO `pigcms_config` VALUES ('82', 'web_index_cache', 'type=text&size=20&validate=required:true,number:true,maxlength:5', '0', 'PC端首页缓存时间', 'PC端首页缓存时间，0为不缓存（小时为单位）', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('83', 'notify_appid', '', 'aabbccddeeffgghhiijjkkllmmnn', '', '通知的appid', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('84', 'notify_appkey', '', 'aabbccddeeffgghhiijjkkll', '', '通知的KEY', '0', '', '0', '0', '0');
INSERT INTO `pigcms_config` VALUES ('85', 'is_diy_template', 'type=radio&value=1:开启|0:关闭', '1', '是否使用自定模板', '开启后平台商城首页将不使用微杂志。自定义模板目录/template/wap/default/theme', '0', '', '11', '3', '1');
INSERT INTO `pigcms_config` VALUES ('86', 'service_key', 'type=text&validate=required:false', '66c30caacbab5502649c6d0ff0ff38f8', '服务key', '请填写购买产品时的服务key', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('87', 'attachment_upload_unlink', 'type=select&value=0:不删除本地附件|1:删除本地附件', '1', '是否删除本地附件', '当附件存放在远程时，如果本地服务器空间充足，不建议删除本地附件', 'base', '基础配置', '14', '0', '1');
INSERT INTO `pigcms_config` VALUES ('88', 'syn_domain', 'type=text', 'http://wx.stysc.com', '营销活动地址', '部分功能需要调用平台内容，需要用到该网址', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('89', 'withdrawal_min_amount', 'type=text&validate=required:true,number:true', '100', '单次提现最低金额', '单次提现最低金额，0为不限', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('90', 'encryption', 'type=text', 'pigcms', '营销活动key', '与平台对接时需要用到', '0', '', '8', '0', '1');
INSERT INTO `pigcms_config` VALUES ('91', 'is_allow_comment_control', 'type=select&value=1:允许商户管理评论|2:不允许商户管理评论', '1', '是否允许商户管理评论', '开启后，商户可对评论进行删、改操作', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('92', 'ischeck_to_show_by_comment', 'type=select&value=1:不需要审核评论才显示|0:需审核即可显示评论', '0', '评论是否需要审核显示', '开启后，需商家或管理员审核方可显示，反之：不需审核即可显示', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('93', 'is_have_activity', 'type=radio&value=1:有|0:没有', '1', '活动', '', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('94', 'pc_usercenter_logo', 'type=image&validate=required:false,url:false', 'http://d.xins.cc/upload/images/000/000/001/563b8d4eb85a9.png', 'PC-个人用户中心LOGO图', '请填写带LOGO的网址，包含（http://域名）', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('95', 'pc_shopercenter_logo', 'type=image&validate=required:false,url:false', 'http://d.xins.cc/upload/images/000/000/001/563b8d4eb85a9.png', '商家中心LOGO图', '请填写带LOGO的网址，包含（http://域名）', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('96', 'sales_ratio', 'type=text&size=3&validate=required:true,number:true,maxlength:2', '2', '商家销售分成比例', '例：填入：2，则相应扣除2%，最高位100%，按照所填百分比进行扣除', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('98', 'weidian_key', 'type=salt', 'dxinsccpy888', '微店KEY', '对接微店使用的KEY，请妥善保管', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('99', 'ischeck_store', 'type=select&value=1:开店需要审核|0:开店无需审核', '0', '开店是否要审核', '开启后，会员开店需要后台审核通过后，店铺才能正常使用', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('100', 'synthesize_store', '', '1', '是否有综合商城', '是否有综合商城', '0', '', '1', '0', '0');
INSERT INTO `pigcms_config` VALUES ('102', 'sms_key', 'type=text&validate=required:true', '', '短信key', '短信的key（必填）', '0', '平台短信平台', '15', '0', '1');
INSERT INTO `pigcms_config` VALUES ('103', 'sms_price', 'type=text&validate=required:true,number:true,maxlength:2', '8', '短信价格(单位:分)', '每条多少分钱(卖给客户的)', '0', '平台短信平台', '15', '0', '1');
INSERT INTO `pigcms_config` VALUES ('104', 'sms_sign', 'type=text&validate=required:true,maxlength:5', '弈新云微店', '短信签名', '短信的前缀（一起发送给客户的）', '0', '平台短信平台', '15', '0', '1');
INSERT INTO `pigcms_config` VALUES ('106', 'sms_open', 'type=radio&value=1:开启|0:关闭', '1', '短信是否开启', '在以上内容全部完整的情况下，开启有效', '0', '平台短信平台', '15', '0', '1');
INSERT INTO `pigcms_config` VALUES ('101', 'sms_topdomain', 'type=text&validate=required:true,url:true', 'http://up.18biz.net', '发送短信授权域名', '发送短信授权域名', '0', '平台短信平台', '15', '0', '1');
INSERT INTO `pigcms_config` VALUES ('109', 'emergent_mode', 'type=radio&value=1:开启|0:关闭', '0', '紧急模式', '请不要随意开启，开启后会导致无法升级，使用短信等服务（接到小猪紧急通知时可开启此项）。', '0', '平台短信平台', '1', '0', '0');
INSERT INTO `pigcms_config` VALUES ('107', 'order_return_date', 'type=text&size=2&validate=required:true,number:true,maxlength:2', '7', '退货周期', '确认收货后多长时间内可以退货', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('108', 'order_complete_date', 'type=text&size=2&validate=required:true,number:true,maxlength:2', '15', '默认交易完成时间', '发货后，用户一直没有确认收货，此值为发货后的交易完成时间周期', '0', '', '1', '0', '1');
INSERT INTO `pigcms_config` VALUES ('111', 'is_open_wap_login_sms_check', 'type=select&value=0:不开启微信短信注册验证|1:开启短信注册验证', '0', 'wap站注册短信验证', 'wap站注册是否开启短信验证', '0', '', '2', '0', '1');
INSERT INTO `pigcms_config` VALUES ('112', 'weidian_version', '', '0', '微店版本', '微店版本 0 普通 1 对接', '0', '', '1', '0', '0');

-- ----------------------------
-- Table structure for `pigcms_config_group`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_config_group`;
CREATE TABLE `pigcms_config_group` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `gname` char(20) NOT NULL,
  `gsort` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='配置分组';

-- ----------------------------
-- Records of pigcms_config_group
-- ----------------------------
INSERT INTO `pigcms_config_group` VALUES ('1', '基础配置', '10', '1');
INSERT INTO `pigcms_config_group` VALUES ('2', '会员配置', '9', '1');
INSERT INTO `pigcms_config_group` VALUES ('7', '支付配置', '4', '1');
INSERT INTO `pigcms_config_group` VALUES ('8', '平台公众号配置', '3', '1');
INSERT INTO `pigcms_config_group` VALUES ('11', '微信版商城配置', '0', '1');
INSERT INTO `pigcms_config_group` VALUES ('12', '分销配置', '0', '1');
INSERT INTO `pigcms_config_group` VALUES ('13', '店铺绑定公众号配置', '0', '1');
INSERT INTO `pigcms_config_group` VALUES ('14', '附件配置', '0', '1');
INSERT INTO `pigcms_config_group` VALUES ('15', '平台短信接口配置', '0', '1');

-- ----------------------------
-- Table structure for `pigcms_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_coupon`;
CREATE TABLE `pigcms_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `store_id` int(11) NOT NULL COMMENT '商铺id',
  `name` varchar(255) NOT NULL COMMENT '优惠券名称',
  `face_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠券面值(起始)',
  `limit_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用优惠券的订单金额下限（为0：为不限定）',
  `most_have` int(11) NOT NULL COMMENT '单人最多拥有优惠券数量（0：不限制）',
  `total_amount` int(11) NOT NULL COMMENT '发放总量',
  `start_time` int(11) NOT NULL COMMENT '生效时间',
  `end_time` int(11) NOT NULL COMMENT '过期时间',
  `is_expire_notice` tinyint(1) NOT NULL COMMENT '到期提醒（0：不提醒；1：提醒）',
  `is_share` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许分享链接（0：不允许；1：允许）',
  `is_all_product` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否全店通用（0：全店通用；1：指定商品使用）',
  `is_original_price` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:非原价购买可使用；1：原价购买商品时可',
  `timestamp` int(11) NOT NULL COMMENT '添加优惠券的时间',
  `description` text NOT NULL COMMENT '使用说明',
  `used_number` int(11) NOT NULL DEFAULT '0' COMMENT '已使用数量',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '已领取数量',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否失效（0：失效；1：未失效）',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '券类型（1：优惠券； 2:赠送券）',
  UNIQUE KEY `id` (`id`),
  KEY `uid` (`uid`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠券';

-- ----------------------------
-- Records of pigcms_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_coupon_to_product`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_coupon_to_product`;
CREATE TABLE `pigcms_coupon_to_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL COMMENT '优惠券id',
  `product_id` int(11) NOT NULL COMMENT '产品id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `coupon_id` (`coupon_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠券产品对应表';

-- ----------------------------
-- Records of pigcms_coupon_to_product
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_custom_field`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_custom_field`;
CREATE TABLE `pigcms_custom_field` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `module_name` varchar(20) NOT NULL,
  `module_id` int(11) NOT NULL,
  `field_type` varchar(20) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`field_id`),
  KEY `store_id_2` (`store_id`,`module_name`,`module_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=217 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='自定义字段';

-- ----------------------------
-- Records of pigcms_custom_field
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_custom_page`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_custom_page`;
CREATE TABLE `pigcms_custom_page` (
  `page_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自定义页面id',
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '自定页面模块名',
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`page_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='自定义页面模块';

-- ----------------------------
-- Records of pigcms_custom_page
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_diy_attestation`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_diy_attestation`;
CREATE TABLE `pigcms_diy_attestation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `type` varchar(150) NOT NULL DEFAULT '' COMMENT '类型',
  `info` varchar(20) NOT NULL DEFAULT '' COMMENT '信息',
  `desc` varchar(250) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_diy_attestation
-- ----------------------------
INSERT INTO `pigcms_diy_attestation` VALUES ('1', 'admin_1', 'type=text&validate=required:true,cn_username:true', 'hh', 'hhh', '1');

-- ----------------------------
-- Table structure for `pigcms_diymenu_class`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_diymenu_class`;
CREATE TABLE `pigcms_diymenu_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `keyword` varchar(30) NOT NULL,
  `is_show` tinyint(1) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  `url` varchar(300) NOT NULL DEFAULT '',
  `wxsys` char(40) NOT NULL,
  `content` varchar(500) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL COMMENT 'type [0:文本，1：图文，2：音乐，3：商品，4：商品分类，5：微页面，6：微页面分类，7：店铺主页，8：会员主页]',
  `fromid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_diymenu_class
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_express`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_express`;
CREATE TABLE `pigcms_express` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`pigcms_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='快递公司表';

-- ----------------------------
-- Records of pigcms_express
-- ----------------------------
INSERT INTO `pigcms_express` VALUES ('1', 'ems', 'ems快递', 'http://www.ems.com.cn/', '0', '1419225737', '1');
INSERT INTO `pigcms_express` VALUES ('2', 'shentong', '申通快递', 'http://www.sto.cn/', '0', '1419220300', '1');
INSERT INTO `pigcms_express` VALUES ('3', 'yuantong', '圆通速递', 'http://www.yto.net.cn/', '0', '1419220397', '1');
INSERT INTO `pigcms_express` VALUES ('4', 'shunfeng', '顺丰速运', 'http://www.sf-express.com/', '0', '1419220418', '1');
INSERT INTO `pigcms_express` VALUES ('5', 'tiantian', '天天快递', 'http://www.ttkd.cn/', '0', '1419220435', '1');
INSERT INTO `pigcms_express` VALUES ('6', 'yunda', '韵达快递', 'http://www.yundaex.com/', '0', '1419220474', '1');
INSERT INTO `pigcms_express` VALUES ('7', 'zhongtong', '中通速递', 'http://www.zto.cn/', '0', '1419220493', '1');
INSERT INTO `pigcms_express` VALUES ('8', 'longbanwuliu', '龙邦物流', 'http://www.lbex.com.cn/', '0', '1419220511', '1');
INSERT INTO `pigcms_express` VALUES ('9', 'zhaijisong', '宅急送', 'http://www.zjs.com.cn/', '0', '1419220528', '1');
INSERT INTO `pigcms_express` VALUES ('10', 'quanyikuaidi', '全一快递', 'http://www.apex100.com/', '0', '1419220551', '1');
INSERT INTO `pigcms_express` VALUES ('11', 'huitongkuaidi', '汇通速递', 'http://www.htky365.com/', '0', '1419220569', '1');
INSERT INTO `pigcms_express` VALUES ('12', 'minghangkuaidi', '民航快递', 'http://www.cae.com.cn/', '0', '1419220586', '1');
INSERT INTO `pigcms_express` VALUES ('13', 'yafengsudi', '亚风速递', 'http://www.airfex.cn/', '0', '1419220605', '1');
INSERT INTO `pigcms_express` VALUES ('14', 'kuaijiesudi', '快捷速递', 'http://www.fastexpress.com.cn/', '0', '1419220623', '1');
INSERT INTO `pigcms_express` VALUES ('15', 'tiandihuayu', '天地华宇', 'http://www.hoau.net/', '0', '1419220676', '1');
INSERT INTO `pigcms_express` VALUES ('16', 'zhongtiekuaiyun', '中铁快运', 'http://www.cre.cn/', '0', '1427265253', '1');
INSERT INTO `pigcms_express` VALUES ('17', 'deppon', '德邦物流', 'http://www.deppon.com/', '0', '1427265464', '1');

-- ----------------------------
-- Table structure for `pigcms_financial_record`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_financial_record`;
CREATE TABLE `pigcms_financial_record` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `order_no` varchar(100) NOT NULL DEFAULT '' COMMENT '订单号',
  `income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '收入 负值为支出',
  `type` tinyint(1) NOT NULL COMMENT '类型 1订单入账 2提现 3退款 4系统退款 5分销',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `payment_method` varchar(30) NOT NULL DEFAULT '' COMMENT '支付方式',
  `trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '交易号',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1进行中 2退款 3成功 4失败',
  `user_order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户订单id,统一分销订单',
  `profit` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分销利润',
  `storeOwnPay` tinyint(1) NOT NULL DEFAULT '0',
  `bak` varchar(500) DEFAULT '' COMMENT '备注',
  `return_id` int(11) unsigned DEFAULT '0' COMMENT '退货id',
  PRIMARY KEY (`pigcms_id`),
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `order_no` (`order_no`) USING BTREE,
  KEY `return_id` (`return_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='财务记录';

-- ----------------------------
-- Records of pigcms_financial_record
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_first`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_first`;
CREATE TABLE `pigcms_first` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `content` varchar(500) NOT NULL COMMENT '回复内容',
  `fromid` tinyint(1) unsigned NOT NULL COMMENT '网站功能回复（1：网站首页，2:团购，3：订餐）',
  `title` varchar(50) NOT NULL COMMENT '图文回复标题',
  `info` varchar(200) NOT NULL COMMENT '图文回复内容',
  `pic` varchar(200) NOT NULL COMMENT '图文回复图片',
  `url` varchar(200) NOT NULL COMMENT '图文回复外站链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_first
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_flink`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_flink`;
CREATE TABLE `pigcms_flink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `info` varchar(100) NOT NULL,
  `url` varchar(150) NOT NULL,
  `add_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_flink
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_fx_order`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_fx_order`;
CREATE TABLE `pigcms_fx_order` (
  `fx_order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fx_order_no` varchar(100) NOT NULL DEFAULT '' COMMENT '订单号',
  `fx_trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '交易单号',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '买家id',
  `session_id` varchar(32) NOT NULL,
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '主订单id',
  `order_no` varchar(100) NOT NULL DEFAULT '' COMMENT '主订单号',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供货商id',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '分销商id',
  `postage` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价',
  `cost_sub_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品成本总价',
  `quantity` int(5) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `cost_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本总额',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订单状态',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间',
  `paid_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `supplier_sent_time` int(11) NOT NULL DEFAULT '0' COMMENT '供货商发货时间',
  `complate_time` int(11) NOT NULL DEFAULT '0' COMMENT '交易完成时间',
  `delivery_user` varchar(100) NOT NULL DEFAULT '' COMMENT '收货人',
  `delivery_tel` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人电话',
  `delivery_address` varchar(200) NOT NULL DEFAULT '' COMMENT '收货地址',
  `user_order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户订单id,统一分销订单',
  `suppliers` varchar(500) NOT NULL DEFAULT '' COMMENT '供货商',
  `fx_postage` varchar(500) NOT NULL DEFAULT '' COMMENT '分销运费',
  PRIMARY KEY (`fx_order_id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `order_no` (`order_no`) USING BTREE,
  KEY `supplier_id` (`supplier_id`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='分销订单';

-- ----------------------------
-- Records of pigcms_fx_order
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_fx_order_product`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_fx_order_product`;
CREATE TABLE `pigcms_fx_order_product` (
  `pigcms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fx_order_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分销订单id',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '单价',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `quantity` int(5) NOT NULL DEFAULT '0' COMMENT '数量',
  `sku_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存id',
  `sku_data` text NOT NULL COMMENT '库存信息',
  `comment` text NOT NULL COMMENT '买家留言',
  `source_product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '源商品id',
  PRIMARY KEY (`pigcms_id`),
  KEY `fx_order_id` (`fx_order_id`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE,
  KEY `sku_id` (`sku_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='分销订单商品';

-- ----------------------------
-- Records of pigcms_fx_order_product
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_image_text`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_image_text`;
CREATE TABLE `pigcms_image_text` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `title` varchar(64) NOT NULL COMMENT '标题',
  `author` varchar(10) NOT NULL COMMENT '作者',
  `cover_pic` varchar(200) NOT NULL COMMENT '封面图',
  `digest` varchar(300) NOT NULL COMMENT '介绍',
  `content` text NOT NULL COMMENT '内容',
  `url` varchar(200) NOT NULL COMMENT '外链',
  `dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `is_show` tinyint(1) unsigned NOT NULL COMMENT '封面图是否显示正文（0:不显示，1：显示）',
  `url_title` varchar(300) NOT NULL COMMENT '外链名称',
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='图文表';

-- ----------------------------
-- Records of pigcms_image_text
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_keyword`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_keyword`;
CREATE TABLE `pigcms_keyword` (
  `pigcms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `content` varchar(200) NOT NULL,
  `from_id` int(11) NOT NULL,
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_keyword
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_location_qrcode`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_location_qrcode`;
CREATE TABLE `pigcms_location_qrcode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket` varchar(500) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `add_time` int(11) NOT NULL,
  `openid` char(40) NOT NULL,
  `lat` char(10) NOT NULL,
  `lng` char(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2815 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='使用微信登录生成的临时二维码';

-- ----------------------------
-- Records of pigcms_location_qrcode
-- ----------------------------
INSERT INTO `pigcms_location_qrcode` VALUES ('2814', 'gQEy8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xLzlFaWhFSW5scEFmZFVHUXVDbWJoAAIEeSCbVgMEgDoJAA==', '0', '1453006967', '', '', '');

-- ----------------------------
-- Table structure for `pigcms_login_qrcode`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_login_qrcode`;
CREATE TABLE `pigcms_login_qrcode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ticket` varchar(500) NOT NULL,
  `uid` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='使用微信登录生成的临时二维码';

-- ----------------------------
-- Records of pigcms_login_qrcode
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_margin_recharge_log`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_margin_recharge_log`;
CREATE TABLE `pigcms_margin_recharge_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '充值记录id',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '收款方店铺id',
  `distributor_id` int(11) NOT NULL DEFAULT '0' COMMENT '打款方店铺id',
  `bank_id` int(11) NOT NULL DEFAULT '0' COMMENT '开户银行',
  `bank_card` varchar(30) NOT NULL DEFAULT '0' COMMENT '银行卡号',
  `bank_card_user` varchar(20) NOT NULL DEFAULT '0' COMMENT '开卡人姓名',
  `opening_bank` varchar(30) NOT NULL DEFAULT '0' COMMENT '开户行',
  `phone` varchar(20) NOT NULL DEFAULT '0' COMMENT '打款人手机号',
  `apply_recharge` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值额度',
  `add_time` varchar(30) NOT NULL DEFAULT '0' COMMENT '充值时间',
  `status` tinyint(5) NOT NULL DEFAULT '0' COMMENT '0 未确认 1 已确认',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_margin_recharge_log
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_my_supplier`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_my_supplier`;
CREATE TABLE `pigcms_my_supplier` (
  `seller_store_id` int(11) NOT NULL DEFAULT '0' COMMENT '分销商店铺id',
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '分销商id',
  `supplier_store_id` int(11) NOT NULL DEFAULT '0' COMMENT '供货商店铺id',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供货商id',
  KEY `seller_store_id` (`seller_store_id`) USING BTREE,
  KEY `supplier_store_id` (`supplier_store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='我的供货商';

-- ----------------------------
-- Records of pigcms_my_supplier
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_ng_word`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_ng_word`;
CREATE TABLE `pigcms_ng_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ng_word` varchar(100) NOT NULL,
  `replace_word` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `ng_word` (`ng_word`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='敏感词表';

-- ----------------------------
-- Records of pigcms_ng_word
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order`;
CREATE TABLE `pigcms_order` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `order_no` varchar(100) NOT NULL COMMENT '订单号',
  `trade_no` varchar(100) NOT NULL COMMENT '交易号',
  `pay_type` varchar(10) NOT NULL COMMENT '支付方式',
  `third_id` varchar(100) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '买家id',
  `session_id` varchar(32) NOT NULL,
  `postage` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `sub_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品金额（不含邮费）',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额（含邮费）',
  `pro_count` int(11) NOT NULL COMMENT '商品的个数',
  `pro_num` int(10) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `address` text NOT NULL COMMENT '收货地址',
  `address_user` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人',
  `address_tel` varchar(20) NOT NULL DEFAULT '' COMMENT '收货人电话',
  `payment_method` varchar(50) NOT NULL DEFAULT '' COMMENT '支付方式',
  `peerpay_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单为代付订单时，1：单人付，2：多人付',
  `peerpay_content` varchar(200) NOT NULL COMMENT '代付订单时，代付人求助语',
  `shipping_method` varchar(50) NOT NULL DEFAULT '' COMMENT '物流方式 express快递发货 selffetch上门自提',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型 0普通 1代付 2送礼 3分销',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 0临时订单 1未支付 2未发货 3已发货 4已完成 5已取消 6退款中 ',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单时间',
  `paid_time` int(11) NOT NULL DEFAULT '0' COMMENT '付款时间',
  `sent_time` int(11) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `delivery_time` int(11) NOT NULL DEFAULT '0' COMMENT '收货时间',
  `cancel_time` int(11) NOT NULL DEFAULT '0' COMMENT '取消时间',
  `complate_time` int(11) NOT NULL,
  `refund_time` int(11) NOT NULL COMMENT '退款时间',
  `comment` varchar(500) NOT NULL DEFAULT '' COMMENT '买家留言',
  `bak` varchar(500) NOT NULL DEFAULT '' COMMENT '备注',
  `star` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加星订单 1|2|3|4|5 默认0',
  `pay_money` decimal(10,2) NOT NULL COMMENT '实际付款金额',
  `cancel_method` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单取消方式 0过期自动取消 1卖家手动取消 2买家手动取消',
  `float_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单浮动金额',
  `is_fx` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含分销商品 0 否 1是',
  `fx_order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销订单id',
  `user_order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户订单id,统一分销订单',
  `suppliers` varchar(500) NOT NULL DEFAULT '' COMMENT '商品供货商',
  `packaging` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '打包中',
  `fx_postage` varchar(500) NOT NULL DEFAULT '' COMMENT '分销运费详细 supplier_id=>postage',
  `useStorePay` tinyint(1) NOT NULL DEFAULT '0',
  `storePay` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收款店铺id',
  `storeOpenid` varchar(100) NOT NULL,
  `sales_ratio` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商家销售分成比例,按照所填百分比进行扣除',
  `is_check` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否对账，1：未对账，2：已对账',
  `activity_data` text COMMENT '营销系统活动订单数据',
  `use_deposit_pay` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否满足保证金扣款',
  `is_assigned` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单里商品是否已经分配到门店,0:未分配,1:部分分配,2:全部分配 ',
  `has_physical_send` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有门店配送项:0无 1有',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_no` (`order_no`) USING BTREE,
  UNIQUE KEY `trade_no` (`trade_no`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `store_id_2` (`store_id`,`status`) USING BTREE,
  KEY `storePay` (`storePay`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单';

-- ----------------------------
-- Records of pigcms_order
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_check_log`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_check_log`;
CREATE TABLE `pigcms_order_check_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) DEFAULT NULL COMMENT '订单id',
  `order_no` varchar(100) DEFAULT NULL COMMENT '订单号',
  `store_id` int(11) DEFAULT NULL COMMENT '被操作的商铺id',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `admin_uid` int(11) DEFAULT NULL COMMENT '操作人uid',
  `ip` bigint(20) DEFAULT NULL COMMENT '操作人ip',
  `timestamp` int(11) DEFAULT NULL COMMENT '记录的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_order_check_log
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_coupon`;
CREATE TABLE `pigcms_order_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `name` varchar(255) NOT NULL COMMENT '优惠券名称',
  `user_coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_coupon表id',
  `money` float(8,2) NOT NULL COMMENT '优惠券金额',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_order_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_discount`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_discount`;
CREATE TABLE `pigcms_order_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户UID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID（供货商店铺ID）',
  `discount` double(3,1) NOT NULL DEFAULT '10.0' COMMENT '折扣',
  `is_postage_free` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包邮，1：是包邮，0：不包邮',
  `postage_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '邮费',
  UNIQUE KEY `id` (`id`),
  KEY `order_id` (`order_id`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单折扣表';

-- ----------------------------
-- Records of pigcms_order_discount
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_package`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_package`;
CREATE TABLE `pigcms_order_package` (
  `package_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `express_code` varchar(50) NOT NULL,
  `express_company` varchar(50) NOT NULL DEFAULT '' COMMENT '快递公司',
  `express_no` varchar(50) NOT NULL DEFAULT '' COMMENT '快递单号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0未发货 1已发货 2已到店 3已签收',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `sign_name` varchar(30) NOT NULL DEFAULT '' COMMENT '签收人',
  `sign_time` int(11) NOT NULL DEFAULT '0' COMMENT '签收时间',
  `products` varchar(500) NOT NULL DEFAULT '' COMMENT '商品集合',
  `user_order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户订单id',
  `physical_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `courier_id` int(11) NOT NULL DEFAULT '0' COMMENT '配送员id',
  `order_products` varchar(500) NOT NULL DEFAULT '' COMMENT '订单商品集合',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '配送员开始配送时间',
  `arrive_time` int(11) NOT NULL DEFAULT '0' COMMENT '配送员送达时间',
  PRIMARY KEY (`package_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单包裹';

-- ----------------------------
-- Records of pigcms_order_package
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_peerpay`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_peerpay`;
CREATE TABLE `pigcms_order_peerpay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `order_id` int(11) NOT NULL COMMENT '订单表ID',
  `peerpay_no` varchar(100) NOT NULL COMMENT '代支付订单号，格式：PEERPAY_生成另外订单号',
  `money` float(8,2) NOT NULL COMMENT '支付金额',
  `name` varchar(50) DEFAULT NULL COMMENT '支付人姓名',
  `content` varchar(255) DEFAULT NULL COMMENT '支付人留言',
  `pay_dateline` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `third_id` varchar(100) NOT NULL COMMENT '第三方支付ID',
  `third_data` text NOT NULL COMMENT '第三方支付返回内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付状态，0：未支付，1：已支付',
  `untread_money` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '退回金额',
  `untread_dateline` int(11) NOT NULL DEFAULT '0' COMMENT '退回时间',
  `untread_content` varchar(200) DEFAULT NULL COMMENT '退回申请说明',
  `untread_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退回状态，0：未完成，1：已完成',
  UNIQUE KEY `id` (`id`) USING BTREE,
  UNIQUE KEY `peerpay_no` (`peerpay_no`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_order_peerpay
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_printing_template`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_printing_template`;
CREATE TABLE `pigcms_order_printing_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `typename` varchar(50) NOT NULL COMMENT '系统模板类型名',
  `folder` varchar(200) DEFAULT NULL COMMENT '系统模板所在static下的文件夹',
  `filename` varchar(200) NOT NULL COMMENT '调用的系统模板名',
  `text` longtext COMMENT '系统模板具体内容',
  `timestamp` int(11) NOT NULL COMMENT '操作的时间戳',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '模板状态开启:1/关闭:0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_order_printing_template
-- ----------------------------
INSERT INTO `pigcms_order_printing_template` VALUES ('1', '购物清单 ', 'order_print', 'shopper.php', '', '1412456123', '1');
INSERT INTO `pigcms_order_printing_template` VALUES ('2', '配货单', 'order_print', 'invoice.php', '', '1412456111', '1');

-- ----------------------------
-- Table structure for `pigcms_order_product`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_product`;
CREATE TABLE `pigcms_order_product` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单商品id',
  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '订单id',
  `product_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品id',
  `sku_id` int(10) NOT NULL DEFAULT '0' COMMENT '库存id',
  `sku_data` text NOT NULL COMMENT '库存信息',
  `pro_num` int(10) NOT NULL DEFAULT '0' COMMENT '数量',
  `pro_price` decimal(10,2) NOT NULL,
  `pro_weight` float(10,2) NOT NULL COMMENT '每一个产品的重量，单位：克',
  `comment` text NOT NULL COMMENT '买家留言',
  `is_packaged` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已打包 0未打包 1已打包',
  `in_package_status` tinyint(1) NOT NULL COMMENT '在包裹里的状态 0未发货 1已发货 2已到店 3已签收',
  `is_fx` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销商品 0否 1是',
  `supplier_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '供货商id',
  `original_product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '源商品id',
  `user_order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户订单id',
  `is_comment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已评论，1：是，0：否',
  `return_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '产品退款状态，0：未退款，1：部分退款，2：全部退完',
  `rights_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '产品维权状态，0：未维权，1：部分维权，2：全部维权',
  `is_present` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为赠品，1：是，0：否',
  `sp_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `profit` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分销单件商品利润',
  PRIMARY KEY (`pigcms_id`),
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE,
  KEY `sku_id` (`sku_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单商品';

-- ----------------------------
-- Records of pigcms_order_product
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_product_physical`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_product_physical`;
CREATE TABLE `pigcms_order_product_physical` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '订单id',
  `sku_id` int(11) NOT NULL DEFAULT '0' COMMENT '规格sku_id',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单商品id',
  `physical_id` int(10) NOT NULL DEFAULT '0' COMMENT '仓库/门店id',
  PRIMARY KEY (`pigcms_id`),
  KEY `sku_id` (`sku_id`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE,
  KEY `physical_id` (`physical_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='门店分配订单商品关系';

-- ----------------------------
-- Records of pigcms_order_product_physical
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_reward`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_reward`;
CREATE TABLE `pigcms_order_reward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单表ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `rid` int(11) NOT NULL COMMENT '满减/送ID',
  `name` varchar(255) NOT NULL COMMENT '活动名称',
  `content` text NOT NULL COMMENT '描述序列化数组',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单优惠表';

-- ----------------------------
-- Records of pigcms_order_reward
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_sms`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_sms`;
CREATE TABLE `pigcms_order_sms` (
  `sms_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `uid` int(11) NOT NULL COMMENT '购买人uid',
  `pay_openid` varchar(50) DEFAULT NULL COMMENT '支付人openid',
  `smspay_no` varchar(100) DEFAULT NULL COMMENT '短信支付订单号，格式：SMSPAY_生成另外订单号',
  `trade_no` varchar(100) NOT NULL COMMENT '交易流水号 ',
  `sms_price` int(11) DEFAULT '0' COMMENT '短信单价(单位：分)',
  `sms_num` int(11) DEFAULT NULL COMMENT '购买短信数量',
  `money` float(8,2) NOT NULL COMMENT '支付金额',
  `name` varchar(50) DEFAULT NULL COMMENT '支付人姓名',
  `content` varchar(255) DEFAULT NULL COMMENT '支付人留言',
  `pay_dateline` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `third_id` varchar(100) NOT NULL COMMENT '第三方支付ID',
  `third_data` text NOT NULL COMMENT '第三方支付返回内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付状态，0：未支付，1：已支付',
  PRIMARY KEY (`sms_order_id`),
  UNIQUE KEY `id` (`sms_order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='短信订单表';

-- ----------------------------
-- Records of pigcms_order_sms
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_order_trade`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_order_trade`;
CREATE TABLE `pigcms_order_trade` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `third_data` text NOT NULL,
  PRIMARY KEY (`pigcms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='交易支付返回消息详细数据';

-- ----------------------------
-- Records of pigcms_order_trade
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_platform`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_platform`;
CREATE TABLE `pigcms_platform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `info` varchar(500) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `key` varchar(50) NOT NULL COMMENT '关键词',
  `url` varchar(200) NOT NULL COMMENT '外链url',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='首页回复配置';

-- ----------------------------
-- Records of pigcms_platform
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_points`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_points`;
CREATE TABLE `pigcms_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL COMMENT '积分店铺id',
  `points` int(11) DEFAULT NULL COMMENT '积分数',
  `type` tinyint(1) DEFAULT NULL COMMENT '给积分类别: 1:关注我的微信,2:每成功交易笔数, 3:每购买金额多少元',
  `trade_or_amount` int(11) DEFAULT NULL COMMENT '当type=2:为交易笔数值,type=3：为购买金额数',
  `is_call_to_fans` tinyint(1) DEFAULT NULL COMMENT '是否通知粉丝',
  `starttime` int(2) DEFAULT NULL COMMENT '开始时间 整点',
  `endtime` int(2) DEFAULT NULL COMMENT '结束时间 整点',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0:关闭,1开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_points
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_postage_template`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_postage_template`;
CREATE TABLE `pigcms_postage_template` (
  `tpl_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '邮费模板id',
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `tpl_name` varchar(100) NOT NULL DEFAULT '' COMMENT '模板名称',
  `tpl_area` varchar(10000) NOT NULL COMMENT '模板配送区域',
  `last_time` int(11) NOT NULL,
  `copy_id` int(11) NOT NULL,
  PRIMARY KEY (`tpl_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='邮费模板';

-- ----------------------------
-- Records of pigcms_postage_template
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_present`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_present`;
CREATE TABLE `pigcms_present` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) NOT NULL COMMENT '添加时间',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `name` varchar(255) NOT NULL COMMENT '赠品名称',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '赠品开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '赠品结束时间',
  `expire_date` int(11) NOT NULL DEFAULT '0' COMMENT '领取有效期，此只对虚拟产品,保留字段',
  `expire_number` int(11) NOT NULL DEFAULT '0' COMMENT '领取限制，此只对虚拟产品，保留字段',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '领取次数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效，1：有效，0：无效，',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`,`start_time`,`end_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='赠品表';

-- ----------------------------
-- Records of pigcms_present
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_present_product`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_present_product`;
CREATE TABLE `pigcms_present_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '赠品表ID',
  `product_id` int(11) NOT NULL COMMENT '产品表ID',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='赠品产品列表';

-- ----------------------------
-- Records of pigcms_present_product
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product`;
CREATE TABLE `pigcms_product` (
  `product_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `category_fid` int(11) NOT NULL,
  `category_id` int(10) NOT NULL DEFAULT '0' COMMENT '分类id',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '分组id',
  `name` varchar(300) NOT NULL DEFAULT '' COMMENT '商品名称',
  `sale_way` char(1) NOT NULL DEFAULT '0' COMMENT '出售方式 0一口价 1拍卖',
  `buy_way` char(1) NOT NULL DEFAULT '1' COMMENT '购买方式 1店内购买 0店外购买',
  `type` char(1) NOT NULL DEFAULT '0' COMMENT '商品类型 0实物 1虚拟',
  `quantity` int(10) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `weight` float(10,2) NOT NULL COMMENT '产品重量，单位：克',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '商品编码',
  `image` varchar(200) NOT NULL DEFAULT '' COMMENT '商品主图',
  `image_size` varchar(200) NOT NULL,
  `postage_type` char(1) NOT NULL DEFAULT '0' COMMENT '邮费类型 0统计邮费 1邮费模板 ',
  `postage` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `postage_template_id` int(10) NOT NULL DEFAULT '0' COMMENT '邮费模板',
  `buyer_quota` int(5) NOT NULL DEFAULT '0' COMMENT '买家限购',
  `allow_discount` char(1) NOT NULL DEFAULT '1' COMMENT '参加会员折扣',
  `invoice` char(1) NOT NULL DEFAULT '0' COMMENT '发票 0无 1有',
  `warranty` char(1) NOT NULL DEFAULT '0' COMMENT '保修 0无 1有',
  `sold_time` int(11) NOT NULL DEFAULT '0' COMMENT '开售时间 0立即开售',
  `sales` int(10) NOT NULL DEFAULT '0' COMMENT '商品销量',
  `show_sku` char(1) NOT NULL DEFAULT '1' COMMENT '显示库存 0 不显示 1显示',
  `status` char(1) NOT NULL DEFAULT '1' COMMENT '状态 0仓库中 1上架 2 删除',
  `date_added` varchar(20) NOT NULL DEFAULT '0' COMMENT '添加日期',
  `soldout` char(1) NOT NULL DEFAULT '0' COMMENT '售完 0未售完 1已售完',
  `pv` int(10) NOT NULL DEFAULT '0' COMMENT '商品浏览量',
  `uv` int(10) NOT NULL DEFAULT '0' COMMENT '商品浏览人数',
  `buy_url` varchar(200) NOT NULL DEFAULT '' COMMENT '外部购买地址',
  `intro` varchar(300) NOT NULL DEFAULT '' COMMENT '商品简介',
  `info` text NOT NULL COMMENT '商品描述',
  `has_custom` tinyint(4) NOT NULL COMMENT '有没有自定义文本',
  `has_category` tinyint(4) NOT NULL COMMENT '有没有商品分组',
  `properties` text NOT NULL COMMENT '商品属性',
  `has_property` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有商品属性 0否 1是',
  `is_fx` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销商品',
  `fx_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分销类型 0全网分销 1排他分销',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本价格',
  `min_fx_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分销最低价格',
  `max_fx_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分销最高价格',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商家推荐',
  `source_product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销来源商品id',
  `supplier_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '供货商店铺id',
  `delivery_address_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收货地址 0为买家地址，大于0为分销商地址',
  `last_edit_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `original_product_id` int(11) NOT NULL DEFAULT '0' COMMENT '分销原始id,同一商品各分销商相同',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_fx_setting` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已设置分销信息',
  `collect` int(11) unsigned NOT NULL COMMENT '收藏数',
  `attention_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `drp_profit` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品分销利润总额',
  `drp_seller_qty` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销商数量(被分销次数)',
  `drp_sale_qty` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销商品销量',
  `unified_price_setting` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '供货商统一定价',
  `drp_level_1_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级分销商商品价格',
  `drp_level_2_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级分销商商品价格',
  `drp_level_3_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '三级分销商商品价格',
  `drp_level_1_cost_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级分销商商品成本价格',
  `drp_level_2_cost_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级分销商商品成本价格',
  `drp_level_3_cost_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '三级分销商商品成本价格',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热门 0否 1是',
  `is_wholesale` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '批发商品',
  `wholesale_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '批发价格',
  `sale_min_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '建议最低售价',
  `sale_max_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '建议最高售价',
  `wholesale_product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '批发商品id',
  `public_display` tinyint(1) NOT NULL DEFAULT '1' COMMENT '开启后将会在微信综合商城和pc综合商城展示,0不展示1展示',
  `unified_profit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否统一直销利润',
  `is_whitelist` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否是白名单商品',
  PRIMARY KEY (`product_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `category_id` (`category_id`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE,
  KEY `postage_template_id` (`postage_template_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品';

-- ----------------------------
-- Records of pigcms_product
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_category`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_category`;
CREATE TABLE `pigcms_product_category` (
  `cat_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `cat_name` varchar(50) NOT NULL COMMENT '分类名称',
  `cat_desc` varchar(1000) NOT NULL COMMENT '描述',
  `cat_fid` int(10) NOT NULL DEFAULT '0' COMMENT '父类id',
  `cat_pic` varchar(50) NOT NULL COMMENT 'wap端栏目图片',
  `cat_pc_pic` varchar(50) NOT NULL COMMENT 'pc端栏目图片',
  `cat_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `cat_sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序，值越大优前',
  `cat_path` varchar(1000) NOT NULL,
  `cat_level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '级别',
  `filter_attr` varchar(255) NOT NULL COMMENT '拥有的属性id 用,号分割',
  `tag_str` varchar(1024) NOT NULL COMMENT 'tag列表，每个tag_id之间用逗号分割',
  `cat_parent_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '父类状态',
  PRIMARY KEY (`cat_id`),
  KEY `parent_category_id` (`cat_fid`) USING BTREE,
  KEY `cat_sort` (`cat_sort`) USING BTREE,
  KEY `cat_name` (`cat_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品分类';

-- ----------------------------
-- Records of pigcms_product_category
-- ----------------------------
INSERT INTO `pigcms_product_category` VALUES ('1', '女人', '', '0', '', 'category/2015/07/55af5d8c50aaf.png', '1', '0', '0,01', '1', '8,9', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('2', '女装', '', '1', 'category/2015/04/55273743e28a7.jpg', '', '1', '1', '0,01,02', '2', '9', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('3', '女鞋', '', '1', 'category/2015/04/5527380cdee55.jpg', '', '1', '0', '0,01,03', '2', '8', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('4', '女包', '', '1', 'category/2015/04/5527383f1d1f5.jpg', '', '1', '0', '0,01,04', '2', '8', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('5', '男人', '', '0', '', 'category/2015/07/55af5e3520ebd.png', '1', '0', '0,05', '1', '8', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('6', '男装', '', '5', 'category/2015/04/552738de81f42.jpg', '', '1', '0', '0,05,06', '2', '8', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('7', '男鞋', '', '5', 'category/2015/04/5527389296c4c.jpg', '', '1', '0', '0,05,07', '2', '9', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('8', '男包', '', '5', 'category/2015/04/552738b59a84a.jpg', '', '1', '0', '0,05,08', '2', '9', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('9', '女士内衣', '', '1', 'category/2015/04/552739159e37e.jpg', '', '1', '0', '0,01,09', '2', '9', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('10', '男士内衣', '', '5', 'category/2015/04/5527393c02fa7.jpg', '', '1', '0', '0,05,10', '2', '8', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('11', '餐饮食品', '', '0', '', 'category/2015/07/55af5e63c5a59.png', '1', '0', '0,11', '1', '13', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('12', '茶叶', '', '11', 'category/2015/04/552739cd6bbce.jpg', '', '1', '0', '0,11,12', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('13', '坚果炒货', '', '11', 'category/2015/04/552739ecaa0ad.jpg', '', '1', '0', '0,11,13', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('14', '零食', '', '11', 'category/2015/04/55273a09cb340.jpg', '', '1', '0', '0,11,14', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('15', '特产', '', '11', 'category/2015/04/55273b8f95804.png', '', '1', '0', '0,11,15', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('16', '家居服', '', '1', 'category/2015/04/55273be0a0771.jpg', '', '1', '0', '0,01,16', '2', '8', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('17', '服饰配件', '', '1', 'category/2015/04/55273c530d6a6.jpg', '', '1', '0', '0,01,17', '2', '8', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('18', '围巾手套', '', '1', 'category/2015/04/55273ce948db8.jpg', '', '1', '0', '0,01,18', '2', '8', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('19', '棉袜丝袜', '', '1', 'category/2015/04/55273d24949ae.jpg', '', '1', '0', '0,01,19', '2', '8', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('20', '个护美妆', '', '0', '', 'category/2015/07/55af5e890a30a.png', '1', '0', '0,20', '1', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('21', '清洁', '', '20', 'category/2015/04/55273e9e9e563.png', '', '1', '0', '0,20,21', '2', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('22', '护肤', '', '20', 'category/2015/04/55273eb645e0b.png', '', '1', '0', '0,20,22', '2', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('23', '面膜', '', '20', 'category/2015/04/55273ee20197e.jpg', '', '1', '0', '0,20,23', '2', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('24', '眼霜', '', '20', 'category/2015/04/55273f0606ebe.jpg', '', '1', '0', '0,20,24', '2', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('25', '精华', '', '20', 'category/2015/04/55273f2f28827.jpg', '', '1', '0', '0,20,25', '2', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('26', '防晒', '', '20', 'category/2015/04/55273f52c60f0.jpg', '', '1', '0', '0,20,26', '2', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('27', '香水彩妆', '', '20', 'category/2015/04/55273f8ddc835.jpg', '', '1', '0', '0,20,27', '2', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('28', '个人护理', '', '20', 'category/2015/04/55273fac76513.jpg', '', '1', '0', '0,20,28', '2', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('29', '沐浴洗护', '', '20', 'category/2015/04/55273fe4c6469.jpg', '', '1', '0', '0,20,29', '2', '15', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('30', '母婴玩具', '', '0', '', 'category/2015/07/55af5ecbcf208.png', '1', '0', '0,30', '1', '14', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('31', '孕妈食品', '', '30', 'category/2015/04/552740b099e46.jpg', '', '1', '0', '0,30,31', '2', '14', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('32', '妈妈护肤', '', '30', 'category/2015/04/552740dfca8dc.jpg', '', '1', '0', '0,30,32', '2', '14', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('33', '孕妇装', '', '30', 'category/2015/04/5527410749daa.jpg', '', '1', '0', '0,30,33', '2', '14', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('34', '宝宝用品', '', '30', 'category/2015/04/5527419a167ba.jpg', '', '1', '0', '0,30,34', '2', '14', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('35', '童装童鞋', '', '30', 'category/2015/04/552741cb6979e.jpg', '', '1', '0', '0,30,35', '2', '14', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('36', '童车童床', '', '30', 'category/2015/04/5527420d5643d.png', '', '1', '0', '0,30,36', '2', '14', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('37', '玩具乐器', '', '30', 'category/2015/04/5527423eb17bd.jpg', '', '1', '0', '0,30,37', '2', '14', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('38', '寝具服饰', '', '30', 'category/2015/04/55274283af0a4.jpg', '', '1', '0', '0,30,38', '2', '14', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('39', '家居百货', '', '0', '', 'category/2015/07/55af5ee50597a.png', '1', '0', '0,39', '1', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('40', '家纺', '', '39', 'category/2015/04/552745a79931e.png', '', '1', '0', '0,39,40', '2', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('41', '厨具', '', '39', 'category/2015/04/552745e700431.png', '', '1', '0', '0,39,41', '2', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('42', '家用', '', '39', 'category/2015/04/55274acb138c9.jpg', '', '1', '0', '0,39,42', '2', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('43', '收纳', '', '39', 'category/2015/04/5527462826195.jpg', '', '1', '0', '0,39,43', '2', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('44', '家具', '', '39', 'category/2015/04/5527464a87c77.jpg', '', '1', '0', '0,39,44', '2', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('45', '建材', '', '39', 'category/2015/04/5527466f6a0d6.jpg', '', '1', '0', '0,39,45', '2', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('46', '纸品', '', '39', 'category/2015/04/552746ac0f269.jpg', '', '1', '0', '0,39,46', '2', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('47', '女性护理', '', '1', 'category/2015/04/55274720db396.jpg', '', '1', '0', '0,01,47', '2', '9', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('49', '运动户外', '', '0', '', 'category/2015/07/55af606cb8382.png', '1', '0', '0,49', '1', '18,19', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('50', '运动鞋包', '', '49', 'category/2015/04/552747be89089.jpg', '', '1', '0', '0,49,50', '2', '18,19', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('51', '运动服饰', '', '49', 'category/2015/04/552747d81ea2b.jpg', '', '1', '0', '0,49,51', '2', '18,19', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('52', '户外鞋服', '', '49', 'category/2015/04/552747ff766bf.jpg', '', '1', '0', '0,49,52', '2', '18,19', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('53', '户外装备', '', '49', 'category/2015/04/552748237f5d5.jpg', '', '1', '0', '0,49,53', '2', '18,19', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('54', '垂钓游泳', '', '49', 'category/2015/04/55274847891a5.jpg', '', '1', '0', '0,49,54', '2', '18,19', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('55', '体育健身', '', '49', 'category/2015/04/5527486189e62.jpg', '', '1', '0', '0,49,55', '2', '18,19', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('56', '骑行运动', '', '49', 'category/2015/04/5527487e2dac0.jpg', '', '1', '0', '0,49,56', '2', '18,19', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('57', '酒水', '', '11', 'category/2015/04/55274936e745d.png', '', '1', '0', '0,11,57', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('58', '水果', '', '11', 'category/2015/04/5527495bde3c1.png', '', '1', '0', '0,11,58', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('59', '生鲜', '', '11', 'category/2015/04/5527497f0b9d4.jpg', '', '1', '0', '0,11,59', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('60', '粮油', '', '11', 'category/2015/04/552749acec1d1.jpg', '', '1', '0', '0,11,60', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('61', '干货', '', '11', 'category/2015/04/552749dd835c5.jpg', '', '1', '0', '0,11,61', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('62', '饮料', '', '11', 'category/2015/04/55274a1d54e02.jpg', '', '1', '0', '0,11,62', '2', '7', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('63', '计生', '', '39', 'category/2015/04/55274b2f541fd.jpg', '', '1', '0', '0,39,63', '2', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('64', '电脑数码', '', '0', '', 'category/2015/07/55af5f1b08177.png', '1', '0', '0,64', '1', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('65', '手机', '', '64', 'category/2015/04/55275d3ebc545.jpg', '', '1', '0', '0,64,65', '2', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('66', '手机配件', '', '64', 'category/2015/04/55275d663f0ae.png', '', '1', '0', '0,64,66', '2', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('67', '电脑', '', '64', 'category/2015/04/55275da6169b7.jpg', '', '1', '0', '0,64,67', '2', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('68', '平板', '', '64', 'category/2015/04/55275dbc4824a.jpg', '', '1', '0', '0,64,68', '2', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('69', '电脑配件', '', '64', 'category/2015/04/55275df02c582.jpg', '', '1', '0', '0,64,69', '2', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('70', '摄影', '', '64', 'category/2015/04/55275e0fbba9f.jpg', '', '1', '0', '0,64,70', '2', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('71', '影音', '', '64', 'category/2015/04/55275e2f89c97.jpg', '', '1', '0', '0,64,71', '2', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('72', '网络', '', '64', 'category/2015/04/55275e4eedc8a.jpg', '', '1', '0', '0,64,72', '2', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('73', '办公', '', '64', 'category/2015/04/55275ea744bfc.jpg', '', '1', '0', '0,64,73', '2', '7,5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('74', '电器', '', '39', 'category/2015/04/55275eed3b47c.png', '', '1', '0', '0,39,74', '2', '10', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('75', '手表饰品', '', '0', '', 'category/2015/07/55af5f281a3da.png', '1', '0', '0,75', '1', '4', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('76', '钟表', '', '75', 'category/2015/04/55275f39eb17e.jpg', '', '1', '0', '0,75,76', '2', '4', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('77', '饰品', '', '75', 'category/2015/04/55275f618cdd6.jpg', '', '1', '0', '0,75,77', '2', '4', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('78', '天然珠宝', '', '75', 'category/2015/04/55275fa4e1713.jpg', '', '1', '0', '0,75,78', '2', '4', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('79', '汽车用品', '', '0', '', 'category/2015/07/55af61c1404fa.png', '1', '0', '0,79', '1', '17', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('80', '汽车装饰', '', '79', 'category/2015/04/552760961dfa9.jpg', '', '1', '0', '0,79,80', '2', '12', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('81', '车载电器', '', '79', 'category/2015/04/552760140ba45.png', '', '1', '0', '0,79,81', '2', '12', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('82', '美容清洗', '', '79', 'category/2015/04/5527603514e1b.jpg', '', '1', '0', '0,79,82', '2', '12', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('83', '维修保养', '', '79', 'category/2015/04/55276054c30f7.png', '', '1', '0', '0,79,83', '2', '12', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('84', '安全自驾', '', '79', 'category/2015/04/5527607a693b0.png', '', '1', '0', '0,79,84', '2', '12', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('87', '音响配件', '', '0', 'category/2015/07/55af63c14b7dc.png', 'category/2015/07/55af63c14b9bb.png', '1', '0', '0,87', '1', '5', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('88', '金融理财', '', '0', 'category/2015/07/55af641bb8118.png', 'category/2015/07/55af641bb82fd.png', '1', '0', '0,88', '1', '11', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('89', '旅行票务', '', '0', 'category/2015/07/55af64674c46f.png', 'category/2015/07/55af64674c663.png', '1', '0', '0,89', '1', '8', '3', '1');
INSERT INTO `pigcms_product_category` VALUES ('90', '图书音像', '', '0', 'category/2015/07/55af64bb5b325.png', 'category/2015/07/55af64bb5b4b9.png', '1', '0', '0,90', '1', '12', '1,2,3,4', '1');
INSERT INTO `pigcms_product_category` VALUES ('91', '厨卫用具', '', '0', 'category/2015/07/55af65105a76a.png', 'category/2015/07/55af65105a92c.png', '1', '0', '0,91', '1', '13', '1,2,3,4', '1');

-- ----------------------------
-- Table structure for `pigcms_product_custom_field`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_custom_field`;
CREATE TABLE `pigcms_product_custom_field` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品id',
  `field_name` varchar(30) NOT NULL DEFAULT '' COMMENT '自定义字段名称',
  `field_type` varchar(30) NOT NULL DEFAULT '' COMMENT '自定义字段类型',
  `multi_rows` tinyint(1) NOT NULL DEFAULT '0' COMMENT '多行 0 否 1 是',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '必填 0 否 1是',
  PRIMARY KEY (`pigcms_id`),
  KEY `product_id` (`product_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品自定义字段';

-- ----------------------------
-- Records of pigcms_product_custom_field
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_group`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_group`;
CREATE TABLE `pigcms_product_group` (
  `group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '商品分组id',
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `group_name` varchar(50) NOT NULL COMMENT '分组名称',
  `is_show_name` char(1) NOT NULL DEFAULT '0' COMMENT '显示商品分组名称',
  `first_sort` varchar(30) NOT NULL DEFAULT '' COMMENT '商品排序',
  `second_sort` varchar(30) NOT NULL DEFAULT '' COMMENT '商品排序',
  `list_style_size` char(1) NOT NULL DEFAULT '0' COMMENT '列表大小 0大图 1小图 2一大两小 3详细列表',
  `list_style_type` char(1) NOT NULL DEFAULT '0' COMMENT '列表样式 0卡片样式 1瀑布流 2极简样式',
  `is_show_price` char(1) NOT NULL DEFAULT '1' COMMENT '显示价格',
  `is_show_product_name` char(1) NOT NULL DEFAULT '0' COMMENT '显示商品名 0不显示 1显示',
  `is_show_buy_button` char(1) NOT NULL DEFAULT '1' COMMENT '显示购买按钮',
  `buy_button_style` char(1) NOT NULL DEFAULT '1' COMMENT '购买按钮样式 1样式1 2样式2 3样式3 4 样式4',
  `group_label` varchar(300) NOT NULL DEFAULT '' COMMENT '商品标签简介',
  `product_count` int(10) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `has_custom` tinyint(1) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品分组';

-- ----------------------------
-- Records of pigcms_product_group
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_image`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_image`;
CREATE TABLE `pigcms_product_image` (
  `product_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品id',
  `image` varchar(200) NOT NULL DEFAULT '' COMMENT '商品图片',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  KEY `product_id` (`product_id`) USING BTREE,
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品图片';

-- ----------------------------
-- Records of pigcms_product_image
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_property`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_property`;
CREATE TABLE `pigcms_product_property` (
  `pid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '属性名',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品属性名';

-- ----------------------------
-- Records of pigcms_product_property
-- ----------------------------
INSERT INTO `pigcms_product_property` VALUES ('1', '尺寸');
INSERT INTO `pigcms_product_property` VALUES ('2', '净重');
INSERT INTO `pigcms_product_property` VALUES ('3', '机芯');
INSERT INTO `pigcms_product_property` VALUES ('4', '适用');
INSERT INTO `pigcms_product_property` VALUES ('5', '净含量');
INSERT INTO `pigcms_product_property` VALUES ('6', '包装');
INSERT INTO `pigcms_product_property` VALUES ('7', '款式');
INSERT INTO `pigcms_product_property` VALUES ('8', '口味');
INSERT INTO `pigcms_product_property` VALUES ('9', '产地');
INSERT INTO `pigcms_product_property` VALUES ('10', '种类');
INSERT INTO `pigcms_product_property` VALUES ('11', '内存');
INSERT INTO `pigcms_product_property` VALUES ('12', '套餐');
INSERT INTO `pigcms_product_property` VALUES ('13', '出行日期');
INSERT INTO `pigcms_product_property` VALUES ('14', '出行人群');
INSERT INTO `pigcms_product_property` VALUES ('15', '入住时段');
INSERT INTO `pigcms_product_property` VALUES ('16', '房型');
INSERT INTO `pigcms_product_property` VALUES ('17', '介质');
INSERT INTO `pigcms_product_property` VALUES ('18', '开本');
INSERT INTO `pigcms_product_property` VALUES ('19', '版本');
INSERT INTO `pigcms_product_property` VALUES ('20', '类型（例如实体票,电子票）');
INSERT INTO `pigcms_product_property` VALUES ('22', '有效期');
INSERT INTO `pigcms_product_property` VALUES ('23', '乘客类型');
INSERT INTO `pigcms_product_property` VALUES ('24', '伞面尺寸');
INSERT INTO `pigcms_product_property` VALUES ('25', '儿童/青少年床尺寸');
INSERT INTO `pigcms_product_property` VALUES ('26', '内裤尺码');
INSERT INTO `pigcms_product_property` VALUES ('27', '出发日期');
INSERT INTO `pigcms_product_property` VALUES ('28', '剩余保质期');
INSERT INTO `pigcms_product_property` VALUES ('29', '佛珠尺寸');
INSERT INTO `pigcms_product_property` VALUES ('30', '克重');
INSERT INTO `pigcms_product_property` VALUES ('31', '型号');
INSERT INTO `pigcms_product_property` VALUES ('32', '大小');
INSERT INTO `pigcms_product_property` VALUES ('33', '大小描述');
INSERT INTO `pigcms_product_property` VALUES ('34', '功率');
INSERT INTO `pigcms_product_property` VALUES ('35', '吉祥图案');
INSERT INTO `pigcms_product_property` VALUES ('36', '圆床尺寸');
INSERT INTO `pigcms_product_property` VALUES ('37', '奶嘴规格');
INSERT INTO `pigcms_product_property` VALUES ('38', '娃娃尺寸');
INSERT INTO `pigcms_product_property` VALUES ('39', '安全套规格');
INSERT INTO `pigcms_product_property` VALUES ('40', '宠物适用尺码');
INSERT INTO `pigcms_product_property` VALUES ('41', '布尿裤尺码');
INSERT INTO `pigcms_product_property` VALUES ('42', '帽围');
INSERT INTO `pigcms_product_property` VALUES ('43', '床品尺寸');
INSERT INTO `pigcms_product_property` VALUES ('44', '戒圈');
INSERT INTO `pigcms_product_property` VALUES ('45', '户外帽尺码');
INSERT INTO `pigcms_product_property` VALUES ('46', '户外手套尺码');
INSERT INTO `pigcms_product_property` VALUES ('47', '手镯内径');
INSERT INTO `pigcms_product_property` VALUES ('48', '方形地毯规格');
INSERT INTO `pigcms_product_property` VALUES ('49', '毛色');
INSERT INTO `pigcms_product_property` VALUES ('50', '洗车机容量');
INSERT INTO `pigcms_product_property` VALUES ('51', '珍珠直径');
INSERT INTO `pigcms_product_property` VALUES ('52', '珍珠颜色');
INSERT INTO `pigcms_product_property` VALUES ('53', '瓷砖尺寸（平方毫米）');
INSERT INTO `pigcms_product_property` VALUES ('54', '线号');
INSERT INTO `pigcms_product_property` VALUES ('55', '床垫厚度');
INSERT INTO `pigcms_product_property` VALUES ('56', '床垫规格');
INSERT INTO `pigcms_product_property` VALUES ('57', '床尺寸');
INSERT INTO `pigcms_product_property` VALUES ('58', '座垫套件数量');
INSERT INTO `pigcms_product_property` VALUES ('59', '建议身高（尺码）');
INSERT INTO `pigcms_product_property` VALUES ('60', '画布尺寸');
INSERT INTO `pigcms_product_property` VALUES ('61', '画框尺寸');
INSERT INTO `pigcms_product_property` VALUES ('62', '皮带长度');
INSERT INTO `pigcms_product_property` VALUES ('63', '窗帘尺寸（宽X高)');
INSERT INTO `pigcms_product_property` VALUES ('64', '笔芯颜色');
INSERT INTO `pigcms_product_property` VALUES ('65', '粉粉份量');
INSERT INTO `pigcms_product_property` VALUES ('66', '纸张规格');
INSERT INTO `pigcms_product_property` VALUES ('67', '线材长度');
INSERT INTO `pigcms_product_property` VALUES ('68', '线长');
INSERT INTO `pigcms_product_property` VALUES ('69', '组合');
INSERT INTO `pigcms_product_property` VALUES ('70', '绣布CT数');
INSERT INTO `pigcms_product_property` VALUES ('71', '胸围尺码');
INSERT INTO `pigcms_product_property` VALUES ('72', '胸垫尺码');
INSERT INTO `pigcms_product_property` VALUES ('73', '自定义项');
INSERT INTO `pigcms_product_property` VALUES ('74', '色温');
INSERT INTO `pigcms_product_property` VALUES ('75', '花束直径');
INSERT INTO `pigcms_product_property` VALUES ('76', '花盆规格');
INSERT INTO `pigcms_product_property` VALUES ('77', '蛋糕尺寸');
INSERT INTO `pigcms_product_property` VALUES ('78', '袜子尺码');
INSERT INTO `pigcms_product_property` VALUES ('79', '规格尺寸');
INSERT INTO `pigcms_product_property` VALUES ('80', '规格（粒/袋/ml/g）');
INSERT INTO `pigcms_product_property` VALUES ('81', '贵金属成色');
INSERT INTO `pigcms_product_property` VALUES ('82', '车用香水香味');
INSERT INTO `pigcms_product_property` VALUES ('83', '适用年龄');
INSERT INTO `pigcms_product_property` VALUES ('84', '适用床尺寸');
INSERT INTO `pigcms_product_property` VALUES ('85', '适用户外项目');
INSERT INTO `pigcms_product_property` VALUES ('86', '适用范围');
INSERT INTO `pigcms_product_property` VALUES ('87', '适用规格');
INSERT INTO `pigcms_product_property` VALUES ('88', '遮阳挡件数');
INSERT INTO `pigcms_product_property` VALUES ('89', '邮轮房型');
INSERT INTO `pigcms_product_property` VALUES ('90', '钓钩尺寸');
INSERT INTO `pigcms_product_property` VALUES ('91', '钻石净度');
INSERT INTO `pigcms_product_property` VALUES ('92', '钻石重量');
INSERT INTO `pigcms_product_property` VALUES ('93', '钻石颜色');
INSERT INTO `pigcms_product_property` VALUES ('94', '链子长度');
INSERT INTO `pigcms_product_property` VALUES ('95', '锅具尺寸');
INSERT INTO `pigcms_product_property` VALUES ('96', '锅身直径尺寸');
INSERT INTO `pigcms_product_property` VALUES ('97', '镜子尺寸');
INSERT INTO `pigcms_product_property` VALUES ('98', '镜片适合度数');
INSERT INTO `pigcms_product_property` VALUES ('99', '镶嵌材质');
INSERT INTO `pigcms_product_property` VALUES ('100', '长度');
INSERT INTO `pigcms_product_property` VALUES ('101', '防潮垫大小');
INSERT INTO `pigcms_product_property` VALUES ('102', '雨刷尺寸');
INSERT INTO `pigcms_product_property` VALUES ('103', '鞋码');
INSERT INTO `pigcms_product_property` VALUES ('104', '鞋码（内长）');
INSERT INTO `pigcms_product_property` VALUES ('105', '香味');
INSERT INTO `pigcms_product_property` VALUES ('106', '颜色');
INSERT INTO `pigcms_product_property` VALUES ('107', '尺码');
INSERT INTO `pigcms_product_property` VALUES ('108', '上市时间');
INSERT INTO `pigcms_product_property` VALUES ('109', '容量');
INSERT INTO `pigcms_product_property` VALUES ('110', '系列');
INSERT INTO `pigcms_product_property` VALUES ('111', '规格');

-- ----------------------------
-- Table structure for `pigcms_product_property_value`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_property_value`;
CREATE TABLE `pigcms_product_property_value` (
  `vid` int(10) NOT NULL AUTO_INCREMENT COMMENT '商品属性值id',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '商品属性名id',
  `value` varchar(50) NOT NULL DEFAULT '' COMMENT '商品属性值',
  `image` varchar(255) NOT NULL COMMENT '属性对应图片',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0：不显示，1：显示',
  PRIMARY KEY (`vid`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `pid_2` (`pid`,`value`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品属性值';

-- ----------------------------
-- Records of pigcms_product_property_value
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_qrcode_activity`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_qrcode_activity`;
CREATE TABLE `pigcms_product_qrcode_activity` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `buy_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '购买方式 0扫码直接购买',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠方式 0扫码折扣 1扫码可减优惠',
  `discount` float NOT NULL DEFAULT '0' COMMENT '折扣',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品扫码活动';

-- ----------------------------
-- Records of pigcms_product_qrcode_activity
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_sku`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_sku`;
CREATE TABLE `pigcms_product_sku` (
  `sku_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '库存id',
  `product_id` int(10) NOT NULL,
  `properties` varchar(500) NOT NULL DEFAULT '' COMMENT '商品属性组合 pid1:vid1;pid2:vid2;pid3:vid3',
  `quantity` int(10) NOT NULL DEFAULT '0' COMMENT '库存',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT '相应规格的重量',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '库存编码',
  `sales` int(10) NOT NULL DEFAULT '0' COMMENT '销量',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本价格',
  `min_fx_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分销最低价格',
  `max_fx_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '分销最高价格',
  `drp_level_1_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '一级分销商商品价格',
  `drp_level_2_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级分销商商品价格',
  `drp_level_3_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '三级分销商商品价格',
  `drp_level_1_cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT ' 一级分销商商品成本价格',
  `drp_level_2_cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级分销商商品成本价格',
  `drp_level_3_cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '三级分销商商品成本价格',
  `wholesale_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '批发价格',
  `sale_min_price` decimal(10,2) NOT NULL,
  `sale_max_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '建议最高售价',
  PRIMARY KEY (`sku_id`),
  KEY `code` (`code`) USING BTREE,
  KEY `product_id` (`product_id`,`quantity`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品库存';

-- ----------------------------
-- Records of pigcms_product_sku
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_to_group`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_to_group`;
CREATE TABLE `pigcms_product_to_group` (
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '分组id',
  KEY `product_id` (`product_id`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品分组关联';

-- ----------------------------
-- Records of pigcms_product_to_group
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_to_property`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_to_property`;
CREATE TABLE `pigcms_product_to_property` (
  `pigcms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `product_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品id',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '商品属性id',
  `order_by` int(5) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`pigcms_id`),
  KEY `product_id` (`product_id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品关联属性id';

-- ----------------------------
-- Records of pigcms_product_to_property
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_to_property_value`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_to_property_value`;
CREATE TABLE `pigcms_product_to_property_value` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `product_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品id',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '商品属性id',
  `vid` int(10) NOT NULL DEFAULT '0' COMMENT '商品属性值id',
  `order_by` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`pigcms_id`),
  KEY `product_id` (`product_id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `vid` (`vid`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品关联属性值';

-- ----------------------------
-- Records of pigcms_product_to_property_value
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_product_whitelist`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_product_whitelist`;
CREATE TABLE `pigcms_product_whitelist` (
  `pigcms_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `supplier_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '供货商id',
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '经销商id',
  `sales` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '经销商销量',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '加入白名单时间',
  PRIMARY KEY (`pigcms_id`),
  KEY `product_id` (`product_id`,`supplier_id`) USING BTREE,
  KEY `seller_id` (`seller_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of pigcms_product_whitelist
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_rbac_action`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_rbac_action`;
CREATE TABLE `pigcms_rbac_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `controller_id` varchar(25) DEFAULT NULL COMMENT '控制器ID',
  `action_id` varchar(25) DEFAULT NULL COMMENT '动作ID',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_rbac_action
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_recognition`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_recognition`;
CREATE TABLE `pigcms_recognition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `third_type` varchar(30) NOT NULL,
  `third_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ticket` varchar(200) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_recognition
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_reply_relation`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_reply_relation`;
CREATE TABLE `pigcms_reply_relation` (
  `pigcms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL COMMENT '[0:文本，1：图文，2：音乐，3：商品，4：商品分类，5：微页面，6：微页面分类，7：店铺主页，8：会员主页]',
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_reply_relation
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_reply_tail`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_reply_tail`;
CREATE TABLE `pigcms_reply_tail` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `content` varchar(200) NOT NULL,
  `is_open` tinyint(1) NOT NULL COMMENT '是否开启（0：关，1：开）',
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_reply_tail
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_return`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_return`;
CREATE TABLE `pigcms_return` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '退货申请时间',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID号',
  `order_no` varchar(50) NOT NULL COMMENT '订单号',
  `type` tinyint(1) NOT NULL DEFAULT '3' COMMENT '退货类型，1、买/卖双方协商一致，2、买错/多买/不想要，3、商品质量问题，4、未到货品，5、其它',
  `phone` varchar(20) DEFAULT NULL COMMENT '退货人的联系方式',
  `content` varchar(1024) NOT NULL COMMENT '退货说明',
  `images` varchar(1024) DEFAULT NULL COMMENT '图片列表，图片数组序列化',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '申请状态，1：申请中，2：商家审核不通过，3：商家审核通过，4：退货物流，5：退货完成，6：退货取消',
  `cancel_dateline` int(11) NOT NULL DEFAULT '0' COMMENT '商家不同意退款时间',
  `user_cancel_dateline` int(11) NOT NULL DEFAULT '0' COMMENT '取消退货时间',
  `store_content` varchar(1024) DEFAULT NULL COMMENT '商家不同意退款说明',
  `shipping_method` varchar(50) DEFAULT NULL COMMENT '物流方式 express快递发货 selffetch亲自送货',
  `express_code` varchar(50) DEFAULT NULL COMMENT '快递公司代码',
  `express_company` varchar(50) DEFAULT NULL COMMENT '快递公司',
  `express_no` varchar(50) DEFAULT NULL COMMENT '快递单号',
  `address` text COMMENT '收货详细地址',
  `address_user` varchar(20) DEFAULT NULL COMMENT '收货人',
  `address_tel` varchar(20) DEFAULT NULL COMMENT '收货人电话',
  `product_money` float(8,2) DEFAULT '0.00' COMMENT '产品退货的费用',
  `postage_money` float(8,2) DEFAULT '0.00' COMMENT '产品退货的物流费用',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户UID',
  `is_fx` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含分销商品 0 否 1是',
  `user_return_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户申请退货单ID，',
  UNIQUE KEY `id` (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_return_id` (`user_return_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_return
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_return_product`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_return_product`;
CREATE TABLE `pigcms_return_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `return_id` int(11) NOT NULL DEFAULT '0' COMMENT '退货单ID',
  `order_product_id` int(11) NOT NULL DEFAULT '0' COMMENT 'order_product表的pigcms_id值',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品ID',
  `sku_id` int(11) NOT NULL DEFAULT '0' COMMENT '库存ID',
  `sku_data` text COMMENT '库存信息',
  `pro_num` int(11) NOT NULL DEFAULT '1' COMMENT '数量',
  `pro_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买时的价格',
  `is_packaged` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已打包，0：未打包，1：已打包',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供货商ID',
  `original_product_id` int(11) NOT NULL DEFAULT '0' COMMENT '源商品ID',
  `discount` double(3,1) NOT NULL DEFAULT '10.0' COMMENT '折扣',
  `user_return_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户退货单ID',
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `order_id` (`order_id`),
  KEY `return_id` (`return_id`),
  KEY `order_product_id` (`order_product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_return_product
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_reward`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_reward`;
CREATE TABLE `pigcms_reward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) NOT NULL COMMENT '添加时间',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `name` varchar(255) NOT NULL COMMENT '活动名称',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '优惠方式，1：普通优惠，2：多级优惠，每级优惠不累积',
  `is_all` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否所有商品都参与活动，1：全部商品，2：部分商品',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效，1：有效，0：无效，',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`store_id`,`start_time`,`end_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='满减/送表';

-- ----------------------------
-- Records of pigcms_reward
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_reward_condition`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_reward_condition`;
CREATE TABLE `pigcms_reward_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL COMMENT '满减/送表ID',
  `money` int(11) NOT NULL COMMENT '钱数限制',
  `cash` int(11) NOT NULL DEFAULT '0' COMMENT '减现金，0：表示没有此选项',
  `postage` int(11) NOT NULL DEFAULT '0' COMMENT '免邮费，0：表示没有此选项',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '送积分，0：表示没有此选项',
  `coupon` int(11) NOT NULL DEFAULT '0' COMMENT '送优惠，0：表示没有此选项',
  `present` int(11) NOT NULL DEFAULT '0' COMMENT '送赠品，0：表示没有此选项',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠条件表';

-- ----------------------------
-- Records of pigcms_reward_condition
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_reward_product`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_reward_product`;
CREATE TABLE `pigcms_reward_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL COMMENT '满减/送表ID',
  `product_id` int(11) NOT NULL COMMENT '产品表ID',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='满减/送产品列表';

-- ----------------------------
-- Records of pigcms_reward_product
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_rights`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_rights`;
CREATE TABLE `pigcms_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '维权申请时间',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID号',
  `order_no` varchar(50) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '退货类型，1、商品质量问题，2、未到货品，3、其它',
  `phone` varchar(20) DEFAULT NULL COMMENT '维权人的联系方式',
  `content` text COMMENT '维权说明',
  `images` varchar(1024) DEFAULT NULL COMMENT '图片列表，图片数组序列化',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '申请状态，1：申请中，2：维权中，3：维权完成，10：维权取消',
  `user_cancel_dateline` int(11) NOT NULL DEFAULT '0' COMMENT '取消维权时间',
  `complete_dateline` int(11) NOT NULL DEFAULT '0' COMMENT '维权完成时间',
  `platform_content` text COMMENT '维权结果',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户UID',
  `is_fx` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包含分销商品 0 否 1是',
  `user_rights_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户申请维权单ID',
  UNIQUE KEY `id` (`id`),
  KEY `order_id` (`order_id`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='维权申请表';

-- ----------------------------
-- Records of pigcms_rights
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_rights_product`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_rights_product`;
CREATE TABLE `pigcms_rights_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `order_no` varchar(50) NOT NULL COMMENT '订单号',
  `rights_id` int(11) NOT NULL DEFAULT '0' COMMENT '维权单ID',
  `order_product_id` int(11) NOT NULL DEFAULT '0' COMMENT 'order_product表的pigcms_id值',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品ID',
  `sku_id` int(11) NOT NULL DEFAULT '0' COMMENT '库存ID',
  `sku_data` text COMMENT '库存信息',
  `pro_num` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `pro_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买时的价格',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供货商ID',
  `original_product_id` int(11) NOT NULL DEFAULT '0' COMMENT '源商品ID',
  `user_rights_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户退货单ID',
  UNIQUE KEY `id` (`id`),
  KEY `order_id` (`order_id`),
  KEY `rights_id` (`rights_id`),
  KEY `order_product_id` (`order_product_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='维权商品表';

-- ----------------------------
-- Records of pigcms_rights_product
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_rule`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_rule`;
CREATE TABLE `pigcms_rule` (
  `pigcms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL COMMENT '规则类型（0：手动添加的，1：系统默认的）',
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='规则表';

-- ----------------------------
-- Records of pigcms_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_sale_category`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_sale_category`;
CREATE TABLE `pigcms_sale_category` (
  `cat_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '类目id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '类目名称',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '描述',
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '父类id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `order_by` int(10) NOT NULL DEFAULT '0' COMMENT '排序，值小优越',
  `path` varchar(1000) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '级别',
  `parent_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '父类状态',
  `stores` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '店铺数量',
  `cat_pic` varchar(120) NOT NULL COMMENT '图片',
  PRIMARY KEY (`cat_id`),
  KEY `parent_category_id` (`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='店铺主营类目';

-- ----------------------------
-- Records of pigcms_sale_category
-- ----------------------------
INSERT INTO `pigcms_sale_category` VALUES ('1', '女装', '精美女装 新款上市', '0', '1', '0', '0,01', '1', '1', '912', '');
INSERT INTO `pigcms_sale_category` VALUES ('2', '男装', '帅气男装 新款上市', '0', '1', '0', '0,02', '1', '1', '218', '');
INSERT INTO `pigcms_sale_category` VALUES ('3', '食品酒水', '绿水食品 放心酒水', '0', '1', '0', '0,03', '1', '1', '874', '');
INSERT INTO `pigcms_sale_category` VALUES ('4', '个护美妆', '美丽从现在开始', '0', '1', '0', '0,04', '1', '1', '199', '');
INSERT INTO `pigcms_sale_category` VALUES ('5', '母婴玩具', '安全母婴 健康成长', '0', '1', '0', '0,05', '1', '1', '125', '');
INSERT INTO `pigcms_sale_category` VALUES ('6', '家居百货', '家居超市 齐全百货', '0', '1', '0', '0,06', '1', '1', '251', '');
INSERT INTO `pigcms_sale_category` VALUES ('7', '运动户外', '运动锻炼 户外旅行', '0', '1', '0', '0,07', '1', '1', '102', '');
INSERT INTO `pigcms_sale_category` VALUES ('8', '电脑数码', '全类3C 保证正品', '0', '1', '0', '0,08', '1', '1', '375', '');
INSERT INTO `pigcms_sale_category` VALUES ('9', '手表饰品', '打扮不一样的自己', '0', '1', '0', '0,09', '1', '1', '73', '');
INSERT INTO `pigcms_sale_category` VALUES ('10', '汽车用品', '汽车用品超市', '0', '1', '0', '0,10', '1', '1', '105', '');
INSERT INTO `pigcms_sale_category` VALUES ('13', '汽车装饰', '', '10', '1', '0', '0,10,13', '2', '1', '59', '');
INSERT INTO `pigcms_sale_category` VALUES ('14', '车载电器', '', '10', '0', '0', '0,10,14', '2', '1', '6', '');
INSERT INTO `pigcms_sale_category` VALUES ('15', '美容清洗', '', '10', '1', '0', '0,10,15', '2', '1', '3', '');
INSERT INTO `pigcms_sale_category` VALUES ('16', '维修保养', '', '10', '1', '0', '0,10,16', '2', '1', '8', '');
INSERT INTO `pigcms_sale_category` VALUES ('17', '安全自驾', '', '10', '1', '0', '0,10,17', '2', '1', '3', '');
INSERT INTO `pigcms_sale_category` VALUES ('18', '全品类', '', '10', '1', '0', '0,10,18', '2', '1', '26', '');
INSERT INTO `pigcms_sale_category` VALUES ('19', '其他', '其他分类', '0', '1', '0', '0,19', '1', '1', '456', '');
INSERT INTO `pigcms_sale_category` VALUES ('20', '虚拟卡券', '', '19', '1', '0', '0,19,20', '2', '1', '456', '');

-- ----------------------------
-- Table structure for `pigcms_search_hot`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_search_hot`;
CREATE TABLE `pigcms_search_hot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `url` varchar(500) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='热门搜索';

-- ----------------------------
-- Records of pigcms_search_hot
-- ----------------------------
INSERT INTO `pigcms_search_hot` VALUES ('1', '休闲零食', 'http://d.xins.cc/category/35', '1', '0', '1432971333');
INSERT INTO `pigcms_search_hot` VALUES ('2', '婚庆摄影', 'http://d.xins.cc/category/14', '1', '0', '1432971431');
INSERT INTO `pigcms_search_hot` VALUES ('3', '茶饮冲调', 'http://d.xins.cc/category/36', '0', '0', '1432971589');
INSERT INTO `pigcms_search_hot` VALUES ('4', '数码家电', 'http://d.xins.cc/category/7', '1', '0', '1432975713');
INSERT INTO `pigcms_search_hot` VALUES ('5', '美妆', 'http://d.xins.cc/category/4', '0', '0', '1432971701');
INSERT INTO `pigcms_search_hot` VALUES ('6', '男装', 'http://d.xins.cc/category/37', '0', '0', '1432971775');
INSERT INTO `pigcms_search_hot` VALUES ('7', '男鞋', 'http://d.xins.cc/category/33', '0', '0', '1432971892');

-- ----------------------------
-- Table structure for `pigcms_search_tmp`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_search_tmp`;
CREATE TABLE `pigcms_search_tmp` (
  `md5` varchar(32) NOT NULL COMMENT 'md5系统分类表id字条串，例md5(''1,2,3'')',
  `product_id_str` text COMMENT '满足条件的产品id字符串，每个产品id以逗号分割',
  `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
  UNIQUE KEY `md5` (`md5`) USING BTREE,
  KEY `expire_time` (`expire_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统分类筛选产品临时表';

-- ----------------------------
-- Records of pigcms_search_tmp
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_seller_fx_product`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_seller_fx_product`;
CREATE TABLE `pigcms_seller_fx_product` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供货商店铺id',
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '分销商店铺id',
  `source_product_id` int(11) NOT NULL DEFAULT '0' COMMENT '源商品id',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  PRIMARY KEY (`pigcms_id`),
  KEY `supplier_id` (`supplier_id`) USING BTREE,
  KEY `seller_id` (`seller_id`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='分销商分销商品';

-- ----------------------------
-- Records of pigcms_seller_fx_product
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_service`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_service`;
CREATE TABLE `pigcms_service` (
  `service_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `nickname` char(50) NOT NULL COMMENT '客服昵称',
  `truename` char(50) NOT NULL COMMENT '真实姓名',
  `avatar` char(150) NOT NULL COMMENT '客服头像',
  `intro` text NOT NULL COMMENT '客服简介',
  `tel` char(20) NOT NULL COMMENT '电话',
  `qq` char(11) NOT NULL COMMENT 'qq',
  `email` char(45) NOT NULL COMMENT '联系邮箱',
  `openid` char(60) NOT NULL COMMENT '绑定openid',
  `add_time` char(15) NOT NULL COMMENT '添加时间',
  `status` tinyint(4) NOT NULL COMMENT '客服状态',
  `store_id` int(11) NOT NULL COMMENT '所属店铺',
  PRIMARY KEY (`service_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='店铺客服列表';

-- ----------------------------
-- Records of pigcms_service
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_slider`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_slider`;
CREATE TABLE `pigcms_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `url` varchar(200) NOT NULL,
  `pic` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='导航表';

-- ----------------------------
-- Records of pigcms_slider
-- ----------------------------
INSERT INTO `pigcms_slider` VALUES ('1', '2', '餐具', 'http://d.xins.cc/wap/category.php?keyword=%E9%9B%B6%E9%A3%9F&id=14', 'slider/2015/06/5570f82e5d4ea.png', '0', '1433727140', '1');
INSERT INTO `pigcms_slider` VALUES ('2', '2', '居家', 'http://d.xins.cc/wap/category.php?keyword=%E5%AE%B6%E7%BA%BA&id=40', 'slider/2015/06/5570f85fc0844.png', '0', '1433727124', '1');
INSERT INTO `pigcms_slider` VALUES ('3', '2', '娱乐', 'http://d.xins.cc/wap/category.php?keyword=%E9%AA%91%E8%A1%8C%E8%BF%90%E5%8A%A8&id=56', 'slider/2015/06/5570f87bb0b60.png', '0', '1433727106', '1');
INSERT INTO `pigcms_slider` VALUES ('4', '2', '户外', 'http://d.xins.cc/wap/category.php?keyword=%E8%BF%90%E5%8A%A8%E9%9E%8B%E5%8C%85&id=50', 'slider/2015/06/5570f8b047c7f.png', '0', '1433727067', '1');
INSERT INTO `pigcms_slider` VALUES ('5', '1', '运动鞋包', 'http://d.xins.cc/category/50', '', '1', '1433395845', '1');
INSERT INTO `pigcms_slider` VALUES ('6', '1', '食品酒水', 'http://d.xins.cc/category/11', '', '2', '1433572631', '1');
INSERT INTO `pigcms_slider` VALUES ('7', '1', '热卖男鞋', 'http://d.xins.cc/category/6', '', '3', '1437208910', '1');
INSERT INTO `pigcms_slider` VALUES ('8', '1', '宝宝用品', 'http://d.xins.cc/category/34', '', '0', '1433495147', '1');
INSERT INTO `pigcms_slider` VALUES ('9', '1', '沐浴洗护', 'http://d.xins.cc/category/29', '', '0', '1433495177', '1');
INSERT INTO `pigcms_slider` VALUES ('10', '1', '居家百货', 'http://d.xins.cc/category/39', '', '0', '1433495207', '1');
INSERT INTO `pigcms_slider` VALUES ('11', '1', '电脑数码', 'http://d.xins.cc/category/64', '', '0', '1433495234', '1');
INSERT INTO `pigcms_slider` VALUES ('12', '1', '手表饰品', 'http://d.xins.cc/category/75', '', '0', '1437550162', '0');
INSERT INTO `pigcms_slider` VALUES ('13', '1', '汽车用品', 'http://d.xins.cc/category/79', '', '0', '1437550155', '0');
INSERT INTO `pigcms_slider` VALUES ('14', '1', '服装配件', 'http://d.xins.cc/category/17', '', '0', '1437550147', '0');
INSERT INTO `pigcms_slider` VALUES ('15', '2', '小吃', 'http://d.xins.cc/wap/home.php?id=1068', 'slider/2015/07/55b72442a47f3.png', '0', '1438065730', '1');
INSERT INTO `pigcms_slider` VALUES ('16', '2', '汽车', 'http://d.xins.cc/wap/home.php?id=1687', 'slider/2015/07/55b7250a9ac5c.png', '0', '1438065930', '1');
INSERT INTO `pigcms_slider` VALUES ('17', '2', '户外', 'http://d.xins.cc/wap/home.php?id=944', 'slider/2015/07/55b7256302c82.png', '0', '1438066018', '1');
INSERT INTO `pigcms_slider` VALUES ('18', '2', '电器', 'http://d.xins.cc/wap/home.php?id=674', 'slider/2015/07/55b725c472c82.png', '0', '1438066116', '1');

-- ----------------------------
-- Table structure for `pigcms_slider_category`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_slider_category`;
CREATE TABLE `pigcms_slider_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` char(20) NOT NULL,
  `cat_key` char(20) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='导航归类表';

-- ----------------------------
-- Records of pigcms_slider_category
-- ----------------------------
INSERT INTO `pigcms_slider_category` VALUES ('1', 'PC端导航', 'pc_nav');
INSERT INTO `pigcms_slider_category` VALUES ('2', '手机端-首页导航', 'wap_index_nav');

-- ----------------------------
-- Table structure for `pigcms_sms_by_code`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_sms_by_code`;
CREATE TABLE `pigcms_sms_by_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `code` int(11) DEFAULT NULL COMMENT '短信验证码',
  `is_use` tinyint(1) DEFAULT '0' COMMENT '是否使用过（0:未使用；1:已使用）',
  `timestamp` int(11) DEFAULT NULL COMMENT '发送的时间戳',
  `type` varchar(30) DEFAULT NULL COMMENT '取到验证码类型(reg:注册,forget:找回密码)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_sms_by_code
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_sms_record`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_sms_record`;
CREATE TABLE `pigcms_sms_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '发送者的uid',
  `store_id` int(11) DEFAULT NULL COMMENT '操作的店铺id',
  `price` smallint(2) NOT NULL DEFAULT '0' COMMENT '短信单价',
  `mobile` varchar(20) DEFAULT NULL COMMENT '发送到的手机号',
  `text` text NOT NULL COMMENT '短信内容',
  `time` int(11) DEFAULT NULL COMMENT '发送的时间戳',
  `status` varchar(250) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_sms_record
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_source_material`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_source_material`;
CREATE TABLE `pigcms_source_material` (
  `pigcms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `it_ids` varchar(50) NOT NULL COMMENT '图文表id集合',
  `store_id` int(10) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL COMMENT '图文类型（0：单图文，1：多图文）',
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_source_material
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_sql_log`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_sql_log`;
CREATE TABLE `pigcms_sql_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL,
  `hash` char(40) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `code` smallint(6) NOT NULL DEFAULT '0',
  `exception` text NOT NULL,
  `update_time` int(10) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_sql_log
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store`;
CREATE TABLE `pigcms_store` (
  `store_id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '会员id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '店铺名称',
  `edit_name_count` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '店铺名修改次数',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '店铺logo',
  `qcode` varchar(300) NOT NULL COMMENT '店铺二维码',
  `openid` varchar(50) NOT NULL COMMENT '微信唯一标识 (关联绑定公众号)',
  `qcode_starttime` int(11) NOT NULL COMMENT '二维码开始生效时间',
  `sale_category_fid` int(11) NOT NULL,
  `sale_category_id` int(10) NOT NULL DEFAULT '0' COMMENT '主营类目',
  `linkman` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '电话',
  `intro` varchar(1000) NOT NULL DEFAULT '' COMMENT '店铺简介',
  `approve` char(1) NOT NULL DEFAULT '0' COMMENT '认证 0未认证 1已证',
  `status` char(1) NOT NULL DEFAULT '1' COMMENT '状态 0禁用 1启用',
  `public_display` tinyint(1) DEFAULT '1' COMMENT '开启后将会在微信综合商城 和 pc综合商城展示 0：不展示，1：展示',
  `date_added` varchar(20) NOT NULL DEFAULT '' COMMENT '店铺入驻时间',
  `service_tel` varchar(20) NOT NULL DEFAULT '' COMMENT '客服电话',
  `service_qq` varchar(15) NOT NULL DEFAULT '' COMMENT '客服qq',
  `service_weixin` varchar(60) NOT NULL DEFAULT '' COMMENT '客服微信',
  `bind_weixin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '绑定微信 0未绑定 1已绑定',
  `weixin_name` varchar(60) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `weixin_original_id` varchar(20) NOT NULL DEFAULT '' COMMENT '微信原始ID',
  `weixin_id` varchar(20) NOT NULL DEFAULT '' COMMENT '微信ID',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT 'qq',
  `open_weixin` char(1) NOT NULL DEFAULT '0' COMMENT '绑定微信',
  `buyer_selffetch` char(1) NOT NULL DEFAULT '0' COMMENT '买家上门自提',
  `buyer_selffetch_name` varchar(50) NOT NULL COMMENT '“上门自提”自定义名称',
  `pay_agent` char(1) NOT NULL DEFAULT '0' COMMENT '代付',
  `offline_payment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支持货到付款，0：是，1：否',
  `open_logistics` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启物流配送，1：开启，0：关闭',
  `open_friend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启送朋友，1：是，0：否',
  `open_autoassign` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启订单自动分配到门店： 1是 0否',
  `open_local_logistics` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用本地化物流: 0否 1是',
  `warn_sp_quantity` int(8) NOT NULL DEFAULT '0' COMMENT '门店库存报警：0为不报警',
  `open_nav` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启店铺导航',
  `nav_style_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT '店铺导航样式',
  `use_nav_pages` varchar(20) NOT NULL DEFAULT '1' COMMENT '使用导航菜单的页面 1店铺主页 2会员主页 3微页面及分类 4商品分组 5商品搜索',
  `open_ad` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启广告',
  `has_ad` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有店铺广告',
  `ad_position` tinyint(1) NOT NULL DEFAULT '0' COMMENT '广告位置 0页面头部 1页面底部',
  `use_ad_pages` varchar(20) NOT NULL DEFAULT '' COMMENT '使用广告的页面 1微页面 2微页面分类 3商品 4商品分组 5店铺主页 6会员主页',
  `date_edited` varchar(20) NOT NULL DEFAULT '' COMMENT '更新时间',
  `income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '店铺收入',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '未提现余额',
  `unbalance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '不可用余额',
  `withdrawal_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已提现金额',
  `withdrawal_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '提现方式 0对私 1对公',
  `bank_id` int(5) NOT NULL DEFAULT '0' COMMENT '开户银行',
  `bank_card` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `bank_card_user` varchar(30) NOT NULL DEFAULT '' COMMENT '开卡人姓名',
  `opening_bank` varchar(30) NOT NULL DEFAULT '' COMMENT '开户行',
  `last_edit_time` varchar(20) NOT NULL DEFAULT '' COMMENT '最后修改时间',
  `physical_count` smallint(6) NOT NULL,
  `drp_supplier_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销店铺供货商id',
  `drp_level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '分销级别',
  `collect` int(11) unsigned NOT NULL COMMENT '店铺收藏数',
  `wxpay` tinyint(1) NOT NULL DEFAULT '0',
  `open_drp_approve` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启分销商审核',
  `drp_approve` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分销商审核状态',
  `drp_profit` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分销利润',
  `drp_profit_withdrawal` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分销利润提现',
  `open_service` tinyint(1) NOT NULL COMMENT '是否开启客服',
  `attention_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `source_site_url` varchar(200) NOT NULL DEFAULT '' COMMENT '对接来源网站url',
  `payment_url` varchar(200) NOT NULL DEFAULT '' COMMENT '站外支付地址',
  `notify_url` varchar(200) NOT NULL DEFAULT '' COMMENT '通知地址',
  `oauth_url` varchar(200) NOT NULL DEFAULT '' COMMENT '对接网站用户认证地址',
  `token` varchar(100) NOT NULL DEFAULT '' COMMENT '微信token',
  `open_drp_guidance` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '店铺分销引导',
  `open_drp_limit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分销限制',
  `drp_limit_buy` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '消费多少金额可分销',
  `drp_limit_share` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分享多少次可分销',
  `drp_limit_condition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 或（2个条件满足一个即可分销） 1 和（2个条件都满足即可分销）',
  `pigcmsToken` char(100) DEFAULT NULL,
  `template_cat_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺模板ID',
  `template_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模板ID',
  `open_drp_setting_price` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '开启分销价设置',
  `unified_price_setting` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '供货商统一定价',
  `open_drp_diy_store` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启分销商装修店铺配置',
  `drp_diy_store` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否有装修店铺权限',
  `open_drp_subscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分销是否需要先关注公众号',
  `open_drp_subscribe_auto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '关注自动分销',
  `drp_subscribe_tpl` varchar(500) NOT NULL DEFAULT '' COMMENT '关注后发送的消息',
  `reg_drp_subscribe_tpl` varchar(500) NOT NULL DEFAULT '' COMMENT '申请分销商关注后发送的消息',
  `is_show_drp_tel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '供货商设定的分销商店铺是显示分销商电话:0，供货商电话:1',
  `drp_subscribe_img` varchar(500) NOT NULL DEFAULT '' COMMENT '关注后发送的消息封面图片',
  `reg_drp_subscribe_img` varchar(500) NOT NULL DEFAULT '' COMMENT '申请分销商关注后发送的消息封面图片',
  `update_drp_store_info` tinyint(1) DEFAULT '1' COMMENT '是否允许分销商修改店铺名称(默认允许)',
  `is_official_shop` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为官方店铺',
  `setting_fans_forever` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝是否终身制',
  `is_required_to_audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要审核批发商(默认不允许)',
  `is_required_margin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要审核交纳保证金(默认不需要)',
  `bond` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '保证金额度',
  PRIMARY KEY (`store_id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='店铺';

-- ----------------------------
-- Records of pigcms_store
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_analytics`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_analytics`;
CREATE TABLE `pigcms_store_analytics` (
  `pigcms_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `module` varchar(30) NOT NULL DEFAULT '' COMMENT '模块名',
  `title` varchar(300) NOT NULL DEFAULT '' COMMENT '页面标题',
  `page_id` int(10) NOT NULL DEFAULT '0' COMMENT '页面id',
  `visited_time` int(11) NOT NULL DEFAULT '0' COMMENT '访问时间',
  `visited_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '访问ip',
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`,`visited_time`,`visited_ip`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='店铺访问统计';

-- ----------------------------
-- Records of pigcms_store_analytics
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_brand`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_brand`;
CREATE TABLE `pigcms_store_brand` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL COMMENT '商铺品牌名',
  `pic` varchar(200) NOT NULL COMMENT '品牌图片',
  `order_by` int(100) NOT NULL DEFAULT '0' COMMENT '排序，越小越前面',
  `store_id` int(11) NOT NULL COMMENT '商铺id',
  `type_id` int(11) NOT NULL COMMENT '所属品牌类别id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用（1：启用；  0：禁用）',
  PRIMARY KEY (`brand_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商铺品牌表';

-- ----------------------------
-- Records of pigcms_store_brand
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_brand_type`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_brand_type`;
CREATE TABLE `pigcms_store_brand_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(100) NOT NULL COMMENT '商铺品牌类别名',
  `order_by` int(10) NOT NULL COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '品牌状态（1：开启，0：禁用）',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商铺品牌类别表';

-- ----------------------------
-- Records of pigcms_store_brand_type
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_contact`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_contact`;
CREATE TABLE `pigcms_store_contact` (
  `store_id` int(11) NOT NULL,
  `phone1` varchar(6) NOT NULL,
  `phone2` varchar(15) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `county` varchar(30) NOT NULL,
  `address` varchar(200) NOT NULL,
  `long` decimal(10,6) NOT NULL,
  `lat` decimal(10,6) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_store_contact
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_nav`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_nav`;
CREATE TABLE `pigcms_store_nav` (
  `store_nav_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '店铺导航id',
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `style` tinyint(1) NOT NULL DEFAULT '1' COMMENT '导航样式',
  `bgcolor` char(7) NOT NULL DEFAULT '' COMMENT '背景颜色',
  `data` text NOT NULL COMMENT '店铺导航数据',
  `date_added` varchar(20) NOT NULL,
  PRIMARY KEY (`store_nav_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `store_nav_template_id` (`style`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='店铺导航';

-- ----------------------------
-- Records of pigcms_store_nav
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_orderprint_machine`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_orderprint_machine`;
CREATE TABLE `pigcms_store_orderprint_machine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL COMMENT '店铺id',
  `mobile` char(20) DEFAULT NULL COMMENT '绑定手机号',
  `username` varchar(250) DEFAULT NULL COMMENT '绑定帐号',
  `terminal_number` varchar(250) DEFAULT NULL COMMENT '终端号',
  `keys` varchar(250) DEFAULT NULL COMMENT '密钥',
  `counts` int(11) NOT NULL DEFAULT '0' COMMENT '打印份数',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '打印类型:1.只打印付过款的,2:无论是否付款都打印',
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启:0.关闭，1.开启',
  `timestamp` int(11) DEFAULT NULL COMMENT '保存/修改的 时间戳',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商铺订单打印机';

-- ----------------------------
-- Records of pigcms_store_orderprint_machine
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_pay_agent`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_pay_agent`;
CREATE TABLE `pigcms_store_pay_agent` (
  `agent_id` int(10) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `type` char(1) NOT NULL DEFAULT '0' COMMENT '类型 0 发起人 1 代付人',
  `content` varchar(200) NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`agent_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='找人代付';

-- ----------------------------
-- Records of pigcms_store_pay_agent
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_physical`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_physical`;
CREATE TABLE `pigcms_store_physical` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `phone1` varchar(6) NOT NULL,
  `phone2` varchar(15) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `county` varchar(30) NOT NULL,
  `address` varchar(200) NOT NULL,
  `long` decimal(10,6) NOT NULL,
  `lat` decimal(10,6) NOT NULL,
  `last_time` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `images` varchar(500) NOT NULL,
  `business_hours` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_store_physical
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_physical_courier`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_physical_courier`;
CREATE TABLE `pigcms_store_physical_courier` (
  `courier_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` char(50) NOT NULL COMMENT '配送员名称',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别(默认1)：0女 1男',
  `avatar` char(150) NOT NULL COMMENT '客服头像',
  `tel` char(20) NOT NULL COMMENT '手机号',
  `openid` char(60) NOT NULL COMMENT '绑定openid',
  `store_id` int(11) NOT NULL COMMENT '所属店铺',
  `physical_id` int(11) NOT NULL COMMENT '所属门店',
  `status` tinyint(4) NOT NULL COMMENT '配送员状态：0关闭 1启用',
  `long` decimal(10,6) NOT NULL COMMENT '配送员 经度',
  `lat` decimal(10,6) NOT NULL COMMENT '配送员 纬度',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `location_time` int(11) NOT NULL COMMENT '最后上报坐标时间',
  PRIMARY KEY (`courier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='本地化物流-配送员';

-- ----------------------------
-- Records of pigcms_store_physical_courier
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_physical_quantity`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_physical_quantity`;
CREATE TABLE `pigcms_store_physical_quantity` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `physical_id` int(10) NOT NULL DEFAULT '0' COMMENT '仓库/门店id',
  `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `sku_id` int(11) NOT NULL DEFAULT '0' COMMENT 'sku规格id',
  `quantity` int(10) NOT NULL DEFAULT '0' COMMENT '库存',
  PRIMARY KEY (`pigcms_id`),
  KEY `sku_id` (`sku_id`) USING BTREE,
  KEY `product_id` (`product_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='仓库分配库存关系';

-- ----------------------------
-- Records of pigcms_store_physical_quantity
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_printing_order_template`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_printing_order_template`;
CREATE TABLE `pigcms_store_printing_order_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL COMMENT '订单模版所属店铺',
  `text` longtext COMMENT '订单模版内容',
  `typeid` int(11) DEFAULT NULL COMMENT '模版所属类型',
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_store_printing_order_template
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_supplier`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_supplier`;
CREATE TABLE `pigcms_store_supplier` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '供货商店铺id',
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '分销商店铺id',
  `supply_chain` varchar(500) NOT NULL DEFAULT '' COMMENT '供货链',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '级别',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分销类型，0 全网分销 1排他分销',
  PRIMARY KEY (`pigcms_id`),
  KEY `supplier_id` (`supplier_id`) USING BTREE,
  KEY `seller_id` (`seller_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='供货商';

-- ----------------------------
-- Records of pigcms_store_supplier
-- ----------------------------
INSERT INTO `pigcms_store_supplier` VALUES ('1', '4', '5', '', '1', '0');

-- ----------------------------
-- Table structure for `pigcms_store_user_data`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_user_data`;
CREATE TABLE `pigcms_store_user_data` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `point` int(11) unsigned NOT NULL COMMENT '店铺返还积分(有消耗)',
  `point_count` int(11) unsigned NOT NULL COMMENT '店铺返还总积分(无消耗)',
  `order_unpay` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '未付款订单数',
  `order_unsend` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '未发货订单数',
  `order_send` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '已发货订单数',
  `order_complete` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '交易完成订单数',
  `last_time` int(11) NOT NULL,
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '店铺消费总金额',
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`,`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='店铺用户数据表';

-- ----------------------------
-- Records of pigcms_store_user_data
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_store_withdrawal`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_store_withdrawal`;
CREATE TABLE `pigcms_store_withdrawal` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '交易号',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `supplier_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '供货商id',
  `bank_id` int(11) NOT NULL DEFAULT '0' COMMENT '银行id',
  `opening_bank` varchar(30) NOT NULL DEFAULT '' COMMENT '开户行',
  `bank_card` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `bank_card_user` varchar(30) NOT NULL DEFAULT '' COMMENT '开卡人姓名',
  `withdrawal_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '提现方式 0对私 1对公',
  `add_time` varchar(20) NOT NULL DEFAULT '' COMMENT '申请时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1申请中 2银行处理中 3提现成功 4提现失败',
  `amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `complate_time` varchar(20) NOT NULL DEFAULT '' COMMENT '完成时间',
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `bank_id` (`bank_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='提现';

-- ----------------------------
-- Records of pigcms_store_withdrawal
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_subscribe_store`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_subscribe_store`;
CREATE TABLE `pigcms_subscribe_store` (
  `sub_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝id',
  `openid` varchar(200) NOT NULL DEFAULT '' COMMENT '粉丝对应被关注店铺公众号openid',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '被关注店铺id',
  `subscribe_time` varchar(20) NOT NULL DEFAULT '0' COMMENT '关注时间',
  PRIMARY KEY (`sub_id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='粉丝关注店铺';

-- ----------------------------
-- Records of pigcms_subscribe_store
-- ----------------------------
INSERT INTO `pigcms_subscribe_store` VALUES ('21', '0', '', '15', '1449373228');
INSERT INTO `pigcms_subscribe_store` VALUES ('22', '58', '', '20', '1452215555');

-- ----------------------------
-- Table structure for `pigcms_supp_dis_relation`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_supp_dis_relation`;
CREATE TABLE `pigcms_supp_dis_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '关系表id',
  `supplier_id` int(11) NOT NULL COMMENT '供货商ID',
  `distributor_id` int(11) NOT NULL COMMENT '经销商ID（批发商ID）',
  `authen` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0 未认证 1 认证中 2 已认证',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '认证通过时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `bond` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '保证金剩余额度',
  `apply_recharge` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '申请充值额度',
  `bank_id` int(5) NOT NULL DEFAULT '0' COMMENT '开户银行',
  `bank_card` varchar(30) NOT NULL DEFAULT '0' COMMENT '银行卡号',
  `bank_card_user` varchar(30) NOT NULL DEFAULT '0' COMMENT '开卡人姓名',
  `opening_bank` varchar(30) DEFAULT '0' COMMENT '开户行',
  `phone` varchar(20) NOT NULL DEFAULT '0' COMMENT '打款人手机号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_supp_dis_relation
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_system_info`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_system_info`;
CREATE TABLE `pigcms_system_info` (
  `lastsqlupdate` int(10) NOT NULL,
  `version` varchar(10) NOT NULL,
  `currentfileid` varchar(40) NOT NULL DEFAULT '0',
  `currentsqlid` varchar(40) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_system_info
-- ----------------------------
INSERT INTO `pigcms_system_info` VALUES ('15240', '1448679720', '0', '0');

-- ----------------------------
-- Table structure for `pigcms_system_menu`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_system_menu`;
CREATE TABLE `pigcms_system_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `name` char(20) NOT NULL,
  `module` char(20) NOT NULL,
  `action` char(20) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统后台菜单表';

-- ----------------------------
-- Records of pigcms_system_menu
-- ----------------------------
INSERT INTO `pigcms_system_menu` VALUES ('1', '0', '后台首页', '', '', '10', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('2', '0', '系统设置', '', '', '9', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('3', '0', '商品管理', '', '', '7', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('4', '0', '订单管理', '', '', '6', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('5', '0', '用户管理', '', '', '5', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('6', '1', '后台首页', 'index', 'main', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('7', '1', '修改密码', 'index', 'pass', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('8', '1', '个人资料', 'index', 'profile', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('9', '1', '更新缓存', 'index', 'cache', '0', '0', '1');
INSERT INTO `pigcms_system_menu` VALUES ('10', '2', '站点配置', 'config', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('11', '2', '友情链接', 'flink', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('12', '0', '店铺管理', '', '', '4', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('13', '12', '店铺列表', 'Store', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('14', '2', '城市区域', 'area', 'index', '0', '0', '0');
INSERT INTO `pigcms_system_menu` VALUES ('15', '3', '商品列表', 'Product', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('16', '3', '商品分类', 'Product', 'category', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('17', '2', '广告管理', 'Adver', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('19', '2', '导航管理', 'Slider', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('20', '2', '热门搜索词', 'Search_hot', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('21', '24', '自定义菜单', 'diymenu', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('22', '2', '快递公司', 'Express', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('23', '12', '收支明细', 'Store', 'inoutdetail', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('24', '0', '微信设置', '', '', '8', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('25', '24', '首页回复配置', 'home', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('28', '4', '所有订单', 'Order', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('29', '5', '用户列表', 'user', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('30', '24', '首次关注回复', 'home', 'first', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('31', '24', '关键词回复', 'home', 'other', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('32', '2', '平台会员卡', 'Card', 'index', '0', '0', '0');
INSERT INTO `pigcms_system_menu` VALUES ('33', '24', '模板消息', 'templateMsg', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('34', '4', '到店自提订单', 'Order', 'selffetch', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('35', '4', '货到付款订单', 'Order', 'codpay', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('36', '4', '代付的订单', 'Order', 'payagent', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('37', '12', '主营类目', 'Store', 'category', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('38', '12', '提现记录', 'Store', 'withdraw', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('40', '3', '商品分组', 'Product', 'group', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('41', '2', '商品栏目属性类别管理', 'Sys_product_property', 'propertyType', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('42', '3', '商品属性列表', 'Product_property', 'property', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('43', '3', '商品属性值列表', 'Product_property', 'propertyValue', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('44', '2', '商城筛选属性列表', 'Sys_product_property', 'property', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('45', '2', '商城筛选属性值列表', 'Sys_product_property', 'propertyValue', '0', '0', '0');
INSERT INTO `pigcms_system_menu` VALUES ('46', '2', '商城评论标签', 'Tag', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('49', '2', '敏感词', 'Ng_word', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('50', '12', '品牌类别管理', 'Store', 'brandtype', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('51', '12', '品牌管理', 'Store', 'brand', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('53', '12', '营销活动管理', 'Store', 'activityManage', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('54', '12', '营销活动展示', 'Store', 'activityRecommend', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('55', '12', '店铺对账日志', 'Order', 'checklog', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('58', '3', '被分销的源商品列表', 'Product', 'fxlist', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('59', '3', '商品评价管理', 'Product', 'comment', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('60', '12', '店铺评价管理', 'Store', 'comment', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('61', '2', '收款银行', 'Bank', 'index', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('62', '1', '更新程序', 'System', 'checkUpdate', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('63', '1', '更新数据库', 'System', 'sqlUpdate', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('64', '4', '商家购买的短信订单', 'Order', 'smspay', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('65', '4', '退货列表', 'Order', 'return_order', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('66', '4', '维权列表', 'Order', 'rights', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('68', '5', '用户导出', 'User', 'checkout', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('67', '12', '认证自定义表单', 'Store', 'diyAttestation', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('69', '2', '商品栏目属性列表', 'Sys_product_property', 'property', '0', '1', '1');
INSERT INTO `pigcms_system_menu` VALUES ('70', '2', '商品栏目属性值列表', 'Sys_product_property', 'propertyValue', '0', '1', '1');

-- ----------------------------
-- Table structure for `pigcms_system_product_property`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_system_product_property`;
CREATE TABLE `pigcms_system_product_property` (
  `pid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '属性名',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序字段',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：启用，0：关闭',
  `property_type_id` smallint(5) NOT NULL COMMENT '产品属性所属类别id',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品栏目属性名表';

-- ----------------------------
-- Records of pigcms_system_product_property
-- ----------------------------
INSERT INTO `pigcms_system_product_property` VALUES ('5', '内存ROM', '0', '1', '2');
INSERT INTO `pigcms_system_product_property` VALUES ('7', '屏幕分辨率', '0', '1', '2');
INSERT INTO `pigcms_system_product_property` VALUES ('8', '领型', '0', '1', '1');
INSERT INTO `pigcms_system_product_property` VALUES ('9', '服装版型', '0', '1', '1');
INSERT INTO `pigcms_system_product_property` VALUES ('10', '空间', '0', '1', '3');
INSERT INTO `pigcms_system_product_property` VALUES ('11', '饰品', '0', '1', '4');
INSERT INTO `pigcms_system_product_property` VALUES ('12', '儿童读物', '0', '1', '5');
INSERT INTO `pigcms_system_product_property` VALUES ('13', '休闲零食', '0', '1', '7');
INSERT INTO `pigcms_system_product_property` VALUES ('14', '宝宝用品', '0', '1', '8');
INSERT INTO `pigcms_system_product_property` VALUES ('15', '时尚彩妆', '0', '1', '9');
INSERT INTO `pigcms_system_product_property` VALUES ('17', '汽车用品', '0', '1', '12');
INSERT INTO `pigcms_system_product_property` VALUES ('18', '户外鞋服', '0', '1', '13');
INSERT INTO `pigcms_system_product_property` VALUES ('19', '户外配件', '0', '1', '13');

-- ----------------------------
-- Table structure for `pigcms_system_product_to_property_value`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_system_product_to_property_value`;
CREATE TABLE `pigcms_system_product_to_property_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品id',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '系统筛选表id',
  `vid` int(10) NOT NULL DEFAULT '0' COMMENT '系统筛选属性值id',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`) USING BTREE,
  KEY `vid` (`vid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品关联筛选属性值表';

-- ----------------------------
-- Records of pigcms_system_product_to_property_value
-- ----------------------------
INSERT INTO `pigcms_system_product_to_property_value` VALUES ('1', '4', '14', '29');
INSERT INTO `pigcms_system_product_to_property_value` VALUES ('2', '20', '8', '12');
INSERT INTO `pigcms_system_product_to_property_value` VALUES ('3', '20', '9', '11');
INSERT INTO `pigcms_system_product_to_property_value` VALUES ('4', '20', '8', '13');
INSERT INTO `pigcms_system_product_to_property_value` VALUES ('15', '22', '8', '12');
INSERT INTO `pigcms_system_product_to_property_value` VALUES ('14', '22', '8', '13');

-- ----------------------------
-- Table structure for `pigcms_system_property_type`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_system_property_type`;
CREATE TABLE `pigcms_system_property_type` (
  `type_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(80) NOT NULL COMMENT '属性类别名',
  `type_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1为开启，0为关闭',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='产品属性的类别表';

-- ----------------------------
-- Records of pigcms_system_property_type
-- ----------------------------
INSERT INTO `pigcms_system_property_type` VALUES ('1', '鞋服', '1');
INSERT INTO `pigcms_system_property_type` VALUES ('2', '家电数码', '1');
INSERT INTO `pigcms_system_property_type` VALUES ('3', '家具建材', '1');
INSERT INTO `pigcms_system_property_type` VALUES ('4', '珠宝首饰', '1');
INSERT INTO `pigcms_system_property_type` VALUES ('5', '图书影音', '1');
INSERT INTO `pigcms_system_property_type` VALUES ('7', '食品酒水', '1');
INSERT INTO `pigcms_system_property_type` VALUES ('8', '母婴玩具', '1');
INSERT INTO `pigcms_system_property_type` VALUES ('9', '化妆护理', '1');
INSERT INTO `pigcms_system_property_type` VALUES ('12', '汽车用品', '1');
INSERT INTO `pigcms_system_property_type` VALUES ('13', '运动户外', '1');

-- ----------------------------
-- Table structure for `pigcms_system_property_value`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_system_property_value`;
CREATE TABLE `pigcms_system_property_value` (
  `vid` int(10) NOT NULL AUTO_INCREMENT COMMENT '商品栏目属性值id',
  `pid` int(10) NOT NULL DEFAULT '0' COMMENT '商品栏目属性名id',
  `value` varchar(50) NOT NULL DEFAULT '' COMMENT '商品栏目属性值',
  PRIMARY KEY (`vid`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `pid_2` (`pid`,`value`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品栏目属性值';

-- ----------------------------
-- Records of pigcms_system_property_value
-- ----------------------------
INSERT INTO `pigcms_system_property_value` VALUES ('3', '1', '文艺T恤');
INSERT INTO `pigcms_system_property_value` VALUES ('2', '1', '真丝T恤');
INSERT INTO `pigcms_system_property_value` VALUES ('1', '1', '纯棉');
INSERT INTO `pigcms_system_property_value` VALUES ('4', '4', 'T恤');
INSERT INTO `pigcms_system_property_value` VALUES ('5', '4', '短裙');
INSERT INTO `pigcms_system_property_value` VALUES ('7', '5', '16G');
INSERT INTO `pigcms_system_property_value` VALUES ('8', '5', '32G');
INSERT INTO `pigcms_system_property_value` VALUES ('6', '5', '8G');
INSERT INTO `pigcms_system_property_value` VALUES ('9', '7', '1920*1240');
INSERT INTO `pigcms_system_property_value` VALUES ('13', '8', 'V领');
INSERT INTO `pigcms_system_property_value` VALUES ('12', '8', '圆领');
INSERT INTO `pigcms_system_property_value` VALUES ('11', '9', '修身');
INSERT INTO `pigcms_system_property_value` VALUES ('10', '9', '宽松');
INSERT INTO `pigcms_system_property_value` VALUES ('14', '10', '办公室');
INSERT INTO `pigcms_system_property_value` VALUES ('16', '10', '厨房');
INSERT INTO `pigcms_system_property_value` VALUES ('15', '10', '客厅');
INSERT INTO `pigcms_system_property_value` VALUES ('18', '11', '戒指');
INSERT INTO `pigcms_system_property_value` VALUES ('19', '11', '手镯');
INSERT INTO `pigcms_system_property_value` VALUES ('17', '11', '项链');
INSERT INTO `pigcms_system_property_value` VALUES ('21', '12', '故事');
INSERT INTO `pigcms_system_property_value` VALUES ('22', '12', '文学');
INSERT INTO `pigcms_system_property_value` VALUES ('23', '12', '百科');
INSERT INTO `pigcms_system_property_value` VALUES ('20', '12', '益智');
INSERT INTO `pigcms_system_property_value` VALUES ('26', '13', '巧克力');
INSERT INTO `pigcms_system_property_value` VALUES ('25', '13', '点心');
INSERT INTO `pigcms_system_property_value` VALUES ('24', '13', '饼干');
INSERT INTO `pigcms_system_property_value` VALUES ('27', '14', '奶瓶');
INSERT INTO `pigcms_system_property_value` VALUES ('29', '14', '睡袋/抱被/抱毯');
INSERT INTO `pigcms_system_property_value` VALUES ('28', '14', '纱/布尿裤');
INSERT INTO `pigcms_system_property_value` VALUES ('30', '15', 'BB霜');
INSERT INTO `pigcms_system_property_value` VALUES ('32', '15', '眉笔/眉粉/眉膏');
INSERT INTO `pigcms_system_property_value` VALUES ('31', '15', '眼影');
INSERT INTO `pigcms_system_property_value` VALUES ('35', '17', '内饰用品');
INSERT INTO `pigcms_system_property_value` VALUES ('33', '17', '安全座椅');
INSERT INTO `pigcms_system_property_value` VALUES ('34', '17', '影音娱乐');
INSERT INTO `pigcms_system_property_value` VALUES ('37', '18', '冲锋衣');
INSERT INTO `pigcms_system_property_value` VALUES ('36', '18', '登山');
INSERT INTO `pigcms_system_property_value` VALUES ('39', '19', '睡袋');
INSERT INTO `pigcms_system_property_value` VALUES ('38', '19', '雪橇');

-- ----------------------------
-- Table structure for `pigcms_system_tag`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_system_tag`;
CREATE TABLE `pigcms_system_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT 'system_property_type表type_id，主要是为了方便查找',
  `name` varchar(100) NOT NULL COMMENT 'tag名',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，1为开启，0：关闭',
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统标签表';

-- ----------------------------
-- Records of pigcms_system_tag
-- ----------------------------
INSERT INTO `pigcms_system_tag` VALUES ('1', '0', '大品牌', '1');
INSERT INTO `pigcms_system_tag` VALUES ('2', '0', '正品行货', '1');
INSERT INTO `pigcms_system_tag` VALUES ('3', '0', '价格公道', '1');
INSERT INTO `pigcms_system_tag` VALUES ('4', '0', '物流快', '1');

-- ----------------------------
-- Table structure for `pigcms_tempmsg`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_tempmsg`;
CREATE TABLE `pigcms_tempmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tempkey` char(50) NOT NULL,
  `name` char(100) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `industry` char(50) NOT NULL,
  `topcolor` char(10) NOT NULL DEFAULT '#029700',
  `textcolor` char(10) NOT NULL DEFAULT '#000000',
  `token` char(40) NOT NULL,
  `tempid` char(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tempkey` (`tempkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_tempmsg
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_text`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_text`;
CREATE TABLE `pigcms_text` (
  `pigcms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `content` varchar(200) NOT NULL,
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_text
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_trade_selffetch`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_trade_selffetch`;
CREATE TABLE `pigcms_trade_selffetch` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `province` mediumint(9) NOT NULL,
  `city` mediumint(9) NOT NULL,
  `county` mediumint(9) NOT NULL,
  `address` varchar(150) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`pigcms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='买家上门自提';

-- ----------------------------
-- Records of pigcms_trade_selffetch
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_trade_setting`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_trade_setting`;
CREATE TABLE `pigcms_trade_setting` (
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `pay_cancel_time` smallint(6) NOT NULL COMMENT '自动取消订单时间',
  `pay_alert_time` smallint(6) NOT NULL COMMENT '自动催付订单时间',
  `sucess_notice` tinyint(1) NOT NULL COMMENT '支付成功是否通知',
  `send_notice` tinyint(1) NOT NULL COMMENT '发货是否通知',
  `complain_notice` tinyint(1) NOT NULL COMMENT '维权是否通知',
  `last_time` int(11) NOT NULL,
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='交易物流通知';

-- ----------------------------
-- Records of pigcms_trade_setting
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_ucenter`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_ucenter`;
CREATE TABLE `pigcms_ucenter` (
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `page_title` varchar(100) NOT NULL COMMENT '页面名称',
  `bg_pic` varchar(200) NOT NULL COMMENT '背景图片',
  `show_level` char(1) NOT NULL DEFAULT '1' COMMENT '显示会员等级 0不显示 1显示',
  `show_point` char(1) NOT NULL DEFAULT '1' COMMENT '显示用户积分 0不显示 1显示',
  `has_custom` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有自定义字段',
  `last_time` int(11) NOT NULL COMMENT '最后编辑时间',
  UNIQUE KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户中心';

-- ----------------------------
-- Records of pigcms_ucenter
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_user`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_user`;
CREATE TABLE `pigcms_user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(20) NOT NULL,
  `password` char(32) NOT NULL,
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `openid` varchar(50) NOT NULL COMMENT '微信唯一标识',
  `app_openid` varchar(50) DEFAULT NULL COMMENT 'app端微信唯一标识',
  `reg_time` int(10) unsigned NOT NULL,
  `reg_ip` bigint(20) unsigned NOT NULL,
  `last_time` int(10) unsigned NOT NULL,
  `last_ip` bigint(20) unsigned NOT NULL,
  `check_phone` tinyint(1) NOT NULL DEFAULT '0',
  `login_count` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `intro` varchar(500) NOT NULL DEFAULT '' COMMENT '个人签名',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '头像',
  `is_weixin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是微信用户 0否 1是',
  `stores` smallint(6) NOT NULL DEFAULT '0' COMMENT '店铺数量',
  `token` varchar(100) NOT NULL DEFAULT '' COMMENT '微信token',
  `smscount` int(10) NOT NULL DEFAULT '0' COMMENT '剩余短信数量',
  `session_id` varchar(50) NOT NULL DEFAULT '' COMMENT 'session id',
  `server_key` varchar(50) NOT NULL DEFAULT '',
  `source_site_url` varchar(200) NOT NULL DEFAULT '' COMMENT '来源网站',
  `payment_url` varchar(200) NOT NULL DEFAULT '' COMMENT '站外支付地址',
  `notify_url` varchar(200) NOT NULL DEFAULT '' COMMENT '通知地址',
  `oauth_url` varchar(200) NOT NULL DEFAULT '' COMMENT '对接网站用户认证地址',
  `is_seller` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是卖家',
  `third_id` varchar(50) NOT NULL DEFAULT '' COMMENT '第三方id',
  `drp_store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户所属店铺',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '对接应用id',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '后台ID',
  `item_store_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `weixin_bind` tinyint(1) DEFAULT '1' COMMENT '1:需要绑定手机号方可登陆wap，2.无需绑定即可登陆',
  PRIMARY KEY (`uid`),
  KEY `phone` (`phone`) USING BTREE,
  KEY `nickname` (`nickname`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `app_openid` (`app_openid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_user
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_user_address`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_user_address`;
CREATE TABLE `pigcms_user_address` (
  `address_id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',
  `session_id` varchar(32) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '收货人',
  `tel` varchar(20) NOT NULL COMMENT '联系电话',
  `province` mediumint(9) NOT NULL COMMENT '省code',
  `city` mediumint(9) NOT NULL COMMENT '市code',
  `area` mediumint(9) NOT NULL COMMENT '区code',
  `address` varchar(300) NOT NULL DEFAULT '' COMMENT '详细地址',
  `zipcode` varchar(10) NOT NULL COMMENT '邮编',
  `default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认收货地址',
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`address_id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='收货地址';

-- ----------------------------
-- Records of pigcms_user_address
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_user_attention`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_user_attention`;
CREATE TABLE `pigcms_user_attention` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `data_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '当type=1，这里值为商品id，type=2，此值为店铺id',
  `data_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '数据类型  1，商品 2，店铺',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of pigcms_user_attention
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_user_cart`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_user_cart`;
CREATE TABLE `pigcms_user_cart` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `session_id` varchar(32) NOT NULL,
  `store_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sku_id` int(11) NOT NULL,
  `sku_data` text NOT NULL COMMENT '库存信息',
  `pro_num` int(11) NOT NULL,
  `pro_price` decimal(10,2) NOT NULL,
  `add_time` int(11) NOT NULL,
  `comment` text NOT NULL,
  `is_fx` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为分销商品',
  PRIMARY KEY (`pigcms_id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `session_id` (`session_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户的购物车';

-- ----------------------------
-- Records of pigcms_user_cart
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_user_certificate`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_user_certificate`;
CREATE TABLE `pigcms_user_certificate` (
  `pigcms_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `certificate` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登陆凭证',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`pigcms_id`),
  UNIQUE KEY `uid` (`uid`) USING BTREE,
  KEY `add_time` (`add_time`) USING BTREE,
  KEY `certificate` (`certificate`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_user_certificate
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_user_collect`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_user_collect`;
CREATE TABLE `pigcms_user_collect` (
  `collect_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dataid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当type=1，这里值为商品id，type=2，此值为店铺id',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL COMMENT '1:为商品；2:为店铺',
  `is_attention` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被关注(0:不关注，1：关注)',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`collect_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`dataid`),
  KEY `is_attention` (`is_attention`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户收藏店铺or商品';

-- ----------------------------
-- Records of pigcms_user_collect
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_user_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_user_coupon`;
CREATE TABLE `pigcms_user_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `store_id` int(11) NOT NULL COMMENT '商铺id',
  `coupon_id` int(11) NOT NULL COMMENT '优惠券ID',
  `card_no` char(32) NOT NULL COMMENT '卡号',
  `cname` varchar(255) NOT NULL COMMENT '优惠券名称',
  `face_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠券面值(起始)',
  `limit_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用优惠券的订单金额下限（为0：为不限定）',
  `start_time` int(11) NOT NULL COMMENT '生效时间',
  `end_time` int(11) NOT NULL COMMENT '过期时间',
  `is_expire_notice` tinyint(1) NOT NULL COMMENT '到期提醒（0：不提醒；1：提醒）',
  `is_share` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许分享链接（0：不允许；1：允许）',
  `is_all_product` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否全店通用（0：全店通用；1：指定商品使用）',
  `is_original_price` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:非原价购买可使用；1：原价购买商品时可',
  `description` text NOT NULL COMMENT '使用说明',
  `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用',
  `is_valid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:不可以使用，1：可以使用',
  `use_time` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券使用时间',
  `timestamp` int(11) NOT NULL COMMENT '领取优惠券的时间',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '券类型（1：优惠券，2：赠送券）',
  `give_order_id` int(11) NOT NULL DEFAULT '0' COMMENT '赠送的订单id',
  `use_order_id` int(11) NOT NULL DEFAULT '0' COMMENT '使用的订单id',
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除(0:未删除，1：已删除)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_no` (`card_no`),
  KEY `coupon_id` (`coupon_id`),
  KEY `uid` (`uid`),
  KEY `type` (`type`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户领取的优惠券信息';

-- ----------------------------
-- Records of pigcms_user_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_user_degree`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_user_degree`;
CREATE TABLE `pigcms_user_degree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `name` varchar(600) DEFAULT NULL,
  `level_pic` varchar(600) DEFAULT NULL,
  `rule_type` varchar(33) DEFAULT NULL,
  `level_num` int(11) DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `is_postage_free` tinyint(1) DEFAULT NULL,
  `trade_limit` int(11) DEFAULT NULL,
  `amount_limit` int(11) DEFAULT NULL,
  `points_limit` int(11) DEFAULT NULL,
  `description` varchar(750) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_user_degree
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_user_points_record`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_user_points_record`;
CREATE TABLE `pigcms_user_points_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户uid',
  `store_id` int(11) DEFAULT NULL COMMENT '获取积分的 来源商铺id',
  `order_id` int(11) DEFAULT NULL COMMENT '赠送积分来源的订单',
  `points` int(11) DEFAULT NULL COMMENT '获取积分数',
  `type` tinyint(1) DEFAULT NULL COMMENT '获取积分方式：1:关注我的微信；2:成功交易数量；3:购买金额达到多少,5:满减送送的积分',
  `is_available` tinyint(1) DEFAULT '1' COMMENT '是否可用：0：不可以用，1可以使用',
  `is_call_to_fans` tinyint(1) DEFAULT NULL COMMENT '0:不发送；1发送通知粉丝获得积分',
  `timestamp` int(11) DEFAULT NULL,
  `money` double(10,2) DEFAULT '0.00' COMMENT '变更金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pigcms_user_points_record
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_wei_page`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_wei_page`;
CREATE TABLE `pigcms_wei_page` (
  `page_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '页面id',
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `page_name` varchar(50) NOT NULL COMMENT '页面标题',
  `page_desc` varchar(1000) NOT NULL COMMENT '页面描述',
  `bgcolor` varchar(10) NOT NULL COMMENT '背景颜色',
  `is_home` tinyint(1) NOT NULL DEFAULT '0' COMMENT '主页 0否 1是',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建日期',
  `product_count` int(10) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `hits` int(10) NOT NULL DEFAULT '0' COMMENT '页面浏览量',
  `page_sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `has_category` tinyint(1) NOT NULL,
  `has_custom` tinyint(1) NOT NULL,
  PRIMARY KEY (`page_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微页面';

-- ----------------------------
-- Records of pigcms_wei_page
-- ----------------------------
INSERT INTO `pigcms_wei_page` VALUES ('1', '1', '通用模板', '通用模板', '', '0', '1438999689', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('2', '1', '餐饮外卖模板', '餐饮外卖模板', '', '0', '1438999668', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('3', '1', '食品电商模板', '食品电商模板', '', '0', '1438999652', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('4', '1', '美妆电商模板', '美妆电商模板', '', '0', '1438999625', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('5', '1', '线下门店模板', '线下门店模板', '', '0', '1438999588', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('6', '1', '鲜花速递模板', '鲜花速递模板', '', '1', '1438999567', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('7', '2', '美妆电商模板', '美妆电商模板', '', '1', '1438999625', '0', '1', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('8', '3', '线下门店模板', '线下门店模板', '', '1', '1438999588', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('9', '4', '美妆电商模板', '美妆电商模板', '', '1', '1446748604', '0', '8', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('10', '5', '餐饮外卖模板', '餐饮外卖模板', '', '1', '1446774928', '0', '2', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('11', '6', '餐饮外卖模板', '餐饮外卖模板', '', '0', '1448526250', '0', '1', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('12', '7', '鲜花速递模板', '鲜花速递模板', '', '1', '1438999567', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('13', '8', '通用模板', '通用模板', '', '0', '1438999689', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('14', '8', '测试', '', '', '1', '1447810106', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('17', '10', '这是您的第一篇微杂志', '', '', '0', '1448414778', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('16', '9', '微页面标题', '', '', '1', '1448211438', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('18', '10', '微页面标题', '', '', '1', '1448415958', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('19', '11', '这是您的第一篇微杂志', '', '', '1', '1448458061', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('20', '12', '这是您的第一篇微杂志', '', '', '1', '1448457713', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('21', '13', '这是您的第一篇微杂志', '', '', '0', '1448504771', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('22', '13', '微页面标题', '', '', '1', '1448505771', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('23', '6', '微页面标题', '', '', '1', '1448526827', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('24', '14', '这是您的第一篇微杂志', '', '', '1', '1448549470', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('25', '15', '通用模板', '通用模板', '', '0', '1438999689', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('26', '15', '餐饮外卖模板', '餐饮外卖模板', '', '0', '1438999668', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('27', '15', '食品电商模板', '食品电商模板', '', '0', '1438999652', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('28', '15', '美妆电商模板', '美妆电商模板', '', '0', '1438999625', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('29', '15', '线下门店模板', '线下门店模板', '', '0', '1438999588', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('30', '15', '鲜花速递模板', '鲜花速递模板', '', '1', '1438999567', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('31', '16', '线下门店模板', '线下门店模板', '', '1', '1438999588', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('32', '17', '线下门店模板', '线下门店模板', '', '1', '1438999588', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('33', '18', '美妆电商模板', '美妆电商模板', '', '1', '1438999625', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('34', '19', '通用模板', '通用模板', '', '1', '1438999689', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('35', '20', '线下门店模板', '线下门店模板', '', '1', '1452231203', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('36', '34', '通用模板', '通用模板', '', '0', '1438999689', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('37', '34', '餐饮外卖模板', '餐饮外卖模板', '', '0', '1438999668', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('38', '34', '食品电商模板', '食品电商模板', '', '0', '1438999652', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('39', '34', '美妆电商模板', '美妆电商模板', '', '0', '1438999625', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('40', '34', '线下门店模板', '线下门店模板', '', '0', '1438999588', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('41', '34', '鲜花速递模板', '鲜花速递模板', '', '1', '1438999567', '0', '0', '0', '1', '1');
INSERT INTO `pigcms_wei_page` VALUES ('42', '35', '这是您的第一篇微杂志', '', '', '1', '1453005138', '0', '0', '0', '0', '1');
INSERT INTO `pigcms_wei_page` VALUES ('43', '36', '美妆电商模板', '美妆电商模板', '', '1', '1438999625', '0', '0', '0', '1', '1');

-- ----------------------------
-- Table structure for `pigcms_wei_page_category`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_wei_page_category`;
CREATE TABLE `pigcms_wei_page_category` (
  `cat_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '微页面分类id',
  `store_id` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `cat_name` varchar(50) NOT NULL COMMENT '分类名',
  `first_sort` varchar(20) NOT NULL DEFAULT '' COMMENT '排序 pv DESC order_by DESC',
  `second_sort` varchar(20) NOT NULL DEFAULT '' COMMENT '排序 date_added DESC date_added DESC pv DESC',
  `show_style` char(1) NOT NULL DEFAULT '0' COMMENT '显示样式 0仅显示杂志列表 1用期刊方式展示',
  `cat_desc` text NOT NULL COMMENT '简介',
  `page_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '微页面数',
  `has_custom` tinyint(1) NOT NULL,
  `add_time` int(11) NOT NULL COMMENT '创建日期',
  `cover_img` varchar(100) NOT NULL DEFAULT '' COMMENT '封面路径',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `page_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微页面分类';

-- ----------------------------
-- Records of pigcms_wei_page_category
-- ----------------------------
INSERT INTO `pigcms_wei_page_category` VALUES ('1', '1', '通用模板', '0', '0', '0', '<p>通用模板描述。</p>', '1', '0', '1438998819', '/upload/images/icon_03.png', '5', '1');
INSERT INTO `pigcms_wei_page_category` VALUES ('2', '1', '餐饮外卖', '0', '0', '0', '<p>餐饮外卖描述。</p>', '1', '0', '1438998801', '/upload/images/icon_05.png', '5', '2');
INSERT INTO `pigcms_wei_page_category` VALUES ('3', '1', '食品电商', '0', '0', '0', '<p>食品电商描述</p>', '1', '0', '1438998781', '/upload/images/icon_07.png', '5', '3');
INSERT INTO `pigcms_wei_page_category` VALUES ('4', '1', '美妆电商', '1', '0', '0', '<p>美妆电商描述。</p>', '1', '0', '1438998760', '/upload/images/icon_12.png', '5', '4');
INSERT INTO `pigcms_wei_page_category` VALUES ('5', '1', '线下门店', '0', '0', '0', '<p>线下门店描述</p>', '1', '0', '1438998738', '/upload/images/icon_13.png', '5', '5');
INSERT INTO `pigcms_wei_page_category` VALUES ('6', '1', '鲜花速递', '0', '0', '0', '<p>鲜花速递描述。</p>', '1', '0', '1438998718', '/upload/images/icon_14.png', '5', '6');
INSERT INTO `pigcms_wei_page_category` VALUES ('7', '2', '美妆电商', '1', '0', '0', '<p>美妆电商描述。</p>', '1', '0', '1438998760', '/upload/images/icon_12.png', '6', '4');
INSERT INTO `pigcms_wei_page_category` VALUES ('8', '3', '线下门店', '0', '0', '0', '<p>线下门店描述</p>', '1', '0', '1438998738', '/upload/images/icon_13.png', '9', '5');
INSERT INTO `pigcms_wei_page_category` VALUES ('9', '4', '美妆电商', '1', '0', '0', '<p>美妆电商描述。</p>', '1', '0', '1438998760', '/upload/images/icon_12.png', '12', '4');
INSERT INTO `pigcms_wei_page_category` VALUES ('10', '5', '餐饮外卖', '0', '0', '0', '<p>餐饮外卖描述。</p>', '1', '0', '1438998801', '/upload/images/icon_05.png', '14', '2');
INSERT INTO `pigcms_wei_page_category` VALUES ('11', '6', '餐饮外卖', '0', '0', '0', '<p>餐饮外卖描述。</p>', '1', '0', '1438998801', '/upload/images/icon_05.png', '16', '2');
INSERT INTO `pigcms_wei_page_category` VALUES ('12', '7', '鲜花速递', '0', '0', '0', '<p>鲜花速递描述。</p>', '1', '0', '1438998718', '/upload/images/icon_14.png', '19', '6');
INSERT INTO `pigcms_wei_page_category` VALUES ('13', '8', '通用模板', '0', '0', '0', '<p>通用模板描述。</p>', '1', '0', '1438998819', '/upload/images/icon_03.png', '22', '1');
INSERT INTO `pigcms_wei_page_category` VALUES ('14', '15', '通用模板', '0', '0', '0', '<p>通用模板描述。</p>', '1', '0', '1438998819', '/upload/images/icon_03.png', '0', '25');
INSERT INTO `pigcms_wei_page_category` VALUES ('15', '15', '餐饮外卖', '0', '0', '0', '<p>餐饮外卖描述。</p>', '1', '0', '1438998801', '/upload/images/icon_05.png', '0', '26');
INSERT INTO `pigcms_wei_page_category` VALUES ('16', '15', '食品电商', '0', '0', '0', '<p>食品电商描述</p>', '1', '0', '1438998781', '/upload/images/icon_07.png', '0', '27');
INSERT INTO `pigcms_wei_page_category` VALUES ('17', '15', '美妆电商', '1', '0', '0', '<p>美妆电商描述。</p>', '1', '0', '1438998760', '/upload/images/icon_12.png', '0', '28');
INSERT INTO `pigcms_wei_page_category` VALUES ('18', '15', '线下门店', '0', '0', '0', '<p>线下门店描述</p>', '1', '0', '1438998738', '/upload/images/icon_13.png', '0', '29');
INSERT INTO `pigcms_wei_page_category` VALUES ('19', '15', '鲜花速递', '0', '0', '0', '<p>鲜花速递描述。</p>', '1', '0', '1438998718', '/upload/images/icon_14.png', '0', '30');
INSERT INTO `pigcms_wei_page_category` VALUES ('20', '16', '线下门店', '0', '0', '0', '<p>线下门店描述</p>', '1', '0', '1438998738', '/upload/images/icon_13.png', '0', '29');
INSERT INTO `pigcms_wei_page_category` VALUES ('21', '17', '线下门店', '0', '0', '0', '<p>线下门店描述</p>', '1', '0', '1438998738', '/upload/images/icon_13.png', '54', '29');
INSERT INTO `pigcms_wei_page_category` VALUES ('22', '18', '美妆电商', '1', '0', '0', '<p>美妆电商描述。</p>', '1', '0', '1438998760', '/upload/images/icon_12.png', '55', '28');
INSERT INTO `pigcms_wei_page_category` VALUES ('23', '19', '通用模板', '0', '0', '0', '<p>通用模板描述。</p>', '1', '0', '1438998819', '/upload/images/icon_03.png', '56', '25');
INSERT INTO `pigcms_wei_page_category` VALUES ('24', '20', '线下门店', '0', '0', '0', '<p>线下门店描述</p>', '1', '0', '1438998738', '/upload/images/icon_13.png', '57', '29');
INSERT INTO `pigcms_wei_page_category` VALUES ('25', '34', '通用模板', '0', '0', '0', '<p>通用模板描述。</p>', '1', '0', '1438998819', '/upload/images/icon_03.png', '53', '36');
INSERT INTO `pigcms_wei_page_category` VALUES ('26', '34', '餐饮外卖', '0', '0', '0', '<p>餐饮外卖描述。</p>', '1', '0', '1438998801', '/upload/images/icon_05.png', '53', '37');
INSERT INTO `pigcms_wei_page_category` VALUES ('27', '34', '食品电商', '0', '0', '0', '<p>食品电商描述</p>', '1', '0', '1438998781', '/upload/images/icon_07.png', '53', '38');
INSERT INTO `pigcms_wei_page_category` VALUES ('28', '34', '美妆电商', '1', '0', '0', '<p>美妆电商描述。</p>', '1', '0', '1438998760', '/upload/images/icon_12.png', '53', '39');
INSERT INTO `pigcms_wei_page_category` VALUES ('29', '34', '线下门店', '0', '0', '0', '<p>线下门店描述</p>', '1', '0', '1438998738', '/upload/images/icon_13.png', '53', '40');
INSERT INTO `pigcms_wei_page_category` VALUES ('30', '34', '鲜花速递', '0', '0', '0', '<p>鲜花速递描述。</p>', '1', '0', '1438998718', '/upload/images/icon_14.png', '53', '41');
INSERT INTO `pigcms_wei_page_category` VALUES ('31', '36', '美妆电商', '1', '0', '0', '<p>美妆电商描述。</p>', '1', '0', '1438998760', '/upload/images/icon_12.png', '59', '39');

-- ----------------------------
-- Table structure for `pigcms_wei_page_to_category`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_wei_page_to_category`;
CREATE TABLE `pigcms_wei_page_to_category` (
  `pigcms_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL DEFAULT '0' COMMENT '微页面id',
  `cat_id` int(11) NOT NULL DEFAULT '0' COMMENT '微页面分类id',
  PRIMARY KEY (`pigcms_id`),
  KEY `wei_page_id` (`page_id`) USING BTREE,
  KEY `wei_page_category_id` (`cat_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微页面关联分类';

-- ----------------------------
-- Records of pigcms_wei_page_to_category
-- ----------------------------

-- ----------------------------
-- Table structure for `pigcms_weixin_bind`
-- ----------------------------
DROP TABLE IF EXISTS `pigcms_weixin_bind`;
CREATE TABLE `pigcms_weixin_bind` (
  `pigcms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `authorizer_appid` varchar(100) NOT NULL COMMENT '授权方appid',
  `authorizer_refresh_token` varchar(500) NOT NULL COMMENT '刷新令牌',
  `func_info` varchar(50) NOT NULL COMMENT '公众号授权给开发者的权限集列表',
  `head_img` varchar(300) NOT NULL COMMENT '授权方头像',
  `service_type_info` tinyint(1) NOT NULL COMMENT '授权方公众号类型，0代表订阅号，1代表由历史老帐号升级后的订阅号，2代表服务号',
  `verify_type_info` tinyint(1) NOT NULL COMMENT '授权方认证类型，-1代表未认证，0代表微信认证，1代表新浪微博认证，2代表腾讯微博认证，3代表已资质认证通过但还未通过名称认证，4代表已资质认证通过、还未通过名称认证，但通过了新浪微博认证，5代表已资质认证通过、还未通过名称认证，但通过了腾讯微博认证',
  `user_name` varchar(70) NOT NULL COMMENT '授权方公众号的原始ID',
  `nick_name` varchar(30) NOT NULL COMMENT '授权方昵称',
  `alias` varchar(30) NOT NULL COMMENT '授权方公众号所设置的微信号，可能为空',
  `qrcode_url` varchar(300) NOT NULL COMMENT '二维码图片的URL',
  `wxpay_mchid` varchar(50) NOT NULL,
  `wxpay_key` varchar(50) NOT NULL,
  `wxpay_test` tinyint(1) NOT NULL,
  PRIMARY KEY (`pigcms_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `user_name` (`user_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='绑定微信信息';

-- ----------------------------
-- Records of pigcms_weixin_bind
-- ----------------------------
