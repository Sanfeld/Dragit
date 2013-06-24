<?php
/*
@Project - Race Editor.

@Initial Release Information.
	-	Game ware, Legend of the Green Dragon.
	-	Include Type, Module 
	-	Started, September 8th 2005. (2005.09.08).
	-	Initial nonpublic release for project beta testing and evaluations commenced October 1st 2005. (2005.10.01).
		
@Author and Contributor Credits.
	-	Author, Twisted of http://quest4dragon.com/
	-	Creation, September 8th 2005. (2005.09.08).
	-	Beta Release, October 27th 2005. (2005.10.27).
	-	Description, Provides the ability to limitedly create and edited any number of races via a in-game editor.

Download - http://dragonprime.net/users/Twisted/race.zip
*/
function race_getmoduleinfo(
	)
{
	$info = array(
		"name"=>"Race Editor",
		"author"=>"Twisted.",
		"version"=>"2005.10.27",
		"category"=>"Administrative",
		"download"=>"http://dragonprime.net/users/Twisted/race.zip",
	);
	return $info;
}
function race_install(
	)
{
	module_addhook(
		"superuser");
	module_addhook(
		"racenames");
	module_addhook(
		"charstats");
    module_addhook(
		"chooserace");
    module_addhook(
		"setrace");
	module_addhook(
		"newday");
    module_addhook(
		"raceminedeath");
	module_addhook(
		"creatureencounter");
	require_once(
		"race/sql.php");
	table_dorace("install");
    return true;
}
function race_uninstall(
	)
{
	global $session;
	$newloc = getsetting("villagename",
		LOCATION_FIELDS);
	$newrace = RACE_UNKNOWN;
	$result = db_query("SELECT * FROM " .
		db_prefix("races"));
	while ($race = db_fetch_assoc($result))
	{
		$oldloc = $race['location'];
		$oldrace = $race['basename'];
		if ($session['user']['race'] == $oldrace) {
			$session['user']['race'] = $newrace;
		}
		if ($session['user']['location'] == $oldloc) {
			$session['user']['location'] = $newloc;
		}
		db_query("UPDATE " . 
			db_prefix("accounts") . " " .
			"SET location='$newloc' " .
			"WHERE location='$oldloc'");
		db_query("UPDATE " .
			db_prefix("accounts") . " " .
			"SET race='$newrace' " .
			"WHERE race='$oldrace'");
	}
	require_once(
		"race/sql.php");
	table_dorace("uninstall");
	return true;
}

function race_dohook(
	$hookname,
	$args
	)
{
	global $session,$resline;
	require_once(
		"race/select.php");
	$race = select_dorace(
		$session['user']['race']);
	
	$location = $race['location'];
	
	$fname = $race['formalname'];
	$bname = $race['basename'];
	
	switch($hookname)
	{
		case "superuser":
			if ($session['user']['superuser'] & SU_EDIT_MOUNTS) {
				addnav("Editors");
				addnav(translate_inline("Race Editor"),
					"runmodule.php?module=race");
			}
			break;
		case "racenames":
			$args[$bname]=$bname;
			break;
		case "charstats":
			if ($session['user']['race'] == $bname) {
				addcharstat("Vital Info");
				addcharstat("Race",
					$fname);
			}
			break;
		case "chooserace":
			$result = db_query("SELECT * FROM " .
				db_prefix("races"));
			while ($race = db_fetch_assoc($result))
			{
				if ($session['user']['dragonkills'] < $race['dragonkills'])
			break;
				output("<a href=\"newday.php?setrace=%s%s\">%s</a>",
					$race['basename'],
						$resline,
					$race['formalname'],true);
				output($race['chooserace'],true);
					rawoutput("<br /><br />");
				addnav($race['formalname'],
					"newday.php?setrace=" .
						$race['basename'] .
							$resline);
				addnav("","newday.php?setrace=" .
					$race['basename'] .
						$resline);
			}
			break;
		case "setrace":
			if ($session['user']['race'] == $bname) {
				output($race['setrace'],true);
				if (is_module_active("cities")) {
					if ($session['user']['dragonkills'] == 0 && 
						$session['user']['age'] == 0) {
						set_module_setting("newest-$location",
							$session['user']['acctid'],
								"cities");
					}
					set_module_pref("homecity",
						$location,
							"cities");
					if ($session['user']['age'] == 0) {
						$session['user']['location'] = $location;
					}
				}
			}
			break;
		case "newday":
			if ($session['user']['race'] == $bname) {
				require_once(
					"race/checkcity.php");
				checkcity_dorace($race);
			}
			break;
		case "raceminedeath":
			if ($session['user']['race'] == $bname) {
				$args['chance'] = $race['deathchance'];
			}
			break;
		case "creatureencounter":
			if ($session['user']['race'] == $bname) {
				require_once(
					"race/checkcity.php");
				checkcity_dorace($race);
				$args['creatureexp'] = 
					round($args['creatureexp']*1.1,0);
			}
			break;
		default:
	}
	return $args;
}

function race_run(
	)
{
	page_header("Race Editor");
	addnav("Return To");
	addnav("Superuser Grotto","superuser.php");
	if (httpget("op") == "") {
		addnav("Go To");
		addnav("Add Race",
			"runmodule.php?module=race&op=add");
		
		addnav("Options");
		addnav("Refresh",
			"runmodule.php?module=race");
	}
	if (httpget("op") == "add" || 
		httpget("op") == "edit" || 
		httpget("op") == "del") {
		addnav("Go To");
		addnav("Race Editor",
			"runmodule.php?module=race");
	}
	if (httpget("op") == "") {
		require_once(
			"modules/race/editor.php");
	}
	if (httpget("op") == "add") {
		require_once(
			"modules/race/add.php");
	}
	if (httpget("op") == "edit") {
		require_once(
			"modules/race/edit.php");
	}
	if (httpget("op") == "del") {
		require_once(
			"modules/race/delete.php");
	}
	page_footer();
}
?>