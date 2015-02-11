<?php

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path."includes/common.php");
$language->add_file("message");

// If no error code, just redirect to index.
if(!isset($_GET['code']) || !is_numeric($_GET['code'])) {
	header("location: index.php");
	exit();
}

$tplError = new Template("message.tpl");

if(isset($lang['err_code'.$_GET['code']])) {   
	$tplError->setVar("MESSAGE_CONTENT", $lang['err_code'.$_GET['code']]);
}

if(isset($_SESSION['return_url'])) {
	$tplError->setVar("RETURN_URL", $_SESSION['return_url']);
} else {
	$tplError->setVar("RETURN_URL", "index.php");
}

outputPage($tplError);
?>
