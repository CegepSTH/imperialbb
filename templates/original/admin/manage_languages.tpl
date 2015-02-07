<link rel="stylesheet" type="text/css" href="{T.TEMPLATE_PATH}/newstyle.css" />
<div class="table">
	<h4>{L.Manage_Languages}</h4>
	<table>
		<tr>
			<th>{L.Name}</th>
			<th>{L.Folder}</th>
			<th>{L.Usable}</th>
			<th>###</th>
		</tr>
		<!-- BLOCK language_row -->
		<tr>
			<td>{NAME}</td>
			<td>{FOLDER}</td>
			<td><input type="checkbox" disabled="disabled" {USABLE}></td>
			<td>
				<a href='language.php?func=edit&id={ID}'>{L.Edit}</a> - <a href='language.php?func=delete&id={ID}'>{L.Delete}</a>
			</td>
		</tr>
		<!-- END BLOCK language_row -->
	</table>
</div>
