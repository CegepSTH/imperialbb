<?php
if(!isset($root_path)) {
$root_path = "./";
}

require_once($root_path."includes/config.php");
require_once($root_path."classes/database.php");
require_once($root_path."includes/functions.php");

class Forum {
	private $m_forumId;
		
	private $m_catId;
	private $m_type;
	private $m_name;
	private $m_desc;
	private $m_topicsCount;
	private $m_repliesCount;
	private $m_lastPostId;
	
	// Permissions
	private $m_pRead;
	private $m_pPost;
	private $m_pReply;
	private $m_pPoll;
	private $m_pCreatePoll;
	private $m_pMod;
	
	// Settings
	private $m_orderBy;
	private $m_redirectUrl;
	
	/**
	 * Gets the forum with specified id.
	 * 
	 * @param $n_forumId Forum's id.
	 * @return Forum object if found, null otherwise.
	 */
	public static function findForum($n_forumId) {
		if(!is_numeric($n_forumId)) {
			return null;
		}
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		
		$oDb->query("SELECT * FROM `_PREFIX_forums` 
			WHERE `forum_id`=:fid LIMIT 1",
			array(":fid" => intval($n_forumId)));
		$results = $oDb->fetch();	
		
		if(is_null($results) || $results == false) {
			return null;
		}
		
		// Fill forum info.
		return self::fillForumInfos($results);
	}
	
	/**
	 * Gets the topics in the specified forum
	 * 
	 * @param $n_forumId Forum's id.
	 * @return List of topics in forum.
	 */
	public static function getTopicsListByForumId($n_forumId, $n_start = 0, $n_end = 50) {
		$lstTopics = array();
		
		if(!is_numeric($n_forumId)) {
			return $lstTopics;
		}
		
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		
		$oDb->query("SELECT * FROM `_PREFIX_topics` 
			WHERE `topic_forum_id`=:fid
			LIMIT ".intval($n_start).", ".intval($n_end), 
			array(":fid" => intval($n_forumId)));
		
		while($result = $oDb->fetch()) {
			$topic = Topic::fromDbResult($result);
			$lstTopics[] = $topic;
		}
		
		return $lstTopics;
	}
	
	/**
	 * Gets the forums in the specified forum
	 * 
	 * @param $n_catId Category id. Can be a forum or real category.
	 * @param $b_nested If true, will show nested forums, otherwise, 
	 * 		only subforums.
	 * @return Multi-dimensionnal array or plain array depending on 
	 * 		$b_nested value (multi by default). Maximum depth: 
	 * 		array[cat_id][forum_id] => Forum object
	 */
	public static function getForums($n_catId = null, $b_nested = true) {
		$lstForums = array();
		
		if(!is_numeric($n_catId) && !is_null($n_catId)) {
			return $lstForums;
		}
		
		global $database;
		
		$oDb = new Database($database, $database["prefix"]);
		
		if($n_catId == null) {
			$oDb->query("SELECT * FROM `_PREFIX_forums`", array());
		
			while($result = $oDb->fetch()) {
				$forum = self::fillForumInfos($result);
				$lstForums[$forum->getCatId()][] = $forum;
			}
		} else {
			$oDb->query("SELECT * FROM `_PREFIX_forums` WHERE `forum_cat_id`=:cid",
				array(":cid" => intval($n_catId)));
		
			while($result = $oDb->fetch()) {
				$forum = self::fillForumInfos($result);
				$lstForums[$forum->getCatId()][] = $forum;
			}			
		}
		
		// This one would've been easy: 
		/*
		WITH cte AS 
			(
			SELECT a.Id, a.parentId, a.name
			FROM customer a
			WHERE Id = @Id
			UNION ALL
			SELECT a.Id, a.parentid, a.Name
			FROM customer a JOIN cte c ON a.parentId = c.id
			)
		SELECT parentId, Id, name
		FROM cte
		 */
		// but hey, we're using MySQL, so no CTEs for us!
		 
		return $lstForums;
	}
	
	/**
	 * Gets the forum id.
	 */
	public function getForumId() {
		return $this->m_forumId;
	}
	
	/**
	 * Sets the forum id.
	 * 
	 * @param $n_forumId Forum's id.
	 */
	public function setForumId($n_forumId) {
		if(!is_numeric($n_forumId)) {
			die("Forum::setForumId is not numeric: ".$n_forumId);
		}
		
		$this->m_forumId = intval($n_forumId);
	}
	
