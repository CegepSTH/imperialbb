<?php 
// WebService :^) 
if(!defined("IBB_WEB_SERVICE")) {
	header("Location: ../index.php");
}

require_once("../includes/config.php");
require_once("../classes/database.php");
require_once("../models/user.php");

// Service class.
class ImperialService {

	/**
	 * Creates a category with the given infos.
	 * 
	 * @param $infos Array $key => value. {"name" => string, "orderby" => numeric}
	 * 
	 * @returns True if successfully inserted, false otherwise.
	 */
	public static function createCategory(array $infos) {
		if (!isset($infos["cat_name"]) || !isset($infos["cat_orderby"])) {
			return false;
		}
		
		$oDb = new Database($database, $database["prefix"]);
		$values = new array(":name" => $infos["cat_name"], ":orderby" => $infos["cat_orderby"]);
		$oDb->query("INSERT INTO _PREFIX_categories (`cat_name`, `cat_orderby`) 
			VALUES (:name, :orderby)", $values);
		
		return $oDb->lastInsertId() > 0 ? true : false;
	}
	
	/**
	 * Creates a forum with the given infos.
	 * 
	 * @param $infos Array $key => value.
	 * 
	 * @example {"cat_id" => numeric, "forum_type" => char(1), 
	 * 		"name" => string, "desc" => string, "read" => numeric, 
	 * 		"post" => numeric, "reply" => numeric, "poll" => numeric, 
	 * 		"create_poll" => numeric, "mod" => numeric, "orderby" => numeric, 
	 * 		"redirect" => string}
	 * 
	 * @note If redirect equals to "NULL" (all caps), then it will be translated 
	 * 		as an empty string / null value (normal behavior).
	 * 
	 * @returns True if successfully inserted, false otherwise.
	 */
	public static function createForum(array $infos) {
		if (!isset($infos["forum_name"]) || !isset($infos["forum_orderby"])
			|| !isset($infos["forum_desc"]) || !isset($infos["forum_type"]) 
			|| !isset($infos["forum_cat_id"]) || !isset($infos["forum_read"])
			|| !isset($infos["forum_post"]) || !isset($infos["forum_reply"]) 
			|| !isset($infos["forum_poll"]) || !isset($infos["forum_create_poll"]) 
			|| !isset($infos["forum_mod"]) || !isset($infos["forum_redirect_url"])) {
			return false;
		}
		
		$oDb = new Database($database, $database["prefix"]);
		$values = new array(":name" => $infos["forum_name"], ":orderby" => $infos["forum_orderby"],
			":catId" => $infos["forum_cat_id"], ":desc" => $infos["forum_desc"], ":type" => $infos["forum_type"], 
			":read" => $infos["forum_read"], ":post" => $infos["forum_post"], ":reply" => $infos["forum_reply"]
			":poll" => $infos["forum_poll"], ":cpoll" => $infos["forum_create_poll"],
			":mod" => $infos["forum_mod"], ":redirect" => $infos["forum_redirect_url"] == "NULL" ? null : $infos["forum_redirect_url"]);
		
		$oDb->query("INSERT INTO _PREFIX_forums (`forum_cat_id`, `forum_type`,
				`forum_name`, `forum_description`, `forum_topics`, `forums_posts`,
				`forum_last_post`, `forum_read`, `forum_post`, `forum_reply`, `forum_poll`,
				`forum_create_poll`, `forum_mod`, `forum_orderby`, `forum_redirect_url`)
			VALUES (:catId, :type, :name, 0, 0, 0, :read, :post, :reply, :poll, 
				:cpoll, :mod, :orderby, :redirect)", $values);
		
		return $oDb->lastInsertId() > 0 ? true : false;
	}
	
	/**
	 * Creates a new user.
	 * 
	 * @param $infos Array $key => value
	 * 
	 * @returns True if successfully inserted, false otherwise.
	 */
	public static function createUser($infos) {
		if(!isset($infos["username"]) || !isset($infos["password"]) 
			|| !isset($infos["email"]) || !isset($infos["level"]) 
			|| !isset($infos["rank"]) || !isset($infos["birthday"])) {
			return true;
		}
		
		$oUser = new User(-1, $infos["username"], $infos["email"]);
		$oUser->setPassword($infos["password"]);
		$oUser->setLevel($infos["level"]);
		$oUser->setBirthday($infos["birthday"]);
		$ok = $oUser->update();
		
		return $ok;
	}
	
	/**
	 * Gets the memberlist with the given method.
	 * 
	 * @param $str_method Defines the order by method. Valid values:
	 * 		USERNAMES, IDS, POSTS, DATE_JOINED, LAST_VISIT, LOCATION
	 * 
	 * @param $n_start Starting position of the list. Cannot be negative.
	 * 
	 * @param $n_end Ending position of the list (limit). Cannot be negative.
	 * 
	 * @returns Associative array where $key is the user id.
	 * 		Returns empty array if no data found.
	 */
	public static function getMembersList($str_method, $n_start, $n_end) {
		if(!is_string($str_method) || !is_numeric($n_start)
			|| !is_numeric($n_end)) {
			
			return array();
		} else if ($n_start < 0 || $n_end < 0) {
			return array();
		}
		
		$oDb = new Database($database, $database["prefix"]);
		$query = "SELECT * FROM `_PREFIX_users` "; 
		
		switch($str_method) {
			case "USERNAMES":
				$query .= "ORDER BY `username` ";
			break;
			case "IDS":
				$query .= "ORDER BY `user_id` ";
			break;
			case "POSTS":
				$query .= "ORDER BY `user_posts` ";
			break;
			case "DATE_JOINED":
				$query .= "ORDER BY `user_date_joined` ";
			break;
			case "LAST_VISIT":
				$query .= "ORDER BY `user_lastvisit` ";
			break;
			case "LOCATION":
				$query .= "ORDER BY `user_location` ";
			break;
			default:
				$query .= "ORDER BY `user_id` ";
			break;
		}
		
		$start = min($n_start, $n_end); 
		$end = max($n_start, $n_end);
		
		// For the limit clause, there's worlds of differences between DBMS
		// MsSQL is not supported, because it's /quite/ difficult.
		if($database["dbtype"] == "mysql") $query .= "LIMIT ".$start.",".$end;
		else if($database["dbtype"] == "pgsql") $query .= "LIMIT ".$end." OFFSET ".$start;
		
		$unformatted = $oDb->fetchAll();
		
		if(is_null($unformatted)) {
			return array();
		}
		
		return $unformatted;
	}
	
	/**
	 * Gets all forums as an array.
	 * 
	 * @returns Returns the raw array.
	 */
	public static function getAllForumsList() {
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT * FROM `_PREFIX_forums` 
			ORDER BY `forum_type`, `forum_id`");
		
		$unformatted = $oDb->fetchAll();
		
		if(is_null($unformatted)) {
			return array();
		}
		
		return $unformatted;
	}
	
	/**
	 * Gets all categories as a list.
	 * 
	 * @returns An array of all categories.
	 */
	public static function getAllCategoriesList() {
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT * FROM `_PREFIX_categories` 
			ORDER BY `cat_orderby`, `cat_name`");
		
		$unformatted = $oDb->fetchAll();
		
		if(is_null($unformatted)) {
			return array();
		}
		
		return $unformatted;
	}
	
	/**
	 * Gets all posts from the specified topic
	 * 
	 * @param $n_topicId Topic's id.
	 * 
	 * @param $n_start First post position
	 * 
	 * @param $n_end Last post position
	 * 
	 * @returns An of all posts.
	 */
	public static function getForumPostsList($n_topicId, $n_start, $n_end) {
		if(!is_numeric($n_topicId) || !is_numeric($n_start) 
			|| !is_numeric($n_end)) {
				
			return array();
		}
		
		$query = "SELECT * FROM `_PREFIX_posts` 
			WHERE `post_topic_id`=:topicId
			ORDER BY `post_id` ";
		
		// For the limit clause, there's worlds of differences between DBMS
		// MsSQL is not supported, because it's /quite/ difficult.
		if($database["dbtype"] == "mysql") $query .= "LIMIT ".$start.",".$end;
		else if($database["dbtype"] == "pgsql") $query .= "LIMIT ".$end." OFFSET ".$start;
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query($query, array(":topicId" => $n_topicId));
		
		$unformatted = $oDb->fetchAll();
		
		if(is_null($unformatted)) {
			return array();
		}
		
		return $unformatted;
	} 
	
	/**
	 * Gets topic list for the specified forum.
	 * 
	 * @param $n_forumId Forum's id
	 * 
	 * @param $n_start First post position
	 * 
	 * @param $n_end Last post position
	 * 
	 * @returns Topics array ordered by categories and orderby.
	 */
	public static function getTopicsList($n_forumId, $n_start, $n_end) {
		if(!is_numeric($n_topicId) || !is_numeric($n_start) 
			|| !is_numeric($n_end)) {
				
			return array();
		}
		
		$query = "SELECT * FROM `_PREFIX_topics` 
			WHERE `forum_id`=:forumId
			ORDER BY `forum_cat_id`, `forum_orderby`";
		
		// For the limit clause, there's worlds of differences between DBMS
		// MsSQL is not supported, because it's /quite/ difficult.
		if($database["dbtype"] == "mysql") $query .= "LIMIT ".$start.",".$end;
		else if($database["dbtype"] == "pgsql") $query .= "LIMIT ".$end." OFFSET ".$start;
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query($query, array(":forumId" => $n_topicId));
		
		$unformatted = $oDb->fetchAll();
		
		if(is_null($unformatted)) {
			return array();
		}
		
		return $unformatted;
	}
	
	/**
	 * Gets the user informations
	 * 
	 * @param $id User's id.
	 * 
	 * @returns Returns user informations as array.
	 */ 
	public static function getUserInfo($id) {
		if(!is_numeric($id)) {
			return array();
		}
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT * FROM `_PREFIX_users` WHERE `user_id`=:id", array(":id" => $id));
		$result = $oDb->fetch();
		
		if(is_null($result)) {
			return array();
		}
		
		return $result;
	}
	
	/**
	 * Sets category informations.
	 * 
	 * @param $infos Array containing informations for a given category.
	 * 
	 * @returns True if success, false otherwise.
	 */
	public static function setCategoryInfos(array $infos) {
		if(!is_array($infos)) {
			return false;
		} else if (!isset($infos["cat_id"]) || !isset($infos["cat_name"])
			|| !isset($infos["cat_orderby"])) {
			return false;
		}
		
		$values = array(":name" => $infos["cat_name"], ":orderby" => $infos["cat_orderby"],
			":id" => $infos["cat_id"]);
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("UPDATE `_PREFIX_categories` 
			SET `cat_name`=:name, `cat_orderby`=:orderby 
			WHERE `user_id`=:id", $values);
		$ok = $oDb->rowCount();
		
		return ($ok > 0);
	}
	
	/**
	 * Sets the user informations
	 * 
	 * @param $infos Array containing user informations.
	 * 
	 * @note Password must be encrypted beforehand (BCRYPT)
	 * 
	 * @returns True if success, false otherwise.
	 */
	public static function setUserInfo(array $infos) {
		
		$values = array(":username" => $infos["username"], ":upass" => $infos["user_password"], 
			":email" => $infos["user_email"], ":level" => intval($infos["user_level"]), 
			":group" => intval($infos["user_usergroup"]), ":signature" => $infos["signature"], 
			":rank" => intval($infos["user_rank"]), ":aim" => $infos["user_aim"],
			":icq" => $infos["user_icq"], ":msn" => $infos["user_msn"], ":yahoo" => $infos["user_yahoo"],
			":epm" => $infos["user_email_on_pm"], ":tid" => intval($infos["user_template"]), 
			":lid" => intval($infos["user_language"]), ":timez" => $infos["user_timezone"], 
			":posts" => intval($infos["user_posts"]), ":location" => $infos["user_location"],
			":website" => $infos["user_website"], ":bday" => $infos["user_birthday"],
			":uid" => $infos["user_id"]);
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("UPDATE `_PREFIX_users` 
			SET `username`=:username, `user_password`=:upass, 
				`user_email`=:email, `user_level`=:level, `user_usergroup`=:group,
				`user_signature`=:signature, `user_rank`=:rank, `user_aim`=:aim, 
				`user_icq`=:icq, `user_msn`=:msn, `user_yahoo`=:yahoo, 
				`user_email_on_pm`=:epm, `user_template`=:tid, `user_language`=:lid,
				`user_timezone`=:timez, `user_posts`=:posts, `user_location`=:location, 
				`user_website`=:website, `user_birthday`=:bday
			WHERE `user_id`=:uid", $values);
		$result = $oDb->fetch();
		
		$ok = $oDb->rowCount();
		
		return ($ok > 0);
	}
	
	/**
	 * Checks if the API temporary token is valid.
	 * 
	 * @returns true if success, false otherwise.
	 */
	public static function checkTempToken($str_token) {
		if(!is_string($str_user) || !is_string($str_token)) {
			return false;
		}
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT * FROM `_PREFIX_sessions` WHERE `session_id`=:token", 
			array(":token" => $str_token));
			
		$result = $oDb->fetch();
		
		if(is_null($result)) {
			return false;
		} else {
			if($result["created_time"]) {
			
			}
			
			return true;
		}
	}
	
	/**
	 * Checks if the application token is valid. 
	 * 
	 * @returns True if valid, false otherwise.
	 */
	public static function checkAppToken($str_token) {
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT * FROM `_PREFIX_api_apps` WHERE `app_token`=:token", 
			array(":token" => $str_token));
		
		$result = $oDb->fetch();
		
		if(is_null($result)) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Tries to login the user and create a token for validating request.
	 * 
	 * @returns array: User id and user temp token. 
	 */
	public static function login($str_user, $str_pass, $ip) {
		$return = array();
		$id = User::check($str_user, $str_pass);
		
		if(id != -1) {
			return false;
		}
		
		$return["id"] = $id;
		$return["token"] = md5(time()."".$id);
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("INSERT INTO `_PREFIX_sessions` (`session_id`, `ip`, `user_id`, `time`, `time_created`)
			VALUES (:token, :ip, :uid, :time, :timec)", 
			array(":token" => $return["token"], ":ip" => $ip, ":uid" => $id, 
				":time" => time(), ":timec" => time()));
			
		
		return $return;
	}
}

?>
