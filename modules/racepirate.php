<?php
// translator ready
// addnews ready
// mail ready

function racepirate_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Pirate",
		"version"=>"1.0",
		"author"=>"John McNally, based on troll race by Eric Stevens",
		"category"=>"Races",
		"download"=>"core_module",
		"settings"=>array(
			"Pirate Race Settings,title",
			"villagename"=>"Name for the Pirate village|Port Royal",
			"minedeathchance"=>"Chance for Pirates to die in the mine,range,0,100,1|90",
		),
	);
	return $info;
}

function racepirate_install(){
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("villagetext");
	module_addhook("travel");
	module_addhook("charstats");
	module_addhook("validlocation");
	module_addhook("validforestloc");
	module_addhook("moderate");
	module_addhook("changesetting");
	module_addhook("raceminedeath");
	module_addhook("pvpadjust");
	module_addhook("adjuststats");
	module_addhook("racenames");
	return true;
}

function racepirate_uninstall(){
	global $session;
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$gname = get_module_setting("villagename");
	$sql = "UPDATE " . db_prefix("accounts") . " SET location='$vname' WHERE location = '$gname'";
	db_query($sql);
	if ($session['user']['location'] == $gname)
		$session['user']['location'] = $vname;
	// Force anyone who was a pirate to rechoose race
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Pirate'";
	db_query($sql);
	if ($session['user']['race'] == 'Pirate')
		$session['user']['race'] = RACE_UNKNOWN;

	return true;
}

