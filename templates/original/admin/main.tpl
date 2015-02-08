<table width="100%" align="center" class="maintable">
 <tr>
  <th colspan="2" height="25">
   {L.Statistics}
  </th>
 </tr>
 <tr>
  <td align="center" class="cell2">
   {L.Total_Users} : {TOTAL_USERS}
  </td>
  <td align="center" class="cell2">
   {L.Users_Today} : {USERS_TODAY}
  </td>
 </tr>
 <tr>
  <td align="center" class="cell2">
   {L.Total_Posts} : {TOTAL_POSTS}
  </td>
  <td align="center" class="cell2">
   {L.Posts_Today} : {POSTS_TODAY}
  </td>
 </tr>
 <tr>
  <td align="center" class="cell2">
   {L.Total_Topics} : {TOTAL_TOPICS}
  </td>
  <td align="center" class="cell2">
   {L.Topics_Today} : {TOPICS_TODAY}
  </td>
 </tr>
</table><br />
<form method="post" action="main.php?func=update_notepad" style="margin: 0px; padding: 0px;">
	<table width="100%" class="maintable">
		<tr>
			<th style="height: 25px; text-align: left;"><!--<div style="float: right; padding-right: 4px;"><a href="javascript: void(0);" onclick="return ibb_resize_textarea(1, 'admincp_notepad')"><img src="{T.TEMPLATE_PATH}/images/plus.gif" id ="admincp_notepad" border="0" alt="{L.expand}" title="{L.expand}" /></a>&nbsp;<a href="javascript: void(0);" onclick="return ibb_resize_textarea(-1, 'admincp_notepad')"><img src="{T.TEMPLATE_PATH}/images/minus.gif" id ="admincp_notepad" border="0" alt="{L.collapse}" title="{L.collapse}" /></a></div>//--><strong style="padding-left: 4px;">{L.notepad_admincp}</strong></th>
		</tr>
		<tr>
			<td align="center" width="100%" class="cell2" valign="middle">
				<textarea name="admincp_notepad" id="admincp_notepad" rows="8" cols="98">{C.admincp_notepad}</textarea>
			</td>
		</tr>
		<tr>
			<th style="height: 25px;" align="center">
				<input type='submit' name='submit' value='{L.Submit}' />&nbsp;<input type='reset' name='reset' value='{L.Reset}' />
			</th>
		</tr>
	</table>
</form><br />
<table width="100%" class="maintable" align="center">
 <tr>
    <th height="25">{L.Installation_Status}</th>
 </tr>
 <!-- BLOCK install_warning -->
 <tr>
  <td class="cell2" align="center" style="color:red;">
   {L.Installer_presence_warning}
  </td>
 </tr>
 <!-- END BLOCK install_warning -->
</table>
