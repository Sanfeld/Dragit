<?php
/*
/ Magic Stones V0.3.0
/ Originally by Excalibur (www.ogsi.it)
/ English cleanup by Talisman (dragonprime.cawsquad.net)
/ Contribution LonnyL (www.pqcomp.com)
/ Original concept from Aris (www.ogsi.it)
/ July 2004
/
/ Aris Stones V1.0
/ Converted to LoGD v1.x 04/13/07
/ By Dragon89
/ 
/ v1.1 added missing db_prefixes to queries
*/

function stones_getmoduleinfo(){
	$info = array(
		"name"=>"Aris Stones",
		"author"=>"Excalibur converted by Dragon89",
		"version"=>"1.1",
		"category"=>"Forest Specials",
		"download"=>"http://www.dragonprime.net/",
		"description"=>"",
	);
	return $info;
}

function stones_install(){
	if (!db_table_exists("stones")) {
	    debug("Installing stones module.`n");
	    $sql = "CREATE TABLE ".db_prefix("stones")." (stone int(4) unsigned NOT NULL default '0', `owner` int(4) unsigned NOT NULL default '0') TYPE=MyISAM;";
        db_query($sql);
    }
	module_addhook("newday");
	module_addeventhook("forest", "return 100;");
	return true;
}

function stones_uninstall(){
	if (db_table_exists("stones")) {
		debug("Dropping Table: stones");
		$sql = "DROP TABLE ".db_prefix("stones")."";
		db_query($sql);
	}	
	return true;
}

