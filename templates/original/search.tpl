<div class="breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <b>{L.Search}</b>
</div>

<form method="post" action="">
{CSRF_TOKEN}
	<table width="100%" align="center" class="maintable">
		<tr>
			<th height="25" colspan="3">{L.Search}</th>
		</tr>
		<tr>
			<td class="cell2" width="30%">{L.Search_for}</td>
			<td class="cell1"><input type="text" name="search_query" size="65"></td>
			<td class="cell1" width="140"><input type="checkbox" name="search_topic_title" value="1" checked="checked" />{L.Search_Topic_Titles}<br /><input type="checkbox" name="search_post_text" value="1" checked="checked" />{L.Search_Post_Text}
		</tr>
		<tr>
			<td class="cell2">{L.Search_author}</td>
			<td class="cell1" colspan="2"><input type="text" name="search_author" size="65" /></td>
		</tr>
		<tr>
			<th height="25" colspan="3">{L.Search_Options}</th>
		</tr>
		<tr>
			<td class="cell2">{L.Search_in}</td>
			<td class="cell1" colspan="2">
				<!--// forums list row -->
				<!-- BLOCK forumrow -->
					<option value="forum_{FORUM_ID}" style="font-weight:normal;">{PREFIX} {FORUM_NAME}</option>
				<!-- END BLOCK forumrow -->
				<select name="search_in">
					<option value="all" selected="selected">{L.All_Forums}</option>
					<!-- BLOCK catrow -->
					<option value="cat_{CAT_ID}" style="font-weight:bold;">+ {CAT_NAME}</option>
					{block_forumrow}
					<!-- END BLOCK catrow -->
				</select>
			</td>
		</tr>
		<tr>
			<td class="cell2">{L.Post_Age}</td>
			<td class="cell1" colspan="2">
			 	<select name="post_age">
			 		<option value="-1">{L.All_Posts}</option>
			 		<option value="1">{L.Today}</option>
			 		<option value="7">{L.7_Days_Ago}</option>
			 		<option value="14">{L.2_Weeks_Ago}</option>
			 		<option value="30">{L.1_Month_Ago}</option>
			 		<option value="90">{L.3_Months_Ago}</option>
			 		<option value="180">{L.6_Months_Ago}</option>
			 		<option value="365">{L.1_Year_Ago}</option>
			 	</select><br />
			 	<input type="radio" name="post_age_type" value="newer" checked="checked" />{L.Newer}<br />
			 	<input type="radio" name="post_age_type" value="older" />{L.Older}<br />
			 </td>
		<tr>
			<th height="25" colspan="3">
				<input type="submit" name="submit" value="{L.Submit}" />  <input type="reset" value="{L.Reset}" />
			</th>
		</tr>
	</table>
</form>
