<?php

class Template {
	// Base path of the template
	private static $m_basePath;
	// Associative array as $varName => $value
	private $m_vars;
	// Associative array as $tagName => $content
	private $m_tags;
	// Relative file path for the template.
	private $m_filePath; 
	
	/**
	 * CTOR 
	 * 
	 * @param $str_file File relative path / file name
	 */
	public __construct($str_file) {
		$this->m_vars = array();
		$this->m_tags = array();
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
		
		$this->m_tags[$str_tagName] .= $content;
	}
	
	/**
	 * Add multiple tags to template.
	 * 
	 * @param $tags Associative array as $name => $content
	 * @note $content may be either a template object or a string.
	 */
	public addToTags(array $tags) {
		foreach($tags as $name => $content) {
			addToTag($name, $content);
		}
	}
	
	/**
	 * Renders the template
	 * 
	 * @returns Parsed content string.
	 */
	public render() {
		$content = "";
		
		$fPath = $this->m_basePath . "/" . $this->m_filePath;
		$hFile = fopen($fPath, "r");
		
		if ($hFile) {
			while (($sLine = fgets($hFile)) !== false) {
				$sLineCopy = "";
				
				foreach($this->m_vars as $name => $value) {
					$sLineCopy .= str_replace("{".$name."}", $value, $sLine);
				}
				
				foreach($this->m_tags as $name => $value) {
					$content .= str_replace("<!-- TAG ".$name." -->", $value, $sLineCopy);
				}
			}

			fclose($hFile);
		} else {
			die(__FILE__ . " : Cannot open " . $fPath );
		} 
		
		return $content;
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
