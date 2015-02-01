<!-- BEGIN SWITCH logged_in -->
<table width="100%">
	<tr>
		<td><a href="pm.php">{PRIVATE_MESSAGE_INFO}</a><br />{CURRENT_TIME} |  {ALL_TIMES_ARE_TIMEZONE}</td>
	</tr>
	<tr>
		<td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a></td>
		<td align="right"><a href="search.php?func=new">{L.Search_New}</a> | <a href="search.php?func=unanswered">{L.Search_Unanswered}</a><br /><a href="posting.php?mark=0">{L.Mark_All_Forums_As_Read}</a></td>
	</tr>
</table>
<!-- SWITCH logged_in -->
<table width="100%">
	<tr>
		<td colspan="2">{CURRENT_TIME}</td>
	</tr>
	<tr>
		<td align="left"><a href="index.php">{C.site_name}</a></td>
		<td align="right"><a href="search.php?func=unanswered">{L.Search_Unanswered}</a></td>
	</tr>
</table>
<table width="100%" align="center" class="maintable">
	<tr>
		<th height="25" align="center">{L.Login}</th>
	</tr>
	<tr>
		<form method="post" action="login.php">
			<td align="center" valign="middle" class="cell2" height="40">
				{CSRF_TOKEN}
				<input type="text" name="UserName" value="{L.Username}" onfocus="this.value=''" />&nbsp;&nbsp;
				<input type="password" name="PassWord" value="{L.Password}" onfocus="this.value=''" />&nbsp;&nbsp;
				<input type="submit" name="Submit" value="{L.Login}" />&nbsp;&nbsp;<input type="button" onclick="window.location = 'register.php'" value="{L.Register}" />
			</td>
		</form>
	</tr>
