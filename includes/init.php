<?
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: init.php                                                   # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB"))
{
	die("Hacking Attempt");
}

if(!defined('PHPVERSION'))
{
	define('PHPVERSION',intval(str_replace('.','',PHP_VERSION)));
}

function unescape_string($string)
{
	if(!get_magic_quotes_gpc())
	{
		return stripslashes($string);
	}
}

function sanitize_string($string)
{
	global $db;
	if(get_magic_quotes_gpc())
	{
		$string = stripslashes($string);
	}
	if(!is_numeric($string))
	{
		$string = $db->escape_string($string);
	}
	return $string;
}

function sql_quote($value)
{
	if(is_array($value))
	{
		foreach($value as $id => $var)
		{
		    $value[$id] = sql_quote($var);
		}
	}
	else
	{
		if(is_string($value))
		{

			$value = trim(sanitize_string(strip_tags(htmlspecialchars($value, ENT_QUOTES))));
		}
		else
		{
			$value = intval($value);
		}
	}
	return $value;
}

function clean_globals()
{
	global $_GET, $_POST, $_COOKIE, $_REQUEST, $_SESSION, $_ENV, $_SERVER, $_FILES;

	foreach($_GET as $varid => $var)
	{
		$_GET[$varid] = sql_quote($var);
		$$varid = sql_quote($var);
	}

	foreach($_POST as $varid => $var)
	{
		$_POST[$varid] = sql_quote($var);
		$$varid = sql_quote($var);
	}

	foreach($_SESSION as $varid => $var)
	{
		$_SESSION[$varid] = sql_quote($var);
		$$varid = sql_quote($var);
	}

	foreach($_COOKIE as $varid => $var)
	{
		$_COOKIE[$varid] = sql_quote($var);
		$$varid = sql_quote($var);
	}

	foreach($_REQUEST as $varid => $var)
	{
		$_REQUEST[$varid] = sql_quote($var);
		$$varid = sql_quote($var);
	}

	foreach($_SERVER as $varid => $var)
	{
		$_SERVER[$varid] = sql_quote($var);
		$$varid = sql_quote($var);
	}

	foreach($_ENV as $varid => $var)
	{
		$_ENV[$varid] = sql_quote($var);
		$$varid = sql_quote($var);
	}

	foreach($_FILES as $varid => $var)
	{
		$_FILES[$varid] = sql_quote($var);
		$$varid = sql_quote($var);
	}

	unset($varid, $var);
}

if(PHPVERSION < 410)
{
	$_GET 		= &$HTTP_GET_VARS;
	$_POST 		= &$HTTP_POST_VARS;
	$_COOKIE 	= &$HTTP_COOKIE_VARS;
	$_SERVER 	= &$HTTP_SERVER_VARS;
	$_FILES 	= &$HTTP_POST_FILES;
	$_ENV		= &$HTTP_ENV_VARS;
	$_SESSION	= &$HTTP_SESSION_VARS;
	$_REQUEST 	= array_merge($_GET,$_POST,$_COOKIE);
}
@ini_set('magic_quotes_sybase', 0);
@ini_set('magic_quotes_runtime', 0);
clean_globals();

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
