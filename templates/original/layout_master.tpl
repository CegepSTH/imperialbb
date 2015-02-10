<!DOCTYPE html>
<html lang="{L.head_htmlang}" dir="{L.head_langdir}">
	<head>
		<title>{TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset={L.head_charset}" />
		<link rel="stylesheet" type="text/css" href="{T.TEMPLATE_PATH}/newstyle.css" />
		<script type="text/javascript" src="{C.jscripts_dir}/js_global.js"></script>
	</head>
	<body>
		<div class="site-header">
			<div class="navlinks">
				<!-- BLOCK navh_admin_link -->
				<a href="admin/">{L.Administration_Panel}</a>
				<!-- END BLOCK navh_admin_link -->

				<!-- BLOCK navh_logged_in -->
				<a href="index.php">{L.Home}</a>
				{ADMIN_LINK}
				<a href="search.php">{L.Search}</a>
				<a href="members.php">{L.Members}</a>
				<a href="profile.php?func=edit">{L.User_CP}</a>
				<a href="pm.php">{L.PM}</a>
				<!-- END BLOCK navh_logged_in -->

				<!-- BLOCK navh_guest -->
				<a href="index.php">{L.Home}</a>
				<a href="search.php">{L.Search}</a>
				<a href="login.php">{L.Login}</a>
				<a href="register.php">{L.Register}</a>
				<!-- END BLOCK navh_guest -->
			</div>
		</div>
		<div class="site-content-wrapper">
			<div class="connected-as">
				<!-- BLOCK name_logged_in -->
				{L.Welcome} {USERNAME} (<a href="login.php?func=logout">{L.Logout}</a>&nbsp;&ndash;&nbsp;<a href="profile.php?func=edit">{L.User_CP}</a>)
				<!-- END BLOCK name_logged_in -->
				<!-- BLOCK name_guest -->
				{L.Welcome_Guest} (<a href="login.php">{L.Login}</a>&nbsp;&ndash;&nbsp;<a href="register.php">{L.Register}</a>)
				<!-- END BLOCK name_guest -->
			</div>
			<div class="site-content">
			<!-- TAG content -->
			</div>
		</div>
		<div class="site-footer">
			<div class="navlinks">
				<!-- BLOCK navh_admin_link -->
				<a href="admin/">{L.Administration_Panel}</a>&nbsp;&ndash;&nbsp;
				<!-- END BLOCK navh_admin_link -->
			
				<!-- BLOCK navf_logged_in -->
				<a href="index.php">{L.Home}</a>&nbsp;&ndash;&nbsp;
				{ADMIN_LINK}
				<a href="search.php">{L.Search}</a>&nbsp;&ndash;&nbsp;
				<a href="members.php">{L.Members}</a>&nbsp;&ndash;&nbsp;
				<a href="profile.php?func=edit">{L.User_CP}</a>&nbsp;&ndash;&nbsp;
				<a href="pm.php">{L.PM}</a>
				<!-- END BLOCK navf_logged_in -->

				<!-- BLOCK navf_guest -->
				<a href="index.php">{L.Home}</a>&nbsp;&ndash;&nbsp;
				<a href="search.php">{L.Search}</a>&nbsp;&ndash;&nbsp;
				<a href="login.php">{L.Login}</a>&nbsp;&ndash;&nbsp;
				<a href="register.php">{L.Register}</a>
				<!-- END BLOCK navf_guest -->
			</div>
			<div class="info">
				Powered by <a href="http://imperialbb.m-ka.net/">ImperialBB</a><br />
				{GENERATION_TIME}
			</div>
		</div>
	</body>
</html>
