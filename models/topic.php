<?php
if(!isset($root_path)) {
$root_path = "./";
}

require_once($root_path."includes/config.php");
require_once($root_path."classes/database.php");
require_once($root_path."includes/functions.php");
require_once($root_path."models/post.php");

class Topic
{
	private $m_topicId;
	private $m_forumId;
	private $m_title;
	private $m_pollTitle;
	private $m_status;
	private $m_type;
	
	// More informations, pre-calculated. 
	private $m_firstPostId;
	private $m_userId;
	private $m_repliesCount;
	private $m_viewsCount;
	private $m_lastPostId;
	private $m_modificationTime;
	
	// Post; Only for new topics. (CTOR) (First post)
	private $m_post; 
	
	/**
	 * @param $n_userId User's id (Author)
	 * @param $n_forumId Forum's id.
	 * @param $str_title Topic's title. 
	 * @param $post Post's object (first post)
	 */
	public function __construct($n_userId, $n_forumId, $str_title, $post) {
		if(!is_numeric($n_userId)) {
			die("Topic::__construct : Non-numeric userId.");
		} 
		
		if(!is_numeric($n_forumId)) {
			die("Topic::__construct : Non-numeric forumId.");
		}
		
		if(is_null($str_title) || !is_string($str_title) || empty($str_title)) {
			die("Topic::__construct : Non-string content.");
		}
		
		if(is_null($post) || !($post instanceof Post)) {
			die("Topic::__construct : First post not valid Post object.");
		}
		
		$this->setUserId($n_userId);
		$this->setForumId($n_forumId);
		$this->setTitle($str_title);
		$this->m_post = $post;
	}
	
	/**
	 * Finds the topic with the specified id.
	 * 
	 * @param $n_topicId Topic's id.
	 * @return Topic object if found, otherwise returns null.
	 */
	public static function findTopic($n_topicId) {
		if(!is_numeric($n_topicId)) {
			return null;
		}
		
		global $database;
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT * FROM `_PREFIX_topics` 
			WHERE `topic_id`=:tid LIMIT 1",
			array(":tid" => $n_topicId));
		
		if($oDb->rowCount() < 1) {
			return null;
		}
		
		$result = $oDb->fetch();

		$topic = new Topic();
		$topic->setTopicId($result['topic_id']);
		$topic->setForumId($result['topic_forum_id']);
		$topic->setTitle($result['topic_title']);
		$topic->setPollTitle($result['topic_poll_title']);
		$topic->setStatus($result['topic_status']);
		$topic->setType($result['topic_type']);
		$topic->setFirstPostId($result['topic_first_post']);
		$topic->setUserId($result['topic_user_id']);
		$topic->setRepliesCount($result['topic_replies']);
		$topic->setViewsCount($result['topic_views']);
		$topic->setLastPostId($result['topic_last_post']);
		$topic->setLastModificationTime($result['topic_time']);
		
