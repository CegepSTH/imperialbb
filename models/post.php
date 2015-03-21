<?php
if(!isset($root_path)) {
$root_path = "./";
}

require_once($root_path."includes/config.php");
require_once($root_path."classes/database.php");
require_once($root_path."includes/functions.php");

class Post
{
	// Basic post informations.
	private $m_postId;
	private $m_topicId;
	private $m_content;
	private $m_userId;
	
	// Post options
	public $disableHtml;
	public $disableBBCode;
	public $disableSmilies;
	public $joinSignature;
	
	private $m_postDate;
	
	/**
	 * @param $n_topicId Topic's id
	 * @param $str_content Post's content
	 * @param $n_userId Author's id. -1 is Guest. 
	 * @param $b_dBBCode Disable bbcode Default: false
	 * @param $b_dSmilies Disables smilies Default: false
	 * @param $n_postTime Post's time. 
	 * @param $b_dHtml Disable HTML. Default: true. Legacy.
	 */
	public function __construct($n_topicId, $str_content, $n_userId)
	{
		$b_dBBcode = false;
		$b_dSmilies = false; 
		$b_joinSignature = true; 
		$n_postDate = time(); 
		$b_dHtml = true;
		
		$this->m_postId = null;
		$this->setTopicId($n_topicId);
		$this->setContent($str_content);
		$this->setUserId($n_userId);
		$this->disableBBCode = (bool)$b_dBBcode;
		$this->disableSmilies = (bool)$b_dSmilies;
		$this->joinSignature = (bool)$b_joinSignature;
		$this->setDate($n_postDate);
		$this->disableHtml = (bool)$b_dHtml;
	}
	
