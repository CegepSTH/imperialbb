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
	
	/**
	 * EDIT INFOS
	 */
} else if($_GET['func'] == "edit") {
	// Usergroup not even set.
	if(!isset($_POST['usergroup'])) { 
		$_SESSION['return_url'] = "usergroups.php?func=search"; 
		header("location: error.php?code=".ERR_CODE_USERGROUP_NOT_FOUND);
		exit();
	}
	// Check CSRF
	CSRF::validate();
		
	// Do they want permissions ? If so redirect.
	if(isset($_POST['edit_permissions'])) {
		$_SESSION['usergroup_edit_name'] = $_POST['usergroup'];
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
	
	/**
	 * EDIT PERMISSIONS
	 */
} else if($_GET['func'] == "permissions") {
	// Check if edit name was set.
	if(!isset($_SESSION['usergroup_edit_name'])) {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_NAME_MUSTNT_BE_EMPTY);
		exit();
	}
	
	$ug_name = $_SESSION['usergroup_edit_name'];
	$tplUsergroupPermsEdit = new Template("usergroups_permissions.tpl");
	
	// fetch usergroup informations.
	$db2->query("SELECT * FROM `_PREFIX_usergroups` WHERE `name`=:name", array(":name" => $ug_name));
	$result = $db2->fetch();
	
	// If not found.
	if(is_null($result) || $result === false) {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_NOT_FOUND);
		exit();	
	}
	
	// Substitute in template.
	$tplUsergroupPermsEdit->setVars(array(
		"CSRF_TOKEN" => CSRF::getHTML(),
		"GROUP_ID" => $result['id'],
		"GROUP_NAME" => $result['name'] ));
		
	// Show subforums.
	$pdo_forum = $db2->query("SELECT * FROM `_PREFIX_forums` ORDER BY `forum_id` DESC");
	
	while($forum = $pdo_forum->fetch()) {
		$values = array(":uid" => $result['id'], ":fid" => $forum['forum_id']);
		$db2->query("SELECT * FROM `_PREFIX_ug_auth` WHERE `usergroup`=:uid AND `ug_forum_id`=:fid", $values);
		
		if($result_auth = $db2->fetch()) {
			$tplUsergroupPermsEdit->addToBlock("forumslist_item", array(
				"FORUM_ID" => $forum['forum_id'],
				"FORUM_NAME" => $forum['forum_name'],
				"READ_TRUE" => ($result_auth['ug_read'] == 1) ? "selected" : "",
				"READ_FALSE" => ($result_auth['ug_read'] == 0) ? "selected" : "",
				"READ_DEFAULT" => ($result_auth['ug_read'] == 2) ? "selected" : "",
				"POST_TRUE" => ($result_auth['ug_post'] == 1) ? "selected" : "",
				"POST_FALSE" => ($result_auth['ug_post'] == 0) ? "selected" : "",
				"POST_DEFAULT" => ($result_auth['ug_post'] == 2) ? "selected" : "",
				"REPLY_TRUE" => ($result_auth['ug_reply'] == 1) ? "selected" : "",
				"REPLY_FALSE" => ($result_auth['ug_reply'] == 0) ? "selected" : "",
				"REPLY_DEFAULT" => ($result_auth['ug_reply'] == 2) ? "selected" : "",
				"POLL_TRUE" => ($result_auth['ug_poll'] == 1) ? "selected" : "",
				"POLL_FALSE" => ($result_auth['ug_poll'] == 0) ? "selected" : "",
				"POLL_DEFAULT" => ($result_auth['ug_poll'] == 2) ? "selected" : "",
				"CREATE_POLL_TRUE" => ($result_auth['ug_create_poll'] == 1) ? "selected" : "",
				"CREATE_POLL_FALSE" => ($result_auth['ug_create_poll'] == 0) ? "selected" : "",
				"CREATE_POLL_DEFAULT" => ($result_auth['ug_create_poll'] == 2) ? "selected" : "",
				"MOD_TRUE" => ($result_auth['ug_mod'] == 1) ? "selected" : "",
				"MOD_FALSE" => ($result_auth['ug_mod'] == 0) ? "selected" : "",
				"MOD_DEFAULT" => ($result_auth['ug_mod'] == 2) ? "selected" : ""
			));
		} else {
			$tplUsergroupPermsEdit->addToBlock("forumslist_item", array(
				"FORUM_ID" => $forum['forum_id'],
				"FORUM_NAME" => $forum['forum_name'],
				"READ_TRUE" => "",
				"READ_FALSE" => "",
				"READ_DEFAULT" => "SELECTED",
				"POST_TRUE" => "",
				"POST_FALSE" => "",
				"POST_DEFAULT" => "SELECTED",
				"REPLY_TRUE" => "",
				"REPLY_FALSE" => "",
				"REPLY_DEFAULT" => "SELECTED",
				"POLL_TRUE" => "",
				"POLL_FALSE" =>  "",
				"POLL_DEFAULT" => "SELECTED",
				"CREATE_POLL_TRUE" => "",
				"CREATE_POLL_FALSE" => "",
				"CREATE_POLL_DEFAULT" => "SELECTED",
				"MOD_TRUE" => "",
				"MOD_FALSE" => "",
				"MOD_DEFAULT" =>"SELECTED"
				));
		}		
	}
	// Add to subview.
	$tplUsergroups->addToTag("usergroups_page", $tplUsergroupPermsEdit);
	
	/**
	 * SAVING EDIT
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
	
	// Ensure name is not empty.
	if(trim($_POST['usergroupName']) == "") {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_NAME_MUSTNT_BE_EMPTY);
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
	// If not in editing; Relocate.
	if(!isset($_SESSION['usergroup_edit_name'])) {
		header("location: usergroups.php?func=search");
		exit();
	}
	// Fetch usergroup id.
	$ug_name = $_SESSION['usergroup_edit_name'];
	$db2->query("SELECT `id` FROM `_PREFIX_usergroups` WHERE `name`=:name LIMIT 1", array(":name" => $ug_name));
	$res = $db2->fetch();
	$ug_id = $res['name'];
	
	$db2->query("SELECT * FROM `_PREFIX_forums`");
	$ok = false;
	
	while($result = $db2->fetch()) {
		$forum_id = $result['forum_id'];

		if($_POST[$forum_id]['Read'] == "2" 
			&& $_POST[$forum_id]['Post'] == "2" 
			&& $_POST[$forum_id]['Reply'] == "2" 
			&& $_POST[$forum_id]['Poll'] == "2" 
			&& $_POST[$forum_id]['Create_Poll'] == "2" 
			&& $_POST[$forum_id]['Mod'] == "2")
		{
			$values = array(":id" => $ug_id, ":fid" => $forum_id);
			$db2->query("DELETE FROM `_PREFIX_ug_auth` WHERE `usergroup`=:id AND `ug_forum_id`=:fid", $values);
		} else {
			$values = array(":id" => $ug_id, ":fid" => $forum_id, ":fread" => $_POST[$forum_id]['Read'], 
				":fpost" => $_POST[$forum_id]['Post'], ":freply" => $_POST[$forum_id]['Reply'], ":cpoll" => $_POST[$forum_id]['Create_Poll'],
				":poll" => $_POST[$forum_id]['Poll'], ":mod" => $_POST[$forum_id]['Mod'], ":idd" => $ug_id, ":fidd" => $forum_id);

			$ug_sql = $db2->query("SELECT * FROM `_PREFIX_ug_auth` WHERE `usergroup`=:id AND `ug_forum_id`=:fid", $values);
					
			if(!$db2->fetch()) {
				$db2->query("INSERT INTO `_PREFIX_ug_auth`
					VALUES(:id, :fid, :fread, :fpost, :freply, :cpoll, :poll, :mod)", $values);
			} else {
				$db2->query("UPDATE `_PREFIX_ug_auth`
					SET `usergroup`=:id, `ug_forum_id`=:fid, `ug_read`=:fread, `ug_post`=:fpost, `ug_reply`=:freply, `ug_create_poll`=:fcpoll, `ug_poll`=:poll, `ug_mod`=:mod
					WHERE `usergroup`=:idd AND `ug_forum_id`=:fidd", $values);
			}
			
			$ok = $db2->rowCount() > 0;
		}	
	}
	
	// determine return message.
	if($ok) {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		// Unset var.
		unset($_SESSION['usergroup_edit_name']);
		header("location: error.php?code=".ERR_CODE_UG_PERMISSIONS_UPDATE_SUCCESS);
		exit();
	} else {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_UG_PERMISSIONS_CANT_UPDATE);
		exit();
	}
	
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
	// Check if valid request.
	if(!isset($_SESSION['create_group_name']) || trim($_SESSION['create_group_name']) == "") {
		$_SESSION['return_url'] = "usergroups.php?func=search";
		header("location: error.php?code=".ERR_CODE_USERGROUP_NAME_MUSTNT_BE_EMPTY);
		exit();
	}
	
	$ug_name = $_SESSION['create_group_name']; 
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

/**
 * /*

			info_box($lang['Usergroup_Permissions'], $lang['Usergroup_Perm_Msg'], "usergroups.php?func=permissions");

			}
 **/
?>
