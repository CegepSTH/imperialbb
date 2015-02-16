<script type="text/javascript">
	var smilieTarget = null;

	function setSmilieTarget(elementId) {
		smilieTarget = document.getElementById(elementId);
	}

	function add_emoticon(text) {
		if(smilieTarget == null) {
			return;
		}

		smilieTarget.value += text;
		smilieTarget.focus();
	}
</script>
<div class="panel smilies-panel">
	<div class="panel-header alt">
		{L.Emoticons}
	</div>
	<div class="panel-body">
		<!-- BLOCK smilie_button -->
		<div class="smilie">
			<a onclick="javascript:add_emoticon('{EMOTICON_CODE}');">
				<img src="{C.smilies_url}/{EMOTICON_URL}" alt="{EMOTICON_TITLE}" title="{EMOTICON_TITLE}" />
			</a>
		</div>
		<!-- END BLOCK smilie_button -->
	</div>
	<div class="panel-footer">
		{L.Show_All_Emoticons}
	</div>
</div>
