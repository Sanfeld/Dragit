<?php
/*

This is part of a series of specialties I'm making to recreate the core D&D classes in LoGD.
I'm also trying to reach out to, and cater to different styles of players.  So long as the
various specialties are fairly well balanced, it shouldn't matter too much what your specialty
is on paper.  You can't use specialty buffs in PvP or anything.  But, in the player's eyes,
I think the game might be a smidge more enjoyable if they are playing a "class/specialty" that
they enjoy.

Also, by increasing the number of specialties in the game, I'm trying to provide replay value.

Both the number of uses you get per day, as well as the power of the buffs are affected by how
much charm the character has.  It only makes sense.  I felt many of the specialties were fairly
static.  This rewards some of the players who go out of the way to roleplay their character
according to what their race and specialty are.  However, I must warn you, that as I am developing
new races and specialties with dynamic effects based off charm, alignment, etc. that a player
could twink out their character.  Effectively, this is designed for that, in so much as that it
rewards players for certain actions.  To balance this, you have to consider two things.

One, it takes effort and time to raise charm, alignment, etc. so the bonus power is not granted
for nothing.  Secondly, these specialties and races with dynamic effects are usually a little
less powerful to start off with.  Or atleast, the ones I am designing are that way.

For instance, until you hit around 20 charm, you get no bonus to the duration of the buffs.  And
the base is 4 rounds instead of 4.  You also won't really get any bonus use parts early on either.
So, I don't think this is unbalancing.  To start it's weaker, and has the potential of becoming
more powerful if the player plays their cards right, is all.

-- Enderandrew


*/

function specialtybard_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Bard",
		"author" => "`!Enderandrew",
		"version" => "1.11",
		"download" => "http://dragonprime.net/users/enderwiggin/specialitybard.zip",
		"vertxtloc"=>"http://dragonprime.net/users/enderwiggin/",
		"category" => "Specialties",
		"description"=>"This will add a D&D inspired Bard Specialty to the game.",
		"prefs" => array(
			"Specialty - Bard User Prefs,title",
			"skill"=>"Skill points in Bard Songs,int|0",
			"uses"=>"Uses of Bard Songs allowed,int|0",
		),
		"settings"=> array(
			"Specialty - Bard Settings,title",
			"mindk"=>"How many DKs do you need before the specialty is available?,int|5",
			"cost"=>"How many points do you need before the specialty is available?,int|5",
		),
	);
	return $info;
}

function specialtybard_install(){
	$specialty="BA";
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

function specialtybard_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='BA'";
	db_query($sql);
	return true;
}

