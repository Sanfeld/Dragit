<?php
function wiseman_getmoduleinfo(){
	$info = array(
		"name" => "Wise Man",
		"author" => "`b`&Ka`6laza`&ar`b",
		"version" => "1.0",
		"Download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1070",
		"category" => "Village Specials",
		"description" => "Special to align the notes",
		"requires"=>array(
            "arena" => "1.3|`b`&Ka`6laza`&ar`b",
            ),
		);
	return $info;
}
function wiseman_install(){
	module_addeventhook("village", "return 100;");
	return true;
}
function wiseman_uninstall(){
	return true;
}
function wiseman_runevent(){
	global $session;
	page_header("The Wise Man");
	$u=&$session['user'];
	$op=httpget('op');
	$u['specialinc']="module:wiseman";
	if($op=="" || $op=="search"){
		output("You see a mountain rising up ahead of you, you can vaguely make out the outline of a cave in the side.`n`n");
		addnav("What do You do");
		addnav("Climb up to the cave","runmodule.php?module=wiseman&op=climb");
		addnav("Ignore it","village.php");
	}
	$u['specialinc']="";
	page_footer;
}
function wiseman_run(){
	global $session;
	page_header("The Wise Man");
	$u=&$session['user'];
	$op=httpget('op');
	$u['specialinc']="";
	if ($op=="climb"){
		output("You climb up to the entrance of the cave, peering into the darkness, you hear a strange shuffling sound, followed by a dull clunk.. repeated a few times, as an old man shuffles his way out of the cave.");
		output("`n`nPointing his staff at you he speaks `6My child, I offer you my wisdom, long have I lived within this cave, only a few are able to find it, and even then only if I will it.  You may ask me a question, I will answer it for you, although not all my answers are easily understood");
		output("`n`n`0Thinking hard you decide to");
		addnav("What Will You Ask About");
		addnav("Ingredients","runmodule.php?module=wiseman&op=ingredients");
		addnav("Potions","runmodule.php?module=wiseman&op=potions");
		addnav("How they work","runmodule.php?module=wiseman&op=how");
		addnav("Village");
		addnav("Return","village.php");
	}elseif($op=="ingredients"){
		output("You ask a general question about ingredients`n`n");
		$c=e_rand(1,4);
		if ($c==1){
			output("The old man looks at you blankly");
		}elseif ($c==2){
			output("The old man nods thoughtfully before replying `6Ahh yes, there are only 5 ingredients my child, it is however, the order you mix them in that matters.");
		}elseif($c==3){
			output("The old man looks amazed at your question before replying `6The basilisk venom is muchly prized my child, value it highly");
		}elseif($c==4){
			output("`6Beware the Witches Garden, all is not as it seems, and she is not known to deal nicely with those who cross her");
		}
		addnav("Village","village.php");
	}elseif ($op=="potions"){
		output("You ask a question about potions");
		$c=e_rand(1,4);
		if ($c==1){
			output("Nodding wisely the man replies `6There are many notes lost by travellers, and mayhap dropped by myself to help some out.  Take note of them, for they will give you a powerful potion if used correctly");
		}elseif($c==2){
			output("Nodding the man looks at you `6Mayhap, you have already found, that the notes found in the forest, do indeed match, they are indeed two halves, there is a way to match them, the first in english, the second in an ancient language, not oft heard nor practised anymore");
		}elseif($c==3){
			output("`6Yes, many potions appear to be weak, in their lower states, however, the most powerful one of all, doth appear to be but a weakened shadow of itself, beware the Flames for they shalt burn eternal");
		}elseif($c==4){
			output("The old man seems to ponder your question for some time, before nodding and seeming to reach some decision `6To get thee started I wilt give thee, one potion, use it wisely `4Strength, take Monkhood and Basilisk Venom then add another Monkhood and Basilisk Venom and Mandrake to make Vires");
		}
		addnav("Village","village.php");
	}elseif($op=="how"){
		output("You wonder aloud how they work");
		$c=e_rand(1,4);
		if ($c==1){
			output("`6Some like Vires will increase your strength my child, or even better yet try Valde Vires");
		}elseif($c==2){
			output("`6There are a few that will hurt your opponent, try hate, although if they have love, the effect will be negated");
		}elseif($c==3){
			output("`6Mayhap you wish to fly my child, in which case try Navi");
		}elseif($c==4){
			output("`6Although to be supreme and with the most effect try working on Flamma of Abyssus");
		}
		addnav("Village","village.php");
	}
	page_footer();
}
?>