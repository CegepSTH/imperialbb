<table width="100%" class="maintable">
 <tr>
  <th height="25">{L.Name}</th><th>{L.Description}</th><th width="125"></th>
 </tr>
 <!-- BEGIN ug_row -->
 <tr>
  <td class="cell2">{NAME}</td>
  <td class="cell1">{DESC}</td>
  <td class="cell2" align="center"><input type="button" value="{L.Edit}" onclick="window.location='?act=usergroups&func=edit&id={ID}'" /><input type="button" value="{L.Delete}" onclick="window.location='?act=usergroups&func=delete&id={ID}'" /></td>
 </tr>
 <!-- END ug_row -->
</table>
<br />
<input type="button" value="{L.Create_New_Usergroup}" onclick="window.location='?act=usergroups&func=add'" />

