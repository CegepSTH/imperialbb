<?php
if(!isset($root_path)) {
$root_path = "./";
}

require_once($root_path."includes/config.php");
require_once($root_path."classes/database.php");
require_once($root_path."includes/functions.php");
require_once($root_path."classes/password.php");

/**
 * class User
 * author Alexandre Leblanc
 * Class representing a User.
 */
class User {
	private $m_id;				// Id:int
	private $m_username;		// Username:string
	private $m_mail;			// Email:string
	private $m_date_joined;		// Date:int(11)
	private $m_last_visit;		// Date:int(11)
	private $m_level;			// Level:tinyint(5)
	private $m_usergroup;		// Usergroup:int(8)
	private $m_signature;		// Signature:string
	private $m_rank;			// Rank:int
	private $m_aim;				// Aim:string
	private $m_icq;				// ICQ:string
	private $m_msn;				// MSN:string
	private $m_yahoo;			// YAHOO:string
	private $m_bEmail_on_pm;	// EmailOnPm:bool|int -> tinyint(5)
	private $m_template;		// Template:int
	private $m_language;		// Language:int
	private $m_timezone;		// Timezone:char(3)
	private $m_posts;			// Posts:int(8)
	private $m_activation_key;  // ActivationKey:string
	private $m_location;		// Location:string
	private $m_website;			// Website:string
	private $m_avatar_type;		// type:int(4)
	private $m_avatar_location;	// string(75)
	private $m_avatar_dimension;// string(11)
	private $m_birthday;		// string(50)
	private $m_password;		// string(225)
	
	private static $m_lastUser; // Last user queried.
	
	/**
	 * CTOR
	 * @param $id User's id. If -1, it'll update itself to most recent (in case of additions).
	 */
	function __construct($id, $username, $mail) {
		if(!is_numeric($id)) {
			exit("Id is not numeric");
		} 
		if(!is_string($username)) {
			exit("Username is not a string");
		}
		if(!is_string($mail)) {
			exit("Mail is not a string");
		}
		
		$this->m_id = $id;
		$this->m_username = $username;
		$this->m_mail = $mail;
		$this->init();
	}
	
	/**
	 *  Inits the user (fix for insert() ) for new user.
	 */
	private function init() {
		$this->setActivationKey('');
		$this->setAvatarDimensions('');
		$this->setAvatarLocation('');
		$this->setAvatarType(0);
		$this->setBirthday("");
		$this->setDateJoined(time());
		$this->setEmailOnPm(true);
		$this->setLanguageId(1);
		$this->setLastVisit(time());
		$this->setLevel(0);
		$this->setLocation('');
		$this->setMessengers((array("aim" => '', "icq" => '', "msn" => '', "yahoo" => '')));
		$this->setPostsCount(0);
		$this->setRankId(0);
		$this->setSignature("");
		$this->setTemplateId(1);
		$this->setTimezone(0);
		$this->setUsergroupId(0);
		$this->setWebsite("");	
	}
	
