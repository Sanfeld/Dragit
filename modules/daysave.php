<?php
require_once("lib/http.php");
require_once("common.php");
require_once("lib/villagenav.php");

// v1.1 fixed a bug that caused a possible infinite newday loop when not logging out after using a newday
// V1.2 Fixes newday hook, added debug info by SexyCook
// V1.3 Added hook to jail
// V1.4 commented the debugs that were getting on my nerves, added an output for 0 days, due to translation difficulties.
// V1.5 Fixed the bug that gave new players the max amount of saved days

function daysave_getmoduleinfo(){
	$info = array(
		"name"=>"Game Day Accumulation",
		"author"=>"Exxar, fixes by SexyCook",
		"version"=>"1.5",
		"category"=>"General",
		"download"=>"http://dragonprime.net/users/Exxar/daysave.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Exxar/",
		"settings"=>array(
					"maxpool"=>"maximum number of saved days,int|6",
					),
			"prefs"=>array(
					"pool"=>"Number of saved days,int|0",
					"lastlognewday"=>"Next newday after logout,int|0",
					),
			);
	return $info;
}

function daysave_install(){
	module_addhook("newday");
	module_addhook("player-logout");
	module_addhook("village");
	module_addhook("shades");
	module_addhook("injail");
	return true;
}

function daysave_uninstall(){
	return true;
}

function daysave_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "newday":
			$pool=get_module_pref("pool");
			$lastonnextday=get_module_pref("lastlognewday");
			//debug("lastonnextday: $lastonnextday");
			$maxpool=get_module_setting("maxpool");
			$time=gametimedetails();
			//debug("time:". $time['gametime']);
			$timediff=$time['gametime']-$lastonnextday;
			//debug("timediff: $timediff");
			if ($timediff>86400){
				$addition=floor($timediff/86400);
				$pool+=$addition;
				if ($pool > $maxpool) $pool=$maxpool;
				if($lastonnextday<1){
					$pool=0;				
				}
				set_module_pref("pool", $pool);
			}
			set_module_pref("lastlognewday", $time['tomorrow']);
			//$lastonnextday=get_module_pref("lastlognewday");
			//debug("lastonnextday: $lastonnextday");
		break;
		case "player-logout":
			$details=gametimedetails();
			set_module_pref("lastlognewday", $details['tomorrow']);
			break;
		case "village":
			$pool=get_module_pref("pool");
			if ($pool>0){
				tlschema('daysavenav');
				addnav("Saved Days");
				addnav("New Day","runmodule.php?module=daysave&op=start&return=village");
			}
		break;
		case "shades":
			$pool=get_module_pref("pool");
			if ($pool>0){
				tlschema('daysavenav');
				addnav("Saved Days");
				addnav("New Day","runmodule.php?module=daysave&op=start&return=shades");
			}
		break;
		case "injail":
			$pool=get_module_pref("pool");
			if ($pool>0){
				addnav("Saved Days");
				addnav("New Day","runmodule.php?module=daysave&op=start&return=jail");
			}
		break;
	}
	return $args;
}

function daysave_run(){
	global $session;
	$op = httpget('op');
	$return = httpget('return');
	$pool=get_module_pref("pool");

	page_header("Saved Days");
		switch ($op){
			case "start":
				if ($pool>1) $day="days";
				else $day="day";
				output("You have %s more $day in your savings pool. Do you wish to be granted a new day?", $pool);
				tlschema('yesnav');
				addnav("Yes");
				addnav("Continue", "runmodule.php?module=daysave&op=finish");
				if ($return=="village") {
					tlschema('nonav');
					addnav("No");
					villagenav();
				}
				else if($return=="shades") {
					tlschema('nonav');
					addnav("No");
					addnav("Return to Shades", "shades.php");
				}
				else if($return=="jail") {
					tlschema('nonav');
					addnav("No");
					addnav("Return to Jail", "runmodule.php?module=jail");
				}
				else addnav("Your navs are corrupted!", "badnav.php");
			break;
			case "finish":
				$pool-=1;
				if ($pool<0) $pool=0;
				set_module_pref("pool", $pool);
				if ($pool>1 || $pool<1) $day="days";
				else $day="day";
				if($pool==0) output("You will be granted a new day. You have no more $day in your savings pool");
				else { $num=$pool;
				output("You will be granted a new day. You have %s more $day in your savings pool.", $num); }
				addnav("It is a New Day!","newday.php");
			break;
		}
	page_footer();
}
?>
