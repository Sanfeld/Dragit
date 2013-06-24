<?php
require_once("lib/commentary.php");
require_once("lib/systemmail.php");
require_once("lib/http.php");
require_once("lib/addnews.php");
require_once("lib/villagenav.php");
/*
Details:
 * This is a module for Clans to buy a barracks
 * They can also buy games
*/

function clanbarracks_getmoduleinfo(){
	$info = array(
		"name"=>"Clan Barracks",
		"author"=>"`@CortalUX",
		"version"=>"1.3",
		"category"=>"Clan",
		"download"=>"http://www.dragonprime.net/users/CortalUX/clanbarracks.zip",
		"vertxtloc"=>"http://dragonprime.net/users/CortalUX/",
		"settings"=>array(
			"Clan Barracks - General Settings,title",
			"builderName"=>"Name of Head Builder?,text|Tyeneth",
			"builderGender"=>"Gender of Head Builder?,enum,0,Male,1,Female|1",
			"Clan Barracks - Entertainer Settings,title",
			"goldStonesGame"=>"Basic Gold cost of an entertainer for the Stones Game?,int|2000",
			"gemStonesGame"=>"Basic Gem cost of an entertainer for the Stones Game?,int|30",
			"goldFiveSixGame"=>"Basic Gold cost of an entertainer for the Five Sixes Dice Game?,int|2000",
			"gemFiveSixGame"=>"Basic Gem cost of an entertainer for the Five Sixes Dice Game?,int|30",
			"goldDiceGame"=>"Basic Gold cost of an entertainer for the Single Dice Game?,int|2000",
			"gemDiceGame"=>"Basic Gem cost of an entertainer for the Single Dice Game?,int|30",
			"(requires the module to be installed and for the clan to have a dormitory),note",
			"Clan Barracks - Dormitory Settings,title",
			"goldCostDorm"=>"Basic Gold cost of a Dormitory?,int|20000",
			"gemCostDorm"=>"Basic Gem cost of a Dormitory?,int|3000",
			"Clan Barracks - Private Wing Settings,title",
			"goldCostRooms"=>"Rent for Private Rooms? (gold),int|300",
			"(set an to 0 to turn it off),note",
			"goldCostWing"=>"Basic Gold cost of a Private Wing?,int|20000",
			"gemCostWing"=>"Basic Gem cost of a Private Wing?,int|3000",
			"wingMaxRooms"=>"Maximum amount of rooms for each wing size?,int|10",
			"(set to 0 to turn off),note",
			"Clan Barracks - Room Settings,title",
			"clanMoveGold"=>"Cost to move your room to a new wing?,int|100",
			"(set to 0 to turn off),note",
			"goldCostRoom"=>"Basic Gold cost of a Room?,int|20000",
			"gemCostRoom"=>"Basic Gem cost of a Room?,int|3000",
			"minDKs"=>"Minimum number of Dragonkills to build a Room?,int|0",
			"roomsPerPage"=>"Maximum number of rooms per page in the Clan?,range,5,50,5|10",
			"maxWingSize"=>"Maximum size of a clans private wing?,int|10",
			"(Basically- a Clan Owner has to buy a private wing),note",
			"(A Clan owner can buy an extension to it),note",
			"(Each room can grow no bigger than the size of the clans wing),note",
			"(For each size of their room the user can buy one more piece of furniture),note",
			"Clan Barracks - Monetary Preferences,title",
			"gemStore"=>"Enable the storage of Gems?,bool|0",
			"goldStore"=>"Enable the storage of Gold?,bool|0",
			"maxGems"=>"Maximum amount of Gems in users Chest?,int|50",
			"maxGold"=>"Maximum amount of Gold in users Chest?,int|50",
			"Clan Barracks - Planeshift Settings,title",
			"pshiftlink"=>"Amount of times to see this- out of 10?,range,1,10,1|10",
		),
		"prefs"=>array(
			"Clan Barracks - General Preferences,title",
			"logOff"=>"Which City did the user logoff in?,viewonly",
			"inBar"=>"Is this person in the bar?,bool|0",
			"Clan Barracks - Monetary Preferences,title",
			"goldChest"=>"Gold in Chest?,int|0",
			"gemChest"=>"Gems in Chest?,int|0",
			"Clan Barracks - Room Location,title",
			"roomLocation"=>"Which clan is this room registered with?,int|0",
			"Clan Barracks - Room Customization,title",
			"roomCeiling"=>"Ceiling type?,text|Stone",
			"roomFlooring"=>"Floor type?,text|Wood",
			"roomWindow"=>"Window Size?,text|Small- just a slit in the wall",
			"roomBed"=>"Bed type?,text|a cloth pallet",
			"roomSize"=>"Room Size,int|0",
			"roomFurniture"=>"Furniture?,viewonly|",
			"Clan Barracks - Rent,title",
			"chuckOut"=>"User has been chucked out?,bool|0",
			"goldPaid"=>"Gold paid?,int|0",
			"Clan Barracks - Planeshift Link,title",
			"linkno"=>"Current number?,int|0",
		),
		"prefs-clans"=>array(
			"Clan Barracks - Clan Settings,title",
			"boughtStones"=>"Has this clan bought the stones game?,bool|0",
			"boughtFiveSix"=>"Has this clan bought the five sixes dice game?,bool|0",
			"boughtDice"=>"Has this clan bought the single dice game?,bool|0",
			"haveDorm"=>"Does this clan have a Dormitory yet?,bool|0",
			"dormPoster"=>"What is the current Dormitory poster?,text|... It's too smudgy to be made out.",
			"authID"=>"ID of author?,int|1",
			"haveWing"=>"Does this clan have a private wing yet?,bool|0",
			"sleepCost"=>"Cost to use the Dormitory?,int|100",
			"Clan Barracks - Wing Settings,title",
			"wingSize"=>"Current size of Wing?,int|0",
		),
		"requires"=>array(
			"clanoptions"=>"1.5|`@CortalUX, http://dragonprime.net/users/CortalUX/clanoptions.zip",
		),
	);
	return $info;
}

