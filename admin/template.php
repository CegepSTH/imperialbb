<?php

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path . "includes/common.php");
Template::setBasePath($root_path . "templates/original/admin/");

$language->add_file("admin/template");
Template::addNamespace("L", $lang);

$tplTemplates = new Template("templates.tpl");

if(!isset($_GET['func'])) $_GET['func'] = "";

/**
 * Add template.
 */
if($_GET['func'] == "add") {
	
	if(isset($_POST['Submit'])) {
		CSRF::validate();
		
		// Check if name is empty.
		if(!isset($_POST['name']) || empty($_POST['name'])) {
			$_SESSION['return_url'] = "template.php?func=add";
			header("location: error.php?code=".ERR_CODE_TEMPLATE_NAME_CANT_EMPTY);
			exit();
		}
		
		if(!isset($_POST['folder']) || empty($_POST['folder'])) {
			$_SESSION['return_url'] = "template.php?func=add";
			header("location: error.php?code=".ERR_CODE_TEMPLATE_FOLDER_CANT_EMPTY);
			exit();
		} else {
			if(!is_dir($root_path . "/templates/".$_POST['folder']."/")) {
				$_SESSION['return_url'] = "template.php?func=add";
				header("location: error.php?code=".ERR_CODE_TEMPLATE_FOLDER_DOESNT_EXIST);
				exit();
			}
		}
		
		$usable = isset($_POST['usable']) ? "1" : "0";
			
		$values = array(":name" => $_POST['name'], ":folder" => $_POST['folder'], ":usable" => $usable);
		$db2->query("INSERT INTO `_PREFIX_templates` (`template_name`, `template_folder`, `template_usable`) VALUES (:name, :folder, :usable)", $values);
		$ok = $db2->rowCount() > 0;
		
		// Check if success.
		if($ok) {
			$_SESSION['return_url'] = "template.php";
			header("location: error.php?code=".ERR_CODE_TEMPLATE_ADDED_SUCCESS);
			exit();
		} else {
			$_SESSION['return_url'] = "template.php?func=add";
			header("location: error.php?code=".ERR_CODE_TEMPLATE_CANT_ADD);
			exit();
		}
	} else {
		$tplTemplatesAdd = new Template("templates_add.tpl");

		$tplTemplatesAdd->setVars(array(
			"CSRF_TOKEN" => CSRF::getHTML(),
			"ACTION" => $lang['Add_Template'],
			"NAME" => "",
			"FOLDER" => "",
			"USABLE" => ""
		));
		
		$tplTemplates->addToTag("template_page", $tplTemplatesAdd);
	}
} else if($_GET['func'] == "download") {
	header("location: template.php");
} else if($_GET['func'] == "edit") {
	// Unset token
	unset($_SESSION['csrf_weird_token']);
	
	// Check if valid id.
	if(!(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)) {
		$_SESSION['return_url'] = "template.php";
		header("location: error.php?code=".ERR_CODE_TEMPLATE_INVALID_ID);
		exit();
	}
	
	// Do we receive the save?
	if(isset($_POST['Submit'])) {
		CSRF::validate();

		// Check if name is empty.
		if(!isset($_POST['name']) || empty($_POST['name'])) {
			$_SESSION['return_url'] = "template.php?func=edit&id=".$_GET['id'];
			header("location: error.php?code=".ERR_CODE_TEMPLATE_NAME_CANT_EMPTY);
			exit();
		}
		
		// Check if folder is valid.
		if(!isset($_POST['folder']) || empty($_POST['folder'])) {
			$_SESSION['return_url'] = "template.php?func=edit&id=".$_GET['id'];
			header("location: error.php?code=".ERR_CODE_TEMPLATE_FOLDER_CANT_EMPTY);
			exit();
		} else {
			if(!is_dir($root_path . "/templates/".$_POST['folder']."/")) {
				$_SESSION['return_url'] = "template.php?func=edit&id=".$_GET['id'];
				header("location: error.php?code=".ERR_CODE_TEMPLATE_FOLDER_DOESNT_EXIST);
				exit();
			}
		}

		$usable = isset($_POST['usable']) ? "1" : "0";
			
		$values = array(":name" => $_POST['name'], ":folder" => $_POST['folder'], ":usable" => $usable, ":id" => $_GET['id']);
		$db2->query("UPDATE `_PREFIX_templates` SET `template_name`=:name, `template_folder`=:older, `template_usable`=:usable WHERE `template_id`=:id", $values);
		$ok = $db2->rowCount() > 0;
		
		// Check result.
		if($ok) {
			$_SESSION['return_url'] = "template.php";
			header("location: error.php?code=".ERR_CODE_TEMPLATE_EDIT_SUCCESS);
			exit();
		} else {
			$_SESSION['return_url'] = "template.php?func=edit&id=".$_GET['id'];
			header("location: error.php?code=".ERR_CODE_TEMPLATE_EDIT_FAILED);
			exit();			
		}
	} else {
		// SHOW EDIT FORM
		$tplTemplatesEdit = new Template("templates_edit.tpl");

		$db2->query("SELECT `template_id`, `template_name`, `template_folder`, `template_usable` FROM `_PREFIX_templates` WHERE `template_id`=:id", array(":id" => $_GET['id']));

		if($result = $db2->fetch()) {
			$tplTemplatesEdit->setVars(array(
			    "CSRF_TOKEN" => CSRF::getHTML(),
				"ACTION" => $lang['Edit_Template'],
				"ID" => $result['template_id'],
				"NAME" => $result['template_name'],
				"FOLDER" => $result['template_folder'],
				"USABLE" => ($result['template_usable'] == 1) ? "checked=\"checked\"" : ""
			));
		} else {
			$_SESSION['return_url'] = "template.php";
			header("location: error.php?code=".ERR_CODE_TEMPLATE_INVALID_ID);
			exit();		
		}
		
		$tplTemplates->addToTag("template_page", $tplTemplatesEdit);
	}
	
} else if($_GET['func'] == "delete") {
	/**
	 * Delete a template.
	 */ 
	 
	// A bit hacky, since it's _GET request.
	if(!isset($_SESSION['csrf_weird_token'])) {
		CSRF::validate();
	}
	// Unset token.
	unset($_SESSION['csrf_weird_token']);
	
	// Check if id is valid.
	if(!(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)) {
		$_SESSION['return_url'] = "template.php";
		header("location: error.php?code=".ERR_CODE_TEMPLATE_INVALID_ID);
		exit();
	}
	
	$db2->query("SELECT count(`template_id`) AS 'count' FROM `_PREFIX_templates`");
	$result = $db2->fetch();
	// Check if last template.
	if($result['count'] <= 1) {
		$_SESSION['return_url'] = "template.php";
		header("location: error.php?code=".ERR_CODE_TEMPLATE_CANT_DELETE_LAST);
		exit();
	}

	$values = array(":default" => $config['default_template'], ":id" => $_GET['id']);
	$db2->query("UPDATE `_PREFIX_users` SET `user_template`=:default WHERE `user_template`=:id");
	$db2->query("DELETE FROM `_PREFIX_templates` WHERE `template_id`=:id", array(":id" => $_GET['id']));
	$ok = $db2->rowCount > 0;
	
	// Check results. 
	if($ok) {
		$_SESSION['return_url'] = "template.php";
		header("location: error.php?code=".ERR_CODE_TEMPLATE_DELETE_SUCCESS);
		exit();		
	} else {
		$_SESSION['return_url'] = "template.php";
		header("location: error.php?code=".ERR_CODE_TEMPLATE_CANT_DELETE);
		exit();
	}
	
} else {
	
	$_SESSION['csrf_weird_token'] = CSRF::getHTML();
	
	/**
	 * Manage templates
	 */
	$tplTemplatesManage = new Template("templates_manage.tpl");

	// Fetch templates and add to block.
	$db2->query("SELECT `template_id`, `template_name`, `template_folder`, `template_usable` FROM `_PREFIX_templates`");
	while($result = $db2->fetch()) {
		$tplTemplatesManage->addToBlock("templateslist_item", array(
			"ID" => $result['template_id'],
			"NAME" => $result['template_name'],
			"FOLDER" => $result['template_folder'],
			"USABLE" => ($result['template_usable'] == 1) ? "checked" : ""
		));
	}
	
	// Add to sub-view.
	$tplTemplates->addToTag("template_page", $tplTemplatesManage);
}

outputPage($tplTemplates);
exit();
?>
