<?php
/**
* Version:      1.3
* Date:         November 21, 2005
* Author:       Kevin Hatfield - Arune http://www.dragonprime.net
* LOGD VER:     Module for 1.1+
*
*/
function racedarkelf_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Dark Elf",
		"version"=>"1.3",
		"author"=>"<a href=\"http://logd.ecsportal.com\" target=_new>Kevin Hatfield</a>",
		"category"=>"Races",
		"vertxtloc"=>"http://www.dragonprime.net/users/khatfield/",
		"description"=>"Dark Elf Race",
		"download"=>"http://dragonprime.net/users/khatfield/racedarkelf.zip",
		"settings"=>array(
			"Dark Elf Race Settings,title",
			"villagename"=>"Name for the Dark Elf village|Kemsley",
			"minedeathchance"=>"Chance for Dark Elves to die in the mine,range,0,100,1|90",
			"mindk"=>"How many DKs do you need before the race is available?,int|0",
		),
	);
	return $info;
}

function racedarkelf_install(){
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("villagetext");
	module_addhook("travel");
	module_addhook("charstats");
	module_addhook("validlocation");
	module_addhook("moderate");
	module_addhook("changesetting");
	module_addhook("raceminedeath");
	module_addhook("pvpadjust");
	// Update from commentary sections using village-$city to village-$race;
	// This is pretty much a one-time thing
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='DarkElf' WHERE race='DarkElf'";
	db_query($sql);
	$sql = "UPDATE " . db_prefix("commentary") . " SET section='village-Kemsley' WHERE section='village-Kemsley'";
	db_query($sql);
	return true;
}

function racedarkelf_uninstall(){
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='DarkElf'";
	db_query($sql);
	if ($session['user']['race'] == 'DarkElf')
	$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racedarkelf_dohook($hookname,$args){
	//yeah, the $resline thing is a hack.  Sorry, not sure of a better way
	// to handle this.
	// Pass it in via args?
	global $session,$resline;
	$city = get_module_setting("villagename");
	$race = "DarkElf";
	switch($hookname){
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creatureattack']++;
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
		}
		break;
	case "changesetting":
		// Ignore anything other than villagename setting changes
		if ($args['setting'] == "villagename" && $args['module']=="racedarkelf") {
			if ($session['user']['location'] == $args['old'])
				$session['user']['location'] = $args['new'];
			$sql = "UPDATE " . db_prefix("accounts") .
				" SET location='" . $args['new'] .
				"' WHERE location='" . $args['old'] . "'";
			db_query($sql);
			if (is_module_active("cities")) {
				$sql = "UPDATE " . db_prefix("module_userprefs") .
					" SET value='" . $args['new'] .
					"' WHERE modulename='cities' AND setting='homecity'" .
					"AND value='" . $args['old'] . "'";
				db_query($sql);
			}
		}
		break;
	case "charstats":
		if ($session['user']['race']==$race){
			addcharstat("Vital Info");
			addcharstat("Race", $race);
		}
		break;
	case "chooserace":
		if ($session['user']['dragonkills'] < get_module_setting("mindk"))
                        break;
		output("<a href='newday.php?setrace=DarkElf$resline'>In the center of $city</a>`2 as a `@Dark Elf`2, wandering the city you smile to yourself..What an excellent day this is going to be! `n`n",true);
		addnav("`@Dark Elf`0","newday.php?setrace=DarkElf$resline");
		addnav("","newday.php?setrace=DarkElf$resline");
		break;
	case "setrace":
		if ($session['user']['race']==$race){
			output("`@As a Dark Elf, you've adapted to darkness. `n`^Your muscular build and your enhanced perception increases your defense!");
			if (is_module_active("cities")) {
				if ($session['user']['dragonkills']==0 &&
						$session['user']['age']==0){
					//new farmthing, set them to wandering around this city.
					set_module_setting("newest-$city",
							$session['user']['acctid'],"cities");
				}
				set_module_pref("homecity",$city,"cities");
				$session['user']['location']=$city;
			}
		}
		break;
	case "validlocation":
		if (is_module_active("cities"))
			$args[$city] = "village-$race";
		break;
        case "moderate":
            if (is_module_active("cities")) {
               tlschema("commentary");
               $args["village-$race"]=sprintf_translate("City of %s", $city);
               tlschema();
               }
               break;
	case "newday":
		if ($session['user']['race']=="DarkElf"){
			racedarkelf_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`@Elven Perception`0",
				"defmod"=>"1+.7/<defense>",
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				)
			);
		}
		break;
	case "travel":
		$capital = getsetting("villagename", LOCATION_FIELDS);
		if ($session['user']['location']==$capital){
			addnav("Safer Travel");
			addnav(substr($city,0,1)."?Go to $city","runmodule.php?module=cities&op=travel&city=$city");
		}elseif ($session['user']['location']!=$city){
			addnav("More Dangerous Travel");
			addnav(substr($city,0,1)."?Go to $city","runmodule.php?module=cities&op=travel&city=$city&d=1");
		}
		if ($session['user']['superuser'] & SU_EDIT_USERS){
			addnav("Superuser");
			addnav("Go to $city","runmodule.php?module=cities&op=travel&city=$city&su=1");
		}
		tlschema();
		break;	
	case "villagetext":
		racedarkelf_checkcity();
		if ($session['user']['location'] == $city){
			$args['text']="`@`b`c$city, Home of the Dark Elves`c`b`n`2You are standing on plagued ground in the center of the village, the smells of the forest and the moisture of the rain filling your nostrils.  Around you are the dark streets, once inhabited by humans, nearby you hear faint laughter and screams, chills run up your spine.`n";
			$args['clock']="`n`2Through a slight break in the dense fog you can see the clock outside the tavern reads `@%s`2.`n";
			$args['title']="The Village of $city";
			$args['sayline']="muses";
			$args['talk']="`n`@Nearby some villagers muse:`n";
            $new = get_module_setting("newest-$city", "cities");
			if ($new != 0) {
				$sql =  "SELECT name FROM " . db_prefix("accounts") .
					" WHERE acctid='$new'";
				$result = db_query_cached($sql, "newest-$city");
				$row = db_fetch_assoc($result);
				$args['newestplayer'] = $row['name'];
				$args['newestid']=$new;
			} else {
				$args['newestplayer'] = $new;
				$args['newestid']="";
			}
			if ($new == $session['user']['acctid']) {
				$args['newest']="`n`2You wander the village, keeping close eye out for thieves.";
			} else {
				$args['newest']="`n`2Staring directly in your direction is `@%s`2.";
			}
			$args['gatenav']="Village Gates";
			$args['fightnav']="Training";
			$args['marketnav']="Market";
			$args['tavernnav']="The Dark Tavern";
			$args['section']="village-$race";
		}
		break;
	}
	return $args;
}

function racedarkelf_checkcity(){
	global $session;
	$race="DarkElf";
	$city=get_module_setting("villagename");
	
	if ($session['user']['race']==$race && is_module_active("cities")){
		//if they're this race and their home city isn't right, set it up.
		if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
			set_module_pref("homecity",$city,"cities");
		}
	}	
	return true;
}

function racedarkelf_run(){

}
?>