		return $topic;
	}
	
	/**
	 * Updates the current topic. If new, it will insert it. 
	 * 
	 * @return True if success, false otherwise.
	 */
	public function update() {
		if(is_null($this->m_topicId)) {
			return insert();
		}
		
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		
		$values = array(":tid" => $this->m_topicId,
			":fid" => $this->m_forumId,
			":title" => $this->m_title,
			":ptitle" => $this->m_pollTitle,
			":status" => $this->m_status, 
			":type" => $this->m_type, 
			":fpost" => $this->m_firstPostId,
			":uid" => $this->m_userId, 
			":rcount" => $this->m_repliesCount,
			":vcount" => $this->m_viewsCount,
			":lpost" => $this->m_lastPostId, 
			":time" => $this->m_modificationTime);
		
		$oDb->query("UPDATE `_PREFIX_topics` 
			SET `topic_forum_id`=:fid,
				`topic_title`=:title,
				`topic_poll_title`=:ptitle,
				`topic_status`=:status,
				`topic_type`=:type,
				`topic_user_id`=:uid,
				`topic_replies`=:rcount,
				`topic_views`=:vcount,
				`topic_last_post`=:lpost,
				`topic_time`=:time
			WHERE `topic_id`=:tid", $values);
			
		return $oDb->rowCount() > 0;
	}
	
	/**
	 * Inserts the new topic in the database.
	 * Must have a forum's id set.
	 * @return True if success, false otherwise.
	 */
	private function insert() {
		if(!is_numeric($this->m_forumId)) {
			return false;
		}
		/*
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		
		$values = array(":fid" => $this->m_forumId,
			":title" => $this->m_title,
			":ptitle" => $this->m_pollTitle,
			":status" => $this->m_status, 
			":type" => $this->m_type, 
			":fpost" => $this->m_firstPostId,
			":uid" => $this->m_userId, 
			":rcount" => $this->m_repliesCount,
			":vcount" => $this->m_viewsCount,
			":lpost" => $this->m_lastPostId, 
			":time" => $this->m_modificationTime);
		*/
	}
	
	/**
	 * Sets the topic id.
	 * 
	 * @param $n_topicId Topic's id.
	 */
	private function setTopicId($n_topicId) {
		if(!is_numeric($n_topicId)) {
			return;
		}
		
		$this->m_topicId = intval($n_topicId);
	} 

	/**
	 * Gets the topic's id.
	 * 
	 * @return Topic's id.
	 */
	public function getTopicId() {
		return $this->m_topicId;
	}

	/**
	 * Sets the topic forum id.
	 * @param $n_forumId Topic forum's id.
	 */
	public function setForumId($n_forumId) {
		if(!is_numeric($n_forumId)) {
			return;
		}
		
		$this->m_forumId = intval($n_forumId);
	}
	
	/**
	 * @return Topic forum's id.
	 */
	public function getForumId() {
		return $this->m_forumId;
	}
	
	/**
	 * Sets the topic's title.
	 * 
	 * @param $str_title Topic's title.
	 */
	public function setTitle($str_title) {
		if(!is_string($str_title)) {
			return;
		}
		
		$this->m_title = $str_title;
	}
	
	/**
	 * Gets the topic's title.
	 * 
	 * @return Topic's title.
	 */
	public function getTitle() {
		return $this->m_title;
	}
	
	/**
	 * Sets the topic poll's title.
	 * 
	 * @param $str_title Poll's title.
	 */
	public function setPollTitle($str_title) {
		if(!is_string($str_title)) {
			return;
		}
		
		$this->m_pollTitle = $str_title;		
	}
	
	/**
	 * Gets the poll title.
	 * 
	 * @return Poll's title.
	 */
	public function getPollTitle() {
		return $this->m_pollTitle;
	}
	
	/**
	 * Sets the topic's status
	 * 
	 * @param Topic's status
	 */
	public function setStatus($n_status) {
		if(!is_numeric($n_status)) {
			return;
		}
		
		$this->m_status = intval($n_status);
	}
	
	/**
	 * Gets the topic's status. 
	 * 
	 * @param Topic's status
	 */
	public function getStatus() {
		return $this->m_status;
	}
	
	/**
	 * Sets the topic's type.
	 * 
	 * @param $n_type Topic's type.
	 */
	public function setType($n_type) {
		if(!is_numeric($n_type)) {
			return;
		}
		
		$this->m_type = intval($n_type);
	}
	
	/**
	 * Gets the topic type
	 * 
	 * @return Topic's type.
	 */
	public function getType() {
		return $this->m_type;
	}
	
	/**
	 * Sets the first post in topic.
	 * 
	 * @param $n_postId Post's id.
	 */
	private function setFirstPostId($n_postId) {
		if(!is_numeric($n_postId)) {
			return;
		}
		
		$this->m_firstPostId = intval($n_postId);
	}
	
	/**
	 * Gets the first post id. 
	 * 
	 * @return First post id.
	 */
	public function getFirstPostId() {
		return $this->m_firstPostId;
	}
	
	/**
	 * Sets the author's user id.
	 * 
	 * @param $n_userId User id.
	 */
	public function setUserId($n_userId) {
		if(!is_numeric($n_userId)) {
			return;
		}
		
		$this->m_userId = intval($n_userId);
	}
	
	/**
	 * Gets the topic's author user id.
	 * 
	 * @return User's id.
	 */
	public function getUserId() {
		return $this->m_userId;
	}
	
	/**
	 * Sets the topic's replies count.
	 * 
	 * @param $n_count Replies count.
	 */
	private function setRepliesCount($n_count) {
		if(!is_numeric($n_count)) {
			return;
		}
		
		$this->m_repliesCount = intval($n_count);
	}
	
	/**
	 * Gets the topic's replies count.
	 * 
	 * @return Replies count.
	 */
	public function getRepliesCount() {
		return $this->m_repliesCount;
	}
	
	/**
	 * Sets the topic views count.
	 * 
	 * @param $n_count Topic's view count.
	 */
	public function setViewsCount($n_count) {
		if(!is_numeric($n_count)) {
			return;
		}
		
		$this->m_viewsCount = intval($n_count);
	}
	
	/**
	 * Adds a view to the views count.
	 * 
	 * @return Topic's views count.
	 */
	public function addView() {
		$this->m_viewsCount = $this->m_viewCount != 0 ? $this->m_viewCount + 1 : 1;
		return $this->m_viewsCount;
	}
	
	/**
	 * Gets the topic's views count.
	 * 
	 * @return Views count.
	 */
	public function getViewsCount() {
		return $this->m_viewsCount;
	}

	/**
	 * Sets the topic's last post id.
	 * 
	 * @param $n_postId Post's id.
	 */
	private function setLastPostId($n_postId) {
		if(!is_numeric($n_postId)) {
			return;
		}
		
		$this->m_lastPostId = intval($n_postId);
	}
	
	/**
	 * Gets the topic's last post id.
	 * 
	 * @return Post's id.
	 */
	public function getLastPostId() {
		return $this->m_lastPostId;
	}

	/**
	 * Sets the last modification time.
	 * 
	 * @param $n_time Default to time(). 
	 */
	public function setLastModificationTime($n_time = null) {
		if(!is_numeric($n_time)) {
			return;
		}
		
		$this->m_modificationTime = intval($n_time ?: time());
	}
	
	/**
	 * Gets the last modification time.
	 * 
	 * @return timestamp
	 */
	public function getLastModificationTime() {
		return $this->m_modificationTime;
	}
}

?>
