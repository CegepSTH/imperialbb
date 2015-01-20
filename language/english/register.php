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

$lang['Registration'] = "Registration";
$lang['Already_Logged_in'] = "Sorry, you are already logged in as %s. If this is not you please %slogout%s and return to this page."; // First %s = Username -- Others are links
$lang['Username_Too_Short'] = "Your username is too short";
$lang['Username_Already_Taken'] = "That username is already taken";
$lang['Registration_Successfull_Msg'] = "Your registration was successful. You may now log into your account";
$lang['Activate_Your_Acct_Msg'] = "Your registration was successful. You will now need to activate your account via a link sent by email in order to login.";
$lang['Email_New_Account_Subject'] = "New account at " . $config['site_name'] . "";

?>