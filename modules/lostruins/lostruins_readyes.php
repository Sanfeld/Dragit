<?php
function lostruins_readyes(){
	global $session;
	output("`n`c`b`&The Strange Inscription`c`b`n");
	output("You begin reading the strange inscription...`n`n");
	switch(e_rand(1,5)){
		case 1: case 2: case 3: case 4:
			output("`6You stumble for a second as you think you felt the earth shake... Oh wait! That was nothing. In fact, you probably pronounced something wrong and it didn't work. You shrug and wander off.`n`n");
		break;
		case 5:
			if (get_module_setting("sexchange")>0){
				$sql = "SELECT acctid,name,sex FROM ".db_prefix("accounts")." WHERE alive=1 and acctid<>'$id' ORDER BY rand(".e_rand().") LIMIT 1";
				$res = db_query($sql);
				$row = db_fetch_assoc($res);
				$name = $row['name'];
				$id = $row['acctid'];
				$sex = $row['sex'];
				$allprefsg=unserialize(get_module_pref('allprefs','lostruins',$id));
				if ($allprefsg['sexcount']>0) output("`6You stumble for a second as you think you felt the earth shake... Oh wait! That was nothing. In fact, you probably pronounced something wrong and it didn't work. You shrug and wander off.`n`n");
				else{
					output("Something happened!`n`n`6You're sure of it. You do a complete inventory of yourself and find");
					if ($id==$session['user']['acctid']) output(" your clothes don't fit quite the same anymore...`n`nAfter some serious research, you find that your sex has changed, but it will reverse back to normal in`& %s days`^.",get_module_setting("sexchange"));
					else{
						require_once("lib/systemmail.php");
						output("nothing changed.`n`nSo what in the world happened? `3`n`n Or maybe it's 'What happened to someone in the world?'`7`n`n  Oh well!");				
						output("`n`n`%Maybe you should consider chatting with`^ %s`% to see how they are feeling.",$name);
						$subj = sprintf("You feel a disturbance from the `5A`6ncient `5R`6uins");
						$body = sprintf("`^Something VERY strange has happened to you.  You notice your clothes don't fit quite the same anymore... You've changed, and it happened because %s`^ decided to read random inscriptions in the `5A`6ncient `5R`6uins`^.`n`nAfter some serious research, you find that your sex has changed, but it will reverse back to normal in`& %s days`^.",$session['user']['name'],get_module_setting("sexchange"));
						systemmail($id,$subj,$body);
					}
					$allprefsg['sexcount']=get_module_setting("sexchange");
					set_module_pref('allprefs',serialize($allprefsg),'lostruins',$id);
					if ($sex==1) $sql = "UPDATE ". db_prefix("accounts") . " SET sex=0 WHERE acctid='{$row['acctid']}'";
					else $sql = "UPDATE ". db_prefix("accounts") . " SET sex=1 WHERE acctid='{$row['acctid']}'";
					db_query($sql);
					debuglog("changed the sex of $name in the Ancient Ruins.");
				}
			}else{
				output("`7Suddenly, the best grilled cheese sandwich you've ever seen appears right in front of you.");
				output("You take a quick bite and you are in`% Paradise`7!!`n`nYour`$ hitpoints increase by 25`7!");
				$session['user']['hitpoints']+= 25;
			}
		break;
	}
}
?>