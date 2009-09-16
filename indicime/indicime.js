function indictl(e1,e2)
{
try{
	var elem=document.getElementById(e2);var el=document.getElementById(e1);
	d = new Date();
	d.setMonth(d.getMonth()+1);
	if(elem.style.display=="none")
	{
		elem.style.display="";
		if(e1=="indicpin")
		{
			el.src=indicime_bu + "img/pin2.gif";
			indicSetCookie("indicime_wp_minmax","max",d);
		}
	}
	else
	{
		elem.style.display="none";
		if(e1=="indicpin")
		{
			el.src=indicime_bu+"img/pin1.gif"
			indicSetCookie("indicime_wp_minmax","min",d);
		}
	}
}
catch(e)
{}
}
function indicChange(id, script)
{
try{
	script = script.toLowerCase();
	var old = (id == '_globalIndicIME')?document.getElementById('indicscript').value:pphText.getScript('_globalIndicIME');
	var indic = (old == "english"?script:old);
	pphText.setGlobalScript(script);
	document.getElementById('indiccm').src = indicime_bu + "img/" + script + 'charmap.png';
	document.getElementById('indicscript').value = script.toLowerCase();
	d = new Date();
	d.setMonth(d.getMonth()+1);
	indicSetCookie("indicime_wp",script + ":" + indic,d);
}
catch(e)
{}
}

function initIndicIME()
{
try{
	var lang = LoadIndicBar();
	pphText  = new PramukhPhoneticHandler();
	var ck = indicGetCookie("indicime_wp");
	indicime_script = ck !=null?ck:'english:english';
	ck = indicGetCookie("indicime_wp_minmax");
	minmax = ck !=null?ck:'max';
	if(minmax == 'min')
		indictl('indicpin','indiccontent');
	var scr = [];
	scr = indicime_script.toLowerCase().split(":");
	scr[1] = (lang != '' && scr[1]!=lang)?lang:scr[1];
	scr[0] = (lang != '' && scr[0]!=lang && scr[0] != 'english')?lang:scr[0];
	pphText.convertPageToIndicIME(scr[1], indicChange);
	indicChange('dummy',scr[0]);
}
catch(e)
{}
}
function LoadIndicBar()
{
try{
	var iil = document.getElementById("indicimelayer");
	var content = "<div style='float:left'><img src='" + indicime_bu + "img/pin2.gif' id='indicpin' onclick=\"indictl('indicpin','indiccontent');\" style='cursor: pointer; cursor: hand;' title='Toggle IndicIME visibility'></div><div id='indiccontent' style='DISPLAY:inline'>&nbsp;Type in <select name='indicscript' id='indicscript' onchange='indicChange(this.id ,this.options[this.selectedIndex].value);' style='font-family:verdana, arial, helvetica, sans-serif; font-size:10px;'>";
	if(indicime_script != '' && indicime_script != "defaultlist")
	{
		var list = indicime_script.split(";");
		var i, len = list.length, val;
		for(i=0;i<len;i++)
		{
			val = list[i].split(":");
			val[0] = val[0] || val[1];
			val[1] = val[1] || val[0];
			if(val[0] !='' && val[1] !='')
				content += "<option value='" + (val[1]).toLowerCase() + "'>" + val[0] + "</option>";
		}
	}
	else
	{
		content += "<option value='bengali'>Bengali</option><option value='devanagari'>Devanagari</option>"+
		"<option value='gujarati'>Gujarati</option><option value='gurmukhi'>Gurmukhi</option><option value='kannada'>Kannada</option>" +
		"<option value='malayalam'>Malayalam</option><option value='oriya'>Oriya</option><option value='tamil'>Tamil</option><option value='telugu'>Telugu</option>";
	}	
	content +=	"<option value='english' selected>English (F12)</option></select><img src='" + indicime_bu + "img/help.gif' id='indichp' onclick=\"indictl('indichp','indiccmc');return false;\" style='cursor: pointer; cursor: hand;' title='Toggle help description'><div id='indiccmc' style='display:none;width:540px;text-align:left;'>Select Indian script from the list and type with 'The way you speak, the way you type' rule on this page. Refer to following image for details. Press F12 to toggle between Indic script and English.<br><img src='" + indicime_bu + "img/englishcharmap.png' alt='indic script char map' id='indiccm'></div>"+
		(indicime_lnk != 'off'?"<br \/>Powered By <a href=\"http:\/\/www.vishalon.net\/IndicResources\/IndicIME.aspx\" target='_blank'>Indic IME<\/a>":'') +
		"</div><div style='clear:both'></div>";
	iil.innerHTML = content;
	return (!val?'':val[1]);
}
catch(e)
{}
}

function indicGetCookie(name)
{
try{
	var dc = document.cookie;
	var prefix = name + "=";
	var begin = dc.indexOf("; " + prefix);

	if (begin == -1)
	{
		begin = dc.indexOf(prefix);

		if (begin != 0)
			return null;
	}
	else
		begin += 2;

	var end = document.cookie.indexOf(";", begin);

	if (end == -1)
		end = dc.length;

	return unescape(dc.substring(begin + prefix.length, end));
}
catch(e)
{}
}
function indicSetCookie(name, value, expires, path, domain, secure)
{
try{
	var curCookie = name + "=" + escape(value) + ((expires) ? "; expires=" + expires.toGMTString() : "") + "; path=/";
	document.cookie = curCookie;
}
catch(e)
{}
}	