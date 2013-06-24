<?php
/**************
Name: Alien Encounter
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.0
Release Date: 01-06-2006
About: Player has an encounter with aliens in the forest. 'Nuff said
translation compatible.
*****************/
require_once("lib/http.php");
require_once("lib/villagenav.php");

function alienencounter_getmoduleinfo(){
    $info = array(
        "name"=>"Alien Encounter",
        "version"=>"1.0",
        "author"=>"Eth",
        "category"=>"Forest Events",
        "download"=>"http://dragonprime.net/users/Eth/alienencounter.zip",
        "vertxtloc"=>"http://dragonprime.net/users/Eth/",
        "settings"=>array(
            "Alien Encounter - Main Settings,title",             
            "alienchance"=>"Raw Chance of encountering aliens?,range,0,100,5|25",
        ),
        "prefs"=>array(
         	"Alien Encounter - User Settings,title",
         	"alienencounter"=>"Found one yet?,bool|0",            
        )
    );
    return $info;
}
function alienencounter_install(){
	module_addeventhook("forest", "require_once(\"modules/alienencounter.php\"); return alienencounter_test();");
	module_addhook("newday");

	return true;
}
function alienencounter_uninstall(){
	return true;
}
function alienencounter_test(){
	global $session;	
	$chance = get_module_setting("alienchance","alienencounter");
	if (get_module_pref("alienencounter","alienencounter") == 1) return 0; 
	return $chance; 
}
function alienencounter_dohook($hookname,$args){
	global $session;
	switch($hookname){		
		case "newday":
		if (get_module_pref("alienencounter") == 1){
			set_module_pref("alienencounter",0);
		}
		break;
	}
	return $args;
}
function alienencounter_runevent($type) {
	global $session;
	$from = "runmodule.php?module=alienencounter&";	
	if ($type == "forest") $from = "forest.php?";
	//elseif ($type == "village") $from = "village.php?";
	$haspet = get_module_pref("haspet","petshop");
    $session['user']['specialinc'] = "module:alienencounter";	
    $op = httpget('op');    
	switch ($type) {	
	case forest:	
		if ($op=="" || $op=="search") {		
			//if they haven't found one yet
			output("`n`2Your forest travels are suddenly interrupted as a brilliant green beam of light suddenly shoots down from the sky.");
			output(" `2Stunned in disbelief at such a sight, you stand frozen in your tracks as a pair of small green aliens emerge from the beam.`n`n");
			output("`2One of them take a few steps forward, holds up it's right hand in some kind of greeting, and utters something in a language you've never before heard.`n`n");
			output("`3\"Fzzxtlt bzylbrrk txrzt!\" it proclaims.`n`n");
			//$session['user']['specialinc']="";	
			addnav("Um...Hi?", $from . "op=hello");
			addnav("Run Away!", $from . "op=leave");		
		}else if ($op == "hello"){
			output("`3\"Hello there,\" you respond to the little green creature.`n`n");
			set_module_pref("alienencounter",1);
			switch (e_rand(1,10)){
				case 1:
				case 2:
				case 3:
				case 4:
				//they leave
				output("`2The alien blinks, says something to his (or is it her?) companion, then they both turn and disappear back into the beam.`n`n");
				output("`2With a flash, the beam is gone and you're left standing on the trail wondering what in blazes just happened.");
				output(" `2Shrugging, you decide it's best not to think about it too much and you return to your travels.`n`n");
				if (e_rand(1,3)==1){
					output("`3As you leave, you suddenly notice that the strange creature dropped a gem!`n`n");
					$session['user']['gems']++;
				}
				$session['user']['specialinc']="";
				break;
				case 5:
				case 6:
				case 7:
				output("`2Both aliens turn and start chattering amongst themselves for a moment, leaving you confused once again.`n`n");
				output("`2After a time, they both turn to you, mutter some more strange words, and disappear back into the beam of light.");
				output(" `2After the beam disappears, you're left scratching your head. You decide it's best to simply continue on with your travels.`n`n");
				$session['user']['specialinc']="";
				break;
				case 8:
				case 9:
				output("`2The alien's eyes go wide and it fumbles for something at it's waist.");
				output(" `2Before you can say another word, it points a small, strange-looking weapon at you and fires.`n`n");
				output("`2The last thing you remember is hearing a loud \"Zat!\" before the world around you goes black.");
				addnews("`3%s `2was zapped by aliens in the forest today!", $session['user']['name']);
				$session['user']['hitpoints'] = 0;
				$session['user']['alive'] = false;
				$session['user']['specialinc']="";
				addnav("Land of Shades","shades.php");
				break;
				case 10:
				output("`2The alien stares at you blankly for a moment before it's companion points a strange looking contraption at you and presses a button.");
				output(" `2Before you can react, you find yourself paralyzed and being drawn into the beam of light!`n`n");
				output("`2Within moments, you black out.`n`n");
				output("`2Upon awakening, you find yourself strapped down to a cold metal table, with both aliens looking over you and chattering amongst themselves.");
				output(" `2Before blacking out again, you see a strange probe-like device in one of their hands...`n`n");
				addnav("Uh Oh...",$from."op=afterwards");
				break;				
			}
		}else if ($op == "afterwards"){	
			output("`2Hours (maybe even days) later, you find yourself laying naked on the forest floor, exactly where those strange aliens appeared.");
			output(" `2Gathering your wits (and your clothes) about you, you sit up and discover strange welts and bruises covering your entire body, and a dull ache in your posterior.`n`n");
			output("`2You quickly dress yourself and decide it would be prudent to be away from here as quickly as possible, less your new 'friends' return.`n`n");
			$session['user']['turns']-=1;					
			addnews("`3%s `2was kidnapped and experimented on by aliens in the forest today!",$session['user']['name']);
			$session['user']['specialinc']="";
			addnav("Return to Forest","forest.php");
		}else if ($op == "leave"){			
			output("`2Whether it be from fear or disbelief, you turn and flee from the pair of strange creatures.`n`n");
			if (e_rand(1,3)==1 && $session['user']['gems']>0){
				output("`3In your flight from the aliens, you drop a gem!`n`n");
				$session['user']['gems']--;				
			}
			$session['user']['specialinc']="";
			addnav("Return to Forest","forest.php");						
		}
		break;							
	}	
}
function alienencounter_run(){	}
?>