<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: profile.php                                                # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright � 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");
require_once($root_path."models/user.php");

$language->add_file("profile");

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "edit")
{
	if($user['user_id'] < 0) {
		info_box($lang['Error'], $lang['Must_Be_Logged_In'], "login.php");
	}
	
	if(isset($_POST['Submit']))
	{
		$error = "";
		if(strlen($_POST['PassWord']) > 0) {
			$oUser = User::findUser($user['user_id']);
			
			$ok = User::check($oUser->getUsername(), $_POST['OldPass']);
			
			if(!$ok) {
				$error .= $lang['Incorrect_Old_Password'] . "<br />";
			}

			if(strlen($_POST['PassWord']) < 4) {
				$error .= $lang['Password_Too_Short'] . "<br />";
			} else if($_POST['PassWord'] != $_POST['Pass2']) {
				$error .= $lang['Passwords_Dont_Match'] . "<br />";
			}
			
			$oUser->setPassword($_POST['PassWord']);
			$ok = $oUser->updatePassword();
		}
		
		if(!preg_match("#(.*?)@(.*?)\.(.*?)#", $_POST['Email'])) {
			$error .= $lang['Invalid_Email_Address'] . "<br />";
		} else if($_POST['Email'] != $_POST['Email2']) {
			$error .= $lang['Email_Addresses_Dont_Match'] . "<br />";
		}

		if($config['allow_uploaded_avatar'] && !empty($_FILES['Upload_Avatar']['name'])) {
			// Check uploaded avatar filesize
			$image_size = GetImageSize($_FILES['Upload_Avatar']['tmp_name']);
			if(!in_array($_FILES['Upload_Avatar']['type'], explode(";", $config['avatar_upload_mime_types']))) {
				$error .= $lang['Invalid_Avatar_Type_Msg'] . "<br />";
			} else if($_FILES['Upload_Avatar']['size'] > $config['avatar_max_upload_size']) {
				$error .= sprintf($lang['Avatar_Filesize_Too_Big'], $config['avatar_max_upload_size']);
			} else if($image_size[0] > $config['avatar_max_upload_width'] || $image_size[1] > $config['avatar_max_upload_width']) {
				// Check uploaded avatar dimensions
				$error .= sprintf($lang['Avatar_Dimensions_Too_Big'], $config['avatar_max_upload_height'], $config['avatar_max_upload_width']);
			}

			// Verify MIME Type
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime=finfo_file($finfo, $_FILES['Upload_Avatar']['tmp_name']);

			if(!in_array($mime, explode(";", $config['avatar_upload_mime_types']))){
				$error .= $lang['Invalid_Avatar_Type_Msg'] . "<br />";
			}
		}

		if(isset($_POST['template']))
		{
			$template_sql = "SELECT `template_id`, `template_name` FROM `_PREFIX_templates` WHERE `template_id` = :template_id";
			if($user['user_level'] < 5)
			{
				$template_sql .= " AND `template_usable` = '1'";
			}
			$template_query = $db2->query($template_sql, array(":template_id" => $_POST['template']));
			if(!$template_result = $template_query->fetch())
			{
				$error .= $lang['Invalid_Template_Selected'];
			}
		}
		else
		{
			$_POST['template'] = $user['user_template'];
		}

		if(isset($_POST['language'])) {
			$language_sql = "SELECT `language_id`, `language_name` FROM `_PREFIX_languages` WHERE `language_id` = :language_id";
			if($user['user_level'] < 5)
			{
				$language_sql .= " AND `language_usable` = '1'";
			}
			$language_query = $db2->query($language_sql, array(":language_id" => $_POST['language']));
			if(!$language_result = $language_query->fetch())
			{
				$error .= $lang['Invalid_Language_Selected'];
			}
		}
		else
		{
			$_POST['language'] = $user['user_language'];
		}

		if(strlen($error) > 0) {
			$theme->new_file("edit_profile", "edit_profile.tpl", "");
			$theme->replace_tags("edit_profile", array(
				"EMAIL" => $_POST['Email'],
				"EMAIL2" => $_POST['Email2'],
				"SIGNATURE" => $_POST['signature'],
				"AIM" => $_POST['aim'],
				"ICQ" => $_POST['icq'],
				"MSN" => $_POST['msn'],
				"YAHOO" => $_POST['yahoo'],
				"REMOTE_AVATAR_URL" => $_POST['Remote_Avatar_URL'],
				"LOCATION" => $_POST['location'],
				"WEBSITE" => $_POST['website'],
				"EOP_TRUE" => ($_POST['email_on_pm'] == "1") ? "CHECKED" : "",
				"EOP_FALSE" => ($_POST['email_on_pm'] == "0") ? "CHECKED" : "",
				"CSRF_TOKEN" => CSRF::getHTML()
			));
			
			if($user['user_avatar_type'] == 0) {
				$theme->switch_nest("edit_profile", "current_avatar", false);
				$theme->add_nest("edit_profile", "current_avatar");
			} else {
				$theme->switch_nest("edit_profile", "current_avatar", true);
				$theme->add_nest("edit_profile", "current_avatar");
			}
			
			$theme->insert_nest("edit_profile", "error", array(
				"ERRORS" => $error
			));
			$theme->add_nest("edit_profile", "error");

			$template_count = 0;
			$theme->insert_nest("edit_profile", "template_select");

			$template_sql = "SELECT `template_id`, `template_name` FROM `_PREFIX_templates`";
			if($user['user_level'] < 5)
			{
				$template_sql .= " WHERE `template_usable` = '1'";
			}
			
			$template_query = $db2->query($template_sql);
			while($template_result = $template_query->fetch()) {
				$theme->insert_nest("edit_profile", "template_select/template_select_option", array(
					"TEMPLATE_ID" => $template_result['template_id'],
					"TEMPLATE_NAME" => $template_result['template_name'],
					"TEMPLATE_SELECTED" => ($template_result['template_id'] == $_POST['template']) ? "selected=\"selected\"" : ""
				));
				$theme->add_nest("edit_profile", "template_select/template_select_option");
				$template_count++;
			}

			if($template_count > 1) {
				$theme->add_nest("edit_profile", "template_select");
			}

			$language_count = 0;
			$theme->insert_nest("edit_profile", "language_select");

			$language_sql = "SELECT `language_id`, `language_name` FROM `_PREFIX_languages`";
			if($user['user_level'] < 5) {
				$language_sql .= " WHERE `language_usable` = '1'";
			}
			
			$language_query = $db2->query($language_sql);
			while($language_result = $language_query->fetch())
			{
				$theme->insert_nest("edit_profile", "language_select/language_select_option", array(
					"LANGUAGE_ID" => $language_result['language_id'],
					"LANGUAGE_NAME" => $language_result['language_name'],
					"LANGUAGE_SELECTED" => ($language_result['language_id'] == $_POST['language']) ? "selected=\"selected\"" : ""
				));
				$theme->add_nest("edit_profile", "language_select/language_select_option");
				$language_count++;
			}

			if($language_count > 1) {
				$theme->add_nest("edit_profile", "language_select");
			}

			include_once($root_path . "includes/page_header.php");
			$theme->output("edit_profile");
			include_once($root_path . "includes/page_footer.php");
			
		} else {
			$oUser = User::findUser($user['user_id']);
			
			if($config['allow_uploaded_avatar'] && !empty($_FILES['Upload_Avatar']['name'])) {
				$filename = $user['username']."_".$_FILES['Upload_Avatar']['name'];

				$filename = preg_replace("# #i", "_", $filename);
				
  		      	if(copy($_FILES['Upload_Avatar']['tmp_name'], $config['avatar_upload_dir'] . "/" . $filename)) {
  		      		if($user['user_avatar_type'] == "2") {
						unlink("images/avatars/uploads/".$user['user_avatar_location']);
					}
					
					$oUser->setAvatarType(UPLOADED_AVATAR);
					$oUser->setAvatarLocation($filename);
					$oUser->setAvatarDimensions($image_size[0]."x".$image_size[1]);
				} else {
					error_msg($lang['Error'], $lang['Unable_To_Change_Avatar_Msg']);
				}
			} else if(isset($_POST['Delete_Avatar'])) {
				if($user['user_avatar_type'] == "2") {
					unlink("images/avatars/uploads/".$user['user_avatar_location']."");
				}
				
				$oUser->setAvatarType(NO_AVATAR);
				$oUser->setAvatarLocation("");

			} else if($config['allow_remote_avatar'] && !empty($_POST['Remote_Avatar_URL'])) {
				if($user['user_avatar_type'] == "2") {
					unlink("images/avatars/uploads/".$user['user_avatar_location']."");
				}
				
				$oUser->setAvatarType(REMOTE_AVATAR);
				$oUser->setAvatarLocation($_POST['Remote_Avatar_URL']);

			}
			
			$oUser->setMail($_POST['Email']);
			$oUser->setSignature($_POST['signature']);
			
			if(strlen($_POST['PassWord']) > 0) {
				$oUser->setPassword($_POST['PassWord']);
			}

			if(isset($_POST['day'])) {
                $day 	= trim($_POST['day']);
            }
			
			if(isset($_POST['month'])) {
                $month 	= trim($_POST['month']);
            }
            
			if(isset($_POST['year'])) {
                $year 	= trim($_POST['year']);
            }
			
			if($day && $month) {
			    $birthday = ifelse(strlen($year) == 4, $year,ifelse(strlen($year) == 2,"19$year","0000"))
                            ."-".ifelse($month<10,"0$month",$month)."-".ifelse($day<10,"0$day",$day);
            } else {
                $birthday = '0000-00-00';
            }
			$birthdate = htmlspecialchars($birthday);

			$oUser->setMessengers(array("aim" => $_POST['aim'], "icq" => $_POST['icq'], "msn" => $_POST['msn'], "yahoo" => $_POST['yahoo']));
			$oUser->setEmailOnPm($_POST['email_on_pm']);
			$oUser->setLocation($_POST['location']);
			$oUser->setWebsite($_POST['website']);
			$oUser->setTemplateId($_POST['template']);
			$oUser->setLanguageId($_POST['language']);
			$oUser->setBirthday($birthdate);

			$oUser->update();
			info_box($lang['Edit_Profile'], $lang['Profile_Updated'], "index.php");
		}
	} else {
		$sql = $db2->query("SELECT *
			FROM `_PREFIX_users`
			WHERE `user_id` = :user_id",
			array(
				":user_id" => $user['user_id']
			)
		);
		
		$oUser = User::findUser($user['user_id']);
		if($oUser != null) {
			$ims = $oUser->getMessengers();
			$theme->new_file("edit_profile", "edit_profile.tpl", "");
			$theme->replace_tags("edit_profile", array(
				"EMAIL" => $oUser->getEmail(),
				"EMAIL2" => $oUser->getEmail(),
				"SIGNATURE" => $oUser->getSignature(),
				"AIM" => $ims["aim"],
				"ICQ" => $ims["icq"],
				"MSN" => $ims["msn"],
				"YAHOO" => $ims["yahoo"],
				"EOP_TRUE" => ($oUser->getEmailOnPm()."" == "1") ? "CHECKED" : "",
				"EOP_FALSE" => ($oUser->getEmailOnPm().""  == "0") ? "CHECKED" : "",
				"REMOTE_AVATAR_URL" => ($oUser->getAvatarType() == REMOTE_AVATAR) ? $oUser->getAvatarLocation() : "",
				"LOCATION" => $oUser->getLocation(),
				"WEBSITE" => $oUser->getWebsite(),
				"CSRF_TOKEN" => CSRF::getHTML()
			));

			$birthday = explode("-", $oUser->getBirthday());
			$day 	  = $birthday[2];
			$month 	  = $birthday[1];
			$year 	  = ifelse($birthday[0], $birthday[0], '');
			$day_options = '';
			for($i = 1; $i<=31; $i++) {
                $day_options   .= fetch_make_options($i, $i, $day);
            }
            
			$month_options = '';
			for($i = 1; $i<=12; $i++) {
                $month_options .= fetch_make_options($i, fetch_months($i), $month);
            }
            
			$year_options = '';
			for($i = 1905; $i <= 2020; $i++) {
                $year_options .= fetch_make_options($i, $i, $year);
            }
            
			$theme->replace_tags("edit_profile", array(
				"DAY_OPTS"   => $day_options,
				"MONTH_OPTS" => $month_options,
				"YEAR_OPTS"  => $year_options,
			));

			if($oUser->getAvatarType() == NO_AVATAR) {
				$theme->switch_nest("edit_profile", "current_avatar", false);
				$theme->add_nest("edit_profile", "current_avatar");
			} else {
				list($user['user_avatar_width'], $user['user_avatar_height']) = explode("x", $user['user_avatar_dimensions']);
				if($oUser->getAvatarType() == UPLOADED_AVATAR) {
					$avatar_loc = $root_path . $config['avatar_upload_dir'] . "/" . $oUser->getAvatarLocation();
				}
				$theme->switch_nest("edit_profile", "current_avatar", true, array(
					"AVATAR_LOCATION" => $avatar_loc,
					"AVATAR_HEIGHT" => $user['user_avatar_height'],
					"AVATAR_WIDTH" => $user['user_avatar_width']
				));
				$theme->add_nest("edit_profile", "current_avatar");
			}

			$template_count = 0;
			$theme->insert_nest("edit_profile", "template_select");

			$template_sql = "SELECT `template_id`, `template_name` FROM `_PREFIX_templates`";
			if($user['user_level'] < 5) {
				$template_sql .= " WHERE `template_usable` = '1'";
			}
			
			$template_query = $db2->query($template_sql);
			while($template_result = $template_query->fetch()) {
				$theme->insert_nest("edit_profile", "template_select/template_select_option", array(
					"TEMPLATE_ID" => $template_result['template_id'],
					"TEMPLATE_NAME" => $template_result['template_name'],
					"TEMPLATE_SELECTED" => ($template_result['template_id'] == $oUser->getTemplateId()) ? "selected=\"selected\"" : ""
				));
				$theme->add_nest("edit_profile", "template_select/template_select_option");
				$template_count++;
			}

			if($template_count > 1) {
				$theme->add_nest("edit_profile", "template_select");
			}

			$language_count = 0;
			$theme->insert_nest("edit_profile", "language_select");

			$language_sql = "SELECT `language_id`, `language_name` FROM `_PREFIX_languages`";
			if($user['user_level'] < 5) {
				$language_sql .= " WHERE `language_usable` = '1'";
			}
			
			$language_query = $db2->query($language_sql);
			while($language_result = $language_query->fetch()) {
				$theme->insert_nest("edit_profile", "language_select/language_select_option", array(
					"LANGUAGE_ID" => $language_result['language_id'],
					"LANGUAGE_NAME" => $language_result['language_name'],
					"LANGUAGE_SELECTED" => ($language_result['language_id'] == $oUser->getLanguageId()) ? "selected=\"selected\"" : ""
				));
				$theme->add_nest("edit_profile", "language_select/language_select_option");
				$language_count++;
			}

			if($language_count > 1) {
				$theme->add_nest("edit_profile", "language_select");
			}

			include_once($root_path . "includes/page_header.php");
			$theme->output("edit_profile");
			include_once($root_path . "includes/page_footer.php");
		}
	}
} else {
	if(!isset($_GET['id']) || $_GET['id'] < 0) {
        error_msg($lang['Error'], $lang['Invalid_User_Id']);
    }
    
    $oUser = User::findUser($_GET['id']);
	$db2->query("SELECT `rank_name` FROM `_PREFIX_ranks` WHERE `rank_id`=:rid", array(":rid" => $oUser->getRankId()));
	$result = $db2->fetch();

	if($oUser != null && isset($result["rank_name"])) {
		$ims = $oUser->getMessengers();
		$theme->new_file("view_profile", "view_profile.tpl", "");
		$theme->replace_tags("view_profile", array(
			"USER_ID" => $oUser->getId(),
			"USERNAME" => $oUser->getUsername(),
			"ABOUT_USER" => sprintf($lang['About_X'], $oUser->getUsername()),
			"RANK" => $result['rank_name'],
			"JOINED" => create_date("D d M Y", $oUser->getDateJoined()),
			"AIM" => $ims['aim'] ?: $lang['NA'],
			"ICQ" => $ims['icq'] ?: $lang['NA'],
			"MSN" => $ims['msn'] ?: $lang['NA'],
			"YAHOO" => $ims['yahoo'] ?: $lang['NA'],
			"LOCATION" => $oUser->getLocation() ?: $lang['NA'],
			"WEBSITE" => (preg_match("#(([http|https]://)[\#$\w%~/&.\-;:=,?@+]*?)([^?\n\r\t].*?)#i", $oUser->getWebsite())) ? "<a href=\"".$oUser->getWebsite()."\" target=\"blank\">".$oUser->getWebsite()."</a>" : $oUser->getWebsite(),
            "LASTVISIT" => create_date('M jS, Y g:i a', $oUser->getLastVisit()),
		));

    	// ===========================
    	// Profile Birthday
    	// ===========================
    	if($user['user_birthday'] && $user['user_birthday'] != '0000-00-00') {
    		$bday_array = explode('-', $user['user_birthday']);
    		$bday_array[1] = fetch_months($bday_array[1]);
    		
    		if($bday_array[0] == '0000') {
    			$birthday =  $bday_array[1].' / '.$bday_array[2];
    		} else {
    			$birthday =  $bday_array[1].' / '.$bday_array[2].' / '.$bday_array[0];
    		}
    	} else {
    		$birthday = $lang['NA'];
    	}
    	
    	$btime = time();
    	$year = intval(create_date("Y",$btime));
    	$age = $year - substr($user['user_birthday'], 0, 4);
    	if ($age < 1 || $age > 200) {
    		$age = '';
    	} else {
    		$age = '(' .$age. ')';
    	}
    	
    	if(!$age) {
    		$age = '';
    	}
    	$theme->replace_tags("view_profile", array(
    		"BIRTHDAY" => $birthday,
    		"USER_AGE" => $age,
    	));

    	// ===========================
    	// Profile Signature
    	// ===========================
    	$sig = $oUser->getSignature();
    	if(!empty($sig)) {
        	$theme->replace_tags("view_profile", array(
    			"SIGNATURE" => format_text($sig)
        	));
    	} else {
        	$theme->replace_tags("view_profile", array(
    			"SIGNATURE" => $lang['None']
        	));
    	}

    	// ===========================
    	// Profile Avatar
    	// ===========================
    	if($oUser->getAvatarType() == UPLOADED_AVATAR || $oUser->getAvatarType() == REMOTE_AVATAR) {
    		if($oUser->getAvatarType() == UPLOADED_AVATAR) {
    			$av_loc = $root_path . $config['avatar_upload_dir'] . "/" . $oUser->getAvatarLocation();
    		}
    		$theme->switch_nest("view_profile", "avatar", true, array(
    			"AUTHOR_AVATAR_LOCATION" => $av_loc
    		));
    	} else {
    		$theme->switch_nest("view_profile", "avatar", false);
    	}
    	$theme->add_nest("view_profile", "avatar");

    	// ===========================
    	// Profile Online Status
    	// ===========================
    	$online_sql = $db2->query("SELECT *
			FROM `_PREFIX_sessions`
			WHERE `user_id` = :user_id",
			array(
				":user_id" => intval($_GET['id'])
			)
		);
		
    	if($online_sql->fetch()) {
    		$theme->switch_nest("view_profile", "online", true);
    	} else {
    		$theme->switch_nest("view_profile", "online", false);
    	}
    	$theme->add_nest("view_profile", "online");

    	// ===========================
    	// Profile Page Output
    	// ===========================
    	include_once($root_path . "includes/page_header.php");
    	$theme->output("view_profile");
    	include_once($root_path . "includes/page_footer.php");
    } else {
    	error_msg($lang['Error'] , $lang['Invalid_User_Id']);
    }
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright � 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
