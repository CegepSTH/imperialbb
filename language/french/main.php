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
$lang['head_htmlang'] = "fr";

// ------------------------
// General
// ------------------------
$lang['Welcome'] = "Bienvenue";
$lang['Welcome_Guest'] = "Bienvenue visiteur";
$lang['Forum'] = "Forum";
$lang['Topics'] = "Sujet";
$lang['Posts'] = "Publications";
$lang['Post'] = "Publication";
$lang['Replies'] = "Répliques";
$lang['Reply'] = "Répliquer";
$lang['Views'] = "vues";
$lang['Topic_Name'] = "Nom du sujet";
$lang['Last_Post'] = "Dernier post";
$lang['New_Topic'] = "Nouveau sujet";
$lang['Fast_Reply'] = "Répliquation rapide";
$lang['Author'] = "auteur";
$lang['Message'] = "Message";
$lang['None'] = "Aucun";
$lang['Title'] = "Titre";
$lang['Body'] = "Corps";
$lang['Menu'] = "Menu";
$lang['The_following_errors_occoured'] = "Erreur : ";
$lang['Click_here_if_you_do_not_want_to_wait'] = "Cliquez ici si vous ne souhaitez pas attendre";
$lang['Email'] = "Email";
$lang['Action'] = "Action";
$lang['Status'] = "Status";
$lang['Online'] = "En ligne";
$lang['Offline'] = "hors ligne";
$lang['Invalid_Action'] = "Action non valide, s'il vous plait retournez à l'arrière et essayer de nouveau.";
$lang['Banned_Msg'] = "Vous avez été banni de ce forum<br />Vous pouvez envoyer un courriel au <a href=\"mailto:%s\">%s</a> si vous pensez avoir recu ce message par erreur."; // %s = Email link
$lang['Board_Offline'] = "Board hors-ligne";
$lang['Administration_Panel'] = "AdminCP";
$lang['enabled'] = "activer";
$lang['disabled'] = "désactiver";
$lang['Thanks'] = "Merci";
$lang['expand'] = "Étendre";
$lang['collapse'] = "Écrouller";

// ------------------------
// Pagination Language Vars
// ------------------------
$lang['paginate_pages']    = "Page %s de %s";
$lang['paginate_lastpage'] = "Dernière page";
$lang['paginate_nextpage'] = "Page suivante";
$lang['paginate_prevpage'] = "Page précédente";
$lang['paginate_frstpage'] = "Première page";
$lang['paginate_currpage'] = "Page actuelle";
$lang['paginate_activepg'] = "Page#";

//
// Button Values
//
$lang['Ok'] = "Ok";
$lang['Cancel'] = "Annuler";
$lang['Submit'] = "Soumettre";
$lang['Reset'] = "Réinitialiser";
$lang['True'] = "Vrai";
$lang['False'] = "Faux";
$lang['Delete'] = "Supprimer";
$lang['Edit'] = "Éditer";
$lang['Prev'] = "Précédent";
$lang['Next'] = "Suivant";
$lang['at'] = "à";
$lang['NA'] = "N/A";
$lang['sqltime'] = "Temps du processus SQL : %s (%s SQL)"; //%s No of SQL seconds //%s Percentage SQL
$lang['phptime'] = "Temps du processus PHP : %s (%s PHP)"; //%s No of PHP seconds //%s Percentage PHP
$lang['Page_Generated_In_X_Seconds'] = "Génération de la page faite en : %s secondes"; // %s = No of seconds
$lang['Query_Count'] = "Requêtes SQL : %s"; // %s = No of queries

// IM's
$lang['AIM'] = "AIM";
$lang['ICQ'] = "ICQ";
$lang['MSN'] = "MSN";
$lang['Yahoo'] = "Yahoo";

// User Levels
$lang['Banned'] = "Banni";
$lang['Guest'] = "Vsiteur";
$lang['Validating'] = "Valider";
$lang['Registered'] = "Inscrit";
$lang['Moderator'] = "Moderateur";
$lang['Admin'] = "Admin";

