<?php
if(!isset($root_path)) {
$root_path = "./";
}

require_once($root_path."includes/config.php");
require_once($root_path."classes/database.php");
require_once($root_path."includes/functions.php");

class Post
{
	private $m_postId;
	private $m_topicId;
	private $m_title;
	private $m_content;
	private $m_userId;
	private $m_postDate;
	
	/**
	 * Gets the post with the specified id.
	 *  
	 * @param $n_postId Post's id.
	 * @return null if no valid entry found. Otherwise return appropriate
	 * post.
	 */
	public static function findPost($n_postId) {
	
	}
	
	/**
	 * @param $n_topicId Topic's id
	 * @param $str_title Post's title
	 * @param $str_content Post's content
	 * @param $n_userId Author's id. -1 is Guest. 
	 * @param $n_postTime Post's time. 
	 */
	function __construct($n_topicId, $str_title, $str_content, $n_userId = -1, 
		$n_postDate = time()) {
		
		$this->m_postId = null;
		$this->setTopicId($n_topicId);
		$this->setTitle($str_title);
		$this->setContent($str_content);
		$this->setUserId($n_userId);
		$this->setDate($n_postDate);
	}
	
	/**
	 * Gets the post id. If not saved yet, returns -1.
	 */
	public function getPostId() {
		return $this->m_postId ?: -1;
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
	private function setTopicId($n_topicId) {
		if(is_numeric($n_topicId)) {
			$this->m_topicId = intval($n_topicId);
		} else {
			die("Post->setTopicId [from CTOR] failed. Must be valid ".
				."integer. Value passed was '".$n_topicId."'");
		}
	}
	
	/**
	 * Gets the post title.
	 * 
	 * @return Post's title.
	 */
	public function getTitle() {
		return $this->m_title;
	}
	
	/**
	 * Sets the post title.
	 * 
	 * @param $str_title Post's title.
	 */
	public function setTitle($str_title) {
		if(is_string($str_title)) {
			$this->m_title = $str_title;
		} else {
			die("Post->setTitle failed. Must be valid string. Value ".
				."passed was '".$str_title."'");			
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
			die("Post->setContent failed. Must be valid string. Value ".
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
			die("Post->setUserId failed. Must be valid integer. Value ".
				."passed was '".$n_userId."'");	
		}
	}
	
	/**
	 * 
	 */
	public function getDate() {
		return $this->m_postDate;
	}
	
	/**
	 * Set the post date.
	 * @param $n_timeStamp Post's timestamp. time() is default.
	 */
	public function setDate($n_timeStamp = time()) {
		if(is_numeric($n_timeStamp)) {
			$this->m_postDate = intval($n_timeStamp);
		} else {
			die("Post->setDate failed. Must be valid date. Value ".
				."passed was '".$n_timeStamp."'");	
		}
	}
	
	public function update() {
	}
}

?>
