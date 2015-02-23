<div class="breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <b>Login</b>
</div>
<form method="post" action="" class="panel login-panel">
	{CSRF_TOKEN}
	<div class="panel-header">
		{L.Login}
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
		<label for="KeepConnected">Keep connected:</label>
		<select name="KeepConnected" id="KeepConnected" class="value-input">
			<option value="{Constant.PLOGIN_DUR_SESSION}">No</option>
			<option value="{Constant.PLOGIN_DUR_DAY}">1 day</option>
			<option value="{Constant.PLOGIN_DUR_WEEK}">1 week</option>
			<option value="{Constant.PLOGIN_DUR_MONTH}">1 month</option>
			<option value="{Constant.PLOGIN_DUR_HALF_YEAR}">6 months</option>
			<option value="{Constant.PLOGIN_DUR_YEAR}">1 year</option>
			<option value="{Constant.PLOGIN_DUR_FOREVER}">Forever</option>
		</select>
	</div>
	<div class="links">
		[ <a href="register.php">{L.Register}</a> | <a href="login.php?func=forgotten_pass">{L.Forgotten_Password}</a> ]
	</div>
	<div class="panel-footer">
		<input type="Submit" name="Submit" value="{L.Login}" />&nbsp;&nbsp;
		<input type="reset" value="{L.Reset}" />
	</div>
</form>
