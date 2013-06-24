<?php
//addnews ready
//mail ready
//translator ready

///////////////////////||\\\\\\\\\\\\\\\\\\\\\\
// .---------------------------------------. \\
// |            Specialty - Geek           | \\
// |---------------------------------------| \\
// | Author: R4000 (Peter Corcoran) (God)  | \\
// |   Date: 03/July/2005 15:15            | \\
// | Module: Specialty Addon               | \\
// |                                       | \\
// |  Notes: None                          | \\
// |                                       | \\
// | Thanks: Thanks Lexington, I used your | \\
// |         White Knight as a template. I | \\
// |         hope you dont mind! I dont    | \\
// |         take any credit for being the | \\
// |         offical owner of this code.   | \\
// |         All i did was some text mods. | \\
// '---------------------------------------' \\
///////////////////////||\\\\\\\\\\\\\\\\\\\\\\


function specialtygeek_getmoduleinfo(){
    $info = array(
        "name" => "Specialty - Geek",
        "author" => "`6God R4000",
        "version" => "1.0",
        "download" => "By Request",
        "category" => "Specialties",
        "settings"=> array(
             "Specialty - Geek Settings,title",
             "alignment"=>"What is the alignment requirement for this specialty?,int|66",
             "mindk"=>"How many DKs do you need before the specialty is available?,int|10",
             "cost"=>"How many points do you need before the specialty is available?,int|0",
             "gain"=>"How much Alignment is gained when specialty is chosen,int|5",
      ),
        "prefs" => array(
            "Specialty - Geek User Prefs,title",
            "skill"=>"Skill points in Geek,int|0",
            "uses"=>"Uses of Geek allowed,int|0",
        ),
    );
    return $info;
}

function specialtygeek_install(){
    $sql = "DESCRIBE " . db_prefix("accounts");
    $result = db_query($sql);
    $specialty="GK";
    while($row = db_fetch_assoc($result)) {
        // Convert the user over
        if ($row['Field'] == "geek") {
            debug("Migrating geek field");
            $sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtygeek', 'skill', acctid, geek FROM " . db_prefix("accounts");
            db_query($sql);
            debug("Dropping whiteknight field from accounts table");
            $sql = "ALTER TABLE " . db_prefix("accounts") . " DROP whiteknight";
            db_query($sql);
        } elseif ($row['Field']=="geekuses") {
            debug("Migrating geek uses field");
            $sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtygeek', 'uses', acctid, geekuses FROM " . db_prefix("accounts");
            db_query($sql);
            debug("Dropping geek field from accounts table");
            $sql = "ALTER TABLE " . db_prefix("accounts") . " DROP geekuses";
            db_query($sql);
        }
    }
    debug("Migrating Geek Specialty");
    $sql = "UPDATE " . db_prefix("accounts") . " SET specialty='$specialty' WHERE specialty='1'";
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
    module_addhook("pointsdesc");
    module_addhook("newday");
    return true;
}

function specialtygeek_uninstall(){
    // Reset the specialty of anyone who had this specialty so they get to
    // rechoose at new day
    $sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='GK'";
    db_query($sql);
    return true;
}

