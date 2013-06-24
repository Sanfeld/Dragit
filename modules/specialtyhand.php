<?php

function specialtyhand_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Hand Techniques",
		"author" => "Chris Vorndran",
		"version" => "1.0",
		"category" => "Specialties",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=44",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Specialty that allows one to take other's out with Assassin-like skill.",
		"settings"=> array(
			"Specialty - Hand Techniques Settings,title",
			"mindk"=>"How many DKs do you need before the specialty is available?,int|25",
			"cost"=>"How many points do you need before the specialty is available?,int|0",
      ),
      "prefs" => array(
			"Specialty - Hand Techniques User Prefs,title",
			"skill"=>"Skill points in Hand Techniques,int|0",
			"uses"=>"Uses of Hand Techniques allowed,int|0",
		),
	);
	return $info;
}

function specialtyhand_install(){
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

function specialtyhand_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='HT'";
	db_query($sql);
	return true;
}

function specialtyhand_dohook($hookname,$args){
	global $session,$resline;
	
	$spec = "HT";
	$name = translate_inline("Hand Techniques");
	$ccode = "`)";
	$cost = get_module_setting("cost");
	
	switch ($hookname){
		case "pointsdesc":
			$args['count']++;
			$format = $args['format'];
			$str = translate("The Hand Techniques are availiable upon reaching %s Dragon Kills and %s points.");
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
				addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
				$t1 = translate_inline("The stealth of the wind has allowed the masters of this technique to destroy others swiftly.");
				$t2 = appoencode(translate_inline("$ccode$name`0"));
				rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
				addnav("","newday.php?setspecialty=$spec$resline");
			}
			break;
		case "set-specialty":
			if($session['user']['specialty'] == $spec) {
				page_header($name);
				$session['user']['donationspent'] = $session['user']['donationspent'] - $cost;
				output("`)Your hand has just become your most deadly weapon.");
				output("The skills that have been imbued into you shall aid you in your quest to destroy all living things.");
				output("Feeling no remorse, as all assassins do, you are able to wipe out entire fields of wildlife.");
				output("Go forth warrior, and fulfill your destiny.");
			}
			break;
		case "specialtycolor":
			$args[$spec] = $ccode;
			break;
		case "specialtynames":
			$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
			if ($session['user']['superuser'] & SU_EDIT_USERS 
				|| $session['user']['dragonkills'] >= get_module_setting("mindk") 
				|| get_module_setting("cost") <= $pointsavailable) 
					$args[$spec] = $name;
			break;
		case "specialtymodules":
			$args[$spec] = "specialtyhand";
			break;
		case "incrementspecialty":
			if($session['user']['specialty'] == $spec) {
				$new = get_module_pref("skill") + 1;
				set_module_pref("skill", $new);
				$c = $args['color'];
				output("`n%sYou gain a level in `&%s%s to `#%s%s!", $c, $name, $c, $new, $c);
				$x = $new % 3;
				
				if ($x == 0){
					output("`n`^You gain an extra use point!`n");
					set_module_pref("uses", get_module_pref("uses") + 1);
				}else{
					if (3-$x == 1) {
						output("`n`^Only 1 more skill level until you gain an extra use point!`n");
					}else{
						output("`n`^Only %s more skill levels until you gain an extra use point!`n", (3-$x));
					}
				}
				output_notl("`0");
			}
			break;
		case "newday":
			$bonus = getsetting("specialtybonus", 1);
			if($session['user']['specialty'] == $spec) {
				if ($bonus){
					output("`n`3For being interested in %s%s`3, you gain `^1`3 extra use of `&%s%s`3 for today.`n",
						$ccode,$name,$ccode,$name);
				}else{
					output("`n`3For being interested in %s%s`3, you gain `^%s`3 extra uses of `&%s%s`3 for today.`n",
						$ccode,$name,$bonus,$ccode,$name);
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
				addnav(array("%s%s (%s points)`0",$ccode,$name,$uses), "");
				addnav(array("%s &#149; %s`7 (%s)`0", $ccode, translate_inline("Shadowbind"), 1), 
				$script."op=fight&skill=$spec&l=1", true);
			}		
			if ($uses > 1) {
				addnav(array("%s &#149; %s`7 (%s)`0", $ccode, translate_inline("Rock Seal"), 2),
				$script."op=fight&skill=$spec&l=2",true);
			}		
			if ($uses > 2) {
				addnav(array("%s &#149; %s`7 (%s)`0", $ccode, translate_inline("Aphonia"), 3),
				$script."op=fight&skill=$spec&l=3",true);
			}		
			if ($uses > 4) {
				addnav(array("%s &#149; %s`7 (%s)`0", $ccode, translate_inline("Last Breath"), 5), 
				$script."op=fight&skill=$spec&l=5",true);
			}
			break;
		case "apply-specialties":
			$skill = httpget('skill');
			$l = httpget('l');
			
			if ($skill == $spec){
				if (get_module_pref("uses") >= $l){
					switch($l){
						case 1:
							apply_buff('ht1',
								array(
									"startmsg"=>"`)With a flick of your wrist, a rope springs from your hand, tying `\${badguy}'s`) shadow to the ground.",
									"name"=>"`)Shadow Bind",
									"rounds"=>5,
									"wearoff"=>"`)The rope disappears, letting `\${badguy}`) move once more.",
									"badguydefmod"=>.7,
									"badguyatkmod"=>.7,
									"roundmsg"=>"`\${badguy} `)struggles against the rope, focusing less of it's energy on you.",
									"schema"=>"module-specialtyhand"
								)
							);
							break;
						case 2:
							apply_buff('ht2',
								array(
									"startmsg"=>"`)`)You strike forth, your hand finding its way to `\${badguy}'s`) throat, drawing it's power to you.",
									"name"=>"`)Aphonia",
									"rounds"=>5,
									"wearoff"=>"`\${badguy}`) gets its strength back and fights back.",
									"atkmod"=>1.3,
									"defmod"=>1.3,
									"badguydefmod"=>.7,
									"badguyatkmod"=>.7,
									"roundmsg"=>"`)Not at full power, `\${badguy}`) is unable to focus on the battle.",
									"schema"=>"module-specialtyhand"
								)
							);
							break;
						case 3:
							apply_buff('ht3',
								array(
									"startmsg"=>"`)Your hand strikes forth and hits `\${badguy}'s`) head, turning it to stone!",
									"name"=>"`)Rock Seal",
									"rounds"=>5,
									"wearoff"=>"`)The rock begins to crack and `\${badguy}`) leaps from the rubble.",
									"badguydefmod"=>.2,
									"badguyatkmod"=>.2,
									"roundmsg"=>"`)Unable to move, `\${badguy}`) is left defenseless!", 
									"schema"=>"module-specialtyhand"
								)
							);
							break;
						case 5:
							apply_buff('ht5',
								array(
									"startmsg"=>"`)Your hand is placed over `\${badguy}'s`) heart and steals the life from it.",
									"name"=>"`)Last Breath",
									"rounds"=>5,
									"wearoff"=>"`)`\${badguy}`) pushes you away, disabling your connection to its heart.",
									"lifetap"=>1.2,
									"atkmod"=>1.6,
									"defmod"=>1.6,
									"roundmsg"=>"`)Your strength increases as you sap the life from `\${badguy}`).",
									"schema"=>"module-specialtyhand"
								)
							);
						break;
					}
					set_module_pref("uses", get_module_pref("uses") - $l);
				}else{
					apply_buff('ht0',
						array(
							"startmsg"=>"Your hand coughs and sputters, erupting sparks all over.",
							"rounds"=>1,
							"schema"=>"specialtyhand"
						)
					);
				}
			}
			break;
		}
	return $args;
}
?>