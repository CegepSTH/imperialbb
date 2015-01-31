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
$expiry_time = time() - 900;
$sql = $db2->query("SELECT * FROM `_PREFIX_sessions`
	WHERE `time` < :expiry_time",
	array(
		':expiry_time' => $expiry_time
	)
);
while($result = $sql->fetch()) {
        $db2->query("UPDATE `_PREFIX_users`
			SET `user_lastvisit` = :time
			WHERE `user_id` = :user_id",
			array(
				':time' => $result['time'],
				':user_id' => $result['user_id']
			)
		);

        $db2->query("DELETE FROM `_PREFIX_sessions`
			WHERE `session_id` = :session_id",
			array(
				':session_id' => $result['session_id']
			)
		);
}


// Begin the main check
$session_sql = $db2->query("SELECT count(f.`ip`) AS 'count', s.*, u.`username`, u.`user_password`
	FROM ((`_PREFIX_sessions` f
	LEFT JOIN `_PREFIX_sessions` s ON s.`ip` = :remote_ip1 AND s.`session_id` = :session_id)
	LEFT JOIN `_PREFIX_users` u ON  u.`user_id` = s.`user_id`)
    WHERE f.`ip` = :remote_ip2
    GROUP BY f.`ip`",
	array(
		':remote_ip1' => $_SERVER['REMOTE_ADDR'],
		':remote_ip2' => $_SERVER['REMOTE_ADDR'],
		':session_id' => session_id()
	)
);

$session = $session_sql->fetch();
if($session['count'] > 1) {
   // Just encase drop ALL sessions from this user and create a new session id..
   $db2->query("DELETE FROM `_PREFIX_sessions` WHERE `ip` = :remote_ip",
		array(
			':remote_ip' => $_SERVER['REMOTE_ADDR']
		)
	);
   session_regenerate_id();
   if(isset($_COOKIE['UserName']) ) { 
      $check_cookies_sql = $db2->query("SELECT * FROM `_PREFIX_users`
         WHERE `username` = :username",
         //AND `user_password` = :password",
         array(
            ':username' => $_COOKIE['UserName']
            //':password' => $_COOKIE['Password']
         )
      );
      if($check_user = $check_cookies_sql->fetch()) {
         $_SESSION['user_id'] = $check_user['user_id'];
      } else {
         $_SESSION['user_id'] = -1;
      }
   } else {
         $_SESSION['user_id'] = -1;
   }
   $db2->query("INSERT INTO `_PREFIX_sessions`
      VALUES(:session_id,
      :remote_ip,
      :user_id,
      :current_time1,
      :current_time2)",
      array(
         ':session_id' => session_id(),
         ':remote_ip' => $_SERVER['REMOTE_ADDR'],
         ':user_id' => $_SESSION['user_id'],
         ':current_time1' => time(),
         ':current_time2' => time()
      )
   );
} else if($session['count'] == 1) {
  if($session['session_id'] == session_id()) {
          if($session['user_id'] == $_SESSION['user_id']) {
                  $db2->query("UPDATE `_PREFIX_sessions`
                      SET `time` = '".time()."'
                      WHERE `ip` = :remote_ip AND `session_id` = :session_id",
                      array(
                          ':remote_ip' => $_SERVER['REMOTE_ADDR'],
                          ':session_id' => session_id()
                      )
                  );
                  setcookie("UserName", $session['username'], time()+604800);
		          setcookie("Password", $session['user_password'], time()+604800);
          } else {
                  // If the user id is wrong just log them straight out!
                  $db2->query("DELETE FROM `_PREFIX_sessions`
                      WHERE `session_id` = :session_id AND `ip` = :remote_ip",
                      array(
                          ':remote_ip' => $_SERVER['REMOTE_ADDR'],
                          ':session_id' => session_id()
                      )
                  );
                  $_SESSION['user_id'] = -1;
                  session_regenerate_id();
                  $db2->query("INSERT INTO `_PREFIX_sessions`
                     VALUES(:session_id,
                     :remote_ip,
                     :user_id,
                     :current_time1,
                     :current_time2)",
                     array(
                        ':session_id' => session_id(),
                        ':remote_ip' => $_SERVER['REMOTE_ADDR'],
                        ':user_id' => $_SESSION['user_id'],
                        ':current_time1' => time(),
                        ':current_time2' => time()
                     )
                  );
                  setcookie("UserName", "");
		          setcookie("Password", "");
          }
          unset($session);
  } else {
    // The user is trying to use a session thats not right? Maybe they opened a new browser... or maybe there a hacker ... we will never know
    // Get it reset anyway!
    $db2->query("DELETE FROM `_PREFIX_sessions`
        WHERE `ip` = :remote_ip",
        array(
            ':remote_ip' => $_SERVER['REMOTE_ADDR']
        )
    );
    session_regenerate_id();
    if(isset($_COOKIE['UserName'])) {
       $check_cookies_sql = $db2->query("SELECT `user_id` FROM `_PREFIX_users`
          WHERE `username` = :username",
          array(
             ':username' => $_COOKIE['UserName']) );
             
       if($check_user = $check_cookies_sql->fetch()) {
          $_SESSION['user_id'] = $check_user['user_id'];
       } else {
          $_SESSION['user_id'] = -1;
       }
    } else {
      $_SESSION['user_id'] = -1;
    }
    $db2->query("INSERT INTO `_PREFIX_sessions`
        VALUES(:session_id,
        :remote_ip,
        :user_id,
        :current_time1,
        :current_time2)",
        array(
            ':session_id' => session_id(),
            ':remote_ip' => $_SERVER['REMOTE_ADDR'],
            ':user_id' => $_SESSION['user_id'],
            ':current_time1' => time(),
            ':current_time2' => time()
        )
    );
  }
} else if($session['count'] == 0) {
     // Get the session created! - change session_id
     session_regenerate_id();
        if(isset($_COOKIE['UserName']) ) { 
           $check_cookies_sql = $db2->query("SELECT `user_id` FROM `".$db_prefix."users`
              WHERE `username` = :username",
              array(
                 ':username' => $_COOKIE['UserName']));
                 
           if($check_user = $check_cookies_sql->fetch()) {
              $_SESSION['user_id'] = $check_user['user_id'];
           } else {
              $_SESSION['user_id'] = -1;
           }
        } else {
              $_SESSION['user_id'] = -1;
        }
        $db2->query("INSERT INTO `_PREFIX_sessions`
            VALUES(:session_id,
            :remote_ip,
            :user_id,
            :current_time1,
            :current_time2)",
            array(
                ':session_id' => session_id(),
                ':remote_ip' => $_SERVER['REMOTE_ADDR'],
                ':user_id' => $_SESSION['user_id'],
                ':current_time1' => time(),
                ':current_time2' => time()
            )
        );
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
