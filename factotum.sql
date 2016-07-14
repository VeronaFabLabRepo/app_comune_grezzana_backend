-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Set 07, 2014 alle 18:02
-- Versione del server: 5.5.37-0ubuntu0.13.10.1
-- Versione PHP: 5.5.3-1ubuntu2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `factotum`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('2d2a97914549d002f4d1af1f0ff00a2c', '192.168.56.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.103 Safari/537.36', 1410109374, ''),
('d14c342f135abcb1e2b54d833af81f6b', '192.168.56.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.103 Safari/537.36', 1410112261, 'a:11:{s:9:"user_data";s:0:"";s:7:"id_user";s:1:"2";s:8:"username";s:7:"tommaso";s:6:"status";i:1;s:4:"role";s:5:"admin";s:14:"access_backend";s:1:"1";s:20:"manage_content_types";s:1:"1";s:25:"manage_content_categories";s:1:"1";s:12:"manage_users";s:1:"1";s:12:"capabilities";a:2:{i:0;a:6:{s:2:"id";s:1:"1";s:12:"id_user_role";s:1:"1";s:15:"id_content_type";s:1:"1";s:9:"configure";s:1:"1";s:4:"edit";s:1:"1";s:7:"publish";s:1:"1";}i:1;a:6:{s:2:"id";s:1:"2";s:12:"id_user_role";s:1:"1";s:15:"id_content_type";s:1:"2";s:9:"configure";s:1:"1";s:4:"edit";s:1:"1";s:7:"publish";s:1:"1";}}s:10:"tmp_folder";N;}');

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_categories`
--

CREATE TABLE IF NOT EXISTS `fm_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_content_type` int(11) NOT NULL,
  `category_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `category_label` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_content_type` (`id_content_type`),
  KEY `categoriesIndex` (`category_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `fm_categories`
--

INSERT INTO `fm_categories` (`id`, `id_content_type`, `category_name`, `category_label`) VALUES
(1, 2, 'sports', 'Sports'),
(2, 2, 'economy', 'Economy');

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_contents`
--

CREATE TABLE IF NOT EXISTS `fm_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_content_type` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `status` varchar(16) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `relative_path` varchar(255) COLLATE utf8_bin NOT NULL,
  `absolute_path` varchar(2000) COLLATE utf8_bin DEFAULT NULL,
  `lang` char(2) COLLATE utf8_bin NOT NULL,
  `data_insert` datetime NOT NULL,
  `data_last_update` datetime NOT NULL,
  `order_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_content_type` (`id_content_type`),
  KEY `contentIndex` (`title`,`relative_path`,`order_no`),
  KEY `lang` (`lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;

--
-- Dump dei dati per la tabella `fm_contents`
--

