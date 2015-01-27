-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_bbcode`
-- 

CREATE TABLE `ibb_bbcode` (
  `bbcode_id` tinyint(5) NOT NULL auto_increment,
  `bbcode_search` varchar(225) NOT NULL default '',
  `bbcode_replace` varchar(225) NOT NULL default '',
  `bbcode_begin_ext` varchar(225) NOT NULL default '',
  `bbcode_end_ext` varchar(225) NOT NULL default '',
  `bbcode_name` varchar(225) NOT NULL default '',
  `bbcode_type` tinyint(2) NOT NULL default '1',
  PRIMARY KEY  (`bbcode_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `ibb_bbcode`
-- 

INSERT INTO `ibb_bbcode` VALUES (1, 'Bb', 'b', '', '', 'Bold', 1);
INSERT INTO `ibb_bbcode` VALUES (2, 'Ii', 'i', '', '', 'Italics', 1);
INSERT INTO `ibb_bbcode` VALUES (3, 'Uu', 'u', '', '', 'Underline', 1);
INSERT INTO `ibb_bbcode` VALUES (4, 'url', 'a', 'href="', '" target="blank"', 'Link', 2);
INSERT INTO `ibb_bbcode` VALUES (5, 'img', 'img', 'src="', '"', 'Image', 3);
INSERT INTO `ibb_bbcode` VALUES (6, 'Cc', 'center', '', '', 'Center', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_categories`
-- 

CREATE TABLE `ibb_categories` (
  `cat_id` int(8) NOT NULL auto_increment,
  `cat_name` varchar(225) NOT NULL default '',
  `cat_orderby` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ibb_categories`
--

INSERT INTO `ibb_categories` VALUES (1, 'Test Category', 1); 

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_config`
-- 

CREATE TABLE `ibb_config` (
  `config_id` int(8) NOT NULL auto_increment,
  `config_name` varchar(40) NOT NULL default '',
  `config_value` text NOT NULL,
  `config_protected` tinyint(5) NOT NULL default '0',
  `config_type` varchar(20) NOT NULL default '',
  `config_orderby` int(8) NOT NULL default '0',
  `config_category_orderby` tinyint(5) NOT NULL default '0',
  `config_category` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=35 ;

-- 
-- Dumping data for table `ibb_config`
-- 

INSERT INTO `ibb_config` VALUES (1, 'site_name', 'ImperialBB Forums', 0, 'textbox', 1, 1, 'General_Configuration');
INSERT INTO `ibb_config` VALUES (2, 'site_desc', 'Advanced Forum Software Solutions', 0, 'textarea', 2, 1, 'General_Configuration');
INSERT INTO `ibb_config` VALUES (3, 'footer', '', 0, 'textarea', 4, 1, 'General_Configuration');
INSERT INTO `ibb_config` VALUES (4, 'admin_email', 'webmaster@yourdomain.com', 0, 'textbox', 1, 5, 'Email_Configuration');
INSERT INTO `ibb_config` VALUES (5, 'board_offline', '0', 0, 'true/false', 1, 2, 'Offline_Configuration');
INSERT INTO `ibb_config` VALUES (6, 'url', 'http://www.yourdomain.com/forum', 0, 'textbox', 3, 1, 'General_Configuration');
INSERT INTO `ibb_config` VALUES (7, 'use_smtp', '1', 0, 'true/false', 2, 5, 'Email_Configuration');
INSERT INTO `ibb_config` VALUES (8, 'smtp_host', 'localhost', 0, 'textbox', 3, 5, 'Email_Configuration');
INSERT INTO `ibb_config` VALUES (9, 'smtp_user', '', 0, 'textbox', 4, 5, 'Email_Configuration');
INSERT INTO `ibb_config` VALUES (10, 'smtp_pass', '', 0, 'password', 5, 5, 'Email_Configuration');
INSERT INTO `ibb_config` VALUES (11, 'version', '2.2.2', 0, '', 0, 0, '');
INSERT INTO `ibb_config` VALUES (12, 'ftp_user', 'ibb_dev', 1, 'textbox', 1, 6, 'FTP_Configuration');
INSERT INTO `ibb_config` VALUES (13, 'ftp_pass', 'UGFzc3dvcmQ=', 1, 'password', 2, 6, 'FTP_Configuration');
INSERT INTO `ibb_config` VALUES (14, 'ftp_path', '/', 1, 'textbox', 3, 6, 'FTP_Configuration');
INSERT INTO `ibb_config` VALUES (15, 'offline_message', 'This board is currently offline', 0, 'textarea', 2, 2, 'Offline_Configuration');
INSERT INTO `ibb_config` VALUES (16, 'default_template', '1', 0, 'dropdown:template', 1, 7, 'Template_Configuration');
INSERT INTO `ibb_config` VALUES (17, 'register_auth_type', '1', 0, 'true/false', 5, 1, 'General_Configuration');
INSERT INTO `ibb_config` VALUES (18, 'timezone', '0', 0, 'dropdown:timezone', 6, 1, 'General_Configuration');
INSERT INTO `ibb_config` VALUES (19, 'topics_per_page', '50', 0, 'textbox', 1, 3, 'Post_Configuration');
INSERT INTO `ibb_config` VALUES (20, 'posts_per_page', '15', 0, 'textbox', 2, 3, 'Post_Configuration');
INSERT INTO `ibb_config` VALUES (21, 'default_language', '1', 0, 'dropdown:language', 1, 8, 'Language_Configuration');
INSERT INTO `ibb_config` VALUES (22, 'allow_uploaded_avatar', '1', 0, 'true/false', 1, 4, 'Avatar_Configuration');
INSERT INTO `ibb_config` VALUES (23, 'allow_remote_avatar', '1', 0, 'true/false', 2, 4, 'Avatar_Configuration');
INSERT INTO `ibb_config` VALUES (24, 'avatar_upload_dir', 'images/avatars/uploads', 0, 'textbox', 3, 4, 'Avatar_Configuration');
INSERT INTO `ibb_config` VALUES (25, 'html_enabled', '0', 0, 'true/false', 3, 3, 'Post_Configuration');
INSERT INTO `ibb_config` VALUES (26, 'bbcode_enabled', '1', 0, 'true/false', 4, 3, 'Post_Configuration');
INSERT INTO `ibb_config` VALUES (27, 'smilies_enabled', '1', 0, 'true/false', 5, 3, 'Post_Configuration');
INSERT INTO `ibb_config` VALUES (28, 'smilies_url', 'images/smilies', 0, 'textbox', 6, 3, 'Post_Configuration');
INSERT INTO `ibb_config` VALUES (29, 'avatar_max_upload_size', '5120', 0, 'textbox', 4, 4, 'Avatar_Configuration');
INSERT INTO `ibb_config` VALUES (30, 'avatar_max_upload_width', '150', 0, 'textbox', 5, 4, 'Avatar_Configuration');
INSERT INTO `ibb_config` VALUES (31, 'avatar_max_upload_height', '150', 0, 'textbox', 6, 4, 'Avatar_Configuration');
INSERT INTO `ibb_config` VALUES (32, 'allow_vote_after_results', '0', 0, 'true/false', 7, 3, 'Post_Configuration');
INSERT INTO `ibb_config` VALUES (33, 'avatar_upload_mime_types', 'image/bmp;image/png;image/jpeg;image/gif;', 0, 'textbox', 7, 4, 'Avatar_Configuration');
INSERT INTO `ibb_config` VALUES (34, 'forum_root', '', 0, '', 0, 0, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_forums`
-- 

CREATE TABLE `ibb_forums` (
  `forum_id` int(8) NOT NULL auto_increment,
  `forum_cat_id` int(8) NOT NULL default '0',
  `forum_type` set('c','f') NOT NULL default 'c',
  `forum_name` varchar(100) NOT NULL default '',
  `forum_description` varchar(200) NOT NULL default '',
  `forum_topics` int(8) NOT NULL default '0',
  `forum_posts` int(8) NOT NULL default '0',
  `forum_last_post` int(8) NOT NULL default '0',
  `forum_read` tinyint(5) NOT NULL default '1',
  `forum_post` tinyint(5) NOT NULL default '1',
  `forum_reply` tinyint(5) NOT NULL default '1',
  `forum_poll` tinyint(5) NOT NULL default '1',
  `forum_create_poll` tinyint(5) NOT NULL default '1',
  `forum_mod` tinyint(5) NOT NULL default '4',
  `forum_orderby` mediumint(8) NOT NULL default '0',
  `forum_redirect_url` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY  (`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ibb_forums`
-- 

INSERT INTO `ibb_forums` VALUES (1, 1, 'c', 'Test Forum', 'This is a test forum', 1, 1, 10, 1, 1, 1, 1, 1, 4, 1, NULL);

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_languages`
-- 

CREATE TABLE `ibb_languages` (
  `language_id` int(8) NOT NULL auto_increment,
  `language_name` varchar(100) NOT NULL default '',
  `language_folder` varchar(100) NOT NULL default '',
  `language_usable` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ibb_languages`
-- 

INSERT INTO `ibb_languages` VALUES (1, 'English', 'english', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_mods`
-- 

CREATE TABLE `ibb_mods` (
  `mod_id` varchar(75) NOT NULL default '',
  `mod_template_updates` text NOT NULL,
  `mod_version` varchar(10) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ibb_mods`
--

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_pm`
-- 

CREATE TABLE `ibb_pm` (
  `pm_id` int(8) NOT NULL auto_increment,
  `pm_title` varchar(80) NOT NULL default '',
  `pm_body` text NOT NULL,
  `pm_send_to` int(8) NOT NULL default '0',
  `pm_sent_from` int(8) NOT NULL default '0',
  `pm_type` tinyint(5) NOT NULL default '1',
  `pm_unread` tinyint(5) NOT NULL default '0',
  `pm_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ibb_pm`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_pollvotes`
-- 

CREATE TABLE `ibb_pollvotes` (
  `poll_topic_id` int(11) NOT NULL default '0',
  `poll_choice_id` tinyint(5) NOT NULL default '0',
  `poll_choice_name` varchar(50) NOT NULL default '',
  `poll_choice_votes` int(11) NOT NULL default '0',
  KEY `poll_topic_id` (`poll_topic_id`),
  KEY `poll_choice_id` (`poll_choice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ibb_pollvotes`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_posts`
-- 

CREATE TABLE `ibb_posts` (
  `post_id` int(8) NOT NULL auto_increment,
  `post_topic_id` int(8) NOT NULL default '0',
  `post_user_id` int(8) NOT NULL default '0',
  `post_text` text NOT NULL,
  `post_timestamp` int(11) default NULL,
  `post_disable_html` tinyint(1) NOT NULL default '0',
  `post_disable_bbcode` tinyint(1) NOT NULL default '0',
  `post_disable_smilies` tinyint(1) NOT NULL default '0',
  `post_attach_signature` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ibb_posts`
-- 

INSERT INTO `ibb_posts` VALUES (1, 1, -1, 'Thank you for choosing ImperialBB Forum Software. This is a test topic to make sure everything is working ok. You may now delete this topic, forum or even entire category.\r\n\r\nThanks\r\nImperialBB Team', 1142289057, 0, 0, 0, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_ranks`
-- 

CREATE TABLE `ibb_ranks` (
  `rank_id` int(8) NOT NULL auto_increment,
  `rank_orderby` tinyint(5) NOT NULL default '0',
  `rank_name` varchar(80) NOT NULL default '',
  `rank_image` varchar(100) NOT NULL default '',
  `rank_minimum_posts` int(11) NOT NULL default '0',
  `rank_special` tinyint(1) NOT NULL default '0',
  `rank_color` varchar(15) NOT NULL default '',
  `rank_bold` tinyint(5) NOT NULL default '0',
  `rank_underline` tinyint(5) NOT NULL default '0',
  `rank_italics` tinyint(5) NOT NULL default '0',
  PRIMARY KEY  (`rank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `ibb_ranks`
-- 

INSERT INTO `ibb_ranks` VALUES (1, 3, 'Member', '', 0, 0, 'black', 0, 1, 0);
INSERT INTO `ibb_ranks` VALUES (2, 2, 'Moderator', '', 0, 1, '#0033FF', 1, 1, 0);
INSERT INTO `ibb_ranks` VALUES (3, 1, 'Admin', '', 0, 1, '#018157', 1, 1, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_sessions`
-- 

CREATE TABLE `ibb_sessions` (
  `session_id` varchar(225) NOT NULL default '',
  `ip` varchar(225) NOT NULL default 'x.x.x.x',
  `user_id` varchar(225) NOT NULL default '',
  `time` varchar(225) NOT NULL default '',
  `time_created` varchar(225) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ibb_sessions`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_smilies`
-- 

CREATE TABLE `ibb_smilies` (
  `smilie_id` int(8) unsigned NOT NULL auto_increment,
  `smilie_code` varchar(50) default NULL,
  `smilie_url` varchar(100) default NULL,
  `smilie_name` varchar(75) default NULL,
  PRIMARY KEY  (`smilie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1 AUTO_INCREMENT=43 ;

-- 
-- Dumping data for table `ibb_smilies`
-- 

INSERT INTO `ibb_smilies` VALUES (1, ':D', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO `ibb_smilies` VALUES (2, ':-D', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO `ibb_smilies` VALUES (3, ':grin:', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO `ibb_smilies` VALUES (4, ':)', 'icon_smile.gif', 'Smile');
INSERT INTO `ibb_smilies` VALUES (5, ':-)', 'icon_smile.gif', 'Smile');
INSERT INTO `ibb_smilies` VALUES (6, ':smile:', 'icon_smile.gif', 'Smile');
INSERT INTO `ibb_smilies` VALUES (7, ':(', 'icon_sad.gif', 'Sad');
INSERT INTO `ibb_smilies` VALUES (8, ':-(', 'icon_sad.gif', 'Sad');
INSERT INTO `ibb_smilies` VALUES (9, ':sad:', 'icon_sad.gif', 'Sad');
INSERT INTO `ibb_smilies` VALUES (10, ':o', 'icon_surprised.gif', 'Surprised');
INSERT INTO `ibb_smilies` VALUES (11, ':-o', 'icon_surprised.gif', 'Surprised');
INSERT INTO `ibb_smilies` VALUES (12, ':eek:', 'icon_surprised.gif', 'Surprised');
INSERT INTO `ibb_smilies` VALUES (13, ':shock:', 'icon_eek.gif', 'Shocked');
INSERT INTO `ibb_smilies` VALUES (14, ':?', 'icon_confused.gif', 'Confused');
INSERT INTO `ibb_smilies` VALUES (15, ':-?', 'icon_confused.gif', 'Confused');
INSERT INTO `ibb_smilies` VALUES (16, ':???:', 'icon_confused.gif', 'Confused');
INSERT INTO `ibb_smilies` VALUES (17, '8)', 'icon_cool.gif', 'Cool');
INSERT INTO `ibb_smilies` VALUES (18, '8-)', 'icon_cool.gif', 'Cool');
INSERT INTO `ibb_smilies` VALUES (19, ':cool:', 'icon_cool.gif', 'Cool');
INSERT INTO `ibb_smilies` VALUES (20, ':lol:', 'icon_lol.gif', 'Laughing');
INSERT INTO `ibb_smilies` VALUES (21, ':x', 'icon_mad.gif', 'Mad');
INSERT INTO `ibb_smilies` VALUES (22, ':-x', 'icon_mad.gif', 'Mad');
INSERT INTO `ibb_smilies` VALUES (23, ':mad:', 'icon_mad.gif', 'Mad');
INSERT INTO `ibb_smilies` VALUES (24, ':P', 'icon_razz.gif', 'Razz');
INSERT INTO `ibb_smilies` VALUES (25, ':-P', 'icon_razz.gif', 'Razz');
INSERT INTO `ibb_smilies` VALUES (26, ':razz:', 'icon_razz.gif', 'Razz');
INSERT INTO `ibb_smilies` VALUES (27, ':oops:', 'icon_redface.gif', 'Embarassed');
INSERT INTO `ibb_smilies` VALUES (28, ':cry:', 'icon_cry.gif', 'Crying or Very sad');
INSERT INTO `ibb_smilies` VALUES (29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad');
INSERT INTO `ibb_smilies` VALUES (30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil');
INSERT INTO `ibb_smilies` VALUES (31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes');
INSERT INTO `ibb_smilies` VALUES (32, ':wink:', 'icon_wink.gif', 'Wink');
INSERT INTO `ibb_smilies` VALUES (33, ';)', 'icon_wink.gif', 'Wink');
INSERT INTO `ibb_smilies` VALUES (34, ';-)', 'icon_wink.gif', 'Wink');
INSERT INTO `ibb_smilies` VALUES (35, ':!:', 'icon_exclaim.gif', 'Exclamation');
INSERT INTO `ibb_smilies` VALUES (36, ':?:', 'icon_question.gif', 'Question');
INSERT INTO `ibb_smilies` VALUES (37, ':idea:', 'icon_idea.gif', 'Idea');
INSERT INTO `ibb_smilies` VALUES (38, ':arrow:', 'icon_arrow.gif', 'Arrow');
INSERT INTO `ibb_smilies` VALUES (39, ':|', 'icon_neutral.gif', 'Neutral');
INSERT INTO `ibb_smilies` VALUES (40, ':-|', 'icon_neutral.gif', 'Neutral');
INSERT INTO `ibb_smilies` VALUES (41, ':neutral:', 'icon_neutral.gif', 'Neutral');
INSERT INTO `ibb_smilies` VALUES (42, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green');

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_templates`
-- 

CREATE TABLE `ibb_templates` (
  `template_id` int(8) NOT NULL auto_increment,
  `template_name` varchar(225) NOT NULL default '',
  `template_folder` varchar(225) NOT NULL default '',
  `template_usable` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ibb_templates`
-- 

INSERT INTO `ibb_templates` VALUES (1, 'Original', 'original', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_topic_subscriptions`
-- 

CREATE TABLE `ibb_topic_subscriptions` (
  `topic_subscription_id` int(11) NOT NULL auto_increment,
  `topic_subscription_user_id` int(11) NOT NULL default '0',
  `topic_subscription_topic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`topic_subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ibb_topic_subscriptions`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_topics`
-- 

CREATE TABLE `ibb_topics` (
  `topic_id` int(8) NOT NULL auto_increment,
  `topic_forum_id` int(8) NOT NULL default '0',
  `topic_title` varchar(75) NOT NULL default '',
  `topic_poll_title` varchar(75) NOT NULL default '',
  `topic_status` tinyint(3) NOT NULL default '0',
  `topic_type` tinyint(3) NOT NULL default '0',
  `topic_first_post` int(8) NOT NULL default '0',
  `topic_user_id` int(8) NOT NULL default '0',
  `topic_replies` int(8) NOT NULL default '0',
  `topic_views` int(8) NOT NULL default '0',
  `topic_last_post` int(8) NOT NULL default '0',
  `topic_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`),
  KEY `topic_type` (`topic_type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ibb_topics`
-- 

INSERT INTO `ibb_topics` VALUES (1, 1, 'Welcome to ImperialBB', '', 1, 1, 1, -1, 1, 17, 1, 1156002157);

-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_ug_auth`
-- 

CREATE TABLE `ibb_ug_auth` (
  `usergroup` int(8) NOT NULL default '0',
  `ug_forum_id` int(8) NOT NULL default '0',
  `ug_read` tinyint(5) NOT NULL default '0',
  `ug_post` tinyint(5) NOT NULL default '0',
  `ug_reply` tinyint(5) NOT NULL default '0',
  `ug_create_poll` tinyint(5) NOT NULL default '0',
  `ug_poll` tinyint(5) NOT NULL default '0',
  `ug_mod` tinyint(5) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ibb_ug_auth`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_upgrades`
-- 

CREATE TABLE `ibb_upgrades` (
  `upgrade_version` varchar(10) NOT NULL default '',
  `upgrade_template_updates` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `ibb_upgrades`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_usergroups`
-- 

CREATE TABLE `ibb_usergroups` (
  `id` int(8) NOT NULL auto_increment,
  `name` varchar(225) NOT NULL default '',
  `desc` varchar(225) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ibb_usergroups`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ibb_users`
-- 

CREATE TABLE `ibb_users` (
  `user_id` int(8) NOT NULL auto_increment,
  `username` varchar(225) NOT NULL default '',
  `user_password` varchar(225) NOT NULL default '',
  `user_email` varchar(225) NOT NULL default '',
  `user_date_joined` int(11) NOT NULL default '0',
  `user_lastvisit` int(11) NOT NULL default '0',
  `user_level` tinyint(5) NOT NULL default '0',
  `user_usergroup` int(8) NOT NULL default '0',
  `user_signature` text NOT NULL,
  `user_rank` int(8) NOT NULL default '0',
  `user_aim` varchar(225) NOT NULL default '',
  `user_icq` varchar(225) NOT NULL default '',
  `user_msn` varchar(225) NOT NULL default '',
  `user_yahoo` varchar(225) NOT NULL default '',
  `user_email_on_pm` tinyint(5) NOT NULL default '0',
  `user_template` int(8) NOT NULL default '1',
  `user_language` int(8) NOT NULL default '1',
  `user_timezone` char(3) NOT NULL default '0',
  `user_posts` int(8) NOT NULL default '0',
  `user_activation_key` varchar(225) NOT NULL default '',
  `user_location` varchar(225) NOT NULL default '',
  `user_website` varchar(225) NOT NULL default '',
  `user_avatar_type` int(4) NOT NULL default '0',
  `user_avatar_location` varchar(75) NOT NULL default '',
  `user_avatar_dimensions` varchar(11) NOT NULL default '',
  `user_password_reset_request` int(11) NOT NULL default '0',
  `user_new_password` varchar(50) NOT NULL default '',
  `user_birthday` VARCHAR(10) NOT NULL DEFAULT '',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ibb_users`
-- 

INSERT INTO `ibb_users` VALUES (-1, 'Guest', '', '', 0, 1164221881, 1, 0, '', 1, '', '', '', '', 0, 1, 1, '0', 0, '', '', '', 0, '', '', 0, '', '0000-00-00');
