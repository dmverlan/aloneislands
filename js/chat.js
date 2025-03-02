if(top.loading)
		top.load(10);

function hide_tip(ii)
{
	top.frames["chmain"].document.getElementById('tips').innerHTML = '';
	top.frames["chmain"].document.getElementById('tips').style.visibility = 'hidden';
	top.frames["chmain"].document.getElementById('tips').style.height = 0;
	top.frames['chmain'].scrollBy(0,65500);
}

function dont_view_this(ii)
{
	top.frames['ch_list'].location= 'ch.php?no_tip='+ii+'&rand='+Math.random();
	hide_tip(ii);
}

function changeChatOrientation(t)
{
	jQuery("#tbox > a").attr("class","ActiveBc");
	jQuery("#ch"+t).addClass("nActiveBc");
	if (top.frames['ch_buttons'].document.mess.message)
		top.frames['ch_buttons'].document.mess.message.focus();
	if (top.frames['ch_buttons'].document.mess.type)
		top.frames['ch_buttons'].document.mess.type.value=t;
	top.chat_turn=t;
	top.add_msg('',0,t);
	top.frames['chmain'].scroll_chat();
	top.Msg_Sended = false;
}

function scroll_chat()
{
	//$("#chat").scrollTo($("#scrollitem"),{duration:500,axis:'y'});
	top.frames['chmain'].document.getElementById('chat').scrollTop += 6500;
}