<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: sessions.php                                               # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

require_once($root_path."classes/csrf.php");

class Session {
	const PERSISTENT_LOGIN_COOKIE_KEY = "PersistentLoginToken";

	public static function createNew($user_id = -1) {
		global $db2;

		session_regenerate_id();

		$current_time = time();
		$db2->query("INSERT INTO `_PREFIX_sessions` (
				`session_id`,
				`ip`,
				`user_id`,
				`time`,
				`time_created`
			) VALUES (
				:session_id,
				:remote_ip,
				:user_id,
				:time1,
				:time2
			);",
			array(
				":session_id" => session_id(),
				":remote_ip" => $_SERVER['REMOTE_ADDR'],
				":user_id" => $user_id,
				":time1" => $current_time,
				":time2" => $current_time
			)
		);

		$_SESSION["user_id"] = $user_id;
	}

	public static function delete($session_id) {
		global $db2;

		$db2->query("DELETE FROM `_PREFIX_sessions`
			WHERE `session_id` = :session_id",
			array(
				':session_id' => $session_id
			)
		);
	}

	public static function refresh($session_id, $new_user_id) {
		self::delete($session_id);
		self::createNew($new_user_id);
	}

	public static function refreshCurrent($new_user_id) {
		self::refresh(session_id(), $new_user_id);
	}

	public static function updateSessionTime($session_id) {
		global $db2;

		$db2->query("UPDATE `_PREFIX_sessions`
			SET `time` = :time
			WHERE `session_id` = :session_id",
			array(
				":time" => time(),
				":session_id" => $session_id
			)
		);
	}

	public static function updateCurrentSessionTime() {
		self::updateSessionTime(session_id());
	}

	public static function start() {
		global $db2;

		session_start();
		$current_id = session_id();

		$db2->query("SELECT COUNT(*) AS `session_count` FROM _PREFIX_sessions
			WHERE `session_id` = :session_id",
			array(
				":session_id" => $current_id
			)
		);

		$result = $db2->fetch();
		if($result["session_count"] != 1) {
			if(!self::continuePersistentLogin()) {
				self::createNew();
			}
		}
	}

	public static function persistLogin($user_id, $persistance_duration) {
		global $db2;

		$token = CSRF::generateToken();

		$absolute_expiration_time = time() + $persistance_duration;
		$db2->query("INSERT INTO `_PREFIX_persistent_logins` (
				`persistance_token`,
				`user_id`,
				`expiry_time`
			) VALUES (
				:token,
				:user_id,
				:expiry_time
			);",
			array(
				":token" => $token,
				":user_id" => $user_id,
				":expiry_time" => $absolute_expiration_time
			)
		);

		setcookie(self::PERSISTENT_LOGIN_COOKIE_KEY,
			$token,
			$absolute_expiration_time
		);
	}

	private static function refreshPersistentLogin($absolute_expiration_time) {
		global $db2;

		$old_token = $_COOKIE[self::PERSISTENT_LOGIN_COOKIE_KEY];

		$new_token = CSRF::generateToken();
		$db2->query("UPDATE `_PREFIX_persistent_logins`
			SET `persistance_token` = :new_token
			WHERE `persistance_token` = :old_token",
			array(
				":new_token" => $new_token,
				":old_token" => $old_token
			)
		);

		setcookie(self::PERSISTENT_LOGIN_COOKIE_KEY,
			$new_token,
			$absolute_expiration_time
		);
	}

	/**
	 * continuePersistentLogin Continues a persistent login.
	 * 
	 * @return If a persistent login was resumed.
	 */
	public static function continuePersistentLogin() {
		global $db2;

		if(!isset($_COOKIE[self::PERSISTENT_LOGIN_COOKIE_KEY])) {
			return false;
		}

		$token = $_COOKIE[self::PERSISTENT_LOGIN_COOKIE_KEY];
		$db2->query("SELECT * FROM `_PREFIX_persistent_logins`
			WHERE `persistance_token` = :token",
			array(
				":token" => $token
			)
		);

		$result = $db2->fetch();
		if(!$result) {
			return false;
		}

		// Persistance expired, erase it and do nothing.
		if(time() > $result["expiry_time"]) {
			deletePersistentLogin($token);

			return false;
		}

		self::createNew($result["user_id"]);
		self::refreshPersistentLogin($result["expiry_time"]);
		return true;
	}

	private static function deletePersistentLogin($token) {
		global $db2;

		$db2->query("DELETE FROM `_PREFIX_persistent_logins`
			WHERE `persistance_token` = :token",
			array(
				":token" => $token
			)
		);

		// Clear cookie.
		setcookie(self::PERSISTENT_LOGIN_COOKIE_KEY,
			"",
			time() - 3600
		);
	}

	public static function completeLogout() {
		self::refreshCurrent(-1);

		if(isset($_COOKIE[self::PERSISTENT_LOGIN_COOKIE_KEY])) {
			$token = $_COOKIE[self::PERSISTENT_LOGIN_COOKIE_KEY];
			self::deletePersistentLogin($token);
		}
	}

	public static function updateLastVisitTime() {
		global $db2, $user;

		if($_SESSION["user_id"] <= 0) {
			return;
		}

		$db2->query("UPDATE `_PREFIX_users`
			SET `user_lastvisit` = :time
			WHERE `user_id` = :user_id",
			array(
				":time" => time(),
				":user_id" => $_SESSION["user_id"]
			)
		);
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
