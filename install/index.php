<?php
define("IN_IBB", 1);
if(file_exists("../includes/config.php"))
{	include("../includes/config.php");


	// Unset db config because we dont need it!
	if(isset($database_config))
	{
	        unset($database_config);
	}

	// Check if the forum is already installed
	if(defined("INSTALLED") && INSTALLED == 1)
	{
	        header("Location: ../");
	        exit();
	}
}

error_reporting(0);

if(!isset($_POST['Submit']))
{
// Attempt to guess the directory
if(!empty($_ENV['SERVER_NAME']) || !empty($_SERVER['SERVER_NAME']))
{
	$server_name = (!empty($HTTP_SERVER_VARS['SERVER_NAME'])) ? $HTTP_SERVER_VARS['SERVER_NAME'] : $HTTP_ENV_VARS['SERVER_NAME'];
}
else if(!empty($_ENV['HTTP_HOST']) || !empty($_SERVER['HTTP_HOST']))
{
	$server_name = (!empty($HTTP_SERVER_VARS['HTTP_HOST'])) ? $HTTP_SERVER_VARS['HTTP_HOST'] : $HTTP_ENV_VARS['HTTP_HOST'];
}
else
{	$server_name = "";
}
$script_path = (isset($_ENV['PHP_SELF']) && !empty($_ENV['PHP_SELF'])) ? $_ENV['PHP_SELF'] : $_SERVER['PHP_SELF'];

$path = "http://" . $server_name . $script_path;
preg_match("#^(.*)/install/(.*)\$#", $path, $directory);
$directory = $directory[1];

page_header();
echo <<<END
					<form method="post" action="">
						<table width="90%" align="center" class="maintable">
							<tr>
								<th colspan="2" height="25">Database Settings</th>
							</tr>
							<tr>
								<td class="cell2" width="300">Database Type : </td><td class="cell1"><select name="dbtype"><option value="mysql">MySQL</option><option value="mysqli">MySQLi</option></select></td>
							</tr>
							<tr>
								<td class="cell2">Database Host (Usually localhost) : </td><td class="cell1"><input type="text" name="dbhost" value="localhost" size="65" /></td>
							</tr>
							<tr>
								<td class="cell2">Database Username : </td><td class="cell1"><input type="text" name="dbuser" value="" size="65" /></td>
							</tr>
							<tr>
								<td class="cell2">Database Password : </td><td class="cell1"><input type="password" name="dbpass" value="" size="65" /></td>
							</tr>
							<tr>
								<td class="cell2">Database Database Name : </td><td class="cell1"><input type="text" name="dbname" value="" size="65" /></td>
							</tr>
							<tr>
								<th colspan="2" height="25">FTP Settings</th>
							</tr>
							<tr>
								<td class="cell2">Use FTP (Recommended) : </td><td class="cell1"><input type="radio" name="useftp" value="true" onclick="use_ftp(true);" id="useftp_true" CHECKED /><label for="useftp_true">True</label>  <input type="radio" name="useftp" value="false" onclick="use_ftp(false);" id="useftp_false" /><label for="useftp_false">False</label></td>
							</tr>
							<tr id="ftp_user">
								<td class="cell2">FTP Username : </td><td class="cell1"><input type="text" name="ftpuser" value="" size="65" /></td>
							</tr>
							<tr id="ftp_pass">
								<td class="cell2">FTP Password : </td><td class="cell1"><input type="password" name="ftppass" value="" size="65" /></td>
							</tr>
							<tr id="ftp_path">
								<td class="cell2">FTP Path (E.G. /public_html/forums/ ) : </td><td class="cell1"><input type="text" name="ftppath" value="/" size="65" /></td>
							</tr>
							<tr>
								<th colspan="2" height="25">General Settings</th>
							</tr>
							<tr>
								<td class="cell2">Forum Name : </td><td class="cell1"><input type="text" name="forum_name" value="My Forum" size="65" /></td>
							</tr>
							<tr>
								<td class="cell2">Forum Description : </td><td class="cell1"><input type="text" name="forum_desc" value="" size="65" /></td>
							</tr>
							<tr>
								<td class="cell2">Forum Path : </td><td class="cell1"><input type="text" name="forum_path" value="$directory" size="65" /></td>
							</tr>
							<tr>
								<th colspan="2" height="25">Administrator Settings</th>
							</tr>
							<tr>
								<td class="cell2">Admin Username : </td><td class="cell1"><input type="text" name="admin_user" value="" size="65" /></td>
							</tr>
							<tr>
								<td class="cell2">Admin Password : </td><td class="cell1"><input type="password" name="admin_pass" value="" size="65" /></td>
							</tr>
							<tr>
								<td class="cell2">Admin Email : </td><td class="cell1"><input type="text" name="admin_email" value="" size="65" /></td>
							</tr>
							<tr>
								<td align="center" colspan="2" class="desc_row" height="25"><input type="submit" name="Submit" value="Submit" /><input type="reset" value="Reset" />
							</tr>
						</table>
					</form>
END;
page_footer();

}
else
{

page_header();

echo <<<END
					<table width="90%" align="center" class="maintable">
						<tr>
							<th colspan="2" height="25">Installing..</th>
						</tr>
						<tr>
							<td class="cell2">
END;


if($_POST['useftp'] == "true")
{	include("temp_ftp.php");
	echo "Connecting to FTP server...  ";
	$ftp = new ftp($_POST['ftpuser'], $_POST['ftppass']);

	if(!$ftp->chdir($_POST['ftppath']))
	{
		error_msg("FTP directory is invalid... Exiting");
	}

	echo "Done<br /><br />";
}
$dbhost = $_POST['dbhost'];
$dbuser = $_POST['dbuser'];
$dbpass = $_POST['dbpass'];
$dbname = $_POST['dbname'];

$file_data = <<<END
<?php

/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: config.php                                                 # ||
|| # ---------------------------------------------------------------- # ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB")) {
	die("Hacking Attempt");
}

