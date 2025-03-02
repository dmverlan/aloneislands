function DecToHex(n){return Number(n).toString(16);} function HexToDec (hex){return parseInt(hex,16);} 
function plusColor(dest,plus,k)
{
	dest = dest.toUpperCase();
	plus = plus.toUpperCase();
	dest = dest.replace('#','');
	dest = HexToDec(dest);
	plus = plus.replace('#','');
	plus = HexToDec(plus);
	var r = plus*k+dest;
	if (r>0xFFFFFF) return '#FFFFFF';
	else if (r<0x000000) return '#000000';
	else	return '#'+DecToHex(r);
}

function museroverout(jQueryElem)
{
	$(jQueryElem).mouseover(function () {
    $(this).css("border-color",plusColor($(this).css("border-color"),'#333333',1));
    });
	$(jQueryElem).mouseout(function () {
    $(this).css("border-color",plusColor($(this).css("border-color"),'#333333',-1));
    });
}

jQuery(function() {
museroverout(":password,:text,:button,:submit");
$(":submit").css("cursor","pointer");
$(":button").css("cursor","pointer");
$(".LinedTable tr:nth-child(odd)").css("background-color","#ECECEC");

/*
var jAurasc = $("div.aurasc");
if (jAurasc)
{
	jAurasc.each(function(){jQuery(this).carousel3d({ fadeEffect: 0, centerX: jQuery(this).offset().left + jQuery(this).width()/2.5, centerY: jQuery(this).offset().top + 5 , radiusX: jQuery(this).width()/2.5}); 
	});
}
*/
/*
$(".aurasc").html("<ul>"+$(".aurasc").html()+"</ul>");
$(".aurasc").jMyCarousel({
	  evtStart: 'mouseover',
	  evtStop: 'mouseout'
 });*/
 
});
/*
function sbxsize()
{
	$("select").each(function () {
		if (this.className == 'items' || this.className == 'laar')
		{
			this.className = 'real';
			$(this).selectbox();
		}
	});
}
sbxsize();
*/
/*
var oldId=0;
var mOutInterv = -1;
if ($("select").get(0))
$("select").each(function () {
SelectBox(this);
});

function SelectBox(_this)
{
	if (_this.className=='real') return;
	var t;
	var objtype;
	var sIndex;
	var values='',inners='';
	var oWidth = 0;
	objtype = _this;
	if (!objtype.id) objtype.id = objtype.name;
		for (var i=0;i<objtype.length;i++)
		{
			if (objtype.options[i].selected == true) 
					sIndex = i;
			values += objtype.options[i].value+'•';
			inners += objtype.options[i].innerHTML+'•';
			if (oWidth<objtype.options[i].innerHTML.length) oWidth = objtype.options[i].innerHTML.length;
		}		
	oWidth *= 12;
	if (_this.style.width && oWidth<_this.style.width) oWidth = parseInt(_this.style.width);
	//alert(this.style.width);
	var change=objtype.onchange;
	if (change) change = 'onchange="'+change+'"'; else change = '';
	t = '<div class="jquery-selectbox" style="width:'+oWidth+'px" id="'+objtype.id+'_div"><input type=hidden id="'+objtype.id+'_values" value="'+values+'"><input type=hidden id="'+objtype.id+'_inners" value="'+inners+'">'+'<table border=0 cellspacing=0 cellspaddin=0 width=100% onclick="ch_SELECT(\''+objtype.id+'\')"><tr><td align=center><b>'+objtype.options[sIndex].innerHTML+'</b></td><td width=16><a class="moreButton" href="javascript:void(0)"> </a></td></tr></table><div style="width:'+oWidth+';" class="jquery-selectbox-list" id="list_'+objtype.id+'"></div></div>';	

	$('#'+_this.id).css("display","none");
	$('#'+_this.id).parent().append(t);
}

function ch_SELECT(id)
{
	if (oldId) set_SELECT(oldId,0);
	//$("#"+id+"_a").attr("href","javascript:void(0)");
	var vArr = $("#"+id+"_values").get(0).value.split('•');
	var iArr = $("#"+id+"_inners").get(0).value.split('•');
	var t = '';
	for (var i=0;i<vArr.length-1;i++)
	{
		t+= '<a class=listelement href="javascript:set_SELECT(\''+id+'\','+i+',1)">'+iArr[i]+'</a>';
	}
	$("#list_"+id+"").get(0).innerHTML += t;
	//$("#tmp_DIV").fadeOut(1);
	$("#list_"+id+"").fadeIn(300);
	oldId = id;
	
	jQuery("#list_"+id).mouseout(function(){mOutInterv = setTimeout('$("#list_'+id+'").fadeOut(300);',1000);});
	jQuery("#list_"+id).mouseover(function(){if (mOutInterv!=-1) {clearTimeout(mOutInterv);mOutInterv=-1;}});
}

function set_SELECT(id,Index,achange)
{
	oldId = 0;
	$("#list_"+id).fadeOut(300);
var t;
var objtype;
var values='',inners='';
var oWidth = 0;
objtype = document.getElementById(id);
	for (var i=0;i<objtype.length;i++)
	{
		values += objtype.options[i].value+'•';
		inners += objtype.options[i].innerHTML+'•';
		if (oWidth<objtype.options[i].innerHTML.length) oWidth = objtype.options[i].innerHTML.length;
	}		
if (objtype.onchange && achange)setTimeout(objtype.onchange,10);
oWidth *= 12;
if (objtype.style.width) oWidth = parseInt(objtype.style.width);
objtype.value = objtype.options[Index].value;
t = '<input type=hidden id="'+objtype.id+'_values" value="'+values+'"><input type=hidden id="'+objtype.id+'_inners" value="'+inners+'">'+'<table border=0 cellspacing=0 cellspaddin=0 width=100%><tr><td align=center><b>'+objtype.options[Index].innerHTML+'</b></td><td width=16>'+
'<a class="moreButton" href="javascript:void(0)"> </a></td></tr></table><div style="width:'+oWidth+';" class="jquery-selectbox-list" id="list_'+objtype.id+'"></div>';	

$("#"+id+"_div").html(t);
}
*/
/*
$(".title").each(function () {
this.innerHTML = '<div id="rounded-box-3"> <b class="r3"></b><b class="r1"></b><b class="r1"></b> <div class="inner-box">'+this.innerHTML+'</div> <b class="r1"></b><b class="r1"></b><b class="r3"></b> </div>'; 
});*/