<div class="nav-breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; 
	<a href="pm.php">{L.PM_Manager}</a> &raquo; 
	<b>{L.View_PM}</b>
</div>

<div class="panel bottom-border pm-view-panel">
	<div class="panel-header">
		{TITLE}
	</div>
	<div class="panel-body clearfix">
		<div class="user pull-left">
			<div class="user-display">
				<b>{AUTHOR_USERNAME}</b><br />
				<br />
				<!-- BLOCK avatar -->
				<img src="{AUTHOR_AVATAR_LOCATION}" /><br />
				<!-- END BLOCK avatar -->
				{AUTHOR_RANK}<br />
				<!-- BLOCK rank_image -->
				<img src="{AUTHOR_RANK_IMG}" alt="{AUTHOR_RANK}" title="{AUTHOR_RANK}" /><br />
				<!-- END BLOCK rank_image -->
				<br />
			</div>
			<div class="user-info">
				<!-- BLOCK author_standard -->
				{L.Posts}: {AUTHOR_POSTS}<br />
				{L.Date_Joined}: {AUTHOR_JOINED}<br />
				<br />
				<!-- END BLOCK author_standard -->
				<!-- BLOCK author_location -->
				{L.Location}: {AUTHOR_LOCATION}
				<!-- END BLOCK author_location -->
			</div>
		</div>
		<div class="pm-body-wrapper">
			<div class="date">
				{DATE}
			</div>
			<div class="body">
				{BODY}
			</div>
			<div class="signature">
				{AUTHOR_SIGNATURE}
			</div>
			<div class="buttons clearfix">
				<div class="pull-right">
					<a href="pm.php?func=send&username={AUTHOR_USERNAME}">
						<img src="{T.TEMPLATE_PATH}/images/reply.gif" />
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