function specialtybard_dohook($hookname,$afis){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "BA";
	$name = "Bard Songs";
	$ccode = "`5";
	$cost = get_module_setting("cost");
	$op69 = httpget('op69');

	switch ($hookname) {

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				$rounds = (4+(int)($session['user']['charm'] / 20));
				if ($rounds > 9) $rounds=9;
				switch($l){
				case 1:
					apply_buff('ba1', array(
						"startmsg"=>"`5You sing a song of your plight as a daring adventurer!  {badguy} loses motivation!",
						"name"=>"`%Fascinate",
						"rounds"=>$rounds,
						"wearoff"=>"Your voice is getting tired...",
						"badguyatkmod"=>0.5,
						"schema"=>"specialtybard"
					));
					break;
				case 2:
					apply_buff('ba2', array(
						"startmsg"=>"`5{badguy} doesn't want to hurt you.  {badguy} wants to hurt {badguy}!",
						"name"=>"`%Countersong",
						"rounds"=>$rounds,
						"damageshield"=>0.5,
						"wearoff"=>"Your voice is getting tired...",
						"schema"=>"specialtybard"
					));
					break;
				case 3:
					apply_buff('ba3', array(
						"startmsg"=>"`5You get wrapped up in your own greatness by listening to your own song.",
						"name"=>"`%Inspire Greatness",
						"rounds"=>$rounds,
						"effectmsg"=>"`%You thwap {badguy} for {damage} damage.",
						"effectnodmgmsg"=>"`%Despite your immense greatness, of which you're singing about, you MISS!",
						"atkmod"=>1.5,
						"defmod"=>1.5,
						"wearoff"=>"Your voice is getting tired...",
						"schema"=>"specialtybard"
					));
					break;
				case 5:
					apply_buff('ba5', array(
						"startmsg"=>"`5You charm several nearby creatures to come to your aid!",
						"name"=>"`%Mass Suggestion",
						"rounds"=>$rounds,
						"minioncount"=>round($session['user']['level']/3),
						"minbadguydamage"=>1,
						"maxbadguydamage"=>$session['user']['level']*2,
						"effectmsg"=>"`%A charmed creature hits {badguy} for {damage} damage!",
						"effectnodmgmsg"=>"`%Your charmed creature misses {badguy} completely!  It's hard to get good help these days.",
						"wearoff"=>"Your voice is getting tired...",
						"schema"=>"specialtybard"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('ba0', array(
					"startmsg"=>"`%You try to sing, but you lack the proper motivation.  Alas, no one understands an artist.",
					"rounds"=>1,
					"schema"=>"specialtybard"
				));
			}
		}
		break;

	case "castlelib":
		if ($op69 == 'bard'){
			output("You sit down and open up the Minstrel Boy.`n");
			output("You read for a while... in the time it takes you to read you use up`n");
			output("3 Turns.`n`n");
			output("You read a very moving story of a young minstrel boy, and his journey off to war, where no one `n");
			output("understood his artistic visions.  Plus, no one sympathized with how difficult it was to keep pitch `n");
			output("with all the screaming and death rattles going on around him.  It's a very sad, sad story.`n");
			output("`@You become more skilled as a Bard!`n");
			$session['user']['turns']-=3;
			set_module_pref('skill',(get_module_pref('skill','specialtybard') + 1),'specialtybard');
			set_module_pref('uses', get_module_pref("uses",'specialtybard') + 1,'specialtybard');
			addnav("Continue","runmodule.php?module=lonnycastle&op=library");
			}
		break;

	case "castlelibbook":
		output("The Minstrel Boy. (3 Turns)`n");
		addnav("Read a Book");
		addnav("The Minstrel Boy","runmodule.php?module=lonnycastle&op=library&op69=bard");
		break;

	case "choose-specialty":
		if ($session['user']['dragonkills']>=get_module_setting("mindk")) {
			if ($session['user']['specialty'] == "" ||
				$session['user']['specialty'] == '0') {
				addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
				$t1 = translate_inline("Telling the world what a great artist you were");
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
			addnav(array("%s &#149; Fascinate`7 (%s)`0", $ccode, 1), 
					$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("%s &#149; Countersong`7 (%s)`0", $ccode, 2),
					$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("%s &#149; Inspire Greatness`7 (%s)`0", $ccode, 3),
					$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("%s &#149; Mass Suggestion`7 (%s)`0", $ccode, 5),
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
		if($session['user']['specialty'] == $spec) {
			if($session['user']['charm'] > 0) {
				$bonus = getsetting("specialtybonus", 1);
					if ($bonus == 1) {
						output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode,$name,$ccode,$name);
					} else {
						output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode,$name,$bonus,$ccode,$name);
					}
				$amt = (int)(get_module_pref("skill") / 3);
				$charmbonus = (int)($session['user']['charm'] / 10);
				if($charmbonus > 0) {
					output("`n`2For being such a charmer, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode,$name);
					$amt += $charmbonus;
				}
				if ($session['user']['specialty'] == $spec) $amt++;
				set_module_pref("uses", $amt);
			}else{
				$bonus = 0;
				output("`n`2It's pretty hard to seduce people with no charm so, you receive no `&%s%s`2 uses for today.`n",$ccode,$name);
			}
		}
		break;

	case "pointsdesc":
		$cost = get_module_setting("cost");
		if ($cost > 0){
			$afis['count']++;
			$format = $afis['format'];
			$str = translate("The Bard Specialty is availiable upon reaching %s Dragon Kills and %s points.");
			$str = sprintf($str, get_module_setting("mindk"),$cost);
		}
		output($format, $str, true);
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			$session['user']['donationspent'] = $session['user']['donationspent'] + $cost;
			output("`5As a small child, you knew that you were special, and better than everyone else.  You were ");
			output("an artist.  Your parents said you were a jobless bum.  But you felt that hedonism went a ");
			output("long way, and artists are so misunderstood.  Then the Dragon came around and started to ");
			output("kill everyone in sight.  That really killed your buzz and disrupted your artistic vision. ");
			output("The whole reason you became an artist--besides avoiding a real job--was to woo members of ");
			output("the opposite sex.  And if everyone is likely to die anyways, why not pursue a bit of wine, ");
			output("romance and song?  But to compose a really heroic ballad, there needs to be a hero worth you ");
			output("singing about.  It looks like you'll have to do everything yourself...`n`n");
		}
		break;

	case "specialtycolor":
		$afis[$spec] = $ccode;
		break;

	case "specialtymodules":
		$afis[$spec] = "specialtybard";
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

function specialtybard_run(){
}
?>