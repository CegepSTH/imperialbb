			<div class="block-form-admin">
				<form action="usergroups.php?func=save_permissions" method="post">
					{CSRF_TOKEN}
					<h3>{L.permissions} - {GROUP_NAME}</h3>
					<div class="form-admin-row" style="width:95%;"><!--
						--><div class="form-admin-cell no-border" style="min-width: 250px;">
							<span><strong>{L.forum_name}</strong></span>
						</div><!--
						--><div class="form-admin-cell">
							<span><strong>{L.read}</strong></span>
						</div><!--
						--><div class="form-admin-cell">
							<span><strong>{L.post}</strong></span>
						</div><!--
						--><div class="form-admin-cell">
							<span><strong>{L.reply}</strong></span>
						</div><!--
						--><div class="form-admin-cell">
							<span><strong>{L.vote}</strong></span>
						</div><!--
						--><div class="form-admin-cell">
							<span><strong>{L.create_poll}</strong></span>
						</div><!--
						--><div class="form-admin-cell">
							<span><strong>{L.moderate}</strong></span>
						</div>
					</div>
					
					<!-- BLOCK forumslist_item -->
					<div class="form-admin-row" style="width:95%;"><!--
						--><div class="form-admin-cell no-border" style="min-width: 250px;">
							<span>{FORUM_NAME}</span>
						</div><!--
						--><div class="form-admin-cell">
							<select name="{FORUM_ID}[Read]">
								<option value="2" {READ_DEFAULT}>{L.default}</option>
								<option value="1" {READ_TRUE}>{L.true}</option>
								<option value="0" {READ_FALSE}>{L.false}</option>
							</select>
						</div><!--
						--><div class="form-admin-cell">
							<select name="{FORUM_ID}[Post]">
								<option value="2" {POST_DEFAULT}>{L.default}</option>
								<option value="1" {POST_TRUE}>{L.true}</option>
								<option value="0" {POST_FALSE}>{L.false}</option>
							</select>
						</div><!--
						--><div class="form-admin-cell">
							<select name="{FORUM_ID}[Reply]">
								<option value="2" {REPLY_DEFAULT}>{L.default}</option>
								<option value="1" {REPLY_TRUE}>{L.true}</option>
								<option value="0" {REPLY_FALSE}>{L.false}</option>
							</select>
						</div><!--
						--><div class="form-admin-cell">
							<select name="{FORUM_ID}[Poll]">
								<option value="2" {POLL_DEFAULT}>{L.default}</option>
								<option value="1" {POLL_TRUE}>{L.true}</option>
								<option value="0" {POLL_FALSE}>{L.false}</option>
							</select>
						</div><!--
						--><div class="form-admin-cell">
							<select name="{FORUM_ID}[Create_Poll]">
								<option value="2" {CREATE_POLL_DEFAULT}>{L.default}</option>
								<option value="1" {CREATE_POLL_TRUE}>{L.true}</option>
								<option value="0" {CREATE_POLL_FALSE}>{L.false}</option>
							</select>
						</div><!--
						--><div class="form-admin-cell">
							<select name="{FORUM_ID}[Mod]">
								<option value="2" {MOD_DEFAULT}>{L.default}</option>
								<option value="1" {MOD_TRUE}>{L.true}</option>
								<option value="0" {MOD_FALSE}>{L.false}</option>
							</select>
						</div><!--
						--></div>
					<!-- END BLOCK forumslist_item -->
					
					<div class="form-admin-row right no-border" style="width:95%;">
						<input type="submit" name="edit_permissions" value="{L.save_permissions}">
					</div>
				</form>
			</div>
