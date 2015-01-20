<table width="100%">
 <tr>
  <td align="left"><a href="index.php">{C.site_name}</a> &raquo; <a href="search.php">Search</a> &raquo; <b>{L.Search_Results}</b></td>
 </tr>
</table>
<table width="100%" align="center" class="maintable">
 <tr>
  <th width="45" height="25"></th><th>{L.Topic_Name}</th><th width="150">{L.Author}</th><th width="100">{L.Replies}</th><th width="100">{L.Views}</th><th width="175">{L.Last_Post}</th>
 </tr>
 <!-- BEGIN searchrow -->
 <tr>
  <td class="cell2" align="center">
  <!-- BEGIN SWITCH new_posts -->
  <img src="templates/original/images/new_posts.gif" alt="New Posts" width="26" height="25" />
  <!-- SWITCH new_posts -->
  <img src="templates/original/images/no_new_posts.gif" alt="No New Posts" width="26" height="25" />
  <!-- END SWITCH new_posts -->
  </td>
  <td class="cell1" style="padding-left:5px" height="35" onclick="location.href='view_topic.php?tid={TOPIC_ID}'" onmouseover="this.className='cell2'" onmouseout="this.className='cell1'"><a href="view_topic.php?tid={TOPIC_ID}">{TOPIC_NAME}</a><br /></td>
  <td class="cell2" align="center">{AUTHOR}</td>
  <td class="cell1" align="center">{REPLIES}</td>
  <td class="cell2" align="center">{VIEWS}</td>
  <td class="cell1" align="center">{LAST_POST}</td>
 </tr>
 <!-- END searchrow -->
</table>
     <br><br>
