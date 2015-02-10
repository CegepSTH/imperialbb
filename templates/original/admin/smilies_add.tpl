			<div class="block-form-admin">
				<form action="smilies.php?func=add" method="post">
					{CSRF_TOKEN}
					<h3>{L.add_smiley}</h3>
					<div class="form-admin-row">
						<label for="name">
							<span>{L.name}:</span>
						</label>
						<label>
							<input id="name" name="name" placeholder="{L.name}" type="text" required>
						</label>
					</div>
					<div class="form-admin-row">
						<label for="code">
							<span>{L.code}:</span>
						</label>
						<label>
							<input id="code" name="code" placeholder="" type="text" required>
						</label>
					</div>
					<div class="form-admin-row">
						<label for="url">
							<span>{L.url}:</span>
						</label>
						<label>
							<input id="url" name="url" placeholder="" type="text" required>
						</label>
					</div>
					<div class="form-admin-row right no-border">
						<input type="submit" name="Submit" value="{L.add_smiley}">
					</div>
				</form>
			</div>
