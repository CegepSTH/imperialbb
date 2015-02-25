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

// For token generation.
require_once($root_path."classes/csrf.php");

/**
 * A class to manage user and guest sessions.
 * 
 * Also, manages the last visit date and persistent logins for members.
 * 
 * @author Michael Tran
 */
class Session {
	const PERSISTENT_LOGIN_COOKIE_KEY = "PersistentLoginToken";

	/**
	 * createNew Creates a new session for the specified user id in the database.
	 * 
	 * The value -1 is the user id of guests.
	 * 
	 * @param $user_id The user id to create a session for.
	 */
	private static function createNew($user_id = -1) {
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

	/**
	 * delete Deletes the specified session from the database.
	 * 
	 * @param $session_id The session id to delete.
	 */
	private static function delete($session_id) {
		global $db2;

		$db2->query("DELETE FROM `_PREFIX_sessions`
			WHERE `session_id` = :session_id",
			array(
				':session_id' => $session_id
			)
		);
	}

	/**
	 * refresh Refreshes the specified session with a new user id.
	 * 
	 * Prevents session fixation attacks. The function refresh or
	 * refreshCurrent must be called when the user level changes to
	 * prevent this kind of attack.
	 * 
	 * @param $session_id The session id to refresh.
	 * After this call, the session id will be no longer valid.
	 * @param $new_user_id The new user id of the session.
	 */
	private static function refresh($session_id, $new_user_id) {
		self::delete($session_id);
		self::createNew($new_user_id);
	}

	/**
	 * refreshCurrent Refreshes the current session with a new user id.
	 * 
	 * Prevents session fixation attacks. This function must be called when the
	 * user level changes to prevent this attack. A example of a user level
	 * change is logging in and logging out.
	 * 
	 * @param $new_user_id The new user id of the session.
	 */
	public static function refreshCurrent($new_user_id) {
		self::refresh(session_id(), $new_user_id);
	}

	/**
	 * updateSessionTime Updates the last activity time of the specified
	 * session.
	 * 
	 * Used for online users statistics.
	 * 
	 * @param $session_id The session id to update the last activity time.
	 */
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

	/**
	 * updateCurrentSessionTime Updates the last activity time of the current
	 * session.
	 * 
	 * Used for online users statistics.
	 */
	public static function updateCurrentSessionTime() {
		self::updateSessionTime(session_id());
	}

	/**
	 * start Begins a session, resuming a persisted login if available.
	 * 
	 * This function ensures that the session id was created by PHP's session
	 * system and not controlled by a malicious user.
	 * 
	 * If a session could not be found, an attempt to resume a persisted
	 * login is done, else a new guest session is opened.
	 */
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
				self::createNew(-1);
			}
		} else {
			// PHP expired the session, resume persistent login or create new.
			if(!isset($_SESSION["user_id"])) {
				if(!self::continuePersistentLogin()) {
					self::delete($current_id);
					self::createNew(-1);
				}
			}
		}
	}

	/**
	 * persistLogin Persists a user login across PHP and browser sessions.
	 * 
	 * The persistance is implemented with a token in a cookie.
	 *
	 * @param $user_id The user id to persist the login.
	 * @param $persistance_duration The duration of the persistance,
	 * in seconds.
	 */
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

	/**
	 * refreshPersistentLogin Refreshes the token for a persistent login.
	 * 
	 * The persistent login token should be refreshed each time it is
	 * used (when the start() method resumes a persistent login) to prevent
	 * token reuse.
	 * 
	 * @param $absolute_expiration_time The absolute expiration time,
	 * in seconds.
	 */
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

	/**
	 * deletePersistentLogin Deletes a persistent login.
	 *
	 * Also unsets the cookie containing the token.
	 * Called on logout to clear the persistance.
	 * 
	 * @param $token The persistent login token to delete.
	 */
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

	/**
	 * logout Logouts the current user.
	 * 
	 * This function changes the session's user id to the user id of
	 * guests (-1) and clears the persistent login if present.
	 */
	public static function logout() {
		self::refreshCurrent(-1);

		if(isset($_COOKIE[self::PERSISTENT_LOGIN_COOKIE_KEY])) {
			$token = $_COOKIE[self::PERSISTENT_LOGIN_COOKIE_KEY];
			self::deletePersistentLogin($token);
		}
	}

	/**
	 * updateLastVisitTime Updates the last visit time of the current member.
	 * 
	 * Has no effect on guests.
	 * Used for online indication on user profile and for the list of users
	 * who were online in the last 24 hours.
	 */
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
