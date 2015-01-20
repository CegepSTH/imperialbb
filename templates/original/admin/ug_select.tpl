<table width="100%" align="center" class="maintable">
 <tr>
  <th height="25">
   {L.Usergroup_Permissions}
  </th>
 </tr>
 <form method="post" action="?act=usergroups&func=permissions">
 <tr>
  <td align="center" class="cell2">
   {L.Please_select_a_usergroup} :
   <select name="id">
   <!-- BEGIN ug_select -->
   <option value="{GROUP_ID}">{GROUP_NAME}</option>
   <!-- END ug_select -->
   </select>
  </td>
 </tr>
 <tr>
  <th height="25"><input type="submit" value="{L.Submit}" /></th>
 </tr>
</table>