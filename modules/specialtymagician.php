<?php
//addnews ready
// mail ready
// translator ready

function specialtymagician_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Magician",
		"author" => "Crazed Lady",
		"version" => "1.0",
		"download" => "http://dragonprime.net/users/K/magician.zip",
		"category" => "Specialties",
		"prefs" => array(
			"Specialty - Magician User Prefs,title",
			"skill"=>"Skill points in Magician,int|0",
			"uses"=>"Uses of Magician allowed,int|0",
			"mindk"=>"How many DKs do you need before the race is available?,int|5",
		),
	);
	return $info;
}

function specialtymagician_install(){
	$sql = "DESCRIBE " . db_prefix("accounts");
	$result = db_query($sql);
	$specialty="MP";
	while($row = db_fetch_assoc($result)) {
		// Convert the user over
		if ($row['Field'] == "magic") {
			debug("Migrating magician field");
			$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtymagician', 'skill', acctid, magic FROM " . db_prefix("accounts");
			db_query($sql);
			debug("Dropping magician from accounts table");
			$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP magic";
			db_query($sql);
		} elseif ($row['Field']=="magicuses") {
			debug("Migrating magician uses field");
			$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtymagician', 'uses', acctid, magicuses FROM " . db_prefix("accounts");
			db_query($sql);
			debug("Dropping magicianuses field from accounts table");
			$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP magicianuses";
			db_query($sql);
		}
	}
	debug("Migrating Magician Specialty");
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='$specialty' WHERE specialty='2'";
	db_query($sql);

	module_addhook("choose-specialty");
	module_addhook("set-specialty");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addhook("incrementspecialty");
	module_addhook("specialtynames");
	module_addhook("specialtymodules");
	module_addhook("specialtycolor");
	module_addhook("dragonkill");
	return true;
}

function specialtymagician_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='MP'";
	db_query($sql);
	return true;
}

function specialtymagician_dohook($hookname,$args){
	global $session,$resline;

	$spec = "MA";
	$name = "Magician";
	$ccode = "`#";

	switch ($hookname) {
	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;
	case "choose-specialty":
		if ($session['user']['specialty'] == "" ||
				$session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Using your magician skills");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`3Wanting to follow in your parents footsteps, you feel it is time to take out your magic hat and use it.");
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;
	case "specialtymodules":
		$args[$spec] = "specialtymagician";
		break;
	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$name = translate_inline($name);
			$c = $args['color'];
			output("`n%sYou gain a level in `&%s%s to `#%s%s!",
					$c, $name, $c, $new, $c);
			$x = $new % 3;
			if ($x == 0){
				output("`n`^You gain an extra use point!`n");
				set_module_pref("uses", get_module_pref("uses") + 1);
			}else{
				if (3-$x == 1) {
					output("`n`^Only 1 more skill level until you gain an extra use point!`n");
				} else {
					output("`n`^Only %s more skill levels until you gain an extra use point!`n", (3-$x));
				}
			}
			output_notl("`0");
		}
		break;
	case "newday":
		$bonus = getsetting("specialtybonus", 1);
		if($session['user']['specialty'] == $spec) {
			$name = translate_inline($name);
			if ($bonus == 1) {
				output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode,$name,$ccode,$name);
			} else {
				output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode,$name,$bonus,$ccode,$name);
			}
		}
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt = $amt + $bonus;
		set_module_pref("uses", $amt);
		break;
	case "fightnav-specialties":
		$uses = get_module_pref("uses");
		$script = $args['script'];
		if ($uses > 0) {
			addnav(array("$ccode2$name (%s points)`0", $uses), "");
			addnav(array("e?$ccode2 &#149; `#Quick Heal`7 (%s)`0", 1),
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode2 &#149; `#Cheap Trick`7 (%s)`0", 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode2 &#149; `#Rabbit Pummel`7 (%s)`0", 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("g?$ccode2 &#149; `#Pet Thumper`7 (%s)`0", 5),
					$script."op=fight&skill=$spec&l=5",true);
		}
		break;
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('mp1', array(
						"startmsg"=>"`^You start to heal quickly!",
						"name"=>"`#Quick Heal",
						"rounds"=>4,
						"wearoff"=>"Your healing trickery has stopped!",
						"regen"=>$session['user']['level'],
						"effectmsg"=>"You heal yourself for {damage}.",
						"effectnodmgmsg"=>"You have no more wounds to heal.",
						"schema"=>"specialtymagician"
					));
					break;
				case 2:
					apply_buff('mp2', array(
						"startmsg"=>"`^You pull a cheap trick!",
						"name"=>"`#Cheap Trick",
						"rounds"=>5,
						"wearoff"=>"Your cheap trick ends with a bang.",
						"minioncount"=>1,
						"effectmsg"=>"Your cheap trick hurts {badguy} for `^{damage}`) points.",
						"minbadguydamage"=>1,
						"maxbadguydamage"=>$session['user']['level']*3,
						"schema"=>"specialtymagician"
					));
					break;
				case 3:
					apply_buff('mp3', array(
						"startmsg"=>"`^You pull a rabbit out of your hat.",
						"name"=>"`#Rabbit Pummel",
						"rounds"=>6,
						"wearoff"=>"You place your rabbit back in your hat.",
                                                "minioncount"=>3,
						"effectmsg"=>"Your rabbit pummels {badguy} for {damage} damage.",
						"effectfailmsg"=>"Your rabbit misses {badguy}.",
						"schema"=>"specialtymagician"
					));
					break;
				case 5:
					apply_buff('mp5', array(
						"startmsg"=>"`^You hear the rumble of your pet rabbit in the distance",
						"name"=>"`#Pet Thumper",
						"rounds"=>7,
						"wearoff"=>"Your pet bounces back into the forest.",
						"damageshield"=>2,
						"effectmsg"=>"Your rabbit over shadows {badguy} and scared them for `^{damage}`) damage.",
						"effectnodmg"=>"{badguy} is not intimidated by the rabbit.",
						"effectfailmsg"=>"{badguy} just laughs at the attempts of your rabbit.",
						"schema"=>"specialtymagician"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('mp0', array(
					"startmsg"=>"You call on your trusty sidekick rabbits to aid you. {badguy} laughs at them before he starts to lunge at you again.",
					"rounds"=>1,
					"schema"=>"specialtymagician"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtymagician_run(){
}
?>

