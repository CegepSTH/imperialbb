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

$language->add_file("ftp");

class ftp {
	var $conn_id;

	function ftp($user, $pass) {
		global $lang;

		$ftp_ip = gethostbyname("localhost");
		if(!$this->conn_id = ftp_connect($ftp_ip, 21, 30))
		{
			error_msg($lang['Error'], $lang['Unable_To_Connect_To_FTP_Server_Msg']);
		}
		if(!$login_result = ftp_login($this->conn_id, $user, $pass))
		{
			error_msg($lang['Error'], $lang['Invalid_FTP_User_Or_Pass']);
		}
	}

	function upload($orig, $dest, $type) {
		$upload = ftp_put($this->conn_id, $dest, $orig, $type);
		if (!$upload) {
			echo "FTP upload has failed!";
		}
	}

	function rename($orig, $new) {
		ftp_rename($this->conn_id, $orig, $new);
	}

	function mk_dir($dir) {
		ftp_mkdir($this->conn_id, $dir);
	}
	function close() {
		@ftp_quit($this->conn_id);
	}
}

?>
