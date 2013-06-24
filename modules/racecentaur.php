<?php
if ($_GET['op']=="download"){ // this offers the module on every server for download
 $dl=join("",file("racecentaur.php"));
 echo $dl;
}

/* Centaur - a race with extra travel */
/* by eph, based on Felyne by Shannon Brown, thanks to xChrisx for the how-to!*/
// 

function racecentaur_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Centaur",
		"version"=>"1.1",
		"author"=>"eph",
		"category"=>"Races",
		"download"=>"modules/racecentaur.php?op=download",
		"settings"=>array(
			"Centaur Race Settings,title",
			"minedeathchance"=>"Percent chance for Centaur to die in the mine,range,0,100,1|80",
			"mindk"=>"How many DKs do you need before the race is available?,int|0",
			"xtravel"=>"How many extra travel does this race get?,int|1",
		),
	);
	return $info;
}

function racecentaur_install(){
	// The Centaur live with the humans, so..
	if (!is_module_installed("racehuman")) {
		output("The Centaur only choose to live with humans. You must install that race module.");
		return false;
	}

	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("charstats");
	module_addhook("raceminedeath");
	module_addhook("pvpadjust");
	module_addhook("racenames");
	module_addhook("count-travels");
	return true;
}

function racecentaur_uninstall(){
	global $session;
	// Force anyone who was a Centaur to rechoose race
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Centaur'";
	db_query($sql);
	if ($session['user']['race'] == 'Centaur')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racecentaur_dohook($hookname,$args){
	//yeah, the $resline thing is a hack.  Sorry, not sure of a better way
	//to handle this.
	// It could be passed as a hook arg?
	global $session,$resline;

	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Centaur";
	switch($hookname){
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creaturedefense']+=(2+floor($args['creaturelevel']/5));
			$args['creaturehealth']-= round($args['creaturehealth']*.05, 0);
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Your equine senses noticed the sound of a coming earthslide fast enough, allowing you to flee the mine in full gallop.`n";
			$args['schema']="module-racecentaur";
		}
		break;
	case "charstats":
		if ($session['user']['race']==$race){
			addcharstat("Vital Info");
			addcharstat("Race", translate_inline($race));
		}
		break;
	case "racenames":
		$args[$race] = $race;
		break;
	case "count-travels":
		if ($session['user']['race']== $race){
		$xtravel = get_module_setting("xtravel");
		$args['available']=($args['available']+$xtravel);
		}
		break;
	case "chooserace":
		if ($session['user']['dragonkills'] > get_module_setting("mindk"))
		{
		output("<a href='newday.php?setrace=Centaur$resline'>On the lush meadows surrounding the city of %s</a>, your race of `QCentaurs`0 lives in wooden emcampments. You are good-natured and fast as the wind.`n`n",$city, true);
		addnav("`QCentaur`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		}
		break;
	case "setrace":
		if ($session['user']['race']==$race){ // it helps if you capitalize correctly
			output("`&As a Centaur, your speed is unrivaled by other races.`n");
			output("You gain extra travel!`n");
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
	case "newday":
		if ($session['user']['race']==$race){
			racecentaur_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`QEquine Speed`0",
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-racecentaur",
				)
			);
		}
		break;
	}

	return $args;
}

function racecentaur_checkcity(){
	global $session;
	$race="Centaur";
	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	
	if ($session['user']['race']==$race && is_module_active("cities")){
		//if they're this race and their home city isn't right, set it up.
		if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
			set_module_pref("homecity",$city,"cities");
		}
	}	
	return true;
}

function racecentaur_run(){
}
?>
