<?php
//addnews ready
// mail ready
// translator ready

/******************************************
This class is meant to do just what it says. There's not a whole lot that's special about it,
except that you have the ability to allow the players to summon a particular hero (such as the
head admin of the site) for the "Summon Hero" ability. Right now, I don't have that function
built in, because I'm not sure yet how it's done. But, hopefully I'll have it figured out before
long.
******************************************/

function specialtysummoner_getmoduleinfo(){
    $info = array(
        "name" => "Specialty - Summoner",
        "author" => "`6Admin Lexington",
        "version" => "1.0",
        "download" => "By Request",
        "category" => "Specialties",
        "settings"=> array(
            "Specialty - Summoner Settings,title",
            "mindk"=>"How many DKs do you need before the specialty is available?,int|0",
            "cost"=>"How many points do you need before the specialty is available?,int|0",
        ),
        "prefs" => array(
            "Specialty - Summoner User Prefs,title",
            "skill"=>"Skill points in Summoner,int|0",
            "uses"=>"Uses of Summoner allowed,int|0",
        ),
    );
    return $info;
}

function specialtysummoner_install(){
    $sql = "DESCRIBE " . db_prefix("accounts");
    $result = db_query($sql);
    $specialty="SU";
    while($row = db_fetch_assoc($result)) {
        // Convert the user over
        if ($row['Field'] == "summoner") {
            debug("Migrating summoner field");
            $sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtysummoner', 'skill', acctid, summoner FROM " . db_prefix("accounts");
            db_query($sql);
            debug("Dropping summoner field from accounts table");
            $sql = "ALTER TABLE " . db_prefix("accounts") . " DROP summoner";
            db_query($sql);
        } elseif ($row['Field']=="summoneruses") {
            debug("Migrating summoner uses field");
            $sql = "INSERT INTO " . db_prefix("module_userprefs") . " (modulename,setting,userid,value) SELECT 'specialtysummoner', 'uses', acctid, summoneruses FROM " . db_prefix("accounts");
            db_query($sql);
            debug("Dropping summoneruses field from accounts table");
            $sql = "ALTER TABLE " . db_prefix("accounts") . " DROP summoneruses";
            db_query($sql);
        }
    }
    debug("Migrating Summoner Specialty");
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
    return true;
}

function specialtysummoner_uninstall(){
    // Reset the specialty of anyone who had this specialty so they get to
    // rechoose at new day
    $sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='SU'";
    db_query($sql);
    return true;
}

