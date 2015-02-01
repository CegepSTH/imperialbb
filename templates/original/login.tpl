<table width="100%">
	<tr>
		<td align="left" style="padding-left:5px;" valign="bottom"><a href="index.php">{C.site_name}</a> &raquo; <b>Login</b></td>
	</tr>
</table>
<form method="post" action="">
{CSRF_TOKEN}
<table width="100%" class="maintable">
	<tr>
		<th height="25">{L.Login}</th>
	</tr>
	<tr>
		<td class="cell2">
			<table width="100%" border="0" style="padding-top: 20px;">
				<tr>
					<td width="40%" align="right" style="padding: 5px;">{L.Username}:</td>
					<td style="padding: 5px;"><input type="text" name="UserName"></td>
				</tr>
				<tr>
					<td align="right" style="padding: 5px;">{L.Password}:</td>
					<td style="padding: 5px;"><input type="password" name="PassWord"></td>
				</tr>
				<tr>
					<td colspan="2" height="30" align="center"><input type="Submit" name="Submit" value="{L.Login}" />&nbsp;&nbsp;<input type="reset" value="{L.Reset}" /></td>
				</tr>
				<tr>
					<td colspan="2" align="center">[ <a href="register.php">{L.Register}</a> | <a href="login.php?func=forgotten_pass">{L.Forgotten_Password}</a> ]</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
