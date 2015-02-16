<div class="breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <a href="mod.php?act=viewforum&fid={FORUM_ID}">{FORUM_NAME}</a> &raquo; <a href="mod.php?act=viewtopic&tid={TOPIC_ID}">{TOPIC_NAME}</a> &raquo; <b>{L.Move_Topic}</b></td>
</div>

<form method="post" action="" class="panel login-panel">
	{CSRF_TOKEN}
	<div class="panel-header">
		{L.MoveTopic} - {TOPIC_NAME}
	</div>
	<div class="form-row">
		<label for="fid">{L.Select_a_forum_to_move_topic_to}</label>
		<select name="fid" id="fid">
			<!-- BLOCK move_topic_forumrow -->
			<option value="{FID}">{FNAME}</option>
			<!-- END BLOCK move_topic_forumrow -->
		</select>
	</div>
	<div class="panel-footer">
		<input type="submit" name="Submit" value="{L.Submit}">  <input type="button" value="{L.Cancel}" onclick="window.location.href='view_topic.php?tid={TOPIC_ID}'">
	</div>
</form>
