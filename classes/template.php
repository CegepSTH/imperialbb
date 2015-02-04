<?php

class Template {
	// Base path of the template
	private static $m_basePath;
	// Associative array as $varName => $value
	private $m_vars;
	// Relative file path for the template.
	private $m_filePath; 
	
	/**
	 * CTOR 
	 * 
	 * @param $str_file File relative path / file name
	 */
	public __construct($str_file) {
		$this->m_vars = array();
	}
	
	/**
	 * Assign a value to a template var. 
	 * 
	 * @param $str_name Variable's name. Must be string.
	 * @param $value Variable's value
	 */
	public setVar($str_name, $value) {
		$this->m_vars[$str_name] = $value;		
	}
	
	/**
	 * Assign value to multiple vars
	 * 
	 * @param $vars Associative array as $name => $value
	 */
	public setVars(array $vars) {
		foreach($vars as $name => $value) {
			setVar($name, $value);
		}	
	}
	
	/**
	 * Adds to the current template
	 * 
	 * @param $str_tagName Tag name.
	 * @param $str_content Either a string with the content or
	 * 		with a Template object.
	 */
	public addToTag($str_tagName, $str_content) {
		$content = $str_content;
		
		if($str_content instanceof Template) {
			$content = $str_content->render();
		}
		
		
	}
	
	/**
	 * Sets the base path for the templates. 
	 * 
	 * @param $str_basePath 
	 */
	public static setBasePath($str_basePath)  {
		if(!is_string($str_basePath)) {
			die( __METHOD__ . " [". __LINE__ . ":] Base path is not a string");
		}
		
		self::$m_basePath = $str_basePath;
	}
}

?>
