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
	<div>
		<i>*ATTENTION: l'administrateur ne peux pas utiliser une adresse courriel comme gmail ou hotmail pour l'envoie de courriel : PHP ne fonctionne pas avec les serveur SMTP qui utilisent SSL.
		Il est toutefois possible sans problème d'utiliser une adresse courriel liée au domaine sur lequel le site est hébergé ex : admin@3am-eternal.org fonctionne sur le serveur où 3am-eternal.org est hébergé sans problème. </i>
	</div>
	
	<div class="panel-footer">
		<input type="submit" name="Submit" value="Submit" /><input type="reset" value="Reset" />
	</div>
</form>
