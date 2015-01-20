<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: config.php                                                 # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
include($root_path . "includes/common.php");

$language->add_file("admin/config");

if(isset($_POST['Submit']))
{
	$post_config = array();
	$query = $db->query("SELECT `config_name`, `config_value`, `config_type` FROM `".$db_prefix."config` WHERE `config_orderby` > 0");

	while($result = $db->fetch_array($query))
	{
		if(isset($_POST[$result['config_name']]) && $_POST[$result['config_name']] != $result['config_value'] && ($result['config_type'] != "password" || !empty($_POST[$result['config_name']])))
		{
			$post_config[$result['config_name']] = $_POST[$result['config_name']];
		}
	}

	// Encrypt the FTP password
	if(isset($post_config['ftp_pass'])) {
        $post_config['ftp_pass'] = base64_encode($post_config['ftp_pass']);
	}

	foreach($post_config as $name => $value) {
		$db->query("UPDATE `".$db_prefix."config` SET `config_value` = '$value' WHERE `config_name` = '$name'");
	}
    info_box($lang['Configuration_Manager'], $lang['Configuration_Updated_Msg'], "config.php");
}
else
{

	$theme->new_file("config", "config.tpl", "");

	$query = $db->query("SELECT `config_name`, `config_value`, `config_type`, `config_category`
						FROM `".$db_prefix."config`
						WHERE `config_category_orderby` != '0'
						ORDER BY `config_category_orderby`, `config_orderby`");
	$current_category = "";
	while($result = $db->fetch_array($query))
	{
		if($current_category == "")
		{
			$theme->insert_nest("config", "category", array(
				"CATEGORY_TITLE" => (isset($lang[$result['config_category']])) ? $lang[$result['config_category']] : ereg_replace("_", " ", $result['config_category'])
			));
			$current_category = $result['config_category'];
		}
		else if($result['config_category'] != $current_category)
		{
			$theme->add_nest("config", "category");
			$theme->insert_nest("config", "category", array(
				"CATEGORY_TITLE" => (isset($lang[$result['config_category']])) ? $lang[$result['config_category']] : ereg_replace("_", " ", $result['config_category'])
			));
			$current_category = $result['config_category'];
		}

		switch($result['config_type']) {
			case "textbox":
				$theme->insert_nest("config", "category/config_option", array(
					"CONFIG_TITLE" => (isset($lang[$result['config_name']])) ? $lang[$result['config_name']] : ereg_replace("_", " ", $result['config_name']),
					"CONFIG_CONTENT" => "<input type=\"text\" name=\"" . $result['config_name'] . "\" value=\"" . changehtml($result['config_value']) . "\" size=\"35\" />"
				));
				$theme->add_nest("config", "category/config_option");
			break;
			case "password":
				$theme->insert_nest("config", "category/config_option", array(
					"CONFIG_TITLE" => (isset($lang[$result['config_name']])) ? $lang[$result['config_name']] : ereg_replace("_", " ", $result['config_name']),
					"CONFIG_CONTENT" => "<input type=\"password\" name=\"" . $result['config_name'] . "\" size=\"35\" />"
				));
				$theme->add_nest("config", "category/config_option");
			break;
			case "textarea":
				$theme->insert_nest("config", "category/config_option", array(
					"CONFIG_TITLE" => (isset($lang[$result['config_name']])) ? $lang[$result['config_name']] : ereg_replace("_", " ", $result['config_name']),
					"CONFIG_CONTENT" => "<textarea name=\"" . $result['config_name'] . "\" rows=\"5\" cols=\"27\">" . changehtml($result['config_value']) . "</textarea>"
				));
				$theme->add_nest("config", "category/config_option");
			break;
			case "true/false":
				$config_true = ($result['config_value'] == 1) ? "checked=\"checked\"" : "";
				$config_false = ($result['config_value'] == 0) ? "checked=\"checked\"" : "";

				$theme->insert_nest("config", "category/config_option", array(
					"CONFIG_TITLE" => (isset($lang[$result['config_name']])) ? $lang[$result['config_name']] : ereg_replace("_", " ", $result['config_name']),
					"CONFIG_CONTENT" => "" . $lang['True'] . "<input type=\"radio\" name=\"" . $result['config_name'] . "\" value=\"1\" $config_true />&nbsp;&nbsp;" . $lang['False'] . "<input type=\"radio\" name=\"" . $result['config_name'] . "\" value=\"0\" $config_false />"
				));
				$theme->add_nest("config", "category/config_option");
			break;
			case "dropdown:timezone":
				$config_content =  "\n  <select name=\"" . $result['config_name'] . "\">";

				foreach($lang['tz'] as $id => $value)
				{
					$selected = ($id == $result['config_value']) ? "selected=\"selected\"" : "";
					$config_content .= "\n    <option value=\"" . $id . "\" $selected>" . $value . "</option>";
				}

				$config_content .= "\n  </select>";

				$theme->insert_nest("config", "category/config_option", array(
					"CONFIG_TITLE" => (isset($lang[$result['config_name']])) ? $lang[$result['config_name']] : ereg_replace("_", " ", $result['config_name']),
					"CONFIG_CONTENT" => $config_content
				));
			break;
			case "dropdown:template":
				$config_content =  "\n  <select name=\"" . $result['config_name'] . "\">";

				$template_query = $db->query("SELECT `template_id`, `template_name` FROM `".$db_prefix."templates` WHERE `template_usable` = '1'");
				while($template_result = $db->fetch_array($template_query))
				{
					$selected = ($template_result['template_id'] == $result['config_value']) ? "selected=\"selected\"" : "";
					$config_content .= "\n    <option value=\"" . $template_result['template_id'] . "\" $selected>" . $template_result['template_name'] . "</option>";
				}

				$config_content .= "\n  </select>";

				$theme->insert_nest("config", "category/config_option", array(
					"CONFIG_TITLE" => (isset($lang[$result['config_name']])) ? $lang[$result['config_name']] : ereg_replace("_", " ", $result['config_name']),
					"CONFIG_CONTENT" => $config_content
				));
			break;
			case "dropdown:language":
				$config_content =  "\n  <select name=\"" . $result['config_name'] . "\">";

				$language_query = $db->query("SELECT `language_id`, `language_name` FROM `".$db_prefix."languages` WHERE `language_usable` = '1'");
				while($language_result = $db->fetch_array($language_query))
				{
					$selected = ($language_result['language_id'] == $result['config_value']) ? "selected=\"selected\"" : "";
					$config_content .= "\n    <option value=\"" . $language_result['language_id'] . "\" $selected>" . $language_result['language_name'] . "</option>";
				}

				$config_content .= "\n  </select>";

				$theme->insert_nest("config", "category/config_option", array(
					"CONFIG_TITLE" => (isset($lang[$result['config_name']])) ? $lang[$result['config_name']] : ereg_replace("_", " ", $result['config_name']),
					"CONFIG_CONTENT" => $config_content
				));
			break;
		}

	}

	$theme->add_nest("config", "category");

	$theme->replace_tags("config", array(
		"SITE_NAME" => changehtml($config['site_name']),
		"SITE_DESC" => changehtml($config['site_desc']),
		"ADMIN_EMAIL" => $config['admin_email'],
		"FOOTER" => changehtml($config['footer']),
		"OFFLINE_TRUE" => ($config['board_offline'] == 1) ? "CHECKED" : "",
		"OFFLINE_FALSE" => ($config['board_offline'] == 0) ? "CHECKED" : "",
		"OFFLINE_MESSAGE" => changehtml($config['offline_message']),
		"FTP_USER" => $config['ftp_user'],
		"FTP_PATH" => $config['ftp_path'],
		"REG_AUTH_TYPE_TRUE" => ($config['register_auth_type'] == 1) ? "CHECKED" : "",
		"REG_AUTH_TYPE_FALSE" => ($config['register_auth_type'] == 0) ? "CHECKED" : ""
	));


	//
	// Output the page header
	//
	include($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("config");

	//
	// Output the page footer
	//
	include($root_path . "includes/page_footer.php");
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
