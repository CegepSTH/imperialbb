<div class="breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <b>{L.Forgotten_Password}</b>
</div>
<form method="post" action="" class="panel">
	{CSRF_TOKEN}
	<div class="panel-header">
		{L.Forgotten_Password}
	</div>
	<div class="form-row">
		<label for="UserName">{L.Username}</label>
		<input type="text" name="username" id="UserName" />
	</div>
	<div class="form-row">
		<label for="Email">{L.Email}</label>
		<input type="text" name="email" id="Email" />
	</div>
	<div class="panel-footer">
		<input type="Submit" name="Submit" value="{L.Login}" />&nbsp;&nbsp;
		<input type="reset" value="{L.Reset}" />
	</div>
</form>
