-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 30, 2019 at 09:56 AM
-- Server version: 8.0.11
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
SET FOREIGN_KEY_CHECKS=0;
START TRANSACTION;

SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `guoxue`
--

--
-- Dumping data for table `auth_policy`
--

INSERT INTO `auth_policy` (`id`, `ver`, `statements`, `create_time`, `update_time`, `name`, `note`, `cate`, `is_default_version`) VALUES
(5, 1, '[{\"Action\":\"Clients:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1545642993, 1545643105, 'ClientsFullAccess', '管理所有应用(CLIENTS)权限', 'system', 1),
(12, 1, '[{\"Action\":[\"Clients:test*\"],\"Resource\":\"Clients:*\",\"Effect\":\"Allow\",\"Condition\":{\"StringEquals\":{\"by:post_table\":\"asasi\"},\"DateGreaterThan\":{\"by:CurrentTime\":\"2018-12-25 10:53:00\"},\"IpAddress\":{\"by:SourceIp\":[\"112.16.93.124\",\"8.8.8.8\"]}}}]', 1545643279, 1545643279, 'DbhClientsTestDenyAccess', '管理所有应用(CLIENTS)权限', 'system', 1),
(13, 1, '[{\"Action\":\"*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1545643417, 1545643417, 'DbhAdministratorAccess', '超级管理员权限', 'system', 1),
(14, 1, '[{\"Action\":\"Clients:*\",\"Resource\":\"*\",\"Effect\":\"Deny\"}]', 1545716754, 1545716754, 'DbhClientsTestAccess', '管理所有应用(CLIENTS)权限', 'system', 1),
(16, 1, '[{\"Action\":\"Auth*:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1547026949, 1547026949, 'DbhAuthAllAlow', '管理所有(Auth)', 'system', 1),
(18, 1, '[{\"Action\":\"UserLoginSession:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1547027264, 1547027264, 'DbhLoginAlow', '登录权限', 'system', 1),
(19, 1, '[{\"Action\":\"SecurityCode:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1547027313, 1547027313, 'DbhSecurityCodeAlow', '验证码权限', 'system', 1),
(21, 1, '[{\"Action\":\"Datatree:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1547027362, 1547027362, 'DbhDatatreeAlow', '数据字典权限', 'system', 1),
(22, 1, '[{\"Action\":\"Log:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1547027362, 1547538965, 'DbhLogAlow', '日志权限', 'system', 1),
(23, 1, '[{\"Action\":\"UserAccount:query\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1547634815, 1547634815, 'DbhUserAccountQueryAllow', '根据手机号查询用户', 'system', 1),
(24, 1, '[{\"Action\":\"Message:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1547705515, 1547705515, 'DbhMessageAllow', 'Mesage', 'system', 1),
(25, 1, '[{\"Action\":\"Config:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1547705515, 1547705515, 'DbhConfigAllow', 'Config', 'system', 1),
(26, 1, '[{\"Action\":\"Menu:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1547717699, 1547717699, 'DbhMenuAllow', 'Menu Resource ', 'system', 1),
(27, 1, '[{\"Action\":\"Album*:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1548136252, 1548139714, 'DbhAlbumAllAlow', 'Album Authority', 'system', 1),
(28, 1, '[{\"Action\":\"Banners:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1548472957, 1548472957, 'DbhBannersAllow', 'banners', 'system', 1),
(29, 1, '[{\"Action\":\"CmsArticle:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'CmsArticlePolicy', 'CmsArticle', 'system', 1),
(30, 1, '[{\"Action\":\"Sp*:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'SpAll', 'Shop All', 'system', 1),
(31, 1, '[{\"Action\":\"PayOrder:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'PayOrder', 'Pay Order', 'system', 1),
(32, 1, '[{\"Action\":\"GxOrder:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'GxOrder', 'GxOrder', 'system', 1),
(33, 1, '[{\"Action\":\"ProfitGraph:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'ProfitGraph', 'ProfitGraph', 'system', 1),
(35, 1, '[{\"Action\":\"Withdraw:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'Withdraw', 'Withdraw', 'system', 1),
(36, 1, '[{\"Action\":\"UserWallet:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'UserWallet', 'UserWallet', 'system', 1),
(37, 1, '[{\"Action\":\"UserBankCard:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'UserBankCard', 'UserBankCard', 'system', 1),
(38, 1, '[{\"Action\":\"UserIdCard:*\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'UserIdCard', 'UserIdCard', 'system', 1),
(39, 1, '[{\"Action\":\"LoginSession:logout\",\"Resource\":\"*\",\"Effect\":\"Allow\"}]', 1551514464, 1551514464, 'LoginSession', 'LoginSession', 'system', 1);

--
-- Dumping data for table `auth_policy_role`
--

INSERT INTO `auth_policy_role` (`role_id`, `policy_id`) VALUES
(1, 13),
(2, 18),
(2, 19),
(2, 32),
(2, 33),
(2, 35),
(2, 36),
(2, 37),
(2, 38),
(2, 39),
(3, 5),
(3, 18),
(3, 19);

--
-- Dumping data for table `auth_role`
--

INSERT INTO `auth_role` (`id`, `name`, `note`, `enable`) VALUES
(1, 'Administrator', 'Administrator', 1),
(2, 'Operator', 'Operator', 1),
(3, 'Base', 'Base', 1),
(12, '测试角色', '测试', 1);

--
-- Dumping data for table `auth_role_menu`
--

INSERT INTO `auth_role_menu` (`role_id`, `menu_id`) VALUES
(2, 2),
(2, 4),
(2, 23),
(2, 32),
(2, 33),
(2, 34),
(2, 35),
(2, 36);

--
-- Dumping data for table `auth_user_role`
--

INSERT INTO `auth_user_role` (`user_id`, `role_id`) VALUES
(2, 3),
(3, 2),
(3, 3),
(4, 2),
(4, 3);

--
-- Dumping data for table `common_clients`
--

INSERT INTO `common_clients` (`id`, `uid`, `client_id`, `client_name`, `client_secret`, `api_alg`, `project_id`, `create_time`, `update_time`, `total_limit`, `day_limit`, `user_private_key`, `user_public_key`, `sys_public_key`, `sys_private_key`) VALUES
(1, 2, 'by04esfH0fdc6Y', '基础', 'fcbe33277447fbd48343b68d1b3f8de0', 'nothing', 'P2', 1545300406, 1545384329, 0, 0, '', '', '', ''),
(8, 4, 'by04esfH0glASt', '接口', 'ee7c5016ee68051d9d3d9448ff3c89e7', 'nothing', 'P2', 1562999162, 1562999162, 0, 0, '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'),
(9, 3, 'by04esfH0glAa1', '普通管理员', '7e4bb4955864d558a82ce0f0dfce2805', 'nothing', 'P2', 1562999604, 1562999604, 0, 0, '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');

--
-- Dumping data for table `common_config`
--

INSERT INTO `common_config` (`id`, `name`, `project_id`, `type`, `title`, `cate`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES
(1, 'WEBSITE_TITLE', 'a', 1, '网站标题2', '1', '', '网站标题前台显示标题3', 1378898976, 1547714096, 1, '管理平台', 4),
(9, 'CONFIG_TYPE_LIST', 'a', 3, '配置类型列表', '4', '', '主要用于数据解析和页面表单的生成', 1378898976, 1435743903, 1, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n5:图片', 2),
(10, 'WEBSITE_ICP', 'a', 1, '网站备案号', '1', '', '设置在网站底部显示的备案号，如“沪ICP备120079号-2', 1378900335, 1379235859, 1, '', 9),
(20, 'CONFIG_GROUP_LIST', 'a', 3, '配置分组', '4', ' e', '配置分组', 1379228036, 1491124301, 1, '1:基本\r\n2:内容\r\n4:系统\r\n7:支付\r\n8:邮件\r\n11:APP推送\r\n6:APP通用\r\n12:Android\r\n13:IOS  \r\n14:API配置\r\n15:短信', 1),
(39, 'WEBSITE_OWNER', 'a', 1, '网站拥有者', '1', '', '当前网站拥有者或是开发者', 1419415563, 1419415563, 1, '管理平台', 1),
(41, 'APP_VERSION', 'a', 0, '程序版本', '4', '', '程序版本主版本+大改动+小改动', 1419496611, 1419496611, 1, '1.0.0', 5),
(42, 'UCENTER_PLATFORM', 'a', 2, '管理平台名称', '1', '', '', 1420006808, 1547714259, 1, '', 1),
(54, 'ALIPAY_API', 'a', 3, '支付宝API', '7', '', '', 1441857154, 1553246925, 1, 'app_id:2016092800613314\nnotify_url:http://apidev.xxx.cn/index.php/alipay/notify\nreturn_url:http://apidev.xxx.cn/index.php/alipay/show\nali_public_key:MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0ciGUrmrtF82LlFWUSYIKs9dU3xzxO+vvLYXui47ahmHc6uudJmccw8jDPVC/r1gNyrmysUjqSUFdy6jN/1WawoZZkrXruXmNB1AMESAuZGu22ozZDYFipnTfZEx4zS05A6mHf+Fh6aaXHCL3IqmxdTYP8+WJziCRor8BSXw8AMaYArDhHyzRTeGNKxx40M3Yw9hxZMZHh9yYSovTsLiDSgKqaenybiLKMDPvayKUeo4tnwaUjFKohXlzRyF2TbiVeyWo5QLx2rknrM1kjs2XuuRFC70b6pXk9VOFKc/qlipqmdtq4bre8r/Kzx4ETK9/eX9I6fun2uSEwA2behXUQIDAQAB\nprivate_key:MIIEowIBAAKCAQEAuDKpxvscD9XtBQj5HMOS9gxbcoOkS/H9v7FRnWDxAlqY5R9hszskMG5dgwheDRehcK1KIXYPO5JWYNtjk8uiGDujEq3AMYf+Pf8qEE4pXrJcrmfMRXpLg6A6rR1TkxT2AkqeT2q1JZTJPccVZFGvQn8HvE/miXBRlA82jg4vfNqVDJi1Sv8l7UpMDVn0+17xbBg8wgUi4eYNMGKgLZ5aR2lhSiP6wiycx37tMLcPgXATaZlN+rSCGVtpfYuJhW16Oqj6Uu1sh7ebbvYP6qpnm10uHEGrSmGBDuGCsAklR20vMu48gpPERcN8rp3CUwceV6mxOPKs2kj45xxpv0vqXQIDAQABAoIBAFbCnk94NdaLqBGoEVZoFE/KsEQ3F2siN6hUCCI96Cd4ru21I1q9r6UURv2SMmKuT794ECPErRYdLox+qd+Sme4aIQyKRK3P5YQkLRbzCe9tydPoVkIfgXQlQ54I4zWzONEJfpnfpCVoeIWdwUi4ZPnIRceARxFTk0lPBR5SNjHzIgWD8+VzccBlQuvuQGMx46x11Q8/x12S+wwZOCUFPtB7Oz+8Nb5PCZiDPIKbkWEkYCxmD8Qx4MPwq2MPxA37Pbs01SS7/eWHVj3zxxqdpUPRl/NH9OJ7dcGMWmjdR55YqvkI0RPn8zMlcHt2+6aRbuq60sZceH8cWWPmSCc06KECgYEA6a1m200Fbpz4mkZdyJlaeQIx++gL1kM4K50ZIl2lIPkQfoKDOTOyWs3RaOKW6ugsVpwJPyoV6pzzsbAtWlCxWHq5i7pO0umn7maZ8ku6hV7yUEogFNJAcnELlTjA5+5tcimhm5OFK24+Qv5FBMCR4wJLxiyI8hI/UI/yH9cIB9UCgYEAycs80eUJBgIl7de6NzOhvfLQgSWzFbEP6snP4zfzBaAqqYyggNly+AeFOGytPfYf5F8L+v4E2Jre1EZSV0OJH5Cgb+9ZBiDiEvG8bnE1/qLUigldNJ+eORgCQ05d1tTSLoWOhlcRqAR42MjfsX4lvQDP/nYMcQ0DRqmEu6Br5GkCgYEAt0u0Rcs5upVYkDMn92ULSaZGLPHM7ynVEqjtAT7xe1bC8GlRo3oAqieN3rb2aJbryJgmzKwnetvYcAXg3Vo0clH53zeqAtkTR/alRSDP0zQx4Hb0iUCPGvW/fzKAZq1onkc3pABoUjPslMI8w0iGZsnzRCTE2xUjIv92jjEu9G0CgYAnUq6IbGxkulhvz8Ee7rXPERkmgZUzXeyvISKcsZNu/jYyEhBY64bm5HhGdY8Q6vxuAUORZpzFJPDVRtrW3HdFKmbJSUf81L2ZWXgRwYf3Ff365DZAgfZQQ+h0+zl4GsLWg/oxHZh4OF7gR8ynhuCNwzHE8XYdNZ70zyXpdipwOQKBgAbpBJ0xi6LsgT+IF+6oj8dMxe9jDGhY5zhsXKPY6JNRHo/bRqu3vnPpKHlAOdkCcmI39b5O5rDmQLDRevQCjxOIJOkKxvFBIvTxjjYqOPZ4fl2i2cvUHbexSoajqD7RC9ERIbbY8/5h3Ouh8kQBtLndVjj7cUyKSgaGT44Av3A2', 0),
(57, 'CUSTOMER_PHONE', 'a', 3, '客服电话', '13', '', '手机端在线电话', 1444982224, 1476771344, 1, 'tel:400-863-9156\r\nname: XXX', 0),
(65, 'FILTRATION_KEYWORDS', 'a', 2, '过滤关键词', '1', '', '过滤关键词', 1459935711, 1460167342, 1, '', 0),
(67, 'ANDROID_VERSION', 'a', 0, 'android版本', '12', '', 'app版本', 1459935711, 1478328548, 1, '1000', 0),
(68, 'APP_DOWNLOAD_URL', 'a', 2, 'APP下载地址', '13', '', '下载地址', 1459935711, 1478328433, 1, 'https://apidev.asasi.com/appdownload.php', 0),
(70, 'ANDROID_UPDATE_LOG', 'a', 2, 'android更新日志', '12', '', '版本更新日志', 1459935711, 1478328032, 1, '', 0),
(73, 'ANDROID_PAY_TYPE', 'a', 3, 'asasi安卓支付方式', '12', '', '', 1466133249, 1466133249, 1, '1:支付宝支付\r\n2:微信支付\r\n3:余额支付', 0),
(79, 'smtp_username', 'a', 1, '发送邮件地址', '8', '', '系统用此邮箱作为发送方', 1488351856, 1488351856, 1, 'asasi@email.xxx.com', 0),
(80, 'smtp_password', 'a', 1, '发送方邮件密码', '8', '', '', 1488351894, 1488353622, 1, 'xxxasasi2017', 0),
(81, 'smtp_port', 'a', 1, 'smtp端口', '8', '', '', 1488353646, 1488353646, 1, '80', 0),
(82, 'smtp_send_email', 'a', 1, 'smtp发送方邮件完整地址', '8', '', 'smtp发送方邮件完整地址（例如: postmaster@asasi.com）', 1488355460, 1488355521, 1, 'xxx@email.xxx.com', 0),
(83, 'smtp_sender_name', 'a', 1, '发送方称谓', '8', '', '发送方称谓', 1488355502, 1488355566, 1, '股份有限公司', 0),
(84, 'smtp_host', 'a', 1, '邮件服务器地址', '8', '', '邮件服务器的地址', 1488437581, 1488437581, 1, 'smtpdm.aliyun.com', 0),
(85, 'login_session_expire_time', 'a', 0, '系统通用会话过期时间', '1', '', '', 1489212177, 1489212177, 1, '7200', 0),
(90, 'admin_email', 'a', 1, '系统管理员邮箱', '4', '', '系统管理员邮箱', 1491449513, 1491449513, 1, '', 0),
(92, 'sys_default_password', 'a', 1, '系统中用户默认密码', '1', '', '系统中用户默认密码,长度控制8-24最佳', 1492066573, 1492066761, 1, 'it12345678', 0),
(95, 'umeng_ios_asasi', 'a', 3, '（ios）', '11', '', '（ios）', 1511579884, 1547715078, 1, 'alias_type:asasi.asasiyiyuan\r\ndevice_type:ios\r\nappkey:5a55bc52f29d9846e9000101\r\nsecret:adz5mrun5krfgtgjkzdgsijpgwwetpen\r\nproduction_mode:true', 0),
(96, 'umeng_android_asasi', 'a', 3, '安卓', '11', '', '（安卓）', 1511579928, 1547715085, 1, 'alias_type:asasiyiyuan\r\ndevice_type:android\r\nappkey:5a4365e68f4a9d0f6c00000c\r\nsecret:adn9lbe7va9fpg58yysc0huphx7wrygn\r\nproduction_mode:true', 0),
(153, 'code_sms_juhe', 'a', 3, '通用验证码', '15', '', '聚合短信', 1516010379, 1516010379, 1, 'app_key:847ef926829b6b248ef7653e57305ab2\r\ntpl_id:164289', 1),
(155, 'code_sms_qcloud', 'a', 3, 'test', '15', '', 'test', 1515984412, 1515984412, 1, 'app_key:test2\r\ntpl_id:test', 1),
(158, 'sys_default_user_group', 'a', 0, '默认注册用户所属组', '1', '', '', 0, 0, 0, '15', 0),
(161, 'sys_random_avatar', 'a', 1, '注册时随机系统头像', '4', '', '注册时随机系统头像', 0, 0, 0, '87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102', 0),
(163, 'IOS_VERSION', 'a', 1, 'ios最新版本', '13', '', '', 0, 0, 0, '1.0.0', 0),
(164, 'IOS_DOWNLOAD_URL', 'a', 2, 'IOS新版下载地址', '13', '', '', 0, 0, 0, 'taobao://taobao.com', 0),
(165, 'wx_open_config', 'a', 3, '微信开放平台配置', '14', '', '微信开放平台配置', 0, 0, 0, 'app_id:wx49cccd968b96ff87\r\napp_secret:81c1a9b79d9c3426fabc17fbdec38cb0\r\ncallback:http://asasi.xxx.com/callback.php/wx_login_callback/index', 0),
(167, 'code_sms_alert', 'a', 3, '直接返回', '15', '', '直接返回', 1516010379, 1516010379, 1, 'app_key:52ad13f9d647a730e11ff34dc6511d90\r\ntpl_id:106943', 5),
(170, 'SYS_BRAND', 'a', 1, '平台名称', '1', '', '平台名称', 1378898976, 1547714096, 1, 'DBH', 4),
(171, 'CONFIG_VERSION', 'a', 1, '配置版本', '1', '', '用于远程获取配置判断是否更新过,递增值', 1378898976, 1547714096, 1, '10000', 4),
(172, 'SYS_BRAND', 'P2', 1, '平台名称', '1', '', '平台名称', 1378898976, 1547714096, 1, 'DBH', 4),
(173, 'CONFIG_VERSION', 'P2', 1, '配置版本', '1', '', '用于远程获取配置判断是否更新过,递增值', 1378898976, 1547714096, 1, '10000', 4),
(174, 'WEBSITE_TITLE', 'P2', 1, '网站标题2', '1', '', '网站标题前台显示标题3', 1378898976, 1547714096, 1, '管理平台', 4),
(175, 'CONFIG_TYPE_LIST', 'P2', 3, '配置类型列表', '4', '', '主要用于数据解析和页面表单的生成', 1378898976, 1435743903, 1, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n5:图片', 2),
(176, 'WEBSITE_ICP', 'P2', 1, '网站备案号', '1', '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', 1378900335, 1379235859, 1, '', 9),
(177, 'CONFIG_GROUP_LIST', 'P2', 3, '配置分组', '4', ' e', '配置分组', 1379228036, 1491124301, 1, '1:基本\r\n2:内容\r\n4:系统\r\n7:支付\r\n8:邮件\r\n11:APP推送\r\n6:APP通用\r\n12:Android\r\n13:IOS  \r\n14:API配置\r\n15:短信', 1),
(178, 'WEBSITE_OWNER', 'P2', 1, '网站拥有者', '1', '', '当前网站拥有者或是开发者', 1419415563, 1419415563, 1, '管理平台', 1),
(179, 'APP_VERSION', 'P2', 0, '程序版本', '4', '', '程序版本主版本+大改动+小改动', 1419496611, 1419496611, 1, '1.0.0', 5),
(180, 'UCENTER_PLATFORM', 'P2', 2, '管理平台名称', '1', '', '', 1420006808, 1547714259, 1, '', 1),
(181, 'ALIPAY_API', 'P2', 3, '支付宝API', '7', '', '', 1441857154, 1553246925, 1, 'app_id:333\nnotify_url:http://apidev.xxx.cn/index.php/alipay/notify\nreturn_url:http://apidev.xxx.cn/index.php/alipay/show\nali_public_key:MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0ciGUrmrtF82LlFWUSYIKs9dU3xzxO+vvLYXui47ahmHc6uudJmccw8jDPVC/r1gNyrmysUjqSUFdy6jN/1WawoZZkrXruXmNB1AMESAuZGu22ozZDYFipnTfZEx4zS05A6mHf+Fh6aaXHCL3IqmxdTYP8+WJziCRor8BSXw8AMaYArDhHyzRTeGNKxx40M3Yw9hxZMZHh9yYSovTsLiDSgKqaenybiLKMDPvayKUeo4tnwaUjFKohXlzRyF2TbiVeyWo5QLx2rknrM1kjs2XuuRFC70b6pXk9VOFKc/qlipqmdtq4bre8r/Kzx4ETK9/eX9I6fun2uSEwA2behXUQIDAQAB\nprivate_key:MIIEowIBAAKCAQEAuDKpxvscD9XtBQj5HMOS9gxbcoOkS/H9v7FRnWDxAlqY5R9hszskMG5dgwheDRehcK1KIXYPO5JWYNtjk8uiGDujEq3AMYf+Pf8qEE4pXrJcrmfMRXpLg6A6rR1TkxT2AkqeT2q1JZTJPccVZFGvQn8HvE/miXBRlA82jg4vfNqVDJi1Sv8l7UpMDVn0+17xbBg8wgUi4eYNMGKgLZ5aR2lhSiP6wiycx37tMLcPgXATaZlN+rSCGVtpfYuJhW16Oqj6Uu1sh7ebbvYP6qpnm10uHEGrSmGBDuGCsAklR20vMu48gpPERcN8rp3CUwceV6mxOPKs2kj45xxpv0vqXQIDAQABAoIBAFbCnk94NdaLqBGoEVZoFE/KsEQ3F2siN6hUCCI96Cd4ru21I1q9r6UURv2SMmKuT794ECPErRYdLox+qd+Sme4aIQyKRK3P5YQkLRbzCe9tydPoVkIfgXQlQ54I4zWzONEJfpnfpCVoeIWdwUi4ZPnIRceARxFTk0lPBR5SNjHzIgWD8+VzccBlQuvuQGMx46x11Q8/x12S+wwZOCUFPtB7Oz+8Nb5PCZiDPIKbkWEkYCxmD8Qx4MPwq2MPxA37Pbs01SS7/eWHVj3zxxqdpUPRl/NH9OJ7dcGMWmjdR55YqvkI0RPn8zMlcHt2+6aRbuq60sZceH8cWWPmSCc06KECgYEA6a1m200Fbpz4mkZdyJlaeQIx++gL1kM4K50ZIl2lIPkQfoKDOTOyWs3RaOKW6ugsVpwJPyoV6pzzsbAtWlCxWHq5i7pO0umn7maZ8ku6hV7yUEogFNJAcnELlTjA5+5tcimhm5OFK24+Qv5FBMCR4wJLxiyI8hI/UI/yH9cIB9UCgYEAycs80eUJBgIl7de6NzOhvfLQgSWzFbEP6snP4zfzBaAqqYyggNly+AeFOGytPfYf5F8L+v4E2Jre1EZSV0OJH5Cgb+9ZBiDiEvG8bnE1/qLUigldNJ+eORgCQ05d1tTSLoWOhlcRqAR42MjfsX4lvQDP/nYMcQ0DRqmEu6Br5GkCgYEAt0u0Rcs5upVYkDMn92ULSaZGLPHM7ynVEqjtAT7xe1bC8GlRo3oAqieN3rb2aJbryJgmzKwnetvYcAXg3Vo0clH53zeqAtkTR/alRSDP0zQx4Hb0iUCPGvW/fzKAZq1onkc3pABoUjPslMI8w0iGZsnzRCTE2xUjIv92jjEu9G0CgYAnUq6IbGxkulhvz8Ee7rXPERkmgZUzXeyvISKcsZNu/jYyEhBY64bm5HhGdY8Q6vxuAUORZpzFJPDVRtrW3HdFKmbJSUf81L2ZWXgRwYf3Ff365DZAgfZQQ+h0+zl4GsLWg/oxHZh4OF7gR8ynhuCNwzHE8XYdNZ70zyXpdipwOQKBgAbpBJ0xi6LsgT+IF+6oj8dMxe9jDGhY5zhsXKPY6JNRHo/bRqu3vnPpKHlAOdkCcmI39b5O5rDmQLDRevQCjxOIJOkKxvFBIvTxjjYqOPZ4fl2i2cvUHbexSoajqD7RC9ERIbbY8/5h3Ouh8kQBtLndVjj7cUyKSgaGT44Av3A2', 0),
(182, 'CUSTOMER_PHONE', 'P2', 3, '客服电话', '13', '', '手机端在线电话', 1444982224, 1476771344, 1, 'tel:400-00-000-000\r\nname: XXX', 0),
(183, 'FILTRATION_KEYWORDS', 'P2', 2, '过滤关键词', '1', '', '过滤关键词', 1459935711, 1460167342, 1, '', 0),
(184, 'ANDROID_VERSION', 'P2', 0, 'android版本', '12', '', 'app版本', 1459935711, 1478328548, 1, '1000', 0),
(185, 'APP_DOWNLOAD_URL', 'P2', 2, 'APP下载地址', '13', '', '下载地址', 1459935711, 1478328433, 1, 'https://apidev.asasi.com/appdownload.php', 0),
(186, 'ANDROID_UPDATE_LOG', 'P2', 2, 'android更新日志', '12', '', '版本更新日志', 1459935711, 1478328032, 1, '', 0),
(187, 'ANDROID_PAY_TYPE', 'P2', 3, 'asasi安卓支付方式', '12', '', '', 1466133249, 1466133249, 1, '1:支付宝支付\r\n2:微信支付\r\n3:余额支付', 0),
(188, 'smtp_username', 'P2', 1, '发送邮件地址', '8', '', '系统用此邮箱作为发送方', 1488351856, 1488351856, 1, '@email.xxx.com', 0),
(189, 'smtp_password', 'P2', 1, '发送方邮件密码', '8', '', '', 1488351894, 1488353622, 1, 'aliyun2017', 0),
(190, 'smtp_port', 'P2', 1, 'smtp端口', '8', '', '', 1488353646, 1488353646, 1, '80', 0),
(191, 'smtp_send_email', 'P2', 1, 'smtp发送方邮件完整地址', '8', '', 'smtp发送方邮件完整地址（例如: postmaster@asasi.com）', 1488355460, 1488355521, 1, 'xxx@email.xxx.com', 0),
(192, 'smtp_sender_name', 'P2', 1, '发送方称谓', '8', '', '发送方称谓', 1488355502, 1488355566, 1, '股份有限公司', 0),
(193, 'smtp_host', 'P2', 1, '邮件服务器地址', '8', '', '邮件服务器的地址', 1488437581, 1488437581, 1, 'smtpdm.aliyun.com', 0),
(194, 'login_session_expire_time', 'P2', 0, '系统通用会话过期时间', '1', '', '', 1489212177, 1489212177, 1, '7200', 0),
(195, 'admin_email', 'P2', 1, '系统管理员邮箱', '4', '', '系统管理员邮箱', 1491449513, 1491449513, 1, '', 0),
(196, 'sys_default_password', 'P2', 1, '系统中用户默认密码', '1', '', '系统中用户默认密码,长度控制8-24最佳', 1492066573, 1492066761, 1, 'it12345678', 0),
(197, 'umeng_ios_asasi', 'P2', 3, '（ios）', '11', '', '（ios）', 1511579884, 1547715078, 1, 'alias_type:asasi.asasiyiyuan\r\ndevice_type:ios\r\nappkey:5a55bc52f29d9846e9000101\r\nsecret:adz5mrun5krfgtgjkzdgsijpgwwetpen\r\nproduction_mode:true', 0),
(198, 'umeng_android_asasi', 'P2', 3, '安卓', '11', '', '（安卓）', 1511579928, 1547715085, 1, 'alias_type:asasiyiyuan\r\ndevice_type:android\r\nappkey:5a4365e68f4a9d0f6c00000c\r\nsecret:adn9lbe7va9fpg58yysc0huphx7wrygn\r\nproduction_mode:true', 0),
(199, 'code_sms_juhe', 'P2', 3, '通用验证码', '15', '', '聚合短信', 1516010379, 1516010379, 1, 'app_key:847ef926829b6b248ef7653e57305ab2\r\ntpl_id:164289', 1),
(200, 'code_sms_qcloud', 'P2', 3, 'test', '15', '', 'test', 1515984412, 1515984412, 1, 'app_key:test2\r\ntpl_id:test', 1),
(201, 'sys_default_user_group', 'P2', 0, '默认注册用户所属组', '1', '', '', 0, 0, 0, '15', 0),
(202, 'sys_random_avatar', 'P2', 1, '注册时随机系统头像', '4', '', '注册时随机系统头像', 0, 0, 0, '87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102', 0),
(203, 'IOS_VERSION', 'P2', 1, 'ios最新版本', '13', '', '', 0, 0, 0, '1.0.0', 0),
(204, 'IOS_DOWNLOAD_URL', 'P2', 2, 'IOS新版下载地址', '13', '', '', 0, 0, 0, 'taobao://taobao.com', 0),
(205, 'wx_open_config', 'P2', 3, '微信开放平台配置', '14', '', '微信开放平台配置', 0, 0, 0, 'app_id:wx49cccd968b96ff87\r\napp_secret:81c1a9b79d9c3426fabc17fbdec38cb0\r\ncallback:http://asasi.xxx.com/callback.php/wx_login_callback/index', 0),
(206, 'code_sms_alert', 'P2', 3, '直接返回', '15', '', '直接返回', 1516010379, 1516010379, 1, 'app_key:52ad13f9d647a730e11ff34dc6511d90\r\ntpl_id:106943', 5),
(207, 'gx_config', 'P2', 3, 'guoxue全局配置', '14', '', 'guoxue全局配置', 1516010379, 1516010379, 1, 'max_income:5900000\r\nplatform_fixed_profit:99\r\npay_fee:0.005\r\nvip1:499\r\nvip_upgrade:200', 1);

--
-- Dumping data for table `common_country`
--

INSERT INTO `common_country` (`id`, `name`, `code`, `tel_prefix`, `py`) VALUES
(1, '中国中国', 'ZH', '+86', 'ZG'),
(2, '阿尔巴尼亚', 'ALB', '+355', 'AEBNY'),
(3, '阿尔及利亚', 'DZA', '+213', 'AEJLY'),
(4, '阿富汗', 'AFG', '+93', 'AFH'),
(5, '阿根廷', 'ARG', '+54', 'AGT'),
(6, '阿拉伯联合酋长国', 'ARE', '+971', 'ALBLHQCG'),
(7, '阿鲁巴', 'ABW', '+297', 'ALB'),
(8, '阿曼', 'OMN', '+968', 'AM'),
(9, '阿塞拜疆', 'AZE', '+994', 'ASBJ'),
(10, '阿森松岛', 'ASC', '+247', 'ASSD'),
(11, '埃及', 'EGY', '+20', 'AJ'),
(12, '埃塞俄比亚', 'ETH', '+251', 'ASEBY'),
(13, '爱尔兰', 'IRL', '+353', 'AEL'),
(14, '爱沙尼亚', 'EST', '+372', 'ASNY'),
(15, '安道尔', 'AND', '+376', 'ADE'),
(16, '安哥拉', 'AGO', '+244', 'AGL'),
(17, '安圭拉', 'AIA', '+1264', 'AGL'),
(18, '安提瓜岛和巴布达', 'ATG', '+1268', 'ATGDHBBD'),
(19, '澳大利亚', 'AUS', '+61', 'ADLY'),
(20, '奥地利', 'AUT', '+43', 'ADL'),
(21, '奥兰群岛', 'ALA', '+358', 'ALQD'),
(22, '巴巴多斯岛', 'BRB', '+1246', 'BBDSD'),
(23, '巴布亚新几内亚', 'PNG', '+675', 'BBYXJNY'),
(24, '巴哈马', 'BHS', '+1242', 'BHM'),
(25, '巴基斯坦', 'PAK', '+92', 'BJST'),
(26, '巴拉圭', 'PRY', '+595', 'BLG'),
(27, '巴勒斯坦', 'PSE', '+970', 'BLST'),
(28, '巴林', 'BHR', '+973', 'BL'),
(29, '巴拿马', 'PAN', '+507', 'BNM'),
(30, '巴西', 'BRA', '+55', 'BX'),
(31, '白俄罗斯', 'BLR', '+375', 'BELS'),
(32, '百慕大', 'BMU', '+1441', 'BMD'),
(33, '保加利亚', 'BGR', '+359', 'BJLY'),
(34, '北马里亚纳群岛', 'MNP', '+1670', 'BMLYNQD'),
(35, '贝宁', 'BEN', '+229', 'BN'),
(36, '比利时', 'BEL', '+32', 'BLS'),
(37, '冰岛', 'ISL', '+354', 'BD'),
(38, '波多黎各', 'PRI', '+1809', 'BDLG'),
(39, '波兰', 'POL', '+48', 'BL'),
(40, '玻利维亚', 'BOL', '+591', 'BLWY'),
(41, '波斯尼亚和黑塞哥维那', 'BIH', '+387', 'BSNYHHSGWN'),
(42, '博茨瓦纳', 'BWA', '+267', 'BCWN'),
(43, '伯利兹', 'BLZ', '+501', 'BLZ'),
(44, '不丹', 'BTN', '+975', 'BD'),
(45, '布基纳法索', 'BFA', '+226', 'BJNFS'),
(46, '布隆迪', 'BDI', '+257', 'BLD'),
(47, '布韦岛', 'BVT', '+00', 'BWD'),
(48, '朝鲜', 'PRK', '+850', 'CX'),
(49, '丹麦', 'DNK', '+45', 'DM'),
(50, '德国', 'DEU', '+49', 'DG'),
(51, '东帝汶', 'TLS', '+670', 'DDZ'),
(52, '多哥', 'TGO', '+228', 'DG'),
(53, '多米尼加', 'DMA', '+', 'DMNJ'),
(54, '多米尼加共和国', 'DOM', '+1809', 'DMNJGHG'),
(55, '俄罗斯', 'RUS', '+7', 'ELS'),
(56, '厄瓜多尔', 'ECU', '593', 'EGDE'),
(57, '厄立特里亚', 'ERI', '+291', 'ELTLY'),
(58, '法国', 'FRA', '+33', 'FG'),
(59, '法罗群岛', 'FRO', '+298', 'FLQD'),
(60, '法属波利尼西亚', 'PYF', '+689', 'FSBLNXY'),
(61, '法属圭亚那', 'GUF', '+594', 'FSGYN'),
(62, '法属南部领地', 'ATF', '+00', 'FSNBLD'),
(63, '梵蒂冈', 'VAT', '+3906698', 'ZDG'),
(64, '菲律宾', 'PHL', '+63', 'FLB'),
(65, '斐济', 'FJI', '+679', 'ZJ'),
(66, '芬兰', 'FIN', '+358', 'FL'),
(67, '佛得角', 'CPV', '+238', 'FDJ'),
(68, '弗兰克群岛', 'FLK', '+', 'FLKQD'),
(69, '冈比亚', 'GMB', '+220', 'GBY'),
(70, '刚果', 'COG', '+242', 'GG'),
(71, '刚果民主共和国', 'COD', '+243', 'GGMZGHG'),
(72, '哥伦比亚', 'COL', '+57', 'GLBY'),
(73, '哥斯达黎加', 'CRI', '+506', 'GSDLJ'),
(74, '格恩西岛', 'GGY', '+', 'GEXD'),
(75, '格林纳达', 'GRD', '+1473', 'GLND'),
(76, '格陵兰', 'GRL', '+299', 'GLL'),
(77, '古巴', 'CUB', '+53', 'GB'),
(78, '瓜德罗普', 'GLP', '+590', 'GDLP'),
(79, '关岛', 'GUM', '+1671', 'GD'),
(80, '圭亚那', 'GUY', '+592', 'GYN'),
(81, '哈萨克斯坦', 'KAZ', '+7', 'HSKST'),
(82, '海地', 'HTI', '+509', 'HD'),
(83, '韩国', 'KOR', '+82', 'HG'),
(84, '荷兰', 'NLD', '+31', 'HL'),
(85, '荷属安地列斯', 'ANT', '+', 'HSADLS'),
(86, '赫德和麦克唐纳群岛', 'HMD', '+', 'HDHMKTNQD'),
(87, '洪都拉斯', 'HND', '+504', 'HDLS'),
(88, '基里巴斯', 'KIR', '+686', 'JLBS'),
(89, '吉布提', 'DJI', '+253', 'JBT'),
(90, '吉尔吉斯斯坦', 'KGZ', '+996', 'JEJSST'),
(91, '几内亚', 'GIN', '+224', 'JNY'),
(92, '几内亚比绍', 'GNB', '+245', 'JNYBS'),
(93, '加拿大', 'CAN', '+1', 'JND'),
(94, '加纳', 'GHA', '+233', 'JN'),
(95, '加蓬', 'GAB', '+241', 'JP'),
(96, '柬埔寨', 'KHM', '+855', 'JPZ'),
(97, '捷克共和国', 'CZE', '+420', 'JKGHG'),
(98, '津巴布韦', 'ZWE', '+263', 'JBBW'),
(99, '喀麦隆', 'CMR', '+237', 'KML'),
(100, '卡塔尔', 'QAT', '+974', 'KTE'),
(101, '开曼群岛', 'CYM', '+1345', 'KMQD'),
(102, '科科斯群岛', 'CCK', '+61-891', 'KKSQD'),
(103, '科摩罗', 'COM', '+269', 'KML'),
(104, '科特迪瓦', 'CIV', '+225', 'KTDW'),
(105, '科威特', 'KWT', '+965', 'KWT'),
(106, '克罗地亚', 'HRV', '+385', 'KLDY'),
(107, '肯尼亚', 'KEN', '+254', 'KNY'),
(108, '库克群岛', 'COK', '+682', 'KKQD'),
(109, '拉脱维亚', 'LVA', '+371', 'LTWY'),
(110, '莱索托', 'LSO', '+266', 'LST'),
(111, '老挝', 'LAO', '+856', 'LW'),
(112, '黎巴嫩', 'LBN', '+961', 'LBN'),
(113, '利比里亚', 'LBR', '+231', 'LBLY'),
(114, '利比亚', 'LBY', '+218', 'LBY'),
(115, '立陶宛', 'LTU', '+370', 'LTW'),
(116, '列支敦士登', 'LIE', '+423', 'LZDSD'),
(117, '留尼旺岛', 'REU', '+262', 'LNWD'),
(118, '卢森堡', 'LUX', '+352', 'LSB'),
(119, '卢旺达', 'RWA', '+250', 'LWD'),
(120, '罗马尼亚', 'ROU', '+40', 'LMNY'),
(121, '马达加斯加', 'MDG', '+261', 'MDJSJ'),
(122, '马尔代夫', 'MDV', '+960', 'MEDF'),
(123, '马耳他', 'MLT', '+356', 'MET'),
(124, '马拉维', 'MWI', '+265', 'MLW'),
(125, '马来西亚', 'MYS', '+60', 'MLXY'),
(126, '马里', 'MLI', '+223', 'ML'),
(127, '马其顿', 'MKD', '+389', 'MQD'),
(128, '马绍尔群岛', 'MHL', '+692', 'MSEQD'),
(129, '马提尼克', 'MTQ', '+596', 'MTNK'),
(130, '马约特岛', 'MYT', '+269', 'MYTD'),
(131, '曼岛', 'IMN', '+44-1624', 'MD'),
(132, '毛里求斯', 'MUS', '+230', 'MLQS'),
(133, '毛里塔尼亚', 'MRT', '+222', 'MLTNY'),
(134, '美国', 'USA', '+1', 'MG'),
(135, '美属萨摩亚', 'ASM', '+1684', 'MSSMY'),
(136, '美属外岛', 'UMI', '+', 'MSWD'),
(137, '蒙古', 'MNG', '+976', 'MG'),
(138, '蒙特塞拉特', 'MSR', '+1664', 'MTSLT'),
(139, '孟加拉', 'BGD', '+880', 'MJL'),
(140, '密克罗尼西亚', 'FSM', '+00691', 'MKLNXY'),
(141, '秘鲁', 'PER', '+51', 'ML'),
(142, '缅甸', 'MMR', '+95', 'MD'),
(143, '摩尔多瓦', 'MDA', '+373', 'MEDW'),
(144, '摩洛哥', 'MAR', '+212', 'MLG'),
(145, '摩纳哥', 'MCO', '+377', 'MNG'),
(146, '莫桑比克', 'MOZ', '+258', 'MSBK'),
(147, '墨西哥', 'MEX', '+52', 'MXG'),
(148, '纳米比亚', 'NAM', '+264', 'NMBY'),
(149, '南非', 'ZAF', '+27', 'NF'),
(150, '南极洲', 'ATA', '+672', 'NJZ'),
(151, '南乔治亚和南桑德威奇群岛', 'SGS', '+', 'NQZYHNSDWQQD'),
(152, '瑙鲁', 'NRU', '+674', 'ZL'),
(153, '尼泊尔', 'NPL', '+977', 'NBE'),
(154, '尼加拉瓜', 'NIC', '+505', 'NJLG'),
(155, '尼日尔', 'NER', '+227', 'NRE'),
(156, '尼日利亚', 'NGA', '+234', 'NRLY'),
(157, '纽埃', 'NIU', '+683', 'NA'),
(158, '挪威', 'NOR', '+47', 'NW'),
(159, '诺福克', 'NFK', '+672', 'NFK'),
(160, '帕劳群岛', 'PLW', '+', 'PLQD'),
(161, '皮特凯恩', 'PCN', '+', 'PTKE'),
(162, '葡萄牙', 'PRT', '+351', 'PTY'),
(163, '乔治亚', 'GEO', '+', 'QZY'),
(164, '日本', 'JPN', '+81', 'RB'),
(165, '瑞典', 'SWE', '+46', 'RD'),
(166, '瑞士', 'CHE', '+41', 'RS'),
(167, '萨尔瓦多', 'SLV', '+503', 'SEWD'),
(168, '萨摩亚', 'WSM', '+684', 'SMY'),
(169, '塞尔维亚,黑山', 'SCG', '+381', 'SEWY,HS'),
(170, '塞拉利昂', 'SLE', '+232', 'SLLA'),
(171, '塞内加尔', 'SEN', '+221', 'SNJE'),
(172, '塞浦路斯', 'CYP', '+357', 'SPLS'),
(173, '塞舌尔', 'SYC', '+248', 'SSE'),
(174, '沙特阿拉伯', 'SAU', '+966', 'STALB'),
(175, '圣诞岛', 'CXR', '+619164', 'SDD'),
(176, '圣多美和普林西比', 'STP', '+239', 'SDMHPLXB'),
(177, '圣赫勒拿', 'SHN', '+290', 'SHLN'),
(178, '圣基茨和尼维斯', 'KNA', '+1869', 'SJCHNWS'),
(179, '圣卢西亚', 'LCA', '+1758', 'SLXY'),
(180, '圣马力诺', 'SMR', '+378', 'SMLN'),
(181, '圣皮埃尔和米克隆群岛', 'SPM', '+', 'SPAEHMKLQD'),
(182, '圣文森特和格林纳丁斯', 'VCT', '+1784', 'SWSTHGLNDS'),
(183, '斯里兰卡', 'LKA', '+94', 'SLLK'),
(184, '斯洛伐克', 'SVK', '+421', 'SLFK'),
(185, '斯洛文尼亚', 'SVN', '+386', 'SLWNY'),
(186, '斯瓦尔巴和扬马廷', 'SJM', '+', 'SWEBHYMT'),
(187, '斯威士兰', 'SWZ', '+268', 'SWSL'),
(188, '苏丹', 'SDN', '+249', 'SD'),
(189, '苏里南', 'SUR', '+597', 'SLN'),
(190, '所罗门群岛', 'SLB', '+677', 'SLMQD'),
(191, '索马里', 'SOM', '+252', 'SML'),
(192, '塔吉克斯坦', 'TJK', '+992', 'TJKST'),
(193, '泰国', 'THA', '+66', 'TG'),
(194, '坦桑尼亚', 'TZA', '+255', 'TSNY'),
(195, '汤加', 'TON', '+676', 'TJ'),
(196, '特克斯和凯克特斯群岛', 'TCA', '+', 'TKSHKKTSQD'),
(197, '特里斯坦达昆哈', 'TAA', '+', 'TLSTDKH'),
(198, '特立尼达和多巴哥', 'TTO', '+1868', 'TLNDHDBG'),
(199, '突尼斯', 'TUN', '+216', 'TNS'),
(200, '图瓦卢', 'TUV', '+688', 'TWL'),
(201, '土耳其', 'TUR', '+90', 'TEQ'),
(202, '土库曼斯坦', 'TKM', '+993', 'TKMST'),
(203, '托克劳', 'TKL', '+690', 'TKL'),
(204, '瓦利斯和福图纳', 'WLF', '+690', 'WLSHFTN'),
(205, '瓦努阿图', 'VUT', '+678', 'WNAT'),
(206, '危地马拉', 'GTM', '+502', 'WDML'),
(207, '维尔京群岛美属', 'VIR', '+1-340', 'W'),
(208, '维尔京群岛英属', 'VGB', '+1-284', 'W'),
(209, '委内瑞拉', 'VEN', '+58', 'WNRL'),
(210, '文莱', 'BRN', '+673', 'WL'),
(211, '乌干达', 'UGA', '+256', 'WGD'),
(212, '乌克兰', 'UKR', '+380', 'WKL'),
(213, '乌拉圭', 'URY', '+598', 'WLG'),
(214, '乌兹别克斯坦', 'UZB', '+998', 'WZBKST'),
(215, '西班牙', 'ESP', '+34', 'XBY'),
(216, '希腊', 'GRC', '+30', 'XL'),
(217, '新加坡', 'SGP', '+65', 'XJP'),
(218, '新喀里多尼亚', 'NCL', '+687', 'XKLDNY'),
(219, '新西兰', 'NZL', '+64', 'XXL'),
(220, '匈牙利', 'HUN', '+36', 'XYL'),
(221, '叙利亚', 'SYR', '+963', 'XLY'),
(222, '牙买加', 'JAM', '+1876', 'YMJ'),
(223, '亚美尼亚', 'ARM', '+374', 'YMNY'),
(224, '也门', 'YEM', '+967', 'YM'),
(225, '伊拉克', 'IRQ', '+964', 'YLK'),
(226, '伊朗', 'IRN', '+98', 'YL'),
(227, '以色列', 'ISR', '+972', 'YSL'),
(228, '意大利', 'ITA', '+39', 'YDL'),
(229, '印度', 'IND', '+91', 'YD'),
(230, '印度尼西亚', 'IDN', '+62', 'YDNXY'),
(231, '英国', 'GBR', '+44', 'YG'),
(232, '英属印度洋领地', 'IOT', '+246', 'YSYDYLD'),
(233, '约旦', 'JOR', '+962', 'YD'),
(234, '越南', 'VNM', '+84', 'YN'),
(235, '赞比亚', 'ZMB', '+260', 'ZBY'),
(236, '泽西岛', 'JEY', '+44', 'ZXD'),
(237, '乍得', 'TCD', '+235', 'ZD'),
(238, '直布罗陀', 'GIB', '+350', 'ZBLT'),
(239, '智利', 'CHL', '+56', 'ZL'),
(240, '中非共和国', 'CAF', '+236', 'ZFGHG');

--
-- Dumping data for table `common_datatree`
--

INSERT INTO `common_datatree` (`id`, `code`, `name`, `alias`, `sort`, `create_time`, `update_time`, `parents`, `parent_id`, `notes`, `level`, `icon`, `data_level`) VALUES
(27, '001', '用户日志类型', '用户日志类型', 0, 1545622408, 1545622408, '0', 0, '', 1, '0', 1),
(28, '001001', '登录', '登录', 1, 1545622408, 1547020714, '0,27,', 27, 'test', 2, '0', 1),
(29, '001002', '更新密码', '更新密码', 0, 1545622421, 1545622421, '0,27,', 27, '', 2, '0', 1),
(31, '001003', '测试', 'test', 2, 1547020702, 1547020702, '0,27,', 27, 'test', 2, '', 1),
(32, '001003001', '123', '456', 3, 1547020808, 1547020808, '0,27,31,', 31, '1323', 3, '', 0),
(33, '002', 'SystemBase', 'system_base', 1, 1548473676, 1548473676, ',0,', 0, '', 1, '', 1),
(34, '002001', 'BannersPosition', 'BannersPosition', 1, 1548473698, 1548473698, ',0,33,', 33, '', 2, '', 1),
(41, '002002', 'ArticlePosition', '文章别名', 1, 1551514037, 1551514037, ',0,33,', 33, 'ArticlePosition', 2, '', 1),
(42, '002002001', 'Other', '杂谈', 1, 1551514084, 1551514084, ',0,33,41,', 41, '没分类的文章', 3, '', 1),
(43, '003', 'ProjectCategory', '项目类别', 1, 1553569646, 1553569646, ',0,', 0, '', 1, '', 1),
(44, '003001', '科技', '', 1, 1553569733, 1553569733, ',0,43,', 43, '', 2, '', 1),
(45, '002001002', 'app中间轮播图片', 'app中间轮播图片', 1, 1548473890, 1548473890, ',0,33,34,', 34, 'app中间轮播图片', 3, '', 1),

--
-- Dumping data for table `common_menu`
--

INSERT INTO `common_menu` (`id`, `icon`, `title`, `pid`, `level`, `sort`, `url`, `url_type`, `hide`, `tip`, `status`, `create_time`, `update_time`, `scene`) VALUES
(2, '', 'System', 0, 0, 0, '#', 0, 0, '', 1, 1546408709, 1546408709, 'backend'),
(3, 'by-shujuzidian', 'DataDictionary', 2, 1, 0, '/admin/datatree/index', 1, 0, '', 1, 1546408724, 1546408724, 'backend'),
(4, 'by-log', 'ApiLog', 2, 1, 0, '/admin/api/log', 1, 0, '', 1, 1546408736, 1546408736, 'backend'),
(5, 'by-yingyongguanli', 'Clients', 2, 1, 0, '/admin/clients/index', 1, 0, '', 1, 1546408744, 1546408744, 'backend'),
(6, 'by-xitongpeizhi', 'SystemSettings', 2, 1, 0, '/admin/config/index', 1, 0, '', 1, 1546408751, 1546408751, 'backend'),
(8, 'by-navicon-jsgl', 'Roles', 2, 1, 0, '/admin/roles/index', 1, 0, '', 1, 1546408751, 1546408751, 'backend'),
(14, 'by-celve', 'Policy', 2, 1, 0, '/admin/policy/index', 1, 0, '', 1, 1546408751, 1546408751, 'backend'),
(15, 'by-caidan', 'Menu', 2, 1, 0, '/admin/menu/index', 1, 0, '', 1, 1546408751, 1546408751, 'backend'),
(20, '#', 'Album', 0, 0, 0, '#', 1, 0, '', -1, 1548060457, 1554360728, 'backend'),
(21, 'by-leimupinleifenleileibie', 'Category', 20, 1, 0, '/admin/album/category', 1, 0, '', -1, 1548060788, 1554360722, 'backend'),
(22, 'by-xiangce', 'Album', 20, 1, 0, '/admin/album/index', 1, 0, '', -1, 1548060812, 1554360725, 'backend'),
(23, 'by-guanggaowei', 'Banner', 2, 1, 0, '/admin/banners/index', 1, 0, '', 1, 1548472904, 1548472904, 'backend'),
(24, '#', 'Cms', 0, 0, 0, '#', 1, 0, '', -1, 1551512930, 1554360771, 'backend'),
(25, 'by-wenzhang', 'Article', 24, 1, 0, '/admin/cms_article/index', 1, 0, '', -1, 1551513015, 1554360769, 'backend'),
(32, '#', 'CrowdFunding', 0, 0, 0, '#', 1, 0, '', -1, 1553247426, 1563881332, 'backend'),
(33, 'by-xiangmu', 'Project', 32, 1, 10, '/admin/cfproject/index', 1, 0, '', -1, 1553247509, 1563881331, 'backend'),
(34, 'by-pay-order', 'PayOrder', 32, 1, 6, '/admin/payorder/index', 1, 0, '', -1, 1553247609, 1563881329, 'backend'),
(35, 'by-yonghuguanli', 'User', 32, 1, 1, '/admin/cfuser/index', 1, 0, '', -1, 1553247651, 1563881328, 'backend'),
(36, 'by-dingdan', 'Order', 32, 1, 8, '/admin/cforder/index', 1, 0, '', -1, 1553247677, 1563881326, 'backend'),
(38, '#', 'Cms', 0, 0, 0, '#', 1, 0, '', 1, 1563882371, 1563882371, 'backend'),
(39, 'by-wodewenzhang', 'Article', 38, 1, 0, '/admin/cms_article/index', 1, 0, '', 1, 1563882398, 1563882398, 'backend'),
(40, 'by-dianpu', 'Shop', 2, 1, 0, '#', 1, 0, '', -1, 1563947028, 1563947039, 'backend'),
(41, 'by-dianpu', 'Shop', 0, 0, 0, '#', 1, 0, '', 1, 1563947057, 1563947057, 'backend'),
(42, 'by-leimupinleifenleileibie', 'Category', 41, 1, 0, '/admin/spcate/index', 1, 0, '', 1, 1563947076, 1563947076, 'backend'),
(43, 'by-property', 'Attribute', 41, 1, 0, '/admin/sp_prop/index', 1, 0, '', 1, 1563947097, 1563947097, 'backend'),
(44, 'by-pinpai', 'Brand', 41, 1, 0, '/admin/sp_brand/index', 1, 0, '', 1, 1563947122, 1563947122, 'backend'),
(45, '#', 'Album', 0, 0, 0, '#', 1, 0, '', 1, 1563956466, 1563956466, 'backend'),
(46, 'by-xiangce', 'Album', 45, 1, 0, '/admin/album/index', 1, 0, '', 1, 1563956487, 1563956487, 'backend'),
(47, 'by-leimupinleifenleileibie', 'Category', 45, 1, 0, '/admin/album/category', 1, 0, '', 1, 1563956506, 1563956506, 'backend'),
(48, '#', 'AlbumFrontIndex', 0, 0, 0, '#', 1, 0, '', 1, 1564110282, 1564110282, 'front'),
(49, '#', 'sexy', 48, 1, 0, '/t/xinggan', 1, 0, '', 1, 1564110316, 1564110316, 'front'),
(50, '#', 'uniform', 48, 1, 0, '/t/zhifu', 1, 0, '', 1, 1564110332, 1564110332, 'front'),
(51, '#', 'shaofu', 48, 1, 0, '/t/shaofu', 1, 0, '', 1, 1564110351, 1564110351, 'front'),
(52, '#', 'luoli', 48, 1, 0, '/t/luoli', 1, 0, '', 1, 1564110364, 1564110364, 'front'),
(53, 'by-video', 'Video', 38, 1, 0, '/admin/video/index', 1, 0, '', 1, 1564136197, 1564136197, 'backend'),
(54, 'by-fenlei', 'VideoCate', 38, 1, 0, '/admin/video/cate', 1, 0, '', 1, 1564136228, 1564136228, 'backend');




--
-- Dumping data for table `platform_wallet`
--

INSERT INTO `platform_wallet` (`id`, `type_no`, `balance`, `profit_ratio`, `remark`) VALUES
(1, 'balance', '0.0000', 0, '平台总余留钱包'),
(2, 'fund_creator', '0.0000', 10, '平台创始人'),
(3, 'pay1_fee', '0.0000', 0, '支付通道手续费');

--
-- Dumping data for table `profit_graph`
--

INSERT INTO `profit_graph` (`id`, `uid`, `vip_level`, `active`, `create_time`, `update_time`, `invite_count`, `parent_uid`, `total_income`, `mobile`, `family`) VALUES
(4, 4, '0', 0, 1569487735, 1569491749, 0, 0, '0.0000', '12345675453', '');

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`id`, `project_id`, `username`, `password`, `pay_secret`, `salt`, `mobile`, `country_no`, `email`, `wxapp_openid`, `wx_openid`, `wx_unionid`, `qq_openid`, `weibo_openid`, `create_time`, `reg_ip`, `last_login_time`, `last_login_ip`, `update_time`, `status`, `login_device_cnt`, `mobile_auth`, `email_auth`, `password_set`, `google_secret`) VALUES
(2, 'a', 'asasi', '$2y$15$kymuZ3gDxUWJIU3oby2NRu.WQkEyLrUF4t5E3zSW1kbALW0PXcbd6', '', 'HRrQNJGN', '12345678900', '86', '', '', '', '', '', '', 1545191751, 1880120700, 1545191751, 0, 1553246773, 1, 3, 0, 0, 0, ''),
(3, 'P2', 'm8611483379111', '$2y$15$EXbnMv8jIgehSu3AgT6KeuxnbfjBr6VhicmipXZKgdjJ6BbAEUKTC', '', 'kYJaHfWA', '11483379111', '86', '', '', '', '', '', '', 1545294352, 1880120700, 1569663319, 1880120700, 1569663319, 1, 3, 1, 0, 1, ''),
(4, 'P2', 'm8612345675453', '$2y$13$qxspcWDv4SplcqIgjGgnNu/HnLE0uk7.WJ4AWI43Cz747O9jDHbc6', '', 'KKEFPfXD', '12345675453', '86', '', '', '', '', '', '', 1569466945, 1880120700, 1569491749, 2130706433, 1569491749, 1, 3, 1, 0, 0, '');

--
-- Dumping data for table `user_grade`
--

INSERT INTO `user_grade` (`id`, `uid`, `grade_id`, `status`, `create_time`, `update_time`) VALUES
(4, 4, 1, 1, 1569467196, 1569467196);

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`uid`, `geohash`, `nickname`, `sex`, `birthday`, `head`, `score`, `login`, `status`, `sign`, `bg_img`, `email_validate`, `identity_validate`, `idcode`, `default_address`, `invite_uid`, `exp`, `nation`, `online_status`, `realname`) VALUES
(2, '', '系统管理员', 0, 0, '', 0, 0, 1, '', 0, 0, 0, '', 0, 0, 0, '', 1, ''),
(3, '', '网站管理员', 0, 0, '', 0, 0, 1, '', 0, 0, 0, '', 0, 0, 0, '', 1, ''),
(4, '', '王大锤', 0, 0, '', 0, 0, 1, '', 0, 0, 0, '5kZYH', 0, 0, 0, '', 1, '');

--
-- Dumping data for table `user_wallet`
--

INSERT INTO `user_wallet` (`id`, `uid`, `balance`, `frozen`, `withdraw_total`) VALUES
(1, 4, 0, 0, 0);

COMMIT;
SET FOREIGN_KEY_CHECKS=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
