<?php
	global $session;
	page_header("Gladiators");
	$op=httpget('op');
	$pvp=get_module_setting("name");
	$magic=get_module_setting("name2");
	//$colliseum=get_module_setting("name3");
	if ($op=="enter"){
		output("You walk into the Arena grounds, looking around, you see several passages.  Which will you take?");
		addnav(array("%s",$pvp),"runmodule.php?module=arena&op=pvp");
		addnav(array("%s",$magic),"runmodule.php?module=arena&op=magic");
		//addnav(array("Watch %s",$pvp),"runmodule.php?module=arena&op=watchpvp");
		//addnav(array("Watch %s",$magic),"runmodule.php?module=arena&op=watchmagic");
		output_notl("`n`n");
		output("`^Coming soon, viewing areas to watch battles");
		
		villagenav();
	}

	if ($op=="pvp"){
		$sql="SELECT * FROM " . db_prefix ("arenastats") . " ORDER BY 'pvpwins' DESC LIMIT 1";
		$res=db_query($sql);
		$row=db_fetch_assoc($res);
		$id = $row['id'];
		$sqln = "SELECT * FROM " . db_prefix("accounts") . "  WHERE acctid = '$id'";
		$resn=db_query($sqln);
		$rown=db_fetch_assoc($resn);
		$champ = $rown['name'];
		output("`b`c`^Current Arena Champion:`b %s`0`c",$champ);
		output("Following the signs along the way, you find yourself in the %s`0",$pvp);
		output_notl("`n`n");
		if (get_module_pref("pvpreg")==0){
			addnav("Register","runmodule.php?module=arena&op=registerpvp");
			output("You don't appear to be registered, and cannot accept nor receive challenges until you do");
			output_notl("`n`n");
		}
		if (get_module_pref("pvpreg")==2){
			addnav("Challenge","runmodule.php?module=arena&op=challengepvp");
			addnav("Deregister","runmodule.php?module=arena&op=deregpvp");
			output("You have registered for this arena, and may now challenge someone, or wait to be challenged.  If you no longer wish to do this, please de-register.");
			output_notl("`n`n");
		}
		addnav("Rules and Guidelines","runmodule.php?module=arena&op=rulespvp");
		output("Also it would be a good idea to check out the rules and guidelines for this Arena");
		villagenav();
	}
	if ($op=="pvphof"){
		page_header("PVP Arena HOF");
		$acc = db_prefix("accounts");
		$ar = db_prefix("arenastats");
		$sql = "SELECT $acc.name AS name,
		$acc.acctid AS acctid,
		$ar.pvpwins AS wins,
		$ar.id FROM $ar INNER JOIN $acc
		ON $acc.acctid = $ar.id
		WHERE $ar.pvpwins > 0 ORDER BY ($ar.pvpwins+0)	
		DESC limit ".get_module_setting("list")."";
		$result = db_query($sql);
		$rank = translate_inline("PvP Wins");
		$name = translate_inline("Name");
		output("`n`b`c`^PvP Arena Wins`n`n`c`b");
		rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center'>");
		rawoutput("<tr class='trhead'><td align=center>$name</td><td align=center>$rank</td></tr>");
		for ($i=0;$i < db_num_rows($result);$i++){ 
			$row = db_fetch_assoc($result);
			if ($row['name']==$session['user']['name']){
				rawoutput("<tr class='trhilight'><td>");
			}else{
				rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td align=left>");
			}
			output_notl("%s",$row['name']);
			rawoutput("</td><td align=right>");
			output_notl("%s",$row['wins']);
			rawoutput("</td></tr>");
		}
		rawoutput("</table>");
		addnav("Back to HoF", "hof.php");
		villagenav();
		page_footer();
	}
	if($op=="deregpvp"){
		set_module_pref("pvpreg",0);
		output("You are now Deregistered from the PVP Arena, and may no longer challenge nor receive challenges");
		villagenav();
	}
	if($op=="registerpvp"){
		output("You are now registered in the %s`0 and may receive and send challenges",$pvp);
		addnav("Return to Arena","runmodule.php?module=arena&op=pvp");
		set_module_pref("fight",3);
		set_module_pref("pvpreg",2);
		villagenav();
		//now to check and see if they have a gladiatorid, if not assign them one
		$id=$session['user']['acctid'];
		$sql = "SElECT * FROM " . db_prefix("arenastats") . " WHERE id = '$id'";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$gladiator = $row['gladiatorid'];
		if ($gladiator==0){
			$sql = "INSERT INTO " .db_prefix("arenastats") . " (gladiatorid, id) VALUES ('0', '$id')";
			db_query($sql);
		}
	}
	if($op=="challengepvp"){
		$lev1 = $session['user']['level']-1;
		$lev2 = $session['user']['level']+2;
		$last = date("Y-m-d H:i:s",
				strtotime("-".getsetting("LOGINTIMEOUT", 900)." sec"));
		$loggedin=1;
		$lastip = $session['user']['lastip'];
		$lastid = $session['user']['uniqueid'];
		$acc = db_prefix("accounts");
		$mp = db_prefix("module_userprefs");
		$sqlc = "SELECT $acc.name AS name,
		$acc.acctid AS acctid,
		$mp.value AS registered,
		$mp.userid FROM $mp INNER JOIN $acc
		ON $acc.acctid = $mp.userid 
		WHERE $mp.modulename = 'arena' 
		AND $mp.setting = 'pvpreg' 
		AND $mp.userid <> ".$session['user']['acctid']."
		AND $acc.level>=$lev1 
		AND $acc.level<=$lev2
		AND $acc.loggedin = $loggedin 
		AND $acc.laston>'$last'
		AND $acc.lastip <> '$lastip'
		AND $acc.uniqueid <> '$lastid'
		AND $mp.value = 2
		ORDER BY ($mp.value+0)
		";
		$resc = db_query($sqlc);
		$opp = translate_inline("Opponent");
    	$chal = translate_inline("Challenge");
    	$unavailable = translate_inline("Unavailable");
        rawoutput("<table border='0' cellpadding='3' cellspacing='0' align='center'><tr class='trhead'><td style='width:250px' align=center>$opp</td><td align=center>$chal</td></tr>"); 
        if(!db_num_rows($resc)){
        	$none = translate_inline("None");
        	rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td align=center  colspan=4><i>$none</i></td></tr>");
    	}else{
        	for ($i = 0; $i < db_num_rows($resc); $i++){ 
        		$rowc = db_fetch_assoc($resc);
	        	$opponent = $rowc['name'];
    	    	$id = $rowc['acctid'];
        		$num = $i+1;
            	rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
	            output_notl($opponent);
    	        rawoutput("</td><td>");
        	   	rawoutput("<a href='runmodule.php?module=arena&op=pvpchallengesent&opponent=".HTMLEntities($rowc['acctid'])."'>");
        		addnav("","runmodule.php?module=arena&op=pvpchallengesent&opponent=".HTMLEntities($rowc['acctid']));
        		output_notl("`#[`&Challenge`#]`0");
    		}
    	}    
    	rawoutput("</table>");
    	addnav("Return to arena", "runmodule.php?module=arena&op=pvp");
    	villagenav();
	}
	if ($op=="pvpchallengesent"){
		
		$id1 = $session['user']['acctid'];
		$hp1 = $session['user']['maxhitpoints'];
		$atk1 = $session['user']['attack'];
		$def1 = $session['user']['defense'];
		$name1=$session['user']['name'];
		$dks = $session['user']['dragonkills'];
		if ($dks*50<$hp1){
			$hp1 = $dks*50;
		}
		$lvl=$session['user']['level'];
		$id2=httpget('opponent');
		$sql = "SELECT * FROM " . db_prefix("accounts") . " WHERE acctid = '$id2'";
		$res=db_query($sql);
        $row=db_fetch_assoc($res);
		$hp2 = $row['maxhitpoints'];
		$atk2= $row['attack'];
		$def2 = $row['defense'];
		$dk2 = $row['dragonkills'];
		if ($dk2*50<$hp2){
			$hp2 = $dk2*50;
		}
		$opponent = $row['name'];
		$sqlb = "INSERT INTO ".db_prefix("arena")." (battleid, type, lvl, id1, name1, id2, name2, hp1, hp2, atk1, atk2, def1, def2) VALUES (0, 1, '$lvl', '$id1', '$name1', '$id2', '$opponent', '$hp1', '$hp2', '$atk1', '$atk2', '$def1', '$def2')";
		db_query($sqlb);
		$sqlq = "SELECT * FROM " .db_prefix("arena"). " WHERE id1 = '$id1' ORDER BY 'battleid' DESC Limit 1";
		$resq = db_query($sqlq);
		$rowq = db_fetch_assoc($resq);
		$battleid = $rowq['battleid'];
		set_module_pref("battleid",$battleid,"arena",$id2);
		set_module_pref("battleid",$battleid);
		set_module_pref("pvpreg",1,"arena",$id2);
		set_module_pref("pvpreg",1,"arena",$id1);
		set_module_pref("fight",4);
		set_module_pref("fight",3,"arena",$id2);
		$min = date("Y-m-d H:i:s");
		set_module_pref("min",$min,"arena",$id1);
		set_module_pref("min",$min,"arena",$id2);
		output("Your opponent %s`0 has been challenged",$opponent);
		addnav("Continue", "runmodule.php?module=arena&op=pvpopponent");
		require_once("lib/systemmail.php");
		systemmail($id2,"`^You Have Been Challenged!`0",array("`&%s`& has challenged you to a %s`& Battle, Please return to the Village to Accept or Decline this challenge.",$session['user']['name'],$name));
	}
	if ($op=="challenged"){
		$id2 = $session['user']['acctid'];
		$battleid = get_module_pref("battleid");
		$sql = "SELECT * FROM " . db_prefix ("arena") . " WHERE battleid = '$battleid'";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$id1 = $row['id1'];
		$type = $row['type'];
		if ($type==1){
			addnav("Accept Challenge", "runmodule.php?module=arena&op=pvpfight&battle=$battleid");
			set_module_pref("fight",0,"arena",$id2);
			
			$battlet="PVP";
		}
		if ($type==2){
			addnav("Accept Challenge", "runmodule.php?module=magicarena&op=magicfight&battle=$battleid&potion=0");
			set_module_pref("mfight",0,"arena",$id2);
			$battlet="Magical";
		}
		output("You have been Challenged to a %s battle, What will you do?",$battlet);
		addnav("Decline", "runmodule.php?module=arena&op=decline");
	}
	if ($op=="decline"){
		$id2 = $session['user']['acctid'];
		$battleid = get_module_pref("battleid");
		$sql = "SELECT * FROM " . db_prefix ("arena") . " WHERE battleid = '$battleid'";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$id1 = $row['id1'];
		$type = $row['type'];
		if ($type==1){
			set_module_pref("pvpreg",2,"arena",$id2);
			set_module_pref("fight",3,"arena",$id2);
			set_module_pref("fight",3,"arena",$id1);
			set_module_pref("cancelled",1,"arena",$id1);
		}
		if ($type==2){
			set_module_pref("magicreg",2,"arena",$id2);
			set_module_pref("mfight",3,"arena",$id2);
			set_module_pref("mfight",3,"arena",$id1);
			set_module_pref("cancelled",1,"arena",$id1);
		}
		output("You have declined the challenge");
		villagenav();
	}
	if ($op=="pvpopponent"){
		$fight = get_module_pref("fight");
		$id = $session['user']['acctid'];
		$battleid = get_module_pref("battleid");
		$sql = "SELECT * FROM " . db_prefix ("arena") . " WHERE battleid = '$battleid'";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$id2 = $row['id2'];
		$timenow = date("Y-m-d H:i:s");
		$time = date("Y-m-d H:i:s",strtotime("-180 seconds"));
		$timeold = get_module_pref("min","arena",$id2);
		if ($time>$timeold){
			output_notl("`n`n");
			output("`b`QYour opponent has failed to reply, the battle is cancelled`b");
			villagenav();
			blocknav("runmodule.php?module=arena&op=pvpopponent");
			set_module_pref("pvpreg",2,"arena",$id2);
			set_module_pref("pvpreg",2,"arena",$id);
			set_module_pref("fight",3,"arena",$id2);
			set_module_pref("fight",3,"arena",$id);
			set_module_pref("cancelled",1,"arena",$id2);
			require_once("lib/systemmail.php");
			systemmail($id2,"`^Challenge Cancelled!`0",array("`The battle with %s`^ has been cancelled as you didn't respond in time.",$session['user']['name']));
		}elseif ($time<=$timeold){
			if ($fight==4){
				output("You wait for your opponent to accept or decline the battle");
				addnav("Refresh","runmodule.php?module=arena&op=pvpopponent");
			}
			if ($fight==3){
				output("Your opponent has declined the challenge");
				set_module_pref("pvpreg",2,"arena",$id);
				villagenav();
			}
			if ($fight==1 || $fight==2 || $fight==0){
				output("Your opponent has accepted, go to the arena");
				addnav("Prepare for Battle","runmodule.php?module=arena&op=pvpfight&battle=$battleid");
			}
			set_module_pref("min",$timenow);
		}
	}
	if ($op=="pvpfight"){
		$id = $session['user']['acctid'];
			$battleid = get_module_pref("battleid");
			$sql= "SELECT * FROM " . db_prefix("arena") . " WHERE battleid = '$battleid'";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$id1=$row['id1'];
			$hp = $row['hp1'];
			$atk = $row['atk1'];
			$def = $row['def1'];
			$ophp = $row['hp2'];
			$opatk= $row['atk2'];
			$opdef = $row['def2'];
			$name1=$row['name1'];
			$name2=$row['name2'];
			$id2=$row['id2'];
			$wp1=$row['wp1'];
			$wp2=$row['wp2'];
			$ar1=$row['ar1'];
			$ar2=$row['ar2'];
			$lvl = $row['lvl'];
			$fight = get_module_pref("fight");
			
		if (get_module_pref("fight","arena",$id1)==1 && get_module_pref("fight","arena",$id2)==1){
			set_module_pref("fight",2,"arena",$id1);
		}
		if ($id == $row['id1']){
			$time = date("Y-m-d H:i:s",strtotime("-180 seconds"));
			$timeold = get_module_pref("min","arena",$id2);
			$timenow = date("Y-m-d H:i:s");
			$lasthit = get_module_pref("lasthit","arena",$id2);
			$bonushit = get_module_pref("bonushit","arena",$id2);
			if (get_module_pref("timeout")==1){
				output_notl("`n`n");
				output("`b`QYou have timed out, your opponent has been awarded the win, the battle is cancelled.`b");
				villagenav();
				set_module_pref("pvpreg",2,"arena",$id1);
				blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
				blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
				$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id1";
				$resr = db_query($sqlr);
				$rowr = db_fetch_assoc($resr);
				$loss = $rowr['pvploss']+=1;
				set_module_pref("fight",3,"arena",$id1);
				db_query("UPDATE " . db_prefix("arenastats") . " SET pvploss = $loss WHERE id = $id1");
			}
			if (get_module_pref("timeout")==0){
				if ($time>$timeold){
					output_notl("`n`n");
					output("`QYour opponent has timed out, you have been awarded the win, the battle is cancelled`b");
					set_module_pref("pvpreg",2,"arena",$id1);
					villagenav();
					blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
					blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
					set_module_pref("timeout",1,"arena",$id2);
					$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id1";
					$resr = db_query($sqlr);
					$rowr = db_fetch_assoc($resr);
					$wins = $rowr['pvpwins']+=1;
					set_module_pref("fight",3,"arena",$id1);
					db_query("UPDATE " . db_prefix("arenastats") . " SET pvpwins = $wins WHERE id = $id1");
				
				}
				if ($time<=$timeold){
					set_module_pref("min",$timenow);
					if ($hp<=0){
						if ($lasthit>0 && $bonushit==0){
							output("Your opponent hits you for %s damage",$lasthit);
							output("`n`n");
						}
						if ($lasthit>0 && $bonushit>0){
							output("Your opponent hits you for %s damage, and ducking under your guard they execute a second attack for %s damage",$lasthit,$bonushit);
							output("`n`n");
						}
						if ($lasthit<=0 && $bonushit>0){
							output("Your opponent swings and misses, they manage to recover just in time to hit you for %s damage",$bonus);
							output("`n`n");
						}
						output("You have been defeated");
						addnav("Return to Village","village.php");
						$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id1";
						set_module_pref("pvpreg",2,"arena",$id1);
						$resr = db_query($sqlr);
						$rowr = db_fetch_assoc($resr);
						$loss = $rowr['pvploss']+=1;
						set_module_pref("fight",3,"arena",$id1);
						db_query("UPDATE " . db_prefix("arenastats") . " SET pvploss = $loss WHERE id = $id1");
						blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
						blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
					}
					if ($ophp<=0){
						output("You have won, and earnt yourself a arena point for this arena");
						addnav("Return to Village","village.php");
						set_module_pref("pvpreg",2,"arena",$id1);
						$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id1";
						$resr = db_query($sqlr);
						$rowr = db_fetch_assoc($resr);
						$wins = $rowr['pvpwins']+=1;
						set_module_pref("fight",3,"arena",$id1);
						addnews("%s`2 defeated %s `2 in the `#Battlegrounds",$name1,$name2);
						db_query("UPDATE " . db_prefix("arenastats") . " SET pvpwins = $wins WHERE id = $id1");
						blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
						blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
					}
					if ($ophp>0 && $hp>0){
						$sqla = "SELECT * FROM " . db_prefix("accounts") . " WHERE acctid = '$id2'";
						$resa=db_query($sqla);
						$rowa=db_fetch_assoc($resa);
						if (get_module_pref("cancelled")==1){
							output("For some reason, usually a time out the battle was cancelled.");
							set_module_pref("pvpreg",2,"arena",$id1);
							set_module_pref("fight",3,"arena",$id1);
							blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
							blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
							villagenav();
						}
						if (get_module_pref("cancelled")==1){
							output("For some reason, usually a time out the battle was cancelled.");
							blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
							blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
							set_module_pref("pvpreg",2,"arena",$id1);
							set_module_pref("fight",3,"arena",$id1);
							set_module_pref("cancelled",0,"arena",$id1);
							villagenav();
						}
						if (get_module_pref("cancelled")==0){
							if ($fight==0){
								output("You enter the arena, to meet your opponent.");
								addnav("Fight", "runmodule.php?module=arena&op=pvphit&battle=$battleid");
								set_module_pref("fight",1,"arena",$id2);
							}
							if ($fight==1){
								output("You wait for your opponent to do something.");
								addnav("Refresh","runmodule.php?module=arena&op=pvpfight&battle=$battleid");
							}
							if ($fight==2){
								if ($lasthit>0 && $bonushit==0){
									output("Your opponent hits you for %s damage",$lasthit);
									output_notl("`n`n");
								}
								if ($lasthit>0 && $bonushit>0){
									output("Your opponent hits you for %s damage, and ducking under your guard they execute a second attack for %s damage",$lasthit,$bonushit);
									output_notl("`n`n");
								}
								if ($lasthit<=0 && $bonushit>0){
									output("Your opponent swings and misses, they manage to recover just in time to hit you for %s damage",$bonus);
									output_notl("`n`n");
								}
								if ($lasthit==0 && $bonushit==0){
									output("Your opponent misses");
									output_notl("`n`n");
								}
								if ($lasthit<0 && $bonushit==0){
									output("You fend off your opponents attack, riposting for %s damage",$lasthit);
									output_notl("`n`n");
								}
								if ($lasthit<0 && $bonushit<>0){
									output("You fend off your opponents attack, riposting for %s damage, they manage to recover enough to hit you for %s damage",$lasthit,$bonushit);
									output_notl("`n`n");
								}
								output("It is now your turn.");
								addnav("Fight","runmodule.php?module=arena&op=pvphit&battle=$battleid");
							}
						}
					}
				}
			}
			output_notl("`n`n");
			output("`b`c`^Arena Stats`b");
			output_notl("`n`n");
			output("Your Stats");
			output_notl("`n`n");
			output("`@Hitpoints: `#%s",$hp);
			output_notl("`n`n");
			output("`@Attack: `#%s",$atk);
			output_notl("`n`n");
			output("`@Defense: `#%s",$def);
			output_notl("`n`n");
			output("`b`c`^Arena Stats`b");
			output_notl("`n`n");
			output("Opponents Stats");
			output_notl("`n`n");
			output("`@Hitpoints: `#%s",$ophp);
			output_notl("`n`n");
			output("`@Attack: `#%s",$opatk);
			output_notl("`n`n");
			output("`@Defense: `#%s",$opdef);
		}
		if ($id == $row['id2']){
			$time = date("Y-m-d H:i:s",strtotime("-180 seconds"));
			$timeold = get_module_pref("min","arena",$id1);
			$timenow = date("Y-m-d H:i:s");
			$lasthit = get_module_pref("lasthit","arena",$id1);
			$bonushit = get_module_pref("bonushit","arena",$id1);
			if (get_module_pref("timeout")==1){
				output_notl("`n`n");
				output("`b`QYou have timed out, your opponent has been awarded the win, the battle is cancelled.`b");
				set_module_pref("pvpreg",2,"arena",$id2);
				villagenav();
				blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
				blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
				$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id2";
				$resr = db_query($sqlr);
				$rowr = db_fetch_assoc($resr);
				$loss = $rowr['pvploss']+=1;
				set_module_pref("fight",3,"arena",$id2);
				db_query("UPDATE " . db_prefix("arenastats") . " SET pvploss = $loss WHERE id = $id2");
			}
			if (get_module_pref("timeout")==0){
				if ($time>$timeold){
					output_notl("`n`n");
					output("`b`QYour opponent has timed out, you have been awarded the win, the battle is cancelled`b");
					set_module_pref("pvpreg",2,"arena",$id2);
					villagenav();
					blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
					blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
					set_module_pref("timeout",1,"arena",$id1);
					$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id2";
					$resr = db_query($sqlr);
					$rowr = db_fetch_assoc($resr);
					$wins = $rowr['pvpwins']+=1;
					set_module_pref("fight",3,"arena",$id2);
					db_query("UPDATE " . db_prefix("arenastats") . " SET pvpwins = $wins WHERE id = $id2");
				}
				if ($time<=$timeold){
					set_module_pref("min",$timenow);
					if ($ophp<=0){
						if ($lasthit>0 && $bonushit==0){
							output("Your opponent hits you for %s damage",$lasthit);
							output("`n`n");
						}
						if ($lasthit>0 && $bonushit>0){
							output("Your opponent hits you for %s damage, and ducking under your guard they execute a second attack for %s damage",$lasthit,$bonushit);
							output("`n`n");
						}
						if ($lasthit<=0 && $bonushit>0){
							output("Your opponent swings and misses, they manage to recover just in time to hit you for %s damage",$bonus);
							output("`n`n");
						}
						output("You have been defeated");
						addnav("Return to Village","village.php");
						blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
						blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
						set_module_pref("pvpreg",2,"arena",$id2);
						$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id2";
						$resr = db_query($sqlr);
						$rowr = db_fetch_assoc($resr);
						$loss = $rowr['pvploss']+=1;
						set_module_pref("fight",3,"arena",$id2);
						db_query("UPDATE " . db_prefix("arenastats") . " SET pvploss = $loss WHERE id = $id2");
					}
					if ($hp<=0){
						output("You have won, and earnt yourself a arena point for this arena");
						addnav("Return to Village","village.php");
						$sqlr="SELECT * FROM " . db_prefix("arenastats") . " WHERE id = $id2";
						$resr = db_query($sqlr);
						$rowr = db_fetch_assoc($resr);
						$wins = $rowr['pvpwins']+=1;
						set_module_pref("fight",3,"arena",$id2);
						set_module_pref("pvpreg",2,"arena",$id2);
						addnews("%s`2 defeated %s `2 in the `#Battlegrounds",$name2,$name1);
						db_query("UPDATE " . db_prefix("arenastats") . " SET pvpwins = $wins WHERE id = $id2");
						blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
						blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
					}
					if ($ophp>0 && $hp>0){
						$sqla = "SELECT * FROM " . db_prefix("accounts") . " WHERE acctid = '$id1'";
						$resa=db_query($sqla);
						$rowa=db_fetch_assoc($resa);
						if (get_module_pref("cancelled")==1){
							output("For some reason, usually a time out the battle was cancelled.");
							blocknav("runmodule.php?module=arena&op=pvpfight&battle=$battleid");
							blocknav("runmodule.php?module=arena&op=pvphit&battle=$battleid");
							set_module_pref("pvpreg",2,"arena",$id2);
							set_module_pref("fight",3,"arena",$id2);
							villagenav();
						}
						if (get_module_pref("cancelled")==0){
							if ($fight==0){
							output("You enter the arena, to meet your opponent.");
							set_module_pref("fight",1,"arena",$id1);
							addnav("Fight","runmodule.php?module=arena&op=pvphit&battle=$battleid");
							}
							if ($fight==1){
								output("You wait for your opponent to do something.");
								addnav("Refresh","runmodule.php?module=arena&op=pvpfight&battle=$battleid");
							}
							if ($fight==2){
								if ($lasthit>0 && $bonushit==0){
									output("Your opponent hits you for %s damage",$lasthit);
									output_notl("`n`n");
								}
								if ($lasthit>0 && $bonushit>0){
									output("Your opponent hits you for %s damage, and ducking under your guard they execute a second attack for %s damage",$lasthit,$bonushit);
									output_notl("`n`n");
								}
								if ($lasthit<=0 && $bonushit>0){
									output("Your opponent swings and misses, they manage to recover just in time to hit you for %s damage",$bonus);
									output_notl("`n`n");
								}
								if ($lasthit==0 && $bonushit==0){
									output("Your opponent misses");
									output_notl("`n`n");
								}
								if ($lasthit<0 && $bonushit==0){
									output("You fend off your opponents attack, riposting for %s damage",$lasthit);
									output_notl("`n`n");
								}
								if ($lasthit<0 && $bonushit<>0){
									output("You fend off your opponents attack, riposting for %s damage, they manage to recover enough to hit you for %s damage",$lasthit,$bonushit);
									output_notl("`n`n");
								}
								output("Your opponent has attacked, it is now your turn.");
								addnav("Fight","runmodule.php?module=arena&op=pvphit&battle=$battleid");
							}
						}
					}
				}
			}
			output_notl("`n`n");
			output("`b`c`^Arena Stats`b");
			output_notl("`n`n");
			output("Your Stats");
			output_notl("`n`n");
			output("`@Hitpoints: `#%s",$ophp);
			output_notl("`n`n");
			output("`@Attack: `#%s",$opatk);
			output_notl("`n`n");
			output("`@Defense: `#%s",$opdef);
			output_notl("`n`n");
			output("`b`c`^Arena Stats`b");
			output_notl("`n`n");
			output("Opponents Stats");
			output_notl("`n`n");
			output("`@Hitpoints: `#%s",$hp);
			output_notl("`n`n");
			output("`@Attack: `#%s",$atk);
			output_notl("`n`n");
			output("`@Defense: `#%s",$def);
	}
}
	if ($op=="pvphit"){
		$id = $session['user']['acctid'];
		$battleid = get_module_pref("battleid");
		$sql= "SELECT * FROM " . db_prefix("arena") . " WHERE battleid = '$battleid' ORDER BY battleid DESC Limit 1";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$id1 = $row['id1'];
		$hp = $row['hp1'];
		$atk = $row['atk1'];
		$def = $row['def1'];
		$ophp = $row['hp2'];
		$opatk= $row['atk2'];
		$opdef = $row['def2'];
		$name1=$row['name1'];
		$name2=$row['name2'];
		$id2=$row['id2'];
		$wp1=$row['wp1'];
		$wp2=$row['wp2'];
		$ar1=$row['ar1'];
		$ar2=$row['ar2'];
		$lvl = $row['lvl'];
		
		if ($id == $id1){
			$time = date("Y-m-d H:i:s",strtotime("-180 seconds"));
			$timeold = get_module_pref("min","arena",$id2);
			$timenow = date("Y-m-d H:i:s");
			
			$atkmin = round($atk*0.1);
			$atkmax = round($atk*0.5);
			$atknew = e_rand($atkmin,$atkmax);
			$defmin = round($def*0.12);
			$defmax = round($def*0.47);
			$defnew = e_rand($defmin,$defmax);
			$opamin = round($opatk*0.1);
			$opamax = round($opatk*0.47);
			$opanew = e_rand($opamin,$opamax);
			$opdmin = round($opdef*0.11);
			$opdmax = round($opdef*0.48);
			$opdnew = e_rand($opdmin,$opdmax);
			//if ($hp>$ophp){
				//$hpadjust = ($hp-$ophp)*0.015;
			//}
			//if ($hp<=$ophp){
			//	$hpadjust=0;
			//}
			//$hp2 = round($hp*0.005);
			$dam = round((($opanew-$atknew)*0.91)*(($opdnew-$defnew)*0.13));
			if ($dam>0){
				output("You hit your opponent for %s damage",$dam);
				addnav("Continue","runmodule.php?module=arena&op=pvpfight&battle=$battleid");
				$hit=e_rand(1,15);
				switch($hit){
					case 1:
					case 5:
					$bonus = round($ophp*0.07);
					output("Dodging under you opponents guard you execute a second quick attack for %s damage",$bonus);
					break;
					case 2:
					case 3:
					case 4:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					break;
				}
				set_module_pref("fight",1,"arena",$id1);
				set_module_pref("fight",2,"arena",$id2);
				$damnew = $dam+$bonus;
				$hpnew = $ophp-$damnew;
				$sql="UPDATE " . db_prefix("arena") . " SET hp2 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				set_module_pref("lasthit",$dam,"arena",$id1);
				set_module_pref("bonushit",$bonus,"arena",$id1);
			}
			if ($dam==0){
				output("You miss");
				addnav("Continue","runmodule.php?module=arena&op=pvpfight&battle=$battleid");
				set_module_pref("fight",1,"arena",$id1);
				set_module_pref("fight",2,"arena",$id2);
				set_module_pref("lasthit",0,"arena",$id1);
				set_module_pref("bonushit",0,"arena",$id1);
			}
			if ($dam<0){
				output("You are riposted for %s damage",$dam);
				addnav("continue","runmodule.php?module=arena&op=pvpfight&battle=$battleid");
				set_module_pref("fight",1,"arena",$id1);
				set_module_pref("fight",2,"arena",$id2);
				$hit=e_rand(1,15);
				switch($hit){
					case 1:
					case 5:
					$bonus = round($ophp*0.07);
					output("Recoiling from your opponent, you swiftly lift your weapon and catch them offguard for %s damage",$bonus);
					break;
					case 2:
					case 3:
					case 4:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					break;
				}
				$hpnew = $hp+$dam;
				$hpopp = $ophp-$bonus;
				$sql="UPDATE " . db_prefix("arena") . " SET hp1 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				$sql="UPDATE " . db_prefix("arena") . " SET hp2 = '$hpopp' WHERE battleid = '$battleid'";
				db_query($sql);
				set_module_pref("lasthit",$dam,"arena",$id1);
				set_module_pref("bonushit",$bonus,"arena",$id1);
			}
			set_module_pref("min",$timenow);
		}
		if ($id == $id2){
			$timenow = date("Y-m-d H:i:s");
			$atkmin = round($atk*0.1);
			$atkmax = round($atk*0.5);
			$atknew = e_rand($atkmin,$atkmax);
			$defmin = round($def*0.12);
			$defmax = round($def*0.47);
			$defnew = e_rand($defmin,$defmax);
			$opamin = round($opatk*0.1);
			$opamax = round($opatk*0.47);
			$opanew = e_rand($opamin,$opamax);
			$opdmin = round($opdef*0.11);
			$opdmax = round($opdef*0.48);
			$opdnew = e_rand($opdmin,$opdmax);
			//if ($ophp>$hp){
				//$hpadjust = ($ophp-$hp)*0.015;
			//}
			//if ($ophp<=$hp){
			//	$hpadjust=0;
			//}
			//$hp2 = round($ophp*0.005);
			$dam = round((($atknew-$opanew)*0.91)*(($defnew-$opdnew)*0.13));
			
			if ($dam > 0){
				output("You hit your opponent for %s damage",$dam);
				addnav("continue","runmodule.php?module=arena&op=pvpfight&battle=$battleid");
				$hit=e_rand(1,15);
				switch($hit){
					case 1:
					case 5:
					$bonus = round($hp*0.07);
					output("Dodging under you opponents guard you execute a second quick attack for %s damage",$bonus);
					break;
					case 2:
					case 3:
					case 4:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					break;
				}
				set_module_pref("fight",1,"arena",$id2);
				set_module_pref("fight",2,"arena",$id1);
				$damnew = $dam+$bonus;
				$hpnew = $hp-$damnew;
				$sql="UPDATE " . db_prefix("arena") . " SET hp1 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				set_module_pref("lasthit",$dam,"arena",$id2);
				set_module_pref("bonushit",$bonus,"arena",$id2);
			}
			if ($dam==0){
				output("You miss");
				addnav("Continue","runmodule.php?module=arena&op=pvpfight&battle=$battleid");
				set_module_pref("fight",1,"arena",$id2);
				set_module_pref("fight",2,"arena",$id1);
				set_module_pref("lasthit",0,"arena",$id2);
				set_module_pref("bonushit",0,"arena",$id2);
			}
			if ($dam<0){
				output("You are riposted for %s damage",$dam);
				addnav("continue","runmodule.php?module=arena&op=pvpfight&battle=$battleid");
				$hit=e_rand(1,15);
				switch($hit){
					case 1:
					case 5:
					$bonus = round($hp*0.07);
					output("Recoiling from your opponent, you swiftly lift your weapon and catch them offguard for %s damage",$bonus);
					break;
					case 2:
					case 3:
					case 4:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
					case 11:
					case 12:
					case 13:
					case 14:
					case 15:
					break;
				}
				set_module_pref("fight",1,"arena",$id2);
				set_module_pref("fight",2,"arena",$id1);
			
				$hpnew = $ophp+$dam;
				$hpopp = $hp-$bonus;
				$sql="UPDATE " . db_prefix("arena") . " SET hp2 = '$hpnew' WHERE battleid = '$battleid'";
				db_query($sql);
				$sql="UPDATE " . db_prefix("arena") . " SET hp1 = '$hpopp' WHERE battleid = '$battleid'";
				db_query($sql);
				set_module_pref("lasthit",$dam,"arena",$id2);
				set_module_pref("bonushit",$bonus,"arena",$id2);
			}
		set_module_pref("min",$timenow);
		}
	}
	if ($op=="rulespvp"){
		output("`b`c`^RULES AND GUIDELINES FOR %s`0`b`c",$pvp);
		output_notl("`n`n");
		output("`b`&1.  This arena takes into account your current stats upon challenging or accepting a challenge.  A hit point cap is in place.");
		output_notl("`n`n");
		output("`^2.  You can only challenge someone one level below and two levels above you");
		output_notl("`n`n");
		output("`&3.  You cannot actually die here.");
		output_notl("`n`n");
		output("`^4.  There is now a 3 minute time out, if your opponent or you have not made a move within 3 minutes, the player who did not move, will receive a defeat and the other a victory");
		output_notl("`n`n");
		output("`&5.  There are no buffs used in this arena, so please choose your opponents wisely");
		output_notl("`n`n");
		output("`^6.  There is no dragon kill limit on whom you can attack, again choose your opponents wisely.");
		output_notl("`n`n");
		output("`&7.  If you get stuck in here, and petition it, please include details, and your opponents name`b");
		addnav("Return to Arena","runmodule.php?module=arena&op=pvp");
		villagenav();
	}
	if ($op=="magic"){
		//output("`b`c`^This Area Under Construction, please check back later.`b`c");
		//$sql="SELECT * FROM " . db_prefix ("arenastats") . "ORDER BY 'magicwins' DESC LIMIT 1";
		//$res=db_query($sql);
		//$row=db_fetch_assoc($res);
		//$id = $row['id'];
		//$sqln = "SELECT * FROM " . db_prefix("accounts") . "WHERE acctid = '$id'";
		//$resn=db_query($sqln);
		//$rown=db_fetch_assoc($resn);
		//$champ = $rown['name'];
		//output("`b`c`^Current Arena Champion: %s`0`b`c",$champ);
		//output("You approach the %s`0 the ",$magic);
		if (get_module_pref("magicreg")==0){
			addnav("Register","runmodule.php?module=magicarena&op=register");
			output("You don't appear to be registered, and cannot accept nor receive challenges until you do");
			output_notl("`n`n");
		}
		if (get_module_pref("magicreg")<>0){
			addnav("De-register","runmodule.php?module=magicarena&op=deregister");
			addnav("Challenge","runmodule.php?module=magicarena&op=challenge");
			output("You have registered for this arena, and may now challenge someone, or wait to be challenged.  If you no longer wish to do this, please de-register.");
			output_notl("`n`n");
		}
		addnav("Rules and Guidelines","runmodule.php?module=magicarena&op=rules");
		villagenav();
	}
	/*if ($op=="colliseum"){
		output("You approach the %s`0 ",$colliseum);
		output_notl("`n`n");
		$clanid = $session['user']['clanid'];
		$clanrank=$session['user']['clanrank'];
		if ($clanid==0){
			output("You are not a member of a clan, if you wish to participate in the Colliseum you should join one");
		}
		if ($clanid<>0){
			if ($session['user']['clanrank'] == 20 && get_module_objpref("clans", $clanid, "colliseum")==1){
				addnav("Register a Battle","colliseum.php?op=regbattle");
				addnav("Check battles due to Start","colliseum.php?op=start");
				addnav("Skip Player","colliseum.php?op=skip");
			}	
			if ($clanrank==30){
				addnav("Register a Battle","colliseum.php?op=regbattle");
				addnav("Check battles due to Start","colliseum.php?op=start");
				addnav("Skip Player","colliseum.php?op=skip");
			}
			if ($clanrank>0){
				addnav("Sign up for a Battle","colliseum.php?op=signup");
				addnav("Rules and Guidelines","colliseum.php?op=rules");
				
			}	
		}
		villagenav();
	}
	*/if ($op=="watchpvp"){
		output("`b`c`^This Area Under Construction, please check back later.`b`c");
		villagenav();
	}
	if ($op=="watchmagic"){
		output("`b`c`^This Area Under Construction, please check back later.`b`c");
		villagenav();
	}
	page_footer();

?>