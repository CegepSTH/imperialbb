<?php
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
define("ERR_CODE_BBCODE_HARDCODED", 302);
define("ERR_CODE_ADMIN_CONFIG_UPDATED", 312); 
define("ERR_CODE_ADMIN_LANGUAGE_ADDED", 322);
define("ERR_CODE_ADMIN_INVALID_LANGUAGE_ID", 332);
define("ERR_CODE_ADMIN_LANGUAGE_EDITED", 342);
define("ERR_CODE_ADMIN_LANGUAGE_CANNOT_DELETE_LAST", 352);
define("ERR_CODE_ADMIN_LANGUAGE_DELETED", 362);
define("ERR_CODE_RANK_CREATED", 372);
define("ERR_CODE_RANKS_UPDATED", 382);
define("ERR_CODE_NOTEPAD_UPDATED", 392);
define("ERR_CODE_FORUM_CREATED", 402);
define("ERR_CODE_CATEGORY_NO_NAME_SET", 412);
define("ERR_CODE_CATEGORY_CREATED", 422);
define("ERR_CODE_CATEGORY_UPDATED", 432);
define("ERR_CODE_CATEGORY_INVALID_ID", 442);
define("ERR_CODE_FORUM_NO_NAME_SET", 452);
define("ERR_CODE_FORUM_UPDATED", 462);
define("ERR_CODE_FORUM_DELETED", 472);
define("ERR_CODE_CATEGORY_DELETED", 482);

// Public errors
define("ERR_CODE_NO_TOPIC_ID_SPECIFIED", 400);
define("ERR_CODE_VOTE_ALREADY_CASTED", 410);
define("ERR_CODE_INVALID_VOTE_ID", 420);
define("ERR_CODE_VOTE_CASTED_SUCCESS", 430);
define("ERR_CODE_NEED_READ_PERMISSIONS", 440);
define("ERR_CODE_INVALID_TOPIC_ID", 450);
define("ERR_CODE_INVALID_PERMISSION_MOD", 460);
define("ERR_CODE_REQUIRE_LOGIN", 470);
define("ERR_CODE_TOPIC_DELETE_SUCCESS", 480);
define("ERR_CODE_INVALID_POST_ID", 490);
define("ERR_CODE_TOPIC_CANT_DELETE_LAST_MSG", 500);
define("ERR_CODE_LAST_POST_IN_FORUM", 510);
define("ERR_CODE_POST_DELETE_SUCCESS", 520);
define("ERR_CODE_INVALID_FORUM_ID", 530);
define("ERR_CODE_TOPIC_MOVE_SUCCESS", 540);
define("ERR_CODE_TOPIC_LOCK_SUCCESS", 550);
define("ERR_CODE_TOPIC_UNLOCK_SUCCESS", 560);
define("ERR_CODE_TOPIC_INVALID_TYPE", 570);
define("ERR_CODE_TOPIC_TYPE_CHANGE_SUCCESS", 580);

// Profile.php
define("ERR_CODE_PROFILE_CANT_CHANGE_AVATAR", 590);
define("ERR_CODE_PROFILE_UPDATE_SUCCESS", 600);
define("ERR_CODE_INVALID_USER_ID", 610);
define("ERR_CODE_ACCOUNT_DELETED_SUCCESS", 620);
define("ERR_CODE_INVALID_TOKEN_ID", 630);
define("ERR_CODE_DELETION_CHECK_MAIL", 640);
define("ERR_CODE_INVALID_PERMISSION_READ", 650);

// login.php errors.
define("ERR_CODE_LOGIN_ACTIVATION_ERROR", 401);
define("ERR_CODE_LOGIN_ALREADY_ACTIVATED", 411);
define("ERR_CODE_LOGIN_RESET_PASSWORD_ERROR", 421);
define("ERR_CODE_LOGIN_RESET_PASSWORD_SUCCESS", 431);
define("ERR_CODE_LOGIN_RESET_PASSWORD_INVALID_ID", 441);
define("ERR_CODE_LOGIN_SUCCESS", 451);
define("ERR_CODE_LOGIN_INVALID_ID", 461);
define("ERR_CODE_LOGIN_ALREADY_LOGGED_IN", 471);

//register
define("ERR_CODE_ACCOUNT_ALREADY_ACTIVATED", 504);
define("ERR_CODE_INVALID_ACTIVATION_KEY", 514);
define("ERR_CODE_ACTIVATION_SUCCESS", 524);
define("ERR_CODE_ACTIVATE_ACCOUNT", 534);


// posting
define("ERR_CODE_INVALID_PERMISSION_POST", 544);
define("ERR_CODE_MESSAGE_POSTED_SUCCESS", 554);
define("ERR_CODE_INVALID_PERMISSION_REPLY", 564);
define("ERR_CODE_TOPIC_IS_CLOSED", 574);
define("ERR_CODE_USER_CANT_EDIT_POST", 584);
define("ERR_CODE_POST_EDITED_SUCCESS", 594);
define("ERR_CODE_TOPIC_CANT_SELECT_LAST_MSG", 604);

// pm
define("ERR_CODE_PM_SENT", 614);
define("ERR_CODE_EMAIL_SENT", 624);
define("ERR_CODE_INVALID_ACTION", 634);
define("ERR_CODE_INVALID_PM_ID", 644);
define("ERR_CODE_PM_DELETED", 654);
define("ERR_CODE_PM_EDITED", 664);
define("ERR_CODE_UNABLE_FIND_USER_INFORMATIONS", 674);
define("ERR_CODE_THE_BIG_BASTARD_SAYS_YOURE_BANNED_GET_OUT_PLEASE", 684);
define("ERR_CODE_BOARD_OFFLINE", 694);
define("ERR_CODE_UNABLE_SEND_MAIL", 704);
define("ERR_CODE_ACCOUNT_CLOSED", 714);
define("ERR_CODE_ACCOUNT_CREATED", 724);
define("ERR_CODE_LOGIN_ACTIVATION_SUCCESS", 734);
define("ERR_CODE_LOGGED_OUT", 744);

// CSRF
define("ERR_CODE_INVALID_REQUEST", 11000);
// portal
define("ERR_CODE_NEWS_NOT_FOUND", 12000);
define("ERR_CODE_NEWS_ALL_FIELDS_REQUIRED", 12340);
define("ERR_CODE_NEWS_INSERT_SUCCESS", 12341);
define("ERR_CODE_NEWS_INSERT_FAILED", 12342);

?>
