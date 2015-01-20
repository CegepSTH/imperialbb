<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <b>{L.Members_List}</b></td>
 </tr>
</table>
<table width="100%" class="maintable">
 <tr>
  <th height="25" colspan="5">
   {L.Members_List}
  </th>
 </tr>
 <tr>
  <td class="desc_row" height="25">{L.ID}</td><td class="desc_row">{L.Username}</td><td class="desc_row">{L.Communication}</td><td class="desc_row">{L.Posts}</td><td class="desc_row">{L.Date_Joined}</td>
 </tr>
 <!-- BEGIN member_row -->
 <tr>
  <td class="cell2" width="50">
   {ID}
  </td>
  <td class="cell1">
   <a href="profile.php?id={ID}">{USERNAME}</a>
  </td>
  <td class="cell2" width="200">
   <a href="pm.php?func=send&username={USER}">{L.PM}</a> -- <a href="pm.php?func=send&username={USER}&action=email">{L.Send_Email}</a>
  </td>
  <td class="cell1" width="75">
   {POSTS}
  </td>
  <td class="cell2" width="125">
   {DATE_JOINED}
  </td>
 </tr>
 <!-- END member_row -->
</table><br />
<table width="100%">
 <tr>
  <td align="left"><a href="index.php">{C.site_name}</a> &raquo; <b>{L.Members_List}</b></td>
  <td width="150" align="right">{PAGINATION}</td>
 </tr>
</table>
