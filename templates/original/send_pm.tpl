<div class="nav-breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; 
	<a href="pm.php">{L.PM_Manager}</a> &raquo; 
	<b>{ACTION}</b>
</div>
<div class="panel bottom-border pm-menu-panel">
	<div class="panel-header">
		{L.PM_Menu}
	</div>
	<div class="panel-body">
		<a href="pm.php">{L.Inbox}</a>
		<a href="pm.php?func=send">{L.Create}</a>
		<a href="pm.php?func=outbox">{L.Outbox}</a>
		<a href="pm.php?func=sentbox">{L.Sent_Box}</a>
	</div>
</div>
<!-- BLOCK error -->
<div class="panel bottom-border pm-error-panel">
	<div class="panel-header">
		{L.The_following_errors_occoured}:
	</div>
	<div class="panel-body">
		{ERRORS}
	</div>
</div>
<!-- END BLOCK error -->
<form method="post" action="" name="send_pm" class="panel pm-edit-panel">
	{CSRF_TOKEN}
	<div class="panel-header">
		{ACTION}
	</div>

	<!-- BLOCK username -->
	<div class="custom-row">
		<div class="label">
			{L.Username}
		</div>
		<div class="value">
			<input type="text" name="username" value="{USERNAME}" tabindex="1" class="value-edit" />
		</div>
		<div class="extra">
			{L.Action} : {L.PM} <input type="radio" name="action" value="pm" {PM_SELECTED}>
				{L.Email} <input type="radio" name="action" value="email" {EMAIL_SELECTED}>
		</div>
	</div>
	<!-- END BLOCK username -->

	<div class="custom-row">
		<div class="label">
			{L.Title}
		</div>
		<div class="value">
			<input type="text" name="title" value="{TITLE}" tabindex="2" class="value-edit"/>
		</div>
	</div>

	<div class="custom-row">
		<div class="label">
			{L.Message}
		</div>
		<div class="value">
			{BBCODE_EDITOR}
			<textarea id="pm_body" name="body" tabindex="3" class="value-edit">{BODY}</textarea>
		</div>
		<div class="extra">
			{SMILIE_PICKER}
		</div>
	</div>

	<script type="text/javascript">
		setSmilieTarget("pm_body");
		setBBCodeTarget("pm_body");
	</script>

	<div class="panel-footer">
		<input type="submit" name="Submit" value="{L.Submit}" />
		<input type="reset" value="{L.Reset}" />
	</div>

</form>
