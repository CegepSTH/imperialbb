<script type="text/javascript">
function add_emoticon(text) {
	document.send_pm.body.value  += text;
	document.send_pm.body.focus();
}

function add_bbcode(text,attrib) {
	if(attrib != undefined) {
		attrib = "=" + attrib;
	}
	else
	{
		attrib = "";
	}
	document.send_pm.body.value += "[" + text + attrib + "]Text[/" + text + "]";
	document.send_pm.body.focus();
}

</script>
<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <a href="pm.php">{L.PM_Manager}</a> &raquo; <b>{ACTION}</b></td>
 </tr>
</table>
<table width="50%" align="center" class="maintable">
 <tr>
  <th colspan="4" height="25">{L.Menu}</th>
 </tr>
 <tr>
  <td align="center" class="cell2"><a href="?act=pm">{L.Inbox}</a></td>
  <td align="center" class="cell2"><a href="?act=pm&func=send">{L.Create}</a></td>
  <td align="center" class="cell2"><a href="?act=pm&func=outbox">{L.Outbox}</a></td>
  <td align="center" class="cell2"><a href="?act=pm&func=sentbox">{L.Sent_Box}</a></td>
 </tr>
</table>
<br /><br />
<!-- BEGIN error -->
<table width="100%" align="center" class="maintable">
 <tr>
  <th height="25">{L.The_following_errors_occoured}:</th>
 </tr>
 <tr>
  <td style="padding-left:10px;" class="cell2">
   {ERRORS}
  </td>
 </tr>
</table>
<br />
<!-- END error -->
<form method="post" action="" name="send_pm">
{CSRF_TOKEN}
<table width="100%" align="center" class="maintable">
 <tr>
  <th colspan="3" height="25">
   {ACTION}
  </th>
 </tr>
 <!-- BEGIN username -->
 <tr>
  <td width="25%" class="cell2" height="30">
   <span style="font-weight:bold;">{L.Username}</span>
  </td>
  <td class="cell1">
   <input type="text" name="username" size="60" value="{USERNAME}" tabindex="1" />
  </td>
  </td>
  <td class="cell2">
   {L.Action} : {L.PM} <input type="radio" name="action" value="pm" {PM_SELECTED}> {L.Email} <input type="radio" name="action" value="email" {EMAIL_SELECTED}>
  </td>
 </tr>
 <!-- END username -->
 <tr>
  <th colspan="3"></th>
 </tr>
 <tr>
  <td class="cell2" width="25%">
   <span style="font-weight:bold;">{L.Title}</span>
  </td>
  <td class="cell1" colspan="2">
   <input type="text" name="title" value="{TITLE}" tabindex="2"  size="60" />
  </td>
 </tr>
 <tr>
	 <td class="cell2" valign="top"><span style="font-weight:bold;">{L.Message}</span></td>
	 <td class="cell1" colspan="2">
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
					<textarea name="body" tabindex="2" rows="13" cols="46" tabindex="3">{BODY}</textarea>
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
							<td align="center"><a onclick="javascript:add_emoticon('{EMOTICON_CODE}');"><img src="{C.smilies_url}/{EMOTICON_URL}" alt="{EMOTICON_TITLE}" title="{EMOTICON_TITLE}" /></a></td>
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
	</td>
 </tr>
 <tr>
  <th colspan="3">
   <input type="submit" name="Submit" value="{L.Submit}" />  <input type="reset" value="{L.Reset}" />
  </th>
 </tr>
</table>
</form>
