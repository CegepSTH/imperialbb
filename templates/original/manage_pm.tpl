<div class="nav-breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; 
	<a href="pm.php">{L.PM_Manager}</a> &raquo; 
	<b>{LOCATION}</b>
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
<div class="panel pm-list-panel">
	<div class="panel-header alt">
		{LOCATION}
	</div>

	{PM_PANEL_CONTENT}
	
	<!-- BLOCK no_pms -->
	<div class="no-pms">
		{NO_PM}
	</div>
	<!-- END BLOCK no_pms -->
	<!-- BLOCK pms_table -->
	<table>
		<tr>
			<th class="read-indicator"></th>
			<th class="title">{L.PM}</th>
			<th class="author">{L.Author}</th>
			<th class="date">{L.Date}</th>
			<th class="actions"></th>
		</tr>
		{PM_ROWS}
	</table>
	<!-- END BLOCK pms_table -->

	<!-- BLOCK unread_pm -->
	<img src="{T.TEMPLATE_PATH}/images/new_general.gif" alt="{L.New_Posts}" width="21" height="20" />
	<!-- END BLOCK unread_pm -->
	<!-- BLOCK read_pm -->
	<img src="{T.TEMPLATE_PATH}/images/general.gif" alt="{L.No_New_Posts}" width="21" height="20" />
	<!-- END BLOCK read_pm -->
	<!-- BLOCK edit_pm -->
	<input type="button" value="{L.Edit}" onclick="window.location = 'pm.php?func=edit&id={ID}'">
	<!-- END BLOCK edit_pm -->

	<!-- BLOCK pm_row -->
	<tr>
		<td class="read-indicator">
			{READ_INDICATOR}
		</td>
		<td class="title">
			<a href="pm.php?id={ID}">{NAME}</a>
		</td>
		<td class="author">
			{AUTHOR}
		</td>
		<td class="date">
			{DATE}
		</td>
		<td class="actions">
			{EDIT_BUTTON}
			<input type="button" value="{L.Delete}" onclick="window.location = 'pm.php?func=delete&id={ID}'">
		</td>
	</tr>
	<!-- END BLOCK pm_row -->
</div>

<div class="pm-footer clearfix">
	<div class="pull-right">{PAGINATION}</div>
</div>
