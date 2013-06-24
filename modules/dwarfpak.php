<?php
#  Dwarf Pak - 28Jun2006
#  Author: Robert of maddrio.com
#  combines stuckdwarf, sleepingdwarf and stumblingdwarf into one file
#  adds 4 new events making a total of 7
#  1st public version is 2.0
#  originals were 097 forest events

function dwarfpak_getmoduleinfo(){
	$info = array(
		"name"=>"Dwarf Pak",
		"version"=>"2.0",
		"author"=>"`2Robert</a>",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?topic=2215.0",
		"settings"=>array(
			"Dwarf Pak - Settings,title",
			"turnset"=>"How many turns to gain or lose?,range,1,10,1|1",
			"The gold setting is multiplied by player level,note",
			"goldset"=>"How much gold to gain or lose?,range,2,100,2|20",
			"The name setting can be whoever, dwarf is default,note",
			"nameset"=>"Who are appearing in these events?,|`6Dwarf",
		),
			"prefs"=>array(
			"Dwarf Pak - User Prefs,title",
			"event"=>"Which event is coming next (7 possible)?,int|1",
		)
	);
	return $info;
}

function dwarfpak_install(){
	if (!is_module_active('dwarfpak')){
		output("`^ Installing Dwarf Pak - forest event `n`0");
	}else{
		output("`^ Up Dating Dwarf Pak - forest event `n`0");
	}
	module_addeventhook("forest","return 50;");
	return true;
}

function dwarfpak_uninstall(){
	output("`^ Un-Installing Dwarf Pak - forest event `n`0");
	return true;
}

function dwarfpak_dohook($hookname,$args){
	return $args;
}

