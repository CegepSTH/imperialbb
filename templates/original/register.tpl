<div class="nav-breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <b>Login</b>
</div>
<!-- BLOCK error -->
<div class="panel bottom-border editor-error-panel">
	<div class="panel-header">
		{L.The_following_errors_occoured}:
	</div>
	<div class="panel-body">
		{ERRORS}
	</div>
</div>
<!-- END BLOCK error -->
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
		<input type="password" name="Password" id="Password" />
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
		<input type="Submit" name="Submit" value="{L.Submit}" />&nbsp;&nbsp;
		<input type="reset" value="{L.Reset}" />
	</div>
</form>
