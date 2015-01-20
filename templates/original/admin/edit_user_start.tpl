<script>
function select_user(username) {
        document.edit_user.username.value = username;
}
</script>
<table width="100%" align="center" class="maintable">
 <tr>
  <th height="25">
   {L.Edit_User}
  </th>
 </tr>
 <tr>
 <form method="post" action="" name="edit_user">
  <td align="center" class="cell2">
   {L.Enter_Username} : <input type="text" name="username">
   <select onchange="select_user(this.options[this.selectedIndex].value)">
    <option value="">{L.Select_A_Username}</option>
    <!-- BEGIN user_option -->
    <option value="{USERNAME}">{USERNAME}</option>
    <!-- END user_option -->
   </select>
  </td>
 </tr>
 <tr>
  <th height="25">
   <input type="submit" value="{L.Submit}" />
  </th>
 </form>
 </tr>
</table>
