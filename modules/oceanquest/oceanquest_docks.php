<?php
function oceanquest_docks(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$op4 = httpget('op4');
	$allprefs=unserialize(get_module_pref('allprefs'));
	page_header("The Docks");
	if ($op2=="enter"){
		if ($allprefs['enter']=="" || $allprefs['enter']==0) $allprefs['enter']=1;
		output("`b`c`^The Docks`b`c`n`7");
		output("You head down to the docks to see what adventures await you there.");
		output("Taking a look around, you notice several locations of interest.  Where would you like to go from here?`n`n`c");
		output("`QBait and Tackle Shack`n`qGo Fishing on the Dock`n`#Seaside Pub`n`3Talk to the Sailors`n`\$International Trade Store`c`n");
		output("`n`7You also notice several ships coming in and leaving the docks:`n`n`c");
		addnav("The Docks");
		addnav("`QBait and Tackle Shack","runmodule.php?module=oceanquest&op=docks&op2=baittackle");
		addnav("`qGo Fishing on the Dock","runmodule.php?module=oceanquest&op=docks&op2=fishing");
		addnav("`#Seaside Pub","runmodule.php?module=oceanquest&op=docks&op2=seasidepub");
		addnav("`3Talk to the Sailors","runmodule.php?module=oceanquest&op=docks&op2=talktosailors");
		addnav("`\$International Trade Store","runmodule.php?module=oceanquest&op=docks&op2=tradestore");
		addnav("Ships");
		$albaniarand=e_rand(1,10);
		$rhodandorand=e_rand(1,20);
		$freestonerand=e_rand(1,15);
		if ($albaniarand>1){
			output("`4`iAlbania`i`n");
			addnav("`4`iAlbania`i","runmodule.php?module=oceanquest&op=docks&op2=albania");		
		}
		if ($rhodandorand>1){
			output("`@`iRhodando`i`n");
			addnav("`@`iRhodando`i","runmodule.php?module=oceanquest&op=docks&op2=rhodando");
		}
		output("`&`iCorinth`i`n");
		addnav("`&`iCorinth`i","runmodule.php?module=oceanquest&op=docks&op2=corinth");//Fishing Vessel
		if ($freestonerand>1){
			output("`6`iFree Stone`i`n");
			addnav("`6`iFree Stone`i","runmodule.php?module=oceanquest&op=docks&op2=freestone");		
		}
		if (($allprefs['notary']==1 && $allprefs['lscount']>0) || $allprefs['luckstarsail']==1){
			output("`n`c`7You notice that the `^`iLuckstar`i`7 isn't in port. It will be back in `^%s`7 %s.`c",$allprefs['lscount'],translate_inline($allprefs['lscount']>1?"days":"day"));
		}else{
			output("`^`iLuckstar`i`n");
			addnav("`^`iLuckstar`i","runmodule.php?module=oceanquest&op=docks&op2=gotoluckstar");
		}
		if ($allprefs['tradeitem']>=1 && $allprefs['tradeitem']<6) output("`n`7Don't forget to stop at the `\$International Trade Store`7 to sell your item from Pilinoria.  You can't trade in Pilinoria until you do!");
		addnav("Return");
		addnav("Back to the Forest","forest.php");
		addnav("About");
		addnav("About Ocean Quest","runmodule.php?module=oceanquest&op=docks&op2=about");
		output_notl("`c");
	}
	if ($op2=="about"){
		output("`c`b`^About Ocean Quest`c`b`7`n");
		page_header("Ocean Quest");
		output("Welcome to `#Ocean Quest`7.");
		output("`n`nFor an unknown reason trade has almost come to a standstill with the kingdom across the sea.  For many years, the exchange of ideas, magic, and art flowed freely between our kingdom and that of Pilinoria.");
		output("However, this has recently changed.  It seems like Pilinoria has turned gray and hopeless. Very infrequently, one of the rare items from Pilinoria will be smuggled out of the devastated country but often at great price.");
		output("What has happened? Has a dragon caused this? Is there a famine? Or has the kingdom been overthrown by an invading army?");
		output("`n`nIt's up to you to help find the answer to these questions and bring prosperity back to Pilinoria.  Will you be able to solve this mystery?");
		output("`n`nOcean Quest is your chance to experience an extensive mystery and adventure. There are");
		output("`^<a href=\"runmodule.php?module=oceanquest&op=docks&op2=hints\">`Hhints`H</a>",true);
		addnav("","runmodule.php?module=oceanquest&op=docks&op2=hints");
		output("`7peppered throughout the docks to help you on your way.  Enjoy the wonders that await you. Since there are limits to the adventure though, please don't share your discoveries with anyone!");
		addnav("Return");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	if ($op2=="hints"){
		output("`c`b`^About Ocean Quest`c`b`7`n");
		page_header("Ocean Quest");
		output("This is a good example of a hint.");
		addnav("About");
		addnav("About Ocean Quest","runmodule.php?module=oceanquest&op=docks&op2=about");
	}
	//tradestore
	if ($op2=="tradestore"){
		page_header("Trade Store");
		output("`c`b`^International Trade Store`c`b`7`n");
		if (get_module_setting("freepil")>""){
			if ($allprefs['sorcerer']>""){
				output("You are in a small but impressive trade shop that features many of the items usually found in Pilinoria.");
				$tradeitem=$allprefs['tradeitem'];
				if ($tradeitem>=1 && $tradeitem<6){
					$itemarray=array("",get_module_setting("item1"),get_module_setting("item2"),get_module_setting("item3"),get_module_setting("item4"),get_module_setting("item5"));
					$colorarray=array("","`5","`1","`6","`2","`4");
					output("You take out your %s%s`7 and show it to the Don Inkler, the shoppkeeper.",$colorarray[$tradeitem],$itemarray[$tradeitem]);
					output("He examines it carefully and tells you `@'The best I can offer you is `^%s gold`@.  That should be a pretty reasonable profit for you.'`7`n`n",round(get_module_setting("cost".$tradeitem)*1.5));
					addnav("Sell");
					addnav(array("Sell Your %s%s",$colorarray[$tradeitem],$itemarray[$tradeitem]),"runmodule.php?module=oceanquest&op=docks&op2=tradesell");
				}
			}else{
				output("You wander into a run-down trade store that deals with items from across the sea.");
				output("The clerk, Don Inkler, looks up at you. `q'I apologize for the mess and the small inventory.  Items are hard to come by that are worth purchasing.'`n`n");			
			}
			if ($allprefs['purchased']==1){
				output("You already bought something today, so you won't be able to buy anything else.`n`n");
			}elseif (get_module_setting("avail1")>0 || get_module_setting("avail2")>0 || get_module_setting("avail3")>0 || get_module_setting("avail4")>0 || get_module_setting("avail5")>0){
				output("You carefully peruse the available items:`n`n");
				addnav("For Sale");
				$avail1=get_module_setting("avail1");
				$avail2=get_module_setting("avail2");
				$avail3=get_module_setting("avail3");
				$avail4=get_module_setting("avail4");
				$avail5=get_module_setting("avail5");
				if ($avail1>0){
					output("`c`b`5%s`b: `^%s`7 Available for `^%s Gold`7 %s`c",get_module_setting("item1"),$avail1,get_module_setting("cost1")*3,translate_inline($avail1==1?"":"Each"));
					addnav(array("Purchase `5%s",get_module_setting("item1")),"runmodule.php?module=oceanquest&op=docks&op2=tradebuy&op3=1");
				}
				if ($avail2>0){
					output("`c`b`1%s`b: `^%s`7 Available for `^%s Gold`7 %s`c",get_module_setting("item2"),$avail2,get_module_setting("cost2")*3,translate_inline($avail2==1?"":"Each"));
					addnav(array("Purchase `1%s",get_module_setting("item2")),"runmodule.php?module=oceanquest&op=docks&op2=tradebuy&op3=2");
				}
				if ($avail3>0){
					output("`c`b`6%s`b: `^%s`7 Available for `^%s Gold`7 %s`c",get_module_setting("item3"),$avail3,get_module_setting("cost3")*3,translate_inline($avail3==1?"":"Each"));
					addnav(array("Purchase `6%s",get_module_setting("item3")),"runmodule.php?module=oceanquest&op=docks&op2=tradebuy&op3=3");
				}
				if ($avail4>0){
					output("`c`b`2%s`b: `^%s`7 Available for `^%s Gold`7 %s`c",get_module_setting("item4"),$avail4,get_module_setting("cost4")*3,translate_inline($avail4==1?"":"Each"));
					addnav(array("Purchase `2%s",get_module_setting("item4")),"runmodule.php?module=oceanquest&op=docks&op2=tradebuy&op3=4");
				}
				if ($avail5>0){
					output("`c`b`4%s`b: `^%s`7 Available for `^%s Gold`7 %s`c",get_module_setting("item5"),$avail5,get_module_setting("cost5")*3,translate_inline($avail5==1?"":"Each"));
					addnav(array("Purchase `4%s",get_module_setting("item5")),"runmodule.php?module=oceanquest&op=docks&op2=tradebuy&op3=5");
				}
			}else{
				output("You look around at the trinkets and don't find anything worth purchasing. The clerk explains that shipments are infrequent but to check back tomorrow.");
			}
		}else{
			output("You enter a small trade store but there's nothing on the shelves!");
			output("`n`n`#'Why don't you have anything for sale? I want to buy something!'`7 you tell the shoppkeeper.");
			output("`n`n`@'I can't get any one to bring me trade items from Pilinoria.  Once someone successfully sells me something, I'll be glad to pass it on to you at a very reasonable price.  Just check back frequently,'`7 he tells you.");
		}
		addnav("Return");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	if ($op2=="tradesell"){
		page_header("Trade Store");
		output("`c`b`^International Trade Store`c`b`7`n");
		$tradeitem=$allprefs['tradeitem'];
		$itemarray=array("",get_module_setting("item1"),get_module_setting("item2"),get_module_setting("item3"),get_module_setting("item4"),get_module_setting("item5"));
		$colorarray=array("","`5","`1","`6","`2","`4");
		if (get_module_setting("chance".$tradeitem)>=e_rand(1,100)){
			output("The shopkeeper takes your %s%s`7 and looks at it carefully.  He gets a very disconcerting look on his face and frowns.",$colorarray[$tradeitem],$itemarray[$tradeitem]);
			output("`n`n`@'I'm so sorry.  But this is nothing but mass-reproduced junk.  I can give you `^3 gold`@ for it but that's it.'");
			$session['user']['gold']+=3;
			output("`n`n`7You're extremely disappointed and try to argue but he points out the signs of it's cheap production.  You have to concede that he's right and you take the `^3 gold`7.");
			
		}else{
			output("You hand over your %s%s`7 and accept your payment of `^%s gold`7.",$colorarray[$tradeitem],$itemarray[$tradeitem],round(get_module_setting("cost".$tradeitem)*1.5));
			output("`n`n`@'Thank you for selling your %s%s`@ to me. I will have to clear it with the trade commission and it will be available for sale in my store tomorrow.'",$colorarray[$tradeitem],$itemarray[$tradeitem]);
			$session['user']['gold']+=round(get_module_setting("cost".$tradeitem)*1.5);
			increment_module_setting("tocome".$tradeitem,1);
			increment_module_pref("trades",1);
		}
		$allprefs['tradeitem']=6;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Return");
		addnav("Trade Shop","runmodule.php?module=oceanquest&op=docks&op2=tradestore");
		addnav("The Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	if ($op2=="tradebuy"){
		page_header("Trade Store");
		output("`c`b`^International Trade Store`c`b`7`n");
		$itemarray=array("",get_module_setting("item1"),get_module_setting("item2"),get_module_setting("item3"),get_module_setting("item4"),get_module_setting("item5"));
		$colorarray=array("","`5","`1","`6","`2","`4");
		$goldcost=get_module_setting("cost".$op3)*3;
		if ($session['user']['gold']<$goldcost){
			output("You don't have enough gold to purchase the %s%s`7.",$colorarray[$op3],$itemarray[$op3]);
		
		}else{
			$session['user']['gold']-=$goldcost;
			output("You hand over `^%s gold`7 and take the %s%s`7 from the shopkeeper.",$goldcost,$colorarray[$op3],$itemarray[$op3]);
			if (get_module_setting("buy".$op3)>=e_rand(1,100)){
				output("As soon as you pick it up you drop it and it breaks. You look at the shopkeeper and plead for a refund.");
				output("`n`nHe shakes his head and points at a sign that says `&'No Refunds'`7.");
			}else{
				output("`n`nAs soon as you pick it up, you feel a peculiar power coming from the item.");
				if ($op3==1){
					$item=get_module_setting("item1");
					$power=$item.translate_inline(" Power");
					apply_buff('trade',array(
						"name"=>$power,
						"rounds"=>10,
						"wearoff"=>"`5The Power of the $item leaves you.",
						"atkmod"=>1.2,
					));
					output("Suddenly, you feel stronger.");
				}elseif ($op3==2){
					$item=get_module_setting("item2");
					$power=$item.translate_inline(" Power");
					apply_buff('trade',array(
						"name"=>$power,
						"rounds"=>10,
						"wearoff"=>"`1The Power of the $item leaves you.",
						"defmod"=>1.3,
					));
					output("You feel faster all of a sudden.");
				}elseif ($op3==3){
					apply_buff('trade',array(
						"name"=>"Genie",
						"rounds"=>10,
						"minioncount"=>1,
						"minbadguydamage"=>0,
						"maxbadguydamage"=>8,
						"effectmsg"=>"`6The Genie hits for `^{damage}`6 hitpoints`^.",
						"effectnodmgmsg"=>"`6The Genie misses.",
						"wearoff"=>"The Genie bids you good luck and departs.",
					));
					output("A genie appears and offers to fight by your side for a little while.");
				}elseif ($op3==4){
					$item=get_module_setting("item3");
					$power=$item.translate_inline(" Power");
					apply_buff('trade',array(
						"name"=>$power,
						"rounds"=>5,
						"wearoff"=>"`2The Power of the $item leaves you.",
						"atkmod"=>1.2,
						"defmod"=>1.3,
					));
					output("You feel faster and stronger!");
				}else{
					$session['user']['charm']++;
					output("It makes you look more charming! You gain a charm.");
				}
			}
			increment_module_setting("avail".$op3,-1);
			$allprefs['purchased']=1;
			set_module_pref('allprefs',serialize($allprefs));
		}
		addnav("Return");
		addnav("Trade Shop","runmodule.php?module=oceanquest&op=docks&op2=tradestore");
		addnav("The Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	//Bait and Tackle Shop
	if ($op2=="baittackle"){
		output("`b`c`^Bait and Tackle Shop`b`c`n`7");
		output("You take a look around the old bait shop to see what you might purchase.");
		output("You notice the owner sitting on a stool behind the counter.  His skin is sun and salt worn but his eyes remain sharp. He looks at you with a smile.");
		output("`2`n`n'My name is Hoglin. Welcome to my little shop.  What can I interest you in today?'");
		output("`n`n`7You decide to take a look around.`n`n");
		if ($allprefs['pole']==1 || $allprefs['bait']==1 || $allprefs['fishbook']==1){
			output("`n`c`@Fishing Inventory:`c`1");
			if ($allprefs['pole']==1) output("`cFishing Pole`c");
			if ($allprefs['bait']==1) output("`cBait`c");
			if ($allprefs['fishbook']==1) output("`cBook on Fishing`c");
			if ($allprefs['pole']==1 && $allprefs['bait']==1) output("`n`c`!Looks like you're ready to go fishing!`c");
		}
		oceanquest_baitnav();
	}
	if ($op2=="fishchat"){
		output("`b`c`^Bait and Tackle Shop`b`c`n`7");
		output("You ask Hoglin how the fish are biting.");
		if ($allprefs['fishweight']<100){
			output("`n`n`2'Ah, well, you can catch a nice bounty off the dock, that's for sure.");
			if ($allprefs['pole']==1 || $allprefs['bait']==1){
				output("I notice you're getting ready to go fishing yourself there. You've got your");
				if ($allprefs['pole']==1) output("fishing pole");
				if ($allprefs['pole']==1 && $allprefs['bait']==1) output("and");
				if ($allprefs['bait']==1) output("bait");
				output("so I bet you're ready to get out there.");
			}else output ("You'll need a good fishing pole and some bait before you can go fishing.");
			if ($allprefs['fishbook']==1) output("Your book probably won't be any good until you get onto a fishing boat.");
			else output("I have some fishing books for sale, but they mainly deal with open sea fishing; they're not too useful for fishing off the end of the dock.");
			output("Now, of course, the best place to catch fish is from the side of a ship.  However, I doubt you'll be let onto a fishing vessel until you've proven you know your way around a fishing pole; so for now your best bet is to head to the docks.'");
		}else{
			output("Hoglin notices that you've been doing your fair share of fishing and you've got your name up on the list showing your abilities.");
			output("`n`n`2'You know, the best fish are caught in the deep deep sea.  I recommend you try to charter an expedition on the `&`iCorinth`i`2 for some real fishing!'");
		}
		if ($allprefs['pole']==1 || $allprefs['bait']==1 || $allprefs['fishbook']==1){
			output("`n`n`c`@Fishing Inventory:`c`1");
			if ($allprefs['pole']==1) output("`cFishing Pole`c");
			if ($allprefs['bait']==1) output("`cBait`c");
			if ($allprefs['fishbook']==1) output("`cBook on Fishing`c");
			if ($allprefs['pole']==1 && $allprefs['bait']==1) output("`n`c`!Looks like you're ready to go fishing!`c");
		}
		oceanquest_baitnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishchat");
	}
	if ($op2=="fishbooks"){
		output("`b`c`^Fishing Books`b`c`n`7");
		output("You decide to take a look at the collection of fishing books for sale.  There doesn't seem to be much of a selection. After reading through a couple of titles you realize there's probably only one worth");
		addnav("Fishing Books");
		if ($allprefs['fishbook']==0 && $allprefs['readbook']==1){
			output("purchasing.  However, if you hadn't lost your old one, you wouldn't be looking at this one now, would you?");
			output("`n`n`2'You know that one costs `^125 gold`2. Are you interested?'");
			addnav("Purchase Book","runmodule.php?module=oceanquest&op=docks&op2=fishbookbuy");
		}elseif ($allprefs['fishbook']==1){
			output("purchasing and it looks like you've already purchased it.");
			addnav("Read Fishing Book","runmodule.php?module=oceanquest&op=docks&op2=readfishbook&op3=store");
		}else{
			output("purchasing.`n`nYou ask Hoglin how much the book titled `HFishing the Open Seas`H`7 costs.  He comes over and takes a look at the book, looks you over, and then quotes you a price.");
			output("`n`n`2'Well, this one here's going to run you `^125 gold`2. Are you interested?'");
			addnav("Purchase Book","runmodule.php?module=oceanquest&op=docks&op2=fishbookbuy");
		}
		oceanquest_baitnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishbooks");
	}
	if ($op2=="fishbookbuy"){
		output("`b`c`^Fishing Books`b`c`n`7");
		if ($session['user']['gold']>=125){
			addnav("Fishing Books");
			addnav("Read Fishing Book","runmodule.php?module=oceanquest&op=docks&op2=readfishbook&op3=store");
			$allprefs['fishbook']=1;
			$session['user']['gold']-=125;
			set_module_pref('allprefs',serialize($allprefs));
			output("You hand over the `^125 gold`7 and take a read through the book.");
			
		}else{
			output("Hoglin repeats the price of the book to you and you realize you don't have enough gold to purchase it.");
			output("`n`nHe puts it back on the shelf and tells you not to worry, it will still be here when you get enough gold.");
			oceanquest_baitnav();
			blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishbooks");
		}
	}
	if ($op2=="readfishbook"){
		output("`b`c`^Fishing the Open Seas`c`b`n`7");
		if ($allprefs['readbook']=="" || $allprefs['readbook']==0){
			output("`6`i\"The winds are critical to the assessment of the location of fish schools. A complicated algorithm has been devised based from the works and studies of Francisco Gronadgolan starting after the Yilithian Wars.");
			output("The foundation of wind analysis includes a correlation with the depth of the water and the surrounding undercurrent temperature with an inversion of the product used to calculate the likelihood ratio for...`i\"");
			output("`n`n`7BLAH BLAH BLAH!  This is the most boring information you've ever seen in your life.");
			output("You page through the book and finally find a chart that could possibly be useful.  You tear out this chart and keep it for future reference and you'll find it available when you need it:`n`n");
			$allprefs['readbook']=1;
			set_module_pref('allprefs',serialize($allprefs));
		}
		output("`c`b`%How to find Excellent Fishing locations using Temperature, Depth, and Wind Speed`b`c`n");
		output("`c`b`#Temperature To Depth Reference`b");
		rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
		rawoutput("<tr class='trhilight'><td><center>");
		rawoutput("</center></td><td class='trhead'><center>");
		output("50+ Feet");
		rawoutput("</center></td><td class='trhead'><center>");
		output("75+ Feet");
		rawoutput("</center></td><td class='trhead'><center>");
		output("100+ Feet");
		rawoutput("<center/></td></tr>");
		rawoutput("<tr class='trhead'><td><center>");
		output("60+ Degrees");
		rawoutput("</center></td><td class='trlight'><center>");
		output("`\$A");
		rawoutput("</center></td><td class='trlight'><center>");
		output("`^B");
		rawoutput("</center></td><td class='trlight'><center>");
		output("`@C");
		rawoutput("<center/></td></tr>");
		rawoutput("<tr class='trhead'><td><center>");
		output("70+ Degrees");
		rawoutput("</center></td><td class='trdark'><center>");
		output("`^B");
		rawoutput("</center></td><td class='trdark'><center>");
		output("`@C");
		rawoutput("</center></td><td class='trdark'><center>");
		output("`\$A");
		rawoutput("<center/></td></tr>");
		rawoutput("<tr class='trhead'><td><center>");
		output("80+ Degrees");
		rawoutput("</center></td><td class='trlight'><center>");
		output("`@C");
		rawoutput("</center></td><td class='trlight'><center>");
		output("`\$A");
		rawoutput("</center></td><td class='trlight'><center>");
		output("`^B");
		rawoutput("<center/></td></tr>");
		rawoutput("</table>");
		output("`n`b`@Wind Cross Reference to Temperature/Depth Chart`b");
		rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
		rawoutput("<tr class='trhead'><td class='trhilight'><center>");
		rawoutput("</center></td><td><center>");
		output("`\$A");
		rawoutput("</center></td><td><center>");
		output("`^B");
		rawoutput("</center></td><td><center>");
		output("`@C");
		rawoutput("<center/></td></tr>");
		rawoutput("<tr class='trhead'><td><center>");
		output("0-10 Knots");
		rawoutput("</center></td><td class='trlight'><center>");
		output("Excellent");
		rawoutput("</center></td><td class='trlight'><center>");
		output("Poor");
		rawoutput("</center></td><td class='trlight'><center>");
		output("Fair");
		rawoutput("<center/></td></tr>");
		rawoutput("<tr class='trhead'><td><center>");
		output("11-20 Knots");
		rawoutput("</center></td><td class='trdark'><center>");
		output("Fair");
		rawoutput("</center></td><td class='trdark'><center>");
		output("Excellent");
		rawoutput("</center></td><td class='trdark'><center>");
		output("Poor");
		rawoutput("<center/></td></tr>");
		rawoutput("<tr class='trhead'><td><center>");
		output("21+ Knots");
		rawoutput("</center></td><td class='trlight'><center>");
		output("Poor");
		rawoutput("</center></td><td class='trlight'><center>");
		output("Fair");
		rawoutput("</center></td><td class='trlight'><center>");
		output("Excellent");
		rawoutput("<center/></td></tr>");
		rawoutput("</table>");
		output_notl("`c");
		if ($op3=="store"){
			oceanquest_baitnav();
			blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishbooks");
			blocknav("runmodule.php?module=oceanquest&op=docks&op2=readfishbook&op3=store");
		}elseif ($op3=="expedition"){
			page_header("Fishing Expedition");
			addnav("Return to Gauges","runmodule.php?module=oceanquest&op=fishingexpedition&op2=gauge&op3=$op4");
			addnav("Return to Expedition","runmodule.php?module=oceanquest&op=fishingexpedition&loc=".get_module_pref("pqtemp"));
		}elseif ($op3=="expeditiona"){
			page_header("Fishing Expedition");
			addnav("Return to Expedition","runmodule.php?module=oceanquest&op=fishingexpeditiona");
		}
	}
	if ($op2=="fishpoles"){
		output("`b`c`^Fishing Pole`b`c`n`7");
		output("Hoglin shows off the fishing poles.`2");
		addnav("Fishing Poles");
		if ($allprefs['pole']==1){
			output("`7Suddenly, Hoglin realizes you've already got a mighty fine fishpole strapped to your back. `3'Hey! You don't need another fishing pole!'");
		}elseif (($allprefs['stickstring']=="" || $allprefs['stickstring']==0) && $op3==""){
			output("'Well, I can give you a stick with a string or I can sell you a quality fishing pole.  What would you prefer?'");
			addnav("Stick with a String","runmodule.php?module=oceanquest&op=docks&op2=fishstick");
			addnav("Quality Fishing Poles","runmodule.php?module=oceanquest&op=docks&op2=fishpoles&op3=quality");
		}else{
			output("`7He shows you a very sturdy fishing pole.  You examine it closely, pretending that you know what you're looking at.");
			output("You studder out `#'How much?'`7 and Hoglin knows he's got a bite for one of his wares.`n`n`2");
			if (get_module_setting("fishingpole")>0) output("'Such a nice pole can't be purchased for less than `^%s gold`2. Are you interested?'",get_module_setting("fishingpole"));
			else output("'Luckily, I received a donation recently to sponsor new fishermen. I can give this to you for free.'");
			if ($session['user']['gold']<get_module_setting("fishingpole")){
				output("`7`n`nExcited at the prospect, you get ready to hand over the gold but suddenly realize you're a little short. You'll have to come back with you've got some more money.");
			}else{
				addnav("Purchase Fishing Pole","runmodule.php?module=oceanquest&op=docks&op2=fishpolebuy");
			}
		}
		if ($allprefs['pole']==1 || $allprefs['bait']==1 || $allprefs['fishbook']==1){
			output("`n`n`c`@Fishing Inventory:`c`1");
			if ($allprefs['pole']==1) output("`cFishing Pole`c");
			if ($allprefs['bait']==1) output("`cBait`c");
			if ($allprefs['fishbook']==1) output("`cBook on Fishing`c");
			if ($allprefs['pole']==1 && $allprefs['bait']==1) output("`n`c`!Looks like you're ready to go fishing!`c");
		}
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishpoles");
		oceanquest_baitnav();
	}
	if ($op2=="fishstick"){
		output("`b`c`^Fishing Pole`b`c`n`7");
		output("`3'I take it you've never heard of sarcasm. You can't catch fish with just a stick and a string; at least not anything worth mentioning. Let's talk about some real fishing equipment.'");
		$allprefs['stickstring']=1;
		set_module_pref('allprefs',serialize($allprefs));
		oceanquest_baitnav();
	}
	if ($op2=="fishpolebuy"){
		output("`b`c`^Fishing Pole`b`c`n`7");
		output("You hand over your `^%s gold`7 and Hoglin gives you a brand new fishing pole.  How nice!",get_module_setting("fishingpole"));
		$session['user']['gold']-=get_module_setting("fishingpole");
		$allprefs['pole']=1;
		set_module_pref('allprefs',serialize($allprefs));
		oceanquest_baitnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishpoles");
		if ($allprefs['pole']==1 || $allprefs['bait']==1 || $allprefs['fishbook']==1){
			output("`n`n`c`@Fishing Inventory:`c`1");
			if ($allprefs['pole']==1) output("`cFishing Pole`c");
			if ($allprefs['bait']==1) output("`cBait`c");
			if ($allprefs['fishbook']==1) output("`cBook on Fishing`c");
			if ($allprefs['pole']==1 && $allprefs['bait']==1) output("`n`c`!Looks like you're ready to go fishing!`c");
		}
	}
	if ($op2=="fishbait"){
		output("`b`c`^Bait`b`c`n`7");
		output("`2'Now, quality worms are essential to catching anything worth keeping.");
		if ($allprefs['bait']>0){
			$fishingbait=get_module_setting("fishingbait");
			$sellprice=floor(.75*$fishingbait*((5-$allprefs['fishingtoday'])/5));
			if ($sellprice==0) $sellprice=floor($fishingbait*.75);
			output("`2I notice you've got some nice Nightcrawlers there.  Would you be interested in selling some of them? I'd be willing to buy your Nightcrawlers for `^%s gold`2. Let me know if you're interested.'",$sellprice);
			addnav("Bait Sell");
			addnav("Sell Nightcrawlers","runmodule.php?module=oceanquest&op=docks&op2=fishbaitsell&op3=$sellprice");
		}elseif ($allprefs['fishingtoday']=="" || $allprefs['fishingtoday']==0){
			if (is_module_active("trading")) output("I can tell you've seen those run-of-the-mill worms from the trading posts.  Those won't do.");
			output("You need Nightcrawlers to play with the big fish.  A box of nightcrawlers will last for a day of fishing.  Of course, these little guys don't last too long so you'll need to get some more everyday.");
			output("They only cost `^%s gold`2.  Are you interested?'",get_module_setting("fishingbait"));
			addnav("Bait Purchase");
			addnav("Box of Nightcrawlers","runmodule.php?module=oceanquest&op=docks&op2=fishbaitbuy");
		}else{
			output("But there's a problem.'`n`n`7Hoglin tells you that he's sorry but he doesn't have enough Nightcrawlers to sell; he has to save them for the `&`iCorinth`i`7 because she's setting sail soon. `2'Stop by tomorrow.  I have a kid out there trying to find some for me.'");
		}
		oceanquest_baitnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishbait");
	}
	if ($op2=="fishbaitsell"){
		output("`b`c`^Bait`b`c`n`7");
		output("You hand over your Nightcrawlers and collect `^%s gold`7.",$op3);
		$session['user']['gold']+=$op3;
		$allprefs['bait']=0;
		$allprefs['fishingtoday']=5;
		set_module_pref('allprefs',serialize($allprefs));
		oceanquest_baitnav();
	}
	if ($op2=="fishbaitbuy"){
		output("`b`c`^Bait`b`c`n`7");
		$fishingbait=get_module_setting("fishingbait");
		if ($allprefs['bait']==1) output("You show your box of Nightcrawlers to Hoglin and he looks them over.`n`n`2'These still look good.  You don't need to buy anymore.'");
		elseif ($session['user']['gold']<$fishingbait) output("You pretend to hand Hoglin `^%s gold`7 and he winks and pretends to hand you a box of Nightcrawlers.  To get REAL Nightcrawlers you'll need to get some REAL gold.",$fishingbait);
		else{
			output("You hand Hoglin `^%s gold`7 and he hands you a box of Nightcrawlers.  You look at the wiggly suckers and blanch a little.  Do fish really think these are tasty?? Oh well, if that's what Hoglin says they like, that's what you're going to use.",$fishingbait);
			$session['user']['gold']-=$fishingbait;
			$allprefs['bait']=1;
			set_module_pref('allprefs',serialize($allprefs));
		}
		if ($allprefs['pole']==1 || $allprefs['bait']==1 || $allprefs['fishbook']==1){
			output("`n`n`c`@Fishing Inventory:`c`1");
			if ($allprefs['pole']==1) output("`cFishing Pole`c");
			if ($allprefs['bait']==1) output("`cBait`c");
			if ($allprefs['fishbook']==1) output("`cBook on Fishing`c");
			if ($allprefs['pole']==1 && $allprefs['bait']==1) output("`n`c`!Looks like you're ready to go fishing!`c");
		}
		oceanquest_baitnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishbait");
	}
	//Notices
	if ($op2=="fishnotices"){
		output("`b`c`^Notice Board`b`c`n`7");
		output("You decide to go look over the Notice Board.");
		oceanquest_noticenav();
		oceanquest_baitnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishnotices");
	}
	if ($op2=="iou"){
		if ($op3=="francis"){
			$iou=translate_inline("Francis");
			$iougold=2450;
		}elseif ($op3=="trandor"){
			$iou=translate_inline("Trandor");
			$iougold=1533;
		}elseif ($op3=="yoglin"){
			$iou=translate_inline("Yoglin");
			$iougold=12;
		}elseif ($op3=="bondo"){
			$iou=translate_inline("Bondo");
			$iougold=598;
		}
		output("`b`c`^IOUs`b`c`n`7");
		output("You read over one of the IOUs.  This is from `&%s`7.`n`n",$iou);
		output("`i`5`cIOU to Hoglin: `&%s`5 owes `2Hoglin`5 a grand total of `^%s gold`5.`c`n`n",$iou,$iougold);
		addnav("IOU Payoff");
		addnav("Pay this IOU","runmodule.php?module=oceanquest&op=docks&op2=ioupay&op3=$op3&op4=$iougold");
		oceanquest_noticenav();
		oceanquest_baitnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishnotices");
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=iou&op3=$op3");
	}
	if ($op2=="ioupay"){
		if ($op3=="francis") $iou=translate_inline("Francis");
		elseif ($op3=="trandor") $iou=translate_inline("Trandor");
		elseif ($op3=="yoglin") $iou=translate_inline("Yoglin");
		elseif ($op3=="bondo") $iou=translate_inline("Bondo");
		output("`b`c`^IOUs`b`c`n`7");
		if ($session['user']['gold']>=$op4){
			output("Feeling a bit generous, you take `&%s's`7 IOU over to Hoglin and offer to pay off the debt.",$iou);
			output("Hoglin looks at you a little suspiciously but takes your money and hands you the IOU marked `1'Paid in Full'`7.");
			if (is_module_active("alignment")){
				increment_module_pref("alignement",1,"alignment");
				output("You feel good about helping people.");
			}
			if ($op3=="francis") $allprefs['iou1']=1;
			elseif ($op3=="trandor"){
				$allprefs['piece3']=1;
				if ($allprefs['unlockdecree']==1) output("You flip over the IOU and realize it's a piece of the `^Royal Decree for Passage on the Luckstar`7!");
			}elseif ($op3=="yoglin") $allprefs['iou2']=1;
			elseif ($op3=="bondo") $allprefs['iou3']=1;
			set_module_pref('allprefs',serialize($allprefs));
			$session['user']['gold']-=$op4;
		}else{
			output("You try to pay off the IOU but you don't have enough gold.");
		}
		oceanquest_noticenav();
		oceanquest_baitnav();
	}
	if ($op2=="coconut"){
		output("`b`c`^Coconut Sale`b`c`n`7");
		output("You meet up with Bernie and offer to sell him your coconut.");
		output("`n`n`6'Sure! I LOVE coconuts!'`7 says Bernie.");
		output("`n`nYou hand over the coconut and Bernie hands you `^25 gold`7.  What a deal!");
		$allprefs['coconut']=2;
		$session['user']['gold']+=25;
		set_module_pref('allprefs',serialize($allprefs));
		oceanquest_baitnav();
	}
	if ($op2=="forsale"){
		output("`b`c`^For Sale`b`c`n`7");
		output("You decide to peruse the 'For Sale' Notices.`n`n");
		output("`5`cFor Sale");
		if ($op3=="usedfish"){
			output("Used fish for sale.  Slight odor.  Please contact Klodnio if interested.`c");
			output("`7`n`nHmm... Now who on earth would want used fish?");
		}elseif ($op3=="newfish"){
			output("New fish for sale.  Never used.  Only 5 weeks old, slight odor.  Please contact Klodnio if interested.`c");
			output("`7`n`nSeems like this Klodnio character has a new marketing ploy for old dead fish.");
		}elseif ($op3=="stickstring"){
			output("Stick with string.  Not useful for fishing, so who are you kidding?`c");
			output("`7`n`nThere's no contact number.  This must be one of Hoglin's jokes.");
		}
		oceanquest_noticenav();
		oceanquest_baitnav();
	}
	if ($op2=="wanted"){
		output("`b`c`^Wanted`b`c`n`7");
		output("You decide to peruse the 'Wanted' Notices.`n`n`5`c");
		if ($op3=="coconut"){
			output("Wanted: Coconuts.  Willing to pay `^25 gold`5 per coconut.  I will visit the Bait shop and catch up to you if you have some.  Look for me!`n Signed, Bernie`c");
			output("`n`n`7You make a mental note to look for coconuts.");
		}elseif ($op3=="usedfish"){
			output("Wanted:  Used fish.  Age unimportant.  Must have eyes intact.  Please contact Witch Brunda.`c");
			output("`7`n`nMaybe Brunda should contact that Klodnio guy.");
		}
		oceanquest_noticenav();
		oceanquest_baitnav();
	}
	if ($op2=="jobavailable"){
		output("`b`c`^Jobs`b`c`n`7");
		output("You decide to peruse the 'Job' Notices.`n`n`5");
		if (is_module_active("jobs") && $op3=="1"){
			output("Job Opening at the Farm:  Please visit our Farm to apply.  We are looking for hard workers who will help supply the village with food.");
		}elseif ($op3=="1"){
			output("Job Opening at the Fish and Tackle Shop:  We are looking for people that will buy items from our store.  Please see Hoglin.");
			output("`n`n`7You glance over at Hoglin and smirk.  This seems like a pretty self-serving 'Job'!");
		}elseif ($op3=="2"){
			output("Job Opening at the Albania:  We are looking for experienced sailors to help sail to edge of Earth.  Not responsible if we fall off.");
			output("`n`n`7You decide that that is NOT the kind of job you're looking for.");
		}elseif($op3=="3"){
			output("Job Description:  Wanted: Person with small fingers to pull cotton out of small bottles.");
			output("`n`n`7Does the adventure end? How boring is that? Could there be anything more boring???");
		}elseif ($op3=="4"){
			output("Job Description:  Wanted: Person with small fingers to put cotton into small bottles.");
			output("`n`n`7Suddenly, you feel like this may be a contender for the most boring job ever.");
		}elseif ($op3=="5"){
			output("Job Description:  Wanted: Nightcrawlers for bait shop.  If you find some in the forest, please see Hoglin.");
			output("`n`n`7You'll be sure to look for nightcrawlers in the forest.");
		}
		oceanquest_noticenav();
		oceanquest_baitnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=jobsavailable&op3=$op3");
	}
	if ($op2=="bigfish" || $op2=="fishweight" || $op2=="numberfish"){
		if ($op2=="bigfish") $title=translate_inline("Largest Fish Ever Caught");
		elseif ($op2=="fishweight") $title=translate_inline("Most Fish Caught by Weight");
		elseif ($op2=="numberfish") $title=translate_inline("Most Fish Caught by Number");
		output("`b`c`^%s`b`c`n`7",$title);
		$perpage = 25;
		$subop = httpget('subop');
		if ($subop=="") $subop=1;
		$min = (($subop-1)*$perpage);
		$max = $perpage*$subop;
		//This unserializes the pref to count the number of fishers so we can set up pages, thanks to Danbi for helping with it
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		$number=0;
		$new_array = array();
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$array=unserialize(get_module_pref('allprefs','oceanquest',$row['acctid']));
			if ($array[$op2]>0) {
				$number=$number+1;
				$new_array[$row['acctid']] = $array[$op2];
			}
		}
		$totalpages=ceil($number/$perpage);
		addnav("Pages");
		if ($totalpages>1){
			for($i = 0; $i < $totalpages; $i++) {
				$j=$i+1;
				$minpage = (($j-1)*$perpage)+1;
				$maxpage = $perpage*$j;
				if ($maxpage>$number) $maxpage=$number;
				addnav("Pages");
				if ($maxpage==$minpage) addnav(array("Page %s (%s)", $j, $minpage), "runmodule.php?module=oceanquest&op=docks&op2=$op2&subop=$j");
				else addnav(array("Page %s (%s-%s)", $j, $minpage, $maxpage), "runmodule.php?module=oceanquest&op=docks&op2=$op2&subop=$j");
			}
		}
		$rank = translate_inline("Rank");
		$name = translate_inline("Name");
		$none = translate_inline("No Fish Caught");
		$weight= translate_inline("Weight");
		$numberfish = translate_inline("Number of Fish");
		rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
		if ($op2=="bigfish"){
			rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$weight</td></tr>");
		}elseif ($op2=="fishweight" || $op2=="numberfish"){
			rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$numberfish</td><td>$weight</td></tr>");
		}
		$n=0;
		if ($number==0) {
			output_notl("<tr class='trlight'><td colspan='4' align='center'>`&$none`0</td></tr>",true);
		}else{
			//Thanks to Sichae for the next lines
			arsort($new_array);
			foreach($new_array AS $acctid => $value){
				$n=$n+1;
				if ($n>$min && $n<=$max){
					if ($acctid==$session['user']['acctid']) rawoutput("<tr class='trhilight'><td>");
					else rawoutput("<tr class='".($n%2?"trdark":"trlight")."'><td>");
					$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=".$acctid;
					$result = db_query($sql);
					$row = db_fetch_assoc($result);
					$name=$row['name'];
					output_notl("`&%s",$n);
					rawoutput("</td><td>");
					output_notl("`&%s",$name);
					if ($op2=="fishweight" || $op2=="numberfish"){
						rawoutput("</center></td><td><center>");
						if ($op2=="fishweight"){
							$allprefshof=unserialize(get_module_pref('allprefs','oceanquest',$acctid));
							$numberfish=$allprefshof['numberfish'];
							output_notl("`^%s",$numberfish);
						}else output_notl("`^%s",$value);
					}
					rawoutput("</td><td><align=right>");
					if ($op2=="fishweight" || $op2=="bigfish"){
						$pounds=floor($value/16);
						$ounces=$value-($pounds*16);
					}else{
						$allprefshof=unserialize(get_module_pref('allprefs','oceanquest',$acctid));
						$fishweight=$allprefshof['fishweight'];
						$pounds=floor($fishweight/16);
						$ounces=$fishweight-($pounds*16);
					}
					output("%s %s, %s %s",$pounds,translate_inline($pounds<>1?"pounds":"pound"),$ounces,translate_inline($ounces<>1?"ounces":"ounce"));
					rawoutput("</td></tr>");
				}
			}
		}
		rawoutput("</table>");
		oceanquest_baitnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=$op2");
	}
	//Fishing
	if ($op2=="fishing"){
		output("`b`c`^Fishing Dock`b`c`7`n");
		output("You arrive at the end of the dock and notice several people fishing here. There's a big sign outlining the rules of fishing off the dock.`n`n");
		addnav("Dock Fishing");
		$fishingtoday=$allprefs['fishingtoday'];
		if (($allprefs['pole']=="" || $allprefs['pole']==0) || (($allprefs['bait']=="" || $allprefs['bait']==0) && $fishingtoday<5)){
			if ($allprefs['pole']=="") output("You pull out a piece of string and tie it to stick");
			else output("You pull out your fishing pole and get ready to cast your empty hook");
			output("and the rest of the fishermen on the dock start laughing and pointing at you. One of the oldest anglers of the bunch wanders over to you and puts his arm around your shoulder.");
			output("`n`n`3'%s, I've been fishing these docks for longer than you've been walking this kingdom.  There have been many truths and many lies I've heard.",translate_inline($session['user']['sex']?"Missy":"Sonny"));
			output("I've seen a man catch an octopus as large as a boat.  I swear this on my last worm.  I've heard a man claim to have caught the greatest fish in the seas- `qCaptain Crouton`3. That's a big fish tale, I promise you. However, there's one thing I've never seen. I've never seen anyone catch a fish");
			if ($allprefs['pole']=="" || $allprefs['pole']==0) output("with a stick and a string.");
			else output("without any bait.");
			output("You'll need to head on over to the 'ole bait shop.'");			
		}elseif ($fishingtoday<5){
			$fishingleft=5-$fishingtoday;
			output("Ready to go fishing? You have enough bait for `^%s `7more %s.",$fishingleft,translate_inline($fishingleft>1?"casts":"cast"));
			addnav("Go Fishing","runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
		}else{
			output("You've done enough fishing for today.");
		}
		addnav("Read Rules","runmodule.php?module=oceanquest&op=docks&op2=fishingrules");
		addnav("Chat with Fishermen","runmodule.php?module=oceanquest&op=docks&op2=fishingchat");
		addnav("Docks");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	if ($op2=="fishingchat"){
		$person=e_rand(0,5);
		$fishername=translate_inline(array("`%Wendall`7","`\$Earl`7","`QFisherman Pete`7","`^Ole Opie`7","`@Gordon`7","`!Admiral Adam`7"));
		$color=array("`%","`\$","`Q","`^","`@","`!");
		output("`b`c`^Fishing Dock`b`c`7`n");
		output("You wander over to one of the fishermen on the dock and try to strike up a casual conversation.");
		output("You introduce yourself and find yourself talking to %s.",$fishername[$person]);
		output("The best you can come up with for conversation is `#'How are the fish biting today?'%s`n`n",$color[$person]);
		switch(e_rand(1,10)){
			case 1:
				output("'I haven't caught a fish from this dock in 3 years.  Anything else you want to know??'");
				output("`n`n`7Hmm.  Maybe he's not the best person to talk to today.");
			break;
			case 2:
				output("'Have you caught `q'Captain Crouton'%s, the biggest fish in the sea??'",$color[$person]);
				output("`n`n`7You dream whistfully of catching the monster fish. `#'Err, no, I haven't caught him yet,'`7 you respond.");
			break;
			case 3:
				output("'Yer not going to get any experience fishing by yabbering all day.'");
				output("`n`n`7You look at the decrepit form of the 'ole salt and think that maybe dedicating your life to fishing isn't all it's cracked up to be.");
			break;
			case 4:
				output("'I once caught a fish bigger than this dock once, but it got away.'");
				output("`n`n`7All the other fishermen at the dock laugh at %s's boast.  You nod and smile politely.",$fishername[$person]);
			break;
			case 5:
				output("'Why are you fishing off the dock? You know, the real fish are caught on the open sea.'");
				output("`n`n`7Ah, the open sea.  That's where you want to fish!");
			break;
			case 6:
				output("'Be careful how you cast your line.  Some of the fishermen on this dock can get pretty mean if you screw around.'");
				output("`n`n`7You make a mental note: Don't screw around too much!");
			break;
			case 7:
				output("'I don't read much, but I suppose if I did I wouldn't have time for all this fishing I need to do.'");
				output("`n`n`7Sometimes old fishermen just don't make sense.");
			break;
			case 8:
				output("'The funniest thing I ever saw on this dock was when a young angler about your size got pulled into the sea by a fish. HA!'");
				output("`n`n`7For some reason you don't think that sounds very funny.");
			break;
			case 9:
				output("'GO AWAY! I'm busy fishing!'");
				output("`n`n`7Cranky ole coot!");
			break;
			case 10:
				output("'Don't forget to weigh your fish. It's about honor!'");
				output("`n`n`7You imagine all the high honor you can get for catching a fish. Wow!");
			break;
		}
		$fishingtoday=$allprefs['fishingtoday'];
		addnav("Fishing Dock");
		addnav("Chat Some More","runmodule.php?module=oceanquest&op=docks&op2=fishingchat");
		if ($fishingtoday<5 && $allprefs['pole']>0 && $allprefs['bait']>0) addnav("Go Fishing","runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
		addnav("Read the Rules","runmodule.php?module=oceanquest&op=docks&op2=fishingrules");
		addnav("Docks");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	if ($op2=="fishingrules"){
		$dockfish=get_module_setting("dockfish");
		$dockfisher=get_module_setting("dockfishangler");
		$pounds=floor($dockfish/16);
		$ounces=$dockfish-($pounds*16);
		rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
		rawoutput("<tr class='trhead'><td>");
		output("`c`bWelcome to the `&Fishing Dock`c`b");
		rawoutput("</td></tr>");
		rawoutput("<tr class='trhilight'><td>");
		if ($dockfish>0){
			output("`c`n`^Largest Fish Ever Caught of the Docks:`n");
			if ($pounds>0) output("`&%s %s%s",$pounds,translate_inline($pounds>1?"Pounds":"Pound"),translate_inline($ounces>0?",":""));
			if ($ounces>0) output("`&%s %s",$ounces,translate_inline($ounces>1?"Ounces":"Ounce"));
			output("`n`^Angler who Caught the Fish:`n");
			output("`&%s`c",$dockfisher);
		}else output("`c`^No Fish caught at Dock Yet`c");
		output("`n`n`&`cPlease obey the following rules for fishing off the dock:`c");
		output("`n`&1. This is CATCH AND RELEASE ONLY! You may weigh your fish after catching it for documentation purposes but you must release the fish after weighing it.");
		output("`n2. No swimming off the dock");
		output("`n3. Please use proper equipment when fishing");
		output("`n4. Do not monopolize the dock.  Fishing is usually limited to no more than `^5 casts`& per day.`n`n");
		rawoutput("</td></tr>");
		rawoutput("</table>");
		$fishingtoday=$allprefs['fishingtoday'];
		addnav("Fishing Dock");
		if ($fishingtoday<5 && $allprefs['pole']>0 && $allprefs['bait']>0) addnav("Go Fishing","runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
		addnav("Chat with Fishermen","runmodule.php?module=oceanquest&op=docks&op2=fishingchat");
		addnav("Docks");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	if ($op2=="godockfishing"){
		output("`b`c`^Fishing Dock`b`c`7`n");
		output("You cast your line.");
		$allprefs['fishingtoday']++;
		$weight=0;
		switch(e_rand(1,50)){
		//switch(40){
			case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10:
				output("You reel it in and find nothing attached. Looks like you've wasted a nightcrawler.");
			break;
			case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19: case 20:
				//average 8
				output("You reel it in and find you've caught a fish! You show your catch to the other anglers on the dock and they laugh, calling your catch a 'cute little guppy'.");
				$weight=e_rand(4,12);
			break;
			case 21: case 22: case 23: case 24: case 25: case 26: case 27: case 28:
				//average 15
				output("You reel it in and find you've caught a fish! You show your catch to the other anglers on the dock and they smile politely at your minnow.");
				$weight=e_rand(10,20);
			break;
			case 27: case 28: case 29: case 30: case 31: case 32:
				//average 24
				output("You reel it in and find you've caught a nice fish! You show your catch to the other anglers on the dock and they give you a thumbs up!");
				$weight=e_rand(16,32);
			break;
			case 33: case 34: case 35: case 36:
				//average 32
				output("You reel it in and find you've caught a decent fish! You show your catch to the other anglers on the dock and they give you a nod of congratulations.");
				$weight=e_rand(25,40);
			break;
			case 37: case 38:
				//average 41
				output("You reel it in and find you've caught an acceptable fish! You show your catch to the other anglers on the dock and they smile at the catch, maybe a little envious!");
				$weight=e_rand(32,50);
			break;
			case 39:
				//average 50 or Bigger!
				$rand=e_rand(1,7);
				if ($rand<7){
					output("You reel it in and pull in quite a nice fish! You proudly display your fish to everyone.  Nice Catch! You gain a turn from your adrenaline.");
					$session['user']['turns']++;
					$weight=e_rand(40,60);
				}else{
					$rand2=e_rand(1,10);
					if ($rand2<10){
						output("You reel in a great fish from the dock! You feel the envious eyes of the other anglers. You gain 2 turns from your adrenaline!");
						$session['user']['turns']+=2;
						$weight=e_rand(61,70);
					}else{
						$rand3=e_rand(1,12);
						if ($rand3<12){
							output("You reel in one of the biggest fish ever caught on the dock! It's quite a catch! You gain 3 turns from your adrenaline rush!");
							$session['user']['turns']+=3;
							$weight=e_rand(71,80);
						}else{
							output("You catch a fish so big you're almost pulled into the sea! It's amazing! One in a million! The other anglers on the dock come over and admire your fish. You gain 4 turns form your adrenaline rush!");
							$session['user']['turns']+=4;
							$weight=e_rand(81,90);
							if ($weight==90){
								$rand4=e_rand(1,50);
								if ($rand4<10) $weight=91;
								elseif ($rand4<19) $weight=92;
								elseif ($rand4<27) $weight=93;
								elseif ($rand4<34) $weight=94;
								elseif ($rand4<39) $weight=95;
								elseif ($rand4<43) $weight=96;
								elseif ($rand4<46) $weight=97;
								elseif ($rand4<48) $weight=98;
								elseif ($rand4<50) $weight=99;
								else $weight=100;
							}
						}
					}
				}
			break;
			case 40:
				$rand=e_rand(1,3);
				output("It hooks on one of the other fishermen!");
				if ($rand<3){
					output("You go over to him and yank it out and he smiles thankfully. It looks like you lost your worm!");
				}else{
					output("He comes up to you and you notice that he isn't smiling. I don't think you're getting your worm back.");
					if (is_module_active("lumberyard") || is_module_active("quarry") || is_module_active("metalmine")){
						output("You suddenly recognize him from");
						if (is_module_active("lumberyard")) output("`@the lumberyard...");
						if (is_module_active("quarry")) output("`%the quarry...");
						if (is_module_active("metalmine")) output("`)the metal mine...");
						output("`7Oh my! Why do you keep picking on this guy??");
					}
					output("`n`nLooks like you're going to have to fight him.");
					addnav("Fisherman Fight!","runmodule.php?module=oceanquest&op=fishermanfight");
					blocknav("runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
					blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishingrules");
					blocknav("runmodule.php?module=oceanquest&op=docks&op2=fishingchat");
					blocknav("runmodule.php?module=oceanquest&op=docks&op2=enter");
				}
			break;
			case 41:
				output("You reel back in an old boot.  Garbage!");
			break;
			case 42:
				output("You reel back in a toilet seat.  Garbage!");
			break;
			case 43:
				output("You reel back in a dead rat.  Ewwww!");
			break;
			case 44:
				output("You reel back in a dead fish.  How did that got onto your hook? You pull off the fish and find it's a magic fish!");
				output("You start talking to it and realize it may be a magic fish, but it's still dead.  All the other fishermen look at you and snicker.");
				output("`n`nYou lose a `&charm`7.");
				$session['user']['charm']--;
			break;
			case 45:
				output("You reel back a magic fish! Yay!");
				output("The fish looks at you with a twinkle... `4'If you grant me my freedom, I will give you one wish.'");
				addnav("Ask for Wish","runmodule.php?module=oceanquest&op=docks&op2=wishfish");
				addnav("Weigh and Gut the Fish","runmodule.php?module=oceanquest&op=docks&op2=weighgut");
			break;
			case 46:
				output("You reel back in a gold piece!");
				$session['user']['gold']++;
			break;
			case 47:
				output("You feel a huge fish on your line! You fight to reel it in but you're pulled into the water...");
				output("`n`nBy the time you dry off and the rest of the fishermen stop laughing, you realize you've lost 2 charm.");
				$session['user']['charm']++;
			break;
			case 48:
				output("You feel a hook of another fishermen catch on your hand. `\$Ouch!!!`7");
				if ($session['user']['hitpoints']>5){
					$session['user']['hitpoints']-=4;
					output("You lose 4 hitpoints!");
				}else output("Luckily you remove the hook without any injury.");
			break;
				output("You notice your worm falling off your hook.  Oh well.");
			break;
			case 49:
				$rand=e_rand(1,3);
				if ($rand==1){
					output("Your pole slips from your and and floats away.  Looks like you'll need to buy a new fishing pole.");
					$allprefs['pole']=0;
					blocknav("runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
				}else{
					output("Your pole slips from your hands but you grasp it desperately.  Phew! You almost lost your pole!");
				}
			break;
			case 50:
				output("You hook a seagull! Eww!");
			break;
		}
		if ($weight>0){
			$pounds=floor($weight/16);
			$ounces=$weight-($pounds*16);
			output("`n`nYou check the weight:`n`n`&");
			if ($pounds>0) output("%s %s%s`7",$pounds,translate_inline($pounds>1?"Pounds":"Pound"),translate_inline($ounces>0?",":""));
			if ($ounces>0) output("`&%s %s`7",$ounces,translate_inline($ounces>1?"Ounces":"Ounce"));
			$allprefs['numberfish']++;
			$allprefs['fishweight']+=$weight;
			if ($weight>$allprefs['bigfish']){
				output("`n`nThis is the biggest fish you've ever caught!");
				$allprefs['bigfish']=$weight;
			}
			if ($weight>get_module_setting("dockfish")){
				output("`n`nYou've caught the biggest fish on the dock! Your name will be immortalized on the dock...");
				if ($weight<100) output("at least until someone else catches a bigger one.");
				else{
					output("You have caught the biggest fish that can be caught at the dock; a one in 2 million chance to happen!");
					output("You gain 10 extra turns for such an amazing accomplishment!");
					$session['user']['turns']+=10;
				}
				set_module_setting("dockfish",$weight);
				set_module_setting("dockfishangler",$session['user']['name']);
			}
		}
		addnav("Dock Fishing");
		$fishingtoday=$allprefs['fishingtoday'];
		if ($fishingtoday<5){
			$fishingleft=5-$fishingtoday;
			addnav("More Fishing","runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
			output("`n`n`c`@Fishing Turns Left: `^%s`c",$fishingleft);
		}else{
			output("`n`n`c`\$No Fishing Turns Left`7`c");
			$allprefs['bait']=0;
		}
		addnav("Read Rules","runmodule.php?module=oceanquest&op=docks&op2=fishingrules");
		addnav("Chat with Fishermen","runmodule.php?module=oceanquest&op=docks&op2=fishingchat");
		addnav("Docks");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op2=="wishfish"){
		output("`b`c`^Wishing Fish`b`c`7`n");
		output("You start to name your wish when suddenly the fish stops you.  `4'No no no.  I'm sorry, I forgot to specify.  I can grant any wish you want as long as you wish for a `%gem`.'`7");
		output("`n`nYou shrug and take the `%gem`7.");
		$session['user']['gems']++;
		if ($allprefs['fishingtoday']<5) addnav("More Fishing","runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	if ($op2=="weighgut"){
		output("`b`c`^Wishing Fish`b`c`7`n");
		output("Figuring that the talking fish is probably a liar, you bring him over to the scale.");
		$weight=e_rand(32,50);
		$pounds=floor($weight/16);
		$ounces=$weight-($pounds*16);
		output("You check the weight:`n`n`&");
		output("%s %s%s",$pounds,translate_inline($pounds>1?"Pounds":"Pound"),translate_inline($ounces>0?",":""));
		if ($ounces>0) output("%s %s",$ounces,translate_inline($ounces>1?"Ounces":"Ounce"));
		$allprefs['numberfish']++;
		$allprefs['fishweight']+=$weight;
		if ($weight>$allprefs['bigfish']){
			output("`n`nThis is the biggest fish you've ever caught!");
			$allprefs['bigfish']=$weight;
		}
		if ($weight>get_module_setting("dockfish")){
			output("`n`nYou've caught the biggest fish on the dock! Your name will be immortalized on the dock... at least until someone else catches a bigger one.");
			set_module_setting("dockfish",$weight);
			set_module_setting("dockfishangler",$session['user']['name']);
		}
		set_module_pref('allprefs',serialize($allprefs));
		if ($allprefs['fishingtoday']<5) addnav("More Fishing","runmodule.php?module=oceanquest&op=docks&op2=godockfishing");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");		
	}
	//Pub
	if ($op2=="seasidepub"){
		output("`^`b`cThe Seaside Pub`b`c`7`n");
		output("You walk into the `iSeaside Pub`i and smell the aroma of the sea; the salt, the fish, and the mead all mingle to a strangely pleasant and reassuring smell.");
		output("You listen to the chatter of the patrons. A group of musicians play old sea chanties in the corner.  `@Buck the Bartender`7 serves up some drinks. You see several sailors sitting at tables.");
		output("After listening for a little while, you catch the names of several of them: `&Ulber`7, `QTrandor`7, `!Quint`7, `)Piper`7, and `\$Rinto`7.");
		oceanquest_pubnav();
	}
	if ($op2=="pubbar"){
		output("`^`b`cThe Seaside Pub`b`c`7`n");
		output("You stride over to the bar and tap the counter to get `@Buck's`7 attention.");
		output("He seems occupied for the moment so you look around. You see some nice bottles of Rum next to some very nasty looking bottles of something called 'Salty Dog Spirits'.");
		output("You pop some stale peanuts in your mouth and blanch a little.  After playing with some of the");
		if ($allprefs['piece4']=="" || $allprefs['piece4']==0){
			output("`^<a href=\"runmodule.php?module=oceanquest&op=docks&op2=coasters\">coasters</a>",true);
			addnav("","runmodule.php?module=oceanquest&op=docks&op2=coasters");
		}else output("coasters");
		output("`7for a little while, you tap the counter and make one of those `#'AHEM'`7 sounds.");
		output("`n`n`@Buck`7 wipes down the counter and asks `@'So what's your poison?'");
		output("`n`n`7You take a look at the prices of all the drinks:");
		$ale=$session['user']['level']*get_module_setting("price1");
		$mead=$session['user']['level']*get_module_setting("price2");
		$rum=$session['user']['level']*get_module_setting("price3");
		$saltydog=$session['user']['level']*get_module_setting("price4");
		$round=get_module_setting("round");
		$aleround=$ale*$round;
		$meadround=$mead*$round;
		$rumround=$rum*$round;
		$saltydoground=$saltydog*$round;
		output("`n`n`&Ale: `^%s Gold`n`&Mead: `^%s Gold`n`&Rum: `^%s Gold`n`&Salty Dog:`^%s Gold",$ale,$mead,$rum,$saltydog);
		output("`n`n`5Round of `&Ale`5 for everyone in the Bar: `^%s Gold",$aleround);
		output("`n`5Round of `&Mead`5 for everyone in the Bar: `^%s Gold",$meadround);
		output("`n`5Round of `&Rum`5 for everyone in the Bar: `^%s Gold",$rumround);
		output("`n`5Round of `&Salty Dogs`5 for everyone in the Bar: `^%s Gold",$saltydoground);
		oceanquest_drinknav();
		oceanquest_pubnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=pubbar");
	}
	if ($op2=="coasters"){
		output("`^`b`cThe Seaside Pub`b`c`7`n");
		output("You take a closer look at the coaster and notice something odd about it.");
		if ($allprefs['unlockdecree']==1){
			output("You realize it's one of the pieces of the `^Decree of Passage`7 that you've been looking for! Excellent!!");
		}else{
			output("You can't figure out what it is, but you decide to keep it and tuck it into a pocket.");
		}
		$allprefs['piece4']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Return to the Bar","runmodule.php?module=oceanquest&op=docks&op2=pubbar");
	}
	if ($op2=="pubdrink"){
		$drinkorder=translate_inline(array("","Ale","Mead","Rum","Salty Dog","Round of Ale","Round of Mead","Round of Rum","Round of Salty Dogs"));
		output("`^`b`cThe Seaside Pub`b`c`7`n");
		$buff=0;
		if ($session['user']['gold']<$op4){
			output("You place your order and `@Buck`7 asks for some gold.  `@'It looks like you're a little short to order a  %s!'",$drinkorder[$op3]);			
		}else{
			if ($op3<=4){
				if (is_module_active("drinks")){
					if (get_module_pref("drunkeness","drinks")>=get_module_setting("maxdrunk","drinks")){
						output("You try to place your order but `@Buck`7 notices that you're too drunk to order.");
						output("`n`n`@'Go sober up. I can't serve you in that state!'");
					}else{
						$buff=$op3;
						if ($op3==1) $drunk=33;
						elseif ($op3==2) $drunk=45;
						elseif ($op3==3) $drunk=50;
						elseif ($op3==4) $drunk=60;
						increment_module_pref("drunkeness",$drunk,"drinks");
					}
				}else{
					if ($allprefs['drinktoday']>0){
						output("You try to place your order but `@Buck`7 doesn't like your look.");
						output("`n`n`@'I'm sorry.  I don't think you can handle any more alcohol today. Come back tomorrow.'");
					}else{
						$buff=$op3;
					}
				}
			}else{
				output("You pay up `^%s gold`7 and `@Buck`7 announces to the bar `@'Hey Everyone! `^%s`@ just bought a round of %s!'",$op4,$session['user']['name'],$drinkorder[$op3]);
				$session['user']['gold']-=$op4;
				$allprefs['round'.$op3]++;
				output("`n`n`7All the patrons raise a glass and shout `i`%'Hazzah'`i`7 to you.");
			}
		}
		if ($buff>0){
			$allprefs['drinktoday']++;
			$session['user']['gold']-=$op4;
			if ($buff==1){
				output("`@Buck`7 pulls out a glass and pours a foamy ale from a tapped barrel behind him.  He slides it down the bar, and you catch it with your warrior-like reflexes.");
				output("`n`nTurning around, you take a big chug of the hearty draught.`n`n You gain a turn and get a buzz!");
				$session['user']['turns']++;
				apply_buff('buzz',array(
					"name"=>"`#Buzz",
					"rounds"=>10,
					"wearoff"=>"Your buzz fades.",
					"atkmod"=>1.25,
					"roundmsg"=>"You've got a nice buzz going.",
				));
			}elseif ($buff==2){
				output("`@Buck`7 turns around and pours a nice glass of Mead.  He slides it to you and you catch it with a smile. `#'Bottoms down!'`7 you say, showing off your amazing wit!`n`nYou get a buzz!");
				apply_buff('buzz',array(
					"name"=>"`#Buzz",
					"rounds"=>5,
					"wearoff"=>"Your buzz fades.",
					"atkmod"=>1.5,
					"roundmsg"=>"You've got a nice buzz going.",
				));
			}elseif ($buff==3){
				output("`@Buck`7 collects your gold and pours out some of the finest rum that can be made from sugar cane scraped off the shoes of mill workers. `#'Yo ho ho!'`7 you say, acting like a rascally pirate!`n`n You gain a turn and get a buzz!");
				$session['user']['turns']++;
				apply_buff('buzz',array(
					"name"=>"`#Buzz",
					"rounds"=>15,
					"wearoff"=>"Your buzz fades.",
					"atkmod"=>1.1,
					"roundmsg"=>"You've got a nice buzz going.",
				));
			}elseif ($buff==4){
				output("`@Buck`7 combines a couple of drinks and you watch smoke puff from it.  So that's what's in a Salty Dog!");
				$randdrink=e_rand(0,2);
				if ($randdrink==2) output(" You gain two turns but you don't feel so well.");
				elseif ($randdrink==1) output("You gain a turn but you don't feel so well.");
				else output ("You don't feel so well.");
				$session['user']['turns']+=$randdrink;
				apply_buff('buzzkill',array(
					"name"=>"`4Bad Buzz",
					"rounds"=>10,
					"wearoff"=>"Your buzz fades.",
					"atkmod"=>.8,
					"roundmsg"=>"You've got a dreadful buzz going.",
				));
			}
		}
		oceanquest_drinknav();
		oceanquest_pubnav();
		blocknav("runmodule.php?module=oceanquest&op=docks&op2=pubbar");
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op2=="pubsingers"){
		output("`^`b`cThe Seaside Pub`b`c`7`n");
		output("You go over by the stage and listen to some of the old sea shanties.`n`n");
		switch(e_rand(1,6)){
			case 1:
				output("`i`#What shall we do with the drunken sailor,`nEarly in the morning?`n`n");
				output("Put him in the longboat ’til he’s sober,`nPut him in the longboat ’til he’s sober,`nPut him in the longboat ’til he’s sober,`nEarly in the morning.`n`n");
				output("Heave-ho and up she rises,`nHeave-ho and up she rises,`nHeave-ho and up she rises,`nEarly in the morning!!`i");
			break;
			case 2:
				output("`i`^Show me the way to go home`nI'm tired and I want to go to bed`nI had a little drink about an hour ago`nAnd it went right to my head`nWhere ever I may roam`nOn land or sea or foam`nYou will always hear me singing this song`nShow me the way to go home!`i");
			break;
			case 3:
				output("`i`!Come all ye young fellows that follow the sea,`nto my way haye, blow the man down,`nAnd pray pay attention and listen to me,`nGive me some time to blow the man down.`n`nI'm a deep water sailor just in from Hong Kong,`nto my way haye, blow the man down,`nif you'll give me some grog, I'll sing you a song,`nGive me some time to blow the man down.`i");
			break;
			case 4:
				output("`i`%Fifteen men on a dead man's chest`nYo ho ho and a bottle of rum`nDrink and the devil had done for the rest`nYo ho ho and a bottle of rum.`nThe mate was fixed by the bosun's pike`nThe bosun brained with a marlinspike`nAnd cookey's throat was marked belike`nIt had been gripped by fingers ten;`nAnd there they lay, all good dead men`nLike break o'day in a boozing ken`nYo ho ho and a bottle of rum.`i");
			break;
			case 5:
				output("`i`@Im sailing away, set an open course for the virgin sea`nIve got to be free, free to face the life that's ahead of me`nOn board, Im the captain, so climb aboard`nWell search for tomorrow on every shore`nAnd Ill try, oh lord, Ill try to carry on`n`nI look to the sea, reflections in the waves spark my memory`nSome happy, some sad`nI think of childhood friends and the dreams we had`nWe live happily forever, so the story goes`nBut somehow we missed out on that pot of gold`nBut well try best that we can to carry on.`i");
				output("`n`n`7Something doesn't quite seem right with this song... it doesn't seem like one of the good 'ole classic sea shanties that you remember.");
			break;
			case 6:
				output("`i`QWe pillage, we plunder, we rifle and loot.`nDrink up me 'earties, Yo Ho!`nWe kidnap and ravage and don't give a hoot.`nDrink up me 'earties, Yo Ho!`n`nYo Ho, Yo Ho! A pirate's life for me.`n`nWe extort, we pilfer, we filch and sack.`nDrink up me 'earties, Yo Ho!`nMaraud and embezzle and even hijack.`nDrink up me 'earties, Yo Ho!`n`nYo Ho, Yo Ho! A pirate's life for me.`i");
			break;
		}
		oceanquest_pubnav();
	}
	if ($op2=="pubchat"){
		output("`^`b`cThe Seaside Pub`b`c`7`n");
		$round8=$allprefs['round8'];
		$round7=$allprefs['round7'];
		$round6=$allprefs['round6'];
		$round5=$allprefs['round5'];
		if (($op3==1 && $round8>=1) || ($op3==2 && $round7>=1) || ($op3==3 && ($round8>=1 || $round7>=1 || $round6>=1 || $round5>=1)) || ($op3==4 && $round6>=1) || ($op3==5 && $round5>=1)) $chat=1;
		else $chat=0;
		$sailor=translate_inline(array("","`&Ulber","`QTrandor","`!Quint","`)Piper","`\$Rinto"));
		$sailorcolor=translate_inline(array("","`&","`Q","`!","`)","`\$"));
		$sailordrinks=translate_inline(array("","Salty Dogs","Rum","drinks","Mead","Ale"));
		if ($chat==1){
			output("%s`7 looks at you and smiles. %s'Anyone willing to buy a round of %s for the Pub is worthy of conversation.'",$sailor[$op3],$sailorcolor[$op3],$sailordrinks[$op3]);
			addnav("Chat");
			addnav("Ask About the `4`iAlbania`i","runmodule.php?module=oceanquest&op=docks&op2=pubchatboats&op3=$op3&op4=albania");
			addnav("Ask About the `@`iRhodando`i","runmodule.php?module=oceanquest&op=docks&op2=pubchatboats&op3=$op3&op4=rhodando");
			addnav("Ask About the `&`iCorinth`i","runmodule.php?module=oceanquest&op=docks&op2=pubchatboats&op3=$op3&op4=corinth");
			addnav("Ask About the `6`iFree Stone`i","runmodule.php?module=oceanquest&op=docks&op2=pubchatboats&op3=$op3&op4=freestone");
			addnav("Ask About the `^`iLuckstar`i","runmodule.php?module=oceanquest&op=docks&op2=pubchatboats&op3=$op3&op4=luckstar");
		}else{
			output("%s`7 looks at you and then looks back at his empty glass. You try to strike up a conversation but he just nurses his empty glass and ignores you.",$sailor[$op3]);
		}
		oceanquest_pubnav();
	}
	if ($op2=="pubchatboats"){
		output("`^`b`cThe Seaside Pub`b`c`7`n");
		$sailor=translate_inline(array("","`&Ulber","`QTrandor","`!Quint","`)Piper","`\$Rinto"));
		$sailorcolor=translate_inline(array("","`&","`Q","`!","`)","`\$"));
		if ($op4=="albania" || $op4=="rhodando" || $op4=="freestone" || ($op4=="corinth" && $op3<>3) || ($op4=="luckstar" && $op3<>2)){
			if ($op4=="albania") $ship=translate_inline("`4`iAlbania`i");
			elseif ($op4=="corinth") $ship=translate_inline("`&`iCorinth`i");
			elseif ($op4=="freestone") $ship=translate_inline("`6`iFree Stone`i");
			elseif ($op4=="luckstar") $ship=translate_inline("`^`iLuckstar`i");
			else $ship=translate_inline("`@`iRhodando`i");
			output("%s`7 looks at you and shakes his head.  %s'Bah, I don't know anything about the %s%s. Sorry.'`n`n`7He gets up and walks away.",$sailor[$op3],$sailorcolor[$op3],$ship,$sailorcolor[$op3]);
		}elseif($op4=="luckstar" && $op3==2) {
			if ($allprefs['healed']==1){
				output("You proudly show `QTrandor`7 the healed `^Decree of Passage`7.  He tells you that you should be able to get on the `i`^Luckstar`i`7 now and you feel very hopeful.");
				if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/recovered.gif></td></tr></table></center><br>");
			}elseif ($allprefs['healed']==3){
				output("You mention how you had to take the pieces to a healer to get them repaired.  He nods and tells you he'd love to see them when they're all back together.");
			}elseif ($allprefs['healed']==4){
				output("You mention how you had to take the pieces to a healer to get them repaired.  He nods and asks why you haven't gone to pick it up yet. You don't have an answer for him.");
			}elseif ($allprefs['piece1']==1 && $allprefs['piece2']==1 && $allprefs['piece3']==1 && $allprefs['piece4']==1){
				output("You take out all four pieces of the `^Royal Decree of Passage`7 and proudly show them to `QTrandor`7.");
				output("`n`n`Q'Well I'll be a smoked fish in a frying pan, you've found all four pieces.  Well, what are you doing here? Why aren't you sailing out to Pilinoria?'`n`n");
				if (get_module_setting("pictures")==0 && get_module_pref("user_pictures")==0) rawoutput("<br><center><table><tr><td align=center><img src=modules/oceanquest/images/quartered.gif></td></tr></table></center><br>");
			}else{
				output("`QTrandor`7 looks at you and smiles.  `Q'So you want to know how to have a chance to travel on the `^`iLuckstar`i`Q? Well, it's not an easy to vessel to gain passage on.");
				output("If you're really interested, I can tell you that you'll need a `^Royal Decree of Passage`Q.  Now, I had one myself at one time, but it got torn into about four pieces and I can't for the life of me remember where I put those pieces.  That's what happens when I'm sober... my memory fades.'");
				output("`n`n`7He pauses and reflects for a second, then takes drink of the Rum that you bought. `Q'Okay, now that I think about it, I remember a little more.  The `^`iLuckstar`i`Q sails to a couple of ports; one of which is the port of Pilinoria.");
				output("Last I heard, our ruler, `&%s`Q, would really like to set up a trade treaty with the country of Pilinoria but is having trouble getting an envoy to talk with the King there.'",get_module_setting("ruler"));
				output("`n`n'If you could find that `^Royal Decree of Passage`Q, you could probably make it to Pilinoria to try your hand at diplomacy.'");
				output("`n`n`7He takes one last swig of Rum and drains the glass.  After releasing a most putrid belch in your general direction, he laughs and says `Q'But boy it might be tricky to find those 4 pieces of the `^Decree`Q.'");
				if ($allprefs['piece1']==1 || $allprefs['piece2']==1 || $allprefs['piece3']==1 || $allprefs['piece4']==1){
					$total=$allprefs['piece1'] + $allprefs['piece2'] + $allprefs['piece3'] + $allprefs['piece4'];
					output("`n`n`7You smile and remember that there's %s of paper that you've been carrying around.",translate_inline($total>1?"some scraps":"a scrap"));
					output("`QTrandor`7 takes a look.");
					if ($allprefs['piece1']==1) output("`Q'Well I'll be.  Here is that piece that I lost in the forest that day I was digging for worms.'`n`n");
					if ($allprefs['piece2']==1) output("`7He carefully picks up this piece and reflects. `Q'This looks like the piece I left over by the Hall of Records.  I can't believe you found it.'`n`n");
					if ($allprefs['piece3']==1) output("`7He gives a snicker when he picks up this piece. `Q'So you paid off old Hoglin to get this scrap?  I certainly hope it turns out to be worth it for you!'`n`n");
					if ($allprefs['piece4']==1) output("`Q'I wasn't sure anyone would notice this piece.  I thought `@Buck`Q would pass it off as a coaster until the end of time!'`7 You feel a little proud about finding that particular piece.`n`n");
				}
				if ($allprefs['unlockdecree']=="" || $allprefs['unlockdecree']==0){
					$allprefs['unlockdecree']=1;
					set_module_pref('allprefs',serialize($allprefs));
				}
				addnav("Decree Pieces");
				if (($allprefs['piece1']=="" || $allprefs['piece1']==0) && $allprefs['round7']>1) addnav("Ask About `^Decree Piece 1","runmodule.php?module=oceanquest&op=docks&op2=decree&op3=1");
				if (($allprefs['piece2']=="" || $allprefs['piece2']==0) && $allprefs['round7']>2) addnav("Ask About `^Decree Piece 2","runmodule.php?module=oceanquest&op=docks&op2=decree&op3=2");
				if (($allprefs['piece3']=="" || $allprefs['piece3']==0) && $allprefs['round7']>3) addnav("Ask About `^Decree Piece 3","runmodule.php?module=oceanquest&op=docks&op2=decree&op3=3");
				if (($allprefs['piece4']=="" || $allprefs['piece4']==0) && $allprefs['round7']>4) addnav("Ask About `^Decree Piece 4","runmodule.php?module=oceanquest&op=docks&op2=decree&op3=4");
			}
		}elseif($op4=="corinth" && $op3==3){
			$fishweight=get_module_setting("fishmin");
			$pounds=floor($fishweight/16);
			output("`!Quint`7 looks at you and drinks a toast to your name. `!'Getting on the `&`iCorinth`i`! is easy enough.  You've just got to prove you're a worthy angler.  Head to the docks if you haven't caught enough fish.  I believe you'll need to catch about `^%s %s`! or so of fish before you're considered good enough to fish on that ship.'",$pounds,translate_inline($pounds>1?"pounds":"pound"));
		}
		oceanquest_pubnav();
	}
	if ($op2=="decree"){
		output("`^`b`cThe Seaside Pub`b`c`7`n");
		if ($op3==1){
			output("`QTrandor`7 fondly remembers that 2nd round of Rum you bought for the bar.  It seems to have 'improved' his memory a bit.");
			output("`n`n`Q'Oh yeah, now that you mention it, I remember dropping a piece of that decree in the forest somewhere.  I bet that will be tough to find!'");
		}elseif ($op3==2){
			output("`QTrandor`7 gives you a knowing look and thanks you for that 3rd round of Rum you bought for the bar.  You start to figure out the key to how to loosen his tongue.");
			output("`n`n`Q'You won't believe where this piece is. I actually left it at the `&Hall of Records`Q. No joke.  Go look around at the Hall of Fame and I think you'll find it there.'");		
		}elseif ($op3==3){
			output("You remind `QTrandor`7 of that time you bought your 4th round of Rum for the bar and he smiles at you.");
			output("`n`n`Q'I can tell you this much. I'd still have a piece of that decree if it wasn't for the fact that I owe money to that darn Bait Shop Owner.'");
		}elseif ($op3==4){
			output("Hoping `QTrandor's`7 memory can last long enough to answer one last question, you press for knowledge about that last piece of the decree.");
			output("`Q`n`n'This will be very tricky.  I think `@Buck's`Q using it as a coaster at the bar.  You have to look VERY carefully to find it... but it's there. Just keep looking and you'll find it.'");
		}
		addnav("Decree Pieces");
		if (($allprefs['piece1']=="" || $allprefs['piece1']==0) && $op3<>1) addnav("Ask About `^Decree Piece 1","runmodule.php?module=oceanquest&op=docks&op2=decree&op3=1");
		if (($allprefs['piece2']=="" || $allprefs['piece2']==0) && $allprefs['round7']>2 && $op3<>2) addnav("Ask About `^Decree Piece 2","runmodule.php?module=oceanquest&op=docks&op2=decree&op3=2");
		if (($allprefs['piece3']=="" || $allprefs['piece3']==0) && $allprefs['round7']>3 && $op3<>3) addnav("Ask About `^Decree Piece 3","runmodule.php?module=oceanquest&op=docks&op2=decree&op3=3");
		if (($allprefs['piece4']=="" || $allprefs['piece4']==0) && $allprefs['round7']>4 && $op3<>4) addnav("Ask About `^Decree Piece 4","runmodule.php?module=oceanquest&op=docks&op2=decree&op3=4");
		oceanquest_pubnav();
	}
	//Talk to Sailors
	if ($op2=="talktosailors"){
		output("`c`b`^Casual Conversations`b`c`7`n");
		output("You decide to strike up a chat or two with some of the sailors.");
		switch(e_rand(1,26)){
			case 1:
				output("You find a group of sailors and they're singing one of their favorite sea shanties.  You try to get their attention but you can't seem to break into the conversation.");
				output("`n`nSuddenly, they stop singing and look confused.  It seems they've forgotten some of the lyrics! They turn to you and look for help.");
				output("`n`n`6'Hey, you look like an old limey.  What are the missing words?'`7");
				output("`n`nThey start singing again and you listen closely:`n`n");
				output("`&`iI'm a deep water sailor just in from _____ ______,`nto my way haye, blow the man down!`i");
				output("`n`n`7They look at you for help.  Where is the sailor from?`n");
				$search = translate_inline("Answer");
				rawoutput("<form action='runmodule.php?module=oceanquest&op=docks&op2=location' method='POST'><input name='name' id='name'><input type='submit' class='button' value='$search'></form>");
				addnav("","runmodule.php?module=oceanquest&op=docks&op2=location");
			break;
			case 2:
				output("You see a nervous looking fellow and ask him what's on his mind. He looks at you with a flash of paranoia.");
				output("`n`n`4'Did you steal my `Qcopper ring`4? Did you??? It's my precciiioousssssss.'`n`n`7");
				if ($allprefs['opencave']==1){
					output("You remember using the `QCopper Ring`7 to open the secret cave entrance but you decide you don't want to mention that to him.");
					output("You tell him that you don't have the ring and quickly walk away.");
				}elseif ($allprefs['copperring']==1){
					output("You feel the `QCopper Ring`7 in your pocket and tell him that you don't have his `QCopper Ring`7 in your pocket so he should quit bugging you.");
					output("You walk away from him hastily.");
				}else{
					output("You try to reassure him that you didn't steal the copper ring and in fact you don't know anything about it.");
					output("`n`nHe stares at you intently and then mutters something about how maybe he lost it fishing on the `i`&Corinth`i`7 and how he'll have to check there.");
					output("`n`nYou try to get him to tell you what's so important about it but he just mutters something about a `4'secret entrance'`7 and he wanders away.");
				}
			break;
			case 3: case 4: case 5:
				output("`n`n`#'What secrets can you tell me about the open sea?'`7 you ask.");
				output("`n`nOne of the sailors looks at you with a grin. `5'The best advice I can give you is to never vomit into the wind!'");
			break;
			case 6: case 7:
				output("`n`nA sailor approaches you and asks if you'd like to buy some magic beans.");
				output("`n`n`#'Sorry buddy, but that's the wrong story. Go find a boy with a cow and you'll probably have better luck,'`7 you tell him.");
				output("`n`nHe thanks you and wanders off looking for a boy with a goat.");
			break;
			case 8: case 9:
				output("`n`nA witch approaches you asking if you'd like to see her house made of candy.");
				output("`n`n`#'Sorry lady, but I think you're looking for a couple of little kids.  I'd go look in the forest,'`7 you advise her.");
				output("`n`nShe nods in agreement and runs off into the forest.");
			break;
			case 10: case 11:
				output("`n`nA cow comes by carrying a trampoline and you hear her moo something about getting to the moon.");
				output("You're about to say something when you see a very distraught Fork complaining about how his girlfriend the Spoon may be cheating on him.");
				output("`n`n`#'I would go talk with the Dish if I were you.  I have a feeling he may know something about it.'");
				output("`n`n`7The Fork looks at you with a glint in his eye.  You hear him say something about getting his friend Knife to help him teach Dish a lesson.");
			break;
			case 12:
				output("A sailor comes by carrying a piece of paper.  You ask him what it is and he explains that it's a note from the bank.  He seems VERY enthusiastic about how wonderful the bank is.");
				output("`n`n`@'Why, Ye Olde Bank can take care of all your financial needs.  They do a wonderful job calculating interest on current holdings, storage of finances, document verification, and even investment opportunities.'");
				output("`n`n`7You wonder why he's so excited about the bank when you notice that he seems to have a strange family resemblance to the banker. Hmmmm...");
			break;
			case 13: case 14:
				output("`n`n`2'Are you trying to pick a fight with me???'`7 asks a particularly aggressive sailor.`n`n");
				output("`7Since you weren't trying to pick a fight, you tell the sailor so.");
				output("`n`n`2'Oh, okay,'`7 he says as he walks away.  He seems a little disappointed.");
			break;
			case 15:
				output("`n`nA shady looking character offers an even money bet if you want to bet `^500 gold`7 on a flip of a coin.`n`n");
				if ($session['user']['gold']>=500){
					output("Are you interested?");
					addnav("Bet 500 Gold");
					addnav("Call Heads","runmodule.php?module=oceanquest&op=docks&op2=flip&op3=heads");
					addnav("Call Tails","runmodule.php?module=oceanquest&op=docks&op2=flip&op3=tails");
				}else{
					output("Not having `^500 gold`7 you tell him `#'No thanks'`7 and walk away.");
				}
			break;
			case 16: case 17:
				output("`n`n`#'What secrets can you tell me about the open sea?'`7 you ask.");
				output("`n`nOne of the sailors looks at you with a grin. `4'If you're under water and you're running out of air, swim to the surface!'");
			break;
			case 18: case 19:
				output("`n`n`#'What secrets can you tell me about the open sea?'`7 you ask.");
				output("`n`nOne of the sailors looks at you with a grin. `3'Don't steal from the captain.  The punisment can be very severe.'`7 He shows you a huge scar on his back and you take his advice to heart.");
			break;
			case 20: case 21:
				output("`n`n`#'What secrets can you tell me about the open sea?'`7 you ask.");
				output("`n`nOne of the sailors looks at you with a grin. `2'Personally, I never eat fish before sailing.  I think the fish know.'");
			break;
			case 22: case 23:
				output("`n`n`#'What secrets can you tell me about the open sea?'`7 you ask.");
				output("`n`nOne of the sailors looks at you with a grin. `1'When you hear someone yell `!'Man overboard!'`1 just hope they aren't talking about you.'");
			break;
			case 24: case 25: case 26:
				output("`n`nYou see a very sad sailor and ask him if there's anything you can do for him.");
				output("`n`n`@'Unless you have my Iron Star, I don't want anything to do with you.  I think I lost it somewhere on the `i`^Luckstar`@`i but I can't be sure.  Please return it to me if you ever find it.'`7");
				if ($allprefs['ironstar']==1){
					output("`n`nYou had the sailor the iron star that you found in `i`^Luckstar`i`7.  He looks at you and says `@'Err... No, that's not what I'm talking about. But thanks for thinking of me.'`7");
				}
			break;
		}
		addnav("Chat");
		addnav("`3Talk to other Sailors","runmodule.php?module=oceanquest&op=docks&op2=talktosailors");
		addnav("Leave");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");		
	}
	if ($op2=="flip"){
		output("`c`b`^Casual Conversations`b`c`7`n");
		$badchance=e_rand(1,5);
		if ($badchance<3){
			output("You call %s in the air and he reveals the coin to show that you're right!",$op3);
			output("`n`nHe hands you `^500 gold`7 and grumbles something under his breath about how it was SUPPOSED to be rigged.");
			$session['user']['gold']+=500;
		}else{
			if ($op3=="heads") $other=translate_inline("tails");
			else $other=translate_inline("heads");
			output("You call %s in the air and it lands %s.  He looks at you with a sly smile, collects his `^500 gold`7 and mentions how `%'sometimes luck isn't with you'`7 and wanders away.",$op3,$other);
			$session['user']['gold']-=500;
		}
		addnav("Chat");
		addnav("`3Talk to other Sailors","runmodule.php?module=oceanquest&op=docks&op2=talktosailors");
		addnav("Leave");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");		
	}
	if ($op2=="location"){
		output("`c`b`^Casual Conversations`b`c`7`n");
		$name = httppost('name');
		$name1 = strtolower($name);
		if ($name1=="hong kong"){
			output("The sailors look at you and smile. `6'YES! That's the place. Hong Kong! How could we have forgotten??'`7");
			output("`n`nYou feel a bit of pride and then ask if they have any advice for an 'old limey' like yourself.");
			output("`n`n`6'Why, the best advice we can give you is to be generous to the old sailors in the `i`#Seaside Pub`i`6.  Buying a round of drinks can loosen the tongues of almost any of them; but some only drink a certain spirit.'`n`n`7");
			if ($allprefs['round8']>=1 || $allprefs['round7']>=1 || $allprefs['round6']>=1 || $allprefs['round5']>=1){
				output("You already knew this much, but thank them for their help nonetheless.");
			}
		}else{
			output("The sailors look at you and shake their heads.  `6'No way.  The sailor isn't from %s`6! You're no help at all!'",$name);
			output("`n`n`7You feel like you've disappointed them.  Oh well!");
		}
		addnav("Chat");
		addnav("`3Talk to other Sailors","runmodule.php?module=oceanquest&op=docks&op2=talktosailors");
		addnav("Leave");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");		
	}
	//Getting onto the Albania, Rhodando, or Freestone
	if ($op2=="albania" || $op2=="rhodando" || $op2=="freestone"){
		if ($op2=="albania") $ship=translate_inline("The Albania");
		elseif ($op2=="rhodando") $ship=translate_inline("The Rhodando");
		elseif ($op2=="freestone") $ship=translate_inline("The Free Stone");
		output("`b`c`^`i%s`i`b`c`n`7",$ship);
		output("`7One of the sailors asks you what you want.`n`n");
		output("`#'I would like passage on your ship.'`n`n");
		output("`7The sailor looks you up and down and sniggers `3'This ain't a passenger ship.  Go away.'");
		output("`n`n`7Not having any other choice, you head back to the docks.");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	//Getting onto the Corinth (Fishing Vessel)
	if ($op2=="corinth"){
		output("`b`c`^`iThe Corinth`i`b`c`n`7");
		output("You approach a sailor to ask about passage but realize that this isn't for the open sea but rather a fishing vessel. You ask about going on a fishing expedition and one of the sailors comes over to size you up.`n`n");
		if ($allprefs['fishweight']>=get_module_setting("fishmin")){
			if ($allprefs['fishingtoday']<=1){
				output("`3'Ah, we've heard of your fishing prowess.  Indeed, you're welcome to come with us on an expedition.'");
				if ($allprefs['bait']==1 && $allprefs['pole']==1){
					output("`n`n`7It will cost a `@forest turn`7 to go on an expedition though!");
					if ($session['user']['turns']>0){
						output("You'll be charged the turn as soon as you step on board.");
						output("`n`nThe captain mentions that there aren't many rules on the ship except regarding your catch.");
						output("`&'I get your fish.  You get anything else you catch. Sorry, that's the rules.'");
						output("`n`n`7Not really having much use for fish, you figure this will still be worth your time.");
						if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Fishing Expedition","runmodule.php?module=oceanquest&op=fishingexpedition&op3=payturn");
						else addnav("Fishing Expedition","runmodule.php?module=oceanquest&op=fishingexpeditiona&op3=payturn");
					}else{
						output("It looks like you don't have the energy to go on an expedition right now.");
					}
				}else{
					output("`n`n'Unfortunately, we don't provide equipment.  Go stop at the Bait Shop and we can set sail.'");
				}
			}else{
				output("`3'Well, you'd be welcome to come fishing with us, but you can only go fishing once a day and can tell by your smell that you've already cast your line today.'");
			}
		}else{
			output("`3'Fishing on this vessel isn't for amateurs.  You have to know your way around a fishing pole before you can sail with us. Maybe you should go practice a little at the end of the dock first.'");
		}
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
	}
	//Getting onto the Luckstar
	if ($op2=="gotoluckstar"){
		output("`b`c`^`iThe Luckstar`i`b`c`n`7");
		if ($allprefs['sorcerer']==1){
			if ($session['user']['level']<get_module_setting("tradelevel")){
				output("You explain to the captain that you'd like to trade with Pilinoria. He smiles kindly at you. `@'Unfortunately, you won't be able to handle trade negotiations at your level. If you are searching for other adventures across the sea you can come aboard and it will still cost you a `2turn`@ to make the journey.'",get_module_setting("tradelevel"));
			}else{
				output("You explain to the captain that you'd like to explore the seas again.");
				$cost1=get_module_setting("cost1");
				$cost2=get_module_setting("cost2");
				$cost3=get_module_setting("cost3");
				$cost4=get_module_setting("cost4");
				$cost5=get_module_setting("cost5");
				$min=0;
				for ($i=1;$i<=5;$i++) {
					$cost=get_module_setting("cost".$i);
					if ($cost>0){
						$win=$i;
						if (($cost>$cost1 && $cost1>0) || ($cost>$cost2 && $cost2>0) || ($cost>$cost3 && $cost3>0) || ($cost>$cost4 && $cost4>0) || ($cost>$cost5 && $cost5>0)) $win=0;
						if ($win>0) $min=$i;
					}
				}
				if ($min>0){
					if ($allprefs['dktrades']>=get_module_setting("tradeperdk")){
						output("`@'I know you're excited to go sail the seven seas, but I'm going to warn you that you won't be able to trade with Pilinoria.  You've done all the trading you can do this dragon kill. We're shipping out right now and you're welcome to come aboard.  It will still cost you a `2turn`@ to make the trip.'");
					}elseif ($session['user']['gold']>=get_module_setting("cost".$min)){
						output("`@'Please come aboard.  We'll be leaving port any moment.  Remember, it still will take you a `2turn`@ to make the journey over there.'");
					}else{
						output("`@'I don't mind bringing you aboard, but items in Pilinoria are a little expensive.  You'll need to have at least `^%s gold`@ to purchase anything of value to trade there. We're shipping out right now so there's no time to get any more gold to bring with you. If you are searching for other adventures across the sea you can come aboard and it will still cost you a `2turn`@ to make the journey.'",get_module_setting("cost".$min));
					}
				}else{
					output("`@'Unfortunately, there's no trade going on in Pilinoria.  You might want to talk to one of your rulers to ask about checking trade prices for items in Pilinoria. If you are searching for other adventures across the sea you can come aboard and it will still cost you a `2turn`@ to make the journey.'");
				}
			}
			if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Set Sail","runmodule.php?module=oceanquest&op=sailing&op3=payturn");
			else addnav("Set Sail","runmodule.php?module=oceanquest&op=sailinga&op3=payturn");
			$allprefs['lscount']=get_module_setting("inport");
		}elseif ($allprefs['notary']==1){
			$port=get_module_setting("inport");
			if ($allprefs['lsfirst']==""||$allprefs['lsfirst']==0){
				$allprefs['lsfirst']=1;
				output("You show your `@healed`7 and `&notarized`^ Official Royal Decree of Passage`7 to the sailor and ask to board.");
				output("`n`nHe looks over your paperwork and looks up at your reluctantly. `3'I'll have to get the captain to talk to you.'`7  He takes your papers and hops on the ship.");
				output("`n`nA couple of minutes later, a gruff but authoritative figure comes down carrying your `^Decree`7.  He looks you up and down.");
				output("`@'Aye.  Your papers are in order.  You may have free passage on the old `^`iLuckstar`i`@ whenever we're in port.  We're heading over to the land of Pilinoria today if you'd like to join us.'");
				output("`n`n`7You nod appreciation to the captain and hop on board.  It looks like a start to a new adventure!");
				output("`n`n`@'One thing.  I'm sure the adrenaline is rushing through you right now.  That means you won't get too tired from today's journey.  However, I'm going to warn you. Any time you get on the `iLuckstar`i in the future you'll need to have at least one `bforest turn`b otherwise you won't have the stamina to survive the high seas.");
				output("In addition, since you're under `^Royal Decree of Passage`@, if you need to return to the docks at any time you let me know and we'll come back. Also, we're in port once every `^%s `@%s so we may not always be here.'",$port,translate_inline($port>1?"days":"day"));
				output("`n`n`7You make a mental note to yourself... `n1. Remember to have one `@forest turn`7 available to set sail. `n2. Let the captain know if you want to come back at any time. `n3.");
				if ($port>1) output(" The `iLuckstar`i is only in port every `^%s `7days.",$port);
				elseif ($port>0) output(" The `iLuckstar`i is only in port once a day.");
				else output(" The `iLuckstar`i is pretty much always in port.");
				if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Set Sail","runmodule.php?module=oceanquest&op=sailing&op3=payturn");
				else addnav("Set Sail","runmodule.php?module=oceanquest&op=sailinga&op3=payturn");
				$allprefs['lscount']=get_module_setting("inport");
			}else{
				output("`7You do a quick mental review of the rules to boarding the `^`iLuckstar`i`7... `n1. Remember to have one `@forest turn`7 available to set sail. `n2. Let the captain know if you want to come back at any time.`n");
				if ($port>1) output("3. The `iLuckstar`i is only in port every `^%s `7days.",$port);
				elseif ($port>0) output("3. The `iLuckstar`i is only in port once a day.");
				else output("3. The `iLuckstar`i is pretty much always in port.");
				output("`n`nYou come to the `^`iLuckstar`i`7 ready for adventure.");
				if ($session['user']['turns']>0){
					if  ($allprefs['shore']==1) output("As soon as you step on board, the captain informs you that although you can go out sailing with them, they will only be returning to the Docks here.  Passengers will not be able to disembark anywhere else and you will still `@lose a forest turn`7 for coming on board.");
					else output("You step on board and feel a little drained from the concept of being at sea again.  You'll spend a `@turn`7 mentally preparing for the journey if you go.");
					if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Set Sail","runmodule.php?module=oceanquest&op=sailing&op3=payturn");
					else addnav("Set Sail","runmodule.php?module=oceanquest&op=sailinga&op3=payturn");
					$allprefs['lscount']=get_module_setting("inport");
				}else{
					output("However, you just don't have the stamina to make a trip today.  You forgot that you need at least one `@forest fight`7 to sail the `iLuckstar`i.");
				}
			}
		}elseif ($allprefs['healed']==1){
			output("You pull out the `@healed `^Official Royal Decree of Passage`7 and show it to the sailor.  He laughs at you and tosses it back at you.");
			output("`3'Now how do I know this ain't a fake `^Official Royal Decree`3? You need to get this notarized to get on board.'`7`n`n");
			output("You look a little dejected and realize that you'll have to find a notary.  Now where can you go to find a notary???");
		}elseif ($allprefs['piece1']==1 && $allprefs['piece2']==1 && $allprefs['piece3']==1 && $allprefs['piece4']==1){
			output("You pull out the four scraps of paper that spell out `^ Official Royal Decree of Passage`7 and try to line them up and show them to a sailor.");
			output("`3'Hah! Now that's a sick looking lot of paper.  Maybe you'd better take them to get healed up before you try to show them off.");
			$allprefs['healed']=2;
		}else{
			output("`7One of the sailors asks you what you want.`n`n");
			output("`#'I would like passage on your ship.'`n`n");
			output("`7The sailor looks you up and down and sniggers `3'This ain't a passenger ship.  Go away.'");
			output("`n`n`7Not having any other choice, you head back to the docks");
		}
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
		set_module_pref('allprefs',serialize($allprefs));
	}
}
?>