<?php

function entwoods_getmoduleinfo(){
	$info = array(
		"name"=>"Ent Grove",
		"version"=>"1.0",
		"author"=>" John McNally,based on Trollbridge by`%Sneakabout`0",
		"category"=>"Forest Specials",

		"settings"=>array(
			"likeelfs"=>"Does the Ent like elves?,bool|1",
			"hatetrolls"=>"Does the Ent attack trolls on sight?,bool|1",
			 "mindk"=>"How many DK's before a player encounters the Ent?,int|2",
		),
		"requires"=>array(
			"raceelf"=>"1.0|by Eric Stevens",
		),
	);
	return $info;
}

function entwoods_install(){
	module_addeventhook("forest", "return 40;");
	return true;
}

function entwoods_uninstall(){
	return true;
}

function entwoods_dohook($hookname,$args){
	return $args;
}

function entwoods_runevent($type,$link){
	global $session;
	$from = $link;
	$city = httpget("city");
	$session['user']['specialinc'] = "module:entwoods";

	$op = httpget('op');
	$fight = httpget('fight');
	switch($op){
	case "cross":
	$mindk=get_module_setting("mindk");
		if ($session['user']['dragonkills'] >$mindk
			) {
			output("`2When you are about halfway across the grove, a huge tree moves of it's own accord, and your realize that your face to face with an Ent who blocks your path!`n`n");
			if ($session['user']['race']=="Elf" &&
					get_module_setting("likeelfs")) {
				output("He looks a little sheepish when he notices that you're an elf and you have a short, awkward conversation about the health of trees, and the dangers in the forest before you run out of small talk.");
				output("After a short silence he wishes you well in your travels and you go on your way.");
				$session['user']['specialinc'] = "";
			} elseif ($session['user']['race']=="Troll" &&
					get_module_setting("hatetrolls")) {
				output("Seeing that you are a slobbering, vile troll the Ent dispenses with any chatter and leaps to the attack,trying to pound you into the ground with his branch like limbs!");
				addnav("Fight the Ent", $from."fight=entfight");
			} else {
				output("The Ent looks disdainful as he advances on you, and says,");
				output("`6\"Did you not read the `4No Tresspassing `6sign back there?. What do you have to say in your defense?`2\"`n`n");
				output("`&What will you say?");
				addnav("Don't Kill Me...");
				addnav("...I can't read!", $from."op=read");
				addnav("...I love the woods!", $from."op=charm");
				addnav("...I didn't see any sign!", $from."op=see");
				addnav("Look, a woodpecker!", $from."op=woodp");
				addnav("Other");
				addnav("Run like the wind", $from."op=run");
				addnav("Fight the Ent", $from."fight=entfight");
			}
		} else {
			output("`2The forest seems to close in around you as you pass, you'd swear the trees are talking to each other.");
                        output("`2Feeling nervous, you stop and say a little prayer.");
                        output("`2 You notice a small sapling just getting it's start on the forest floor, and give it a little water from your canteen.");
                        output("`2The strange whispering seems to stop, and the forest seems a little less scary as you make your way onwards.");
			$session['user']['specialinc'] = "";
		}
		break;
	case "read":
		$brains=$session['user']['dragonkills'];
		if ($brains<e_rand(1,100)) {
			output("`2The ent laughs a little, a deep throaty sound that makes the ground shake, and looks at your more closely.");
			output("`n`n\"`6Well... you're right! You barely have enough brains to walk upright.");
                        output("`6 You may leave this place, but you are never to return!.\"");
			output("`n`n`2He waves you to pass through the grove, and you count your blessings as you make your way to safer parts of the forest.");
			$session['user']['specialinc'] = "";
		} else {
			output("`2The Ent grumbles a little eyeing you closely.");
			output("\"`6If you're too stupid to read, then you're a danger to everything in the forest.`2\"");
			output("He surrounds you with his branch like limbs - you must attack!");
			addnav("Fight the Ent", $from."fight=entfight");
		}
		break;
	case "charm":
		output("`2The Ent looks at you closely, contemplating your words.");
		if ($session['user']['charm']>e_rand(1,50)) {
			output("\"`6I can see that you are a friend to the forest, a worthy creature such as you must be allowed to pass.");
                        output("My most sincere apologies for trying to.. err.. kill you.");
                        output("Go on your way with my blessings.`2\"");
			output("`n`nRelieved, you make your way onwards with the trees singing all around you.");
			$session['user']['specialinc'] = "";
		} else {
			output("\"`2You feel a rumbling beneath your feet, and realize that it's originating from the Ent before you.");
                        output("`n`n`6 \"Lies! You are no friend to the trees, the woods will be a better place without your presence.\"");
			output("He surrounds you with his branch like limbs - you must attack!");
			addnav("Fight the Ent", $from."fight=entfight");
		}
		break;
	case "see":
		output("`2The Ent looks at you, the expression on his face doesn't exactly fill you with confidence.");
		$taste=e_rand(1,5);
		if ($session['user']['race']=="Troll") $taste++;
		if ($session['user']['race']=="Dwarf") $taste+=1;

		if ($taste>3) {
			output("\"`6If you're so blind to your surroundings that you did not see my sign, then maybe you won't see your end coming either.`2\"");
			output("He surrounds you with his branch like limbs - you must attack!");
			addnav("Fight the Ent", $from."fight=entfight");
		} else {
			output("\"`6You're right! The wind must have blown it over.");
                        output("`6Go on, get away with you, it wouldn't be fair to kill you now.`2\"");
			output("`n`nYou hurry on your way, thankful that he didn't notice you reading the sign earlier.");
			$session['user']['specialinc'] = "";
		}
		break;
	case "woodp":
		if (e_rand(0,2) == 0) {
			output("`2The Ent whirls with surprising speed, shaking all of his mighty limbs, trying to find the pesky bird!");
			output("You take this chance to cut across the grove, hoping you'll be long gone before he realizes what happened.");
			$session['user']['specialinc'] = "";
		} else {
			output("`2He whirls around once, but seeing that there's no woodpecker he turns his attention back to you, the deep rumble coming from him tells you that he is not happy.");
			output("`n`n\"`6LIES! You dare to try and trick me, you will barely live long enough to regret that decision.\"");
			output("`2He surrounds you with his branch like limbs - you must attack!");
			addnav("Fight the Ent", $from."fight=entfight");
		}
		break;
	case "run":
		output("`2Not wanting to take on this incredible being, you turn on your heel and run back the way you came.!");
		output("However, the trees around you quickly close in, and now you are not just facing one ent, but six!");
                output("They pick you up and begin playing badmitton with your helpless body.");
		if (e_rand(1,7)>=6) {
			output("All of your gold falls out of your pockets on the way down.");
			output("Your broken body is left on the forest floor for scavengers to eat.");
			output("`n`n`%You have died!");
			output("Your soul drifts to the shades.");
			$session['user']['specialinc'] = "";
			$session['user']['experience']*=0.9;
			debuglog("lost " . $session['user']['gold'] . " trying to flee from the Ents.");
			$session['user']['gold']=0;
			$session['user']['hitpoints'] = 0;
			$session['user']['alive'] = false;
			addnews("%s`7's body was found battered and broken in the forest near a `2NO TRESPASSING `7sign.`0",$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			output("`n`n`2Your broken body is thrown clear of the grove and lands ungracefully in a tree.");
                        output("`n`n A fairy, whom remembers you for giving her a gem, sprinkles some `!fairy dust over you.");
                        output("`n`n`&The dust heals some of your wounds!");
			output("`n`n`2You recover enough strength to climb down from the tree and limp to a safer section of the forest.");
			$session['user']['hitpoints']*=0.5;
			$session['user']['specialinc'] = "";
		}
		break;
	case "return":
		output("`2Looking closely at the fallen `4NO TRESSPASSING! `2sign, you realize that there are some bones underneath it.");
                output("`2Unsure of what sort of beings lurk here, you decide that it would be safer to seek adventure elsewhere.");
		$session['user']['specialinc'] = "";
		require_once("lib/villagenav.php");
		villagenav();
		break;
	default:
		if (!$fight) {
			output("`2As you walk through the forest, you notice that the trees have gotten much taller and thicker than usual, you realize that this must be a very old section of the woods.");
			output("`2You notice a stone slab on the ground that looks a little like a fallen tombstone, lifting it, you read the words `4NO TRESSPASSING! `2carved into its smooth surface.");
			output("`n`n`&What will you do?");
			addnav("Ignore the sign", $from."op=cross");
			addnav(array("Return to %s",$session['user']['location']), $from."op=return");
		}
		break;
	}
	switch($fight){
	case "entfight":
		$badguy = array(
			"creaturename"=>translate_inline("Arboral King of the Ents"),
			"creaturelevel"=>$session['user']['level']+2,
			"creatureweapon"=>translate_inline("Branch like Limbs"),
			"creatureattack"=>round($session['user']['attack']*1.2, 0),
			"creaturedefense"=>round($session['user']['defense']*0.5, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*2.1, 0),
			"diddamage"=>0,
			"type"=>"forest"
		);
		$session['user']['badguy']=createstring($badguy);
		$battle=true;
		// Drop through
	case "entfighting":
		require_once("lib/fightnav.php");
		include("battle.php");
		if ($victory ||
				$badguy['creaturehealth'] <
				($session['user']['maxhitpoints']/4)) {
			output("`2With a great heave of it's mighty form, the Ent pulls back, no longer blocking your way.");
			output("You feel the booming vibrations of other nearby Ents, and get out of there before another one comes after you.");
			$expgain=round($session['user']['experience']*(e_rand(1,3)*0.025)+100);
			$session['user']['experience']+=$expgain;
			output("`n`n`&You gain %s experience from this fight!",$expgain);
			if ($session['user']['hitpoints'] <= 0) {
				output("`n`n`&Looking around quickly, you find some grass and moss to stop the flow of blood from your wounds, saving yourself from bleeding to death!.");
				$session['user']['hitpoints'] = 1;
			}
			$session['user']['specialinc'] = "";
		} elseif ($defeat) {
			output("`2Your vision blacks out as Arboral's great hands crush your bones.");
			output("As your consciousness fades, you wonder if things would have been different, had you hugged more trees during your lifetime.`n`n");
			output("`%You have died! You lose 10% of your experience, and your gold is stolen by some playful fairies!");
			output("Your soul drifts to the shades.");
			debuglog("was killed by an Ent and lost ".$session['user']['gold']." gold.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive'] = false;
			addnews("`2The broken body of %s`2, was found next to a `4NO TRESSPASSING `2 sign in the deep woods.",$session['user']['name']);
			$session['user']['specialinc'] = "";
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,true,$from."fight=entfighting");
		}
		break;
	}
}

function entwoods_run(){
}
?>
