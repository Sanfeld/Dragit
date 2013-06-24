<?php

require_once("lib/http.php");
require_once("lib/villagenav.php");
require_once("lib/buffs.php");
require_once("lib/sanitize.php");

function dagartifact_getmoduleinfo(){
	// I don't know why table locking is necessary, but I occasionally get nasty errors if I don't do it.
	db_query("LOCK TABLES " . db_prefix("clans") . " READ LOCAL," . db_prefix("accounts") . " READ LOCAL");
	$sql = "SELECT " . db_prefix("clans") . ".clanid as id, clanname from " . db_prefix('clans');
	$res = db_query($sql);
	$drops = array();
	while ($row = db_fetch_assoc($res)) {
		$drops[] .= $row['id'] . "," . $row['clanname'];
	}
	db_query("UNLOCK TABLES");
	$dropline = implode(',', $drops);


//	$dropline = '1,Lords of the Lamp';
	$info = array(
		"name"=>"Artifact Quest",
		"version"=>"0.9.1",
		"author"=>"`&rat`7bastid`^ based on code by `%Sneakabout`^",
		"category"=>"Quest",
		"download"=>"http://www.dragonprime.net/users/ratbastid/dagartifact.zip",
		"settings"=>array(
			"Artifact Quest Settings,title",
			"rewardgold"=>"What is the gold reward for the Artifact Quest?,int|5000",
			"rewardgems"=>"What is the gem reward for the Artifact Quest?,int|2",
			"experience"=>"How much XP should the boss give (x times current player XP)?,floatrange,1.01,1.1,0.01|1.05",
			"minlevel"=>"What is the minimum level for this quest?,range,1,15|5",
			"maxlevel"=>"What is the maximum level for this quest?,range,1,15|11",
			"minrep"=>"What is the minimum reputation for this quest?,int|3",
			"Names and Places,title",
			"clan"=>'Which clan is doing the hiring?,|`&Lords of `$Th`5e La`$mp',
			"leadername"=>"What is the name of the clan leader?,|`&rat`7bastid",
			"leaderheshe"=>"Leader is a...,enum,he,he,she,she|he",
			"lostobj"=>'What object have they lost?,|`$Lav`5a L`$am`5p of Qo`$m-Riy`5adh',
			"enemy"=>"What is the enemy's name?,|`)King Chronos",
			"enemyweapon"=>"What does the enemy attack with?,|Time Manipulation Powers",
			"enemyloc"=>"Where does the enemy live?|`)Chronos Keep",
			"Clan Membership Reward,title",
			"clanmember"=>"Offer membership in a clan on successful completion?,bool|1",
			"whichclan"=>"Offer memebership to which clan?,enum,$dropline",
			
		),
		"prefs"=>array(
			"Artifact Quest Preferences,title",
            "status"=>"Player's current status:,int|0",
        ),
        "requires"=>array(
	       "dagquests"=>"1.1|By Sneakabout",
		),
	);
	return $info;
}

function dagartifact_install(){
	module_addhook("village");
	module_addhook("dragonkilltext");
	module_addhook("newday");
	module_addhook("dagquests");
	return true;
}

function dagartifact_uninstall(){
	return true;
}

