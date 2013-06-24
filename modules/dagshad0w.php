<?php

require_once("lib/http.php");
require_once("lib/villagenav.php");

function dagshad0w_getmoduleinfo(){
	$info = array(
		"name"=>"Shad0w Quest",
		"version"=>"1.0",
		"author"=>"Peter Corcoran",
		"category"=>"Quest",
		"download"=>"http://dragonprime.net/users/R4000/dagshad0w.txt",
		"settings"=>array(
			"shad0w Quest Settings,title",
			// reward types.
			"rewardgold"=>"What is the gold reward for the Shad0w Quest?,int|10000",
			"rewardgems"=>"What is the gem reward for the Shad0w Quest?,int|20",
			"experience"=>"What is the quest experience multiplier for the Shad0w Quest?,floatrange,1.01,2.01,0.01|2.01",
			"minlevel"=>"What is the minimum level for this quest?,range,1,15|1",
			// in the future min DKs might get put here too.
			"maxlevel"=>"What is the maximum level for this quest?,range,1,15|15",
		),
		"prefs"=>array(
			"status"=>"How far has the player gotten in the Shad0w Quest?,int|0",
			// 0 is not taken, 1 is in progress, 2 is completed, 3 is failed,
			// 4 is failed through choice/ignoring it and 5 is reward pending.
			// Above 5 can be used for stages of completion.
        ),
        "requires"=>array(
	       "dagquests"=>"1.1|By Sneakabout",// central module to hook into.
		),
	);
	return $info;
}

function dagshad0w_install(){
	module_addhook("village");
	module_addhook("dragonkilltext");
	module_addhook("newday");
	module_addhook("dagquests");
	return true;
}

function dagshad0w_uninstall(){
	return true;
}

function dagshad0w_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
	case "village":
		if ($session['user']['location']==
				getsetting("villagename", LOCATION_FIELDS)) {
			tlschema($args['schemas']['gatenav']);
			addnav($args['gatenav']);
			tlschema();
			// The turns get checked later, so that people don't ask where
			// the link is :(
			if (get_module_pref("status")==1) {
				addnav("Search the Sewers (1 turn)",
						"runmodule.php?module=dagshad0w&op=search");
			}
		}
		break;
	case "dragonkilltext":
		// DK reset.
		set_module_pref("status",0);
		break;
	case "newday":
		if (get_module_pref("status")==1 &&
				$session['user']['level']>get_module_setting("maxlevel")) {
			// if they get beyond the level range.
			set_module_pref("status",4);
			output("`n`6You hear that another adventurer defeated the Shad0w plaguing the village.`0`n");
			require_once("modules/dagquests.php");
			dagquests_alterrep(-1);
		}
		break;
	case "dagquests":
		if (get_module_pref("status")==5) {
			// giving the reward if quest completed. No chance of both
			// triggering.
			$goldgain=get_module_setting("rewardgold");
			$gemgain=get_module_setting("rewardgems");
			$session['user']['gold']+=$goldgain;
			$session['user']['gems']+=$gemgain;
			debuglog("got a reward of $goldgain gold and $gemgain gems for defeating the Shad0w plaguing the villages.");
			if ($goldgain && $gemgain) {
				output("`3You hand Dag the Shad0ws' Orb of Darkness, and Dag pays you the bounty of `^%s gold`3 and a pouch of `%%s %s`3!",$goldgain,$gemgain,translate_inline(($gemgain==1)?"gem":"gems"));
			} elseif ($gemgain) {
				output("`3You hand Dag the Shad0ws' Orb of Darkness, and Dag pays you the bounty of a pouch of `%%s %s`3!",$gemgain,translate_inline(($gemgain==1)?"gem":"gems"));
			} elseif ($goldgain) {
				output("`3You hand Dag the Shad0ws' Orb of Darkness, and Dag pays you the bounty of `^%s gold`3!",$goldgain);
			} else {
				output("`3You hand Dag the Shad0ws' Orb of Darkness, Dag thanks you and says, \"I have nothing to give you today!\"");
			}
			set_module_pref("status",2);
			// complete after reward is given.
		}
		// Another quest is set!
		if ($args['questoffer']) break;

		// checking requirements and setting status.
		if (get_module_setting("minlevel")<=$session['user']['level'] &&
				$session['user']['level']<=get_module_setting("maxlevel") &&
				!get_module_pref("status")) {
			output("He seems very busy, but when you ask him about work, he looks at you carefully and motions you closer.`n`n");
			output("\"Aye, there be something ye might be helpin' me wit'.... there be rumours of a dark being that be plaguin' the villages. It be operatin' from the sewers below us. It seems t' be reasonably smart, and the normal guards ain't bein' the sort to take the thing on. Ye look like ye can handle yerself, and there be a bounty from one o' the relatives if'n yer interested.  Do ye be takin' the job?\"`n`n");
			output("It almost crosses your mind to wonder why Dag would be offering this to you, but the sewers arnt that scary to you, You being a warrior and all...");
			output("It shouldn't be any problem to search them.");
			addnav("Take the Job","runmodule.php?module=dagshad0w&op=take");
			addnav("Refuse","runmodule.php?module=dagshad0w&op=nottake");
			// Necessary! If this wasn't there then you would get presented
			// with a quest you might not want to do and miss other ones.
			$args['questoffer']=1;
		}
		break;
	}
	return $args;
}

