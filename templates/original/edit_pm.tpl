<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="?">{SITE_NAME}</a> &raquo; <b>Edit PM</b></td>
 </tr>
</table>
<table width=50% align="center">
 <tr>
  <th colspan="4">Menu</th>
 </tr>
 <tr>
  <td align="center"><a href="?act=pm">Inbox</a></td>
  <td align="center"><a href="?act=pm&func=send">Create</a></td>
  <td align="center"><a href="?act=pm&func=outbox">Outbox</a></td>
  <td align="center"><a href="?act=pm&func=sentbox">Sent Box</a></td>
 </tr>
</table>
<br /><br />
<!-- BEGIN error -->
<table width="90%" align="center">
 <tr>
  <th>{L.The_following_errors_occoured}:</th>
 </tr>
 <tr>
  <td style="padding:10;">
   {ERRORS}
  </td>
 </tr>
</table>
<!-- END error -->
<form method="post" action="">
{CSRF_TOKEN}
<table width="100%" align="center">
 <tr>
  <th>
   {L.Edit_PM}
  </th>
 </tr>
 <tr>
  <th></th>
 </tr>
 <tr>
  <td style="padding:10;">
   {L.Title} : <br /><input type="text" name="title" value="{TITLE}" style="width:100%;">
  </td>
 </tr>
 <tr>
  <td style="padding:10;">
   {L.Body} : <br /><textarea name="body" rows="5" style="width:100%;">{BODY}</textarea>
  </td>
 </tr>
 <tr>
  <th style="padding:5 0 5 0;">
   <input type="submit" name="Submit" value="{L.Submit}"><input type="reset" value="{L.Reset}">
  </th>
 </tr>
</table>
</form>