function racepirate_dohook($hookname,$args){
	//yeah, the $resline thing is a hack.  Sorry, not sure of a better way
	// to handle this.
	// Pass it in via args?
	global $session,$resline;
	$city = get_module_setting("villagename");
	$race = "pirate";
	switch($hookname){
	case "racenames":
		$args[$race] = $race;
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creatureattack']+=(1+floor($args['creaturelevel']/5));
		}
		break;
	case "adjuststats":
		if ($args['race'] == $race) {
			$args['attack']+=(1+floor($args['level']/5));
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
		}
		break;
	case "changesetting":
		// Ignore anything other than villagename setting changes
		if ($args['setting'] == "villagename" && $args['module']=="racepirate") {
			if ($session['user']['location'] == $args['old'])
				$session['user']['location'] = $args['new'];
			$sql = "UPDATE " . db_prefix("accounts") .
				" SET location='" . addslashes($args['new']) .
				"' WHERE location='" . addslashes($args['old']) . "'";
			db_query($sql);
			if (is_module_active("cities")) {
				$sql = "UPDATE " . db_prefix("module_userprefs") .
					" SET value='" . addslashes($args['new']) .
					"' WHERE modulename='cities' AND setting='homecity'" .
					"AND value='" . addslashes($args['old']) . "'";
				db_query($sql);
			}
		}
		break;
	case "charstats":
		if ($session['user']['race']==$race){
			addcharstat("Vital Info");
			addcharstat("Race", translate_inline($race));
		}
		break;
	case "chooserace":
		output("<a href='newday.php?setrace=$race$resline'>On the coastal city of %s</a>`2 as a `@Pirate`2, roaming the docks and streets, learning the ways of the sea.`n`n",$city, true);
		addnav("`@Pirate`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "setrace":
		if ($session['user']['race']==$race){
			output("`@Growing up around pirates and cutthroats, you learned how to handle yourself at a young age.`n");
			output("`^You gain extra attack!");
			if (is_module_active("cities")) {
				if ($session['user']['dragonkills']==0 &&
						$session['user']['age']==0){
					//new farmthing, set them to wandering around this city.
					set_module_setting("newest-$city",
							$session['user']['acctid'],"cities");
				}
				set_module_pref("homecity",$city,"cities");
				if ($session['user']['age'] == 0)
					$session['user']['location']=$city;
			}
		}
		break;
	case "validforestloc":
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
		if ($session['user']['race']==$race){
			racepirate_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`@Street Smarts`0",
	 			"atkmod"=>"(<attack>?(1+((1+floor(<level>/5))/<attack>)):0)",
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-racepirate",
				)
			);
		}
		break;
	case "travel":
		$capital = getsetting("villagename", LOCATION_FIELDS);
		$hotkey = substr($city, 0, 1);
		tlschema("module-cities");
		if ($session['user']['location']==$capital){
			addnav("Safer Travel");
			addnav(array("%s?Go to %s", $hotkey, $city),"runmodule.php?module=cities&op=travel&city=$city");
		}elseif ($session['user']['location']!=$city){
			addnav("More Dangerous Travel");
			addnav(array("%s?Go to %s", $hotkey, $city),"runmodule.php?module=cities&op=travel&city=$city&d=1");
		}
		if ($session['user']['superuser'] & SU_EDIT_USERS){
			addnav("Superuser");
			addnav(array("%s?Go to %s", $hotkey, $city),"runmodule.php?module=cities&op=travel&city=$city&su=1");
		}
		tlschema();
		break;	
	case "villagetext":
		racepirate_checkcity();
		if ($session['user']['location'] == $city){
			$args['text']=array("`@`b`c%s, Home of the Pirates`c`b`n`2You stand on the docks, looking out at the sea. You can feel it calling you and know that one day you will have a ship of your own and be called captain.`n`n  Nearby some brightly colored fellows are sharing a bottle and singing sea shanties.  Out on the docks,  ships come and go, bringing in all sorts of cargo. The only laws here seem to be might and money, yet despite the anarchy the city has a jovial quality.`n", $city);
			$args['schemas']['text'] = "module-racepirate";
			$args['clock']="`n`2Based on the position of the sun you can tell it is `@%s`2.`n";
			$args['schemas']['clock'] = "module-racepirate";
			if (is_module_active("calendar")) {
				$args['calendar'] = "`n`2The direction of the wind, and the look of the seas, tell you it's `@%1\$s`2, `@%3\$s %2\$s`2, `@%4\$s`2.`n";
				$args['schemas']['calendar'] = "module-racepirate";
			}
			$args['title']=array("The Port City of %s", $city);
			$args['schemas']['title'] = "module-racepirate";
			$args['sayline']="be sayin'";
			$args['schemas']['sayline'] = "module-racepirate";
			$args['talk']="`n`@Nearby some villagers spin yarns:`n";
			$args['schemas']['talk'] = "module-racepirate";
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
				$args['newest']="`n`2You wander the docks, noticing the bars and gambling houses in stride.  Feeling the sea mist on your skin, you see the silvery sun trying to burn through the morning fog.`n`n";
			} else {
				$args['newest']="`n`2Playing Mumblty Peg with a group of other young scaliwags is `@%s`2.";
			}
			$args['schemas']['newest'] = "module-racepirate";
			$args['gatenav']="Seek Other Lands";
			$args['schemas']['gatenav'] = "module-racepirate";
			$args['fightnav']="Brawls";
			$args['schemas']['fightnav'] = "module-racepirate";
			$args['marketnav']="Booty N' Baubles";
			$args['schemas']['marketnav'] = "module-racepirate";
			$args['tavernnav']="The Docks";
			$args['schemas']['tavernnav'] = "module-racepirate";
			$args['infonav']="Info";
			$args['schemas']['infonav'] = "module-racepirate";
			$args['section']="village-$race";
		}
		break;
	}
	return $args;
}

function racepirate_checkcity(){
	global $session;
	$race="pirate";
	$city=get_module_setting("villagename");
	
	if ($session['user']['race']==$race && is_module_active("cities")){
		//if they're this race and their home city isn't right, set it up.
		if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
			set_module_pref("homecity",$city,"cities");
		}
	}	
	return true;
}

function racepirate_run(){

}
?>
