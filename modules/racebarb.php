<?php

function racebarb_getmoduleinfo(){
    $info = array(
        "name"=>"Race - Barbarian",
        "version"=>"1.1",
        "author"=>"Chris Vorndran",
        "category"=>"Races",
        "download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=12",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Race. Extra Experience is Earned. Male Specific",
        "settings"=>array(
            "Barbarian Race Settings,title",
            "minedeathchance"=>"Chance for Barbarian to die in the mine,range,0,100,1|25",
			"mindk"=>"How many DKs do you need before the race is available?,int|5",
			"villagename"=>"Name of Barbarian Village,text|Formenya",
			"Barbarian EXP Settings,title",
			"exp"=>"How much extra exp (%) does a Barbarian get?,range,100,200,1|110",
			"gold"=>"How much less gold (%) does a Barbarian get?,range,1,100,1|50",
			),
		"prefs-drinks"=>array(
			"Barbarian Race Drink Preferences,title",
			"served"=>"Is this drink served in the barbarian inn?,bool|0",
		),
        );
    return $info;
}

function racebarb_install(){
    module_addhook("chooserace");
    module_addhook("setrace");
    module_addhook("creatureencounter");
    module_addhook("charstats");
	module_addhook("racenames");
    module_addhook("raceminedeath");
	module_addhook("villagetext");
    module_addhook("travel");
    module_addhook("validlocation");
	module_addhook("validforestloc");
    module_addhook("moderate");
   	module_addhook("drinks-text");
	module_addhook("changesetting");
	module_addhook("village");
	module_addhook("drinks-check");
    return true;
}

