<script>
 function changetype(type) {
         if(type == 1) {
                 document.edit_bbcode.begin_ext.disabled = true;
                 document.edit_bbcode.end_ext.disabled = true;
         } else {
                 document.edit_bbcode.begin_ext.disabled = false;
                 document.edit_bbcode.end_ext.disabled = false;
         }
 }
</script>

<table width="100%" class="maintable">
 <tr>
  <th colspan="2">
   {L.Add_BBCode}
  </th>
 </tr>
 <form method="post" action="" name="edit_bbcode">
 <tr>
  <td class="cell2">{L.Name}</td><td class="cell1"><input type="text" name="Name" value="{NAME}"></td>
 </tr>
 <tr>
  <td class="cell2">{L.Search}</td><td class="cell1"><input type="text" name="Search" value="{SEARCH}"></td>
 </tr>
 <tr>
  <td class="cell2">{L.Replace}</td><td class="cell1"><input type="text" name="Replace" value="{REPLACE}"></td>
 </tr>
 <tr>
  <th colspan="2">
   {L.Advanced_BBCode_Options}
  </th>
 </tr>
 <tr>
  <td class="cell2">{L.Type}<br />{L.Simple} = &lt;b&gt;[{L.Text}]&lt;/b&gt;<br />{L.Complex_1} = &lt;a href="[{L.Text} 1]"&gt;[{L.Text} 2]&lt;/a&gt;<br />{L.Complex_2} = &lt;img src="[{L.Text}]"&gt;</td>
  <td class="cell1">
   <select name="type" onchange="changetype(this.options[this.selectedIndex].value);">
    <!-- BEGIN type_options -->
    <option value="{TYPE.VALUE}" {TYPE.SELECTED}>{TYPE.NAME}</option>
    <!-- END type_options -->
   </td>
 </tr>
 <tr>
  <td class="cell2">{L.Begining_Extension} ({L.EG} href=") :</td><td class="cell1"><input type="text" name="begin_ext" value="{BEGIN_EXT}"></td>
 </tr>
 <tr>
  <td class="cell2">{L.End_Extension} ({L.EG} ") :</td><td class="cell1"><input type="text" name="end_ext" value="{END_EXT}"></td>
 </tr>
 <tr>
  <th colspan="2">
   <input type="submit" name="Submit" value="{L.Add_BBCode}" /> <input type="reset" value="{L.Reset}" />
  </th>
 </tr>
 </form>
</table>
<script>
if(document.edit_bbcode.type.options[document.edit_bbcode.type.selectedIndex].value == 1) {
                 document.edit_bbcode.begin_ext.disabled = true;
                 document.edit_bbcode.end_ext.disabled = true;
} else {
                 document.edit_bbcode.begin_ext.disabled = false;
                 document.edit_bbcode.end_ext.disabled = false;
}
</script>