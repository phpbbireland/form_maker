/******
* This is a collection of scripts written for Form Mod (Form Creator)
*****/

var PreloadFlag = false;
var expDays = 90;
var exp = new Date(); 
var tmp = '';
var tmp_counter = 0;
var tmp_open = 0;

var exp = new Date();

exp.setTime(exp.getTime() + (expDays*24*60*60*5000));


// cookies //
function SetCookie(name, value) 
{
	var argv = SetCookie.arguments;
	var argc = SetCookie.arguments.length;

	var expires = (argc > 2) ? argv[2] : null;
	expires = exp;
	var path = (argc > 3) ? argv[3] : null;
	var domain = (argc > 4) ? argv[4] : null;
	var secure = (argc > 5) ? argv[5] : false;
	document.cookie = name + "=" + escape(value) +
		((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
		((path == null) ? "" : ("; path=" + path)) +
		((domain == null) ? "" : ("; domain=" + domain)) +
		((secure == true) ? "; secure" : "");
}

function getCookieVal(offset) 
{
	var endstr = document.cookie.indexOf(";",offset);
	if (endstr == -1)
	{
		endstr = document.cookie.length;
	}
	return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie(name) 
{
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen) 
	{
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg)
		{
			return getCookieVal(j);
		}

		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0)
		{
			break;
		}
	} 
	return null;
}

// Show/Hide element with cookie option

/*** 
*  takes three possible elements...
*  switches the first element and set cookie
*  switch second element visibility...
*
***/

function Show(id)
{
	var element = null;
	if (document.getElementById) 
	{
		element = document.getElementById(id);
	}
	else if (document.all)
	{
		element = document.all[id];
	} 
	else if (document.layers)
	{
		element = document.layers[id];
	}
	if (element.style.display == "none")
	{ 
		element.style.display = "inline"; 
	}
	else
	{
		element.style.display = "none";
	}
}
function Hide(id)
{
	var element = null;
	if (document.getElementById) 
	{
		element = document.getElementById(id);
	}
	else if (document.all)
	{
		element = document.all[id];
	} 
	else if (document.layers)
	{
		element = document.layers[id];
	}
	if (element.style.display == "inline")
	{ 
		element.style.display = "none"; 
	}
	else
	{
		element.style.display = "inline";
	}
}

function ShowHideSwap(id1, id2)
{
	switch_visibility(id1);
	switch_visibility(id2);
}

function ShowHide(id1, id2, id3) 
{
	var onoff = switch_visibility(id1);
	if (id2 != '')
	{
		switch_visibility(id2);
	}
	if (id3 != '')
	{
		SetCookie(id3, onoff, exp);
	}
}
	
function switch_visibility(id) 
{
	var element = null;
	if (document.getElementById) 
	{
		element = document.getElementById(id);
	}
	else if (document.all)
	{
		element = document.all[id];
	} 
	else if (document.layers)
	{
		element = document.layers[id];
	}

	if (!element) 
	{
		// do nothing
	}
	else if (element.style) 
	{
		if (element.style.display == "none")
		{ 
			element.style.display = ""; 
			return 1;
		}
		else
		{
			element.style.display = "none";
			return 2;
		}
	}
	else 
	{
		element.visibility = "show"; 
		return 1;
	}
}


function is_hidden(id) 
{
	var element = null;

	if (document.getElementById) 
	{
		element = document.getElementById(id);
	}
	else if (document.all)
	{
		element = document.all[id];
	} 
	else if (document.layers)
	{
		element = document.layers[id];
	}

	if (!element) 
	{
		// do nothing
		//alert('NOT AN ELEMENT');
	}
	else if (element.style) 
	{
		if (element.style.display == "none")
		{ 
			return(1);
		}
		else
		{
			return(0);
		}
	}
}

function toggle_validation_form_mod(main_form, secondary_form)
{
	// toggle validation based on secondary form visibility //

	var $_hidden = 0;

	$_hidden = is_hidden(secondary_form);

	if ($_hidden == 0)
	{
		document.getElementById(main_form).noValidate=true;
		alert('Turned off validation');
	}
	else
	{
		document.getElementById(main_form).noValidate=false;
		alert('Turned on validation');
	}
}

function toggle_validation_form(thisform)
{
	// toggle validation on a given form //

	if (document.getElementById(thisform).noValidate=false)
	{
		document.getElementById(thisform).noValidate=true;
		//alert('Turned off validation');
	}
	else
	{
		document.getElementById(form).noValidate=false;
		//alert('Turned on validation');
	}

}
