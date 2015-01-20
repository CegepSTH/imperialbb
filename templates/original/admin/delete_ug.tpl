<form method="post" action="">
<table width="100%" class="maintable">
 <tr>
  <th>{L.Delete_Usergroup} '{UG_NAME}'</th>
 </tr>
 <tr>
  <td>{L.New_usergroup_for_existing_users_of_this_usergroup} : <br />
   <select name="new_ug">
    <option value="-1">{L.Remove_from_usergroup}</option>
    <optgroup label="New Usergroup">
     <!-- BEGIN ug_row -->
     <option value="{ID}">{NAME}</option>
     <!-- END ug_row -->
    </optgroup>
   </select>
  </td>
 </tr>
 <tr>
  <th><input type="submit" name="submit" value="{L.Submit}" /><input type="reset" value="{L.Reset}" /></th>
 </tr>
</table>
</form>
