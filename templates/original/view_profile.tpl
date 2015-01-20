<table width="100%" style="margin: 6px 0px 6px 0px;">
	<tr>
		<td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <b>{L.View_Profile}</b></td>
	</tr>
</table>
<table class="maintable" style="width: 100%" align="center">
	<tr>
		<th style="height: 25px; padding-left: 4px; text-align: left;">{L.View_Profile} :: {USERNAME}</th>
	</tr>
	<tr>
		<td class="cell2" valign="top" style="width: 100%;">
			<table border="0" cellspacing="0" cellpadding="0" style="padding: 5px; width: 100%;">
				<tr>
					<td style="width: 75%;" valign="top">
						<strong class="largetext">{USERNAME}</strong><br />
						<strong class="smalltext">{L.Rank}:&nbsp;{RANK}</strong><br />
						<strong class="smalltext">{L.lastvisit}:&nbsp;{LASTVISIT}</strong><br />
						<!-- BEGIN SWITCH online -->{L.Online}<!-- SWITCH online -->{L.Offline}<!-- END SWITCH online -->
					</td>
					<td style="width: 25%; text-align: right;" valign="middle">
						<!-- BEGIN SWITCH avatar -->
						<img src="{AUTHOR_AVATAR_LOCATION}" alt="" border="0" />
						<!-- SWITCH avatar -->
						<img src="./images/avatars/blank_avatar.gif" alt="" border="0" />
						<!-- END SWITCH avatar -->
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table><br />
<table border="0" cellspacing="0" cellpadding="0" style="width: 100%" align="center">
	<tr>
		<td style="width: 49%;" valign="top">
			<table class="maintable" style="width: 100%" align="center">
				<tr>
					<th colspan="2" style="height: 25px; width: 100%;">{ABOUT_USER}</th>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.Date_Joined}</td>
					<td class="cell1" style="width: 60%;">{JOINED}</td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.Status}</td>
					<td class="cell1" style="width: 60%;"><!-- BEGIN SWITCH online -->{L.Online}<!-- SWITCH online -->{L.Offline}<!-- END SWITCH online --></td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.lastvisit}</td>
					<td class="cell1" style="width: 60%;">{LASTVISIT}</td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.Location}</td>
					<td class="cell1" style="width: 60%;">{LOCATION}</td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.Website}</td>
					<td class="cell1" style="width: 60%;">{WEBSITE}</td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.profile_birthday}</td>
					<td class="cell1" style="width: 60%;">{BIRTHDAY} {USER_AGE}</td>
				</tr>
			</table>
		</td>
		<td style="width: 2%;">&nbsp;</td>
		<td style="width: 49%;" valign="top">
			<table class="maintable" style="width: 100%" align="center">
				<tr>
					<th colspan="2" style="height: 25px; width: 100%;">{L.Communication}</th>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.AIM_Address}</td>
					<td class="cell1" style="width: 60%;">{AIM}</td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.ICQ_Number}</td>
					<td class="cell1" style="width: 60%;">{ICQ}</td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.MSN_Address}</td>
					<td class="cell1" style="width: 60%;">{MSN}</td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.Yahoo_Messenger}</td>
					<td class="cell1" style="width: 60%;">{YAHOO}</td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.PM}</td>
					<td class="cell1" style="width: 60%;"><a href="pm.php?func=send&username={USERNAME}">{L.PM}</a></td>
				</tr>
				<tr>
					<td class="cell2" style="width: 40%;">{L.Email}</td>
					<td class="cell1" style="width: 60%;"><a href="pm.php?func=send&action=email&username={USERNAME}">{L.Email}</a></td>
				</tr>
			</table>
		</td>
	</tr>
</table><br />
<table class="maintable" style="width: 100%" align="center">
	<tr>
		<th style="height: 25px; width: 100%;">{L.Signature}</th>
	</tr>
	<tr>
		<td class='cell2' valign='top' style="width: 100%;">
			<div style="padding: 6px;">{SIGNATURE}</div>
		</td>
	</tr>
</table>
