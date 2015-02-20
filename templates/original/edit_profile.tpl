<div class="breadcrumb">
	<a href="index.php">{C.site_name}</a> &raquo; <b>{L.Edit_Profile}</b>
</div>

<!-- BLOCK error -->
<div class="block-form-admin" style="text-align:center;">
	<h3>{L.error}</h3>
	<br />
	{ERRORS}
</div>
<br />
<!-- END BLOCK error -->

<div class="block-form-admin">
<form method="post" action="" enctype="multipart/form-data">
{CSRF_TOKEN}
	<h4>{L.Email_Preferences}</h4>
	<div class="form-admin-row">
		<label for="Email">{L.Email_Address}</label>
		<label><input type="text" id="Email" name="Email" value="{EMAIL}" required></label>
	</div>
	<div class="form-admin-row">
		<label for="Email2">{L.Retype_Email_Address}</label>
		<label><input type="text" id="Email2" name="Email2" value="{EMAIL2}" required></label>
	</div>
	<h4>{L.Change_Password}</h4>
	<div class="form-admin-row" style="text-align:center;">
		<em>{L.Only_fill_in_if_you_want_to_change_your_password}</em>
	</div>
	<div class="form-admin-row">
		<label for="OldPass">{L.Old_Password}</label>
		<label><input type="password" id="OldPass" name="OldPass"></label>
	</div>
	<div class="form-admin-row">
		<label for="PassWord">{L.New_Password}</label>
		<label><input type="password" id="PassWord" name="PassWord"></label>
	</div>
	<div class="form-admin-row">
		<label for="Pass2">{L.New_Password} ({L.Retype})</label>
		<label><input type="password" name="Pass2" id="Pass2"></label>
	</div>
	<h4>{L.Signature}</h4>
	<div class="form-admin-row">
		<label for="signature" style="vertical-align:top;">{L.Signature}</label>
		<label><textarea name="signature" id="signature" rows="5">{SIGNATURE}</textarea></label>
	</div>
	<h4>{L.IM_Setup}</h4>
	<div class="form-admin-row">
		<label for="aim">{L.AIM}</label>
		<label><input type="text" name="aim" id="aim" value="{AIM}"></label>
	</div>
	<div class="form-admin-row">
		<label for="icq">{L.ICQ}</label>
		<label><input type="text" name="icq" value="{ICQ}"></label>
	</div>
	<div class="form-admin-row">
		<label for="msn">{L.MSN}</label>
		<label><input type="text" name="msn" id="msn" value="{MSN}"></label>
	</div>
	<div class="form-admin-row">
		<label for="yahoo">{L.Yahoo}</label>
		<label><input type="text" name="yahoo" id="yahoo" value="{YAHOO}"></label>
	</div>
	<h4>{L.Avatar_Preferences}</h4>
	<div class="form-admin-row" style="text-align:center;">
		<em>{L.Only_fill_in_if_you_want_to_change_your_avatar}</em>
	</div>
	<div class="form-admin-row">
		<label style="vertical-align:top;">{L.Current_Avatar}</label>
		  	<!-- BLOCK current_avatar_on -->
			<img src="{AVATAR_LOCATION}" height="{AVATAR_HEIGHT}" width="{AVATAR_WIDTH}" /><br />
			<label></label>
			<span style="vertical-align:middle;">{L.Delete_Avatar}&nbsp;&nbsp;
			<input style="vertical-align:middle;" type="checkbox" name="Delete_Avatar" id="Delete_Avatar" /></span>
			<!-- END BLOCK current_avatar_on -->
			<!-- BLOCK current_avatar_off -->
			<label><span>{L.No_Current_Avatar_Msg}</span></label>
			<!-- END BLOCK current_avatar_off -->
	</div>
	<div class="form-admin-row">
		<label for="Remote_Avatar_URL">{L.Remote_Avatar}</label>
		<label><input type="text" name="Remote_Avatar_URL" id="Remove_Avatar_URL" value="{REMOTE_AVATAR_URL}"></label>
	</div>
	<div class="form-admin-row">
		<label for="Upload_Avatar">{L.Upload_Avatar}</label>
		<label><input type="file" name="Upload_Avatar" id="Upload_Avatar" value=""></label>
	</div>
	<h4>{L.Other_Preferences}</h4>
	<div class="form-admin-row">
		<label for="template">{L.Template}</label>
		<label>
		   <select name="template" id="template">
			<!-- BLOCK template_select_option -->
				<option value="{TEMPLATE_ID}" {TEMPLATE_SELECTED}>{TEMPLATE_NAME}</option>
			<!-- END BLOCK template_select_option -->
			</select>
		</label>
	</div>
	<div class="form-admin-row">
		<label for="language">{L.Language}</label>
		<label>
		   <select name="language" id="language">
			<!-- BLOCK language_select_option -->
			<option value="{LANGUAGE_ID}" {LANGUAGE_SELECTED}>{LANGUAGE_NAME}</option>
			<!-- END BLOCK language_select_option -->
			</select>
		</label>
	</div>
	<div class="form-admin-row">
		<label>{L.profile_birthday}</label>
		<label>
			<select name='month'>
				<option value='00'>{L.birthday_month}</option>
				{MONTH_OPTS}
			</select>
			<select name='day'>
				<option value='00'>{L.birthday_day}</option>
				{DAY_OPTS}
			</select>
			<select name='year'>
				<option value='0000'>{L.birthday_year}</option>
				{YEAR_OPTS}
			</select>
		</label>
	</div>
	<div class="form-admin-row">
		<label for="website">{L.Website}</label>
		<label><input type="text" name="website" id="website" value="{WEBSITE}"></label>
	</div>
	<div class="form-admin-row">
		<label for="location">{L.Location}</label>
		<label><input type="text" name="location" id="location" value="{LOCATION}"></label>
	</div>
	<div class="form-admin-row">
		<label for="email_on_pm">{L.Email_On_PM}</label>
		<label>
			{L.True}<input type="radio" name="email_on_pm" value="1" {EOP_TRUE}>&nbsp;&nbsp;
			{L.False}<input type="radio" name="email_on_pm" value="0" {EOP_FALSE}>
		</label>
	</div>
	<h4>{L.Account_closure}</h4>
	<div class="form-admin-row" style="text-align:center;">
		<em>{L.Only_fill_in_if_you_want_to_close_your_account}</em>
	</div>
	<div class="form-admin-row">
		<label for="close_Account">{L.Close_Account}</label>
		<label>
			<input type="checkbox" name="Close_Account" id="Close_Account"  />
		</label>
	</div>
	<div class="form-admin-row">
		<label for="Close_Account_Reason" style="color: red;">{L.Reason}</label>
		<label>
			<input type="text" name="Close_Account_Reason" id="Close_Account_Reason">
		</label>
	</div>
	<h4>
	   <input type="submit" name="Submit" value="{L.Submit}" />&nbsp;&nbsp;<input type="reset" value="{L.Reset}" />
	</h4>
</form>
<br />