</table>
<br />
<!-- END SWITCH logged_in -->
<script src="{C.jscripts_dir}/js_toggle.js" tyle="text/javascript"></script>
<table width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<!-- BEGIN catrow -->
			<!-- BEGIN break_line -->
			<br />
			<!-- END break_line -->
			<div id="cat_h{CAT_ID}">
				<table width="100%" align="center" class="maintable">
					<tr>
						<td>
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<th height="25" align="left"><div style="float:right; padding-right:5px;padding-top:2px;"><a href="javascript:void(0)" onclick="javascript:collapseforum('{CAT_ID}');" title="{L.expand}"><img src="{T.TEMPLATE_PATH}/images/minus.gif" alt="{L.expand}" title="{L.expand}" border="0" /></a></div><span style="padding-left: 4px;"><a href="index.php?cid={CAT_ID}">{CAT_NAME}</a></span></th>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div id="cat_v{CAT_ID}">
				<table width="100%" align="center" class="maintable">
					<tr>
						<td colspan="5">
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<th height="25" align="left"><div style="float:right; padding-right:5px;padding-top:2px;"><a href="javascript:void(0)" onclick="javascript:collapseforum('{CAT_ID}');" title="{L.collapse}"><img src="{T.TEMPLATE_PATH}/images/plus.gif" alt="{L.collapse}" title="{L.collapse}" border="0" /></a></div><span style="padding-left: 4px;"><a href="index.php?cid={CAT_ID}">{CAT_NAME}</a></span></th>

								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="desc_row" height="25"></td><td align="center" class="desc_row">{L.Forum}</td><td align="center" class="desc_row">{L.Topics}</td><td align="center" class="desc_row">{L.Posts}</td><td align="center" class="desc_row">{L.Last_Post}</td>
					</tr>
					<!-- BEGIN SWITCH forumrow -->
					<tr>
						<td class="cell2" height="45" width="45" align="center">
							<!-- BEGIN SWITCH new_posts -->
							<img src="{T.TEMPLATE_PATH}/images/new_posts.gif" alt="{L.New_Posts}" title="{L.New_Posts}" border="0" />
							<!-- SWITCH new_posts -->
							<img src="{T.TEMPLATE_PATH}/images/no_new_posts.gif" alt="{L.No_New_Posts}" title="{L.No_New_Posts}" border="0" />
							<!-- END SWITCH new_posts -->
						</td>
						<td class="cell1" height="45" onclick="location.href='view_forum.php?fid={FORUM_ID}'" onmouseover="this.className='cell2'" onmouseout="this.className='cell1'">
							<a href="view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a><br />
							<i>{FORUM_DESCRIPTION}</i>
							<!-- BEGIN subforums_list --><br /><span style="font-size: 10px;">{SUBFORUMS}</span><!-- END subforums_list -->
						</td>
						<td width="50" align="center" class="cell2">{TOPICS}</td>
						<td width="50" align="center" class="cell1">{POSTS}</td>
						<td width="200" align="center" valign="middle" class="cell2">
							<!-- BEGIN SWITCH last_post -->
							<a href="view_topic.php?tid={LAST_POST_ID}">{LAST_POST_TITLE}</a><br />
							{LAST_POST_DATE}<br />
							{LAST_POST_AUTHOR}
							<!-- SWITCH last_post -->
							<b>{L.None}</b>
							<!-- END SWITCH last_post -->
						</td>
					</tr>
					<!-- SWITCH forumrow -->
					<tr>
						<td class="cell2" height="45" align="center">
							<img src="{T.TEMPLATE_PATH}/images/no_new_posts.gif" alt="Redirect Forum" width="26" height="25" />
						</td>
						<td class="cell1" onclick="location.href='view_forum.php?fid={FORUM_ID}'" onmouseover="this.className='cell2'" onmouseout="this.className='cell1'">
							<a href="view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a><br />
							<i>{FORUM_DESCRIPTION}</i>
							<!-- BEGIN subforums_list --><br /><span style="font-size: 10px;">{SUBFORUMS}</span><!-- END subforums_list -->
						</td>
						<td colspan="3" align="center" class="cell2">{REDIRECT_HITS}</td>
					</tr>
					<!-- END SWITCH forumrow -->
					</tr>
					<tr>
						<th colspan="5" height="5"></th>
					</tr>
				</table>
				<script language="javascript">
				if(GetCookie('cat{CAT_ID}') == 'block' || GetCookie('cat{CAT_ID}') == null)
				{
					update_display('{CAT_ID}', true);
				}
				else
				{
					update_display('{CAT_ID}', false);
				}
				</script>
			</div>
			<!-- END catrow -->
		</td>
	</tr>
</table>
<div align="right">{ALL_TIMES_ARE_TIMEZONE}</div><br />
<div id="cat_h{L.stats_forumstats}">
	<table width="100%" class="maintable" align="center">
		<tr>
			<th colspan="2" height="25" align="left"><div style="float:right; padding-right:4px;padding-top:2px;"><a href="javascript:void(0)" onclick="javascript:collapseforum('{L.stats_forumstats}');" title="{L.expand}"><img src="{T.TEMPLATE_PATH}/images/minus.gif" alt="{L.expand}" title="{L.expand}" border="0" /></a></div><span style="padding-left: 4px;">{L.stats_forumstats}</span></th>
		</tr>
		<tr>
			<td class="cell1" width="100%" align="left" valign="middle">
				<strong style="float:right;">{TOTAL_POSTS} {L.Topics} | {TOTAL_TOPICS} {L.Posts}</strong>
				<strong>{L.Total_Users_Online}: {TOTAL_ONLINE} | {L.Users_Online}: {USERS_ONLINE} | {L.Guests_Online}: {GUESTS_ONLINE}</strong>
			</td>
		</tr>
		<tr>
			<th colspan="2" height="5"></th>
		</tr>
	</table>
