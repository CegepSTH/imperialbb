<?php 
// WebService :^) 
if(!defined("IBB_WEB_SERVICE")) {
	header("Location: ../index.php");
}
define("IN_IBB", 1);
$root_path = "../";
require_once("../includes/config.php");
require_once("../classes/database.php");
require_once("../includes/functions.php");
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
		global $database;
		
		$oDb = new Database($database, $database["prefix"]);
		$values = array(":name" => $infos["cat_name"], ":orderby" => $infos["cat_orderby"]);
		$oDb->query("INSERT INTO _PREFIX_categories (`cat_name`, `cat_orderby`) 
			VALUES (:name, :orderby)", $values);
		
		return ($oDb->lastInsertId() > 0);
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
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		$values = array(":name" => $infos["forum_name"], ":orderby" => $infos["forum_orderby"],
			":catId" => $infos["forum_cat_id"], ":desc" => $infos["forum_desc"], ":type" => $infos["forum_type"], 
			":read" => $infos["forum_read"], ":post" => $infos["forum_post"], ":reply" => $infos["forum_reply"],
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
		global $database;
		
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
		
		global $database;
		
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
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT `forum_id`, `forum_name`, `forum_description`, `forum_topics`, `forum_posts`
			FROM `_PREFIX_forums` 
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
		global $database;
		
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
	public static function getTopicPostsList($n_topicId, $n_start, $n_end) {
		if(!is_numeric($n_topicId) || !is_numeric($n_start) 
			|| !is_numeric($n_end)) {
				
			return array();
		}
		
		global $database;
		
		$query = "SELECT `post_id`, `post_user_id`, `post_text`, `post_timestamp`,
			`username`, `user_signature`, `user_avatar_location`, `rank_name` 
			FROM `_PREFIX_posts` 
			JOIN `_PREFIX_users` ON `user_id` = `post_user_id`
			JOIN `_PREFIX_ranks` ON `rank_id` = `user_rank`
			WHERE `post_topic_id`=:topicId
			ORDER BY `post_id` ";
		
		// For the limit clause, there's worlds of differences between DBMS
		// MsSQL is not supported, because it's /quite/ difficult.
		if($database["dbtype"] == "mysql") {
			$query .= "LIMIT ".$n_start.",".$n_end;
		} else if($database["dbtype"] == "pgsql") {
			$query .= "LIMIT ".$n_end." OFFSET ".$n_start;
		}
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query($query, array(":topicId" => $n_topicId));
		
		$unformatted = $oDb->fetchAll();
		
		foreach($unformatted as $key => $value) {
			//$unformatted[$key]["post_text"] = unbbcode($value["post_text"]);
			if(trim($value["user_signature"]) != "") {
				$unformatted[$key]["user_signature"] = "____________\r\n".$value["user_signature"];
			}
		}
		
		$oDb->query("SELECT `topic_views` FROM `_PREFIX_topics` WHERE `topic_id`=:tid LIMIT 1", 
			array(":tid" => $n_topicId));
		
		$views = $oDb->fetch();
		
		// Update to say we viewed the topic. 
		$oDb->query("UPDATE `_PREFIX_topics` SET `topic_views`=:count WHERE `topic_id`=:tid", 
			array(":count" => intval($views['topic_views'] + 1), ":tid" => $n_topicId));
		
		if(is_null($unformatted)) {
			return array();
		}
		
		return $unformatted;
	} 
	
	/**
	 * Gets topic list for the specified forum.
	 * 
	 * @param $n_forumId Forum's id
	 * @param $n_start First post position
	 * @param $n_end Last post position
	 * 
	 * @returns Topics array ordered by categories and orderby.
	 */
	public static function getTopicsList($n_forumId, $n_start, $n_end) {
		if(!is_numeric($n_forumId) || !is_numeric($n_start) 
			|| !is_numeric($n_end)) {
				
			return array();
		}
		global $database;
		
		$query = "SELECT `topic_id`, `topic_title`, `username` AS `topic_author`, `topic_replies`, `topic_views`, `topic_forum_id` 
			FROM `_PREFIX_topics` 
				JOIN `_PREFIX_users` ON `topic_user_id` = `user_id`
			WHERE `topic_forum_id`=:forumId
			ORDER BY `topic_id`";
		
		// For the limit clause, there's worlds of differences between DBMS
		// MsSQL is not supported, because it's /quite/ difficult.
		if($database["dbtype"] == "mysql") $query .= "LIMIT ".$n_start.",".$n_end;
		else if($database["dbtype"] == "pgsql") $query .= "LIMIT ".$n_end." OFFSET ".$n_start;
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query($query, array(":forumId" => $n_forumId));
		
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
		
		global $database;
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT `user_id`, `username`, `user_signature`, `user_email`, 
		`user_date_joined`, `user_rank`, `user_posts`, `user_location`, `user_website`,
		`user_avatar_location`, `user_birthday`
		FROM `_PREFIX_users` 
		WHERE `user_id`=:id", array(":id" => $id));
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
		
		global $database;
		
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
	 * Sends post data to topic.
	 * 
	 * @param $str_data Post data. 
	 * @param $n_userId User's id.
	 * @param $n_topicId Topic's id.
	 * 
	 * @returns True if success, false otherwise.
	 */
	public static function sendPostToTopicId($str_data, $n_userId, $n_topicId) {
		if(empty($str_data) || !is_string($str_data)) {
			return false;
		}
		
		if(!is_numeric($n_userId) || $n_userId < 0) {
			return false;
		}
		
		if(!is_numeric($n_topicId) || $n_topicId < 0) {
			return false;
		}
		
		global $database;
		echo "WHAT";
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("INSERT INTO `_PREFIX_posts` (`post_topic_id`, `post_user_id`,
			`post_text`, `post_timestamp`) 
			VALUES (:tid, :user_id, :post_body, :post_time)",
			array(":tid" => $n_topicId,
				":user_id" => $n_userId,
				":post_body" => $str_data,
				":post_time" => time()
				));
		
		$rows = ($oDb->lastInsertId() > 0);
		$postId = $oDb->lastInsertId();
		
		$oDb->query("UPDATE `_PREFIX_topics` SET `topic_last_post`=:pid 
			WHERE `topic_id`=:tid", 
			array(":pid" => $postId, ":tid" => $n_topicId));
			
		$oDb->query("SELECT `topic_forum_id` FROM `_PREFIX_forums` 
			WHERE `topic_id`=:tid LIMIT 1", array(":tid" => $n_topicId));
		$result = $oDb->fetch();
		
		$oDb->query("UPDATE `_PREFIX_forums` SET `forum_last_post`=:pid 
			WHERE `forum_id`=:fid", array(":pid" => $postId,
			":fid" => $result["topic_forum_id"]));
		
		return $rows;
	}
	
	/**
	 * Saves the new topic into the specified forum.
	 * 
	 * @param $n_forumId Forum's id.
	 * @param $n_userId User's id. 
	 * @param $str_title Topic's title.
	 * @param $str_data Topic's first post/message.
	 * @return True if success, false otherwise.
	 */
	public static function sendTopicToForumId($n_forumId, $n_userId, $str_title, $str_data) {
		if(!is_numeric($n_forumId) || !is_numeric($n_userId) 
			|| empty($str_title) || empty($str_data)) 
		{
			return false;
		}
		
		global $database;
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("INSERT INTO `_PREFIX_topics` (`topic_forum_id`,
			`topic_title`, `topic_poll_title`, `topic_user_id`, `topic_time`)
			VALUES (:fid, :title, :poll_title, :user_id, :time )",
			array(":fid" => $n_forumId,
				":title" => $str_title,
				":poll_title" => "",
				":user_id" => $n_userId,
				":time" => time() ));
				
		$topicId = $oDb->lastInsertId();
		
		if($topicId < 0) {
			return false;
		}
		
		$oDb->query("INSERT INTO `_PREFIX_posts` (
			`post_topic_id`, `post_user_id`, `post_text`, `post_timestamp`,
			`post_disable_html`, `post_disable_bbcode`, `post_disable_smilies`,
			`post_attach_signature`)
			VALUES (:tid, :user_id, :post_body, :post_time, :disable_html,
				:disable_bbcode, :disable_smilies, :attach_signature )",
			array(":tid" => $topicId,
				":user_id" => $n_userId,
				":post_body" => $str_data,
				":post_time" => time(),
				":disable_html" => 0,
				":disable_bbcode" => 0,
				":disable_smilies" => 0,
				":attach_signature" => 1));
		// Post id.
		$postId = $oDb->lastInsertId();
		
		if($postId < 0) {
			return false;
		}
		
		// Update forum info
		$sql = $oDb->query("SELECT * FROM `_PREFIX_forums`
			WHERE `forum_id` = :fid LIMIT 1",
			array( ":fid" => $n_forumId ));
				
		if($row = $sql->fetch()) {
			$new_topics = $row['forum_topics'] + 1;
			$new_posts = $row['forum_posts'] + 1;
			$oDb->query("UPDATE `_PREFIX_forums`
				SET `forum_topics` = :new_topics, `forum_posts` = :new_posts,
					`forum_last_post` = :pid
				WHERE `forum_id` = :fid",
				array(":new_topics" => $new_topics, 
					":new_posts" => $new_posts,
					":pid" => $postId,
					":fid" => $n_forumId));
		}

		$oDb->query("UPDATE `_PREFIX_topics`
			SET `topic_first_post` = :pid1, `topic_last_post` = :pid2
			WHERE `topic_id` = :tid",
			array(":pid1" => $postId,
				":pid2" => $postId,
				":tid" => $topicId));
		
		return ($oDb->rowCount() > 0);
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
		global $database;
		$query = "UPDATE `_PREFIX_users` 
			SET `user_signature`=:signature, `user_location`=:location, `user_website`=:website
			WHERE `user_id`=:uid";
		$values = array(":uid" => $infos["id"], 
			":website" => $infos["website"],
			":location" => $infos["location"], 
			":signature" => $infos["signature"]);

		$oDb = new Database($database, $database["prefix"]);
		$oDb->query($query, $values);
		
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
		
		global $database;
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT * FROM `_PREFIX_sessions` WHERE `session_id`=:token", 
			array(":token" => $str_token));
			
		$result = $oDb->fetch();
		
		if(is_null($result)) {
			return false;
		} else {
			// If token is expired.
			if(($result["created_time"] + 3600 * 2) < time()) {
				return false;
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
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT * FROM `_PREFIX_api_apps` WHERE `app_token`=:token LIMIT 1", 
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
		$id = User::findUser($str_user);
		
		if($id == null) {
			return false;
		}
		
		global $database;
		
		$ok = User::check($str_user, $str_pass);

		if($ok < 0) {
			return false;
		}

		$db = new Database($database, $database["prefix"]);
		$db->query("SELECT `rank_name` FROM `_PREFIX_ranks` 
			WHERE `rank_id`=:rid LIMIT 1", 
			array(":rid" => $id->getRankId()));
		
		$rank = $db->fetch();
		
		$return["id"] = $id->getId();
		$return["token"] = "app_".md5(time()."".$id->getId());
		$return["username"] = $id->getUsername();
		$return["date_joined"] = $id->getDateJoined();
		$return["email"] = $id->getEmail();
		$return["avatar_location"] = $id->getAvatarLocation();
		$return["posts_count"] = $id->getPostsCount();
		$return["signature"] = $id->getSignature();
		$return["rank_name"] = $rank["rank_name"];
		$return["website"] = $id->getWebsite();
		$return["location"] = $id->getLocation();

		// Delete possible previous user app token,
		$db->query("DELETE FROM `_PREFIX_sessions` WHERE `user_id`=:uid AND `session_id` LIKE '%app\$_'", 
		array(":uid" => $id->getId()));
		
		// Insert new user token.
		$db->query("INSERT INTO `_PREFIX_sessions` (`session_id`, `ip`, `user_id`, `time`, `time_created`)
			VALUES (:token, :ip, :uid, :time, :timec)", 
			array(":token" => $return["token"], ":ip" => $ip, ":uid" => $id->getId(), 
				":time" => time(), ":timec" => time()));
			
		
		return $return;
	}
}

/**
 * Validate access.
 */
function ValidateAccess() {
	// Check if token was supplied.
	if(!isset($_GET['tok']) || trim($_GET['tok']) == "") {
		$error = array(
			"error" => "NO_TOKEN_ERROR", 
			"error_level" => "FATAL",
			"error_msg" => "No token was given.");
	
		$json = json_encode($error);
	
		echo $json;
		exit();
	} else {
		// Verify if user is ok to access.
		if(ImperialService::checkAppToken($_GET['tok']) == false) {
			$error = array(
				"error" => "BAD_TOKEN_BAD_ERROR",
				"error_level" => "FATAL", 
				"error_msg" => "Specified token is invalid.");
			$json = json_encode($error);
			echo $json;
			exit();
		}
	}
	
	// Is action specified?
	if(!isset($_GET['act']) || trim($_GET['act']) == "") {
		$error = array(
			"error" => "NO_ACTION_ERROR", 
			"error_level" => "FATAL",
			"error_msg" => "No action was specified.");
	
		$json = json_encode($error);
	
		echo $json;
		exit();	
	}
}

?>
