<?php
/**
 * class Database 
 * author Alexandre Leblanc
 * This class manage the database. 
 */
class Database {
	// Error
	private $m_error;
	
	// Database handle
	private $m_db;
	
	// Resultset handle (PDOStatement)
	private $m_results;
	
	// Tables prefix (e.g. ibb_users, prefix is ibb_)
	private $m_prefix;
	
	/**
	 * CTOR
	 * @param $database String array containing each settings for the db.
	 */
	function __construct($database, $prefix = "") {
		$this->m_error = "";
		
		if(!isset($database) || !is_array($database)) {
			$this->m_error = "Database settings are not defined.";
			return;
		}
		
		if(!is_string($database['dbtype'])) {
			$this->m_error = "Database type not a string.";
			return;
		}
		
		if(!is_string($database['dbhost'])) {
			$this->m_error = "Database host not a string.";
			return;
		}
		
		if(!is_string($database['dbname'])) {
			$this->m_error = "Database name not a string.";
			return;
		}
		
		if(!is_string($database['dbuser']) || !is_string($database['dbpass'])) {
			$this->m_error = "Database credentials not strings.";
			return;
		}
		
		if(!is_string($prefix) || $prefix == null) {
			$this->m_error = "Database tables prefix are either not string or is null";
			return;
		}
		
		$this->m_prefix = $prefix;
		$conn_str = $database['dbtype'].":host=".$database['dbhost'].";dbname=".$database['dbname'].";charset=UTF8";
		
		try {
			$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);			
			$this->m_db = new PDO($conn_str, $database['dbuser'], $database['dbpass'], $options);
		} catch (PDOException $ex) {
			$this->m_error = $ex->getMessage();
			$this->m_db = null;
		} 
		
		return;
	}
	
	/**
	 * DTOR
	 */
	function __destruct() {
		$this->m_error = "";
		$this->m_db = null;
		$this->m_results = null;
	}
	
	/**
	 * query Executes the given query.
	 * @param $query Query's string.
	 * @param $values Binded values if any. Can be left empty.
	 * @returns PDOStatement object if success, null otherwise.
	 */
	function query($query, $values = null) {
		if(!is_string($query)) {
			$this->m_error = "Query is not a string";
			return null;
		}
		
		if(!is_null($values) && !is_array($values)) {
			$this->m_error = "Values are not in an array format.";
			return null;
		}
		
		if($this->m_db == null) {
			$this->m_error = "Tried to query without a valid DB Context.";
			return null;
		}
		
		// Replace the prefix marker. The space is important.
		$query = str_replace("_PREFIX_", $this->m_prefix, $query);

		try {
			$this->m_results = null;
			$this->m_results = $this->m_db->prepare($query, is_null($values) ? array() : $values);
			$this->m_results->execute($values);
		} catch (PDOException $ex) {
			$this->m_error = $ex->getMessage();
			
			return null;
		}
		
		return $this->m_results;
	}
	
	/**
	 * fetch() Fetch next entry.
	 * @note: Get as an associative array index with column name ("bla" => "value")
	 */
	function fetch() {
		if(is_null($this->m_db)) {
			$this->m_error = "No database set.";
			return null;
		}
		
		if(is_null($this->m_results)) {
			$this->m_error = "There were no results..!";
			return null;
		}
		
		return $this->m_results->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * fetchObject
	 * @returns Null if no results + error message. Otherwise return the next object.
	 * @note: Returns next entry as an anonymous object.
	 */
	function fetchObject() {
		if(is_null($this->m_db)) {
			$this->m_error = "No database set.";
			return null;
		}
		
		if(is_null($this->m_results)) {
			$this->m_error = "There were no results..!";
			return null;
		}
		
		return $this->m_results->fetchObject();
	}
	
	/**
	 * fetchAll Fetch all results in an array. 
	 * @returns All rows in an array.
	 */
	function fetchAll() {
		if(is_null($this->m_db)) {
			$this->m_error = "No database set.";
			return null;
		}
		
		if(is_null($this->m_results)) {
			$this->m_error = "There were no results..!";
			return null;
		}
		
		return $this->m_results->fetchAll();	
	}
	
	/**
	 * freeData
	 * Free the data.
	 */
	function freeData() {
		$this->m_results = null;
	}
	
	/**
	 * rowCount Returns row count
	 * @returns -1 if an error occured.
	 */
	function rowCount() {
		if(is_null($this->m_db)) {
			$this->m_error = "No database set.";
			return -1;
		}
		
		if(is_null($this->m_results)) {
			$this->m_error = "There were no results..!";
			return -1;
		}
		
		return $this->m_results->rowCount();
	}

	/**
	 * lastInsertId Gets the id of the last inserted row.
	 * @returns The id of the last inserted row, or -1 if an error occurred.
	 */
	function lastInsertId() {
		if(is_null($this->m_db)) {
			$this->m_error = "No database set.";
			return -1;
		}

		$lastId = $this->m_db->lastInsertId();
		return intval($lastId);
	}

	/** 
	 * getError Returns the last error if any.
     * Returns the last error message.
	 */
	function getError() {
		return $this->m_error;
	}
}

?>
