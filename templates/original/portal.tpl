<!-- BLOCK no_news_message -->
<p>
	{L.no_news_found_sorry}
</p>
<!-- END BLOCK no_news_message -->

<!-- BLOCK news_read_complete -->
<p style="text-align:right;">
	<a href="portal.php?nid={NEWS_ID}">{L.read_complete_news}</a>
</p>
<!-- END BLOCK news_read_complete -->

<div style="display:inline-block;margin-top:1em;margin-bottom:-1.2em;">
{PAGINATION}
</div>
<!-- BLOCK news_item -->
<div class="panel" style="margin-left: 5em;margin-right: 5em;margin-top:2em;border-bottom:2px solid #ccc;">
<h3 class="panel-header">{TITLE}</h3>
<article style="padding: 5px;padding-left:10px;padding-right:10px;">
	<p style="font-weight: normal;border-bottom:1px solid #ccc;" class="clearfix">
		<img style="float:left;margin-right:1em;" src="images/avatars/{AUTHOR_AVATAR}" alt="{L.author_avatar}" />
		<strong>{L.published_by}<a href="profile.php?id={AUTHOR_ID}">{AUTHOR_NAME}</a></strong><br />
		<em>{L.published_on}{DATE}</em><br />
	</p>
	<p style="font-weight: normal;text-align:justify;text-indent:3em;">
		<span style="font-size: 1.2em;">
		{CONTENT}
		</span><br />
	</p>
	{block_news_read_complete}
</article>
</div>
<!-- END BLOCK news_item -->
<div style="display:inline-block;">
{PAGINATION}
</div>
