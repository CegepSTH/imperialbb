<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: class_mysql.php                                            # ||
|| # ---------------------------------------------------------------- # ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB"))
{
        die("Hacking Attempt");
}

class ibb_db_engine
{
	var $dbname		 = '';
	var $dbhost		 = '';
	var $dbuser		 = '';
	var $dbpass		 = '';
	var $dbpcon		 = '';
	var $sqlstart    = '';
	var $sqlstop     = '';
	var $dblink		 = NULL;
	var $querytime   = NULL;
	var $query_count = 0;
	var $format      = 3;

	function ibb_db_engine()
	{
		global $database;
		$this->dbname = $database['dbname'];
		$this->dbhost = $database['dbhost'];
		$this->dbuser = $database['dbuser'];
		$this->dbpass = $database['dbpass'];
        $this->dbpcon = $database['dbpcon'];
		$this->connect();
	}

	function connect()
	{
		if($this->dbpcon = 'y')
		{
			$this->dblink = @mysql_pconnect($this->dbhost, $this->dbuser, $this->dbpass);
		}
		else
		{
			$this->dblink = @mysql_connect($this->dbhost, $this->dbuser, $this->dbpass);
		}
		if(!$this->dblink)
		{
			die("Could not connect to mysql database:<br />".mysql_error());
			return FALSE;
		}
		if(!mysql_select_db($this->dbname,$this->dblink))
		{
			die("Unable to select database<br />".mysql_error());
			return FALSE;
		}
		return TRUE;
	}

	function query($sql, $query_type = 'mysql_query')
	{
		$this->sqlstart 	= explode(' ',microtime());
		$this->sqlstart 	= $this->sqlstart[0] + $this->sqlstart[1];
    	if(!get_magic_quotes_gpc())
    	{
    		$sql = $this->escape_string($sql);
    	}
		$this->query 		= $query_type($sql, $this->dblink) or $this->sqlerror($sql);
		$this->sqlstop 		= explode(' ',microtime());
		$this->sqlstop 		= $this->sqlstop[0] + $this->sqlstop[1];
		$this->querytime 	+= round($this->sqlstop - $this->sqlstart, $this->format);
		$this->query_count++;
		return $this->query;
	}

	function fetch_array($query, $type = '')
	{
		if($type == "")
		{
			return mysql_fetch_array($query, MYSQL_ASSOC);
		}
		else
		{
			return mysql_fetch_array($query, $type);
		}
	}

	function fetch_assoc($query)
	{
        return mysqli_fetch_assoc($query);
	}

	function fetch_row($query)
	{
		return mysql_fetch_row($query);
	}

    function fetch_object($query)
    {
        return mysql_fetch_object($query);
    }

	function num_rows($query)
	{
        return mysql_num_rows($query);
	}

    function num_fields($query)
    {
        return mysql_num_fields($query);
    }

	function insert_id()
	{
		return mysql_insert_id($this->dblink);
	}

	function escape_string($string)
	{
		if(function_exists('mysql_real_escape_string'))
		{
			$string = mysql_real_escape_string($string);
		}
		elseif(function_exists('mysql_escape_string'))
		{
			$string = mysql_escape_string($string);
		}
		else
		{
			$string = addslashes($string);
		}
		return $string;
	}

	function sqlversion()
	{
		$sql = $this->query("SELECT VERSION() AS version");
		$ver = $this->fetch_array($sql);
		if($ver['version'])
		{
			$version = explode('.', $ver['version'], 3);
			$dbversion = intval($version[0]).'.'.intval($version[1]).'.'.intval($version[2]);
		}
		return $dbversion;
	}

	function free($sql = NULL)
	{
		if($sql == TRUE)
		{
			$this->free = mysql_free_result($sql);
		}
		else if($sql == NULL && $this->query == TRUE)
		{
			$this->free = mysql_free_result($this->query);
		}
		else
		{
			if($sql == NULL && $this->query == NULL)
			{
				$this->free = die(($sql == NULL ? $this->query : $sql));
			}
		}
		return $this->free;
	}

	function close()
	{
        if($this->dblink)
        {
		    return mysql_close($this->dblink);
        }
        else
        {
            return FALSE;
        }
	}

	function sqlerror($sql)
	{
		if(function_exists('error_msg'))
		{
			error_msg('Error', 'Error executing mysql query<br />MySQL : .'.htmlspecialchars($sql).'<br />Error : '.mysql_error());
		}
		else
		{
			die('Error executing mysql query<br />MySQL : '.htmlspecialchars($sql).'<br />Error : '.mysql_error());
		}
		return 0;
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
