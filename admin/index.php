<?php

/**********************************************************
*
*			admin/index.php
*
*		ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
include($root_path . "includes/common.php");


$theme->new_file("frameset", "frameset.tpl");
$theme->output("frameset");

?>