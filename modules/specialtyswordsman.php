<?php
// addnews ready
// mail ready
// translator ready

function specialtyswordsman_getmoduleinfo(){
	$spec_version = "1.40";
	$info = array(
		"name" => "Specialty - Swordsman",
		"author" => "Tizen",
		"version" => $spec_version,
		"download" => "http://dragonprime.net/users/Tizen/specialtyswordsman.zip",
		"category" => "Specialties",
		"settings"=>array(
			"Specialty - Swordsman Settings,title",
			"Version ". $spec_version .",note",
			"mindk"=>"Minimum DK for specialty,int|1",
		),
		"prefs" => array(
			"Specialty - Swordsman User Prefs,title",
			"skill"=>"Skill points in Swordsman,int|0",
			"uses"=>"Uses of Swordsman allowed,int|0",
		),
	);
	return $info;
}

function specialtyswordsman_install(){
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

function specialtyswordsman_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='SW'";
	db_query($sql);
	return true;
}

function specialtyswordsman_dohook($hookname,$args){
	global $session,$resline;

	$spec = "SW";
	$name = "Swordsman";
	$ccode = "`^";

	switch ($hookname) {
	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;
	case "choose-specialty":
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Physical strength and strong defenses.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`6You always preferred playing pretend sword-fights as a child, and never turned down an opportunity to fight a bigger kid.");
			output("All those long days of practice and the occasional beating from a larger kid in the neighborhood gave you strength");
			output("and determination. Becoming a swordsman feels almost like destiny.");
		}
		break;
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;
	case "specialtymodules":
		$args[$spec] = "specialtyswordsman";
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
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode &#149; Bash`7 (%s)`0", 1), 
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Increased HP Recovery`7 (%s)`0", 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Provoke`7 (%s)`0", 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Berserk`7 (%s)`0", 5),
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
					apply_buff('sw1',array(
						"startmsg"=>"`^You bash {badguy} with your {weapon}!",
						"name"=>"`\$Bash",
						"rounds"=>1,
						"atkmod"=>3,
						"defmod"=>3,
						"schema"=>"module-specialtyswordsman"
					));
					break;
				case 2:
					apply_buff('sw2', array(
						"startmsg"=>"`^A sudden calm falls over you, and you feel invigorated!",
						"name"=>"`\$Increased HP Recovery",
						"rounds"=>6,
						"wearoff"=>"You lose your calm.",
						"regen"=>$session['user']['level'],
						"effectmsg"=>"You regenerate {damage} HP.",
						"effectnodmgmsg"=>"Your HP is full!",
						"schema"=>"module-specialtyswordsman"
					));
					break;
				case 3:
					apply_buff('sw3', array(
						"startmsg"=>"`^You provoke {badguy}.",
						"name"=>"`\$Provoke",
						"rounds"=>10,
						"wearoff"=>"{badguy} seems to be less agitated by your provoking.",
						"badguyatkmod"=>1.2,
						"badguydefmod"=>0.4,
						"roundmsg"=>"`^{badguy} is `\$provoked`^, and seems to be taking alot more damage. (but also dealing a little bit more...)",
						"schema"=>"module-specialtyswordsman"
					));
					break;
				case 5:
					apply_buff('sw5',array(
						"startmsg"=>"`^You go berserk, and fall into a blind rage.",
						"name"=>"`\$Berserk",
						"rounds"=>10,
						"atkmod"=>1.6,
						"defmod"=>0.8,
						"wearoff"=>"You regain your composure.",
						"schema"=>"module-speciatlyswordsman"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('sw0', array(
					"startmsg"=>"You try to attack {badguy} by putting your best swordsman skills into practice, but instead, you trip over your feet.",
					"rounds"=>1,
					"schema"=>"module-specialtyswordsman"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtyswordsman_run(){
}
?>