	/**
	 * findUser Finds a user in the database.
	 * @param $id_username Id or username of the user.
	 * @returns User class if success, null otherwise.
	 */
	static function findUser($id_username) {
		
		// If last user is same user than requested here, 
		// just return it.
		if(!is_null(self::$m_lastUser)) {
			if(is_numeric($id_username) && self::$m_lastUser->getId() == $id_username) {
				return self::$m_lastUser;
			} else if (is_string($id_username) && self::$m_lastUser->getUsername() == $id_username) {
				return self::$m_lastUser;
			}
		} 
		
		$result = array();
		global $database;
		
		$db = new Database($database, $database['prefix']);
		
		if(is_numeric($id_username)) {
			// Search with id.
			$db->query("SELECT * FROM _PREFIX_users WHERE user_id=:uid", array(":uid" => intval($id_username)));
			$result = $db->fetch();
		} 
		elseif (is_string($id_username)) {
			//$db = new Database($database, $database['prefix']);
			$db->query("SELECT * FROM _PREFIX_users WHERE username=:uname", array(":uname" => $id_username));
			$result = $db->fetch();
		} 
		else 
		{
			return null;
		}
		
		if(is_null($result)) {
			return null;
		}

		// If we're here, it means we got a user in array.
		$user = new User(intval($result["user_id"]), "".$result["username"], $result["user_email"]);
		$user->setActivationKey($result["user_activation_key"]);
		$user->setAvatarDimensions($result["user_avatar_dimensions"]);
		$user->setAvatarLocation($result["user_avatar_location"]);
		$user->setAvatarType($result["user_avatar_type"]);
		$user->setBirthday($result["user_birthday"]);
		$user->setDateJoined($result["user_date_joined"]);
		$user->setEmailOnPm($result["user_email_on_pm"]);
		$user->setLanguageId(intval($result["user_language"]));
		$user->setLastVisit($result["user_lastvisit"]);
		$user->setLevel(intval($result["user_level"]));
		$user->setLocation($result["user_location"]);
		$user->setMessengers((array("aim" => $result["user_aim"], "icq" => $result["user_icq"], "msn" => $result["user_msn"], "yahoo" => $result["user_yahoo"])));
		$user->setPostsCount($result["user_posts"]);
		$user->setRankId(intval($result["user_rank"]));
		$user->setSignature($result["user_signature"]);
		$user->setTemplateId(intval($result["user_template"]));
		$user->setTimezone($result["user_timezone"]);
		$user->setUsergroupId(intval($result["user_usergroup"]));
		$user->setWebsite($result["user_website"]);		
		
		self::$m_lastUser = $user;
		return $user;
	}
	
	/**
	 * getId Gets the user's id.
	 * @returns User id (int)
	 */
	function getId() {
		return $this->m_id;
	}
	
	/**
	 * getUsername Gets User's username
	 * @returns Null if it fails, somehow. Otherwise with success username.
	 */
	function getUsername() {
		return $this->m_username;
	}
	
	/**
	 * setUsername Sets the user's username.
	 * @param $username Username. Must be a string.
	 */
	function setUsername($username) {
		if(!is_string($username)) {
			return "Username is not a string";
		}
		
		$this->m_username = $username;
	}
	
	/**
	 * getEmail Gets the user's email
	 * @returns Null if no email, somehow, or fails. 
	 */
	function getEmail() {
		return $this->m_mail;
	}
	
	/**
	 * setMail Sets the user's mail
	 * @param $mail Email address
	 */
	function setMail($mail) {
		if(!is_string($mail)) {
			return "Email is not a string";
		}
		if(strpos($mail, "@") === false) {
			return "Email seems not valid.";
		}
		
		$this->m_mail = $mail;
	}
	
	/**
	 * getDateJoined Gets the user join date.
	 */
	function getDateJoined() {
		return $this->m_date_joined;
	}
	
	/**
	 * setDateJoined Sets the join date.
	 * @param $date Numeric format of date.
	 */
	function setDateJoined($date) {		
		$this->m_date_joined = $date;
	}
	
	/**
	 * getLastVisit Gets the user last visit.
	 */
	function getLastVisit() {
		return $this->m_last_visit;
	}
	
	/**
	 * setLastVisit Sets the user last visit.
	 * @param $date Date as integer(11).
	 */
	function setLastVisit($date) {		
		$this->m_last_visit = $date;
	}
	
	/**
	 * getLevel Gets the level.
	 * @return User's level.
	 */
	function getLevel() {
		return $this->m_level;
	}
	
	/**
	 * setLevel Sets the user level.
	 * @param Level as tinyint(5)
	 */
	function setLevel($level) {
		if(!is_numeric($level)) {
			return "Level is not a valid number";
		}
		
		$this->m_level = $level;
	}
	
	/**
	 * getUserGroupId Gets the user's usergroup's id.
	 * @return usergroup's id (int(8))
	 */
	function getUsergroupId() {
		return $this->m_usergroup;
	}
	
