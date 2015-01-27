<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: class_template.php                                         # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright � 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB"))
{
        die("Hacking Attempt");
}

class Theme
{
	var $nests = array();
	var $nested;
	var $theme;
	var $template_name;
	var $template_dir;

	function Theme()
	{
	}

	function new_file($template_name, $template)
	{
		global $config, $user, $root_path;

		$admin_path = (empty($module_dir) && defined("IN_ADMIN")) ? "admin/" : "";
		$this->template_dir = $user['user_template_folder'];
		if (file_exists($root_path . "templates/".$this->template_dir."/" . $admin_path . "" . $template . ""))
		{
			$this->theme[$template_name]['value'] = join("", file($root_path . "templates/".$this->template_dir."/" . $admin_path . "" . $template . ""));
		}
		else
		{
			if(function_exists("error_msg") && $template != "error_msg.tpl" && $template != "page_header.tpl" && $template != "page_footer.tpl")
			{
				error_msg("Error", "Template file templates/".$this->template_dir."/" . $admin_path . "" . $template . " not found.");
			}
			else
			{
				die("Template file templates/".$this->template_dir."/" . $admin_path . "" . $template . " not found.");
			}
		}
		$this->theme[$template_name]['type'] = "template";
	}

	function parse($file)
	{
		global $config, $db, $user, $theme, $location_bar, $lang, $db_prefix; // Globals that will run through the entire script
		if (is_file($file) && is_readable($file))
		{
			ob_start();
			include($file);
			$contents = ob_get_contents();
			ob_end_clean();
		}
		if(!isset($contents)) $contents = $file;
		return $contents;
	}


	function replace_tags($template_name, $tags, $no_parse = false)
	{
		if (sizeof($tags) > 0)
		{
			foreach ($tags as $tag => $data)
			{
				$this->theme[$template_name]['value'] = preg_replace("#{" . $tag . "}#i", $data, $this->theme[$template_name]['value']);
			}
		}
	}

	function insert_file($template_name, $tag, $filename)
	{
		if(file_exists($filename))
		{
//			$this->theme[$template_name]['value'] = eregi_replace("{" . $tag . "}", $this->parse($filename), $this->theme[$template_name]['value']); DEPRECATED

			// J'imagine que c'est la manière de procéder lorsqu'il y a de la concaténation php dans un regex...
			$this->theme[$template_name]['value'] = preg_replace("#{" . $tag . "}#i", $this->parse($filename), $this->theme[$template_name]['value']);
		}
	}

	function insert_nest($template_name, $name, $tags = '')
	{
		$current_val =& $this->theme[$template_name];
		$current_pos = 0;
		$name_array = explode("/", $name);
		$last_val = array();
		$last_val =& $this->theme[$template_name];
		$nest_value = "";
		$old_key = "";
		foreach($name_array as $key) {
			if($current_pos != 0)
			{
				$last_val =& $last_val[$old_key];
			}
			if($current_pos != (count($name_array) - 1))
			{
				if(!array_key_exists($key, $current_val))
				{
					$current_val[$key] = array();
				}
				$current_val =& $current_val[$key];
			}
			else
			{
				if(preg_match("/<!-- BEGIN $key -->((.|\n)*?)<!-- END $key -->/", $last_val['value'], $temp))
				{
					$nest_value = $temp[1];
					if(sizeof($tags) > 0 && $tags != "")
					{
						foreach ($tags as $tag => $data)
						{
//							$nest_value = eregi_replace("{" . $tag . "}", $data, $nest_value); DEPRECATED
							$nest_value = preg_replace("#{" . $tag . "}#i", $data, $nest_value);
						}
					}
				}
				$current_val[$key]['type'] = "nest";
				$current_val[$key]['value'] = $nest_value;
  			}
  			$old_key = $key;
			$current_pos++;
		}
	}

	function _implode_nested_array($array)
	{
		$value = '';
		$type = '';
		$value = $array['value'];
		$type = $array['type'];
		foreach($array as $name => $array_value)
		{
			if(is_array($array_value))
			{
				list($nest_type,$nest_value) = $this->_implode_nested_array($array_value);
				if($nest_type == "nest")
				{
					$value = preg_replace("/<!-- BEGIN $name -->((.|\n)*?)<!-- END $name -->/", $nest_value, $value);
				}
				else if($nest_type == "switch")
				{
					$value = preg_replace("/<!-- BEGIN SWITCH $name -->((.|\n)*?)<!-- END SWITCH $name -->/", $nest_value, $value);
				}
			}
		}
		return array($type, $value);
	}

	function add_nest($template_name, $name)
	{
		$current_val =& $this->theme[$template_name];
		$current_pos = 0;
		$name_array = explode("/", $name);
		$last_val = array();
		$last_val =& $this->theme[$template_name];
		$nest_value = "";
		$old_key = "";
		foreach($name_array as $key)
		{
			if($current_pos != 0)
			{
				$last_val =& $last_val[$old_key];
			}
			if($current_pos != count($name_array))
			{
				if(!array_key_exists($key, $current_val))
				{
					$current_val[$key] = array();
				}
				$current_val =& $current_val[$key];
  			}
  			$old_key = $key;
  			$current_pos++;
  		}

		list($type, $nest_value) = $this->_implode_nested_array($current_val);

		$nest_value = str_replace("\$", "\\\$", $nest_value);
		if($type == "nest")
		{
			$last_val['value'] = preg_replace("/<!-- BEGIN $key -->((.|\n)*?)<!-- END $key -->/", $nest_value . "\\0", $last_val['value']);
		}
		else if($type == "switch")
		{
			$last_val['value'] = preg_replace("/<!-- BEGIN SWITCH $key -->((.|\n)*?)<!-- END SWITCH $key -->/", $nest_value . "\\0", $last_val['value']);
		}
		$current_val = "";

	}

