<?php

require_once("lib/http.php");
require_once("lib/villagenav.php");
require_once("lib/titles.php");
require_once("lib/names.php");

function dagdarkwizard_getmoduleinfo(){
	$info = array(
		"name"=>"Dark Wizard's Tower",
		"version"=>"1.0",
		"author"=>"John M, based on Bandits Quest by`%Sneakabout`^",
		"category"=>"Quest",

		"settings"=>array(
			"Dark Wizard's Tower Settings,title",
			"rewardgold"=>"What is the gold reward for the Dark Wizard's Tower Quest?,int|5000",
			"rewardgems"=>"What is the gem reward for the Dark Wizard's Tower?,int|2",
			"experience"=>"What is the quest experience multiplier for the Dark Wizard Quest?,floatrange,1.01,1.3,0.01|1.1",
			"minlevel"=>"What is the minimum level for this quest?,range,1,15|5",
			"maxlevel"=>"What is the maximum level for this quest?,range,1,15|11",
			"minrep"=>"What is the minimum reputation for this quest?,int|2",
			"name"=>"Name of the Dark Wizard,text|Alkaroth",
			"heshe"=>"Dark Wizard's Sex,text|he",
		),
		"prefs"=>array(
			"Dark Wizard's Tower Preferences,title",
            "status"=>"How far into the Dark Wizard's Tower has the player got?,int|0",
        ),
        "requires"=>array(
	       "dagquests"=>"1.1|By Sneakabout",
	       
		),
	);
	return $info;
}

function dagdarkwizard_install(){
	module_addhook("village");
	module_addhook("dragonkilltext");
	module_addhook("newday");
	module_addhook("dagquests");
	return true;
}

function dagdarkwizard_uninstall(){
	return true;
}

function dagdarkwizard_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
	case "village":
		if ($session['user']['location']==
				getsetting("villagename", LOCATION_FIELDS)) {
			tlschema($args['schemas']['gatenav']);
			addnav($args['gatenav']);
			tlschema();
			if (get_module_pref("status")==1) {
				addnav("Travel to the Dark Tower (3 turns)","runmodule.php?module=dagdarkwizard&op=search");
			}
		}
		break;
	case "dragonkilltext":
		set_module_pref("queststatus",0);
		set_module_pref("status",0);
		break;
	case "newday":
		if (get_module_pref("status")==1 &&
				$session['user']['level']>get_module_setting("maxlevel")) {
			set_module_pref("status",4);
			output("`n`2You hear that %s has once again moved his tower to a new location, so you abandon the bounty.`0`n");
			require_once("modules/dagquests.php");
			dagquests_alterrep(-1);
		}
		break;
	case "dagquests":
		if (get_module_pref("status")==5) {
			$goldgain=get_module_setting("rewardgold");
			$gemgain=get_module_setting("rewardgems");
			$session['user']['gold']+=$goldgain;
			$session['user']['gems']+=$gemgain;
			debuglog("gained $goldgain gold and $gemgain gems for killing evil wizards.");
			if ($goldgain && $gemgain) {
				output("`n`nYou hand Dag the assorted wands, and he grins before producing `^%s gold`3 and `%%s %s`3!",$goldgain,$gemgain,translate_inline(($gemgain==1)?"gem":"gems"));
			} elseif ($gemgain) {
				output("`n`nYou hand Dag the assorted wands, and he grins before producing `%%s %s`3!",$gemgain,translate_inline(($gemgain==1)?"gem":"gems"));
			} elseif ($goldgain) {
				output("`n`nYou hand Dag the assorted wands, and he grins before producing `^%s gold`3!",$goldgain);
			} else {
				output("`n`nYou hand Dag the assorted wands, and he grimaces before shrugging, and saying that they're not giving out rewards for those anymore.");
			}
			addnews("`&%s`^ has defeated the Dark Wizard %s and destroyed the dark Tower! A day of celebration is declared throughout the land!`0",$session['user']['name'],translate_inline (get_module_setting("name")));
			set_module_pref("status",2);
			require_once("modules/dagquests.php");
			dagquests_alterrep(3);
			$args['questoffer']=1;
		}
		if ($args['questoffer']) break;
		if (get_module_setting("minlevel")<=$session['user']['level'] &&
				$session['user']['level']<=get_module_setting("maxlevel") &&
				get_module_pref("dkrep","dagquests") >=
					get_module_setting("minrep") &&
				!get_module_pref("status")) {
			output("`7He seems very busy, but when you ask him about work, he nods and leans in closer.`n`n");
			output("`&\"Aye, if ye got the heart for it, I got a very special job for ye.");
                        output("An adventurer, a young'n, stumbled across the path to the Dark Wizard %s's Tower.",translate_inline (get_module_setting("name")));
                        output(" Thankfully, the lad had enough of a brain to get his arse out of there and bring the information to me.");
                        output("`n`nI don' need to tell ye how slippery that %s is, if I sends more than one person, %s'll know what's up and move the whole durned tower to a new hiding place.",translate_inline (get_module_setting("name")),translate_inline(get_module_setting("heshe")));
                        output("`n`nI can't tell ye what's waitin' there, but if ye can bring back the wands of any wizards ye slay, they'll be a fine bounty for ye labors.");
                        output("Do ye want t' be givin' it a shot?\"");
			output("`n`n`7You consider the offer, momentarily flattered that Dag considers you worthy of this great deed, but apprehensive as well.");
                        output("Everyone has heard of %s, and the Dark Tower, if even some of the rumors are true, there are fates far worse then death in store for anyone trapped in that place.",translate_inline (get_module_setting("name")));
                        output("Still, defeating %s would make a name for you as one of the greatest heroes in the land...",translate_inline (get_module_setting("name")));
			addnav("Take the Job","runmodule.php?module=dagdarkwizard&op=take");
			addnav("Refuse","runmodule.php?module=dagdarkwizard&op=nottake");
			$args['questoffer']=1;
		}
		break;
	}
	return $args;
}

