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

//$lang['Account_Activation'] = "Account Activation";
//$lang['Activation_Successful'] = "Activation Successful";
//$lang['Activation_Successful_Msg'] = "Activation of account '%s' was successful.<br />You may now log into your account."; // %s = Newly registered username
//$lang['Activation_Error'] = "Activation Error";
//$lang['Activation Error_Msg'] = "Sorry, either the user id or activation key you entered was invalid.<br />Please try again";
//$lang['Already_Activated_Msg'] = "Your account has already been previously activated. Please login via the login page.";
//$lang['Already_Logged_in'] = "Sorry, you are already logged in as %s. If this is not you please %slogout%s and return to this page."; // First %s = Username -- Others are links
//$lang['Successful_Login_Msg'] = "You are now logged in as %s."; // %s = The users username
//$lang['Invalid_Login_Msg'] = "Sorry, the username or password you entered is invalid. If you have forgotten your password please use the 'forgotten password' link";
//$lang['Invalid_Activation_Key'] = "Sorry the activation key you entered is invalid. If you typed it manually please retry.";
//$lang['Account_Activated'] = "Your account has been successfully activated. You may now login to your account.";
//$lang['Must_Be_Logged_In'] = "Sorry, you must be logged in to use this feature.";
//$lang['Logged_Out_Msg'] = "You are now logged out.";
//
////
//// Forgotten Password
////
//$lang['Forgotten_Password'] = "Forgotten Password";
//$lang['Forgotten_Password_Email_Subject'] = $config['site_name'] . " password recovery";
//$lang['Activate_New_Pass_Error'] = "Error activating your new password, invalid user id or activation key";
//$lang['New_Pass_Activation_Successful_Msg'] = "Your new password has been successfully activated.";

$lang['Account_Activation'] = "Activation du compte";
$lang['Activation_Successful'] = "L'activation réussite";
$lang['Activation_Successful_Msg'] = "L'activation du compte '%s' est réussitte.<br />Vous pouvez maintenant vous connecter."; // %s = Newly registered username
$lang['Activation_Error'] = "Erreur lors de l'activation";
$lang['Activation Error_Msg'] = "Erreur lors de l'activation : soit l'ID ou la clé d'activation que vous avez entré est invalide<br />Essayer de nouveau";
$lang['Already_Activated_Msg'] = "Votre compte est déjà activé. Vous pouvez vous connecter sur la page d'ouverture de session";
$lang['Already_Logged_in'] = "Vous êtes déjà connecté sur le compte %s. S'il ne s'agit pas de vous, %slogout%s puis revenez sur cette page."; // First %s = Username -- Others are links
$lang['Successful_Login_Msg'] = "Vous êtes maintenant connecté sur le compte %s."; // %s = The users username
$lang['Invalid_Login_Msg'] = "Le nom d'utilisateur ou le mot de passe fourni n'est pas valide. Si vous avez oublié votre mot de passe vous pouvez utiliser la fonction 'mot de passe oublié'";
$lang['Invalid_Activation_Key'] = "La clé d'activation que vous avez fourni n'est pas valide. Si vous l'avez écrite à la main vous pouvez vous ressayer.";
$lang['Account_Activated'] = "Votre compte a été activé avec succès, vous pouvez maintenant vous connecter.";
$lang['Must_Be_Logged_In'] = "Vous devez être enregistrer pour utiliser cette fonction.";
$lang['Logged_Out_Msg'] = "Vous êtes maintenant déconnecté.";

//
// Forgotten Password
//
$lang['Forgotten_Password'] = "Mot de passe oublié";
$lang['Forgotten_Password_Email_Subject'] = $config['site_name'] . " récupération du mot de passe";
$lang['Activate_New_Pass_Error'] = "Erreur lors de l'activation de votre mot de passe : l'ID utilisateur ou la clé d'activation est incorrect";
$lang['New_Pass_Activation_Successful_Msg'] = "Votre nouveau mot de passe a été activé avec succès.";

?>