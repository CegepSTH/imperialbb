			<div class="block-form-admin">
				<form action="template.php?func=edit" method="post">
					{CSRF_TOKEN}
					<h3>{L.edit_template}</h3>
					<div class="form-admin-row">
						<label for="name">
							<span>{L.name}:</span>
						</label>
						<label>
							<input id="name" name="name" placeholder="{L.name}" value="{NAME}"type="text" required>
						</label>
					</div>
					<div class="form-admin-row">
						<label for="folder">
							<span>{L.folder}:</span>
						</label>
						<label>
							<input id="folder" name="folder" placeholder="{L.folder}" value="{FOLDER}" type="text" required>
						</label>
					</div>
					<div class="form-admin-row">
						<label for="usable">
							<span>{L.usable}:</span>
						</label>
						<label>
							<input type="checkbox" name="checkbox" id="usable" {USABLE}>
						</label>
					</div>
					<div class="form-admin-row right no-border">
						<input type="submit" name="Submit" value="{L.save_template}">
					</div>
				</form>
			</div>
