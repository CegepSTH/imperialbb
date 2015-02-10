			<div class="block-form-admin">
				<form action="smilies.php?func=edit&id={ID}" method="post">
					{CSRF_TOKEN}
					<h3>{L.edit_smiley}</h3>
					<div class="form-admin-row">
						<label for="name">
							<span>{L.name}:</span>
						</label>
						<label>
							<input id="name" name="name" placeholder="{L.name}" value="{NAME}" type="text" required>
						</label>
					</div>
					<div class="form-admin-row">
						<label for="code">
							<span>{L.code}:</span>
						</label>
						<label>
							<input id="code" name="code" placeholder="" value="{CODE}" type="text" required>
						</label>
					</div>
					<div class="form-admin-row">
						<label for="url">
							<span>{L.url}:</span>
						</label>
						<label>
							<input id="url" name="url" placeholder="" value="{URL}" type="text" required>
						</label>
					</div>
					<div class="form-admin-row right no-border">
						<input type="submit" name="Submit" value="{L.save_smiley}">
					</div>
				</form>
			</div>
