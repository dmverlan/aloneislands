function setElementOpacity(oElem, nOpacity)
{
	var p = getOpacityProperty();
	(setElementOpacity = p=="filter"?new Function('oElem', 'nOpacity', 'nOpacity *= 100;	var oAlpha = oElem.filters["DXImageTransform.Microsoft.alpha"] || oElem.filters.alpha;	if (oAlpha) oAlpha.opacity = nOpacity; else oElem.style.filter += "progid:DXImageTransform.Microsoft.Alpha(opacity="+nOpacity+")";'):p?new Function('oElem', 'nOpacity', 'oElem.style.'+p+' = nOpacity;'):new Function)(oElem, nOpacity);
}

function getOpacityProperty()
{
	var p;
	if (typeof document.body.style.opacity == 'string') p = 'opacity';
	else if (typeof document.body.style.MozOpacity == 'string') p =  'MozOpacity';
	else if (typeof document.body.style.KhtmlOpacity == 'string') p =  'KhtmlOpacity';
	else if (document.body.filters && navigator.appVersion.match(/MSIE ([\d.]+);/)[1]>=5.5) p =  'filter';
	
	return (getOpacityProperty = new Function("return '"+p+"';"))();
}

function fadeOpacity(sElemId, sRuleName, bBackward)
{
	var elem = document.getElementById(sElemId);
	if (!elem || !getOpacityProperty() || !fadeOpacity.aRules[sRuleName]) return;
	
	var rule = fadeOpacity.aRules[sRuleName];
	var nOpacity = rule.nStartOpacity;
	
	if (fadeOpacity.aProc[sElemId]) {clearInterval(fadeOpacity.aProc[sElemId].tId); nOpacity = fadeOpacity.aProc[sElemId].nOpacity;}
	if ((nOpacity==rule.nStartOpacity && bBackward) || (nOpacity==rule.nFinishOpacity && !bBackward)) return;

	fadeOpacity.aProc[sElemId] = {'nOpacity':nOpacity, 'tId':setInterval('fadeOpacity.run("'+sElemId+'")', fadeOpacity.aRules[sRuleName].nDalay), 'sRuleName':sRuleName, 'bBackward':Boolean(bBackward)};
}

fadeOpacity.addRule = function(sRuleName, nStartOpacity, nFinishOpacity, nDalay){fadeOpacity.aRules[sRuleName]={'nStartOpacity':nStartOpacity, 'nFinishOpacity':nFinishOpacity, 'nDalay':(nDalay || 30),'nDSign':(nFinishOpacity-nStartOpacity > 0?1:-1)};};

fadeOpacity.back = function(sElemId){fadeOpacity(sElemId,fadeOpacity.aProc[sElemId].sRuleName,true);};

fadeOpacity.run = function(sElemId)
{
	var proc = fadeOpacity.aProc[sElemId];
	var rule = fadeOpacity.aRules[proc.sRuleName];
	
	proc.nOpacity = Math.round(( proc.nOpacity + .1*rule.nDSign*(proc.bBackward?-1:1) )*10)/10;
	setElementOpacity(document.getElementById(sElemId), proc.nOpacity);
	
	if (proc.nOpacity==rule.nStartOpacity || proc.nOpacity==rule.nFinishOpacity) clearInterval(fadeOpacity.aProc[sElemId].tId);
}
fadeOpacity.aProc = {};
fadeOpacity.aRules = {};

function fade(sElemId, sRule, bBackward)
{
	if (!document.getElementById(sElemId)) return;
	
	var aRuleList = sRule.split(/\s*,\s*/);
	for (var j	= 0; j < aRuleList.length; j++)
	{
		sRule = aRuleList[j];
		
		if (!fade.aRules[sRule]) continue;
		var i=0;
		
		if (!fade.aProc[sElemId])
		{
			fade.aProc[sElemId] = {};
		}
		else if (fade.aProc[sElemId][sRule]) 
		{
			i = fade.aProc[sElemId][sRule].i;
			clearInterval(fade.aProc[sElemId][sRule].tId);
		}
		
		if ((i==0 && bBackward) || (i==fade.aRules[sRule][3] && !bBackward)) continue;
		
		
		fade.aProc[sElemId][sRule] = {'i':i, 'tId':setInterval('fade.run("'+sElemId+'","'+sRule+'")', fade.aRules[sRule][4]),'bBackward':Boolean(bBackward)};
	}
}

fade.aProc = {};
fade.aRules = {};
fade.run = function(sElemId, sRule)
{
	
    fade.aProc[sElemId][sRule].i += fade.aProc[sElemId][sRule].bBackward?-1:1;
 	var finishPercent = fade.aProc[sElemId][sRule].i/fade.aRules[sRule][3]; 
	var startPercent = 1 - finishPercent;	
	var aRGBStart = fade.aRules[sRule][0];
	var aRGBFinish = fade.aRules[sRule][1];
	
	document.getElementById(sElemId).style[fade.aRules[sRule][2]] = 'rgb('+ 
	Math.floor( aRGBStart['r'] * startPercent + aRGBFinish['r'] * finishPercent ) + ','+
	Math.floor( aRGBStart['g'] * startPercent + aRGBFinish['g'] * finishPercent ) + ','+
	Math.floor( aRGBStart['b'] * startPercent + aRGBFinish['b'] * finishPercent ) +')';
	
	
	if ( fade.aProc[sElemId][sRule].i == fade.aRules[sRule][3] || fade.aProc[sElemId][sRule].i ==0) clearInterval(fade.aProc[sElemId][sRule].tId); 
}

fade.back = function (sElemId, sRule){fade(sElemId, sRule, true);};

fade.addRule = function (sRuleName, sFadeStartColor, sFadeFinishColor, sCSSProp, nMiddleColors, nDelay)
{
	fade.aRules[sRuleName] = [fade.splitRGB(sFadeStartColor), fade.splitRGB(sFadeFinishColor), fade.ccs2js(sCSSProp), nMiddleColors || 50, nDelay || 1];
};


fade.splitRGB = function (color){var rgb = color.replace(/[# ]/g,"").replace(/^(.)(.)(.)$/,'$1$1$2$2$3$3').match(/.{2}/g); for (var i=0;  i<3; i++) rgb[i] = parseInt(rgb[i], 16); return {'r':rgb[0],'g':rgb[1],'b':rgb[2]};};

fade.ccs2js = function (prop){var i; while ((i=prop.indexOf("-"))!=-1) prop = prop.substr(0, i) + prop.substr(i+1,1).toUpperCase() + prop.substr(i+2); return prop;};



fadeOpacity.addRule('oR1', .1, .8, 10);
fadeOpacity.addRule('oR2', 1, .2, 10);