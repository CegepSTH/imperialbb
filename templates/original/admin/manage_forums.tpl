<!-- BLOCK category -->
<br />
<table width="100%" class="maintable">
 <tr>
  <th colspan="3" height="25"><a href="../index.php?cid={CAT_ID}">{CAT_NAME}</a> - <a href="?act=forums&func=edit_category&cid={CAT_ID}">{L.Edit}</a> - <a href="?act=forums&func=delete_category&cid={CAT_ID}">{L.Delete}</a></th><th align="center" width="140"><a href="forums.php?move=up&cid={CAT_ID}">[{L.Up}]</a> <a href="forums.php?move=down&cid={CAT_ID}">[{L.Down}]</a></th>
 </tr>
{CAT_CONTENTS}
 <!-- BLOCK forums_table_header -->
 <tr>
   <td align="center" class="desc_row" height="25">{L.Forum}</td><td align="center" width="75" class="desc_row">{L.Topics}</td><td align="center" width="75" class="desc_row">{L.Posts}</td><td class="desc_row"></td>
 </tr>
 <!-- END BLOCK forums_table_header -->
 <!-- BLOCK no_forums_in_cat -->
 <tr>
   <td colspan="4" height="50" class="cell2" align="center" style="font-weight:bold;">{L.This_category_has_no_boards}</td>
 </tr>
 <!-- END BLOCK no_forums_in_cat -->
<!--// Keep the space at the end of the line of the block parent_forum. -->
<!-- BLOCK parent_forum -->
<a href="../view_forum.php?fid={SUBFORUM_ID}">{SUBFORUM_NAME}</a> &raquo; 
<!-- END BLOCK parent_forum -->
 <!-- BLOCK regular_forum -->
 <tr>
  <td class="cell2">
   {PARENT_FORUMS}<a href="../view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a><br />
   <i>{FORUM_DESCRIPTION}</i>
  </td>
  <td align="center" class="cell1">
   {TOPICS}
  </td>
  <td align="center" class="cell2">
   {POSTS}
  </td>
  <td align="center" valign="center" class="cell1">
   <input type="button" value="{L.Up}" onclick="window.location = 'forums.php?move=up&fid={FORUM_ID}'"> - <input type="button" value="{L.Down}" onclick="window.location = 'forums.php?move=down&fid={FORUM_ID}'" /><br />
   <input type="button" value="{L.Edit}" onclick="window.location = 'forums.php?func=edit_forum&fid={FORUM_ID}'"> - <input type="button" value="{L.Delete}" onclick="window.location = 'forums.php?func=delete_forum&fid={FORUM_ID}'" />
  </td>
 </tr>
 <!-- END BLOCK regular_forum -->
 <!-- BLOCK redirection_forum -->
 <tr>
  <td class="cell2">
   {PARENT_FORUMS}<a href="../view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a><br />
   <i>{FORUM_DESCRIPTION}</i>
  </td>
  <td colspan="2" align="center" class="cell1">
   {REDIRECTS}
  </td>
  <td align="center" valign="center" class="cell1">
   <input type="button" value="{L.Up}" onclick="window.location = 'forums.php?move=up&fid={FORUM_ID}'"> - <input type="button" value="{L.Down}" onclick="window.location = 'forums.php?move=down&fid={FORUM_ID}'" /><br />
   <input type="button" value="{L.Edit}" onclick="window.location = 'forums.php?func=edit_forum&fid={FORUM_ID}'"> - <input type="button" value="{L.Delete}" onclick="window.location = 'forums.php?func=delete_forum&fid={FORUM_ID}'" />
  </td>
 </tr>
 <!-- END BLOCK redirection_forum -->
 <tr>
  <form method="post" action="?act=forums&func=add_forum&cid={CAT_ID}">
   <th colspan="4" align="left" height="25">
    &nbsp;&nbsp;{L.Create_Forum} : <input type="text" name="name"><input type="submit" name="no_submit" value="{L.Submit}" />
   </th>
  </form>
 </tr>
</table>
<!-- END BLOCK category -->
<br />
<table width="100%" class="maintable">
 <tr>
  <form method="post" action="?act=forums&func=add_category">
   <th align="left" height="25">
    &nbsp;&nbsp;{L.Create_Category} : <input type="text" name="name"><input type="submit" name="Submit" value="{L.Submit}" />
   </th>
  </form>
 </tr>
</table>