function stones_dohook($hookname, $args){
	global $session;
    $stone = array(1=>"`\$Poker's Stone",2=>"`^Love's Stone",3=>"`^Friendship's Stone",4=>"`#King's Stone",5=>"`#Mighthy's Stone",6=>"`#Pegasus' Stone",7=>"`@Aris' Stone",8=>"`@Excalibur's Stone",9=>"`@Luke's Stone",10=>"`&Innocence's Stone",11=>"`#Queen's Stone",12=>"`#Imperator's Stone",13=>"`!Gold's Stone",14=>"`%Power's Stone",15=>"`\$Ramius' Stone",16=>"`#Cedrik's Stone",17=>"`%Honour's Stone",18=>"`&Purity's Stone",19=>"`&Light's Stone",20=>"`&Diamond's Stone");

	switch($hookname){
	    case "newday":
        $owner = $session['user']['acctid'];
        $sql = "SELECT * FROM ".db_prefix("stones")." WHERE owner='$owner'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        if (db_num_rows($result) != 0) {
            $flagstone = $row['stone'];
            switch ($flagstone) {
                case 1:
                output("`n`n`\$As you own %s `\$you lose a forest fight!`n", $stone[$flagstone]);
                $session['user']['turns']-=1;
    			break;
    			case 2:
         		output("`n`n`\$As you own %s `\$you gain a charm point!`n", $stone[$flagstone]);
         		$session['user']['charm']+=1;
    			break;
    			case 3:
         		output("`n`n`\$As you own %s `\$you gain a forest fight!`n", $stone[$flagstone]);
         		$session['user']['turns']+=1;
    			break;
    			case 4:
         		output("`n`n`\$As you own %s `\$you gain 500 gold!`n", $stone[$flagstone]);
         		$session['user']['gold']+=500;
    			break;
    			case 5:
         		output("`n`n`\$As you own %s `\$you gain attack!`n", $stone[$flagstone]);
         		apply_buff('stones1',array(
         		    "name"=>"{$stone[$flagstone]}",
         		    "rounds"=>1000,
         		    "wearoff"=>"`4The glow fades from your ".$stone[$flagstone].".",
         		    "atkmod"=>1.5,
         		    "roundmsg"=>"`4Your ".$stone[$flagstone]." enhances your attacking ability!.",
         		    "activate"=>"offense",
         		    "schema"=>"module-stones"
         		));
    			break;
    			case 6:
         		output("`n`n`\$As you own ".$stone[$flagstone]." `\$you gain defence!`n");
         		apply_buff('stones2',array(
         		    "name"=>"{$stone[$flagstone]}",
         		    "rounds"=>1000,
         		    "wearoff"=>"`4The glow fades from your ".$stone[$flagstone].".",
         		    "defmod"=>1.5,
         		    "roundmsg"=>"`4Your ".$stone[$flagstone]." glows intensely as it protects you!.",
         		    "activate"=>"offense",
         		    "schema"=>"module-stones"
         		));
    			break;
   				case 7:
         		output("`n`n`\$As you own ".$stone[$flagstone]."`\$ you attack and defend better!`n");
         		apply_buff('stones3',array(
         		    "name"=>"{$stone[$flagstone]}",
         		    "rounds"=>300,
         		    "wearoff"=>"`4The glow fades from your ".$stone[$flagstone].".",
         		    "atkmod"=>1.3,
         		    "defmod"=>1.3,
         		    "roundmsg"=>"`4Your ".$stone[$flagstone]." glows intensely as it empowers you!!",
         		    "activate"=>"offense",
         		    "schema"=>"module-stones"
         		));
    			break;
    			case 8:
         		output("`n`n`\$As you own ".$stone[$flagstone]." `\$you gain attack!`n");
         		apply_buff('stones4',array(
         		    "name"=>"{$stone[$flagstone]}",
         		    "rounds"=>500,
         		    "wearoff"=>"`4The glow fades from your ".$stone[$flagstone].".",
         		    "atkmod"=>1.5,
         		    "roundmsg"=>"`4Your ".$stone[$flagstone]." enhances your attacking ability!",
         		    "activate"=>"offense",
         		    "schema"=>"module-stones"
         		));
    			break;
    			case 9:
         		output("`n`n`\$As you own %s `\$you have gain extra skill points in your specialty today!`n", $stone[$flagstone]);
         		require_once("lib/increment_specialty.php");
         		increment_specialty("`^");
    			break;
    			case 10:
         		$chance = e_rand(1,100);
         		if($chance <= 50) {
         			output("`n`n`\$The %s is a stone of chance.  Each day your fate randomly is decided!", $stone[$flagstone]);
         			output("Today is a good day `\$you gain 2 forest fights!`n");
         			$session['user']['turns'] += 2;
         		} else {
         			output("`n`n`\$The %s is a stone of chance.  Each day your fate randomly is decided!", $stone[$flagstone]);
         			output("Today is a bad day `\$you lose 2 forest fights!`n");
         			$session['user']['turns'] -= 2;
                }         			
    			break;
    			case 11:
    			$gain = e_rand(300,1000);
         		output("`n`n`\$As you own %s `\$you gain $gain gold!`n", $stone[$flagstone]);
         		$session['user']['gold']+=$gain;
    			break;
    			case 12:
    			$gain = e_rand(800,2000);
         		output("`n`n`\$As you own %s `\$you gain $gain gold!`n", $stone[$flagstone]);
         		$session['user']['gold']+=$gain;
    			break;
    			case 13:
    			$gain = e_rand(1000,3200);
         		output("`n`n`\$As you own %s `\$you gain $gain gold!`n", $stone[$flagstone]);
         		$session['user']['gold']+=$gain;
    			break;
    			case 14:
         		output("`n`n`\$As you own %s `\$you attack and defence!`n", $stone[$flagstone]);
         		apply_buff('stones5',array(
         		    "name"=>"{$stone[$flagstone]}",
         		    "rounds"=>1000,
         		    "wearoff"=>"`4The glow fades from your ".$stone[$flagstone].".",
         		    "atkmod"=>1.5,
         		    "defmod"=>1.5,
         		    "roundmsg"=>"`4Your ".$stone[$flagstone]." glows intensely as it empowers you!!",
         		    "activate"=>"offense",
         		    "schema"=>"module-stones"
         		));
    			break;
    			case 15:
         		output("`n`n`\$As you own %s `\$you gain favor with Ramius!`n", $stone[$flagstone]);
         		$session['user']['deathpower']+=200;
    			break;
    			case 16:
         		output("`n`n`\$As you own %s `\$you are drunk!`n", $stone[$flagstone]);
        	 	$drunk = e_rand(20,66);
         		apply_buff('stones6',array(
         		    "name"=>"{$stone[$flagstone]}",
         		    "rounds"=>100,
         		    "wearoff"=>"`4The glow fades from your ".$stone[$flagstone].".",
         		    "atkmod"=>1.5,
         		    "roundmsg"=>"`4Your ".$stone[$flagstone]." causes you to become drunk and adds to your attack!!",
         		    "activate"=>"offense",
         		    "schema"=>"module-stones"
         		));
    			break;
    			case 17:
         		output("`n`n`\$As you own %s `\$you gain 3 extra forest fights!`n", $stone[$flagstone]);
         		$session['user']['turns']+=3;
    			break;
    			case 18:
         		output("`n`n`\$As you own %s `\$you gain 2 extra forest fight!`n", $stone[$flagstone]);
         		$session['user']['turns']+=2;
    			break;
    			case 19:
         		output("`n`n`\$As you own %s `\$you gain 1 extra forest fight!`n", $stone[$flagstone]);
         		$session['user']['turns']+=1;
   		 		break;
    			case 20:
         		output("`n`n`\$As you own %s `\$you gain a gem!`n", $stone[$flagstone]);
         		$session['user']['gems']+=1;
    			break;
    		}
		}
		break;
	}
	return $args;
}

