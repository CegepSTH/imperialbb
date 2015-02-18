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

class Session {
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
			self::createNew();
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