	/**
	 * Gets the category id.
	 */
	public function getCatId() {
		return $this->m_catId;
	}
	
	/**
	 * Sets the forum category's id.
	 * 
	 * @param $n_catId Category's id.
	 */
	public function setCatId($n_catId) {
		if(!is_numeric($n_catId)) {
			die("Forum::setCatId is not numeric: ".$n_catId);
		}	
		
		$this->m_catId = intval($n_catId);
	}
	
	/**
	 * Gets the forum type.
	 */
	public function getType() {
		return $this->m_type;
	}
	
	/**
	 * Sets the forum's type.
	 * 
	 * @param $n_type Forum's type.
	 */
	public function setType($n_type) {
		if(!is_numeric($n_type)) {
			die("Forum::setType is not numeric: ".$n_type);
		}
		
		$this->m_type = intval($n_type);
	}
	
	/**
	 * Gets the forum name.
	 */
	public function getName() {
		return $this->m_name;
	}
	
	/**
	 * Sets the forum's name. 
	 * 
	 * @param $str_name Forum's name.
	 */
	public function setName($str_name) {
		if(is_null($str_name) || empty($str_name)) {
			die("Forum::setName name must not be empty/null!");
		}
		
		$this->m_name = $str_name;
	}
	
	/**
	 * Gets the forum's description
	 */
	public function getDescription() {
		return $this->m_desc;
	}
	
	/**
	 * Sets the forum's description
	 * 
	 * @param $str_desc Forum's description
	 */
	public function setDescription($str_desc) {
		if(is_null($str_desc) || empty($str_desc)) {
			die("Forum::setDescription must not be null/empty!");
		}
		
		$this->m_desc = $str_desc;
	}
	
	/**
	 * Gets the forum's topics count
	 */
	public function getTopicsCount() {
		return $this->m_topicsCount;
	}
	
	/**
	 * Gets the forum's messages count.
	 */
	public function getMessagesCount() {
		return $this->m_repliesCount;
	}
	
	/**
	 * Gets the forum's last post id.
	 */
	public function getLastPostId() {
		return $this->m_lastPostId;
	}
	
	/**
	 * Gets the forum's read permission 
	 */
	public function getPermissionRead() {
		return $this->m_pRead;
	}
	
	/**
	 * Sets the forum's read permission
	 * 
	 * @param $n_pe Permission value.
	 */
	public function setPermissionRead($n_pe) {
		if(!is_numeric($n_pe)) {
			die("Forum::setPermissionRead must be numeric: ".$n_pe);
		}
		
		$this->m_pRead = intval($n_pe);
	}
	
	/**
	 * Gets the forum's reply permission
	 */
	public function getPermissionReply() {
		return $this->m_pReply;
	}
	
	/**
	 * Sets the forum's reply permission
	 * 
	 * @param $n_pe Permission value
	 */
	public function setPermissionReply($n_pe) {
		if(!is_numeric($n_pe)) {
			die("Forum::setPermissionReply must be numeric: ".$n_pe);
		}
		
		$this->m_pReply = intval($n_pe);
	}
	
	/**
	 * Gets the forum's post permission
	 */
	public function getPermissionPost() {
		return $this->m_pPost;
	}
	
	/**
	 * Sets the forum's post permission
	 * 
	 * @param $n_pe Permission value
	 */
	public function setPermissionPost($n_pe) {
		if(!is_numeric($n_pe)) {
			die("Forum::setPermissionPost must be numeric: ".$n_pe);
		}
		
		$this->m_pPost = intval($n_pe);
	}	
	
	/**
	 * Gets the forum's poll permission
	 */
	public function getPermissionPoll() {
		return $this->m_pPoll;
	}
	
	/**
	 * Sets the forum's reply permission
	 * 
	 * @param $n_pe Permission value
	 */
	public function setPermissionPoll($n_pe) {
		if(!is_numeric($n_pe)) {
			die("Forum::setPermissionPoll must be numeric: ".$n_pe);
		}
		
		$this->m_pPoll = intval($n_pe);
	}
	
	/**
	 * Gets the forum's create poll permission
	 */
	public function getPermissionCreatePoll() {
		return $this->m_pCreatePoll;
	}
	
