<?php
/*

This is part of a series of specialties I'm making to recreate the core D&D classes in LoGD.

-- Enderandrew


*/

function specialtycleric_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Cleric",
		"author" => "`!Enderandrew",
		"version" => "1.01",
		"download" => "http://dragonprime.net/users/enderwiggin/specialitycleric.zip",
		"category" => "Specialties",
		"description"=>"This will add a D&D inspired Cleric Specialty to the game.",
		"vertxtloc"=>"http://dragonprime.net/users/enderwiggin/",
		"prefs" => array(
			"Specialty - Cleric User Prefs,title",
			"skill"=>"Skill points in Cleric Spells,int|0",
			"uses"=>"Uses of Cleric Spells allowed,int|0",
		),
		"settings"=> array(
			"Specialty - Cleric Settings,title",
			"mindk"=>"How many DKs do you need before the specialty is available?,int|5",
			"cost"=>"How many points do you need before the specialty is available?,int|5",
		),
	);
	return $info;
}

function specialtycleric_install(){
	$specialty="CL";
	module_addhook("apply-specialties");
	module_addhook("castlelib");
	module_addhook("castlelibbook");
	module_addhook("choose-specialty");
	module_addhook("dragonkill");
	module_addhook("fightnav-specialties");
	module_addhook("incrementspecialty");
	module_addhook("newday");
	module_addhook("pointsdesc");
	module_addhook("set-specialty");
	module_addhook("specialtycolor");
	module_addhook("specialtymodules");
	module_addhook("specialtynames");
	return true;
}

function specialtycleric_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='CL'";
	db_query($sql);
	return true;
}