	/**
	 * setUsergroupId Sets the User's usergroup id.
	 * @param $id Usergroup's id.
	 */
	function setUsergroupId($id) {
		if(!is_numeric($id)) {
			return "Usergroup id is not valid";
		}
		
		$this->m_usergroup = $id;
	}
	
	/**
	 * getSignature Gets the user's signature
	 * @returns User's signature
	 */
	function getSignature() {
		return $this->m_signature;
	}
	
	/**
	 * setSignature Sets the user's signature.
	 * @param $signature 
	 */
	function setSignature($signature) {	
		$this->m_signature = $signature;
	}
	
	/**
	 * getRankId Gets the user's rank id.
	 * @return Rank's id. (int(8))
	 */
	function getRankId() {
		return $this->m_rank;
	}
	
	/**
	 * setRankId Sets the rank's id.
	 * @param $id Rank's id.
	 */
	function setRankId($id) {
		if(!is_numeric($id)) {
			return "Rank id is not numeric.";
		}
		
		$this->m_rank = $id;
	}
	
	/**
	 * getMessengers Gets the users IMs
	 * @returns array(string) 
	 */
	function getMessengers() {
		$array["aim"] = $this->m_aim;
		$array["icq"] = $this->m_icq;
		$array["msn"] = $this->m_msn;
		$array["yahoo"] = $this->m_yahoo;
		
		return $array;
	}
	
	/**
	 * setMessengers Sets the specified ims to user.
	 * @param $ims Key=>value array.
	 */
	function setMessengers(array $ims) {
		if(!is_array($ims)) {
			return "Messengers array passed is not an array";
		}
		
		if(isset($ims["msn"])) {
			$this->m_msn = $ims["msn"];
		}
		if(isset($ims["icq"])) {
			$this->m_icq = $ims["icq"];
		}
		if(isset($ims["yahoo"])) {
			$this->m_yahoo = $ims["yahoo"];
		}
		if(isset($ims["aim"])) {
			$this->m_aim = $ims["aim"];
		}
	}
	
	/**
	 * getEmailOnPm Define if the user wants an email when a pm goes in.
	 * @returns True if yes, false otherwise.
	 */
	function getEmailOnPm() {
		return $this->m_bEmail_on_pm;
	}
	
	/**
	 * setEmailOnPm Defines if the user wants an email when a pm goes in.
	 * @param $val Boolean or integer-equivalent (will be casted to bool).
	 */
	function setEmailOnPm($val) {
		if(!is_numeric($val) && !is_bool($val)) {
			return "EmailOnPm value is not a number nor a boolean";
		}
		
		$this->m_bEmail_on_pm = (bool)$val;
	}
	
	/**
	 * getTemplateId Gets the user's template id.
	 * @return int Id of the template.
	 */
	function getTemplateId() {
		return $this->m_template;
	}
	
	/**
	 * setTemplateId Sets the user's template id.
	 * @param $id Template's id.
	 */
	function setTemplateId($id) {
		if(!is_numeric($id)) {
			return "Template id is not a number";
		}
		
		$this->m_template = $id;
	}
	
	/**
	 * getLanguageId Gets the user language id.
	 * @return int Language id.
	 */
	function getLanguageId() {
		return $this->m_language;
	}
	
	/**
	 * setLanguageId Sets the user language id.
	 * @param $id Language id.
	 */
	function setLanguageId($id) {
		if(!is_numeric($id)) {
			return "Language id is not a number";
		}
		
		$this->m_language = $id;
	}
	
	/**
	 * getTimezone Gets the user timezone.
	 * @return char(3) Timezone.
	 */
	function getTimezone() {
		return $this->m_timezone;
	}
	
	/**
	 * setTimezone Sets the user timezone.
	 * @param $timezone Timezone. Maximum 3 char.
	 */
	function setTimezone($timezone) {
		//if(!is_string($timezone)) {
			//return "Timezone is not even a string.";
		//}
		
		//if(strlen($timezone) > 3) {
			//return "Timezone data must be 3 characters or lower.";
		//}
		
		$this->m_timezone = $timezone;
	}
	
