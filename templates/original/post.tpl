<!-- BLOCK nav_new_topic -->
<div class="nav-breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; 
	<a href="view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a> &raquo; 
	<b>{ACTION}</b>
</div>
<!-- END BLOCK nav_new_topic -->
<!-- BLOCK nav_reply -->
<div class="nav-breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; 
	<a href="view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a> &raquo; 
	<a href="view_topic.php?tid={TOPIC_ID}">{TOPIC_NAME}</a> &raquo; 
	<b>{ACTION}</b>
</div>
<!-- END BLOCK nav_reply -->
<!-- BLOCK error -->
<div class="panel bottom-border editor-error-panel post-error-panel">
	<div class="panel-header">
		{L.The_following_errors_occoured}:
	</div>
	<div class="panel-body">
		{ERRORS}
	</div>
</div>
<!-- END BLOCK error -->
<form name="new_topic" method="post" action="" class="panel editor-panel post-edit-panel">
	{CSRF_TOKEN}
	<input type="hidden" name="current_poll_choices" value="{CURRENT_POLL_CHOICES}" />
	<div class="panel-header">
		{ACTION}
	</div>

	<!-- BLOCK title -->
	<div class="editor-row">
		<div class="label">
			{L.Topic_Title}
		</div>
		<div class="value">
			<input type="text" name="title" value="{TITLE}" tabindex="1" maxlength="75" class="value-edit" />
		</div>
	</div>
	<!-- END BLOCK title -->

	<div class="editor-row">
		<div class="label">
			{L.Message}
		</div>
		<div class="value">
			{BBCODE_EDITOR}

			<textarea
				id="body"
				name="body"
				tabindex="2"
				onkeyup="getNbCharactersLeft()"
				onchange="getNbCharactersLeft()"
				class="value-edit post-body">{BODY}</textarea>

			<div class="character-indicator" id="CharsLeft">
				{L.Chars_left} 2000
			</div>
			<script>
				function getNbCharactersLeft() {
					var nbCharsLeft = 2000 - document.getElementById("body").value.length;
					document.getElementById("CharsLeft").innerHTML = "{L.Chars_left}" + nbCharsLeft;

					if(nbCharsLeft <= 0) {
						document.getElementById("Submit").disabled = true;
						document.getElementById("Submit").value = "{L.Disabled}";
					} else {
						document.getElementById("Submit").disabled = false;
						document.getElementById("Submit").value = "{L.Submit}";
					}
				}
			</script>
		</div>
		<div class="extra">
			{SMILIE_PICKER}
		</div>
	</div>

	<script type="text/javascript">
		setSmilieTarget("body");
		setBBCodeTarget("body");
	</script>

	<div class="editor-section">
		{L.Options}
	</div>

	<div class="editor-row">
		<div class="label">
			{HTML_ENABLED_MSG}<br />
			{BBCODE_ENABLED_MSG}<br />
			{SMILIES_ENABLED_MSG}<br >
		</div>
		<div class="value">
			<!-- BLOCK disable_html -->
			<label for="disable_html"><input type="checkbox" name="disable_html" id="disable_html" />{L.Disable_HTML}</label><br />
			<!-- END BLOCK disable_html -->
			<!-- BLOCK disable_bbcode -->
			<label for="disable_bbcode"><input type="checkbox" name="disable_bbcode" id="disable_bbcode" />{L.Disable_BBCode}</label><br />
			<!-- END BLOCK disable_bbcode -->
			<!-- BLOCK disable_smilies -->
			<label for="disable_smilies"><input type="checkbox" name="disable_smilies" id="disable_smilies" />{L.Disable_Smilies}</label><br />
			<!-- END BLOCK disable_smilies -->
			<!-- BLOCK logged_in -->
			<label for="subscribe_to_topic"><input type="checkbox" name="subscribe_to_topic" id="subscribe_to_topic" />{L.Subscribe_To_Topic}</label><br />
			<label for="attach_signature"><input type="checkbox" name="attach_signature" id="attach_signature" checked="checked" />{L.Attach_Signature}</label><br />
			<!-- END BLOCK logged_in -->
		</div>
	</div>

	<!-- BLOCK poll_header -->
	<div class="editor-section">
		{L.Poll}
	</div>
	<div class="editor-section-desc">
		{L.Poll_Leave_Blank_Msg}
	</div>

	<div class="editor-row">
		<div class="label">
			{L.Poll_Title}
		</div>
		<div class="value">
			<input type="text" name="poll_title" value="{POLL_TITLE}" maxlength="75" class="value-edit" />
		</div>
	</div>
	<!-- END BLOCK poll_header -->

	<!-- BLOCK pollchoice_row -->
	<div class="editor-row">
		<div class="label">
			{POLL_CHOICE_DESC}
		</div>
		<div class="value">
			<input
				type="text"
				name="pollchoice[{POLL_CHOICE_NUMBER}]"
				value="{POLL_CHOICE_VALUE}"
				maxlength="50"
				class="value-edit" />
		</div>
	</div>
	<!-- END BLOCK pollchoice_row -->

	<!-- BLOCK poll_trailer -->
	<div class="editor-row">
		<div class="label">
		</div>
		<div class="value">
			<input
				type="button"
				name="add_choice"
				value="{L.Add_Poll_Choice}"
				onclick="document.new_topic.action='{POLL_ADD_CHOICE_URL}'; document.new_topic.submit();" />
		</div>
	</div>
	<!-- END BLOCK poll_trailer -->

	<div class="panel-footer">
		<input type="submit" name="Submit" id="Submit" value="{L.Submit}" tabindex="3" />
		<input type="reset" value="{L.Reset}" />
	</div>
</form>
