<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="{L.head_htmlang}" dir="{L.head_langdir}">
<head>
<title>{TITLE}</title>
<meta http-equiv="Content-Type" content="text/html; charset={L.head_charset}" />
<link rel="stylesheet" type="text/css" href="{T.TEMPLATE_PATH}/style.css" />
<script type="text/javascript" src="{C.jscripts_dir}/js_global.js"></script>
</head>
<body bgcolor="#E5E5E5">
<table width="100%" align="center" class="bodytable">
 <tr>
  <td>
   <table width="98%" align="center">
    <tr>
     <td style="border-style: solid; border-color: #1E34FD; border-width: 1px; padding: 1px;">
      <table width="100%" cellpadding="0" cellspacing="0">
       <tr>
        <td height="67" width="256" align="left">
         <img src="{T.TEMPLATE_PATH}/images/logo.gif" />
        </td>
        <td>
         <img src="{T.TEMPLATE_PATH}/images/logo2.gif" height="67" width="100%" />
        </td>
       </tr>
      </table>
     </td>
    </tr>
    <tr>
     <td align="center" style="border-style: solid; border-color: #1E34FD; border-width: 1px; padding: 1px;">
     <!-- BEGIN SWITCH logged_in -->
      <table width="100%" height="25" cellpadding="0" cellspacing="0" background="{T.TEMPLATE_PATH}/images/menu_back.gif">
       <tr>
        <td align="right" style="padding-right: 10px;">
			<div class="header_links">
				<ul>
					<li><a href="index.php">{L.Home}</a></li>
					<li>{ADMIN_LINK}</li>
					<li><a href="search.php">{L.Search}</a></li>
					<li><a href="members.php">{L.Members}</a></li>
					<li><a href="profile.php?func=edit">{L.User_CP}</a></li>	
					<li><a href="pm.php">{L.PM}</a></li>
				</ul>
			</div>
        </td>
       </tr>
      </table>
      <!-- SWITCH logged_in -->
      <table width="100%" height="25" cellpadding="0" cellspacing="0" background="{T.TEMPLATE_PATH}/images/menu_back.gif">
       <tr>
        <td align="right" style="padding-right: 10px;">
			<div class="header_links">
				<ul>
					<li><a href="index.php">{L.Home}</a></li>
					<li><a href="search.php">{L.Search}</a></li>
					<li><a href="login.php">{L.Login}</a></li>
					<li><a href="register.php">{L.Register}</a></li>	
				</ul>
			</div>
        </td>
       </tr>
      </table>
      <!-- END SWITCH logged_in -->
     </td>
    </tr>
    <tr>
     <td height="10"></td>
    </tr>
    <!-- BEGIN SWITCH logged_in -->
    <tr>
     <td align="left">
      {L.Welcome} {USERNAME} (<a href="login.php?func=logout">{L.Logout}</a>&nbsp;&ndash;&nbsp;<a href="profile.php?func=edit">{L.User_CP}</a>)
     </td>
    </tr>
    <!-- SWITCH logged_in -->
    <tr>
     <td align="left">
      {L.Welcome_Guest} (<a href="login.php">{L.Login}</a>&nbsp;&ndash;&nbsp;<a href="register.php">{L.Register}</a>)
     </td>
    </tr>
    <!-- END SWITCH logged_in -->
	<tr>
	 <td>
