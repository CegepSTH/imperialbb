<table width="80%" align="center" class="maintable">
 <tr>
  <th>
   {L.Delete_Category}
  </th>
 </tr>
 <tr>
  <form method="post" action="">
  <td align="center" height="75" class="cell1">
   {L.Select_a_category_to_move_forums_in_this_category_to}:<br /><br />
   <select name="move_to">
    <option value="0">{L.Delete_All_Forums}</option>
    <!-- BEGIN move_to_options -->
    <option value="{CAT_ID}">{CAT_NAME}</option>
    <!-- END move_to_options -->
   </select>
  </td>
 </tr>
 <tr>
  <th>
   <input type="submit" name="Submit" value="{L.Submit}">
  </th>
  </form>
 </tr>
</table>