<?php

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path."includes/common.php");
Template::setBasePath($root_path . "templates/original/admin/");

$language->add_file("admin/usergroups");
Template::addNamespace("L", $lang);

if(!isset($_GET['func'])) $_GET['func'] = "";

$tplUsergroups = new Template("usergroups.tpl");

/**
 * Permissions
 */
if($_GET['func'] == "search") {
	$db2->query("SELECT * FROM `_PREFIX_usergroups`");
	
	// Process the search sub-view. Add items to users list.
	$tplUsergroupsSearch = new Template("usergroups_search.tpl");
	
	while($result = $db2->fetch()) {
		$tplUsergroupsSearch ->addToBlock("usergroupslist_item", array(
			"USERGROUP_NAME" => $result['name']
		));
	}
	
	$tplUsergroupsSearch ->setVar("CSRF_TOKEN", CSRF::getHTML());
	// Add usersearch sub-view to main user view.
	$tplUsergroups->addToTag("usergroups_page", $tplUsergroupsSearch);
	
} else if($_GET['func'] == "edit") {
	if(!isset($_POST['usergroupName'])) { 
		CSRF::validate();
		
		// Do they want permissions ? If so redirect.
		if(isset($_POST['edit_permissions'])) {
			header("location: usergroups.php?func=permissions");
			exit();
		} else if(isset($_POST['creategroup'])) {
			$_SESSION['create_group_name'] = $_POST['usergroup'];
			header("location: usergroups.php?func=create");
			exit();
		}
		
		// Fetch usergroup
		$db2->query("SELECT * FROM `_PREFIX_usergroups` WHERE `name`=:name", 
			array(":name" => $_POST['usergroup']));
		$result = $db2->fetch();
		
		// Check if existing.
		if($result == false || is_null($result)) {
			$_SESSION['return_url'] = "usergroups.php?func=search"; 
			header("location: error.php?code=".ERR_CODE_USERGROUP_NOT_FOUND);
			exit();		
		}
		
		$_SESSION['usergroup_edit_id'] = $result['id'];
		
		// Edit the current group. Show the field.
		$tplUsergroupEdit = new Template("usergroups_edit.tpl");
		$tplUsergroupEdit->setVars(array("USERGROUP_NAME" => $result['name'], 
			"USERGROUP_DESC" => $result['desc'],
			"CSRF_TOKEN" => CSRF::getHTML()));
			
		// Add to sub-view.
		$tplUsergroups->addToTag("usergroups_page", $tplUsergroupEdit);		
	} else {
		$_SESSION['return_url'] = "usergroups.php?func=search"; 
		header("location: error.php?code=".ERR_CODE_USERGROUP_NOT_FOUND);
		exit();
	}
} else if($_GET['func'] == "permissions") {
	/**
	 * EDIT PERMISSIONS
	 */
	 
} else if($_GET['func'] == "save_edit") {
	CSRF::validate();
	
	// Does he wants to delete ?
	if(isset($_POST['ug_delete'])) {
		header("location: usergroups.php?func=delete");
		exit();
	}
	
	// Look if there was indeed a usergroup begin modified.
	if(!isset($_SESSION['usergroup_edit_id'])) {
		$_SESSION['return_url'] = "usergroups.php?func=search"; 
		header("location: error.php?code=".ERR_CODE_USERGROUP_NOT_FOUND);
		exit();			
	}
	
	$db2->query("UPDATE `_PREFIX_usergroups` SET `name`=:name, `desc`=:desc
		WHERE `id`=:ugid", array(":name" => $_POST['usergroupName'],
		":desc" => $_POST['usergroupDescription'], 
		":ugid" => $_SESSION['usergroup_edit_id']));
	
	// Unset in-modification usergroup session.
	unset($_SESSION['usergroup_edit_id']);
	
	// Redirect with proper messaging.
	if($db2->rowCount() > 0) {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_UPDATE_SUCCESS);
		exit();
	} else {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_CANT_UPDATE);
		exit();		
	}
} else if($_GET['func'] == "save_permissions") {
	
} else if($_GET['func'] == "delete") {
	// Check if we really were modifying that fucker.
	if(!isset($_SESSION['usergroup_edit_id'])) {
		header("location: usergroups.php?func=search");
		exit();
	}
	$id = $_SESSION['usergroup_edit_id'];
	// Set current usergroup id to 0 (zero) for those who were in the 
	// next-to-be deleted usergroup.
	$db2->query("UPDATE `_PREFIX_users` SET `user_usergroup`='0' 
		WHERE `user_usergroup`=:ugid", 
		array(":ugid" => $id));
		
	// Delete usergroup
	$db2->query("DELETE FROM `_PREFIX_usergroups` WHERE `id`=:ugid", array(":ugid" => $id));
	$ok = $db2->rowCount() > -1;
	
	// Delete authorizations.
	$db2->query("DELETE FROM `_PREFIX_ug_auth` WHERE `usergroup`=:ugid", array(":ugid" => $id));
	
	// Unset session var.
	unset($_SESSION['usergroup_edit_id']);
	
	// Show appropriate message according to what we did.
	if($db2->rowCount() > -1 && $ok) {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_DELETE_SUCCESS);
		exit();
	} else {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_CANT_DELETE);
		exit();	
	}	
} else if($_GET['func'] == "create") {
	CSRF::validate();
	$ug_name = $_SESSION['create_group_name'] ?: ""; 
	$ug_desc = "";
	
	$db2->query("INSERT INTO `_PREFIX_usergroups` (`name`, `desc`) 
		VALUES(:name, :desc)", array(":name" => $ug_name, ":desc" => $ug_desc));
		
	// Unset session var.
	unset($_SESSION['create_group_name']);
	if($db2->rowCount() > 0) {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_CREATE_SUCCESS);
		exit();			
	} else {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_CANT_CREATE);
		exit();			
	}
	
} else {
	// Redirect to search.
	header("location: usergroups.php?func=search");
	exit();
}

outputPage($tplUsergroups);
?>
