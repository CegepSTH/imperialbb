			<div class="block-form-admin">
				<form action="users.php?func=delete" method="post">
					{CSRF_TOKEN}
					<h3>{L.delete_user}</h3>
					<div class="form-admin-row">
						<label for="username">
							<span>{L.username}:</span>
						</label>
						<label>
							<select id="userlist" onchange="fillUsername()">
								<option value=""></option>
							<!-- BLOCK userlist_item -->
								<option value="{USERNAME}">{USERNAME}</option>
							<!-- END BLOCK userlist_item -->
							</select>
							<br />
							<input id="username" name="username" placeholder="{L.username}" type="text" required autofocus>
						</label>
					</div>
					<div class="form-admin-row right no-border">
						<input type="submit" value="{L.delete}">
					</div>
				</form>
			</div>


<!--// Javascript functions. -->
			<script type="text/javascript">
				function fillUsername() {
					var field = document.getElementById("username");
					var selected = document.getElementById("userlist");
					field.value = selected.options[selected.selectedIndex].value;
				}
			</script>
