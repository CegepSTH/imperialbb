<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: pm.php                                                     # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
include($root_path . "includes/common.php");

$language->add_file("pm");
$language->add_file("view_topic");

if($user['user_id'] <= 0)
{
	info_box($lang['Error'], $lang['Must_Be_Logged_In_msg'], "login.php");
}
if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "send")
{	$language->add_file("posting");
	if(isset($_POST['Submit'])) {
		CSRF::validate();

		$error = "";
		if(strlen($_POST['username']) < 1 )
		{
			$error .= sprintf($lang['No_x_content'], strtolower($lang['Username'])) ."<br />";
		}
		if(strlen($_POST['title']) < 1 )
		{
			$error .= sprintf($lang['No_x_content'], strtolower($lang['Title'])) ."<br />";
		}
		if(strlen($_POST['body']) < 1)
		{
			$error .= $lang['No_Post_Content'] . "<br />";
		}
		if(!isset($_POST['action']) || strlen($_POST['action']) < 1) {
			$error .= $lang['Select_An_Action_PM'] . "<br />";
		}
		if(strlen($error) > 0)
		{
			$theme->new_file("send_pm", "send_pm.tpl", "");
			$theme->replace_tags("send_pm", array(
				"ACTION" => $lang['Send_PM'],
				"USERNAME" => $_POST['username'],
				"PM_SELECTED" => (!isset($_POST['action']) || $_POST['action'] == "pm" || $_POST['action'] == "") ? "CHECKED" : "",
				"EMAIL_SELECTED" => (isset($_POST['action']) && $_POST['action'] == "email") ? "CHECKED" : "",
				"TITLE" => $_POST['title'],
				"BODY" => $_POST['body'],
				"CSRF_TOKEN" => CSRF::getHTML()
			));

			$theme->insert_nest("send_pm", "username");
			$theme->add_nest("send_pm", "username");

			if($config['bbcode_enabled'] == true)
			{
				// Add the BBCode chooser to the page
				$theme->insert_nest("send_pm", "bbcode");
				$theme->add_nest("send_pm", "bbcode");
			}
			if($config['smilies_enabled'] == true)
			{

				// Add the emoticon chooser to the page
				$theme->insert_nest("send_pm", "smilies");
		 		$smilie_query = $db2->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `_PREFIX_smilies`");
		 		$smilie_no = 1;
				$smilie_count = 1;
				$smilie_url = array();
				while($smilie = $smilie_query->fetch())
				{
					// Check if the smilie has already been displayed
 	      			if(!in_array($emotion['smilie_url'], $smilie_url))
 	     	 		{
 	      			// Add smilie to the array
 	      				$smilie_url[] = $smilie['smilie_url'];

 	      				if($smilie_no == 1)
 	      				{
 	      					$theme->insert_nest("send_pm", "smilies/emoticon_row");
 	      				}

 	      				$theme->insert_nest("send_pm", "smilies/emoticon_row/emoticon_cell", array(
 	      					"EMOTICON_CODE" => $smilie['smilie_code'],
 	      					"EMOTICON_URL" => $smilie['smilie_url'],
 	      					"EMOTICON_TITLE" => $smilie['smilie_name']
 	      				));
 	      				$theme->add_nest("send_pm", "smilies/emoticon_row/emoticon_cell");
 	      				if($smilie_no >= 5)
 	      				{
							$theme->add_nest("send_pm", "smilies/emoticon_row");
 	      					$smilie_no = 1;
 	      				}
 	      				else
 	      				{
 	      					$smilie_no++;
 	      				}
 	      				$smilie_count++;
 	      				if($smilie_count > 20)
 	      				{
 	      					break;
	       				}
	       			}
				}
				$theme->add_nest("send_pm", "smilies");
			}

			$theme->insert_nest("send_pm", "error", array(
				"ERRORS" => $error
			));
			$theme->add_nest("send_pm", "error");

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("send_pm");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		}
		else
		{
			$sql = $db2->query("SELECT *
				FROM `_PREFIX_users`
				WHERE `username` = :username",
				array(
					":username" => $_POST['username']
				)
			);
			if($result = $sql->fetch())
			{
				if($_POST['action'] == "pm")
				{
					$db2->query("INSERT INTO `".$db_prefix."pm`
						VALUES (
						'',
						:title,
						:body,
						:receiver,
						:sender,
						'1',
						'1',
						:pm_time
						)",
						array(
							":title" => $_POST['title'],
							":body" => $_POST['body'],
							":receiver" => $result['user_id'],
							":sender" => $user['user_id'],
							":pm_time" => time()
						)
					);
					$pm_id = $db2->lastInsertId();
					if($result['user_email_on_pm'] == "1") {
						email($lang['Email_PM_Recieved_Subject'], "pm_recieved", array(
							"USERNAME" => $result['username'],
							"SITE_NAME" => $config['site_name'],
							"DOMAIN" => $config['url'],
							"PM_ID" => $pm_id), $result['user_email']);
					}
					info_box($lang['PM'], "PM Sent", "pm.php");
				}
				else if($_POST['action'] == "email")
				{
					email($_POST['title'], "user_email", array(
						"SITE_NAME" => $config['site_name'],
						"AUTHOR_USERNAME" => $user['username'],
						"USERNAME" => $result['username'],
						"MESSAGE" => $_POST['body']), $result['user_email'], $user['user_email']);
					info_box($lang['Email'], $lang['Email_Sent'], "pm.php");
				}
				else
				{
					error_msg($lang['Error'], $lang['Invalid_Action']);
				}
			}
			else
			{
				error_msg($lang['Error'], $lang['User_does_not_exist']);
			}
		}
	}
	else
	{
		$theme->new_file("send_pm", "send_pm.tpl");
		$theme->replace_tags("send_pm", array(
			"ACTION" => $lang['Send_PM'],
			"USERNAME" => (isset($_GET['username'])) ? $_GET['username'] : "",
			"PM_SELECTED" => (isset($_GET['action']) && $_GET['action'] == "email") ? "" : "CHECKED",
			"EMAIL_SELECTED" => (isset($_GET['action']) && $_GET['action'] == "email") ? "CHECKED" : "",
			"TITLE" => "",
			"BODY" => "",
			"CSRF_TOKEN" => CSRF::getHTML()
		));

		$theme->insert_nest("send_pm", "username");
		$theme->add_nest("send_pm", "username");

		if($config['bbcode_enabled'] == true)
		{

			// Add the BBCode chooser to the page
			$theme->insert_nest("send_pm", "bbcode");
			$theme->add_nest("send_pm", "bbcode");
		}
		if($config['smilies_enabled'] == true)
		{

			// Add the emoticon chooser to the page
			$theme->insert_nest("send_pm", "smilies");
	 	    $smilie_query = $db2->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `_PREFIX_smilies`");
	 	    $smilie_no = 1;
			$smilie_count = 1;
			$smilie_url = array();
			while($smilie = $smilie_query->fetch())
			{
				// Check if the smilie has already been displayed
       			if(!in_array($smilie['smilie_url'], $smilie_url))
      	 		{
	       			// Add smilie to the array
       				$smilie_url[] = $smilie['smilie_url'];

       				if($smilie_no == 1)
       				{
       					$theme->insert_nest("send_pm", "smilies/emoticon_row");
       				}

       				$theme->insert_nest("send_pm", "smilies/emoticon_row/emoticon_cell", array(
       					"EMOTICON_CODE" => $smilie['smilie_code'],
       					"EMOTICON_URL" => $smilie['smilie_url'],
       					"EMOTICON_TITLE" => $smilie['smilie_name']
       				));
       				$theme->add_nest("send_pm", "smilies/emoticon_row/emoticon_cell");
       				if($smilie_no >= 5)
       				{
						$theme->add_nest("send_pm", "smilies/emoticon_row");
       					$smilie_no = 1;
       				}
       				else
       				{
       					$smilie_no++;
       				}
       				$smilie_count++;
       				if($smilie_count > 20)
       				{
       					break;
       				}
       			}
			}
			$theme->add_nest("send_pm", "smilies");
		}

		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("send_pm");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
}
else if($_GET['func'] == "delete")
{
	if(!isset($_GET['id'])) error_msg("Error", "Error no PM id specified!");
	$sql = $db2->query("SELECT *
		FROM `_PREFIX_pm`
		WHERE `pm_id` = :id
			AND (`pm_send_to` = :as_receiver || `pm_sent_from` = :as_sender)",
		array(
			":id" => $_GET['id'],
			":as_receiver" => $user['user_id'],
			":as_sender" => $user['user_id']
		)
	);
	if($result = $sql->fetch())
	{
		if($result['pm_type'] == "1")
		{
			if($result['pm_send_to'] == $user['user_id'] && $result['pm_sent_from'] == $user['user_id'])
			{
				$db2->query("DELETE FROM `_PREFIX_pm`
					WHERE `pm_id` = :pm_id",
					array(
						":pm_id" => $_GET['id']
					)
				);
				info_box($lang['PM_Manager'], $lang['PM_Deleted'], "pm.php");
			}
			else if($result['pm_send_to'] == $user['user_id'])
			{
				$db2->query("UPDATE `_PREFIX_pm`
					SET `pm_type` = '3'
					WHERE `pm_id` = :pm_id",
					array(
						":pm_id" => $_GET['id']
					)
				);
				info_box($lang['PM_Manager'], $lang['PM_Deleted'], "pm.php");
			}
			else if($result['pm_sent_from'] == $user['user_id'])
			{
				$db2->query("UPDATE `_PREFIX_pm`
					SET `pm_type` = '2'
					WHERE `pm_id` = :pm_id",
					array(
						":pm_id" => intval($_GET['id'])
					)
				);
				info_box($lang['PM_Manager'], $lang['PM_Deleted'], "pm.php");
			}
			else
			{
				error_msg($lang['Error'], $lang['Invalid_PM_Id']);
			}
		}
		else if($result['pm_type'] == "2")
		{
			if($result['pm_send_to'] == $user['user_id'])
			{
				$db2->query("DELETE FROM `_PREFIX_pm`
					WHERE `pm_id` = :pm_id",
					array(
						":pm_id" => $_GET['id']
					)
				);
				info_box($lang['PM_Manager'], $lang['PM_Deleted'], "pm.php");
			}
			else
			{
				error_msg($lang['Error'], $lang['Invalid_PM_Id']);
 			}
		}
		else if($result['pm_type'] == "3")
		{
			if($result['pm_sent_from'] == $user['user_id'])
			{
				$db2->query("DELETE FROM `_PREFIX_pm`
					WHERE `pm_id` = :pm_id",
					array(
						":pm_id" => $_GET['id']
					)
				);
				info_box($lang['PM_Manager'], $lang['PM_Deleted'], "pm.php");
			}
			else
			{
			error_msg($lang['Error'], $lang['Invalid_PM_Id']);
			}
		}
	}
	else
	{
		error_msg($lang['Error'], $lang['Invalid_PM_Id']);
	}

}
else if($_GET['func'] == "edit")
{
	$sql = $db2->query("SELECT *
		FROM `_PREFIX_pm`
		WHERE `pm_id` = :pm_id AND
			`pm_sent_from` = :user_id AND
			`pm_type` = '1'",
		array(
			":pm_id" => $_GET['id'],
			":user_id" => $user['user_id']
		)
	);
	if($result = $sql->fetch()) {
		$language->add_file("posting");

       	if(!isset($_GET['id'])) error_msg($lang['Error'], $lang['Invalid_PM_Id']);
       	if(isset($_POST['Submit'])) {
			CSRF::validate();

       		$error = "";
       		if(strlen($_POST['title']) < 1 )
       		{
       			$error .= sprintf($lang['No_x_content'], strtolower($lang['Title'])) . "<br />";
       		}
       		if(strlen($_POST['body']) < 1)
       		{
       			$error .= $lang['No_Post_Content'] . "<br />";
       		}
       		if(strlen($error) > 0)
       		{
       			$theme->new_file("edit_pm", "send_pm.tpl", "");
       			$theme->replace_tags("edit_pm", array(
       				"ACTION" => $lang['Edit_PM'],
       				"TITLE" => $_POST['title'],
       				"BODY" => $_POST['body'],
					"CSRF_TOKEN" => CSRF::getHTML()
       			));

       			if($config['bbcode_enabled'] == true)
       			{
       				// Add the BBCode chooser to the page
       				$theme->insert_nest("edit_pm", "bbcode");
       				$theme->add_nest("edit_pm", "bbcode");
       			}
       			if($config['smilies_enabled'] == true)
       			{

       				// Add the emoticon chooser to the page
       				$theme->insert_nest("edit_pm", "smilies");
       		 	    $smilie_query = $db2->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `_PREFIX_smilies`");
       		 	   	$smilie_no = 1;
       				$smilie_count = 1;
       				$smilie_url = array();
       				while($emotion = $smilie_query->fetch())
       				{
       					// Check if the smilie has already been displayed
       	      			if(!in_array($smilie['smilie_url'], $smilie_url))
       	     	 		{
	       	      			// Add smilie to the array
       	      				$smilie_url[] = $smilie['smilie_url'];

       	      				if($smilie_no == 1)
       	      				{
       	      					$theme->insert_nest("edit_pm", "smilies/emoticon_row");
       	      				}

       	      				$theme->insert_nest("edit_pm", "smilies/emoticon_row/emoticon_cell", array(
       	      					"EMOTICON_CODE" => $smilie['smilie_code'],
       	      					"EMOTICON_URL" => $smilie['smilie_url'],
       	      					"EMOTICON_TITLE" => $smilie['smilie_name']
       	      				));
       	      				$theme->add_nest("edit_pm", "smilies/emoticon_row/emoticon_cell");
       	      				if($smilie_no >= 5)
       	      				{
      							$theme->add_nest("edit_pm", "smilies/emoticon_row");
       	      					$smilie_no = 1;
       	      				}
       	      				else
       	      				{
       	      					$smilie_no++;
       	      				}
       	      				$smilie_count++;
       	      				if($smilie_count > 20)
       	      				{
       	      					break;
      	       				}
       	       			}
       				}
       				$theme->add_nest("edit_pm", "smilies");
       			}

       			$theme->insert_nest("edit_pm", "error", array(
       				"ERRORS" => $error
       			));
       			$theme->add_nest("edit_pm", "error");

       			//
       			// Output the page header
       			//
       			include($root_path . "includes/page_header.php");

       			//
       			// Output the main page
       			//
       			$theme->output("edit_pm");

       			//
       			// Output the page footer
       			//
       			include($root_path . "includes/page_footer.php");
       		}
       		else
       		{
       			$db2->query("UPDATE `_PREFIX_pm`
					SET `pm_title` = :title,
					`pm_body` = :body
					WHERE `pm_id` = :pm_id",
					array(
						":title" => $_POST['title'],
						":body" => $_POST['body'],
						":pm_id" => $_GET['id']
					)
				);
       			info_box($lang['PM_Manager'], $lang['PM_Edited'], "?pm.php");
       		}
       	}
       	else
       	{
       		$sql = $db2->query("SELECT *
				FROM `_PREFIX_pm`
				WHERE `pm_id` = :pm_id &&
					`pm_sent_from` = :sender &&
					`pm_type` = '1'",
				array(
					":pm_id" => $_GET['id'],
					":sender" => $user['user_id']
				)
			);
       		if($result = $sql->fetch())
       		{
       			$theme->new_file("edit_pm", "send_pm.tpl");
       			$theme->replace_tags("edit_pm", array(
       				"ACTION" => $lang['Edit_PM'],
       				"TITLE" => $result['pm_title'],
       				"BODY" => $result['pm_body'],
					"CSRF_TOKEN" => CSRF::getHTML()
       			));

       			if($config['bbcode_enabled'] == true)
       			{
       				// Add the BBCode chooser to the page
       				$theme->insert_nest("edit_pm", "bbcode");
       				$theme->add_nest("edit_pm", "bbcode");
       			}
       			if($config['smilies_enabled'] == true)
       			{

       				// Add the emoticon chooser to the page
       				$theme->insert_nest("edit_pm", "smilies");
       		 	    $smilie_query = $db2->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `_PREFIX_smilies`");
       		 	    $smilie_no = 1;
       				$smilie_count = 1;
       				$smilie_url = array();
       				while($smilie = $smilie_query->fetch())
       				{
       					// Check if the smilie has already been displayed
        	      		if(!in_array($smilie['smilie_url'], $smilie_url))
        	     		{
        	      			// Add smilie to the array
        	      			$smilie_url[] = $smilie['smilie_url'];

        	      			if($smilie_no == 1)
        	      			{
        	      				$theme->insert_nest("edit_pm", "smilies/emoticon_row");
        	      			}

        	      			$theme->insert_nest("edit_pm", "smilies/emoticon_row/emoticon_cell", array(
        	      				"EMOTICON_CODE" => $smilie['smilie_code'],
        	      				"EMOTICON_URL" => $smilie['smilie_url'],
        	      				"EMOTICON_TITLE" => $smilie['smilie_name']
        	      			));
        	      			$theme->add_nest("edit_pm", "smilies/emoticon_row/emoticon_cell");
        	      			if($smilie_no >= 5)
        	      			{
       						$theme->add_nest("edit_pm", "smilies/emoticon_row");
        	      				$smilie_no = 1;
        	      			}
        	      			else
        	      			{
        	      				$smilie_no++;
        	      			}
        	      			$smilie_count++;
        	      			if($smilie_count > 20)
        	      			{
        	      				break;
       	       				}
       	       			}
       				}
       				$theme->add_nest("edit_pm", "smilies");
       			}

       			//
       			// Output the page header
       			//
       			include($root_path . "includes/page_header.php");

       			//
       			// Output the main page
       			//
       			$theme->output("edit_pm");

       			//
       			// Output the page footer
       			//
       			include($root_path . "includes/page_footer.php");
       		}
       		else
       		{
       			error_msg($lang['Error'], $lang['Invalid_PM_Id']);
       		}
		}
	}
	else
	{
		error_msg($lang['Error'], $lang['Invalid_PM_Id']);
	}
}
else if(isset($_GET['id']) && $_GET['id'] > 0)
{
	$sql = $db2->query("SELECT pm.*,
		u.`user_id`,
		u.`username`,
		u.`user_avatar_type`,
		u.`user_avatar_location`,
		u.`user_rank`,
		u.`user_date_joined`,
		u.`user_signature`,
		u.`user_posts`,
		u.`user_location`,
		r.`rank_name`,
		r.`rank_image`
		FROM ((`_PREFIX_pm` pm
		LEFT JOIN `_PREFIX_users` u ON u.`user_id` = pm.`pm_sent_from`)
		LEFT JOIN `_PREFIX_ranks` r ON r.`rank_id` = u.`user_rank`)
		WHERE `pm_id` = :pm_id && (
			(`pm_send_to` = :as_receiver && (`pm_type`='1' || `pm_type`='2')) ||
			(`pm_sent_from` = :as_sender && (`pm_type`='1' || `pm_type`='3'))
		)
		LIMIT 1",
		array(
			":pm_id" => $_GET['id'],
			":as_receiver" => $user['user_id'],
			":as_sender" => $user['user_id']
		)
	);

	if($result = $sql->fetch())
	{
		$theme->new_file("view_pm", "view_pm.tpl");

		if(!empty($result['user_signature']))
		{
			$result['user_signature'] = "<br /><br />\n----------<br />\n".format_text($result['user_signature']);
		}

		$theme->replace_tags("view_pm", array(
			"AUTHOR_USERNAME" => $result['username'],
			"AUTHOR_RANK" => ($result['user_id'] > 0) ? $result['rank_name'] : "",
			"AUTHOR_SIGNATURE" => $result['user_signature'],
			"TITLE" => $result['pm_title'],
			"BODY" => format_text($result['pm_body']),
			"DATE" => create_date("D d M Y g:i a", $result['pm_date'])
		));

		if($result['user_id'] > 0)
		{
			$theme->insert_nest("view_pm", "author_standard", array(
				"AUTHOR_JOINED" => create_date("D d M Y", $result['user_date_joined']),
				"AUTHOR_POSTS" => $result['user_posts']
			));
			$theme->add_nest("view_pm", "author_standard");

			if(!empty($result['user_location']))
			{
				$theme->insert_nest("view_pm", "author_location", array(
					"AUTHOR_LOCATION" => $result['user_location']
				));
				$theme->add_nest("view_pm", "author_location");
			}
		}

		if(!empty($result['rank_image']))
		{
			$theme->insert_nest("view_pm", "rank_image", array(
				"AUTHOR_RANK" => $result['rank_name'],
				"AUTHOR_RANK_IMG" => $result['rank_image']
			));
			$theme->add_nest("view_pm", "rank_image");
		}

		if($result['user_avatar_type'] == UPLOADED_AVATAR || $result['user_avatar_type'] == REMOTE_AVATAR)
		{
			if($result['user_avatar_type'] == UPLOADED_AVATAR)
			{
				$result['user_avatar_location'] = $root_path . $config['avatar_upload_dir'] . "/" . $result['user_avatar_location'];
			}
			$theme->insert_nest("view_pm", "avatar", array(
				"AUTHOR_AVATAR_LOCATION" => $result['user_avatar_location']
			));
			$theme->add_nest("view_pm", "avatar");
		}

		if($result['pm_unread'] == "1")
		{
			if(!($result['pm_send_to'] != $user['user_id'] && $result['pm_sent_from'] == $user['user_id'])) {
				$db2->query("UPDATE `_PREFIX_pm`
					SET `pm_unread` = '0'
					WHERE `pm_id` = :pm_id",
					array(
						":pm_id" => $_GET['id']
					)
				);
			}
		}

		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("view_pm");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");

	}
	else
	{
		error_msg($lang['Error'], $lang['Invalid_PM_Id']);
	}
}
else
{
	$theme->new_file("manage_pm", "manage_pm.tpl", "");

	if($_GET['func'] == "sentbox")
	{
		$where_query = "WHERE `pm_sent_from` = :user_id && (`pm_type` = '1' || `pm_type` = '3') && `pm_unread` = '1'";
	}
	else if($_GET['func'] == "outbox")
	{
		$where_query = "WHERE `pm_sent_from` = :user_id && (`pm_type` = '1' || `pm_type` = '3') && `pm_unread` = '0'";
	}
	else
	{
		$where_query = "WHERE `pm_send_to` = :user_id && (`pm_type` = '1' || `pm_type` = '2')";
	}

	$count_sql = $db2->query("SELECT count(`pm_id`) AS `pm_count`
		FROM `_PREFIX_pm` ".$where_query."",
		array(
			":user_id" => $user['user_id']
		)
	);
	$count_array = $count_sql->fetch();
	$pagination = $pp->paginate($count_array['pm_count'], $config['pm_per_page']);
	
	$theme->replace_tags("manage_pm", array(
		"PAGINATION" => $pagination
	));

	$pm_query = $db2->query("SELECT pm.*,
		u.`username`
		FROM (`".$db_prefix."pm` pm
		LEFT JOIN `".$db_prefix."users` u ON u.`user_id` = pm.`pm_sent_from`)
		$where_query
		ORDER BY pm.`pm_date`
		LIMIT ".$pp->limit."",
		array(
			":user_id" => $user['user_id']
		)
	);

	$pm_count = 0;
	while($pm = $pm_query->fetch())
	{
		$theme->insert_nest("manage_pm", "pm_row", array(
			"ID" => $pm['pm_id'],
			"NAME" => $pm['pm_title'],
			"AUTHOR" => $pm['username'],
			"DATE" => create_date("D d M Y", $pm['pm_date'])
		));

		if($pm['pm_unread'] == 1)
		{
			$theme->switch_nest("manage_pm", "pm_row/unread", true);
		}
		else
		{
			$theme->switch_nest("manage_pm", "pm_row/unread", false);
		}

		if($_GET['func'] == "outbox")
		{
			$theme->insert_nest("manage_pm", "pm_row/pm_edit", array(
				"ID" => $pm['pm_id']
			));
		}

		$theme->add_nest("manage_pm", "pm_row");
		$pm_count++;
	}
	if($_GET['func'] == "sentbox")
	{
		$location = strtolower($lang['Sent_Box']);
		$theme->replace_tags("manage_pm", array(
			"LOCATION" => $lang['Sent_Box']
		));
	} else if($_GET['func'] == "outbox")
	{
		$location = strtolower($lang['Outbox']);
		$theme->replace_tags("manage_pm", array(
			"LOCATION" => $lang['Outbox']
		));
	}
	else
	{
		$location = strtolower($lang['Inbox']);
		$theme->replace_tags("manage_pm", array(
			"LOCATION" => $lang['Inbox']
		));
	}
	if($pm_count > 0)
	{
		$theme->switch_nest("manage_pm", "pm_titles", true);
		$theme->add_nest("manage_pm", "pm_titles");
	}
	else
	{
		$theme->switch_nest("manage_pm", "pm_titles", false, array(
			"NO_PM" => sprintf($lang['You_currently_have_no_PMs_in_your'], $location)
		));
		$theme->add_nest("manage_pm", "pm_titles");
	}
	//
	// Output the page header
	//
	include($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("manage_pm");

	//
	// Output the page footer
	//
	include($root_path . "includes/page_footer.php");
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
