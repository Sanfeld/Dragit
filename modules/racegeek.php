<?php
// translator ready
// addnews ready

/* Geek Race */
/* ver 0.1 */
/* Gordon McCallum => ayeright16@gmail.com */

function racegeek_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Geek",
		"version"=>"0.1",
		"author"=>"Gordon McCallum",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/users/Ayeright/racegeek.zip",
		"settings"=>array(
			"Geek Race Settings,title",
			"villagename"=>"Name for the Geek village|Program Files",
			"minedeathchance"=>"Percent chance for Geeks to die in the mine,range,0,100,1|20",
			"gemchance"=>"Percent chance for Geeks to find a gem on battle victory,range,0,100,1|5",
			"gemmessage"=>"Message to display when finding a gem|`&Your Electronic Gem Radar starts to beep. You located a `%gem`&!",
			"goldloss"=>"How much less gold (in percent) do the Geeks find?,range,0,100,1|10",
			"mindk"=>"How many DKs do you need before the race is available?,int|0",
			"cost"=>"How many Donation points do you need before the race is available?,int|0",
		),
	);
	return $info;
}

function racegeek_install(){
	
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

function racegeek_uninstall(){
	global $session;
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$gname = get_module_setting("villagename");
	$sql = "UPDATE " . db_prefix("accounts") . " SET location='$vname' WHERE location = '$gname'";
	db_query($sql);
	if ($session['user']['location'] == $gname)
		$session['user']['location'] = $vname;
	// Force anyone who was a Geek to rechoose race
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Geek'";
	db_query($sql);
	if ($session['user']['race'] == 'Geek')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racegeek_dohook($hookname,$args){
	//yeah, the $resline thing is a hack.  Sorry, not sure of a better way
	//to handle this.
	// It could be passed as a hook arg?
	global $session,$resline;
	$city = get_module_setting("villagename");
	$race = "Geek";
	$cost = get_module_setting("cost");
	switch($hookname){
	case "pointsdesc":
		if (get_module_setting("mindk")>0 || $cost>0)
		{
			$args['count']++;
			$format = $args['format'];
			$str = translate("The Geek Race is availiable upon reaching %s Dragon Kills and %s Donation points.");
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
			$args['racesave'] = "The Geological Scanner was able to detect the unstable structure of the mine.  You escape unscathed.`n";
			$args['schema']="module-racegeek";
		}
		break;
	case "changesetting":
		// Ignore anything other than villagename setting changes
		if ($args['setting'] == "villagename" && $args['module']=="racegeek") {
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
		output("<a href='newday.php?setrace=Geek$resline'>Deep with your hardrive, in the folder named %s</a>, your race of `5Geek`0 are busy learning code.  Your hacking knowledge and extreme geekyness has allowed you access to Cities and towns that are unavailable to other races..`n`n",$city, true);
		addnav("`5Geek`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "setrace":
		if ($session['user']['race']==$race){ // it helps if you capitalize correctly
			output("`&As a Geek, you have programed your laptop to suggest the best stratigical manuevers.`n");
			output("You gain extra defense!`n");
			output("You may have a radar to help you find more Gems, but you have yet to invent one for finding gold...`n");
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
			debuglog("found a gem when slaying a monster, for being a Geek.");
		}
		break;
	// Lets actually lower their gold a bit.. really
	case "creatureencounter":
		if ($session['user']['race']==$race){
			//get those folks who haven't manually chosen a race
			racegeek_checkcity();
			$loss = (100 - get_module_setting("goldloss"))/100;
			$args['creaturegold']=round($args['creaturegold']*$loss,0);
		}
		break;
	case "newday":
		if ($session['user']['race']==$race){
			racegeek_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`@Binary Field`0",
				"defmod"=>"(<defense>?(1+((2+floor(<level>/5))/<defense>)):0)",
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-racegeek",
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
        racegeek_checkcity();
        //remind me to edit this later ^.^
        if ($session['user']['location'] == $city){
            $args['text']="`\$`c`@`b$city, `2Floppy Disk drive of the Geeks`b`@`c`n`n`2You are seated in your bedroom. Wearing nothing but a VR helmet, you ready yourself for adventure. The residents of $city busy themselves with booze and porn sites. Looking all around the city you struggle to see life, even though it is there. All the lights are out, the curtains are drawn. The Geeks are already preparing for battle.`n";
            $args['clock']="`n`2You glance at your task bar in need of the time. You see that it is `^%s`2 and continue with your tasks.`n";
            $args['title']="$city";
            $args['sayline']="types";
            $args['talk']="`n`^Meanwhile in the VR chat room:`n";
            $new = get_module_setting("newest-$city", "cities");
            if (is_module_active("calendar")) {
				$args['calendar'] = "`n`2You glance at your westlife calendar which reads `&%s`2, `&%s %s %s`2.`n";
				$args['schemas']['calendar'] = "module-racegeek";
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
                $args['newest']="`n`The clicking noises make you feel at home as you browse the folders of `@`b$city";
            } else {
                $args['newest']=" `^%s`2 is clicking around, whilst listening to his favorite westlife song.";
            }
			$args['schemas']['newest'] = "module-racegeek";
			$args['section']="village-$race";
			$args['gatenav']="Village Gates";
			$args['schemas']['gatenav'] = "module-racegeek";
        }
        break;
	}

	return $args;
}

function racegeek_checkcity(){
	global $session;
	
	$race="Geek";
	$city=get_module_setting("villagename");
	
	if ($session['user']['race']==$race && is_module_active("cities")){
        //if they're this race and their home city isn't right, set it up.
        if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
            set_module_pref("homecity",$city,"cities");
        }
    }   
	return true;
}

function racegeek_run(){
}
?>
