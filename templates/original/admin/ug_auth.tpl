<table width="100%" align="center" class="maintable">
 <tr>
  <th colspan="7" height="25">
   {L.Usergroup_Permissions} - {GROUP_NAME}
  </th>
 </tr>
 <form method="post" action="?act=usergroups&func=permissions&id={GROUP_ID}">
 <tr>
  <td align="center" class="desc_row">{L.Forum_Name}</td>
  <td align="center" class="desc_row">{L.Read}</td>
  <td align="center" class="desc_row">{L.Post}</td>
  <td align="center" class="desc_row">{L.Reply}</td>
  <td align="center" class="desc_row">{L.Vote}</td>
  <td align="center" class="desc_row">{L.Create_Poll}</td>
  <td align="center" class="desc_row">{L.Moderate}</td>
 </tr>
 <!-- BEGIN forum_permissions -->
 <tr>
  <td width="40%" class="cell2">{FORUM_NAME}</td>
  <td width="10%" align="right" class="cell1"><select name="{FORUM_ID}[Read]"><option value="2" {READ_DEFAULT}>{L.Default}</option><option value="1" {READ_TRUE}>{L.True}</option><option value="0"{READ_FALSE}>{L.False}</option></select></td>
  <td width="10%" align="right" class="cell1"><select name="{FORUM_ID}[Post]"><option value="2" {POST_DEFAULT}>{L.Default}</option><option value="1" {POST_TRUE}>{L.True}</option><option value="0" {POST_FALSE}>{L.False}</option></select></td>
  <td width="10%" align="right" class="cell1"><select name="{FORUM_ID}[Reply]"><option value="2" {REPLY_DEFAULT}>{L.Default}</option><option value="1" {REPLY_TRUE}>{L.True}</option><option value="0"{REPLY_FALSE}>{L.False}</option></select></td>
  <td width="10%" align="right" class="cell1"><select name="{FORUM_ID}[Poll]"><option value="2" {POLL_DEFAULT}>{L.Default}</option><option value="1" {POLL_TRUE}>{L.True}</option><option value="0" {POLL_FALSE}>{L.False}</option></select></td>
  <td width="10%" align="right" class="cell1"><select name="{FORUM_ID}[Create_Poll]"><option value="2" {CREATE_POLL_DEFAULT}>{L.Default}</option><option value="1" {CREATE_POLL_TRUE}>{L.True}</option><option value="0"{CREATE_POLL_FALSE}>{L.False}</option></select></td>
  <td width="10%" align="right" class="cell1"><select name="{FORUM_ID}[Mod]"><option value="2" {MOD_DEFAULT}>{L.Default}</option><option value="1" {MOD_TRUE}>{L.True}</option><option value="0"{MOD_FALSE}>{L.False}</option></select></td>

 </tr>
 <!-- END forum_permissions -->
 <tr>
  <th colspan="7"><input type="submit" name="Submit" value="{L.Submit}" />  <input type="reset" value="{L.Reset}" /></th>
 </tr>
</table>