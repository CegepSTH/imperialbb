<form method="post" action="">
	{CSRF_TOKEN}
	
	<div class="panel-header">
		Database Settings
	</div>
	
	<div class="form-row">
		<label for="UserName">Database Type : </label>
		<select name="dbtype"><option value="mysql">MySQL</option><option value="mysqli">MySQLi</option></select>
	</div>
	
	<div class="form-row">
		<label for="UserName">Database Host (Usually localhost) : </label>
		<input type="text" name="dbhost" value="localhost" size="65" />
	</div>
	
	<div class="form-row">
		<label for="UserName">Database Username : </label>
		<input type="text" name="dbuser" value="" size="65" />
	</div>
		
	<div class="form-row">
		<label for="UserName">Database Password : </label>
		<input type="password" name="dbpass" value="" size="65" />
	</div>
		
	<div class="form-row">
		<label for="UserName">Database Database Name : </label>
		<input type="text" name="dbname" value="" size="65" />
	</div>
		
	<div class="panel-header">
		FTP Settings
	</div>
		
	<div class="form-row">
		<label for="UserName">Use FTP (Recommended) : </label>
		<input type="radio" name="useftp" value="true" onclick="use_ftp(true);" id="useftp_true" CHECKED /><label for="useftp_true">True</label>  <input type="radio" name="useftp" value="false" onclick="use_ftp(false);" id="useftp_false" /><label for="useftp_false">False</label>
	</div>
		
	<div class="form-row" id="ftp_user">
		<label for="UserName">FTP Username : </label>
		<input type="text" name="ftpuser" value="" size="65" />
	</div>
	
	<div class="form-row" id="ftp_pass">
		<label for="UserName">FTP Password : </label>
		<input type="password" name="ftppass" value="" size="65" />
	</div>
	
	<div class="form-row" id="ftp_path">
		<label for="UserName">FTP Path (E.G. /public_html/forums/ ) : </label>
		<input type="text" name="ftppath" value="/" size="65" />
	</div>
	
	<div class="panel-header">
		General Settings
	</div>
	
	<div class="form-row">
		<label for="UserName">Forum Name : </label>
		<input type="text" name="forum_name" value="My Forum" size="65" />
	</div>
	
	<div class="form-row">
		<label for="UserName">Forum Description : </label>
		<input type="text" name="forum_desc" value="" size="65" />
	</div>
	
	<div class="form-row">
		<label for="UserName">Forum Path : </label>
		<input type="text" name="forum_path" value="{DIRECTORY}" size="65" />
	</div>
	
	<div class="panel-header">
		Administrator Settings
	</div>
	
	<div class="form-row">
		<label for="UserName">Admin Username : </label>
		<input type="text" name="admin_user" value="" size="65" />
	</div>
	
	<div class="form-row">
		<label for="UserName">Admin Password : </label>
		<input type="password" name="admin_pass" value="" size="65" />
	</div>
	
	<div class="form-row">
		<label for="UserName">Admin Email : </label>
		<input type="text" name="admin_email" value="" size="65" />
	</div>
	
	<div class="panel-footer">
		<input type="submit" name="Submit" value="Submit" /><input type="reset" value="Reset" />
	</div>
</form>
