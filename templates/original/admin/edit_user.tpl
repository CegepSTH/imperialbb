<!-- BEGIN error -->
<table width="100%" class="maintable">
 <tr>
  <th height="25">{L.The_following_errors_occoured}:</th>
 </tr>
 <tr>
  <td>
   {ERRORS}
  </td>
 </tr>
</table>
<br />
<!-- END error -->
<table width="100%" align="center" class="maintable">
<form method="post" action="?act=users&func=edit&user_id={USER_ID}">
 <tr>
  <th colspan="2" height="25">
   {L.General_Preferences}
  </th>
 </tr>
 <tr>
  <td class="cell2">{L.Username}</td><td class="cell1"><input type="text" name="Username" value="{USERNAME}" style="width:100%;" /></td>
 </tr>
 <tr>
  <td class="cell2">{L.Email_Address}</td><td class="cell1"><input type="text" name="Email" value="{EMAIL}" style="width:100%;" /></td>
 </tr>
 <tr>
  <td class="cell2">{L.Signature}</td><td class="cell1"><textarea name="signature" rows="5" style="width:100%;">{SIGNATURE}</textarea></td>
 </tr>
 <tr>
  <th colspan="2" height="25">{L.IM_Setup}</th>
 </tr>
 <tr>
  <td class="cell2">{L.AIM}</td><td class="cell1"><input type="text" name="aim" style="width:100%;" value="{AIM}" /></td>
 </tr>
 <tr>
  <td class="cell2">{L.ICQ}</td><td class="cell1"><input type="text" name="icq" style="width:100%;" value="{ICQ}" /></td>
 </tr>
 <tr>
  <td class="cell2">{L.MSN}</td><td class="cell1"><input type="text" name="msn" style="width:100%;" value="{MSN}" /></td>
 </tr>
 <tr>
  <td class="cell2">{L.Yahoo}</td><td class="cell1"><input type="text" name="yahoo" style="width:100%;" value="{YAHOO}" /></td>
 </tr>
 <tr>
  <th colspan="2" height="25">
   {L.Permission_Options}
  </th>
 </tr>
 <tr>
  <td class="cell2">{L.Usergroup}</td>
  <td class="cell1">
   <select name="usergroup">
    <option value="0">None</option>
    <!-- BEGIN usergroup_option -->
    <option value="{UG_ID}" {UG_SELECTED}>{UG_NAME}</option>
    <!-- END usergroup_option -->
   </select>
  </td>
 </tr>
 <tr>
  <td class="cell2">{L.Rank}</td>
  <td class="cell1">
   <select name="rank">
    <!-- BEGIN rank_option -->
    <option value="{RANK_ID}" {RANK_SELECTED}>{RANK_NAME}</option>
    <!-- END rank_option -->
   </select>
  </td>
 </tr>
 <tr>
  <td class="cell2">{L.User_Level}</td>
  <td class="cell1">
   <select name="user_level">
    <!-- BEGIN user_level_option -->
    <option value="{UL_ID}" {UL_SELECTED}>{UL_NAME}</option>
    <!-- END user_level_option -->
   </select>
  </td>
 </tr>
 <tr>
  <th colspan="2" height="25">
   {L.Change_Password} ({L.Only_if_changing_password})
  </th>
 </tr>
 <tr>
  <td class="cell2">{L.Password}</td><td class="cell1"><input type="password" name="PassWord" style="width:100%;" /></td>
 </tr>
 <tr>
  <td class="cell2">{L.Retype_Password}</td><td class="cell1"><input type="password" name="Pass2" style="width:100%;" /></td>
 </tr>
 <tr>
  <th colspan="2" height="25">
   <input type="submit" name="Submit" value="{L.Submit}" /> <input type="reset" value="{L.Reset}" />
  </th>
  </form>
 </tr>
</table>