// This file is automatically generated by the ImperialBB installer
// Only edit this file if you know what your doing
\$database = array(
	'dbhost' => '$dbhost',
	'dbuser' => '$dbuser',
	'dbpass' => '$dbpass',
	'dbname' => '$dbname',
    'dbtype' => '$dbtype',
	'dbpcon' => 'n',
	'prefix' => 'ibb_'
);

\$db_prefix = \$database['prefix'];

\$debug = 0; // Debug mode (2 = advanced, 1 = simple, 0 = off)

##### There is no need to edit anything beyond here #####
define("INSTALLED", 1);

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
END;

$db = mysql_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']) or error_msg("Could not connect to mysql database:<br />".mysql_error());

if($_POST['useftp'] == "true")
{	echo "Uploading configuration file...  ";
	$tmpname = @tempnam('/tmp', 'html');
	@unlink($tmpname);
	$fp = @fopen($tmpname, 'w');
	@fwrite($fp, $file_data);
	@fclose($fp);


	$ftp->upload($tmpname, "includes/config.php", FTP_BINARY);
	echo "Done<br /><br />";
}

if(!mysql_select_db($_POST['dbname'],$db))
{
	error_msg("Unable to select mysql database, please ensure it already exists");
}

echo "Running SQL queries...  ";
$file_content = file("database.sql");
$query = "";
foreach($file_content as $sql_line)
{
	$trimmed = trim($sql_line);
	if (($sql_line != "") && (substr($trimmed, 0, 2) != "--") && (substr($trimmed, 0, 1) != "#"))
	{
		$query .= $sql_line;
		if(preg_match("/;\s*$/", $sql_line))
		{
			if (!mysql_query($query)) {
				echo "<b>Error</b><br />SQL : $query<br />Error : " . mysql_error();
			}
			$query = "";
		}
	}
}

