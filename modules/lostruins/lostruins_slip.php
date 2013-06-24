<?php
function lostruins_slip(){
	global $session;
	$exploreturns=get_module_setting("exploreturns");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$usedexpts=$allprefs['usedexpts'];
	if (is_module_active('alignment')) increment_module_pref("alignment",-8,"alignment");   
	output("`n`c`b`#Poisoning the Spring`c`b`n");
	output("`7Oooh! That is pure`$ evil`7!!");
	output("You wait for an unsuspecting adventurer to wander by...`n`n");
	switch(e_rand(1,3)){
		case 1:
			output("Soon enough, a simple little farmboy comes by and jumps into the water.`n`n");
			switch(e_rand(1,2)){
				case 1:
					output("It turns out that the poisoned water transforms the simple farmboy into a towering Stone`Q Golem`7.");
					addnav("Stone`Q Golem`$ Fight","runmodule.php?module=lostruins&op=attack");
				break;
				case 2:
					$exploss = round($session['user']['experience']*.1);
					output("`$ The little farmboy dies.`n`%He was poor.`^`nHe had no gold.`n`n`&You've committed murder most foul.");
					output("The	Gods do not like this one bit and decide you are not fit to live.`n`n`\$You have died.`%");
					output("`nYou are poor.`^`nYou have no gold.`n`b`4You lose `#%s experience`4.`b`n`n",$exploss);
					addnews("%s`^ murdered an innocent farmboy in the `5A`6ncient `5R`6uins`^.",$session['user']['name']);
					addnav("Daily news","news.php");
					$session['user']['experience']-=$exploss;
					$session['user']['alive'] = false;
					$session['user']['hitpoints'] = 0;
					$session['user']['gold']=0;
				break;
			}
		break;
		case 2:
			addnav("V?(V) Return to Village","village.php");
			if ($usedexpts<$exploreturns) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
			if(get_module_setting("poisoned")==1){
				$sql = "SELECT acctid,name,gold,sex FROM ".db_prefix("accounts")." WHERE alive=1 and acctid<>'$id' ORDER BY rand(".e_rand().") LIMIT 1";
				$res = db_query($sql);
				$row = db_fetch_assoc($res);
				$name = $row['name'];
				$id = $row['acctid'];
				$sex = $row['sex'];
				$gold1= $row['gold'];
				$gold=round($gold1*.5);
				$session['user']['gold']+=$gold;
				db_query($sql);
				$sql = "UPDATE " . db_prefix("accounts") . " SET gold=gold*.5, hitpoints=1 WHERE acctid='$id'";
				db_query($sql);
				if ($name==$session['user']['name']){
					output("`&You idiot!`7`n`n You wander over to the spring and take a sip, forgetting that you had just poisoned the water!`n`n  You`$ lose all your hitpoints except one`7 and fall unconscious, `^losing all your gold`7 and`@ losing the rest of your turns`7.`n`n");
					$session['user']['turns']=0;
					$session['user']['gold']=0;
				}else{
					require_once("lib/systemmail.php");
					$subj = sprintf("You were poisoned at the `5A`6ncient `5R`6uins");
					$body = sprintf("`^You were poisoned!`n`nThe malicious act caused you to`$ lose all your hitpoints except one`^ and`b half your gold`b at the `5A`6ncient `5R`6uins`^.`n`n  The last thing you remember was sipping some pleasant spring water.  Then you remember seeing`& %s`^ jump out to steal half your gold.",$session['user']['name']);
					systemmail($id,$subj,$body);
					output("Before you know it, one comes along... `^");
					output("It's %s`^!",$name);
					output("`n`n `7 With a quick drink from the spring, %s falls to the ground`$ unconscious`7!!`n`n",$name);
					if ($gold<1) output("`7Greedily, you try to`^ collect %s`^'s gold...`7  But there was`# none`7!",$name);
					else output("`7Greedily, you `^collect  %s`^'s gold`7 and cram it into your pockets.  You find`^ %s gold`7!",$name,$gold);
				}
			}else{
				$gold=get_module_setting("case11g");
				output("`7Before you know it, one comes along...");
				output("A`% brave young squire`7 looking for a sword for his`& knight`7.");
				output("`n`n  With a quick drink from the spring, he falls to the ground unconscious.`n`n");
				output("You slither over and grab his gold sac and count your fortune...");
				output("You've hit the `@Jackpot`7!! `^%s gold!`$ Evil Rocks!`7",$gold);
				$session['user']['gold']+=$gold;
			}
		break;
		case 3:
			output("But nothing happens.");
			output("Nobody comes along, and before you know it the poison clears from the spring.");
			addnav("V?(V) Return to Village","village.php");
			if ($usedexpts<$exploreturns) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
		break;
	}
}
?>