<table width="100%" class="maintable">
 <tr>
  <th height="25" colspan="2">{L.Mods}</th>
 </tr>
 <!-- BEGIN modrow_true -->
 <tr>
  <td class="cell2">
   <span style="font-weight:bold;">{NAME}</span><br />
   {DESC}
  </td>
  <td class="cell1" align="center">
   <input type="button" value="{L.Install}" onClick="window.location='?act=mods&func=install&id={ID}'" />
  </td>
 </tr>
 <!-- END modrow_true -->
 <!-- BEGIN modrow_false -->
 <tr>
  <td class="cell2">
   <span style="font-weight:bold;">{NAME}</span><br />
   {DESC}
  </td>
  <td class="cell1" align="center">
   <input type="button" value="{L.Upgrade}" onClick="window.location='?act=mods&func=upgrade&id={ID}'" />&nbsp;&nbsp;<input type="button" value="{L.Uninstall}" onClick="window.location='?act=mods&func=uninstall&id={ID}'" />
  </td>
 </tr>
 <!-- END modrow_false -->
</table>