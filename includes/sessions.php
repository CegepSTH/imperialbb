<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: sessions.php                                               # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

// Clear sessions with an excess of 15 mins inactivity
$time = time() - 900;
$sql = $db->query("SELECT * FROM `".$db_prefix."sessions` WHERE `time` < $time");
while($result = $db->fetch_array($sql)) {
        $db->query("UPDATE `".$db_prefix."users` SET `user_lastvisit` = '" . $result['time'] . "' WHERE `user_id` = '" . $result['user_id'] . "'");
        $db->query("DELETE FROM `".$db_prefix."sessions` WHERE `session_id` = '" . $result['session_id'] . "'");
}


// Begin the main check
$session_sql = $db->query("SELECT count(f.`ip`) AS 'count', s.*, u.`username`, u.`user_password`
							FROM ((`".$db_prefix."sessions` f
							LEFT JOIN `".$db_prefix."sessions` s ON s.`ip` = '" . $_SERVER['REMOTE_ADDR'] . "' AND s.`session_id` = '".session_id()."')
							LEFT JOIN `".$db_prefix."users` u ON  u.`user_id` = s.`user_id`)
                            WHERE f.`ip` = '" . $_SERVER['REMOTE_ADDR'] . "'
                            GROUP BY f.`ip`");

$session = $db->fetch_array($session_sql);
if($session['count'] > 1) {
   // Just encase drop ALL sessions from this user and create a new session id..
   $db->query("DELETE FROM `".$db_prefix."sessions` WHERE `ip` =  '" . $_SERVER['REMOTE_ADDR'] . "'");
   session_regenerate_id();
   if(isset($_COOKIE['UserName']) && isset($_COOKIE['Password'])) {
      $check_cookies_sql = $db->query("SELECT * FROM `".$db_prefix."users`
                                        WHERE `username` = '".$_COOKIE['UserName']."'
                                        AND `password` = '".$_COOKIE['Password']."'");
      if($check_user = $db->fetch_array($check_cookies_sql)) {
         $_SESSION['user_id'] = $check_user['user_id'];
      } else {
         $_SESSION['user_id'] = -1;
      }
   } else {
         $_SESSION['user_id'] = -1;
   }
   $db->query("INSERT INTO `".$db_prefix."sessions` VALUES('".session_id()."',
                                            '".$_SERVER['REMOTE_ADDR']."',
                                            '".$_SESSION['user_id']."',
                                            '".time()."',
                                            '".time()."')");
} else if($session['count'] == 1) {
  if($session['session_id'] == session_id()) {
          if($session['user_id'] == $_SESSION['user_id']) {
                  $db->query("UPDATE `".$db_prefix."sessions` SET `time` = '".time()."' WHERE `ip` = '".$_SERVER['REMOTE_ADDR']."' AND `session_id` = '".session_id()."'");
                  setcookie("UserName", $session['username'], time()+604800);
		          setcookie("Password", $session['user_password'], time()+604800);
          } else {
                  // If the user id is wrong just log them straight out!
                  $db->query("DELETE FROM `".$db_prefix."sessions` WHERE `session_id` = '".session_id()."' AND `ip` = '".$_SERVER['REMOTE_ADDR']."'");
                  $_SESSION['user_id'] = -1;
                  session_regenerate_id();
                  $db->query("INSERT INTO `".$db_prefix."sessions` VALUES('".session_id()."',
                                                          '".$_SERVER['REMOTE_ADDR']."',
                                                          '".$_SESSION['user_id']."',
                                                          '".time()."',
                                                          '".time()."')");
                  setcookie("UserName", "");
		          setcookie("Password", "");
          }
          unset($session);
  } else {
    // The user is trying to use a session thats not right? Maybe they opened a new browser... or maybe there a hacker ... we will never know
    // Get it reset anyway!
    $db->query("DELETE FROM `".$db_prefix."sessions` WHERE `ip` =  '" . $_SERVER['REMOTE_ADDR'] . "'");
    session_regenerate_id();
    if(isset($_COOKIE['UserName']) && isset($_COOKIE['Password'])) {
       $check_cookies_sql = $db->query("SELECT `user_id` FROM `".$db_prefix."users`
                                         WHERE `username` = '".$_COOKIE['UserName']."'
                                         AND `user_password` = '".$_COOKIE['Password']."'");
       if($check_user = $db->fetch_array($check_cookies_sql)) {
          $_SESSION['user_id'] = $check_user['user_id'];
       } else {
          $_SESSION['user_id'] = -1;
       }
    } else {
      $_SESSION['user_id'] = -1;
    }
    $db->query("INSERT INTO `".$db_prefix."sessions` VALUES('".session_id()."',
                                             '".$_SERVER['REMOTE_ADDR']."',
                                             '".$_SESSION['user_id']."',
                                             '".time()."',
                                             '".time()."')");
  }
} else if($session['count'] == 0) {
     // Get the session created! - change session_id
     session_regenerate_id();
        if(isset($_COOKIE['UserName']) && isset($_COOKIE['Password'])) {
           $check_cookies_sql = $db->query("SELECT `user_id` FROM `".$db_prefix."users`
                                             WHERE `username` = '".$_COOKIE['UserName']."'
                                             AND `user_password` = '".$_COOKIE['Password']."'");
           if($check_user = $db->fetch_array($check_cookies_sql)) {
              $_SESSION['user_id'] = $check_user['user_id'];
           } else {
              $_SESSION['user_id'] = -1;
           }
        } else {
              $_SESSION['user_id'] = -1;
        }
        $db->query("INSERT INTO `".$db_prefix."sessions` VALUES('".session_id()."',
                                                 '".$_SERVER['REMOTE_ADDR']."',
                                                 '".$_SESSION['user_id']."',
                                                 '".time()."',
                                                 '".time()."')");
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
