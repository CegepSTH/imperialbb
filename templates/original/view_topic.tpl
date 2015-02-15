
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
   <td align="left" style="padding-left:10px;"><a href="index.php">{C.site_name}</a><!-- BLOCK location_top_forum --> &raquo; <a href="view_forum.php?fid={LOCATION_FORUM_ID}">{LOCATION_FORUM_NAME}</a><!-- END BLOCK location_top_forum --> &raquo; <b>{TOPIC_NAME}</b></td>
</tr>
<tr>
  <td width="250"><a href="posting.php?func=newtopic&fid={FORUM_ID}"><img src="{T.TEMPLATE_PATH}/images/new_topic.gif" /></a>&nbsp;&nbsp;&nbsp;<a href="posting.php?func=reply&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/reply.gif" /></a></td>
 </tr>
</table>

<div class="panel">
	<div class="panel-header" style="background-color:#5CB8E6;">
		<span style="color:white;font-weight:bold;">{TOPIC_NAME}</span>
	</div>
	<div class="form-row">	
		<table>
			<tr>
				<th>{L.Author}</th>
				<th colspan="2">{L.Message}</th>
			</tr>
			<!--// BLOCK topic_message_item -->
			<tr>
				<td rowspan="4">
					<h3>{AUTHOR_NAME}</h3>
					<img src="{AUTHOR_AVATAR_LOCATION}"><br>
					<img src="{RANK_IMG_URL}" alt="{AUTHOR_RANK}" title="AUTHOR_RANK" /><br>
					<span style="line-height:20px;">{AUTHOR_RANK}</span><br>
					<span style="font-weight:bold; line-height: 20px;">{L.Posts}:</span> {AUTHOR_POSTS}<br>
					<span style="font-weight:bold; line-height: 20px;">{L.Date_Joined}:</span> {AUTHOR_JOINED}<br>
					<span style="font-weight:bold; line-height: 20px;">{L.Location}:</span> {AUTHOR_LOCATION}
					<hr style="margin-top:5px;margin-bottom:5px;">
					<!--// BLOCK topic_pm_link --><a href="pm.php?func=send&username={AUTHOR_USERNAME}"><img src="{T.TEMPLATE_PATH}/images/pm.gif" title="{L.PM}" alt="{L.PM}" /></a><!-- END BLOCK topic_pm_link -->&nbsp;&nbsp;
					<!--// BLOCK topic_email_link --><a href="pm.php?func=send&action=email&username={AUTHOR_USERNAME}"><img src="{T.TEMPLATE_PATH}/images/email.gif" title="{L.Email}" alt="{L.Email}" /></a><!-- END BLOCK topic_email_link -->&nbsp;&nbsp;<br />
					<!--// BLOCK topic_profile_link --><a href="profile.php?id={AUTHOR_ID}"><img src="{T.TEMPLATE_PATH}/images/profile.gif" title="{L.Profile}" alt="{L.Profile}" /></a><!-- END BLOCK topic_profile_link -->&nbsp;&nbsp;
					<!--// BLOCK topic_website_link --><a href="{AUTHOR_WEBSITE}"><img src="{T.TEMPLATE_PATH}/images/website.gif" title="{L.Website}" alt="{L.Website}" /></a><!-- END BLOCK topic_website_link -->&nbsp;&nbsp;
				</td>
			</tr>
			<tr>
				<td>
					{DATE}
				</td>
				<td align="right">
					<!--// BLOCK mod_links_on -->
					<a href="posting.php?func=edit&pid={POST_ID}"><img src="{T.TEMPLATE_PATH}/images/edit_post.gif" /></a>
					<a href="mod.php?func=delete&pid={POST_ID}"><img src="{T.TEMPLATE_PATH}/images/delete_post.gif" /></a>
					<!--// END BLOCK mod_links_on -->
      
					<!--// BLOCK mod_links_off -->
					<a href="posting.php?func=edit&pid={POST_ID}"><img src="{T.TEMPLATE_PATH}/images/edit_post.gif" /></a>
					<a href="posting.php?func=delete&pid={POST_ID}"><img src="{T.TEMPLATE_PATH}/images/delete_post.gif" /></a>
					<!--// END BLOCK mod_links_off -->
      
					<!--// BLOCK quote_button -->
					<a href="posting.php?func=reply&tid={TOPIC_ID}&quote={POST_ID}"><img src="{T.TEMPLATE_PATH}/images/quote.gif" /></a>
					<!--// END BLOCK quote_button -->
				</td>
			</tr>
			<tr>
				<td valign="top" colspan="2">
					{TEXT}<br>
				</td>
			</tr>
			<tr>
				<td colspan="2" valign="top">
					{SIGNATURE}	
				</td>
			</tr>
		<!--// END BLOCK topic_message_item -->
		</table>
	</div>
