<?php

require_once("lib/http.php");
require_once("lib/villagenav.php");

function dagroc_getmoduleinfo(){
	$info = array(
		"name"=>"roc Quest",
		"version"=>"1.0",
		"author"=>"Kristen Fox`%Sneakabout`^",
		"category"=>"Quest",

		"settings"=>array(
			"Roc Quest Settings,title",
			"rewardgold"=>"What is the gold reward for the Roc Quest?,int|2000",
			"rewardgems"=>"What is the gem reward for the Roc Quest?,int|3",
			"experience"=>"What is the quest experience multiplier for the Roc Quest?,floatrange,1.01,1.2,0.01|1.1",
			"minlevel"=>"What is the minimum level for this quest?,range,1,15|10",
			"maxlevel"=>"What is the maximum level for this quest?,range,1,15|14",
		),
		"prefs"=>array(
			"Roc Quest Preferences,title",
			"status"=>"How far has the player gotten in the Roc Quest?,int|0",
		),
		"requires"=>array(
			"dagquests"=>"1.1|By Sneakabout",
		),
	);
	return $info;
}

function dagroc_install(){
	module_addhook("village");
	module_addhook("dragonkilltext");
	module_addhook("newday");
	module_addhook("dagquests");
	return true;
}

function dagroc_uninstall(){
	return true;
}

function dagroc_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
	case "village":
		if ($session['user']['location']==
				getsetting("villagename", LOCATION_FIELDS)) {
			tlschema($args['schemas']['gatenav']);
			addnav($args['gatenav']);
			tlschema();
			if (get_module_pref("status")==1) {
				addnav("Find the Roc (1 turn)",
					"runmodule.php?module=dagroc&op=search");
			}
		}
		break;
	case "dragonkilltext":
		set_module_pref("status",0);
		break;
	case "newday":
		if (get_module_pref("status")==1 &&
				$session['user']['level']>(get_module_setting("maxlevel")+1)){
			set_module_pref("status",4);
			output("`n`6You hear that another adventurer defeated the Roc which has been terrorizing livestock and threatening children as well.`0`n");
			require_once("modules/dagquests.php");
			dagquests_alterrep(-1);
		}
		break;
	case "dagquests":
		if ($args['questoffer']) break;
		if (get_module_setting("minlevel")<=$session['user']['level'] &&
				$session['user']['level']<=get_module_setting("maxlevel") &&
				!get_module_pref("status")) {
			output("He appraises you silently for a moment, but when you question him about work, he relaxes slightly and nods.`n`n");
			output("\"The stories might have started spreading already. A town near the edge of the forest has been losing livestock to what's being called a Roc, a huge bird almost the size of the Green Dragon itself!  The villagers have even told of this bird, who flies by day, trying to carrying off a small boy, who was lucky enough to be able to grab onto a tree as the bird rose out of the forest.\"`n`n");
                        output("\"In any case, it's the villagers who've collected the gold to pay someone to rid them of this menace. If you kill the Roc, they'll reward you handsomely.  So... are you feelin' like you want to take on a winged demon today?\"`n`n");
			output("The idea of finding and battling a huge bird such as the mythical Roc tantilizes your warrior spirit.");
			output("Really, it's just a matter of watching for it, shouldn't be hard to miss, and then surprising it, right?");
			addnav("Take the Job","runmodule.php?module=dagroc&op=take");
			addnav("Refuse","runmodule.php?module=dagroc&op=nottake");
			$args['questoffer']=1;
		}
		break;
	}
	return $args;
}

function dagroc_runevent($type) {
}

