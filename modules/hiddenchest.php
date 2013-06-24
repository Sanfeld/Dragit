<?php
/**************
Name: Hidden Chest
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.0
Release Date: 01-11-2006
About: Player finds a hidden chest in the woods, in which they can
	   then use it to store gold and gems inbetween dk's. They can
	   also forget it's location inbetween dk's as well. Heh.
translation compatible.
*****************/
require_once("lib/http.php");
require_once("lib/showform.php");

function hiddenchest_getmoduleinfo(){
    $info = array(
        "name"=>"Hidden Chest",
        "version"=>"1.0",
        "author"=>"Eth",
        "category"=>"Forest Events",
        "download"=>"http://dragonprime.net/users/Eth/hiddenchest.zip",
        "vertxtloc"=>"http://dragonprime.net/users/Eth/",
        "settings"=>array(
            "Hidden Chest - Main Settings,title",                                    
            "chestchance"=>"Raw Chance of finding a chest?,range,0,100,5|25",            
            "dklose"=>"Raw Chance of losing chest upon a dk? 0 for no chance.,range,0,100,5|25",
            "dkreq"=>"DK's needed to own chest,int|1",
            "chests"=>"How many chests are hidden in the forest?,int|10", 
            "goldlimit"=>"Gold Limit,int|1000",
            "gemslimit"=>"Gems Limit,int|10",           
        ),
        "prefs"=>array(
         	"Hidden Chest - User Settings,title",
         	"hiddenchest"=>"Found one yet?,bool|0",         	
         	"hiddengold"=>"Gold in chest?,int|0",
         	"hiddengems"=>"Gems in chest?,int|0",            
        )
    );
    return $info;
}
function hiddenchest_install(){
	module_addeventhook("forest", "require_once(\"modules/hiddenchest.php\"); return hiddenchest_test();");
	module_addhook("newday");
	module_addhook("dragonkill");
	module_addhook("forest");
	module_addhook("village");
	return true;
}
function hiddenchest_uninstall(){
	return true;
}
function hiddenchest_test(){
	global $session;	
	$chance = get_module_setting("chestchance","hiddenchest");
	if ($session['user']['dragonkills']<get_module_setting("dkreq","hiddenchest")) return 0;
	else if (get_module_setting("chests","hiddenchest")<1) return 0;
	else if (get_module_pref("hiddenchest","hiddenchest") == 1) return 0;	 
	return $chance; 
}
function hiddenchest_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "dragonkill":
		$losechest = get_module_setting("dklose");
		$chance = (e_rand(1,100));		
		if (get_module_pref("hiddenchest") == 1 && $chance<$losechest){
			output("`2You remember for a moment having a chest hidden safely away in the forest,");
			output(" `2but for the life of you, you can't remember how to get to it anymore.");
			output(" `2For that matter, you don't even remember what you had stored in it.`n`n");			
			//say goodbye to that chest.
			set_module_pref("hiddenchest",0);
			set_module_pref("hiddengold",0);
			set_module_pref("hiddengems",0);
			set_module_setting("chests",get_module_setting("chests")+1);
		}			
		break;
		case "forest":
		$from = "runmodule.php?module=hiddenchest&";
		if (get_module_pref("hiddenchest") && get_module_pref("seenchest")==0){
			addnav("`^Seek Out Chest",$from."op=chest&what=choice");
		}
		break;		
		case "newday":
		if (get_module_pref("hiddenchest") == 1){
			//just to remind the players
			output("`@You anxiously hope the valuables you have stored in your hidden chest are still okay.`n");
		}		
		break;
	}
	return $args;
}
function hiddenchest_runevent($type) {
	global $session;
	$from = "runmodule.php?module=hiddenchest&";	
	if ($type == "forest") $from = "forest.php?";
	//elseif ($type == "village") $from = "village.php?";	
    $session['user']['specialinc'] = "module:hiddenchest";	
    $op = httpget('op');    
	switch ($type) {	
	case forest:	
		if ($op=="" || $op=="search") {
			output("`2Far off the beaten path, you decide to take a moment to catch your breath.");		
			output(" `2Taking a seat upon a rock, you glance over to the side and spy an old, weathered chest tucked away in the bushes!`n`n");
			output("`2Dragging it out, and prying it open, you discover to your disappointment that it's empty.");
			output(" `2However, you think, you could store your own gold and gems here safely.");
			output(" `2You're fairly certain too you could find your way back here each time as well.`n`n");
			set_module_pref("hiddenchest",1);
			set_module_setting("chests", get_module_setting("chests")-1);
			$session['user']['specialinc'] = "";
			//addnav("Inspect Chest",$from."op=chest&what=choice");			
			addnav("Return to Forest","forest.php");			
		}
		break;							
	}	
}
function hiddenchest_run(){
	global $session;
	$op = httpget('op');
	$from = "runmodule.php?module=hiddenchest&";	
	page_header("Hidden Chest");	
	if ($op == "chest"){
		switch(httpget('what')){
			case "choice":			
			output("`2Kneeling down before your chest, you ponder as to what you should do.`n`n");
			addnav("Choice");
			addnav("Deposit Gold or Gems",$from."op=chest&what=deposit");
			addnav("Withdraw Gold or Gems",$from."op=chest&what=withdraw");
			addnav("Leave Instead","forest.php");
			break;
			case "deposit":
			$goldlimit = get_module_setting("goldlimit")-get_module_pref("hiddengold");
			$gemslimit = get_module_setting("gemslimit")-get_module_pref("hiddengems");
			output("`2How much gold or gems would you like to leave in your chest?");
			output(" `2You have space to store `^%s gold `2and `%%s gems`2.`n`n", $goldlimit,$gemslimit);			
			$row = array(
				"hiddengold"=>"",			
				"hiddengems"=>"",					
			);
			$chestinfo = array(				
				"hiddengold"=>"Gold to Add,int|0",			
				"hiddengems"=>"Gems to Add,int|0",					
			);
			rawoutput("<form action='runmodule.php?module=hiddenchest&op=chest&what=done&action=deposit' method='POST'>");
			showform($chestinfo,$row);
			addnav("", $from . "op=chest&what=done&action=deposit");
			rawoutput("</form>");
			addnav("Go Back",$from."op=chest&what=choice");
			addnav("Leave Instead","forest.php");
			break;
			case "withdraw":
			output("`2How much gold or gems would you like to take from your chest?`n`n");
			output("`2You currently have `^%s gold `2and `%%s gems `2stored.`n`n", get_module_pref("hiddengold"),get_module_pref("hiddengems"));
			$row = array(
				"hiddengold"=>"",			
				"hiddengems"=>"",					
			);
			$chestinfo = array(				
				"hiddengold"=>"Gold to Take,int|0",			
				"hiddengems"=>"Gems to Take,int|0",					
			);
			rawoutput("<form action='runmodule.php?module=hiddenchest&op=chest&what=done&action=withdraw' method='POST'>");
			showform($chestinfo,$row);
			addnav("", $from . "op=chest&what=done&action=withdraw");
			rawoutput("</form>");
			addnav("Go Back",$from."op=chest&what=choice");
			addnav("Leave Instead","forest.php");
			break;
			case "done":
			//sorry, no decimals...
			$gold = round(httppost('hiddengold'));
			$gems = round(httppost('hiddengems'));
			//just in case. Probably not needed, but one never knows...
			if ($gold == "") $gold = 0;
			if ($gems == "") $gems = 0;
			//
			$goldlimit = get_module_setting("goldlimit")-get_module_pref("hiddengold");
			$gemslimit = get_module_setting("gemslimit")-get_module_pref("hiddengems");
			//
			$action = httpget('action');
			if ($action == "deposit"){
				if ($gold>$session['user']['gold']){
					output("`2You don't have that much gold!`n");
				}else if ($gold>$goldlimit){
					output("`2Couldn't store that much gold!`n");	
				}else if ($gold == 0){
					output("`2No gold deposited!`n");
				}else{
					output("`^%s gold `2deposited in chest.`n", $gold);
					$session['user']['gold']-=$gold;
					set_module_pref("hiddengold",get_module_pref("hiddengold")+$gold);
				}
				if ($gems>$session['user']['gems']){
					output("`2You don't have that many gems!`n");
				}else if ($gems>$gemslimit){
					output("`2Couldn't store that many gems!`n");	
				}else if ($gems == 0){
					output("`2No gems deposited!`n");
				}else{
					output("`%%s gems `2deposited in chest.`n",$gems);
					set_module_pref("hiddengems",get_module_pref("hiddengems")+$gems);
					$session['user']['gems']-=$gems;
				}					
			}else if ($action == "withdraw"){
				if ($gold>get_module_pref("hiddengold")){
					output("`2You don't have that much stored!`n");
				}else{
					output("`^%s gold `2taken from chest.`n",$gold);					
					set_module_pref("hiddengold",get_module_pref("hiddengold")-$gold);
					$session['user']['gold']+=$gold;
				}
				if ($gems>get_module_pref("hiddengems")){
					output("`2You don't have that many gems!`n`n");
				}else{
					output("`%%s gems `2taken from chest.`n",$gems);
					set_module_pref("hiddengems",get_module_pref("hiddengems")-$gems);
					$session['user']['gems']+=$gems;
				}		
			}
			addnav("Go Back",$from."op=chest&what=choice");
			break;
		}
	}
	page_footer();	
}
?>