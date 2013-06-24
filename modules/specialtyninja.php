<?php

function specialtyninja_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Ninja",
		"author" => "`6Harry B and Kenny Chu",
		"version" => "1.0",
		"download"=>"http://dragonprime.net/users/Harry%20B/specialtyninja.zip",
		"category" => "Specialties",
		"prefs" => array(
		"Specialty - Ninja User Prefs,title",
		"skill"=>"Skill points in Ninja,int|0",
		"uses"=>"Uses of Ninja allowed,int|0",
		),
	);
	return $info;
}

function specialtyninja_install(){
	$sql = "DESCRIBE " . db_prefix("accounts");
	$result = db_query($sql);
	$specialty="NI";
	while($row = db_fetch_assoc($result)) {
	if ($row['Field'] == "ninja") {
		debug("Migrating ninja field");
		$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtyninja', 'skill', acctid, ninja FROM " . db_prefix("accounts");
		db_query($sql);
		debug("Dropping ninja field from accounts table");
		$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP ninja";
		db_query($sql);
	} elseif ($row['Field']=="ninjauses") {
		debug("Migrating ninja uses field");
		$sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtyninja', 'uses', acctid, ninjauses FROM " . db_prefix("accounts");
		db_query($sql);
		debug("Dropping ninjauses field from accounts table");
		$sql = "ALTER TABLE " . db_prefix("accounts") . " DROP ninjauses";
		db_query($sql);
		}
	}
	debug("Migrating ninja Specialty");
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='$specialty' WHERE specialty='4'";
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

function specialtyninja_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='NI'";
	db_query($sql);
	return true;
}

function specialtyninja_dohook($hookname,$args){
	global $session,$resline;

	$spec = "NI";
	$name = "Ninja";
	$ccode = "`3";

	switch ($hookname) {
	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;
	case "choose-specialty":
		if ($session['user']['specialty'] == "" ||
				$session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=$spec$resline");
			$t1 = translate_inline("Master of stealth and assassin skills");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`3Growing up, you spent many a day creeping up on insects and  small animals without their knowledge and swiftly killing them.");
			output("Your parents, seeing such great stealth skills sent you off to learn from the masters.");
			output("After many years of practice your skills are well honed. You have become a master of stealth and assassination.");
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;
	case "specialtymodules":
		$args[$spec] = "specialtyninja";
		break;
	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$c = $args['color'];
			$name = translate_inline($name);
			output("`n%sYou gain a level in `3%s%s to `#%s%s!",
					$c, $name, $c, $new, $c);
			$x = $new % 3;
			if ($x == 0){
				output("`n`^You gain an extra use point!`n");
				set_module_pref("uses", get_module_pref("uses") + 1);
			}else{
				if (3-$x == 1) {
					output("`n`3Only 1 more skill level until you gain an extra use point!`n");
				} else {
					output("`n`3Only %s more skill levels until you gain an extra use point!`n", (3-$x));
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
				output("`n`3For being interested in %s%s`3, you receive `^1`3 extra `&%s%s`3 use for today.`n",$ccode, $name, $ccode, $name);
			} else {
				output("`n`3For being interested in %s%s`3, you receive `^%s`3 extra `&%s%s`3 uses for today.`n",$ccode, $name,$bonus, $ccode,$name);
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
			addnav(array("$ccode$name (%s points)`0", $uses),"");
			addnav(array("$ccode &#149; Side Kick`7 (%s)`0", 1), 
			$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Poison Dart`0 (%s)`0", 2),
			$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Stealth`0 (%s)`0", 3),
			$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Powder`0 (%s)`0", 5),
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
			apply_buff('ni1',array(
			"startmsg"=>"`3You turn and side kick {badguy} right in the gut!",
			"name"=>"`3Side Kick",
			"rounds"=>5,
			"wearoff"=>"You are finished kicking.",
			"minioncount"=>round($session['user']['level']/3)+2,
			"maxbadguydamage"=>round($session['user']['level']/2,0)+1,
			"effectmsg"=>"`3Your side kick hits {badguy}`3 for `^{damage}`3 damage.",
			"effectnodmgmsg"=>"`3You try to kick {badguy}`3 but he eludes it!",
			"schema"=>"specialtyninja"));
			break;
		case 2:
			apply_buff('ni2',array(
			"startmsg"=>"`3You toss a dart treated with herbal poison.",
			"name"=>"`3Poison Dart",
			"rounds"=>5,
			"wearoff"=>"Your enemy struggles to regain his composure .",
			"atkmod"=>2.5,
			"roundmsg"=>"You take advantage of your enemies weakened condition", 
			"schema"=>"specialtyninja"));
			break;
		case 3:
			apply_buff('ni3', array(
			"startmsg"=>"`3As your enemy blinks, you disappear!",
			"name"=>"`3Stealth",
			"rounds"=>5,
			"wearoff"=>"You step back into the real world.",
			"roundmsg"=>"{badguy} looks but has trouble finding you !",
			"badguyatkmod"=>0,
			"schema"=>"specialtyninja"));
			break;
		case 5:
			apply_buff('ni5',array(
			"startmsg"=>"`3From a secret pouch you toss blinding powder into the eyes of {badguy}.",
			"name"=>"`3Powder",
			"rounds"=>5,
			"wearoff"=>"You enemy regains his vision.",
			"atkmod"=>2.5,
			"badguydefmod"=>0,
			"roundmsg"=>"{badguy} covers its eyes as the powder burns and blinds them.",
			"schema"=>"specialtyninja"));
			break;
			}
	set_module_pref("uses", get_module_pref("uses") - $l);
	}else{
		apply_buff('ni0', array(
			"startmsg"=>"You exhuasted your skills, your endurance in not what you think it is.",
			"rounds"=>1,
			"schema"=>"specialtyninja"));
			}
		}
		break;
	}
	return $args;
}

function specialtyninja_run(){
}
?>