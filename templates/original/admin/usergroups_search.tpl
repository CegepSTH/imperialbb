			<div class="block-form-admin">
				<form action="usergroups.php?func=edit" method="post">
					{CSRF_TOKEN}
					<h3>{L.search_usergroups}</h3>
					<div class="form-admin-row">
						<label for="usergroup">
							<span>{L.usergroup}:</span>
						</label>
						<label>
							<select id="usergroupslist" onchange="fillUsername()">
								<option value=""></option>
							<!-- BLOCK usergroupslist_item -->
								<option value="{USERGROUP_NAME}">{USERGROUP_NAME}</option>
							<!-- END BLOCK usergroupslist_item -->
							</select>
							<br />
							<input id="usergroup" name="usergroup" placeholder="{L.usergroup_name}" type="text" required autofocus>
						</label>
					</div>
					<div class="form-admin-row right no-border">
						<input type="submit" style="float:left;" name="creategroup" value="{L.create_group}"> &nbsp;
						<input type="submit" name="editgroup" value="{L.edit_group}"> &nbsp;
						<input type="submit" name="edit_permissions" value="{L.edit_permissions}">
					</div>
				</form>
			</div>


<!--// Javascript functions. -->
			<script type="text/javascript">
				function fillUsername() {
					var field = document.getElementById("usergroup");
					var selected = document.getElementById("usergroupslist");
					field.value = selected.options[selected.selectedIndex].value;
				}
			</script>
