			<div class="block-form-admin">
				<form action="users.php?func=save" method="post">
					{CSRF_TOKEN}
					<h3>{L.edit_user}</h3>
					<div class="form-admin-row">
						<label for="username">
							<span>{L.username}:</span>
						</label>
						<label>
							<input id="username" name="username" placeholder="{L.username}" value="{USERNAME}" type="text" required>
						</label>
					</div>
					<div class="form-admin-row">
						<label>
							<span>{L.birthday}:</span>
						</label>
						<label>
							<span>{BDAY_DAY} - {BDAY_MONTH} - {BDAY_YEAR}</span>
						</label>
					</div>
					<!--// Fill only changed is password -->
					<h4>{L.password_fill_only_if_changed}</h4>
					<div class="form-admin-row">
						<label for="new_password">
							<span>{L.new_password}:</span>
						</label>
						<label>
							<input type="password" name="new_password" id="new_password" placeholder="{L.new_password}" type="text">
						</label>
					</div>
					<div class="form-admin-row">
						<label for="new_password2">
							<span>{L.new_password_again}:</span>
						</label>
						<label>
							<input type="password" name="new_password2" id="new_password2" placeholder="{L.new_password_again}" type="text">
						</label>
					</div>
					<h4>{L.user_permissions}</h4>
					<div class="form-admin-row">
						<label for="rankslist">
							<span>{L.rank}:</span>
						</label>
						<label>
							<select id="rankslist" name="rankslist">
							<!-- BLOCK rankslist_item -->
								<option value="{RANK_ID}" {RANK_SELECTED}>{RANK_NAME}</option>
							<!-- END BLOCK rankslist_item -->
							</select>
						</label>
					</div>
					<div class="form-admin-row">
						<label for="usergroupslist">
							<span>{L.usergroup}:</span>
						</label>
						<label>
							<select id="usergroupslist" name="usergroupslist">
								<option value="">{L.usergroup_none}</option>
							<!-- BLOCK usergroupslist_item -->
								<option value="{UG_ID}" {UG_SELECTED}>{UG_NAME}</option>
							<!-- END BLOCK usergroupslist_item -->
							</select>
						</label>
					</div>
					<div class="form-admin-row">
						<label for="levelslist">
							<span>{L.userlevel}:</span>
						</label>
						<label>
							<select id="levelslist" name="levelslist">
							<!-- BLOCK levelslist_item -->
								<option value="{UL_ID}" {UL_SELECTED}>{UL_NAME}</option>
							<!-- END BLOCK levelslist_item -->
							</select>
						</label>
					</div>
					<h4>{L.user_preferences}</h4>
					<div class="form-admin-row">
						<label for="emailonpm">
							<span>{L.email_on_pm}:</span>
						</label>
						<label>
							<!-- BLOCK email_on_pm_true -->
								<input type="radio" name="emailonpm" value="true" checked>{L.yes}
								<input type="radio" name="emailonpm" value="false">{L.no}
							<!-- END BLOCK email_on_pm_true -->
							<!-- BLOCK email_on_pm_false -->
								<input type="radio" name="emailonpm" value="true">{L.yes}
								<input type="radio" name="emailonpm" value="false" checked>{L.no}
							<!-- END BLOCK email_on_pm_false -->
						</label>
					</div>
					<div class="form-admin-row">
						<label for="website">
							<span>{L.website}:</span>
						</label>
						<label>
							<input id="website" name="website" value="{WEBSITE}" type="text">
						</label>
					</div>
					<div class="form-admin-row">
						<label for="location">
							<span>{L.location}:</span>
						</label>
						<label>
							<input id="location" name="location" value="{LOCATION}" type="text">
						</label>
					</div>
					<div class="form-admin-row">
						<label style="vertical-align:top;padding-top: 3px;" for="signature">
							<span>{L.user_signature}:</span>
						</label>
						<label>
							<textarea name="signature" id="signature">{SIGNATURE}</textarea>
						</label>
					</div>
					<div class="form-admin-row right no-border">
						<input type="submit" value="{L.submit}">
					</div>
				</form>
			</div>
