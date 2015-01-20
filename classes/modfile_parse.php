<?php

/**********************************************************
*
*			classes/modfile_parse.php
*
*	      ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

class mod_installer {
        var $remote_location = '';
        var $modfile = '';
        var $filename = '';
        var $file_contents = '';
        var $search = '';
        var $ftp;

        function mod_installer($remote_location) {
                global $config;
                $this->ftp = new ftp($config['ftp_user'], base64_decode($config['ftp_pass']));
                $this->remote_location = $remote_location;
        }

        function load_modfile($modfile, $return_template_updates = 'false') {
                if(@fopen($this->remote_location . $modfile, 'r')) {
                        $file_array = file($this->remote_location . $modfile);
                        // Convert modfile to UNIX format.
                        foreach($file_array as $id => $data) {
    						if(substr($data, -2) == "\r\n") {
    							$file_array[$id]= substr($data, 0, (strlen($data) - 2)) . "\n";
        					}
        				}
        				$this->modfile = $file_array;
        				unset($file_array);
                        return $this->parse_modfile($return_template_updates);
                } else {
                        return false;
                }
        }

        function parse_modfile($return_template_updates) {
                global $db, $db_prefix, $config;


                $tpl_folders = array();
                $query = $db->query("SELECT `template_folder` FROM `".$db_prefix."templates`");
                while($result = $db->fetch_array($query)) {
                        $tpl_folders[] = $result['template_folder'];
                }

                $commands = array();
                $current = 0;
                $current_file = "";
                $in_command = false;
                $in_db = false;
                $in_copy = false;
                $template_updates = array();
                $in_tpl_update = false;

                // Pass through for version
                foreach($this->modfile as $line) {
                	 if(preg_match("/# \[VERSION = '([A-Z-a-z0-9\.]+)'\]/", $line, $version)) {
                                if(isset($version[1])) {
                                	$version = $version[1];
                                	break;
                                }
                     }
                }

                // Pass through for database files && Version
                foreach($this->modfile as $line) {
                        if(preg_match("/^# \[DB FILES\]/", $line, $match)) {
                                $current++;
                                $in_db = true;
                        } else if($in_db) {
                                if(preg_match("/^#--/", $line)) {
                                        $in_db = false;
                                } else {
                                        echo "Running Database Updates :: $line<br />\n";
                                        flush();
                                        $this->run_db_file($line);
                                }
                        }
                }

                // Pass through for files to copy
                foreach($this->modfile as $line) {
                        if(preg_match("/^# \[COPY\]/", $line, $match)) {
                                $current++;
                                $in_copy = true;
                        } else if($in_copy) {
                                if(preg_match("/^#--/", $line)) {
                                        $in_copy = false;
                                } else {
                                        echo "Copying File :: $line<br />\n";
                                        flush();
                                        if($this->copy_files($line)) {
                                                echo "Error copying file $line, please report this in the imperialbb forum.<br />";
                                        }
                                }
                        }
                }

                // Pass through for file edits
                foreach($this->modfile as $line) {
                        if(preg_match("/^# \[FILE '([a-zA-Z0-9-_!£$%^&()\.\/\*]*)'\]/", $line, $match)) {
                                if(empty($current_file)) {
                                        $current_file = $match[1];
                                        if(preg_match("#^templates/#", $current_file)) {
                                                $in_tpl_updates = true;
                                                $template_updates[$current_file] = array();
                                        }
                                        if(!isset($commands[$current_file]) || empty($commands[$current_file])) {
                                                $commands[$current_file] = array();
                                        }
                                } else {
                                        echo "(".__FILE__.":".__LINE__.") Mod Parse Error: Already in file.";
                                }
                        } else if(preg_match("/^# \[END FILE\]/", $line, $match)) {
                                if(!empty($current_file)) {
                                        $current_file = "";
                                        $in_tpl_updates = false;
                                } else {
                                        echo "(".__FILE__.":".__LINE__.") Mod Parse Error: No active file initiated.";
                                }
                        } else if(!empty($current_file)) {
                                if($in_tpl_update) $template_updates[$current_file] .= $line;
                                if(preg_match("/^# \[([a-zA-Z0-9-_!£$%^&()\. ]*)\]/", $line, $match)) {
                                        $current++;
                                        $in_command = true;
                                        $commands[$current_file][$current][0] = $match[1];
                                        $commands[$current_file][$current][1] = array();
                                } else if(preg_match("/^#--/", $line)) {
                                        $in_command = false;
                                        $commands[$current_file][$current][1] = (count($commands[$current_file][$current][1]) <= 0) ?  "" : implode("", $commands[$current_file][$current][1]);
                                        if(substr($commands[$current_file][$current][1], -3) == "\r\n") {
                                        	$commands[$current_file][$current][1] = substr($commands[$current_file][$current][1], 0, -3);
                                		} else if(substr($commands[$current_file][$current][1], -1) == "\n") {
                                			$commands[$current_file][$current][1] = substr($commands[$current_file][$current][1], 0, -1);
                                		}
                                } else if($in_command) {
                                        $commands[$current_file][$current][1][] = $line;
                                }
                        }
                }
                foreach($commands as $filename => $command) {
                        $filenames = array();
                        if(preg_match("#^templates/\*/#", $filename)) {
                                foreach($tpl_folders as $tpl_folder) {
                                        $filenames[] = preg_replace("#^templates/\*/#", "templates/$tpl_folder/", $filename);
                                }
                        } else if(!ereg("/", $filename)) {
                        		if(substr($config['forum_root'], 0, 1) == "/")
                        			$config['forum_root'] = substr($config['forum_root'], 1);

                        		$filenames[] = $config['forum_root'] . $filename;
                        } else {
                                $filenames[] = $filename;
                        }
                        unset($filename);
                        foreach($filenames as $filename) {
                                echo "<br /><br />EDITING FILE :: $filename<br />\n";
                                flush();
                                if(!$this->begin_file($filename)) {
                                        echo "<b>Loading file $filename failed</b><br /><br />\n";
                                        flush();
                                } else {
                                        foreach($command as $cmd) {
                                                if(!$this->update_file($cmd[0], $cmd[1])) {
                                                        echo "<b>$cmd[0] FAILED</b><br />\n\n";
                                                        flush();
                                                }
                                        }
                                }
                                $this->end_file();
                        }
                }
                return ($return_template_updates) ? array($template_updates, $version) : $version;
        }

        function copy_files($filename) {
                global $config;
				$filename = trim($filename);
                if(@fopen($this->remote_location . "downloads/" . $filename . "s", 'r')) {
                        $this->filename = $filename;
                        $this->file_contents = file_get_contents($this->remote_location . "downloads/" . $filename . "s");
                        $this->end_file(false);
                } else {
                        return false;
                }
        }

        function begin_file($filename) {
                if(file_exists("../" . $filename)) {
                        if($file_array = file("../" . $filename)) {
                        	// Convert the file to UNIX format.. Why is windows so annoying?
    						foreach($file_array as $id => $data) {
    							if(substr($data, -2) == "\r\n") {
    								$file_array[$id]= substr($data, 0, (strlen($data) - 2)) . "\n";
        						}
        					}

    					}
    					$this->file_contents = implode("", $file_array);

    					$this->filename = $filename;
         				return true;
                } else {
                        return false;
                }
        }

        function update_file($command, $action) {
                if($command == "SEARCH") {
                        $action = eregi_replace('\\\\', '\\\\', $action);
                        $slash_array = array('#', '$', '(', ')', '[', ']', '.', '?', '*', '+', '|');
                        foreach($slash_array as $value) {
                                $action = eregi_replace("\\".$value, "\\".$value, $action);
                        }

                        if(preg_match("#$action#", $this->file_contents)) {
                                $this->search = $action;
                                return true;
                        } else {
								$this->failures_temp_search = $action;
                                return false;
                        }
                } else if($command == "REPLACE") {
                        if(empty($this->search)) {
                        		$this->failures_temp[$this->filename][] = array("search" => $this->failures_temp_search, "replace" => $action);
                                return false;
                        } else {
                                return $this->file_contents = preg_replace("#".$this->search."#", eregi_replace('\\\\', '\\\\', $action), $this->file_contents);
                        }
                } else if($command == "ADD AFTER") {
                        if(empty($this->search)) {
                        		$this->failures_temp[$this->filename][] = array("search" => $this->failures_temp_search, "addafter" => $action);
                                return false;
                        } else {
                                return $this->file_contents = preg_replace("#".$this->search."#", "\\0" . eregi_replace('\\\\', '\\\\', $action), $this->file_contents);
                        }
                } else if($command == "ADD BEFORE") {
                        if(empty($this->search)) {
                        		$this->failures_temp[$this->filename][] = array("search" => $this->failures_temp_search, "addbefore" => $action);
                                return false;
                        } else {
                                return $this->file_contents = preg_replace("#".$this->search."#", eregi_replace('\\\\', '\\\\', $action) . "\\0", $this->file_contents);
                        }
                }
        }

        function end_file($backup = true) {
                global $config;
                if(empty($this->filename)) {
                        return false;
                }

                if(is_array($this->failures_temp))
                {
                	$failures = '';

                	foreach($this->failures_temp as $file => $commands)
                	{
                		$failures .= "# [FILE '$file']\n\n";

                		foreach($commands as $command)
                		{
                			$failures .= "# [SEARCH]\n".$command['search']."\n#--\n\n";

                			if(isset($command['replace']))
                			{
                				$failures .= "# [REPLACE]\n".$command['replace']."\n#--\n\n";
                			}
                			else if(isset($command['addafter']))
                			{
                				$failures .= "# [ADD AFTER]\n".$command['addafter']."\n#--\n\n";
                			}
                			else if(isset($command['addbefore']))
                			{
                				$failures .= "# [ADD BEFORE]\n".$command['addbefore']."\n#--\n\n";
                			}
                		}
                		$failures .= "# [END FILE]\n\n\n";
                	}

                	$this->failures .= $failures;
                	$failures = '';
                	$this->failures_temp = '';
                }
                if($backup && file_exists("../" . $this->filename)) {
                        $this->ftp->rename($config['ftp_path'].$this->filename, $config['ftp_path'].$this->filename.".bak".time());
                }

                $tmpfname = @tempnam('/tmp', 'php');
                @unlink($tmpfname);
                $fp = @fopen($tmpfname, 'w');
                @fwrite($fp, $this->file_contents);
                @fclose($fp);
                $this->ftp->upload($tmpfname, $config['ftp_path'].$this->filename, FTP_ASCII);

                $this->filename = '';
                $this->file_content = '';
                $this->search = '';
        }

        function run_db_file($filename) {
                global $db,$db_prefix;
				$filename = trim($filename);
                if(@fopen($this->remote_location."db/mysql/".$filename, 'r')) {
                        $file_content = file($this->remote_location."db/mysql/".$filename);
                        $query = "";
                        foreach($file_content as $sql_line) {
                                $trimmed = trim($sql_line);
                                if (($sql_line != "") && ($sql_line != "\r\n") && (substr($trimmed, 0, 2) != "--") && (substr($trimmed, 0, 1) != "#")) {
                                        $query .= $sql_line;
                                        if(preg_match("/;\s*$/", $sql_line)) {
                                                if (!$db->query($query)) {
                                                        $db->sqlerror($query);
                                                        flush();
                                                }
                                                $query = "";
                                        }
                                }
                        }
                } else {
                        return false;
                }
        }

		function write_failures($filename)
		{
			global $config, $lang;

			if(!empty($this->failures))
			{
				echo "<br /><br /><b>Saving needed manual updates to '<a href=\"../$filename\" target=\"_blank\">/manual_updates_".$config['version']."</a>'...</b><br />";
 				$tmpfname = @tempnam('/tmp', 'php');
				@unlink($tmpfname);
				$fp = @fopen($tmpfname, 'w');
				@fwrite($fp, $this->failures);
				@fclose($fp);

 				$this->ftp->upload($tmpfname, $config['ftp_path'].$filename, FTP_BINARY);

 				echo "Manual Updates Needed: <br /><textarea cols=\"85\" rows=\"20\">".$this->failures."</textarea><br /><br /><br />";

 				error_msg($lang['Error'], $lang['Auto_Insaller_Failed_msg']);

			}
		}
}

?>
