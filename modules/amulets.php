<?php

function amulets_getmoduleinfo(){
	$info = array(
		"name"=>"Amulets",
		"version"=>"2.37",
		"author"=>"`#Lonny Luberts",
		"category"=>"PQcomp",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=90",
		"vertxtloc"=>"http://www.pqcomp.com/",
		"prefs"=>array(
			"Amulets Module User Prefs,title",
            "amulet"=>"Has Amulet,viewonly|",
        ),
        "settings"=>array(
        	"Amulets Behavior Settings,title",
        	"godname"=>"Name of the God who gives Amulets (created this so we could honor a moderator),text|Zeus",
        	"findperc"=>"Percentage - How often when getting special will amulet be awarded,int|33",
        	"takeperc"=>"Percentage - How often will an amulet be taken from one player and given to another,int|33",
        	"dragloose"=>"Does player keep amulet after Dragon Kill?,bool|1",
        	"Amulets Owner Settings,title",
        	"shamrock"=>"Shamrock Amulet owner id,int|0",
        	"triquetra"=>"Triquetra Amulet owner id,int|0",
        	"heart"=>"Heart Amulet owner id,int|0",
        	"cross"=>"Cross Amulet owner id,int|0",
        	"ankh"=>"Ankh Amulet owner id,int|0",
        	"pegasus"=>"Pegasus Amulet owner id,int|0",
        	"unicorn"=>"Unicorn Amulet owner id,int|0",
        	"phoenix"=>"Phoenix Amulet owner id,int|0",
        	"dragon"=>"Flying Dragon Amulet owner id,int|0",
        	"yinyang"=>"YinYang Amulet owner id,int|0",
        	"artemis"=>"Artemis Amulet owner id,int|0",
        	"horace"=>"Horace Amulet owner id,int|0",
        	"star"=>"Star Amulet owner id,int|0",
        	"salamander"=>"Salamander Amulet owner id,int|0",
        	"bastet"=>"Bastet Amulet owner id,int|0",
        	"thor"=>"Thor Amulet owner id,int|0",
        	"anubis"=>"Anubis Amulet owner id,int|0",
        	"apollo"=>"Apollo Amulet owner id,int|0",
        	"dionysos"=>"Dionysos Amulet owner id,int|0",
        	"hermes"=>"Hermes Amulet owner id,int|0",
        ),
	);
	return $info;
}

function amulets_install(){
	if (!is_module_active('amulets')){
		output("`4Installing Amulets Module.`n");
	}else{
		output("`4Updating Amulets Module.`n");
	}
	module_addhook("charstats");
	module_addhook("newday");
	module_addhook("battle-victory");
	module_addhook("footer-hof");
	module_addhook("apply-specialties");
	module_addhook("shades");
	module_addhook("dragonkill");
	module_addhook("newday-runonce");
	module_addeventhook("forest", "return 100;");
	module_addeventhook("travel", "return 33;");
	return true;
}

function amulets_uninstall(){
	output("`4Un-Installing Amulets Module.`n");
	return true;
}

