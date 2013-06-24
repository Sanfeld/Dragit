<?php
/*

This is part of a series of specialties I'm making to recreate the core D&D classes in LoGD.
This also works well as a basic drop-in specialty because the core specialties are basically
a thief, and two magic users.  The game could use a few basic fighter-types, IMO.

It should be noted that the buffs in this particular specialty are just a tad weaker than in
other specialties I've seen.  I've offset that with a fairly significant advantage.  At each
level-up (each time you beat your master), Barbarians receive one bonus max-hitpoint.  You
don't get uber-powerful buffs when you use your points, but you're a little more resilient all
of the time.  Is it perfectly balanced?  That's a matter of opinion, and only extensive play-
testing will really say.

-- Enderandrew


*/

function specialtybarbarian_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Barbarian",
		"author" => "`!Enderandrew",
		"version" => "1.11",
		"description"=>"This adds a D&D inspired Barbarian Specialty to the game.",
		"download" => "http://dragonprime.net/users/enderwiggin/specialitybarbarian.zip",
		"vertxtloc"=>"http://dragonprime.net/users/enderwiggin/",
		"category" => "Specialties",
		"prefs" => array(
			"Specialty - Barbarian User Prefs,title",
			"skill"=>"Skill points in Barbarian Powers,int|0",
			"uses"=>"Uses of Barbarian Powers allowed,int|0",
		),
		"settings"=> array(
			"Specialty - Barbarian Settings,title",
			"mindk"=>"How many DKs do you need before the specialty is available?,int|5",
			"cost"=>"How many points do you need before the specialty is available?,int|5",
		),
	);
	return $info;
}

function specialtybarbarian_install(){
	$specialty="BB";
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
	module_addhook("training-victory");
	return true;
}

function specialtybarbarian_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='BB'";
	db_query($sql);
	return true;
}