	/**
	 * Gets the post with the specified id.
	 *  
	 * @param $n_postId Post's id.
	 * @return null if no valid entry found. Otherwise return appropriate
	 * post.
	 */
	public static function findPost($n_postId) {
		if(!is_numeric($n_postId)) {
			die("Post::findPost was given a non-numerical value. Value passed: '".$n_postId."'");
		}
		
		global $database;
		
		$oDb = new Database($database, $database["prefix"]);
		$oDb->query("SELECT * FROM `_PREFIX_posts` 
			WHERE `post_id`=:pid
			LIMIT 1", array(":pid" => intval($n_postId)));
		
		$result = $oDb->fetch();
		
		// Create Post object.
		$oPost = new Post($result['post_topic_id'], $result['post_text'],
			$result['post_user_id'], $result['post_disable_bbcode'] == 1, 
			$result['post_disable_smilies'] == 1, $result['post_attach_signature'] == 1, 
			$result['post_timestamp']);
		
		//Set data
		$oPost->m_postId = $result['post_id'];
		$oPost->disableBBCode = $result['post_disable_bbcode'] == 1;
		$oPost->disableSmilies = $result['post_disable_smilies'] == 1;
		$oPost->joinSignature = $result['post_attach_signature'] == 1;
		$oPost->setDate($result['post_timestamp']);
		$oPost->disableHtml = $result['post_disable_html'];	
		
		return $oPost;
	}
	
	/**
	 * Saves the changes of the post. 
	 * If the post is new, it will call insert() function. 
	 * Otherwise, just update the post in DB.
	 * 
	 * @return True if success, false otherwise.
	 */
	public function update() {
		if(is_null($this->m_postId)) {
			return $this->insert();
		}

		global $database;
		$oDb = new Database($database, $database["prefix"]);
		
		$values = array(":content" => $this->m_content, 
			":bbcode" => $this->disableBBCode ? 1 : 0, 
			":smilies" => $this->disableSmilies ? 1 : 0, 
			":html" => $this->disableHtml ? 1 : 0, 
			":signature" => $this->joinSignature ? 1 : 0, 
			":time" => $this->m_postDate, 
			":pid" => $this->m_postId);
			
		$oDb->query("UPDATE `_PREFIX_posts` 
			SET `post_text`=:content, `post_disable_bbcode`=:bbcode, 
				`post_disable_smilies`=:smilies, `post_disable_html`=:html, 
				`post_attach_signature`=:signature, `post_timestamp`=:time
			WHERE `post_id`=:pid", $values);
		
		
		return ($oDb->rowCount() > 0);
	}
	
	/**
	 * Gets the post id. If not saved yet, returns -1.
	 */
	public function getPostId() {
		return $this->m_postId ?: -1;
	}
	
	/**
	 * Sets the post id.
	 * @param $n_postId Post's id.
	 */
	private function setPostId($n_postId) {
		if(is_numeric($n_postId)) {
			$this->m_postId = intval($n_postId);
		} else {
			die("Post->setPostId failed. Needed a valid numerical value."
				." Value passed: '".$n_postId."'");
		}
	}
	
	/**
	 * Gets the topic's id.
	 */
	public function getTopicId() {
		return $this->m_topicId;
	}
	
	/**
	 * Sets the topic id.
	 * 
	 * @param $n_topicId Post numeric integer.
	 */
	public function setTopicId($n_topicId) {
		if(is_numeric($n_topicId)) {
			$this->m_topicId = intval($n_topicId);
		} else {
			die("Post->setTopicId failed. Must be valid "
				."integer. Value passed was '".$n_topicId."'");
		}
	}
	
	/**
	 * Gets the post content.
	 * @return Post's content.
	 */
	public function getContent() {
		return $this->m_content;
	}
	
	/**
	 * Sets the post content
	 * 
	 * @param $str_content Post's content.
	 */
	public function setContent($str_content) {
		if(is_string($str_content)) {
			$this->m_content = $str_content;
		} else {
			die("Post->setContent failed. Must be valid string. Value "
				."passed was '".$str_content."'");				
		}
	}
	
	/**
	 * Gets the author user's id.
	 * @retunr User's id.
	 */
	public function getUserId() {
		return $this->m_userId;
	}
	
	/**
	 * Sets the author user's id.
	 * @param $n_userId User's id.
	 */
	public function setUserId($n_userId) {
		if(is_numeric($n_userId)) {
			$this->m_userId = intval($n_userId);
		} else {
			die("Post->setUserId failed. Must be valid integer. Value "
				."passed was '".$n_userId."'");	
		}
	}
	
	/**
	 * Gets the post timestamp.
	 */
	public function getDate() {
		return $this->m_postDate;
	}
	
	/**
	 * Set the post date.
	 * @param $n_timeStamp Post's timestamp. time() is default.
	 */
	public function setDate($n_timeStamp = null) {
		$n_timeStamp = $n_timeStamp ?: time();
		if(is_numeric($n_timeStamp)) {
			$this->m_postDate = intval($n_timeStamp);
		} else {
			die("Post->setDate failed. Must be valid date. Value "
				."passed was '".$n_timeStamp."'");	
		}
	}
	
	/**
	 * Inserts the post in the database.
	 * Must have a topic id set. 
	 */
	private function insert() {
		if(!isset($this->m_topicId)) {
			return false;
		}
		
		if($this->m_topicId < 0) {
			return false;
		}
		
		global $database;
		$oDb = new Database($database, $database["prefix"]);
		
		$values = array(":content" => $this->m_content, 
			":bbcode" => $this->disableBBCode ? 1 : 0, 
			":smilies" => $this->disableSmilies ? 1 : 0, 
			":html" => $this->disableHtml ? 1 : 0, 
			":signature" => $this->joinSignature ? 1 : 0, 
			":time" => $this->m_postDate, 
			":tid" => $this->m_topicId,
			":puid" => $this->m_userId);

		$query = "INSERT INTO `_PREFIX_posts` (`post_text`, `post_disable_bbcode`, 
			`post_disable_smilies`, `post_disable_html`, `post_attach_signature`, 
			`post_timestamp`, `post_topic_id`, `post_user_id`) 
			VALUES (:content, :bbcode, :smilies, :html, :signature, :time,
				:tid, :puid)";
		
		$oDb->query($query, $values);
		$lastId = $oDb->lastInsertId();
		 
		if($lastId > 0) {
			$this->setPostId($lastId);
			
			// Update parent data (topic post count, forum post count)
			$oDb->query("SELECT `topic_replies`, `topic_first_post`, 
					`topic_last_post`, `forum_id`, `forum_posts`, 
					`forum_last_post`, `forum_topics` 
				FROM `_PREFIX_topics`
				JOIN `_PREFIX_forums` ON `forum_id`=`topic_forum_id`
				WHERE `topic_id`=:tid
				LIMIT 1", array(":tid" => $this->m_topicId));
			$result = $oDb->fetch(); 

			// Update the topic
			$oDb->query("UPDATE `_PREFIX_topics`
				SET `topic_replies`=:replies, `topic_first_post`=:fpost, 
					`topic_last_post`=:lpost, `topic_time`=:time
				WHERE `topic_id`=:tid 
				LIMIT 1", 
				array(":replies" => ($result['topic_first_post'] < 1) ? ($result['topic_replies'] + 1) : 0,
					":fpost" => $result['topic_first_post'] < 1 ? $lastId : $result['topic_first_post'], 
					":lpost" => $lastId, 
					":time" => time(),
					":tid" => $this->m_topicId));
					
			$ok = $oDb->rowCount() > 0;
			
			if(!$ok) {
				return false;
			}
			
			// Update the forum
			$oDb->query("UPDATE `_PREFIX_forums`
				SET `forum_posts`=:posts, `forum_last_post`=:lpost,
					`forum_topics`=:topics
				WHERE `forum_id`=:fid
				LIMIT 1", 
				array(":posts" => ($result['forum_posts'] + 1),
					":lpost" => $lastId,
					":topics" => $result['topic_first_post'] < 1 ? ($result['forum_topics'] + 1) : $result['forum_topics'],
					":fid" => $result['forum_id']));
					
			$ok = $oDb->rowCount() > 0;
			
			if(!$ok) {
				return false;
			}
			
			// Totally forgot User post count
			$oDb->query("SELECT `user_posts` FROM `_PREFIX_users` 
				WHERE `user_id`=:uid LIMIT 1", 
				array(":uid" => $this->getUserId()));
			
			$result = $oDb->fetch();
			
			$ok = $oDb->query("UPDATE `_PREFIX_users` SET `user_posts`=:posts
				WHERE `user_id`=:uid", array(":uid" => $this->getUserId(), 
				":posts" => $result["user_posts"] + 1));
			
			return $ok;
		} else {
			return false;
		}
	}
	
	/**
	 * Deletes a post from the database.
	 * 
	 * @param $n_postId Post's id.
	 */
	public static function delete($n_postId) {
		if(!is_numeric($n_postId)) {
			return false;
		}
		
		global $database;
	
		$oDb = new Database($database, $database["prefix"]);
		
		// when deleting a post, you need to decrement the counts in 
		// forum and topic. First off, we'll need to know what is the 
		// count of posts in the topic, then forum
		// This is an issue coming form MyISAM engine.
		
		// Also, we need to cover the use of last_post and first_post
		// in both _forums and _topics.
		$query = "SELECT `topic_first_post`, `topic_last_post`, 
				`forum_last_post`, `topic_replies`, `topic_time`,
				`forum_id`, `forum_posts`, `topic_id`, `topic_views`, 
				`user_id`, `user_posts`
			FROM `_PREFIX_posts`
				JOIN `_PREFIX_topics` ON `topic_id` = `post_topic_id`
				JOIN `_PREFIX_forums` ON `forum_id` = `topic_forum_id`
				JOIN `_PREFIX_users` ON `user_id` = `post_user_id`
			WHERE `post_id`=:pid LIMIT 1";
		$oDb->query($query, array(":pid" => $n_postId));
		$result = $oDb->fetch();
		
		if($result["user_id"] != -1) {
			// First update user and decrement his posts count.
			$oDb->query("UPDATE `_PREFIX_users` SET `user_posts`=:posts
				WHERE `user_id`=:uid", 
				array(":uid" => intval($result["user_id"]),
					":posts" => intval($result["user_posts"]) - 1));
		}
		
		// Update  last and first post of topic.
		if($result["topic_last_post"] == $n_postId) {
			// Update with new last post id.
			$oDb->query("SELECT MAX(`post_id`) AS `post_id` 
				FROM `_PREFIX_posts` AS `p`
				WHERE `p`.`post_id` <> :pid AND `post_topic_id`=:tid", 
				array(":pid" => intval($n_postId), 
					":tid" => intval($result["topic_id"])));
			
			$sub_res = $oDb->fetch();
			
			$oDb->query("SELECT `post_timestamp` FROM `_PREFIX_posts`
				WHERE `post_id`=:pid", array(":pid" => $sub_res["post_id"]));
			$sub_res_time = $oDb->fetch();
			
			$result["topic_last_post"] = $sub_res["post_id"];
			$result["topic_time"] = $sub_res_time["post_timestamp"];
		}
		
		// Update the topic replies (and posts id (l/f)
		$oDb->query("UPDATE `_PREFIX_topics` SET `topic_first_post`=:fpost,
			`topic_last_post`=:lpost, `topic_replies`=:replies, 
			`topic_time`=:time
			WHERE `topic_id`=:tid",
				array(":fpost" => intval($result["topic_first_post"]), 
				":lpost" => intval($result["topic_last_post"]), 
				":replies" => intval($result["topic_replies"]) - 1,
				":time" => intval($result["topic_time"]),
				":tid" => intval($result["topic_id"])));
				
		if($oDb->rowCount() <= 0) {
			return false;
		}
		
		// Update forum.
		if($result["forum_last_post"] == $n_postId) {
			$oDb->query("SELECT MAX(`post_id`) AS `last_post_id` FROM `_PREFIX_forums` 
				JOIN `_PREFIX_topics` ON `topic_forum_id`=:fid
				JOIN `_PREFIX_posts` ON `post_topic_id` = `topic_id` 
				WHERE `post_id` <> :pid",
				array(":fid" => intval($result["forum_id"]), 
					":pid" => intval($n_postId)));
			$lastIdResult = $oDb->fetch();
			$result["forum_last_post"] = $lastIdResult["last_post_id"];
		}
		
		$oDb->query("UPDATE `_PREFIX_forums` SET `forum_last_post`=:lpost,
			`forum_posts`=:posts
			WHERE `forum_id`=:fid", 
			array(":lpost" => intval($result["forum_last_post"]),
				":posts" => intval($result["forum_posts"]) - 1,
				":fid" => intval($result["forum_id"])));
		
		if($oDb->rowCount() <= 0) {
			return false;
		}
		
		// Delete post, finally.
		$oDb->query("DELETE FROM `_PREFIX_posts` WHERE `post_id`=:pid",
			array(":pid" => intval($n_postId)));
		
		return $oDb->rowCount() > 0;
	}
}

?>
