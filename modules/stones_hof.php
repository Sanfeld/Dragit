<?php

// v 1.1 added missing db_prefixes to queries

function stones_hof_getmoduleinfo(){
	$info = array(
		"name"=>"Aris Stones HoF",
		"author"=>"Excalibur converted by Dragon89",
		"version"=>"1.1",
		"category"=>"Forest Specials",
		"download"=>"",
		"description"=>"",
	);
	return $info;
}

function stones_hof_install(){
	module_addhook("footer-hof");
}

function stones_hof_uninstall(){
}

function stones_hof_dohook($hookname, $args){
	switch($hookname){
	    case "footer-hof":
        addnav("Aris Stones");
        addnav("Stone Owners","runmodule.php?module=stones_hof&op=enter");
        break;
    }
    return $args;
}

function stones_hof_run() {
	global $session;
    $op = httpget('op');
    $stone = array(1=>"`\$Poker's Stone",2=>"`^Love's Stone",3=>"`^Friendship's Stone",4=>"`#King's Stone",5=>"`#Mighthy's Stone",6=>"`#Pegasus' Stone",7=>"`@Aris' Stone",8=>"`@Excalibur's Stone",9=>"`@Luke's Stone",10=>"`&Innocence's Stone",11=>"`#Queen's Stone",12=>"`#Imperator's Stone",13=>"`!Gold's Stone",14=>"`%Power's Stone",15=>"`\$Ramius' Stone",16=>"`#Cedrik's Stone",17=>"`%Honour's Stone",18=>"`&Purity's Stone",19=>"`&Light's Stone",20=>"`&Diamond's Stone");
    if($op == "enter") {
		page_header("Aris' Stones");
		addnav("F?Back to the Hall of Fame","hof.php");

		output("`!`b`c<font size='+1'>Aris' Stones</font>`c`b`n`n",true);
		output("`@You want to know who are the owner of `&Aris' Stones`@ and if any of them are still available ? ");
		output("Here we go, my young warrior.`n");
		output("<table cellspacing=2 cellpadding=2 align='center'>",true);
		output("<tr bgcolor='#FF0000'><td align='center'>`&`bStone N°`b</td><td align='center'>`&`bStone`b</td><td align='center'>`b`&Warrior`b</td></tr>",true);
        $stone = array(1=>"`\$Poker's Stone",2=>"`^Love's Stone",3=>"`^Friendship's Stone",4=>"`#King's Stone",5=>"`#Mighthy's Stone",6=>"`#Pegasus' Stone",7=>"`@Aris' Stone",8=>"`@Excalibur's Stone",9=>"`@Luke's Stone",10=>"`&Innocence's Stone",11=>"`#Queen's Stone",12=>"`#Imperator's Stone",13=>"`!Gold's Stone",14=>"`%Power's Stone",15=>"`\$Ramius' Stone",16=>"`#Cedrik's Stone",17=>"`%Honour's Stone",18=>"`&Purity's Stone",19=>"`&Light's Stone",20=>"`&Diamond's Stone");
        for ($i = 1; $i < 21; $i++){
    		$sql = "SELECT owner FROM ".db_prefix("stones")." WHERE stone=$i";
    		$result = db_query($sql);
    		$row = db_fetch_assoc($result);
    		if (db_num_rows($result) == 0) {
        		$rown['name']="`b`\$Available`b";
        		$pietra1="`5Unknown";
    		}else {
          		$pietra1 = $stone[$i];
          		$sqln="SELECT name FROM ".db_prefix("accounts")." WHERE acctid = {$row['owner']}";
          		$resultn = db_query($sqln);
          		$rown = db_fetch_assoc($resultn);
    		}
    		if ($rown['name'] == $session['user']['name']) {
        		output("<tr bgcolor='#007700'>", true);
    		} else {
        		output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        	}
    		output("<td align='center'>`&".$i."</td><td align='center'>`&`b$pietra1`b</td><td align='center'>`&`b{$rown[name]}`b</td></tr>",true);
		}
		output("</table>", true);
		page_footer();
	}
}
?>