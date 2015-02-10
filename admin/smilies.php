<?php

/**********************************************************
*
*			admin/smilies.php
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
Template::setBasePath($root_path . "templates/original/admin");
$language->add_file("admin/smilies");
Template::addNamespace("L", $lang);

$tplSmilies = new Template("smilies.tpl");

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "add")
{
	// SAVE
	if(isset($_POST['Submit'])) {
		CSRF::validate();
		
		$values = array(":name" => $_POST['name'], ":code" => $_POST['code'], ":url" => $_POST['url']);
		$db2->query("INSERT INTO `_PREFIX_smilies` (`smilie_name`, `smilie_code`, `smilie_url`) VALUES (:name, :code, :url)", $values);
		$ok = $db2->rowCount() > 0;
		
		if($ok) {
			$_SESSION['return_url'] = "smilies.php";
			header("location: error.php?code=".ERR_CODE_SMILIES_ADD_SUCCESS);
			exit();		
		} else {
			$_SESSION['return_url'] = "smilies.php";
			header("location: error.php?code=".ERR_CODE_SMILIES_ADD_FAILED);
			exit();		
		}
	} else {
		$tplSmiliesAdd = new Template("smilies_add.tpl");
		$tplSmiliesAdd->setVar("CSRF_TOKEN", CSRF::getHTML());
		$tplSmilies->addToTag("smilies_page", $tplSmiliesAdd);
	}
}
else if($_GET['func'] == "edit")
{
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		die("wut");
		$_SESSION['return_url'] = "smilies.php";
		header("location: error.php?code=".ERR_CODE_SMILIES_INVALID_ID);
		exit();
	}
	
	if(isset($_POST['Submit'])) {
		CSRF::validate();
		
		$values = array(":sname" => $_POST['name'], ":scode" => $_POST['code'], ":url" => $_POST['url'], ":sid" => $_GET['id']);
		$db2->query("UPDATE `_PREFIX_smilies` 
			SET `smilie_name`=:sname, `smilie_code`=:scode, `smilie_url`=:url 
			WHERE `smilie_id`=:sid", $values);
		$ok = $db2->rowCount() > 0;
		
		if($ok) {
			$_SESSION['return_url'] = "smilies.php";
			header("location: error.php?code=".ERR_CODE_SMILIES_UPDATE_SUCCESS);
			exit();
		} else {
			$_SESSION['return_url'] = "smilies.php";
			header("location: error.php?code=".ERR_CODE_SMILIES_UPDATE_FAILED);
			exit();
		}
	} else {
		$db2->query ("SELECT * FROM `_PREFIX_smilies` WHERE `smilie_id`=:id LIMIT 1", array(":id" => $_GET['id']));
		
		if ($result = $db2->fetch()) {
			$tplSmiliesEdit = new Template("smilies_edit.tpl");
			$tplSmiliesEdit->setVars(array(
				"CSRF_TOKEN" => CSRF::getHTML(),
				"ID" => $_GET['id'],
				"NAME" => $result['smilie_name'],
				"CODE" => $result['smilie_code'],
				"URL" => $result['smilie_url']
			));
			
			// add to subview
			$tplSmilies->addToTag("smilies_page", $tplSmiliesEdit);
			
		} else {
			$_SESSION['return_url'] = "smilies.php?func=edit&id=".$_GET['id'];
			header("location: error.php?code=".ERR_CODE_SMILIES_INVALID_ID);
			exit();
		}
	}
}
else if($_GET['func'] == "delete")
{
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['return_url'] = "smilies.php";
		header("location: error.php?code=".ERR_CODE_SMILIES_INVALID_ID);
		exit();
	}

	$db2->query("DELETE FROM `_PREFIX_smilies` WHERE `smilie_id`=:id", array(":id" => $_GET['id']));
	$ok = $db2->rowCount() > 0;
	
	if($ok) {
		$_SESSION['return_url'] = "smilies.php";
		header("location: error.php?code=".ERR_CODE_SMILIES_DELETE_SUCCESS);
		exit();
	} else {
		$_SESSION['return_url'] = "smilies.php";
		header("location: error.php?code=".ERR_CODE_SMILIES_DELETE_FAILED);
		exit();	
	}
} else {
	
	$tplSmiliesManage = new Template("smilies_manage.tpl");
	
	$db2->query ("SELECT * FROM `_PREFIX_smilies`");
	while($result = $db2->fetch()) {
		$tplSmiliesManage->addToBlock("smilieslist_item", array(
			"ID" => $result['smilie_id'],
			"NAME" => $result['smilie_name'],
			"CODE" => $result['smilie_code'],
			"URL" => $result['smilie_url']
		));
	}
	
	$tplSmilies->addToTag("smilies_page", $tplSmiliesManage);
}

outputPage($tplSmilies);
?>
