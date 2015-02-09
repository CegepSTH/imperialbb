			<div class="block-form-admin">
				<form action="usergroups.php?func=save_edit" method="post">
					{CSRF_TOKEN}
					<h3>{L.edit_usergroup}</h3>
					<div class="form-admin-row">
						<label for="usergroupName">
							<span>{L.usergroup_name}:</span>
						</label>
						<label>
							<input id="usergroupName" name="usergroupName" placeholder="{L.usergroup_name}" value="{USERGROUP_NAME}" type="text" required>
						</label>
					</div>
					<div class="form-admin-row">
						<label for="usergroupDescription" style="vertical-align:top;">
							<span>{L.usergroup_desc}:</span>
						</label>
						<label>
							<textarea id="usergroupDescription" name="usergroupDescription" placeholder="{L.usergroup_desc}">{USERGROUP_DESC}</textarea>
						</label>
					</div>
					<div class="form-admin-row right no-border">
						<input type="submit" value="{L.save}">
					</div>
				</form>
			</div>
