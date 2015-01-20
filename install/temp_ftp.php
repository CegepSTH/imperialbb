<?php

/**********************************************************
*
*			classes/ftp.php
*
*	      ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

class ftp {
	var $conn_id;

	function ftp($user, $pass) {
		global $lang;

		$ftp_ip = gethostbyname("localhost");
		if(!$this->conn_id = ftp_connect($ftp_ip, 21, 5))
		{
			error_msg("Unable to connect to FTP server... Exiting");
		}
		if(!$login_result = ftp_login($this->conn_id, $user, $pass))
		{
			error_msg("Invalid FTP username or password... Exiting");
		}
	}

	function upload($orig, $dest, $type) {
		if (!ftp_put($this->conn_id, $dest, $orig, $type)) {
			error_msg("FTP upload has failed!... Exiting");
		}
	}

	function rename($orig, $new) {
		return ftp_rename($this->conn_id, $orig, $new);
	}

	function mk_dir($dir) {
		return ftp_mkdir($this->conn_id, $dir);
	}

	function chdir($dir) {		return ftp_chdir($this->conn_id, $dir);
	}

	function close() {
		@ftp_quit($this->conn_id);
	}
}

?>
