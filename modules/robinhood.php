<?php
//original 0.9.8 Conversion by Frederic Hutow
//add settings to make creatures easier or harder.

//V1.42 Translation Readied the Systemmail
//V1.43 Translation Readied the Men's names and weapons
//V1.44 Added Alignment changes
//V1.45 Corrected alignment loss (wrong position in the code)
//V1.46 Experience win (10% up to 15%) + loss (10%) built in

function robinhood_getmoduleinfo(){

	$info = array(
		"name"=>"Robin Hood and his band of Merry Men",
		"author"=>"`#Lonny Luberts, fixes by SexyCook",
		"version"=>"1.46",
		"category"=>"Forest Specials",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=15",
		"vertxtloc"=>"http://www.pqcomp.com/",
		"prefs"=>array(
			"wonfight" => "Won Last Fight #,range,0,4,1|0"
		)
	);
	return $info;	
}

function robinhood_install(){
	module_addhook("dragonkill");
	module_addeventhook("forest","return 100;");
	return true;
}

function robinhood_uninstall(){
	return true;
}

function robinhood_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "dragonkill":
		set_module_pref("wonfight", 0);
		break;
	}
	return $args;
}

function robinhood_runevent($type){
	global $session;
	$session['user']['specialinc'] = "module:robinhood";

	$op = httpget('op');
	$op2 = httpget('op2');

	$wonfight = get_module_pref('wonfight');

	if ($op == ""){
		if ($wonfight == 4) {
			$session['user']['specialinc'] = "";
			redirect("forest.php?op=search");
		}
		$totalgold = $session['user']['goldinbank'] + $session['user']['gold'];
		if ($session['user']['gold'] <1) $totalgold = 0;
		output("`7While wandering the forest you come across Robin Hood and his Band of Merry Men.`n");
		if ($totalgold > 499) output("`7Robin Hood explains that he intends to take your gold and give it to the poor.`n");
		if ($totalgold < 499) output("`7They wave and continue on.");
		if ($totalgold > 499) output("`4Robin Hood demands you hand over your gold.`n");
		if ($totalgold > 499) output("`7What are you going to do?  Help the poor or try and hold on to your gold.");
		if ($totalgold > 499) addnav("Give them Your Gold","forest.php?op=loose&op2=give");
		if ($totalgold > 499) addnav("Fight Them","forest.php?op=fight" . ($wonfight + 1));
		if ($totalgold < 499) addnav("Continue","forest.php?op=continue");
		//I cannot make you keep this line here but would appreciate it left in.
		output("`n`n");
		rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.pqcomp.com\" target=\"_blank\">Robin Hood by Lonny @ http://www.pqcomp.com</a><br>");
	}

	if ($op == "continue") {
		$session['user']['specialinc'] = "";
		output("`7You try to catch them but they are too fast for you. Maybe one day you will be able to talk to them. Hopefully they will be able to teach you a thing or two.`n");
	}

	if ($op == "loose"){
		if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints'] = 1;
		$loot = $session['user']['gold'];
		$session['user']['gold'] = 0;
		$sql = "SELECT acctid,name,goldinbank,gold,login FROM ".db_prefix("accounts");
		$result = db_query($sql);
		for ($i=0;$i<db_num_rows($result);$i++){
	    $row = db_fetch_assoc($result);
			if ($row['goldinbank'] < 1 and $row['gold'] < 1){
				$num++;
			}
		}
		if ($num == 0){
			for ($i=0;$i<db_num_rows($result);$i++){
		    $row = db_fetch_assoc($result);
				if ($row['goldinbank'] < 1 and $row['gold'] < 10000 and $row['name'] <> $session['user']['name']){
					$num++;
				}
			}	
		}
		$dist = round($loot/$num);
		// if there is no one to give the gold to never worry.... robin hood keeps it. hehe
		$result = db_query($sql);
		for ($i=0;$i<db_num_rows($result);$i++){
	    $row = db_fetch_assoc($result);
			if ($row['goldinbank'] < 1 ){
				$give = $row['goldinbank'] + $dist;
				if ($row['name'] <> $session['user']['name']){
					$sql2 = ("UPDATE ".db_prefix("accounts")." SET goldinbank=$give WHERE login = '{$row['login']}'");
					db_query($sql2);
					$mailmessage = array("%s was robbed by Robin Hood and his merry men.  They took %s gold and gave it to %s people.  You each have recieved %s gold.  The gold has been placed in your bank account.",$session['user']['name'],$loot,$num,$dist);
					require_once("lib/systemmail.php");
					systemmail($row['acctid'],array("`2Robin Hood has given you some gold!`2"),$mailmessage);
				}
			}
		}

		if ($num > 0) addnews("Robin Hood steals %s gold from %s`7 and gives to the poor!",$loot,$session['user']['name']);
		if ($num < 1) addnews("Robin Hood steals %s gold from %s`7 and keeps the loot!",$loot,$session['user']['name']);
		if ($op2 <> "give") output("`4You lost!`n");
		if ($op2 == "give") {
        output("`7You hand over the gold!");
     
        if (is_module_active('alignment')) {
            require_once("modules/alignment/func.php");
            $align=get_module_pref("alignment","alignment")+1;
            set_module_pref("alignment",$align,"alignment");
         		debuglog("increased alignment points by 1 to $align by giving money to robin hood");
            debug("increased alignment points by 1 to $align by giving money to robin hood");
        }
    }
		//take alt currencies
		if (is_module_active('altcurrency')){
			$sql = "SELECT name FROM ".db_prefix("altcurrency");
			$result = db_query($sql);
			for ($i=0;$i<db_num_rows($result);$i++){
				$row = db_fetch_assoc($result);
				$altloot = round(get_module_pref($row['name'],'altcurrency') * .25);
				output("Robin Hook also takes %s %s.`n",$altloot,$row['name']);
				set_module_pref($row['name'],(get_module_pref($row['name'],'altcurrency') - $altloot),'altcurrency');
			}
		}
		if ($op2 <> "give"){
        output("`7Robin Hood and his Merry Men are not all that bad... Although you are beaten up pretty badly, they let you live.`n");
 
        if (is_module_active('alignment')) {
           require_once("modules/alignment/func.php");
            $align=get_module_pref("alignment","alignment")-2;
            set_module_pref("alignment",$align,"alignment");
        		debuglog("decreased alignment points by 2 to $align by refusing robin hood to donate money");
            debug("decreased alignment points by 2 to $align by refusing robin hood to donate money");
        }
    }
 
		output("`3Robin Hood takes your gold to distribute to the poorest in the realm.");
		$session['user']['specialinc'] = "";
		addnav("Continue","forest.php");
		if ($session['user']['hitpoints'] == 1){
			output("Before they leave, Robin Hoods looks back and tosses you a potion.  You drink it up and ");
			switch(e_rand(1,10)){
				case 1:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'] * .1;
				output("it restores 10% of your health.`n");
				break;
				case 2:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'] * .2;
				output("it restores 20% of your health.`n");
				break;
				case 3:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'] * .3;
				output("it restores 30% of your health.`n");
				break;
				case 4:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'] * .4;
				output("it restores 40% of your health.`n");
				break;
				case 5:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'] * .5;
				output("it restores 50% of your health.`n");
				break;
				case 6:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'] * .6;
				output("it restores 60% of your health.`n");
				break;
				case 7:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'] * .7;
				output("it restores 70% of your health.`n");
				break;
				case 8:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'] * .8;
				output("it restores 80% of your health.`n");
				break;
				case 9:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'] * .9;
				output("it restores 90% of your health.`n");
				break;
				case 10:
				$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
				output("it restores all of your health.`n");
				break;
			}
			$session['user']['hitpoints'] = round($session['user']['hitpoints']);
		}
		
	}

	if ($op == "win"){
		output("You have beaten Robin Hood and his band of Merry Men!");
		addnews("Robin Hood and his band of Merry Men were defeated in the Forest by %s`7!",$session['user']['name']);
		output("You decide to move on before they get up.");
		$session['user']['specialinc'] = "";

    if (is_module_active('alignment')) {
        require_once("modules/alignment/func.php");
        $align=get_module_pref("alignment","alignment")-2;
        set_module_pref("alignment",$align,"alignment");
    		debuglog("decreased alignment points by 2 to $align by refusing robin hood to donate money");
        debug("decreased alignment points by 2 to $align by refusing robin hood to donate money");
    }

		addnav("Continue","forest.php");
	}
	
	if ($op == "fight1"){
		$badguy = array(        "creaturename"=>translate_inline("`@Friar Tuck`0")
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>translate_inline("Beer Belly")
                                ,"creatureattack"=>0
                                ,"creaturedefense"=>1
                                ,"creaturehealth"=>2
                                ,"creaturegold"=>0
                                ,"diddamage"=>0);

		$userlevel=$session['user']['level'];
    	$userattack=e_rand(2,$session['user']['atack'])+2;
    	$userhealth=e_rand(30,110)+$session['user']['level'];
    	$userdefense=e_rand(2,$session['user']['defense'])+2;
    	$badguy['creaturelevel']+=$userlevel;
    	$badguy['creatureattack']+=$userattack;
    	$badguy['creaturehealth']=$userhealth;
    	$badguy['creaturedefense']+=$userdefense;
    	$badguy['creaturegold']=0;
    	$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op == "fight2"){
		$badguy = array(        "creaturename"=>translate_inline("`@Will Scarlet`0")
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>translate_inline("Sword")
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>2
                                ,"creaturegold"=>0
                                ,"diddamage"=>0);

    $userlevel=$session['user']['level'];
    $userattack=e_rand(2,$session['user']['atack'])+4;
    $userhealth=e_rand(40,120)+$session['user']['level'];
    $userdefense=e_rand(2,$session['user']['defense'])+4;
    $badguy['creaturelevel']+=$userlevel;
    $badguy['creatureattack']+=$userattack;
    $badguy['creaturehealth']=$userhealth;
    $badguy['creaturedefense']+=$userdefense;
    $badguy['creaturegold']=0;
    $session['user']['badguy']=createstring($badguy);
    $op="fight";
	} 
	if ($op == "fight3"){
		$badguy = array(        "creaturename"=>translate_inline("`@Little John`0")
                                ,"creaturelevel"=>1
                                ,"creatureweapon"=>translate_inline("Staff")
                                ,"creatureattack"=>2
                                ,"creaturedefense"=>3
                                ,"creaturehealth"=>2
                                ,"creaturegold"=>0
                                ,"diddamage"=>0);

    $userlevel=$session['user']['level'];
    $userattack=e_rand(2,$session['user']['atack'])+6;
    $userhealth=e_rand(50,130)+$session['user']['level'];
    $userdefense=e_rand(2,$session['user']['defense'])+6;
    $badguy['creaturelevel']+=$userlevel;
    $badguy['creatureattack']+=$userattack;
    $badguy['creaturehealth']=$userhealth;
    $badguy['creaturedefense']+=$userdefense;
    $badguy['creaturegold']=0;
    $session['user']['badguy']=createstring($badguy);
    $op="fight";
	}
	if ($op == "fight4"){
		$badguy = array(        "creaturename"=>translate_inline("`@Robin Hood`0")
                                ,"creaturelevel"=>2
                                ,"creatureweapon"=>translate_inline("Flying Arrows")
                                ,"creatureattack"=>3
                                ,"creaturedefense"=>4
                                ,"creaturehealth"=>2
                                ,"creaturegold"=>0
                                ,"diddamage"=>0);

		$userlevel=$session['user']['level'];
    $$userattack=e_rand(2,$session['user']['atack'])+8;
    $userhealth=e_rand(60,140)+$session['user']['level'];
    $userdefense=e_rand(2,$session['user']['defense'])+8;
    $badguy['creaturelevel']+=$userlevel;
    $badguy['creatureattack']+=$userattack;
    $badguy['creaturehealth']=$userhealth;
    $badguy['creaturedefense']+=$userdefense;
    $badguy['creaturegold']=0;
    $session['user']['badguy']=createstring($badguy);
    $op="fight";
	}

	if ($op == "fight"){
		$battle=true;                               
	}
	if ($battle){
    include_once("battle.php");                              
		if ($victory){
			$wonfight++;
			set_module_pref('wonfight', $wonfight);
			output("You have beaten `^%s.",$badguy['creaturename']);
			if ($badguy['creaturename']=="`@Friar Tuck`0"){
				addnav("Continue","forest.php?op=fight2");
				$exp = round($session['user']['experience']/10, 0);
				$session['user']['experience']+=$exp;	
				output("`n`nYou gain %s experience.",$exp);
			}
			if ($badguy['creaturename']=="`@Will Scarlet`0"){
				addnav("Continue","forest.php?op=fight3");
				$exp = round($session['user']['experience']/9, 0);
				$session['user']['experience']+=$exp;	
				output("`n`nYou gain %s experience.",$exp);
			}
			if ($badguy['creaturename']=="`@Little John`0"){
				addnav("Continue","forest.php?op=fight4");
				$exp = round($session['user']['experience']/8, 0);
				$session['user']['experience']+=$exp;	
				output("`n`nYou gain %s experience.",$exp);
			}
			if ($badguy['creaturename']=="`@Robin Hood`0"){
				addnav("Continue","forest.php?op=win");
				$exp = round($session['user']['experience']/7, 0);
				$session['user']['experience']+=$exp;	
				output("`n`nYou gain %s experience.",$exp);
			}
			$badguy=array();
			$session['user']['badguy']="";
		}elseif ($defeat){
			output("As you hit the ground `^%s and the rest of the Merry Men take your gold.",$badguy['creaturename']);
			$exp = round($session['user']['experience']/10, 0);
			$session['user']['experience']-=$exp;	
			output("`n`nYou lose %s experience.",$exp);
			addnews("`% %s`5 has been beaten when ".($session['user']['sex']?"she":"he")." was attacked by Robin Hood and his Band of Merry Men.",$session['user']['name']);
			$session['user']['hitpoints']=1;
			addnav("Continue","forest.php?op=loose");
		}else{
			require_once("lib/fightnav.php");
			fightnav(true,false);
		}
	}else{
	}
}
?>
