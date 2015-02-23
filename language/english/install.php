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

// ------------------------
// Global Header Settings
// ------------------------
$lang['head_charset'] = "utf-8";
$lang['head_langdir'] = "ltr";
$lang['head_htmlang'] = "en";

// Errors
$lang['Error'] = "Error";
$lang['Critical_Error'] = "Critical Error";

// Some General Errors
$lang['Unable_To_Send_Email'] = "Unable to send email, please contact an admin about this error";
$lang['Invalid_Post_Id'] = "Invalid post id specified. It may have been deleted.";
$lang['Invalid_Topic_Id'] = "Invalid topic id specified. It may have been deleted.";
$lang['Invalid_Forum_Id'] = "Invalid forum id specified. It may have been deleted.";
$lang['Invalid_Category_Id'] = "Invalid category id specified. It may have been deleted.";
$lang['Invalid_PM_Id'] = "Invalid PM id specified. Please go back and try again.";
$lang['Invalid_User_Id'] = "Invalid User id specified. Please go back and try again.";
$lang['No_Post_Content'] = "You did not enter anything in the content box.";
$lang['No_x_content'] = "You did not enter a %s"; // %s = An item of content (E.G Title)
$lang['User_does_not_exist'] = "Sorry, that user does not exist";
$lang['Title_Too_Long'] = "The title you entered must be less than 75 characters long.";
$lang['Unable_to_select_template'] = "Unable to select template";
$lang['post_has_too_many_chars'] = "Your post is too long. Maximum size of your post : 2000 characters.";
$lang['Chars_left'] = "Characters left : ";

// Permission Errors
$lang['Invalid_Permissions_Mod'] = "You do not have permission to moderate this topic";
$lang['Invalid_Permissions_Read'] = "Sorry, you do not have permission to read in this topic";
$lang['Invalid_Permissions_Post'] = "Sorry, you do not have permission to post in this forum";
$lang['Invalid_Permissions_Reply'] = "Sorry, you do not have permission to post a reply in this forum";
$lang['User_Edit_Msg'] = "You do not have permission to edit this post";


// ------------
// Confirm Msg
// ------------
$lang['Yes'] = "Yes";
$lang['No'] = "No";
?>