function specialtygeek_dohook($hookname,$args){
    global $session,$resline;

    $spec = "GK";
    $name = "Geek";
    $ccode = "`1G`2ee`1k";
    $cost = get_module_setting("cost");
    $loss = get_module_setting("gain");

    switch ($hookname) {
        case "pointsdesc":
            $args['count']++;
            $format = $args['format'];
            $str = translate("The Geek Specialty is availiable upon reaching %s Dragon Kills and %s points.");
            $str = sprintf($str, get_module_setting("mindk"), $cost);
            output($format, $str, true);
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
        if (is_module_active('alignment') && $session['user']['specialty'] == 'GK') {
                output("`nYour holy calling has raised your alignment.`n");
                align("+2");
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
                $t1 = translate_inline("A small geek who is drooling over the keyboard while killing things for no apparent reason.");
                $t2 = appoencode(translate_inline("$ccode$name`0"));
                rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
                addnav("","newday.php?setspecialty=$spec$resline");
            }
        break;

        case "set-specialty":
            if($session['user']['specialty'] == $spec) {
                page_header($name);
                $session['user']['donationspent'] = $session['user']['donationspent'] - $cost;
                output("`7The geeks are back, In Green Dragon style!.");
                output(" You know that becoming a geek is an easyly acomplised task.");
                output(" Let the battling begin!.");
                if (is_module_active('alignment')) {
                    set_module_pref('alignment',get_module_pref('alignment','alignment') + $gain,'alignment');
                }
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
        $args[$spec] = "specialtygeek";
    break;

    case "incrementspecialty":
        if($session['user']['specialty'] == $spec) {
            $new = get_module_pref("skill") + 1;
            set_module_pref("skill", $new);
            $c = $args['color'];
            //$name = translate_inline($name);
            output("`n%sYou gain a level in `&%s%s to `#%s%s!", $c, $name, $c, $new, $c);
            $x = $new % 3;
            if ($x == 0) {
                output("`n`^You gain an extra use point!`n");
                set_module_pref("uses", get_module_pref("uses") + 1);
            }
            else{
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

    case "fightnav-specialties":
        $uses = get_module_pref("uses");
        $script = $args['script'];
        if ($uses > 0) {
            addnav(array("$ccode$name (%s points)`0", $uses), "");
            addnav(array("$ccode &#149; `6Healing Light`7 (%s)`0", 1),
                $script."op=fight&skill=$spec&l=1", true);
        }
        if ($uses > 1) {
            addnav(array("$ccode &#149; `6Sword of Sunder`7 (%s)`0", 2),
                $script."op=fight&skill=$spec&l=2",true);
        }
        if ($uses > 2) {
            addnav(array("$ccode &#149; `6Retribution`7 (%s)`0", 3),
                $script."op=fight&skill=$spec&l=3",true);
        }
        if ($uses > 4) {
            addnav(array("$ccode &#149; `6Smite`7 (%s)`0", 5),
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
                        apply_buff('Gk1',
                        array(
                            "startmsg"=>"`6You summon the blue screen of death!",
                            "name"=>"`1Blue screen of death",
                            "rounds"=>round($session['user']['level']+5),
                            "wearoff"=>"`)The screen slowly returns to normal.",
                            "regen"=>round($session['user']['level']),
                            "effectmsg"=>"You hide behind the blue screen and recover {damage} health.",
                            "effectnodmgmsg"=>"You have no wounds to heal, but the blue screen still protects you.",
                            "badguyatkmod"=>0.75,
                            "schema"=>"specialtygeek"
                            )
                        );
                    break;
                    case 2:
                        apply_buff('Gk2',array(
                            "startmsg"=>"`You press the reset button on {badguy}'s mobo. ",
                            "name"=>"`^Reboot",
                            "rounds"=>10,
                            "effectmsg"=>"`6You strike {badguy} while it is rebooting (`\${damage}`6 points!)",
                            "wearoff"=>"`6{badguy}'s mobo has reset! Revenge is imminent.",
                            "atkmod"=>3.0,
                            "schema"=>"specialtygeek"
                            )
                        );
                    break;
                case 3:
                    apply_buff('Gk3',
                        array(
                            "startmsg"=>"`You boot up bruteforcey and start to own {badguy}'s laptop.",
                            "name"=>"`^Hack",
                            "rounds"=>10,
                            "wearoff"=>"`6{badguy} fliks off its router and changes its password then comes back for more.",
                            "effectmsg"=>"`6You slowly disable {badguy}'s defence while hitting it `6 for `\${damage}`6 damage.",
                            "badguydmgmod"=>0.25,
                            "badguydefmod"=>0.25,
                            "atkmod"=>2,
                            "defmod"=>2,
                            "roundmsg"=>"`6{badguy} staggers at the sight of you, and deals only one quarter damage.",
                            "schema"=>"specialtygeek"
                        )
                    );
                break;
                case 4:
                    apply_buff('Gk4',
                        array(
                            "startmsg"=>"`You throw your cdrom collection at {badguy}.",
                            "name"=>"`^Disk toss",
                            "rounds"=>10,
                            "wearoff"=>"`6{badguy} catches your disk and throws it back.",
                            "effectmsg"=>"`6You throw another disk, hitting {badguy} `6 for `\${damage}`6 damage.",
                            "badguydmgmod"=>0.25,
                            "badguydefmod"=>0.25,
                            "atkmod"=>2,
                            "defmod"=>2,
                            "roundmsg"=>"`6{badguy} staggers at the sight of you, and deals only one quarter damage.",
                            "schema"=>"specialtygeek"
                        )
                    );
                break;
                case 5:
                    apply_buff('Gk5',
                        array(
                            "startmsg"=>"`6You call up the most xtreme attack method possable and unleash virii on {badguy}.",
                            "name"=>"`^Virii",
                            "rounds"=>10,
                            "wearoff"=>"`)Feeling that {badguy} has suffered enough your command the virii to stop.",
                            "badguyatkmod"=>.1,
                            "badguydefmod"=>.1,
                            "atkmod"=>3,
                            "defmod"=>3,
                            "effectmsg"=>"`6Your `b`^Virus`b`6 hits {badguy}`6 for `\${damage}`6 damage.",
                            "roundmsg"=>"`6{badguy} `6stands motionless, while his mobo slowly burns out.",
                            "schema"=>"specialtygeek"
                            )
                        );
                break;
                }
            set_module_pref("uses", get_module_pref("uses") - $l);
            }
            else{
                apply_buff('Gk0',
                    array(
                        "startmsg"=>"Lacking the strength to continue, you try to raise your sword to attack and defend yourself, but the {badguy} only laughs and lunges forward to attack!",
                        "rounds"=>1,
                        "schema"=>"specialtygeek"
                        )
                );
            }
        }
        break;
    }
    return $args;
}

function specialtygeek_run(){
}
?>