function dagartifact_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
	case "village":
		if ($session['user']['location']==
				getsetting("villagename", LOCATION_FIELDS)) {
			tlschema($args['schemas']['gatenav']);
			addnav($args['gatenav']);
			tlschema();
			if (get_module_pref("status")==1) {
				addnav(array("Head for %s`& (3 turns)", get_module_setting('enemyloc')),"runmodule.php?module=dagartifact&op=go");
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
			output("`n`2You hear that Dag can't pay enough for adventurers of your stature, and you abandon the bounty.`0`n");
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
			debuglog("gained $goldgain gold and $gemgain gems for killing bandits.");
			$heshe = get_module_setting('leaderheshe');
			$uheshe = ucfirst($heshe);
			if ($goldgain && $gemgain) {
				output("`n`n`3You carefully hand %s`3 the %s`3. %s`3 examines it closely, and, when he is satisfied that it is intact, solemnly produces `^%s gold`3 and `%%s %s`3!",get_module_setting('leadername'),get_module_setting('lostobj'),$uheshe,$goldgain,$gemgain,translate_inline(($gemgain==1)?"gem":"gems"));
			} elseif ($gemgain) {
				output("`n`n`3You carefully hand %s`3 the %s`3. %s`3 examines it closely, and, when he is satisfied that it is intact, solemnly produces `%%s %s`3!",get_module_setting('leadername'),get_module_setting('lostobj'),$uheshe,$gemgain,translate_inline(($gemgain==1)?"gem":"gems"));
			} elseif ($goldgain) {
				output("`n`n`3You carefully hand %s`3 the %s`3. %s`3 examines it closely, and, when he is satisfied that it is intact, solemnly produces `^%s gold`3!",get_module_setting('leadername'),get_module_setting('lostobj'),$uheshe,$goldgain);
			} else {
				output("`n`n`3You hand Dag the %s, and he grimaces before shrugging, and saying that they \"daen give out rewards fae that anymoor\".",get_module_setting('lostobj'));
			}
			output("`n`nDag regards you proudly. \"Good wark, %s", $session[user][sex] ? 'lassie."' : 'laddie."');
			
			$userclan = $session['user']['clanid'];
			$modclan = get_module_setting("whichclan");
			
			
			if (get_module_setting('clanmember') && $userclan != $modclan) {
				output("`n`n`)%s`) takes you by the arm and leads you to a dark corner of the Inn so you can talk privately. `7\"As a reward for your valor, %s`7 would like to make you a member. Will you accept this honor?\"", get_module_setting('leadername'),get_module_setting('clan'));
				addnav("Accept Clan Membership",'runmodule.php?module=dagartifact&op=acceptclan');
				addnav("Decline Clan Membership",'runmodule.php?module=dagartifact&op=declineclan');
			}
			elseif ($userclan == $modclan) {
				output("`n`n`)%s`) extends a hand and does the official %s`) clan handshake with you.", get_module_setting('leadername'),get_module_setting('clan'));
				addnav("Return to Inn",'inn.php');
			}
			else {
				addnav('Return to the Inn','inn.php');
			}
			
			addnews("`&%s`^ has recovered an ancient artifact!`0",$session['user']['name']);
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
			output("`n`nYou notice Dag sitting with a distinguished looking %s. \"Aye, aye, sit with us,\" he says.`n`n", get_module_setting('leaderheshe') == "he" ? "man" : "woman");
			output("You sit with Dag and the stranger. \"This har is %s`3, leader of the %s`3. \"`n`n", get_module_setting('leadername') , get_module_setting('clan'));
			output("%s`3 leans toward you. \"Dag assures me you're capable of the task we need accomplished,\" ", get_module_setting('leadername'));
			output("%s`3 says. \"Our most holy relic, the %s`3 has been stolen by forces of the evil %s`3. We need a brave warrior to enter %s`3 and retrieve it for us.\"`n`n", get_module_setting('leaderheshe'), get_module_setting('lostobj'), get_module_setting('enemy'), get_module_setting('enemyloc'));
			output("\"We don't know exactly what you'll face once you get in there, though you can be sure that %s`3 is well defended. Are you willing to take this risk for us?\"", get_module_setting('enemyloc'));

			addnav("Take the Job","runmodule.php?module=dagartifact&op=take");
			addnav("Refuse","runmodule.php?module=dagartifact&op=nottake");
			$args['questoffer']=1;
		}
		break;
	}
	return $args;
}

function dagartifact_runevent($type) {
}

