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
    function select_query($fields, $tables, $where = array(), $order = array(), $group = array(), $limit = "")
    {
        $fields = implode(",",$fields);
        $tables = implode(",", $tables);
        $where  = implode(" AND ", $where);
        $order  = implode(",",$order);
        $group  = implode(",",$group);
        $sql = "SELECT ".$fields." FROM ".$tables." ";
        if(!empty($where))
        {
            $sql .= ' WHERE '.$where;
        }
        if(!empty($order))
        {
            $sql .= ' ORDER BY '.$order;
        }
        if(!empty($group))
        {
            $sql .= ' GROUP BY '.$group;
        }
        if(!empty($limit))
        {
            $sql .= ' LIMIT '.$limit;
        }
        return $this->query($sql);
    }

    function insert_query($tables, $values, $fields = array(), $replace = FALSE)
    {
        foreach($values as $key => $val)
        {
            $values[$key] = $this->sanitize_query($val);
        }
        $tables = implode(",", $tables);
        $values = implode(",", $values);
        $fields = implode(",", $fields);
        if($replace)
        {
            $sql = "REPLACE INTO ".$tables." ";
        }
        else
        {
            $sql = "INSERT INTO ".$tables." ";
        }
        if(!empty($fields))
        {
            $sql .= '('.$fields.') ';
        }
        $sql .= 'VALUES ('.$values.')';
        return $this->query($sql);
    }

    function update_query($tables, $values, $fields, $where = array(), $limit = "")
    {
        foreach($values as $key=>$val)
        {
            $pairs[] = $this->$fields[$key].' = '.$this->sanitize_query($val);
        }
        $pairs  = implode(",",$pairs);
        $tables = implode(",", $tables);
        $where  = implode(" AND ", $where);
        $sql    = "UPDATE ".$tables." SET ".$pairs." ";
        if(!empty($where))
        {
            $sql .= " WHERE ".$where."";
        }
        if(!empty($limit))
        {
            $sql .= " LIMIT ".$limit."";
        }
        return $this->query($sql);
    }

    function delete_query($table, $where = "", $limit = "")
    {
        $where = implode(",", $where);
        if(empty($where) && empty($limit))
        {
            $sql = "TRUNCATE ".$table."";
        }
        else
        {
            $sql = "DELETE FROM ".$table." ";
            if(!empty($where))
            {
                $sql .= ' WHERE '.$where;
            }
            if(!empty($limit))
            {
                $sql .= ' LIMIT '.$limit;
            }
        }
        return $this->query($sql);
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

    function sanitise_query($string)
    {
        return $this->escape_string(htmlentities($string, ENT_QUOTES));
    }
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
