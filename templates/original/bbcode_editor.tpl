<script type="text/javascript">
	var bbcodeTarget = null;

	function setBBCodeTarget(elementId) {
		bbcodeTarget = document.getElementById(elementId);
	}

	function add_bbcode(text,attrib) {
		if(bbcodeTarget == null) {
			return;
		}

		if(attrib != undefined) {
			attrib = "=" + attrib;
		} else {
			attrib = "";
		}

		bbcodeTarget.value += "[" + text + attrib + "]" +
		"[/" + text + "]";
		bbcodeTarget.focus();
	}
</script>
<div class="bbcode-editor">

	<div class="button-row">

		<input
			type="button"
			name="bold"
			value="{L.B}"
			accesskey="b"
			onclick="javascript:add_bbcode('b');" />

		<input
			type="button"
			name="underline"
			value="{L.U}"
			accesskey="u"
			onclick="javascript:add_bbcode('u');" />

		<input
			type="button"
			name="italics"
			value="{L.I}"
			accesskey="i"
			onclick="javascript:add_bbcode('i');" />

		<input
			type="button"
			name="quote"
			value="{L.Quote}"
			accesskey="q"
			onclick="javascript:add_bbcode('quote');" />

		<input
			type="button"
			name="code"
			value="{L.Code}"
			accesskey="c"
			onclick="javascript:add_bbcode('code');" />

		<input
			type="button"
			name="url"
			value="{L.URL}"
			accesskey=""
			onclick="javascript:add_bbcode('url');" />

		<input
			type="button"
			name="image"
			value="{L.Img}"
			accesskey=""
			onclick="javascript:add_bbcode('img');" />
	</div>

	<div class="font-size-selector">
		{L.Font_Size}
		<select name="font_size" onchange="javascript:add_bbcode('size', this.options[this.selectedIndex].value)">
			<option value="1">{L.Very_Small}</option>
			<option value="1.5">{L.Small}</option>
			<option value="2">{L.Medium}</option>
			<option value="3">{L.Large}</option>
			<option value="4">{L.Very_Large}</option>
		</select>
	</div>

	<div class="font-color-selector">
		{L.Font_Color}
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
	</div>
</div>