function racebarb_uninstall(){
	global $session;
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$gname = get_module_setting("villagename");
	$sql = "UPDATE " . db_prefix("accounts") . " SET location='$vname' WHERE location = '$gname'";
	db_query($sql);
	if ($session['user']['location'] == $gname)
		$session['user']['location'] = $vname;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Barbarian'";
	db_query($sql);
	if ($session['user']['race'] == 'Barbarian')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racebarb_dohook($hookname,$args){
    //yeah, the $resline thing is a hack.  Sorry, not sure of a better way
    //to handle this.
    // It could be passed as a hook arg?
    global $session,$resline;
	$city = get_module_setting("villagename");
	$exp = get_module_setting("exp")/100;
	$gold = get_module_setting("gold")/100;
    $race = "Barbarian";
    switch($hookname){
		case "village":
			if ($session['user']['location'] == $city) {
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav("I? The Misty Cloud","runmodule.php?module=racebarb&op=ale");
			}
			break;
		case "drinks-text":
			if ($session['user']['location'] != $city) break;
			$args["title"]="The Misty Cloud";
			$args['schemas']['title'] = "module-racebarb";
			$args["return"]="B?Return to the Bar";
			$args['schemas']['return'] = "module-racebarb";
			$args['returnlink']="runmodule.php?module=racebarb&op=ale";
			$args["demand"]="Pounding your fist on the bar, you demand another drink";
			$args['schemas']['demand'] = "module-racebarb";
			$args["toodrunk"]=" but `@Arthas`0 the bartender continues to clean the stein he was working on and growls,  \"`@No more of my drinks for you!`0\"";
			$args['schemas']['toodrunk'] = "module-racebarb";
			$args["toomany"]="`@Arthas`0 the bartender furrows his balding head.  \"`@You're too weak to handle any more of `bMY`b brew.  Begone!`0\"";
			$args['schemas']['toomany'] = "module-racebarb";
			$args['drinksubs']=array(
					"/^Cedrik/"=>translate_inline("`@Arthas`0"),
					"/Cedrik/"=>translate_inline("`@Arthas`0"),
					"/ Violet /"=>translate_inline(" a stranger "),
					"/ Seth /"=>translate_inline(" a stranger "),
					);
			break;
		case "drinks-check":
			if ($session['user']['location'] == $city) {
				$val = get_module_objpref("drinks", $args['drinkid'], "served");
				$args['allowdrink'] = $val;
			}
			break;
		case "villagetext":
			if ($session['user']['location'] == $city){
				$args['text']="`Q`c`b$city, The City in the Sky `b`c`n`qA vast city, with tiny lights along the edge. Looking from a distance, the city is like a heavenly blanket with holes poked in it. You walk into the center of town, and notice the grand Statues all around. Seeing that you are more than welcome in this town, you decide to venture around and poke in the various shoppes. Several warriors stand around, holding weapons that are coated in blood and a fresh kill in their other hand. Amazingly, there is no smell.`n";
				$args['clock']="`n`qYou stop a groggy warrior in the street and ask him the time. He replies that it is `^%s`q and then tells you to be getting on your way.`n";
				$args['title']="$city";
				$args['sayline']="boasts";
				$args['talk']="`n`^Nearby some villagers boast, raving about their latest kill:`n";
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
					$args['newest']="`nLooking about in awe, you decide that it is about time to start on your own journey.";
				} else {
					$args['newest']="`nLooking dumbstruck, standing in the middle of the square is: %s.";
				}
				$args['gatenav']="Valhalla Gates";
				$args['fightnav']="Grounds of Valor";
				$args['marketnav']="Axe and Hammer";
				$args['tavernnav']="Nectar Fields";
				$args['section']="village-$race";
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
			break;
		case "validforestloc":
		case "validlocation":
			if (is_module_active("cities"))
				$args[$city] = "village-$race";
			break;
		case "moderate":
			if (is_module_active("cities"))
					$args["village-$race"]="City of $city";
				break;
		case "changesetting":
			if ($args['setting'] == "villagename") {
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
    case "raceminedeath":
        if ($session['user']['race'] == $race) {
            $args['chance'] = get_module_setting("minedeathchance");
            $args['racesave'] = "Fortunately your Barbarian skill let you escape unscathed.`n";
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
		if ($session['user']['sex']==SEX_FEMALE)
		    break;
		if ($session['user']['dragonkills'] < get_module_setting("mindk"))
			break;
        output("<a href='newday.php?setrace=Barbarian$resline'>High in the mountainous lands around %s</a>, home to the brute and savage `#Barbarian`0 people. The immense race of Barbarians, living in the coldest of regions.`n`n",$city,true);
        addnav("`qBarbarian`0","newday.php?setrace=Barbarian$resline");
        addnav("","newday.php?setrace=Barbarian$resline");
        break;
    case "setrace":
        if ($session['user']['race']==$race){
            output("`#As a barbarian, you are more knowledgeable at battle.`n`^You gain extra experience from forest fights!");
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
            racebarb_checkcity();
			$args['creaturegold']=round($args['creaturegold']*$gold,0);
            $args['creatureexp']=round($args['creatureexp']*$exp,0);
        }
        break;
    }
    return $args;
}

function racebarb_checkcity(){
    global $session;
    $race="Barbarian";
    $city = get_module_setting("villagename");
	
	if ($session['user']['race']==$race && is_module_active("cities")){
		//if they're this race and their home city isn't right, set it up.
		if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
			set_module_pref("homecity",$city,"cities");
		}
	}
    return true;
}
function racebarb_run(){
	global $session;
	$op = httpget('op');

	switch ($op){
		case "ale":
			require_once("lib/villagenav.php");
			page_header("The Misty Cloud");
			output("`3You make your way over to the great kegs of ale lined up near by, looking to score a hearty draught from their mighty reserves.");
			output("A mighty barbarian barkeep named `@Arthas`3 stands at least 6 feet tall, and is serving out the drinks to the boisterous crowd.");
			addnav("Drinks");
			modulehook("ale");
			addnav("Other");
			villagenav();
			page_footer();
			break;
	}
	addnav("Return");
	addnav("Return to the Lodge","lodge.php");
	page_footer();
}
?>