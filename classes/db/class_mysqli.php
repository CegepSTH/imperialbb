<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: class_mysqli.php                                           # ||
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
			$this->dblink = @mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		}
		else
		{
			$this->dblink = @mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		}
		if(!$this->dblink)
		{
			die("Can not connect mysqli Server or DataBase: <br />" . "Error #" . mysqli_connect_errno() . ": " . mysqli_connect_errno());
			return FALSE;
		}
		return TRUE;
	}

  	function query($sql, $query_type = 'mysqli_query')
	{
		$this->sqlstart 	= explode(' ',microtime());
		$this->sqlstart 	= $this->sqlstart[0] + $this->sqlstart[1];
    	if(!get_magic_quotes_gpc())
    	{
    		$sql = $this->escape_string($sql);
    	}
		$this->query 		= $query_type($this->dblink, $sql) or $this->sqlerror($sql);
		$this->sqlstop 		= explode(' ',microtime());
		$this->sqlstop 		= $this->sqlstop[0] + $this->sqlstop[1];
		$this->querytime 	+= round($this->sqlstop - $this->sqlstart, $this->format);
		$this->query_count++;
		return $this->query;
	}

	function fetch_array($query, $type = '')
	{
		if($type == '')
		{
			return mysqli_fetch_array($query, MYSQLI_ASSOC);
		}
		else
		{
			return mysqli_fetch_array($query, $type);
		}
	}

	function fetch_assoc($query)
	{
        return mysqli_fetch_assoc($query);
	}

	function fetch_row($query)
	{
		return mysqli_fetch_row($query);
	}

    function fetch_object($query)
    {
        return mysqli_fetch_object($query);
    }

	function num_rows($query)
	{
        return mysqli_num_rows($query);
	}

    function num_fields($query)
    {
        return mysqli_num_fields($query);
    }

	function insert_id()
	{
		return mysqli_insert_id($this->dblink);
	}

	function escape_string($string)
	{
		return mysqli_real_escape_string($this->dblink,$string);
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
			$this->free = mysqli_free_result($sql);
		}
		else if($sql == NULL && $this->query == TRUE)
		{
			$this->free = mysqli_free_result($this->query);
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
		    return mysqli_close($this->dblink);
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
			error_msg('Error','Error executing mysqli query<br />MySQLi:.'.htmlspecialchars($sql).'<br />Error : '.mysqli_error($this->dblink));
		}
		else
		{
			die('Error executing mysqli query<br />MySQLi : '.htmlspecialchars($sql).'<br />Error : '.mysqli_error($this->dblink));
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
