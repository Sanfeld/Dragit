<?php
function oceanquest_island(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$allprefs=unserialize(get_module_pref('allprefs'));
	page_header("Island");
	if ($op2=="landing"){
		output("`c`b`^Island Exploration`b`c`n`7");
		if ($allprefs['shore']==""||$allprefs['shore']==0) output("You step off the `i`^Luckstar`7`i and find yourself on a small island. You take a look around and notice several areas of interest.");
		output("You're a short walk away from a very nice beach that may be worthy of exploration. Towards the center of the island is a menacing cave that may hold adventures.");
		output("Finally, you see a very nice stream that looks like it may hold a refreshing drink.`n`n");
		output("What would you like to do?");
		$allprefs['island']=1;
		$allprefs['shore']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Island Exploration");
		addnav("Go to the Cave","runmodule.php?module=oceanquest&op=island&op2=cave");
		addnav("Explore the Beach","runmodule.php?module=oceanquest&op=island&op2=beach");
		addnav("Go to the Stream","runmodule.php?module=oceanquest&op=island&op2=stream");
		addnav("Return");
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailing");
		else addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailinga");
	}
	if ($op2=="beach"){
		output("`c`b`^Island Exploration`b`c`n`7");
		output("You decide to wander down the beach and explore a little bit.  It turns out to be a pleasant walk.");
		if ($allprefs['coconut']=="" || $allprefs['coconut']==0){
			output("Eventually you find yourself under a palm tree with several beautiful coconuts beckoning for you to collect them.");
			output("`n`nWould you like to get a coconut?");
			addnav("Coconuts");
			addnav("Get a Coconut","runmodule.php?module=oceanquest&op=island&op2=grabcoconut");
			addnav("Return");
			addnav("Return to the Landing","runmodule.php?module=oceanquest&op=island&op2=landing");
		}else{
			output("Eventually, you find yourself standing back at the landing.");
			addnav("Island Exploration");
			addnav("Go to the Cave","runmodule.php?module=oceanquest&op=island&op2=cave");
			addnav("Explore the Beach","runmodule.php?module=oceanquest&op=island&op2=beach");
			addnav("Go to the Stream","runmodule.php?module=oceanquest&op=island&op2=stream");
			addnav("Return");
			if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailing");
			else addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailinga");
		}
	}
	if ($op2=="stream"){
		output("`c`b`^Island Exploration`b`c`n`7");
		output("After a short walk you arrive at the stream.  It looks very inviting and refreshing.");
		addnav("The Stream");
		addnav("Drink from the Stream","runmodule.php?module=oceanquest&op=island&op2=drinkstream&op3=1");
		addnav("Return");
		addnav("Return to the Landing","runmodule.php?module=oceanquest&op=island&op2=landing");
	}
	if ($op2=="drinkstream"){
		output("`c`b`^Island Exploration`b`c`n`7");
		if ($allprefs['firstisland'] =="" || $allprefs['firstisland'] ==0 || $allprefs['firstisland']==2){
			//guaranty stream first trip
			if ($allprefs['firstisland']=="" || $allprefs['firstisland']==0) $allprefs['firstisland'] =1;
			elseif ($allprefs['firstisland']==2) $allprefs['firstisland'] =3;
			$allprefs['stream']=1;
			output("You take a drink from the stream and feel refreshed.  Looking down you notice a `%gem`7!");
			output("`n`nYou find a `%gem`7.");
			$session['user']['gems']++;
		}else{
			if (e_rand(1,40)==1){
				$allprefs['stream']=1;
				if ($op3>1) output("You take another sip from the the stream.");
				else output("You take a sip from the stream.");
				output("The water is cool and refreshing.  You look down and find a `%gem`7!");
				 $session['user']['gems']++;
			}else output("You take a long sip from the water and feel refreshed.");
			$op3++;
			if ($op3>=10){
				output("You've probably spent enough time at the stream today.");
				$allprefs['stream']=1;
			}
		}
		set_module_pref('allprefs',serialize($allprefs));
		if ($allprefs['stream']==""||$allprefs['stream']==0) addnav("Drink Again","runmodule.php?module=oceanquest&op=island&op2=drinkstream&op3=$op3");
		addnav("Return");
		addnav("Return to the Landing","runmodule.php?module=oceanquest&op=island&op2=landing");
	}
	if ($op2=="grabcoconut"){
		output("`c`b`^Island Exploration`b`c`n`7");
		$weapon=$session['user']['weapon'];
		if ($allprefs['firstisland'] =="" || $allprefs['firstisland'] ==0 || $allprefs['firstisland']==1){
			//guaranty coconut first trip
			if ($allprefs['firstisland'] ==""||$allprefs['firstisland'] ==0) $allprefs['firstisland'] =2;
			elseif ($allprefs['firstisland']==1) $allprefs['firstisland'] =3;
			output("With a nimbleness you didn't know you had, you scoot up the palm tree with ease.");
			output("Using your %s`7, you deftly cut down a coconut.  Perfect! I hope you know what to do with this now!",$weapon);
			$allprefs['coconut']=1;
		}else{
			//chance to find coconut
			switch (e_rand(1,5)){
				case 1:
					$allprefs['coconut']=1;
					output("You make it to the top of the palm tree and hack down a coconut with your %s`7.",$weapon);
					if ($session['user']['gold']>0){
						output("Unfortunately you accidentally knock out a `^gold coin`7 from your pouch and it falls into the sand, lost forever.");
						$session['user']['gold']--;
					}
				break;
				case 2:
					$allprefs['coconut']=1;
					output("You make it to the top of the palm tree and hack down a coconut with your %s`7.",$weapon);
					if ($session['user']['gold']>0){
						output("You arrive at the bottom of the tree and find a `^gold coin`7 that must have fallen from somebody's gold pouch.");
						$session['user']['gold']++;
					}
				break;
				case 3:
					$allprefs['coconut']=1;
					output("You can't climb the tree so you start throwing your %s`7 at the coconuts.  Oh, yes, this is a clever plan.",$weapon);
					output("`n`nSomehow, despite your attempts to impale yourself, you successfully knock down a coconut!");
				break;
				case 4:
					output("You can't climb the tree so you start throwing your %s`7 at the coconuts.  Oh, yes, this is a clever plan.",$weapon);
					output("`n`nNot surprisingly, the %s`7 falls and hits your toe nearly impaling you.",$weapon);
					if ($session['user']['hitpoints']>1){
						output("`n`nInstead, you luckily just lose `\$1 hitpoint`7.  <Phew!>");
						$session['user']['hitpoints']--;
					}else output("You just barely escape death.");
					output("`n`nNo Coconut for you!");
				break;
				case 5:
					output("You try to climb the tree but you lose your grip.");
				break;
			}
		}
		if ($allprefs['coconut']==""||$allprefs['coconut']==0){
			addnav("Coconuts");
			addnav("Get a Coconut","runmodule.php?module=oceanquest&op=island&op2=grabcoconut");
		}
		addnav("Return");
		addnav("Return to the Landing","runmodule.php?module=oceanquest&op=island&op2=landing");
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op2=="cave"){
		output("`c`b`^The Cave`b`c`n`7");
		if ($allprefs['bear']==1){
			output("You approach the cave to look around.  You hear a snort and a huff. Oh no!! It's that darn Cave Bear!!");
			output("`n`nIt seems like the bear found the old magical cave to be a nice and cosy den.");
			output("`n`nYou don't even have a chance to run away.");
			addnav("`qB`^ear`^ Fight!","runmodule.php?module=oceanquest&op=bear");
			blocknav("runmodule.php?module=oceanquest&op=island&op2=landing");
		}elseif ($allprefs['magicscroll']==1){
			output("Having already found the magic scroll, there's not much to do here anymore.  No bears, no adventure.  Nothing.");
		}elseif ($allprefs['opencave']==1){
			output("You come up to the cave ready to enter it again, looking for adventure.");
			addnav("Enter the Cave","runmodule.php?module=oceanquest&op=island&op2=entercave");
		}else{
			output("You arrive at the entrance to a cave but you notice that the entrance is sealed by a huge boulder!");
			output("You try to figure out a way to open it and notice two crevices carved into the boulder; one in the shape of a ring and the other in the shape of a star.`n`n");
			//output("`n`nironstar = %s, copperring=%s`n`n",$allprefs['ironstar'],$allprefs['copperring']);
			if ($allprefs['ironstar']==1 && $allprefs['copperring']==1){
				output("A flash of insight reminds you that you have an Iron Star and a Copper Ring!");
				output("You place them in the crevices and wait for something to happen.");
				output("`n`nSlowly, through magical means that you don't quite understand, the door starts to fade away.");
				output("`n`nWell, what kind of adventurer wouldn't walk into an eerie, mystical, magical cave?");
				$allprefs['ironstar']=0;
				$allprefs['copperring']=0;
				$allprefs['opencave']=1;
				set_module_pref('allprefs',serialize($allprefs));
				addnav("Cave");
				addnav("Enter the Cave","runmodule.php?module=oceanquest&op=island&op2=entercave");
			}elseif ($allprefs['ironstar']==1){
				output("You pull out the iron star you found and place it in appropriate crevice. It fits, but nothing happens.  Clearly you'll need to find some type of ring to go into the other crevice.");
				output("`n`nA bit disappointed, you remove the star and try to figure out what to do next.  You take a look around and notice something on the rock written in chalk.");
				addnav("Read the Chalk Writing","runmodule.php?module=oceanquest&op=island&op2=writing&op3=2");
			}elseif ($allprefs['copperring']==1){
				output("You pull out the copper ring you found and place it in appropriate crevice. It fits, but nothing happens.  Clearly you'll need to find some type of star to go into the other crevice.");
				output("`n`nA bit disappointed, you remove the star and try to figure out what to do next.  You take a look around and notice something on the rock written in chalk.");
				addnav("Read the Chalk Writing","runmodule.php?module=oceanquest&op=island&op2=writing&op3=1");
			}else{
				output("Having neither a star nor a ring, you stare at the rock wondering what to do next.  Soon, your eyes catch a glimpse of a couple of writings on the rock.");
				addnav("Read the Chalk Writing","runmodule.php?module=oceanquest&op=island&op2=writing&op3=3");
			}
		}
		addnav("Return");
		addnav("Return to the Landing","runmodule.php?module=oceanquest&op=island&op2=landing");
	}
	if ($op2=="writing"){
		output("`c`b`^The Cave`b`c`n`7");
		if ($op3==1 || $op3==3){
			if ($op3==3) output("You read the first note on the rock:");
			else output("You read the note on the rock:");
			output("`n`n`&Alas, if not for the fact that I lost my Iron Star somewhere on the `i`^Luckstar`i`& I'd be one step closer to figuring out how to open this darn cave.`n`n");
		}
		if ($op3==2 || $op3==3){
			if ($op3==3) output("You read the second note on the rock:");
			else output("You read the note on the rock:");
			output("`n`n`^I was such a fool to try to use my copper ring as bait when I was on the `&`iCorinth`i`^.  Now that I lost it at sea, I'll probably never find out what magic lies behind this rock.`n`n");
		}
		addnav("Island Exploration");
		addnav("The Cave","runmodule.php?module=oceanquest&op=island&op2=cave");
		addnav("Explore the Beach","runmodule.php?module=oceanquest&op=island&op2=beach");
		addnav("Go to the Stream","runmodule.php?module=oceanquest&op=island&op2=stream");
		addnav("Return");
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailing");
		else addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailinga");
	}
	if ($op2=="entercave"){
		output("`c`b`^The Cave`b`c`n`7");
		output("You walk a short distance into the cave and see a magical fountain. In the fountain floats a scroll.");
		output("`n`nAs you approach the scroll, you see a `!Water Guardian`7 appear in front of you.");
		output("`n`nNow you have to decide what to do:`n`n");
		output("1. Bravely retreat out of the Cave`n");
		output("2. Throw a rock at the `!Water Guardian`7`n");
		output("3. Throw a rock in the corner of the cave to distract the `!Water Guardian`7`n");
		output("4. Attack!!");
		addnav("1. Retreat!","runmodule.php?module=oceanquest&op=island&op2=cave");
		addnav("2. Throw a Rock at the `!Guardian","runmodule.php?module=oceanquest&op=island&op2=throw&op3=1");
		addnav("3. Throw a Rock in the Corner","runmodule.php?module=oceanquest&op=island&op2=throw&op3=2");
		addnav("4. Attack","runmodule.php?module=oceanquest&op=waterguardian");
	}
	if ($op2=="throw"){
		output("`c`b`^The Cave`b`c`n`7");
		output("You find a nice sized rock and toss it");
		if ($op3==1) output("at the `!Water Guardian`7. However, the rock just bounces off harmlessly.");
		else output("in the corner.  The `!Water Guardian`7 gives you a look that conveys `1'Hey, how stupid do you think I am? I mean, do you seriously think that's going to work???'");
		output("`n`n`7Looks like you'll have to try something else.");
		addnav("Retreat!","runmodule.php?module=oceanquest&op=island&op2=cave");
		if ($op3==2) addnav("Throw a Rock at the `!Guardian","runmodule.php?module=oceanquest&op=island&op2=throw&op3=1");
		else addnav("Throw a Rock in the Corner","runmodule.php?module=oceanquest&op=island&op2=throw&op3=2");
		addnav("Attack","runmodule.php?module=oceanquest&op=waterguardian");
	}
	if ($op2=="getscroll"){
		output("`c`b`^The Cave`b`c`n`7");
		output("You have defeated the `!Water `1Guardian`7! The `^Magic Scroll`7 is yours!");
		output("`n`nYou decide to read it and find that it's a magic spell.");
		output("How curious.  It takes a little time to figure out, but eventually you decipher that it's a polymorph spell.");
		output("You read the spell to yourself and suddenly notice some very small print.`n`n");
		output("`6For use only in the land of Pilinoria");
		output("`n`n`7Great.  A useless scroll.  You tuck it in a pocket and look around.  There's not much left here in the cave.  Now that the magic has been defeated, it looks like a regular old cave.");
		output("`n`nIn fact, it looks like a nice bear den.  Oh well, that's not your problem.");
		$allprefs['magicscroll']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Return");
		addnav("Return to the Landing","runmodule.php?module=oceanquest&op=island&op2=landing");
	}
	if ($op2=="crawlaway"){
		output("`c`b`^The Cave`b`c`n`7");
		output("You're weak and defeated.  You should probably try to get home before a mosquito bites you and kills you.");
		addnav("Return");
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailing");
		else addnav("Return to the `i`^Luckstar`i","runmodule.php?module=oceanquest&op=sailinga");
	}
}
?>