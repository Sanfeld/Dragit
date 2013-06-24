<?php

function specialtydaemon_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Daemonic Powers",
		"author" => "Chris Vorndran",
		"version" => "1.21",
		"category" => "Specialties",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=47",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Specialty that allows a user to summon the power of darkness to their aid.",
		"settings"=> array(
			"Specialty - Daemon Powers Settings,title",
			"mindk"=>"How many DKs do you need before the specialty is available?,int|0",
			"cost"=>"How many points do you need before the specialty is available?,int|0",
			"loss"=>"How much Alignment is lost when specialty is chosen,int|40",
      ),
      "prefs" => array(
			"Specialty - Daemon Powers User Prefs,title",
			"skill"=>"Skill points in Daemon Powers,int|0",
			"uses"=>"Uses of Daemon Powers allowed,int|0",
		),
	);
	return $info;
}

function specialtydaemon_install(){
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

function specialtydaemon_uninstall(){
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='DP'";
	db_query($sql);
	return true;
}

function specialtydaemon_dohook($hookname,$args){
	global $session,$resline;
	
	$spec = "DP";
	$name = translate_inline("Daemonic Powers");
	$ccode = "`)";
	$loss = get_module_setting("loss");
	
	switch ($hookname) {
		case "pointsdesc":
			$args['count']++;
			$format = $args['format'];
			$str = translate("The Daemon Powers Specialty is availiable upon reaching %s Dragon Kills and %s points.");
			$str = sprintf($str, get_module_setting("mindk"),
			get_module_setting("cost"));
			output($format, $str, true);
			break;
		case "newday":
			$bonus = getsetting("specialtybonus", 1);
	
			if($session['user']['specialty'] == $spec) {
				if ($bonus) {
					output("`n`3For being interested in %s%s`3, you gain `^1`3 extra use of `&%s%s`3 for today.`n",$ccode,$name,$ccode,$name);
				}else{
					output("`n`3For being interested in %s%s`3, you gain `^%s`3 extra uses of `&%s%s`3 for today.`n",$ccode,$name,$bonus,$ccode,$name);
				}
			}
			
			$amt = (int)(get_module_pref("skill") / 3);
			if ($session['user']['specialty'] == $spec) $amt++;
			set_module_pref("uses", $amt);
			if (is_module_active('alignment') && $session['user']['specialty'] == 'DP') {
					output("`nYour Daemonic Tendencies have lowered your alignment.`n");
					require_once("./modules/alignment/func.php");
					align("-1");
			}
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
				$t1 = translate_inline("From the depths of the Netherworld, Daemons have ruled supreme.");
				$t2 = appoencode(translate_inline("$ccode$name`0"));
				rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
				addnav("","newday.php?setspecialty=$spec$resline");
			}
			break;
		case "set-specialty":
			if($session['user']['specialty'] == $spec) {
				page_header($name);
				$session['user']['donationspent'] = $session['user']['donationspent'] - $cost;
				output("`)The Kings of old have passed down the tales of the Daemon.");
				output(" You accepted them as fables, until the one day came, and you learned that you were a Daemon.");
				output(" You could feel the newfound power coursing in your veins.");
				output(" The ability to manipulate darkness, to aide you in battle.");
				output(" Little did you know, that you were destined for so much more...");
				if (is_module_active('alignment')) {
				set_module_pref('alignment',get_module_pref('alignment','alignment') - $loss,'alignment');
			}
			}
			break;
		case "specialtycolor":
			$args[$spec] = $ccode;
			break;
		case "specialtynames":
			$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
			if ($session['user']['superuser'] & SU_EDIT_USERS || $session['user']['dragonkills'] >= get_module_setting("mindk") || get_module_setting("cost") <= $pointsavailable){
			$args[$spec] = $name;
			}
			break;
		case "specialtymodules":
			$args[$spec] = "specialtydaemon";
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
				}else{
					if (3-$x == 1) {
						output("`n`^Only 1 more skill level until you gain an extra use point!`n");
					}else {
						output("`n`^Only %s more skill levels until you gain an extra use point!`n", (3-$x));
					}
				}
				output_notl("`0");
			}
			break;
		case "fightnav-specialties":
			$uses = get_module_pref("uses");
			$script = $args['script'];
			
			if ($uses > 0){
				addnav(array("$ccode$name (%s points)`0", $uses), "");
				addnav(array("%s &#149; %s`7 (%s)`0",$ccode, translate_inline("Bloodlust"), 1), 
				$script."op=fight&skill=$spec&l=1", true);
			}
			if ($uses > 1){
				addnav(array("%s &#149; %s`7 (%s)`0",$ccode, translate_inline("Daemon Summoning"), 2),
				$script."op=fight&skill=$spec&l=2",true);
			}
			if ($uses > 2){
				addnav(array("%s &#149; %s`7 (%s)`0",$ccode, translate_inline("Winged Slayer"), 3),
				$script."op=fight&skill=$spec&l=3",true);
			}
			if ($uses > 4){
				addnav(array("%s &#149; %s`7 (%s)`0",$ccode, translate_inline("Overlord's Wrath"), 5), 
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
							apply_buff('dp1',
								array(
									"startmsg"=>"`)You strike forth, a new rage about you. Your muscles grow at an exponential rate...",
									"name"=>"`)Bloodlust",
									"rounds"=>5,
									"wearoff"=>"The blood dissapates from your eyes, and you see {badguy} clearly.",
									"atkmod"=>1.75,
									"defmod"=>.1,
									"schema"=>"module-specialtydaemon"
								)
							);
							break;
							
						case 2:
							apply_buff('dp2',
								array(
									"startmsg"=>"`)You pop your neck, and slowly summon many minions from the depths of the Netherworld!",
									"name"=>"`)Daemon Summoning",
									"rounds"=>5,
									"wearoff"=>"`)Your minions decide that there is no more `^gold `)that can be earned, so they leave",
									"minioncount"=>round(get_module_pref("skill")/3),
									"minbadguydamage"=>round($session['user']['level']/4),
									"maxbadguydamage"=>round($session['user']['level']/2),
									"effectmsg"=>"`)A Daemon strikes {badguy}`) for `^{damage}`) damage.",
									"effectnodmgmsg"=>"`)When your Daemon lashes out to hit {badguy},`) it`\$MISSES`)!",
									"schema"=>"module-specialtydaemon"
								)
							);
							break;
							
						case 3:
							apply_buff('dp3'
								,array(
									"startmsg"=>"`)You mutter a deep and dark charm, and a gigantic shadow appears overhead!",
									"name"=>"`)Winged Slayer",
									"rounds"=>5,
									"wearoff"=>"The rune fades from your hands, and you feel less powerful.",
									"minioncount"=>1,
									"minbadguydamage"=>round($session['user']['level']/2,0),
									"maxbadguydamage"=>round($session['user']['level']*1.5),
									"effectmsg"=>"`)The Winged Slayer strikes {badguy}`) for `^{damage}`) damage.",
									"effectnodmgmsg"=>"`)When the Winged Slayer lashes out to hit {badguy},`) it`\$MISSES`)!",
									"schema"=>"module-specialtydaemon"
								)
							);
							break;
						case 5:
							apply_buff('dp5'
								,array(
									"startmsg"=>"`)You feel the Royal Blood coursing in your veins, and your body begins to grow!",
									"name"=>"`)Overlord's Wrath",
									"rounds"=>5,
									"wearoff"=>"The Royal Effect slowly dies off... growing dormant for another day.",
									"lifetap"=>1,
									"atkmod"=>1.75,
									"roundmsg"=>"You begin to feel power being returned, as you strike {badguy} blow for blow!",
									"schema"=>"module-specialtydaemon"
								)
							);
						break;
					}
					set_module_pref("uses", get_module_pref("uses") - $l);
				}else {
					apply_buff('dp0',
						array(
							"startmsg"=>"You can not feel the Daemonic Blood in your veins. This may be due to high cholesterol!",
							"rounds"=>1,
							"schema"=>"module-specialtydaemon"
						)
					);
				}
			}
			break;
		}
	return $args;
}
?>