function dagroc_run(){
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
		output("`3Dag nods, and tells you how to get to the village under seige.");
		output("He advises you to prepare well - this bird won't go lightly!");
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
		output("`3Dag makes a snide remark about a warrior being afraid of a BIRD.");
		output("You leave Dag's table, feeling a bit sheepish, but knowing you probably made the right decision.");
		set_module_pref("status",4);
		addnav("I?Return to the Inn","inn.php");
		require_once("modules/dagquests.php");
		dagquests_alterrep(-1);
		break;
	case "search":
		page_header("Find the Roc");
		if (!$session['user']['turns']) {
			output("`2You feel far too tired to lie in wait for the Roc - you'd probably fall asleep!");
			output("Maybe tomorrow.`n`n");
			villagenav();
			page_footer();
		}
		output("`2You discover the small village, whose people seem to be somewhat distracted by glancing nervously at the skies.");
		$session['user']['turns']--;
		output("You set yourself up in a hidden spot and scan the skies yourself.");
		$rand=e_rand(1,7);
		switch($rand){
		case 1:
		case 2:
			output("As you settle in to a camouflaging thicket with a nice view, you get pretty comfortable in the warm air.");
			output("The next thing you know it's dusk and you're just waking up - you fell asleep for quite a few hours!  Oh well, you figure that if the Roc had come, the villagers would have started screaming, which would have woke you up anyway.`n`n");
			output("Guess you'll have to try again - maybe drink some strong coffee too.");
			addnews("%s fell asleep on the job today!",$session['user']['name']);
			villagenav();
			break;
		case 3:
		case 4:
			output("You sit in your camouflaged thicket, alert to any movement in the sky or even above the treetops.");
			output("Suddenly, at your back, you hear a large rustling and a number of strange yips. You turn around quickly to find a pack of hungry coyotes making their way along the edge of the forest, as surprised to find you as you are them!");
			output("And you know, it's never good to surprise a pack of hungry coyotes! Quick - draw your %s!",$session['user']['weapon']);
			addnav("Fight the Coyotes","runmodule.php?module=dagroc&fight=coyotefight");
			break;
		case 5:
		case 6:
		case 7:
			output("As you lie in wait, you suddenly see a dot in the distant sky. You balance on the balls of your feet, ready to spring should this turn out to be the dreaded Roc.");
			output("The dot grows larger, seemingly intent on a large pasture next to your hiding place, where many fluffy white sheep graze peacefully.");
			output("It is indeed the Roc! As it swoops closer, apparently eyeing a big juicy sheep that's off by itself, you are ready to attack!");
			addnav("Fight the Roc","runmodule.php?module=dagroc&fight=rocfight");
			break;
		}
		break;
	}
	$fight=httpget("fight");
	switch($fight){
	case "coyotefight":
		$badguy = array(
			"creaturename"=>translate_inline("Pack of Coyotes"),
			"creaturelevel"=>$session['user']['level']-1,
			"creatureweapon"=>translate_inline("Many Snapping Jaws"),
			"creatureattack"=>$session['user']['attack'],
			"creaturedefense"=>round($session['user']['defense']*0.75, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.1, 0),
			"diddamage"=>0,
			"type"=>"quest"
		);
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		// drop through
	case "coyotefighting":
		page_header("The Terrorized Village");
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			output("`2The pack of coyotes, or what's left of them, scatters, running scared into the woods.");
			if ($session['user']['hitpoints'] <= 0) {
				output("`n`n`^You realize that if you'd had to continue your battle, you might not have made it out alive!`n");
				$session['user']['hitpoints'] = 1;
			}
			output("`2You quickly flee the scene, hoping to avoid the rest of the pack.`n`n");
			$expgain=round($session['user']['experience']*(e_rand(2,4)*0.002));
			$session['user']['experience']+=$expgain;
			output("`&You gain %s experience from this fight!",$expgain);
			output("`2Woozily, you return to the village, shaken by the attack.");
			villagenav();
		} elseif ($defeat) {
			output("`6There are coyotes everywhere - you don't have enough arms and weapons to fight them all! You are overcome by sheer numbers!`n`n");
			output("`%You have died!`n");
			output("You lose 10% of your experience, and your gold is stolen by scavengers!`n");
			output("Your spirit drifts to the shades, reconsidering how dangerous a pack of hungry coyotes could possibly be.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive'] = false;
			debuglog("Killed by coyotes.");
			addnews("%s was overcome by a large pack of hungry coyotes!",
				$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,true,
				"runmodule.php?module=dagroc&fight=coyotefighting");
		}
		break;
	case "rocfight":
		$badguy = array(
			"creaturename"=>translate_inline("Roc"),
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>translate_inline("Huge Claws and Razor-Like Beak"),
			"creatureattack"=>round($session['user']['attack']*1.15, 0),
			"creaturedefense"=>round($session['user']['defense']*1.1, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.4, 0), 
			"diddamage"=>0,
			"type"=>"quest"
		);
		apply_buff('rocbeak',array(
			"name"=>"`\$Razor Beak",
			"roundmsg"=>"The roc swoops around and snaps its beak at you!",
			"effectmsg"=>"You are cut by the beak for `4{damage}`) points!",
			"effectnodmgmsg"=>"You dodge the Roc's sharp beak!",
			"rounds"=>20,
			"wearoff"=>"The Roc tires of snapping at you with its beak.",
			"minioncount"=>1,
			"maxgoodguydamage"=>$session['user']['level'],
			"schema"=>"module-dagroc"
		));
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		// drop through
	case "rocfighting":
		page_header("The Terrorized Village");
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			output("`2The Roc is finally struck dead by your weapon, crying out with an anguished screech!");
			output("The villagers gather and begin to applaud - you have saved the village!`n`n");
			$expgain = round($session['user']['experience'] *
					(get_module_setting("experience")-1), 0);
			$session['user']['experience']+=$expgain;
			output("`&You gain %s experience from this fight!",$expgain);
			if ($session['user']['hitpoints']<1) {
				// Coping with the doublebuffkill scenario
				output("The villagers notice that you're not doing so well.");
				output("An old woman dressed in healer's robes comes up to you and pours a potion in your mouth.");
				output("It brings you back from the brink of death, but just barely.");
				$session['user']['hitpoints']=1;
			}
			$goldgain=get_module_setting("rewardgold");
			$gemgain=get_module_setting("rewardgems");
			$session['user']['gold']+=$goldgain;
			$session['user']['gems']+=$gemgain;
			debuglog("found $goldgain gold and $gemgain gems after slaying a roc.");
			output("`n`n`2The village leader then steps forward and he bows in thanks.");
			if ($goldgain && $gemgain) {
				output("He then hands you `^%s gold`2 and `%%s %s`2 as a reward for killing the huge bird.",$goldgain,$gemgain,translate_inline(($gemgain==1)?"gem":"gems"));
			} elseif ($gemgain) {
				output("He hands you `%%s %s`2 as a reward and steps away quickly before you ask for more.",$gemgain,translate_inline(($gemgain==1)?"gem":"gems"));
			} elseif ($goldgain) {
				output("He hands you `^%s gold`2 and says he wishes he could give you more, but that this is as much as the village can afford right now.",$goldgain);
			} else {
				output("After he thanks you, he walks away, and you realize that you've just done a good deed without getting a reward. You try not to look too put off.");
			}
			output("As you leave, you hear the villagers spreading the news.");
			set_module_pref("status",2);
			addnews("%s defeated a Roc! The village is now free of the tyranny of this huge flying menace!",$session['user']['name']);
			villagenav();
			strip_buff("rocbeak");
			require_once("modules/dagquests.php");
			dagquests_alterrep(3);
		} elseif ($defeat) {
			output("`2You fall to the ground as the last slice of the razor sharp beak does you in.");
			output("`2The Roc swoops over, picks up your bloodied body and carries it off!");
			output("You have been defeated by a bird.  A BIRD!`n`n");
			output("`%You have died!`n");
			output("You lose 10% of your experience, and your gold is stolen by scavengers!`n");
			output("Your spirit drifts to the shades.");
			debuglog("was killed by a Roc and lost ".
					$session['user']['gold']." gold.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive'] = false;
			set_module_pref("status",3);
			addnews("%s went out hunting for a Roc but never came back! Villagers reported seeing this great bird carrying off something large in its beak.",
					$session['user']['name']);
			addnav("Return to the News","news.php");
			strip_buff("rocbeak");
			require_once("modules/dagquests.php");
			dagquests_alterrep(-1);
		} else {
			fightnav(true,true,
				"runmodule.php?module=dagroc&fight=rocfighting");
		}
		break;
	}
	page_footer();
}
?>
