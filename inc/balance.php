<?
	for($i=1;$i<7;$i++)
		define("s".$i,"s".$i);
	for($i=1;$i<7;$i++)
		define("ts".$i,"ts".$i);
	for($i=1;$i<6;$i++)
		define("mf1".$i,"mf1".$i);
	define("hp","hp");
	define("ma","ma");
	define("udmin","udmin");
	define("udmax","udmax");
	
	$types = Array("shlem","ojerelie","poyas","sapogi","naruchi","perchatki","bronya","kolco","orujie");
	
	$stypes = Array("shle","kylo","poya","sapo","naru","perc","bron","kolc","noji","topo","book","shit","drob","mech");
	
	function kmult($k1,$k2)
	{
		$result = Array();
		foreach($k1 as $key=>$val)
		{
			$result[$key] = $val*$k2[$key];
		}
		for($i=1;$i<=9;$i++)
		{
			if($k2["tsb".$i] or $k1["tsb".$i]) $result["tsb".$i] = $k1["tsb".$i]+$k2["tsb".$i];
		}
		$result["weight"] = $k1["weight"]+$k2["weight"];
		return $result;
	}
	
	######Кэффициенты классов
	#Воин-Критовик
	$i = 1;
	$k[$i]["s1"] = 0.4;
	$k[$i]["s2"] = 0.1;
	$k[$i]["s3"] = 0.5;
	$k[$i]["s4"] = 0;
	$k[$i]["s5"] = 0;
	$k[$i]["s6"] = 0; 
	$k[$i]["hp"] = 0.7;
	$k[$i]["ma"] = 0;
	$k[$i]["kb"] = 0.6;
	$k[$i]["mf1"] = 0.5; // сокр
	$k[$i]["mf2"] = 0; // уловка
	$k[$i]["mf3"] = 0.3; // точность
	$k[$i]["mf4"] = 0.2; // стойкость
	$k[$i]["udmin"] = 0.6; 
	$k[$i]["udmax"] = 0.6; 
	
	$k[$i]["ts1"] = 0.2;
	$k[$i]["ts2"] = 0.3;
	$k[$i]["ts3"] = 0.2;
	$k[$i]["ts4"] = 0.3;
	$k[$i]["ts5"] = 0;
	$k[$i]["ts6"] = 0;
	######
	
	#Воин-Уворотчик
	$i = 2;
	$k[$i]["s1"] = 0.3;
	$k[$i]["s2"] = 0.5;
	$k[$i]["s3"] = 0.1;
	$k[$i]["s4"] = 0;
	$k[$i]["s5"] = 0;
	$k[$i]["s6"] = 0;
	$k[$i]["hp"] = 0.5;
	$k[$i]["ma"] = 0;
	$k[$i]["kb"] = 0.4;
	$k[$i]["mf1"] = 0; // сокр
	$k[$i]["mf2"] = 0.5; // уловка
	$k[$i]["mf3"] = 0.3; // точность
	$k[$i]["mf4"] = 0.1; // стойкость
	$k[$i]["udmin"] = 0.5; 
	$k[$i]["udmax"] = 0.5; 
	
	$k[$i]["ts1"] = 0.2;
	$k[$i]["ts2"] = 0.2;
	$k[$i]["ts3"] = 0.3;
	$k[$i]["ts4"] = 0.3;
	$k[$i]["ts5"] = 0;
	$k[$i]["ts6"] = 0;
	######
	
	#Воин-Танк
	$i = 3;
	$k[$i]["s1"] = 0.5;
	$k[$i]["s2"] = 0.2;
	$k[$i]["s3"] = 0.2;
	$k[$i]["s4"] = 0;
	$k[$i]["s5"] = 0;
	$k[$i]["s6"] = 0;
	$k[$i]["hp"] = 0.8;
	$k[$i]["ma"] = 0;
	$k[$i]["kb"] = 0.7;
	$k[$i]["mf1"] = 0; // сокр
	$k[$i]["mf2"] = 0; // уловка
	$k[$i]["mf3"] = 0.5; // точность
	$k[$i]["mf4"] = 0.5; // стойкость
	$k[$i]["udmin"] = 0.7; 
	$k[$i]["udmax"] = 0.7; 
	
	$k[$i]["ts1"] = 0.3;
	$k[$i]["ts2"] = 0.2;
	$k[$i]["ts3"] = 0.2;
	$k[$i]["ts4"] = 0.3;
	$k[$i]["ts5"] = 0;
	$k[$i]["ts6"] = 0;
	######
	
	#Маг-Критовик
	$i = 4;
	$k[$i]["s1"] = 0;
	$k[$i]["s2"] = 0.1;
	$k[$i]["s3"] = 0.5;
	$k[$i]["s4"] = 0;
	$k[$i]["s5"] = 0;
	$k[$i]["s6"] = 0.4; 
	$k[$i]["hp"] = 0.3;
	$k[$i]["ma"] = 0.6;
	$k[$i]["kb"] = 0.2;
	$k[$i]["mf1"] = 0.5; // сокр
	$k[$i]["mf2"] = 0; // уловка
	$k[$i]["mf3"] = 0.3; // точность
	$k[$i]["mf4"] = 0.2; // стойкость
	$k[$i]["udmin"] = 0.2; 
	$k[$i]["udmax"] = 0.2; 
	
	$k[$i]["ts1"] = 0;
	$k[$i]["ts2"] = 0.3;
	$k[$i]["ts3"] = 0.2;
	$k[$i]["ts4"] = 0.3;
	$k[$i]["ts5"] = 0;
	$k[$i]["ts6"] = 0.2;
	######
	
	#Маг-Уворотчик
	$i = 5;
	$k[$i]["s1"] = 0;
	$k[$i]["s2"] = 0.5;
	$k[$i]["s3"] = 0.1;
	$k[$i]["s4"] = 0;
	$k[$i]["s5"] = 0;
	$k[$i]["s6"] = 0.3;
	$k[$i]["hp"] = 0.3;
	$k[$i]["ma"] = 0.8;
	$k[$i]["kb"] = 0.2;
	$k[$i]["mf1"] = 0; // сокр
	$k[$i]["mf2"] = 0.5; // уловка
	$k[$i]["mf3"] = 0.3; // точность
	$k[$i]["mf4"] = 0.1; // стойкость
	$k[$i]["udmin"] = 0.1; 
	$k[$i]["udmax"] = 0.1; 
	
	$k[$i]["ts1"] = 0;
	$k[$i]["ts2"] = 0.2;
	$k[$i]["ts3"] = 0.3;
	$k[$i]["ts4"] = 0.3;
	$k[$i]["ts5"] = 0;
	$k[$i]["ts6"] = 0.2;
	######
	
	#Маг-Танк
	$i = 6;
	$k[$i]["s1"] = 0;
	$k[$i]["s2"] = 0.2;
	$k[$i]["s3"] = 0.2;
	$k[$i]["s4"] = 0;
	$k[$i]["s5"] = 0;
	$k[$i]["s6"] = 0.5;
	$k[$i]["hp"] = 0.3;
	$k[$i]["ma"] = 1;
	$k[$i]["kb"] = 0.3;
	$k[$i]["mf1"] = 0; // сокр
	$k[$i]["mf2"] = 0; // уловка
	$k[$i]["mf3"] = 0.5; // точность
	$k[$i]["mf4"] = 0.5; // стойкость
	$k[$i]["udmin"] = 0.3; 
	$k[$i]["udmax"] = 0.3; 
	
	$k[$i]["ts1"] = 0;
	$k[$i]["ts2"] = 0.2;
	$k[$i]["ts3"] = 0.2;
	$k[$i]["ts4"] = 0.3;
	$k[$i]["ts5"] = 0;
	$k[$i]["ts6"] = 0.3;
	######
	

	$r = all_params();	
	#############################Коэффициенты для типов вещей
	foreach($stypes as $stype)
	{
		for($i=0;$i<16;$i++)
			$kt[$stype][$r[$i]] = 1; //Изначально все коэфы равны 1
		for($i=1;$i<7;$i++)
			$kt[$stype]["ts".$i] = 1;
	}
	
	foreach($stypes as $stype)
	{
		if($stype=='noji')
		{
			$kt[$stype]["kb"] = 0;
			$kt[$stype]["udmin"] = 0.3;
			$kt[$stype]["udmax"] = 0.3;
			$kt[$stype]["hp"] = 0;
			$kt[$stype]["ma"] = 0.8;
			$kt[$stype]["s1"] = 0.5;
			$kt[$stype]["s2"] = 0.2;
			$kt[$stype]["s3"] = 0.2;
			$kt[$stype]["s4"] = 0.3;
			$kt[$stype]["s5"] = 0.3;			
			$kt[$stype]["s6"] = 0.5;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.4;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			
			$kt[$stype]["tsb3"] = 1;
			$kt[$stype]["weight"] = 10;
		}
		if($stype=='mech')
		{
			$kt[$stype]["kb"] = 0;
			$kt[$stype]["udmin"] = 0.8;
			$kt[$stype]["udmax"] = 0.8;
			$kt[$stype]["hp"] = 0.4;
			$kt[$stype]["ma"] = 0.8;
			$kt[$stype]["s1"] = 1;
			$kt[$stype]["s2"] = 1;
			$kt[$stype]["s3"] = 1;
			$kt[$stype]["s4"] = 1;
			$kt[$stype]["s5"] = 1;			
			$kt[$stype]["s6"] = 1;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 1;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 0;
			$kt[$stype]["class5"] = 0;
			$kt[$stype]["class6"] = 0;
			
			$kt[$stype]["tsb5"] = 1;
			$kt[$stype]["weight"] = 30;
		}
		if($stype=='topo')
		{
			$kt[$stype]["kb"] = 0;
			$kt[$stype]["udmin"] = 0.9;
			$kt[$stype]["udmax"] = 0.9;
			$kt[$stype]["hp"] = 0.4;
			$kt[$stype]["ma"] = 0.8;
			$kt[$stype]["s1"] = 0.9;
			$kt[$stype]["s2"] = 0.9;
			$kt[$stype]["s3"] = 0.9;
			$kt[$stype]["s4"] = 0.9;
			$kt[$stype]["s5"] = 0.9;			
			$kt[$stype]["s6"] = 0.9;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.9;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 0;
			$kt[$stype]["class5"] = 0;
			$kt[$stype]["class6"] = 0;
			
			$kt[$stype]["tsb6"] = 1;
			$kt[$stype]["weight"] = 40;
		}
		if($stype=='drob')
		{
			$kt[$stype]["kb"] = 0;
			$kt[$stype]["udmin"] = 1;
			$kt[$stype]["udmax"] = 1;
			$kt[$stype]["hp"] = 0.4;
			$kt[$stype]["ma"] = 0.8;
			$kt[$stype]["s1"] = 0.8;
			$kt[$stype]["s2"] = 0.8;
			$kt[$stype]["s3"] = 0.8;
			$kt[$stype]["s4"] = 0.8;
			$kt[$stype]["s5"] = 0.8;			
			$kt[$stype]["s6"] = 0.8;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.8;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 0;
			$kt[$stype]["class5"] = 0;
			$kt[$stype]["class6"] = 0;
			
			$kt[$stype]["tsb7"] = 1;
			$kt[$stype]["weight"] = 50;
		}
		if($stype=='book')
		{
			$kt[$stype]["kb"] = 0;
			$kt[$stype]["udmin"] = 0;
			$kt[$stype]["udmax"] = 0;
			$kt[$stype]["hp"] = 0.8;
			$kt[$stype]["ma"] = 0.8;
			$kt[$stype]["s1"] = 1;
			$kt[$stype]["s2"] = 1;
			$kt[$stype]["s3"] = 1;
			$kt[$stype]["s4"] = 1;
			$kt[$stype]["s5"] = 1;			
			$kt[$stype]["s6"] = 1;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 1;	
				
			$kt[$stype]["class1"] = 0;	
			$kt[$stype]["class2"] = 0;
			$kt[$stype]["class3"] = 0;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			
			$kt[$stype]["tsb8"] = 1;
			$kt[$stype]["weight"] = 10;
		}
		if($stype=='shit')
		{
			$kt[$stype]["kb"] = 1;
			$kt[$stype]["udmin"] = 0.1;
			$kt[$stype]["udmax"] = 0.2;
			$kt[$stype]["hp"] = 0.3;
			$kt[$stype]["ma"] = 0.8;
			$kt[$stype]["s1"] = 0.3;
			$kt[$stype]["s2"] = 0.1;
			$kt[$stype]["s3"] = 0.1;
			$kt[$stype]["s4"] = 0.3;
			$kt[$stype]["s5"] = 0.3;			
			$kt[$stype]["s6"] = 0.5;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.2;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 0;
			$kt[$stype]["class5"] = 0;
			$kt[$stype]["class6"] = 0;
			
			$kt[$stype]["ts1"] = 1;
			$kt[$stype]["ts2"] = 0.7;
			$kt[$stype]["ts3"] = 0.7;
			$kt[$stype]["ts4"] = 1;
			$kt[$stype]["ts5"] = 0.3;			
			$kt[$stype]["ts6"] = 1;
			
			$kt[$stype]["tsb4"] = 1;
			$kt[$stype]["weight"] = 40;
		}
		if($stype=='shle')
		{
			$kt[$stype]["kb"] = 0.4;
			$kt[$stype]["udmin"] = 0;
			$kt[$stype]["udmax"] = 0;
			$kt[$stype]["hp"] = 0.4;
			$kt[$stype]["ma"] = 0.4;
			$kt[$stype]["s1"] = 0.2;
			$kt[$stype]["s2"] = 0.2;
			$kt[$stype]["s3"] = 0.2;
			$kt[$stype]["s4"] = 0.2;
			$kt[$stype]["s5"] = 0.2;			
			$kt[$stype]["s6"] = 0.2;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.4;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			$kt[$stype]["weight"] = 10;
		}
		if($stype=='perc')
		{
			$kt[$stype]["kb"] = 0.3;
			$kt[$stype]["udmin"] = 0.05;
			$kt[$stype]["udmax"] = 0.05;
			$kt[$stype]["hp"] = 0.1;
			$kt[$stype]["ma"] = 0.1;
			$kt[$stype]["s1"] = 0.8;
			$kt[$stype]["s2"] = 0.8;
			$kt[$stype]["s3"] = 0.8;
			$kt[$stype]["s4"] = 0.8;
			$kt[$stype]["s5"] = 0.8;			
			$kt[$stype]["s6"] = 0.8;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.1;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			$kt[$stype]["weight"] = 5;
			
			$kt[$stype]["ts1"] = 0.7;
			$kt[$stype]["ts2"] = 0.7;
			$kt[$stype]["ts3"] = 0.7;
			$kt[$stype]["ts4"] = 0.7;
			$kt[$stype]["ts5"] = 0.3;			
			$kt[$stype]["ts6"] = 1;
		}
		if($stype=='naru')
		{
			$kt[$stype]["kb"] = 0.4;
			$kt[$stype]["udmin"] = 0.08;
			$kt[$stype]["udmax"] = 0.08;
			$kt[$stype]["hp"] = 0.4;
			$kt[$stype]["ma"] = 0.4;
			$kt[$stype]["s1"] = 0.3;
			$kt[$stype]["s2"] = 0.3;
			$kt[$stype]["s3"] = 0.3;
			$kt[$stype]["s4"] = 0.3;
			$kt[$stype]["s5"] = 0.3;			
			$kt[$stype]["s6"] = 0.3;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.2;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			$kt[$stype]["weight"] = 7;
		}
		if($stype=='bron')
		{
			$kt[$stype]["kb"] = 1;
			$kt[$stype]["udmin"] = 0;
			$kt[$stype]["udmax"] = 0;
			$kt[$stype]["hp"] = 1;
			$kt[$stype]["ma"] = 1;
			$kt[$stype]["s1"] = 0.3;
			$kt[$stype]["s2"] = 0.3;
			$kt[$stype]["s3"] = 0.3;
			$kt[$stype]["s4"] = 0.3;
			$kt[$stype]["s5"] = 0.3;			
			$kt[$stype]["s6"] = 0.3;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.8;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			
			$kt[$stype]["ts1"] = 1;
			$kt[$stype]["ts2"] = 0.8;
			$kt[$stype]["ts3"] = 0.8;
			$kt[$stype]["ts4"] = 1;
			$kt[$stype]["ts5"] = 0.8;			
			$kt[$stype]["ts6"] = 1;
			$kt[$stype]["weight"] = 80;
		}
		if($stype=='kolc')
		{
			$kt[$stype]["kb"] = 0.1;
			$kt[$stype]["udmin"] = 0.03;
			$kt[$stype]["udmax"] = 0.03;
			$kt[$stype]["hp"] = 0.1;
			$kt[$stype]["ma"] = 0.1;
			$kt[$stype]["s1"] = 0.2;
			$kt[$stype]["s2"] = 0.2;
			$kt[$stype]["s3"] = 0.2;
			$kt[$stype]["s4"] = 0.2;
			$kt[$stype]["s5"] = 0.2;			
			$kt[$stype]["s6"] = 0.2;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.3;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			
			$kt[$stype]["ts1"] = 0.3;
			$kt[$stype]["ts2"] = 0.6;
			$kt[$stype]["ts3"] = 0.6;
			$kt[$stype]["ts4"] = 1;
			$kt[$stype]["ts5"] = 0.3;			
			$kt[$stype]["ts6"] = 0.3;
			$kt[$stype]["weight"] = 3;
		}
		if($stype=='kylo')
		{
			$kt[$stype]["kb"] = 0.15;
			$kt[$stype]["udmin"] = 0;
			$kt[$stype]["udmax"] = 0;
			$kt[$stype]["hp"] = 0.15;
			$kt[$stype]["ma"] = 0.15;
			$kt[$stype]["s1"] = 0.3;
			$kt[$stype]["s2"] = 0.3;
			$kt[$stype]["s3"] = 0.3;
			$kt[$stype]["s4"] = 0.3;
			$kt[$stype]["s5"] = 0.3;			
			$kt[$stype]["s6"] = 0.3;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.1;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			
			$kt[$stype]["ts1"] = 1;
			$kt[$stype]["ts2"] = 0.3;
			$kt[$stype]["ts3"] = 0.3;
			$kt[$stype]["ts4"] = 0.3;
			$kt[$stype]["ts5"] = 0.3;			
			$kt[$stype]["ts6"] = 1;
			$kt[$stype]["weight"] = 4;
		}
		if($stype=='sapo')
		{
			$kt[$stype]["kb"] = 0.6;
			$kt[$stype]["udmin"] = 0.09;
			$kt[$stype]["udmax"] = 0.09;
			$kt[$stype]["hp"] = 0.4;
			$kt[$stype]["ma"] = 0.4;
			$kt[$stype]["s1"] = 0.2;
			$kt[$stype]["s2"] = 0.2;
			$kt[$stype]["s3"] = 0.2;
			$kt[$stype]["s4"] = 0.2;
			$kt[$stype]["s5"] = 0.2;			
			$kt[$stype]["s6"] = 0.2;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 0.3;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			$kt[$stype]["weight"] = 20;
		}
		if($stype=='poya')
		{
			$kt[$stype]["kb"] = 0.1;
			$kt[$stype]["udmin"] = 0;
			$kt[$stype]["udmax"] = 0;
			$kt[$stype]["hp"] = 0.1;
			$kt[$stype]["ma"] = 0.1;
			$kt[$stype]["s1"] = 0.2;
			$kt[$stype]["s2"] = 0.2;
			$kt[$stype]["s3"] = 0.2;
			$kt[$stype]["s4"] = 0.2;
			$kt[$stype]["s5"] = 0.2;			
			$kt[$stype]["s6"] = 0.2;		
			for($i=0;$i<6;$i++)
				$kt[$stype]["mf".$i] = 1;	
				
			$kt[$stype]["class1"] = 1;	
			$kt[$stype]["class2"] = 1;
			$kt[$stype]["class3"] = 1;
			$kt[$stype]["class4"] = 1;
			$kt[$stype]["class5"] = 1;
			$kt[$stype]["class6"] = 1;
			$kt[$stype]["weight"] = 10;
		}
	}
	
	
	function _MakeItem($level,$class,$stype,$power = 1,$tpower = 1)
	{
		GLOBAL $k,$kt,$stypes,$types;
		$power *= 1.1;
		if($kt[$stype]["class".$class]==0) return false;
		$z = kmult($k[$class],$kt[$stype]);
		$result = array();
		$summ = sqlr("SELECT SUM(stats) FROM exp WHERE level<=".intval($level))+20;
		foreach($stypes as $ss)
		{
			$ztmp = kmult($k[$class],$kt[$ss]);
			for($i=1;$i<=6;$i++) 
				$summ += floor($ztmp["s".$i]*$level);
		}
		$summ *= 0.63;
		foreach($z as $key=>$value)
		{
			$t = 1;
			if($key=='kb') $t = 10;
			if($key=='hp') $t = 10;
			if($key=='ma') $t = 10;
			if($key=='udmin') $t = 4;
			if($key=='udmax') $t = 6;
			if($key=='mf1') $t = 10;
			if($key=='mf2') $t = 10;
			if($key=='mf3') $t = 10;
			if($key=='mf4') $t = 10;
			if($key=='mf5') $t = 10;
			if($key=='weight')
			{
				$result[$key] = floor(   $value*$level/10   );
			}
			elseif($key[2]=='b')
			{
				$result[$key] = floor(   $level*1.5   );
			}
			elseif($key[0]!='t')
				$result[$key] = floor(   $value*$level*$power*$t   );
			else
				$result[$key] = floor(   $value*$summ*$tpower   );
			if((substr($key,0,2)=='mf' or $key=='hp' or $key=='ma') and $result[$key]>30)
				$result[$key] = floor($result[$key]/5)*5;
		}
			$s = array_search($stype, $stypes);
			if($s<9) 
				$result["type"] = $types[$s];
			else	
				$result["type"] = "orujie";
			$result["tlevel"] = $level;
			$result["max_durability"] = $result["weight"];
			$result["stype"] = $stype;
			$result["weight"] *= $tpower;
		return $result;
	}
	
	function CalculatePrice($r)
	{
		$cost = $r["s1"]*4+
				$r["s2"]*5+
				$r["s3"]*5+
				$r["s4"]*6+
				$r["s5"]*2+
				$r["s6"]*7+
				$r["kb"]*2+
				$r["hp"]*1+
				$r["ma"]*1+
				$r["mf1"]*0.4+
				$r["mf2"]*0.4+
				$r["mf3"]*0.35+
				$r["mf4"]*0.35+
				$r["mf5"]*0.3+
				$r["udmin"]*4+
				$r["udmax"]*4;
		if($r["tlevel"]<6) $cost = $cost/4;
		if($r["tlevel"]>=10) $cost = $cost*2;
		if($r["tlevel"]>=12) $cost = $cost*3;
		if($r["tlevel"]>=15) $cost = $cost*3;
		return intval($cost);
	}
	
	function CalculateDPrice($r)
	{
		$cost = $r["s1"]*4+
				$r["s2"]*5+
				$r["s3"]*5+
				$r["s4"]*6+
				$r["s5"]*2+
				$r["s6"]*7+
				$r["kb"]*2+
				$r["hp"]*1+
				$r["ma"]*1+
				$r["mf1"]*0.4+
				$r["mf2"]*0.4+
				$r["mf3"]*0.35+
				$r["mf4"]*0.35+
				$r["mf5"]*0.3+
				$r["udmin"]*4+
				$r["udmax"]*4;
		return intval(intval($cost/2)/5)*5;
	}
	
	function Class_Params($level,$class,$wpclass = 'mech',$power = 1)
	{
		GLOBAL $stypes;
		$_class = Array();
		foreach($stypes as $stype)
		{
					$r = _MakeItem($level,$class,$stype,$power);
					if($r===false) continue;
									
					foreach($r as $key=>$value)
					{
						if($stype!='topo' and $stype!='drob' and $stype!='shit' and $stype!='kolc' and $stype!='mech' or $stype==$wpclass)
							$_class[$key] += $value;
						if($stype=='kolc')
							$_class[$key] += $value*2;
					}		
		}
		return $_class;
	}
	
	function AddAllWp()
	{
		GLOBAL $stypes;
		$mid = sqlr("SELECT MAX(idn) FROM weapons",0)+1;
		if ($mid<500) $mid+=500;
		for($i=3;$i<=15;$i++)
		{
			foreach($stypes as $stype)
			{
				for($j=1;$j<=6;$j++)
				{
					$mid++;
					$v = _MakeItem($i,$j,$stype,1);
					if($v===false) continue;
					$v["price"] = CalculatePrice($v);
					
					$v["name"] = $stype."/".$i."_".$j;
					$v["image"] = "i/".$stype."/".$i."_".$j;
					

					sql("INSERT INTO `weapons` (`id`,`idn`,`price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `radius` , `slots` ,`arrows` ,`arrows_max` ,`arrow_name` , `arrow_price` , `tlevel` , `ts1` , `ts2` , `ts3` , `ts4` , `ts5` , `ts6` , `kb` , `udmin` , `udmax` , `hp` , `ma` , `mf1` , `mf2` , `mf3` , `mf4` , `mf5` , `s1` , `s2` , `s3` , `s4` , `s5` , `s6` , `tsb1`, `tsb2`, `tsb3`, `tsb4`, `tsb5`, `tsb6` , `tsb7` , `tsb8` , `tsb9` , `sb1` , `sb7` , `sb8` , `sb9` , `sb10` , `sb11` , `sb12` , `sb13` , `sb14` , `sm7` , `sm8` , `sm9` , `sm10` , `tsp1` , `tsp2` , `tsp3` , `tsp4` , `tsp5` , `tsp6` , `tsp7` , `tsp8` , `tsp9` , `tsp10` , `sp1` , `sp2` , `sp3` , `sp4` , `sp5` , `sp6` , `sp7` , `sp8` , `sp9` , `sp10` , `tsp11` , `sp11` , `tsp12` , `sp12` , `tsp13` , `sp13`,`p_type` ,`m1`,`m2`,`m3`,`m4`,`m5`,`tm1`,`tm2`,`tm3`,`tm4`,`tm5`,`q_s`) 
VALUES ('".$mid."','".$mid."','".$v["price"]."', '".$v["dprice"]."', '".$v["image"]."', '".$v["index"]."', '".$v["type"]."', '".$v["stype"]."', '".$v["name"]."', '".$v["describe"]."', '".$v["weight"]."', '0', '".$v["max_durability"]."', '".$v["radius"]."', '".$v["slots"]."', '".$v["arrows"]."', '".$v["arrows_max"]."', '".$v["arrow_name"]."', '".$v["arrow_price"]."', '".$v["tlevel"]."', '".$v["ts1"]."', '".$v["ts2"]."', '".$v["ts3"]."', '".$v["ts4"]."', '".$v["ts5"]."', '".$v["ts6"]."', '".$v["kb"]."', '".$v["udmin"]."', '".$v["udmax"]."', '".$v["hp"]."', '".$v["ma"]."', '".$v["mf1"]."', '".$v["mf2"]."', '".$v["mf3"]."', '".$v["mf4"]."', '".$v["mf5"]."', '".$v["s1"]."', '".$v["s2"]."', '".$v["s3"]."', '".$v["s4"]."', '".$v["s5"]."', '".$v["s6"]."', '".$v["tsb1"]."', '".$v["tsb2"]."', '".$v["tsb3"]."', '".$v["tsb4"]."', '".$v["tsb5"]."', '".$v["tsb6"]."', '".$v["tsb7"]."', '".$v["tsb8"]."', '".$v["tsb9"]."', '".$v["sb1"]."', '".$v["sb7"]."', '".$v["sb8"]."', '".$v["sb9"]."', '".$v["sb10"]."', '".$v["sb11"]."', '".$v["sb12"]."', '".$v["sb13"]."', '".$v["sb14"]."', '".$v["sm7"]."', '".$v["sm8"]."', '".$v["sm9"]."', '".$v["sm10"]."', '".$v["tsp1"]."', '".$v["tsp2"]."', '".$v["tsp3"]."', '".$v["tsp4"]."', '".$v["tsp5"]."', '".$v["tsp6"]."', '".$v["tsp7"]."', '".$v["tsp8"]."', '".$v["tsp9"]."', '".$v["tsp10"]."', '".$v["sp1"]."', '".$v["sp2"]."', '".$v["sp3"]."', '".$v["sp4"]."', '".$v["sp5"]."', '".$v["sp6"]."', '".$v["sp7"]."', '".$v["sp8"]."', '".$v["sp9"]."', '".$v["sp10"]."', '".$v["tsp11"]."', '".$v["sp11"]."', '".$v["tsp12"]."', '".$v["sp12"]."', '".$v["tsp13"]."', '".$v["sp13"]."','".$v["p_type"]."', '".$v["m1"]."', '".$v["m2"]."', '".$v["m3"]."', '".$v["m4"]."', '".$v["m5"]."', '".$v["tm1"]."', '".$v["tm2"]."', '".$v["tm3"]."', '".$v["tm4"]."', '".$v["tm5"]."',10000);");
				}
			}
		}
	}
	
	function AddAllArtWp()
	{
		GLOBAL $stypes;
		$mid = sqlr("SELECT MAX(idn) FROM weapons",0)+1;
		if ($mid<10000) $mid+=10000;
		for($i=5;$i<=20;$i+=5)
		{
			foreach($stypes as $stype)
			{
				for($j=1;$j<=6;$j++)
				{
					$mid++;
					$v = _MakeItem($i,$j,$stype,2,0.5);
					if($v===false) continue;
					$v["dprice"] = CalculateDPrice($v);
					
					$v["name"] = $stype."/".($i)."_".$j;
					$v["image"] = "i/".$stype."/".($i/5+3)."_".$j;
					

					sql("INSERT INTO `weapons` (`id`,`idn`,`price` , `dprice` , `image` , `index` , `type` , `stype` , `name` , `describe` , `weight` , `where_buy` , `max_durability` , `radius` , `slots` ,`arrows` ,`arrows_max` ,`arrow_name` , `arrow_price` , `tlevel` , `ts1` , `ts2` , `ts3` , `ts4` , `ts5` , `ts6` , `kb` , `udmin` , `udmax` , `hp` , `ma` , `mf1` , `mf2` , `mf3` , `mf4` , `mf5` , `s1` , `s2` , `s3` , `s4` , `s5` , `s6` , `tsb1`, `tsb2`, `tsb3`, `tsb4`, `tsb5`, `tsb6` , `tsb7` , `tsb8` , `tsb9` , `sb1` , `sb7` , `sb8` , `sb9` , `sb10` , `sb11` , `sb12` , `sb13` , `sb14` , `sm7` , `sm8` , `sm9` , `sm10` , `tsp1` , `tsp2` , `tsp3` , `tsp4` , `tsp5` , `tsp6` , `tsp7` , `tsp8` , `tsp9` , `tsp10` , `sp1` , `sp2` , `sp3` , `sp4` , `sp5` , `sp6` , `sp7` , `sp8` , `sp9` , `sp10` , `tsp11` , `sp11` , `tsp12` , `sp12` , `tsp13` , `sp13`,`p_type` ,`m1`,`m2`,`m3`,`m4`,`m5`,`tm1`,`tm2`,`tm3`,`tm4`,`tm5`,`q_s`) 
VALUES ('art_".$mid."','".$mid."','0', '".$v["dprice"]."', '".$v["image"]."', '".$v["index"]."', '".$v["type"]."', '".$v["stype"]."', '".$v["name"]."', '".$v["describe"]."', '".$v["weight"]."', '1', '".$v["max_durability"]."', '".$v["radius"]."', '".$v["slots"]."', '".$v["arrows"]."', '".$v["arrows_max"]."', '".$v["arrow_name"]."', '".$v["arrow_price"]."', '".$v["tlevel"]."', '".$v["ts1"]."', '".$v["ts2"]."', '".$v["ts3"]."', '".$v["ts4"]."', '".$v["ts5"]."', '".$v["ts6"]."', '".$v["kb"]."', '".$v["udmin"]."', '".$v["udmax"]."', '".$v["hp"]."', '".$v["ma"]."', '".$v["mf1"]."', '".$v["mf2"]."', '".$v["mf3"]."', '".$v["mf4"]."', '".$v["mf5"]."', '".$v["s1"]."', '".$v["s2"]."', '".$v["s3"]."', '".$v["s4"]."', '".$v["s5"]."', '".$v["s6"]."', '".$v["tsb1"]."', '".$v["tsb2"]."', '".$v["tsb3"]."', '".$v["tsb4"]."', '".$v["tsb5"]."', '".$v["tsb6"]."', '".$v["tsb7"]."', '".$v["tsb8"]."', '".$v["tsb9"]."', '".$v["sb1"]."', '".$v["sb7"]."', '".$v["sb8"]."', '".$v["sb9"]."', '".$v["sb10"]."', '".$v["sb11"]."', '".$v["sb12"]."', '".$v["sb13"]."', '".$v["sb14"]."', '".$v["sm7"]."', '".$v["sm8"]."', '".$v["sm9"]."', '".$v["sm10"]."', '".$v["tsp1"]."', '".$v["tsp2"]."', '".$v["tsp3"]."', '".$v["tsp4"]."', '".$v["tsp5"]."', '".$v["tsp6"]."', '".$v["tsp7"]."', '".$v["tsp8"]."', '".$v["tsp9"]."', '".$v["tsp10"]."', '".$v["sp1"]."', '".$v["sp2"]."', '".$v["sp3"]."', '".$v["sp4"]."', '".$v["sp5"]."', '".$v["sp6"]."', '".$v["sp7"]."', '".$v["sp8"]."', '".$v["sp9"]."', '".$v["sp10"]."', '".$v["tsp11"]."', '".$v["sp11"]."', '".$v["tsp12"]."', '".$v["sp12"]."', '".$v["tsp13"]."', '".$v["sp13"]."','".$v["p_type"]."', '".$v["m1"]."', '".$v["m2"]."', '".$v["m3"]."', '".$v["m4"]."', '".$v["m5"]."', '".$v["tm1"]."', '".$v["tm2"]."', '".$v["tm3"]."', '".$v["tm4"]."', '".$v["tm5"]."',10000);");
				}
			}
		}
	}
/*
	######Выведем все вещи как в лавке.
	echo "<center><table border=0 width=600>";
	for($i=3;$i<=15;$i++)
	{
		foreach($stypes as $stype)
		{
			for($j=1;$j<=6;$j++)
				{
				//	$image = rand(100,500);
					$r = _MakeItem($i,$j,$stype,1);
					if($r===false) continue;
					$r["price"] = CalculatePrice($r);
					
					if($r["ts6"])
					$name_image = sqla("SELECT name FROM weapons WHERE stype='".$stype."' and ts6>0 and price<".$r["price"]." ORDER BY RAND()");
					else
					$name_image = sqla("SELECT name FROM weapons WHERE stype='".$stype."' and ts6=0 and price<".$r["price"]." ORDER BY RAND()");
					$r["name"] = $name_image["name"];
					$r["image"] = $stype."/".$i."_".$j;//$image.'_'.($image*2);
					
					//$a = implode("|",$r);
					$a = '';
					foreach($r as $key=>$value)
						$a .= $key.'='.name_of_skill($key).'|';
					echo $a;
					break;
					
					//$r["id"] = 1;
					//$vesh = $r;
					
					foreach($r as $key=>$value)
					{
						if($stype!='topo' and $stype!='drob' and $stype!='shit' and $stype!='kolc')
							$class[$i][$j][$key] += $value;
						if($stype=='kolc')
							$class[$i][$j][$key] += $value*2;
					}
						echo "\n";			
				}break;
		}break;
	}*/
	
	/*
	foreach($class as $c)
		foreach($c as $key=>$cl)
		{
			echo "<center class=but>".$key;
			echo '<table class=LinedTable border=0 cellspacing=0 cellspadding=0 width=500>';
			foreach ($cl as $key=>$value)
			{
				if (substr($key,0,2)=='mf') $perc = '%'; else $perc = '';
				if ($key == 'kb') $perc = '<b class=green>КБ</b>';
				if ($key == 'hp') $perc = '<b class=hp>HP</b>';
				if ($key == 'ma') $perc = '<b class=ma>MP</b>';
				if($value and $key[0]!='t')
				echo "<tr><td width=60% class=items>".name_of_skill($key).": </td><td class=items><b>".plus_param($value).$perc."</b></td></tr>";
				
			}
			echo '</table>';
			echo "</center>";
		}
	echo "</table>";
	
	*/
	
?>