if(!mysql_query($_POST['dbtype'], "INSERT INTO `ibb_users` (`username`, `user_password`, `user_email`, `user_date_joined`, `user_level`, `user_rank`) VALUES ('".$_POST['admin_user']."', '".md5(md5($_POST['admin_pass']))."', '".$_POST['admin_email']."', '".time()."', '5', '3')"))
{
	echo "<b>Error</b> : #1 Unable to insert admin user : " . mysql_error() . "<br />";
}

if($_POST['useftp'] == "true")
{
	if(!mysql_query("UPDATE `ibb_config` SET `config_value` = '".$_POST['ftpuser']."' WHERE `config_name` = 'ftp_user'"))
	{
		echo "<b>Error</b> : #2 Unable to update mysql data : " . mysql_error() . "<br />";
	}
	if(!mysql_query("UPDATE `ibb_config` SET `config_value` = '". base64_encode($_POST['ftppass'])."' WHERE `config_name` = 'ftp_pass'"))
	{
		echo "<b>Error</b> : #3 Unable to update mysql data : " . mysql_error() . "<br />";
	}
	if(!mysql_query("UPDATE `ibb_config` SET `config_value` = '".$_POST['ftppath']."' WHERE `config_name` = 'ftp_path'"))
	{
		echo "<b>Error</b> : #4 Unable to update mysql data : " . mysql_error() . "<br />";
	}
}

if(!mysql_query("UPDATE `ibb_config` SET `config_value` = '" . $_POST['forum_name'] . "' WHERE `config_name` = 'site_name'"))
{
	echo "<b>Error</b> : #5 Unable to update mysql data : " . mysql_error() . "<br />";
}
if(!mysql_query("UPDATE `ibb_config` SET `config_value` = '" . $_POST['forum_desc'] . "' WHERE `config_name` = 'site_desc'"))
{
	echo "<b>Error</b> : #6 Unable to update mysql data : " . mysql_error() . "<br />";
}
if(!mysql_query("UPDATE `ibb_config` SET `config_value` = '" . $_POST['forum_path'] . "' WHERE `config_name` = 'url'"))
{
	echo "<b>Error</b> : #7 Unable to update mysql data : " . mysql_error() . "<br />";
}

echo "Done<br /><br />";

if($_POST['useftp'] == "false")
{	echo "<b>Configuration File</b><br /><br />Please upload the below file contents into includes/config.php<br /><textarea rows=\"20\" cols=\"75\">".htmlspecialchars($file_data)."</textarea>";
}

echo <<<END
							<br /><br /><h2>Forum Installed Successfully</h2><br />You may now continue to your board by <a href="../">Clicking Here</a>
							</td>
						</tr>
					</table>
END;

page_footer();

}

function page_header()
{
	echo <<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
 <title>ImperialBB Forum Software Installer</title>
 <link rel="stylesheet" type="text/css" href="tpl_files/style.css" />
 <script type="text/javascript" src="tpl_files/scripts.js"></script>
</head>
<body bgcolor="#E5E5E5">
<table width="100%" align="center" class="bodytable">
	<tr>
		<td>
			<table width="98%" align="center">
				<tr>
					<td style="border-style: solid; border-color: #1E34FD; border-width: 1px; padding: 1px;">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td height="67" width="256" align="left">
									<img src="tpl_files/logo.gif" />
								</td>
								<td>
									<img src="tpl_files/logo2.gif" height="67" width="100%" />
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
					<br /><br />
END;

}

function page_footer()
{

	echo <<<END
					</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
</table>
<br />
<table align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			Powered by <a href="http://www.imperialbb.com/">ImperialBB</a><br />
		</td>
	</tr>
</table>
</body>
</html>
END;

}

function error_msg($message)
{	echo <<<END
							$message
							<br /><br /><h2>Failed to install forum</h2>
							</td>
						</tr>
					</table>
END;
	page_footer();
	exit();
}

?>