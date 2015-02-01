
<script language="javascript" type="text/javascript">
function quick_reply() {
        if (document.getElementById) {
                var style2 = document.getElementById('quick_reply').style;
                style2.display = style2.display? "":"block";
        } else if (document.all) {
                var style2 = document.all['quick_reply'].style;
                style2.display = style2.display? "":"block";

        } else if(document.layers) {
                var style2 = document.layers['quick_reply'].style;
                style2.display = style2.display? "":"block";
        }
}
</script>
<style type="text/css">
div#quick_reply {
        display: none;
}
</style>

<table width="100%">
 <tr>
   <td align="left" style="padding-left:10px;"><a href="index.php">{C.site_name}</a><!-- BEGIN location_top_forum --> &raquo; <a href="view_forum.php?fid={LOCATION_FORUM_ID}">{LOCATION_FORUM_NAME}</a><!-- END location_top_forum --> &raquo; <b>{TOPIC_NAME}</b></td>
</tr>
<tr>
  <td width="250"><a href="posting.php?func=newtopic&fid={FORUM_ID}"><img src="{T.TEMPLATE_PATH}/images/new_topic.gif" /></a>&nbsp;&nbsp;&nbsp;<a href="posting.php?func=reply&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/reply.gif" /></a></td>
 </tr>
</table>
<table width="100%" align="center" class="maintable">
 <!-- BEGIN poll -->
 <tr>
  <th height="25" colspan="2">{L.Poll}</th>
 </tr>
 <tr>
  <td colspan="2" align="center" class="cell2">
   <table width="50%" cellspacing="2" cellpadding="2">
    <tr>
     <td colspan="2" height="25" align="center"><span style="font-weight:bold;">{POLL_TITLE}</span></td>
    </tr>
    <!-- BEGIN poll_vote_form -->
    <form action="" method="post">
	{CSRF_TOKEN}
    <!-- END poll_vote_form -->
    <!-- BEGIN SWITCH poll_choice -->
    <tr>
     <td align="right"><input type="radio" name="poll_vote_choice" value="{POLL_CHOICE_ID}" /></td>
     <td align="left">{POLL_CHOICE_NAME}</td>
    </tr>
    <!-- SWITCH poll_choice -->
    <tr>
     <td width="50%" align="right">{POLL_CHOICE_NAME}</td>
     <td align="left"><img src="{T.TEMPLATE_PATH}/images/poll_left.gif" /><img src="{T.TEMPLATE_PATH}/images/poll_bar.gif" width="{POLL_CHOICE_WIDTH}" height="12" /><img src="{T.TEMPLATE_PATH}/images/poll_right.gif" /> {PERCENTAGE}% ({NO_OF_VOTES} {L.Votes})</td>
    </tr>
    <!-- END SWITCH poll_choice -->
    <!-- BEGIN poll_vote_buttons -->
    <tr>
     <td colspan="2" align="center"><input type="submit" name="vote" value="{L.Vote}" />&nbsp;&nbsp;<input type="button" name="view_results" value="{L.View_Results}" onclick="window.location='view_topic.php?tid={TOPIC_ID}&view_poll_results=1'" />
    </tr>
    </form>
    <!-- END poll_vote_buttons -->
   </table>
  </td>
 </tr>
 <!-- END poll -->
 <tr>
  <th width="25%" height="25">{L.Author}</th>
  <th colspan="2">{L.Message}</th>
 </tr>
 <!-- BEGIN postrow -->
 <tr>
  <td class="cell2" valign="top" style="padding-top:10px;">
   <table width="100%" height="100%" cellspacing="0" style="margin-bottom: 20px;">
    <tr>
     <td>
      <div align="center">
       <b>{AUTHOR_USERNAME}</b><br /><br />
       <!-- BEGIN avatar -->
       <img src="{AUTHOR_AVATAR_LOCATION}" /><br />
       <!-- END avatar -->
       <!-- BEGIN rank_image -->
       <img src="{RANK_IMG_URL}" alt="{AUTHOR_RANK}" title="AUTHOR_RANK" />
       <!-- END rank_image -->
       {AUTHOR_RANK}<br /><br /></div>
       <!-- BEGIN author_standard -->
       {L.Posts}: {AUTHOR_POSTS}<br />
       {L.Date_Joined}: {AUTHOR_JOINED}<br /><br />
       <!-- END author_standard -->
       <!-- BEGIN author_location -->
       {L.Location}: {AUTHOR_LOCATION}
       <!-- END author_location -->
      </div>
     </td>
    </tr>
   </table>
  </td>
  <td class="cell2" valign="top" rowspan="2">
   <table width="100%" cellspacing="0">
    <tr>
     <td height="20">{DATE}</td>
     <td width="300" class="cell2" align="right" style="padding-right:5px;">
      <!-- BEGIN SWITCH mod_links -->
      <a href="posting.php?func=edit&pid={POST_ID}"><img src="{T.TEMPLATE_PATH}/images/edit_post.gif" /></a>&nbsp;&nbsp;<a href="mod.php?func=delete&pid={POST_ID}"><img src="{T.TEMPLATE_PATH}/images/delete_post.gif" /></a>
      <!-- SWITCH mod_links -->
      <a href="posting.php?func=edit&pid={POST_ID}"><img src="{T.TEMPLATE_PATH}/images/edit_post.gif" /></a>
      <!-- END SWITCH mod_links -->
      <!-- BEGIN quote_button -->
      <a href="posting.php?func=reply&tid={TOPIC_ID}&quote={POST_ID}"><img src="{T.TEMPLATE_PATH}/images/quote.gif" /></a>
      <!-- END quote_button -->
     </td>
    </tr>
    <tr>
     <td colspan="2" height="3" style="background:#d6d7d9;"></td>
    </tr>
    <tr>
     <td style="padding-top:10px; padding-bottom:10px;" valign="top" colspan="2">
      {TEXT}
      {SIGNATURE}
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td class="cell2" align="center" height="75">
   <!-- BEGIN pm_link --><a href="pm.php?func=send&username={AUTHOR_USERNAME}"><img src="{T.TEMPLATE_PATH}/images/pm.gif" title="{L.PM}" alt="{L.PM}" /></a><!-- END pm_link -->&nbsp;&nbsp;
   <!-- BEGIN email_link --><a href="pm.php?func=send&action=email&username={AUTHOR_USERNAME}"><img src="{T.TEMPLATE_PATH}/images/email.gif" title="{L.Email}" alt="{L.Email}" /></a><!-- END email_link -->&nbsp;&nbsp;<br />
   <!-- BEGIN profile_link --><a href="profile.php?id={AUTHOR_ID}"><img src="{T.TEMPLATE_PATH}/images/profile.gif" title="{L.Profile}" alt="{L.Profile}" /></a><!-- END profile_link -->&nbsp;&nbsp;
   <!-- BEGIN website_link --><a href="{AUTHOR_WEBSITE}"><img src="{T.TEMPLATE_PATH}/images/website.gif" title="{L.Website}" alt="{L.Website}" /></a><!-- END website_link -->&nbsp;&nbsp;
  </td>
 </tr>
 <tr>
  <td colspan="3" class="desc_row" height="5"></td>
 </tr>
 <!-- END postrow -->
