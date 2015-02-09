<!-- BLOCK error -->
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
<!-- END BLOCK error -->
<link rel="stylesheet" type="text/css" href="{T.TEMPLATE_PATH}/newstyle.css" />
<form action="" method="post">
	<div class="rTable2">
		<div class="rTableRow2">
			<div class="rTableHead2">
				<strong>{ACTION}</strong>
			</div>
		</div>

		<div class="rTableRow2">
			{L.Language_Pack_Upload_Msg}
		</div>

		<div class="rTableRow2">
			<div class="rTableCell2">{L.Name}</div>
			<div class="rTableCell2">
				<input type="text" id="Name" name="name" value="{NAME}" />
			</div>
		</div>

		<div class="rTableCell2">{L.Folder}</div>
		<div class="rTableCell2">
			<input type="text" id="Folder" name="folder" value="{FOLDER}" />
		</div>

		<div class="rTableCell2">{L.Usable}</div>
		<div class="rTableCell2">
			<input type="checkbox" name="usable" {USABLE} />
		</div>
	</div>

	<p>
		<input type="submit" name="Submit" class="formbutton" value="{L.Submit}" />
		<input type="reset" name="reset" class="formbutton" value="{L.Reset}" />
	</p>
</form>
