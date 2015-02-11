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
define("ERR_CODE_USERGROUP_CANT_UPDATE", 90);
define("ERR_CODE_USERGROUP_UPDATE_SUCCESS", 100);
define("ERR_CODE_USERGROUP_CANT_CREATE", 110);
define("ERR_CODE_USERGROUP_CREATE_SUCCESS", 120);
define("ERR_CODE_USERGROUP_DELETE_SUCCESS", 130);
define("ERR_CODE_USERGROUP_CANT_DELETE", 140);
define("ERR_CODE_USERGROUP_NAME_MUSTNT_BE_EMPTY", 150);
define("ERR_CODE_UG_PERMISSIONS_UPDATE_SUCCESS", 160);
define("ERR_CODE_UG_PERMISSIONS_CANT_UPDATE", 170);
define("ERR_CODE_TEMPLATE_INVALID_ID", 180);
define("ERR_CODE_TEMPLATE_CANT_DELETE_LAST", 190);
define("ERR_CODE_TEMPLATE_DELETE_SUCCESS", 200);
define("ERR_CODE_TEMPLATE_CANT_DELETE", 210);
define("ERR_CODE_TEMPLATE_NAME_CANT_EMPTY", 220);
define("ERR_CODE_TEMPLATE_ADDED_SUCCESS", 230);
define("ERR_CODE_TEMPLATE_CANT_ADD", 240);
define("ERR_CODE_TEMPLATE_FOLDER_DOESNT_EXIST", 250);
define("ERR_CODE_TEMPLATE_FOLDER_CANT_EMPTY", 260);
define("ERR_CODE_TEMPLATE_EDIT_SUCCESS", 270);
define("ERR_CODE_TEMPLATE_EDIT_FAILED", 280);
define("ERR_CODE_SMILIES_CANT_ADD", 290);
define("ERR_CODE_SMILIES_INVALID_ID", 300);
define("ERR_CODE_SMILIES_DELETE_FAILED", 310);
define("ERR_CODE_SMILIES_DELETE_SUCCESS", 320);
define("ERR_CODE_SMILIES_ADD_SUCCESS", 330);
define("ERR_CODE_SMILIES_ADD_FAILED", 340);
define("ERR_CODE_SMILIES_UPDATE_FAILED", 350);
define("ERR_CODE_SMILIES_UPDATE_SUCCESS", 360);
define("ERR_CODE_RANKS_INVALIDID", 370);
define("ERR_CODE_RANKS_DELETED", 380);
define("ERR_CODE_RANKS_DELETE_FAILED", 390);


/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
