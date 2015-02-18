<script language=javascript type='text/javascript'>
function change_perm_type() {
        if (document.getElementById) {
                var style1 = document.getElementById('advanced_perm').style;
                var style2 = document.getElementById('simple_perm').style;
                style1.display = style1.display == "block"? "none":"block";
                style2.display = style2.display == "none"? "block":"none";
        } else if (document.all) {
                var style1 = document.all['advanced_perm'].style;
                var style2 = document.all['simple_perm'].style;
                style1.display = style1.display == "block"? "none":"block";
                style2.display = style2.display == "none"? "block":"none";
        } else if(document.layers) {
                var style1 = document.layers['advanced_perm'].style;
                var style2 = document.layers['simple_perm'].style;
                style1.display = style1.display == "block"? "none":"block";
                style2.display = style2.display == "none"? "block":"none";
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

<form method="post" action="" name="edit_forum">
<!-- BLOCK error -->
<table width="100%" class="maintable">
 <tr>
  <th>
   {L.Error}
  </th>
 </tr>
 <tr>
  <td class="cell1">
   {ERROR}
  </td>
 </tr>
</table>
<br />
<!-- END BLOCK error -->
<table width="100%" class="maintable">
 <tr>
  <th height="25">
   {L.Edit_Forum}
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
  <td>
   <table width="100%">
    <tr>
     <td align="right" width="100" class="cell2">{L.Forum_Name}</td><td class="cell2"><input type="text" name="name" value="{NAME}" style="width:98%;"><br /></td>
    </tr>
    <tr>
     <td align="right" class="cell2">{L.Forum_Description}</td><td class="cell2"><textarea name="description" rows="8" style="width:98%;">{DESCRIPTION}</textarea></td>
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
 <tr>
  <td class="cell2">
   {L.Advanced_Permissions} : <input type="checkbox" name="advanced_permissions" onclick="change_perm_type()" {ADV_CHECKED}>
  </td>
 </tr>
 <tr>
  <td class="cell2">
   <div id="simple_perm">
    <select name="simple_select">
     <option value="0"{S0}>{L.Banned}</option>
     <option value="1"{S1}>{L.Guest}</option>
     <option value="2"{S2}>{L.Validating}</option>
     <option value="3"{S3}>{L.Registered}</option>
     <option value="3H"{S3H}>{L.Registered} [{L.Hidden}]</option>
     <option value="4"{S4}>{L.Moderator}</option>
     <option value="4H"{S4H}>{L.Moderator} [{L.Hidden}]</option>
     <option value="5"{S5}>{L.Admin}</option>
     <option value="5H"{S5H}>{L.Admin} [{L.Hidden}]</option>
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
        <option value="0"{E0}>{L.Banned}</option>
        <option value="1"{E1}>{L.Guest}</option>
        <option value="2"{E2}>{L.Validating}</option>
        <option value="3"{E3}>{L.Registered}</option>
        <option value="4"{E4}>{L.Moderator}</option>
        <option value="5"{E5}>{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Post">
        <option value="0"{P0}>{L.Banned}</option>
        <option value="1"{P1}>{L.Guest}</option>
        <option value="2"{P2}>{L.Validating}</option>
        <option value="3"{P3}>{L.Registered}</option>
        <option value="4"{P4}>{L.Moderator}</option>
        <option value="5"{P5}>{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Reply">
        <option value="0"{R0}>{L.Banned}</option>
        <option value="1"{R1}>{L.Guest}</option>
        <option value="2"{R2}>{L.Validating}</option>
        <option value="3"{R3}>{L.Registered}</option>
        <option value="4"{R4}>{L.Moderator}</option>
        <option value="5"{R5}>{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Poll">
        <option value="0"{V0}>{L.Banned}</option>
        <option value="1"{V1}>{L.Guest}</option>
        <option value="2"{V2}>{L.Validating}</option>
        <option value="3"{V3}>{L.Registered}</option>
        <option value="4"{V4}>{L.Moderator}</option>
        <option value="5"{V5}>{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Create_Poll">
        <option value="0"{CP0}>{L.Banned}</option>
        <option value="1"{CP1}>{L.Guest}</option>
        <option value="2"{CP2}>{L.Validating}</option>
        <option value="3"{CP3}>{L.Registered}</option>
        <option value="4"{CP4}>{L.Moderator}</option>
        <option value="5"{CP5}>{L.Admin}</option>
       </select>
      </td>
      <td>
       <select name="Mod">
        <option value="0"{M0}>{L.Banned}</option>
        <option value="1"{M1}>{L.Guest}</option>
        <option value="2"{M2}>{L.Validating}</option>
        <option value="3"{M3}>{L.Registered}</option>
        <option value="4"{M4}>{L.Moderator}</option>
        <option value="5"{M5}>{L.Admin}</option>
       </select>
      </td>
     </tr>
    </table>
   </div>
  </td>
 </tr>
 <tr>
  <th>
   <input type="submit" name="Submit" value="{L.Submit}" />
  </th>
 </tr>
 </table>
</form>

<script>
if(document.edit_forum.advanced_permissions.checked) {
        if (document.getElementById) {
                document.getElementById('simple_perm').style.display = "none";
                document.getElementById('advanced_perm').style.display = "block";
        } else if (document.all) {
                document.all['simple_perm'].style.display = "none";
                document.all['advanced_perm'].style.display = "block";
        } else if(document.layers) {
                document.layers['advanced_perm'].style.display = "none";
                document.layers['simple_perm'].style.display = "block";
        }
} else {
        if (document.getElementById) {
                document.getElementById('simple_perm').style.display = "block";
                document.getElementById('advanced_perm').style.display = "none";
        } else if (document.all) {
                document.all['simple_perm'].style.display = "block";
                document.all['advanced_perm'].style.display = "none";
        } else if(document.layers) {
                document.layers['advanced_perm'].style.display = "block";
                document.layers['simple_perm'].style.display = "none";
        }
}
</script>
