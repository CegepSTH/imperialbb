/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: acp_toggle.js                                              # ||
|| # ---------------------------------------------------------------- # ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

IE  = (document.all)            ? true : false;
NS4 = (document.layers)         ? true : false;
NS6 = (document.getElementById) ? true : false;

function ibb_switchdom(name)
{
	if(IE)
    {
        return document.all[name];
    }
	else if(NS4)
    {
        return document.layers[name];
    }
	else if(NS6)
    {
        return document.getElementById(name);
    }
}

function ibb_setcookie(name,value)
{
	document.cookie = cookieprefix + name + '=' + escape(value) + '; expires=Wed, 1 Jan 2020 00:00:00 GMT; path=/';
}

function ibb_fetchcookie(name)
{
	var cookie = document.cookie;
	var prefix = cookieprefix + name + '=';
	var begin = cookie.indexOf("; " + prefix);
	if (begin == -1)
    {
		begin = cookie.indexOf(prefix);
		if (begin != 0)
        {
            return null;
        }
	}
    else
    {
        begin += 2;
    }
	var end = cookie.indexOf(';', begin);
	if (end == -1)
    {
        end = cookie.length;
    }
	result = unescape(cookie.substring(begin + prefix.length, end));
	return result;
}

function ibb_deletecookie(name)
{
	document.cookie = cookieprefix + name+'=/; path=/; expires=Thu, 01-Jan-70 00:00:01 GMT;';
}

function ibb_deletecookie_item(name,cookiename)
{
	var cookie = ibb_fetchcookie(cookiename);
	if(cookie)
	{
		var newids = new Array();
		var ids    = cookie.split('\n');
		for(i in ids)
		{
			if(ids[i] != name && ids[i] != '')
			{
				newids[newids.length] = ids[i];
			}
		}
		ibb_setcookie(cookiename,newids.join('\n'));
	}
}

function ibb_addcookie_item(name,cookiename)
{
	var cookie = ibb_fetchcookie(cookiename);
	if(cookie)
	{
		var newids = new Array();
		var ids    = cookie.split('\n');
		for(i in ids)
		{
			if(ids[i] != name && ids[i] != '')
			{
				newids[newids.length] = ids[i];
			}
		}
		newids[newids.length] = name;
		ibb_setcookie(cookiename,newids.join('\n'));
	}
	else
	{
		ibb_setcookie(cookiename,name);
	}
}

function ibb_init()
{
	var fetchcookie = ibb_fetchcookie('acp_collapsed');
	if(fetchcookie)
	{
		var ids = fetchcookie.split('\n');
		for(i in ids)
		{
			var split = ids[i].split('_');
			ibb_toggle(split[1],true);
		}
	}
}

function ibb_collapseall()
{
	var ids = menu_ids.split(',');
	for(i in ids)
	{
		ibb_switchdom('img_' + ids[i]).src = imagefolder + 'minus.gif';
		ibb_switchdom('menu_' + ids[i]).style.display = 'none';
		ibb_addcookie_item('menu_' + ids[i],'acp_collapsed');
	}
	return false;
}

function ibb_expandall()
{
	var ids = menu_ids.split(',');
	for(i in ids)
	{
		ibb_switchdom('img_' + ids[i]).src = imagefolder + 'plus.gif';
		ibb_switchdom('menu_' + ids[i]).style.display = '';
		ibb_deletecookie_item('menu_' + ids[i],'acp_collapsed');
	}
	return false;
}

function ibb_toggle(id)
{
	if(ibb_switchdom('menu_' + id).style.display == 'none')
	{
	    ibb_deletecookie_item('menu_' + id,'acp_collapsed');
		ibb_switchdom('img_' + id).src = imagefolder + 'plus.gif';
		ibb_switchdom('menu_' + id).style.display = '';
	}
	else
	{
		ibb_addcookie_item('menu_' + id,'acp_collapsed');
		ibb_switchdom('img_' + id).src = imagefolder + 'minus.gif';
		ibb_switchdom('menu_' + id).style.display = 'none';
	}
	return false;
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
