<?php
// translator ready
// addnews ready

/* Shadow Warriors */
/* ver 0.1 */
/* Gordon McCallum => ayeright16@gmail.com */
/* Contains settings to load into the Lodge if you want to have it a points award only race */

function raceninja_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Ninja",
		"version"=>"0.1",
		"author"=>"Gordon McCallum",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/users/Ayeright/raceninja.zip",
		"settings"=>array(
			"Shadow Warrior Race Settings,title",
			"villagename"=>"Name for the Shadow Warrior's village|Shadow Realm",
			"minedeathchance"=>"Percent chance for Ninjas to die in the mine,range,0,100,1|20",
			"gemchance"=>"Percent chance for Ninjas to find a gem on battle victory,range,0,100,1|5",
			"gemmessage"=>"Message to display when finding a gem|`&Your ninja skills have not failed you, you notice a `%gem`&!",
			"goldloss"=>"How much less gold (in percent) do the Ninjas find?,range,0,100,1|10",
			"mindk"=>"How many DKs do you need before the race is available?,int|0",
			"cost"=>"How many Donation points do you need before the race is available?,int|0",
		),
	);
	return $info;
}

function raceninja_install(){
	
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
	module_addhook("pointsdesc");
	return true;
}

function raceninja_uninstall(){
	global $session;
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$gname = get_module_setting("villagename");
	$sql = "UPDATE " . db_prefix("accounts") . " SET location='$vname' WHERE location = '$gname'";
	db_query($sql);
	if ($session['user']['location'] == $gname)
		$session['user']['location'] = $vname;
	// Force anyone who was a Ninja to rechoose race
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Ninja'";
	db_query($sql);
	if ($session['user']['race'] == 'Ninja')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function raceninja_dohook($hookname,$args){
	//yeah, the $resline thing is a hack.  Sorry, not sure of a better way
	//to handle this.
	// It could be passed as a hook arg?
	global $session,$resline;
	$city = get_module_setting("villagename");
	$race = "Ninja";
	$cost = get_module_setting("cost");
	switch($hookname){
	case "pointsdesc":
		if (get_module_setting("mindk")>0 || $cost>0)
		{
			$args['count']++;
			$format = $args['format'];
			$str = translate("Shadow Warriors are only availiable upon reaching %s Dragon Kills and %s Donation points.");
			$str = sprintf($str, get_module_setting("mindk"),
					get_module_setting("cost"));
			output($format, $str, true);
		}
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creaturedefense']+=(2+floor($args['creaturelevel']/5));
			$args['creaturehealth']-= round($args['creaturehealth']*.05, 0);
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Your hightened senses indicate a cave collapse!! You throw a smoke bomb and vanish to saftey.`n";
			$args['schema']="module-raceninja";
		}
		break;
	case "changesetting":
		// Ignore anything other than villagename setting changes
		if ($args['setting'] == "villagename" && $args['module']=="raceninja") {
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
			addcharstat("Race", translate_inline($race));
		}
		break;
	case "chooserace":
		$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || get_module_setting("cost") > $pointsavailable)
			break;
		output("<a href='newday.php?setrace=Ninja$resline'>In the mystic lands of the %s</a>, your race of `5Ninja`0 train hard.  Your stealth and agile movement allow you to stow away on enemy ships in order to reach new lands.`n`n",$city, true);
		addnav("`5Ninja`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "setrace":
		if ($session['user']['race']==$race){ // it helps if you capitalize correctly
			output("`&As a Ninja, your cat-like reflexes allow you to respond very quickly to any attacks.`n");
			output("You gain extra defense!`n");
			output("Your relations with the pirates many years ago has brought good fortune to your city. It has also tought you how to seek treasure and steal it easily.`n");
			output("You gain extra gems from forest fights, but you also do not gain as much gold!");
			$session['user']['donationspent'] = $session['user']['donationspent'] + $cost;
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
	case "battle-victory":
		if ($session['user']['race'] != $race) break;
		if ($args['type'] != "forest") break;
		if ($session['user']['level'] < 15 &&
				e_rand(1,100) <= get_module_setting("gemchance")) {
			output(get_module_setting("gemmessage")."`n`0");
			$session['user']['gems']++;
			debuglog("found a gem when slaying a monster!!.");
		}
		break;
	// Lets actually lower their gold a bit.. really
	case "creatureencounter":
		if ($session['user']['race']==$race){
			//get those folks who haven't manually chosen a race
			raceninja_checkcity();
			$loss = (100 - get_module_setting("goldloss"))/100;
			$args['creaturegold']=round($args['creaturegold']*$loss,0);
		}
		break;
	case "newday":
		if ($session['user']['race']==$race){
			raceninja_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`@Dark Shadow`0",
				"defmod"=>"(<defense>?(1+((2+floor(<level>/5))/<defense>)):0)",
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-raceninja",
				)
			);
		}
		break;
	case "validlocation":
        if (is_module_active("cities"))
            $args[$city]="village-$race";
        break;
    case "moderate":
        if (is_module_active("cities"))
            $args["village-$race"]="City of $city";
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
    case "villagetext":
        raceninja_checkcity();
        //remind me to edit this later ^.^
        if ($session['user']['location'] == $city){
            $args['text']="`\$`c`@`b$city, `2home to the Shadow Warriors`b`@`c`n`n`2You stand in a valley between towering cliffs.  The residents of $city are busy making weapons for the Ninjas.  Blood and bones scatter the village, none of which belong to a ninja. Throwing stars glide from person to person in the town dojo as the ninjas battle for rank.`n";
            $args['clock']="`n`2The wise elder approaches and whispers the time as `^%s`2 before disappearing in a puff of smoke.`n";
            $args['title']="$city";
            $args['sayline']="says";
            $args['talk']="`n`^Nearby some villagers talk:`n";
            $new = get_module_setting("newest-$city", "cities");
            if (is_module_active("calendar")) {
				$args['calendar'] = "`n`2A small rock in the center of the city reads `&%s`2, `&%s %s %s`2.`n";
				$args['schemas']['calendar'] = "module-raceninja";
			}
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
                $args['newest']="`n`4The dust trails behind you as you follow the footpath towards the `@`b$city";
            } else {
                $args['newest']=" `^%s`2 is wandering around fidgiting with his sword.";
            }
			$args['schemas']['newest'] = "module-raceninja";
			$args['section']="village-$race";
			$args['gatenav']="Village Gates";
			$args['schemas']['gatenav'] = "module-raceninja";
        }
        break;
	}

	return $args;
}

function raceninja_checkcity(){
	global $session;
	
	$race="Ninja";
	$city=get_module_setting("villagename");
	
	if ($session['user']['race']==$race && is_module_active("cities")){
        //if they're this race and their home city isn't right, set it up.
        if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
            set_module_pref("homecity",$city,"cities");
        }
    }   
	return true;
}

function raceninja_run(){
}
?>
