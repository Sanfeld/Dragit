<?php
function dwarvenfight_getmoduleinfo(){
	$info = array(
		"name"=>"Dwarven Fight",
		"author"=>"Strider<br>`#Converted by: Chris Vorndran",
		"version"=>"1.11",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=90",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Forest Special, in which a player will fight against Old Knaught. If killed, the user can have a bounty set on their head.",
		"settings"=>array(
			"bountyset"=>"Does this set a bounty at all,bool|1",
			"bountyamnt"=>"Amount that Dwarves put on someone's head,int|6000",
		),
		);
	return $info;
}
function dwarvenfight_install(){
	module_addeventhook("forest", "return 100;");
	return true;
}
function dwarvenfight_uninstall(){
	return true;
}
function dwarvenfight_dohook($hookname,$args){
return $args;
}
function dwarvenfight_runevent(){
	global $session;
	$bountyamount = get_module_setting("bountyamount");
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:dwarvenfight";

	$op = httpget('op');
	if ($session['user']['gems']>0){
		if ($op=="" || $op=="search"){
	
	if($session['user']['race']== 'Dwarf'){
		output("`n`n`3Wandering through the forest, you hear the sound of familiar voices and a roaring fire that smells of coal and peat.");
		output(" You cautiously approach the blaze where you see two Dwarven Soldiers with battle axes drawn at your coming.");
		output(" Judging their armor, you guess these are the dwarven patrols of the Laimond Holds.");
		output(" They're a sturdy bunch of Forge Masters that have taken down forest gods and dragons in their day.");
		output(" You're thankful that they're your kin.");
		output(" Your heart is lightened with a healthy laugh as these your fellow dwarves raise their hands to welcome you.");
		output(" The trees rustle and a dozen armed dwarves come out of the night, sheathing their weapons and taking a place by the fire.");
		output(" The captain of this small patrol claps his massive hand on your shoulder and offers you a pint of `6Dwarven Ale`3.");
		output(" `n`n`@\"You gave us a start there lad! For a minute we thought you might be one of those thieving elves or trolls.");
		output(" Silent as the grave, those bloody elves, especially here in the forests.");
		output(" I cannae wait to take the hands of `4Lonestrider `@and those jewel thieves.");
		output(" Don't worry %s, you're among friends here.",($session['user']['sex']?"Lass":"Lad"));
		output(" We're just making our way back to the `6Dwarven Hold `@after selling some wares to the `& Silver King `@of `&Caltrope`@.");
		output(" So, relax a bit %s, you're among	elders and friends here.\"",translate_inline(($session['user']['sex']?"lassie":"laddie")));
		output("`n`n`3You lean back and relax for a spell, drinking the ale of your fellow dwarves.");
		output(" Your cup runs empty and the conversation dies down, so	you decide it is time to say something. `n`n`7You tell your fellow dwarves:`n");
		$session['user']['specialinc'] = "module:dwarvenfight";
		addnav("Look for Creatures","forest.php?op=creatures");
		addnav("Search the Mines","forest.php?op=mines");
		addnav("Visit the Smith","forest.php?op=armor");
		addnav("Betrayal");
		addnav("ATTACK","forest.php?op=fight1");
	}else{
		//ss// Begin the script for everyone else. The dwarves are carrying quite a lot of gold, they don't want to see
		// any other races around. If you stumble upon them, they're going to fight! 
		
		output("`n`n`6You smell smoke from a distant fire and race through the forest to determine what sort of camp you might find.");
		output(" Silently, you stalk through the shadows and get a glimpse of two dwarves standing guard by a bonfire.");
		output(" Both dwarves sit like statues, staring at the dark forests from behind their great beards.");
		output(" Near the old guards are several locked chests and a cache of dwarven weapons.");
		output(" You consider launching an attack on the two guards, trusting your `@%s`6 skills to pull yourself through this encounter.",$session['user']['race']);
		output(" Or, you could leave the dwarves alone and go back to your hunting.");
		output(" You know a chance like this doesn't come around too often and it's sometimes turns a good profit to harass dwarves for their gems.");
		$session['user']['specialinc'] = "module:dwarvenfight";
		//addnav("Harass Dwarves","forest.php?op=give");
		addnav("Leave","forest.php?op=runawaylikealittlesissybaby");
		addnav("Attack the Dwarves!","forest.php?op=fight1");
		}
	}elseif ($op=="mines"){
	if ($session['user']['gems']>10){
		output("`n`n`3Surrounded by these Forge Masters, you take a rare chance to ask about the mines in hopes to improve your own skill.");
		output(" `n`n`@\"Aye, %s, the mines are a wonderful place where work and skill are the best rewards!",($session['user']['sex']?"lassie":"laddie"));
		output(" Tools are important, you must have a good sturdy ax and a pick that can withstand ages of use."); output(" The old iron picks are inferior	to a good steel one, but then you have your enchanted mining tools that are even better at cutting through the rock. . .\"");
		$experience = e_rand(25, 500);
		$session['user']['experience']+=$experience;
		output("`n`3You listen to your elder, soaking up his stories of the Laimond Mines and paying attention to his advice.");
		output(" It's about time to get moving and you wave goodbye to your fellow dwarves, with `6%s experience `3gained from this encounter!.",$experience);
		debuglog("Met up with the dwarves and gained $experience experience.");
	}else{
		output("`n`n`6Depressed, you ask the elders about gems, especially since you can't seem to find any. The dwarves laugh and shake their beards.");
		output(" `n`n`@\"Well %s, we dwarves have the best chances of finding gems you know.",translate_inline(($session['user']['sex']?"lass":"lad")));
		output(" It takes hard work in the mines to pull these beauties from the ground, but then you learn to work them into ore and fit them into the hilts of blades and you'll find your entire world changed!\"");
		$gems = e_rand(2, 6);
		$session['user']['gems']+=$gems;
		output("`n`6He tosses you a small leather bag. You're surprised to find `%%s gems `6in the little pouch!.",$gems);		
		debuglog("Met up with Dwarves gained $gems gem for being a dwarf.");
		}
		$session['user']['specialinc']="";
	}elseif ($op=="creatures"){
		output("`n`n`3You drink the ale and enjoy the company of your fellow dwarves for a short while.");
		output(" Soon it's time to get back to hunting the creatures in this forest and you tell your friends goodbye.");
		output(" They offer a final pint of ale for your battle and cheer you on as	you drink the full pint in a single gulp!");
		output("`n`n`^The draught burns your throat and makes you feel stronger! Your health has also returned!`n");
		if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
		    $session['user']['hitpoints']= $session['user']['maxhitpoints'];
		$dwarf = array(
			"name"=>"`#Dwarven Warrior",
			"rounds"=>e_rand(5,10),
			"wearoff"=>"You feel yourself sober...",
			"atkmod"=>1.5,
			"activate"=>"offense",
			"schema"=>"module-dwarvenfight",
			);
		apply_buff("dwarven-fight",$dwarf);
		$session['user']['specialinc'] = "";
	}elseif ($op=="armor"){
		output("`n`n`3Surrounded by these great Dwarven Smiths, you decide to ask the captain about enhancing your		armor.");
		output(" You'd like to make it better in battle.");
		output(" With a cheerful grin, several of these dwarves begin to look over your %s and making improvements.",$session['user']['armor']);
		output(" Before you know it, they're taking pieces off, hammering bits together and carving into your armor to create something even better.");
		output(" When they're done, you can't thank them enough for their expert work.");
		output("`n`n`6Your armor is enhanced.`n");
		$session['user']['defense']+=1;
		$session['user']['armordef']+=1;
		$session['user']['armorvalue']*=1.33;
		$session['user']['armor'] = $session['user']['armor']."+";  
		$session['user']['specialinc']="";
	}elseif ($op=="runawaylikealittlesissybaby"){
		output("`n`n`6You realize that this is probably more of a problem than you need right now.");
		output(" Old dwarves are as tough as nails and this smells like a trap to you.");
		output(" Slowly, you back away from the dwarven camp. ");
		if (e_rand(1,3)==1){
			$session['user']['specialinc']="";
			output("`n`nAfter you're a good distance away, you breathe a little easier.");
			output(" Out of the corner of your eye, you swear you can see the shadows of other dwarves hiding amongst the trees.");
		}else{
			output("`n`nSuddenly you feel something kick you square in the back. You fall forward, but quickly spring to your feet with your `%%s `6drawn and ready for battle.",$session['user']['weapon']);
			output(" A dozen sturdy dwarves surround your flanks and you curse yourself for letting these warriors to get the better of you.");
			output(" It looks like you have no choice but to fight!");
			addnav("Stand and fight!","forest.php?op=fight1");
		}
	}elseif ($op=="fight1"){
		$dkb = round($session['user']['dragonkills']*.1);
		$badguy = array(
			"creaturename"=>translate_inline("`^Dwarven Soldiers`0"),  
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>translate_inline("Many BattleRaged Dwarves"),
			"creatureattack"=>$session['user']['attack']-1,
			"creaturedefense"=>$session['user']['defense']+2,
			"creaturehealth"=>round($session['user']['maxhitpoints']*0.9,0), 
			"diddamage"=>0);
		$dwarves = array(
			"startmsg"=>"`n`^You are surrounded by dwarves!`n`n",
			"name"=>"`%Swinging Axes",
			"rounds"=>3,
			"wearoff"=>"The Dwarves are starting to tire and falter.",
			"minioncount"=>$session['user']['level'],
			"mingoodguydamage"=>0,
			"maxgoodguydamage"=>1+$dkb,
			"effectmsg"=>"A dwarf hits you you for {damage}.",
			"effectnodmgmsg"=>"A battle ax MISSES your head.",
			"effectfailmsg"=>"Your weapon wails as you deal no damage to your opponent.",
			"activate"=>"roundstart",
			"schema"=>"module-dwarvenfight",
		);
		apply_buff("dwarves-battle",$dwarves);
		$session['user']['badguy']=createstring($badguy);
		$op= "fight";
	}
	if ($op=="run"){
		output("There are too many dwarves blocking the way now, you have no chance to run!");
		$op="fight";
	}
	if ($op=="fight"){
		$battle=true;
	}
	if ($battle){
	  include("battle.php");
		if ($victory){
			if (e_rand(1,3) == 1 && $session['user']['dragonkills']>5) {
			output("`n`3The last dwarf falls to his knees and leans against his battleaxe.");
			output(" You kick him to the ground and laugh at the pathetic attempt to deny you this treasure.");
			output("These dwarves should have just given up and made it easier on themselves.");
			output(" Quickly you go to open the chests.");
			output(" You force the iron latch of one lockbox and find it's full of gold!");
			output(" Your heart quickens as you reach for another chest.");
			output(" `n`n\"`^Take yer hand off the box, yew blighter.");
			output(" This hammer of mine has something to settle with you.\"");
			output("`3`nYou look up to see a heavily armed dwarf wielding a massive war-hammer.");
			output(" A deep scar crosses the left side of his face and a patch covers his left eye as his right eye glares with rage.");
			output(" You've laughed at the legends of `@Old Knaught, the dwarven lord`3, but to see the dwarf warrior now, you know you're in for a battle.");
			output(" For a moment, you consider your options, but Old Knaught doesn't let you finish the moment.");
			output(" Faster than you expected, the war-hammer sings its way into your chest.");
			output(" A jarring thud sends you into the black abyss.`3");
			output("`n`nYour eyes flutter open for a moment to watch ");
				$costlose2 = $costlose*1.5;
				if ($costlose2 > $session['user']['gems'])
				$costlose2 = $session['user']['gems'];
				$session['user']['gems']-=$costlose2;
				$goldloss = $session['user']['gold'];
				$session['user']['gold']-=$goldloss;
				output("`6`@Old Knaught`3 take `^%s gems `6and `^%s gold`6.",$costlose2,$goldloss);
		    	debuglog("lost $costlose2 gems and all gold when Old Knaught (dwarf lord) did a special attack.");
				addnews("`^%s `2 challenged `@Old Knaught `2and a patrol of dwarves in the forest. The poor warrior fell to `@Old Knaught's`2 dwarven war-hammer.",$session['user']['name']);

			}else{
				if (is_module_active("dag")){
					$bountmax = get_module_setting("bountymax","dag");
				}else{
					$bountmax = 200;
				}
			$bounty = round($bountmax * $session['user']['level'] / 6, 0);
			output("`n`6The bodies of dwarven soldiers are scattered before you.");
			output(" Quickly you go	to the chests and force an iron latch off a lockbox and find it's full of gold!");
			output(" Your heart quickens as you reach for another chest and find even more gold.");
			output(" These Dwarves must have sold quite a few wares.");
			output(" You've earned `^%s gold`6 in this little adventure and you manage to find a cask of dwarven ale to soothe your wounds.",$bounty);
			output(" Silently, you enjoy their fire and feel a bit sorry for this patrol, but they certainly didn't put up much of a fight.");
			output(" As you casually search the soldiers' pockets, you discover `5a healing potion`6, which you avail yourself of.");
		if (get_module_setting("bountyset") == 1){
			$bountyamnt = get_module_setting("bountyamnt");
			$id = $session['user']['acctid'];
			$sql = "SELECT bountyid,amount,target,setter,status FROM ".db_prefix("bounty")." WHERE target=".$id."";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
		if ($row['amount'] <= 0) {
			$bountyamnt = get_module_setting("bountyamnt");
			$sqlset = "INSERT INTO ".db_prefix("bounty")." (amount,target,setter,setdate,status) VALUES (".$bountyamnt.",".$session['user']['acctid'].",0,now(),0)";
			db_query($sqlset);
			addnews("`^%s`3 defeated a `6Dwarven Patrol `3in the deep forest! The adventure was a profitable one! But %s `3has had a bounty of `^%s gold`3 placed on %s head.", $session['user']['name'], $session['user']['name'], get_module_setting("bountyamnt"), translate_inline(($session['user']['sex']?"her":"his")));
		}else{
			$bountyamnt = get_module_setting("bountyamnt");
			$sqlfind = "SELECT amount FROM ".db_prefix("bounty")." WHERE target=".$session['user']['acctid']." AND status=0";
			$res = db_query($sqlfind);
			$row = db_fetch_assoc($res);
			$newbounty = $row['amount'] + $bountyamnt;
			$sqlset2 = "UPDATE ".db_prefix("bounty")." SET amount=".$newbounty." WHERE target=".$session['user']['acctid']." AND status=0";
			db_query($sqlset2);
			addnews("`^%s`3 defeated a `6Dwarven Patrol `3in the deep forest! The adventure was a profitable one! But %s `3has had a bounty of `^%s gold`3 placed on %s head.", $session['user']['name'], $session['user']['name'], $newbounty, translate_inline(($session['user']['sex']?"her":"his")));
		}
		}else{
			addnews("`^%s `2 challenged `@Old Knaught `2and a patrol of dwarves in the forest. The poor warrior fell to `@Old Knaught's`2 dwarven war-hammer.",$session['user']['name']);
		}
			if ($session['user']['specialmisc']=="triedtorun") 
				output("`n`nTo think you tried to run away. . .");
			}
			$session['user']['gold']+=$bounty;
		    debuglog("gained $bounty gold for taking on the dwarven patrol");
		    if ($session['user']['hitpoint'] < $session['user']['maxhitpoints'])
			    $session['user']['hitpoints']=$session['user']['maxhitpoints'];
			$session['user']['specialinc']="";
			strip_buff("dwarves-battle");
			$badguy = array();
			$session['user']['badguy']="";
		}elseif ($defeat){
			unset($session['bufflist']['dwarves']);
			$badguy=array();
			$session['user']['badguy']="";
			// vv This should never evaluate to true, because of the test at line 11.
			if ($costlose > $session['user']['gems'])
				$costlose = $session['user']['gems'];
			$session['user']['gems']-=$costlose;
			$goldloss = $session['user']['gold'];
			$session['user']['gold']-=$goldloss;
		    debuglog("lost $costlose gems and $goldloss Gold when the dwarves and Old Knaught knocked them unconscious.");
			addnews("`^%s`6 attacked a patrol of dwarves in the forest! %s was quickly overpowered by them and fell in battle.`n%s",$session['user']['name'], translate_inline(($session['user']['sex']?"She":"He")), $taunt);
			if ($session['user']['gems'] > 0) {
				output("`n`3The `6Dwarves`3 have laid you unconscious.");
				output(" They help themselves to the `^%s `3gold and `^%s`3 gems that they find in one of your purses.", $goldloss, $costlose);
				output("Fortunately for you, they do not notice your other purse containing the remainder of your gems.");
			}else{
				output("`n`3The `6Dwarves`3 have laid you unconscious. They help themselves to the `^%s `6and `^`6 gems that they find in your purse.", $goldloss, $costlose);
			}
			$session['user']['turns']--;
			output("`n`nYou lay, bleeding on the forest road, barely clinging to life.");
			output(" Holy men and politicians pass you by without so much as a downward glance.");
			output(" Warriors walk over you, leaving you to die.");
			output(" It is not until a villager from hated Eythgim village sees you that your aid comes though.");
			output(" He raises a healing potion to your lips, and drags you to the %s.",getsetting("innname","The Boar's Head Inn"));
			output(" There, he purchases a room from Cedrik, and leaves coin for your care, departing before you fully gain consciousness, leaving no opportunity to thank him.");
			output("`n`n`^You lose a forest fight while unconscious.");
			$session['user']['specialinc']="";
			$session['user']['boughtroomtoday']=1;
			if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			addnav("Wake Up!","inn.php?op=strolldown");
		}else{
			require_once("lib/fightnav.php");
			fightnav(true,false);
		}
	}
}else{
if($session['user']['race']== 'Dwarf'){
	output("`n`n`6Your dwarven senses begin to tingle as you kneel before a great slab of rock in the forest.");
	output(" The slab seems well worn by the elements, but the coloring catches your eye.");
	output(" From your travel pack, you pull out a small pickaxe and cut into the rock.");
	output(" After a short time, you're able to withdraw a nice gem from the stone.");
	output("`n`n`^*You gain 1 Gem*`0");
	$session['user']['gems'] ++;
	$session['user']['specialinc']="";
	debuglog("Dug up a gem as a dwarf in the forest.");
}else{
	output("`n`n`6You smell smoke from a distant fire and race through the forest to determine what sort of camp you might find.");
	output(" Silently, you stalk through the shadows and get a glimpse of two dwarves standing guard by a bonfire.");
	output(" Both dwarves sit like statues, staring at the dark forests from behind their great beards.");
	output(" Near the old guards are several locked chests and a cache of dwarven weapons.");
	output(" You consider launching an attack on the two guards, trusting your `@%s `6skills to pull yourself through this encounter.",$session['user']['race']);
	output(" But then you think twice as you see `@Old Knaught `6and his mighty warhammer ready to defend the bounty that his kin may be protecting.");
	output(" Silently, you back away from the Dwarven Troops.");
	}
}
}
function dwarvenfight_run(){
}
?>