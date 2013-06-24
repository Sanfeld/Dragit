<?php
// Robert Riochas
// maddrio.com
// 29 APRIL 2006
function batteredshrine_getmoduleinfo(){
	$info = array(
		"name"=>"Battered Shrine",
		"version"=>"1.1",
		"author"=>"`2Robert",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?topic=2215.0",
		"settings"=>array(
			"Battered Shrine - Settings,title",
			"gemcost"=>"Cost in gems to get a Squire?,range,1,25,1|1",
		),
		"prefs"=>array(
			"Battered Shrine - User Prefs,title",
			"totalgems"=>"Total times player gave a gem?,int",
		),
	);
	return $info;
}

function batteredshrine_install(){
	module_addeventhook("forest","return 100;");
	return true;
}

function batteredshrine_uninstall(){
	return true;
}

function batteredshrine_dohook($hookname,$args){
	return $args;
}

function batteredshrine_runevent($type){
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:batteredshrine";
	if ($session['user']['sex']==0){ $who="m'lord";}else{ $who="m'lady";}
	$gemcost = get_module_setting("gemcost");
	$op = httpget('op');
	
	if ($op=="" || $op=="search"){
		output("`n`n`2 You encounter a battered Shrine in the forest. ");
		output("`n`n Where upon a Knight comes out to greet you. ");
		output("`n`n`6 $who these are difficult times, a dragon has ravished this shrine as you can see. ");
		output("`n`n I beg of you, $who ...please, for a gem to help restore our home, ");
		output("`n`n I shall grant you use of one of my Squire's. ");
		addnav(" Battered Shrine ");
		addnav("Give a gem", $from."op=give");
		addnav("Don't give a gem", $from."op=dont");
	}elseif ($op=="give"){
		$session['user']['specialinc'] = "";
		if ($session['user']['gems']>=$gemcost){
			output("`n`n`2 You see the carnage the dragon did and agree to help the Knight. ");
	 		output("`n`n You give %s of your gems to the Knight and he calls for a Squire. ",$gemcost);
			output("`n`n A handsome young man, no more than 17 years of age, ");
			output("`n`n comes running with a Bow in one hand and a fistful of arrows in the other. ");
			$session['user']['gems']-=$gemcost;
			increment_module_pref("totalgems",1);
			debuglog("gave $gemcost gem to knight in battered shrine");
			switch(e_rand(1,6)){
			case 1: case 4:
			output("`n`n`6 This is Richard, He is a fine lad $who, and my finest archer!");
			output("`n`n We are short on supplies $who and we only have 16 arrows. ");
			output("`n`n When the arrows are gone, my Squire will leave you. ");
			$richard = array(
				"name"=>"`2Squire Richard",
				"rounds"=>16,
				"atkmod"=>1.2,
				"wearoff"=> "Richard is out of arrows and bids you farewell.",
				"roundmsg"=>"Squire Richard carefully aims and fires an arrow at your enemy!",
				"schema"=>"module-batteredshrine",
				);
			apply_buff('richard',$richard);
			break;
			case 2: case 5:
			output("`n`n`6 This is Henry, He is a fine lad $who, and he is a fine archer.");
			output("`n`n We are short on supplies $who and we only have 14 arrows. ");
			output("`n`n When the arrows are gone, my Squire will leave you. ");
			$henry = array(
				"name"=>"`2Squire Henry",
				"rounds"=>14,
				"atkmod"=>1.2,
				"wearoff"=> "Henry is out of arrows and bids you farewell.",
				"roundmsg"=>"Squire Henry carefully aims and fires an arrow at your enemy!",
				"schema"=>"module-batteredshrine",
				);
			apply_buff('henry',$henry);
			break;
			case 3: case 6:
			output("`n`n`6 This is Patick, He is a fine lad $who, and he is a good archer.");
			output("`n`n We are short on supplies $who and we only have 12 arrows. ");
			output("`n`n When the arrows are gone, my Squire will leave you. ");
			$patrick = array(
				"name"=>"`2Squire Patrick",
				"rounds"=>12,
				"atkmod"=>1.2,
				"wearoff"=> "Patrick is out of arrows and bids you farewell.",
				"roundmsg"=>"Squire Patrick carefully aims and fires an arrow at your enemy!",
				"schema"=>"module-batteredshrine",
				);
			apply_buff('patrick',$patrick);
			break;
			}
		}else{
			output("`n`n`2 You see the carnage the dragon did and agree to help the Knight. ");
			output("`n`n However, upon reaching into your pouch, you cant find a gem! ");
			output("`n`n Terribly embarrassed, you cower and bid the Knight the best of luck and return to the forest. ");
		}
	}else{
		output("`n`n`2 You have no time nor the funds to be charitable to those in desperate need and make pleads for help. ");
		output("`n`n You mutter some lame unbelievable excuse and return to the forest. ");
		$session['user']['specialinc'] = "";
	}
}
function batteredshrine_run(){
}
function richard_run(){
}
function henry_run(){
}
function patrick_run(){
}
?>