<?php

function bluspring_getmoduleinfo(){
	$info = array(
		"name"=>"Bluspring's Encounter",
		"author"=>"Chris Vorndran",
		"version"=>"1.0",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=84",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Allows a player to best Bluspring and win the chance to challenge their master again.",
		"settings"=>array(
			"Bluspring's Encounter Settings,title",
				"chance"=>"Chance that a player will be able to fight their master once again?,range,1,100,1|50",
			),
		"prefs"=>array(
			"Bluspring's Encounter Prefs,title",
				"seen"=>"Has this user seen Bluspring today?,bool|0",
			),
		);
	return $info;
}
function bluspring_install(){
	module_addeventhook("forest","return 100;");
	module_addhook("newday");
	return true;
}
function bluspring_uninstall(){
	return true;
}
function bluspring_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "newday":
			set_module_pref("seen",0);
			break;
		}
	return $args;
}
function bluspring_runevent(){
	global $session;
	$session['user']['specialinc'] = "module:bluspring";
	$op = httpget('op');
	$from = "forest.php?";
	$games = array(
		1=>"game of chess",
		"drinking game",
		"spitting contest",
		"caber toss",
		"dwarf tossing match"
	);
	$games = translate_inline($games);
	
	switch ($op){
		case "": case "search":
			if ($session['user']['seenmaster'] && !get_module_pref("seen")){
				output("`3As you stride into a clearing, an odd rustling in the bushes catches your attention.");
				output("All of a sudden, Bluspring walks out from behind a tree and acknowledges your presence.");
				addnav("Talk to Bluspring",$from."op=talk");
				addnav("Leave",$from."op=leave");
			}else{
				$session['user']['specialinc'] = "";
				output("`3You hear an odd rustling in the bushes, but decide to dismiss it.");
				output("You have far too much on your mind to be dealing with other things.");
			}
			break;
		case "talk":
			$num = e_rand(1,5);
			set_module_pref("seen",1);
			output("`3Bluspring smiles and places her hand on your shoulder.");
			output("\"`#If ye can best me in a %s, then I shall allow you to fight your master once again...`3\"",$games[$num]);
			addnav("Sure",$from."op=yes&number=".$num);
			addnav("I'll Pass",$from."op=leave");
			break;
		case "yes":
			$session['user']['specialinc'] = "";
			$num = httpget('number');
			output("`3Bluspring begins to set up for the %s, as you wait patiently.",$games[$num]);
			output("Bluspring finishes and stands, taking her position.`n`n");
			if (e_rand(1,100) <= get_module_setting("chance")){
				output("`3After many grueling hours, Bluspring concedes defeat and bows to you.");
				output("\"`#Aye, ye hath bested me... I shall inform my apprentices of your newfound chance.");
				output("They will let you fight them once more...`3\" he finishes reluctantly.");
				$session['user']['seenmaster'] = 0;
			}else{
				output("`3Bluspring raises her fists in triumph, as she has beaten you.");
				output("\"`#Ye are such a wuss... no wonder you lost to my apprentices...`3\"");
			}
			break;
		case "leave":
			$session['user']['specialinc'] = "";
			output("`3Bluspring chuckles behind your back, as you make your way out of the clearing.");
			break;
	}
}
?>