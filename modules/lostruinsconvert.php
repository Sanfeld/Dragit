<?php
function lostruinsconvert_getmoduleinfo(){
	$info = array(
		"name"=>"Lost Ruins Converter",
		"version"=>"5.0",
		"author"=>"DaveS",
		"category"=>"Converter",
		"download"=>"",
		"settings"=>array(
			"Lost Ruins,title",
			"exploreturns"=>"How many turns can they search in the ruins each day?,int|3",
			"runonce"=>"When should turns be reset?,enum,0,New Day,1,System New Day|0",
			"poisoned"=>"Allow player to poison random other player?,bool|1",
			"sexchange"=>"Number of days the sex change lasts?,int|5",
			"case3g"=>"`5Case 3: Gold reward for catching a lieing beggar:,int|2154",
			"case3ge"=>"`5Case 3: Gem reward for catching a lieing beggar:,int|3",
			"case7g"=>"`6Case 7: Gold reward for waving to God:,int|1225",
			"case8g"=>"`5Case 8: Gold reward for Excavating a Blessed Mummy:,int|350",
			"case10gs"=>"`6Case 10: Gold reward for Small Pirate Treasure:,int|100",
			"case10ges"=>"`6Case 10: Gem reward for Small Pirate Treasure:,int|2",
			"case10g"=>"`6Case 10: Gold reward for Large Pirate Treasure:,int|640",
			"case10ge"=>"`6Case 10: Gem reward for Large Pirate Treasure:,enum,1,1-2 Gems,2,2-3 Gems,3,3-5 Gems,4,5-10 Gems|2",
			"case11g"=>"`5Case 11: Gold reward for slipping poison to a Rich Knight:,int|1500",
			"case11g2"=>"`5Case 11: Gold profit for selling water to old people:,int|1250",
			"case11g3"=>"`5Case 11: Gold profit for selling lemonade:,int|800",
			"case15g"=>"`6Case 15: Gold reward for kissing the frog princess:,int|253",
			"case17g"=>"`5Case 17: Gold reward for meeting Evad the Scrivener:,int|1000",
			"case17ge"=>"`5Case 17: Gem reward for meeting Evad the Scrivener:,int|2",
			"case18g"=>"`6Case 18: Gold gift for another player from Lakinne the Great:,int|1000",
			"case25ge"=>"`5Case 25: Gem reward for not finding the quarry:,enum,1,1-2 Gems,2,2-3 Gems,3,3-5 Gems,4,5-10 Gems|3",
			"quarrytrig"=>"How many counters trigger before finding the quarry?,int|3",
			"quarrycount"=>"How many counters are there currently?,int|0",
			"quarryfound"=>"Has the Quarry been found?,bool|0",
			"qgold"=>"Gold reward for finding the Quarry?,int|1000",
			"qgems"=>"Gem reward for finding the Quarry?,int|5",
			"ruin1trig"=>"How many counters trigger before finding the Ruin World 1?,int|4",
			"ruin1count"=>"How many counters are there currently?,int|0",
			"ruin1found"=>"Has Ruin World 1 been found?,bool|0",
			"ruin2trig"=>"How many counters trigger before finding the Ruin World 2?,int|4",
			"ruin2count"=>"How many counters are there currently?,int|0",
			"ruin2found"=>"Has Ruin World 2 been found?,bool|0",
			"ruin3trig"=>"How many counters trigger before finding the Ruin World 3?,int|4",
			"ruin3count"=>"How many counters are there currently?,int|0",
			"ruin3found"=>"Has Ruin World 3 been found?,bool|0",
		),
		"prefs"=>array(
			"Lost Ruins,title",
			"allprefs"=>"Preferences for Lost Ruins,textarea|",
		),	
		"requires"=>array(
			"lostruins"=>"3.0|by DaveS",
		),
	);
	return $info;
}
function lostruinsconvert_install(){
	module_addhook("superuser");
	$sql = "SELECT acctid FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		$id=$row['acctid'];
		$allprefs=unserialize(get_module_pref('allprefs',"lostruinsconvert",$id));
		$allprefs['firstruin']=get_module_pref("firstruin","lostruins",$id);
		$allprefs['usedexpts']=get_module_pref("usedexpts","lostruins",$id);
		$allprefs['sexcount']=get_module_pref("sexcount","lostruins",$id);
		set_module_pref("allprefs",serialize($allprefs),"lostruinsconvert",$id);
	}
	set_module_setting("exploreturns",get_module_setting("exploreturns","lostruins"),"lostruinsconvert");
	set_module_setting("runonce",get_module_setting("runonce","lostruins"),"lostruinsconvert");
	set_module_setting("poisoned",get_module_setting("poisoned","lostruins"),"lostruinsconvert");
	set_module_setting("sexchange",get_module_setting("sexchange","lostruins"),"lostruinsconvert");
	set_module_setting("case3g",get_module_setting("case3g","lostruins"),"lostruinsconvert");
	set_module_setting("case3ge",get_module_setting("case3ge","lostruins"),"lostruinsconvert");
	set_module_setting("case7g",get_module_setting("case7g","lostruins"),"lostruinsconvert");
	set_module_setting("case8g",get_module_setting("case8g","lostruins"),"lostruinsconvert");
	set_module_setting("case10gs",get_module_setting("case10gs","lostruins"),"lostruinsconvert");
	set_module_setting("case10ges",get_module_setting("case10ges","lostruins"),"lostruinsconvert");
	set_module_setting("case10g",get_module_setting("case10g","lostruins"),"lostruinsconvert");
	set_module_setting("case11g2",get_module_setting("case11g2","lostruins"),"lostruinsconvert");
	set_module_setting("case11g3",get_module_setting("case11g3","lostruins"),"lostruinsconvert");
	set_module_setting("case15g",get_module_setting("case15g","lostruins"),"lostruinsconvert");
	set_module_setting("case17g",get_module_setting("case17g","lostruins"),"lostruinsconvert");
	set_module_setting("case17ge",get_module_setting("case17ge","lostruins"),"lostruinsconvert");
	set_module_setting("case18g",get_module_setting("case18g","lostruins"),"lostruinsconvert");
	set_module_setting("case25ge",get_module_setting("case25ge","lostruins"),"lostruinsconvert");
	set_module_setting("quarrytrig",get_module_setting("quarrytrig","lostruins"),"lostruinsconvert");
	set_module_setting("quarrycount",get_module_setting("quarrycount","lostruins"),"lostruinsconvert");
	set_module_setting("quarryfound",get_module_setting("quarryfound","lostruins"),"lostruinsconvert");
	set_module_setting("qgold",get_module_setting("qgold","lostruins"),"lostruinsconvert");
	set_module_setting("qgems",get_module_setting("qgems","lostruins"),"lostruinsconvert");
	set_module_setting("ruin1trig",get_module_setting("ruin1trig","lostruins"),"lostruinsconvert");
	set_module_setting("ruin1count",get_module_setting("ruin1count","lostruins"),"lostruinsconvert");
	set_module_setting("ruin1found",get_module_setting("ruin1found","lostruins"),"lostruinsconvert");
	set_module_setting("ruin2trig",get_module_setting("ruin2trig","lostruins"),"lostruinsconvert");
	set_module_setting("ruin2count",get_module_setting("ruin2count","lostruins"),"lostruinsconvert");
	set_module_setting("ruin2found",get_module_setting("ruin2found","lostruins"),"lostruinsconvert");
	set_module_setting("ruin3trig",get_module_setting("ruin3trig","lostruins"),"lostruinsconvert");
	set_module_setting("ruin3count",get_module_setting("ruin3count","lostruins"),"lostruinsconvert");
	set_module_setting("ruin3found",get_module_setting("ruin3found","lostruins"),"lostruinsconvert");

	output("`b`nPLEASE DO NOT UNINSTALL THE LOST RUINS CONVERTER MODULE YET.");
	output("`nYou should now UNINSTALL the OLD lostruins module.");
	output("`nAfter you have uninstalled the Old lostruins Module, Copy the new lostruins to your module directory and install it.");
	output("`nThen go to the grotto to Converters: Convert Lost Ruins.`n`n`b");
	return true;
}
function lostruinsconvert_uninstall(){
	return true;
}
function lostruinsconvert_dohook($hookname,$args){
	switch($hookname){
		case "superuser":
			addnav("Converters");
			addnav("Convert Lost Ruins","runmodule.php?module=lostruinsconvert&op=super");
		break;
	}
	return $args;
}
function lostruinsconvert_run(){
	global $session;
	$op = httpget('op');
	page_header("Lost Ruins Converter");
	if ($op=="super"){
		$sql = "SELECT acctid FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$id=$row['acctid'];
			$allprefs=get_module_pref("allprefs","lostruinsconvert",$id);
			set_module_pref("allprefs",$allprefs,"lostruins",$id);
		}
		set_module_setting("exploreturns",get_module_setting("exploreturns","lostruinsconvert"),"lostruins");
		set_module_setting("runonce",get_module_setting("runonce","lostruinsconvert"),"lostruins");
		set_module_setting("poisoned",get_module_setting("poisoned","lostruinsconvert"),"lostruins");
		set_module_setting("sexchange",get_module_setting("sexchange","lostruinsconvert"),"lostruins");
		set_module_setting("case3g",get_module_setting("case3g","lostruinsconvert"),"lostruins");
		set_module_setting("case3ge",get_module_setting("case3ge","lostruinsconvert"),"lostruins");
		set_module_setting("case7g",get_module_setting("case7g","lostruinsconvert"),"lostruins");
		set_module_setting("case8g",get_module_setting("case8g","lostruinsconvert"),"lostruins");
		set_module_setting("case10gs",get_module_setting("case10gs","lostruinsconvert"),"lostruins");
		set_module_setting("case10ges",get_module_setting("case10ges","lostruinsconvert"),"lostruins");
		set_module_setting("case10g",get_module_setting("case10g","lostruinsconvert"),"lostruins");
		set_module_setting("case11g2",get_module_setting("case11g2","lostruinsconvert"),"lostruins");
		set_module_setting("case11g3",get_module_setting("case11g3","lostruinsconvert"),"lostruins");
		set_module_setting("case15g",get_module_setting("case15g","lostruinsconvert"),"lostruins");
		set_module_setting("case17g",get_module_setting("case17g","lostruinsconvert"),"lostruins");
		set_module_setting("case17ge",get_module_setting("case17ge","lostruinsconvert"),"lostruins");
		set_module_setting("case18g",get_module_setting("case18g","lostruinsconvert"),"lostruins");
		set_module_setting("case25ge",get_module_setting("case25ge","lostruinsconvert"),"lostruins");
		set_module_setting("quarrytrig",get_module_setting("quarrytrig","lostruinsconvert"),"lostruins");
		set_module_setting("quarrycount",get_module_setting("quarrycount","lostruinsconvert"),"lostruins");
		set_module_setting("quarryfound",get_module_setting("quarryfound","lostruinsconvert"),"lostruins");
		set_module_setting("qgold",get_module_setting("qgold","lostruinsconvert"),"lostruins");
		set_module_setting("qgems",get_module_setting("qgems","lostruinsconvert"),"lostruins");
		set_module_setting("ruin1trig",get_module_setting("ruin1trig","lostruinsconvert"),"lostruins");
		set_module_setting("ruin1count",get_module_setting("ruin1count","lostruinsconvert"),"lostruins");
		set_module_setting("ruin1found",get_module_setting("ruin1found","lostruinsconvert"),"lostruins");
		set_module_setting("ruin2trig",get_module_setting("ruin2trig","lostruinsconvert"),"lostruins");
		set_module_setting("ruin2count",get_module_setting("ruin2count","lostruinsconvert"),"lostruins");
		set_module_setting("ruin2found",get_module_setting("ruin2found","lostruinsconvert"),"lostruins");
		set_module_setting("ruin3trig",get_module_setting("ruin3trig","lostruinsconvert"),"lostruins");
		set_module_setting("ruin3count",get_module_setting("ruin3count","lostruinsconvert"),"lostruins");
		set_module_setting("ruin3found",get_module_setting("ruin3found","lostruinsconvert"),"lostruins");
		output("Conversion Complete.  You may now Uninstall the lostruins Converter Module.");
		addnav("Navigation");
		addnav("Return to the Grotto","superuser.php");
		addnav("Manage Modules","modules.php");
		villagenav();
	}
page_footer();
}
?>