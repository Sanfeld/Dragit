<?php
function oceanquest_expeditionnav(){
	addnav("Deep Sea Fishing");
	$op = httpget('op');
	$allprefs=unserialize(get_module_pref('allprefs'));
	$fishingtoday=$allprefs['fishingtoday'];
	if ($fishingtoday<5){
		$fishingleft=5-$fishingtoday;
		$op3=$allprefs['quality'];
		addnav("More Fishing","runmodule.php?module=oceanquest&op=$op&op2=fish&op3=$op3");
		addnav("Check Gauges","runmodule.php?module=oceanquest&op=$op&op2=gauge&op3=$op3");
		output("`n`n`c`@Fishing Turns Left: `^%s`c",$fishingleft);
	}else{
		output("`n`n`c`\$No Fishing Turns Left`7`c");
		$allprefs['bait']=0;
		set_module_pref('allprefs',serialize($allprefs));
	}
	addnav("Move the Ship","runmodule.php?module=oceanquest&op=$op&loc=".get_module_pref("pqtemp"));
}
function oceanquest_drinknav(){
	global $session;
	$ale=$session['user']['level']*get_module_setting("price1");
	$mead=$session['user']['level']*get_module_setting("price2");
	$rum=$session['user']['level']*get_module_setting("price3");
	$saltydog=$session['user']['level']*get_module_setting("price4");
	$round=get_module_setting("round");
	$aleround=$ale*$round;
	$meadround=$mead*$round;
	$rumround=$rum*$round;
	$saltydoground=$saltydog*$round;
	addnav("The Bar");
	addnav(array("Ale - `^%s Gold", $ale),"runmodule.php?module=oceanquest&op=docks&op2=pubdrink&op3=1&op4=$ale");
	addnav(array("Mead - `^%s Gold", $mead),"runmodule.php?module=oceanquest&op=docks&op2=pubdrink&op3=2&op4=$mead");
	addnav(array("Rum - `^%s Gold", $rum),"runmodule.php?module=oceanquest&op=docks&op2=pubdrink&op3=3&op4=$rum");
	addnav(array("Salty Dog - `^%s Gold", $saltydog),"runmodule.php?module=oceanquest&op=docks&op2=pubdrink&op3=4&op4=$saltydog");
	addnav(array("`5Round of `&Ale - `^%s Gold", $aleround),"runmodule.php?module=oceanquest&op=docks&op2=pubdrink&op3=5&op4=$aleround");
	addnav(array("`5Round of `&Mead - `^%s Gold", $meadround),"runmodule.php?module=oceanquest&op=docks&op2=pubdrink&op3=6&op4=$meadround");
	addnav(array("`5Round of `&Rum - `^%s Gold", $rumround),"runmodule.php?module=oceanquest&op=docks&op2=pubdrink&op3=7&op4=$rumround");
	addnav(array("`5Round of `&Salty Dogs - `^%s Gold", $saltydoground),"runmodule.php?module=oceanquest&op=docks&op2=pubdrink&op3=8&op4=$saltydoground");
}
function oceanquest_pubnav(){
	addnav("Seaside Pub");
	addnav("Listen to Musicians","runmodule.php?module=oceanquest&op=docks&op2=pubsingers");
	addnav("Go to the Bar","runmodule.php?module=oceanquest&op=docks&op2=pubbar");
	addnav("Chat with `&Ulber","runmodule.php?module=oceanquest&op=docks&op2=pubchat&op3=1"); // Likes Salty Dogs 8
	addnav("Chat with `QTrandor","runmodule.php?module=oceanquest&op=docks&op2=pubchat&op3=2"); // Likes Rum 7, knows the freestone
	addnav("Chat with `!Quint","runmodule.php?module=oceanquest&op=docks&op2=pubchat&op3=3"); // Likes Anything 5 6 7 8, knows the corinth
	addnav("Chat with `)Piper","runmodule.php?module=oceanquest&op=docks&op2=pubchat&op3=4"); // Likes Mead 6
	addnav("Chat with `\$Rinto","runmodule.php?module=oceanquest&op=docks&op2=pubchat&op3=5"); // Likes Ale 5
	addnav("Leave");
	addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
}
function oceanquest_baitnav(){
	$allprefs=unserialize(get_module_pref('allprefs'));
	$rand=e_rand(1,3);
	page_header("Bait and Tackle Shop");
	addnav("Bait Shop");
	addnav("Chat with Hoglin","runmodule.php?module=oceanquest&op=docks&op2=fishchat");
	if ($allprefs['coconut']==1 && $rand==1) addnav("Chat with Bernie","runmodule.php?module=oceanquest&op=docks&op2=coconut");
	addnav("Fishing Poles","runmodule.php?module=oceanquest&op=docks&op2=fishpoles");
	addnav("Bait","runmodule.php?module=oceanquest&op=docks&op2=fishbait");
	if ($allprefs['fishbook']=="" || $allprefs['fishbook']==0) addnav("Fishing Books","runmodule.php?module=oceanquest&op=docks&op2=fishbooks");
	else addnav("Read Your Fishing Book","runmodule.php?module=oceanquest&op=docks&op2=readfishbook&op3=store");
	addnav("Notices","runmodule.php?module=oceanquest&op=docks&op2=fishnotices");
	addnav("Biggest Fish Caught","runmodule.php?module=oceanquest&op=docks&op2=bigfish");
	addnav("Most Fish by Weight","runmodule.php?module=oceanquest&op=docks&op2=fishweight");
	addnav("Most Fish by Number","runmodule.php?module=oceanquest&op=docks&op2=numberfish");
	addnav("Docks");
	addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
}
function oceanquest_noticenav(){
	$allprefs=unserialize(get_module_pref('allprefs'));
	addnav("Notices");
	if ($allprefs["iou1"]==""||$allprefs["iou1"]==0) addnav("IOU - Francis","runmodule.php?module=oceanquest&op=docks&op2=iou&op3=francis");
	if ($allprefs["piece3"]==""||$allprefs["piece3"]==0) addnav("IOU - Trandor","runmodule.php?module=oceanquest&op=docks&op2=iou&op3=trandor");
	if ($allprefs["iou2"]==""||$allprefs["iou2"]==0) addnav("IOU - Yoglin","runmodule.php?module=oceanquest&op=docks&op2=iou&op3=yoglin");
	if ($allprefs["iou3"]==""||$allprefs["iou3"]==0) addnav("IOU - Bondo","runmodule.php?module=oceanquest&op=docks&op2=iou&op3=bondo");
	addnav("For Sale - Used Fish","runmodule.php?module=oceanquest&op=docks&op2=forsale&op3=usedfish");
	addnav("For Sale - New Fish","runmodule.php?module=oceanquest&op=docks&op2=forsale&op3=newfish");
	addnav("For Sale - Stick with String","runmodule.php?module=oceanquest&op=docks&op2=forsale&op3=stickstring");
	addnav("Wanted - Coconuts","runmodule.php?module=oceanquest&op=docks&op2=wanted&op3=coconut");
	addnav("Wanted - Used Fish","runmodule.php?module=oceanquest&op=docks&op2=wanted&op3=usedfish");
	addnav("Job Available #1","runmodule.php?module=oceanquest&op=docks&op2=jobavailable&op3=1");
	addnav("Job Available #2","runmodule.php?module=oceanquest&op=docks&op2=jobavailable&op3=2");
	addnav("Job Available #3","runmodule.php?module=oceanquest&op=docks&op2=jobavailable&op3=3");
	addnav("Job Available #4","runmodule.php?module=oceanquest&op=docks&op2=jobavailable&op3=4");
	addnav("Job Available #5","runmodule.php?module=oceanquest&op=docks&op2=jobavailable&op3=5");
}

