<table width="100%" align="center" class="maintable">
	<tr>
		<th colspan="4" height="25">{L.Manage_Templates}</th>
	</tr>
	<tr>
		<td class="desc_row" height="25" align="center">{L.Name}</td><td class="desc_row" align="center">{L.Folder}</td><td class="desc_row" width="100" align="center">{L.Usable}</td><td class="desc_row" width="150" align="center">###</td>
	</tr>
	<!-- BEGIN template_row -->
	<tr>
		<td class="cell2">{NAME}</td>
		<td class="cell1">{FOLDER}</td>
		<td class="cell2" align="center"><input type="checkbox" disabled="disabled" {USABLE}</td>
		<td class="cell1" align="center">
			<a href='template.php?func=edit&id={ID}'>{L.Edit}</a> - <a href='template.php?func=delete&id={ID}'>{L.Delete}</a>
		</td>
	</tr>
	<!-- END template_row -->
</table>