function dagartifact_run(){
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
		output("`3%s`3 gives you thorough directions to %s`3. %s grasps you by the shoulder. \"Thank you. Good luck.\"", get_module_setting('leadername'), get_module_setting('enemyloc'), ucfirst(get_module_setting('leaderheshe')));
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
		output("`3Dag and %s`3 turn away from you immediately, whispering conspiratorially about what other warrior they could have perform this noble task.", get_module_setting('leadername'));
		set_module_pref("status",4);
		villagenav();
		break;
	
	case 'go':
		page_header("Outside Town");
		if ($session['user']['turns']<3) {
			output("`2You get a few steps outside the town gate and realize... you're way too pooped for this quest today.");
			output("`n`nMaybe tomorrow you'll be up to it.`n`n");
			villagenav();
			break;
		}
		$session['user']['turns'] -= 3;
		output("`2You strike out for the location that %s`2 described.`n`n", get_module_setting('leadername'));
		output("`2After a long, arduous hike, you see %s`2 in the distance. Its spires rise into the sky, and the clouds seem to circle around it, steeping it in a permanent shroud of half-day. You can hear the howling and beying of wild pack animals somewhere on the path between you and %s`2. You estimate there's about a 20 percent chance of being attacked by wolves between here and there.`n`n", get_module_setting('enemyloc'), get_module_setting('enemyloc'));
		output("`2Needless to say, you're not thrilled about this.`n`n");
		addnav(array("Approach %s", get_module_setting('enemyloc')), "runmodule.php?module=dagartifact&op=approach");
		addnav("Chicken Out","runmodule.php?module=dagartifact&op=chicken");
		break;
	
	case "chicken":
		page_header("Back to town");
		output("`1\"Those crazy %s`1 will have to fend for themselves!\" you mutter under your breath as you head back into town.`n`n", get_module_setting('clan'));
		output("`1Since you didn't even get inside %s`1, you saved a little time. `%You get 1 turn back.", get_module_setting('enemyloc'));
		$session['user']['turns'] += 1;
		set_module_pref('status',4);
		villagenav();
		break;
	
	case "approach":
		$header = sprintf_translate("Approaching %s", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);
		$rand = mt_rand(1,5);
		if ($rand <= 2) {
			output("`3Suddenly a pack of snarling wolves emerges, snarling, from the underbrush. They circle around you, cutting off your escape.`n`nOh, and... they look `4hungry`3.");
			addnav('Fight the Wolves','runmodule.php?module=dagartifact&fight=wolves');
		} else {
			output("`3As you approach %s`3, you can see the carnage of wolf fights littering the path. You shiver slightly, thanking your lucky stars you weren't here when that happened.`n`n", get_module_setting('enemyloc'));
			output("`3You're getting closer to  %s`3. You can see a pair of well-armed guards standing outside the gate.", get_module_setting('enemyloc'));
			addnav("Approach the Gates",'runmodule.php?module=dagartifact&op=gates');
			addnav("Chicken Out",'runmodule.php?module=dagartifact&op=chicken');
		}
		break;
	
	case "gates":
		$header = sprintf_translate("The Gates of %s", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);
		output("`2You approach the massive iron gates of %s`2. Two guards stand at attention. \"`4Halt! Who approaches?`2\" they demand.`n`n", get_module_setting('enemyloc'));
		output("`2You don't really have a good answer to that question. Looks like, as usual, you'll be forced to respond with violence. Either that or slink away to the village...");
		addnav("Attack the Guards",'runmodule.php?module=dagartifact&fight=gateguards');
		addnav("Chicken Out", 'runmodule.php?module=dagartifact&op=chicken');
		break;

	case "entry":
		$header = sprintf_translate("%s: Entryway", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);		
		output("`2You enter the cavernous entryway of %s`2. Try though you might, you can't seem to keep your footsteps from echoing in this enormous, empty place.`n`n", get_module_setting('enemyloc'));
		output("You see a large stone staircase leading up to a dark hallway. A guttering candle in a wall holder lights your way up this gloomy passage.`n`n");
		output("Suddenly you hear a loud scraping sound from back down the hall. `&The huge iron gates are swinging shut behind you!`2 What should you do?`n`n");
		addnav("Let them shut",'runmodule.php?module=dagartifact&op=hall');
		addnav("Run to the gate", 'runmodule.php?module=dagartifact&op=leap');
	
		break;
	case "leap":
		page_header("An Heroic Leap!");
		output("`2Ditching your pack and weapon, you sprint down the hallway. The iron gate makes a dry, slow, squeaky scrapeing noise as it gradually swings shut.`n`n");
		output("You're almost there! Twenty feet away... Fifteen... Ten... You're going to make it! You're going to make it!`n`n");
		output("And then no! The gate slams shut just as you get there--`4slamming heavily on your pinky finger!`2--OUCH!`n`n");
		output("You receive a `&Nasty Pinch on your Pinky Finger for 3 HP`2");
		$session['user']['hitpoints'] -= 3;
		if ($session['user']['hitpoints'] <= 0) {
			output("`n`n`%You have died! And in the most embarassing way possible, too! You lose 10 percent of your experience, and your gold ashamed to be seen with you!");
			output("Your soul drifts to the shades.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive']=false;
			debuglog(sprintf_translate("was killed by a slamming door at %s", get_module_setting('enemyloc') ));
			addnews("%s went on a noble quest, and was never heard from again! Rumor has it a door was involved...",$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			addnav("Climb the Stairs", 'runmodule.php?module=dagartifact&op=hall');
		}
		break;
			

	case "hall":
		$header = sprintf_translate("%s: Upstairs Hall", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);		
		output("You climb the wide staircase. You have no idea what fell creature lurks down this hall, so you are careful not to make a sound.`n`n");
		output("Suddenly you come over all clumsy. You trip over your own feet, and you and your equipment clatter noisily to the hard stone floor!`n`n");
		output("From the end of the hall you hear a scraping noise. `&You look up, shocked, to see an `5animated suit of armor`&, its `5bloody longsword`& drawn, charging down the hall at you! As it rushes on you, it opens its mouth and bellows a ear-shatteringly loud, metalic roar!");
		addnav("Stand and fight","runmodule.php?module=dagartifact&fight=armor");
		break;
		
	case "throneroom":
		$header = sprintf_translate("%s: Throne Room", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);		
		output("`2You turn a corner at the end of the long, dark hall, and emerge, blinking, into a brightly lit chamber, richly appointed in black and red. You have found the Throne Room of %s`2! On a golden throne at the far end of the room sits %s`2 himself!`n`n", get_module_setting('enemyloc'),get_module_setting('enemy'));
		output("%s`2 stands from his throne, points at you, and cries, \"`4Guards! Destroy this creature!`2\"`n`n", get_module_setting('enemy'));
		output("Three `1Royal Guards`2 flank you. You draw your %s and face them bravely", $session['user']['weapon']);
		addnav("Fight the `1Royal Guards`2","runmodule.php?module=dagartifact&fight=royalguards");
		break;


	
	
	case "bossattacks":
		$header = sprintf_translate("%s: Throne Room", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);		
		output("`&While you were fighting, %s`7 was silently slipping off his throne and preparing to attack you. He prepares to launch a massive attack on you with his %s`&!`n`n", get_module_setting('enemy'), get_module_setting('enemyweapon'));
		output("`4\"You may have bested my Royal Guard, %s, but you'll never defeat ME!\"`& he screams." , $session['user']['sex'] ? 'little girl' : 'boy');
		$damage = $session['user']['maxhitpoints'] - $session['user']['hitpoints'];
		if (($damage / $session['user']['maxhitpoints']) > .60) {
			output("`n`nYou smart at his taunting and get Super PISSED!");
			apply_buff('rat1', array(
				"name"=>"Super PISSED!",
				"roundmsg"=>"You're gonna GET this guy!",
				"rounds"=>10,
				"atkmod"=>1.2,
				"defmod"=>1.3,
				"schema"=>"module-dagartifact",
			));
		}
				

		addnav(array("Fight %s", get_module_setting('enemy')),
					"runmodule.php?module=dagartifact&fight=boss");
	break;
	
	case "postboss":
		page_header("In the Throne Room");
		output("`&You step over the lifeless form of %s`& toward the back of the Throne Room. You can see three doors on the back wall. The doors are locked with a cunning series of locks that will allow you to open one door, but will seal the other two permanently. Which will you pick?", get_module_setting('enemy'));
		addnav("Left Door",'runmodule.php?module=dagartifact&op=door');
		addnav("Center Door",'runmodule.php?module=dagartifact&op=door');
		addnav("Right Door",'runmodule.php?module=dagartifact&op=door');
	break;
	
	case "door":
		page_header("In the Throne Room");
		output("You open your chosen door...`n`n");
		
		$rand = mt_rand(1,3);
		switch ($rand) {
		
			case "1":
				set_module_pref('status','5');
				output("`&There, in the dark closet, you see it... A source of dread and fascination for all those who gaze upon it...`n`n");
				output("%s`&!!`n`n", get_module_setting('lostobj'));
				output("You carefully bundle it in the cloth and pack it gently into your pack. Now to get it safely back to the Inn!`n`n");
				output("After much grunting and pulling, you pry open the gates and head back toward town.");
				addnav("Hurry back to the Village", "village.php");
			break;

			case "2":
				output("`&There, in the dark closet, you see it.. The thing %s`& tried to keep secret from the whole world: his secret treasure chest.`n`n",get_module_setting('enemy'));
				output("You find 1200 gold and 7 gems!`n`n");
				$session['user']['gold']+=1200;
				$session['user']['gems']+=7;
				output("`4Unfortunately, there's no %s here`4...`n`n", get_module_setting('lostobj'));
				output("After much grunting and pulling, you pry open the gates and head back toward town.");
				output("`&You can't believe it! You've come all this way for nothing! You shuffle your feet as you walk out of %s`&. If only there was some way you could lose your memory and have the opportunity to do this all over again...  Oh well! ", get_module_setting('enemyloc'));
				set_module_pref('status','2');
				addnav("(V) Return to Degolburg", "village.php");
			break;

			case "3":
				output("`&There, in the dark closet, you see it.. The thing %s`& tried to keep secret from the whole world: his Mummy!`n`n", get_module_setting('enemy'));
				output("The Mummy lunges at you. You try to swat it away, but it's just too shocking. The mummy mauls you and you barely escape from the Throne Room with your life.`n`n", get_module_setting('enemyloc'));
				$session['user']['hitpoints'] = 3;
				output("`4Unfortunately, was no %s`4 in that closet!`n`n", get_module_setting('lostobj'));
				output("After much grunting and pulling, you pry open the gates and head back toward town.");
				output("`&You can't believe it! You've come all this way for nothing! You shuffle your feet as you walk out of %s`&. If only there was some way you could lose your memory and have the opportunity to do this all over again...  Oh well! ", get_module_setting('enemyloc'));
				set_module_pref('status','3');
				addnav("Back to Town", "village.php");
			break;		
		}
		break;
		
	case "acceptclan":
		page_header("In the Inn");
		output("`3%s`3 smiles warmly and puts a hand on your shoulder. `&\"Welcome,\"`3 %s`3 says, pinning the clan badge on your lapel. `&\"See you in the Clan Hall.\"`3", get_module_setting('leadername'),get_module_setting('leaderheshe'));
		
		//do database stuff to make this player a member
		
		$session['user']['clanid'] = get_module_setting('whichclan');
		$session['user']['clanrank'] = 1;
		$session['user']['clanjoindate'] = "NOW()";
		
		addnav("Return to the Inn",'inn.php');
		break;
	
	case "declineclan":
		page_header("In the Inn");
		output("`3%s`3 smiles sadly. `&\"I wish you would let us show our gratitude in this way. Nonetheless, I will respect your wishes.`\"`3 %s`3 bows deeply and excuses himself to take the %s`3 back to his clan hall.", get_module_setting('leadername'), ucfirst(get_module_setting('leaderheshe')),get_module_setting('lostobj'));
		addnav("Return to the Inn",'inn.php');
		break;	
	
	
	}
	//Fight Cases
	$fight=httpget("fight");
	switch($fight){
	
	case "wolves":
		$badguy = array(
			"creaturename"=>"Pack of Snarling Wolves",
			"creaturelevel"=>$session['user']['level']-1,
			"creatureweapon"=>"Circling and Snarling",
			"creatureattack"=>round($session['user']['attack']*0.9, 0),
			"creaturedefense"=>round($session['user']['defense']*0.6, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.02, 0),
			"diddamage"=>0,
			"type"=>"quest"
		);
		$session['user']['badguy'] = createstring($badguy);
		$battle = true;
		//drop through
	case "wolvesfighting":
		$header = sprintf_translate("The Road to %s", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			output("`2The wolves die whimpering.");

			if ($session['user']['hitpoints'] <= 0) {
				output("`n`n`^Your staunch your own wounds with a small bit of moss from the ground nearby, stopping your bloodloss before you are completely dead.");
				$session['user']['hitpoints'] = 1;
			}
			$expgain=round($session['user']['experience']*(e_rand(2,4)*0.003), 0);
			$session['user']['experience']+=$expgain;
			output("`n`n`&You gain %s experience from this fight!`n`n",$expgain);
			output("`2Do you want to forge ahead to %s`2, or flee back to town?", get_module_setting('enemyloc'));
			addnav(array("Head to %s", get_module_setting('enemyloc')),
					"runmodule.php?module=dagartifact&op=gates");
			addnav("Flee back to town","runmodule.php?module=dagartifact&op=chicken");
		} elseif ($defeat) {
			output("`6Your vision blacks out as the wolves feast on your bloody remains.");
			output("`n`n`%You have died! You lose 10 percent of your experience, and your gold is dragged off into the bushes by the wolves!");
			output("Your soul drifts to the shades.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive']=false;
			debuglog(sprintf_translate("was killed on the road to %s", get_module_setting('enemyloc') ));
			addnews("%s went on a noble quest, and was never heard from again!",$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,false,"runmodule.php?module=dagartifact&fight=wolvesfighting");
		}
		break;			

	case "gateguards":
		$name = sprintf_translate("Guards of the Gates of %s", get_module_setting('enemyloc'));
		$badguy= array(
			"creaturename" => $name,
			"creaturelevel" => $session['user']['level']-1,
			"creatureweapon" => 'Pike and Sword',
			"creatureattack" => round($session['user']['attack']*.08, 0),
			"creaturedefense" => round($session['user']['defense']*.09, 0),
			"creaturehealth" => round($session['user']['maxhitpoints']*1.1,0),
			"diddamage" => 0,
			"type" => "quest"
		);
		$session['user']['badguy'] = createstring($badguy);
		$battle = true;
		//drop through
	case "gateguardsfighting":
		$header = sprintf_translate("The Gates of %s", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			output("`2You've slaughtered the guards!");

			//we'll heal them up a little.
			$healamount += round(($session['user']['maxhitpoints'] - $session['user']['hitpoints']) * 0.8);
			
			$expgain=round($session['user']['experience']*.02, 0);
			$session['user']['experience']+=$expgain;
			output("`n`n`&You gain %s experience from this fight!",$expgain);
			if ($healamount) {
				output("`n`n`1You find a healing potion in one of the guards' pockets. You quaff it and feel better.`n`n"); 
				$session['user']['hitpoints'] += $healamount;
			}
			output("`2You try the gate, and to your surprise, it swings open. You can see an entryway beyond it. ");
			output("You realize that, if you enter, there will be no turning back.");
			addnav(array("Enter %s", get_module_setting('enemyloc')),
					"runmodule.php?module=dagartifact&op=entry");
			addnav("Chicken Out","runmodule.php?module=dagartifact&op=chicken");
		} elseif ($defeat) {
			output("`6The last thing you hear before losing consciousness is the laughter of the guards.");
			output("`n`n`%You have died! You lose 10 percent of your experience, and your gold is stolen by the no good thieving guards!");
			output("Your soul drifts to the shades.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive']=false;
			debuglog(sprintf_translate("was killed at the gates of %s", get_module_setting('enemyloc')));
			addnews("%s's went on a noble quest, and was never heard from again!",$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,true,"runmodule.php?module=dagartifact&fight=gateguardsfighting");
		}
		break;	
	case "armor":
		$badguy = array(
			"creaturename"=>"Animated Armor",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"bloody longsword",
			"creatureattack"=>round($session['user']['attack'], 0),
			"creaturedefense"=>round($session['user']['defense'], 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.15, 0),
			"diddamage"=>0,
			"type"=>"quest"
		);
		$session['user']['badguy'] = createstring($badguy);
		$battle = true;
		//drop through
	case "armorfighting":
		$header = sprintf_translate("Inside %s", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			output("`n`n`2The armor clatters empty to the floor. Spooky!");

			$expgain=round($session['user']['experience']*0.03, 0);
			$session['user']['experience']+=$expgain;
			output("`n`n`&You gain %s experience from this fight!`n`n",$expgain);
			output("`2You continue (quietly!) down the hall.");
			addnav("Continue down the Hall",
					"runmodule.php?module=dagartifact&op=throneroom");
		} elseif ($defeat) {
			output("`6The animated armor impales you on its longsword. You gasp and clutch at the wound as you stumble to the floor.");
			output("`n`n`%You have died! You lose 10 percent of your experience, and your gold rolls off into a corner somewhere!");
			output("Your soul drifts to the shades.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive']=false;
			debuglog(sprintf_translate("was killed in the hall of %s.", get_module_setting('enemyloc')));
			addnews("%s went on a noble quest, and was never heard from again!",$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,false,"runmodule.php?module=dagartifact&fight=armorfighting");
		}
		break;			

	case "royalguards":
		$badguy = array(
			"creaturename"=>"Royal Guards",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"superior tactics and training",
			"creatureattack"=>round($session['user']['attack'], 0),
			"creaturedefense"=>round($session['user']['defense'], 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.2, 0),
			"diddamage"=>0,
			"type"=>"quest"
		);
		$session['user']['badguy'] = createstring($badguy);
		$battle = true;
		//drop through
	case "rgfighting":
		$header = sprintf_translate("Approaching %s", color_sanitize(get_module_setting('enemyloc')));
		page_header($header);	
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			output("`n`n`1You wipe the floor with that silly Royal Guard.");

			$expgain=round($session['user']['experience']*0.04, 0);
			$session['user']['experience']+=$expgain;
			output("`n`n`&You gain %s experience from this fight!`n`n",$expgain);
			addnav("Continue",
					"runmodule.php?module=dagartifact&op=bossattacks");
		} elseif ($defeat) {
			output("`6\"I'm sorry, %s`6,\" you think, \"I've failed you!\"", get_module_setting('leadername'));
			output("`n`n`%You have died! You lose 10 percent of your experience, and your gold is added to the coffers of %s`%!`n`n", get_module_setting('enemy'));
			output("Your soul drifts to the shades.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive']=false;
			debuglog(sprintf_translate("was killed in the hall of $s.", get_module_setting('enemyloc')));
			addnews("%s went on a noble quest, and was never heard from again!",$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,false,"runmodule.php?module=dagartifact&fight=rgfighting");
		}
		break;			

	case "boss":
		$badguy = array(
			"creaturename"=>get_module_setting('enemy'),
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>get_module_setting('enemyweapon'),
			"creatureattack"=>round($session['user']['attack']*1.05, 0),
			"creaturedefense"=>round($session['user']['defense']*1.05, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.2, 0),
			"diddamage"=>0,
			"type"=>"quest"
		);
		$session['user']['badguy'] = createstring($badguy);
		$battle = true;
		//drop through
	case "bossfighting":
		$header = sprintf_translate("Fighting %s", color_sanitize(get_module_setting('enemy')));
		page_header($header);
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory) {
			output("`n`n%s`& lets out a might scream as you plunge your %s into his heart.", get_module_setting('enemy'), $session['user']['weapon']);

			$expgain=round($session['user']['experience']* (get_module_setting('experience') - 1), 0);
			$session['user']['experience']+=$expgain;
			output("`n`n`&You gain %s experience from this fight!`n`n",$expgain);
			addnav("Continue",
					"runmodule.php?module=dagartifact&op=postboss");
		} elseif ($defeat) {
			output("`n`n`6\"I'm sorry, %s`6,\" you think, \"I've failed you!\"", get_module_setting('leadername'));
			output("`n`n`%You have died! You lose 10 percent of your experience, and your gold is added to the coffers of %s`%!", get_module_setting('enemy'));
			output("Your soul drifts to the shades.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive']=false;
			debuglog(sprintf_translate("was killed in the hall of $s.", get_module_setting('enemyloc')));
			addnews("%s went on a noble quest, and was never heard from again!",$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,false,"runmodule.php?module=dagartifact&fight=bossfighting");
		}
		break;			


	
	
	}
	page_footer();
}
?>