// Topic Types
$lang['Announcment'] = "Annonce";
$lang['Pinned'] = "Épinglé";
$lang['General'] = "Général";
$lang['Poll'] = "Sondage";
$lang['New_Posts'] = "Nouvelles publications";
$lang['No_New_Posts'] = "Aucune nouvelles publications";

// Errors
$lang['Error'] = "Erreur";
$lang['Critical_Error'] = "Erreur critique";

// Some General Errors
$lang['Unable_To_Send_Email'] = "Impossible d'envoyer le courriel, contactez un administrateur à propos de cette erreur";
$lang['Invalid_Post_Id'] = "L'id de la publication est invalide. La publication a peut-être été supprimée.";
$lang['Invalid_Topic_Id'] = "L'id du sujet est invalide. Le sujet a peut-être été supprimé.";
$lang['Invalid_Forum_Id'] = "L'id du forum est invalide. Le forum a peut-être été supprimé.";
$lang['Invalid_Category_Id'] = "L'id de la catégorie est ivalide. La catégorie a peut-être été supprimé.";
$lang['Invalid_PM_Id'] = "L'id du message privé est invalide. Retournez à l'arrière et ressayer de nouveau";
$lang['Invalid_User_Id'] = "L'iid de l'utilisateur est invalide. Retournez à l'arrière et ressayer de nouveau.";
$lang['No_Post_Content'] = "Vous n'avez entré aucune donné dans la boite de champ.";
$lang['No_x_content'] = "Vous n'avez rien entré comme %s"; // %s = An item of content (E.G Title)
$lang['User_does_not_exist'] = "Cette utilisateur n'existe pas";
$lang['Title_Too_Long'] = "Le titre doit faire moins que 75 caractères de long.";
$lang['Unable_to_select_template'] = "Le modèle n'est pas sélectionnable";

// Permission Errors
$lang['Invalid_Permissions_Mod'] = "Vous n'avez pas la permission de modérer ce sujet";
$lang['Invalid_Permissions_Read'] = "Vous n'vez pas la permission de lire ce sujet";
$lang['Invalid_Permissions_Post'] = "Vous n'avez pas la permission de  publier dans ce forum";
$lang['Invalid_Permissions_Reply'] = "Vous n'avez pas la permission de publier une réplique dans ce forum";
$lang['User_Edit_Msg'] = "Vous n'avez pas la permission d'éditer cette publication";


// ------------
// Confirm Msg
// ------------
$lang['Yes'] = "Oui";
$lang['No'] = "Non";

// -----------
// Menu Items
// -----------
$lang['Home'] = "Accueil";
$lang['PM'] = "MP";

// --------------
// User Related
// --------------
$lang['ID'] = "ID";
$lang['Login'] = "S'identifier";
$lang['Logout'] = "Se déconnecter";
$lang['Register'] = "S'enregistrer";
$lang['Username'] = "Nom d'utilisateur";
$lang['Password'] = "Mot de passe";
$lang['Rank'] = "Rang";
$lang['Send_Email'] = "Envoyer un courriel";
$lang['Communication'] = "Communication";
$lang['Date_Joined'] = "Date d'inscription";


// ---------------------------
// User / Moderator functions
// ---------------------------
$lang['Delete_Topic'] = "Supprimer le sujet";
$lang['Delete_Post'] = "Supprimer la publication";
$lang['Move_Topic'] = "Déménager le sujet";
$lang['Lock_Topic'] = "Verrouiller le sujet";
$lang['Unlock_Topic'] = "déverrouiller le sujet";
$lang['Edit_Post'] = "Éditer la publication";


// ---------------------------
// Profile
// ---------------------------
$lang['User_CP'] = "CP de l'utilisateur";
$lang['Retype'] = "Retaper";
$lang['View_Profile'] = "Vor le Profil";
$lang['Edit_Profile'] = "Éditer le Profil";
$lang['Email_Address'] = "Adresse courrielle";
$lang['Retype_Email_Address'] = "Réinscrire l'adresse courrielle";
$lang['Website'] = "Site web";
$lang['Location'] = "Location";

