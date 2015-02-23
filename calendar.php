<?php
define("IN_IBB", 1);

$root_path = "./";
require_once($root_path."includes/common.php");
require_once($root_path."classes/calendar.php");
$language->add_file("calendar");
Template::addNamespace("L", $lang);

$cal = new Calendar($lang);

// Birthdays
$db2->query("SELECT `user_birthday`, `username`, `user_id` 
	FROM `_PREFIX_users` 
	WHERE `user_birthday`=:bday", 
	array(":bday" => (string)(date("Y-m-d"))));

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
