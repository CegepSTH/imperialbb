
<table width="100%" align="center" class="maintable">
 <tr>
  <th height="25">{L.Name}</th><th>{L.Minimum_Posts}</th><th>{L.Color}</th><th>{L.Bold}</th><th>{L.Underline}</th><th>{L.Italics}</th><th>###</th>
 </tr>
 <tr>
  <td colspan="7"><h4>{L.Displayed_On_Index}</h4></td>
 </tr>
 
 <!-- BLOCK displayed_rank_row -->
 <tr>
  <td><span style="{RANK_STYLE}">{NAME}</span></td><td class="cell1" width="75" align="center">{MINIMUM_POSTS}</td><td class="cell2" width="75" align="center"><span style="color:{COLOR};">{COLOR}</span></td><td class="cell1" align="center" width="75"><input type="checkbox" disabled="disabled" {BOLD}/></td><td class="cell2" align="center" width="75"><input type="checkbox" disabled="disabled" {UNDERLINE}/></td><td class="cell1" align="center" width="75"><input type="checkbox" disabled="disabled" {ITALICS}/></td>
  <td width="175" align="center">
   <a href='ranks.php?func=edit&id={ID}'>{L.Edit}</a> - <a href='ranks.php?func=delete&id={ID}'>{L.Delete}</a> - <a href='ranks.php?move=up&id={ID}'>{L.Up}</a> - <a href='ranks.php?move=down&id={ID}'>{L.Down}</a>
  </td>
 </tr>
 <!-- END BLOCK displayed_rank_row -->
 
 <tr>
  <td colspan="7"><h4>{L.Not_Displayed_On_Index}</h4></td>
 </tr>
 
 <!-- BLOCK not_displayed_rank_row -->
 <tr>
  <td><span style="{RANK_STYLE}">{NAME}</span></td><td class="cell1" width="75" align="center">{MINIMUM_POSTS}</td><td class="cell2" width="75" align="center"><span style="color:{COLOR};">{COLOR}</span></td><td class="cell1" align="center" width="75"><input type="checkbox" disabled="disabled" {BOLD}/></td><td class="cell2" align="center" width="75"><input type="checkbox" disabled="disabled" {UNDERLINE}/></td><td class="cell1" align="center" width="75"><input type="checkbox" disabled="disabled" {ITALICS}/></td>
  <td width="175" align="center">
   <a href='ranks.php?func=edit&id={ID}'>{L.Edit}</a> - <a href='ranks.php?func=delete&id={ID}'>{L.Delete}</a>
  </td>
 </tr>
 <!-- END BLOCK not_displayed_rank_row -->
 
</table>
<br />
<input type="button" value="{L.Create_New_Rank}" onclick="window.location.href='ranks.php?func=add'" />
<br />
