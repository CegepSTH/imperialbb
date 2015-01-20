<table width="100%" align="center" class="maintable">
 <tr>
  <th>{L.BBCode_Bug_Warning}</th>
 </tr>
 <tr>
  <td><br /></td>
 </tr>
 <tr>
  <th>{L.Simple_BBCode}</th>
 </tr>
 <tr>
  <td>
   <table width="100%">
    <tr>
     <td width="25%" class="desc_row" align="center">{L.Name}</td>
     <td width="25%" class="desc_row" align="center">{L.Search}</td>
     <td width="25%" class="desc_row" align="center">{L.Replace}</td>
     <td width="25%" class="desc_row" align="center">###</td>
    </tr>
    <!-- BEGIN simple_bbcode_row -->
    <tr>
     <td align="center" class="cell1">{NAME}<br />&lt;{REPLACE}&gt;[{L.Text}]&lt;/{REPLACE}&gt;</td><td class="cell2">{SEARCH}</td><td class="cell1">{REPLACE}</td>
     <td class="cell2">
      <a href="?act=bbcode&func=edit&id={ID}">{L.Edit}</a> - <a href="?act=bbcode&func=delete&id={ID}">{L.Delete}</a>
     </td>
    </tr>
    <!-- END simple_bbcode_row -->
   </table>
  </td>
 </tr>
 <tr>
  <th>{L.Complex_BBCode}</th>
 </tr>
 <tr>
  <td>
   <table width="100%">
    <tr>
     <td width="13%" class="desc_row" align="center">{L.Name}</td>
     <td width="12%" class="desc_row" align="center">{L.Search}</td>
     <td width="12%" class="desc_row" align="center">{L.Replace}</td>
     <td width="20%" class="desc_row" align="center">{L.Beginning_Extension}</td>
     <td width="20%" class="desc_row" align="center">{L.End_Extension}</td>
     <td width="10%" class="desc_row" align="center">###</td>
    </tr>
    <!-- BEGIN complex1_bbcode_row -->
    <tr>
     <td colspan="6" align="center" class="desc_row" style="font-weight:normal;">
      &lt;{REPLACE} {BEGIN_EXT}[{L.Text}1]{END_EXT}&gt;[{L.Text}2]&lt;/{REPLACE}&gt;
     </td>
    </tr>
    <tr>
     <td class="cell1">{NAME}</td><td class="cell2">{SEARCH}</td><td class="cell1">{REPLACE}</td><td class="cell2">{BEGIN_EXT}</td><td class="cell1">{END_EXT}</td>
     <td class="cell2">
      <a href="?act=bbcode&func=edit&id={ID}">{L.Edit}</a> - <a href="?act=bbcode&func=delete&id={ID}">{L.Delete}</a>
     </td>
    </tr>
    <!-- END complex1_bbcode_row -->
   </table>
  </td>
 </tr>
 <tr>
  <td>
   <table width="100%">
    <tr>
     <td width="13%" class="desc_row" align="center">{L.Name}</td>
     <td width="12%" class="desc_row" align="center">{L.Search}</td>
     <td width="12%" class="desc_row" align="center">{L.Replace}</td>
     <td width="20%" class="desc_row" align="center">{L.Beginning_Extension}</td>
     <td width="20%" class="desc_row" align="center">{L.End_Extension}</td>
     <td width="13%" class="desc_row" align="center">###</td>
    </tr>
    <!-- BEGIN complex2_bbcode_row -->
    <tr>
     <td colspan="6" align="center" class="desc_row" style="font-weight:normal;">
      &lt;{REPLACE} {BEGIN_EXT}[{L.Text}]{END_EXT}&gt;
     </td>
    </tr>
    <tr>
     <td class="cell1">{NAME}</td><td class="cell2">{SEARCH}</td><td class="cell1">{REPLACE}</td><td class="cell2">{BEGIN_EXT}</td><td class="cell1">{END_EXT}</td>
     <td class="cell2">
      <a href="?act=bbcode&func=edit&id={ID}">{L.Edit}</a> - <a href="?act=bbcode&func=delete&id={ID}">{L.Delete}</a>
     </td>
    </tr>
    <!-- END complex2_bbcode_row -->
   </table>
   <br />
   <a href="?act=bbcode&func=add">{L.Add_BBCode}</a>
  </td>
 </tr>
</table>
