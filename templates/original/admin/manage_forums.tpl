<!-- BLOCK category -->
<div class="panel category-manage">
	<div class="panel-header">
		<span class="category-name">
			<a href="../index.php?cid={CAT_ID}">{CAT_NAME}</a> - 
			<a href="?act=forums&func=edit_category&cid={CAT_ID}">{L.Edit}</a> - 
			<a href="?act=forums&func=delete_category&cid={CAT_ID}">{L.Delete}</a>
		</span>
		<span class="category-move">
			<a href="forums.php?move=up&cid={CAT_ID}">[{L.Up}]</a> 
			<a href="forums.php?move=down&cid={CAT_ID}">[{L.Down}]</a>
		</span>
	</div>
	{CAT_CONTENTS}
	<!-- BLOCK no_forums_in_cat -->
	<div class="category-noforums">{L.This_category_has_no_boards}</div>
	<!-- END BLOCK no_forums_in_cat -->

	<!-- BLOCK forums_table_start -->
	<table>
		<tr>
			<th class="forum-name">{L.Forum}</th>
			<th class="topic-count">{L.Topics}</th>
			<th class="post-count">{L.Posts}</th>
			<th class="action-buttons"></th>
		</tr>
	<!-- END BLOCK forums_table_start -->

<!--// Keep the space at the end of the line of the block parent_forum. -->
<!-- BLOCK parent_forum -->
<a href="../view_forum.php?fid={SUBFORUM_ID}">{SUBFORUM_NAME}</a> &raquo; 
<!-- END BLOCK parent_forum -->

		<!-- BLOCK regular_forum -->
		<tr>
			<td class="forum-name">
				{PARENT_FORUMS}<a href="../view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a><br />
				<i>{FORUM_DESCRIPTION}</i>
			</td>
			<td class="topic-count">{TOPICS}</td>
			<td class="post-count">{POSTS}</td>
			<td class="action-buttons">
				<div class="button-row">
					<input type="button" value="{L.Up}" onclick="window.location = 'forums.php?move=up&fid={FORUM_ID}'"> - <input type="button" value="{L.Down}" onclick="window.location = 'forums.php?move=down&fid={FORUM_ID}'" />
				</div>
				<div class="button-row">
					<input type="button" value="{L.Edit}" onclick="window.location = 'forums.php?func=edit_forum&fid={FORUM_ID}'"> - <input type="button" value="{L.Delete}" onclick="window.location = 'forums.php?func=delete_forum&fid={FORUM_ID}'" />
				</div>
			</td>
		</tr>
		<!-- END BLOCK regular_forum -->
		<!-- BLOCK redirection_forum -->
		<tr>
			<td class="forum-name">
				{PARENT_FORUMS}<a href="../view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a><br />
				<i>{FORUM_DESCRIPTION}</i>
			</td>
			<td colspan="2" class="redir-count">{REDIRECTS}</td>
			<td class="action-buttons">
				<div class="button-row">
					<input type="button" value="{L.Up}" onclick="window.location = 'forums.php?move=up&fid={FORUM_ID}'"> - <input type="button" value="{L.Down}" onclick="window.location = 'forums.php?move=down&fid={FORUM_ID}'" />
				</div>
				<div class="button-row">
					<input type="button" value="{L.Edit}" onclick="window.location = 'forums.php?func=edit_forum&fid={FORUM_ID}'"> - <input type="button" value="{L.Delete}" onclick="window.location = 'forums.php?func=delete_forum&fid={FORUM_ID}'" />
				</div>
			</td>
		</tr>
		<!-- END BLOCK redirection_forum -->
	<!-- BLOCK forums_table_end -->
	</table>
	<!-- END BLOCK forums_table_end -->
	<div class="panel-footer">
		<form method="post" action="?act=forums&func=add_forum&cid={CAT_ID}">
			{L.Create_Forum} : <input type="text" name="name">
			<input type="submit" name="no_submit" value="{L.Submit}" />
		</form>
	</div>
</div>
<!-- END BLOCK category -->

<div class="category-create">
	<form method="post" action="?act=forums&func=add_category">
		{L.Create_Category} : <input type="text" name="name">
		<input type="submit" name="Submit" value="{L.Submit}" />
	</form>
</div>

