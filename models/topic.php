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
		
		if(!($post instanceof Post) && !is_null($post)) {
			die("Topic::__construct : First post not valid Post object.");
		}
		
		$this->setUserId($n_userId);
		$this->setForumId($n_forumId);
		$this->setTitle($str_title);
		$this->setPollTitle("");
		$this->setStatus(0);
		$this->setType(0);
		$this->setFirstPostId(-1);
		$this->setLastPostId(-1);
		$this->m_repliesCount = 0;
		$this->m_viewsCount = 0;
		$this->m_post = $post;
	}
	
	/**
	 * Returns a Topic from DB Result
	 * 
	 * @param $dbResult Database Result array. 
	 * @return Topic object if true, false otherwise.
	 */
	public static function fromDbResult(array &$dbResult) {
		if(empty($dbResult)) {
			return null;
		}
		$topic = new Topic($dbResult["topic_user_id"], $dbResult["topic_forum_id"],
			$dbResult["topic_title"], null);
		$topic->m_pollTitle = $dbResult["topic_title"];
		$topic->m_status = intval($dbResult["topic_status"]);
		$topic->m_type = intval($dbResult["topic_type"]);
		$topic->m_firstPostId = intval($dbResult["topic_first_post"]);
		$topic->m_lastPostId = intval($dbResult["topic_last_post"]);
		$topic->m_repliesCount = intval($dbResult["topic_replies"]);
		$topic->m_viewsCount = intval($dbResult["topic_views"]);
		$topic->m_modification = intval($dbResult["topic_time"]);
		
		return $topic;
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
			return $this->insert();
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
				`topic_first_post`=:fpost,
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
		
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		
		$this->m_modificationTime = time();
		
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
		$query = "INSERT INTO `_PREFIX_topics` (`topic_forum_id`, `topic_title`, 
			`topic_poll_title`, `topic_status`, `topic_type`, 
			`topic_first_post`, `topic_user_id`, `topic_replies`, 
			`topic_views`, `topic_last_post`, `topic_time`) 
			VALUES(:fid, :title, :ptitle, :status, :type, :fpost, :uid, :rcount,
				:vcount, :lpost, :time)";
		
		$oDb->query($query, $values);
		
		$this->setTopicId($oDb->lastInsertId());
		
		
		$this->m_post->setTopicId($this->getTopicId());
		$this->m_post->update();
		
		$this->setFirstPostId($this->m_post->getPostId());
		$this->setLastPostId($this->m_post->getPostId());
		$this->update();
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
	
	/**
	 * Deletes the specified topic with the given id.
	 * 
	 * @param $n_topicId Topic's id.
	 * @return True if success, false otherwise.
	 */
	public static function delete($n_topicId) {
		if(!is_numeric($n_topicId)) {
			return false; 
		}
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		
		$query = "SELECT `forum_last_post`, `topic_replies`, 
				`forum_id`, `forum_posts`, `forum_topics`
			FROM `_PREFIX_topics`
				JOIN `_PREFIX_forums` ON `forum_id` = `topic_forum_id`
			WHERE `topic_id`=:tid LIMIT 1";
		$oDb->query($query, array(":tid" => $n_topicId));
		
		// Topic data.
		$topicData = $oDb->fetch();
		
		if(is_null($topicData)) {
			return false;
		}
		
		// Get users id and posts to remove.
		$sql = "SELECT `post_user_id`, `user_posts`, COUNT(*) AS `user_topic_posts_count`
			FROM `_PREFIX_posts`
				JOIN `_PREFIX_users` ON `user_id` = `post_user_id`
			WHERE `post_topic_id`=:tid
			GROUP BY `post_user_id`";
		$oDb->query($sql, array(":tid" => intval($n_topicId)));
		
		// Prepare future users queries.
		$usersSQL =""; 
		while($uInfos = $oDb->fetch()) {
			// So sad... 
			$postCount = intval($uInfos["user_posts"]) - intval($uInfos["user_topic_posts_count"]); 
			$usersSQL = $usersSQL."UPDATE `_PREFIX_users` 
				SET `user_posts`=".$postCount."
				WHERE `user_id`=".intval($uInfos["post_user_id"]).";";
		}
		
		// Update users.
		$oDb->query($usersSQL, array());
		
		// Delete posts.
		$oDb->query("DELETE FROM `_PREFIX_posts` WHERE `post_topic_id`=:tid",
			array(":tid" => intval($n_topicId)));
		// Delete topic.
		$oDb->query("DELETE FROM `_PREFIX_topics` WHERE `topic_id`=:tid",
			array(":tid" => intval($n_topicId)));
		
		// Get last forum post. 
		$oDb->query("SELECT MAX(`post_id`) AS `id` 
			FROM `_PREFIX_forums`
				JOIN `_PREFIX_topics` ON `topic_forum_id`=:fid 
				JOIN `_PREFIX_posts` ON `post_topic_id`=`topic_id`
			WHERE `forum_id`=:fid",
			array(":fid" => intval($topicData["forum_id"])));
		$postId = $oDb->fetch();
		
		// Update forum.
		$oDb->query("UPDATE `_PREFIX_forums` 
			SET `forum_posts`=:posts, `forum_topics`=:topics, 
				`forum_last_post`=:lpost
			WHERE `forum_id`=:fid", 
			array(":posts" => intval($topicData["forum_posts"]) - intval($topicData["topic_replies"]), 
				":topics" => intval($topicData["forum_topics"]) - 1, 
				":lpost" => $postId["id"],
				":fid" => intval($topicData["forum_id"])));
				
		return true;
	}
}

?>
