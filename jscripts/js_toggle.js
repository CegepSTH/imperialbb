/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: js_toggle.js                                               # ||
|| # ---------------------------------------------------------------- # ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

function getCookieVal (offset)
{
	var endstr = document.cookie.indexOf (";", offset);
	if (endstr == -1) endstr = document.cookie.length;
	return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie (name)
{
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen)
    {
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg) return getCookieVal (j);
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0) break;
	}
	return null;
}

function SetCookie (name, value)
{
	var argv = SetCookie.arguments;
	var argc = SetCookie.arguments.length;
	var expires = (argc > 2) ? argv[2] : null;
	var path = (argc > 3) ? argv[3] : null;
	var domain = (argc > 4) ? argv[4] : null;
	var secure = (argc > 5) ? argv[5] : false;
	document.cookie = name + "=" + escape (value) +
	((expires == null) ? "" : ("; expires=" +
	expires.toGMTString())) +
	((path == null) ? "" : ("; path=" + path)) +
	((domain == null) ? "" : ("; domain=" + domain)) +
	((secure == true) ? "; secure" : "");
}

function collapseforum(id)
{
	var div1_name = 'cat_v'+id;
	var div2_name = 'cat_h'+id;
	if (document.getElementById)
    {
		var div1 = document.getElementById(div1_name).style;
		var div2 = document.getElementById(div2_name).style;
	}
    else if (document.layers)
    {
		var div1 = document.div1_name;
		var div2 = document.div2_name;
	}
    else
    {
		var div1 = document.all.div1_name.style;
		var div2 = document.all.div2_name.style;
	}
	div1.display = div1.display == "none"? "block":"none";
	div2.display = div2.display == "block"? "none":"block";
	pathname = location.pathname;
	myDomain = pathname.substring(0,pathname.lastIndexOf('/')) +'/';
	var largeExpDate = new Date ();
	largeExpDate.setTime(largeExpDate.getTime() + (365 * 24 * 3600 * 1000));
	SetCookie('cat'+id,div1.display,largeExpDate,myDomain);
}

function update_display(id, show)
{
	var div1_name = 'cat_v'+id;
	var div2_name = 'cat_h'+id;
	if (document.getElementById)
    {
		var div1 = document.getElementById(div1_name).style;
		var div2 = document.getElementById(div2_name).style;
	}
    else if (document.layers)
    {
		var div1 = document.div1_name;
		var div2 = document.div2_name;
	}
    else
    {
		var div1 = document.all.div1_name.style;
		var div2 = document.all.div2_name.style;
	}
	if(show)
    {
		div1.display = "block";
		div2.display = "none";
	}
    else
    {
		div1.display = "none";
		div2.display = "block";
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
