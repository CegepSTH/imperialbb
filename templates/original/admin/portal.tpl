<!--// blocks declarations -->
<!-- BLOCK news_item -->
<tr>
	<td style="width: 80%;"> 
		"{NEWS_TITLE}", {L.by} <em>{NEWS_AUTHOR}</em>
	</td>
	<td style="text-align:right;">
		<form style="display:inline;" action="portal.php?func=delete&nid={NEWS_ID}" method="post">
			{CSRF_TOKEN}
			<input type="submit" name="Submit" value="{L.delete}">
		</form>
		<button onclick="window.location.replace('portal.php?func=edit&nid={NEWS_ID}');">{L.Edit}</button>
	</td>
</tr>
<!-- END BLOCK news_item -->

<!-- BLOCK create_news -->
<div class="block-form-admin">
	<form action="portal.php?func=save" method="post">
		{CSRF_TOKEN}
		<h3>{L.Create_news}</h3>
		<br />
		<label style="padding-left: 1em;">{L.news_title}</label> 
		<label style="width: 70%;"><input type="text" id="title" name="title" required></label><br /><br />
		<label style="padding-left: 1em;vertical-align:top;">{L.news_content}</label> 
		<label style="width: 70%;"><textarea rows="10" name="news_content" id="news_content" required></textarea></label>
		<input type="submit" name="submit" style="float:right;margin-top:1em;" value="{L.Submit}">
	</form>
</div>
<!-- END BLOCK create_news -->

<!-- BLOCK edit_news -->
<div class="block-form-admin">
	<form action="portal.php?func=save&nid={NEWS_ID}" method="post">
		{CSRF_TOKEN}
		<h3>{L.Create_news}</h3>
		<br />
		<label style="padding-left: 1em;">{L.news_title}</label> 
		<label style="width: 70%;"><input type="text" id="title" name="title" value="{NEWS_TITLE}" required></label><br /><br />
		<label style="padding-left: 1em;vertical-align:top;">{L.news_content}</label> 
		<label style="width: 70%;"><textarea rows="10" name="news_content" id="news_content" required>{NEWS_CONTENT}</textarea></label>
		<input type="submit" name="submit" style="float:right;margin-top:1em;" value="{L.Submit}">
	</form>
</div>
<!-- END BLOCK edit_news -->

<!-- BLOCK news_main -->
<div style="display:inline-block;margin-top:1em;margin-bottom:-1.2em;">
{PAGINATION}
</div>
<div class="block-form-admin">
<h4>{L.News}</h4>
<table>
	{block_news_item}
</table>
</div>
<button onclick="window.location.replace('portal.php?func=create');">{L.New_News}</button>
<div style="display:inline-block;">
{PAGINATION}
</div>
<!-- END BLOCK news_main -->