	function switch_nest($template_name, $name, $val, $tags = '')
	{
		$current_val =& $this->theme[$template_name];
		$current_pos = 0;
		$name_array = explode("/", $name);
		$last_val = array();
		$last_val =& $this->theme[$template_name];
		$nest_value = "";
		$old_key = "";
		foreach($name_array as $key) {
			if($current_pos != 0)
			{
				$last_val =& $last_val[$old_key];
			}
			if($current_pos != (count($name_array) - 1))
			{
				if(!array_key_exists($key, $current_val))
				{
					$current_val[$key] = array();
				}
				$current_val =& $current_val[$key];
			}
			else
			{
				$match = ($val == true) ? "<!-- BEGIN SWITCH $key -->((.|\n)*?)<!-- SWITCH $key -->" : "<!-- SWITCH $key -->((.|\n)*?)<!-- END SWITCH $key -->";
				if(preg_match("#$match#", $last_val['value'], $temp))
				{

					$nest_value = $temp[1];

					if(sizeof($tags) > 0 && $tags != "")
					{
						foreach ($tags as $tag => $data)
						{
//							$nest_value = eregi_replace("{" . $tag . "}", $data, $nest_value); DEPRECATED
							$nest_value = preg_replace("#{" . $tag . "}#i", $data, $nest_value);
						}
					}
				}
				$current_val[$key]['type'] = "switch";
				$current_val[$key]['value'] = $nest_value;
  			}
  			$old_key = $key;
			$current_pos++;
		}
	}

	function output($template_name, $return = false)
	{
		global $config, $user, $lang, $root_path;

		// Switch nests for users that are logged in / logged out
		if($user['user_id'] > 0)
		{
			$this->theme[$template_name]['value'] = preg_replace("#<!-- BEGIN SWITCH logged_in -->((.|\n)*?)<!-- SWITCH logged_in -->((.|\n)*?)<!-- END SWITCH logged_in -->#", "\\1", $this->theme[$template_name]['value']);
			$this->theme[$template_name]['value'] = preg_replace("#<!-- BEGIN logged_in -->((.|\n)*?)<!-- END logged_in -->#", "\\1", $this->theme[$template_name]['value']);
		}
		else
		{
			$this->theme[$template_name]['value'] = preg_replace("#<!-- BEGIN SWITCH logged_in -->((.|\n)*?)<!-- SWITCH logged_in -->((.|\n)*?)<!-- END SWITCH logged_in -->#", "\\3", $this->theme[$template_name]['value']);
			$this->theme[$template_name]['value'] = preg_replace("#<!-- BEGIN logged_out -->((.|\n)*?)<!-- END logged_out -->#", "\\1", $this->theme[$template_name]['value']);
		}

		// Remove all unused nests
		$this->theme[$template_name]['value'] = preg_replace("#<!-- BEGIN (.*?) -->((.|\n)*?)<!-- END \\1 -->#", "", $this->theme[$template_name]['value']);

		// Replace all language variables in the file
		$matches = array();
		preg_match_all("/{L\.([0-9a-zA-Z\-_]+)}/", $this->theme[$template_name]['value'], $matches);
		foreach($matches[1] as $match)
		{
			if(isset($lang[$match]))
			{
				$this->theme[$template_name]['value'] = preg_replace("/{L\.$match}/", $lang[$match], $this->theme[$template_name]['value']);
			}
		}

		// Replace all config variables in the file
		$matches = array();
		preg_match_all("/{C\.([a-zA-Z0-9\-_]+)}/", $this->theme[$template_name]['value'], $matches);
		foreach($matches[1] as $match)
		{
			if(isset($config[$match]))
			{
				$this->theme[$template_name]['value'] = preg_replace("/{C\.$match}/", $config[$match], $this->theme[$template_name]['value']);
			}
		}

		// Replace all user variables in the file
		$matches = array();
		preg_match_all("/{U\.([a-zA-Z0-9\-_]+)}/", $this->theme[$template_name]['value'], $matches);
		foreach($matches[1] as $match)
		{
			if(isset($user[$match]))
			{
				$this->theme[$template_name]['value'] = preg_replace("/{U\.$match}/", $user[$match], $this->theme[$template_name]['value']);
			}
		}

		// Replace all constants in the template file
		$matches = array();
		preg_match_all("/{Constant\.([0-9a-zA-Z_]+)}/", $this->theme[$template_name]['value'], $matches);
		foreach($matches[1] as $match)
		{
			if(defined($match))
			{
				$this->theme[$template_name]['value'] = preg_replace("/{Constant\.$match}/", constant($match), $this->theme[$template_name]['value']);
			}
		}

		$this->theme[$template_name]['value'] = preg_replace("/{T\.TEMPLATE_PATH}/", $root_path . "templates/".$this->template_dir."", $this->theme[$template_name]['value']);

		if($return)
		{
			return $this->theme[$template_name]['value'];
		}
		else
		{
			echo $this->theme[$template_name]['value'];
			return true;
		}
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright � 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