function oceanquest_fight($op) {
	$temp=get_module_pref("pqtemp");
	page_header("Fight");
	global $session,$badguy;
	$op2 = httpget('op2');
	if ($op=='xavicon'){
		$name=translate_inline("Xavicon");
		$weapon=translate_inline("`\$Fire Ball Spell`0");
		$allprefs=unserialize(get_module_pref('allprefs'));
		if ($allprefs['xaviconhp']==""||$allprefs['xaviconhp']==0){
			$allprefs['xaviconhp']=$session['user']['maxhitpoints']*1.5 + 3;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$hitpoints=$allprefs['xaviconhp'];
		$badguy = array(
			"creaturename"=>$name,
			"creaturelevel"=>17,
			"creatureweapon"=>$weapon,
            "creatureattack"=>$session['user']['attack']*1.5 + 3,
            "creaturedefence"=>$session['user']['defense']*1.5 + 3,
            "creaturehealth"=>$hitpoints,
			"diddamage"=>0,
			"type"=>"oqendboss",
		);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=='reddragon'){
		$name=translate_inline("Red Dragon");
		$weapon=translate_inline("`\$Fire Breath Weapon`0");
		$allprefs=unserialize(get_module_pref('allprefs'));
		if ($allprefs['dragonhp']==""||$allprefs['dragonhp']==0){
			$allprefs['dragonhp']=$session['user']['maxhitpoints']*1.2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$hitpoints=$allprefs['dragonhp'];
		$badguy = array(
			"creaturename"=>$name,
			"creaturelevel"=>16,
			"creatureweapon"=>$weapon,
            "creatureattack"=>$session['user']['attack']*1.3 + 2,
            "creaturedefence"=>$session['user']['defense']*1.3 + 2,
            "creaturehealth"=>$hitpoints,
			"diddamage"=>0,
			"type"=>"oqreddragon",
		);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=='fishermanfight'){
		$name=translate_inline("A Burly Fisherman");
		$weapon=translate_inline("Flying Fists");
		$badguy = array(
			"creaturename"=>$name,
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>$weapon,
			"creatureattack"=>$session['user']['attack']*.9,
			"creaturedefense"=>$session['user']['defense']*.9,
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.1),
			"diddamage"=>0,
			"type"=>"oqburly",
		);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=='waterguardian'){
		$name=translate_inline("The `!Water `1Guardian");
		$weapon=translate_inline("Waves of Pain");
		$badguy = array(
			"creaturename"=>$name,
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>$weapon,
			"creatureattack"=>$session['user']['attack'],
			"creaturedefense"=>$session['user']['defense'],
			"creaturehealth"=>$session['user']['maxhitpoints']+25,
			"diddamage"=>0,
			"type"=>"oqguardian",
		);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=='fishcrew'){
		$name=translate_inline("a Beefy Fisherman");
		$weapon=translate_inline("Beefy Fists");
		$badguy = array(
			"creaturename"=>$name,
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>$weapon,
			"creatureattack"=>$session['user']['defense'],
			"creaturedefense"=>$session['user']['attack'],
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.1),
			"diddamage"=>0,
			"type"=>"oqbeefy",
		);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=='fishshark'){
		$name=translate_inline("a shark that you're reeling in");
		$weapon=translate_inline("strength against you");
		$badguy = array(
			"creaturename"=>$name,
			"creaturelevel"=>1,
			"creatureweapon"=>$weapon,
			"creatureattack"=>1,
			"creaturedefense"=>1,
			"creaturehealth"=>2000,
			"diddamage"=>0,
			"type"=>"oqfishshark",
		);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=='pilinoriasoldier'){
		$name=translate_inline("Pilinoria Soldier");
		$weapon=translate_inline("a Halberd");
		$badguy = array(
			"creaturename"=>$name,
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>$weapon,
			"creatureattack"=>$session['user']['attack']-1,
			"creaturedefense"=>$session['user']['defense']-1,
			"creaturehealth"=>round($session['user']['maxhitpoints']*.8),
			"diddamage"=>0,
			"type"=>"oqsoldier",
		);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="bear"){
		$name=translate_inline("`qG`^reat `qB`^ig `qB`^ear");
		$weapon=translate_inline("`@its`% Claws");
		$badguy = array(
			"creaturename"=>$name,
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>$weapon,
			"creatureattack"=>round($session['user']['attack']*.85),
			"creaturedefense"=>round($session['user']['defense']*1.3),
			"creaturehealth"=>round($session['user']['maxhitpoints']*0.98),
			"diddamage"=>0,
			"type"=>"oqbear",
		);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="fight"){
		global $badguy;
		$battle=true;
		$fight=true;
		if ($battle){
			require_once("battle.php");
			if ($victory){
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($badguy['type']=='oqendboss'){
					output("`n`n`&`bYou've defeated Xavicon! Huzzah!!");
					addnews("`% %s`0 has destroyed the `\$Evil Sorceror`0 in a foreign land.",$session['user']['name']);
					addnav("Continue","runmodule.php?module=oceanquest&op=pilinoria&op2=endgame1");
				}elseif ($badguy['type']=='oqreddragon'){
					$expmultiply = e_rand(25,40);
					$expbonus=$session['user']['dragonkills']*6;
					$expgain =($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`#You defeat the `\$Red Dragon`#!!`n");
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					output("`nYou also find `^450 gold pieces`# by the corpse.`n");
					$session['user']['gold']+=450;
					$allprefs['reddragon']=1;
					addnews("`% %s`0 has defeated a  `\$Red Dragon`0 in a foreign land.",$session['user']['name']);
					output("`n`#It looks like the way into the Sorcerer's Fortress is open... are you ready?");
					addnav("Retreat North","runmodule.php?module=oceanquest&op=pilinoria&op2=village");
					addnav("Enter the Fortress","runmodule.php?module=oceanquest&op=pilinoria&op2=fortress");
				}elseif ($badguy['type']=='oqsoldier'){
					$allprefs['pass']=2;
					output("`n`#You knock the Pilinoria Guard unconscious and grab his badge.`n");
					output("`nHopefully you won't have any more trouble!");
					addnav("Continue","runmodule.php?module=oceanquest&op=pilinoria&op2=servant");
				}elseif($badguy['type']=="oqguardian"){
					$expmultiply = e_rand(12,18);
					$expbonus=$session['user']['dragonkills']*4;
					$expgain =($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`#You defeat the `!Water `1Guardian`#!!`n");
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					addnav("Continue","runmodule.php?module=oceanquest&op=island&op2=getscroll");
				}elseif($badguy['type']=="oqburly"){
					$expmultiply = e_rand(10,20);
					$expbonus=$session['user']['dragonkills']*2;
					$expgain =($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You decide leaving him beaten to a pulp is good enough for you.`n");
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					addnav("Dock Fishing");
					$fishingtoday=$allprefs['fishingtoday'];
					if ($fishingtoday<5){
						$fishingleft=5-$fishingtoday;
						addnav("More Fishing","runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
						output("`n`n`c`@Fishing Turns Left: `^%s`c",$fishingleft);
					}else{
						output("`n`n`c`\$No Fishing Turns Left`7`c");
						$allprefs['bait']=0;
					}
					addnav("Read Rules","runmodule.php?module=oceanquest&op=docks&op2=fishingrules");
					addnav("Chat with Fishermen","runmodule.php?module=oceanquest&op=docks&op2=fishingchat");
					addnav("Docks");
					addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
				}elseif($badguy['type']=="oqbear"){
					$expbonus=$session['user']['dragonkills']*4;
					$expgain =($session['user']['level']*39+$expbonus);
					$session['user']['experience']+=$expgain;
					if (is_module_active("bearhof")) increment_module_pref("bearkills",1,"bearhof");
					$allprefs['bear']=0;
					output("`n`%Silly `qB`^ear`%! When will you ever learn?`n");
					output("`n`@`bYou've gained `#%s experience`@.`b`n`n",$expgain);
					addnav("Continue","runmodule.php?module=oceanquest&op=island&op2=cave");
				}elseif($badguy['type']=="oqbeefy"){
					$expbonus=$session['user']['dragonkills']*3;
					$expgain =($session['user']['level']*20+$expbonus);
					$session['user']['experience']+=$expgain;
					$session['user']['gold']+=200;
					output("`n`7You stand over the Beefy Fisherman and beat your chest.  You've won!`n");
					output("`n`@`bYou've gained `#%s experience`@ and `^200 Gold`@.`b`n`n",$expgain);
					debuglog("gained 200 gold and $expgain experience fighting a beefy fisherman in the Oceanquest.");
					addnav("Deep Sea Fishing");
					$fishingtoday=$allprefs['fishingtoday'];
					if ($fishingtoday<5){
						$fishingleft=5-$fishingtoday;
						$op3=$allprefs['quality'];
						if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0){
							addnav("More Fishing","runmodule.php?module=oceanquest&op=fishingexpedition&op2=fish&op3=$op3");
							addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpedition&op2=gauge&op3=$op3");
						}else{
							addnav("More Fishing","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=fish&op3=$op3");
							addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=gauge&op3=$op3");
						}
						output("`n`n`c`@Fishing Turns Left: `^%s`c",$fishingleft);
					}else{
						output("`n`n`c`\$No Fishing Turns Left`7`c");
						$allprefs['bait']=0;
					}
					if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Move the Ship","runmodule.php?module=oceanquest&op=fishingexpedition&loc=".get_module_pref("pqtemp"));
					else addnav("Move the Ship","runmodule.php?module=oceanquest&op=fishingexpeditiona");
				}elseif($badguy['type']=="oqfishshark"){
					output("`n`7You haul in the shark to the applause of everyone on board!  You've won! The captain shakes your hand and you feel proud. You `@gain a turn`7!");
					$session['user']['turns']++;
					addnav("Deep Sea Fishing");
					$weight=e_rand(700,1000);
					$pounds=floor($weight/16);
					$ounces=$weight-($pounds*16);
					output("`n`nYou check the weight:`n`n`&");
					if ($pounds>0) output("%s %s%s`7",$pounds,translate_inline($pounds>1?"Pounds":"Pound"),translate_inline($ounces>0?",":""));
					if ($ounces>0) output("`&%s %s`7",$ounces,translate_inline($ounces>1?"Ounces":"Ounce"));
					$allprefs['numberfish']++;
					$allprefs['fishweight']+=$weight;
					if ($weight>$allprefs['bigfish']){
						output("`n`nThis is the biggest fish you've ever caught!");
						$allprefs['bigfish']=$weight;
					}
					$fishingtoday=$allprefs['fishingtoday'];
					if ($fishingtoday<5){
						$fishingleft=5-$fishingtoday;
						$op3=$allprefs['quality'];
						if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0){
							addnav("More Fishing","runmodule.php?module=oceanquest&op=fishingexpedition&op2=fish&op3=$op3");
							addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpedition&op2=gauge&op3=$op3");
						}else{
							addnav("More Fishing","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=fish&op3=$op3");
							addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=gauge&op3=$op3");
						}
						output("`n`n`c`@Fishing Turns Left: `^%s`c",$fishingleft);
					}else{
						output("`n`n`c`\$No Fishing Turns Left`7`c");
						$allprefs['bait']=0;
					}
					if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Move the Ship","runmodule.php?module=oceanquest&op=fishingexpedition&loc=".get_module_pref("pqtemp"));
					else addnav("Move the Ship","runmodule.php?module=oceanquest&op=fishingexpeditiona");
				}
				set_module_pref('allprefs',serialize($allprefs));
				$badguy=array();
				$session['user']['badguy']="";
			}elseif ($defeat){
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($badguy['type']=='oqendboss'){
					$allprefs['xaviconhp']=$badguy['creaturehealth'];
					$exploss = round($session['user']['experience']*.1);
					$session['user']['experience']-=$exploss;
					$session['user']['gold']=0;
					$session['user']['hitpoints']=0;
					addnav("Shades","shades.php");
					addnews("`% %s`0 has been slain by an `\$Evil Sorceror`0 in a foreign land.",$session['user']['name']);
					output("`n`nYou die slowly under the devastating magic of Xavicon.  As your life energy fades, you feel confident that you've wounded the sorceror. Luckily, the next time you fight him, he won't be as strong.`n`n");
					output("`b`^All gold on hand has been lost!`b`n");
					output("`b`%You lose `#%s experience`4.`b`n`n",$exploss);
					output("`b`\$Xavicon uses your blood to strengthen his fortress and also to give the master bedroom a nice tint of iron-red on the crown moulding.");
				}elseif ($badguy['type']=='oqreddragon'){
					$allprefs['dragonhp']=$badguy['creaturehealth'];
					$exploss = round($session['user']['experience']*.1);
					$session['user']['experience']-=$exploss;
					$session['user']['gold']=0;
					$session['user']['hitpoints']=0;
					$session['user']['alive']=false;
					addnav("Shades","shades.php");
					addnews("`% %s`0 has been slain by a `\$Red Dragon`0 in a foreign land.",$session['user']['name']);
					output("`n`nYou die slowly in the jaws of the dragon.  As your life energy fades, you feel confident that you've wounded the creature. Luckily, the next time you fight it, it won't be as strong.`n`n");
					output("`b`^All gold on hand has been lost!`b`n");
					output("`b`%You lose `#%s experience`4.`b`n`n",$exploss);
				}elseif ($badguy['type']=='oqsoldier'){
					$session['user']['hitpoints']=1;
					$session['user']['gold']=0;
					$session['user']['experience']*=.9;
					output("`n`#The guard knocks you almost unconsious.  Fearing having to do a lot of paperwork, he steals all your gold and dumps your body on the pier.");
					addnav("Continue","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
				}elseif($badguy['type']=="oqguardian"){
					$expmultiply = e_rand(12,18);
					$expbonus=$session['user']['dragonkills']*4;
					$expgain =($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']-=$expgain;
					output("`n`#You are defeated by the `!Water `1Guardian`#!!`n");
					output("`n`#You have lost `7%s `#experience.`n",$expgain);
					output("`nThe `1Guardian`# leaves you for dead but you're not.  You have 1 hitpoint to crawl away with.");
					$session['user']['hitpoints']=1;
					addnav("Continue","runmodule.php?module=oceanquest&op=island&op2=crawlaway");
				}elseif($badguy['type']=="oqburly"){
					$expbonus=$session['user']['dragonkills']*5;
					$exploss =($session['user']['level']*30+$expbonus);
					if ($exploss>$session['user']['experience']) $exploss=$session['user']['experience'];
					$session['user']['experience']-=$exploss;
					output("`n`7The Burly Fisherman stands over you and beats his chest, accepts the applause from the other anglers, and leaves you with one hitpoint left.`n");
					output("`n`@`bYou've lost `#%s experience`@.`b`n`n",$exploss);
					$session['user']['hitpoints']=1;
					addnav("Fishing");
					$fishingtoday=$allprefs['fishingtoday'];
					if ($fishingtoday<5){
						$fishingleft=5-$fishingtoday;
						$op3=$allprefs['quality'];
						addnav("More Fishing","runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
						output("`n`n`c`@Fishing Turns Left: `^%s`c",$fishingleft);
					}else{
						output("`n`n`c`\$No Fishing Turns Left`7`c");
						$allprefs['bait']=0;
					}
					addnav("Read Rules","runmodule.php?module=oceanquest&op=docks&op2=fishingrules");
					addnav("Chat with Fishermen","runmodule.php?module=oceanquest&op=docks&op2=fishingchat");
					addnav("Docks");
					addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
				}elseif($badguy['type']=="oqbeefy"){
					$expbonus=$session['user']['dragonkills']*6;
					$exploss =($session['user']['level']*40+$expbonus);
					if ($exploss>$session['user']['experience']) $exploss=$session['user']['experience'];
					$session['user']['experience']-=$exploss;
					output("`n`7The Beefy Fisherman stands over you and beats his chest.  You've lost!`n");
					output("`n`@`bYou've lost `#%s experience`@.`b`n`n",$exploss);
					output("You spend your next turn swobbing the deck. The captain gives you a potion that restores half of your hitpoints.");
					$session['user']['hitpoints']=round($session['user']['maxhitpoints']/2);
					$session['user']['turns']--;
					addnav("Deep Sea Fishing");
					$fishingtoday=$allprefs['fishingtoday'];
					if ($fishingtoday<5){
						$fishingleft=5-$fishingtoday;
						$op3=$allprefs['quality'];
						if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0){
							addnav("More Fishing","runmodule.php?module=oceanquest&op=fishingexpedition&op2=fish&op3=$op3");
							addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpedition&op2=gauge&op3=$op3");
						}else{
							addnav("More Fishing","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=fish&op3=$op3");
							addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=gauge&op3=$op3");
						}
						output("`n`n`c`@Fishing Turns Left: `^%s`c",$fishingleft);
					}else{
						output("`n`n`c`\$No Fishing Turns Left`7`c");
						$allprefs['bait']=0;
					}
					if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Move the Ship","runmodule.php?module=oceanquest&op=fishingexpedition&loc=".get_module_pref("pqtemp"));
					else addnav("Move the Ship","runmodule.php?module=oceanquest&op=fishingexpeditiona");
				}elseif($badguy['type']=="oqfishshark"){
					output("`n`7You run out of energy and the shark gets away.");
					if ($session['user']['turns']>0){
						output("You lose a turn recovering your strength.");
						$session['user']['turns']--;
					}
					output("The captain gives you a potion that restores half of your hitpoints.");
					$session['user']['hitpoints']=round($session['user']['maxhitpoints']/2);
					addnav("Deep Sea Fishing");
					$fishingtoday=$allprefs['fishingtoday'];
					if ($fishingtoday<5){
						$fishingleft=5-$fishingtoday;
						$op3=$allprefs['quality'];
						if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0){
							addnav("More Fishing","runmodule.php?module=oceanquest&op=fishingexpedition&op2=fish&op3=$op3");
							addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpedition&op2=gauge&op3=$op3");
						}else{
							addnav("More Fishing","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=fish&op3=$op3");
							addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=gauge&op3=$op3");
						}
						output("`n`n`c`@Fishing Turns Left: `^%s`c",$fishingleft);
					}else{
						output("`n`n`c`\$No Fishing Turns Left`7`c");
						$allprefs['bait']=0;
					}
					if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Move the Ship","runmodule.php?module=oceanquest&op=fishingexpedition&loc=".get_module_pref("pqtemp"));
					else addnav("Move the Ship","runmodule.php?module=oceanquest&op=fishingexpeditiona");			
				}elseif($badguy['type']=="oqbear"){
					$exploss = round($session['user']['experience']*.1);
					$session['user']['experience']-=$exploss;
					$session['user']['gold']=0;
					$session['user']['hitpoints']=0;
					$session['user']['alive']=false;
					addnews("`% %s`0 has been slain by a `qG`^reat `qB`^ig `qB`^ear`0 after rooting around in its den.",$session['user']['name']);
					output("`n`n`b`%You can't `Q'bear'`% to think how you got killed...`b`n");
					output("`b`%You shouldn't have `%'picked'`% on him!`b`n");
					output("`b`^All gold on hand has been lost!`b`n");
					output("`b`%You lose `#%s experience`4.`b`n`n",$exploss);
					$allprefs['island']=0;
				}
				set_module_pref('allprefs',serialize($allprefs));
				$badguy=array();
				$session['user']['badguy']="";
			}else{
				require_once("lib/fightnav.php");
				fightnav(true,false,"runmodule.php?module=oceanquest");
			}
		}
	}
	page_footer();
}
?>