function dwarfpak_runevent($type){
	global $session;
	$gold=$session['user']['gold'];
	$level=$session['user']['level'];
	$min = round(get_module_setting("goldset")/2);
	$max = get_module_setting("goldset");
	$nameset = get_module_setting("nameset");
	$rand = e_rand(1,get_module_setting("goldset"));
	$goldpay= round($level*$max);
	if ($session['user']['sex']==0){ $sex="m'lord"; }else{ $sex="m'lady";}
	$who=get_module_setting("nameset");
	$event=get_module_pref("event");
	
	if (is_module_active('alignment')) {
	$evil = get_module_setting('evilalign','alignment');
	$good = get_module_setting('goodalign','alignment');
	$alignment = get_module_pref('alignment','alignment');
#	$evilmessage1 = get_module_setting('evilmessage1'); not used in this verion
#	$evilmessage2 = get_module_setting('evilmessage2'); ""
#	$evilmessage3 = get_module_setting('evilmessage3'); ""
	}
	
	if ($event == 1){
		switch(e_rand(1,6)){
		case 1: case 4:
			output("`n`n`2 You have come across a sleeping %s`2, you steal $rand of his gold! ",$who,$rand);
			$session['user']['gold']+=$rand;
			debuglog(" stole $rand gold from a sleeping $who ");
			break;
		case 2: case 5:
			output("`n`n`2 You have come across a sleeping $who`2, you quietly try to steal his gold. ",$who);
			output("`n`n He awakens, grabs his pouch and runs away ...dropping %s of his gold! ",$min);
			$session['user']['gold']+=$min;
			debuglog(" gained $min gold from a sleeping $who ");
			break;
		case 3: case 6:
			output("`n`n`2 You have come across a sleeping %s`2, you try to steal gold from his pouch. ",$who);
			output("`n`n He rolls over in his sleep, hiding his pouch, but not before you nik %s of his gold! ",$goldpay);
			$session['user']['gold']+=$goldpay;
			debuglog(" stole $goldpay gold from a sleeping $who ");
			break;
		}
		set_module_pref("event",2);
	}
	if ($event == 2){
		output("`n`^ LUCKY YOU! ");
		output("`n`n`2 Finding a %s `2hanging upside down from a tree. ",$who);
		output("`n`n Seems he got his foot caught in a hunter's animal snare! ");
		output("`n`n You shake the %s `2until `^his Gold `2falls out to the ground. ",$who);
		switch(e_rand(1,5)){
			case 1:
			output("`n`n You count %s `2, Thank the %s `2and leave him there!",$rand,$who);
			$session['user']['gold']+=$rand;
			debuglog(" gained $rand gold from a stuck $who ");
			break;
			case 2: case 3: case 4:
			output("`n`n You count %s `2, and say to him, a pauper you are? ...too bad!",$min);
			$session['user']['gold']+=$min;
			debuglog(" gained $min gold from a stuck $who ");
			break;
			case 5:
			output("`n`n You count %s `2, and say to him, Ahh... thank you `bvery much`b my little friend!",$max);
			$session['user']['gold']+=$max;
			debuglog(" gained $max gold from a stuck $who ");
			break;
		}
		if ($alignment <=30){
			output("`n`n`\$ You grab a nearby tree limb and beat on the helpless $who",$who);
			increment_module_pref('alignment',-1,'alignment');
		}
		if ($alignment >=70){
			output("`n`n`@ Feeling sorry for the poor %s `@you cut them down and they thank you. ",$who);
			increment_module_pref('alignment',+1,'alignment');
		}
		set_module_pref("event",3);
	}
	if ($event == 3){
		output("`n`n`2 You find a %s `2walking around in a daze, he has a big knot on his head. ",$who);
		if ($alignment <=30){
			output("`n`n`\$ You slay the %s `\$ and find `^ %s gold `\$ in their pouch!",$who,$rand);
			$session['user']['gold']+=$rand;
			debuglog(" gained $rand gold from a dazed $who ");
			increment_module_pref('alignment',-1,'alignment');
		}elseif ($alignment >=70){
			output("`n`n`@ You help the  %s `@find their way back home. ",$who);
			output("`n`n  The family is glad to see them again and gives you `^ %s gold `@as a reward. ",$rand);
			$session['user']['gold']+=$rand;
			debuglog(" gained $rand gold from a dazed $who ");
			increment_module_pref('alignment',+1,'alignment');
		}else{
			output("`n`n`2 You learn the %s `2 was recently attacked by bandits and warns you to be very careful. ",$who);
		}
		set_module_pref("event",4);
	}
	if ($event == 4){
		output("`n`n`2 You stumble upon the carcass of what appears to be a %s.",$who);
		if ($alignment <= $evil){
			output("`n`n`\$ You discover the %s `\$ was slain and robbed by bandits and has no pouch.",$who);
		}elseif ($alignment >= $good){
			output("`n`n`@ You discover the identity of the %s `@ and inform the family, who thanks you. ",$who);
		}else{
			output("`n`n`2 You cant tell who the %s `2is but in their pouch you find `^ %s gold`2! ",$who,$rand);
			$session['user']['gold']+=$rand;
			debuglog(" gained $rand gold from a $who carcass");
		}
		set_module_pref("event",5);
	}
	if ($event == 5){
		output("`n`n`2 Your travels force to to pass through a small obscure %s Village`2. ",$who);
		output("`n`n Clumsy creatures, always stumbling and bumping into you. ");
		if ($session['user']['gold']>=10){
			$gold=round($session['user']['gold']*.5);
			output("`n`n Upon leaving the village you find out you are missing `^ %s of your gold`2!`",$gold);
			$session['user']['gold']-=$gold;
			debuglog(" $who steals $gold gold ");
		}
		if ($alignment <= $evil){
			output("`n`n`\$ You curse out loud and vow to slay all of the %s's`\$!",$who);
		}
		if ($alignment >= $good){
			output("`n`n`@ You blame yourself for not being more careful when around so many %s's`@.",$who);
		}
		set_module_pref("event",6);
	}
	if ($event == 6){
		output("`n`n`2 You have come upon a %s caught under a fallen tree. ",$who);
		output("`n`n Knowing to leave him now will be his demise, ");
		if ($alignment >= $good){
			output("`n`n`2 you lift the tree thats upon him. ");
			output("`n`n Grateful to be saved from certain death, he gives you `^ %s gold`2! ",$goldpay);
			debuglog(" got $goldpay gold from $who under a tree ");
			$session['user']['gold']+=$goldpay;
		}elseif ($alignment <= $evil){
			output("`n`n`\$ you stand there ...waiting for him to breathe his last breath. ");
			output("`n`n Within moments, he dies. You take `^ %s gold `\$ from his pouch! ",$goldpay);
			debuglog(" got $goldpay gold from $who under a tree ");
			$session['user']['gold']+=$goldpay;
		}else{
			output("`n`n`2 Not caring wether he lives or dies ...you continue your travels in the forest. ");
		}
		set_module_pref("event",7);
	}
	if ($event == 7){
		output("`n`n`2 You have come upon a fallen tree. ");
		output("`n`n Under the tree you see the remains of a %s`2. ",$who);
		output("`n`n Poor little guy must have been trapped and could not get out. ");
		output("`n`n You search the remains and within his money pouch you find `^ %s gold`2! ",$max);
		debuglog(" got $max gold from a dead $who ");
		$session['user']['gold']+=$max;
		set_module_pref("event",1);
	}
}

function dwarfpak_run(){
}
?>