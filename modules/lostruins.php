<?php
function lostruins_getmoduleinfo(){
	$info = array(
		"name"=>"The Lost Ruins",
		"version"=>"5.24",
		"author"=>"DaveS",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=174",
		"description"=>"Loading program to launch other worlds by random discovery",
		"settings"=>array(
			"Lost Ruins,title",
			"exploreturns"=>"How many turns can they search in the ruins each day?,int|3",
			"runonce"=>"When should turns be reset?,enum,0,New Day,1,System New Day|0",
			"poisoned"=>"Allow player to poison random other player?,bool|1",
			"sexchange"=>"Number of days the sex change lasts?,range,0,20,2|6",
			"Note: Set to zero to disable,note",
			"limitloc"=>"Limit Lost Ruins to exist in only one city?,bool|0",
			"ruinsloc"=>"If Limited: Where does the Lost Ruins appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"Rewards,title",
			"You can set gem rewards to zero for any event but try to give at least one gold for all events,note",
			"case2"=>"`6Case 2: Minimum level for players to instantly get Monkey Paw Gold:,range,1,15,1|8",
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
			"case25ge"=>"`5Case 25: Gem reward for not finding the quarry:,enum,1,1-2 Gems,2,2-3 Gems,3,3-5 Gems,4,5-10 Gems,5,0-1 Gems|3",
			"Quarry Settings,title",
			"quarrytrig"=>"How many counters trigger before finding the quarry?,int|3",
			"quarrycount"=>"How many counters are there currently?,int|0",
			"quarryfound"=>"Has the Quarry been found?,bool|0",
			"qgold"=>"Gold reward for finding the Quarry?,int|1000",
			"qgems"=>"Gem reward for finding the Quarry?,int|5",
			"Ruin World 1, title",
			"ruin1trig"=>"How many counters trigger before finding the Ruin World 1?,int|4",
			"ruin1count"=>"How many counters are there currently?,int|0",
			"ruin1found"=>"Has Ruin World 1 been found?,bool|0",
			"Ruin World 2, title",
			"ruin2trig"=>"How many counters trigger before finding the Ruin World 2?,int|4",
			"ruin2count"=>"How many counters are there currently?,int|0",
			"ruin2found"=>"Has Ruin World 2 been found?,bool|0",
			"Ruin World 3, title",
			"ruin3trig"=>"How many counters trigger before finding the Ruin World 3?,int|4",
			"ruin3count"=>"How many counters are there currently?,int|0",
			"ruin3found"=>"Has Ruin World 3 been found?,bool|0",
		),
		"prefs"=>array(
			"Lost Ruins,title",
			"Note: Please edit with caution. Consider using the Allprefs Editor instead.,note",
			"allprefs"=>"Preferences for Lost Ruins,textarea|",
		),
	);
	return $info;
}
function lostruins_install(){
	module_addhook("village");
	module_addhook("newday");
	module_addhook("newday-runonce");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
    return true;
}
function lostruins_uninstall(){
	return true;
}
function lostruins_dohook($hookname,$args){
	global $session;
	require("modules/lostruins/dohook/$hookname.php");
	return $args;
}
function lostruins_run(){
	include("modules/lostruins/lostruins.php");
}
?>