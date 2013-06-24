<?php

//Logd 0.9.8 clan hall Clan Weapon and Armor add-on
//Created by Damien(JV)
//Version 1.3 for rel6 or later
//Almost completely based on idea from Dasher's guilds-clans mod
//Installation:
//Put this file into folder modules and
//and install using module manager in super-user grotto
//WARNING: USE THIS MODULE AT YOUR OWN RISK
//Translation ready?

function clanwa_getmoduleinfo(){
        $info = array(
                "name"=>"Clan Weapon and Armor",
                "author"=>"Damien",
                "version"=>"1.3",
                "category"=>"Clan add-on",
                "download"=>"http://dragonprime.net/users/Damien",
                "settings"=>array(
                        "Clan Weapon and Armor settings,title",
                        "costperlevel"=>"Upgrade price = level * ,int|250",
                        "allowinpvp"=>"Allow upgrades in PvP, bool|1",
                        "allowintrain"=>"Allow upgrades in Training, bool|1",
                        "powergain"=>"Power gain = attack/defense * , float|0.15",
                ),
                "prefs"=>array(
                        "Clan Weapon and Armor user preferences,title",
                        "weaponupgraded"=>"Weapon Upgrade purchased,bool|0",
                        "armorupgraded"=>"Armor Upgrade purchased,bool|0",
                        "weapon"=>"Weapon name,text|default",
                        "armor"=>"Armor name,text|default",
                ),
        );
        return $info;
}

function clanwa_install(){
        module_addhook("footer-clan");
        module_addhook("newday");
        module_addhook("footer-weapons");
        module_addhook("footer-armor");
        module_addhook("dragonkill");
        return true;
}

function clanwa_uninstall(){
        return true;
}

function clanwa_dohook($hookname,$args){
        global $session;
        switch($hookname){
        case "footer-clan":
             if($session['user']['clanid']!=0 and httpget("op")==""){
                if($session['user']['clanrank']>0){
                   addnav("Clan Options");
                   addnav("Clan Weapon and Armor","runmodule.php?module=clanwa&op=enter");
                }
             }
        break;
        case "newday":
             if($session['user']['clanid']!=0){
                if(get_module_pref("weaponupgraded")==1){
                   $upgrade = 1+get_module_setting("powergain");
                   apply_buff("clanhardenedweapon",array(
                              "name"=>"`^Clan Hardened Weapon`0",
                               "atkmod"=>$upgrade,
                              "allowinpvp"=>get_module_setting("allowinpvp"),
                              "allowintrain"=>get_module_setting("allowintrain"),
                              "rounds"=>60,
                              "roundmsg"=>"the Clan hardened weapon makes em cry!",
                              "schema"=>"module-clanwa",
                             )
                   );
                   output("`n`^Your clan hardened weapon is ready to slice and dice!`n");
                }

                if(get_module_pref("armorupgraded")==1){
                   $upgrade = 1+get_module_setting("powergain");
                   apply_buff("clanhardenedarmor",array(
                              "name"=>"`^Clan Hardened Armor`0",
                               "defmod"=>$upgrade,
                              "allowinpvp"=>get_module_setting("allowinpvp"),
                              "allowintrain"=>get_module_setting("allowintrain"),
                              "rounds"=>60,
                              "roundmsg"=>"the Clan hardened armor prevents you to cry!",
                              "schema"=>"module-clanwa",
                             )
                   );
                   output("`n`^It is a good day to fight safely wearing your clan hardened armor`n");
                }

             }
        break;
        
        case "footer-weapons":
             if(get_module_pref("weaponupgraded")==1){
             if($session['user']['weapon']!=get_module_pref("weapon")){
                set_module_pref("weaponupgraded",0);
                set_module_pref("weapon","default");
                strip_buff("clanhardenedweapon");
                output("`n`n`@You have to buy a new upgrade for your new weapon`n");
             }
             }
        break;
        
        case "footer-armor":
             if(get_module_pref("armorupgraded")==1){
             if($session['user']['armor']!=get_module_pref("armor")){
                set_module_pref("armorupgraded",0);
                set_module_pref("armor","default");
                strip_buff("clanhardenedarmor");
                output("`n`n`@You have to buy a new upgrade for your new armor`n");
             }
             }
        break;
        
        case "dragonkill":
             set_module_pref("weapon","default");
             set_module_pref("armor","default");
             set_module_pref("weaponupgraded",0);
             set_module_pref("armorupgraded",0);
        break;

        }
        
        return $args;
}

