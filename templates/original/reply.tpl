<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <a href="view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a> &raquo; <a href="view_topic.php?tid={TOPIC_ID}">{TOPIC_NAME}</a> &raquo; <b>{L.Reply}</b></td>
 </tr>
</table>
<!-- BEGIN error -->
<table width=70%>
 <tr>
  <th>{L.The_following_errors_occoured}:</th>
 </tr>
 <tr>
  <td>
   {ERRORS}
  </td>
 </tr>
</table>
<!-- END error -->
<form method="post" action="">
<table width="100%" align="center">
 <tr>
  <th>{L.Reply}</th>
 </tr>
 <tr>
  <td>
   <input type="text" name="title" value="{TITLE}" style="width:100%;" maxlength="75">
  </td>
 </tr>
 <tr>
  <td>
   <textarea name="body" rows="5" style="width:100%;">{BODY}</textarea>
  </td>
 </tr>
 <tr>
  <th>
   <input type="submit" name="Submit" value="{L.Submit}" />  <input type="reset" value="{L.Reset}">
  </th>
 </tr>
</table>
</form>