function specialtybarbarian_dohook($hookname,$afis){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "BB";
	$name = "Barbarian Powers";
	$ccode = "`6";
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
					apply_buff('bb1', array(
						"startmsg"=>"`6Most people don't expect Barbarians to dodge blows, and that's exactly why they can!",
						"name"=>"`^Uncanny Dodge",
						"rounds"=>5,
						"wearoff"=>"You begin to tire...",
						"defmod"=>1.4,
						"schema"=>"specialtybarbarian"
					));
					break;
				case 2:
					apply_buff('bb2', array(
						"startmsg"=>"`6You get in touch with your unhappy place and open-up a can of whoop-a**!",
						"name"=>"`^Rage",
						"rounds"=>5,
						"wearoff"=>"Fatigue is setting in.",
						"effectmsg"=>"`^You lay the smack down on {badguy} for {damage} damage.",
						"effectnodmgmsg"=>"`^Maybe this anger channeling thing isn't working because you miss {badguy}!",
						"atkmod"=>1.5,
						"schema"=>"specialtybarbarian"
					));
					break;
				case 3:
					apply_buff('bb3', array(
						"startmsg"=>"`6Pain merely fuels your anger even more...",
						"name"=>"`^Damage Reduction",
						"rounds"=>5,
						"wearoff"=>"The adrenaline wears off, and the pain from battle sets in....",
						"damageshield"=>1,4,
						"schema"=>"specialtybarbarian"
					));
					break;
				case 5:
					apply_buff('bb5', array(
						"startmsg"=>"`6Being a Barbarian isn't difficult, or anything secret.  You just get really angry and wail on things.",
						"name"=>"`^Mighty Rage",
						"rounds"=>5,
						"wearoff"=>"Fatigue is setting in.",
						"effectmsg"=>"`^You lay the smack down on {badguy} for {damage} damage.",
						"effectnodmgmsg"=>"`^Maybe this anger channeling thing isn't working because you miss {badguy}!",
						"atkmod"=>1.5,
						"damageshield"=>1,4,
						"schema"=>"specialtybarbarian"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('bb0', array(
					"startmsg"=>"You try to infuse yourself with rage, but can't stop laughing about one of Cedrik's jokes.",
					"rounds"=>1,
					"schema"=>"specialtybarbarian"
				));
			}
		}
		break;

	case "castlelib":
		if ($op69 == 'barbarian'){
			output("You sit down and open up the Hello Kitty book.`n");
			output("You read for a while... in the time it takes you to read you use up`n");
			output("3 Turns.`n`n");
			output("It is a commonly accepted fact that reading anything as inanely gleeful as Hello Kitty will drive `n");
			output("people into uncontrollable fits of rage and anger.  Most people would be clubbing random unwashed `n");
			output("miscreants over the head with obtuse objects right now, but as a Barbarian, you compartmentalize `n");
			output("that seething rancor, and save it for battle.`n");
			output("`@You become more skilled as a Barbarian!`n");
			$session['user']['turns']-=3;
			set_module_pref('skill',(get_module_pref('skill','specialtybarbarian') + 1),'specialtybarbarian');
			set_module_pref('uses', get_module_pref("uses",'specialtybarbarian') + 1,'specialtybarbarian');
			addnav("Continue","runmodule.php?module=lonnycastle&op=library");
			}
		break;

	case "castlelibbook":
		output("Hello Kitty book. (3 Turns)`n");
		addnav("Read a Book");
		addnav("Hello Kitty book","runmodule.php?module=lonnycastle&op=library&op69=barbarian");
		break;

	case "choose-specialty":
		if ($session['user']['dragonkills']>=get_module_setting("mindk")) {
			if ($session['user']['specialty'] == "" ||
				$session['user']['specialty'] == '0') {
				addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
				$t1 = translate_inline("Throwing violent temper tantrums");
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
			addnav(array("%s &#149; Uncanny Dodge`7 (%s)`0", $ccode, 1), 
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("%s &#149; Rage`7 (%s)`0", $ccode, 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("%s &#149; Damage Reduction`7 (%s)`0", $ccode, 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("%s &#149; Mighty Rage`7 (%s)`0", $ccode, 5),
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
			$str = translate("The Barbarian Specialty is availiable upon reaching %s Dragon Kills and %s points.");
			$str = sprintf($str, get_module_setting("mindk"),$cost);
		}
		output($format, $str, true);
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			$session['user']['donationspent'] = $session['user']['donationspent'] + $cost;
			output("`6When you were a small child, all you wanted in the universe was a pony for your birthday. ");
			output("It seemed reasonable enough, as horse riding is a valuable skill, and horses make great ");
			output("companions.  Your parents weren't rich, but they went without food for a month, and bought ");
			output("your ungrateful self a pony for your birthday none the less.  Upon seeing you, the pony ran ");
			output("off in horror--they can sense the maladjusted.  As the pony ran near the edge of the forest, ");
			output("the Dragon swept down and ate your precious pony for breakfast.  You never even got to call ");
			output("your pony by it's new name, Mr. Choo-Choo-Horse.`n`n");
			output("`^The Rage from the tragic childhood trauma fuels your Barbarian Powers today.`n`n");
		}
		break;

	case "specialtycolor":
		$afis[$spec] = $ccode;
		break;

	case "specialtymodules":
		$afis[$spec] = "specialtybarbarian";
		break;

	case "specialtynames":
		$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
		if ($session['user']['superuser'] & SU_EDIT_USERS || $session['user']['dragonkills'] >= get_module_setting("mindk") || get_module_setting("cost") <= $pointsavailable){
			$afis[$spec] = translate_inline($name);
		}
		break;

	case "training-victory":
		if($session['user']['specialty'] == $spec) {
			$session['user']['maxhitpoints']++;
			output("`2For being skilled in %s%s`2, you are extra hardy and receive an extra hit point!`n`n",$ccode,$name);
		}
	}
	return $afis;
}

function specialtybarbarian_run(){
}
?>