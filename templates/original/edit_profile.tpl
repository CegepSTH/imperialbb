<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <b>{L.Edit_Profile}</b></td>
 </tr>
</table>
<!-- BEGIN error -->
<table width="100%" class="maintable">
 <tr>
  <th>{L.Error}</th>
 <tr>
  <td class="cell2">
   {ERRORS}
  </td>
 </tr>
</table>
<br />
<!-- END error -->
<table width="100%" align="center" class="maintable">
<form method="post" action="" enctype="multipart/form-data">
{CSRF_TOKEN}
 <tr>
  <th colspan="2" height="25">
   {L.Email_Preferences}
  </th>
 </tr>
 <tr>
  <td class="cell2">{L.Email_Address}</td><td class="cell1"><input type="text" name="Email" value="{EMAIL}" style="width:98%;"></td>
 </tr>
 <tr>
  <td class="cell2">{L.Retype_Email_Address}</td><td class="cell1"><input type="text" name="Email2" value="{EMAIL2}" style="width:98%;"></td>
 </tr>
 <tr>
  <th colspan="2" height="25">{L.Change_Password}</th>
 </tr>
 <tr>
  <td colspan="2" class="desc_row" align="center"><i>{L.Only_fill_in_if_you_want_to_change_your_password}</i></td>
 </tr>
 <tr>
  <td class="cell2">{L.Old_Password}</td><td class="cell1"><input type="password" name="OldPass" style="width:98%;"></td>
 </tr>
 <tr>
  <td class="cell2">{L.New_Password}</td><td class="cell1"><input type="password" name="PassWord" style="width:98%;"></td>
 </tr>
 <tr>
  <td class="cell2">{L.New_Password} [{L.Retype}]</td><td class="cell1"><input type="password" name="Pass2" style="width:98%;"></td>
 </tr>
 <tr>
  <th colspan="2" height="25"></th>
 </tr>
 <tr>
  <td class="cell2">{L.Signature}</td><td class="cell1"><textarea name="signature" rows="5" style="width:98%;">{SIGNATURE}</textarea></td>
 </tr>
 <tr>
  <th colspan="2" height="25">{L.IM_Setup}</th>
 </tr>
 <tr>
  <td class="cell2">{L.AIM}</td><td class="cell1"><input type="text" name="aim" style="width:98%;" value="{AIM}"></td>
 </tr>
 <tr>
  <td class="cell2">{L.ICQ}</td><td class="cell1"><input type="text" name="icq" style="width:98%;" value="{ICQ}"></td>
 </tr>
 <tr>
  <td class="cell2">{L.MSN}</td><td class="cell1"><input type="text" name="msn" style="width:98%;" value="{MSN}"></td>
 </tr>
 <tr>
  <td class="cell2">{L.Yahoo}</td><td class="cell1"><input type="text" name="yahoo" style="width:98%;" value="{YAHOO}"></td>
 </tr>
 <tr>
  <th colspan="2" height="25">{L.Avatar_Preferences}</th>
 </tr>
 <tr>
  <td colspan="2" class="desc_row" align="center"><i>{L.Only_fill_in_if_you_want_to_change_your_avatar}</i></td>
 </tr>
 <tr>
  <td class="cell2">{L.Current_Avatar}</td><td class="cell1">
  	<!-- BEGIN SWITCH current_avatar -->
  	<img src="{AVATAR_LOCATION}" height="{AVATAR_HEIGHT}" width="{AVATAR_WIDTH}" /><br /><label for="Delete_Avatar">{L.Delete_Avatar} <input type="checkbox" name="Delete_Avatar" id="Delete_Avatar" /></label></td>
  	<!-- SWITCH current_avatar -->
  	{L.No_Current_Avatar_Msg}
  	<!-- END SWITCH current_avatar -->
 </tr>
  <tr>
  <td class="cell2">{L.Remote_Avatar}</td><td class="cell1"><input type="text" name="Remote_Avatar_URL" style="width:98%;" value="{REMOTE_AVATAR_URL}" /></td>
 </tr>
 <tr>
  <td class="cell2">{L.Upload_Avatar}</td><td class="cell1"><input type="file" name="Upload_Avatar" style="width:98%;" value="" /></td>
 </tr>
 <tr>
  <th colspan="2" height="25">{L.Other_Preferences}</th>
 </tr>
 <!-- BEGIN template_select -->
 <tr>
  <td class="cell2">{L.Template}</td>
  <td class="cell1">
   <select name="template">
   	<!-- BEGIN template_select_option -->
   	<option value="{TEMPLATE_ID}" {TEMPLATE_SELECTED}>{TEMPLATE_NAME}</option>
   	<!-- END template_select_option -->
   </select>
  </td>
 </tr>
 <!-- END template_select -->
 <!-- BEGIN language_select -->
 <tr>
  <td class="cell2">{L.Language}</td>
  <td class="cell1">
   <select name="language">
   	<!-- BEGIN language_select_option -->
   	<option value="{LANGUAGE_ID}" {LANGUAGE_SELECTED}>{LANGUAGE_NAME}</option>
   	<!-- END language_select_option -->
   </select>
  </td>
 </tr>
 <!-- END language_select -->
 <tr>
  <td class="cell2">{L.profile_birthday}</td>
  <td class="cell1">
		<table border="0" cellpadding="3" cellspacing="0">
			<tr>
				<td>
					<select name='month'>
						<option value='00'>{L.birthday_month}</option>
						{MONTH_OPTS}
					</select>
				</td>
				<td>
					<select name='day'>
						<option value='00'>{L.birthday_day}</option>
						{DAY_OPTS}
					</select>
				</td>
				<td>
					<select name='year'>
						<option value='0000'>{L.birthday_year}</option>
						{YEAR_OPTS}
					</select>
				</td>
			</tr>
		</table>
  </td>
 </tr>
 <tr>
  <td class="cell2">{L.Website}</td><td class="cell1"><input type="text" name="website" style="width:98%;" value="{WEBSITE}"></td>
 </tr>
  <tr>
  <td class="cell2">{L.Location}</td><td class="cell1"><input type="text" name="location" style="width:98%;" value="{LOCATION}"></td>
 </tr>
 <tr>
  <td class="cell2">{L.Email_On_PM}</td><td class="cell1">{L.True}<input type="radio" name="email_on_pm" value="1" {EOP_TRUE}>&nbsp;&nbsp;{L.False}<input type="radio" name="email_on_pm" value="0" {EOP_FALSE}></td>
 </tr>
 <tr>
  <th colspan="2" height="25">
   <input type="submit" name="Submit" value="{L.Submit}" />&nbsp;&nbsp;<input type="reset" value="{L.Reset}" />
  </th>
  </form>
 </tr>
</table>
