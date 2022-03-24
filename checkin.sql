-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- 主機： momodaMasterdb
-- 產生時間： 2022 年 03 月 24 日 06:40
-- 伺服器版本： 10.1.48-MariaDB
-- PHP 版本： 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `checkin`
--

-- --------------------------------------------------------

--
-- 資料表結構 `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('BOSS', '4', 1554885378),
('BOSS', '7', 1554885378),
('leaveManagement', '38', 1611905504),
('會計部門', '2', 1554968486),
('會計部門', '34', 1554968486),
('會計部門', '4', 1580813056),
('美術部管理員', '15', 1582167522),
('美術部管理員', '27', 1582167583),
('資訊前端管理員', '22', 1578915093),
('資訊前端管理員', '6', 1554960810),
('資訊後端管理員', '1', 1554885693),
('資訊後端管理員', '5', 1554890942);

-- --------------------------------------------------------

--
-- 資料表結構 `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('BOSS', 1, '可以看所有部門的出勤和工作狀況', NULL, NULL, 1554875826, 1554875826),
('leaveEmployee', 2, '可以設定離職員工的權限', NULL, NULL, 1559700954, 1559700954),
('leaveManagement', 2, '請假管理的權限', NULL, NULL, 1611904781, 1611904781),
('lookAll', 2, '可以觀看所有部門的權限', NULL, NULL, 1554873947, 1554873947),
('lookArtDepartment', 2, '可以觀看美術部的權限', NULL, NULL, 1582167436, 1582167436),
('lookITDepartment', 2, '可以觀看資訊部門的權限', NULL, NULL, 1554867890, 1554867890),
('lookITDepartmentBackend', 2, '可以觀看資訊部後端工程師的權限', NULL, NULL, 1554884862, 1554884862),
('lookITDepartmentFrontend', 2, '可以觀看資訊部前端工程師的權限', NULL, NULL, 1554884908, 1554884908),
('lookTimecard', 2, '可以觀看出勤狀況的權限', NULL, NULL, 1554792257, 1554792257),
('lookWork', 2, '可以觀看工作日誌的權限', NULL, NULL, 1554792257, 1554792257),
('出勤管理員', 1, '可以從後台看到每個人的出勤狀況', NULL, NULL, 1554780590, 1554780590),
('工作日誌管理員', 1, '可以從後台看到每個人的工作日誌', NULL, NULL, 1554780590, 1554780590),
('會計部門', 1, '可以看到所有部門的出勤狀況', NULL, NULL, 1554873947, 1554873947),
('美術部管理員', 1, '可以看到美術部員工的出勤和工作狀況', NULL, NULL, 1582167436, 1582167436),
('資訊前端管理員', 1, '可以看到前端工程師的出勤和工作狀況', NULL, NULL, 1554884683, 1554884683),
('資訊後端管理員', 1, '可以看到後端工程師的出勤和工作狀況', NULL, NULL, 1554884716, 1554884716);

-- --------------------------------------------------------

--
-- 資料表結構 `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('BOSS', 'leaveEmployee'),
('BOSS', 'leaveManagement'),
('BOSS', 'lookAll'),
('BOSS', '出勤管理員'),
('BOSS', '工作日誌管理員'),
('出勤管理員', 'lookTimecard'),
('工作日誌管理員', 'lookWork'),
('會計部門', 'lookAll'),
('會計部門', '出勤管理員'),
('美術部管理員', 'lookArtDepartment'),
('美術部管理員', '出勤管理員'),
('美術部管理員', '工作日誌管理員'),
('資訊前端管理員', 'lookITDepartmentFrontend'),
('資訊前端管理員', '出勤管理員'),
('資訊前端管理員', '工作日誌管理員'),
('資訊後端管理員', 'lookITDepartmentBackend'),
('資訊後端管理員', '出勤管理員'),
('資訊後端管理員', '工作日誌管理員');

-- --------------------------------------------------------

--
-- 資料表結構 `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `country`
--