function amulets_dohook($hookname,$args){
	global $session;
	global $badguy;
	$amulet = get_module_pref("amulet");
	switch($hookname){
	case "charstats":
		if ($amulet <> ""){
			addcharstat("Equipment Info");
			$amulet = ucfirst($amulet);
			if ($amulet == "Dragon") $amulet = "Flying Dragon";
			if ($amulet == "Star") $amulet = "Star of Solomon";
			addcharstat("Amulet",$amulet);
		}
	break;
	case "newday-runonce":
		$sql = "SELECT userid,value FROM ".db_prefix("module_userprefs")." where modulename = 'amulets' AND setting = 'amulet' AND value <> ''";
		$result = db_query($sql);
		for($i=1;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			if (get_module_setting($row['value'],'amulets') <> $row['userid']) clear_module_pref('amulet','amulets',$row['userid']);
		}
	break;
	case "newday":
		if ($amulet <> ""){
			if (get_module_setting($amulet != $session['user']['acctid'])){
				clear_module_pref("amulet");
				return $args;
			}
			//clean up any mess we may have
			//check the amulet owner and clear anyone else who should not have it
			switch($amulet){
			case "shamrock":
				output("You rub your Shamrock Amulet between your fingers.`n");
			break;	
			case "triquetra":
				output("You rub your Triquetra Amulet between your fingers.`n");
			break;
			case "heart":
				output("You rub your Heart Amulet between your fingers.`n");
				output("You feel its incredible power!`n");
				$session['user']['charm']+=1;
			break;
			case "cross":
				if (is_module_active("alignment")){
					output("You rub your Cross Amulet between your fingers.`n");
					output("You feel its incredible power!`n");
					require_once("./modules/alignment/func.php");
					align(2);
				}else{
					$session['user']['hitpoints'] = $session['user']['hitpoints'] * 2;
				}
			break;
			case "ankh":
				output("You rub your Ankh Amulet between your fingers.`n");
			break;
			case "pegasus":
				output("You rub your Pegasus Amulet between your fingers.`n");
				output("You feel its incredible power! You gain 3 Turns!`n");
				$session['user']['turns'] +=3;
			break;	
			case "unicorn":
				output("You rub your Unicorn Amulet between your fingers.`n");
				output("You feel its incredible power!`n");
				apply_buff('unicornamulet',array(
						 "startmsg"=>"`n`^Your Unicorn Amulet Heals you!",
                   		 "name"=>"`%Unicorn Amulet",
                  		 "rounds"=>200,
                  		 "minioncount"=>1,
                  		 "regen"=>$session['user']['level'],
                  		 "effectmsg"=>"`!You heal for ".$session['user']['level']." hitpoints!",
                   		 "activate"=>"offense"
					));
			break;
			case "phoenix":
				output("You rub your Phoenix Amulet between your fingers.`n");
				output("You feel its incredible power!`n");
				apply_buff('phoenixamulet',array(
						 "startmsg"=>"`n`^Your Phoenix Amulet Heals you!",
                   		 "name"=>"`%Phoenix Amulet",
                  		 "rounds"=>300,
                  		 "minioncount"=>1,
                  		 "regen"=>($session['user']['level'] * 2),
                  		 "effectmsg"=>"`!You heal for ".($session['user']['level'] * 2)." hitpoints!",
                   		 "activate"=>"offense"
					));
			break;
			case "dragon":
				output("You rub your Flying Dragon Amulet between your fingers.`n");
				output("You feel its incredible power!`n");
				apply_buff('dragonamulet',array(
						 "startmsg"=>"`n`^You feel the power of the Flying Dragon in you!",
                   		 "name"=>"`%Flying Dragon",
                  		 "rounds"=>200,
                   		 "atkmod"=>1.5,
                   		 "activate"=>"offense"
					));
			break;
			case "yinyang":
				output("You rub your Flying Dragon Amulet between your fingers.`n");
				output("You feel its incredible power!`n");
				if ($session['user']['attack'] < $session['user']['defense']) $session['user']['attack'] = $session['user']['defense'];
				if ($session['user']['defense'] < $session['user']['attack']) $session['user']['defense'] = $session['user']['attack'];
			break;
			case "artemis":
				output("You rub your Artemis Amulet between your fingers.`n");
				output("You feel its incredible power!`n");
				apply_buff('artemisamulet',array(
						 "startmsg"=>"`n`^You feel the power of the Artemis within you!",
                   		 "name"=>"`%Power of Artemis",
                  		 "rounds"=>200,
                   		 "atkmod"=>1.5,
                   		 "activate"=>"offense"
					));
			break;	
			case "horace":
				output("You rub your Horace Amulet between your fingers.`n");
			break;
			case "star":
				output("You rub your Star of Solomon Dragon Amulet between your fingers.`n");
				output("You feel its incredible power!  You gain 500 gold!`n");
				$session['user']['gold'] += 500;
			break;
			case "salamander":
				output("You rub your Salamander Amulet between your fingers.`n");
				output("You feel its incredible power!`n");
				apply_buff('salamanderamulet',array(
						 "startmsg"=>"`n`^The Power of the Salamander is with you!",
                   		 "name"=>"`%Power of the Salamander",
                  		 "rounds"=>200,
			 			 "badguyatkmod"=>$badguy['creatureattack'] - (($session['user']['level'] * 2)),
                   		 "activate"=>"defence"
					));
			break;
			case "bastet":
				output("You rub your Bastet Amulet between your fingers.`n");
				output("You feel its incredible power!`n");
				$session['user']['charm']+=1;
			break;
			case "thor":
				output("You rub your Apollo Amulet between your fingers.`n");
				output("You feel its incredible power!`n");
				apply_buff('thor',array(
						 "startmsg"=>"`n`^You feel the power of Thor's Hammer in your Weapon!",
                   		 "name"=>"`%Thor's Hammer",
                  		 "rounds"=>200,
                   		 "atkmod"=>1.5,
                   		 "activate"=>"offense"
					));
			break;	
			case "anubis":
				output("You rub your Anubis Amulet between your fingers.`n");
			break;
			case "apollo":
				if (is_module_active("secondweapon")){
					output("You rub your Apollo Amulet between your fingers.`n");
					output("You feel its incredible power!`n");
					set_module_pref('weaponskill',get_module_pref('weaponskill','secondweapon') + 5,'secondweapon');
				}else{
					$session['user']['hitpoints'] = $session['user']['hitpoints'] * 2;
				}
			break;
			case "dionysos":
				if (is_module_active("drinks")){
					output("You rub your Dionysos Amulet between your fingers.`n");
					output("You feel its incredible power!");
					set_module_pref('drunkeness',66,'drinks');
				}else{
					$session['user']['hitpoints'] = $session['user']['hitpoints'] * 2;
				}
			break;
			case "hermes":
				if (is_module_active("trading")){
					output("You rub your Hermes Amulet between your fingers.`n");
					output("You feel its incredible power!");
					set_module_pref('dailytrades', get_module_pref('dailytrades','trading') + 10,'trading');
				}else{
					$session['user']['hitpoints'] = $session['user']['hitpoints'] * 2;
				}
			break;
			}
		}
	break;
	case "battle-victory":
		if ($amulet == "shamrock" AND $badguy['type'] == "forest"){
			$badguy['creaturegold'] += round($badguy['creaturegold'] * .25);
			debug("Boosting Creature Gold for Shamrock Amulet");
		}
		if ($amulet == "ankh" AND $badguy['type'] == "forest" AND $session['user']['hitpoints'] < $session['user']['maxhitpoints']){
			output("`@Your Ankh Amulet glows with a healing power.`n");
			$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
		}
	break;
	case "footer-hof":
		addnav("Amulets");
		addnav("Amulet Holders","runmodule.php?module=amulets");
	break;
	case "apply-specialties":
		if ($amulet == "horace" AND $session['user']['alive'] == 0){
			apply_buff('horaceamulet',array(
						 "startmsg"=>"`n`^Your Horace Amulet Glows, emopowering you!",
                   		 "name"=>"`%Horace Amulet",
                  		 "rounds"=>1,
                  		 "minioncount"=>1,
                   		 "atkmod"=>1.5,
                   		 "activate"=>"offense"
					));
		}
		//I'm not sure if this is going to work in the graveyard
		if ($amulet == "anubis" AND $session['user']['alive'] == 0){
			apply_buff('anubisamulet',array(
						 "startmsg"=>"`n`^Your Anubis Amulet Glows, emopowering you!",
                   		 "name"=>"`%Anubis Amulet",
                  		 "rounds"=>1,
                  		 "minioncount"=>1,
                   		 "atkmod"=>1.5,
                   		 "activate"=>"offense"
					));
		}
	break;
	case "shades":
		if ($amulet == "triquetra"){
			addnav("`^Triquetra Amulet","runmodule.php?module=amulets&op=shades");
		}
	break;
	case "dragonkill":
		if (get_module_setting("dragloose") == 0){
			set_module_setting(get_module_pref("amulet"),0);
			clear_module_pref("amulet");
		}
	break;
	}
	return $args;
}