</div>



<table width="100%">
 <tr>
  <td width="360"><a href="posting.php?func=newtopic&fid={FORUM_ID}"><img src="{T.TEMPLATE_PATH}/images/new_topic.gif" /></a>&nbsp;&nbsp;&nbsp;<a href="posting.php?func=reply&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/reply.gif" /></a>&nbsp;&nbsp;&nbsp;<a href="javascript:quick_reply();"><img src="{T.TEMPLATE_PATH}/images/fast_reply.gif" /></a></td>
  <td align="left"><a href="index.php">{C.site_name}</a><!-- BLOCK location_bottom_forum --> &raquo; <a href="view_forum.php?fid={LOCATION_FORUM_ID}">{LOCATION_FORUM_NAME}</a><!-- END BLOCK location_bottom_forum --> &raquo; <b>{TOPIC_NAME}</b></td>
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
  <!-- BLOCK mod_links_on -->
  <a href="mod.php?func=delete&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/delete.gif" alt="{L.Delete_Topic}" title="{L.Delete_Topic}" /></a>
  <a href="mod.php?func=move&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/move.gif" alt="{L.Move_Topic}" title="{L.Move_Topic}" /></a>
   <!-- END BLOCK mod_links_on -->
   <a href="mod.php?func=delete&tid={TOPIC_ID}"><img src="{T.TEMPLATE_PATH}/images/delete.gif" alt="{L.Delete_Topic}" title="{L.Delete_Topic}" /></a>
  <!-- BLOCK lock_topic_on -->
  <form method="post" action="mod.php" class="mod-action-form">
    {CSRF_TOKEN}
	<input type="hidden" name="func" value="lock" />
	<input type="hidden" name="tid" value="{TOPIC_ID}" />
  	<button name="modaction">
      <img src="{T.TEMPLATE_PATH}/images/lock.gif" alt="{L.Lock_Topic}" title="{L.Lock_Topic}" />
	</button>
  </form>
  <!-- END BLOCK lock_topic_on -->
  <!-- BLOCK lock_topic_off -->
  <form method="post" action="mod.php" class="mod-action-form">
    {CSRF_TOKEN}
	<input type="hidden" name="func" value="unlock" />
	<input type="hidden" name="tid" value="{TOPIC_ID}" />
  	<button name="modaction">
       <img src="{T.TEMPLATE_PATH}/images/unlock.gif" alt="{L.Unlock_Topic}" title="{L.Unlock_Topic}" />
	</button>
  </form>
  <!-- END BLOCK lock_topic_off -->
  <br />
  <!-- announce_topic -->
  <form method="post" action="mod.php" class="mod-action-form">
    {CSRF_TOKEN}
	<input type="hidden" name="func" value="topic_type" />
	<input type="hidden" name="tid" value="{TOPIC_ID}" />
	<input type="hidden" name="type" value="{Constant.ANNOUNCMENT}" />
  	<button name="modaction">
      <img src="{T.TEMPLATE_PATH}/images/announce_topic.gif" alt="{L.Announce_Topic}" title="{L.Announce_Topic}" />
	</button>
  </form>
  <!-- END announce_topic -->
  <!-- BEGIN pin_topic -->
  <form method="post" action="mod.php" class="mod-action-form">
    {CSRF_TOKEN}
	<input type="hidden" name="func" value="topic_type" />
	<input type="hidden" name="tid" value="{TOPIC_ID}" />
	<input type="hidden" name="type" value="{Constant.PINNED}" />
  	<button name="modaction">
      <img src="{T.TEMPLATE_PATH}/images/pin_topic.gif" alt="{L.Pin_Topic}" title="{L.Pin_Topic}" />
	</button>
  </form>
  <!-- END pin_topic -->
  <!-- BEGIN general_topic -->
  <form method="post" action="mod.php" class="mod-action-form">
    {CSRF_TOKEN}
	<input type="hidden" name="func" value="topic_type" />
	<input type="hidden" name="tid" value="{TOPIC_ID}" />
	<input type="hidden" name="type" value="{Constant.GENERAL}" />
  	<button name="modaction">
	  <img src="{T.TEMPLATE_PATH}/images/general_topic.gif" alt="{L.Make_General_Topic}" title="{L.Make_General_Topic}" />
	</button>
  </form>
  <!-- END general_topic -->
  <!-- END mod_links -->
  </td>
 </tr>
</table>