function dagdarkwizard_runevent($type) {
}

function dagdarkwizard_run(){
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
		output("`3Dag points to a spot on the map,`& \"Here be th' spot where the Tower was seen.");
                output("It might be spelled, so don' be givin' up if ye can't find it right away.");
                output(" It'll be a long n' difficult trip, so make sure yer prepared.\"`3");
		set_module_pref("status",1);
		villagenav();
		break;
	case "nottake":
		$iname = getsetting("innname", LOCATION_INN);
		page_header($iname);
		rawoutput("<span style='color: #9900FF'>");
		output_notl("`c`b");
		output($iname);
		output_notl("`b`c");
		output("`3Dag nods his head slowly.`&\"Yer not the first to refuse me, but I hoped ye had more guts 'n that.\" `3He turns away and goes back to work.");
		set_module_pref("status",4);
		villagenav();
		break;
	case "search":
		page_header("Outside Town");
		if ($session['user']['turns']<3) {
			output("`2You get a short way outside of town but realize you feel far too tired to hike to the Dark Wizard's tower today.");
			output("Maybe tomorrow you'll be up to it.`n`n");
			villagenav();
			break;
		}
		output("`2Once outside the town limits, you hike out to the place `3Dag`2 indicated and halt at the edge of the area.");
		output("`n`nYou're in a rather narrow valley between two mountains which is enshrouded in fog.");
                output("There's a river running through the center of it, and steep cliffs which rise all around you.");
                output("You realize that even without magic, it would be easy for someone to hide out here.");
		$session['user']['turns'] -= 3;
		output("`n`nReadying your`! %s`2, you follow what looks like a goat path for about a mile, until you come around a bend in the canyon, and see a large stone tower through the fog ahead.",$session['user']['weapon']);
                output("Excited, you pick up the pace a little, ever alert for danger.`n`n");

		if (e_rand(0,3)) {
                        output("The entrance looks unguarded, and you slip inside the Tower easily, a little too easily...");
			output("`n`nFrom the ground before you, a Wraith rises silently to block your path!");
			output("It's looking for a fresh soul to feed on, and you're it!");
			addnav("Fight the Wraith",
					"runmodule.php?module=dagdarkwizard&fight=wraithfight");
		} else {
			output("Though you see the remains of several poor souls who have ventured here before you, you manage to reach the tower without incident.");
			output("`n`nThere's a zombie guarding the entrance to the tower, but you distract by throwing a rock in the other direction.");
			output("`n`nAs the zombie lurches off after the sound, you quietly slip inside.");
                        output("You can hear some strange and anguished sounds as you move about.");
                        output("The main floor reveals nothing but a sparse kitchen, and some storage rooms.");
                        output("You start climbing the stairs, serarching for %s and his followers.",translate_inline (get_module_setting("name")));
			output("`n`nAs you reach the first landing, you realize this tower is much bigger than you expected.");
			output("You figure that the tower must be magically enchanted to look narrower from the outside.");
                        output("You start to search the various rooms, hoping to catch one of the wizards unawares.");
			addnav("Search for an evil Wizard",
					"runmodule.php?module=dagdarkwizard&op=searchwizard");
		}
		break;
	case "searchwizard":
		page_header("Inside the Tower");
		$rand=e_rand(1,5);
		switch($rand){
		case 1:
			output("`2You explore several of the rooms, but find nothing of interest.");
                        output("Suddenly, you see a shadowy form out of the corner of your eye!`n`n");
			output("With ghostly silence, a Wraith has come upon you!");
			output("You will have to fight or risk losing your soul!");
			addnav("Fight the Wraith",
					"runmodule.php?module=dagdarkwizard&fight=wraithfight");
			break;
		case 2:
			output("`2As you make your way through the castle, you hear a distinctive mechanical click beneath your feet.");
			// As long as they have at least 1/2 their hitpoints they survive
			if (round($session['user']['hitpoints']/
						$session['user']['maxhitpoints'])) {
				output("With lightning reflexes you leap into the air and grab on to a sconce!");
                                output("You pull yourself out of harm's way as several crossbow bolts shatter themselves against the stone wall.");
				output("Unfortunately, one hit your leg, as you leapt.`n`n");
				$hploss=round($session['user']['hitpoints']*(e_rand(1,4)*0.1));
				$session['user']['hitpoints']-=$hploss;
				output("Limping away surprisingly fast, you realize that it only grazed you, and didn't do any serious damage.");
				output("You lose %s hitpoints!`n`n",$hploss);
				output("Do you want to keep searching in your weakened condition?");
				addnav("Keep Searching",
						"runmodule.php?module=dagdarkwizard&op=searchwizard");
				addnav("V?Flee back to town","village.php");
			} else {
				output("`6You're thrown against the wall as three crossbow bolts are launched from a hidden spot within the wall.");
                                output("Everything goes black as you realize that one of the bolts has pierced your heart.");
				output("`n`n`%You have died!");
				output("You lose 10% of your experience, and your gold is taken by the Wizards.");
                                output("They also grind up your earthly remains to use in their spellcraft.");
                                output("But at least you still have your soul.");
				output("Your soul drifts to the shades.");
				debuglog("was killed by the booby trap in the Dark Wizard's tower, losing " . $session['user']['gold'] . " gold");
				$session['user']['gold']=0;
				$session['user']['experience']*=0.9;
				$session['user']['alive']=false;
				$session['user']['hitpoints']=0;
				addnews("%s went off on a secret quest and was never heard from again.",
						$session['user']['name']);
				addnav("Return to the News","news.php");
			}
			break;
		case 3: output("`2You enter an old storage room.");
		        output("There are some loosely piled clothes and weapons here, that you realize came from the adventurers who were here before you.");
		        output("Quickly rummaging through their pockets, you find a healing potion!");
		        $session['user']['hitpoints']=$session ['user']['maxhitpoints'];
		        addnav("Keep Searching",
						"runmodule.php?module=dagdarkwizard&op=searchwizard");
				addnav("V?Flee back to town","village.php");
		        break;
		case 4:
		case 5:
			output("`2As you move through the castle, you hear the sounds of movement coming from one of the rooms.");
                        output("`2 You step in quietly, trying to catch your enemy by surprise.");
			$status=get_module_pref("queststatus");
			$status=unserialize($status);
			$status['qthree']++;
			switch($status['qthree']){
			case 1:
				output("`n`nYou see a wizard casting spells over a dead body laid out on a table, sensing your presence, the Wizard looks up from his work.");
                                output("`n`n`&\"Rise and fight for me.\" He says to the body on the table.");
                                output("`n`n`2Jerkily, the corpse on the table gets to it's feet and lurches towards you!");
                                output(" `n`nMeanwhile the Wizard pulls his wand out from his robes, and casts a spell on you!");
				addnav("Fight the Necromancer",
						"runmodule.php?module=dagdarkwizard&fight=wizardfight&wizard=1");
				break;
			case 2:
				output("`n`n`2Here you come upon a young woman who has been `@bound `2and `@gagged.");
                                output("`n`n`2Her eyes widen as she sees you, then she moves her head to the left, indicating that she's not alone.");
                                output("You see the Wizard is studying something, and his back is to both of you.");
                                output("Silently, you untie the girl, and point towards the tower stairs.");
                                output("`n`nThe Wizard turns just in time to see her escape.");
                                output("`n`n`&\"Idiot! You have no idea what important work you've interrupted!");
                                output("Now, you will have to take her place!\"");
                                output("`n`n`2He opens his hand, and you are surrounded by a `7silver mist!");
                                output("`2 Then, he whips out his wand to fend off your attack.");
                                output("`n`n`&\"Now, face the wrath of Melvyn the Great!\"");
                                output("`n`n`2You try not to snicker as you draw your`! %s`2.",$session['user']['weapon']);
				addnav("Fight Melvyn",
						"runmodule.php?module=dagdarkwizard&fight=wizardfight&wizard=2");
				break;
			case 3: output("`n`n`2Unlike the other wizard, this man is much younger, possibly still in his teens.");
			        output("He's busy dissecting a corpse, humming to himself while he removes the vital organs.");
			        output("He looks a bit too happy in his work, and you figure he must be an apprentice.");
                        output("`n`nSeeing you enter the room he sneers, `&\"Oh good, I can always use more organ donors!\"");
                        output("`n`n`2With a wave of his wand, all the lights go out around you!");
			addnav("Fight the Apprentice",
			"runmodule.php?module=dagdarkwizard&fight=wizardfight&wizard=3");
			break;
			case 4:
				output("`n`n`2At first glance into this room, all you see is an elaborately dressed Skeleton, propped up in a chair.");
                                output("However, before you can completely withdraw it gets to it's feet and addresses you.");
                                output("`n`n`&\"Greetings young hero, you have done quite well to get this far.");
                                output("Your soul will serve me well as my new guard.\"");
                                output("`n`n`2The Lich nods it's head, and a doppleganger of yourself appears before you!");
                                output("`n`n`&\"As each blow by your double here, weakens you. You will be drawn into my service.\"");
                                output("`n`n`2It's bony hand pulls out a black wand and begins casting a spell...");
				addnav("Fight the Lich",
						"runmodule.php?module=dagdarkwizard&fight=wizardfight&wizard=4");
				break;
			case 5:
				output("`2Making your way to the very top of the tower, you begin to wonder if %s is even here.",translate_inline (get_module_setting("name")));
				output("You open the door slowly,seeing no one, you step inside.");
				output("`n`n`2As the door closes behind you, the `7silver aura`2 around you goes stronger, and you are pulled into the very center of the room.");
				output("You are standing in the center of a very elaborate magic circle which has been engraved into the floor.");
				output("There is an inner circle, surrounded by a nine pointed star.");
				output("Inside each point, is a magical rune, but not like any you have seen before.");
				output("`n`n %s is steps out of an ante room smiling.",translate_inline (get_module_setting("name")));
				output("`n`n`&\"That's odd, you don't look like the volunteer.");
				output("However, I see you've been prepared, so all that's left now is to kill you.");

				output("You should be honored, very few people get to particpate in sorcery of this caliber.\"");
				output("`n`n`&\"Alas, I see you are unappreciative of this honor, and I must kill you with my blade in order to draw out your life energy.\"");
				output("`n`nYou draw your`! %s `2and stand fast against %s's attack.",translate_inline($session['user']['weapon']),translate_inline (get_module_setting("name")));
				addnav("Fight the Dark Wizard",
						"runmodule.php?module=dagdarkwizard&fight=wizardfight&wizard=5");
				break;
			}
			$status=serialize($status);
			set_module_pref("queststatus",$status);
			break;
		}
		break;
	}
	$fight=httpget("fight");
	switch($fight){
	case "wraithfight":
		$badguy = array(
			"creaturename"=>translate_inline("Wraith"),
			"creaturelevel"=>$session['user']['level']-1,
			"creatureweapon"=>translate_inline("Soul Draining"),
			"creatureattack"=>round($session['user']['attack']*.9),
			"creaturedefense"=>round($session['user']['defense']*0.75, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.1, 0), 
			"diddamage"=>0,
			"type"=>"quest"
		);
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		// Drop through
	case "wraithfighting":
		page_header("The Dark Wizard's Tower");
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			output("`2The Wraith is driven back by your weapons, and finally dissolves into nothingness.");


			if ($session['user']['hitpoints'] <= 0) {
				output("`n`n`^Despite being weakened by the Wraith, you manage to hold onto your life, and your soul, barely.");
				$session['user']['hitpoints'] = 1;
			}
			$expgain=round($session['user']['experience']*(e_rand(2,4)*0.003), 0);
			$session['user']['experience']+=$expgain;
			output("`n`n`&You gain %s experience from this fight!",$expgain);
			output("`2Do you want to keep searching the tower, or flee back to the city?.");
			addnav("Keep Searching",
					"runmodule.php?module=dagdarkwizard&op=searchwizard");
			addnav("V?Flee back to town","village.php");
		} elseif ($defeat) {
			output("`6You drop your %s as your body suddenly becomes too weak to hold it.");
                        output("You feel vacant and empty, as your will is sapped away, and you realize that your only purpose in life now is to serve your Wraith master.");
			output("`n`n`%You have died!");
                        output("You lose 10% of your experience, and your gold is collected as a donation to the Dark Wizard's cause.");
			output("Your soul drifts to the shades.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive']=false;

			$newtitle="wraith";
                        $newname = change_player_title($newtitle);
	               $session['user']['title'] = $newtitle;
	               $session['user']['name'] = $newname;
			apply_buff('wraithcurse', array(
						"startmsg"=>"`^You are now a wraith, you wander the world draining people of their life energy.",
						"name"=>"`4Wraith Curse",
						"rounds"=>300,
						"wearoff"=>"Your strength returns, but you still don't feel like yourself.",
                                                 "atkmod"=>.75,
                                                "defmod"=>.75,
                                                "lifetap"=>1.3,
                                                "allowinpvp"=>1,
			                        "allowintrain"=>1,
                                                "survivenewday"=>1,
                                                "roundmsg"=>"You drain {badguy} for {damage} points, feeding your insaitable hunger.",

						"schema"=>"module-darkwizard",));
			debuglog("was turned into a Wraith after being killed by one in the Dark Wizard's Tower.");
			addnews("%s's went off on a secret quest, and has not been heard from since.",$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,false,"runmodule.php?module=dagdarkwizard&fight=wraithfighting");
		}
		break;
	case "wizardfight":
		$wizard=httpget("wizard");
		switch($wizard){
		case 1:
			$badguy = array(
				"creaturename"=>translate_inline("Necromancer"),
				"creaturelevel"=>$session['user']['level']-1,
				"creatureweapon"=>translate_inline("Wand of Slashing"),
				"creatureattack"=>round($session['user']['attack']*0.75, 0),
				"creaturedefense"=>round($session['user']['defense']*0.8, 0),
				"creaturehealth"=>$session['user']['maxhitpoints'], 
				"diddamage"=>0,
				"type"=>"quest"
			);
				apply_buff('qw1',array(
						"startmsg"=>"`\$The zombie lurches toward you with outstretched arms and rotting flesh!",
						"name"=>"`\$Zombie Attack",
						"rounds"=>10,
						"wearoff"=>"The zombie collapses to the floor, inanimate again.",
						"minioncount"=>1,
						"maxgoodguydamage"=>3,
						"effectmsg"=>"`)The zombie hits you for `^{damage}`) damage.",
						"effectnodmgmsg"=>"`)The zombie lurches unsteadily, and misses you.",
						"schema"=>"module-dagdarkwizard"
					));
			break;
		case 2:
			$badguy = array(
				"creaturename"=>translate_inline("Melvyn the Great!"),
				"creaturelevel"=>$session['user']['level']-1,
				"creatureweapon"=>translate_inline("Wand of Slow Death"),
				"creatureattack"=>round($session['user']['attack']*0.85, 0),
				"creaturedefense"=>round($session['user']['defense']*0.8, 0),
				"creaturehealth"=>$session['user']['maxhitpoints'], 
				"diddamage"=>0,
				"type"=>"quest"
			);
			apply_buff('qw2',array(
						"startmsg"=>"`4You suddenly feel weakened by the Wizard's curse!",
						"name"=>"`7Prepararation Curse",
						"rounds"=>5,
						"wearoff"=>"Your strength returns, but the silver aura remains.",
						"atkmod"=>.9,
						"roundmsg"=>"The Apprentice Wizard laughs at your weakness",
						"schema"=>"module-dagdarkwizard"
                                                ));
			break;
		case 3:
			$badguy = array(
				"creaturename"=>translate_inline("Apprentice Wizard"),
				"creaturelevel"=>$session['user']['level'],
				"creatureweapon"=>translate_inline("Scalpel"),
				"creatureattack"=>$session['user']['attack'],
				"creaturedefense"=>round($session['user']['defense']*0.9, 0),
				"creaturehealth"=>round($session['user']['maxhitpoints']*1.05, 0), 
				"diddamage"=>0,
				"type"=>"quest"
			);
			apply_buff('qw3',array(
						"startmsg"=>"`4You can't see the Apprentice and swing wildly!",
						"name"=>"`4Darkness",
						"rounds"=>5,
						"wearoff"=>"Light returns to the room.",
						"atkmod"=>.5,
						"defmod"=>.5,
						"roundmsg"=>"You listen for the Apprentice's breath, and strike in that direction.",
						"schema"=>"module-dagdarkwizard"
                                                ));
			break;
               case 4:
			$badguy = array(
				"creaturename"=>translate_inline("Lich"),
				"creaturelevel"=>$session['user']['level']+1,
				"creatureweapon"=>translate_inline("Wand of Agony"),
				"creatureattack"=>$session['user']['attack'],
				"creaturedefense"=>round($session['user']['defense']*0.9, 0),
				"creaturehealth"=>round($session['user']['maxhitpoints']*1.05, 0), 
				"diddamage"=>0,
				"type"=>"quest"
			);
			apply_buff('qw4',array(
						"startmsg"=>"`\$Your Dopplganger attacks you with {weapon}!",
						"name"=>"`\$Doppleganger Attack",
						"rounds"=>10,
						"wearoff"=>"The Doppleganger takes a direct blow, and disappears into the ether.",
						"minioncount"=>1,
						"maxgoodguydamage"=>$session['user']['level'],
						"effectmsg"=>"`)The Doppleganger hits you for`^{damage}`) damage.",
						"effectnodmgmsg"=>"`)You dodge your double's attack, since you know all its moves.",
						"schema"=>"module-dagdarkwizard"
					));
			break;
		case 5:
			$badguy = array(
				"creaturename"=>translate_inline (get_module_setting("name")),
				"creaturelevel"=>$session['user']['level']+1,
				"creatureweapon"=>translate_inline("Soul Stealing Sword"),
				"creatureattack"=>round($session['user']['attack']*1.25, 0),
				"creaturedefense"=>round($session['user']['defense']*1.9, 0),
				"creaturehealth"=>round($session['user']['maxhitpoints']*1.15, 0), 
				"diddamage"=>0,
				"type"=>"quest"
			);
			apply_buff('qw5',array(
						"startmsg"=>"`\)This eerie ebony blade drains the energy from your body!",
						"name"=>"`\$Soul Draining",
						"rounds"=>10,
						"wearoff"=>"`&You should be dead by now!`2 {badguy} grumbles.",
						"minioncount"=>1,
						"maxgoodguydamage"=>round($session['user']['maxhitpoints']/3),
						"effectmsg"=>"`)The ebony blade drains you for `^{damage}`) points of damage.",
						"effectnodmgmsg"=>"`)You sidestep the attack, avoiding the blade's curse.",
						"schema"=>"module-dagdarkwizard"
					));
			break;
		}
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		// drop through
	case "wizardfighting":
		page_header("The Dark Wizard's Tower");
		require_once("lib/fightnav.php");
		$wizard=httpget("wizard");
		include("battle.php");
		if ($victory) {
			output("`2You deal the Wizard  a mortal blow with your `!%s!",$session['user']['weapon']);
                        output("`2You remember to take the dead Wizard's wand in order to collect your bounty.`n`n");
			if ($session['user']['hitpoints'] <= 0) {
				output("`n`n`^Your staunch your own wounds with a bit of cloth torn from the wizard's robes, stopping your bloodloss before you are completely dead.`n");
				$session['user']['hitpoints'] = 1;
			}
			$expgain=round($session['user']['experience']*(e_rand(2,4)*0.003), 0);
			strip_buff('qw1');
			strip_buff('qw2');
			strip_buff('qw3');
			strip_buff('qw4');
			strip_buff('qw5');
			$session['user']['experience']+=$expgain;
			output("`&You gain %s experience from this fight!`n`n",$expgain);
			if (httpget("wizard")==5) {
				output("`2With %s defeated, you can feel the energy of your soul returning.",translate_inline (get_module_setting("name")));
				output("`2Before you, the body of %s begins to glow, and %s is transformed into a `7silver mist.",translate_inline (get_module_setting("name")),translate_inline(get_module_setting("heshe")));
				output("`2You realize that you are still being held within the circle and on impulse you smash the floor with your `!%s!",$session['user']['weapon']);
				output("`n`n`2The tower trembles beneath your feet, as dozens of misty figures are suddenly freed.");
                                output("They gather in a swarm, and move in on the mist by %s's fallen cloak.",translate_inline (get_module_setting("name")));
                                output("`n`n `2One silvery form pauses by you long enough to whisper a ghostly \"`7thank you\" `2before joining the others.");
                                output("`n`n`2You realize that it's time to leave.");
                                output("`n`n`2You exit the tower as fast as you can, taking your collected wands with you.");
                                output("`n`nOnce outside, the entire tower shimmers and then vanishes!");
                                output("You wonder if %s is really dead or if %s will return one day.",translate_inline (get_module_setting("name")),translate_inline(get_module_setting("heshe")));
				if (get_module_setting("experience")>1) {
					$expgain=round($session['user']['experience']*(get_module_setting("experience")-1), 0);
					$session['user']['experience']+=$expgain;
					output("`n`n`^You gain %s experience!",$expgain);
					
				}
				set_module_pref("status",5);
				villagenav();
			} else {
				output("`2Do you want to keep searching the tower, or sneak back to town?.");
				addnav("Keep Searching","runmodule.php?module=dagdarkwizard&op=searchwizard");
				addnav("V?Flee back to town","village.php");
			}
		} elseif ($defeat) {
			output("`2You are overwhelmed by the dark magic, and fall to the ground, helpless.");
			output("The wizards take your corpse and transform you into a zombie!`n`n");
			output("`%You have died!");
			output("You lose 15% of your experience, and all of your gold!");
			debuglog("was killed by an evil wizard and lost " . $session['user']['gold'] . " gold.");
			output("Your soul drifts to the shades.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.85;
			$session['user']['alive']=false;
			addnews("%s was defeated in the Dark Wizard's tower, and now roams the earth as a zombie!",
					$session['user']['name']);
			set_module_pref("status",3);
			$newtitle="Zombie";
			$newname = change_player_title($newtitle);
	               $session['user']['title'] = $newtitle;
	               $session['user']['name'] = $newname;
                                 apply_buff('dwcurse', array(
						"startmsg"=>"`^Your soul has been trapped, you are severely weakened.",
						"name"=>"`%Zombie Curse",
						"rounds"=>300,
						"wearoff"=>"Your strength returns, but you still don't feel like yourself.",
						"atkmod"=>0.75,
                                                "defmod"=>0.75,
                                                "allowinpvp"=>1,
			                        "allowintrain"=>1,
                                                "survivenewday"=>1,
                                                "roundmsg"=>"You are a shell of your former self.",

						"schema"=>"module-darkwizard",));
			require_once("modules/dagquests.php");
			dagquests_alterrep(-2);

			addnav("Return to the News","news.php");
		} else {
			fightnav(true,false,"runmodule.php?module=dagdarkwizard&fight=wizardfighting&wizard=$wizard");
		}
		break;
	}
	page_footer();
}
?>