	/**
	 * getPostsCount Gets the user posts count.
	 * @return Posts count (int(8))
	 */
	function getPostsCount() {
		return $this->m_posts;
	}
	
	/**
	 * setPostsCount Sets the user posts count.
	 * @param $count Posts count.
	 */
	function setPostsCount($count) {
		if (!is_numeric($count)) {
			return "Posts count is not numeric.";
		}
		
		$this->m_posts = $count;
	}
	
	/**
	 * getActivationKey Gets the activation key of the user.
	 * @return string Activaton key.
	 */
	function getActivationKey() {
		return $this->m_activation_key;
	}
	
	/**
	 * setActivationKey Creates the activation key.
	 */
	function setActivationKey() {
		$this->m_activation_key = generate_activate_key();
	}
	
	/**
	 * getLocation Gets the user location.
	 * @return string User location.
	 */
	function getLocation() {
		return $this->m_location;
	}
	
	/**
	 * setLocation Sets the user location.
	 * @param $location Location string.
	 */
	function setLocation($location) {
		if(!is_string($location)) {
			return "Location is not a string.";
		}
		
		if(strlen($location) > 225) {
			return "Location string too long (>225)";
		}
		
		$this->m_location = $location;
	}
	
	/**
	 * getWebsite Gets the user website.
	 * @return string Website address
	 */
	function getWebsite() {
		return $this->m_website;
	}
	
	/**
	 * setWebsite Sets the user website.
	 * @param $website Website address.
	 */
	function setWebsite($website) {
		if(!is_string($website)) {
			return "Website is not a string!";
		}
		
		if(strlen($website) > 225) {
			return "Website address length must be lower than 225 characters";
		}
		
		$this->m_website = $website;
	}
	
	/**
	 * getAvatarType Gets the user avatar type.
	 * @return int(8) Avatar type.
	 */
	function getAvatarType() {
		return $this->m_avatar_type;
	}
	
	/**
	 * setAvatarType Sets the user avatar type.
	 * @param $type Avatar type (int(8))
	 */
	function setAvatarType($type) {
		if(!is_numeric($type)) {
			return "Avatar type must be a number.";
		}
		
		$this->m_avatar_type = $type;
	}
	
	/**
	 * getAvatarLocation Gets the user avatar location
	 * @return string Avatar location
	 */
	function getAvatarLocation() {
		return $this->m_avatar_location;
	}
	
	/**
	 * setAvatarLocation Sets the user avatar location
	 * @param $location Avatar's location.
	 */
	function setAvatarLocation($location) {
		if(!is_string($location)) {
			return "Avatar location must be a string";
		}
		
		if(strlen($location) > 75) {
			return "Avatar location length must be 75 characters or lower";
		}
		
		$this->m_avatar_location = $location;
	}
	
	/**
	 * getAvatarDimension Get user's avatar dimension.
	 * @return string Avatar dimension 
	 */
	function getAvatarDimensions() {
		return $this->m_avatar_dimension;
	}
	
	function setAvatarDimensions($dimension) { 
		if(!is_string($dimension)) {
			return "Avatar dimension must be a string. Yea. I know.";
		}
		
		if(strlen($dimension) > 11) {
			return "Avatar dimension length must be under 11 characters.";
		}
		
		$this->m_avatar_dimension = $dimension;
	}
	
	/**
	 * getBirthday Gets the user birthday.
	 * @return string(50) Birth. Day.
	 */
	function getBirthday() {
		return $this->m_birthday;
	}
	
	/**
	 * setBirthday Sets the user birthday.
	 * @param $bday Birth's day.
	 */
	function setBirthday($bday) {
		if(!is_string($bday)) {
			return "Birthday must be a string. 50 characters and lower.";
		}
		
		if(strlen($bday) > 50) {
			return "Birthday must be under 50 characters.";
		}
		
		$this->m_birthday = $bday;
	}
	
	/**
	 * setPassword Sets the user password.
	 * @param $password User's password.
	 */
	function setPassword($password) {
		if(!is_string($password)) {
			return "Password must be a string";
		}
		
		$this->m_password = password_hash($password, PASSWORD_BCRYPT);
	}
	
