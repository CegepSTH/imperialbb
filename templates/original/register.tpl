<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <b>{L.Register}</b></td>
 </tr>
</table>
<!-- BEGIN error -->
<table width=70%>
 <tr>
  <th>The following errors occoured:</th>
 </tr>
 <tr>
  <td>
   {ERRORS}
  </td>
 </tr>
</table>
<!-- END error -->
<form method="post" action="">
<table width="100%" class="maintable">
 <tr>
  <th colspan="2" height="25">{L.Registration}</th>
 </tr>
 <tr>
  <td class="cell1" width="50%">{L.Username}</td>
  <td class="cell2"><input type="text" name="UserName" value="{USERNAME}"></td>
 </tr>
 <tr>
  <td class="cell1">{L.Password}</td>
  <td class="cell2"><input type="password" name="Password"></td>
 </tr>
 <tr>
  <td class="cell1">{L.Password} [{L.Retype}]</td>
  <td class="cell2"><input type="password" name="Pass2"></td>
 </tr>
 <tr>
  <td class="cell1">{L.Email_Address}</td>
  <td class="cell2"><input type="text" name="Email" value="{EMAIL}"></td>
 </tr>
 <tr>
  <th colspan="2" height="30"><input type="submit" name="Submit" value="{L.Submit}" />  <input type="reset" name="Reset" value="{L.Reset}" /></th>
 </tr>
</table>
