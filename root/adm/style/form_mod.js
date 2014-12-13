/******
* This is a collection of scripts written for Form Mod (Form Creator)
*****/

function show_hide(id1, id2, id3) 
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