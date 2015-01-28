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
include($root_path . "includes/common.php");

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
			$sql = $db2->query("SELECT `user_id` FROM `_PREFIX_users`
				WHERE `user_id` = :uid AND `user_password` = :hashed_password",
				array(
					":uid" => $user['user_id'],
					":hashed_password" => md5(md5($_POST['OldPass']))
				)
			);
			if(!$sql->fetch()) {
				$error .= $lang['Incorrect_Old_Password'] . "<br />";
			}
			if(strlen($_POST['PassWord']) < 4) {
				$error .= $lang['Password_Too_Short'] . "<br />";
			} else if($_POST['PassWord'] != $_POST['Pass2']) {
				$error .= $lang['Passwords_Dont_Match'] . "<br />";
			}
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
				"EOP_FALSE" => ($_POST['email_on_pm'] == "0") ? "CHECKED" : ""
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

            //
            // Template Select
            //
			$template_count = 0;
			$theme->insert_nest("edit_profile", "template_select");

			$template_sql = "SELECT `template_id`, `template_name` FROM `_PREFIX_templates`";
			if($user['user_user_level'] < 5)
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

			//
			// Language Select
			//
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

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_profile");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		} else {
			if($config['allow_uploaded_avatar'] && !empty($_FILES['Upload_Avatar']['name'])) {
				$filename = $user['username']."_".$_FILES['Upload_Avatar']['name'];

//				$filename = eregi_replace(" ", "_", $filename); // DEPRECATED
				$filename = preg_replace("# #i", "_", $filename);


  		      	if(copy($_FILES['Upload_Avatar']['tmp_name'], $config['avatar_upload_dir'] . "/" . $filename)) {
  		      		if($user['user_avatar_type'] == "2") {
						unlink("images/avatars/uploads/".$user['user_avatar_location']);
					}
					$db2->query("UPDATE `_PREFIX_users`
						SET `user_avatar_type` = '".UPLOADED_AVATAR."',
						`user_avatar_location` = :filename,
						`user_avatar_dimensions` = :dimensions
						WHERE `user_id` = :user_id",
						array(
							":filename" => $filename,
							":dimensions" => $image_size[0]."x".$image_size[1],
							":user_id" => $user['user_id']
						)
					);
				} else {
					error_msg($lang['Error'], $lang['Unable_To_Change_Avatar_Msg']);
				}
			} else if(isset($_POST['Delete_Avatar'])) {
				if($user['user_avatar_type'] == "2") {
					unlink("images/avatars/uploads/".$user['user_avatar_location']."");
				}
				$db2->query("UPDATE `_PREFIX_users`
					SET `user_avatar_type` = '".NO_AVATAR."',
					`user_avatar_location` = ''
					WHERE `user_id` = :user_id",
					array(
						":user_id" => $user['user_id']
					)
				);
			} else if($config['allow_remote_avatar'] && !empty($_POST['Remote_Avatar_URL'])) {
				if($user['user_avatar_type'] == "2") {
					unlink("images/avatars/uploads/".$user['user_avatar_location']."");
				}
				$db2->query("UPDATE `_PREFIX_users`
					SET `user_avatar_type` = '".REMOTE_AVATAR."',
					`user_avatar_location` = :avatar_url
					WHERE `user_id` = :user_id",
					array(
						":avatar_url" => $_POST['Remote_Avatar_URL'],
						":user_id" => $user['user_id']
					)
				);
			}
			$sql = "UPDATE `".$db_prefix."users`
				SET `user_email` = :email,
				`user_signature` = :signature, ";
			$params = array(
				":email" => $_POST['Email'],
				":signature" => $_POST['signature']
			);
			if(strlen($_POST['PassWord']) > 0)
			{
				$sql .= "`user_password` = :hashed_password, ";
				$params[":hashed_password"] = md5(md5($_POST['PassWord']));
			}

			if(isset($_POST['day']))
            {
                $day 	= trim($_POST['day']);
            }
			if(isset($_POST['month']))
            {
                $month 	= trim($_POST['month']);
            }
			if(isset($_POST['year']))
            {
                $year 	= trim($_POST['year']);
            }
			if($day && $month)
            {
			    $birthday = ifelse(strlen($year) == 4, $year,ifelse(strlen($year) == 2,"19$year","0000"))
                            ."-".ifelse($month<10,"0$month",$month)."-".ifelse($day<10,"0$day",$day);
            }
			else
            {
                $birthday = '0000-00-00';
            }
			$birthdate = htmlspecialchars($birthday);

			$sql .= "`user_aim` = :aim,
			`user_icq` = :icq,
			`user_msn` = :msn,
			`user_yahoo` = :yahoo,
			`user_email_on_pm` = :email_on_pm,
			`user_location` = :location,
			`user_website` = :website,
			`user_template` = :template,
			`user_language` = :language,
			`user_birthday` = :birthday
			WHERE `user_id` = :user_id";

			$params[":aim"] = $_POST['aim'];
			$params[":icq"] = $_POST['icq'];
			$params[":msn"] = $_POST['msn'];
			$params[":yahoo"] = $_POST['yahoo'];
			$params[":email_on_pm"] = $_POST['email_on_pm'];
			$params[":location"] = $_POST['location'];
			$params[":website"] = $_POST['website'];
			$params[":template"] = $_POST['template'];
			$params[":language"] = $_POST['language'];
			$params[":birthday"] = $birthdate;

			$params[":user_id"] = $_SESSION['user_id'];

			$db2->query($sql, $params);
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
		if($result = $sql->fetch()) {
			$theme->new_file("edit_profile", "edit_profile.tpl", "");
			$theme->replace_tags("edit_profile", array(
				"EMAIL" => $result['user_email'],
				"EMAIL2" => $result['user_email'],
				"SIGNATURE" => $result['user_signature'],
				"AIM" => $result['user_aim'],
				"ICQ" => $result['user_icq'],
				"MSN" => $result['user_msn'],
				"YAHOO" => $result['user_yahoo'],
				"EOP_TRUE" => ($result['user_email_on_pm'] == "1") ? "CHECKED" : "",
				"EOP_FALSE" => ($result['user_email_on_pm'] == "0") ? "CHECKED" : "",
				"REMOTE_AVATAR_URL" => ($result['user_avatar_type'] == REMOTE_AVATAR) ? $result['user_avatar_location'] : "",
				"LOCATION" => $result['user_location'],
				"WEBSITE" => $result['user_website']
			));

			$birthday = explode("-", $result['user_birthday']);
			$day 	  = $birthday[2];
			$month 	  = $birthday[1];
			$year 	  = ifelse($birthday[0], $birthday[0], '');
			$day_options = '';
			for($i = 1; $i<=31; $i++)
            {
                $day_options   .= fetch_make_options($i, $i, $day);
            }
			$month_options = '';
			for($i = 1; $i<=12; $i++)
            {
                $month_options .= fetch_make_options($i, fetch_months($i), $month);
            }
			$year_options = '';
			for($i = 1905; $i <= 2020; $i++)
            {
                $year_options .= fetch_make_options($i, $i, $year);
            }
			$theme->replace_tags("edit_profile", array(
				"DAY_OPTS"   => $day_options,
				"MONTH_OPTS" => $month_options,
				"YEAR_OPTS"  => $year_options,
			));

			if($result['user_avatar_type'] == NO_AVATAR) {
				$theme->switch_nest("edit_profile", "current_avatar", false);
				$theme->add_nest("edit_profile", "current_avatar");
			} else {
				list($user['user_avatar_width'], $user['user_avatar_height']) = explode("x", $user['user_avatar_dimensions']);
				if($result['user_avatar_type'] == UPLOADED_AVATAR) {
					$result['user_avatar_location'] = $root_path . $config['avatar_upload_dir'] . "/" . $result['user_avatar_location'];
				}
				$theme->switch_nest("edit_profile", "current_avatar", true, array(
					"AVATAR_LOCATION" => $result['user_avatar_location'],
					"AVATAR_HEIGHT" => $user['user_avatar_height'],
					"AVATAR_WIDTH" => $user['user_avatar_width']
				));
				$theme->add_nest("edit_profile", "current_avatar");
			}
            //
            // Template Select
            //
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
					"TEMPLATE_SELECTED" => ($template_result['template_id'] == $result['user_template']) ? "selected=\"selected\"" : ""
				));
				$theme->add_nest("edit_profile", "template_select/template_select_option");
				$template_count++;
			}

			if($template_count > 1) {
				$theme->add_nest("edit_profile", "template_select");
			}

			//
			// Language Select
			//
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
					"LANGUAGE_SELECTED" => ($language_result['language_id'] == $result['user_language']) ? "selected=\"selected\"" : ""
				));
				$theme->add_nest("edit_profile", "language_select/language_select_option");
				$language_count++;
			}

			if($language_count > 1) {
				$theme->add_nest("edit_profile", "language_select");
			}

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_profile");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		}
	}
} else {
	if(!isset($_GET['id']) || $_GET['id'] < 0)
    {
        error_msg($lang['Error'], $lang['Invalid_User_Id']);
    }
	$sql = $db2->query("SELECT u.*, r.`rank_name`
		FROM (`_PREFIX_users` u
		LEFT JOIN `_PREFIX_ranks` r ON r.`rank_id` = u.`user_rank`)
		WHERE `user_id` = :user_id",
		array(
			":user_id" => intval($_GET['id'])
		)
    );

	if($result = $sql->fetch())
    {
		$theme->new_file("view_profile", "view_profile.tpl", "");
		$theme->replace_tags("view_profile", array(
			"USER_ID" => $result['user_id'],
			"USERNAME" => $result['username'],
			"ABOUT_USER" => sprintf($lang['About_X'], $result['username']),
			"RANK" => $result['rank_name'],
			"JOINED" => create_date("D d M Y", $result['user_date_joined']),
			"AIM" => ( $result['user_aim'] ) ? $result['user_aim'] : $lang['NA'],
			"ICQ" => ( $result['user_icq'] ) ? $result['user_icq'] : $lang['NA'],
			"MSN" => ( $result['user_msn'] ) ? $result['user_msn'] : $lang['NA'],
			"YAHOO" => ( $result['user_yahoo'] ) ? $result['user_yahoo'] : $lang['NA'],
			"LOCATION" => ( $result['user_location'] ) ? $result['user_location'] : $lang['NA'],
			"WEBSITE" => (preg_match("#(([http|https]://)[\#$\w%~/&.\-;:=,?@+]*?)([^?\n\r\t].*?)#i", $result['user_website'])) ? "<a href=\"".$result['user_website'] ."\" target=\"blank\">".$result['user_website']."</a>" : $result['user_website'],
            "LASTVISIT" => create_date('M jS, Y g:i a', $result['user_lastvisit']),
		));

    	// ===========================
    	// Profile Birthday
    	// ===========================
    	if($user['user_birthday'] && $user['user_birthday'] != '0000-00-00')
    	{
    		$bday_array = explode('-', $user['user_birthday']);
    		$bday_array[1] = fetch_months($bday_array[1]);
    		if($bday_array[0] == '0000')
    		{
    			$birthday =  $bday_array[1].' / '.$bday_array[2];
    		}
    		else
    		{
    			$birthday =  $bday_array[1].' / '.$bday_array[2].' / '.$bday_array[0];
    		}
    	}
    	else
    	{
    		$birthday = $lang['NA'];
    	}
    	$btime = time();
    	$year = intval(create_date("Y",$btime));
    	$age = $year - substr($user['user_birthday'], 0, 4);
    	if ($age < 1 || $age > 200)
    	{
    		$age = '';
    	}
    	else
    	{
    		$age = '(' .$age. ')';
    	}
    	if(!$age)
    	{
    		$age = '';
    	}
    	$theme->replace_tags("view_profile", array(
    		"BIRTHDAY" => $birthday,
    		"USER_AGE" => $age,
    	));

    	// ===========================
    	// Profile Signature
    	// ===========================
    	if(!empty($result['user_signature']))
    	{
        	$theme->replace_tags("view_profile", array(
    			"SIGNATURE" => format_text($result['user_signature'])
        	));
    	}
    	else
    	{
        	$theme->replace_tags("view_profile", array(
    			"SIGNATURE" => $lang['None']
        	));
    	}

    	// ===========================
    	// Profile Avatar
    	// ===========================
    	if($result['user_avatar_type'] == UPLOADED_AVATAR || $result['user_avatar_type'] == REMOTE_AVATAR)
    	{
    		if($result['user_avatar_type'] == UPLOADED_AVATAR)
    		{
    			$result['user_avatar_location'] = $root_path . $config['avatar_upload_dir'] . "/" . $result['user_avatar_location'];
    		}
    		$theme->switch_nest("view_profile", "avatar", true, array(
    			"AUTHOR_AVATAR_LOCATION" => $result['user_avatar_location']
    		));
    	}
    	else
    	{
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
    	if($online_sql->fetch())
    	{
    		$theme->switch_nest("view_profile", "online", true);
    	}
    	else
    	{
    		$theme->switch_nest("view_profile", "online", false);
    	}
    	$theme->add_nest("view_profile", "online");

    	// ===========================
    	// Profile Page Output
    	// ===========================
    	include($root_path . "includes/page_header.php");
    	$theme->output("view_profile");
    	include($root_path . "includes/page_footer.php");
    }
    else
    {
    	error_msg($lang['Error'] , $lang['Invalid_User_Id']);
    }
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright � 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
