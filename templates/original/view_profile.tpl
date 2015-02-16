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
			{USER_ONLINE}
		</div>
		<div style="float:right;padding-right: 5px;padding-top:5px;">
			<!-- BLOCK avatar_on -->
			<img src="{AUTHOR_AVATAR_LOCATION}" alt="" border="0" />
			<!-- END BLOCK avatar_on -->
			<!-- BLOCK avatar_off -->
			<img src="./images/avatars/blank_avatar.gif" alt="" border="0" />
			<!-- END BLOCK avatar_off -->
		</div>
		<div style="clear:both;float:left; width:45%;padding-top:20px;">
			<div class="panel-header" style="background-color:#5CB8E6;">
				<span style="color:white;font-weight:bold;">{ABOUT_USER}</span>
			</div>
			<div style="clear:both;">
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.Date_Joined}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{JOINED}</span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.Status}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{USER_ONLINE}</span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.lastvisit}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{LASTVISIT}</span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.Location}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{LOCATION}</span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.Website}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{WEBSITE}</span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.profile_birthday}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{BIRTHDAY} {USER_AGE}</span><br />
			</div>
		</div>
		<div style="float:right;width:45%;padding-top:20px;">
			<div class="panel-header" style="background-color:#5CB8E6;">
				<span style="color:white;font-weight:bold;">{L.Communication}</span>
			</div>
			<div>
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.AIM_Address}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{AIM}</span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.ICQ_Number}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{ICQ}</span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.MSN_Address}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{MSN}</span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.Yahoo_Messenger}</strong></span><span style="display:inline-block;width:50%;text-align:right;">{YAHOO}</span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.PM}</strong></span><span style="display:inline-block;width:50%;text-align:right;"><a href="pm.php?func=send&username={USERNAME}">{L.PM}</a></span><br />
				<span style="margin-left:5px;margin-right:5px;display:inline-block;width:45%;"><strong>{L.Email}</strong></span><span style="display:inline-block;width:50%;text-align:right;"><a href="pm.php?func=send&action=email&username={USERNAME}">{L.Email}</a></span><br />
			</div>		
		</div>
	</div>
</div>

<div class="panel" style="clear:both;padding-top:20px;">
	<div class="panel-header" style="background-color:#5CB8E6;">
		<span style="color:white;font-weight:bold;">{L.Signature}</span>
	</div>
	<div style="padding: 6px;">{SIGNATURE}</div>
</div>