	/**
	 * update Updates the current user (not the password)
	 * @returns True if success, false otherwise.
	 */
	function update($gen_key = false) {
		if($this->m_id < 0) {
			return $this->insert($gen_key);
		}
		global $database;
		
		$db = new Database($database, $database['prefix']);
		$query = "UPDATE _PREFIX_users 
		SET username=:username, user_email=:email, user_date_joined=:datejoined, user_lastvisit=:lastvisit, user_level=:ulevel,
		user_usergroup=:usergroup, user_signature=:signature, user_rank=:urank, user_aim=:uaim, user_icq=:uicq, user_msn=:umsn,
		user_yahoo=:uyahoo, user_email_on_pm=:umailpm, user_template=:utid, user_language=:ulangid, user_timezone=:utimez,
		user_posts=:uposts, user_activation_key=:uakey, user_location=:uloc, user_website=:uweb, user_avatar_type=:uavtype,
		user_avatar_location=:uavloc, user_avatar_dimensions=:uavdim, user_birthday=:ubirth
		WHERE user_id=:uid";
		
		$values = array(":uid" => intval($this->m_id), 					
						":username" => $this->m_username, 		
						":email" => $this->m_mail,
						":datejoined" => $this->m_date_joined ?: 0, 	
						":lastvisit" => $this->m_last_visit ?: 0, 	
						":ulevel" => intval($this->m_level),
						":usergroup" => $this->m_usergroup ?: 0, 	
						":signature" => $this->m_signature ?: " ", 	
						":urank" => intval($this->m_rank),
						":uaim" => $this->m_aim ?: " ",
						":uicq" => $this->m_icq ?: " ", 				
						":umsn" => $this->m_msn ?: " ",
						":uyahoo" => $this->m_yahoo ?: " ", 			
						":umailpm" => intval($this->m_bEmail_on_pm) ?: 0, 
						":utid" => $this->m_template ?: 1,
						":ulangid" => $this->m_language ?: 1,		
						":utimez" => $this->m_timezone ?: 0,			
						":uposts" => $this->m_posts ?: 0,
						":uakey" => $this->m_activation_key ?: " ",	
						":uloc" => $this->m_location ?: " ",			
						":uweb" => $this->m_website ?: " ",
						":uavtype" => $this->m_avatar_type ?: 0,		
						":uavloc" => $this->m_avatar_location ?: " ",	
						":uavdim" => $this->m_avatar_dimension ?: " ",
						":ubirth" => $this->m_birthday ?: " ");
	
		$db->query($query, $values);
		
		return $db->rowCount() > 0;
	}
	
	/**
	 * updatePassword Updates the user password only.
	 * @return True if success false otherwise
	 */
	function updatePassword() {
		global $database;
		$query = "UPDATE _PREFIX_users
		SET user_password=:upass, user_new_password=:unewpass
		WHERE user_id=:uid";
		
		$values = array(":uid" 		=> intval($this->m_id),
						":upass" 	=> $this->m_password,
						":unewpass" => $this->m_password);
						
		$db = new Database($database, $database['prefix']);
		$db->query($query, $values);
		
		return ($db->rowCount() > 0 ? true : false);
	}
	
