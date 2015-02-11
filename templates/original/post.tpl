<script type="text/javascript">
function add_emoticon(text) {
	document.new_topic.body.value  += text;
	document.new_topic.body.focus();
}

function add_bbcode(text,attrib) {
	if(attrib != undefined) {
		attrib = "=" + attrib;
	}
	else
	{
		attrib = "";
	}
	document.new_topic.body.value += "[" + text + attrib + "]Text[/" + text + "]";
	document.new_topic.body.focus();
}
</script>
<!-- BEGIN SWITCH navbar -->
<table width="100%">
	<tr>
		<td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <a href="view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a> &raquo; <b>{ACTION}</b></td>
	</tr>
</table>
<!-- SWITCH navbar -->
<table width="100%">
	<tr>
		<td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <a href="view_forum.php?fid={FORUM_ID}">{FORUM_NAME}</a> &raquo; <a href="view_topic.php?tid={TOPIC_ID}">{TOPIC_NAME}</a> &raquo; <b>{ACTION}</b></td>
	</tr>
</table>
<!-- END SWITCH navbar -->
<!-- BEGIN error -->
<table width="100%" class="maintable">
	<tr>
		<th>{L.The_following_errors_occoured}:</th>
	</tr>
	<tr>
		<td class="cell2">
			{ERRORS}
		</td>
	</tr>
