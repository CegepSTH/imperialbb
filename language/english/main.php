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

// ------------------------
// General
// ------------------------
$lang['Welcome'] = "Welcome";
$lang['Welcome_Guest'] = "Welcome Guest";
$lang['Forum'] = "Forum";
$lang['Topics'] = "Topics";
$lang['Posts'] = "Posts";
$lang['Post'] = "Post";
$lang['Replies'] = "Replies";
$lang['Reply'] = "Reply";
$lang['Views'] = "Views";
$lang['Topic_Name'] = "Topic Name";
$lang['Last_Post'] = "Last Post";
$lang['New_Topic'] = "New Topic";
$lang['Fast_Reply'] = "Fast Reply";
$lang['Author'] = "Author";
$lang['Message'] = "Message";
$lang['None'] = "None";
$lang['Title'] = "Title";
$lang['Body'] = "Body";
$lang['Menu'] = "Menu";
$lang['The_following_errors_occoured'] = "The following errors occoured";
$lang['Click_here_if_you_do_not_want_to_wait'] = "Click here if you do not want to wait";
$lang['Email'] = "Email";
$lang['Action'] = "Action";
$lang['Status'] = "Status";
$lang['Online'] = "Online";
$lang['Offline'] = "Offline";
$lang['Invalid_Action'] = "Sorry, invalid action specified. Please go back and try again.";
$lang['Banned_Msg'] = "Error you have been banned from these forums!<br />Please email <a href=\"mailto:%s\">%s</a> if you think you have recieved this message in error."; // %s = Email link
$lang['Board_Offline'] = "Board Offline";
$lang['Administration_Panel'] = "AdminCP";
$lang['enabled'] = "enabled";
$lang['disabled'] = "disabled";
$lang['Thanks'] = "Thanks";
$lang['expand'] = "Expand";
$lang['collapse'] = "Collapse";

// ------------------------
// Pagination Language Vars
// ------------------------
$lang['paginate_pages']    = "Page %s of %s";
$lang['paginate_lastpage'] = "Last Page";
$lang['paginate_nextpage'] = "Next Page";
$lang['paginate_prevpage'] = "Previous Page";
$lang['paginate_frstpage'] = "First Page";
$lang['paginate_currpage'] = "Current Page";
$lang['paginate_activepg'] = "Page#";

//
// Button Values
//
$lang['Ok'] = "Ok";
$lang['Cancel'] = "Cancel";
$lang['Submit'] = "Submit";
$lang['Disabled'] = "Disabled";
$lang['Reset'] = "Reset";
$lang['True'] = "True";
$lang['False'] = "False";
$lang['Delete'] = "Delete";
$lang['Edit'] = "Edit";
$lang['Prev'] = "Prev";
$lang['Next'] = "Next";
$lang['at'] = "at";
$lang['NA'] = "N/A";
$lang['sqltime'] = "SQL Processing Time: %s (%s SQL)"; //%s No of SQL seconds //%s Percentage SQL
$lang['phptime'] = "PHP Processing Time: %s (%s PHP)"; //%s No of PHP seconds //%s Percentage PHP
$lang['Page_Generated_In_X_Seconds'] = "Page Generation : %s seconds"; // %s = No of seconds
$lang['Query_Count'] = "SQL Queries : %s"; // %s = No of queries
$lang['Quote'] = "Quote: ";

// IM's
$lang['AIM'] = "AIM";
$lang['ICQ'] = "ICQ";
$lang['MSN'] = "MSN";
$lang['Yahoo'] = "Yahoo";

// User Levels
$lang['Banned'] = "Banned";
$lang['Guest'] = "Guest";
$lang['Validating'] = "Validating";
$lang['Registered'] = "Registered";
$lang['Moderator'] = "Moderator";
$lang['Admin'] = "Admin";

// Topic Types
$lang['Announcment'] = "Announcment";
$lang['Pinned'] = "Pinned";
$lang['General'] = "General";
$lang['Poll'] = "Poll";
$lang['New_Posts'] = "New Posts";
$lang['No_New_Posts'] = "No New Posts";

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

// -----------
// Menu Items
// -----------
$lang['Home'] = "Home";
$lang['PM'] = "PM";

// --------------
// User Related
// --------------
$lang['ID'] = "ID";
$lang['Login'] = "Login";
$lang['Logout'] = "Logout";
$lang['Register'] = "Register";
$lang['Username'] = "Username";
$lang['Password'] = "Password";
$lang['Rank'] = "Rank";
$lang['Send_Email'] = "Send Email";
$lang['Communication'] = "Communication";
$lang['Date_Joined'] = "Date Joined";