function clanbarracks_install(){
	if (!is_module_active('clanbarracks')){
		output("`n`c`b`QClan Barracks Module - Installed`0`b`c");
	}else{
		output("`n`c`b`QClan Barracks Module - Updated`0`b`c");
	}
	module_addhook("clanmoderate");
	module_addhook("footer-clan");
	module_addhook("clanoptions-admin-text");
	module_addhook("newday");
	module_addhook("drinks-text");
	module_addhook("village");
	module_addhook("header-inn");
	return true;
}
function clanbarracks_uninstall(){
	output("`n`c`b`QClan Barracks Module - Uninstalled`0`b`c");
	return true;
}
function clanbarracks_dohook($hookname, $args){
	global $session;
	$op = httpget('op');
	$clan = $session['user']['clanid'];
	switch($hookname){
		case "clanmoderate":
			$area="clanbarracks-{$args['clan']}";
			$name="<{$args['clanshort']}> {$args['clanname']} ".translate_inline("Clan Barracks Dormitory");
			$args[$area]=$name;
			$area="clanbarracks-bar-{$args['clan']}";
			$name="<{$args['clanshort']}> {$args['clanname']} ".translate_inline("Clan Barracks Bar");
			$args[$area]=$name;
		break;
		case "footer-clan":
			if ($op=='enter'&&$clan!=0||$op==''&&$clan!=0) {
				addnav("~");
				if (get_module_objpref("clans", $clan, "haveDorm")==1) {
					addnav("Walk to the Clan Dormitory","runmodule.php?module=clanbarracks&op=dormitory");
				}
				if (get_module_objpref("clans", $clan, "haveWing")==1) {
		//			addnav("Walk to the Private Wing","runmodule.php?module=clanbarracks&op=wing");
				}
			}
			set_module_pref('inBar',0);
		break;
		case "clanoptions-admin-text":
			addnav("Clan Barracks");
			if ($session['user']['clanrank'] == CLAN_LEADER){
				if (get_module_objpref("clans", $clan, "haveDorm")==0) {
					addnav("Buy a Clan Dormitory","runmodule.php?module=clanbarracks&op=leaderbuy&type=dormitory");
				} elseif (get_module_objpref("clans", $clan, "haveWing")==0) {
	//				addnav("Buy a Private Wing","runmodule.php?module=clanbarracks&op=leaderbuy&type=privatewing");
				} elseif (get_module_objpref("clans",$clan, "wingSize")<get_module_setting("maxWingSize")) {
	//				addnav(array("Upgrade your Private Wing to Size %s",get_module_objpref("clans",$clan, "wingSize")+1),"runmodule.php?module=clanbarracks&op=leaderbuy&type=upgradeprivatewing");
				}
				if (get_module_objpref("clans", $clan, "boughtStones")==0&&is_module_active('game_stones')) {
					addnav("Hire an Entertainer to play the Stones Game","runmodule.php?module=clanbarracks&op=leaderbuy&type=stones");
				}
				if (get_module_objpref("clans", $clan, "boughtFiveSix")==0&&is_module_active('game_fivesix')) {
					addnav("Hire an Entertainer to play the Five Sixes Dice Game","runmodule.php?module=clanbarracks&op=leaderbuy&type=fivesix");
				}
				if (get_module_objpref("clans", $clan, "boughtDice")==0&&is_module_active('game_dice')) {
					addnav("Hire an Entertainer to play the Single Dice Game","runmodule.php?module=clanbarracks&op=leaderbuy&type=dice");
				}
			}
			if ($session['user']['clanrank'] == CLAN_LEADER||$session['user']['clanrank'] == CLAN_OFFICER&&get_module_objpref("clans", $clan, "officersUse", "clanoptions")==1){
				addnav(array("%s", translate_inline("Settings")),"runmodule.php?module=clanbarracks&op=admin");
			}
		break;
		case "newday":
			$cost = get_module_setting('goldCostRooms');
			$x = clanbarracks_synch();
			$clan = $x['roomLocation'];
			$size = $x['roomSize'];
			if ($cost>0) {
				if ($x['chuckOut']==0&&$size>0) {
					if ($session['user']['gold']>=$cost) {
						clanbarracks_golddeposit($cost,'Paid for clan room',$clan);
						if ($clan==$session['user']['clanid']) {
							output("`@You pay `^%s`@ cost in gold rent, for your room.",$cost);
						} else {
							output("`@Though you cannot sleep in your room, you still pay `^%s`@ gold in rent for it..",$cost);
						}
					} else {
						$x['chuckOut']=1;
						$x['goldPaid']=0;
						output("`nAttempting to pay for a room at your clan, you realise you don't have enough money... they chuck you out!");
					}
				}
			}
			set_module_pref('inBar',0);
			$x = clanbarracks_synch($x);
		break;
		case "drinks-text":
			if (get_module_pref('inBar')) {
				$args['title']="The Dormitoy";
				$args['barkeep']="Old Man";
				$args['return']="Return to the Corner";
				$args['returnlink']="runmodule.php?module=clanbarracks&op=dormitory&type=oldman&sop=tavern";
				$args['demand']="Staring intently at the wall, you ask for another drink";
				$args['toodrunk']=" and so the Old Man places one on chiselled granite table.. however, you are too drunk to pick it up, so the Old Man clears it up..";
				$args['toomany']="`^The Old Man `3apologizes, \"`&You've cleaned the place out.`3\"";
				$array = array("title","barkeep","return","demand","toodrunk","toomany","drinksubs");
				$schemas=array();
				foreach ($array as $val) {
					$schemas[$val]="module-clanbarracks";
				}
				$args['schemas']=$schemas;
				$args['drinksubs']=array(
					"/Cedrik/"=>translate_inline("The Old Man"),
					"/Violet/"=>translate_inline("a stranger"),
					"/Seth/"=>translate_inline("a stranger"),
				);
			}
		break;
		default:
			set_module_pref('inBar',0);
		break;
	}
 	return $args;
}