function dagshad0w_runevent($type) {
}

function dagshad0w_run(){
	global $session;
	$op = httpget('op');
	
	switch($op){
	case "take":
		$iname = getsetting("innname", LOCATION_INN);
		page_header($iname);
		rawoutput("<span style='color: #9900FF'>");
		output_notl("`c`b");
		output($iname);
		output_notl("`b`c");
		output("`3Dag nods, and gives you directions to the rough area the Shad0w has been seen in, as well as a description of a dark being, tough and strong.");
		output("`3Dag also say, \"Ye might be wantin' to prepare ye self first.\"");		
		output("You leave the table, ready to seek out the beast.");
		// In progress.
		set_module_pref("status",1);
		addnav("I?Return to the Inn","inn.php");
		break;
	case "nottake":
		$iname = getsetting("innname", LOCATION_INN);
		page_header($iname);
		rawoutput("<span style='color: #9900FF'>");
		output_notl("`c`b");
		output($iname);
		output_notl("`b`c");
		output("`3Dag nods, spits to one side and turns away, disgusted with your cowardice.");
		// Failed through choice
		set_module_pref("status",4);
		addnav("I?Return to the Inn","inn.php");
		break;
	case "search":
		page_header("The Sewers");
		if (!$session['user']['turns']) {
			// coping with having the link appear at all times.
			output("`2You dont think it would be safe to return to the sewers today.");
			output("Maybe tomorrow.`n`n");
			villagenav();
			page_footer();
		}
		output("`2You climb down to the dark, damp sewers, and start to check out the complex sewers individually for traces of the beast.`n`n");
		$session['user']['turns']--;
		$rand=e_rand(1,10);
		switch($rand){// various things they can find.
		case 1:
		case 2:
			output("You search through the sewers for a while, finding nothing but bleached bones and dust.");
			output("Dispirited after a few hours, you trudge back to the town and look for something else to do.");
			villagenav();
			break;
		case 3:
		case 4:
			output("You wander through the sewers for a while, eventually hearing some cries for help from a distance.");
			output("You rush over, and find and injured traveller who has fallen down a stray drain hatch opened by some ugly kids.");
			output("You can see through a hole in his chest, he has obviously been attacked by 'something' - you do your best, but he dies after choking something about an attack from a powerful monster.");
			output("You hurry back to town, watching your back for whatever attacked the traveller.");
			villagenav();
			break;
		case 5:
			output("You wander through the sewers for a while, finding a large gate blocking your way.");
			output("While looking in vain for a way to open the gate, you notice a gem on the other side of the gate.");
			output("You reach though the gate to grab the gem, but it appears to be embedded in the mud, you pry it our as a souvenir before returning to town.");
			debuglog("gained a gem from an ancient sewer gate");
			$session['user']['gems']++;
			villagenav();
			break;
		case 6:
			output("You wander through the sewers for a while before hearing a roar from the pipe to your left.");
			output("Shitting yourself you look left, and notice a HUGE sewer rat launching itself at you.");
			output("You have nowhere to run to, so you ready your %s`2 to fight!",$session['user']['weapon']);
			addnav("Fight the Rat",
					"runmodule.php?module=dagshad0w&fight=ratfight");
			break;
		case 7:// bingo!
		case 8:
		case 9:
		case 10:
			output("You wander through the sewers for a while before finding a trail of gem from a dropped backpack.");
			output("You rush following the trail through the sewers to a sandy outcrop where you can see the Shad0w, gorging on the body of the dead traveller in front of a small drainage pipe.");
			output("The beast sniffs the air, and you know you have been detected - you draw your %s`2 and charge down as the beast prepares with its club, snarling all the while.",$session['user']['weapon']);
			addnav("Fight the Shad0w","runmodule.php?module=dagshad0w&fight=shad0wfight");
			break;
		}
		break;
	}
	// handle fights separately - you can't use op because the fight
	// script uses that.
	$fight=httpget("fight");
	switch($fight){
	case "ratfight":
		// Set stats, but only at the start of the fight.
		$badguy = array(
			"creaturename"=>"Rat",
			"creaturelevel"=>$session['user']['level']-1,
			"creatureweapon"=>"Large teeth",
			"creatureattack"=>$session['user']['attack'],
			"creaturedefense"=>round($session['user']['defense']*0.8, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*0.9, 0), 
			"diddamage"=>0,
			"type"=>"quest"
		);
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		// Drop through
	case "ratfighting":
		page_header("The Sewers");
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			// not the main quest, put them back in the village.
			output("`2The rat collapses on the ground, bleeding from its wounds.");
			output("You quickly flee the scene, hoping that there are not more of them around.`n`n");
			$expgain=round($session['user']['experience']*(e_rand(1,2)*0.01));
			$session['user']['experience']+=$expgain;
			output("`&You gain %s experience from this fight!",$expgain);
			output("`2You return to town, shaken by your experience.");
			villagenav();
		} elseif ($defeat) {
			// not the main quest, they get to keep trying.
			output("`2Your vision blacks out as the rat chews the throat out of your already badly injured body.`n`n");
			output("`%You have died!");
			output("You lose 10% of your experience, and your gold is stolen by scavengers!");
			output("Your soul drifts to the shades.");
			debuglog("was killed by a rat and lost ".
					$session['user']['gold']." gold.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive'] = false;
			addnews("%s was slain by a Rat in the Sewers!",
					$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,true,
				"runmodule.php?module=dagshad0w&fight=ratfighting");
		}
		break;
	case "shad0wfight":
		// main creature stats, make sure it isn't too easy.
		$badguy = array(
			"creaturename"=>"Shad0w",
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>"Orb of Darkness",
			"creatureattack"=>round($session['user']['attack']*1.15, 0),
			"creaturedefense"=>round($session['user']['defense']*0.9, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.2, 0), 
			"diddamage"=>0,
			"type"=>"quest"
		);
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		// drop through
	case "shad0wfighting":
		page_header("The Sewers");
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			// they've won the quest..... but the reward isn't here!
			// Set the reward flag!
			output("`2The Shad0w collapses to the ground with a thud, sending up a cloud of dust!");
			output("You have avenged the deaths of many travellers!`n`n");
			$expgain=round($session['user']['experience']*(get_module_setting("experience")-1), 0);
			$session['user']['experience']+=$expgain;
			output("`&You gain %s experience from this fight!`n`n",$expgain);
			output("`2You grab the beasts' orb, and stash the gruesome thing in your backpack.");
			// Reward flag
			set_module_pref("status",5);
			addnews("%s defeated a Shad0w in the Sewers! The deaths of many travellers have been avenged!",$session['user']['name']);
			villagenav();
		} elseif ($defeat) {
			// Failed against the quest creature... 
			output("`2Your vision blacks out as the Shad0w's darkness clams around you.");
			output("You have failed your task to avenge the travellers!`n`n");
			output("`%You have died!`n");
			output("You lose 10% of your experience, and your gold is stolen by the Shad0w!`n");
			output("Your soul drifts to the shades.");
			debuglog("was killed by a Shad0w in the Sewers and lost ".
					$session['user']['gold']." gold.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive'] = false;
			// They fail it!
			set_module_pref("status",3);
			addnews("%s was slain by a Shad0w in the Sewers!",
					$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,true,
				"runmodule.php?module=dagshad0w&fight=shad0wfighting");
		}
		break;
	}
	page_footer();
}
?>