</table>
<table width="100%">
 <tr>
  <td width="360"><a href="posting.php?func=newtopic&fid={FORUM_ID}"><img src="{T.TEMPLATE_PATH}/images/new_topic.gif" /></a>&nbsp;&nbsp;&nbsp;<a href="posting.php?func=reply&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/reply.gif" /></a>&nbsp;&nbsp;&nbsp;<a href="javascript:quick_reply();"><img src="{T.TEMPLATE_PATH}/images/fast_reply.gif" /></a></td>
  <td align="left"><a href="index.php">{C.site_name}</a><!-- BEGIN location_bottom_forum --> &raquo; <a href="view_forum.php?fid={LOCATION_FORUM_ID}">{LOCATION_FORUM_NAME}</a><!-- END location_bottom_forum --> &raquo; <b>{TOPIC_NAME}</b></td>
  <td width="75" align="right">{PAGINATION}</td>
 </tr>
 <tr>
  <td colspan="3">
   <div id="quick_reply">
    <table width="100%" class="maintable">
     <tr>
      <th height="25">{L.Quick_Reply}</th>
     </tr>
     <tr>
      <form method="post" action="posting.php?func=reply&tid={TOPIC_ID}" style="margin:0px;">
	  {CSRF_TOKEN}
      <td align="center">
       <textarea name="body" rows="10" style="width:99%"></textarea>
      </td>
     </tr>
     <tr>
      <th height="25">
       <input type="submit" name="Submit" value="{L.Submit}">&nbsp;&nbsp;<input type="reset" value="{L.Reset}">
      </th>
      </form>
     </tr>
    </table>
   </div>
  </td>
 </tr>
 <tr>
  <td colspan="2">
  <!-- BEGIN mod_links -->
  <a href="mod.php?func=delete&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/delete.gif" alt="{L.Delete_Topic}" title="{L.Delete_Topic}" /></a>&nbsp;&nbsp;<a href="mod.php?func=move&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/move.gif" alt="{L.Move_Topic}" title="{L.Move_Topic}" /></a>&nbsp;&nbsp;<!-- BEGIN SWITCH lock_topic --><a href="mod.php?func=lock&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/lock.gif" alt="{L.Lock_Topic}" title="{L.Lock_Topic}" /></a><!-- SWITCH lock_topic --><a href="mod.php?func=unlock&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/unlock.gif" alt="{L.Unlock_Topic}" title="{L.Unlock_Topic}" /></a><!-- END SWITCH lock_topic --><br />
  <!-- BEGIN announce_topic --><a href="mod.php?func=topic_type&tid={TOPIC_ID}&type={Constant.ANNOUNCMENT}"><img src="{T.TEMPLATE_PATH}/images/announce_topic.gif" alt="{L.Announce_Topic}" title="{L.Announce_Topic}" /></a>&nbsp;&nbsp;<!-- END announce_topic --><!-- BEGIN pin_topic --><a href="mod.php?func=topic_type&tid={TOPIC_ID}&type={Constant.PINNED}"><img src="{T.TEMPLATE_PATH}/images/pin_topic.gif" alt="{L.Pin_Topic}" title="{L.Pin_Topic}" /></a>&nbsp;&nbsp;<!-- END pin_topic --><!-- BEGIN general_topic --><a href="mod.php?func=topic_type&tid={TOPIC_ID}&type={Constant.GENERAL}"><img src="{T.TEMPLATE_PATH}/images/general_topic.gif" alt="{L.Make_General_Topic}" title="{L.Make_General_Topic}" /></a><!-- END general_topic -->
  <!-- END mod_links -->
  </td>
 </tr>
</table>
