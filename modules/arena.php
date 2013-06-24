<?php
//ver 1.1..colliseum added
//ver 1.2 magical arena added
//ver 1.3 few bug fixes, added new special to magical arena
//ver 1.4 changed battle code removed colliseum - seperately downloadable module
function arena_getmoduleinfo(){
	$info = array(
		"name" => "Gladiator Arena's",
		"author" => "`b`&Ka`6laza`&ar`b",
		"version" => "1.4",
		"Download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1070",
		"category" => "Village",
		"description" => "Gladiator Arena's, PVP and Magical",
		"settings"=>array(
			"Gladiator Arena, title",
			"name"=>"Arena name,int|PVP Arena",
			"name2"=>"Arena 2's name,int|Magical Arena",
			"arenaloc"=>"Where does the Arena appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"list"=>"how many on hof,int|25",
			
		),
		"prefs"=>array(
			"PVP Arena,title",
				"pvpreg"=>"registered in pvp arena,enum,0,no,1,yes,2,attackable",
				"fight"=>"fight status, enum,0,firsthit,1,waiting,2,atk,3,none,4,acceptdecline|3",
				"battleid"=>"current battleid,int|0",
				"These are for coding purposes only,note",
				"cancelled"=>"fight cancelled,bool|0",
				"min"=>"time since last move,int|",
				"timeout"=>"timed out,bool|0",
				"lasthit"=>"amount of last hit,int|0",
				"bonushit"=>"amount of bonus hit,int|0",
			"Magical Arena,title",
				"magicreg"=>"registered in magical arena,enum,0,no,1,yes,2,attackable",
				"mfight"=>"fight status, enum,0,firsthit,1,waiting,2,atk,3,none,4,acceptdecline|3",
				"mbattleid"=>"current battleid,int|0",
				"These are for coding purposes only,note",
				"mcancelled"=>"fight cancelled,bool|0",
				"mmin"=>"time since last move,int|",
				"mtimeout"=>"timed out,bool|0",
				"mlasthit"=>"amount of last hit,int|0",
				"mbonushit"=>"amount of bonus hit,int|0",
				),
		);
		return $info;
}
function arena_install(){
	require_once("lib/tabledescriptor.php"); 
	 $arena = array(
		'battleid'=>array('name'=>'battleid', 'type'=>'int unsigned',	'extra'=>'not null auto_increment'),
		'type'=>array('name'=>'type', 'type'=>'int unsigned',	'extra'=>'not null'),
		'lvl'=>array('name'=>'lvl', 'type'=>'int unsigned',	'extra'=>'not null'),
		'id1'=>array('name'=>'id1', 'type'=>'int unsigned',	'extra'=>'not null'),
		'name1'=>array('name'=>'name1', 'type'=>'text',	'extra'=>'not null'),
		'id2'=>array('name'=>'id2', 'type'=>'int unsigned',	'extra'=>'not null'),
		'name2'=>array('name'=>'name2', 'type'=>'text',	'extra'=>'not null'),
		'atk1'=>array('name'=>'atk1', 'type'=>'int unsigned', 'extra'=>'not null'),
		'atk2'=>array('name'=>'atk2', 'type'=>'int unsigned', 'extra'=>'not null'),
		'def1'=>array('name'=>'def1', 'type'=>'int unsigned', 'extra'=>'not null'),
		'def2'=>array('name'=>'def2', 'type'=>'int unsigned', 'extra'=>'not null'),
		'hp1'=>array('name'=>'hp1', 'type'=>'int unsigned',	'extra'=>'not null'),
		'hp2'=>array('name'=>'hp2', 'type'=>'int unsigned',	'extra'=>'not null'),
		'key-PRIMARY'=>array('name'=>'PRIMARY', 'type'=>'primary key',	'unique'=>'1', 'columns'=>'battleid'));
	$arenastats = array(
		'gladiatorid'=>array('name'=>'gladiatorid', 'type'=>'int unsigned',	'extra'=>'not null auto_increment'),
		'id'=>array('name'=>'id', 'type'=>'int unsigned',	'extra'=>'not null'),
		'pvpwins'=>array('name'=>'pvpwins', 'type'=>'int unsigned',	'extra'=>'not null'),
		'pvploss'=>array('name'=>'pvploss', 'type'=>'int unsigned', 'extra'=>'not null'),
		'magicwins'=>array('name'=>'magicwins', 'type'=>'int unsigned', 'extra'=>'not null'),
		'magicloss'=>array('name'=>'magicloss', 'type'=>'int unsigned', 'extra'=>'not null'),
		'collwins'=>array('name'=>'collwins', 'type'=>'int unsigned', 'extra'=>'not null'),
		'collloss'=>array('name'=>'collloss', 'type'=>'int unsigned', 'extra'=>'not null'),
		'key-PRIMARY'=>array('name'=>'PRIMARY', 'type'=>'primary key',	'unique'=>'1', 'columns'=>'gladiatorid'));
		synctable(db_prefix('arena'), $arena, true);
		synctable(db_prefix('arenastats'), $arenastats, true);
	module_addhook("changesetting");
	module_addhook("village");
	module_addhook("newday");
	module_addhook("footer-hof");
	//module_addhook("charstats");
	//module_addhook_priority("footer-prefs",75);	
	return true;
}
function arena_uninstall(){
	debug("Dropping arena table");
    $sql = "DROP TABLE IF EXISTS " . db_prefix("arena");
    db_query($sql);
    debug("Dropping arenastats table");
    $sql = "DROP TABLE IF EXISTS " . db_prefix("arenastats");
    db_query($sql);
    return true;
}
function arena_dohook($hookname,$args){
	global $session;
	$pvpreg=get_module_pref("pvpreg");
	$magicreg=get_module_pref("magicreg");
	$id = $session['user']['acctid'];
	switch ($hookname){
		case "changesetting":
			if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("arenaloc")) {
			set_module_setting("arenaloc", $args['new']);
			}
		}
		break;
		case "village":
			if ($session['user']['location'] == get_module_setting("arenaloc"))
			tlschema($args['schemas']['gatenav']);
		    addnav($args['gatenav']);
			tlschema();
			addnav("`b`#Battlegrounds`b","runmodule.php?module=arena&op=enter");
        	
	        if ($pvpreg==1 || $magicreg==1){
		    	tlschema($args['schemas']['gatenav']);
		    	addnav($args['gatenav']);
				tlschema();
		    	output("You have been challenged");
		    	addnav("`b`#Arena Challenge`b", "runmodule.php?module=arena&op=challenged");
	        }
	        if ($pvpreg==2){  
				set_module_pref("fight",3,"arena",$id);
			    clear_module_pref("timeout","arena",$id);
	    		clear_module_pref("cancelled","arena",$id);
	        	
        	}
        	if ($magicreg==2){
	        	set_module_pref("mfight",3,"arena",$id);
	    	    clear_module_pref("mtimeout","arena",$id);
	        	clear_module_pref("mcancelled","arena",$id);
        	}
	        break;
		case "newday":
			if ($pvpreg==1){
				set_module_pref("pvpreg",2);
			}
			break;
		case "footer-hof":
			addnav("Warrior Rankings");
			addnav("PVP Arena", "runmodule.php?module=arena&op=pvphof");
			break;
		
		}
	return $args;
}
function arena_run(){
	global $SCRIPT_NAME;
	if ($SCRIPT_NAME == "runmodule.php"){
		$module=httpget("module");
		if ($module == "arena") {
			include("modules/arena/arena.php");
		}
	}
}
	
?>