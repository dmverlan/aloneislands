var birdInterv;
var loop1,loop2,loop3;
var Night;

function rand(a1,a2)
{
	return Math.floor(a1+(Math.random()*10000)%(a2-a1));
}

function Bird(a)
{
var z=0;
	if (a=='hot' || a=='wind')
	{
		z = rand(1,10);
		if (!Night)
		{
			if (z==1) Sound('bir1',3);
			if (z==2) Sound('bir2',3);
			if (z==3) Sound('bir4',3);
			if (z==4) Sound('bir5',3);
			if (z==5) Sound('bir7',3);
			if (z==6) Sound('bir8',3);
			if (z==7) Sound('bir9',3);
			if (z==8) Sound('bir15',3);
			if (z==9) Sound('bir20',3);
			if (z==10) Sound('bir29',3);
		}
		else
		{
			if (z==1) Sound('bir51',2);
			if (z==2) Sound('bir50',2);
			if (z==3) Sound('bir49',2);
			if (z==4) Sound('owl1',2);
			if (z==5) Sound('owl2',2);
			if (z==6) Sound('owl3',2);
			if (z==7) Sound('bug30',2);
			if (z==8) Sound('bug31',2);
			if (z==9) Sound('bug28',2);
			if (z==10) Sound('misc11',2);
		}
	}
	
	if (a=='rain')
	{
		z = rand(1,9);
		Sound('jungle'+z,3);
	}
	
	if (a=='hrain')
	{
		z = rand(1,9);
		Sound('jungle'+z,3);
	}
	
	if (a=='storm')
	{
		z = rand(1,6);
		Sound('thunder'+z,3);
	}
	
	if (a=='fog')
	{
		z = rand(1,9);
		Sound('jungle'+z,3);
	}
}

function StopMixes()
{
	soundManager.stopAll();
	clearInterval(birdInterv);
	clearInterval(loop1);
	clearInterval(loop2);
}

function PlaySummer(a,n)
{
	Night = n;
	soundManager.stopAll();
	clearInterval(birdInterv);
	clearInterval(loop1);
	clearInterval(loop2);
var t='';
	if (a == 'hot')
	{
		if (!n)
		{
			Sound('summer',3);
			loop1 = setInterval("Sound('summer',3);",12000);
		}
		else
		{
			Sound('night',3);
			loop1 = setInterval("Sound('night',3);",11000);
		}
		birdInterv = setInterval("Bird('"+a+"')",15000);
	}
	if (a == 'rain')
	{
		Sound('ltrain',3);
		loop1 = setInterval("Sound('ltrain',3);",6200);
		birdInterv = setInterval("Bird('"+a+"')",15000);
	}
	if (a == 'hrain')
	{
		Sound('ltrain',3);
		loop1 = setInterval("Sound('ltrain',3);",6200);
		Sound('beach',5,1);
		Sound('river',7,1);
		birdInterv = setInterval("Bird('"+a+"')",15000);
	}
	if (a == 'wind')
	{
		Sound('windsand',3);
		loop1 = setInterval("Sound('windsand',3);",9000);
		birdInterv = setInterval("Bird('"+a+"')",15000);
	}
	if (a == 'storm')
	{
		Sound('thunderrain',3,1);
		Sound('ltrain',3);
		loop1 = setInterval("Sound('ltrain',3);",6200);
		birdInterv = setInterval("Bird('"+a+"')",15000);
	}
	
	if (a == 'fog')
	{
		Sound('riverbrd',3);
		loop1 = setInterval("Sound('riverbrd',3);",11000);
		birdInterv = setInterval("Bird('"+a+"')",15000);
	}
	
	if (a == 'gsnow')
	{
		Sound('fire2',2,1);
		Sound('beach',3);
		loop1 = setInterval("Sound('beach',3);",5000);
	}
	
	if (a == 'snow')
	{
		Sound('windsand',3);
		loop1 = setInterval("Sound('windsand',3);",9000);
	}
}