<?php
//as this module gives out permanent atk/def on completion its a long module to get through.
//at least 6 seperate encounters are required to get to the end stage.  There's also a few random outcomes so the end is never fully guaranteed.
//basically set on chasing a leprechaun through the forest, its smattered with fairytales, legends, myths and creatures from all three.
//This module is exclusively written for ShadowRavens Lotgd.

function rainbowquest_getmoduleinfo(){
	$info = array(
		"name" => "Rainbow Quest - Hunt for Gold",
		"author" => "`b`&Ka`6laza`&ar`b ",
		"version" => "1.0",
		"Download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1070",
		"category" => "Forest Specials",
		"description" => "multi-sectioned chase leprechaun special",
		"settings"=>array(
			"Rainbow Quest Settings,title",
			"extrahps"=>"How many hitpoints can Rainbow Quest give?,range,1,5,1|3",
			"carrydk"=>"Do max hitpoints gained carry across DKs?,bool|1",
			"mindk" => "Minimum dk's for bracelet, int|31",
			),
		"prefs" => array(
			"section" => "What section is the player up to?, int|",
			"runaway" => "Has the player ran away? and what stage, int|",
			"today" => "Has the player seen this special today?, bool|",
			"charms" => "How many charms has the player collected, int|",
			"mirror" => "Does this player have the mirror, bool|",
			"bracelet" => "Does this player have the bracelet, bool|",
			"fightnum" => "Which creature is this player facing, int|",
			"bridle" => "Which bridle does the player have, enum,0,old,1,Gold,2,Silver,3,Bronze,4,Platinum,5,Leather,6,Pyrite",
			"dupits"=>"Did this player do something stupid,bool|",
			"dupitsday"=>"Days til DupitS disappears,int",
		),
		);
	return $info;
}
function rainbowquest_chance(){
	global $session;
    if (get_module_pref('today','rainbowquest',$session['user']['acctid'])==1) return 0;
    else return 100;
}
function rainbowquest_install(){
	module_addeventhook("forest","require_once(\"modules/rainbowquest.php\"); return rainbowquest_chance();");
    module_addhook("hprecalc");
    module_addhook("newday");
    module_addhook("dragonkilltext");
	module_addhook("biostat");
	return true;
}
function rainbowquest_uninstall(){
	return true;
}
function rainbowquest_dohook($hookname, $args){
	global $session;
			switch($hookname){
	case "hprecalc":
		$args['total'] -= get_module_pref("extrahps");
		if (!get_module_setting("carrydk")) {
			$args['extra'] -= get_module_pref("extrahps");
			set_module_pref("extrahps", 0);
		}
		break;
	case "newday":
		clear_module_pref("today");
		if (get_module_pref("dupits")==1){
			increment_module_pref("dupitsday")+1;
		}
		if (get_module_pref("dupitsday")==7){
			clear_module_pref("dupitsday");
			clear_module_pref("dupits");
		}
		break;
	case "dragonkilltext":
		if (get_module_pref("bracelet")==1){
			$charm = get_module_pref("charms");
			$gain = round($charm*0.5);
			$session['user']['attack']+=$gain;
			$session['user']['defense']+=$gain;
			output("`%You gain %s attack and defence from your charms",$gain);
		}
		break;
	case "biostat":
			$char = httpget("char");
			$sql = ("SELECT acctid FROM ".db_prefix("accounts")." WHERE login='$char'");
			$res = db_query($sql);
            $row = db_fetch_assoc($res);
            $acctid = $row['acctid'];
            $sqln = ("SELECT name FROM ".db_prefix("accounts")." WHERE login='$char'");
			$resn = db_query($sqln);
            $rown = db_fetch_assoc($resn);
            $name=$rown['name'];
            $charms = get_module_pref("charms","rainbowquest", $acctid);
            if (get_module_pref("bracelet","rainbowquest",$acctid)==1){
            	if ($charms<>0){
            	output("`2Leprechaun Charms:  `^%s", $charms);
           		output_notl("`n");
        		}
        	}
        	if (get_module_pref("dupits","rainbowquest",$acctid)==1){
        		output("%s, `^has `@DupitS `^stamped on their forehead",$name);
        		output_notl("`n");
    		}
            break;
	}
	return $args;
}
function rainbowquest_runevent(){
	global $session;
	page_header("Rainbow Quest");
	$session['user']['specialinc'] = "module:rainbowquest";
	$op = httpget('op');
	addnav ("What do you do?");
	set_module_pref("today",1);
	debuglog(" `@Encountered RQ");
	if (get_module_pref("runaway")==1){
		output("`^The `2Leprechaun `^you were chasing earlier (You DO remember the `QKick Me `^sign don't you?), peeps out from behind a overhanging rock.  Pokes his tongue out at you and makes a few silly faces.  He then turns tail and runs for it.");
		output_notl("`n`n");
		addnav("Give Chase", "runmodule.php?module=rainbowquest&op=chase");
		addnav("Don't bother", "runmodule.php?module=rainbowquest&op=giveup2");
		$session['user']['specialinc'] = "";
	}elseif (get_module_pref("section")==1){
		output("`^You come upon a familiar fork in the road, picking up your marker you head down one of the roads");
		addnav("Left Road", "runmodule.php?module=rainbowquest&op=leftroad");
		addnav("Right Road", "runmodule.php?module=rainbowquest&op=rightroad");
		addnav("Not Today", "runmodule.php?module=rainbowquest&op=giveup3");
		$session['user']['specialinc'] = "";
	}elseif (get_module_pref("runaway")==2){
		output("`^You really don't take a hint do you, or do you? Hearing a zinging sound, you twist sideways just as a arrow plunks into the tree where you were standing.");
		addnav("Chase Him", "runmodule.php?module=rainbowquest&op=chase");
		addnav("Give up", "runmodule.php?module=rainbowquest&op=giveup4");
		$session['user']['specialinc'] = "";
	}elseif (get_module_pref("runaway")==3){
		output("`^You find yourself back at the fork in the road, realising this is your LAST chance to follow after the `2Leprechaun`^");
		addnav("Left Road", "runmodule.php?module=rainbowquest&op=leftroad");
		addnav("Right Road", "runmodule.php?module=rainbowquest&op=rightroad");
		addnav("Not Today", "runmodule.php?module=rainbowquest&op=giveup4");
		$session['user']['specialinc'] = "";
	}elseif (get_module_pref("section")==2){
		output("Nimbly stepping over the hole you found, you pick up your marker and keep heading down the road");
		addnav("Continue down the road", "runmodule.php?module=rainbowquest&op=chase2");
		addnav("Not Today", "runmodule.php?module=rainbowquest&op=giveup5");
		$session['user']['specialinc'] = "";
	}elseif (get_module_pref("section")=="bridle"){
		output("You come across a small village in the forest, your memory floods back, thinking of the Basilisk you continue on around the village, and keep heading down the road");
		addnav("Continue down the road", "runmodule.php?module=rainbowquest&op=chase3");
		addnav("Not Today", "runmodule.php?module=rainbowquest&op=giveup5");
		$session['user']['specialinc'] = "";
	}elseif (get_module_pref("section")=="chasm"){
		output("You stumble across that chasm again.  What are you going to pick this time?");
		addnav("Use the Bridge", "runmodule.php?module=rainbowquest&op=chasmbridge");
		addnav("Find another way round", "runmodule.php?module=rainbowquest&op=anotherway");
		addnav("Give Up", "runmodule.php?module=rainbowquest&op=giveup5");
	}elseif (get_module_pref("section")=="ignore"){
		output("You pick up your marker and once again head deep into the forest, in search of that elusive Leprechaun");
		addnav("Continue On","runmodule.php?module=rainbowquest&op=chase4");
		addnav("Give Up", "runmodule.php?module=rainbowquest&op=giveup5");
	}elseif (get_module_pref("section")=="bridge"){
		output("You stumble across that chasm again.  What are you going to pick this time?");
		addnav("Drag a Log Over", "runmodule.php?module=rainbowquest&op=draglog");
		addnav("Find another way round", "runmodule.php?module=rainbowquest&op=anotherway");
		addnav("Give Up", "runmodule.php?module=rainbowquest&op=giveup6");
		//add runaway options, once coded.
	}else{
		output("`^You stumble across a small man, dressed all in `2green `^sitting on a rock in the middle of the forest.  Gasping you realise, you've discovered a `2Leprechaun`^.");
		output_notl("`n`n");
		output("`^Creeping up behind him, you suddenly throw yourself on top of the `2leprechaun`^ hitting only open air, and the rock.. Ouch!!");
		output_notl("`n`n");
		output("`^Catching a flash of green out the corner of your eye, you take off in pursuit, knowing if you capture him, he'll have to lead you to his pot of gold.  Breathing hard you slowly catch up with him.");
		output_notl("`n`n");
		output("`^With a sudden burst of speed he disappears around a turn in the path.  You consider your choices.");
		addnav("Keep Chasing", "runmodule.php?module=rainbowquest&op=chase");
		addnav("Give Up", "runmodule.php?module=rainbowquest&op=giveup1");
		$session['user']['specialinc'] = "";
	}
		$session['user']['specialinc'] = "";
		page_footer();
}
function rainbowquest_run(){
	global $session;
	page_header("Rainbow Quest");
	$session['user']['specialinc'] = "module:rainbowquest";
	$op = httpget('op');
	set_module_pref("today",1);
	addnav("What do you do?");
	if ($op=="giveup1"){
		output("`^You head back to the main section of the forest, to the sounds of guffaws.  Feeling as if you're being laughed at, you finally realise the `2Leprechaun `^has stuck a `QKick Me!`^ sign on your back.  You feel really silly and loose 2 charm.");
		$session['user']['charm']-=2;
		set_module_pref("runaway",1);
		addnav("Return to the Forest", "forest.php?");
		$session['user']['specialinc'] = "";
	}
	if ($op=="giveup2"){
		output("`^Deciding NOT to chase the `2Leprechaun `^ you turn around to head back to the forest, just in time to see a huge rock hurtling at you, fired of course, from the `2Leprechauns `^Slingshot.  It hits you fair between the eye's, knocking you from your feet.  When you wake up you've lost the time for 2 forest fights, and are beginning to wonder about that `2Leprechaun.`^ (Do you think he's BORED?).");
		set_module_pref("runaway",2);
		$session['user']['turns']-=2;
		addnav("Return to the Forest", "forest.php?");
		$session['user']['specialinc'] = "";
	}
	if ($op=="giveup3"){
		output("`^Deciding that you're too tired to spend anymore time searching for the `2Leprechaun`^ you turn and walk back into the forest, tripping over a piece of rope laid across the path.");
		output_notl("`n`n");
		output("`^Looking up you catch a glimpse of green.  The `2Leprechaun `^had set up a booby trap just for you!!  Checking your person, you realise you dropped a gem in the fall");
		set_module_pref("runaway",3);
		$session['user']['gems']-=1;
		addnav("Return to the Forest", "forest.php?");
		$session['user']['specialinc'] = "";
	}
	if ($op=="giveup4"){
		output("`^Racing back to the forest, you trip over what looks to be a bush, realising that the bush is moving, your eye's widen, remembering your recent history with the `2Leprechaun`^.");
		output_notl("`n`n");
		output("`^Giggling gleefully, the `2Leprechaun `^springs out from the bush and paints you from head to toe in `@Green `^paint and runs off giggling");
		addnews("%s `^is looking rather `@green `^from an encounter with a `2Leprechaun`0.",$session['user']['name']);
		addnav("Return to the Forest", "forest.php?");
		set_module_pref("runaway",0);
		$session['user']['specialinc'] = "";
	}
	if ($op=="giveup5"){
		output("Giving up now will reset your Quest, are you sure?");
		addnav("Yes, Give up", "runmodule.php?module=rainbowquest&op=giveup6");
		addnav("Continue to the Chasm", "runmodule.php?module=rainbowquest&op=chasm");
	}
	if ($op=="giveup6"){
		output("Your quest has been reset");
		set_module_pref("section",0);
		set_module_pref("runaway",0);
		set_module_pref("mirror",0);
		set_module_pref("bridle",0);
		addnav("Return to the forest", "forest.php?");
		$session['user']['specialinc'] = "";
	}
	if ($op=="chase"){
		output("`^Turning the corner, you come to a cross roads, there is a signpost, unfotunately lieing on its side in the dirt!  Now which way did that little imp go?");
		output_notl("`n`n");
		output("`^Coming towards you down one of the roads is a wizened old woman, she stops and looks you up and down.  You start to wonder why she's staring at you when she says");
		output("`6\"Well are you going to stand there all day blocking the path\" `^Perhaps you should move eh? `6\"Would you help an old woman carry her basket home from the market?\"");
		addnav("Carry Basket", "runmodule.php?module=rainbowquest&op=carry");
		addnav("Tell her to Carry it Herself", "runmodule.php?module=rainbowquest&op=dontcarry");
		set_module_pref("section",1);
		set_module_pref("runaway",0);
		$session['user']['specialinc'] = "";
	}
	if ($op=="carry"){
		output("`^She hands you her basket and lugging it onto your back you stagger down the path after her.  Reaching her house, she thanks you and casts a small spell to help you on your way.");
		output_notl("`n`n");
		apply_buff('witchesspell',array(
				"name"=>"Witches Spell",
				"rounds"=>20,
				"wearoff"=>"`&The Spell wears off!",
				"atkmod"=>1.05,
				"roundmsg"=>"`^The Witches Spell lifts your spirits and helps you fight.",
			));
		output("`^As you leave the womans house, you notice your new buff!");
		output_notl("`n`n");
		output("You return to the fork in the road. Having had enough exerting yourself for today, you mark the spot to continue on from and head back into the forest.");
		addnav("Return to the Forest", "forest.php?");
		$session['user']['specialinc'] = "";
	}
	if ($op=="dontcarry"){
		output("`^The old woman, claps her hands together and suddenly a hoard of little black imps rush out of the forest at you.  You have no choice you'll have to fight them");
		output_notl("`n`n");
		set_module_pref("fightnum",1);
		addnav("Attack", "runmodule.php?module=rainbowquest&op=attack");
	}
	if ($op=="attack"){
		if (get_module_pref("fightnum")==1){
		$level = $session['user']['level']+1;
		$dk = round($session['user']['dragonkills']*.1);
		$badguy = array(
			"creaturename"=>"`7The `4Wizened `7Hag",
			"creaturelevel"=>$level,
			"creatureweapon"=>"`\$Lightning `^and `7Thunder",
			"creatureattack"=>round($session['user']['attack']),
			"creaturedefense"=>round($session['user']['defense'])-1,
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.1),
			"diddamage"=>0,
			"type"=>"Old Hag");
		apply_buff('blackimps', array(
			"startmsg"=>"`4The Black Imps swarm all over you`n",
			"name"=>"`4Black Imps",
			"rounds"=>3,
			"wearoff"=>"You squash the last Imp.",
			"minioncount"=>$session['user']['level'],
			"mingoodguydamage"=>0,
			"maxgoodguydamage"=>3+$dk,
			"effectmsg"=>"`^A `\$Imp`^ pulls your hair for `\${damage}`^ hitpoints`^.",
			"effectnodmgmsg"=>"`^The Imp gets squoosed by you`^!",
			"effectfailmsg"=>"`^The Imp fails to get a hold of you`^!",
		));
		$session['user']['badguy']=createstring($badguy);
		}
		if (get_module_pref("fightnum")==2){
		$level = $session['user']['level']+3;
		$attack = $session['user']['attack']*2;
		set_module_pref("mirror",0);
		$badguy = array(
			"creaturename"=>"`7Basilisk",
			"creaturelevel"=>$level,
			"creatureweapon"=>"`&Penetrating Glare",
			"creatureattack"=>round($session['user']['attack'])+3,
			"creaturedefense"=>round($session['user']['defense'])+2,
			"creaturehealth"=>round($session['user']['maxhitpoints']*2.1),
			"diddamage"=>0,
			"type"=>"basilisk");
		apply_buff('mirror', array(
			"startmsg"=>"`^You pull out your mirror!`n",
			"name"=>"Power of Reflection",
			"rounds"=>10,
			"wearoff"=>"Your Mirror Cracks.",
			"minioncount"=>3,
			"minbadguydamage"=>0,
			"maxbadguydamage"=>$attack,
			"effectmsg"=>"`&Your Mirror Reflects the Glare.",
			"effectnodmgmsg"=>"`^Your Mirror starts to crack.",
			"effectfailmsg"=>"`&You drop your Mirror.",
		));
		$session['user']['badguy']=createstring($badguy);
		}
		if (get_module_pref("fightnum")==3){
		$level = $session['user']['level']+1;
		$attack = $session['user']['attack']*1.1;
		$badguy = array(
			"creaturename"=>"`#Friendly Giant",
			"creaturelevel"=>$level,
			"creatureweapon"=>"`3Cries of Friendship",
			"creatureattack"=>round($session['user']['attack'])+3,
			"creaturedefense"=>round($session['user']['defense'])+2,
			"creaturehealth"=>round($session['user']['maxhitpoints']*2.1),
			"diddamage"=>0,
			"type"=>"giant");
		apply_buff('mirror', array(
			"startmsg"=>"``^You decide to Attack the Giant!`n",
			"name"=>"Fear Surge",
			"rounds"=>10,
			"wearoff"=>"You realise you're scared of a Friendly Giant",
			"minioncount"=>3,
			"minbadguydamage"=>0,
			"maxbadguydamage"=>$attack,
			"effectmsg"=>"`&Your Fear gives you an edge.",
			"effectnodmgmsg"=>"`^Your Terror roots you to the spot`^.",
			"effectfailmsg"=>"`&Your Fear is no match for the Giants Hands.",
		));
		$session['user']['badguy']=createstring($badguy);
		}
		if (get_module_pref("fightnum")==4 || get_module_pref("fightnum")==5){
		$level = $session['user']['level']+1;
		$dk = round($session['user']['dragonkills']*.1);
		$badguy = array(
			"creaturename"=>"`$ Giant Ogre",
			"creaturelevel"=>$level,
			"creatureweapon"=>"`@Razor Sharp Teeth",
			"creatureattack"=>round($session['user']['attack']),
			"creaturedefense"=>round($session['user']['defense'])-1,
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.1),
			"diddamage"=>0,
			"type"=>"Giant Ogre");
		$session['user']['badguy']=createstring($badguy);
		}
		$op="fight";
		httpset("op", "fight");
	}
	if ($op=="fight"){ $battle=true; }
	if ($battle){
	include("battle.php");
	if ($victory){
		if (get_module_pref("fightnum")==1){
			output("`n`^You're surrounded by the dead bodies of many imps, and the Hag is slain.");
			$expbonus=$session['user']['dragonkills']*4;
			$expgain =($session['user']['level']*e_rand(18,26)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("`@You gain `#%s experience`@.`n`n",$expgain);
			addnews("%s `^defeated a swarm of imps in the forest`^.",$session['user']['name']);
			if (get_module_pref("mirror")==0){
				output("You retrieve a mirror from the Hags belongings");
				set_module_pref("mirror",1);
			
			}
		output("Feeling somewhat tired from your exertions you mark the spot and return to the forest, planning on coming back another day");
		addnav("Return to the Forest", "forest.php?");
		$session['user']['specialinc'] = "";
		}
		if (get_module_pref("fightnum")==2){
			output("`n`^The Basilisk lays dead at your feet.");
			$expbonus=$session['user']['dragonkills']*5;
			$expgain =($session['user']['level']*e_rand(10,27)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("`@You gain `#%s experience`@.`n`n",$expgain);
			addnews("%s `^saved a village deep in the forest`^.",$session['user']['name']);
			output("You collect some basilisk venom into a flask (that'll come in handy I'm sure) and head back to the village.");
			addnav("Return in Triumph", "runmodule.php?module=rainbowquest&op=triumph");
			if (is_module_active("witchgarden")){
				$hm=e_rand(1,5);
				$id = $session['user']['acctid'];
				$v = get_module_pref("venom","witchgarden",$id)+$hm;
				set_module_pref("venom",$v,"witchgarden",$id);
			}
			$session['user']['specialinc'] = "";
			}
		if (get_module_pref("fightnum")==3){
			output("`n`^You feel slightly ashamed for attacking a Friendly Giant.");
			$expbonus=$session['user']['dragonkills']*2;
			$expgain =($session['user']['level']*e_rand(5,15)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("`@You gain `#%s experience`@.`n`n",$expgain);
			addnews("%s `^attacked and killed a Friendly Giant the meanie`^.",$session['user']['name']);
			addnav("Continue on", "runmodule.php?module=rainbowquest&op=leftpath2");
			$session['user']['specialinc'] = "";
			}
		if (get_module_pref("fightnum")==4){
			output("`n`^The Giant Ogre lays dead at your feet.");
			$expbonus=$session['user']['dragonkills']*5;
			$expgain =($session['user']['level']*e_rand(10,27)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("`@You gain `#%s experience`@.`n`n",$expgain);
			addnews("%s `^killed a Ogre in a castle`^.",$session['user']['name']);
			addnav("Try another door","runmodule.php?module=rainbowquest&op=doorchoose");
			addnav("Leave", "runmodule.php?module=rainbowquest&op=chase4");
			$session['user']['specialinc'] = "";
			}
			if (get_module_pref("fightnum")==5){
			output("`n`^The Giant Ogre lays dead at your feet.");
			$expbonus=$session['user']['dragonkills']*5;
			$expgain =($session['user']['level']*e_rand(10,27)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("`@You gain `#%s experience`@.`n`n",$expgain);
			addnews("%s `^killed a Ogre in a castle`^.",$session['user']['name']);
			addnav("Try again","runmodule.php?module=rainbowquest&op=dungeon");
			addnav("Leave", "runmodule.php?module=rainbowquest&op=chase4");
			$session['user']['specialinc'] = "";
			}
	}elseif($defeat){
		if (get_module_pref("fightnum")==1){
		$session['user']['specialinc'] = "";
		$session['user']['alive'] = false;
		$session['user']['hitpoints'] = 0;
		$session['user']['gold'] = 0;
		set_module_pref("runaway",0);
		set_module_pref("section",0);
		output("You can hear the Hags cackling as your lifeforce fades, You're Dead!!");
		if (get_module_pref("mirror")==1){
			output("The Hag retrieves her mirror from you.");
			set_module_pref("mirror",0);
		}
		$exploss = round($session['user']['experience']*.05);
		$session['user']['experience']-=$exploss;
		output("You have lost %s experience",$exploss);
		output("You've lost all your gold");
		output("You may continue playing again tomorrow.");
		addnav("The Shades","shades.php");
		addnews("The body of %s was discovered at a crossroads.",$session['user']['name']);
		}
		if (get_module_pref("fightnum")==2){
		$session['user']['specialinc'] = "";
		$session['user']['alive'] = false;
		$session['user']['hitpoints'] = 0;
		$session['user']['gold'] = 0;
		set_module_pref("runaway",0);
		set_module_pref("section",0);
		output("As you black out, your last memory is looking the Basilisk fair in the EYE!!");
		$exploss = round($session['user']['experience']*.05);
		$session['user']['experience']-=$exploss;
		output("You have lost %s experience",$exploss);
		output("You've lost all your gold");
		output("You may continue playing again tomorrow.");
		addnav("The Shades","shades.php");
		addnews("%s has gone missing near a cave deep in the forest.",$session['user']['name']);
		}
		if (get_module_pref("fightnum")==3){
		$session['user']['specialinc'] = "";
		$session['user']['alive'] = false;
		$session['user']['hitpoints'] = 0;
		$session['user']['gold'] = 0;
		set_module_pref("runaway",0);
		set_module_pref("section",0);
		output("Pain wracks your body as the Giant deals you a death blow, maybe he wasn't that friendly!!");
		$exploss = round($session['user']['experience']*.05);
		$session['user']['experience']-=$exploss;
		output("You have lost %s experience",$exploss);
		output("You've lost all your gold");
		output("You may continue playing again tomorrow.");
		addnav("The Shades","shades.php");
		addnews("%s has gone missing deep in the forest.",$session['user']['name']);
		}
		if (get_module_pref("fightnum")==4 || get_module_pref("fightnum")==5){
		$session['user']['specialinc'] = "";
		$session['user']['alive'] = false;
		$session['user']['hitpoints'] = 0;
		$session['user']['gold'] = 0;
		set_module_pref("runaway",0);
		set_module_pref("section",0);
		output("The Giant Ogre will gnaw on your bones for quite some time!!");
		$exploss = round($session['user']['experience']*.05);
		$session['user']['experience']-=$exploss;
		output("You have lost %s experience",$exploss);
		output("You've lost all your gold");
		output("You may continue playing again tomorrow.");
		addnav("The Shades","shades.php");
		addnews("%s has gone missing in a castle deep in the forest.",$session['user']['name']);
		}
		}else{
		require_once("lib/fightnav.php");
		fightnav(true,false,"runmodule.php?module=rainbowquest");
	}
}
if ($op=="leftpath2"){
	output("I can't believe you killed him. That poor giant.  Feeling extremely embarrassed and a bit guilty, you turn and slip quickly back into the forest");
	set_module_pref("section",0);
	addnav("Return to Forest", "forest.php");
	$session['user']['specialinc'] = "";
}
if ($op=="leftroad"){
	output("`^Taking the left road you walk for what seems like miles, after a while you realise you are completely lost, seeing a small cottage off to one side of the road, you decide to stop and ask for directions");
	output_notl("`n`n");
	cottage_outcome();
	addnav("Continue on", "runmodule.php?module=rainbowquest&op=cleftroad");
	set_module_pref("section",2);
}
if ($op=="cleftroad"){
	output("`^You catch a glimpse of `2green `^just ahead of you, taking off at a run, you suddenly feel the ground open up beneath your feet, tricky lil thing aint he that `2Leprechaun?");
	addnav("Try and Climb out", "runmodule.php?module=rainbowquest&op=climbout");
	addnav("Go Exploring", "runmodule.php?module=rainbowquest&op=exploreleft");
}
if ($op=="climbout"){
	$climbtry=e_rand(1,7);
	set_module_pref("section","2");
	output("`^You start to pull yourself up on some vines");
	if ($climbtry==2) output("a vine breaks");
	if ($climbtry==3) output("your hand slips");
	if ($climbtry==4) output("the wall is slippery and your foot slips");
	if ($climbtry==7) output("you slither back to the bottom");
	output("you're not quite to the top yet...");
	output_notl("`n`n");
	if ($climbtry==1){
		output("`^With one last superhuman pull you clamber out of the hole!!  Deciding you've had enough adventure for today, you decide to try again tomorrow");
		addnav("Return to the Forest","forest.php?");
		$session['user']['specialinc'] = "";
	}else{
		addnav("Keep going!","runmodule.php?module=rainbowquest&op=climbout");
	}

}
if ($op=="exploreleft"){
		$result = e_rand(1,3);
		output("Turning you decide to explore the hole in the ground, after fossicking around for a while, you manage to find %s gems",$result);
		$session['user']['gems']+=$result;
		output_notl("`n`n");
		output("Deciding to climbout of the hole, you discover that its later than you thought, you no longer have time to fight in the forest today");
		$session['user']['turns']=0;
		addnav("Climb out", "runmodule.php?module=rainbowquest&op=climbout");
}
if ($op=="rightroad"){
	output("`^Turning to the right, you follow the road.  Turning a corner, the forest seems to fall away, and you are standing in a clearing, dappled shade falling across a lagoon.");
	output_notl("`n`n");
	output("`^You come to a dead standstill, hardly daring to breath, as you see on the lagoons bank, a beautiful young maiden, petting a shining unicorn, you look on with pleasure until the two take their leave.");
	output("`^Thinking of what you've just seen brings a smile to your lips, you gain 2 charm");
	$session['user']['charm']+=2;
	set_module_pref("section",3);
	addnav("Approach the Lagoon", "runmodule.php?module=rainbowquest&op=lagoon");
	addnav("Follow the Maiden", "runmodule.php?module=rainbowquest&op=maiden");
	addnav("Follow the Unicorn", "runmodule.php?module=rainbowquest&op=unicorn");
}
if ($op=="lagoon"){
	output("You approach the Lagoon, the waters look cool and inviting and you are feeling quite warm");
	addnav("Swim", "runmodule.php?module=rainbowquest&op=swimlagoon");
	addnav("Keep after the Leprechaun", "runmodule.php?module=rainbowquest&op=chasm");
	addnav("Sit down and Relax", "runmodule.php?module=rainbowquest&op=relaxlagoon");
}
if ($op=="swimlagoon"){
	output("Diving into the cool waters of the lagoon");
	lagoonswim_outcome();
	addnav("Keep after the Leprechaun", "runmodule.php?module=rainbowquest&op=chasm");
	addnav("Sit down and Relax", "runmodule.php?module=rainbowquest&op=relaxlagoon");
}
if ($op=="relaxlagoon"){
	output("You feel totally relaxed, your rest has gained you a little hitpoint boost");
	$hpgain = $session['user']['hitpoints']*0.05;
	$session['user']['hitpoints']+=$hpgain;
	addnav("Follow the Leprechaun", "runmodule.php?module=rainbowquest&op=chasm");
}
if ($op=="maiden"){
	output("You follow the maiden to a small village.  One of the villagers notices you, and soon enough you are approached by the Villages Headman");
	output_notl("`n`n");
	output("\"`6Warrior, we be under attack by a horrible beast, a Basilisk, can you be helping us?");
	addnav("I'll help", "runmodule.php?module=rainbowquest&op=basilisk");
	addnav("Umm No", "runmodule.php?module=rainbowquest&op=nohelp");
}
if ($op=="nohelp"){
	output("The Villagers look at you in disgust, before you know what is happening, they've grabbed some rotten tomatoes, lettuce and cabbages from the basket near the stocks and start pelting you with them");
	output_notl("`n`n");
	output("You race for the forest, and feel decidedly less charming, marking the path, you decide you've had enough adventuring today and head back into the forest for a while");
	$session['user']['charm']-=2;
	set_module_pref("section","chasm");
	addnav("Return to Forest", "forest.php");
	$session['user']['specialinc'] = "";
}
if ($op=="basilisk"){
	output("`^The headsman shakes your hand and points you in the direction of the basilisk's cave, bidding him farewell, you stride up to the cave.");
	output_notl("`n`n");
	if (get_module_pref("mirror")==1){
		output("`@Entering the cave, you confront the basilisk, holding your mirror in front of you, you attack");

		set_module_pref("fightnum",2);
		addnav("Attack", "runmodule.php?module=rainbowquest&op=attack");
	}
	if (get_module_pref("mirror")==0){
		output("`^Searching frantically for your mirror, you realise you don't have one!! Running from the cave, you catch a fleeting glimpse of the basilisk, your hitpoints are reduced to one and fearing for your life you run back to the forest, forgetting to mark your trail");
		$session['user']['hitpoints']=1;
		set_module_pref("section",0);
		set_module_pref("runaway",0);
		addnav("Return to Forest", "forest.php?");
		$session['user']['specialinc'] = "";
	}
}
if ($op=="triumph"){
	output("`^You return to the Village telling stories of your daring fight with the Basilisk and your Victory over it, the Headsman gives you a small bag of gems and gold as a reward");
	$session['user']['gold']+=1250;
	$session['user']['gems']+=5;
	output_notl("`n`n");
	output("The villagers offer you a reward, leading you to a small hut, they show you several bridles, gold, silver, bronze, platinum, pyrite and leather, which will you pick?");
	addnav("Gold Bridle", "runmodule.php?module=rainbowquest&op=goldbridle");
	addnav("Silver Bridle", "runmodule.php?module=rainbowquest&op=silverbridle");
	addnav("Bronze Bridle", "runmodule.php?module=rainbowquest&op=bronzebridle");
	addnav("Platinum Bridle", "runmodule.php?module=rainbowquest&op=platinumbridle");
	addnav("Pyrite Bridle", "runmodule.php?module=rainbowquest&op=pyritebridle");
	addnav("Leather Bridle", "runmodule.php?module=rainbowquest&op=leatherbridle");
	$session['user']['specialinc'] = "";
}
if ($op=="goldbridle"){
	set_module_pref("bridle", 1);
	output("You have picked the gold bridle.");
	output("`^You carefully mark your spot and head back to the forest, hoping to catch the `2Leprechaun `^another day.");
	addnav("Return to the Forest", "forest.php?");
	set_module_pref("section","bridle");
	$session['user']['specialinc'] = "";
}
if ($op=="silverbridle"){
	set_module_pref("bridle", 2);
	output("You have picked the silver bridle.");
	output("`^You carefully mark your spot and head back to the forest, hoping to catch the `2Leprechaun `^another day.");
	addnav("Return to the Forest", "forest.php?");
	set_module_pref("section","bridle");
	$session['user']['specialinc'] = "";
}
if ($op=="bronzebridle"){
	set_module_pref("bridle", 3);
	output("You have picked the bronze bridle.");
	output("`^You carefully mark your spot and head back to the forest, hoping to catch the `2Leprechaun `^another day.");
	addnav("Return to the Forest", "forest.php?");
	set_module_pref("section","bridle");
	$session['user']['specialinc'] = "";
}
if ($op=="platinumbridle"){
	set_module_pref("bridle", 4);
	output("You have picked the platinum bridle.");
	output("`^You carefully mark your spot and head back to the forest, hoping to catch the `2Leprechaun `^another day.");
	addnav("Return to the Forest", "forest.php?");
	set_module_pref("section","bridle");
	$session['user']['specialinc'] = "";
}
if ($op=="leatherbridle"){
	set_module_pref("bridle", 5);
	output("You have picked the leather bridle.");
	output("`^You carefully mark your spot and head back to the forest, hoping to catch the `2Leprechaun `^another day.");
	addnav("Return to the Forest", "forest.php?");
	set_module_pref("section","bridle");
	$session['user']['specialinc'] = "";
}
if ($op=="pyritebridle"){
	set_module_pref("bridle", 6);
	output("You have picked the pyrite bridle.");
	output("`^You carefully mark your spot and head back to the forest, hoping to catch the `2Leprechaun `^another day.");
	addnav("Return to the Forest", "forest.php?");
	set_module_pref("section","bridle");
	$session['user']['specialinc'] = "";
}
if ($op=="chase2"){
	output("`^From behind you you hear something that sounds like thunder.. suddenly the tree's part and you see a giant coming straight at you.");
	set_module_pref("section",4);
	addnav("Fight Giant", "runmodule.php?module=rainbowquest&op=attack");
	addnav("Hide behind a tree", "runmodule.php?module=rainbowquest&op=treehide");
	addnav("Talk to the Giant", "runmodule.php?module=rainbowquest&op=talkgiant");
	set_module_pref("fightnum",3);
}
if ($op=="treehide"){
	output("`^Thinking quickly you jump behind the nearest tree.. only to have it almost fall on you, scaring you half to death, when the Giant runs into it");
	output_notl("`n`n");
	$session['user']['hitpoints']=3;
	output("You've lost most of your hitpoints and not feeling terribly well, you pass out, suffering some memory loss, you wander around lost for a while before finding your way back to a more familiar section of the forest");
	set_module_pref("runaway",0);
	set_module_pref("section",0);
	$session['user']['turns']-=3;
	addnav("Return to the Forest", "forest.php?");
	$session['user']['specialinc'] = "";
}
if ($op=="talkgiant"){
	output("`^You decide you're not scared of that big, huge, lumbering Giant and deciding to risk it you yell up at him \"`6Hello`^\" Looking down at you he smiles.  Phew he's a friendly giant, luckily for you.");
	output("He sighs sadly and you inquire of him what is wrong?");
	output_notl("`n`n");
	output("`^Slowly he tells you a long sad story, `6\"My old father, sadly passed away a few weeks ago, he left me a wishing cloak, with it I can travel anywhere I like, the only problem is I can't remember where I put the cloak.");
	output("It's a lovely red cloak, and sad as I am to say, I appear to have lost it somewhere.\"`^ Your ears prick up at the mention of a wishing cloak and you listen attentively to the Giant telling you where he last remembers having it.");
	output("`6\"I seem to remember about a day or two ago, I was helping my older brother to collect some firewood, the storm had blown down quite a few large trees near our house.  I remember putting it somewhere for safe keeping, but now I can't remember where that is.\"");
	output_notl("`n`n");
	output("`^You remember seeing some blown over tree's just a little way back down the path, and after commiserating with the Giant for a while on his loss, you leave him and run back to the tree's, YES!! There it is, the Wishing Cloak, just the corner of it sticking out from behind a bush.");
	addnav("Take it to the Giant", "runmodule.php?module=rainbowquest&op=giantcloak");
	addnav("Put it on and make a wish", "runmodule.php?module=rainbowquest&op=cloakwish");
}
if ($op=="giantcloak"){
	output("`^Quickly you gather up the cloak, and run back to the Giant with it.  Handing it to him, he's most overcome with gratitude and he hands you a magic bracelet and some gold in gratitude.");
	addnav("Continue on", "runmodule.php?module=rainbowquest&op=findcastle");
	$session['user']['gold']+=2000;
	if (get_module_pref("mindk")<=$session['user']['dragonkills']){
		set_module_pref("bracelet",1);
	}else{
		output("unfortunately the bracelet is yet to heavy for you to carry, let alone wear");
	}
}
if ($op=="findcastle"){
	output("Soon enough, you come upon a castle, walking around it you see it has no door, and the only window is waaaaaaay up the top.  Yeah sounds familiar right?");
	output_notl("`n`n");
	output("Ok time to test your FairyTale knowledge, Who or what is inside this Castle?  Choose wisely, your answer will influence the outcome.");
	addnav("Sleeping Beauty", "runmodule.php?module=rainbowquest&op=sleepingbeauty");
	addnav("Rapunzel", "runmodule.php?module=rainbowquest&op=rapunzel");
	addnav("Blarney Stone", "runmodule.php?module=rainbowquest&op=blarneystone");
}
if ($op=="sleepingbeauty"){
	output("Tut, tut, tut.. Your knowledge of FairyTales is abysmal.  Looks like you're going to have to do some research, and well we won't tell anyone, but looks like you're going to have to go back a ways and try again.");
	set_module_pref("section",1);
	set_module_pref("runaway",0);
	addnav("Return to the Forest", "forest.php?");
	$session['user']['specialinc'] = "";
}
if ($op=="blarneystone"){
	output("Okay, points for trying, but honestly, just because you're chasing a Leprechaun doesn't make it all about Ireland you know, and well the Blarney Stone isn't a Fairy Tale, it does exist.  Brrrr goes the buzzer, back to the start for you. Oh but you get to keep anything you've found.");
	set_module_pref("section",0);
	set_module_pref("runaway",0);
	addnav("Return to the Forest", "forest.php?");
	$session['user']['specialinc'] = "";
}
if ($op=="rapunzel"){
	output("YaY, finally someone who knows their Fairy Tales.  Ok you know what to do, go do it.");
	output("`^Heading up to the castle, you call out. Rapunzel, Rapunzel let down your hair.  Yada Yada Yada, we all know what happens, she lets down her hair, you climb up, free her and well the rest is Fairy Tale History.");
	output_notl("`n`n");
	output("Okay, so you've freed Rapunzel, Well done, and as a reward, you get to come back and continue from here another time, oh and yup here have a small reward.");
	$session['user']['turns']+=5;
	$session['user']['gold']+=500;
	addnav("Return to the Forest", "forest.php?");
	set_module_pref("section","ignore");
	$session['user']['specialinc'] = "";
}
if ($op=="cloakwish"){
	output("You quickly don the cloak and make a wish.. Yes you COULD wish yourself to the end of this quest.. but thats NOT gonna happen (It would be a little too easy that way)");
	output_notl("`n`n");
	output("Did I mention you could end up anywhere? Not very reliable this cloak");
	switch(e_rand(1,3)) {
		case 1:
		addnav("Wish", "runmodule.php?module=rainbowquest&op=chase4");
		break;
		case 2:
		addnav("Wish", "runmodule.php?module=rainbowquest&op=cleftroad");
		break;
		case 3:
		addnav("Wish", "runmodule.php?module=rainbowquest&op=chase");
		break;

	}
}
if ($op=="chase3"){
	output("You walk through the village to the cheers and greetings of the many happy villagers.  Soon you have left the village far behind and turning catching a glimpse of green up ahead, you quickly start running");
	output("You come to a sudden halt, your breath coming raggedly and its not from the running, more likely from the huge chasm you just about ran straight into.");
	output_notl("`n`n");
	output("looking around, you see several logs, one of them looks as if it would be big enough to slide across the chasm and cross, looking further up you see a rickety looking rope bridge.");
	addnav("Drag a Log Over", "runmodule.php?module=rainbowquest&op=draglog");
	addnav("Use the Bridge", "runmodule.php?module=rainbowquest&op=chasmbridge");
	addnav("Find another way round", "runmodule.php?module=rainbowquest&op=anotherway");
}
if ($op=="draglog"){
	output("`^Bracing yourself, you choose the longest log and panting and heaving, you slowly manage to balance it across the chasm.  It doesn't really look that steady.  Are you sure you want to do this?");
	addnav("Cross", "runmodule.php?module=rainbowquest&op=crosslog");
	addnav("Find another way", "runmodule.php?module=rainbowquest&op=anotherway");
	addnav("Head for the Bridge", "runmodule.php?module=rainbowquest&op=chasmbridge");
}
if ($op=="anotherway"){
	output("Deciding that there has to be a better way, you wander around for a while before....");
	addnav("Continue", "runmodule.php?module=rainbowquest&op=cleftroad");
}
if ($op=="chasm"){
	output("You are standing at the edge of a huge chasm");
	output_notl("`n`n");
	output("looking around, you see several logs, one of them looks as if it would be big enough to slide across the chasm and cross, looking further up you see a rickety looking rope bridge.");
	addnav("Drag a Log Over", "runmodule.php?module=rainbowquest&op=draglog");
	addnav("Use the Bridge", "runmodule.php?module=rainbowquest&op=chasmbridge");
	addnav("Find another way round", "runmodule.php?module=rainbowquest&op=anotherway");
}
if ($op=="chasmbridge"){
	$bridge=e_rand(1,3);
		switch ($bridge){
		case 1:
		if ($session['user']['race']=="Barbarian" || $session['user']['race']=="Amazon"){
			output("The bridge sways precariously in the breeze, confident in your abilities you start to cross.  The bridge snaps under your weight, however your skill as a Warrior Race, enables you to swiftly grab a section of the bridge, swinging to the other side of the chasm and clamber up the remains of the bridge");
			addnav("Continue on", "runmodule.php?module=rainbowquest&op=chasmcontinue");
		}else{
			output("The bridge sways precariously in the breeze, confident in your abilites you start to cross.  The bridge snaps under your weight, you fall to the bottom of the chasm, landing on some soft moss.  It would appear you're unharmed you will have to find another way across though.");
			set_module_pref("section","bridge");
			addnav("Return to Forest","forest.php?");
		}
		break;
		case 2:
		output("Taking a deep breath you run across the bridge, making it to the other side, you heave a sigh of relief, just as you notice the frayed ropes on this side of the bridge, finally give way and snap, That was close");
		$session['user']['hitpoints']*1.5;
		addnav("Continue on", "runmodule.php?module=rainbowquest&op=chasmcontinue");
		break;
		case 3:
		output("You tentatively place one foot ahead of the other onto the bridge, just as you make it half way you notice how frayed the ropes on the far side are and hope they hold...... NOPE, no such luck, the ropes snap before you can make it, fortunately you land on some soft moss, so although unharmed you need to find another way across");
		set_module_pref("section","bridge");
		addnav("Return to Forest","forest.php?");
		break;
	}
	$session['user']['specialinc']="";
}
if ($op=="anotherway"){
	output("Deciding to look for another way, you decide to follow a trail");
	addnav("continue","runmodule.php?module=rainbowquest&op=chase2");
}
if ($op=="crosslog"){
	switch(e_rand(1,3)) {
		case 1:
		output("You slowly put one foot on the log, testing your weight, when ... CRACK!! the log gives way and plummets to the bottom of the chasm.");
		output_notl("`n`n");
		output("Wow that was close, you don't feel to good.");
		$session['user']['hitpoints']=1;
		set_module_pref("section","chasm");
		addnav("Return to the Forest", "forest.php?");
		$session['user']['specialinc'] = "";
		break;
		case 2:
		output("`^You slowly put one foot on the log, it creaks slightly as you test your weight, deciding to take your chances you slowly start to cross the chasm.");
		output_notl("`n`n");
		output("`^There's a few ominous creaks, but after a few hairy moments you make it to the other side.  You can feel the adreneline pumping through your body.");
		apply_buff('adreneline',array(
				"name"=>"`^Adreneline",
				"rounds"=>12,
				"wearoff"=>"`&Your heart starts to slow!",
				"atkmod"=>1.2,
				"roundmsg"=>"`^Your heart is pumping the adreneline through your body!",
			));
		addnav("Continue on", "runmodule.php?module=rainbowquest&op=chasmcontinue");
		break;
		case 3:
		if ($session['user']['race']=="Felyne"){
			output("Looking across the bridge you see a group of playful Felyne's.. A smile comes to your face and you race over the chasm to join them. Wow you made it.");
			addnav("Continue on", "runmodule.php?module=rainbowquest&op=chasmcontinue");
		}
		if ($session['user']['race']<>"Felyne"){
			output("Looking across the bridge you see a group of playful Felyne's.. A mischievious smile lights up one's face and he whispers to his friends.  Watching from your place standing in the middle of the log, you see them approach.");
			output("Realising what they're up to, you quickly turn hoping to make it back to the other side...");
			output_notl("`n`n`n`n`n");
			output("You've almost made it back to safety when the log suddenly plummets to the bottom of the chasm, taking you with it.");
			output("Luckily you land on a cushion of soft moss, it somewhats cushions your fall... `2In other words you're not dead");
			output_notl("`n`n");
			output("You loose a few things in the fall, you've still got your health right?  Wrong!!");
			$session['user']['turns']=1;
			$session['user']['gold']=0;
			$session['user']['gems']-=1;
			$session['user']['hitpoints']=1;
			set_module_pref("section","chasm");
			addnav("Return to the Forest", "forest.php?");
			$session['user']['specialinc'] = "";
		}
		break;

	}
}
if ($op=="unicorn"){
	output("`^Don't you know your Unicorn legends....");
	output_notl("`n`n");
	output("`^You're dead!!  Gouged to death by the fierce Unicorns horn and hooves");
	output("You lose 10% of your experience.");
	output("You may continue playing again tomorrow.");
	$session['user']['alive']=false;
	$session['user']['hitpoints']=0;
	$session['user']['experience']*=0.9;
	$gold = $session['user']['gold'];
	$session['user']['gold'] = 0;
	set_module_pref("runaway",0);
	set_module_pref("section",0);
	//loose the bracelet they've earnt
    if (get_module_pref("bracelet")==1){
	    set_module_pref("bracelet",0);
	    output("Your bracelet is now ground into the dirt by the unicorns hooves, you'll have to earn another.");
    }
	if (get_module_pref("charms")<>0){
	//now they loose the charms attached to their jewelry
	set_module_pref("charms",0);
	output("`b`^You've also lost any charms you've gathered`b");
	}
	addnav("The Shades","shades.php");
	addnews("The body of %s, well what was left of them, was discovered near a lagoon.",$session['user']['name']);
	$session['user']['specialinc'] = "";
}
if ($op=="chasmcontinue"){
	if ($session['user']['race']=="Felyne"){
		output("You gambol and play with your new found friends for a while");
	}elseif ($session['user']['race']<>"Felyne"){
		output("Glancing back at the chasm you release a sigh of relief.");
	}
	output("As you continue on your way you hear a strange sound, like a loud thrumming, coming closer, oddly it seems to be coming from above you.  Looking up to your astonishment you see `&Pegasus`0.");
	output_notl("`n`n");
	output("A dim memory of how to catch the flying horse comes to you, Are you up for the challenge?");
	addnav("Try to Capture Pegasus", "runmodule.php?module=rainbowquest&op=trypegasus");
	addnav("I don't think so", "runmodule.php?module=rainbowquest&op=ignorepegasus");
}
if ($op=="ignorepegasus"){
	output("Shaking your head, you decide that you're really can't remember clearly how to catch Pegasus.");
	output_notl("`n`n");
	output("You decide to head back to the main section of the forest, and continue on from here another day.");
	set_module_pref("section", "ignore");
	addnav("Return to the Forest", "forest.php?");
	$session['user']['specialinc'] = "";
}
if ($op=="trypegasus"){
	$bridle=get_module_pref("bridle");
	if ($bridle==1){
		$bridletype="Gold";
	}
	if ($bridle==2){
		$bridletype="Silver";
	}
	if ($bridle==0){
		$bridletype="Old";
	}
	if ($bridle==3){
		$bridletype="Bronze";
	}
	if ($bridle==4){
		$bridletype="Platinum";
	}
	if ($bridle==5){
		$bridletype="Leather";
	}
	if ($bridle==6){
		$bridletype="Pyrite";
	}
	output("Deciding you know how to catch the famed winged horse, you head up the trail following him discretely");
	output("You watch silently as `&Pegasus`0 comes to land near a well, and lowers his head to drink. Pulling the %s Bridle from your pack you slowly approach him",$bridletype);
	if ($bridle==1){
		output("Mesmerised by the Gold Bridle in your hand, `&Pegasus`0 allows you to capture him");
		addnav("Fly on", "runmodule.php?module=rainbowquest&op=flyon");
	}
	if ($bridle<>1){

		output("`&Pegasus `0lifts his head and seeing the %s Bridle in your hands, he starts in fright and takes off to the skies.  You chose unwisely.",$bridletype);
		set_module_pref("section","ignore");
		addnav("Return to the Forest", "forest.php?");
		$session['user']['specialinc'] = "";
	}
}
if ($op=="flyon"){
	output("You climb up on `&Pegasus'`0 back and he quickly takes to the skies. Flying low over a castle, you spot a flash of `2green`0 quick as a flash `&Pegasus `0swoops down and you scoop up the `2Leprechaun`0");
	output_notl("`n`n");
	output("Landing lightly, you slide from `&Pegasus'`0 back, the struggling `2Leprechaun`0 in your grasp, not even daring to blink, you quickly release `&Pegasus `0and thank him for a job well done");
	addnav("Continue","runmodule.php?module=rainbowquest&op=rainbowquest");
}
if ($op=="chase4"){
	output("You come upon a large castle, you decide to explore");
	addnav("Enter Castle", "runmodule.php?module=rainbowquest&op=entercastle");
	addnav("Knock on Door", "runmodule.php?module=rainbowquest&op=knock");
	addnav("Look around Outside", "runmodule.php?module=rainbowquest&op=lookcastle");
	$session['user']['specialinc'] = "";
}
if ($op=="entercastle"){
	output("Pushing the door of the castle open, you enter the main entrance hall, only to find everyone asleep.  Sound familiar?");
	addnav("Climb Stairs","runmodule.php?module=rainbowquest&op=castlestairs");
	addnav("Descend to the Dungeon","runmodule.php?module=rainbowquest&op=dungeon");
}
if ($op=="castlestairs"){
	output("You slowly climb the stairs leading up a winding staircase to a tower, there is a lovely princess asleep in one of the rooms, which room will you pick?");
	addnav("Door one","runmodule.php?module=rainbowquest&op=doorchoose");
	addnav("Door two","runmodule.php?module=rainbowquest&op=doorchoose");
	addnav("Door three","runmodule.php?module=rainbowquest&op=doorchoose");
	addnav("Door four","runmodule.php?module=rainbowquest&op=doorchoose");
	addnav("Door five","runmodule.php?module=rainbowquest&op=doorchoose");
}
if ($op=="doorchoose"){
	$choose=e_rand(1,10);
	switch($choose){
		case 1:

		output("You push open the door to find yourself face to face with a Giant Ogre, Looks like you'll have to fight him");
		addnav("attack","runmodule.php?module=rainbowquest&op=attack");
		set_module_pref("fightnum",4);
		break;
		case 2:
		output("You've found the beautiful princess, Congratulations.  You know you're almost at the end.  Waking her she rewards you with a hug (nope not a kiss), and you continue on your way");
		addnav("Castle gardens", "runmodule.php?module=rainbowquest&op=lookcastle");
		break;
		case 3:
		case 4:
		case 9:
		case 10:
		case 5:
		case 6:
		case 7:
		case 8:
		output("The room is empty, try again");
		addnav("Try again","runmodule.php?module=rainbowquest&op=doorchoose");
		addnav("Leave", "runmodule.php?module=rainbowquest&op=chase4");
		break;
	}
}
if ($op=="dungeon"){
	$choose=e_rand(1,10);
	switch($choose){
		case 1:

		output("You push open the door to the dungeon to find yourself face to face with a Giant Ogre, Looks like you'll have to fight him");
		addnav("attack","runmodule.php?module=rainbowquest&op=attack");
		set_module_pref("fightnum",5);
		break;
		case 2:
		output("You've found a secret passage, Congratulations.  You know you're almost at the end.");
		addnav("Secret Passage", "runmodule.php?module=rainbowquest&op=lookcastle");
		break;
		case 3:
		case 4:
		case 9:
		case 10:
		case 5:
		case 6:
		case 7:
		case 8:
		output("This section of the dungeon contains dried bones and some rats, there must be something more, try again");
		addnav("Try again","runmodule.php?module=rainbowquest&op=dungeon");
		addnav("Leave", "runmodule.php?module=rainbowquest&op=chase4");
		break;
	}
}
if ($op=="knock"){
	output("You knock on the castle doors, you wait a while.");
	output_notl("`n`n");
	output("You twiddle your thumbs");
	output_notl("`n`n");
	output("You scuff your boots on the ground");
	output_notl("`n`n");
	output("You whistle to yourself");
	output_notl("`n`n");
	output("You knock again");
	output_notl("`n`n");
	output("You tap your fingers on your leg");
	output_notl("`n`n");
	output("You look around");
	output_notl("`n`n");
	output("You knock AGAIN!");
	output_notl("`n`n");
	output("You start to stare at a bumblebee");
	addnav("Enter Castle", "runmodule.php?module=rainbowquest&op=entercastle");
	addnav("Knock on Door", "runmodule.php?module=rainbowquest&op=knock2");
	addnav("Look around Outside", "runmodule.php?module=rainbowquest&op=lookcastle");
}
if ($op=="knock2"){
	output("You surely aren't this silly? Just for that, I have to do something, what to do, umm, let me see, ahh I know (The writer now stamps something in the middle of your forehead)");
	set_module_pref("dupits",1);
	addnews("%s`0 has had `@DupitS `0stamped on their forehead",$session['user']['name']);
	addnav("Enter Castle", "runmodule.php?module=rainbowquest&op=entercastle");
	addnav("Look around Outside", "runmodule.php?module=rainbowquest&op=lookcastle");
}
if ($op=="lookcastle"){
	$choose=e_rand(1,10);
	output("You explore the gardens");
	switch($choose){
		case 1:
		case 3:
		case 4:
		case 9:
		case 10:
		output("You stop to smell a flower.");
		addnav("Try again","runmodule.php?module=rainbowquest&op=lookcastle");
		break;
		case 2:
		output("You wander around some flower beds when you catch a flash of `2green`0 out of the corner of your eye, turning you tackle the `2Leprechaun`0 to the ground.");
		addnav("Continue","runmodule.php?module=rainbowquest&op=rainbowquest");
		break;
		case 5:
		case 6:
		case 7:
		case 8:
		output("Nothing of Interest in this section of the garden, perhaps elsewhere?");
		addnav("Try again","runmodule.php?module=rainbowquest&op=lookcastle");
		addnav("Leave", "runmodule.php?module=rainbowquest&op=chase4");
		break;
	}
}
if ($op=="rainbowquest"){
	output("`b`c`^YOU'VE DONE IT!!`b`c");
	output_notl("`6You've caught that pesky `2Leprechaun`6.  Bowing to your perserverance, he rewards you with a small charm which when added to a bracelet (you did find the bracelet didn't you?) adds to your strength");
	$session['user']['attack']+1;
	increment_module_pref("charms")+1;
	addnews("%s has caught the `2Leprechaun`0",$session['user']['name']);
	addnav("Return to the Forest", "forest.php?");
	$session['user']['specialinc'] = "";
	set_module_pref("section","");
	set_module_pref("bridle",0);
}
	page_footer();
}
function cottage_outcome(){
	global $session;
	switch(e_rand(1,3)) {
	case 1:
	output("You knock on the door and after hearing a slight scuffling sound, the door is opened by a rather large `qbear`^ slightly startled you manage to stammer your need for directions to him");
	output_notl("`n`n");
	output("In a rather gruff voice the bear tells you that you're the prime suspect in a porridge stealing scheme, he grabs you by the scruff of the neck and drags you inside the cottage.");
	output_notl("`n`n");
	output("After a few hours of being questioned by the `qbear `^and his family, they decide that you're innocent and let you go, offering you a gem in compensation for your lost time.");
	$session['user']['gems']+=1;
	$session['user']['turns']-=1;
	break;
	case 2:
	output("From inside the cottage you hear several voices, after the door is opened to your knocking, you are surrounded by what you at first believe to be a hoard of children, upon closer inspection, you realise they are in fact dwarves.");
	output_notl("`n`n");
	output("Explaining your dilemma to them, they soon point you in the right direction, after giving you a bite of some fruit (an apple would you believe), you feel terrible");
	apply_buff('apple',array(
				"name"=>"`4The Apple",
				"rounds"=>10,
				"wearoff"=>"`&Your stomach ache feels better!",
				"atkmod"=>0.95,
				"roundmsg"=>"`^The Apple makes your stomach ache, Seven Dwarves and an apple, you live you learn!",
			));
	break;
	case 3:
	output("You knock on the cottage door and from inside you hear a small tentative `6\"Who's there?\" \"A lost Traveller who needs directions\" `^You reply");
	output_notl("`n`n");
	output("The voice replies`6\"I'm not letting you in, not by the hair of my chinny, chin, chin\" `6you find yourself replying `6\"I'll huff and I'll puff...\" `^until you realise what you're doing");
	output_notl("`n`n");
	output("Convinced you're the big bad wolf, the occupants of the house will neither open the door, nor help you with directions.  You turn to wander off, just as a cauldron of boiling water pours down on you.");
	apply_buff('boilingwater',array(
				"name"=>"`%Three Pigs",
				"rounds"=>10,
				"wearoff"=>"`&Your hair is starting to grow back!",
				"atkmod"=>0.95,
				"roundmsg"=>"`^You can still feel the scalding sensation!",
			));
	break;
}
}
function lagoonswim_outcome(){
	global $session;
	switch(e_rand(1,3)) {
	case 1:
	output("You hit your head on a rock and nearly drown, you loose some hitpoints");
	$hploss = $session['user']['hitpoints']*0.05;
	$session['user']['hitpoints']-=$hploss;
	break;
	case 2:
	output("You land on a rock, which splits open and you find 3 gems");
	$session['user']['gems']+=2;
	break;
	case 3:
	output("You almost land on a rock, thanking your lucky stars you can feel the adreneline surge through your body");
	apply_buff('rock',array(
				"name"=>"`7Lucky Stars",
				"rounds"=>15,
				"wearoff"=>"`&Your memory fades!",
				"atkmod"=>1.05,
				"roundmsg"=>"`^Remembering your luck, you feel the adreneline surge through your body!",
			));
	break;
}
}
?>