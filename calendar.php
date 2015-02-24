<?php
define("IN_IBB", 1);

$root_path = "./";
require_once($root_path."includes/common.php");
require_once($root_path."classes/calendar.php");
$language->add_file("calendar");
Template::addNamespace("L", $lang);

$cal = new Calendar($lang);

$month = isset($_GET['month']) ? $_GET['month'] : date("m");
// Birthdays
$db2->query("SELECT `user_birthday`, `username`, `user_id` 
	FROM `_PREFIX_users` 
	WHERE `user_birthday` LIKE :bday", 
	array(":bday" => "%-".$month."-%"));

while($result = $db2->fetch()) {
	$bday = parseBirthday($result["user_birthday"]);
	$bday["year"] = "";
	$cal->addEvent($result["username"], 
		"profile.php?id=".$result["user_id"], 
		$bday);
}

outputPage($cal->getTPL());
exit();
?>
