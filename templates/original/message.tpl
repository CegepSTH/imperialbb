			<div class="block-form-admin" style="text-align:center;">
				<h3>{L.message}</h3>
					<br />
					{MESSAGE_CONTENT}<br /><br />
					<a href="{RETURN_URL}">{L.do_not_wait}</a><br /><br />
			</div>

<!--// Automatic redirection. -->
			<script type="text/javascript">
				setTimeout(function(){
					document.location.replace("{RETURN_URL}");
				}, 5000);
			</script>
