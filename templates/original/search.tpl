<div class="breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <b>{L.Search}</b>
</div>

<div class="block-form-admin">
<form method="post" action="panel login-panel">
{CSRF_TOKEN}
	<div class="panel-header">
		<h4>{L.Search}</h4>
	</div>
	<div class="form-admin-row">
		<label for="search_query">{L.Search_for}:</label>
		<label><input type="text" id="search_query" name="search_query" required></label>
		<label for="search_topic_title">{L.Search_Topic_Titles}</label>
		<label>
			<input type="checkbox" name="search_topic_title" id="search_topic_title" value="1" checked="checked">
		</label>
		<label for="search_post_text">{L.Search_Post_Text}</label>
		<label>
			<input type="checkbox" name="search_post_text" value="1" checked="checked">
		</label>
	</div>
	<div class="form-admin-row">
		<label for="search_author">{L.Search_author}</label>
		<label><input type="text" name="search_author"></label>
	</div>
	<h4>{L.Search_Options}</h4>
	<div class="form-admin-row">
		<label for="search_in">{L.Search_in}</label>
		<label>
			<!--// forums list row -->
			<!-- BLOCK forumrow -->
				<option value="forum_{FORUM_ID}" style="font-weight:normal;">{PREFIX} {FORUM_NAME}</option>
			<!-- END BLOCK forumrow -->
			<select name="search_in" id="search_in">
				<option value="all" selected="selected">{L.All_Forums}</option>
				<!-- BLOCK catrow -->
				<option value="cat_{CAT_ID}" style="font-weight:bold;">+ {CAT_NAME}</option>
				{block_forumrow}
				<!-- END BLOCK catrow -->
			</select>
		</label>
	</div>
	<div class="form-admin-row">
		<label for="post_age">{L.Post_Age}</label>
		<label>
			<select name="post_age" id="post_age">
				<option value="-1">{L.All_Posts}</option>
				<option value="1">{L.Today}</option>
				<option value="7">{L.7_Days_Ago}</option>
				<option value="14">{L.2_Weeks_Ago}</option>
				<option value="30">{L.1_Month_Ago}</option>
				<option value="90">{L.3_Months_Ago}</option>
				<option value="180">{L.6_Months_Ago}</option>
				<option value="365">{L.1_Year_Ago}</option>
			</select>	
		</label>
	</div>
	<div class="form-admin-row">
		<label style="text-align:right;"><input type="radio" name="post_age_type" value="newer" checked="checked" />{L.Newer}</label>
		<label><input type="radio" name="post_age_type" value="older" />{L.Older}</label>
	</div>
	<h4>
		<input type="submit" name="submit" value="{L.Submit}">  <input type="reset" value="{L.Reset}>
	</h4>
</form>
</div><br />
