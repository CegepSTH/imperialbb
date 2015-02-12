<?php

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

$lang['message'] = "Message";
$lang['do_not_wait'] = "Do not wait";

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

?>
