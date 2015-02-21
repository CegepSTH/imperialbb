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

		$query = $db2->query("SELECT `user_id`, `username`, `user_email`
			FROM `_PREFIX_users`
			WHERE `username` = :username AND `user_email` = :email",
			array(
				":username" => $_POST['username'],
				":email" => $_POST['email']
			));

		if($result = $query->fetch()) {
			$key = generate_activate_key();
			$password = generate_activate_key(7);
			$db2->query("UPDATE `_PREFIX_users`
				SET `user_activation_key` = :key,
				`user_new_password` = :password,
				`user_password_reset_request` = :current_time
				WHERE `user_id` = :user_id",
				array(
					":key" => $key,
					":password" => password_hash($password, PASSWORD_BCRYPT),
					":current_time" => time(),
					":user_id" => $result['id']
				));
				
			email($lang['Forgotten_Password_Email_Subject'], "forgotten_password", array(
				"DOMAIN" => $config['url'],
				"USER_ID" => $result['user_id'],
				"USERNAME" => $result['username'],
				"PASSWORD" => $password,
				"KEY" => $key
			), $result['user_email']);
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

	$sql = $db2->query("SELECT `user_id` FROM `_PREFIX_users`
		WHERE `user_id` = :user_id && `user_activation_key` = :key
		LIMIT 1",
		array(
			":user_id" => $_GET['user_id'],
			":key" => $_GET['key'],
		));
	
	if($result = $sql->fetch()) {
		$db2->query("UPDATE `_PREFIX_users`
			SET `user_password` = `new_password`,
			`user_activation_key` = '',
			`user_password_reset_request` = '',
			`user_new_password` = ''
			 WHERE `user_id` = :user_id",
			array(
				":user_id" => $_GET['user_id']
			)
		);

		showMessage(ERR_CODE_LOGIN_ACTIVATION_SUCCESS);
	} else {
		showMessage(ERR_CODE_LOGIN_ACTIVATION_ERROR);
	}
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
