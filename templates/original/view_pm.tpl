<table width="100%">
 <tr>
  <td align="left" style="padding-left:5px;" valign="bottom"><a href="/">{C.site_name}</a> &raquo; <a href="/forum/">Forum</a> &raquo; <a href="pm.php">{L.PM_Manager}</a> &raquo; <b>{L.View_PM}</b></td>
 </tr>
</table>
<table width="100%" align="center" class="maintable">
 <tr>
  <th colspan="2" height="25">{TITLE}</th>
 </tr>
 <tr>
  <td width="200" class="cell1" valign="top" style="padding-top: 10px;">
   <div align="center">
    <b>{AUTHOR_USERNAME}</b><br /><br />
    <!-- BEGIN avatar -->
    <img src="{AUTHOR_AVATAR_LOCATION}" /><br />
    <!-- END avatar -->
	{AUTHOR_RANK}<br />
    <!-- BEGIN rank_image -->
    <img src="{AUTHOR_RANK_IMG}" alt="{AUTHOR_RANK}" title="{AUTHOR_RANK}" /><br />
    <!-- END rank_image -->
    <br /></div>
    <!-- BEGIN author_standard -->
    {L.Posts}: {AUTHOR_POSTS}<br />
    {L.Date_Joined}: {AUTHOR_JOINED}<br /><br />
    <!-- END author_standard -->
    <!-- BEGIN author_location -->
    {L.Location}: {AUTHOR_LOCATION}
    <!-- END author_location -->
   </div>
  </td>
  <td class="cell2" valign="top">
   <table width="100%" cellspacing="0">
    <tr>
     <td height="20">
   	  {DATE}
     </td>
    </tr>
    <tr>
     <td height="3" style="background:#d6d7d9;"></td>
    </tr>
    <tr>
     <td class="cell2">
      {BODY}<br />
   	  {AUTHOR_SIGNATURE}
     </td>
    </tr>
   </table><br />
   <div align="right"><a href="pm.php?func=send&username={AUTHOR_USERNAME}"><img src="{T.TEMPLATE_PATH}/images/reply.gif" /></div>
  </td>
 </tr>
</table>
