<div class="breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <b>{L.View_Profile}</b>
</div>

<div class="panel">
	<div class="panel-header" style="background-color:#5CB8E6;">
		<span style="color:white;font-weight:bold;">{L.View_Profile} :: {USERNAME}</span>
	</div>
	<div>
		<div style="float: left;padding-left:5px;padding-top:5px;">
			<strong class="largetext">{USERNAME}</strong><br />
			<strong class="smalltext">{L.Rank}:&nbsp;{RANK}</strong><br />
			<strong class="smalltext">{L.lastvisit}:&nbsp;{LASTVISIT}</strong><br />
			<!-- BEGIN SWITCH online -->{L.Online}<!-- SWITCH online -->{L.Offline}<!-- END SWITCH online -->
		</div>
		<div style="float:right;padding-right: 5px;padding-top:5px;">
			<!-- BLOCK avatar_on -->
			<img src="{AUTHOR_AVATAR_LOCATION}" alt="" border="0" />
			<!-- END BLOCK avatar_on -->
			<!-- BLOCK avatar_off -->
			<img src="./images/avatars/blank_avatar.gif" alt="" border="0" />
			<!-- END BLOCK avatar_off -->
		</div>
		<div style="clear:both;float:left; width:45%;">
			<div class="panel-header" style="background-color:#5CB8E6;">
				<span style="color:white;font-weight:bold;">{ABOUT_USER}</span>
			</div>
			<div style="clear:both;">
				<span style="margin-right:5px;"><strong>{L.Date_Joined}</strong></span><span>{JOINED}</span><br />
				<span style="margin-right:5px;"><strong>{L.Status}</strong></span><span>{USER_ONLINE}</span><br />
				<span style="margin-right:5px;"><strong>{L.lastvisit}</strong></span><span>{LASTVISIT}</span><br />
				<span style="margin-right:5px;"><strong>{L.Location}</strong></span><span>{LOCATION}</span><br />
				<span style="margin-right:5px;"><strong>{L.Website}</strong></span><span>{WEBSITE}</span><br />
				<span style="margin-right:5px;"><strong>{L.profile_birthday}</strong></span><span>{BIRTHDAY} {USER_AGE}</span><br />
			</div>
		</div>
		<div style="float:right;width:45%;">
			<div class="panel-header" style="background-color:#5CB8E6;">
				<span style="color:white;font-weight:bold;">{L.Communication}</span>
			</div>
			<div>
				<span style="margin-right:5px;"><strong>{L.AIM_Address}</strong></span><span>{AIM}</span><br />
				<span style="margin-right:5px;"><strong>{L.ICQ_Number}</strong></span><span>{ICQ}</span><br />
				<span style="margin-right:5px;"><strong>{L.MSN_Address}</strong></span><span>{MSN}</span><br />
				<span style="margin-right:5px;"><strong>{L.Yahoo_Messenger}</strong></span><span>{YAHOO}</span><br />
				<span style="margin-right:5px;"><strong>{L.PM}</strong></span><span><a href="pm.php?func=send&username={USERNAME}">{L.PM}</a></span><br />
				<span style="margin-right:5px;"><strong>{L.Email}</strong></span><span><a href="pm.php?func=send&action=email&username={USERNAME}">{L.Email}</a></span><br />
			</div>		
		</div>
	</div>
</div>

<div class="panel" style="clear:both;">
	<div class="panel-header" style="background-color:#5CB8E6;">
		<span style="color:white;font-weight:bold;">{L.Signature}</span>
	</div>
	<div style="padding: 6px;">{SIGNATURE}</div>
</div>
