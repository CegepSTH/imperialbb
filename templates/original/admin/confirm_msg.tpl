<form method="post" action="{URL}">
<table width="75%" align="center" class="maintable">
 <tr>
  <th height="25">{TITLE}</th>
 </tr>
 <tr>
  <td align="center" class="cell1" valign="center" height="75">
   {MESSAGE}<br />
   <!-- BEGIN hidden_row -->
   <input type="hidden" name="{NAME}" value="{VALUE}" />
   <!-- END hidden_row -->
   <input type="submit" name="confirm" value="{L.Yes}" />&nbsp;&nbsp;<input type="button" name="confirm" value="{L.No}" onclick="window.location.href='{NO_URL}'" />
  </td>
 </tr>
</table>
</form>
