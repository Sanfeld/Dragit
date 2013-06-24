<?php
function oceanquest_pilinoria(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$allprefs=unserialize(get_module_pref('allprefs'));
	page_header("Pilinoria");
	if ($op2=="landing"){
		output("`c`b`^Pilinoria`c`b`n`7");
		$allprefs['shore']=1;
		$allprefs['pilinoria']=1;
		if ($allprefs['sorcerer']==1){
			if ($session['user']['level']<get_module_setting("tradelevel")) output("`2'Unfortunately, you can't trade until you've reached level `^%s`2. Please come back then.'",get_module_setting("tradelevel"));
			else{
				$tradeitem=$allprefs['tradeitem'];
				if ($tradeitem>=6){
					output("You're only able to trade once per day and you've already made your trade for today.");
				}elseif ($allprefs['dktrades']>get_module_setting("tradeperdk")){
					output("You've already made as many trades as you can this dragon kill.");
				}elseif ($tradeitem>=1){
					$itemarray=array("",get_module_setting("item1"),get_module_setting("item2"),get_module_setting("item3"),get_module_setting("item4"),get_module_setting("item5"));
					$colorarray=array("","`5","`1","`6","`2","`4");
					output("You still have your %s%s`7 from your last trip here.  You'll need to sell that in your kingdom before you can buy anything else to trade!",$colorarray[$tradeitem],$itemarray[$tradeitem]);
				}else{
					output("You arrive at the Pilinoria Pier ready for trade.  It's marvelous how beautiful the country is now.");
					output("You have some time to look around at the stalls and see what you may be able to purchase for trade.");
					$cost1=get_module_setting("cost1");
					$cost2=get_module_setting("cost2");
					$cost3=get_module_setting("cost3");
					$cost4=get_module_setting("cost4");
					$cost5=get_module_setting("cost5");
					addnav("Trade");
					if ($cost1>0){
						$item1=get_module_setting("item1");
						output("`n`n`%A beautiful stall set up on the edge of the dock has many items that look inviting. You examine each one carefully trying to calculate which one will bring the best trade value.");
						output("Finally, you find an impressive `5%s`% and ask the stall owner about it.",$item1);
						output("`5'Ah, very nice.  Well, I can't let that go for anything less than `^%s gold`5,'`% she says.",round($cost1*1.4));
						if ($session['user']['gold']<$cost1) output("You don't have anywhere near that amount of gold.");
						else{
							output("After a little haggling, you agree on a more reasonable price of `^%s gold`%.",$cost1);
							addnav(array("Purchase `5%s",$item1),"runmodule.php?module=oceanquest&op=pilinoria&op2=trade&op3=1");	
						}
					}
					if ($cost2>0){
						$item2=get_module_setting("item2");
						output("`n`n`!A canvas coated stall greets you and the owner invites you in.  Instantly you notice a `1%s`! that would be very desirable in your kingdom.",$item2);
						output("Without negotiation, you offer `^%s gold`! and the owner readily accepts your offer.",$cost2);
						if ($session['user']['gold']<$cost2) output("You suddenly realize you don't have enough and apologize.");
						else addnav(array("Purchase `1%s",$item2),"runmodule.php?module=oceanquest&op=pilinoria&op2=trade&op3=2");	
					}
					if ($cost3>0){
						$item3=get_module_setting("item3");
						output("`n`n`^In a small tent, a `6%s`^ grabs your attention.",$item3);
						output("Before you have a chance to say anything the owner sees your interest.  `6'Low price guaranty. only `^%s gold`6.'",$cost3);
						output("`^Knowing a good deal when you see one, you realize that this will fetch a good price back home.");
						if ($session['user']['gold']<$cost3) output("Unfortunately, you didn't bring enough gold to buy it.  You shrug and move on.");
						else addnav(array("Purchase `6%s",$item3),"runmodule.php?module=oceanquest&op=pilinoria&op2=trade&op3=3");	
					}
					if ($cost4>0){
						$item4=get_module_setting("item4");
						output("`n`n`@You don't even get to take a couple of steps before a strange merchant tugs your sleeve.");
						output("`2'I have exactly what you want. This is a one-of-a-kind `@%s`2. I can give it to you for the low low price of only `^%s gold`2.'",$item4,$cost4*4);
						output("`@You have to stifle a laugh at such an outrageous price.  `#'How about `^%s gold`#?'`@ you counter.",$cost4);
						output("`2'Deal!'`@ exclaims the merchant.");
						if ($session['user']['gold']<$cost4) output("`#'If I had that much gold, I'd buy it!'`@ you tell him before moving on.");
						else addnav(array("Purchase `2%s",$item4),"runmodule.php?module=oceanquest&op=pilinoria&op2=trade&op3=4");	
					}
					if ($cost5>0){
						$item5=get_module_setting("item5");
						output("`n`n`\$A dark little stall almost escapes your notice.  However, being the savvy trader, you don't let anything slip past you.");
						output("A particularly nice `4%s `\$catches your eye and you ask the stallkeeper about the price.",$item5);
						output("`4'It's magic, you know.  Powerful magic.  I can't let it go for less than `^%s gold`4.  That's a very fair price,'`\$ explains the owner.",$cost5);
						output("You're about to ask what the magic is, but decide that it's not your problem.");
						if ($session['user']['gold']<$cost5) output("`#'I would purchase it if I could afford it.  But I am a little short on funds today,'`\$ you explain.  The stallkeeper shrugs and you leave.");
						else addnav(array("Purchase `4%s",$item5),"runmodule.php?module=oceanquest&op=pilinoria&op2=trade&op3=5");	
					}
					$dktrades=$allprefs['dktrades'];
					$tradeperdk=get_module_setting("tradeperdk");
					output("`n`n`c`7You may only trade `^%s`7 %s per dragonkill.",$tradeperdk,translate_inline($tradeperdk>1?"times":"time"));
					if ($dktrades>1) output("You've traded `^%s`7 %s so far.`c",$dktrades,translate_inline($dktrades>1?"times":"time"));
					else output("You haven't made any trades yet.`c");
				}
			}
		}else{
			if($allprefs['freed']==1){
				output("You notice that Pilinoria is shaping up. The land is looking more beautiful and it appears that cautious preparations are starting to open trade again.");
				output("`n`n`2'Until that Sorcerer in the south is killed, we still have to be very careful.  We cannot trade until that occurs,'`7 explains one of the dock workers.");
			}else{
				if ($allprefs['landpil']==""||$allprefs['landpil']==0){
					$allprefs['landpil']=1;
					output("You step off the `i`^Luckstar`i`7 to look around Pilinoria.  You try to recall the stories about it from your youth.");
					output("Memories of the artistry of the land washes over you.  You recall the beautiful glassware, amazing carved sculptures, and paintings of grand scenery.");
					output("`n`nAround you is a different world.  Bland, simple, and primitive.");
					output("`n`nOff in the distance you see a dull grey castle with simple red banners flying in the turrets. Could this really be the fantastic land of Pilinoria?");
					output("`n`nIt's time to travel to the castle to find out what is wrong.`n");
				}else{
					output("There's no more trade to be established here.  You must figure out a way to free the kingdom from the current oppression before open communication can occur between your kingdom and Pilinoria.`n");
				}
			}
			if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0){
				if ($allprefs['sorcerer']>=1) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria3.gif></td></tr></table></center><br>");
				elseif ($allprefs['freed']>=1)rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria2.gif></td></tr></table></center><br>");
				else rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria1.gif></td></tr></table></center><br>");
			}
			addnav("Options");
			addnav("Travel to the Castle","runmodule.php?module=oceanquest&op=pilinoria&op2=castle");
			addnav("Go South to the Forest","runmodule.php?module=oceanquest&op=pilinoria&op2=tovillage");
		}
		addnav("Return");
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailing");
		else addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailinga");
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op2=="trade"){
		output("`b`c`^Pilinoria Trade`b`c`n`7");
		$itemarray=array("",get_module_setting("item1"),get_module_setting("item2"),get_module_setting("item3"),get_module_setting("item4"),get_module_setting("item5"));
		$colorarray=array("","`5","`1","`6","`2","`4");
		$cost=get_module_setting("cost".$op3);
		$session['user']['gold']-=$cost;
		$allprefs['tradeitem']=$op3;
		$allprefs['dktrades']++;
		output("You hand over `^%s gold`7 and take the %s%s`7 from the shoppkeeper.  This was a good trade.",$cost,$colorarray[$op3],$itemarray[$op3]);
		addnav("Return");
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailing");
		else addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailinga");
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op2=="castle"){
		output("`c`b`^Castle Pilinoria`c`b`n`7");
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Approach the King","runmodule.php?module=oceanquest&op=throne");
		else addnav("Approach the King","runmodule.php?module=oceanquest&op=thronea");
		if ($allprefs['sorcerer']==1){
			blocknav("runmodule.php?module=oceanquest&op=throne");
			blocknav("runmodule.php?module=oceanquest&op=thronea");
			output("The royal guards thank you for your service to helping save the kingdom.");
			if ($session['user']['level']>=get_module_setting("tradelevel")) output("They encourage you to travel to the docks to start trading.");
			else output("They encourage you to return once you've reached level `^%s`7 so that you may begin trading.",get_module_setting("tradelevel"));
		}elseif ($allprefs['freed']==1){
			output("One of the royal guards approaches you. `5'The king asks how you're doing against the evil sorcerer in the south.'");
			output("`n`n`7Not having much of an answer, you don't respond.`n");
		}elseif ($allprefs['freed']==2){
			output("One of the royal guards approaches you.  `5'The king is anxious to talk to you.  Please enter.'`n");
		}else{
			output("You arrive at the castle and are herded behind a line of envoys from various kingdoms.  After a short discussion, you realize that they've been waiting for days to speak to the king.");
			output("`3'Pilinoria is all about `4red tape`3 now.  You can't get anything done.  There haven't been any new trade routes established in a long time,'`7 explains the Envoy from Dartanio.");
			output("`n`nDisappointed by this report, you decide it's still worth your time to approach the king to enquire further.`n");
			if ($allprefs['mystic']==1 && ($allprefs['freed']==""||$allprefs['freed']==0)) addnav("Go to the Servant's Quarters","runmodule.php?module=oceanquest&op=pilinoria&op2=servant");
		}
		addnav("Go back to the Pier","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
		if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0){
			if ($allprefs['sorcerer']>=1) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria3.gif></td></tr></table></center><br>");
			elseif ($allprefs['freed']>=1)rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria2.gif></td></tr></table></center><br>");
			else rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria1.gif></td></tr></table></center><br>");
		}
	}
	if ($op2=="servant"){
		page_header("Pilinoria Castle");
		output("`^`c`bThe Servant's Entrance`b`c`n`7");
		if ($allprefs['furniture']>3){
			output("Having just destroyed some of the King's bedroom furniture, you realize it's probably safer if you don't go into his bedroom for a day.  You need to lay low!`n");
			addnav("Go back to the Pier","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
		}elseif ($allprefs['pass']>=1){
			output("`7Using the polymorph spell you change into one of the servants.");
			output("`7You remember to put on your Badge that you found off the body of the Pilinoria Soldier.");
			if ($allprefs['pass']==1) output("Don't you remember? You found it on the body while fishing on the `i`&Corinth`i`7? Well, luckily I remember!");
			output("`n`nNobody takes a second look at you as you travel to the King's Bedroom.`n");
			addnav("Go back to the Pier","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
			addnav("Continue to the King's Bedroom","runmodule.php?module=oceanquest&op=pilinoria&op2=kingsroom");
		}else{
			output("`7Using the polymorph spell you change into one of the servants.");
			output("As you try to enter through the back, one of the soldier's catches you sneaking around.");
			output("You notice that it's quite a secluded area.  Perhaps if you could 'borrow' his badge you wouldn't arouse suspicion.`n");
			addnav("Fight the Soldier","runmodule.php?module=oceanquest&op=pilinoriasoldier");
		}
		if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0){
			if ($allprefs['sorcerer']>=1) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria3.gif></td></tr></table></center><br>");
			if ($allprefs['freed']>=1)rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria2.gif></td></tr></table></center><br>");
			else rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria1.gif></td></tr></table></center><br>");
		}
	}
	if ($op2=="kingsroom"){
		page_header("Pilinoria Castle");
		output("`^`c`bThe King's Bedroom`b`c`7`n");
		output("You sneak into the king's bedroom.  Your mission:  Free the king by destroying the magic furniture keeping him captive.`n`n");
		$furniture=$allprefs['furniture'];
		if ($furniture=="") $furniture=0;
		if ($furniture==3){
			output("The chair and the dresser have been destroyed. This place is looking like a mess.  Maybe it's time to destroy the bed.");
		}elseif ($furniture==2){
			output("You look at the table you destroyed and have a pinge of regret. Oh well.  There's still a bed and a chair to destory.");
		}elseif ($furniture==1){
			output("You see a shattered chair and shrug.  Oh well.  There's still a bed and a table to destroy.  One of these has got to break the spell!");
		}else{
			output("You notice 3 pieces of furniture that could be holding the king captive.  On the table are several documents.  Is one glowing with the aura of a magic spell? Over on the chair are some shiny gems with an eerie glow. What is causing that?");
			output("Finally, you see the bed with a whispy white canopy.  For some reason the material has a phosphorescent glow.");
			output("`n`nYou need to destroy one of the pieces of furniture but there's no way to tell which one.  You better hurry up, because it sounds like there are guards coming!");
		}
		addnav("Furniture");
		if ($furniture==1 || $furniture==0) addnav("Destroy the Table","runmodule.php?module=oceanquest&op=pilinoria&op2=table&op3=$furniture");
		if ($furniture==2 || $furniture==0) addnav("Destroy the Chair","runmodule.php?module=oceanquest&op=pilinoria&op2=chair&op3=$furniture");
		addnav("Destroy the Bed","runmodule.php?module=oceanquest&op=pilinoria&op2=bed&op3=$furniture");
		addnav("Other");
		addnav("Return Downstairs","runmodule.php?module=oceanquest&op=pilinoria&op2=servant");
	}
	if ($op2=="bed"){
		page_header("Pilinoria Castle");
		output("`^`c`bThe King's Bedroom`b`c`7`n");
		output("Surely this must be the cause of the problem! You grabe your %s`7 and make short work of the bed!",$session['user']['weapon']);
		output("`n`nSuddenly, a bright light explodes from the bed knocking you backwards.  You did it!");
		$allprefs['freed']=2;
		set_module_pref('allprefs',serialize($allprefs));
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Continue","runmodule.php?module=oceanquest&op=throne");
		else addnav("Continue","runmodule.php?module=oceanquest&op=thronea");
	}
	if ($op2=="chair" || $op2=="table"){
		page_header("Pilinoria Castle");
		output("`^`c`bThe King's Bedroom`b`c`7`n");
		if ($op2=="chair"){
			$destroyed=translate_inline("chair");
			if ($op3==0) $allprefs['furniture']=4;
			else $allprefs['furniture']=6;
		}else{
			$destroyed=translate_inline("table");
			if ($op3==0) $allprefs['furniture']=5;
			else $allprefs['furniture']=6;
		}
		set_module_pref('allprefs',serialize($allprefs));
		output("You chop the %s to pieces, feeling a little adrenaline rush from the destruction.  It's destroyed!",$destroyed);
		output("`n`nYou hear loud trumpets. It must be the celebration starting! But then you hear the sound of rushing guards.  Oh no! You destroyed the wrong piece of furniture.");
		output("`n`nYou better get out of here before they lock you in the dungeon.  You'll need to lay low for a day until this mistake is forgotten.");
		addnav("Return Downstairs","runmodule.php?module=oceanquest&op=pilinoria&op2=servant");
	}
	if ($op2=="tovillage"){
		page_header("Pilinoria Forest");
		output("`7`c`bThe Forest`b`c");
		output("The Forest, home to evil creatures and evildoers of all sorts.");
		output("`n`nThe thick foliage of the forest restricts your view to only a few yards in most places. The paths would be imperceptible except for your trained eye. You move as silently as a soft breeze across the thick moss covering the ground, wary to avoid stepping on a twig or any of the numerous pieces of bleached bone that populate the forest floor, lest you betray your presence to one of the vile beasts that wander the forest.`n");
		addnav("Heal");
		addnav("Healer's Hut","runmodule.php?module=oceanquest&op=pilinoria&op2=healer");
		addnav("Fight");
		addnav("Look for Something to Kill","runmodule.php?module=oceanquest&op=pilinoria&op2=forest");
		if (is_module_active("outhouse")){
			addnav("Outhouse");
			addnav("The Outhouse","runmodule.php?module=oceanquest&op=pilinoria&op2=outhouse");
		}
		addnav("Other");
		addnav("North to the Pier","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
		addnav("V?(V) South to Village","runmodule.php?module=oceanquest&op=pilinoria&op2=village");
		if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0){
			if ($allprefs['sorcerer']>=1) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria3.gif></td></tr></table></center><br>");
			elseif ($allprefs['freed']>=1)rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria2.gif></td></tr></table></center><br>");
			else rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria1.gif></td></tr></table></center><br>");
		}
	}
	if ($op2=="forest"){
		page_header("Pilinoria Forest");
		output("`b`c`\$~ ~ ~ Fight ~ ~ ~`b`c`n");
		output("`@You have encountered`^ Nothing worth fighting`@!!");
		output("`n`nIt turns out there's nothing in the forests of Pilinoria worthy of fighting.`n");
		addnav("Heal");
		addnav("Healer's Hut","runmodule.php?module=oceanquest&op=pilinoria&op2=healer");
		if (is_module_active("outhouse")){
			addnav("Outhouse");
			addnav("The Outhouse","runmodule.php?module=oceanquest&op=pilinoria&op2=outhouse");
		}
		addnav("Other");
		addnav("V?(V) South to Village","runmodule.php?module=oceanquest&op=pilinoria&op2=village");
		addnav("North to Pier","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
		if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0){
			if ($allprefs['sorcerer']>=1) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria3.gif></td></tr></table></center><br>");
			elseif ($allprefs['freed']>=1)rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria2.gif></td></tr></table></center><br>");
			else rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria1.gif></td></tr></table></center><br>");
		}
	}
	if ($op2=="healer"){
		//Directly taken from Core Healer's Hut Eric Stevens
		page_header("Pilinoria Healer's Hut");
		output("`#`c`bHealer's Hut`b`c`7`n");
		output("`3You duck into the small smoke-filled grass hut. The pungent aroma makes you cough, attracting the attention of a grizzled old person that does a remarkable job of reminding you of a rock, which probably explains why you didn't notice them until now.");
		output("Couldn't be your failure as a warrior. Nope, definitely not.");
		$loglev = log($session['user']['level']);
		$cost = round((($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10))/2);
		if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']){
			addnav("Potions");
			addnav("`^Complete Healing","runmodule.php?module=oceanquest&op=pilinoria&op2=completeheal&op3=$cost");
			output("\"`6See you, I do.  Before you did see me, I think, hmm?`3\" the old thing remarks.");
			output("\"`6Know you, I do; healing you seek.  Willing to heal am I, but only if willing to pay are you.`3\"`n`n");
			output("\"`5Uh, um.  How much?`3\" you ask, ready to be rid of the smelly old thing.`n`n");
			output("The old being thumps your ribs with a gnarly staff.  \"`6For you... `$`b%s`b`6 gold pieces for a complete heal!!`3\" it says as it bends over and pulls a clay vial from behind a pile of skulls sitting in the corner. An no bargain potions here!`n", $cost);
		}else{
			output("`3The old creature grunts as it looks your way. \"`6Need a potion, you do not.  Wonder why you bother me, I do.`3\" says the hideous thing.");
			output("The aroma of its breath makes you wish you hadn't come in here in the first place.  You think you had best leave.`n");
		}
		addnav("Fight");
		addnav("Look for Something to Kill","runmodule.php?module=oceanquest&op=pilinoria&op2=forest");
		if (is_module_active("outhouse")){
			addnav("Outhouse");
			addnav("The Outhouse","runmodule.php?module=oceanquest&op=pilinoria&op2=outhouse");
		}
		addnav("Continue");
		addnav("V?(V) South to Village","runmodule.php?module=oceanquest&op=pilinoria&op2=village");
		addnav("North to Pier","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
		if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0){
			if ($allprefs['sorcerer']>=1) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria3.gif></td></tr></table></center><br>");
			elseif ($allprefs['freed']>=1)rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria2.gif></td></tr></table></center><br>");
			else rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria1.gif></td></tr></table></center><br>");
		}
	}
	if ($op2=="completeheal"){
		page_header("Pilinoria Healer's Hut");
		output("`#`c`bHealer's Hut`b`c`7`n");
		if ($session['user']['gold']>=$op3){
			output("`3With a grimace, you up-end the potion the creature hands you, and despite the foul flavor, you feel a warmth spreading through your veins as your muscles knit back together.`n");
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			$session['user']['gold']-=$op3;
		}else{
			output("`3The old creature pierces you with a gaze hard and cruel.");
			output("Your lightning quick reflexes enable you to dodge the blow from its gnarled staff.");
			output("Perhaps you should get some more money before you attempt to engage in local commerce.`n`n");
			output("You recall that the creature had asked for `b`\$%s`3`b gold.`n", $op3);
		}
		addnav("Fight");
		addnav("Look for Something to Kill","runmodule.php?module=oceanquest&op=pilinoria&op2=forest");
		if (is_module_active("outhouse")){
			addnav("Outhouse");
			addnav("The Outhouse","runmodule.php?module=oceanquest&op=pilinoria&op2=outhouse");
		}
		addnav("Continue");
		addnav("V?(V) South to Village","runmodule.php?module=oceanquest&op=pilinoria&op2=village");
		addnav("North to Pier","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
		if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0){
			if ($allprefs['sorcerer']>=1) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria3.gif></td></tr></table></center><br>");
			elseif ($allprefs['freed']>=1)rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria2.gif></td></tr></table></center><br>");
			else rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria1.gif></td></tr></table></center><br>");
		}
	}
	if ($op2=="outhouse"){
		//Almost Directly from Outhouse by John Collins from Core
		page_header("Pilinoria Outhouse");
		output("`#`c`bOuthouse`b`c`7`n");
		if ($allprefs['outhouse']==1){
			output("`2You really don't have anything left to relieve today!");
		}else{
			$allprefs['outhouse']=1;
			set_module_pref('allprefs',serialize($allprefs));
			output("`2The smell is so strong your eyes tear up and your nose hair curls!`n`n");
			output("After blowing his nose with it, the Toilet Paper Gnome gives you 1 sheet of single-ply TP to use.");
			output("After looking at the stuff covering his hands, you think you might not want to use it.`n`n");
			output("While %s over the big hole in the middle of the room with the TP Gnome observing you closely, you almost slip in.`n`n", translate_inline($session['user']['sex']?"squatting":"standing"));
			output("You go ahead and take care of business as fast as you can; you can only hold your breath so long.`n");
			output("`nYou quickly wash your hands and leave.`n");
			if (is_module_active("drinks")){
				$args = array(
					'soberval'=>0.9,
					'sobermsg'=>"`&Leaving the outhouse, you feel a little more sober.`n",
					'schema'=>"module-outhouse",
				);
				modulehook("soberup", $args);
			}
		}
		addnav("Fight");
		addnav("Look for Something to Kill","runmodule.php?module=oceanquest&op=pilinoria&op2=forest");
		addnav("Heal");
		addnav("Healer's Hut","runmodule.php?module=oceanquest&op=pilinoria&op2=healer");
		addnav("Other");
		addnav("V?(V) South to Village","runmodule.php?module=oceanquest&op=pilinoria&op2=village");
		addnav("North to Pier","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
		if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0){
			if ($allprefs['sorcerer']>=1) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria3.gif></td></tr></table></center><br>");
			elseif ($allprefs['freed']>=1)rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria2.gif></td></tr></table></center><br>");
			else rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria1.gif></td></tr></table></center><br>");
		}
	}
	if ($op2=="village"){
		page_header("Pilinoria Village");
		output("`^`c`bVillage`b`c`7`n");
		addnav("Options");
		addnav("North To the Forest","runmodule.php?module=oceanquest&op=pilinoria&op2=tovillage");
		if ($allprefs['sorcerer']==1){
			output("The villagers are busy preparing items for trade and don't have much time to chat.  They give you a quick `3'thank you'`7 and continue on their way.");
		}elseif ($allprefs['sorcerer']==2){
			output("You see an obvious change in the village as the land to the south is recovered for farm land.");
			output("The villagers are busy preparing items for trade and don't have much time to chat.  They give you a quick `3'thank you'`7 and continue on their way.");
		}elseif ($allprefs['freed']==1){
			output("You enter the village and it's alive with activity.  Having accomplished your goal of freeing the king, all the villagers are busy working to create products for trade for after you kill the sorcerer.");
			output("However, the dark shadow of the fortress to the south still prevents you from succeeding in freeing the kingdom completely.`n");
			addnav("South To the Fortress of Xavicon","runmodule.php?module=oceanquest&op=pilinoria&op2=fortress");
		}else{
			output("Eventually you make it to the Village in southern Pilinoria. The poverty here is unbelievable. Nothing stronger than a grass huts stands here. In the smallest of storms this village would be decimated.");
			output("`n`nYou realize there's really nothing to do here except visit the one hut in the corner with a plaque that reads `&'Mystic'`7.`n");
			addnav("Visit the Mystic","runmodule.php?module=oceanquest&op=pilinoria&op2=mystic");
		}
		if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0){
			if ($allprefs['sorcerer']>=1) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria3.gif></td></tr></table></center><br>");
			elseif ($allprefs['freed']>=1)rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria2.gif></td></tr></table></center><br>");
			else rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/pilinoria1.gif></td></tr></table></center><br>");
		}
	}
	if ($op2=="mystic"){
		page_header("Pilinoria Village");
		output("`^`c`bThe Mystic`b`c`7`n`n");
		if ($allprefs['mystic']==1){
			output("The Mystic's Hut is empty. You hope your return hasn't caused Xavicon to kill her.");
		}else{
			if ($allprefs['magicscroll']==1){
				output("Before you get a chance to speak, an old lady speaks.");
				output("`5'You must break the spell held over the King.  The Sorcerer Xavicon in the fortress to the south is the cause of the gloom in the country. You must free the king.' `7 The Mystic pauses.");
				output("`5'Did you find the polymorph spell?'`7 she asks. You hand her the scroll and she teaches you the how to use it to pass as a servant of the king. `5'You can go to the castle now and enter through the back.'");
				output("`7She hands you a map that points to the servant's entrance.  You tuck it safely in your pack.  `5'You must make it to the king's bedroom and destroy the magic furniture that holds him prisoner.");
				output("I do not know which particular piece of furniture it is though.  That will be up for you to figure out.  Good luck.  Do not come back here for you'll draw the suspicion of Xavicon.'");
				output("`7`n`nIt looks your mission is clear.");
				$allprefs['mystic']=1;
				set_module_pref('allprefs',serialize($allprefs));
			}else{
				output("You tell the Mystic that the king refuses to hear your petition.");
				output("`5'And he won't hear it until you free him from a spell controlling him.  However, there's no way you're going to be able to free him looking like you do.  You'll need to find some way to disguise yourself.");
				output("I know there's a magic spell hidden in the cave in the South Island that may be able to help you. Come back to me once you have found it.'");
			}
		}
		addnav("North To the Forest","runmodule.php?module=oceanquest&op=pilinoria&op2=tovillage");
	}
	if ($op2=="fortress"){
		page_header("Xavicon's Fortress");
		output("`^`c`bFortress Entrance`b`c`7`n");
		if ($allprefs['sorcerer']==2){
			output("As you leave the fortress you suddenly hear a huge crashing sound.  You've made it out safely! The King's Personal Guard is outside waiting to escort you to speak with the king.");
			if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Meet with the King","runmodule.php?module=oceanquest&op=throne&op2=king");
			else addnav("Meet with the King","runmodule.php?module=oceanquest&op=thronea&op2=king");
		}elseif ($allprefs['reddragon']==""||$allprefs['reddragon']==0){
			output("Ready for anything, you go to the entrance of the fortress.");
			output("At least, I hope you're ready for anything, because you're attacked by the fortress guard; a `\$Giant Red Dragon`7!!!");
			addnav("Red Dragon Fight","runmodule.php?module=oceanquest&op=reddragon");
		}else{
			output("Having defeated the `\$Red Dragon`7 that was guarding the entrance, you can now enter the fortress.");
			output("`n`nHowever, that's easier said than done because now you have a chance to look around.");
			output("`n`nYou look `)up`7 and notice the walls are smooth and imposing, rising high above you.  The material is blood red; a color you hope comes from a peculiar dye rather than from those that peculiarily died.");
			output("`n`nTo your `!left`7 is a huge moat, impassable without fail due to the dangerous sea serpent, poisonous fish, and guard water troll.");
			output("To your `\$right`7 is a flame fed with primordial oil, stoked by a fire drake, and maintained by demon spawn.");
			output("`n`nStraight in `@front`7 of you is... err... well, actually, there's a very nice path right in front of you. It's landscaped with some very nice shrubbery, with the one on the left only slightly higher than the one on the right, giving a nice two-level effect with a little path running down the middle. You see the remains of a rotting herring by the side of the path.");
			output("`n`nWell, time to meet your fate!");
			addnav("Xavicon Encounter","runmodule.php?module=oceanquest&op=pilinoria&op2=xaviconencounter");
			addnav("RETREAT!!!","runmodule.php?module=oceanquest&op=pilinoria&op2=village");
		}
	}
	if ($op2=="xaviconencounter"){
		page_header("Xavicon's Fortress");
		output("`^`c`bThe Fortress`b`c`7`n");
		output("Worried that you'll have to walk down a hallway to the throne room like you do every time you go to the King's castle, you brace yourself and enter the fortress.");
		output("`n`nThere's no hallway. That's `@Good`7.");
		output("`n`nThere's Xavicon. That's `\$Bad`7.");
		output("`n`nHe looks unarmed. That's `@Good`7.");
		output("`n`nHe doesn't need a weapon because he's a sorcerer. That's `\$Bad`7.");
		output("`n`n`\$'Get out of my Fortress!'`7 he yells. `\$'You have ruined my plan to take over the kingdom and the only price that I will accept as repayment is your head.'`7");
		output("`n`nYou have a feeling that this probably won't end in a reasonable negotiation allowing free trade and laissez faire economics.  That's too bad for Xavicon.  `#'It's your turn to die, Evil sorcerer!'`7 you yell.");
		addnav("Xavicon Fight","runmodule.php?module=oceanquest&op=xavicon");
	}
	if ($op2=="endgame1"){
		page_header("Xavicon's Fortress");
		output("`^`c`bThe Fall of Xavicon`b`c`7`n");
		output("You've defeated Xavicon! Congratulations!");
		$expmultiply = e_rand(90,120);
		$expbonus=$session['user']['dragonkills']*10;
		$expgain =($session['user']['level']*$expmultiply+$expbonus);
		$session['user']['experience']+=$expgain;
		$allprefs['sorcerer']=2;
		set_module_setting("freepil",$session['user']['name']);
		set_module_pref('allprefs',serialize($allprefs));
		output("`n`n`#You have gained `7%s `#experience.`n`n",$expgain);
		output("As you absorb the glory and excitement, you start to hear the fortress start to collapse.");
		output("You're only going to have time to grab one reward!`n`n");
		output("`c`&Potion to Gain 10 Charm`c");
		addnav("Xavicon's Treasure");
		addnav("Take the `&Charm","runmodule.php?module=oceanquest&op=pilinoria&op2=endgame2&op3=1");
		if (get_module_setting("xgold")>0){
			output("`c`^%s Gold`7`c",get_module_setting("xgold"));
			addnav("Take the Gold","runmodule.php?module=oceanquest&op=pilinoria&op2=endgame2&op3=2");
		}
		if (get_module_setting("xgems")>0){
			output("`c`%%s Gems`7`c",get_module_setting("xgems"));
			addnav("Take the Gems","runmodule.php?module=oceanquest&op=pilinoria&op2=endgame2&op3=3");
		}
		if (get_module_setting("xweapon")==1){
			output("`c`4Xavicon's Wand `3(Attack 20)`7`c");
			addnav("Take the Wand","runmodule.php?module=oceanquest&op=pilinoria&op2=endgame2&op3=4");
		}
		if (get_module_setting("xarmor")==1){
			output("`c`5Xavicon's Magic Robe `3(Defense 20)`7`c");
			addnav("Take the Robe","runmodule.php?module=oceanquest&op=pilinoria&op2=endgame2&op3=5");
		}
	}
	if ($op2=="endgame2"){
		page_header("Xavicon's Fortress");
		output("`^`c`bThe Fall of Xavicon`b`c`7`n");
		if ($op3==1){
			output("You quickly grab the charm potion and drink it down.  You look ~ Mah-velous ~ !!");
			$session['user']['charm']+=10;
		}elseif ($op3==2){
			output("You grab the gold without hesitation.");
			$session['user']['gold']+=get_module_setting("xgold");
		}elseif ($op3==3){
			output("You grab the gems without hesitation.");
			$session['user']['gems']+=get_module_setting("xgems");
		}elseif ($op3==4){
			output("You quickly toss aside your %s`7 and grab the `#Wand of `\$Xavicon`7.  The power surges through you!",$session['user']['weapon']);
			$session['user']['attack']-=$session['user']['weapondmg'];
			$session['user']['weapon']="`#Wand of `\$Xavicon`0";
			$session['user']['weaponvalue']=5000;
			$session['user']['weapondmg'] =20;
			$session['user']['attack']+=20;
		}else{
			output("You quickly toss aside your %s`7 and grab the `#Robe of `\$Xavicon`7.  The power surges through you!",$session['user']['armor']);
			$session['user']['defense']-=$session['user']['armordef'];
			$session['user']['armor']="`#Robe of `\$Xavicon`0";
			$session['user']['armorvalue']=5000;
			$session['user']['armordef'] = 20;
			$session['user']['defense']+=20;
		}
		output("`n`nThe walls start to collapse around you.  Time to leave!");
		addnav("Leave the Fortress","runmodule.php?module=oceanquest&op=pilinoria&op2=fortress");
	}
}
?>