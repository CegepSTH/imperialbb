<?php
define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path . "includes/common.php");
$language->add_file("admin/bbcode");
Template::addNamespace("L", $lang);

// BBCode is hardcoded.
$_SESSION["return_url"] = "index.php";
header("Location: error.php?code=".ERR_CODE_BBCODE_HARDCODED);
exit();

?>
