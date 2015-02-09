<?php

/**********************************************************
*
*			admin/config.php
*
*		ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path . "includes/common.php");

$language->add_file("admin/config");

if(isset($_POST['Submit']))
{
	$post_config = array();
	$db2->query("SELECT `config_name`, `config_value`, `config_type` FROM `_PREFIX_config` WHERE `config_orderby` > 0");

	while($result = $db2->fetch()) {
		
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
		$db2->query("UPDATE `_PREFIX_config` SET `config_value`=:value WHERE `config_name`=:name", 
			array(":value" => $value, ":name" => $name));
	}
    info_box($lang['Configuration_Manager'], $lang['Configuration_Updated_Msg'], "config.php");
}
else
{
	Template::setBasePath($root_path . "templates/original/admin/");
	Template::addNamespace("L", $lang);
	$page_master = new Template("config.tpl");

	$db_in = $db2->query("SELECT `config_name`, `config_value`, `config_type`, `config_category` 
		FROM `_PREFIX_config`
		WHERE `config_category_orderby` != '0'
		ORDER BY `config_category_orderby`, `config_orderby`");
						
	$current_category = "";
	$current_category_configs = "";

	while($result = $db_in->fetch()) {

		// Special case for the first iteration.
		if($current_category == "") {
			$current_category = $result['config_category'];
		}

		if($result['config_category'] != $current_category) {
			$page_master->addToBlock("category", array(
				"CATEGORY_TITLE" => (isset($lang[$result['config_category']])) ?
					$lang[$result['config_category']] : preg_replace("#_#", " ", $result['config_category']),
				"CATEGORY_CONFIG_OPTIONS" => $current_category_configs
			));

			$current_category = $result['config_category'];
			$current_category_configs = "";
		}

		$config_content_defined = true;
		$config_content = "";

		switch($result['config_type']) {
			case "textbox":
				$config_content = "<input type=\"text\" name=\"" . $result['config_name'] . "\" value=\"" . changehtml($result['config_value']) . "\" size=\"35\" />";

			break;
			case "password":
				 $config_content = "<input type=\"password\" name=\"" . $result['config_name'] . "\" size=\"35\" />";
			break;
			case "textarea":
				$config_content = "<textarea name=\"" . $result['config_name'] . "\" rows=\"5\" cols=\"27\">" .
					changehtml($result['config_value']) .
					"</textarea>";

			break;
			case "true/false":
				$config_true = ($result['config_value'] == 1) ? "checked=\"checked\"" : "";
				$config_false = ($result['config_value'] == 0) ? "checked=\"checked\"" : "";

				$config_content = "" . $lang['True'] .
					"<input type=\"radio\" name=\"" . $result['config_name'] . "\" value=\"1\" $config_true />" .
					"&nbsp;&nbsp;" .
					$lang['False'] .
					"<input type=\"radio\" name=\"" . $result['config_name'] . "\" value=\"0\" $config_false />";
			break;
			case "dropdown:timezone":
				$config_content = "\n  <select name=\"" . $result['config_name'] . "\">";

				foreach($lang['tz'] as $id => $value) {
					$selected = ($id == $result['config_value']) ? "selected=\"selected\"" : "";
					$config_content .= "\n    <option value=\"" . $id . "\" $selected>" . $value . "</option>";
				}

				$config_content .= "\n  </select>";
			break;
			case "dropdown:template":
				$config_content =  "\n  <select name=\"" . $result['config_name'] . "\">";

				$db2->query("SELECT `template_id`, `template_name` FROM `_PREFIX_templates` WHERE `template_usable` = '1'");
				while($template_result = $db2->fetch()) {
					$selected = ($template_result['template_id'] == $result['config_value']) ? "selected=\"selected\"" : "";
					$config_content .= "\n    " .
						"<option value=\"" . $template_result['template_id'] . "\" $selected>" .
						$template_result['template_name'] .
						"</option>";
				}

				$config_content .= "\n  </select>";
			break;
			case "dropdown:language":
				$config_content =  "\n  <select name=\"" . $result['config_name'] . "\">";

				$db2->query("SELECT `language_id`, `language_name` FROM `_PREFIX_languages` WHERE `language_usable` = '1'");
				while($language_result = $db2->fetch()) {
					$selected = ($language_result['language_id'] == $result['config_value']) ? "selected=\"selected\"" : "";
					$config_content .= "\n    <option value=\"" . $language_result['language_id'] . "\" $selected>" . $language_result['language_name'] . "</option>";
				}

				$config_content .= "\n  </select>";
			break;
			default:
				$config_content_defined = false;
			break;
		}

		if($config_content_defined) {
			$current_category_configs .= $page_master->renderBlock("config_option", array(
				"CONFIG_TITLE" => (isset($lang[$result['config_name']])) ?
					$lang[$result['config_name']] : preg_replace("#_#", " ", $result['config_name']),
				"CONFIG_CONTENT" => $config_content
			));
		}

	}

	outputPage($page_master);
}
?>
