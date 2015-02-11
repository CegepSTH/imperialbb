/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: js_global.js                                               # ||
|| # ---------------------------------------------------------------- # ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

function ibb_fetch_obj(id)
{
	itm = null;
	if(document.getElementById)
	{
		itm = document.getElementById(id);
	}
	else if(document.all)
	{
		itm = document.all[id];
	}
	else if(document.layers)
	{
		itm = document.layers[id];
	}
	return itm;
}

function ibb_hide_element(itm)
{
	if(!itm)
	{
		return;
	}
	itm.style.display = "none";
}

function ibb_show_element(itm)
{
	if(!itm)
	{
		return;
	}
	itm.style.display = "";
}

function ibb_toggle_view(id)
{
	if(!id)
	{
		return;
	}
	if(itm = ibb_fetch_obj(id))
	{
		if(itm.style.display == "none")
		{
			ibb_show_element(itm);
		}
		else
		{
			ibb_hide_element(itm);
		}
	}
}

function ibb_resize_textarea(to, id)
{
	if(to < 0)
	{
		var rows = -5;
		var cols = -10;
	}
	else
	{
		var rows = 5;
		var cols = 10;
	}
	var textarea = ibb_fetch_object(id);
	if(typeof textarea.orig_rows == 'undefined')
	{
		textarea.orig_rows = textarea.rows;
		textarea.orig_cols = textarea.cols;
	}
	var newrows = textarea.rows + rows;
	var newcols = textarea.cols + cols;
	if(newrows >= textarea.orig_rows && newcols >= textarea.orig_cols)
	{
		textarea.rows = newrows;
		textarea.cols = newcols;
	}
	return false;
}

function blurAnchors()
{
	if(document.getElementsByTagName)
	{
		var a = document.getElementsByTagName("a");
		for(var i = 0; i < a.length; i++)
		{
			a[i].onfocus = function()
			{
				this.blur()
			};
		}
	}
}
window.onload = blurAnchors;

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
