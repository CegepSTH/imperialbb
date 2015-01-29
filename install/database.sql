-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 29, 2015 at 09:34 AM
-- Server version: 5.5.41
-- PHP Version: 5.3.10-1ubuntu3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `imperialbb`
--
CREATE DATABASE `imperialbb` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `imperialbb`;

-- --------------------------------------------------------

--
-- Table structure for table `ibb_bbcode`
--

CREATE TABLE IF NOT EXISTS `ibb_bbcode` (
  `bbcode_id` tinyint(5) NOT NULL AUTO_INCREMENT,
  `bbcode_search` varchar(225) NOT NULL DEFAULT '',
  `bbcode_replace` varchar(225) NOT NULL DEFAULT '',
  `bbcode_begin_ext` varchar(225) NOT NULL DEFAULT '',
  `bbcode_end_ext` varchar(225) NOT NULL DEFAULT '',
  `bbcode_name` varchar(225) NOT NULL DEFAULT '',
  `bbcode_type` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`bbcode_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ibb_bbcode`
--

INSERT INTO `ibb_bbcode` (`bbcode_id`, `bbcode_search`, `bbcode_replace`, `bbcode_begin_ext`, `bbcode_end_ext`, `bbcode_name`, `bbcode_type`) VALUES
(1, 'Bb', 'b', '', '', 'Bold', 1),
(2, 'Ii', 'i', '', '', 'Italics', 1),
(3, 'Uu', 'u', '', '', 'Underline', 1),
(4, 'url', 'a', 'href="', '" target="blank"', 'Link', 2),
(5, 'img', 'img', 'src="', '"', 'Image', 3),
(6, 'Cc', 'center', '', '', 'Center', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ibb_categories`
--

CREATE TABLE IF NOT EXISTS `ibb_categories` (
  `cat_id` int(8) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(225) NOT NULL DEFAULT '',
  `cat_orderby` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ibb_categories`
--

INSERT INTO `ibb_categories` (`cat_id`, `cat_name`, `cat_orderby`) VALUES
(1, 'Test Category', 1)

-- --------------------------------------------------------

--
-- Table structure for table `ibb_config`
--

CREATE TABLE IF NOT EXISTS `ibb_config` (
  `config_id` int(8) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(40) NOT NULL DEFAULT '',
  `config_value` text NOT NULL,
  `config_protected` tinyint(5) NOT NULL DEFAULT '0',
  `config_type` varchar(20) NOT NULL DEFAULT '',
  `config_orderby` int(8) NOT NULL DEFAULT '0',
  `config_category_orderby` tinyint(5) NOT NULL DEFAULT '0',
  `config_category` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `ibb_config`
--

INSERT INTO `ibb_config` (`config_id`, `config_name`, `config_value`, `config_protected`, `config_type`, `config_orderby`, `config_category_orderby`, `config_category`) VALUES
(1, 'site_name', 'Nom du site', 0, 'textbox', 1, 1, 'General_Configuration'),
(2, 'site_desc', 'Ceci est une description', 0, 'textarea', 2, 1, 'General_Configuration'),
(3, 'footer', 'Test footer', 0, 'textarea', 4, 1, 'General_Configuration'),
(4, 'admin_email', 'webmaster@yourdomain.com', 0, 'textbox', 1, 5, 'Email_Configuration'),
(5, 'board_offline', '0', 0, 'true/false', 1, 2, 'Offline_Configuration'),
(6, 'url', 'http://www.google.ca/', 0, 'textbox', 3, 1, 'General_Configuration'),
(7, 'use_smtp', '0', 0, 'true/false', 2, 5, 'Email_Configuration'),
(8, 'smtp_host', 'localhost', 0, 'textbox', 3, 5, 'Email_Configuration'),
(9, 'smtp_user', '', 0, 'textbox', 4, 5, 'Email_Configuration'),
(10, 'smtp_pass', '', 0, 'password', 5, 5, 'Email_Configuration'),
(11, 'version', '2.2.2', 0, '', 0, 0, ''),
(12, 'ftp_user', 'ibb_dev', 1, 'textbox', 1, 6, 'FTP_Configuration'),
(13, 'ftp_pass', 'UGFzc3dvcmQ=', 1, 'password', 2, 6, 'FTP_Configuration'),
(14, 'ftp_path', '/', 1, 'textbox', 3, 6, 'FTP_Configuration'),
(15, 'offline_message', 'Test', 0, 'textarea', 2, 2, 'Offline_Configuration'),
(16, 'default_template', '1', 0, 'dropdown:template', 1, 7, 'Template_Configuration'),
(17, 'register_auth_type', '0', 0, 'true/false', 5, 1, 'General_Configuration'),
(18, 'timezone', '0', 0, 'dropdown:timezone', 6, 1, 'General_Configuration'),
(19, 'topics_per_page', '50', 0, 'textbox', 1, 3, 'Post_Configuration'),
(20, 'posts_per_page', '15', 0, 'textbox', 2, 3, 'Post_Configuration'),
(21, 'default_language', '1', 0, 'dropdown:language', 1, 8, 'Language_Configuration'),
(22, 'allow_uploaded_avatar', '1', 0, 'true/false', 1, 4, 'Avatar_Configuration'),
(23, 'allow_remote_avatar', '1', 0, 'true/false', 2, 4, 'Avatar_Configuration'),
(24, 'avatar_upload_dir', 'images/avatars/uploads', 0, 'textbox', 3, 4, 'Avatar_Configuration'),
(25, 'html_enabled', '0', 0, 'true/false', 3, 3, 'Post_Configuration'),
(26, 'bbcode_enabled', '1', 0, 'true/false', 4, 3, 'Post_Configuration'),
(27, 'smilies_enabled', '1', 0, 'true/false', 5, 3, 'Post_Configuration'),
(28, 'smilies_url', 'images/smilies', 0, 'textbox', 6, 3, 'Post_Configuration'),
(29, 'avatar_max_upload_size', '5120', 0, 'textbox', 4, 4, 'Avatar_Configuration'),
(30, 'avatar_max_upload_width', '150', 0, 'textbox', 5, 4, 'Avatar_Configuration'),
(31, 'avatar_max_upload_height', '150', 0, 'textbox', 6, 4, 'Avatar_Configuration'),
(32, 'allow_vote_after_results', '0', 0, 'true/false', 7, 3, 'Post_Configuration'),
(33, 'avatar_upload_mime_types', 'image/bmp;image/png;image/jpeg;image/gif;', 0, 'textbox', 7, 4, 'Avatar_Configuration'),
(34, 'forum_root', '', 0, '', 0, 0, ''),
(35, 'admincp_notepad', 'I&#039;m watching you Michael. Fuckerino. &amp;&amp;&amp;rnTest', 0, 'textbox', 0, 0, ''),
(36, 'censor_enabled', '0', 0, 'true/false', 8, 3, 'Post_Configuration'),
(37, 'paginate_pernum', '10', 0, 'textbox', 0, 0, 'General_Configuration');

-- --------------------------------------------------------

--
-- Table structure for table `ibb_forums`
--

CREATE TABLE IF NOT EXISTS `ibb_forums` (
  `forum_id` int(8) NOT NULL AUTO_INCREMENT,
  `forum_cat_id` int(8) NOT NULL DEFAULT '0',
  `forum_type` set('c','f') NOT NULL DEFAULT 'c',
  `forum_name` varchar(100) NOT NULL DEFAULT '',
  `forum_description` varchar(200) NOT NULL DEFAULT '',
  `forum_topics` int(8) NOT NULL DEFAULT '0',
  `forum_posts` int(8) NOT NULL DEFAULT '0',
  `forum_last_post` int(8) NOT NULL DEFAULT '0',
  `forum_read` tinyint(5) NOT NULL DEFAULT '1',
  `forum_post` tinyint(5) NOT NULL DEFAULT '1',
  `forum_reply` tinyint(5) NOT NULL DEFAULT '1',
  `forum_poll` tinyint(5) NOT NULL DEFAULT '1',
  `forum_create_poll` tinyint(5) NOT NULL DEFAULT '1',
  `forum_mod` tinyint(5) NOT NULL DEFAULT '4',
  `forum_orderby` mediumint(8) NOT NULL DEFAULT '0',
  `forum_redirect_url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`forum_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `ibb_forums`
--

INSERT INTO `ibb_forums` (`forum_id`, `forum_cat_id`, `forum_type`, `forum_name`, `forum_description`, `forum_topics`, `forum_posts`, `forum_last_post`, `forum_read`, `forum_post`, `forum_reply`, `forum_poll`, `forum_create_poll`, `forum_mod`, `forum_orderby`, `forum_redirect_url`) VALUES
(9, 1, 'c', 'Wat', '', 3, 5, 13, 1, 1, 1, 1, 1, 4, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ibb_languages`
--

CREATE TABLE IF NOT EXISTS `ibb_languages` (
  `language_id` int(8) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(100) NOT NULL DEFAULT '',
  `language_folder` varchar(100) NOT NULL DEFAULT '',
  `language_usable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ibb_languages`
--

INSERT INTO `ibb_languages` (`language_id`, `language_name`, `language_folder`, `language_usable`) VALUES
(1, 'English', 'english', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ibb_mods`
--

CREATE TABLE IF NOT EXISTS `ibb_mods` (
  `mod_id` varchar(75) NOT NULL DEFAULT '',
  `mod_template_updates` text NOT NULL,
  `mod_version` varchar(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ibb_pm`
--

CREATE TABLE IF NOT EXISTS `ibb_pm` (
  `pm_id` int(8) NOT NULL AUTO_INCREMENT,
  `pm_title` varchar(80) NOT NULL DEFAULT '',
  `pm_body` text NOT NULL,
  `pm_send_to` int(8) NOT NULL DEFAULT '0',
  `pm_sent_from` int(8) NOT NULL DEFAULT '0',
  `pm_type` tinyint(5) NOT NULL DEFAULT '1',
  `pm_unread` tinyint(5) NOT NULL DEFAULT '0',
  `pm_date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `ibb_pollvotes`
--

CREATE TABLE IF NOT EXISTS `ibb_pollvotes` (
  `poll_topic_id` int(11) NOT NULL DEFAULT '0',
  `poll_choice_id` tinyint(5) NOT NULL DEFAULT '0',
  `poll_choice_name` varchar(50) NOT NULL DEFAULT '',
  `poll_choice_votes` int(11) NOT NULL DEFAULT '0',
  KEY `poll_topic_id` (`poll_topic_id`),
  KEY `poll_choice_id` (`poll_choice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ibb_posts`
--

CREATE TABLE IF NOT EXISTS `ibb_posts` (
  `post_id` int(8) NOT NULL AUTO_INCREMENT,
  `post_topic_id` int(8) NOT NULL DEFAULT '0',
  `post_user_id` int(8) NOT NULL DEFAULT '0',
  `post_text` text NOT NULL,
  `post_timestamp` int(11) DEFAULT NULL,
  `post_disable_html` tinyint(1) NOT NULL DEFAULT '0',
  `post_disable_bbcode` tinyint(1) NOT NULL DEFAULT '0',
  `post_disable_smilies` tinyint(1) NOT NULL DEFAULT '0',
  `post_attach_signature` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ibb_posts`
--

INSERT INTO `ibb_posts` (`post_id`, `post_topic_id`, `post_user_id`, `post_text`, `post_timestamp`, `post_disable_html`, `post_disable_bbcode`, `post_disable_smilies`, `post_attach_signature`) VALUES
(1, 3, 1, 'test', 1422030719, 0, 0, 0, 1),

-- --------------------------------------------------------

--
-- Table structure for table `ibb_ranks`
--

CREATE TABLE IF NOT EXISTS `ibb_ranks` (
  `rank_id` int(8) NOT NULL AUTO_INCREMENT,
  `rank_orderby` tinyint(5) NOT NULL DEFAULT '0',
  `rank_name` varchar(80) NOT NULL DEFAULT '',
  `rank_image` varchar(100) NOT NULL DEFAULT '',
  `rank_minimum_posts` int(11) NOT NULL DEFAULT '0',
  `rank_special` tinyint(1) NOT NULL DEFAULT '0',
  `rank_color` varchar(15) NOT NULL DEFAULT '',
  `rank_bold` tinyint(5) NOT NULL DEFAULT '0',
  `rank_underline` tinyint(5) NOT NULL DEFAULT '0',
  `rank_italics` tinyint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rank_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ibb_ranks`
--

INSERT INTO `ibb_ranks` (`rank_id`, `rank_orderby`, `rank_name`, `rank_image`, `rank_minimum_posts`, `rank_special`, `rank_color`, `rank_bold`, `rank_underline`, `rank_italics`) VALUES
(1, 3, 'Member', '', 0, 0, 'black', 0, 1, 0),
(2, 2, 'Moderator', '', 0, 1, '#0033FF', 1, 1, 0),
(3, 1, 'Admin', '', 0, 1, '#018157', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ibb_sessions`
--

CREATE TABLE IF NOT EXISTS `ibb_sessions` (
  `session_id` varchar(225) NOT NULL DEFAULT '',
  `ip` varchar(225) NOT NULL DEFAULT 'x.x.x.x',
  `user_id` varchar(225) NOT NULL DEFAULT '',
  `time` varchar(225) NOT NULL DEFAULT '',
  `time_created` varchar(225) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ibb_smilies`
--

CREATE TABLE IF NOT EXISTS `ibb_smilies` (
  `smilie_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `smilie_code` varchar(50) DEFAULT NULL,
  `smilie_url` varchar(100) DEFAULT NULL,
  `smilie_name` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`smilie_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `ibb_smilies`
--

INSERT INTO `ibb_smilies` (`smilie_id`, `smilie_code`, `smilie_url`, `smilie_name`) VALUES
(1, ':D', 'icon_biggrin.gif', 'Very Happy'),
(2, ':-D', 'icon_biggrin.gif', 'Very Happy'),
(3, ':grin:', 'icon_biggrin.gif', 'Very Happy'),
(4, ':)', 'icon_smile.gif', 'Smile'),
(5, ':-)', 'icon_smile.gif', 'Smile'),
(6, ':smile:', 'icon_smile.gif', 'Smile'),
(7, ':(', 'icon_sad.gif', 'Sad'),
(8, ':-(', 'icon_sad.gif', 'Sad'),
(9, ':sad:', 'icon_sad.gif', 'Sad'),
(10, ':o', 'icon_surprised.gif', 'Surprised'),
(11, ':-o', 'icon_surprised.gif', 'Surprised'),
(12, ':eek:', 'icon_surprised.gif', 'Surprised'),
(13, ':shock:', 'icon_eek.gif', 'Shocked'),
(14, ':?', 'icon_confused.gif', 'Confused'),
(15, ':-?', 'icon_confused.gif', 'Confused'),
(16, ':???:', 'icon_confused.gif', 'Confused'),
(17, '8)', 'icon_cool.gif', 'Cool'),
(18, '8-)', 'icon_cool.gif', 'Cool'),
(19, ':cool:', 'icon_cool.gif', 'Cool'),
(20, ':lol:', 'icon_lol.gif', 'Laughing'),
(21, ':x', 'icon_mad.gif', 'Mad'),
(22, ':-x', 'icon_mad.gif', 'Mad'),
(23, ':mad:', 'icon_mad.gif', 'Mad'),
(24, ':P', 'icon_razz.gif', 'Razz'),
(25, ':-P', 'icon_razz.gif', 'Razz'),
(26, ':razz:', 'icon_razz.gif', 'Razz'),
(27, ':oops:', 'icon_redface.gif', 'Embarassed'),
(28, ':cry:', 'icon_cry.gif', 'Crying or Very sad'),
(29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad'),
(30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil'),
(31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes'),
(32, ':wink:', 'icon_wink.gif', 'Wink'),
(33, ';)', 'icon_wink.gif', 'Wink'),
(34, ';-)', 'icon_wink.gif', 'Wink'),
(35, ':!:', 'icon_exclaim.gif', 'Exclamation'),
(36, ':?:', 'icon_question.gif', 'Question'),
(37, ':idea:', 'icon_idea.gif', 'Idea'),
(38, ':arrow:', 'icon_arrow.gif', 'Arrow'),
(39, ':|', 'icon_neutral.gif', 'Neutral'),
(40, ':-|', 'icon_neutral.gif', 'Neutral'),
(41, ':neutral:', 'icon_neutral.gif', 'Neutral'),
(42, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green');

-- --------------------------------------------------------

--
-- Table structure for table `ibb_templates`
--

CREATE TABLE IF NOT EXISTS `ibb_templates` (
  `template_id` int(8) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(225) NOT NULL DEFAULT '',
  `template_folder` varchar(225) NOT NULL DEFAULT '',
  `template_usable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ibb_templates`
--

INSERT INTO `ibb_templates` (`template_id`, `template_name`, `template_folder`, `template_usable`) VALUES
(1, 'Original', 'original', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ibb_topic_subscriptions`
--

CREATE TABLE IF NOT EXISTS `ibb_topic_subscriptions` (
  `topic_subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_subscription_user_id` int(11) NOT NULL DEFAULT '0',
  `topic_subscription_topic_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`topic_subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ibb_topics`
--

CREATE TABLE IF NOT EXISTS `ibb_topics` (
  `topic_id` int(8) NOT NULL AUTO_INCREMENT,
  `topic_forum_id` int(8) NOT NULL DEFAULT '0',
  `topic_title` varchar(75) NOT NULL DEFAULT '',
  `topic_poll_title` varchar(75) NOT NULL DEFAULT '',
  `topic_status` tinyint(3) NOT NULL DEFAULT '0',
  `topic_type` tinyint(3) NOT NULL DEFAULT '0',
  `topic_first_post` int(8) NOT NULL DEFAULT '0',
  `topic_user_id` int(8) NOT NULL DEFAULT '0',
  `topic_replies` int(8) NOT NULL DEFAULT '0',
  `topic_views` int(8) NOT NULL DEFAULT '0',
  `topic_last_post` int(8) NOT NULL DEFAULT '0',
  `topic_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`topic_id`),
  KEY `topic_type` (`topic_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ibb_topics`
--

INSERT INTO `ibb_topics` (`topic_id`, `topic_forum_id`, `topic_title`, `topic_poll_title`, `topic_status`, `topic_type`, `topic_first_post`, `topic_user_id`, `topic_replies`, `topic_views`, `topic_last_post`, `topic_time`) VALUES
(1, 9, 'test', '', 0, 0, 11, -1, 0, 3, 11, 1422467028),

-- --------------------------------------------------------

--
-- Table structure for table `ibb_ug_auth`
--

CREATE TABLE IF NOT EXISTS `ibb_ug_auth` (
  `usergroup` int(8) NOT NULL DEFAULT '0',
  `ug_forum_id` int(8) NOT NULL DEFAULT '0',
  `ug_read` tinyint(5) NOT NULL DEFAULT '0',
  `ug_post` tinyint(5) NOT NULL DEFAULT '0',
  `ug_reply` tinyint(5) NOT NULL DEFAULT '0',
  `ug_create_poll` tinyint(5) NOT NULL DEFAULT '0',
  `ug_poll` tinyint(5) NOT NULL DEFAULT '0',
  `ug_mod` tinyint(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ibb_upgrades`
--

CREATE TABLE IF NOT EXISTS `ibb_upgrades` (
  `upgrade_version` varchar(10) NOT NULL DEFAULT '',
  `upgrade_template_updates` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ibb_usergroups`
--

CREATE TABLE IF NOT EXISTS `ibb_usergroups` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL DEFAULT '',
  `desc` varchar(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `ibb_users`
--

CREATE TABLE IF NOT EXISTS `ibb_users` (
  `user_id` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(225) NOT NULL DEFAULT '',
  `user_password` varchar(225) NOT NULL DEFAULT '',
  `user_email` varchar(225) NOT NULL DEFAULT '',
  `user_date_joined` int(11) NOT NULL DEFAULT '0',
  `user_lastvisit` int(11) NOT NULL DEFAULT '0',
  `user_level` tinyint(5) NOT NULL DEFAULT '0',
  `user_usergroup` int(8) NOT NULL DEFAULT '0',
  `user_signature` text NOT NULL,
  `user_rank` int(8) NOT NULL DEFAULT '0',
  `user_aim` varchar(225) NOT NULL DEFAULT '',
  `user_icq` varchar(225) NOT NULL DEFAULT '',
  `user_msn` varchar(225) NOT NULL DEFAULT '',
  `user_yahoo` varchar(225) NOT NULL DEFAULT '',
  `user_email_on_pm` tinyint(5) NOT NULL DEFAULT '0',
  `user_template` int(8) NOT NULL DEFAULT '1',
  `user_language` int(8) NOT NULL DEFAULT '1',
  `user_timezone` char(3) NOT NULL DEFAULT '0',
  `user_posts` int(8) NOT NULL DEFAULT '0',
  `user_activation_key` varchar(225) NOT NULL DEFAULT '',
  `user_location` varchar(225) NOT NULL DEFAULT '',
  `user_website` varchar(225) NOT NULL DEFAULT '',
  `user_avatar_type` int(4) NOT NULL DEFAULT '0',
  `user_avatar_location` varchar(75) NOT NULL DEFAULT '',
  `user_avatar_dimensions` varchar(11) NOT NULL DEFAULT '',
  `user_password_reset_request` int(11) NOT NULL DEFAULT '0',
  `user_new_password` varchar(50) NOT NULL DEFAULT '',
  `user_birthday` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ibb_users`
--

INSERT INTO `ibb_users` (`user_id`, `username`, `user_password`, `user_email`, `user_date_joined`, `user_lastvisit`, `user_level`, `user_usergroup`, `user_signature`, `user_rank`, `user_aim`, `user_icq`, `user_msn`, `user_yahoo`, `user_email_on_pm`, `user_template`, `user_language`, `user_timezone`, `user_posts`, `user_activation_key`, `user_location`, `user_website`, `user_avatar_type`, `user_avatar_location`, `user_avatar_dimensions`, `user_password_reset_request`, `user_new_password`, `user_birthday`) VALUES
(-1, 'Guest', '', '', 0, 1422464343, 1, 0, '', 1, '', '', '', '', 0, 1, 1, '0', 0, '', '', '', 0, '', '', 0, '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
