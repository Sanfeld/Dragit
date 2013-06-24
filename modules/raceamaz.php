<?php
// City-Less Functionality adopted from racefelyne of core.

function raceamaz_getmoduleinfo(){
    $info = array(
        "name"=>"Race - Amazon",
        "version"=>"1.1",
        "author"=>"Chris Vorndran",
        "category"=>"Races",
        "download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=38",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Race. Extra Experience is Earned. Female Specific",
        "settings"=>array(
            "Amazon Race Settings,title",
            "minedeathchance"=>"Chance for Amazon to die in the mine,range,0,100,1|25",
			"mindk"=>"How many DKs do you need before the race is available?,int|5",
			"Amazon EXP Settings,title",
			"exp"=>"How much extra exp (%) does a Amazon get?,range,100,200,1|110",
			"gold"=>"How much less gold (%) does a Amazon get?,range,1,100,1|50",
        ),
        );
    return $info;
}

function raceamaz_install(){
    module_addhook("chooserace");
    module_addhook("setrace");
    module_addhook("creatureencounter");
    module_addhook("charstats");
	module_addhook("racenames");
    module_addhook("raceminedeath");
    return true;
}

function raceamaz_uninstall(){
	global $session;
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$gname = get_module_setting("villagename");
	$sql = "UPDATE " . db_prefix("accounts") . " SET location='$vname' WHERE location = '$gname'";
	db_query($sql);
	if ($session['user']['location'] == $gname)
		$session['user']['location'] = $vname;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Amazon'";
	db_query($sql);
	if ($session['user']['race'] == 'Amazon')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function raceamaz_dohook($hookname,$args){
    //yeah, the $resline thing is a hack.  Sorry, not sure of a better way
    //to handle this.
    // It could be passed as a hook arg?
    global $session,$resline;
	if (is_module_active("racebarb")) {
		$city = get_module_setting("villagename", "racebarb");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$exp = get_module_setting("exp")/100;
	$gold = get_module_setting("gold")/100;
    $race = "Amazon";
    switch($hookname){
    case "raceminedeath":
        if ($session['user']['race'] == $race) {
            $args['chance'] = get_module_setting("minedeathchance");
            $args['racesave'] = "Fortunately your amazon skill let you escape unscathed.`n";
        }
        break;
	case "racenames":
		$args[$race] = $race;
		break;
    case "charstats":
        if ($session['user']['race']==$race){
            addcharstat("Vital Info");
            addcharstat("Race", $race);
        }
        break;
    case "chooserace":
		if($session['user']['sex']==SEX_MALE)
			break;
		if ($session['user']['dragonkills'] < get_module_setting("mindk"))
			break;
        output("<a href='newday.php?setrace=Amazon$resline'>Deep in the heavily forested areas around %s</a>, home to the noble and strong `#Amazonian`0 people. The lost race of Amazons, hidden away in the depths of decivilization.`n`n",$city,true);
        addnav("`#Amazon`0","newday.php?setrace=Amazon$resline");
        addnav("","newday.php?setrace=Amazon$resline");
        break;
    case "setrace":
        if ($session['user']['race']==$race){
            output("`#As a amazon, you are more skillful and deft at battle.`n`^You gain extra experience from forest fights!");
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
    case "creatureencounter":
        if ($session['user']['race']==$race){
            //get those folks who haven't manually chosen a race
            raceamaz_checkcity();
			$args['creaturegold']=round($args['creaturegold']*$gold,0);
            $args['creatureexp']=round($args['creatureexp']*$exp,0);
        }
        break;
    }
    return $args;
}

function raceamaz_checkcity(){
    global $session;
    $race="Amazon";
    if (is_module_active("racebarb")) {
		$city = get_module_setting("villagename", "racebarb");
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

function raceamaz_run(){
}
?>