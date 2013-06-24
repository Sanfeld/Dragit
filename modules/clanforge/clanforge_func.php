<?php
global $session;
page_header("The Forge");
$op=httpget('op');
$cost = get_module_setting("cost");
$level = get_module_pref("level");
$tries = get_module_pref("tries");
$atries = get_module_pref("atries");
$levela = get_module_pref("levela");
$fortune = get_module_pref("fortune");
if ($op=="weaponhofc"){
	page_header("Weapon Forge HOF");
	$clanid = $session['user']['clanid'];
	$acc = db_prefix("accounts");
	$mp = db_prefix("module_userprefs");
	$sql = "SELECT $acc.name AS name,
	$acc.acctid AS acctid,
	$mp.value AS level,
	$mp.userid FROM $mp INNER JOIN $acc
	ON $acc.acctid = $mp.userid
	WHERE $acc.clanid = $clanid
	AND $mp.modulename = 'clanforge'
	AND $mp.setting = 'level'
	AND $mp.value > 0 ORDER BY ($mp.value+0)
	DESC limit ".get_module_setting("list")."";
	$result = db_query($sql);
	$rank = translate_inline("Level");
	$name = translate_inline("Name");
	output("`n`b`c`4Weapon Forge Clan HoF`n`n`c`b");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center'>");
	rawoutput("<tr class='trhead'><td align=center>$name</td><td align=center>$rank</td></tr>");
	for ($i=0;$i < db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		if ($row['name']==$session['user']['name']){
			rawoutput("<tr class='trhilight'><td>");
		}else{
			rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td align=left>");
		}
		output_notl("%s",$row['name']);
		rawoutput("</td><td align=right>");
		output_notl("%s",$row['level']);
		rawoutput("</td></tr>");
	}
	rawoutput("</table>");
	addnav("Clan Halls", "clan.php");
	villagenav();
	page_footer();
}
if ($op=="armorhofc"){
	page_header("Armor Forge HOF");
	$clanid = $session['user']['clanid'];
	$acc = db_prefix("accounts");
	$mp = db_prefix("module_userprefs");
	$sql = "SELECT $acc.name AS name,
	$acc.acctid AS acctid,
	$mp.value AS level,
	$mp.userid FROM $mp INNER JOIN $acc
	ON $acc.acctid = $mp.userid
	WHERE $acc.clanid = $clanid
	AND $mp.modulename = 'clanforge'
	AND $mp.setting = 'levela'
	AND $mp.value > 0 ORDER BY ($mp.value+0)
	DESC limit ".get_module_setting("list")."";
	$result = db_query($sql);
	$rank = translate_inline("Level");
	$name = translate_inline("Name");
	output("`n`b`c`4Armor Forge Clan HoF`n`n`c`b");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center'>");
	rawoutput("<tr class='trhead'><td align=center>$name</td><td align=center>$rank</td></tr>");
	for ($i=0;$i < db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		if ($row['name']==$session['user']['name']){
			rawoutput("<tr class='trhilight'><td>");
		}else{
			rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td align=left>");
		}
		output_notl("%s",$row['name']);
		rawoutput("</td><td align=right>");
		output_notl("%s",$row['level']);
		rawoutput("</td></tr>");
	}
	rawoutput("</table>");
	addnav("Clan Halls", "clan.php");
	villagenav();
	page_footer();
}
if ($op=="weaponhof"){
	page_header("Weapon Forge HOF");
	$acc = db_prefix("accounts");
	$mp = db_prefix("module_userprefs");
	$sql = "SELECT $acc.name AS name,
	$acc.acctid AS acctid,
	$mp.value AS level,
	$mp.userid FROM $mp INNER JOIN $acc
	ON $acc.acctid = $mp.userid
	WHERE $mp.modulename = 'clanforge'
	AND $mp.setting = 'level'
	AND $mp.value > 0 ORDER BY ($mp.value+0)
	DESC limit ".get_module_setting("list")."";
	$result = db_query($sql);
	$rank = translate_inline("Level");
	$name = translate_inline("Name");
	output("`n`b`c`4Weapon Forge HoF`n`n`c`b");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center'>");
	rawoutput("<tr class='trhead'><td align=center>$name</td><td align=center>$rank</td></tr>");
	for ($i=0;$i < db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		if ($row['name']==$session['user']['name']){
			rawoutput("<tr class='trhilight'><td>");
		}else{
			rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td align=left>");
		}
		output_notl("%s",$row['name']);
		rawoutput("</td><td align=right>");
		output_notl("%s",$row['level']);
		rawoutput("</td></tr>");
	}
	rawoutput("</table>");
	addnav("Back to HoF", "hof.php");
	villagenav();
	page_footer();
}
if ($op=="armorhof"){
	page_header("Armor Forge HOF");
	$acc = db_prefix("accounts");
	$mp = db_prefix("module_userprefs");
	$sql = "SELECT $acc.name AS name,
	$acc.acctid AS acctid,
	$mp.value AS level,
	$mp.userid FROM $mp INNER JOIN $acc
	ON $acc.acctid = $mp.userid
	WHERE $mp.modulename = 'clanforge'
	AND $mp.setting = 'levela'
	AND $mp.value > 0 ORDER BY ($mp.value+0)
	DESC limit ".get_module_setting("list")."";
	$result = db_query($sql);
	$rank = translate_inline("Level");
	$name = translate_inline("Name");
	output("`n`b`c`4Armor Forge HoF`n`n`c`b");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center'>");
	rawoutput("<tr class='trhead'><td align=center>$name</td><td align=center>$rank</td></tr>");
	for ($i=0;$i < db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		if ($row['name']==$session['user']['name']){
			rawoutput("<tr class='trhilight'><td>");
		}else{
			rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td align=left>");
		}
		output_notl("%s",$row['name']);
		rawoutput("</td><td align=right>");
		output_notl("%s",$row['level']);
		rawoutput("</td></tr>");
	}
	rawoutput("</table>");
	addnav("Back to HoF", "hof.php");
	villagenav();
	page_footer();
}
if ($op=="knock"){
	output("You knock upon the door of the hut, and from inside you hear a shuffling sound, then the turning of a key.  As the door slowly creaks open you find yourself face to face with an old wizened hag.  Standing slightly aside she gestures you to enter");
	output_notl("`n`n");
	output("You walk through the door, and take a seat that she gestures too.");
	output_notl("`n`n");
	output("She takes a seat across the table from you, clearing her throat she speaks. `4\"Stranger, my name is Lyubitshka, welcome to my home. For a moment of your time, I can read your fortune.  Do you have the time?\"");
	addnav("Take the Time","runmodule.php?module=clanforge&op=time");
	addnav("Leave","runmodule.php?module=clanforge&op=leave");
}
if ($op=="leave"){
	output("Getting to your feet, you excuse yourself and leave the hut, returning to the forest");
	addnav("Return to forest","forest.php?");
	$session['user']['specialinc']="";
}
if ($op=="time"){
	output("Nodding slowly, you agree to let Lyubitshka tell your fortune. Reaching for your hand, she traces your palm before telling you ");
	clanforge_fortune();
	output_notl("`n`n");
	output("Thanking Lyubitshka for her time, you leave the hut");
	addnav("Leave","forest.php");
	$session['user']['specialinc']=="";
}
if ($op=="enter"){
	output("`b`c`4Clan Forge`b`c");
	output_notl("`n`n");
	output("`$ You enter the Clan Forge, you approach your `)Stone Anvil`$ and set to work creating...");
	output_notl("`n`n`n");
	clear_module_pref("paid");
	clear_module_pref("paida");
	$wlevel=get_module_pref("level");
	$alevel=get_module_pref("levela");
	output("You are on Level %s for Weapons and Level %s for Armor",$wlevel,$alevel);
	output_notl("`n`n");
	output("`7Note: Forging weapons or armor will cost you `^%s `7gems per attempt, you may also only forge levels of 5 times your dk eg. 10 dks forge up to level 50.",$cost);
	output_notl("`n`n");
	output("`b`&The Forge will now only take gems per attempt/try at making a weapon or armor.`b");
	addnav("Weapons","runmodule.php?module=clanforge&op=weapon");
	addnav("Armor","runmodule.php?module=clanforge&op=armor");
	modulehook("clanforge");
	addnav("Forget it","clan.php");
}
if ($op=="weapon"){
	output("`b`c`4Weapons`b`c");
	output_notl("`n`n");
	$dk = $session['user']['dragonkills'];
	$lvlmax = $dk*5;
	if ($level>=$lvlmax){
		output("Sorry you have forged to your current maximum level, please try again after a dragonkill");
		addnav("Clan Halls","clan.php");
	}else{
	$attempts = round($level*0.5);
	if ($fortune >500){
		$fortune=500;
	}
	$fc = ($fortune*.001);
	$percent = round($attempts*$fc);
	$mt = round($attempts-$percent);
	if ($mt>300){
		$mt = 300;
	}
	output("`$ Picking up a large hammer you begin to work on your new weapon, you are currently at level %s.  It will take you up to %s tries to make this weapon, you have tried %s times.",$level,$mt,$tries);
	rawoutput("<form id='weapons' action='runmodule.php?module=clanforge&op=cweapon' method='POST'>");
	rawoutput("<table cellpadding='0' cellspacing='0' border='0' width='200'>");
	rawoutput("<tr><td>");
	output("Enter Custom Name, Limit 50 characters");
	rawoutput("</td><td>");
	rawoutput("<input id='wname' name='wname' size='50' maxlength='50'>");
	rawoutput("</td></tr>");
	$click = translate_inline("Create");
	rawoutput("<input type='submit' class='button' value='$click'>");
	rawoutput("</table>");
	rawoutput("</form>");
	rawoutput("<script language='JavaScript'>document.getElementById('hp').focus();</script>");
	addnav("","runmodule.php?module=clanforge&op=cweapon");
	villagenav();
}
}
if ($op=="armor"){
	output("`b`c`4Armor`b`c");
	output_notl("`n`n");
	$dk = $session['user']['dragonkills'];
	$lvlmax = $dk*5;
	if ($levela>=$lvlmax){
		output("Sorry you have forged to your current maximum level, please try again after a dragonkill");
		addnav("Clan Halls","clan.php");
	}else{
	$attempts = round($levela*0.5);
	if ($fortune >500){
		$fortune=500;
	}
	$fc = ($fortune*.001);
	$percent = round($attempts*$fc);
	$mt = round($attempts-$percent);
	if ($mt>300){
		$mt = 300;
	}
	output("`$ Picking up a large hammer you begin to work on your new armor, you are currently at level %s.  It will take you up to %s tries to make this armor, you have tried %s times.",$levela,$mt,$atries);
	rawoutput("<form id='armors' action='runmodule.php?module=clanforge&op=carmor' method='POST'>");
	rawoutput("<table cellpadding='0' cellspacing='0' border='0' width='200'>");
	rawoutput("<tr><td>");
	output("Enter Custom Name, Limit 50 characters");
	rawoutput("</td><td>");
	rawoutput("<input id='aname' name='aname' size='50' maxlength='50'>");
	rawoutput("</td></tr>");
	$click = translate_inline("Create");
	rawoutput("<input type='submit' class='button' value='$click'>");
	rawoutput("</table>");
	rawoutput("</form>");
	rawoutput("<script language='JavaScript'>document.getElementById('hp').focus();</script>");
	addnav("","runmodule.php?module=clanforge&op=carmor");
villagenav();
}
}
if ($op=="cweapon"){
	$wn=httppost('wname');
	if ($session['user']['gems']<$cost){
		output("Sorry you do not have the required gems onhand to forge anymore");
		addnav("Clan Halls","clan.php");
	}else{
		$session['user']['gems']-=$cost;
		if ($wn==""){
			$wn = get_module_pref("name");
		}
		$attempts = round($level*0.5);
		if ($fortune >500){
			$fortune=500;
		}
		$fc = ($fortune*.001);
		$percent = round($attempts*$fc);
		$mt = round($attempts-$percent);
		if ($mt>300){
			$mt = 300;
		}
		$batk = round(($level*2)+20);
		$bonus = $fortune*.1;
		if ($tries>=$mt){
			$create = e_rand(1,4);
			switch ($create){
				case 1:
				output("You've created blunt %s",$wn);
				$wname = ("Blunt $wn");
				$atk=$batk-7;
				$wcost = $atk*690;
				set_module_pref("name",$wname);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$atk);
				$lvl = $level+1;
				set_module_pref("level",$lvl);
				output_notl("`n`n");
				output("Attack: %s",$atk);
				clear_module_pref("paid");
				addnav("Equip","runmodule.php?module=clanforge&op=equip");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=1");
				break;
				case 2:
				output("You've created bent %s",$wn);
				$wname = ("Bent $wn");
				$atk=$batk-12;
				$wcost = $atk*690;
				set_module_pref("name",$wname);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$atk);
				$lvl = $level+1;
				set_module_pref("level",$lvl);
				output_notl("`n`n");
				output("Attack: %s",$atk);
				clear_module_pref("paid");
				addnav("Equip","runmodule.php?module=clanforge&op=equip");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=1");
				break;
				case 3:
				output("You've created %s",$wn);
				$atk=$batk;
				$wcost = $atk*690;
				set_module_pref("name",$wn);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$atk);
				$lvl = $level+1;
				set_module_pref("level",$lvl);
				output_notl("`n`n");
				output("Attack: %s",$atk);
				clear_module_pref("paid");
				addnav("Equip","runmodule.php?module=clanforge&op=equip");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=1");
				break;
				case 4:
				output("You've created %s`0 and its perfect",$wn);
				$atk=$batk+5;
				$wcost = $atk*690;
				set_module_pref("name",$wn);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$atk);
				$lvl = $level+2;
				set_module_pref("level",$lvl);
				clear_module_pref("paid");
				output_notl("`n`n");
				output("Attack: %s",$atk);
				addnav("Equip","runmodule.php?module=clanforge&op=equip");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=1");
				break;
			}
			addnav("Discard","runmodule.php?module=clanforge&op=discard");
			set_module_pref("tries",0);
		}elseif ($tries<$mt){
			$create = e_rand(1,100);
				switch ($create){
					case 1:
				output("You've created blunt %s",$wn);
				$wname = ("Blunt $wn");
				$atk=$batk-7;
				$wcost = $atk*690;
				set_module_pref("name",$wname);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$atk);
				$lvl = $level+1;
				set_module_pref("level",$lvl);
				output_notl("`n`n");
				output("Attack: %s",$atk);
				addnav("Equip","runmodule.php?module=clanforge&op=equip");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=1");
				addnav("Discard","runmodule.php?module=clanforge&op=discard");
				set_module_pref("tries",0);
				clear_module_pref("paid");
				break;
				case 2:
				output("You've created bent %s",$wn);
				$wname = ("Bent $wn");
				$atk=$batk-12;
				$wcost = $atk*690;
				set_module_pref("name",$wname);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$atk);
				$lvl = $level+1;
				set_module_pref("level",$lvl);
				output_notl("`n`n");
				output("Attack: %s",$atk);
				addnav("Equip","runmodule.php?module=clanforge&op=equip");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=1");
				addnav("Discard","runmodule.php?module=clanforge&op=discard");
				set_module_pref("tries",0);
				clear_module_pref("paid");
				break;
				case 3:
				output("You've created %s",$wn);
				$atk=$batk;
				$wcost = $atk*690;
				set_module_pref("name",$wn);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$atk);
				$lvl = $level+1;
				set_module_pref("level",$lvl);
				output_notl("`n`n");
				output("Attack: %s",$atk);
				addnav("Equip","runmodule.php?module=clanforge&op=equip");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=1");
				addnav("Discard","runmodule.php?module=clanforge&op=discard");
				set_module_pref("tries",0);
				clear_module_pref("paid");
				break;
				case 4:
				output("You've created %s`0 and its perfect",$wn);
				$atk=$batk+5;
				$wcost = $atk*690;
				set_module_pref("name",$wn);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$atk);
				$lvl = $level+2;
				set_module_pref("level",$lvl);
				output_notl("`n`n");
				output("Attack: %s",$atk);
				addnav("Equip","runmodule.php?module=clanforge&op=equip");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=1");
				addnav("Discard","runmodule.php?module=clanforge&op=discard");
				set_module_pref("tries",0);
				clear_module_pref("paid");
				break;
				case 5://failed to create
				case 6:
				case 7;
				case 8:
				case 9:
				case 10:
				case 11:
				case 12:
				case 13:
				case 14:
				case 15:
				case 16:
				case 17:
				case 18:
				case 19:
				case 20:
				case 21:
				case 22:
				case 23:
				case 24:
				case 25:
				case 26:
				case 27:
				case 28:
				case 29:
				case 30:
				case 31:
				case 32:
				case 33:
				case 34:
				case 35:
				case 36:
				case 37:
				case 38:
				case 39:
				case 40:
				case 41:
				case 42:
				case 43:
				case 44:
				case 45:
				case 46:
				case 47:
				case 48:
				case 49:
				case 50:
				case 51:
				case 52:
				case 53:
				case 54:
				case 55:
				case 56:
				case 57:
				case 58:
				case 59:
				case 60:
				case 61:
				case 62:
				case 63:
				case 64:
				case 65:
				case 66:
				case 67:
				case 68:
				case 69:
				case 70:
				case 71:
				case 72:
				case 73:
				case 74:
				case 75:
				case 76:
				case 77:
				case 78:
				case 79:
				case 80:
				case 81:
				case 82:
				case 83:
				case 84:
				case 85:
				case 86:
				case 87:
				case 88:
				case 89:
				case 90:
				case 91:
				case 92:
				case 93:
				case 94:
				case 95:
				case 96:
				case 97:
				case 98:
				case 99:
				case 100:
				output("You fail to create a weapon");
				output("It will take you up to %s tries to make this weapon, you have tried %s times.",$mt,$tries);
				output_notl("`n`n");
				if ($bonus>0){
					output("`^Your fortune gives you a bonus of %s percent.  Making it easier to work.",$bonus);
				}
				if ($bonus==0){
					output("`^You have no fortune, your chances are the same");
				}
				if ($bonus<0){
					output("`^Your fortune makes it harder to work. Taking an extra %s percent of tries",$bonus);
				}
				addnav("Try Again","runmodule.php?module=clanforge&op=cweapon");
				addnav("Try Again and enter new name","runmodule.php?module=clanforge&op=weapon");
				addnav("Come back later","clan.php?");
				set_module_pref("name",$wn);
				$nt = $tries+1;
				set_module_pref("tries",$nt);
				break;
			}
		}
	}
}
if ($op=="carmor"){
	$wn=httppost('aname');
	if ($session['user']['gems']<$cost){
		output("Sorry you do not have the required gems onhand to forge");
		addnav("Clan Halls","clan.php");
	}else{
		$session['user']['gems']-=$cost;
		if ($wn==""){
			$wn = get_module_pref("name");
		}
		$attempts = round($levela*0.5);
		if ($fortune >500){
			$fortune=500;
		}
		$fc = ($fortune*.001);
		$percent = round($attempts*$fc);
		$mt = round($attempts-$percent);
		if ($mt>300){
			$mt = 300;
		}
		$bdef = round(($levela*2)+20);
		$bonus = $fortune*.1;
		if ($atries>=$mt){
			$create = e_rand(1,4);
			switch ($create){
				case 1:
				output("You've created Cracked %s",$wn);
				$wname = ("Cracked $wn");
				$def=$bdef-7;
				$wcost = $def*690;
				set_module_pref("name",$wname);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$def);
				$lvl = $levela+1;
				set_module_pref("levela",$lvl);
				output_notl("`n`n");
				output("Defense: %s",$def);
				addnav("Equip","runmodule.php?module=clanforge&op=equipa");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=2");
				addnav("Discard","runmodule.php?module=clanforge&op=discarda");
				clear_module_pref("paida");
				set_module_pref("atries",0);
				break;
				case 2:
				output("You've created Dented %s",$wn);
				$wname = ("Dented $wn");
				$def=$bdef-12;
				$wcost = $def*690;
				set_module_pref("name",$wname);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$def);
				$lvl = $levela+1;
				set_module_pref("levela",$lvl);
				output_notl("`n`n");
				output("Defense: %s",$def);
				addnav("Equip","runmodule.php?module=clanforge&op=equipa");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=2");
				addnav("Discard","runmodule.php?module=clanforge&op=discarda");
				set_module_pref("atries",0);
				clear_module_pref("paida");
				break;
				case 3:
				output("You've created %s",$wn);
				$def=$bdef;
				$wcost = $def*690;
				set_module_pref("name",$wn);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$def);
				$lvl = $levela+1;
				set_module_pref("levela",$lvl);
				output_notl("`n`n");
				output("Defense: %s",$def);
				addnav("Equip","runmodule.php?module=clanforge&op=equipa");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=2");
				addnav("Discard","runmodule.php?module=clanforge&op=discarda");
				set_module_pref("atries",0);
				clear_module_pref("paida");
				break;
				case 4:
				output("You've created %s`0 and its perfect",$wn);
				$def=$bdef+5;
				$wcost = $def*690;
				set_module_pref("name",$wn);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$def);
				$lvl = $levela+2;
				set_module_pref("levela",$lvl);
				output_notl("`n`n");
				output("Defense: %s",$def);
				addnav("Equip","runmodule.php?module=clanforge&op=equipa");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=2");
				addnav("Discard","runmodule.php?module=clanforge&op=discarda");
				set_module_pref("atries",0);
				clear_module_pref("paida");
				break;
			}
			addnav("Clan Halls","clan.php");
		}elseif ($atries<$mt){
			$create = e_rand(1,100);
				switch ($create){
					case 1:
				output("You've created Cracked %s",$wn);
				$wname = ("Cracked $wn");
				$def=$bdef-7;
				$wcost = $def*690;
				set_module_pref("name",$wname);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$def);
				$lvl = $levela+1;
				set_module_pref("levela",$lvl);
				output_notl("`n`n");
				output("Defense: %s",$def);
				addnav("Equip","runmodule.php?module=clanforge&op=equipa");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=2");
				addnav("Discard","runmodule.php?module=clanforge&op=discarda");
				set_module_pref("atries",0);
				clear_module_pref("paida");
				break;
				case 2:
				output("You've created Dented %s",$wn);
				$wname = ("Dented $wn");
				$def=$bdef-12;
				$wcost = $def*690;
				set_module_pref("name",$wname);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$def);
				$lvl = $levela+1;
				set_module_pref("levela",$lvl);
				output_notl("`n`n");
				output("Defense: %s",$def);
				addnav("Equip","runmodule.php?module=clanforge&op=equipa");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=2");
				addnav("Discard","runmodule.php?module=clanforge&op=discarda");
				set_module_pref("atries",0);
				clear_module_pref("paida");
				break;
				case 3:
				output("You've created %s",$wn);
				$def=$bdef;
				$wcost = $def*690;
				set_module_pref("name",$wn);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$def);
				$lvl = $levela+1;
				set_module_pref("levela",$lvl);
				output_notl("`n`n");
				output("Defense: %s",$def);
				addnav("Equip","runmodule.php?module=clanforge&op=equipa");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=2");
				addnav("Discard","runmodule.php?module=clanforge&op=discarda");
				set_module_pref("atries",0);
				clear_module_pref("paida");
				break;
				case 4:
				output("You've created %s`0 and its perfect",$wn);
				$def=$bdef+5;
				$wcost = $def*690;
				set_module_pref("name",$wn);
				set_module_pref("cost",$wcost);
				set_module_pref("value",$def);
				$lvl = $levela+2;
				set_module_pref("levela",$lvl);
				output_notl("`n`n");
				output("Defense: %s",$def);
				addnav("Equip","runmodule.php?module=clanforge&op=equipa");
				addnav("Send to Shop","runmodule.php?module=clanforge&op=sendshop&type=2");
				addnav("Discard","runmodule.php?module=clanforge&op=discarda");
				set_module_pref("atries",0);
				clear_module_pref("paida");
				break;
				case 5://failed to create
				case 6:
				case 7;
				case 8:
				case 9:
				case 10:
				case 11:
				case 12:
				case 13:
				case 14:
				case 15:
				case 16:
				case 17:
				case 18:
				case 19:
				case 20:
				case 21:
				case 22:
				case 23:
				case 24:
				case 25:
				case 26:
				case 27:
				case 28:
				case 29:
				case 30:
				case 31:
				case 32:
				case 33:
				case 34:
				case 35:
				case 36:
				case 37:
				case 38:
				case 39:
				case 40:
				case 41:
				case 42:
				case 43:
				case 44:
				case 45:
				case 46:
				case 47:
				case 48:
				case 49:
				case 50:
				case 51:
				case 52:
				case 53:
				case 54:
				case 55:
				case 56:
				case 57:
				case 58:
				case 59:
				case 60:
				case 61:
				case 62:
				case 63:
				case 64:
				case 65:
				case 66:
				case 67:
				case 68:
				case 69:
				case 70:
				case 71:
				case 72:
				case 73:
				case 74:
				case 75:
				case 76:
				case 77:
				case 78:
				case 79:
				case 80:
				case 81:
				case 82:
				case 83:
				case 84:
				case 85:
				case 86:
				case 87:
				case 88:
				case 89:
				case 90:
				case 91:
				case 92:
				case 93:
				case 94:
				case 95:
				case 96:
				case 97:
				case 98:
				case 99:
				case 100:
				output("You fail to create a armor");
				output("It will take you up to %s tries to make this armor, you have tried %s times.",$mt,$atries);
				output_notl("`n`n");
				if ($bonus>0){
					output("`^Your fortune gives you a bonus of %s percent.  Making it easier to work.",$bonus);
				}
				if ($bonus==0){
					output("`^You have no fortune, your chances are the same");
				}
				if ($bonus<0){
					output("`^Your fortune makes it harder to work. Taking an extra %s percent of tries",$bonus);
				}
				addnav("Try Again","runmodule.php?module=clanforge&op=carmor");
				addnav("Try Again and enter new name","runmodule.php?module=clanforge&op=armor");
				addnav("Come back later","clan.php?");
				set_module_pref("name",$wn);
				$nt = $atries+1;
				set_module_pref("atries",$nt);
				break;
			}
		}
	}
}
if ($op=="discard"){
	output("You toss your weapon onto a pile of discards from other clan members in the corner");
	addnav("Try again","runmodule.php?module=clanforge&op=weapon");
	addnav("Try armor","runmodule.php?module=clanforge&op=armor");
	addnav("Clan Halls","clan.php");
}
if ($op=="discarda"){
	output("You toss your armor onto a pile of discards from other clan members in the corner");
	addnav("Try again","runmodule.php?module=clanforge&op=armor");
	addnav("Try Weapons","runmodule.php?module=clanforge&op=weapon");
	addnav("Clan Halls","clan.php");
}
if ($op=="sendshop"){
	$fname=get_module_pref("name");
	$fcost=get_module_pref("cost");
	$fvalue=get_module_pref("value");
	$type=httpget('type');
	$creator = $session['user']['acctid'];
	$clan = $session['user']['clanid'];
	db_query("INSERT INTO " .db_prefix("clanshop"). " (shopid,type,name,value,cost,clan,creator,buyer) VALUES ('0','$type','$fname','$fvalue','$fcost','$clan','$creator','0')");
	output("Your %s `0 has been sent to the clanshop, for others to buy.",$wname);
	addnav("Weapons","runmodule.php?module=clanforge&op=weapon");
	addnav("Armor","runmodule.php?module=clanforge&op=armor");
	addnav("Clan Halls","clan.php");
}
if ($op=="equip"){
	$fname=get_module_pref("name");
	$fcost=get_module_pref("cost");
	$fvalue=get_module_pref("value");
	$weapondamage=$session['user']['weapondmg'];
	$session['user']['attack']-=$weapondamage;
	$session['user']['attack']+=$fvalue;
	$session['user']['weapon']=$fname;
    $session['user']['weapondmg']=$fvalue;
    $session['user']['weaponvalue']=$fcost;
    output("You toss your old weapon into the pile of discards, and put your new %s `0on",$fname);
	addnav("Weapons","runmodule.php?module=clanforge&op=weapon");
	addnav("Armor","runmodule.php?module=clanforge&op=armor");
	addnav("Clan Halls","clan.php");
}
if ($op=="equipa"){
	$fname=get_module_pref("name");
	$fcost=get_module_pref("cost");
	$fvalue=get_module_pref("value");
	$armor=$session['user']['armordef'];
	$session['user']['defense']-=$armor;
	$session['user']['defense']+=$fvalue;
	$session['user']['armor']=$fname;
    $session['user']['armordef']=$fvalue;
    $session['user']['armorvalue']=$fcost;
    output("You toss your old armor onto the pile of discards, and put your new %s `0on",$fname);
	addnav("Weapons","runmodule.php?module=clanforge&op=weapon");
	addnav("Armor","runmodule.php?module=clanforge&op=armor");
	addnav("Clan Halls","clan.php");
}
if ($op=="shop"){
	output("`b`c`4Clan Shops`b`c");
	output("`n`n");
	output("`$ Here you can buy armor and weapons made by your fellow clan members");
	addnav("Weapons","runmodule.php?module=clanforge&op=wshop");
	addnav("Armor","runmodule.php?module=clanforge&op=ashop");
	modulehook("clanforge-shop");
	addnav("Clan Halls","clan.php");
}
if ($op=="wshop"){
	$ref = round(($session['user']['weaponvalue']*.75),0);
	output("You look at the weapons on display, the shop owner informs you he'll give you %s for your current weapon",$ref);
	$clan = $session['user']['clanid'];
	$dk = $session['user']['dragonkills'];
	$dklimit = ($dk*3)+1;
	$buy1 = translate_inline("");
   	$wname = translate_inline("Weapon Name");
    $watk = translate_inline("Attack");
    $wcost=translate_inline("Cost");
    $choose = translate_inline("Buy");
   	rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
    rawoutput("<tr class='trhead'>");
   	rawoutput("<td>$buy1</td><td>$wname</td><td>$watk</td><td>$wcost</td>");
    rawoutput("</tr>");
   	$sql = "SELECT * FROM " .db_prefix("clanshop"). " WHERE type = '1' AND clan = '$clan' AND buyer < 1 AND value < '$dklimit' ORDER BY 'value'";
	$res = db_query($sql);
   	for ($i=0;$i<db_num_rows($res);$i++){
   		$row = db_fetch_assoc($res);
    	$id = $row['shopid'];
        $name = $row['name'];
    	$value = $row['value'];
    	$cost = $row['cost'];
       	rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
		rawoutput("<td nowrap>[ <a href='runmodule.php?module=clanforge&op=buyweapon&id=$id'>$choose</a>");
        addnav("","runmodule.php?module=clanforge&op=buyweapon&id=$id");
	    output_notl("<td>`^%s</td>`0", $row['name'], true);
    	output_notl("<td>`&%s`0</td>", $row['value'], true);
        output_notl("<td>`^%s`0</td>", $row['cost'], true);
        rawoutput("</tr>");
	}
    rawoutput("</table>");
    addnav("Clan Halls","clan.php");
}
if ($op=="ashop"){
	$ref = round(($session['user']['armorvalue']*.75),0);
	output("You look at the armor on display, the shop owner informs you he'll give you %s for your current armor",$ref);
	$clan = $session['user']['clanid'];
	$dk = $session['user']['dragonkills'];
	$dklimit = ($dk*3)+1;
	$buy1 = translate_inline("");
   	$wname = translate_inline("Armor Name");
    $watk = translate_inline("Defense");
    $wcost=translate_inline("Cost");
    $choose = translate_inline("Buy");
   	rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
    rawoutput("<tr class='trhead'>");
   	rawoutput("<td>$buy1</td><td>$wname</td><td>$watk</td><td>$wcost</td>");
    rawoutput("</tr>");
   	$sql = "SELECT * FROM " .db_prefix("clanshop"). " WHERE type = '2' AND clan = '$clan' AND buyer < 1 AND value < '$dklimit' ORDER BY 'value'";
	$res = db_query($sql);
    for ($i=0;$i<db_num_rows($res);$i++){
	   	$row = db_fetch_assoc($res);
	   	$id = $row['shopid'];
	    $name = $row['name'];
	   	$value = $row['value'];
	   	$cost = $row['cost'];
       	rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
        rawoutput("<td nowrap>[ <a href='runmodule.php?module=clanforge&op=buyarmor&id=$id'>$choose</a>");
        addnav("","runmodule.php?module=clanforge&op=buyarmor&id=$id");
	    output_notl("<td>`^%s</td>`0", $row['name'], true);
    	output_notl("<td>`&%s`0</td>", $row['value'], true);
        output_notl("<td>`^%s`0</td>", $row['cost'], true);
        rawoutput("</tr>");
	}
    rawoutput("</table>");
    addnav("Clan Halls","clan.php");
}
if ($op=="buyweapon"){
	$clanid = $session['user']['clanid'];
	$ref = round(($session['user']['weaponvalue']*.75),0);
	$id = httpget("id");
	$sql = "SELECT * FROM " .db_prefix("clanshop"). " WHERE shopid = '$id'";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$name = $row['name'];
	$value = $row['value'];
	$cost = $row['cost'];
	$creator = $row['creator'];
	$buyer = $session['user']['acctid'];
	$value2 = $value*0.33;
	if ($session['user']['gold']<$cost){
		output("Sorry you cannot afford this weapon");
	}elseif ($session['user']['dragonkills']<$value2){
		output("Sorry this weapon is too strong for you");
	}elseif ($session['user']['gold']>=$cost &&  $session['user']['dragonkills']>=$value2){
		$weapondamage=$session['user']['weapondmg'];
		$session['user']['attack']-=$weapondamage;
		$session['user']['attack']+=$value;
		$session['user']['weapon']=$name;
        $session['user']['weapondmg']=$value;
       	$session['user']['weaponvalue']=$cost;
	    output("You equip your new %s `0 and head out",$name);
        db_query("UPDATE " .db_prefix("clanshop"). " SET buyer = '$buyer' WHERE shopid = '$id'");
        $amt = $cost-$ref;
	    $session['user']['gold']-=$amt;
	}
	addnav("Clan Halls","clan.php?");
}
if ($op=="buyarmor"){
    $clanid = $session['user']['clanid'];
	$ref = round(($session['user']['armorvalue']*.75),0);
	$id = httpget("id");
	$sql = "SELECT * FROM " .db_prefix("clanshop"). " WHERE shopid = '$id'";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$name = $row['name'];
	$value = $row['value'];
	$cost = $row['cost'];
	$creator = $row['creator'];
	$buyer = $session['user']['acctid'];
	$value2 = $value*0.33;
	if ($session['user']['gold']<$cost){
		output("Sorry you cannot afford this armor");
	}elseif ($session['user']['dragonkills']<$value2){
		output("Sorry this armor is too strong for you");
	}elseif ($session['user']['gold']>=$cost && $session['user']['dragonkills']>=$value2){
		$armor=$session['user']['armordef'];
		$session['user']['defense']-=$armor;
		$session['user']['defense']+=$value;
		$session['user']['armor']=$name;
   	    $session['user']['armordef']=$value;
       	$session['user']['armorvalue']=$cost;
        output("You equip your new %s `0 and head out",$name);
   	    db_query("UPDATE " .db_prefix("clanshop"). " SET buyer = '$buyer' WHERE shopid = '$id'");
      	$amt = $cost-$ref;
       	$session['user']['gold']-=$amt;
	}
	addnav("Clan Halls","clan.php?");
}
page_footer();
?>