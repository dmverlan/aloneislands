document.write('<div style="position:absolute; left:0px; top:0px; z-index: 2; width:300 ; height:300; visibility:hidden;" id="ml" class=loc>&nbsp;</div>');
var ml = document.getElementById('ml');
var i=0;
var $ = function(id){
if (document.getElementById(id)) return document.getElementById(id);
else return document.getElementByName(id);
};

function change_img()
{
	init_main_layer();
	var hg = '60';
	var wg = '48';
	ml.innerHTML += '<form onsubmit="show_images();return false;"><img src=images/magic/'+image+'.gif><br>Длина:<input class=login type=text value='+wg+' id="_width" size=4>Высота:<input class=login type=text value='+hg+' id="_height" size=4><hr><input class=login type=submit value=[Показать все рисунки]></form><a href=imgs.php?'+Math.random()+' target=updater class=bga>Обновить библиотеку рисунков.</a>';
}
function show_images()
{
	ml.innerHTML += '<img src=images/progress.gif>';
	xmlhttp.open('get', 'services/image_list.php?type=2&width='+$('_width').value+'&height='+$('_height').value+'&rand='+Math.random()+'');
	xmlhttp.onreadystatechange = ajax_response;
	xmlhttp.send(null);
}
function ajax_response()
{
	if(xmlhttp.readyState == 4)
			{
				if(xmlhttp.status == 200)
				{
					var response = xmlhttp.responseText;
					var z = '';
					if (response == 'none') ml.innerHTML += 'Рисунков не найдено.';
					else
					{
					response = response.split('|');
					for (var i=0;i<response.length;i++)
					if (response[i])
					{
						response[i] = response[i].substr(0,response[i].length-4);
						z+= '<img src=images/magic/'+response[i]+'.gif onclick="set_img(\''+response[i]+'\')" width=30> ';
					}
					ml.innerHTML = z;
					}
				}
			}
}

function set_img(i)
{
	image = i;
	change_img();
	d.getElementById('image').value = i;
	$('img').src = 'images/magic/'+image+'.gif';
}

function init_main_layer()
{
	ml.style.visibility = 'visible';
	ml.style.left = screen.width/2 - 150;
	ml.style.top = 80;
	ml.innerHTML = 'РЕДАКТОР ВЕЩЕЙ [Aloneislands.Ru] <a href=javascript:disable_main_layer() class=timef><b>[УБРАТЬ]</b></a><hr>';
}
function disable_main_layer()
{
	ml.style.visibility = 'hidden';
	ml.style.left = screen.width/2 - 150;
	ml.style.top = 0;
	ml.innerHTML = '';
}