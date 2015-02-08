<link rel="stylesheet" type="text/css" href="{T.TEMPLATE_PATH}/newstyle.css" />
<div class="table">
	<h4>{L.Statistics}</h4>
	<table>
		<tr>
			<td>{L.Total_Users} : {TOTAL_USERS}</td>
			<td>{L.Users_Today} : {USERS_TODAY}</td>
		</tr>
		<tr>
			<td>{L.Total_Posts} : {TOTAL_POSTS}</td>
			<td>{L.Posts_Today} : {POSTS_TODAY}</td>
		</tr>
		<tr>
			<td>{L.Total_Topics} : {TOTAL_TOPICS}</td>
			<td>{L.Topics_Today} : {TOPICS_TODAY}</td>
		</tr>
	</table>
</div>
<div class="table">
	<h4>{L.notepad_admincp}</h4>
	<form action="main.php?func=update_notepad" method="post">
		<p>
			<textarea rows="8" style="width: 98%;" id="message" name="admincp_notepad">{C.admincp_notepad}</textarea>
			<br />
		</p>
		<p>
			<input type="submit" name="submit" class="formbutton" value="{L.Submit}" />
			<input type="reset" name="reset" class="formbutton" value="{L.Reset}" />
		</p>
	</form>
</div>
<div class="table">
	<h4>{L.Installation_Status}</h4>
	<!-- BLOCK install_warning -->
	{L.Installer_presence_warning}
	<!-- END BLOCK install_warning -->
</div>