</table>
<!-- END error -->
<form name="new_topic" method="post" action="">
{CSRF_TOKEN}
	<input type="hidden" name="current_poll_choices" value="{CURRENT_POLL_CHOICES}" />
	<table width="100%" align="center" class="maintable">
		<tr>
			<th colspan="2" height="25">{ACTION}</th>
		</tr>
		<!-- BEGIN title -->
		<tr>
			<td class="cell2" width="25%"><span style="font-weight:bold;">{L.Topic_Title}</span></td>
			<td class="cell1">
				<input type="text" name="title" value="{TITLE}" tabindex="1" size="60" maxlength="75">
			</td>
		</tr>
		<!-- END title -->
		<tr>
			<td class="cell2" valign="top" width="25%"><span style="font-weight:bold;">{L.Message}</span></td>
			<td class="cell1">
				<table width="100%" cellpadding="0" cellspacing="0">
					<!-- BEGIN bbcode -->
					<tr>
						<td width="400" align="center">
							<input type="button" name="bold" value="{L.B}" accesskey="b" onclick="javascript:add_bbcode('b');" />
							<input type="button" name="underline" value="{L.U}" accesskey="u" onclick="javascript:add_bbcode('u');" />
							<input type="button" name="italics" value="{L.I}" accesskey="i" onclick="javascript:add_bbcode('i');" />
							<input type="button" name="quote" value="{L.Quote}" accesskey="q" onclick="javascript:add_bbcode('quote');" />
							<input type="button" name="code" value="{L.Code}" accesskey="c" onclick="javascript:add_bbcode('code');" />
							<input type="button" name="url" value="{L.URL}" accesskey="" onclick="javascript:add_bbcode('url');" />
							<input type="button" name="image" value="{L.Img}" accesskey="" onclick="javascript:add_bbcode('img');" /><br />
							{L.Font_Size}
							<select name="font_size" onchange="javascript:add_bbcode('size', this.options[this.selectedIndex].value)">
								<option value="1">{L.Very_Small}</option>
								<option value="1.5">{L.Small}</option>
								<option value="2">{L.Medium}</option>
								<option value="3">{L.Large}</option>
								<option value="4">{L.Very_Large}</option>
							</select>
							&nbsp;&nbsp;{L.Font_Color}
							<select name="font_color" onchange="javascript:add_bbcode('color', this.options[this.selectedIndex].value)">
								<option value="red">{L.Red}</option>
								<option value="blue">{L.Blue}</option>
								<option value="green">{L.Green}</option>
								<option value="orange">{L.Orange}</option>
								<option value="yellow">{L.Yellow}</option>
								<option value="purple">{L.Purple}</option>
								<option value="black">{L.Black}</option>
								<option value="white">{L.White}</option>
							</select>
						</td>
						<td></td>
					</tr>
					<!-- END bbcode -->
					<tr>
						<td>
							<textarea id="body" name="body" tabindex="2" rows="13" cols="46" onkeyup="getNbCharactersLeft()" onchange="getNbCharactersLeft()">{BODY}</textarea>
						</td>

						<td>
							<!-- BEGIN smilies -->
							<table align="center" class="maintable" cellspacing="5">
								<tr>
									<td align="center" style="font-weight:bold;" colspan="5">{L.Emoticons}</td>
								</tr>
								<!-- BEGIN emoticon_row -->
								<tr>
									<!-- BEGIN emoticon_cell -->
									<td align="center"><a onclick="javascript:add_emoticon('{EMOTICON_CODE}');"><img src="{EMOTICON_URL}" alt="{EMOTICON_TITLE}" title="{EMOTICON_TITLE}" /></a></td>
									<!-- END emoticon_cell -->
								</tr>
								<!-- END emoticon_row -->
								<tr>
									<td align="center" colspan="5">{L.Show_All_Emoticons}</td>
								</tr>
							</table>
							<!-- END smilies -->
						</td>
					</tr>

				</table>

				<!-- HARD CODED -->
				<p id="CharsLeft">{L.Chars_left} 2000</p>
				<script>
					function getNbCharactersLeft() {
						var nbCharsLeft = 2000 - document.getElementById("body").value.length;
						document.getElementById("CharsLeft").innerHTML = "{L.Chars_left}" + nbCharsLeft;

						<!-- HARD CODED -->
						if(nbCharsLeft <= 0){
							document.getElementById("Submit").disabled = true;
							document.getElementById("Submit").value = "{L.Disabled}";

							// document.getElementById("Submit").value = "Disabled";
						}
						else{
							document.getElementById("Submit").disabled = false;
							document.getElementById("Submit").value = "{L.Submit}";
						}
					}
				</script>

			</td>
		</tr>
		<tr>
			<th colspan="2" height="25">{L.Options}</th>
		</tr>
		<tr>
			<td class="cell2">
				{HTML_ENABLED_MSG}<br />
				{BBCODE_ENABLED_MSG}<br />
				{SMILIES_ENABLED_MSG}<br >
			</td>
			<td class="cell1">
				<!-- BEGIN disable_html -->
				<label for="disable_html"><input type="checkbox" name="disable_html" id="disable_html" />{L.Disable_HTML}</label><br />
				<!-- END disable_html -->
				<!-- BEGIN disable_bbcode -->
				<label for="disable_bbcode"><input type="checkbox" name="disable_bbcode" id="disable_bbcode" />{L.Disable_BBCode}</label><br />
				<!-- END disable_bbcode -->
				<!-- BEGIN disable_smilies -->
				<label for="disable_smilies"><input type="checkbox" name="disable_smilies" id="disable_smilies" />{L.Disable_Smilies}</label><br />
				<!-- END disable_smilies -->
				<!-- BEGIN logged_in -->
				<label for="subscribe_to_topic"><input type="checkbox" name="subscribe_to_topic" id="subscribe_to_topic" />{L.Subscribe_To_Topic}</label><br />
				<label for="attach_signature"><input type="checkbox" name="attach_signature" id="attach_signature" checked="checked" />{L.Attach_Signature}</label><br />
				<!-- END logged_in -->
			</td>
		</tr>
		<!-- BEGIN poll -->
		<tr>
			<th colspan="2" height="25">{L.Poll}</th>
		</tr>
		<tr>
			<td class="desc_row" colspan="2" align="center">{L.Poll_Leave_Blank_Msg}</td>
		</tr>
		<tr>
			<td class="cell2">{L.Poll_Title}</td>
			<td class="cell1"><input type="text" name="poll_title" value="{POLL_TITLE}" size="60" maxlength="75"></td>
		</tr>
		<!-- BEGIN pollchoice_row -->
		<tr>
			<td class="cell2">{POLL_CHOICE_DESC}</td>
			<td class="cell1"><input type="text" name="pollchoice[{POLL_CHOICE_NUMBER}]" value="{POLL_CHOICE_VALUE}" size="60" maxlength="50">
		</tr>
		<!-- END pollchoice_row -->
		<tr>
			<td class="cell2"></td>
			<td class="cell1"><input type="button" name="add_choice" value="{L.Add_Poll_Choice}" onclick="document.new_topic.action='{POLL_ADD_CHOICE_URL}'; document.new_topic.submit();" /></td>
		</tr>
		<!-- END poll -->
		<tr>
			<th colspan="2">
				<input type="submit" name="Submit" id="Submit" value="{L.Submit}" tabindex="3" />  <input type="reset" value="{L.Reset}" />
			</th>
		</tr>
	</table>
</form>


