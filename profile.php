<?php
define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");
require_once($root_path."models/user.php");
$language->add_file("profile");
Template::addNamespace("L", $lang);

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "edit")
{
	if($user['user_id'] < 0) {
		showMessage(ERR_CODE_REQUIRE_LOGIN, "login.php");
	}
	
	if(isset($_POST['Submit'])) {
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
			$mime = finfo_file($finfo, $_FILES['Upload_Avatar']['tmp_name']);

			if(!in_array($mime, explode(";", $config['avatar_upload_mime_types']))){
				$error .= $lang['Invalid_Avatar_Type_Msg'] . "<br />";
			}
		}

		if(isset($_POST['template'])) {
			$template_sql = "SELECT `template_id`, `template_name` FROM `_PREFIX_templates` WHERE `template_id` = :template_id";
			
			if($user['user_level'] < 5) {
				$template_sql .= " AND `template_usable` = '1'";
			}
			$template_query = $db2->query($template_sql, array(":template_id" => $_POST['template']));
			
			if(!$template_result = $template_query->fetch()) {
				$error .= $lang['Invalid_Template_Selected'];
			}
		} else {
			$_POST['template'] = $user['user_template'];
		}

		if(isset($_POST['language'])) {
			$language_sql = "SELECT `language_id`, `language_name` FROM `_PREFIX_languages` WHERE `language_id` = :language_id";
			if($user['user_level'] < 5) {
				$language_sql .= " AND `language_usable` = '1'";
			}
			$language_query = $db2->query($language_sql, array(":language_id" => $_POST['language']));
			if(!$language_result = $language_query->fetch()) {
				$error .= $lang['Invalid_Language_Selected'];
			}
		} else {
			$_POST['language'] = $user['user_language'];
		}

		if(isset($_POST['Close_Account'])){
			if(strlen($_POST['Close_Account_Reason']) < 1){
				$error .= "No reason mentionned, request aborded"; // TODO : HARDCODED
			}
			else {
				$token = md5(time() . $user['user_id']);

				$template_sql = "INSERT INTO `_PREFIX_users_token` (user_id, token, token_type)
						 VALUES (:user_id, :token, :token_type)";
				$params = array(
					':user_id' => $user['user_id'],
					':token' => $token,
					':token_type' => 1);

				$db2->query($template_sql, $params);

				// TODO : Envoyer un courriel avec le lien get qui contient le hash pour supprimer le compte
				// Sinon.. Envoyer un PM aux admin.

				$get_config = "SELECT *
							FROM `_PREFIX_config`
							WHERE `config_name` = :use_smtp
							OR `config_name` = :url";
				$db2->query($get_config, array(":use_smtp" => "use_smtp", ":url" => "url"));
				$answer = $db2->fetchAll();

				foreach($answer as $key => $value){
					if($value['config_name'] == "url"){
						$url = $value['config_value'];
					}
					else if($value['config_name'] == 'use_smtp'){
						$use_smtp = $value['config_value'];
					}
				}

				if ($use_smtp == 0) {

					echo $url;

					// Get all administrators
					$db2->query("SELECT * FROM `_PREFIX_users` WHERE `user_level` = :admin", array(':admin' => '5'));

					$body = $lang['Body_On_Pm'] . $url . 'profile.php?func=CloseAccount&token=' . $token . $lang['Body_On_Pm_2'] .
							"\r\n\r\n reason : " . $_POST['Close_Account_Reason'];

					while ($administrator = $db2->fetch()) {
						$db2->query("INSERT INTO `" . $db_prefix . "pm`
						VALUES (
						'',
						:title,
						:body,
						:receiver,
						:sender,
						'1',
						'1',
						:pm_time
						)",
							array(
								":title" => $lang['Title_On_Pm'],
								":body" => $body,
								":receiver" => $administrator['user_id'],
								":sender" => $user['user_id'],
								":pm_time" => time()
							)
						);
					}
				}
				// Ça me dit fuckall ces err code là avec le show message...
				showMessage(ERR_CODE_DELETION_CHECK_MAIL, "profile.php?func=edit");
			}
		}

		if(strlen($error) > 0) {
			$tplEditProfile = new Template("edit_profile.tpl");
			$tplEditProfile->setVars(array(
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
				"EOP_TRUE" => ($_POST['email_on_pm'] == "1") ? "checked" : "",
				"EOP_FALSE" => ($_POST['email_on_pm'] == "0") ? "checked" : "",
				"CSRF_TOKEN" => CSRF::getHTML()
			));
			
			if($user['user_avatar_type'] == 0) {
				$tplEditProfile->addToBlock("current_avatar_off", array());
			} else {
				$tplEditProfile->addToBlock("current_avatar_on", array());
			}
			
			$tplEditProfile->addToBlock("error", array("ERRORS" => $error));

			$template_sql = "SELECT `template_id`, `template_name` FROM `_PREFIX_templates`";
			if($user['user_level'] < 5) {
				$template_sql .= " WHERE `template_usable` = '1'";
			}
			
			$template_query = $db2->query($template_sql);
			while($template_result = $template_query->fetch()) {
				$tplEditProfile->addToBlock("template_select_option", array(
					"TEMPLATE_ID" => $template_result['template_id'],
					"TEMPLATE_NAME" => $template_result['template_name'],
					"TEMPLATE_SELECTED" => ($template_result['template_id'] == $_POST['template']) ? "selected=\"selected\"" : ""
				));
			}

			$language_sql = "SELECT `language_id`, `language_name` FROM `_PREFIX_languages`";
			if($user['user_level'] < 5) {
				$language_sql .= " WHERE `language_usable` = '1'";
			}
			
			$language_query = $db2->query($language_sql);
			while($language_result = $language_query->fetch()) {
				$tplEditProfile->addToBlock("language_select_option", array(
					"LANGUAGE_ID" => $language_result['language_id'],
					"LANGUAGE_NAME" => $language_result['language_name'],
					"LANGUAGE_SELECTED" => ($language_result['language_id'] == $_POST['language']) ? "selected=\"selected\"" : ""
				));
			}

			outputPage($tplEditProfile);
			exit();
		} else {
			$oUser = User::findUser($user['user_id']);
			
			if($config['allow_uploaded_avatar'] && !empty($_FILES['Upload_Avatar']['name'])) {
				$filename = $user['username']."_".$_FILES['Upload_Avatar']['name'];

				$filename = preg_replace("# #i", "_", $filename);
				
  		      	if(copy($_FILES['Upload_Avatar']['tmp_name'], $config['avatar_upload_dir'] . "/" . $filename)) {
						unlink("images/avatars/uploads/".$user['user_avatar_location']);
					
					$oUser->setAvatarType(UPLOADED_AVATAR);
					$oUser->setAvatarLocation($filename);
					$oUser->setAvatarDimensions($image_size[0]."x".$image_size[1]);
				} else {
					showMessage(ERR_CODE_PROFILE_CANT_CHANGE_AVATAR, "profile.php?func=edit");
				}
			} else if(isset($_POST['Delete_Avatar'])) {
					unlink("images/avatars/uploads/".$user['user_avatar_location']."");
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
			    $birthday = (strlen($year) == 4) ? $year : ((strlen($year) == 2) ? "19$year" : "0000")
                            ."-".(($month<10) ? "0$month" : $month)."-".(($day<10) ? "0$day" : $day);
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
			showMessage(ERR_CODE_PROFILE_UPDATE_SUCCESS, "profile.php?id=".$user['user_id']);
		}

	} else {
		$sql = $db2->query("SELECT *
			FROM `_PREFIX_users`
			WHERE `user_id` = :user_id",
			array(":user_id" => $user['user_id']));
		
		$oUser = User::findUser($user['user_id']);
		if($oUser != null) {
			$ims = $oUser->getMessengers();
			$tplEditProfile = new Template("edit_profile.tpl");
			$tplEditProfile->setVars(array(
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
			$day = $birthday[2];
			$month = $birthday[1];
			$year = $birthday[0] ?: '';
			$day_options = '';
			for($i = 1; $i<=31; $i++) {
                $day_options .= fetch_make_options($i, $i, $day);
            }
            
			$month_options = '';
			for($i = 1; $i<=12; $i++) {
                $month_options .= fetch_make_options($i, fetch_months($i), $month);
            }
            
			$year_options = '';
			for($i = 1905; $i <= 2020; $i++) {
                $year_options .= fetch_make_options($i, $i, $year);
            }
            $tplEditProfile->setVars(array(
				"DAY_OPTS"   => $day_options,
				"MONTH_OPTS" => $month_options,
				"YEAR_OPTS"  => $year_options,
			));

			if($oUser->getAvatarType() == NO_AVATAR) {
				$tplEditProfile->addToBlock("current_avatar_off", array());
			} else {
				list($user['user_avatar_width'], $user['user_avatar_height']) = explode("x", $user['user_avatar_dimensions']);
				$avatar_loc = "";
				if($oUser->getAvatarType() == UPLOADED_AVATAR) {
					$avatar_loc = $root_path . $config['avatar_upload_dir'] . "/" . $oUser->getAvatarLocation();
				}
				$tplEditProfile->addToBlock("current_avatar_on", array(
					"AVATAR_LOCATION" => $avatar_loc,
					"AVATAR_HEIGHT" => $user['user_avatar_height'],
					"AVATAR_WIDTH" => $user['user_avatar_width']
				));
			}

			$template_sql = "SELECT `template_id`, `template_name` FROM `_PREFIX_templates`";
			if($user['user_level'] < 5) {
				$template_sql .= " WHERE `template_usable` = '1'";
			}
			
			$template_query = $db2->query($template_sql);
			while($template_result = $template_query->fetch()) {
				$tplEditProfile->addToBlock("template_select_option", array(
					"TEMPLATE_ID" => $template_result['template_id'],
					"TEMPLATE_NAME" => $template_result['template_name'],
					"TEMPLATE_SELECTED" => ($template_result['template_id'] == $oUser->getTemplateId()) ? "selected=\"selected\"" : ""
				));
			}

			$language_sql = "SELECT `language_id`, `language_name` FROM `_PREFIX_languages`";
			if($user['user_level'] < 5) {
				$language_sql .= " WHERE `language_usable` = '1'";
			}
			
			$language_query = $db2->query($language_sql);
			while($language_result = $language_query->fetch()) {
				$tplEditProfile->addToBlock("language_select_option", array(
					"LANGUAGE_ID" => $language_result['language_id'],
					"LANGUAGE_NAME" => $language_result['language_name'],
					"LANGUAGE_SELECTED" => ($language_result['language_id'] == $oUser->getLanguageId()) ? "selected=\"selected\"" : ""
				));
			}

			outputPage($tplEditProfile);
			exit();
		}
	}
}else if($_GET['func'] == "CloseAccount"){
	if($user['user_id'] < 0) {
		showMessage(ERR_CODE_REQUIRE_LOGIN, "login.php");
	}
	if(isset($_GET['token'])){
		// TODO : Delete account
		// GET WHO'S THE TOKEN GIVEN TO

		$token = $_GET['token'];
		$retrieve_user_id_template = "SELECT * FROM `_PREFIX_users_token` where `token`=:token";

		$db2->query($retrieve_user_id_template, array(":token" => $token));

		$user_token = $db2->fetch();
		$user_id = $user_token['user_id'];

		if(isset($user_id)){
			// Remove the user email and set the account as "inactive"
			$remove_account = "UPDATE `ibb_users` SET `user_level` = '-1', `user_email` = '' WHERE `user_id`=:userid";
			$db2->query($remove_account, array(":userid" => $user_id));

			// remove the token
			$remove_token_template = "DELETE FROM `_PREFIX_users_token`
									  WHERE `user_id` = :userid";

			$db2->query($remove_token_template, array(":userid" => $user_id));

			// Then logout user
			if($user['user_level'] < 5) {
				Session::completeLogout();
			}

			showMessage(ERR_CODE_ACCOUNT_DELETED_SUCCESS);
		}
		else{
			showMessage(ERR_CODE_INVALID_TOKEN_ID, "profile.php?func=edit");
		}
	} /*else {
		$oUser = User::findUser($user['user_id']);
		$token = md5(time().$user['user_id']);

		$template_sql = "INSERT INTO `_PREFIX_users_token` (user_id, token, token_type)
						 VALUES (:user_id, :token, :token_type)";
		$params = array(
			':user_id' => $user['user_id'],
			':token' => $token,
			':token_type' => 1);

		$db2->query($template_sql, $params);

		// TODO : Envoyer un courriel avec le lien get qui contient le hash pour supprimer le compte
		// Sinon.. Envoyer un PM aux admin.

		$get_smtp_config = "SELECT *
							FROM `ibb_config`
							WHERE `config_name` = :use_smtp";
		$db2->query($get_smtp_config, array(":use_smtp" => "use_smtp"));
		$use_smtp = $db2->fetch();

		if($use_smtp['config_value'] == 0) {

			// Get all administrators
			$db2->query("SELECT * FROM `_PREFIX_users` WHERE `user_level` = :admin", array(':admin' => '5'));

			$body = $lang['Body_On_Pm'].$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'&token='.$token.$lang['Body_On_Pm_2'];

			while($administrator = $db2->fetch()) {
				$db2->query("INSERT INTO `" . $db_prefix . "pm`
						VALUES (
						'',
						:title,
						:body,
						:receiver,
						:sender,
						'1',
						'1',
						:pm_time
						)",
					array(
						":title" => $lang['Title_On_Pm'],
						":body" => $body,
						":receiver" => $administrator['user_id'],
						":sender" => $user['user_id'],
						":pm_time" => time()
					)
				);
			}
		}
		// Ça me dit fuckall ces err code là avec le show message...
		showMessage(ERR_CODE_DELETION_CHECK_MAIL, "profile.php?func=edit");
	}*/
} else {
	if(!isset($_GET['id']) || $_GET['id'] < 0) {
		showMessage(ERR_CODE_INVALID_USER_ID, "index.php");
    }

    $oUser = User::findUser($_GET['id']);
	$db2->query("SELECT `rank_name` FROM `_PREFIX_ranks` WHERE `rank_id`=:rid", array(":rid" => $oUser->getRankId()));
	$result = $db2->fetch();

	if($oUser != null && isset($result["rank_name"])) {
		$ims = $oUser->getMessengers();
		$tplViewProfile = new Template("view_profile.tpl");
		$tplViewProfile->setVars(array(
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
    	$tplViewProfile->setVars(array(
    		"BIRTHDAY" => $birthday,
    		"USER_AGE" => $age,
    	));

    	// ===========================
    	// Profile Signature
    	// ===========================
    	$sig = $oUser->getSignature();
    	if(!empty($sig)) {
			$tplViewProfile->setVar("SIGNATURE", format_text($sig));
    	} else {
			$tplViewProfile->setVar("SIGNATURE", $lang['None']);
    	}

    	// ===========================
    	// Profile Avatar
    	// ===========================
    	if($oUser->getAvatarType() == UPLOADED_AVATAR || $oUser->getAvatarType() == REMOTE_AVATAR) {
    		if($oUser->getAvatarType() == UPLOADED_AVATAR) {
    			$av_loc = $root_path . $config['avatar_upload_dir'] . "/" . $oUser->getAvatarLocation();
    		}
    		$tplViewProfile->addToBlock("avatar_on", array(
    			"AUTHOR_AVATAR_LOCATION" => $av_loc
    		));
    	} else {
			$tplViewProfile->addToBlock("avatar_off", array());
    	}

    	// ===========================
    	// Profile Online Status
    	// ===========================

		// A session is considered online if the last action time is in the
		// last 30 minutes.
		$online_time_limit = time() - 30 * 60;

    	$online_sql = $db2->query("SELECT *
			FROM `_PREFIX_sessions`
			WHERE `user_id` = :user_id AND `time` > :time_limit;",
			array(
				":user_id" => intval($_GET['id']),
				":time_limit" => $online_time_limit
			)
		);
		
    	if($online_sql->fetch()) {
			$tplViewProfile->setVar("USER_ONLINE", $lang['Online']);
    	} else {
			$tplViewProfile->setVar("USER_ONLINE", $lang['Offline']);
    	}

    	// ===========================
    	// Profile Page Output
    	// ===========================
    	outputPage($tplViewProfile);
    	exit();
    } else {
		showMessage(ERR_CODE_INVALID_USER_ID, "index.php");
    }
}
?>
