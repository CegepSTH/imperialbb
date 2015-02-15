<!-- BLOCK logged_in -->
<div class="index-time">
	<a href="pm.php">{PRIVATE_MESSAGE_INFO}</a><br />{CURRENT_TIME} |  {ALL_TIMES_ARE_TIMEZONE}
</div>
<div class="index-site-nav clearfix">
	<div class="site-name">
		<a href="index.php">{C.site_name}</a>
	</div>
	<div class="site-navsearch pull-right">
		<a href="search.php?func=new">{L.Search_New}</a> | 
		<a href="search.php?func=unanswered">{L.Search_Unanswered}</a><br />
		<a href="posting.php?mark=0">{L.Mark_All_Forums_As_Read}</a>
	</div>
</div>
<!-- END BLOCK logged_in -->
<!-- BLOCK guest -->
<div class="index-time">{CURRENT_TIME}</div>
<div class="index-site-nav clearfix">
	<div class="site-name">
		<a href="index.php">{C.site_name}</a>
	</div>
	<div class="site-navsearch pull-right">
		<a href="search.php?func=unanswered">{L.Search_Unanswered}</a>
	</div>
</div>
<form method="post" action="login.php" class="panel bottom-border login-panel-index">
	{CSRF_TOKEN}
	<div class="panel-header">{L.Login}</div>
	<div class="panel-body">
		<input type="text" name="UserName" value="{L.Username}" onfocus="this.value=''" />&nbsp;&nbsp;
		<input type="password" name="PassWord" value="{L.Password}" onfocus="this.value=''" />&nbsp;&nbsp;
		<input type="submit" name="Submit" value="{L.Login}" />&nbsp;&nbsp;<input type="button" onclick="window.location = 'register.php'" value="{L.Register}" />
	</div>
</form>
<!-- END BLOCK guest -->
<!-- BLOCK catrow -->
<div class="panel category-panel">
	<div class="panel-header nocenter alt">
		<a href="index.php?cid={CAT_ID}">{CAT_NAME}</a>
	</div>
	<table>
		<tr>
			<th class="read-indicator"></th>
			<th class="forum-name">{L.Forum}</th>
			<th class="topic-count">{L.Topics}</th>
			<th class="post-count">{L.Posts}</th>
			<th class="last-post">{L.Last_Post}</th>
		</tr>
		{CATEGORY_CONTENTS}
	</table>

<!--// Blocks for normal forums -->
	<!-- BLOCK new_posts -->
	<img src="{T.TEMPLATE_PATH}/images/new_posts.gif" alt="{L.New_Posts}" title="{L.New_Posts}" border="0" />
	<!-- END BLOCK new_posts -->
	<!-- BLOCK no_new_posts -->
	<img src="{T.TEMPLATE_PATH}/images/no_new_posts.gif" alt="{L.No_New_Posts}" title="{L.No_New_Posts}" border="0" />
	<!-- END BLOCK no_new_posts -->
	<!-- BLOCK subforums_list --><br /><span style="font-size: 10px;">{SUBFORUMS}</span><!-- END BLOCK subforums_list -->
	<!-- BLOCK last_post -->
	<a href="view_topic.php?tid={LAST_POST_ID}">{LAST_POST_TITLE}</a><br />
	{LAST_POST_DATE}<br />
	{LAST_POST_AUTHOR}
	<!-- END BLOCK last_post -->
	<!-- BLOCK no_last_post -->
	<b>{L.None}</b>
	<!-- END BLOCK no_last_post -->
<!--// Blocks for normal forums end -->

	<!-- BLOCK forumrow_normal -->
	<tr class="forum normal">
		<td class="read-indicator">
			{NEW_POSTS_INDICATOR}
		</td>
		<td class="forum-name">
			<a href="view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a><br />
			<i>{FORUM_DESCRIPTION}</i>
			{SUBFORUMS}
		</td>
		<td class="topic-count">{TOPICS}</td>
		<td class="post-count">{POSTS}</td>
		<td class="last-post">
			{LAST_POST}
		</td>
	</tr>
	<!-- END BLOCK forumrow_normal -->
	<!-- BLOCK forumrow_redir -->
	<tr class="forum redir">
		<td class="read-indicator">
			<img src="{T.TEMPLATE_PATH}/images/no_new_posts.gif" alt="Redirect Forum" width="26" height="25" />
		</td>
		<td class="forum-name">
			<a href="view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a><br />
			<i>{FORUM_DESCRIPTION}</i>
		</td>
		<td colspan="3" class="redir-count">{REDIRECT_HITS}</td>
	</tr>
	<!-- END BLOCK forumrow_redir -->
</div>
<!-- END BLOCK catrow -->
<div class="clearfix tz-ind-bottom">
	<div class="pull-right">{ALL_TIMES_ARE_TIMEZONE}</div>
</div>
<div class="forum-stats panel">
	<div class="panel-header alt">
		{L.stats_forumstats}
	</div>

	<div class="stat-header">
		{L.Total_Users_Online}: {TOTAL_ONLINE} | 
		{L.Users_Online}: {USERS_ONLINE} | 
		{L.Guests_Online}: {GUESTS_ONLINE}
	</div>
	<div class="stat-content clearfix online-stats">
		<div class="pull-left">
			<img src="{T.TEMPLATE_PATH}/images/stats_online.gif" alt="{L.Whos_Online}" title="{L.Whos_Online}" border="0" />
		</div>
		<div>
			<div class="rank-list">
				{RANKS_LIST}
			</div>
			<hr />
			<div class="online-list">
				{ONLINE_LIST}
			</div>
		</div>
	</div>

	<div class="stat-header">{L.stats_boardstats}</div>
	<div class="stat-content clearfix board-stats">
		<div class="pull-left">
			<img src="{T.TEMPLATE_PATH}/images/stats_stats.gif" alt="{L.stats_boardstats}" title="{L.stats_boardstats}" />
		</div>
		<div class="clearfix">
			<div class="pull-left">
				{TOTAL_TOPICS_POSTS}<br />
				{TOTAL_USERS}<br />
				{NEWEST_MEMBER}<br />
			</div>
			<div class="pull-right">
				<strong>{L.New_Posts}</strong>&nbsp;
				<img src="{T.TEMPLATE_PATH}/images/new_posts.gif" alt="{L.New_Posts}" title="{L.New_Posts}"  />
				&nbsp;&nbsp;&nbsp;
				<strong>{L.No_New_Posts}</strong>&nbsp;
				<img src="{T.TEMPLATE_PATH}/images/no_new_posts.gif" alt="{L.No_New_Posts}" title="{L.No_New_Posts}" />
			</div>
		</div>
	</div>
	<!-- BLOCK forumstats_birthdays -->
	<div class="stat-header">{L.stats_birthdays}</div>
	<div class="stat-content clearfix">
		<div class="pull-left">
			<img src="{T.TEMPLATE_PATH}/images/stats_bday.gif" alt="{L.stats_birthdays}" title="{L.stats_birthdays}" />
		</div>
		<div>
			{BDAY_LIST}
		</div>
	</div>
    <!-- END BLOCK forumstats_birthdays -->
	<div class="stat-header">{TODAY_TOTAL}</div>
	<div class="stat-content clearfix">
		<div class="pull-left">
			<img src="{T.TEMPLATE_PATH}/images/stats_onlinetoday.gif" alt="{L.stats_onlinetoday}" title="{L.stats_onlinetoday}" />
		</div>
		<div>
			{ONLINE_TODAY}
		</div>
	</div>
</div>
