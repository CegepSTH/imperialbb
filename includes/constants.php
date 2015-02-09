<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: constants.php                                              # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

// Topic Types
define("ANNOUNCMENT", 2);
define("PINNED", 1);
define("GENERAL", 0);

// Avatar Types
define("NO_AVATAR", 0);
define("UPLOADED_AVATAR", 1);
define("REMOTE_AVATAR", 2);

//ERRORS
define("ERR_CODE_USERNAME_NOT_SET", 10);
define("ERR_CODE_USER_NOT_FOUND", 20);
define("ERR_CODE_USER_CANT_UPDATE", 30);
define("ERR_CODE_USER_CANT_DELETE", 40);
define("ERR_CODE_USER_UPDATE_SUCCESS", 50);
define("ERR_CODE_USER_DELETE_SUCCESS", 60);
define("ERR_CODE_USER_PASS_MISMATCH", 70);
define("ERR_CODE_USERGROUP_NOT_FOUND", 80);

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
