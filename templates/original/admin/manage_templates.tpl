			<div class="table">
							
				<h4>Manage Template</h4>
				<table>
				<tr>
					<th>{L.name}</th>
					<th>{L.folder}</th>
					<th>{L.usable}</th>
					<th>{L.symbol_number_diese}</th>
				</tr>
				<!-- BLOCK templateslist_item -->
				<tr>
					<td>{NAME}</td>
					<td>{FOLDER}</td>
					<td><input type="checkbox" disabled="disabled" {USABLE}></td>
					<td><a href="template.php?func=edit&id={ID}">{L.edit}</a> - <a href="template.php?func=delete&id={ID}">{L.delete}</a></td>
				</tr>
				<!-- END BLOCK templateslist_item -->
			</table>
			</div>
