<form method="post" action="{URL}" class="panel login-panel">
{CSRF_TOKEN}
	<div class="panel-header">
		{TITLE}
	</div>
	<div class="form-row" style="text-align:center;">
		{MESSAGE}<br /><br />
		<!-- BLOCK hidden_row -->
		<input type="hidden" name="{NAME}" value="{VALUE}" />
		<!-- END BLOCK hidden_row -->
	</div>
	<div class="panel-footer">
		<input type="submit" name="confirm" value="{L.Yes}" />&nbsp;&nbsp;<input type="button" name="confirm" value="{L.No}" onclick="window.location.href='{NO_URL}'" />
	</div>
</form>
