<?php

/**********************************************************
*
*			login.php
*
*	      ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

if(!defined("IN_IBB")) {
	die("Hacking attempt");
}

class tar {

	//
	// Variable Declarations
	//
	var $current_dir = "";
	var $attributes = array();
	var $tar_path = "";
	var $tar_name = "";
	var $tar_filename = "";
	var $tar_unpack_header = 'a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/a8chksum/a1typeflag/a100linkname/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor/a155/prefix';
	var $tar_pack_header   = 'A100 A8 A8 A8 A12 A12 A8 A1 A100 A6 A2 A32 A32 A8 A8 A155';

	function tar() {

		//
		// Get the current directory
		//
		if(function_exists("getcwd")) {
			$this->current_dir = getcwd();
		} else {
			$this->current_dir = "./";
		}

		//
		// Set basic attributes
		//
		$this->attributes = array('over_write_existing'	=> 0,
						    'over_write_newer'      	=> 0,
							'rm_tar'       				=> 0,
							'rm_original' 				=> 0,
		);
	}

	function tar_file($tar_path, $tar_name) {
		$this->tar_path = $tar_path;
		$this->tar_name = $tar_name;

		//
		// Check for trailing forward / backslash
		//
		$this->tar_path = preg_replace("#[\\\\/]$#", "", $this->tar_path);

		//
		// Construct the filename
		//
		$this->tar_filename = $this->tar_path . "/" . $this->tar_name;
	}

	//
	// Change the current dir
	//
	function current_dir($current_dir = "") {

		$this->current_dir = $current_dir;

	}

	//
	// Change Attributes (Check for 0/1 first)
	//
	function over_write_existing($value) {
		if(preg_match("#^[0|1]$#", $value))
			$this->attributes['over_write_existing'] = $value;
	}
	function over_write_newer($value) {
		if(preg_match("#^[0|1]$#", $value))
			$this->attributes['over_write_newer'] = $value;
	}
	function rm_tar($value) {
		if(preg_match("#^[0|1]$#", $value))
			$this->attributes['rm_tar'] = $value;
	}
	function rm_original($value) {
		if(preg_match("#^[0|1]$#", $value))
			$this->attributes['rm_original'] = $value;
	}

	//
	// Main Functions: EXTRACTION
	//
	//
	function extract_files( $to_dir, $files="" ) {

		//
		// Create the directory if it doesnt exist
		//
		if (! is_dir($to_dir) )
		{
			mkdir($to_dir, 0775);
		}

		//
		// change into the directory chosen by the user.
		//

		chdir($to_dir);
		$cur_dir = getcwd();

		$to_dir_slash = $to_dir . "/";

		//
		// Get the file info from the tar
		//

		$in_files = $this->read_tar();

		if ($this->error != "") {
			return;
		}

		foreach ($in_files as $k => $file) {

			//
			// Are we choosing which files to extract?
			//

			if (is_array($files))
			{
				if (! in_array($file['name'], $files) )
				{
					continue;
				}
			}

			chdir($cur_dir);

			//
			// GNU TAR format dictates that all paths *must* be in the *nix
			// format - if this is not the case, blame the tar vendor, not me!
			//

			if ( preg_match("#/#", $file['name']) )
			{
				$path_info = explode( "/" , $file['name'] );
				$file_name = array_pop($path_info);
			} else
			{
				$path_info = array();
				$file_name = $file['name'];
			}

			//
			// If we have a path, then we must build the directory tree
			//


			if (count($path_info) > 0)
			{
				foreach($path_info as $dir_component)
				{
					if ($dir_component == "")
					{
						continue;
					}
					if ( (file_exists($dir_component)) && (! is_dir($dir_component)) )
					{
						$this->warnings[] = "WARNING: $dir_component exists, but is not a directory";
						continue;
					}
					if (! is_dir($dir_component))
					{
						mkdir( $dir_component, 0777);
						chmod( $dir_component, 0777);
					}

					if (! @chdir($dir_component))
					{
						$this->warnings[] = "ERROR: CHDIR to $dir_component FAILED!";
					}
				}
			}

			//
			// check the typeflags, and work accordingly
			//

			if (($file['typeflag'] == 0) or (!$file['typeflag']) or ($file['typeflag'] == ""))
			{
				if ( $FH = fopen($file_name, "wb") )
				{
					fputs( $FH, $file['data'], strlen($file['data']) );
					fclose($FH);
				}
				else
				{
					$this->warnings[] = "Could not write data to $file_name";
				}
			}
			else if ($file['typeflag'] == 5)
			{
				if ( (file_exists($file_name)) && (! is_dir($file_name)) )
				{
					$this->warnings[] = "$file_name exists, but is not a directory";
					continue;
				}
				if (! is_dir($file_name))
				{
					@mkdir( $file_name, 0777);
				}
			}
			else if ($file['typeflag'] == 6)
			{
				$this->warnings[] = "Cannot handle named pipes";
				continue;
			}
			else if ($file['typeflag'] == 1)
			{
				$this->warnings[] = "Cannot handle system links";
			}
			else if ($file['typeflag'] == 4)
			{
				$this->warnings[] = "Cannot handle device files";
			}
			else if ($file['typeflag'] == 3)
			{
				$this->warnings[] = "Cannot handle device files";
			}
			else
			{
				$this->warnings[] = "Unknown typeflag found";
			}

			if (! @chmod( $file_name, $file['mode'] ) )
			{
				$this->warnings[] = "ERROR: CHMOD $mode on $file_name FAILED!";
			}

			@touch( $file_name, $file['mtime'] );

		}

		// Return to the "real" directory the scripts are in

		@chdir($this->current_dir);

	}

	function read_tar() {

		$filename = $this->tar_filename;

		if ($filename == "") {
			error_msg($lang['Error'], "No filename specified when attempting to read a tar file");
			return array();
		}

		if (! file_exists($filename) ) {
			error_msg($lang['Error'], "Cannot locate the file ".$filename);
			return array();
		}

		$tar_info = array();

		$this->tar_filename = $filename;

		// Open up the tar file and start the loop

		if (! $FH = fopen( $filename , 'rb' ) ) {
			error_msg($lang['Error'], "Cannot open $filename for reading");
			return array();
		}

		$this->tar_unpack_header = preg_replace( "/\s/", "" , $this->tar_unpack_header);

		while (!feof($FH)) {

			$buffer = fread( $FH , '512' );

			// check the block

			$checksum = 0;

			for ($i = 0 ; $i < 148 ; $i++) {
				$checksum += ord( substr($buffer, $i, 1) );
			}
			for ($i = 148 ; $i < 156 ; $i++) {
				$checksum += ord(' ');
			}
			for ($i = 156 ; $i < 512 ; $i++) {
				$checksum += ord( substr($buffer, $i, 1) );
			}

			$fa = unpack( $this->tar_unpack_header, $buffer);

			$name     = trim($fa['filename']);
			$mode     = OctDec(trim($fa['mode']));
			$uid      = OctDec(trim($fa['uid']));
			$gid      = OctDec(trim($fa['gid']));
			$size     = OctDec(trim($fa['size']));
			$mtime    = OctDec(trim($fa['mtime']));
			$chksum   = OctDec(trim($fa['chksum']));
			$typeflag = trim($fa['typeflag']);
			$linkname = trim($fa['linkname']);
			$magic    = trim($fa['magic']);
			$version  = trim($fa['version']);
			$uname    = trim($fa['uname']);
			$gname    = trim($fa['gname']);
			$devmajor = OctDec(trim($fa['devmajor']));
			$devminor = OctDec(trim($fa['devminor']));
			$prefix   = (isset($fa['prefix'])) ? trim($fa['prefix']) : "";

			if ( ($checksum == 256) && ($chksum == 0) ) {
				//EOF!
				break;
			}

			if ($prefix) {
				$name = $prefix.'/'.$name;
			}

			// Some broken tars don't set the type flag
			// correctly for directories, so we assume that
			// if it ends in / it's a directory...

			if ( (preg_match( "#/$#" , $name)) and (! $name) ) {
				$typeflag = 5;
			}

			// If it's the end of the tarball...
			$test = $this->internal_build_string( '\0' , 512 );
			if ($buffer == $test) {
				break;
			}

			// Read the next chunk

			$data = fread( $FH, $size );

			if (strlen($data) != $size) {
				error_msg($lang['Error'], "Read error on tar file");
				fclose( $FH );
				return array();
			}

			$diff = $size % 512;

			if ($diff != 0) {
				// Padding, throw away
				$crap = fread( $FH, (512-$diff) );
			}

			// Protect against tarfiles with garbage at the end

			if ($name == "") {
				break;
			}

			$tar_info[] = array (
								  'name'     => $name,
								  'mode'     => $mode,
								  'uid'      => $uid,
								  'gid'      => $gid,
								  'size'     => $size,
								  'mtime'    => $mtime,
								  'chksum'   => $chksum,
								  'typeflag' => $typeflag,
								  'linkname' => $linkname,
								  'magic'    => $magic,
								  'version'  => $version,
								  'uname'    => $uname,
								  'gname'    => $gname,
								  'devmajor' => $devmajor,
								  'devminor' => $devminor,
								  'prefix'   => $prefix,
								  'data'     => $data
								 );
		}

		fclose($FH);

		return $tar_info;
	}

	//
	// Main Functions: CREATION
	//
	function add_directory( $dir ) {


		if (! is_dir($dir) )
		{
			error_msg($lang['Error'], "Failed adding directory, folder ($dir) does not exist");
			return FALSE;
		}

		$cur_dir = getcwd();
		chdir($dir);

		$this->get_dir_contents("./");

		$this->add_files($this->workfiles, $dir);

		chdir($cur_dir);

	}

	function add_files( $files, $root_path="" )
	{
		// Do we a root path to change into?

		if ($root_path != "")
		{
			chdir($root_path);
		}

		$count    = 0;

		foreach ($files as $file)
		{
			// is it a Mac OS X work file?

			if ( preg_match("/\.ds_store/i", $file ) )
			{
				continue;
			}

			$typeflag = 0;
			$data     = "";
			$linkname = "";

			$stat = stat($file);

			// Did stat fail?

			if (! is_array($stat) )
			{
				$this->warnings[] = "Error: Stat failed on $file";
				continue;
			}

			$mode  = fileperms($file);
			$uid   = $stat[4];
			$gid   = $stat[5];
			$rdev  = $stat[6];
			$size  = filesize($file);
			$mtime = filemtime($file);

			if (is_file($file))
			{
				// It's a plain file, so lets suck it up

				$typeflag = 0;

				if ( $FH = fopen($file, 'rb') )
				{
					$data = fread( $FH, filesize($file) );
					fclose($FH);
				}
				else
				{
					$this->warnings[] = "ERROR: Failed to open $file";
					continue;
				}
			}
			else if (is_link($file))
			{
				$typeflag = 1;
				$linkname = @readlink($file);
			}
			else if (is_dir($file))
			{
				$typeflag = 5;
			}
			else
			{
				// Sockets, Pipes and char/block specials are not
				// supported, so - lets use a silly value to keep the
				// tar ball legitimate.
				$typeflag = 9;
			}

			// Add this data to our in memory tar file

			$this->tar_in_mem[] = array (
										  'name'     => $file,
										  'mode'     => $mode,
										  'uid'      => $uid,
										  'gid'      => $gid,
										  'size'     => strlen($data),
										  'mtime'    => $mtime,
										  'chksum'   => "      ",
										  'typeflag' => $typeflag,
										  'linkname' => $linkname,
										  'magic'    => "ustar\0",
										  'version'  => '00',
										  'uname'    => 'unknown',
										  'gname'    => 'unknown',
										  'devmajor' => "",
										  'devminor' => "",
										  'prefix'   => "",
										  'data'     => $data
										);
			// Clear the stat cache

			@clearstatcache();

			$count++;
		}

		@chdir($this->current_dir);

		//Return the number of files to anyone who's interested

		return $count;

	}

	function get_dir_contents( $dir )
	{

		$dir = preg_replace( "#/$#", "", $dir );

		if ( file_exists($dir) )
		{
			if ( is_dir($dir) )
			{
				$handle = opendir($dir);

				while (($filename = readdir($handle)) !== false)
				{
					if (($filename != ".") && ($filename != ".."))
					{
						if (is_dir($dir."/".$filename))
						{
							$this->get_dir_contents($dir."/".$filename);
						}
						else
						{
							$this->workfiles[] = $dir."/".$filename;
						}
					}
				}

				closedir($handle);
			}
			else
			{
				error_msg($lang['Error'], "$dir is not a directory");
				return FALSE;
			}
		}
		else
		{
			error_msg($lang['Error'], "Could not locate $dir");
			return;
		}
	}

	function write_tar() {

		if ($this->tar_filename == "") {
			error_msg($lang['Error'], "No filename or path was specified to create a new tar file");
			return;
		}

		if ( count($this->tar_in_mem) < 1 ) {
			error_msg($lang['Error'], "No data to write to the new tar file";
			return;
		}

		$tardata = "";

		foreach ($this->tar_in_mem as $file) {

			$prefix = "";
			$tmp    = "";
			$last   = "";

			// make sure the filename isn't longer than 99 characters.

			if (strlen($file['name']) > 99)
			{
				$pos = strrpos( $file['name'], "/" );

				if (is_string($pos) && !$pos)
				{
					// filename alone is longer than 99 characters!
					error_msg($lang['Error'], "Filename ( . "$file['name']" . ) exceeds the length allowed by Archives";
					continue;
				}

				$prefix = substr( $file['name'], 0 , $pos );  // Move the path to the prefix
				$file['name'] = substr( $file['name'], ($pos+1));

				if (strlen($prefix) > 154)
				{
					error_msg($lang['Error'], "File path exceeds the length allowed by Archives");
					continue;
				}
			}

			// BEGIN FORMATTING (a8a1a100)

			$mode  = sprintf("%6s ", decoct($file['mode']));
			$uid   = sprintf("%6s ", decoct($file['uid']));
			$gid   = sprintf("%6s ", decoct($file['gid']));
			$size  = sprintf("%11s ", decoct($file['size']));
			$mtime = sprintf("%11s ", decoct($file['mtime']));

			$tmp  = pack("a100a8a8a8a12a12",$file['name'],$mode,$uid,$gid,$size,$mtime);

			$last  = pack("a1"   , $file['typeflag']);
			$last .= pack("a100" , $file['linkname']);

			$last .= pack("a6", "ustar"); // magic
			$last .= pack("a2", "" ); // version
			$last .= pack("a32", $file['uname']);
			$last .= pack("a32", $file['gname']);
			$last .= pack("a8", ""); // devmajor
			$last .= pack("a8", ""); // devminor
			$last .= pack("a155", $prefix);
			//$last .= pack("a12", "");
			$test_len = $tmp . $last . "12345678";
			$last .= $this->internal_build_string( "\0" , ('512' - strlen($test_len)) );

			// Here comes the science bit, handling
			// the checksum.

			$checksum = 0;

			for ($i = 0 ; $i < 148 ; $i++ )
			{
				$checksum += ord( substr($tmp, $i, 1) );
			}

			for ($i = 148 ; $i < 156 ; $i++)
			{
				$checksum += ord(' ');
			}

			for ($i = 156, $j = 0 ; $i < 512 ; $i++, $j++)
			{
				$checksum += ord( substr($last, $j, 1) );
			}

			$checksum = sprintf( "%6s ", decoct($checksum) );

			$tmp .= pack("a8", $checksum);

			$tmp .= $last;

		   	$tmp .= $file['data'];

		   	// Tidy up this chunk to the power of 512

		   	if ($file['size'] > 0)
		   	{
		   		if ($file['size'] % 512 != 0)
		   		{
		   			$homer = $this->internal_build_string( "\0" , (512 - ($file['size'] % 512)) );
		   			$tmp .= $homer;
		   		}
		   	}

		   	$tardata .= $tmp;
		}

		// Add the footer

		$tardata .= pack( "a512", "" );

		// print it to the tar file

		$FH = fopen( $this->tar_filename, 'wb' );
		fputs( $FH, $tardata, strlen($tardata) );
		fclose($FH);

		@chmod( $this->tar_filename, 0777);

		// Done..
	}

	//
	// build_string: Builds a repititive string
	//

	function internal_build_string($string="", $times=0) {

		$return = "";
		for ($i=0 ; $i < $times ; ++$i ) {
			$return .= $string;
		}

		return $return;
	}

}

?>
