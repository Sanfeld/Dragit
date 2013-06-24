<?php
function oceanquest_getmoduleinfo(){
	$info = array(
		"name"=>"Ocean Quest",
		"version"=>"5.25",
		"author"=>"DaveS",
		"category"=>"Forest",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1180",
		"description"=>"Explore the Ocean on a Quest from the King",
		"settings"=>array(
			"Ocean Quest,title",
			"ruler"=>"Who is the Ruler of this Kingdom?,text|King Arthur",
			"dockdks"=>"Minimum Dks required to go to the docks:,int|1",
			"limitloc"=>"Limit Docks to exist in only one forest?,bool|0",
			"oceanloc"=>"If Limited: Where do the Docks appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"Note: Do NOT leave the Docks in a city without a Forest.,note",
			"interface"=>"Disable the graphics interface?,bool|0",
			"pictures"=>"Disable pictures?,bool|0",
			"Fishing,title",
			"fishingpole"=>"Price of a fishingpole:,int|500",
			"fishingbait"=>"Price of nightcrawlers per day:,int|25",
			"dockfish"=>"Biggest fish caught on the dock:,int|0",
			"dockfishangler"=>"Who caught the biggest dock fish?,text|",
			"fishmin"=>"How many ounces of fish must the player have caught to go fishing on the `&Corinth`0?,int|500",
			"captaincrouton"=>"Who caught Captain Crouton?,text|",
			"croutongold"=>"Gold Reward for catching Captain Crouton:,int|100000",
			"croutongems"=>"Gem Reward for catching Captain Crouton:,int|25",
			"Don't worry. It's a 1 in 1.4 million chance to ever catch him!,note",
			"The Seaside Pub,title",
			"price1"=>"Price of Ale per level:,int|10",
			"price2"=>"Price of Mead per level:,int|15",
			"price3"=>"Price of Rum per level:,int|20",
			"price4"=>"Price of Salty Dog per level:,int|25",
			"round"=>"Number of patrons in the bar for buying a round:,int|25",
			"Luckstar,title",
			"inport"=>"How many game days is it between time that the Luckstar is in port?,int|1",
			"cookgold"=>"How much gold does the cook have?,int|1000",
			"Don't worry.  This is just for fun.  The player only gets one gold piece at a time.,note",
			"Xavicon Reward,title",
			"Set any to 0 or No to disable that option.  If all are set to 0 they will get 10 charm,note",
			"xgold"=>"Choice 1: Take this much Gold:,int|5000",
			"xgems"=>"Choice 2: Take this many Gems:,int|10",
			"xweapon"=>"Choice 3: Take the wand worth attack 20:,bool|1",
			"xarmor"=>"Choice 4: Take the robe worth defense 20:,bool|1",
			"Trade,title",
			"freepil"=>"Who was the last person to free Pilinoria?,text|",
			"tradeperdk"=>"How many times per dk can the player trade?:,int|5",
			"tradelevel"=>"Minimum level before being able to trade:,range,1,15,1|6",
			"Trading makes the player about 50% profit on the value of the item when traded.,note",
			"Items sell for 3 times the purchase price listed here for people to buy them at the shop.,note",
			"Set any item Gold Cost to 0 to turn it off,note",
			"`#Item 1 gives a 10 round attack buff,note",
			"item1"=>"`#Item 1:,text|Glass Goblet",
			"cost1"=>"`#Item 1: Gold Cost,int|200",
			"chance1"=>"`#Item 1: Chance that it won't be accepted at the dock store:,range,0,100,1|5",
			"buy1"=>"`#Item 1: Chance that it won't give the buff:,range,0,100,1|10",
			"avail1"=>"`#Item 1: Number available now in the store:,int|0",
			"tocome1"=>"`#Item 1: Number that will become available the next day:,int|0",
			"`@Item 2 gives a 10 round defense buff,note",
			"item2"=>"`@Item 2:,text|Hand Woven Rug",
			"cost2"=>"`@Item 2: Gold Cost:,int|1000",
			"chance2"=>"`@Item 2: Chance that it won't be accepted at the dock store,range,0,100,1|10",
			"buy2"=>"`@Item 2: Chance that it won't give the buff:,range,0,100,1|10",
			"avail2"=>"`@Item 2: Number available now in the store:,int|0",
			"tocome2"=>"`@Item 2: Number that will become available the next day:,int|0",
			"`QItem 3 gives a 10 round minion to help fight,note",
			"item3"=>"`QItem 3:,text|Exotic Wood Sculpture",
			"cost3"=>"`QItem 3: Gold Cost:,int|300",
			"chance3"=>"`QItem 3: Chance that it won't be accepted at the dock store,range,0,100,1|6",
			"buy3"=>"`QItem 3: Chance that it won't give the buff:,range,0,100,1|5",
			"avail3"=>"`QItem 3: Number available now in the store:,int|0",
			"tocome3"=>"`QItem 3: Number that will become available the next day:,int|0",
			"`%Item 4 gives a 5 round attack/defense buff,note",
			"item4"=>"`%Item 4:,text|Sheepskin Drum",
			"cost4"=>"`%Item 4: Gold Cost:,int|250",
			"chance4"=>"`%Item 4: Chance that it won't be accepted at the dock store,range,0,100,1|7",
			"buy4"=>"`%Item 4: Chance that it won't give the buff:,range,0,100,1|5",
			"avail4"=>"`%Item 4: Number available now in the store:,int|0",
			"tocome4"=>"`%Item 4: Number that will become available the next day:,int|0",
			"`^Item 5 gives 1 extra charm,note",
			"item5"=>"`^Item 5:,text|Bead Necklace",
			"cost5"=>"`^Item 5: Gold Cost:,int|500",
			"chance5"=>"`^Item 5: Chance that it won't be accepted at the dock store,range,0,100,1|7",
			"buy5"=>"`^Item 5: Chance that it won't give the bonus:,range,0,100,1|5",
			"avail5"=>"`^Item 5: Number available now in the store:,int|0",
			"tocome5"=>"`^Item 5: Number that will become available the next day:,int|0",
			"Hall of Fame,title",
			"tradehof"=>"Use the Trade HoF:,bool|1",
			"nosuper"=>"Exclude Superusers from the HoF?,bool|0",
			"perpage"=>"How many players per page in Hall of Fame?,int|40",
		),
		"prefs"=>array(
			"Ocean Quest,title",
			"trades"=>"How many trades have they made?:,int|0",
			"user_interface"=>"Disable the Graphics Interface (Travel using the graphics):,bool|0",
			"user_pictures"=>"Disable the basic Pictures:,bool|0",
			"Note: Either may be disabled by the administrator,note",
			"Allprefs,title",
			"Note: Please edit with caution. Consider using the Allprefs Editor instead.,note",
			"allprefs"=>"Preferences for Lost Ruins,textarea|",
			"Maps,title",
			"fishmap"=>"Fishing Map,viewonly|",
			"travelmap"=>"Travel Map,viewonly|",
			"thronemap"=>"Throne Map,viewonly|",
			"pqtemp"=>"Temporary Information,int|",
		),
	);
	return $info;
}
function oceanquest_install(){
	module_addhook("forest");
	module_addhook("newday");
	module_addhook("dragonkill");
	module_addhook("newday-runonce");
	module_addhook("footer-hof");
	module_addhook("footer-bank");
	module_addhook("header-healer");
	module_addeventhook("forest", "return 100;");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	return true;
}
function oceanquest_uninstall(){
	return true;
}
function oceanquest_dohook($hookname,$args){
	global $session;
	require("modules/oceanquest/dohook/$hookname.php");
	return $args;
}
function oceanquest_run(){
	include("modules/oceanquest/oceanquest.php");
}
function oceanquest_runevent($type){
	include("modules/oceanquest/oceanquest_event.php");
}
?>