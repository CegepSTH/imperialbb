<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="?">{C.site_name}</a> &raquo; <b>{L.Forgotten_Password}</b></td>
 </tr>
</table>
<form method="post" action="">
{CSRF_TOKEN}
<table width="100%" class="maintable">
 <tr>
  <th colspan="2" height="25">{L.Forgotten_Password}</th>
 </tr>
 <tr>
  <td class="cell1" width="40%">{L.Username}</td>
  <td class="cell2"><input type="text" name="username"></td>
 </tr>
 <tr>
  <td class="cell1">{L.Email}</td>
  <td class="cell2"><input type="text" name="email"></td>
 </tr>
 <tr>
  <th colspan="2" height="25"><input type="Submit" name="Submit" value="{L.Submit}">&nbsp;&nbsp;<input type="reset" value="{L.Reset}">
 </tr>
</table>
</form>
