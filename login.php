<?php
define("IN_IBB", 1);

$root_path = "./";
$ignore_offline = true;
require_once($root_path . "includes/common.php");
require_once($root_path . "classes/password.php");
require_once($root_path . "models/user.php");

$language->add_file("login");
Template::addNamespace("L", $lang);

if(!isset($_GET['func'])) $_GET['func'] = "";


if($_GET['func'] == "activate")
{
	if(!isset($_GET['user_id'])) {
		showMessage(ERR_CODE_LOGIN_ACTIVATION_ERROR);
	}
	
	if(!isset($_GET['key'])) {
		showMessage(ERR_CODE_LOGIN_ACTIVATION_ERROR);
	}
	
	$activationStatus = User::activate(intval($_GET['user_id']), $_GET['key']);
	
	// If done properly, show message.
	if($activationStatus == 0) {
		showMessage(ERR_CODE_LOGIN_ACTIVATION_SUCCESS);
	} elseif ($activationStatus == 1) {
		showMessage(ERR_CODE_LOGIN_ALREADY_ACTIVATED);
	} else {
		showMessage(ERR_CODE_LOGIN_ACTIVATION_ERROR);
	}
}
else if($_GET['func'] == "logout")
{
	Session::completeLogout();
	
	showMessage(ERR_CODE_LOGGED_OUT);
}
else if($_GET['func'] == "forgotten_pass")
{
	if(isset($_POST['Submit'])) {
		CSRF::validate();

		if(!isset($_POST['username']) || !isset($_POST['email'])) {
			showMessage(ERR_CODE_LOGIN_RESET_PASSWORD_INVALID_ID);
		}

		$oUser = User::findUser($_POST['username']);
		
		if($oUser == null) {
			showMessage(ERR_CODE_LOGIN_RESET_PASSWORD_ERROR);
		}
		
		if($oUser->getEmail() != trim($_POST['email'])) {
			showMessage(ERR_CODE_LOGIN_RESET_PASSWORD_ERROR);
		}
		
		$oUser->setActivationKey();
		$password = generate_activate_key(7);
		$oUser->update();

		if($result = $query->fetch()) {				
			email($lang['Forgotten_Password_Email_Subject'], "forgotten_password", array(
				"DOMAIN" => $config['url'],
				"USER_ID" => $oUser->getId()."",
				"USERNAME" => $oUser->getUsername(),
				"PASSWORD" => $oUser->getActivationKey(),
				"KEY" => $oUser->getActivationKey()
			), $oUser->getEmail());
		} else {
			showMessage(ERR_CODE_LOGIN_RESET_PASSWORD_INVALID_ID);
		}
	} else {
		$page_master = new Template("forgotten_password.tpl");
		$page_master->setVars(array(
			"CSRF_TOKEN" => CSRF::getHTML()
		));

		$page_title = $config['site_name'] . " &raquo; " . $lang['Forgotten_Password'];
		outputPage($page_master, $page_title);
		exit();
	}
}
else if($_GET['func'] == "activate_new_pass")
{
	if(!isset($_GET['user_id'])) {
		showMessage(ERR_CODE_LOGIN_ACTIVATION_ERROR);
	}
	if(!isset($_GET['key'])) {
		showMessage(ERR_CODE_LOGIN_ACTIVATION_ERROR);
	}
	$oUser = User::findUser($_GET['user_id']);
	
	if($oUser->getActivationKey() == $_GET['key']) {
		$oUser->setPassword($oUser->getActivationKey());
		$oUser->updatePassword();
		$oUser->setActivationKey("null");
		$oUser->update();
		showMessage(ERR_CODE_LOGIN_ACTIVATION_SUCCESS);
	}
	
	showMessage(ERR_CODE_LOGIN_ACTIVATION_ERROR);
} else {
	if($user['user_id'] > 0) {
		showMessage(ERR_CODE_LOGIN_ALREADY_LOGGED_IN);
	}
	
	if(isset($_POST['Submit'])) {
		CSRF::validate();

		$user_id = User::check($_POST['UserName'], $_POST['PassWord']);
		$oUser = User::findUser($user_id);

		if($user_id > -1) {
			if($oUser->getLevel() == -1) {
				// Account closed.
				showMessage(ERR_CODE_ACCOUNT_CLOSED, "login.php");
			}

			Session::refreshCurrent($oUser->getId());

			if(isset($_POST['KeepConnected'])) {

				$persist_login = true;
				$persistance_duration = 0;
				$seconds_in_day = 24 * 60 * 60;
				switch($_POST['KeepConnected']) {
					case PLOGIN_DUR_DAY:
						$persistance_duration = 1 * $seconds_in_day;
					break;
					case PLOGIN_DUR_WEEK:
						$persistance_duration = 7 * $seconds_in_day;
					break;
					case PLOGIN_DUR_MONTH:
						$persistance_duration = 31 * $seconds_in_day;
					break;
					case PLOGIN_DUR_HALF_YEAR:
						$persistance_duration = 182 * $seconds_in_day;
					break;
					case PLOGIN_DUR_YEAR:
						$persistance_duration = 365 * $seconds_in_day;
					break;
					case PLOGIN_DUR_FOREVER:
						$persistance_duration = 5 * 365 * $seconds_in_day;
					break;
					default:
						$persist_login = false;
					break;
				}

				if($persist_login) {
					Session::persistLogin($oUser->getId(), $persistance_duration);
				}
			}

			showMessage(ERR_CODE_LOGIN_SUCCESS);
		} else {
			showMessage(ERR_CODE_LOGIN_INVALID_ID);
		}
	} else {
		$page_master = new Template("login.tpl");
		$page_master->setVars(array(
			"CSRF_TOKEN" => CSRF::getHTML()
		));

		$page_title = $config['site_name'] . " &raquo; " . $lang['Login'];
		outputPage($page_master, $page_title);
		exit();
	}
}
?>
