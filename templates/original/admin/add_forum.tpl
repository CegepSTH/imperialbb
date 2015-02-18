<script language=javascript type='text/javascript'>
function change_perm_type() {
        if (document.getElementById) {
                var style1 = document.getElementById('advanced_perm').style;
                var style2 = document.getElementById('simple_perm').style;
                style1.display = style1.display? "":"block";
                style2.display = style2.display? "":"none";
        } else if (document.all) {
                var style1 = document.all['advanced_perm'].style;
                var style2 = document.all['simple_perm'].style;
                style1.display = style1.display? "":"block";
                style2.display = style2.display? "":"none";
        } else if(document.layers) {
                var style1 = document.layers['advanced_perm'].style;
                var style2 = document.layers['simple_perm'].style;
                style1.display = style1.display? "":"block";
                style2.display = style2.display? "":"none";
        }
}
</script>
<style>
div#simple_perm {
        display: block;
}
div#advanced_perm {
        display: none;
}
</style>
<!-- BLOCK error -->
<table width="100%" class="maintable">
 <tr>
  <th>{L.The_following_errors_occoured}</th>
 </tr>
 <tr>
  <td class="cell2">
   {ERROR}
  </td>
 </tr>
</table>
<!-- END BLOCK error -->
<form method="post" action="">
<table width="100%" class="maintable">
 <tr>
  <th height="25">
   {L.Add_Forum}
  </th>
 </tr>
 <tr>
  <td class="cell2">
   {L.Category_Or_Subforum} : <select name="cid">
   <!-- BLOCK category_select -->
    <option value="{CAT_ID}" style="{CAT_STYLE}" {SELECTED}>{CAT_PREFIX} {CAT_NAME}</option>
   <!-- END BLOCK category_select -->
   </select>
  </td>
 </tr>
 <tr>
  <td class="cell2">
   <table width="100%">
    <tr>
     <td align="right" width="150">{L.Forum_Name}</td><td><input type="text" name="name" value="{NAME}" style="width:98%;"><br /></td>
    </tr>
    <tr>
     <td align="right">{L.Forum_Description}</td><td><textarea name="description" rows="8" style="width:98%;">{DESCRIPTION}</textarea></td>
    </tr>
    <tr>
     <td align="right" class="cell2">{L.Redirect_Url}<br /><span class="small_text">{L.Redirect_Url_Desc}</span></td><td class="cell2"><input type="text" name="redirect_url" value="{REDIRECT_URL}" style="width:98%;"></td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <th height="25">
   {L.Permissions}
  </th>
 </tr>
  <td class="cell2">
   {L.Advanced_Permissions} : <input type="checkbox" name="advanced_permissions" onclick="change_perm_type()">
  </td>
 </tr>
 <tr>
  <td class="cell2">
   <div id="simple_perm">
    <select name="simple_select">
     <option value="0">{L.Banned}</option>
     <option selected value="1">{L.Guest}</option>
     <option value="2">{L.Validating}</option>
     <option value="3">{L.Registered}</option>
     <option value="3H">{L.Registered} [{L.Hidden}]</option>
     <option value="4">{L.Moderator}</option>
     <option value="4H">{L.Moderator} [{L.Hidden}]</option>
     <option value="5">{L.Admin}</option>
     <option value="5H">{L.Admin} [{L.Hidden}]</option>
    </select>
   </div>
   <div id="advanced_perm">
    <table>
     <tr>
      <td>{L.Read}</td><td>{L.Post}</td><td>{L.Reply}</td><td>{L.Vote}</td><td>{L.Create_Poll}</td><td>{L.Moderate}</td>
     </tr>
     <tr>
      <td>
       <select name="Read">
        <option value="0">{L.Banned}</option>
        <option selected value="1">{L.Guest}</option>
        <option value="2">{L.Validating}</option>
        <option value="3">{L.Registered}</option>
        <option value="4">{L.Moderator}</option>
        <option value="5">{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Post">
        <option value="0">{L.Banned}</option>
        <option selected value="1">{L.Guest}</option>
        <option value="2">{L.Validating}</option>
        <option value="3">{L.Registered}</option>
        <option value="4">{L.Moderator}</option>
        <option value="5">{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Reply">
        <option value="0">{L.Banned}</option>
        <option selected value="1">{L.Guest}</option>
        <option value="2">{L.Validating}</option>
        <option value="3">{L.Registered}</option>
        <option value="4">{L.Moderator}</option>
        <option value="5">{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Poll">
        <option value="0">{L.Banned}</option>
        <option selected value="1">{L.Guest}</option>
        <option value="2">{L.Validating}</option>
        <option value="3">{L.Registered}</option>
        <option value="4">{L.Moderator}</option>
        <option value="5">{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Create_Poll">
        <option value="0">{L.Banned}</option>
        <option selected value="1">{L.Guest}</option>
        <option value="2">{L.Validating}</option>
        <option value="3">{L.Registered}</option>
        <option value="4">{L.Moderator}</option>
        <option value="5">{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Mod">
        <option value="0">{L.Banned}</option>
        <option selected value="1">{L.Guest}</option>
        <option value="2">{L.Validating}</option>
        <option value="3">{L.Registered}</option>
        <option value="4">{L.Moderator}</option>
        <option value="5">{L.Admin}</option>
       </select>
      </td>
     </tr>
    </table>
   </div>
  </td>
 </tr>
  <th>
   <input type="submit" name="Submit" value="{L.Submit}">
  </th>
 </tr>
</table>
</form>
