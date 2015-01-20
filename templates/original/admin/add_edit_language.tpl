<!-- BEGIN error -->
<table width="100%" class="maintable">
	<tr>
		<th height="25">{L.Error}</th>
	</tr>
	<tr>
		<td class="cell2">
			{ERROR}
		</td>
	</tr>
</table><br />
<!-- END error -->
<table width="100%" class="maintable">
	<tr>
		<th colspan="2" height="25">
			{ACTION}
		</th>
	</tr>
	<tr>
		<td class="desc_row" height="25" colspan="2">
			{L.Language_Pack_Upload_Msg}
		</td>
	</tr>
	<form method="post" action="">
	<tr>
		<td width="30%" class="cell2">{L.Name}</td><td class="cell1"><input type="text" name="name" value="{NAME}" /></td>
	</tr>
	<tr>
		<td class="cell2">{L.Folder}</td><td class="cell1"><input type="text" name="folder" value="{FOLDER}" /></td>
	</tr>
	<tr>
		<td class="cell2">{L.Usable}</td><td class="cell1"><input type="checkbox" name="usable" {USABLE} /></td>
	</tr>
	<tr>
		<th colspan="2" height="25">
			<input type="submit" name="Submit" value="{L.Submit}" />  <input type="reset" value="{L.Reset}" />
		</th>
	</tr>
</table>