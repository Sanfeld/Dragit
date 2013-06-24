<?php
// a bluff dragon fight
// not translator ready

function blufffight_getmoduleinfo(){
	$info = array(
		"name"=>"Bluff Fight",
		"category"=>"Forest Specials",
		"author"=>"Keith, debugged by DaveS",
		"version"=>"0.9",
		"download"=>"",
		"settings"=>array(
			"Bluff Fight Settings,title",
			"chancetofight"=>"Percent Chance to Fight Dragon,int|50",
			"maxgold"=>"Max gold*level*((dragonkills+1)*0.5)  for trying to fight,int|500",
			"mingold"=>"Min gold*level*((dragonkills+1)*0.5)  for trying to fight,int|100",
			"maxgems"=>"Max gems*level*(dragonkills+1)  for trying to fight,int|2",
			"mingems"=>"Min gems*level*(dragonkills+1)  for trying to fight,int|1",
			"stolemaxgold"=>"Max gold*level  for trying to grab,int|20",
			"stolemingold"=>"Min gold*level  for trying to grab,int|5",
		),
	);
	return $info;
}

function blufffight_install(){
	module_addeventhook("forest","return 100;");
	return true;
}

function blufffight_uninstall(){
	return true;
}

function blufffight_dohook($hookname,$args){
	return $args;
}

