<?php

/**********************************************************
*
*			language/english/main.php
*
*	      ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

$lang['Account_Activation'] = "Account Activation";
$lang['Activation_Successful'] = "Activation Successful";
$lang['Activation_Successful_Msg'] = "Activation of account '%s' was successful.<br />You may now log into your account."; // %s = Newly registered username
$lang['Activation_Error'] = "Activation Error";
$lang['Activation Error_Msg'] = "Sorry, either the user id or activation key you entered was invalid.<br />Please try again";
$lang['Already_Activated_Msg'] = "Your account has already been previously activated. Please login via the login page.";
$lang['Already_Logged_in'] = "Sorry, you are already logged in as %s. If this is not you please %slogout%s and return to this page."; // First %s = Username -- Others are links
$lang['Successful_Login_Msg'] = "You are now logged in as %s."; // %s = The users username
$lang['Invalid_Login_Msg'] = "Sorry, the username or password you entered is invalid. If you have forgotten your password please use the 'forgotten password' link";
$lang['Account_Disabled'] = "Sorry, this account is disabled, if you think this is an error please contact an administrator";
$lang['Invalid_Activation_Key'] = "Sorry the activation key you entered is invalid. If you typed it manually please retry.";
$lang['Account_Activated'] = "Your account has been successfully activated. You may now login to your account.";
$lang['Must_Be_Logged_In'] = "Sorry, you must be logged in to use this feature.";
$lang['Logged_Out_Msg'] = "You are now logged out.";

//
// Forgotten Password
//
$lang['Forgotten_Password'] = "Forgotten Password";
$lang['Forgotten_Password_Email_Subject'] = $config['site_name'] . " password recovery";
$lang['Activate_New_Pass_Error'] = "Error activating your new password, invalid user id or activation key";
$lang['New_Pass_Activation_Successful_Msg'] = "Your new password has been successfully activated.";

?>