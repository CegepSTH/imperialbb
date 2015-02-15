<div class="nav-breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <b>{L.Members_List}</b>
</div>
<div class="panel members-panel">
	<div class="panel-header alt">
		{L.Members_List}
	</div>
	<table>
		<tr>
			<th class="user-id">{L.ID}</th>
			<th class="user-name">{L.Username}</th>
			<th class="user-contact">{L.Communication}</th>
			<th class="post-count">{L.Posts}</th>
			<th class="date-joined">{L.Date_Joined}</th>
		</tr>
		<!-- BLOCK member -->
		<tr>
			<td class="user-id">{ID}</td>
			<td class="user-name">
				<a href="profile.php?id={ID}">{USERNAME}</a>
			</td>
			<td class="user-contact">
				<a href="pm.php?func=send&username={USER}">{L.PM}</a>
				-- 
				<a href="pm.php?func=send&username={USER}&action=email">{L.Send_Email}</a>
			</td>
			<td class="post-count">{POSTS}</td>
			<td class="date-joined">{DATE_JOINED}</td>
		</tr>
		<!-- END BLOCK member -->
	</table>
</div>
<div class="clearfix members-footer">
	<div class="pull-left">
		<a href="index.php">{C.site_name}</a> &raquo; <b>{L.Members_List}</b>
	</div>
	<div class="pull-right">{PAGINATION}</div>
</div>