// ---------------------------
// User / Moderator functions
// ---------------------------
$lang['Delete_Topic'] = "Delete Topic";
$lang['Delete_Post'] = "Delete Post";
$lang['Move_Topic'] = "Move Topic";
$lang['Lock_Topic'] = "Lock Topic";
$lang['Unlock_Topic'] = "Unlock Topic";
$lang['Edit_Post'] = "Edit Post";


// ---------------------------
// Profile
// ---------------------------
$lang['User_CP'] = "User CP";
$lang['Retype'] = "Retype";
$lang['View_Profile'] = "View Profile";
$lang['Edit_Profile'] = "Edit Profile";
$lang['Email_Address'] = "Email Address";
$lang['Retype_Email_Address'] = "Retype Email Address";
$lang['Website'] = "Website";
$lang['Location'] = "Location";

// ---------------------------
// Members List
// ---------------------------
$lang['Members'] = "Members";
$lang['Members_List'] = "Members List";

// PORTAL
$lang['Portal'] = "Portal";

// ---------------------------
// Search
// ---------------------------
$lang['Search'] = "Search";

// ---------------------------
// Forum Statistics
// ---------------------------
$lang['stats_newest_member'] = "Please welcome our newest member, %s";
$lang['stats_total_members'] = "We currently have %s members registered.";
$lang['stats_total_poststopics'] = "Our members have made a total of %s posts in %s topics.";
$lang['stats_online_today'] = "There were %s Member(s) online today.";
$lang['stats_onlinetoday'] = "Online Today";
$lang['stats_boardstats'] = "Board Statistics";
$lang['stats_forumstats'] = "Forum Statistics";
$lang['legend_new_posts'] = "Forum Contains New Posts";
$lang['legend_nonew_posts'] = "Forum Contains No New Posts";
$lang['Whos_Online'] = "Who's Online";
$lang['Total_Users_Online'] = "Total Users Online";
$lang['Users_Online'] = "Users Online";
$lang['Guests_Online'] = "Guests Online";
$lang['profile_birthday'] = "Birthday";
$lang['stats_birthdays'] = "Member Birthdays";
$lang['birthday_month'] = "Month";
$lang['birthday_day'] = "Day";
$lang['birthday_year'] = "Year";
$lang['profile_months'] = "January-Feburary-March-April-May-June-July-August-September-October-November-December";

// ------------------------
// Time / Date / Timezones
// ------------------------
$lang['Date'] = "Date";
$lang['All_Times_Are_TZ'] = "All Times Are %s"; // %s = the users timezone
$lang['tz']['-12'] = "GMT - 12 Hours";
$lang['tz']['-11'] = "GMT - 11 Hours";
$lang['tz']['-10'] = "GMT - 10 Hours";
$lang['tz']['-9'] = "GMT - 9 Hours";
$lang['tz']['-8'] = "GMT - 8 Hours";
$lang['tz']['-7'] = "GMT - 7 Hours";
$lang['tz']['-6'] = "GMT - 6 Hours";
$lang['tz']['-5'] = "GMT - 5 Hours";
$lang['tz']['-4'] = "GMT - 4 Hours";
$lang['tz']['-3.5'] = "GMT - 3.5 Hours";
$lang['tz']['-3'] = "GMT - 3 Hours";
$lang['tz']['-2'] = "GMT - 2 Hours";
$lang['tz']['-1'] = "GMT - 1 Hours";
$lang['tz']['0'] = "GMT";
$lang['tz']['1'] = "GMT + 1 Hour";
$lang['tz']['2'] = "GMT + 2 Hours";
$lang['tz']['3'] = "GMT + 3 Hours";
$lang['tz']['3.5'] = "GMT + 3.5 Hours";
$lang['tz']['4'] = "GMT + 4 Hours";
$lang['tz']['4.5'] = "GMT + 4.5 Hours";
$lang['tz']['5'] = "GMT + 5 Hours";
$lang['tz']['5.5'] = "GMT + 5.5 Hours";
$lang['tz']['6'] = "GMT + 6 Hours";
$lang['tz']['6.5'] = "GMT + 6.5 Hours";
$lang['tz']['7'] = "GMT + 7 Hours";
$lang['tz']['8'] = "GMT + 8 Hours";
$lang['tz']['9'] = "GMT + 9 Hours";
$lang['tz']['9.5'] = "GMT + 9.5 Hours";
$lang['tz']['10'] = "GMT + 10 Hours";
$lang['tz']['11'] = "GMT + 11 Hours";
$lang['tz']['12'] = "GMT + 12 Hours";
$lang['tz']['13'] = "GMT + 13 Hours";
?>
