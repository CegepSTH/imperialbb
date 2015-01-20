<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="?">{C.site_name}</a> &raquo; <a href="?act=viewforum&fid={FORUM_ID}">{FORUM_NAME}</a> &raquo; <a href="?act=viewtopic&tid={TOPIC_ID}">{TOPIC_NAME}</a> &raquo; <b>{L.Move_Topic}</b></td>
 </tr>
</table>
<form method="post" action="">
<table width="100%" align="center" class="maintable">
 <tr>
  <th height="25">Move Topic '{TOPIC_NAME}'</th>
 </tr>
 <tr>
  <td class="cell2" align="center" height="75">
   {L.Select_a_forum_to_move_topic_to}<br />
   <select name="fid">
    <!-- BEGIN forumrow -->
    <option value="{FID}">{FNAME}</option>
    <!-- END forumrow -->
   </select>
  </td>
 </tr>
 <tr>
  <th height="25">
   <input type="submit" name="Submit" value="{L.Submit}">  <input type="button" value="{L.Cancel}" onclick="window.location.href='view_topic.php?tid={TOPIC_ID}'">
  </th>
 </tr>
</table>
</form>