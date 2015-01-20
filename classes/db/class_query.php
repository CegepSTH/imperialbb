<?
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: class_query.php                                            # ||
|| # ---------------------------------------------------------------- # ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB"))
{
        die("Hacking Attempt");
}

class ibb_db_query extends ibb_db_engine
{
	function select_query($table, $fields = "*", $conditions = "", $options = array())
	{
		$query = "SELECT ".$fields." FROM ".$table;
		if($conditions != "")
		{
			$query .= " WHERE ".$conditions;
		}
		if(isset($options['order_by']))
		{
			$query .= " ORDER BY ".$options['order_by'];
			if(isset($options['order_dir']))
			{
				$query .= " ".strtoupper($options['order_dir']);
			}
		}
		if(isset($options['limit_start']) && isset($options['limit']))
		{
			$query .= " LIMIT ".$options['limit_start'].", ".$options['limit'];
		}
		elseif(isset($options['limit']))
		{
			$query .= " LIMIT ".$options['limit'];
		}
		return $this->query($query);
	}

	function insert_query($table, $array)
	{
		if(!is_array($array))
		{
			return false;
		}
		$comma  = "";
		$query1 = "";
		$query2 = "";
		foreach($array as $field => $value)
		{
			$query1 .= $comma.$field;
			$query2 .= $comma."'".$value."'";
			$comma = ", ";
		}
		return $this->query("INSERT INTO ".$table." (".$query1.") VALUES (".$query2.")");
	}

	function replace_query($table, $array)
	{
		if(!is_array($array))
		{
			return false;
		}
		$comma  = "";
		$query1 = "";
		$query2 = "";
		foreach($array as $field => $value)
		{
			$query1 .= $comma.$field;
			$query2 .= $comma."'".$value."'";
			$comma = ", ";
		}
		return $this->query("REPLACE INTO ".$table." (".$query1.") VALUES (".$query2.")");
	}

	function update_query($table, $array, $where = "", $limit = "")
	{
		if(!is_array($array))
		{
			return false;
		}
		$comma = "";
		$query = "";
		foreach($array as $field => $value)
		{
			$query .= $comma.$field."='".$value."'";
			$comma = ", ";
		}
		if(!empty($where))
		{
			$query .= " WHERE ".$where."";
		}
		if(!empty($limit))
		{
			$query .= " LIMIT ".$limit."";
		}
		return $this->query("UPDATE ".$table." SET ".$query."");
	}

	function delete_query($table, $where = "", $limit = "")
	{
		$query = "";
		if(!empty($where))
		{
			$query .= " WHERE ".$where."";
		}
		if(!empty($limit))
		{
			$query .= " LIMIT ".$limit."";
		}
		return $this->query("DELETE FROM ".$table." ".$query."");
	}

	function select_query_with_join($data)
	{
		$select = array($data['select']);
		$join_sql = '';
		if(is_array($data['joins']))
		{
			foreach($data['joins'] as $join)
			{
				if($join['select'])
				{
					$select[] = $join['select'];
				}
				if($join['type'] == "left")
				{
					$join_sql .= "LEFT JOIN ".$join['table']." ";
					if($join['where'])
					{
						$join_sql .= "ON (".$join['where'].") ";
					}
				}
				else if($join['type'] == "inner")
				{
					$join_sql .= "INNER JOIN ".$join['table']." ";
					if($join['where'])
					{
						$join_sql .= "ON (".$join['where'].") ";
					}
				}
			}
		}
		$select = implode(", ", $select);
		$rows = "";
		if($data['rows'] != "")
		{
			$rows = "(".$data['rows'].")";
		}
		$query = "SELECT ".$select." FROM ".$data['table']." ".$rows." ".$join_sql."";
		if($data['where'])
		{
			$query .= " WHERE ".$data['where']."";
		}
		if($data['order'])
		{
			$query .= " ORDER BY ".$data['order']."";
		}
		if(is_array($data['limit']))
		{
			$query .= " LIMIT ".$data['limit'][0]."";
			if($data['limit'][1])
			{
				$query .= ", ".$data['limit'][1]."";
			}
		}
		return $this->query($query);
	}

	function db_drop_table($table)
	{
		return $this->query("DROP TABLE if exists ".$table."");
	}

	function db_create_table($table)
	{
		return $this->query("CREATE TABLE ".$table."");
	}

	function db_drop_field($table, $field)
	{
		return $this->query("ALTER TABLE ".$table." DROP ".$field."");
	}

	function db_add_field($table, $field_name, $field_type, $field_default = "''")
	{
		return $this->query("ALTER TABLE ".$table." ADD ".$field_name." ".$field_type." DEFAULT ".$field_default."");
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