INSERT INTO `fm_contents` (`id`, `id_content_type`, `id_user`, `status`, `title`, `relative_path`, `absolute_path`, `lang`, `data_insert`, `data_last_update`, `order_no`) VALUES
(3, 1, 1, 'offline', 'About Us', 'about-us', 'about-us/', 'en', '0000-00-00 00:00:00', '2014-09-05 16:41:11', 0),
(4, 1, 1, 'live', 'Contact Us', 'contact-us', 'contact-us/', 'en', '0000-00-00 00:00:00', '2014-09-05 00:41:24', 10),
(7, 1, 1, 'live', 'Visit Site', 'visit-site', 'visit-site/', 'en', '2013-09-04 14:16:09', '2014-09-05 00:41:26', 30),
(8, 1, 1, 'live', 'Thank You', 'thank-you', 'thank-you/', 'en', '2013-09-04 16:16:40', '2014-09-05 00:41:25', 20),
(9, 2, 1, 'live', 'Manchester VS Liverpool', 'manchester-vs-liverpool', NULL, '', '2014-04-13 18:06:38', '2014-06-29 11:32:48', NULL),
(10, 2, 1, 'live', 'New increase of productivity', 'new-increase-of-productivity', NULL, '', '2014-04-13 18:10:44', '2014-04-13 18:11:04', NULL),
(11, 1, 1, 'live', 'News', 'news', 'news/', 'en', '2014-04-13 18:11:47', '2014-09-07 18:34:37', 40),
(12, 1, 1, 'live', 'Test', 'test', 'about-us/test/', 'en', '2014-06-29 09:40:59', '2014-09-07 18:13:37', NULL),
(14, 1, 2, 'offline', 'Test 2', 'test', 'about-us/test/test/', 'en', '2014-09-05 16:12:49', '2014-09-07 19:03:10', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_content_attachments`
--

CREATE TABLE IF NOT EXISTS `fm_content_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_content` int(11) NOT NULL,
  `id_content_field` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_bin NOT NULL,
  `filename` varchar(50) COLLATE utf8_bin NOT NULL,
  `order_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_content` (`id_content`),
  KEY `id_content_field` (`id_content_field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_content_categories`
--

CREATE TABLE IF NOT EXISTS `fm_content_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_content` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_content` (`id_content`),
  KEY `id_category` (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `fm_content_categories`
--

INSERT INTO `fm_content_categories` (`id`, `id_content`, `id_category`) VALUES
(1, 10, 2),
(2, 9, 1),
(3, 9, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_content_fields`
--

CREATE TABLE IF NOT EXISTS `fm_content_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_content_type` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `label` varchar(50) COLLATE utf8_bin NOT NULL,
  `type` varchar(50) COLLATE utf8_bin NOT NULL,
  `order_no` int(11) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `hint` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `options` text COLLATE utf8_bin,
  `max_file_size` int(10) DEFAULT NULL,
  `max_image_size` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `image_operation` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `image_bw` tinyint(1) DEFAULT NULL,
  `allowed_types` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `thumb_size` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `linked_id_content_type` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `id_content_type` (`id_content_type`),
  KEY `contentFieldsIndex` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=18 ;

--
-- Dump dei dati per la tabella `fm_content_fields`
--

INSERT INTO `fm_content_fields` (`id`, `id_content_type`, `name`, `label`, `type`, `order_no`, `mandatory`, `hint`, `options`, `max_file_size`, `max_image_size`, `image_operation`, `image_bw`, `allowed_types`, `thumb_size`, `linked_id_content_type`) VALUES
(1, 1, 'text', 'Text', 'xhtml_textarea', 10, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'operation', 'Operation', 'select', 30, 1, 'Choose the page operation', 'text:Show the page content\ncontent:Show a content\ncontent_list:Content List\nlink:Link\naction:Action', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 'main_menu', 'Show in the main menu', 'radio', 0, 1, 'Choose if you want to show the item into the menu (only for pages)', '1:Yes\n0:No', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 1, 'template', 'Template', 'select', 20, 1, NULL, 'basic_content:Basic Page Template\nbasic_content_list:Content List Template\nbasic_news:Basic News Template\nregister:Registration Template\nlogin:Login Template\najax:Ajax Template\ncontact_us:Contact Us Template', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 1, 'content_types_list', 'Content Types List', 'select', 40, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 1, 'content_list_pagination', 'Content List Pagination', 'radio', 70, 0, NULL, 'yes:Yes\nno:No', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 1, 'content_list_order', 'Content List Order', 'select', 90, 0, NULL, 'id_content_asc:Id Content ASC\nid_content_desc:Id Content DESC\ndata_insert_asc:Data Insert ASC\ndata_insert_desc:Data Insert DESC\norder_no_asc:Order No. ASC\norder_no_desc:Order No. DESC\ntitle_asc:Title ASC\ntitle_desc:Title DESC\norder_no_asc_title_asc:Order No. ASC, Title ASC\norder_no_desc_title_desc:Order No. DESC, Title DESC', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, 'link', 'Link', 'text', 100, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 1, 'link_title', 'Link Title', 'text', 110, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, 'link_open_in', 'Link Open In', 'select', 120, 0, NULL, '_self:Same Page\n_blank:New Page/Tab\npopup:Pop Up', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 1, 'action', 'Action', 'text', 130, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 1, 'content_list', 'Content List', 'select', 50, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 2, 'news_article', 'News Article', 'textarea', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 1, 'content_list_num_per_page', 'Content List Number of items per page', 'text', 80, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 1, 'content_list_categories', 'Content List Categories', 'multiselect', 60, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 1, 'parent_page_id', 'Parent Page', 'select', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_content_types`
--

CREATE TABLE IF NOT EXISTS `fm_content_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_type` varchar(50) COLLATE utf8_bin NOT NULL,
  `order_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_type` (`content_type`),
  KEY `contentTypesIndex` (`content_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `fm_content_types`
--

INSERT INTO `fm_content_types` (`id`, `content_type`, `order_no`) VALUES
(1, 'pages', 1),
(2, 'News', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_content_values`
--

CREATE TABLE IF NOT EXISTS `fm_content_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_content_field` int(11) NOT NULL,
  `id_content` int(11) NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_content` (`id_content`),
  KEY `id_content_field` (`id_content_field`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=112 ;

--
-- Dump dei dati per la tabella `fm_content_values`
--

INSERT INTO `fm_content_values` (`id`, `id_content_field`, `id_content`, `value`) VALUES
(1, 3, 3, '1'),
(2, 1, 3, '<p>About Us Page!</p>\n<p>Edited</p>'),
(3, 4, 3, 'basic_content'),
(4, 2, 3, 'text'),
(5, 5, 3, ''),
(6, 13, 3, '0'),
(7, 6, 3, '0'),
(9, 8, 3, ''),
(10, 9, 3, ''),
(11, 10, 3, ''),
(12, 11, 3, ''),
(13, 12, 3, ''),
(14, 3, 4, '1'),
(15, 1, 4, ''),
(16, 4, 4, 'contact_us'),
(17, 2, 4, 'action'),
(18, 5, 4, ''),
(19, 13, 4, '0'),
(20, 6, 4, '0'),
(22, 8, 4, ''),
(23, 9, 4, ''),
(24, 10, 4, ''),
(25, 11, 4, ''),
(26, 12, 4, 'contactUs'),
(27, 3, 7, '1'),
(28, 1, 7, ''),
(29, 4, 7, 'basic_content'),
(30, 2, 7, 'link'),
(31, 5, 7, ''),
(32, 13, 7, '0'),
(33, 6, 7, '0'),
(35, 8, 7, ''),
(36, 9, 7, 'http://www.veronafablab.it'),
(37, 10, 7, ''),
(38, 11, 7, '_blank'),
(39, 12, 7, ''),
(40, 14, 9, 'This is a news of sport.'),
(41, 14, 10, 'This is a news about economy.'),
(42, 3, 11, '1'),
(43, 1, 11, '<p>News list</p>'),
(44, 4, 11, 'basic_news'),
(45, 2, 11, 'content_list'),
(46, 5, 11, 'News'),
(47, 13, 11, '0'),
(48, 6, 11, 'yes'),
(50, 8, 11, 'title_desc'),
(51, 9, 11, ''),
(52, 10, 11, ''),
(53, 11, 11, ''),
(54, 12, 11, ''),
(55, 15, 11, '1'),
(56, 3, 12, '1'),
(57, 1, 12, '<p>test</p>'),
(58, 4, 12, 'basic_news'),
(59, 2, 12, 'content'),
(60, 5, 12, 'News'),
(61, 13, 12, '10'),
(62, 6, 12, '0'),
(63, 15, 12, ''),
(64, 8, 12, ''),
(65, 9, 12, ''),
(66, 10, 12, ''),
(67, 11, 12, ''),
(68, 12, 12, ''),
(69, 15, 3, ''),
(70, 16, 11, '1,2'),
(71, 17, 12, '3'),
(72, 16, 12, ''),
(73, 17, 11, ''),
(74, 17, 3, ''),
(75, 16, 3, ''),
(76, 17, 4, ''),
(77, 16, 4, ''),
(78, 15, 4, ''),
(79, 17, 8, ''),
(80, 3, 8, '0'),
(81, 1, 8, ''),
(82, 4, 8, 'basic_content'),
(83, 2, 8, 'text'),
(84, 5, 8, ''),
(85, 13, 8, '0'),
(86, 16, 8, ''),
(87, 6, 8, '0'),
(88, 15, 8, ''),
(89, 8, 8, ''),
(90, 9, 8, ''),
(91, 10, 8, ''),
(92, 11, 8, ''),
(93, 12, 8, ''),
(94, 17, 7, ''),
(95, 16, 7, ''),
(96, 15, 7, ''),
(97, 17, 14, '12'),
(98, 3, 14, '1'),
(99, 1, 14, '<p>Vinooooh</p>'),
(100, 4, 14, 'register'),
(101, 2, 14, 'text'),
(102, 5, 14, ''),
(103, 13, 14, '0'),
(104, 16, 14, ''),
(105, 6, 14, '0'),
(106, 15, 14, ''),
(107, 8, 14, ''),
(108, 9, 14, ''),
(109, 10, 14, ''),
(110, 11, 14, ''),
(111, 12, 14, '');

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_login_attempts`
--

CREATE TABLE IF NOT EXISTS `fm_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_users`
--

CREATE TABLE IF NOT EXISTS `fm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_role` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user_role` (`id_user_role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `fm_users`
--

INSERT INTO `fm_users` (`id`, `id_user_role`, `username`, `password`, `email`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`) VALUES
(1, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'info@veronafablab.it', 1, 0, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', '2014-08-05 18:31:41', '2013-08-29 10:28:01', '2014-08-05 14:31:41');

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_user_autologins`
--

CREATE TABLE IF NOT EXISTS `fm_user_autologins` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `fm_user_autologins`
--

INSERT INTO `fm_user_autologins` (`key_id`, `user_id`, `user_agent`, `last_ip`, `last_login`) VALUES
('67edeb2de13885cb96e49af2bee96c2e', 2, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.143 Safari/537.36', '192.168.56.1', '2014-08-16 15:26:05'),
('6d5a8419363322e437142ccac41af385', 1, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36', '192.168.56.1', '2014-08-15 00:27:18'),
('7a82a362bd1f8b9102568f870245e640', 2, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.103 Safari/537.36', '192.168.56.1', '2014-09-07 16:10:17');

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_user_capabilities`
--

CREATE TABLE IF NOT EXISTS `fm_user_capabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_role` int(11) NOT NULL,
  `id_content_type` int(11) NOT NULL,
  `configure` tinyint(1) NOT NULL,
  `edit` tinyint(1) NOT NULL,
  `publish` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_role` (`id_user_role`),
  KEY `id_content_type` (`id_content_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `fm_user_capabilities`
--

INSERT INTO `fm_user_capabilities` (`id`, `id_user_role`, `id_content_type`, `configure`, `edit`, `publish`) VALUES
(1, 1, 1, 1, 1, 1),
(2, 1, 2, 1, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_user_profiles`
--

CREATE TABLE IF NOT EXISTS `fm_user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `firstname` varchar(50) COLLATE utf8_bin NOT NULL,
  `lastname` varchar(255) COLLATE utf8_bin NOT NULL,
  `dob` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `fm_user_profiles`
--

INSERT INTO `fm_user_profiles` (`id`, `id_user`, `firstname`, `lastname`, `dob`) VALUES
(1, 1, 'Verona', 'FabLab', '1989-03-16');

-- --------------------------------------------------------

--
-- Struttura della tabella `fm_user_roles`
--

CREATE TABLE IF NOT EXISTS `fm_user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) COLLATE utf8_bin NOT NULL,
  `backend_access` tinyint(1) NOT NULL,
  `manage_content_types` tinyint(1) NOT NULL,
  `manage_users` tinyint(1) NOT NULL,
  `manage_content_categories` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `fm_user_roles`
--

INSERT INTO `fm_user_roles` (`id`, `role`, `backend_access`, `manage_content_types`, `manage_users`, `manage_content_categories`) VALUES
(1, 'admin', 1, 1, 1, 1);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `fm_categories`
--
ALTER TABLE `fm_categories`
  ADD CONSTRAINT `fm_categories_ibfk_1` FOREIGN KEY (`id_content_type`) REFERENCES `fm_content_types` (`id`);

--
-- Limiti per la tabella `fm_contents`
--
ALTER TABLE `fm_contents`
  ADD CONSTRAINT `fm_contents_ibfk_1` FOREIGN KEY (`id_content_type`) REFERENCES `fm_content_types` (`id`);

--
-- Limiti per la tabella `fm_content_attachments`
--
ALTER TABLE `fm_content_attachments`
  ADD CONSTRAINT `fm_content_attachments_ibfk_1` FOREIGN KEY (`id_content`) REFERENCES `fm_contents` (`id`),
  ADD CONSTRAINT `fm_content_attachments_ibfk_2` FOREIGN KEY (`id_content_field`) REFERENCES `fm_content_fields` (`id`);

--
-- Limiti per la tabella `fm_content_categories`
--
ALTER TABLE `fm_content_categories`
  ADD CONSTRAINT `fm_content_categories_ibfk_1` FOREIGN KEY (`id_content`) REFERENCES `fm_contents` (`id`),
  ADD CONSTRAINT `fm_content_categories_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `fm_categories` (`id`);

--
-- Limiti per la tabella `fm_content_fields`
--
ALTER TABLE `fm_content_fields`
  ADD CONSTRAINT `fm_content_fields_ibfk_1` FOREIGN KEY (`id_content_type`) REFERENCES `fm_content_types` (`id`);

--
-- Limiti per la tabella `fm_content_values`
--
ALTER TABLE `fm_content_values`
  ADD CONSTRAINT `fm_content_values_ibfk_1` FOREIGN KEY (`id_content`) REFERENCES `fm_contents` (`id`),
  ADD CONSTRAINT `fm_content_values_ibfk_2` FOREIGN KEY (`id_content_field`) REFERENCES `fm_content_fields` (`id`);

--
-- Limiti per la tabella `fm_users`
--
ALTER TABLE `fm_users`
  ADD CONSTRAINT `fm_users_ibfk_1` FOREIGN KEY (`id_user_role`) REFERENCES `fm_user_roles` (`id`);

--
-- Limiti per la tabella `fm_user_capabilities`
--
ALTER TABLE `fm_user_capabilities`
  ADD CONSTRAINT `fm_user_capabilities_ibfk_1` FOREIGN KEY (`id_user_role`) REFERENCES `fm_user_roles` (`id`),
  ADD CONSTRAINT `fm_user_capabilities_ibfk_2` FOREIGN KEY (`id_content_type`) REFERENCES `fm_content_types` (`id`);

--
-- Limiti per la tabella `fm_user_profiles`
--
ALTER TABLE `fm_user_profiles`
  ADD CONSTRAINT `fm_user_profiles_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `fm_users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
