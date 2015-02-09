<?php
if(!isset($root_path)) $root_path = "../";

require_once($root_path . "includes/functions.php");

class Block {
	public $output;
	private static $m_blocks;
	
	public function Block($str_file, $str_blockName, array $values) {
		if(is_null(self::$m_blocks)) {
			self::$m_blocks = array();
		}
		$content = "";
		
		// If block was already loaded, do not redo an IO operation. 
		if(!isset(self::$m_blocks[$str_blockName]) || trim(self::$m_blocks[$str_blockName]) == "") {
			$content = file_get_contents($str_file);
			self::$m_blocks[$str_blockName] = $content;
		} else {
			$content = self::$m_blocks[$str_blockName];
		}

		$matches = array();
		preg_match_all("/<!-- BLOCK $str_blockName -->(.*?)<!-- END BLOCK $str_blockName -->/s", $content, $matches);
		$this->output = $matches[1][0];

		// replaces tags
		foreach($values as $key => $value) {
			$this->output = str_replace("{".$key."}", $value, $this->output);
		}
	}
}

class Template {
	// Base path of the template
	private static $m_basePath;
	// Associative array as $varName => $value
	private $m_vars;
	// Associative array as $tagName => $content
	private $m_tags;
	// Relative file path for the template.
	private $m_filePath;
	// Associative array of namespace ['C'] => array values
	private static $m_namespaces;
	// Blocks
	private $m_blocks;
	// Rendered: Already rendered content.
	private $m_rendered;
	
	/**
	 * CTOR 
	 * 
	 * @param $str_file File relative path / file name
	 */
	public function __construct($str_file) {
		$this->m_vars = array();
		$this->m_tags = array();
		$this->m_blocks = array();
		$this->m_filePath = $str_file;
		$this->m_rendered = null;
	}
	
	/**
	 * Assign a value to a template var. 
	 * 
	 * @param $str_name Variable's name. Must be string.
	 * @param $value Variable's value
	 */
	public function setVar($str_name, $value) {
		$this->m_vars[$str_name] = $value;		
	}
	
	/**
	 * Assign value to multiple vars
	 * 
	 * @param $vars Associative array as $name => $value
	 */
	public function setVars(array $vars) {
		foreach($vars as $name => $value) {
			$this->setVar($name, $value);
		}	
	}
	
	/**
	 * Adds to the current template
	 * 
	 * @param $str_tagName Tag name.
	 * @param $str_content Either a string with the content or
	 * 		with a Template object.
	 */
	public function addToTag($str_tagName, $str_content) {
		$content = $str_content;
		
		if($str_content instanceof Template) {
			$content = $str_content->render();
		}
		
		if(!isset($this->m_tags[$str_tagName])) {
			$this->m_tags[$str_tagName] = $content;
		} else {
			$this->m_tags[$str_tagName] .= $content;
		}
	}
	
	/**
	 * Add multiple tags to template.
	 * 
	 * @param $tags Associative array as $name => $content
	 * @note $content may be either a template object or a string.
	 */
	public function addToTags(array $tags) {
		foreach($tags as $name => $content) {
			$this->addToTag($name, $content);
		}
	}
	
	/**
	 * Adds values to the specified block
	 * 
	 * @param $str_name Name of the block
	 * @param $values Associative array of values as $key => $value
	 * @note Limited to 3 nested.
	 */
	public function addToBlock($str_name, array $values) {
		$block = new Block(self::$m_basePath . "/" . $this->m_filePath, $str_name, $values);
		
		if(!isset($this->m_blocks[$str_name])) {
			$this->m_blocks[$str_name] = "".$block->output;
		} else {
			$this->m_blocks[$str_name] .= "".$block->output;
		}
	}
	
	/**
	 * Renders the template
	 * 
	 * @returns Parsed content string.
	 */
	public function render() {
		if(!is_null($this->m_rendered)) {
			return $this->m_rendered;
		}
		
		$content = "";
		
		$fPath = self::$m_basePath . "/" . $this->m_filePath;
		$hFile = fopen($fPath, "r");
		
		if ($hFile) {
			while (($sLine = fgets($hFile)) !== false) {
				$sLineCopy = $sLine;
						
				// Replace the variables
				foreach($this->m_vars as $name => $value) {
					$sLineCopy = str_replace("{".$name."}", $value, $sLineCopy);
				}
				
				// Replace the tags.
				foreach($this->m_tags as $name => $value) {
					$sLineCopy = str_replace("<!-- TAG ".$name." -->", $value, $sLineCopy);
				}
				
				// Replace all namespaces 
				foreach(self::$m_namespaces as $key => $value) {
					$matches = array();
					preg_match_all("/{".$key."\.([0-9a-zA-Z\-_]+)}/", $sLineCopy, $matches);
	
					foreach($matches[1] as $match) {
						if(isset($value[$match])) {
							$sLineCopy = str_replace("{".$key.".".$match."}", 
								$value[$match], $sLineCopy);
						}
					}
				}
				
				$content .= $sLineCopy;
			}

			fclose($hFile);
		} else {
			die(__FILE__ . " : Cannot open " . $fPath );
		}
		
		// Replace blocks
		foreach($this->m_blocks as $block => $output) {
			// Replace all namespaces 
			foreach(self::$m_namespaces as $key => $value) {
				$matches = array();
				preg_match_all("/{".$key."\.([0-9a-zA-Z\-_]+)}/", $output, $matches);
	
				foreach($matches[1] as $match) {
					if(isset($value[$match])) {
						$output= str_replace("{".$key.".".$match."}", 
							$value[$match], $output);
					}
				}
			}
			$content = preg_replace("/<!-- BLOCK $block -->(.*?)<!-- END BLOCK $block -->/s", $output, $content);
		}
		
		// Remove what should be removed: blocks, tags and comments.
		$content = preg_replace("/<!-- BLOCK(.*?)-->(.*?)<!-- END BLOCK(.*?)-->/s", "", $content);
		$content = preg_replace("/<!-- TAG(.*?)-->/s", "", $content);
		$content = preg_replace("/<!--\\/\\/(.*?)-->/s", "", $content);
		
		// Cache and empty buffers
		$this->m_rendered = $content;
		
		$this->m_vars = null;
		$this->m_tags = null;
		$this->m_blocks = null;
		
		return $content;
	}
	
	/**
	 * Add a namespace with values.
	 * 
	 * @param $str_name Namespace name
	 * @param $values Associative array of values.
	 */ 
	public static function addNamespace($str_name, array &$values) {
		if(strlen($str_name) < 1) {
			die(__METHOD__ . ": namespace name '".$str_name."' is either invalid or empty.");
		}
		
		self::$m_namespaces[$str_name] = $values;
	}
	
	/**
	 * Sets the base path for the templates. 
	 * 
	 * @param $str_basePath Base template path.
	 */
	public static function setBasePath($str_basePath)  {
		if(!is_string($str_basePath)) {
			die( __METHOD__ . " [". __LINE__ . ":] Base path is not a string");
		}
		
		self::$m_basePath = $str_basePath;
	}
}

?>