function amulets_runevent($type){
	include("modules/amulets/amulets_event.php");
}

function amulets_run(){
	include("modules/amulets/amulets.php");
}

function amulets_takeamulet($amulet,$name){
	global $session;
	clear_module_pref('amulet','amulets',get_module_setting($amulet));
	set_module_setting($amulet,$session['user']['acctid']);
	set_module_pref('amulet',$amulet);
	$amulet = ucfirst($amulet);
	if ($amulet == "Dragon") $amulet = "Flying Dragon";
	if ($amulet == "Star") $amulet = "Star of Solomon";
	output("You have been given the %s Amulet!`n",$amulet);
	addnews("`#The %s Amulet has been taken from %s and given to %s!`n",$amulet,$name,$session['user']['name']);
	require_once("lib/systemmail.php");
	systemmail($name,"`2Your Amulet has been taken!`2","Your amulet has been taken from you and given to".$session['user']['name'].".");
}

function amulets_giveamulet($amulet){
	global $session;
	set_module_setting($amulet,$session['user']['acctid']);
	set_module_pref('amulet',$amulet);
	$amulet = ucfirst($amulet);
	if ($amulet == "Dragon") $amulet = "Flying Dragon";
	if ($amulet == "Star") $amulet = "Star of Solomon";
	output("You have been given the %s Amulet!`n",$amulet);
	addnews("`#%s has recieved the %s Amulet!`n",$session['user']['name'],$amulet);
}

function amulets_getowner($amulet){
	$sql = "SELECT name from ".db_prefix("accounts")." WHERE acctid = '".get_module_setting($amulet)."'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	return $row['name'];
}

function amulets_nogive($amulet,$name){
	global $session;
	$amulet = ucfirst($amulet);
	if ($amulet == "Dragon") $amulet = "Flying Dragon";
	if ($amulet == "Star") $amulet = "Star of Solomon";
	output("I am sorry, but %s already has the %s Amulet and at this time I feel that you are not as worthy.`n",$name,$amulet);
	addnews("`#%s has been found unworthy of the %s Amulet.",$session['user']['name'],$amulet);
}
?>