			<table>
				<tr>
					<th>{L.code}</th>
					<th>{L.image}</th>
					<th>{L.image_url}</th>
					<th>{L.name}</th>
					<th>{L.operations}</th>
				</tr>
				
				<!-- BLOCK smilieslist_item -->
				<tr>
					<td>
					{CODE}
					</td>
					<td>
						<img src='../images/smilies/{URL}'></td><td class="cell1">{URL}
					</td>
					<td>
					{NAME}
					</td>
					<td>
						<a href='smilies.php?func=edit&id={ID}'>{L.Edit}</a> - <a href='smilies.php?func=delete&id={ID}'>{L.Delete}</a>
					</td>
				</tr>
				<!-- END BLOCK smilieslist_item -->
			</table>
