<?php

function racesamu_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Samurai",
		"version"=>"1.0",
		"author"=>"`^Harry Balzitch",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/users/Harry%20B/racesamu.zip",
		"settings"=>array(
		"Samurai Race Settings,title",
		"minedeathchance"=>"Percent chance for Samurai to die in the mine,range,0,10,1|1",
		"gemchance"=>"Percent chance for Samurai to find a gem on battle victory,range,20,100,1|10",
		"gemmessage"=>"Message to display when finding a gem|`3The Samurai see's a `%gem`&!",
		"mindk"=>"How many DKs do you need before the race is available?,int|0",
		),
	);
	return $info;
}

function racesamu_install(){

	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("charstats");
	module_addhook("raceminedeath");
	module_addhook("battle-victory");
	module_addhook("creatureencounter");
	module_addhook("pvpadjust");
	module_addhook("racenames");
	return true;
}

function racesamu_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Samurai'";
	db_query($sql);
	if ($session['user']['race'] == 'Samurai')
	$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racesamu_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("racehuman")) {
	$city = get_module_setting("villagename","racehuman");
	}else{
	$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Samurai";
	switch($hookname){
	case "racenames":
	$args[$race] = $race;
	break;
	case "pvpadjust":
	if ($args['race'] == $race) {
		$args['creaturedefense']+=(2+floor($args['creaturelevel']/5));
		$args['creaturehealth']-= round($args['creaturehealth']*.20, 0);
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Fortunately your Samurai skills once again lets you escape.`n";
			$args['schema']="module-racesamu";
		}
		break;
	case "charstats":
		if ($session['user']['race']==$race){
			addcharstat("Vital Info");
			addcharstat("Race", translate_inline($race));
		}
		break;
	case "chooserace":
		if ($session['user']['dragonkills'] < get_module_setting("mindk"))
			break;
		output("<a href='newday.php?setrace=Samurai$resline'>In the village of %s</a>, a village of ancient warriors, you are race of `3Samurai`0, You have great fighting abilty and lasting endurance.`n`n",$city, true);
		addnav("`5Samurai`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "setrace":
		if ($session['user']['race']==$race){
		output("`&As a Samurai, you attack quickly with your razor sharp Tanto.`n");
		output("You gain extra defense!`n");
		output("Your keen eye for gems  not unusual for your race, you are strong and weild a razor sharp Tanto.`n");
		output("You gain extra gems from forest fights!");
		if (is_module_active("cities")) {
			if ($session['user']['dragonkills']==0 &&
			$session['user']['age']==0){
			set_module_setting("newest-$city",
				$session['user']['acctid'],"cities");
			}
			set_module_pref("homecity",$city,"cities");
			if ($session['user']['age'] == 0)
			$session['user']['location']=$city;
			}
		}
		break;
	case "battle-victory":
		if ($session['user']['race'] != $race) break;
		if ($args['type'] != "forest") break;
		if ($session['user']['level'] <=15 &&
			e_rand(1,100) <= get_module_setting("gemchance")) {
			output(get_module_setting("gemmessage")."`n`0");
			$session['user']['gems']+=5;
			$session['user']['gold']+=100;
			$session['user']['attack']++;
			$session['user']['hitpoints']+=25;
			debuglog("found a gem when slaying a monster, for being a Samurai.");
		}
		break;

	case "creatureencounter":
		if ($session['user']['race']==$race){
			racesamu_checkcity();
			$loss = (100 - get_module_setting("goldloss"))/10;
			$args['creaturegold']=round($args['creaturegold']*$loss,0);
		}
		break;
	case "newday":
		if ($session['user']['race']==$race){
			racesamu_checkcity();
			apply_buff("racialbenefit",array(
			"name"=>"`3Samurai Tanto`0",
			"defmod"=>"(<defense>?(1+((3+floor(<level>/5))/<defense>)):0)",
			"badguydmgmod"=>1.4,
			"allowinpvp"=>1,
			"allowintrain"=>1,
			"rounds"=>200,
			"schema"=>"module-racesamu",
			)
			);
		}
		break;
	}

	return $args;
}

function racesamu_checkcity(){
	global $session;
	$race="Samurai";
	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	
	if ($session['user']['race']==$race && is_module_active("cities")){
		if (get_module_pref("homecity","cities")!=$city){
			set_module_pref("homecity",$city,"cities");
		}
	}
	return true;
}

function racesamu_run(){
}
?>