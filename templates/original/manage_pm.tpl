<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <a href="pm.php">{L.PM_Manager}</a> &raquo; <b>{LOCATION}</b></td>
 </tr>
</table>
<table width=50% align="center" class="maintable">
 <tr>
  <th colspan="4" height="25">Menu</th>
 </tr>
 <tr>
  <td align="center" class="cell2"><a href="pm.php">{L.Inbox}</a></td>
  <td align="center" class="cell2"><a href="pm.php?func=send">{L.Create}</a></td>
  <td align="center" class="cell2"><a href="pm.php?func=outbox">{L.Outbox}</a></td>
  <td align="center" class="cell2"><a href="pm.php?func=sentbox">{L.Sent_Box}</a></td>
 </tr>
</table>
<br /><br />
<table width="100%" align="center" class="maintable">
 <tr>
  <th colspan="5" height="25">{LOCATION}</th>
 </tr>
 <!-- BEGIN SWITCH pm_titles -->
 <tr>
   <td class="desc_row" height="25" width="40"></td><td align="center"class="desc_row">{L.PM}</td><td align="center" class="desc_row" width="200">{L.Author}</td><td align="center" class="desc_row" width="200">{L.Date}</td><td class="desc_row" width="125"></td>
 </tr>
 <!-- SWITCH pm_titles -->
 <tr>
   <td colspan="5" height="50" class="cell2" align="center"><b>{No_PM}</b></td>
 </tr>
 <!-- END SWITCH pm_titles -->
 <!-- BEGIN pm_row -->
 <tr>
  <td class="cell2" align="center">
   <!-- BEGIN SWITCH unread -->
   <img src="{T.TEMPLATE_PATH}/images/new_general.gif" alt="{L.New_Posts}" width="21" height="20" />
   <!-- SWITCH unread -->
   <img src="{T.TEMPLATE_PATH}/images/general.gif" alt="{L.No_New_Posts}" width="21" height="20" />
   <!-- END SWITCH unread -->
  </td>
  <td class="cell1" height="40">
   <a href="pm.php?id={ID}">{NAME}</a><br />
  </td>
  <td align="center" class="cell2">
   {AUTHOR}
  </td>
  <td align="center" class="cell1">
   {DATE}
  </td>
  <td align="center" class="cell2">
  <!-- BEGIN pm_edit -->
  <input type="button" value="{L.Edit}" onclick="window.location = 'pm.php?func=edit&id={ID}'">
  <!-- END pm_edit -->
  <input type="button" value="{L.Delete}" onclick="window.location = 'pm.php?func=delete&id={ID}'">
 </td>
 </tr>
 <!-- END pm_row -->
</table><br />
<div align="right">{PAGINATION}</div>