	/**
	 * insert Inserts the user in the DB.
	 * @returns True if success, false otherwise.
	 */
	private function insert($gen_key = false) {
		global $database;
		$db = new Database($database, $database['prefix']);
		$query = "";
		$level = 0;
		
		if($gen_key) {
			$level = 2;
		} else {
			$level = 3;
		}
		
		$query = "INSERT INTO _PREFIX_users
			VALUES ('', :username, :upass, :umail, :udatejoined, :ulastvisit, :ulevel, :usergroup, :usignature, :urank, :uaim, :uicq, 
			:umsn, :uyahoo, :umailpm, :utemplate, :ulang, :utimezone, :uposts, :uackey, :uloc, :uweb, :uavtype, :uavloc, :uavdim, 
			:upassresetreq, :unewpass, :ubirth)";

		$values = array(":username" => $this->m_username, ":upass" => $this->m_password, ":umail" => $this->m_mail, 
			":udatejoined" => $this->m_date_joined, ":ulastvisit" => $this->m_last_visit, ":ulevel" => $level,
			":usergroup" => $this->m_usergroup, ":usignature" => $this->m_signature, ":urank" => $this->m_rank, 
			":uaim" => $this->m_aim, ":uicq" => $this->m_icq, ":umsn" => $this->m_msn, ":uyahoo" => $this->m_yahoo,
			":umailpm" => (int)$this->m_bEmail_on_pm, ":utemplate" => $this->m_template, ":ulang" => $this->m_language, 
			":utimezone" => $this->m_timezone, ":uposts" => $this->m_posts, ":uackey" => $this->m_activation_key, 
			":uloc" => $this->m_location, ":uweb" => $this->m_website, ":uavtype" => $this->m_avatar_type, 
			":uavloc" => $this->m_avatar_location, ":uavdim" => $this->m_avatar_dimension, ":upassresetreq" => 0,
			":unewpass" => $this->m_password, ":ubirth" => $this->m_birthday);
		
						
		$db->query($query, $values);
		
		if($db->rowCount() != 1) {
			return false;
		}
		
		$db->query("SELECT user_id FROM _PREFIX_users WHERE username=:uname", array(":uname" => $this->m_username));
		$arrayResponse = $db->fetch();
		
		$this->m_id = $arrayResponse["user_id"];
		
		return true;
	}
	
	/**
	 * check Checks if the user's credentials are valid.
	 * 
	 * @returns True if valid, false otherwise.
	 */
	static function check($username, $password) {
		if(!is_string($username) || !is_string($password)) {
			return "Username or password must be a string.";
		}
		global $database;
		
		$db = new Database($database, $database['prefix']);
		
		$query = "SELECT user_password, user_id FROM _PREFIX_users WHERE username=:uname";
		$values = array(":uname" => $username);

		$db->query($query, $values);
		$result = $db->fetch();
		
		if(password_verify($password, $result['user_password'])) {
			return intval($result['user_id']);
		} else {
			return -1;
		}
	}
	
	/**
	 * Activates the user.
	 * 
	 * @returns Status integer. 0: is "done", 1: is "already done", 
	 * 3: is "wrong key" 4: wrong parameters.
	 */
	static function activate($user_id, $key) {
		if(!is_string($key) || !is_numeric($user_id)) {
			return "activate() Wrong parameters types. [int, string]";
		}
		
		global $database;
		
		$db = new Database($database, $database['prefix']);
		
		$query = "SELECT * FROM `_PREFIX_users`
			WHERE `user_id` = :user_id AND `user_activation_key` = :user_activation_key
			LIMIT 1";
		$values = array(":uname" => $username);

		$db->query($query, $values);
		
		if($result = $db->fetch()) {			
			if($result['user_level'] != 2) {
				return 1;
			}
		
			// Activates user.
			$db->query("UPDATE `_PREFIX_users`
				SET `user_level` = '3', `user_activation_key` = ''
				WHERE `user_id` = :user_id",
				array(":user_id" => $user_id));
				
			if($db->rowCount() > 0) {
				return 0;
			} else {
				return "activate() unknown error";
			}
		} else {
			return 3;
		}
	}
	
	/**
	 * Deletes a user.
	 * 
	 * @param Either an id or a username.
	 * @returns True if success, else returns an error message.
	 */ 
	static function delete($id_username) {
		$db = new Database($database, $database['prefix']);
		
		if(is_string($id_username)) {
			$db->query("DELETE FROM `_PREFIX_users` WHERE `username`=:uname LIMIT 1", array(":uname" => $id_username));
		} else {
			$db->query("DELETE FROM `_PREFIX_users` WHERE `user_id`=:uid LIMIT 1", array(":uid" => $id_username));
		}
		
		return ($db->rowCount() > 0);
	}
}

?>
