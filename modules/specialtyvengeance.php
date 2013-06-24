<?php

function specialtyvengeance_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Vengeance",
		"author" => "Alan Thomson",
		"version" => "1.0",
		"category" => "Specialties",
		"download"=>"http://dragonprime.net/users/Atom/specialtyvengeance.txt",
		"vertxtloc"=>"http://dragonprime.net/users/Atom/",
		"settings"=> array(
			"Specialty - Vengeance Settings,title",
			"mindk"=>"How many DKs do you need before the specialty is available?,int|0",
			"cost"=>"How many points do you need before the specialty is available?,int|0",
      ),
      "prefs" => array(
			"Specialty - Vengeance User Prefs,title",
			"skill"=>"Skill points in Vengeance,int|0",
			"uses"=>"Uses of Vengeance allowed,int|0",
		),
	);
	return $info;
}

function specialtyvengeance_install(){
	module_addhook("choose-specialty");
	module_addhook("set-specialty");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addhook("incrementspecialty");
	module_addhook("specialtynames");
	module_addhook("specialtycolor");
	module_addhook("specialtymodules");
	module_addhook("dragonkill");
	module_addhook("pointsdesc");
	return true;
}

function specialtyvengeance_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='AR' OR specialty='VN'";
	db_query($sql);
	return true;
}

function specialtyvengeance_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");
	
	$spec = "VN";
	$name = "Vengeance";
	$ccode = "`7";
	
	switch ($hookname) {
    
	case "pointsdesc":
		$args['count']++;
		$format = $args['format'];
		$str = translate("The Vengeance Specialty is availiable upon reaching %s Dragon Kills and %s points.");
		$str = sprintf($str, get_module_setting("mindk"),
		get_module_setting("cost"));
		output($format, $str, true);
		break;
	

	case "dragonkill":
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;

	case "choose-specialty":
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
			if ($session['user']['dragonkills'] < get_module_setting("mindk") || get_module_setting("cost") > $pointsavailable) break;
			addnav("$ccode$name`0","newday.php?setspecialty=$spec$resline");
			$t1 = translate_inline("Once again your soul is overwhelmed with thoughts of gaining your revenge.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
		
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			$session['user']['donationspent'] = $session['user']['donationspent'] - $cost;
			output("`7As a child you lived a life of peace and tranquility in a small forest village. ");
			output("Your large family and loving parents made every day a joy. ");
			output("Then your world was torn apart when the `@Green Dragon`7 destroyed your village leaving you the only survivor.");
			output("You have spent the intervening years learning the arcane powers of the forest towards one goal ... ");
			output("Avenging your family and killing the `@Green Dragon`7!. ");
		}
		break;
		
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
		
	case "specialtynames":
		$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
		if ($session['user']['superuser'] & SU_EDIT_USERS || $session['user']['dragonkills'] >= get_module_setting("mindk") || get_module_setting("cost") <= $pointsavailable){
		$args[$spec] = translate_inline($name);
		}
		break;

	case "specialtymodules":
	    $args[$spec] = "specialtyvengeance";
		break;
		
	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$c = $args['color'];
			output("`n%sYou gain a level in `&%s%s to `#%s%s!", $c, $name, $c, $new, $c);
			$x = $new % 3;
			
			if ($x == 0) {
				output("`n`^You gain an extra use point!`n");
				set_module_pref("uses", get_module_pref("uses") + 1);
			}
			
			else {
				if (3-$x == 1) {
					output("`n`^Only 1 more skill level until you gain an extra use point!`n");
				}
				
				else {
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
				output("`n`3For being interested in %s%s`3, you gain `^1`3 extra use of `&%s%s`3 for today.`n",$ccode,$name,$ccode,$name);
			}
			
			else {
				output("`n`3For being interested in %s%s`3, you gain `^%s`3 extra uses of `&%s%s`3 for today.`n",$ccode,$name,$bonus,$ccode,$name);
			}
		}
		
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt++;
		set_module_pref("uses", $amt);
		break;
	
	case "fightnav-specialties":
		$uses = get_module_pref("uses");
		$script = $args['script'];
		
		if ($uses > 0) {
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode &#149; Splinter`7 (%s)`0", 1), 
			$script."op=fight&skill=$spec&l=1", true);
		}
		
		if ($uses > 1) {
			addnav(array("$ccode &#149; Vitality`7 (%s)`0", 2),
			$script."op=fight&skill=$spec&l=2",true);
		}
		
		if ($uses > 2) {
			addnav(array("$ccode &#149; Hatred`7 (%s)`0", 3),
			$script."op=fight&skill=$spec&l=3",true);
		}
		
		if ($uses > 4) {
			addnav(array("$ccode &#149; Family Spirit`7 (%s)`0", 5), $script."op=fight&skill=$spec&l=5",true);
		}
		break;
		
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
					case 1:
						apply_buff('vn1',
							array(
								"startmsg"=>"`7Your cantrip causes a nearby log to explode!",
								"name"=>"`7Splinter",
								"rounds"=>5,
								"wearoff"=>"`7The flurry of splinters clears ... ",
								"minioncount"=>round(get_module_pref("skill")/3),
								"minbadguydamage"=>1,
								"maxbadguydamage"=>2,
								"effectmsg"=>"`7A splinter hits {badguy}`7 for `^{damage}`7 damage.",
								"effectnodmgmsg"=>"{badguy}`7dodges a splinter!",
								"schema"=>"specialtyvengeance"
							)
						);
						break;
						
					case 2:
						apply_buff('vn2',
							array(
								"startmsg"=>"`7You call upon the power of the forest to heal you.",
								"name"=>"`7Vitality",
								"rounds"=>5,
								"wearoff"=>"`7You have drained the forest in this area",
								"regen"=>$session['user']['level'],
								"effectmsg"=>"`7You gain {damage} health; nearby a small patch of flowers die.",
								"effectnodmgmsg"=>"`7You have no wounds to heal.",
								"schema"=>"specialtyvengeance"
							)
						);
						break;
						
					case 3:
						apply_buff('vn3'
							,array(
								"startmsg"=>"`7Thinking of the `@Green Dragon`7 spurs you into greater violence!",
								"name"=>"`7Hatred",
								"rounds"=>5,
								"wearoff"=>"You are emotionally exhausted.",
								"atkmod"=>(e_rand(2,3,4)/2),
								"defmod"=>(e_rand(2,3,4)/2),
								"roundmsg"=>"`7You strike {badguy}, imagining that it is the `@Dragon`7!", 
								"schema"=>"specialtyvengeance"
							)
						);
					break;
						
					case 5:
						apply_buff('vn5'
							,array(
								"startmsg"=>"`7You call forth the spirit of your family to imprison {badguy}!",
								"name"=>"`7Family Spirit",
								"rounds"=>5,
								"wearoff"=>"{badguy} has escaped the ghostly imprisonment ...",
								"badguyatkmod"=>0,
								"badguydefmod"=>0,
								"roundmsg"=>"`7Your Family's Spirit is holding {badguy} and he cannot attack or defend!",
								"schema"=>"specialtyvengeance"
							)
						);
					break;
				}
				
				set_module_pref("uses", get_module_pref("uses") - $l);
			}
			
			else {
				apply_buff('ar0',
					array(
						"startmsg"=>"`7You are unable to sustain your vengeful hatred at it's usual strength!",
						"rounds"=>1,
						"schema"=>"specialtyvengeance"
					)
				);
			}
		}
		break;
	}
	return $args;
}

function specialtyvengeance_run() {
}
?>