function stones_runevent($type) {
	global $session;
	switch($type) {
	    case "forest":
		page_header("Aris' Spring");
		output("<font size='+1'>`c`b`!Aris' Spring`b`c`n</font>",true);
		$session['user']['specialinc'] = "module:stones";
    	$stone = array(1=>"`\$Poker's Stone",2=>"`^Love's Stone",3=>"`^Friendship's Stone",4=>"`#King's Stone",5=>"`#Mighthy's Stone",6=>"`#Pegasus' Stone",7=>"`@Aris' Stone",8=>"`@Excalibur's Stone",9=>"`@Luke's Stone",10=>"`&Innocence's Stone",11=>"`#Queen's Stone",12=>"`#Imperator's Stone",13=>"`!Gold's Stone",14=>"`%Power's Stone",15=>"`\$Ramius' Stone",16=>"`#Cedrik's Stone",17=>"`%Honour's Stone",18=>"`&Purity's Stone",19=>"`&Light's Stone",20=>"`&Diamond's Stone");
		$owner = $session['user']['acctid'];
		$sql = "SELECT * FROM ".db_prefix("stones")." WHERE owner='$owner'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$flagstone = $row['stone'];
		if (db_num_rows($result) == 0) {
        	page_header("The Spring");
			$op = httpget('op');
			switch($op){
    			case "":
        		output("`@Wandering through the forest looking for adventure, you find a natural spring from which emanates a mysterious glow. You've stumbled across the mythical `&Aris' Spring'`@, `nnamed after the wandering sage said to have discovered it.");
        		output("`n`nAlthough little is known of the spring, Aris did discover some knowledge of it, `nwhich is recorded in the annals of legend.`n`nHe found that the stones found in the spring emanate a powerful force, able to `naugment the energy of its owners, giving them an additional forest fight each day.`n");
        		output("The stones are limited in quantity, and each stone can be possessed by only one warrior at a time. You could become the holder of one of these magic stones, with a bit of luck.`n`n");
        		output("You noticed a rough button inserted in the bedrock near the spring, with some runic symbols engraved around it.`n`n");
        		output("You do not understand the symbols - are they an invitation?   Or...perhaps...a warning?");
        		addnav("`\$Leave the Spring","forest.php?op=leave");
        		addnav("`^Push the Button","forest.php?op=press");
    			break;
    			case "press":
        		output("`@Your hand pauses over the button as you sense the strength of the mystical power coming from Aris' Spring and its hidden treasures. ");
        		output("You begin to wonder if the legends were true, or if you're about to make a deadly mistake.`n`nAs you feel a flux of energy coming from the stone, you close your eyes and push the button firmly. ");
        		output("As the button gives way under pressure, you hear mechanical noises coming from inside the stone. When you open your eyes, you see a pool of water has been revealed. A golden glitter in the water makes you wonder if the Earth Goddess will grant you a favour .... `n`n");
        		$session['user']['specialinc']="";
            	villagenav();
        		addnav("F?`\$Back to Forest","forest.php");
        		$flagstone = e_rand(1,20);
        		$sql = "SELECT stone,owner FROM stones WHERE stone = $flagstone";
        		$result = db_query($sql) or die(db_error(LINK));
        		if (db_num_rows($result) == 0) {
            		// The stone is available
            		output("`#... you hear something rolling inside the stone, then a marvelous stone magically appears in the pool!!`n`nIt has some runes engraved on it, ");
            		if ($stone == 1) {
                		output("and you discover with horror that is %s`#!!!`n",$stone[$flagstone]);
                		output("Owning this cursed stone will cause you to lose 1 forest fight each day. `nYour only hope is that some other unlucky warrior will unwittingly stumble onto `&Aris' Source`# and claim the stone from you. ");
                		$session['user']['turns'] -= 1;
                		$id = $session['user']['acctid'];
                		$sql = "INSERT INTO stones (stone,owner) VALUES ('$flagstone','$id')";
                		db_query($sql);
            		} else {
                		output("and you discover with great joy that is %s`#!!`n`n", $stone[$flagstone]);
                		output("As owner of this stone, you gain a special bonus each newday. `nToday has been your lucky day, %s!!!`n", $session['user']['name']);
                		$id = $session['user']['acctid'];
                		$sql = "INSERT INTO stones (stone,owner) VALUES ('$flagstone','$id')";
                		db_query($sql);
            		}
        		} else {
            		$row = db_fetch_assoc($result);
            		output("`# you hear a whistle sound which grows in intensity, until it becomes a lament, stopping as suddenly as it started. A deep, calm voice speaks: `n`n\"");
            		$case = e_rand(0,1);
            		$account = $row['owner'];
            		$sqlz = "SELECT name FROM accounts WHERE acctid = $account";
            		$resultz = db_query($sqlz) or die(db_error(LINK));
            		$rowz = db_fetch_assoc($resultz);
            		if ($stone == 1) $switch = 1;
            		if ($case == 0) {
                		output("`%".($switch ? "Luckily":"Unluckily")." my dear %s`%, the %s `%is the possession of `@%s`%.`n", $session['user']['name'],$stone[$flagstone],$rowz['name']);
                		output("It is not in my nature to take it from him for you. `nYou will have to be satisfied with `^`b5`b`% more forest fights which I will grant you instead.`#\" `n`n");
                		output("You feel a flow of energy run through your body, and discover the voice's promise was kept!!! `n");
                		$session['user']['turns']+=5;
                	} else {
                    	output("`^The stone selected for you is possessed by `@%s`^. As he has fallen from my favour, `nI have chosen to retrieve and place it in your deserving care.`#\". `n`nYou see a beautiful stone materialize in the pool, `nand you grab it.", $rowz['name']);
                    	if ($stone != 1){
                        	output("You admire the %s`#, knowing you'll have a special power each day.`n", $stone[$flagstone]);
                    	} else {
                        	output("You discover with horror that is the %s `#!!!`n Ownership of this stone will costs you 1 forest fight each day. `nOur only hope is that some other unlucky warrior will unwittingly stumble onto `&Aris' Source`# and claim the stone from you.", $stone[$flagstone]);
                        	$session['user']['turns']-=1;
                    	}
                    	require_once("lib/systemmail.php");
                    	$account1 = $session['user']['acctid'];
                    	$sqlr = "UPDATE stones SET owner = $account1 WHERE stone=$flagstone";
                    	db_query($sqlr);
                    	$mailmessage = "`@{$session['user']['name']} `@has found `&Aris' Source`@ and the earth goddess has decided to give him your {$stone[$flagstone]} stone`@!! It's your ".($switch?"":"un")."lucky day.";
                    	systemmail($account,"`2Your stone has been given to the care of {$session['user']['name']} `2",$mailmessage);
                	}
            	}
    			break;
    			case "leave":
        		$session['user']['specialinc']="";
        		$loss = round($session['user']['hitpoints']/2);
        		if($loss <= 0) $loss = 1;
        		$session['user']['hitpoints'] -= $loss;
        		output("`6Terrified by the power of the spring, you decide not to tempt fate. `nYou turn back to forest and its relative safety.  As you turn your back, you hear a bubbling `nsound coming from the spring of water. `n`n`^A jet of water hits the back of your head like a mallet `nand throwing you to the ground!`n`n `\$`bYou lose %s hit points from the fall!!!!`b", $loss);
        		addnav("`\$Back to Forest","forest.php");
   				break;
			}
		} else {
    		$session['user']['specialinc']="";
    		output("`@Wandering through the forest looking for adventure, you find a natural spring from which emanates a mysterious glow. You've stumbled across the mythical `&Aris' Spring'`@, `nnamed after the wandering sage said to have discovered it.");
    		output("You are no stranger to the spring, and are well aware of it's power, as you already possess the %s.`n`n",$stone[$flagstone]);
    		output("While you are here, you drink of the clear water and feel refreshed.`n`n`%You lose 1 turn for the time spent here, but are fully healed.");
    		$session['user']['turns'] -= 1;
    		if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
    		addnav("`\$Back to Forest","forest.php");
    	}
		page_footer();
        break;
    }
}

function stones_run() {
}
?>