function clanbarracks_run() {
	global $session;
	$op = httpget('op');
	if ($op=='oldman'||$op=='tavern') {
		$op='dormitory';
		httpset("op","dormitory",true);
		httpset("sop","oldman",true);
		httpset("type","oldman",true);
	}
	switch ($op) {
		case "leaderbuy":
			page_header("Leader Actions");
			clanbarracks_clanleaderbuy();
		break;
		case "dormitory":
			page_header("The Dormitory");
			clanbarracks_dormitory();
		break;
		case "wing":
			page_header("The Private Wing");
			clanbarracks_wing();
		break;
		case "admin":
			page_header("Clan Leader Setup");
			clanbarracks_leadersetup();
		break;
	}
	clanbarracks_planeshift();
	page_footer();
}

function clanbarracks_dormitory() {
	global $session;
	addnav("Navigation");
	addnav("Return to your Clan","clan.php");
	$clan = $session['user']['clanid'];
	$type = httpget('type');
	$cost = get_module_objpref("clans", $clan, "sleepCost");
	$link = "runmodule.php?module=clanbarracks&op=dormitory&";
	addnav("Actions");
	switch ($type) {
		case "sleep":
			if ($session['user']['gold']>=$cost) {
				set_module_pref('logOff', $session['user']['location']);
				if ($session['user']['loggedin']){
					$session['user']['restorepage'] = $link."type=wake";
				  	$sql = "UPDATE " . db_prefix("accounts") . " SET loggedin=0,gold='{$session['user']['gold']}',location='Clan Barracks', restorepage='{$session['user']['restorepage']}' WHERE acctid = ".$session['user']['acctid'];
					db_query($sql);
					invalidatedatacache("charlisthomepage");
					invalidatedatacache("list.php-warsonline");
				}
				$session=array();
				redirect("index.php");
			} else {
				output("`@Checking your Pockets, you realize you don't have enough gold to sleep here.");
			}
		break;
		case "talk":
			addcommentary();
			output("`^As you walk around the room, you hear a kind of indistinct muttering..`n");
			viewcommentary("clanbarracks-".$session['user']['clanid'],"`#Mutter?`@",25,"mutters");
		break;
		case "wake":
			clanbarracks_golddeposit($cost,'Went to sleep in dormitory.');
			$session['user']['alive'] = 1;
			$session['user']['location'] = get_module_pref('logOff','clanbarracks');
			$time = gametime();
			$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
			$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
			$secstotomorrow = $tomorrow-$time;
			$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
			$sql = "SELECT name FROM " . db_prefix("accounts") . " WHERE acctid='".get_module_objpref("clans", $clan, "authID")."'";
			$result = db_query_cached($sql, "stocks");
			$row = db_fetch_assoc($result);
			$author = $row['name'];
			unset($row,$result,$sql,$time,$secstotomorrow,$tomorrow,$time);
			output("`@A loud gong rings out, waking you up with a shock. You blearily stare up at the ceiling of your Clan Dormitory, and read the poster that sits there.`nIt says `^%s`0`@... with the name %s`0`@ etched in a corner.`nYou then stumble out of bed, and stand up slowly.`nThe gong rings out again, and a voice yells, \"`^Come on lazybones! It's %s`@.\"`nYou figure a new day in `^%s hours %s minutes and %s seconds`@.`n`n`0",str_replace("\'","'",str_replace("\\\"","\"",get_module_objpref("clans", $clan, "dormPoster"))),$author,getgametime(),date("G",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("i",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("s",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")));
		break;
		case "poster":
			if (httpget('s')=='yes') {
				output("`@You decide the old poster is boring, and change it.");
				$poster = str_replace("'","\'",httppost('poster'));
				set_module_objpref("clans", $clan, "dormPoster",$poster);
				set_module_objpref("clans", $clan, "authID",$session['user']['acctid']);
				$time = gametime();
				$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
				$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
				$secstotomorrow = $tomorrow-$time;
				$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
				$sql = "SELECT name FROM " . db_prefix("accounts") . " WHERE acctid='".get_module_objpref("clans", $clan, "authID")."'";
				$result = db_query_cached($sql, "stocks");
				$row = db_fetch_assoc($result);
				$author = $row['name'];
				unset($row,$result,$sql,$time,$secstotomorrow,$tomorrow,$time);
				output("`@You read the poster that sits on the ceiling.`nIt says `^%s`0`@... with your name etched in a corner.`nYou then stare at the time on the wall- `^%s`@.`nYou figure a new day in `^%s hours %s minutes and %s seconds`@.`0",str_replace("\'","'",str_replace("\\\"","\"",get_module_objpref("clans", $clan, "dormPoster"))),$author,getgametime(),date("G",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("i",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("s",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")));
			} else {
				rawoutput("<form action='".$link."type=poster&s=yes' method='POST'>");
				addnav("",$link."type=poster&s=yes");
				output("`&`bPoster:`b `7(255 chars)`n");
				rawoutput("<input name='poster' value='' maxlength=255 size=50>");
				$submit = translate_inline('Replace');
				rawoutput("<input type='submit' name='submit' value=\"$submit\" class='button'>");
				rawoutput("</form>");
			}
		break;
		case "oldman":
			$sop=httpget('sop');
			switch ($sop) {
				case "tavern":
					set_module_pref('inBar',1);
					addcommentary();
					output("`^You pull a barrel up, sit at the granite table, see a few other clan members in this hidden bar, and look at your hands..`n");
					addnav("Drinks");
					modulehook("ale", array());
					viewcommentary("clanbarracks-bar-".$session['user']['clanid'],"`#Converse?`@",25,"converses");
					addnav("Actions");
					addnav("To the Old Man",$link."type=oldman&sop=oldman");
				break;
				default:
					output("`@Hidden in the corner of your clan hall is an old man, a chiselled granite table with little playing pieces, and a little mini-bar.`nThe man calls you over, and tells you that he will let you play games if your clan has hired him.`nOtherwise, you can buy drinks from the bar.");
					addnav("To the Bar",$link."type=oldman&sop=tavern");
					$x=true;
					if (get_module_objpref("clans", $clan, "boughtStones")==1&&is_module_active('game_stones')) {
						$x=false;
						addnav("S?Play Stones Game","runmodule.php?module=game_stones&ret=".urlencode("runmodule.php?module=clanbarracks&sop=oldman"));
					}
					if (get_module_objpref("clans", $clan, "boughtFiveSix")==1&&is_module_active('game_stones')) {
						$x=false;
						addnav("Play the Five Sixes Dice Game","runmodule.php?module=game_fivesix&what=play&ret=".urlencode("runmodule.php?module=clanbarracks&sop=oldman"));
					}
					if (get_module_objpref("clans", $clan, "boughtDice")==1&&is_module_active('game_stones')) {
						$x=false;
						addnav("Play the Single Dice Game","runmodule.php?module=game_dice&ret=".urlencode("runmodule.php?module=clanbarracks&sop=oldman"));
					}
					if ($x) {
						output("`n`^It doesn't look like your clan has hired this man for any games.");
					}
				break;
			}
		break;
		default:
			output("`@Walking to the end of your Clan Hall, you enter a wide dormitory.`nYou hear people muttering about the cost to sleep \"`^%s gold??!!`@\", but you ignore them.`nOver in the corner, you can see a little partition, with the end of a mini-bar sticking out...",$cost);
			$time = gametime();
			$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
			$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
			$secstotomorrow = $tomorrow-$time;
			$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
			$sql = "SELECT name FROM " . db_prefix("accounts") . " WHERE acctid='".get_module_objpref("clans", $clan, "authID")."'";
			$result = db_query_cached($sql, "stocks");
			$row = db_fetch_assoc($result);
			$author = $row['name'];
			unset($row,$result,$sql,$time,$secstotomorrow,$tomorrow,$time);
			output("`@You read the poster that sits on the ceiling.`nIt says `^%s`0`@... with the name %s`0`@ etched in a corner.`nYou stare at the time on the wall- `^%s`@.`nYou figure a new day in `^%s hours %s minutes and %s seconds`@.`0",str_replace("\\\"","\"",str_replace("\'","'",get_module_objpref("clans", $clan, "dormPoster"))),$author,getgametime(),date("G",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("i",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("s",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")));
		break;
	}
	addnav("Actions");
	if ($type!='oldman') {
		addnav("Go to the Corner",$link."type=oldman");
		addnav(array("Q?`b`@Go to Sleep (`^%s gold`@)`b",$cost),$link."type=sleep");
		addnav("Change the Poster",$link."type=poster");
		addnav("Talk to the Others",$link."type=talk");
	} else {
		addnav("To the Main Dormitory",$link."type=main");
	}
	addnav("Navigation");
	villagenav();
	addnav("Actions");
}

function clanbarracks_leadersetup() {
	global $session;
	$clan = $session['user']['clanid'];
	$type = httpget('type');
	$link = "runmodule.php?module=clanbarracks&op=admin";
	addnav("Navigation");
	switch ($type) {
		case "save":
			$sleepcost=httppost('sleepcost');
			if (is_numeric($sleepcost)&&$sleepcost>=50) {
				set_module_objpref("clans", $clan, "sleepCost",$sleepcost);
				output("`@`bThat cost has been saved.`b`n`n");
			} else {
				output("`@`bThat cost is not valid.`b`n`n");
			}
		break;
	}
	rawoutput("<form action='".$link."&type=save' method='POST'>");
	$b = translate_inline("Gold Cost to sleep in your Clan's dormitory (Must be greater than or equal to 50):");
	output("`n`&");
	rawoutput("<b>$b"."</b> <input type='text' name='sleepcost'> <input type='submit' value='Submit'>");
	rawoutput("</form>");
	addnav("",$link."&type=save");
	output_notl("`n");
	villagenav();
	addnav("Return to your Clan","clan.php?op=enter");
	addnav("Clan Options","runmodule.php?module=clanoptions&op=admin");
	output_notl("`n`@%s `^%s",translate_inline("The cost for users to sleep in your Clan Dormitory is:"),get_module_objpref("clans", $clan, "sleepCost"));
}

function clanbarracks_clanleaderbuy() {
	global $session;
	$clan = $session['user']['clanid'];
	$gold = $session['user']['gold'];
	$name=get_module_setting('builderName');
	$subjective=get_module_setting('builderGender')?translate_inline("She"):translate_inline("He");
	$posessive=get_module_setting('builderGender')?translate_inline("her"):translate_inline("his");
	$type = httpget('type');
	$stage = httpget('stage');
	$link = "runmodule.php?module=clanbarracks&op=leaderbuy&type=".$type."&";
	addnav("Building");
	switch ($type) {
		case "dice":
			$gocost = get_module_setting('goldDiceGame');
			$gecost = get_module_setting('gemDiceGame');
			if ($stage=='') {
				clanbarracks_sy("You",":","walk around the Clan Halls to try and find the Head Builder, looking for a deal on the sly.");
				clanbarracks_sy("You",":","sneak into an alleyway.");
				clanbarracks_sy($name,":",array("walks past, and you call %s over.",$posessive));
				clanbarracks_sy($name,"listens, and says","So you want an entertainer for single dice game, on the sly..");
				clanbarracks_sy($name,"says","It won't be cheap. I'll need my cost `bnow`b.");
				clanbarracks_sy($name,"says",array("His fee is a one-off `^%s`@ gold and `^%s`@ gems.",$gocost,$gecost));
				if ($session['user']['gold']<$gocost||$session['user']['gems']<$gecost) {
					clanbarracks_sy($name,"says","You don't appear to have his fee.");
				} else {
					clanbarracks_sy($name,"says","You appear to have his fee.");
					addnav("Hire your Dice Entertainer",$link."stage=1");
				}
			} elseif ($stage=='1') {
				set_module_objpref("clans", $clan, "boughtDice",1);
				clanbarracks_sy($name,"says","I've hired him... look in your dormitory.");
			}
		break;
		case "fivesix":
			$gocost = get_module_setting('goldFiveSixGame');
			$gecost = get_module_setting('gemFiveSixGame');
			if ($stage=='') {
				clanbarracks_sy("You",":","walk around the Clan Halls to try and find the Head Builder, looking for a deal on the sly.");
				clanbarracks_sy("You",":","sneak into an alleyway.");
				clanbarracks_sy($name,":",array("walks past, and you call %s over.",$posessive));
				clanbarracks_sy($name,"listens, and says","So you want an entertainer for five sixes dice game, on the sly..");
				clanbarracks_sy($name,"says","It won't be cheap. I'll need my cost `bnow`b.");
				clanbarracks_sy($name,"says",array("His fee is a one-off `^%s`@ gold and `^%s`@ gems.",$gocost,$gecost));
				if ($session['user']['gold']<$gocost||$session['user']['gems']<$gecost) {
					clanbarracks_sy($name,"says","You don't appear to have his fee.");
				} else {
					clanbarracks_sy($name,"says","You appear to have his fee.");
					addnav("Hire your Five Sixes Dice Entertainer",$link."stage=1");
				}
			} elseif ($stage=='1') {
				set_module_objpref("clans", $clan, "boughtFiveSix",1);
				clanbarracks_sy($name,"says","I've hired him... look in your dormitory.");
			}
		break;
		case "stones":
			$gocost = get_module_setting('goldStonesGame');
			$gecost = get_module_setting('gemStonesGame');
			if ($stage=='') {
				clanbarracks_sy("You",":","walk around the Clan Halls to try and find the Head Builder, looking for a deal on the sly.");
				clanbarracks_sy("You",":","sneak into an alleyway.");
				clanbarracks_sy($name,":",array("walks past, and you call %s over.",$posessive));
				clanbarracks_sy($name,"listens, and says","So you want an entertainer for stones, on the sly..");
				clanbarracks_sy($name,"says","It won't be cheap. I'll need my cost `bnow`b.");
				clanbarracks_sy($name,"says",array("His fee is a one-off `^%s`@ gold and `^%s`@ gems.",$gocost,$gecost));
				if ($session['user']['gold']<$gocost||$session['user']['gems']<$gecost) {
					clanbarracks_sy($name,"says","You don't appear to have his fee.");
				} else {
					clanbarracks_sy($name,"says","You appear to have his fee.");
					addnav("Hire your Stones Entertainer",$link."stage=1");
				}
			} elseif ($stage=='1') {
				set_module_objpref("clans", $clan, "boughtStones",1);
				clanbarracks_sy($name,"says","I've hired him... look in your dormitory.");
			}
		break;
		case "dormitory":
			$gocost = get_module_setting('goldCostDorm');
			$gecost = get_module_setting('gemCostDorm');
			if ($stage=='') {
				clanbarracks_sy("You",":","walk around the Clan Halls to try and find the Head Builder.");
				clanbarracks_sy("You",":","eventually get so tired, you have to rest, so you look for a convenient bench.");
				clanbarracks_sy("You",":","sit down.");
				clanbarracks_sy($name,":","walks past.");
				clanbarracks_sy("You",":",array("stand up, waddle over, and begin to talk to %s.",$posessive));
				clanbarracks_sy($name,"listens, and says","So you want me to build you a dormitory?");
				clanbarracks_sy($name,"says","It won't be cheap. I'll need my cost `bnow`b.");
				clanbarracks_sy($name,"says",array("My fee is `^%s`@ gold and `^%s`@ gems.",$gocost,$gecost));
				if ($session['user']['gold']<$gocost||$session['user']['gems']<$gecost) {
					clanbarracks_sy($name,"says","You don't appear to have my fee.");
				} else {
					clanbarracks_sy($name,"says","You appear to have my fee.");
					addnav("Build your Dormitory",$link."stage=1");
				}
			} elseif ($stage=='1') {
				set_module_objpref("clans", $clan, "haveDorm",1);
				clanbarracks_sy($name,"says","I have built your dormitory.");
			}
		break;
		case "privatewing":
			$gocost = get_module_setting('goldCostWing');
			$gecost = get_module_setting('gemCostWing');
			if ($stage=='') {
				clanbarracks_sy("You",":","mooch around the Clan Halls trying to find the Head Builder.");
				clanbarracks_sy("You",":",array("enter %s workshop.",$posessive));
				clanbarracks_sy($name,":","looks up.");
				clanbarracks_sy("You",":",array("begin to talk to %s",$name));
				clanbarracks_sy($name,"nods, and exclaims","So you want me to build you a Private Wing!!?");
				clanbarracks_sy($name,"says","It won't be cheap at ALL. I'll need my cost `bnow`b.");
				clanbarracks_sy($name,"says",array("My fee is `^%s`@ gold and `^%s`@ gems.",$gocost,$gecost));
				if ($session['user']['gold']<$gocost||$session['user']['gems']<$gecost) {
					clanbarracks_sy($name,"says","You don't appear to have my fee.");
				} else {
					clanbarracks_sy($name,"says","You appear to have my fee.");
					addnav("Build your Private Wing",$link."stage=1");
				}
			} elseif ($stage=='1') {
				set_module_objpref("clans", $clan, "haveWing",1);
				set_module_objpref("clans", $clan, "wingSize",1);
				clanbarracks_sy($name,"says","I have built your Private Wing.");
				clanbarracks_sy($name,"says","It is a level one Wing.");
				clanbarracks_sy($name,"says","There is room enough for one extra piece of furniture in each room.");
			}
		break;
		case "upgradeprivatewing":
			$gocost = get_module_setting('goldCostWing');
			$gecost = get_module_setting('gemCostWing');
			if ($stage=='') {
				clanbarracks_sy("You",":","stroll around the Clan Halls trying to find the Head Builder.");
				clanbarracks_sy("You",":",array("enter %s workshop.",$posessive));
				clanbarracks_sy($name,":","sees you, and nods.");
				clanbarracks_sy("You",":",array("huddle in a corner, talking to %s",$name));
				clanbarracks_sy($name,"nods, and exclaims","So you want me to extend your Private Wing!!?");
				clanbarracks_sy($name,"says","It won't be cheap at ALL. I'll need my cost `bnow`b.");
				clanbarracks_sy($name,"says",array("My fee is `^%s`@ gold and `^%s`@ gems.",$gocost,$gecost));
				if ($session['user']['gold']<$gocost||$session['user']['gems']<$gecost) {
					clanbarracks_sy($name,"says","You don't appear to have my fee.");
				} else {
					clanbarracks_sy($name,"says","You appear to have my fee.");
					addnav("Extend your Private Wing",$link."stage=1");
				}
			} elseif ($stage=='1') {
				set_module_objpref("clans", $clan, "wingSize",get_module_objpref("clans",$clan, "wingSize")+1);
				clanbarracks_sy($name,"says","I have built your Private Wing.");
				clanbarracks_sy($name,"says",array("It is a level %s Wing.",get_module_objpref("clans",$clan, "wingSize")));
				clanbarracks_sy($name,"says",array("There is room enough for %s extra pieces of furniture in each room.",get_module_objpref("clans",$clan, "wingSize")));
			}
		break;
	}
	if (httpget('stage')==1&&isset($gocost)&&isset($gecost)) {
		$session['user']['gold']-=$gocost;
		$session['user']['gems']-=$gecost;
	}
	addnav("Clan Options","runmodule.php?module=clanoptions&op=admin");
	addnav("Return to your Clan","clan.php");
}

function clanbarracks_sy($name="",$type="",$message="") {
	if (is_array($message)) {
		$n = 0;
		$str = "";
		foreach ($message as $val) {
			$n++;
			if ($n==1) {
				$str = translate_inline($val);
			} else {
				$y = (string)$val;
				$str = preg_replace('/%s/', $y, $str, 1);
			}
		}
		$message = $str;
	}
	if ($type==":") {
		output("`^%s `&%s`n",$name,$message);
	} else {
		output("`^%s `3%s, \"`@%s`3\"`n",$name,$type,$message);
	}
}

function clanbarracks_planeshift() {
	$max = get_module_setting('pshiftlink');
	$amount = get_module_pref('linkno');
	$amount++;
	if ($amount<=$max) {
		$logdname=getsetting('serverdesc','');
		// Please show the link to our site:)
		// You can turn this off for nine times out of ten in the Grotto
		if (!stristr($logdname,'Planeshift')) {
			output_notl("`n`n`c<a class='colLtYellow' href=\"http://www.planeshift.co.uk\" target=\"_blank\">%sCortalUX%shttp://www.planeshift.co.uk</a>`n`c",translate_inline("Clan Barracks by "),translate_inline(" @ "),true);
		}
	}
	if ($amount>10)	$amount = 0;
	set_module_pref('linkno',$amount);
}


function clanbarracks_wing() {
	global $session;
	$name=get_module_setting('builderName');
	$subjective=get_module_setting('builderGender')?translate_inline("She"):translate_inline("He");
	$posessive=get_module_setting('builderGender')?translate_inline("her"):translate_inline("his");
	$x = clanbarracks_synch();
	addnav("Navigation");
	addnav("Return to your Clan","clan.php");
	$clan = $session['user']['clanid'];
	$type = httpget('type');
	$link = "runmodule.php?module=clanbarracks&op=dormitory&";
	addnav("Actions");
	switch ($type) {
		case "sleep":
			set_module_pref('logOff', $session['user']['location']);
			if ($session['user']['loggedin']){
				$session['user']['restorepage'] = $link."type=wake";
			  	$sql = "UPDATE " . db_prefix("accounts") . " SET loggedin=0,gold='{$session['user']['gold']}',location='Clan Barracks', restorepage='{$session['user']['restorepage']}' WHERE acctid = ".$session['user']['acctid'];
				db_query($sql);
				invalidatedatacache("charlisthomepage");
				invalidatedatacache("list.php-warsonline");
			}
			$session=array();
			redirect("index.php");
		break;
		case "wake":
			$time = gametime();
			$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
			$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
			$secstotomorrow = $tomorrow-$time;
			$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
			unset($time,$secstotomorrow,$tomorrow,$time);
			output("`@You blearily stare up at the ceiling of your Clan Room, having been rudely awoken by a loud gong.. as you lie there, you listen to the time- `^%s`@.`nYou figure a new day in `^%s hours %s minutes and %s seconds`@.`0",getgametime(),date("G",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("i",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("s",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")));
		break;
		case "talk":
			$ty = httpget('ty');
			$cost = get_module_setting("clanMoveGold");
			if ($ty=='') {
				addnav("Sub-actions");
				addnav(array("Barter with `^%s`0 about your room.",$name),$link."type=talk&ty=move");
				clanbarracks_sy("You",":","stroll around the Clan Halls trying to find the Head Builder.");
				clanbarracks_sy("You",":",array("enter %s workshop.",$posessive));
				clanbarracks_sy($name,":","sees you, and nods.");
				clanbarracks_sy($name,"says","Ah. I wondered when you would notice...");
				clanbarracks_sy($name,"says","So you wish yer room to be moved to ya new clan?");
				clanbarracks_sy("You","say",array("I could accomodate you there... but it won't be cheap. `^%s`0 gold.",$cost));
			} elseif ($ty=='move') {
				if ($session['user']['gold']>$cost) {
				} else {
				}
			}
		break;
		case "buy":
		break;
		case "room":
		break;
		default:
			output("`@Walking to the end of your Clan Hall, you enter your Clan's private wing.");
			$time = gametime();
			$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
			$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
			$secstotomorrow = $tomorrow-$time;
			$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
			unset($time,$secstotomorrow,$tomorrow,$time);
			output("`@You listen to mutters about not enough time in the day, and interestedly hear the time- `^%s`@.`nYou figure a new day in `^%s hours %s minutes and %s seconds`@.`0",getgametime(),date("G",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("i",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")),date("s",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds")));
			if ($x['roomSize']>0) {
				if ($x['roomLocation']==$clan) {
					output("`nBeing very new to this clan, you get a lot of evil stares...`nAs you walk forward, you realise that your room has not been moved by `^%s`@.. maybe you should go and see %s?",$name,$subjective);
					addnav(array("Talk to `^%s`0 about your room.",$name),$link."type=talk");
				} else {
					addnav("Walk to your room",$link."type=room");
				}
			} else {
				addnav(array("Talk to `^%s`0 about buying a room.",$name),$link."type=buy");
			}
		break;
	}
	if ($type!=''&&$type!='buy'&&$type!='talk') addnav(array("Q?`b`@Go to Sleep (`^Free`@)`b",$cost),$link."type=sleep");
	addnav("Navigation");
	villagenav();
	addnav("Actions");	// We make sure all the navs appear in the correct order
	addnav("Sub-actions");
	$x = clanbarracks_synch($x);
}

function clanbarracks_golddeposit($amount=0,$reason='',$clan=false) {
	global $session;
	$session['user']['gold']-=$amount;
	if ($clan===false) $clan = $session['user']['clanid'];
	if (is_module_active('clanvault')) {
		set_module_objpref("clans", $clan, "vaultgold",(get_module_objpref("clans", $session['user']['clanid'], "vaultgold","clanvault")+$amount),"clanvault");
		debuglog("$reason".": Entered $amount Gold in the clan vault. : Clan ".$session['user']['clanid']);
	} else {
		debuglog("$reason".": Paid $amount Gold.");
	}
}

function clanbarracks_synch($array=false,$user=false) {
	global $session;
	$stuff = array('goldChest','gemChest','roomLocation','roomCeiling','roomFlooring','roomWindow','roomBed','roomSize','roomFurniture','goldPaid','chuckOut');
	if ($user===false) $user=$session['user']['acctid'];
	$x=array();
	foreach ($stuff as $val) {
		$x[$val]=get_module_pref($val,'clanbarracks',$user);
	}
	if ($array===false) {
		return $x;
	}
	foreach ($array as $key => $val) {
		if ($x[$key]!=$val&&$val!=''&&$key!=''&&isset($x[$val])) {
			$x[$key]=$val;
			set_module_pref($key,$val,'clanbarracks',$user);
		}
	}
	return $x;
}
?>