function specialtycleric_dohook($hookname,$afis){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "CL";
	$name = "Cleric Spells";
	$ccode = "`1";
	$cost = get_module_setting("cost");
	$op69 = httpget('op69');

	switch ($hookname) {

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('cl1', array(
						"startmsg"=>"`1You call upon your deity's powers and cast `!Cure Light Wounds`1.",
						"name"=>"`!Cure Light Wounds",
						"rounds"=>5,
						"wearoff"=>"Your spell begins to wear off!",
						"regen"=>$session['user']['level'],
						"effectmsg"=>"You cast Cure Light Wounds and cure {damage} health.",
						"effectnodmgmsg"=>"You have no wounds to heal.",
						"schema"=>"specialtycleric"
					));
					break;
				case 2:
					apply_buff('cl2', array(
						"startmsg"=>"`1You grip your holy symbol tight and outstretch a palm towards {badguy} as you cast `!Hold Person`1!",
						"name"=>"`!Hold Person",
						"rounds"=>5,
						"wearoff"=>"{badguy} wriggles free of the spell's effects.",
						"badguyatkmod"=>0.4,
						"schema"=>"specialtycleric"
					));
					break;
				case 3:
					apply_buff('cl3', array(
						"startmsg"=>"`1You shove your holy symbol in {badguy}'s face and blast them with divine fury as you cast `!Searing Light`1!",
						"name"=>"`!Searing Light",
						"rounds"=>5,
						"wearoff"=>"Your spiritual reserves are tapped.",
						"effectmsg"=>"`!Burning, divine light blasts {badguy} for {damage} damage.",
						"effectnodmgmsg"=>"`!Your beam of divine light blasts right past {badguy}, missing completely!",
						"atkmod"=>2.5,
						"schema"=>"specialtycleric"
					));
					break;
				case 5:
					apply_buff('cl5', array(
						"startmsg"=>"`1Your god shines down brightly on you, allowing you to heal the most serious of wounds.",
						"name"=>"`!Regeneration",
						"rounds"=>5,
						"wearoff"=>"You have stopped regenerating",
						"regen"=>round($session['user']['level']*3.5),
						"effectmsg"=>"Divine strength fills you, healing {damage} health.",
						"effectnodmgmsg"=>"You have no wounds to regenerate.",
						"schema"=>"specialtycleric"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('cl0', array(
					"startmsg"=>"`!You reach for your holy symbol, but seem to have misplaced it.",
					"rounds"=>1,
					"schema"=>"specialtycleric"
				));
			}
		}
		break;

	case "castlelib":
		if ($op69 == 'cleric'){
			output("You sit down and open up the Ye Olde Bible.`n");
			output("You read for a while... in the time it takes you to read you use up`n");
			output("3 Turns.`n`n");
			output("You spend some quality time with the good book of your choosen deity, and they are,`n");
			output("quite pleased.  You learn more about your deity, and how to appease them.  You grow`n");
			output("in power as a Cleric, and your deity gives you a merit badge to boot!`n");
			$session['user']['turns']-=3;
			set_module_pref('skill',(get_module_pref('skill','specialtycleric') + 1),'specialtycleric');
			set_module_pref('uses', get_module_pref("uses",'specialtycleric') + 1,'specialtycleric');
			addnav("Continue","runmodule.php?module=lonnycastle&op=library");
			}
		break;

	case "castlelibbook":
		output("Ye Olde Bible. (3 Turns)`n");
		addnav("Read a Book");
		addnav("Ye Olde Bible","runmodule.php?module=lonnycastle&op=library&op69=cleric");
		break;

	case "choose-specialty":
		if ($session['user']['dragonkills']>=get_module_setting("mindk")) {
			if ($session['user']['specialty'] == "" ||
				$session['user']['specialty'] == '0') {
				addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
				$t1 = translate_inline("Going to Seminary school");
				$t2 = appoencode(translate_inline("$ccode$name`0"));
				rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
				addnav("","newday.php?setspecialty=$spec$resline");
			}
		}
		break;
		
	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;

	case "fightnav-specialties":
		$uses = get_module_pref("uses");
		$script = $afis['script'];
		if ($uses > 0) {
			addnav(array("%s%s (%s points)`0", $ccode, $name, $uses), "");
			addnav(array("%s &#149; Cure Light Wounds`7 (%s)`0", $ccode, 1), 
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("%s &#149; Hold Person`7 (%s)`0", $ccode, 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("%s &#149; Searing Light`7 (%s)`0", $ccode, 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("%s &#149; Regenerate`7 (%s)`0", $ccode, 5),
					$script."op=fight&skill=$spec&l=5",true);
		}
		break;

	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$c = $afis['color'];
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
			if ($bonus == 1) {
				output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode,$name,$ccode,$name);
			} else {
				output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode,$name,$bonus,$ccode,$name);
			}
		}
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt++;
		set_module_pref("uses", $amt);
		break;

	case "pointsdesc":
		$cost = get_module_setting("cost");
		if ($cost > 0){
			$afis['count']++;
			$format = $afis['format'];
			$str = translate("The Cleric Specialty is availiable upon reaching %s Dragon Kills and %s points.");
			$str = sprintf($str, get_module_setting("mindk"),$cost);
		}
		output($format, $str, true);
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			$session['user']['donationspent'] = $session['user']['donationspent'] + $cost;
			output("`1These have been dark times to grow up as a child, what with `@Dragons`1 running about and all. ");
			output("Many grabbed weapons, and trained themselves to defend themselves and others.  They often ");
			output("did so out of fear for their own life.  The `@Dragon`1 is mighty, and many have fallen prey to ");
			output("the `@Dragon`1's power.  For those with no Faith, fear of death is quite natural.  You, well ");
			output("you were different.  Everyone could sense a certain internal strength about you.  You have ");
			output("Faith, and that Faith will not only guide you, but it will save this land from the `@Dragon`2.`n`n");
			output("Well, you were also a rotten kid that acted very naughty, so maybe you're trying to earn some ");
			output("brownie points with your deity now...`n`n");
		}
		break;

	case "specialtycolor":
		$afis[$spec] = $ccode;
		break;

	case "specialtymodules":
		$afis[$spec] = "specialtycleric";
		break;

	case "specialtynames":
		$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
		if ($session['user']['superuser'] & SU_EDIT_USERS || $session['user']['dragonkills'] >= get_module_setting("mindk") || get_module_setting("cost") <= $pointsavailable){
			$afis[$spec] = translate_inline($name);
		}
		break;
	}
	return $afis;
}

function specialtycleric_run(){
}
?>