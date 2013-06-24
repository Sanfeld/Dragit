<?php
function oceanquest_sailmisc($op2){
	global $session;
	$op = httpget('op');
	$op3 = httpget('op3');
	$temp=get_module_pref("pqtemp");
	page_header("Sailing Expedition");
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($op2=='captain'){
		output("You go to talk to the captain about how magnificent the `^`iLuckstar`i`7 is and how impressed you are with the ship.");
		output("He smiles at your compliment.`n`n");
		output("`@'Now, I've been around these seas long enough to know when someone wants something.  What can I help you with? Be quick, I only have time to answer one question today.'`7");
		addnav("Speak with the Captain");
		if ($allprefs['sailfish']==0) addnav("Ask to Go Fishing","runmodule.php?module=oceanquest&op=$op&op2=askfishing");
		if ($allprefs['captainmeal']==0) addnav("Ask About Dinner","runmodule.php?module=oceanquest&op=$op&op2=askdinner");
		if ($allprefs['captainexp']==0) addnav("Ask to Explore the Ship","runmodule.php?module=oceanquest&op=$op&op2=askexplore");
		addnav("Sailing");
		addnav("Continue Sailing","runmodule.php?module=oceanquest&op=$op&loc=".$temp);
	}
	if ($op2=='askfishing'){
		if ($allprefs['pole']==1 || $allprefs['bait']==1 || $allprefs['fishbook']==1){
			output("The captain smiles and notices that you're ready to go fishing with your");
			if ($allprefs['pole']==1) output("fishing pole,");
			if ($allprefs['bait']==1) output("bait,");
			if ($allprefs['fishbook']==1) output("book on fishing,");
			output("a little bucket, and a cute little fishing hat.");
		}
		$allprefs['sailfish']=1;
		$allprefs['captaintalk']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("`n`n`@'Well, I'm sorry, but this isn't a fishing expedition.  If you want to go fishing maybe I should take you back to the docks and you can go join the `&`iCorinth`i`@.");
		output("No, I'm sorry, there's no fishing off my ship.  This is a trade ship. What is with the fishing, anyway? Aren't you supposed to be a great adventurer??'");
		addnav("Continue Sailing","runmodule.php?module=oceanquest&op=$op&loc=".$temp);
	}
	if ($op2=='askdinner'){
		output("You kindly ask about meals on the ship and the captain invites you to dine with him.");
		output("Knowing that it can be a long day at sea, you accept the invitation.");
		addnav("Continue","runmodule.php?module=oceanquest&op=$op&op2=dinner2");
	}
	if ($op2=='dinner2'){
		output("Passing across the deck of the ship you find yourself in the Captain's Quarters. A meal is already laid before you and seems exquisite. The captain offers you a chair and you sit down to enjoy dinner.");
		output("`n`n`@'There's plenty of adventure on the open seas for someone like yourself. If I were a younger man, I'd be out there myself.  Instead, I've settled down as the captain of a fine trade vessel.  I have no problems with that.'`n`n");
		output("`7He seems to be hinting at something and you decide to ask him more about the adventures he wanted to go on.");
		output("`n`n`@'Well, to be honest, there's a small island on the route to Pilinoria.  I saw a cave on the eastern shore once, but I'm not enough of an adventurer to go there myself.'`7 He looks at you and smiles.");
		output("`@'However, if you're interested, I think we'd be more than able to stop there to drop you off. Anytime you're interested, just direct us to land at the eastern shore of the island and you can disembark.'");
		output("`n`n`7You finish the meal with an excited energy.");
		apply_buff('buzz',array(
			"name"=>"`@Captain's Energy",
			"rounds"=>5,
			"atkmod"=>1.2,
			"roundmsg"=>"`@You feel the excitment of the hunt.`0",
		));
		$allprefs['captaindinner']=1;
		$allprefs['captaintalk']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue Sailing","runmodule.php?module=oceanquest&op=$op&loc=".$temp);
	}
	if ($op2=='askexplore'){
		output("`@'Thank you for asking.  Yes, feel free to explore the ship.'");
		$allprefs['okexplore']=1;
		$allprefs['captaintalk']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue Sailing","runmodule.php?module=oceanquest&op=$op&loc=".$temp);
	}
	if ($op2=='gofishing'){
		output("You get ready to cast your line when one of the crew taps you on the shoulder.");
		output("`n`n`2'Now what do you think you're doing? There's no fishing on this ship without special permission from the captain.'");
		addnav("Continue Sailing","runmodule.php?module=oceanquest&op=$op&loc=".$temp);
	}
	if ($op2=='goexplore'){
		if ($allprefs['okexplore']==""||$allprefs['okexplore']==0){
			output("You are getting ready to explore when one of the crew approaches you.");
			output("`n`n`2'You shouldn't go looking around unless you've got permission from the captain,' `7he says.");
		}else{
			if ($allprefs['shipsearches']>=10){
				output("You've done enough exploring today.");
			}else{
				output("Deciding that it's kind of boring standing on the deck looking at the water, you explore the ship. Where would you like to go?");
				addnav("Explore");
				addnav("Galley","runmodule.php?module=oceanquest&op=$op&op2=locations&op3=1");
				addnav("Hold","runmodule.php?module=oceanquest&op=$op&op2=locations&op3=2");
				addnav("Sleeping Quarters","runmodule.php?module=oceanquest&op=$op&op2=locations&op3=3");
				addnav("Captain's Quarters","runmodule.php?module=oceanquest&op=$op&op2=locations&op3=4");
			}
		}
		addnav("Sailing");
		addnav("Go Back to Sailing","runmodule.php?module=oceanquest&op=$op&loc=".$temp);
	}
	if ($op2=='locations'){
		if ($op3==1){
			output("You search the galley for something interesting.`n`n");
			switch(e_rand(1,10)){
				//galley
				case 1: case 2: case 3: case 4:
					if (($allprefs['ironstar']==""||$allprefs['ironstar']==0) && ($allprefs['opencave']==""||$allprefs['opencave']==0)){
						$allprefs['ironstar']=1;
						output("You find a strange Iron Star in the Larder of the Galley.  You slip it in your pocket.");
					}else{
						output("You don't find anything except the Cook who yells at you `\$'Get out of my Kitchen!!'");
					}
				break;
				case 5:
					output("You find some limes.  The Cook explains that sailors used to get a disease called Scurvy for lack of Vitamin C.");
					output("`&'Limes have since become a staple of traveling on the sea,'`7 he explains.  He offers you one and you accept it, taking a bite.");
					apply_buff('lime',array(
						"name"=>"`@Lip Pucker",
						"rounds"=>5,
						"wearoff"=>"Your lips return to normal.",
						"roundmsg"=>"Your lips pucker up because of the lime.",
					));
				break;
				case 6:
					output("You find a rat.  This is strangely reassuring.  A rat means the boat isn't sinking!");
				break;
				case 7:
					$cookgold=get_module_setting("cookgold");
					if ($cookgold>1){
						output("You find a bag with `^%s gold`7 in it! However, the Cook taps you on the shoulder.",$cookgold);
						output("`n`n`&'I'm sorry, that's mine.  Here, you can have `^one gold piece`&.'`7");
						$session['user']['gold']++;
						increment_module_setting("cookgold",-1);
					}elseif ($cookgold==1){
						output("You find a `^gold piece`7 and the cook smiles. `&'Well, that's mine, but you can keep it.'");
						set_module_setting("cookgold",0);
						$session['user']['gold']++;
					}else{
						output("You find an empty sack and look at the cook.  He smiles and says `&'Actually, that used to be full of gold but I gave it all away.'");
						output("`n`n`7Wow, what a nice guy!");
					}
				break;
				case 8:
					output("You find a cat.  It's probably to help get rid of the rats.");
				break;
				case 9:
					output("You find a container full of dried smelly fish.  Yum.  I guess that's dinner.");
				break;
				case 10:
					output("You don't find anything.");
				break;
			}
		}elseif ($op3==2){
			//hold
			output("You wander through the hold.  There's not much here except some fish that have been salted and preserved.  This must be what they're trading.");
		}elseif ($op3==3){
			//sleeping quarters
			output("You tiptoe through the sleeping quarters.  There's nobody here.  It makes you wonder why you're tiptoeing. There's nothing of interest here.");
		}else{
			//captain's quarters
			output("You're not allowed to search the captain's quarters.");
		}
		$allprefs['shipsearches']++;
		if ($allprefs['shipsearches']<10){
			addnav("Explore");
			addnav("Galley","runmodule.php?module=oceanquest&op=$op&op2=locations&op3=1");
			addnav("Hold","runmodule.php?module=oceanquest&op=$op&op2=locations&op3=2");
			addnav("Sleeping Quarters","runmodule.php?module=oceanquest&op=$op&op2=locations&op3=3");
			addnav("Captain's Quarters","runmodule.php?module=oceanquest&op=$op&op2=locations&op3=4");
		}
		addnav("Sailing");
		addnav("Go Back to Sailing","runmodule.php?module=oceanquest&op=$op&loc=".$temp);
		set_module_pref('allprefs',serialize($allprefs));
	}
}
?>