<?php
define("IN_IBB", 1);

$root_path = "./";
require_once($root_path."includes/common.php");
require_once($root_path."classes/calendar.php");
$language->add_file("calendar");
Template::addNamespace("L", $lang);

$cal = new Calendar($lang);

outputPage($cal->getTPL());
exit();
?>
