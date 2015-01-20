function use_ftp(value) {
	if(value)
	{
		document.getElementById('ftp_user').style.display = '';
		document.getElementById('ftp_pass').style.display = '';
		document.getElementById('ftp_path').style.display = '';
	}
	else
	{
		document.getElementById('ftp_user').style.display = 'none';
		document.getElementById('ftp_pass').style.display = 'none';
		document.getElementById('ftp_path').style.display = 'none';
	}
}