function specialtysummoner_dohook($hookname,$args){
    global $session,$resline;

    $spec = "SU";
    $name = "Summoner";
    $ccode = "`!";

    switch ($hookname) {
    case "dragonkill":
        set_module_pref("uses", 0);
        set_module_pref("skill", 0);
        break;
    case "choose-specialty":
        if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
            $pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
            if ($session['user']['dragonkills'] < get_module_setting("mindk") || get_module_setting("cost") > $pointsavailable) break;
            addnav("$ccode$name`0","newday.php?setspecialty=$spec$resline");
            $t1 = translate_inline("Summoning of great beasts.");
            $t2 = appoencode(translate_inline("$ccode$name`0"));
            rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
            addnav("","newday.php?setspecialty=$spec$resline");
        }
        break;
    case "set-specialty":
        if($session['user']['specialty'] == $spec) {
            page_header($name);
            output("`5From early in your childhood, you found that you were often closely related to animals.");
            output("You found that whenever you called the sheep in your flock to come to you, they did just that. Or when you called a dog, or other stray animal, they too would come.");
            output("As you grew older, you began to experiment on things other than animals. Things like spirits and demons, or sometimes even people.");
        }
        break;
    case "specialtycolor":
        $args[$spec] = $ccode;
        break;
    case "specialtynames":
        $args[$spec] = translate_inline($name);
        break;
    case "specialtymodules":
        $args[$spec] = "specialtysummoner";
        break;
    case "incrementspecialty":
        if($session['user']['specialty'] == $spec) {
            $new = get_module_pref("skill") + 1;
            set_module_pref("skill", $new);
            $c = $args['color'];
            $name = translate_inline($name);
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
                output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode, $name, $ccode, $name);
            } else {
                output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode, $name,$bonus, $ccode,$name);
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
            addnav(array("$ccode &#149; Summon Animals`7 (%s)`0", 1),
                    $script."op=fight&skill=$spec&l=1", true);
        }
        if ($uses > 1) {
            addnav(array("$ccode &#149; Summon Spirits`7 (%s)`0", 2),
                    $script."op=fight&skill=$spec&l=2",true);
        }
        if ($uses > 2) {
            addnav(array("$ccode &#149; Summon Demons`7 (%s)`0", 3),
                    $script."op=fight&skill=$spec&l=3",true);
        }
        if ($uses > 4) {
            addnav(array("$ccode &#149; Summon Heroes`7 (%s)`0", 5),
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
                    apply_buff('su1',array(
                        "startmsg"=>"`!You summon forth the beasts of the wide world to attack the {badguy}.",
                        "name"=>"`!Summon Animals",
                        "rounds"=>round($session['user']['level']+3),
                        "wearoff"=>"Your animals let out a great sound of triumph before vanishing into nothingness.",
                        "minioncount"=>round($session['user']['level']/3)+1,
                        "maxbadguydamage"=>round($session['user']['level']/3,0)+1,
                        "effectmsg"=>"`2The animals hit the {badguy}`2 for `^{damage}`2 damage.",
                        "effectnodmgmsg"=>"`2The animals try to hit the {badguy}`2 but `\$MISS`2!",
                        "schema"=>"specialtysummoner"
                    ));
                    break;
                case 2:
                    apply_buff('su2',array(
                        "startmsg"=>"`!You summon forth the wandering spirits of the world to attack the {badguy}.",
                        "name"=>"`!Summon Spirits",
                        "rounds"=>round($session['user']['level']+7),
                        "wearoff"=>"`)The spirits moan and wail before returning to the Void.",
                        "minioncount"=>round($session['user']['level']/3,0)+1,
                        "maxbadguydamage"=>round($session['user']['level']/3,0)+1,
                        "effectmsg"=>"`)The spirits hit the {badguy}`) for `^{damage}`) damage.",
                        "effectnodmgmsg"=>"`)The spirits try to hit the {badguy}`) but `\$MISS`)!",
                        "schema"=>"specialtysummoner"
                    ));
                    break;
                case 3:
                    apply_buff('su3',array(
                        "startmsg"=>"`!You call forth the most vile of your servants, the `\$Demons.",
                        "name"=>"`!Summon Demons",
                        "rounds"=>round($session['user']['level']*1.5),
                        "wearoff"=>"`\$Your demonic servants cry out with blood-curdling screams as they return to the underworld.",
                        "minioncount"=>round($session['user']['level'])+1,
                        "maxbadguydamage"=>round($session['user']['level']/2,0),
                        "effectmsg"=>"`\$The demons hit the {badguy}`\$ for `^{damage}`\$ damage.",
                        "effectnodmgmsg"=>"`\$The demons try to hit the {badguy}`\$ but `^MISS`\$!",
                        "schema"=>"specialtysummoner"
                    ));
                    break;
                case 5:
                    apply_buff('su5',array(
                        "startmsg"=>"`!You call forth the most powerful of your servents, the `6Heroes of Legend`!.",
                        "name"=>"`!Summon Heroes",
                        "rounds"=>round($session['user']['level']*2),
                        "wearoff"=>"`)The heroes return to the `6Halls of Legends`), with no sound at all.",
                        "minioncount"=>round($session['user']['level'])+1,
                        "maxbadguydamage"=>round($session['user']['level'])+1,
                        "effectmsg"=>"`!The heroes hit the {badguy}`! for `^{damage}`! damage.",
                        "effectnodmgmsg"=>"`!The Heroes try to hit the {badguy}`! but `\$MISS`!!",
                        "schema"=>"specialtysummoner"
                    ));
                    break;
                }
                set_module_pref("uses", get_module_pref("uses") - $l);
            }else{
                apply_buff('su0', array(
                    "startmsg"=>"`#Exhausted, you try your most powerful summon, an admin. The admins look at you for a minute, wondering who you think you are, and finally get the joke.  Laughing, they send the {badguy} charging towards you again.",
                    "rounds"=>1,
                    "schema"=>"specialtysummoner"
                ));
            }
        }
        break;
    }
    return $args;
}

function specialtysummoner_run(){
}
?>