<form method="post" action="">
<table width="100%" class="maintable">
 <tr>
  <th colspan="2">{ACTION} {L.Usergroup}</th>
 </tr>
 <!-- BEGIN error -->
 <tr>
  <td colspan="2" class="desc_row">{ERROR}</td>
 </tr>
 <!-- END error -->
 <tr>
  <td valign="top" align="right" class="cell2">{L.Name}</td><td><input type="text" name="name" value="{NAME}" style="width:100%" /></td>
 </tr>
 <tr>
  <td valign="top" align="right" class="cell1">
   {L.Description}</td><td><textarea name="desc" style="width:100%">{DESC}</textarea>
  </td>
 </tr>
 <tr>
  <th colspan="2"><input type="submit" name="submit" value="{L.Submit}" />  <input type="reset" value="{L.Reset}" /></th>
 </tr>
</table>
</form>