CREATE TABLE `country` (
  `code` char(2) NOT NULL,
  `name` char(52) NOT NULL,
  `population` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `country`
--

INSERT INTO `country` (`code`, `name`, `population`) VALUES
('AU', 'Australia', 18886000),
('BR', 'Brazil', 170115000),
('CA', 'Canada', 1147000),
('CN', 'China', 1277558000),
('DE', 'Germany', 82164700),
('FR', 'France', 59225700),
('GB', 'United Kingdom', 59623400),
('IN', 'India', 1013662000),
('RU', 'Russia', 146934000),
('US', 'United States', 278357000);

-- --------------------------------------------------------

--
-- 資料表結構 `employee`
--

CREATE TABLE `employee` (
  `id` int(20) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(256) NOT NULL,
  `name` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `department` int(1) NOT NULL COMMENT '1=系統部，2=遊戲部，3=美術部',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0=離職，1=在職，2=老闆'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `leave_detail`
--

CREATE TABLE `leave_detail` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL COMMENT '員工編號',
  `type` tinyint(2) UNSIGNED NOT NULL COMMENT '假別：0:公假,1:事假,2:病假,3:特休假',
  `reason` varchar(256) NOT NULL COMMENT '請假原因',
  `start_at` datetime NOT NULL COMMENT '開始時間',
  `end_at` datetime NOT NULL COMMENT '結束時間',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '狀態：0:審核中，1:審核通過，2:審核失敗，3:取消',
  `proxy` varchar(50) NOT NULL COMMENT '工作代理人',
  `approver_id` int(10) UNSIGNED NOT NULL COMMENT '簽核人ID',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='請假明細';

-- --------------------------------------------------------

--
-- 資料表結構 `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1553758645),
('m140506_102106_rbac_init', 1553852483),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1553852483),
('m180523_151638_rbac_updates_indexes_without_prefix', 1553852483);

-- --------------------------------------------------------

--
-- 資料表結構 `roy`
--

CREATE TABLE `roy` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(256) NOT NULL,
  `name` varchar(20) NOT NULL,
  `birthday` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `roy`
--

INSERT INTO `roy` (`id`, `username`, `password`, `name`, `birthday`) VALUES
(1, 'demo', 'demo', 'abcd', '0000-00-00'),
(2, 'aaa', '123456', 'zxc', '2019-03-12'),
(3, 'test', 'test', 'zxc', '0000-00-00'),
(4, 'roy', '123456', 'roy', '2019-03-19'),
(5, 'zxc', 'zxc', 'zxc', '0000-00-00'),
(6, 'bbb', 'bbb', 'bbb', '0000-00-00'),
(8, 'bbbb', 'bbbb', 'b3', '0000-00-00'),
(9, 'm', 'm', 'm', '0000-00-00'),
(10, 'asd', 'zxc', 'qwe', '0000-00-00'),
(11, 'a', '$2y$13$doLtbjxK1HXLlMf97JcsJ.YOZ8BAL0q/f33p6sN1rWHO8hUWJ8DFa', 'a', '0000-00-00'),
(12, 'b', '$2y$13$Puk0c0yattNe7pJE7VQluubc.X4c3yTE3rcDMSk5TtbMSCWG6LKIq', 'b', '0000-00-00'),
(13, 'fgh', '$2y$13$LDYygEX0k8e7u6yLm2cfE.vTciuANM4vw8Rx7EH/0homCmDMgFJSq', 'rty', '0000-00-00');

-- --------------------------------------------------------

--
-- 資料表結構 `time_card`
--

CREATE TABLE `time_card` (
  `employee_id` int(10) UNSIGNED NOT NULL,
  `clock_in_at` datetime NOT NULL,
  `clock_out_at` datetime NOT NULL,
  `late` varchar(100) NOT NULL,
  `status` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `wager`
--

CREATE TABLE `wager` (
  `id` int(20) UNSIGNED NOT NULL,
  `employee_id` int(20) UNSIGNED NOT NULL COMMENT '員工編號',
  `bet` varchar(50) NOT NULL COMMENT '下注內容',
  `status` tinyint(1) NOT NULL COMMENT '0=>下注未開獎，1=>中獎，2=>未中獎',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `wager`
--

INSERT INTO `wager` (`id`, `employee_id`, `bet`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, '22', 2, '2020-10-04 15:00:24', '2020-11-04 15:07:26');

-- --------------------------------------------------------

--
-- 資料表結構 `work_table`
--

CREATE TABLE `work_table` (
  `employee_id` int(10) UNSIGNED NOT NULL,
  `day` date NOT NULL,
  `work_item` varchar(256) NOT NULL,
  `work_finish` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- 資料表索引 `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- 資料表索引 `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- 資料表索引 `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- 資料表索引 `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`code`);

--
-- 資料表索引 `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 資料表索引 `leave_detail`
--
ALTER TABLE `leave_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`,`type`);

--
-- 資料表索引 `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- 資料表索引 `roy`
--
ALTER TABLE `roy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 資料表索引 `time_card`
--
ALTER TABLE `time_card`
  ADD UNIQUE KEY `employee_id` (`employee_id`,`clock_in_at`);

--
-- 資料表索引 `wager`
--
ALTER TABLE `wager`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `work_table`
--
ALTER TABLE `work_table`
  ADD UNIQUE KEY `employee_id_2` (`employee_id`,`day`),
  ADD KEY `employee_id` (`employee_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `leave_detail`
--
ALTER TABLE `leave_detail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `roy`
--
ALTER TABLE `roy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `wager`
--
ALTER TABLE `wager`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的限制式 `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- 資料表的限制式 `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
