<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: class_language.php                                         # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

class Language {
	var $language;

	//
	// Add a language file to the array
	// Usage: $language->add_file("filename.php");
	//
	function add_file($file)
	{
		global $root_path, $lang, $config;

		if(file_exists($root_path . "language/".$this->language."/".$file.".php"))
		{
			include($root_path . "language/".$this->language."/".$file.".php");
		}
		else
		{
			return false;
		}
	}

	function language()
	{
		global $config, $user, $root_path;

		// Set the default language
		if(is_dir($root_path . "language/".$user['user_language_folder']."") && !empty($user['user_language_folder']))
		{
			$this->language = $user['user_language_folder'];
			$this->add_file("main");
			if(defined("IN_ADMIN")) {
                $this->add_file("main_admin");
        	}
		}
		else if(is_dir($root_path . "language/".$config['default_language'].""))
		{
			$db->query("UPDATE `".$db_prefix."users` SET `user_language` = '".$config['default_language']."' WHERE `user_id` = '".$user['user_id']."'");
			$user['user_language'] = $config['language'];			$this->language = $user['user_language_folder'];
			$this->add_file("main");
			if(defined("IN_ADMIN")) {
                $this->add_file("main_admin");
        	}
   		}
   		else
   		{
			die("Unable to get language file language/".$user['user_language_folder']."/");
		}
	}

	//
	// Change the current language
	// Usage: $language->change_lang("newlanguage");
	//
	function change_lang($language)
	{
        global $root_path;

		// Set the default language
		if(is_dir($root_path . "language/".$language.""))
		{
			$this->language = $language;
		}

		$this->add_file("main");
	}

	//
	// Check if a language variable exists
	// Usage: $language->var_exists("name");
	//
	function var_exists($name)
	{
		if(isset($lang[$name]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