// ---------------------------
// Members List
// ---------------------------
$lang['Members'] = "Membres";
$lang['Members_List'] = "Liste des membres";

// ---------------------------
// Search
// ---------------------------
$lang['Search'] = "Chercher";

// ---------------------------
// Forum Statistics
// ---------------------------
$lang['stats_newest_member'] = "Veuillez accueillir notre nouveau membre : , %s";
$lang['stats_total_members'] = "Présentement %s membres sont enregistrés sur le forum.";
$lang['stats_total_poststopics'] = "Nos membres ont fait un total de %s publications dans %s sujet de discussions.";
$lang['stats_online_today'] = "Il y a eu %s connnection de membres aujourd'hui.";
$lang['stats_onlinetoday'] = "En ligne aujourd'hui";
$lang['stats_boardstats'] = "Statistique du board";
$lang['stats_forumstats'] = "Statistique du forum";
$lang['legend_new_posts'] = "Le forum contient de nouvelles publications";
$lang['legend_nonew_posts'] = "Le forum n'a aucune nouvelle publications";
$lang['Whos_Online'] = "Liste des utilisateur en ligne";
$lang['Total_Users_Online'] = "Total d'utilisateurs en ligne";
$lang['Users_Online'] = "Utilisateur en ligne";
$lang['Guests_Online'] = "Visiteurs en ligne";
$lang['profile_birthday'] = "anniversaire";
$lang['stats_birthdays'] = "Anniversaire des membre";
$lang['birthday_month'] = "Mois";
$lang['birthday_day'] = "Jour";
$lang['birthday_year'] = "Année";
$lang['profile_months'] = "Janvier-Février-Mars-Avril-Mai-Juin-Juillet-Août-Septembre-Novembre-Décembre";

// ------------------------
// Time / Date / Timezones
// ------------------------
$lang['Date'] = "Date";
$lang['All_Times_Are_TZ'] = "Toutes les date sont au format %s"; // %s = the users timezone
$lang['tz']['-12'] = "GMT - 12 heures";
$lang['tz']['-11'] = "GMT - 11 heures";
$lang['tz']['-10'] = "GMT - 10 heures";
$lang['tz']['-9'] = "GMT - 9 heures";
$lang['tz']['-8'] = "GMT - 8 heures";
$lang['tz']['-7'] = "GMT - 7 heures";
$lang['tz']['-6'] = "GMT - 6 heures";
$lang['tz']['-5'] = "GMT - 5 heures";
$lang['tz']['-4'] = "GMT - 4 heures";
$lang['tz']['-3.5'] = "GMT - 3.5 heures";
$lang['tz']['-3'] = "GMT - 3 heures";
$lang['tz']['-2'] = "GMT - 2 heures";
$lang['tz']['-1'] = "GMT - 1 heures";
$lang['tz']['0'] = "GMT";
$lang['tz']['1'] = "GMT + 1 heures";
$lang['tz']['2'] = "GMT + 2 heures";
$lang['tz']['3'] = "GMT + 3 heures";
$lang['tz']['3.5'] = "GMT + 3.5 heures";
$lang['tz']['4'] = "GMT + 4 heures";
$lang['tz']['4.5'] = "GMT + 4.5 heures";
$lang['tz']['5'] = "GMT + 5 heures";
$lang['tz']['5.5'] = "GMT + 5.5 heures";
$lang['tz']['6'] = "GMT + 6 heures";
$lang['tz']['6.5'] = "GMT + 6.5 heures";
$lang['tz']['7'] = "GMT + 7 heures";
$lang['tz']['8'] = "GMT + 8 heures";
$lang['tz']['9'] = "GMT + 9 heures";
$lang['tz']['9.5'] = "GMT + 9.5 heures";
$lang['tz']['10'] = "GMT + 10 heures";
$lang['tz']['11'] = "GMT + 11 heures";
$lang['tz']['12'] = "GMT + 12 heures";
$lang['tz']['13'] = "GMT + 13 heures";
?>