function clanwa_run(){
        global $session;
        
        $upgradecost = get_module_setting("costperlevel")*$session['user']['level'];
        
        $op = httpget("op");
        if(!isset($_POST['action']))
            $action = httpget("action");

        page_header("Clan Weapon and Armor");
        
        switch($op){

               case "enter":
                    addnav("Upgrade Weapon","runmodule.php?module=clanwa&op=weaponupgrade&action=verify");
                    addnav("Upgrade Armor","runmodule.php?module=clanwa&op=armorupgrade&action=verify");
                    addnav("~");
                    output("Clan blacksmith is working to create stronger and more powerful equipment for you and your clan mates.`n");
                    output("Check out what she has to offer at the moment.`n");
               break;
               
               case "weaponupgrade":
                    addnav("Return to Weapon and Armor","runmodule.php?module=clanwa&op=enter");
                    switch($action){

                           case "verify": //Lets ask before updating
                                output("Your clan provides an upgrade to your weapon which gives it more power and strength.`n");
                                output("Price of the upgrade is `&%s `^gold.`n`n",$upgradecost);
                                rawoutput("<form action='runmodule.php?module=clanwa&op=weaponupgrade&action=upgrade' method='POST'>");
                                addnav("","runmodule.php?module=clanwa&op=weaponupgrade&action=upgrade");
                                $text = translate_inline("Upgrade Weapon");
                                rawoutput("<input type='submit' class='button' value='$text'></form>");
                           break;

                           case "upgrade": //Lets do the upgrade
                                   if($session['user']['gold']>=$upgradecost){

                                      if(get_module_pref("weaponupgraded")==0){
                                         $session['user']['gold']-=$upgradecost;

                                         set_module_pref("weaponupgraded",1);
                                         $upgrade = 1+get_module_setting("powergain");

                                         if($session['user']['weapon']!=get_module_pref("weapon")){
                                            apply_buff("clanhardenedweapon",array(
                                                       "name"=>"`^Clan Hardened Weapon`0",
                                                        "atkmod"=>$upgrade,
                                                       "allowinpvp"=>get_module_setting("allowinpvp"),
                                                         "allowintrain"=>get_module_setting("allowintrain"),
                                                       "rounds"=>60,
                                                       "roundmsg"=>"the Clan hardened weapon makes em cry!",
                                                       "schema"=>"module-clanwa",
                                                       )
                                            );
                                         }
                                      set_module_pref("weapon",$session['user']['weapon']);
                                      output("You can feel the increased power of you weapon given by the upgrade. Maybe you should test your weapon on someone...");
                                    }
                                    else
                                        output("You already have upgraded weapon!");
                                    }
                                    else{
                                      output("You don't have enough gold to purchase the upgrade!");
                                    }
                           break;
                    }
               break;
               
               case "armorupgrade":
                    addnav("Return to Weapon and Armor","runmodule.php?module=clanwa&op=enter");
                    switch($action){
                           
                           case "verify":
                                output("Your clan provides an upgrade to your armor which gives it more power and strength.`n");
                                output("Price of the upgrade is `&%s `^gold.`n`n",$upgradecost);
                                rawoutput("<form action='runmodule.php?module=clanwa&op=armorupgrade&action=upgrade' method='POST'>");
                                addnav("","runmodule.php?module=clanwa&op=armorupgrade&action=upgrade");
                                $text = translate_inline("Upgrade Armor");
                                rawoutput("<input type='submit' class='button' value='$text'></form>");
                           break;

                           case "upgrade":
                                   if($session['user']['gold']>=$upgradecost){
                                      if(get_module_pref("armorupgraded")==0){
                                         $session['user']['gold']-=$upgradecost;
                                   
                                         set_module_pref("armorupgraded",1);
                                         $upgrade = 1+get_module_setting("powergain");

                                         if($session['user']['armor']!=get_module_pref("armor")){
                                            apply_buff("clanhardenedarmor",array(
                                                       "name"=>"`^Clan Hardened Armor`0",
                                                        "defmod"=>$upgrade,
                                                       "allowinpvp"=>get_module_setting("allowinpvp"),
                                                       "allowintrain"=>get_module_setting("allowintrain"),
                                                       "rounds"=>60,
                                                       "roundmsg"=>"the Clan hardened armor prevents you to cry!",
                                                       "schema"=>"module-clanwa",
                                                       )
                                            );
                                         }

                                      set_module_pref("armor",$session['user']['armor']);
                                      output("You can feel the increased hardness and strenght of you armor given by the upgrade. Testing your armor in battles could be right thing to do now...");
                                    }
                                    else
                                        output("You already have upgraded armor!");
                                   }
                                    else{
                                      output("You don't have enough gold to purchase the upgrade!");
                                    }
                            break;
                    }
               break;
               
               
               default:
                       output("Shouldn't be here!");

        }

        addnav("Return to your clan hall","clan.php");
        villagenav();
        page_footer();
}
?>