function blufffight_runevent($type){
	global $session;
	$session['user']['specialinc'] = "module:blufffight";
	$op = httpget('op');
	if ($op == "" || $op == "search"){
			output("`QAs you are walking through the forest you come up to a rock cliff, at the top you can see what looks like a thief's lair. ");
			output("You know there must be a fortune at the top but should you climb up to see?");
			addnav("Climb to Cave", "forest.php?op=climb");
			addnav("Return to the Forest", "forest.php?op=leave");
	}
	if ($op == "climb"){
			output("`QYou hurriedly climb up to it imagining what treasures you will find, but when you get to the cave you find that you were horribly wrong. ");
			output("There’s a fortune in the cave, but also the `@Green Dragon `Qherself!!!!`n`n…but she hasn't notice you yet. ");
			output("So you are about to turn and run as fast as can away from this place, but you don't want to leave all that treasure just lying there. ");
			output("You think you might be able to grab some of it without the dragon noticing you or you could rid the land of the dragon now and forever. ");
			addnav("Fight", "forest.php?op=fight");
			addnav("Grab and Run", "forest.php?op=grab");
			addnav("RUN", "forest.php?op=run");
	}
	if ($op == "fight"){
		$real = e_rand(1,100);
		if ($real >=get_module_setting("chancetofight")){
			output("`QYou charge in, %s drawn, ready to kill the dreadful creature... ", $session['user']['weapon']);
			output("But suddenly he start begging for mercy, and he starts feeling around on his belly. ");
			output("You look at him puzzled, not knowing what to do, you stand and watch him. ");
			output("After a second he unzips himself and you watch amazed as a tall scrawny man steps out of a dragon costume.`n  ");
			output("He explains that he had been scaring hunters and taking gold and gems, most of the time they were so scared that wouldn't be any trouble. ");
			output("He is impressed at you bravery and tells you to help yourself to his treasure; you'll need it to help defeat the `@Green Dragon`Q.`n`n`n");
			//calculate how much gold and gems to get
			$maxgold = get_module_setting("maxgold") * $session['user']['level'] * (($session['user']['dragonkills']+1)*0.5);
			$mingold = get_module_setting("mingold") * $session['user']['level'] * (($session['user']['dragonkills']+1)*0.5);
			$maxgems = get_module_setting("maxgems") * $session['user']['level'] * ($session['user']['dragonkills']+1);
			$mingems = get_module_setting("mingems") * $session['user']['level'] * ($session['user']['dragonkills']+1);
			$gold = e_rand($mingold, $maxgold);
			$gems = e_rand($mingems, $maxgems);
			$session['user']['gold'] = $session['user']['gold'] + $gold;
			$session['user']['gems'] = $session['user']['gems'] + $gems;
			output("You fill your pockets with %s gold and %s ",$gold,$gems);
			output("gems and thank the man as you leave.");
			$session['user']['specialinc']="";
			addnav("Return to the Forest", "forest.php?php");
		}else{
			$op="fight1";
		}
	}
	if ($op == "grab"){
		//calculate how much gold and gems to get
		$maxgold = get_module_setting("stolemaxgold") * $session['user']['level'];
		$mingold = get_module_setting("stolemingold") * $session['user']['level'];
		$gold = e_rand($mingold,$maxgold);
		$damage = e_rand(1, $session['user']['hitpoints']);

		output("`QYou run and as fast as you can start stuffing your pockets with the treasure. ");
		output("Before you can do anything a huge tail sweeps you out of the cave and off the edge of the cliff. ");
		output("The landing hurts %s points and you loose all but %s pieces to the treasure on your way down.", $damage, $gold);
		$session['user']['hitpoints']=$session['user']['hitpoints']-$damage;
		$session['user']['gold']=$gold;
		$session['user']['specialinc']="";
		addnav("Return to the Forest", "forest.php?php");
	}
	if ($op == "run"){
		output("`QYou run as fast as you can from that terrible creature.");
		$session['user']['specialinc']="";
		addnav("Return to the Forest", "forest.php?php");
	}
	if ($op == "leave"){
		output("`Qnot wanting to risk a run in with thieves you calmly walk back in to the forest, think about what might have been up there");
		$session['user']['specialinc']="";
		addnav("Return to the Forest", "forest.php?php");
	}

	if ($op=="fight1"){
		$badguy = array(
			"creaturename"=>"`@The Green Dragon",
			"creaturelevel"=>18,
			"creatureweapon"=>"Great Flaming Maw",
			"creatureattack"=>$session['user']['attack']*2,
			"creaturedefence"=>$session['user']['defence']*1.5,
			"creaturehealth"=>$session['user']['maxhitpoints']*3,
			"diddamage"=>0,
			"type"=>"dragon",
		);
		$session['user']['badguy']=createstring($badguy);

		$battle=true;
	}
	if ($battle){
		include("battle.php");
		if ($victory){
			output("`n`n");
			output("`QJust as you are about to make the final blow, the dragon starts to speak!!`n`n `2'Please don't kill me I'm not really the `@Green Dragon`2!!'`n`n ");
			output("`QYou hesitate, reflecting that this is the first time you've heard a dragon beg for mercy.`n`n");
			output("While you think on this, a man steps out of the skin of the `@Green Dragon`Q.  You look stunned for a moment.  Then you realize what really happened.`n`n");
			output("`2'See,'`Q he says, `2'I use this costume to scare travelers and warriors into giving me their gold. ");
			output("Here, help yourself to my treasure.'`n`n");
			$maxgold = get_module_setting("maxgold") * $session['user']['level'] * (($session['user']['dragonkills']+1)*0.5);
			$mingold = get_module_setting("mingold") * $session['user']['level'] * (($session['user']['dragonkills']+1)*0.5);
			$maxgems = get_module_setting("maxgems") * $session['user']['level'] * ($session['user']['dragonkills']+1);
			$mingems = get_module_setting("mingems") * $session['user']['level'] * ($session['user']['dragonkills']+1);
			$gold = e_rand($mingold, $maxgold);
			$gems = e_rand($mingems, $maxgems);
			$session['user']['gold'] = $session['user']['gold'] + $gold;
			$session['user']['gems'] = $session['user']['gems'] + $gems;
			output("`QYou fill your pockets with `^%s gold`Q and `5%s ",$gold,$gems);
			output("gems `Qand thank the man as you leave.");
			addnews("%s `2defeated the man posing as the `@Green Dragon`2 in the forest!!!",$session['user']['name']);
			$session['user']['specialinc']="";
			addnav("Return to the Forest", "forest.php?php");

		}elseif ($defeat){
			addnav("Daily news","news.php");
			$session['user']['alive']=false;
			debuglog("lost {$session['user']['gold']} gold when they were slain by the costumed Green Dragon");
			$session['user']['gold']=0;
			$session['user']['hitpoints']=0;
			$session['user']['badguy']="";
			$session['user']['specialinc']="";
			output("`b`&You have been slain by `@The Green Dragon`&!!!`n");
			output("`4All gold on hand has been lost!`n");
			output("You may begin fighting again tomorrow.");
		}else{
			require_once("lib/fightnav.php");
			fightnav(true,false);
		}
	}
}

function blufffight_fight(){
	$op = httpget("op");
	global $session;


}

function blufffight_run(){
}
?>