	/**
	 * Sets the forum's create poll permission
	 * 
	 * @param $n_pe Permission value
	 */
	public function setPermissionCreatePoll($n_pe) {
		if(!is_numeric($n_pe)) {
			die("Forum::setPermissionCreatePoll must be numeric: ".$n_pe);
		}
		
		$this->m_pCreatePoll = intval($n_pe);
	}
	
	/**
	 * Gets the forum's mod permission
	 */
	public function getPermissionMod() {
		return $this->m_pMod;
	}
	
	/**
	 * Sets the forum's mod permission
	 * 
	 * @param $n_pe Permission value
	 */
	public function setPermissionMod($n_pe) {
		if(!is_numeric($n_pe)) {
			die("Forum::setPermissionMod must be numeric: ".$n_pe);
		}
		
		$this->m_pMod = intval($n_pe);
	}
	
	/**
	 * Gets the forum's order by.
	 */
	public function getOrderby() {
		return $this->m_orderBy;
	}
	
	/**
	 * Sets the forum orderby 
	 * 
	 * @param $n_orderby Order value
	 */ 
	public function setOrderby($n_orderby) {
		if(!is_numeric($n_orderby)) {
			die("Forum::setOrderby must be numeric: ".$n_orderby);
		}
		
		$this->m_orderBy = intval($n_orderby);
	}
	
	/**
	 * Gets the redirection url.
	 */
	public function getRedirectUrl() {
		return $this->m_redirectUrl;
	}
	
	/**
	 * Sets the redirection url
	 * 
	 * @param $str_redirectUrl Can be absolute or relative, or null.
	 */
	public function setRedirectUrl($str_redirectUrl) {
		$this->m_redirectUrl = $str_redirectUrl;
	}
	
	public function __construct() {
	}
	
	/**
	 * Deletes a forum from the forum
	 * 
	 * @param $n_forumId Forum's id.
	 * 
	 * @return True if success, false otherwise.
	 */
	public static function delete($n_forumId) {
		if(!is_numeric($n_forumId)) {
			return false;
		}
		global $database;
		
		// Delete all. #sadness. 
		$sql = "DELETE f, t, p
			FROM `_PREFIX_forums` f
				JOIN `_PREFIX_topics` t ON `t`.`topic_forum_id` = `f`.`forum_id`
				JOIN `_PREFIX_posts` p ON `p`.`post_topic_id` = `t`.`topic_id`
			WHERE `f`.`forum_id` = :fid";
		$values = array(":fid" => intval($n_forumId));
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query($sql, $values);
		
		return $oDb->rowCount() > 0;
	}
	
	/**
	 * Create a forum object and fills it with $dbResult
	 * 
	 * @param $dbResult coming from Database>fetch() => corresponds to 1 row.
	 * @return Forum object if successful, null otherwise.
	 */
	private static function fillForumInfos(array &$dbResult) {
		if(empty($dbResult) || $dbResult == null) {
			return null;
		}
		
		$forum = new Forum();
		$forum->m_forumId = intval($dbResult["forum_id"]);
		$forum->m_catId = intval($dbResult["forum_cat_id"]);
		$forum->m_type = intval($dbResult["forum_type"]);
		$forum->m_name = $dbResult["forum_name"];
		$forum->m_desc = $dbResult["forum_description"];
		$forum->m_topicsCount = intval($dbResult["forum_topics"]);
		$forum->m_repliesCount = intval($dbResult["forum_posts"]);
		$forum->m_lastPostId = intval($dbResult["forum_last_post"]);
		
		// Permissions
		$forum->m_pCreatePoll = intval($dbResult["forum_create_poll"]);
		$forum->m_pMod = intval($dbResult["forum_mod"]);
		$forum->m_pPoll = intval($dbResult["forum_poll"]);
		$forum->m_pPost = intval($dbResult["forum_post"]);
		$forum->m_pRead = intval($dbResult["forum_read"]);
		$forum->m_pReply = intval($dbResult["forum_reply"]);
		
		// Settings
		$forum->m_orderBy = intval($dbResult["forum_orderby"]);
		$forum->m_redirectUrl = $dbResult["forum_redirect_url"] ?: "";
		
		return $forum;
	}
}
?>
