<?php

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

$lang['message'] = "Message";
$lang['do_not_wait'] = "Do not wait";
$lang['err_code'] = "Message constant was not specified!";
$lang['err_code'.ERR_CODE_NO_TOPIC_ID_SPECIFIED] = "No topic id specified.";
$lang['err_code'.ERR_CODE_VOTE_ALREADY_CASTED] = "Vote already casted.";
$lang['err_code'.ERR_CODE_INVALID_VOTE_ID] = "Invalid vote id.";
$lang['err_code'.ERR_CODE_VOTE_CASTED_SUCCESS] = "Vote casted. Now redirecting...";
$lang['err_code'.ERR_CODE_NEED_READ_PERMISSIONS] = "You do not have enough privilege to read this.";
$lang['err_code'.ERR_CODE_LOGIN_ACTIVATION_ERROR] = "Sorry, either the user id or activation key you entered was invalid.<br />Please try again";
$lang['err_code'.ERR_CODE_LOGIN_ALREADY_ACTIVATED] = "Your account has already been previously activated. Please login via the login page.";
$lang['err_code'.ERR_CODE_LOGIN_ACTIVATION_SUCCESS] = "The activation of the account was successful.<br />You may now log into your account.";
$lang['err_code'.ERR_CODE_LOGIN_RESET_PASSWORD_ERROR] = "Error activating your new password, invalid user id or activation key";
$lang['err_code'.ERR_CODE_LOGIN_RESET_PASSWORD_SUCCESS] = "Your new password has been successfully activated.";
$lang['err_code'.ERR_CODE_LOGIN_RESET_PASSWORD_INVALID_ID] = "Sorry, the username or email address you entered is invalid.";
$lang['err_code'.ERR_CODE_LOGIN_SUCCESS] = "You are now logged in.";
$lang['err_code'.ERR_CODE_LOGIN_INVALID_ID] = "Sorry, the username or password you entered is invalid. If you have forgotten your password please use the 'forgotten password' link";
$lang['err_code'.ERR_CODE_LOGIN_ALREADY_LOGGED_IN] = "Sorry, you are already logged in.";
$lang['err_code'.ERR_CODE_LOGGED_OUT] = "You are now logged out.";
$lang['err_code'.ERR_CODE_INVALID_TOPIC_ID] = "Topic specified couldn't be found.";
$lang['err_code'.ERR_CODE_INVALID_PERMISSION_MOD] = "You do not have enough privilege to perform this action.";
$lang['err_code'.ERR_CODE_REQUIRE_LOGIN] = "This action requires login. Click below to proceed.";
$lang['err_code'.ERR_CODE_TOPIC_DELETE_SUCCESS] = "Topic deleted with success.";
$lang['err_code'.ERR_CODE_INVALID_POST_ID] = "Invalid post identifier.";
$lang['err_code'.ERR_CODE_TOPIC_CANT_DELETE_LAST_MSG] = "Cannot delete topic's last message.";
$lang['err_code'.ERR_CODE_LAST_POST_IN_FORUM] = "Cannot delete last post in forum.";
$lang['err_code'.ERR_CODE_POST_DELETE_SUCCESS] = "Post was deleted successfully.";
$lang['err_code'.ERR_CODE_INVALID_FORUM_ID] = "Invalid forum identifier.";
$lang['err_code'.ERR_CODE_TOPIC_MOVE_SUCCESS] = "Topic was moved successfully.";
$lang['err_code'.ERR_CODE_TOPIC_LOCK_SUCCESS] = "Topic was locked successfully.";
$lang['err_code'.ERR_CODE_TOPIC_UNLOCK_SUCCESS] = "Topic was unlocked successfully.";
$lang['err_code'.ERR_CODE_TOPIC_INVALID_TYPE] = "Topic type is invalid.";
$lang['err_code'.ERR_CODE_TOPIC_TYPE_CHANGE_SUCCESS] = "Topic's type was changed successfully.";
$lang['err_code'.ERR_CODE_PROFILE_CANT_CHANGE_AVATAR] = "Couldn't change avatar.";
$lang['err_code'.ERR_CODE_PROFILE_UPDATE_SUCCESS] = "Profile updated successfully.";
$lang['err_code'.ERR_CODE_INVALID_USER_ID] = "Invalid user id.";
$lang['err_code'.ERR_CODE_INVALID_TOKEN_ID] = "Invalid token id.";
$lang['err_code'.ERR_CODE_ACCOUNT_DELETED_SUCCESS] = "Account was deleted with success.";
$lang['err_code'.ERR_CODE_DELETION_CHECK_MAIL] = "A confirmation mail has been sent.";
$lang['err_code'.ERR_CODE_DELETION_PM_SENT] = "A PM has been sent to administrator to close your account.";
$lang['err_code'.ERR_CODE_INVALID_PERMISSION_READ] = "You do not have permission to read.";
$lang['err_code'.ERR_CODE_ACCOUNT_ALREADY_ACTIVATED] = "Account already activated.";
$lang['err_code'.ERR_CODE_INVALID_ACTIVATION_KEY] = "Invalid activation key.";
$lang['err_code'.ERR_CODE_ACTIVATION_SUCCESS] = "Account successfully activated.";
$lang['err_code'.ERR_CODE_ACTIVATE_ACCOUNT] = "Please activate your account.";
$lang['err_code'.ERR_CODE_INVALID_PERMISSION_POST] = "You do not have permission to post.";
$lang['err_code'.ERR_CODE_MESSAGE_POSTED_SUCCESS] = "Message posted successfully.";
$lang['err_code'.ERR_CODE_INVALID_PERMISSION_REPLY] = "You do not have permission to reply.";
$lang['err_code'.ERR_CODE_TOPIC_IS_CLOSED] = "Sorry, this action impossible because the topic is closed.";
$lang['err_code'.ERR_CODE_USER_CANT_EDIT_POST] = "Sorry, you cannot edit this post.";
$lang['err_code'.ERR_CODE_POST_EDITED_SUCCESS] = "Message has been edited successfully.";
$lang['err_code'.ERR_CODE_TOPIC_CANT_SELECT_LAST_MSG] = "Action couldn't be performed since it is the last message in the topic.";
$lang['err_code'.ERR_CODE_PM_SENT] = "Private message sent successfully.";
$lang['err_code'.ERR_CODE_EMAIL_SENT] = "Email sent successfully.";
$lang['err_code'.ERR_CODE_INVALID_ACTION] = "Invalid action.";
$lang['err_code'.ERR_CODE_USER_NOT_FOUND] = "User not found.";
$lang['err_code'.ERR_CODE_INVALID_PM_ID] = "Invalid private message id.";
$lang['err_code'.ERR_CODE_PM_DELETED] = "Private message deleted successfully.";
$lang['err_code'.ERR_CODE_PM_EDITED] = "Private message edited successfully.";
$lang['err_code'.ERR_CODE_UNABLE_FIND_USER_INFORMATIONS] = "Unable to find user informations.";
$lang['err_code'.ERR_CODE_THE_BIG_BASTARD_SAYS_YOURE_BANNED_GET_OUT_PLEASE] = sprintf($lang['Banned_Msg'], $config['admin_email'], $config['admin_email']);
$lang['err_code'.ERR_CODE_BOARD_OFFLINE] = $config['offline_message'];
$lang['err_code'.ERR_CODE_UNABLE_SEND_MAIL] = "Unable to send mail";
$lang['err_code'.ERR_CODE_INVALID_REQUEST] = "Invalid request sent. Please go back and try again.";
$lang['err_code'.ERR_CODE_ACCOUNT_CLOSED] = "Sorry, but this account is closed.";
$lang['err_code'.ERR_CODE_ACCOUNT_CREATED] = "Your account was successfully created. You may now login.";
$lang['err_code'.ERR_CODE_NEWS_NOT_FOUND] = "Sorry, the news you requested couldn't be found.";
$lang['err_code'.ERR_CODE_NEWS_ALL_FIELDS_REQUIRED] = "The news requires all fields.";
$lang['err_code'.ERR_CODE_ACC_DELETE_NO_REASON_PROVIDED] = "No reason was provided.";
$lang['err_code'.ERR_CODE_COULDNT_DELETE_ACCOUNT] = "Couldn't delete account. Please contact administrator.";
?>
