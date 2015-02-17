<div class="breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <b>Login</b>
</div>
<form method="post" action="" class="panel login-panel">
	{CSRF_TOKEN}
	<div class="panel-header">
		{L.Registration}
	</div>	
	<div class="form-row">
		<label for="UserName">{L.Username}:</label>
		<input type="text" name="UserName" id="UserName" />
	</div>
	<div class="form-row">
		<label for="Password">{L.Password}:</label>
		<input type="password" name="PassWord" id="PassWord" />
	</div>
	<div class="form-row">
		<label for="Password">{L.Password} [{L.Retype}]:</label>
		<input type="password" name="Pass2" id="Pass2" />
	</div>
	<div class="form-row">
		<label for="Email">{L.Email_Address}:</label>
		<input type="text"  name="Email" id="PassWord" />
	</div>
	<div class="panel-footer">
		<input type="Submit" name="Submit" value="{L.Login}" />&nbsp;&nbsp;
		<input type="reset" value="{L.Reset}" />
	</div>
</form>
