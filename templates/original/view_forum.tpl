<!-- BLOCK SWITCH new_posts_on -->
<img src="{T.TEMPLATE_PATH}/images/new_posts.gif" alt="New Posts" width="26" height="25" />
<!-- END BLOCK new_posts_on -->
<!-- BLOCK new_posts_off -->
<img src="{T.TEMPLATE_PATH}/images/no_new_posts.gif" alt="No New Posts" width="26" height="25" />
<!-- END BLOCK new_posts_off -->

<!-- BLOCK prow_new_posts_on -->
<img src="{T.TEMPLATE_PATH}/images/new_{IMAGE_URL}.gif" alt="{IMAGE_TITLE_PREFIX}{L.New_Posts}" width="21" height="20" />
<!-- END BLOCK prow_new_posts_on -->
<!-- BLOCK prow_new_posts_off -->
<img src="{T.TEMPLATE_PATH}/images/{IMAGE_URL}.gif" alt="{IMAGE_TITLE_PREFIX}{L.No_New_Posts}" width="21" height="20" />
<!-- END BLOCK prow_new_posts_off -->

<!-- BLOCK subforums_list --><br />
<span style="font-size: 10px;">{SUBFORUMS}</span>
<!-- END BLOCK subforums_list -->

<!-- BLOCK last_post_on -->
<a href="view_topic.php?tid={SUBFORUM_LAST_POST_ID}">{SUBFORUM_LAST_POST_TITLE}</a><br />
{SUBFORUM_LAST_POST_DATE}<br />
{SUBFORUM_LAST_POST_AUTHOR}
<!-- END BLOCK last_post_on -->
<!-- BLOCK last_post_off -->
<b>{L.None}</b>
<!-- END BLOCK last_post_off -->

<!-- BLOCK forum_row_on -->
	<tr>
		<td class="cell2" height="45" width="45" align="center">
			{block_new_posts}
		</td>
		<td class="cell1" height="45" onclick="location.href='view_forum.php?fid={SUBFORUM_ID}'" onmouseover="this.className='cell2'" onmouseout="this.className='cell1'">
			<a href="view_forum.php?fid={SUBFORUM_ID}">{SUBFORUM_NAME}</a><br />
			<i>{SUBFORUM_DESCRIPTION}</i>
			{block_subforums_list}
		</td>
		<td width="50" align="center" class="cell2">{SUBFORUM_TOPICS}</td>
		<td width="50" align="center" class="cell1">{SUBFORUM_POSTS}</td>
		<td width="200" align="center" valign="middle" class="cell2">
			{block_last_post}
		</td>
	</tr>
<!-- END BLOCK forum_row_on -->

<!-- BLOCK forum_row_off -->
	<tr>
		<td class="cell2" height="45" width="45" align="center">
			<img src="{T.TEMPLATE_PATH}/images/no_new_posts.gif" alt="No New Posts" width="26" height="25" />
		</td>
		<td class="cell1" height="45" onclick="location.href='view_forum.php?fid={SUBFORUM_ID}'" onmouseover="this.className='cell2'" onmouseout="this.className='cell1'">
			<a href="view_forum.php?fid={SUBFORUM_ID}">{SUBFORUM_NAME}</a><br />
			<i>{SUBFORUM_DESCRIPTION}</i>
		</td>
		<td colspan="3" colspan="2" align="center" class="cell2">{SUBFORUM_REDIRECTS}</td>
	</tr>
<!-- END BLOCK forum_row_off -->
		
<div class="breadcrumb">
	<a href="index.php">{C.site_name}</a><!-- BLOCK menu_top_forum --> &raquo; <a href="view_forum.php?fid={MENU_FORUM_ID}">{MENU_FORUM_NAME}</a><!-- END BLOCK menu_top_forum --> &raquo; <b>{FORUM_NAME}</b>
</div>

<!-- BLOCK subforums -->
<table width="100%" align="center" class="maintable">
	<tr>
		<th height="25" colspan="5" align="left" style="padding-left:10px;">{L.Sub_Forums} {FORUM_NAME}</th>
	<tr>
		<td width="45" height="25" class="desc_row"></td>
		<td class="desc_row" align="center">{L.Forum_Name}</td>
		<td width="100" class="desc_row" align="center">{L.Topics}</td>
		<td width="100" class="desc_row" align="center">{L.Posts}</td>
		<td width="175" class="desc_row" align="center">{L.Last_Post}</td>
	</tr>
	{block_forum_row}
</table>
<br />
<!-- END BLOCK subforums -->	

<table width="100%">
 <tr>
  <td width="125">
	<a href="posting.php?func=newtopic&fid={FID}"><img src="{T.TEMPLATE_PATH}/images/new_topic.gif" border="0" /></a>
  </td>
 </tr>
</table>
<table width="100%" align="center" class="maintable">
 <tr>
  <th width="45" height="25"></th><th>{L.Topic_Name}</th><th width="150">{L.Author}</th><th width="100">{L.Replies}</th><th width="100">{L.Views}</th><th width="175">{L.Last_Post}</th>
 </tr>
 <!-- BLOCK postrow -->
 <tr>
  <td class="cell2" align="center">
	{block_prow_new_posts}
  </td>
  <td class="cell1" style="padding-left:5px" height="35" onclick="location.href='view_topic.php?tid={TOPIC_ID}'" onmouseover="this.className='cell2'" onmouseout="this.className='cell1'"><a href="view_topic.php?tid={TOPIC_ID}">{TOPIC_NAME}</a><br /></td>
  <td class="cell2" style="font-weight:bold" align="center">{AUTHOR}</td>
  <td class="cell1" align="center">{REPLIES}</td>
  <td class="cell2" align="center">{VIEWS}</td>
  <td class="cell1" align="center">{LAST_POST}</td>
 </tr>
 <!-- END BLOCK postrow -->
</table>
<table width="100%" align="center">
 <tr>
  <td width="125"><a href="posting.php?func=newtopic&fid={FID}"><img src="{T.TEMPLATE_PATH}/images/new_topic.gif" border="0" title="{L.New_Topic}" alt="{L.New_Topic}" /></a></td>
  <td align="left"><a href="index.php">{C.site_name}</a><!-- BLOCK menu_bottom_forum --> » <a href="view_forum.php?fid={MENU_FORUM_ID}">{MENU_FORUM_NAME}</a><!-- END BLOCK menu_bottom_forum --> » <b>{FORUM_NAME}</b></td>
  <td align="right" valign="top">{PAGINATION}</td>
 </tr>
</table>
     <br><br>