</div>
<div id="cat_v{L.stats_forumstats}">
	<table width="100%" class="maintable" align="center">
		<tr>
			<th colspan="2" height="25" align="left"><div style="float:right; padding-right:4px;padding-top:2px;"><a href="javascript:void(0)" onclick="javascript:collapseforum('{L.stats_forumstats}');" title="{L.collapse}"><img src="{T.TEMPLATE_PATH}/images/plus.gif" alt="{L.collapse}" title="{L.collapse}" border="0" /></a></div><span style="padding-left: 4px;">{L.stats_forumstats}</span></th>
		</tr>
		<tr>
			<td class="desc_row" colspan="2" width="100%" style="padding:4px;">{L.Total_Users_Online}: {TOTAL_ONLINE} | {L.Users_Online}: {USERS_ONLINE} | {L.Guests_Online}: {GUESTS_ONLINE}</td>
		</tr>
		<tr>
			<td class="cell1" width="5%" align="center" valign="middle">
				<img src="{T.TEMPLATE_PATH}/images/stats_online.gif" alt="{L.Whos_Online}" title="{L.Whos_Online}" border="0" />
			</td>
			<td class="cell2" width="95%" align="left" valign="middle">
				{RANKS_LIST}<br />
				<hr />
				{ONLINE_LIST}
			</td>
		</tr>
		<tr>
			<td class="desc_row" colspan="2" width="100%" style="padding:4px;">{L.stats_boardstats}</td>
		</tr>
		<tr>
			<td class="cell1" width="5%" align="center" valign="middle">
				<img src="{T.TEMPLATE_PATH}/images/stats_stats.gif" alt="{L.stats_boardstats}" title="{L.stats_boardstats}" border="0" />
			</td>
			<td class="cell2" width="95%" align="left" valign="middle">
				<div style="float:right;padding:4px;"><strong>{L.New_Posts}</strong>&nbsp;<img src="{T.TEMPLATE_PATH}/images/new_posts.gif" alt="{L.New_Posts}" title="{L.New_Posts}" border="0" />&nbsp;&nbsp;&nbsp;<strong>{L.No_New_Posts}</strong>&nbsp;<img src="{T.TEMPLATE_PATH}/images/no_new_posts.gif" alt="{L.No_New_Posts}" title="{L.No_New_Posts}" border="0" /></div>					
				{TOTAL_TOPICS_POSTS}<br />
				{TOTAL_USERS}<br />
				{NEWEST_MEMBER}<br />
			</td>
		</tr>
        <!-- BEGIN forumstats_birthdays -->
		<tr>
			<td class="desc_row" colspan="2" width="100%" style="padding:4px;">{L.stats_birthdays}</td>
		</tr>
		<tr>
			<td class="cell1" width="5%" align="center" valign="middle">
				<img src="{T.TEMPLATE_PATH}/images/stats_bday.gif" alt="{L.stats_birthdays}" title="{L.stats_birthdays}" border="0" />
			</td>
			<td class="cell2" width="95%" align="left" valign="middle">
				{BDAY_LIST}
			</td>
		</tr>
	    <!-- END forumstats_birthdays -->
		<tr>
			<td class="desc_row" colspan="2" width="100%" style="padding:4px;">{TODAY_TOTAL}</td>
		</tr>
		<tr>
			<td class="cell1" width="5%" align="center" valign="middle">
				<img src="{T.TEMPLATE_PATH}/images/stats_onlinetoday.gif" alt="{L.stats_onlinetoday}" title="{L.stats_onlinetoday}" border="0" />
			</td>
			<td class="cell2" width="95%" align="left" valign="middle">
				{ONLINE_TODAY}
			</td>
		</tr>
		<tr>
			<th colspan="2" height="5"></th>
		</tr>
	</table>
	<script language="javascript">
	if(GetCookie('cat{L.stats_forumstats}') == 'block' || GetCookie('cat{L.stats_forumstats}') == null)
	{
		update_display('{L.stats_forumstats}', true);
	}
	else
	{
		update_display('{L.stats_forumstats}', false);
	}
	</script>
</div>
