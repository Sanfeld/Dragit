<?php
function lostruins_explore(){
	global $session;
	$exploreturns=get_module_setting("exploreturns");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$usedexpts=$allprefs['usedexpts'];
	output("`n`c`b`5A`6ncient `5R`6uins`c`b`n");
	addnav("V?(V) Return to Village","village.php");
	if ($session['user']['turns']<1) output("`7Whoa there. You're too exhausted to explore the `5A`6ncient `5R`6uins`7.`n`n Why don't you try again when you've got the strength for some exploring?");
	elseif ($usedexpts>=$exploreturns) output("`#'You've spent enough time exploring the `b`5A`6ncient `5R`6uins`b`#. Try back tomorrow.'");
    else{
		$allprefs['usedexpts']=$allprefs['usedexpts']+1;
		set_module_pref('allprefs',serialize($allprefs));
		$allprefs=unserialize(get_module_pref('allprefs'));
		$usedexpts=$allprefs['usedexpts'];
		if ($usedexpts<$exploreturns) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
		$session['user']['turns']--;
		switch(e_rand(1,25)){
		//switch(25){
			case 1:
				redirect("runmodule.php?module=lostruins&op=welcome");
			break;
			case 2:
				output("`7You find a spot in the ruins that looks very interesting. You start to excavate and find a `qM`Qonkey's `qP`Qaw`7.`n`n What do you wish for?");
				if ($session['user']['gems']>6)addnav("`^3000 Gold","runmodule.php?module=lostruins&op=monkgold");
				if ($session['user']['charm']>4)addnav("`\$100 Hitpoints","runmodule.php?module=lostruins&op=monkhitpoints");
				if ($session['user']['attack']>24)addnav("`4100 Favor Points","runmodule.php?module=lostruins&op=monkfavor");
				if ($session['user']['maxhitpoints']>250)addnav("`#10% Experience","runmodule.php?module=lostruins&op=monkexp");
				if ($session['user']['defense']>24)addnav("`%6 Gems","runmodule.php?module=lostruins&op=monkgems");
				addnav("`&1 Charm Point","runmodule.php?module=lostruins&op=monkcharm");
			break;
			case 3:
				output("`7After an exhaustive search of the ruins, you come up empty.`n`nHowever, a beggar walks up to you holding a tin cup and asking for some assistance.`n`nWhat do you do?");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				if ($session['user']['gold']>0) addnav("`^Give 1 Gold","runmodule.php?module=lostruins&op=lilgold");
				if ($session['user']['gold']>999) addnav("`^Give 1000 Gold","runmodule.php?module=lostruins&op=biggold");
				if ($session['user']['gems']>0) addnav("`%Give 1 Gem","runmodule.php?module=lostruins&op=lilgem");
				if ($session['user']['gems']>4) addnav("`%Give 5 Gems","runmodule.php?module=lostruins&op=biggem");
				if (($session['user']['gold']<1) && ($session['user']['gems']<1)) addnav("I Have Nothing","runmodule.php?module=lostruins&op=nocash");
				else addnav("`#Nothing","runmodule.php?module=lostruins&op=nogift");
				addnav("`\$Steal from Him","runmodule.php?module=lostruins&op=begsteal");
			break;
			case 4:
				output("`7Your explorations lead you to a `1dark corner`7 of the `5A`6ncient `5R`6uins`7 where light doesn't penetrate.`n`n Will you venture in?");
				addnav("Darkness","runmodule.php?module=lostruins&op=darkness");
			break;
			case 5:
				output("`7A small box rests behind a brick wall. You approach cautiously and with a bit of apprehension.`n`nIt seems to be a very sturdy box.");
				output("Written on the side is the following:`c`b`n`QS`^c`Qh`^r`Qo`^e`Qd`^i`Qn`^g`Qe`^r`7`c`b`n");
				output("`7You listen quietly for a while but can't hear a sound from the box. What will you do?");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				addnav("`QOpen the Box","runmodule.php?module=lostruins&op=schroyes");
				addnav("`^Leave the Box","runmodule.php?module=lostruins&op=schrono");
			break;
			case 6:
				require_once("modules/lostruins/lostruins_case6.php");
				lostruins_case6();
			break;
			case 7:
				output("`7A fog suddenly descends upon you and before you know it you're engulfed in a dense haze.`n`nA deep voice booms from above telling you to kneel!");
				output("`n`nWhat do you do?`n`n`%Bend one knee in supplication and await directions`n`\$Speak defiantly`n`^Stand in stunned silence`n`@Start waving your arms to clear the fog`n");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				addnav("`%Kneel","runmodule.php?module=lostruins&op=kneel");
				addnav("`\$Defy","runmodule.php?module=lostruins&op=defy");
				addnav("`^Nothing","runmodule.php?module=lostruins&op=donothing");
				addnav("`@Wave","runmodule.php?module=lostruins&op=wave");
			break;
			case 8:
				require_once("modules/lostruins/lostruins_case8.php");
				lostruins_case8();
			break;
			case 9:
				output("`7As you search the ruins, you come upon a strange looking little man.`n`nWhen you approach him, he slowly looks up with a look of total boredom on his face.");
				output("`#`n`n'I'm bored,'`7 he says,`# 'Pick a number between one and five.'`7`n`n  Being a little bored yourself, you decide to play along.`n`n");
				addnav("One","runmodule.php?module=lostruins&op=number");
				addnav("Two","runmodule.php?module=lostruins&op=number");
				addnav("Three","runmodule.php?module=lostruins&op=number");
				addnav("Four","runmodule.php?module=lostruins&op=number");
				addnav("Five","runmodule.php?module=lostruins&op=number");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
			break;
			case 10:
				output("You find a scraggly, crumbling, old-looking map.  Will you follow it?");
				addnav("Use the Map","runmodule.php?module=lostruins&op=map");
			break;
			case 11:
				output("`7You find an ancient spring.It's beautiful and wonderful.After sitting down by the spring for a couple of minutes, you think up a couple of options. Would you like to try one of these?`n`n");
				output("`#1.  Bottle the spring water and sell it for a HUGE profit`n`n");
				output("`32.  Poison the water so the next adventurer that comes by dies and you can steal their gold`n`n");
				output("`#3.  Make a brochure and bring tours of adventurers to see 'The Lost Fountain of Youth'`n`n");
				output("`34.  Take a sip and be on your merry way`n`n");
				output("`#5.  Make a lemonade stand!`n`n");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				addnav("Ship the water bottles","runmodule.php?module=lostruins&op=ship");
				addnav("Slip some Poison in the water","runmodule.php?module=lostruins&op=slip");
				addnav("Trip to the Fountain of Youth","runmodule.php?module=lostruins&op=trip");
				addnav("Sip some water","runmodule.php?module=lostruins&op=sip");
				addnav("Dip some Lemons in the water","runmodule.php?module=lostruins&op=dip");
			break;
			case 12:
				output("`7You look everywhere for something that might be even remotely interesting.`n`nNothing!`n`n But even more disappointing is the fact that after searching for so long, you start to get a very common syndrome known to the most adventurous explorers...");
				output("`n`n`$`bArmor Chafing!!`b`7`n`nI know, it's not as bad as everyone says, but it does prevent you from being on your best game when you fight.`n`n");
				apply_buff('chafing',array(
					"name"=>"Armor Chafe",
					"rounds"=>20,
					"wearoff"=>"`%That chafing feeling goes away.",
					"atkmod"=>.9,
					"defmod"=>.9,
					"roundmsg"=>"`%You have that 'Not-so-fresh' chafing feeling...",
				));
			break;
			case 13:
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				addnav("The Afterlife","news.php");
				output("`b`$ AVALANCHE!!!!!!!!!!`b`n`n`7You're dead.`n`nYou lose all your `^gold`7.`n`n You `#lose 40% of your experience`7.`n`n`@You may begin your adventures again tomorrow.");
				output("`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n.`n Just Kidding!!!`n`7There really wasn't an avalanche. You've still got your `^gold`7, `#experience`7, and`% your life`7.`n`n`&`b You didn't find anything interesting. Now go away.`b");
			break;
			case 14:
				output("`7While searching through the rocks, you move some large boulders around. Suddenly, the large boulders tap you on the shoulder.");
				output("You've disturbed a`b Stone`Q Golem`b`7!`n`n`QIt's Clobbering Time!`n`n");
				addnav("`7Stone`Q Golem`$ Fight","runmodule.php?module=lostruins&op=attack");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
			break;
			case 15:
				output("`7You find a frog. After looking around for anyone who might see you, you lean down and give the `@frog`7 a kiss.`n`n");
				switch(e_rand(1,3)) {
					case 1:
						output("`7Oh well, nothing happened. You figure it was worth a shot.`n`n");
					break;
					case 2:
						output("`7It was actually not as bad as you thought... `6that is, until a bunch of school girls sees you and goes to tell`% EVERYONE`6 in the village.`n`n `7 Your embarrassment causes you to`& lose a charm point`7!");
						addnews("%s `&was just seen kissing a `@frog`&! Ewwwwww!",$session['user']['name']);
						$session['user']['charm']--;
					break;
					case 3:
						$case15g=get_module_setting("case15g");
						output("`7What a great idea! The frog turns into a`% %s`7!",translate_inline($session['user']['sex']?"prince":"princess"));
						output("`n`nAfter a discussion about the benefits and merits of dating someone who used to be a`@ frog`7,");
						if ($case15g>0) output("you barter %s `7to give you`^ 263 gold`7 and you promise not to tell anyone about %s peculiar past.",translate_inline($session['user']['sex']?"him":"her"),translate_inline($session['user']['sex']?"his":"her"));
						else output("you decide that maybe nobody needs to know about this.");
						$session['user']['gold']+=$case15g;
					break;
				}
			break;
			case 16:
				output("`7You find nothing. I guess it's time to head back home.`n`n`7You step into a strange clearing and blink twice.");
				output("Why is there a man standing next to a pretty colored wheel?`n`n`7Almost as if he read your mind, he starts talking to you.");
				output("`n`n `#'I have a feeling you're wondering why I'm standing next to such a pretty colored wheel, aren't you?'");
				output("`n`n`7 Yup, you were.`n`n `#'Well, I don't have a good answer for you. All I know is that this is a chance for you to see if lady luck is on your side.");
				output("A spin only costs`^ 200 gold`#. The`& King`# requires me to post the following odds. Just let me know if you're interested.'`n`n");
				output("`n`\$1 in 3`7 Nothing`n`#1 in 3 Win`^ 200 gold`n`%2 in 9 Win`^ 500 gold`n`@1 in 9 Win `\$J`^a`@c`#k`!p`%o`\$t`# of `^1000 gold`n`n");
				if ($session['user']['gold']>=200) addnav("Spin","runmodule.php?module=lostruins&op=spin");
				else output("`#'Err, of course, you can't play if you can't pay. Let me know when you get some gold.'`n");
			break;
			case 17:
				$case17g=get_module_setting("case17g");
				$case17ge=get_module_setting("case17ge");
				output("`7An old man with green eyes and red hair is sitting on a rock scribbling furiously on a tablet. He looks tired and close to lunacy.");
				output("Being slightly concerned, you approach him and ask if there's anything you can do to help.`n`n`@'Oh, hello there. My name is `#Evad the Scrivener`@.");
				output("I'm supposed to come up with something really interesting to happen at this exact moment, but I'm running out of ideas. Do me a favor and just pick one of the following:'");
				output("`n`n`^%s Gold`n`%%s Gem%s`n",$case17g,$case17ge,translate_inline($case17ge>1?"s":""));
				output("`#A Generic Buff`n`&2 Charm Points`n`@2 Extra Turns`n`n`7Being a little concerned for the poor fellow's sanity, you quickly pick one.`n`n");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				if ($case17g>0) addnav("`^Gold","runmodule.php?module=lostruins&op=freegold");
				if ($case17ge==1)addnav("`%Gems","runmodule.php?module=lostruins&op=freegems");
				if ($case17ge>1)addnav("`%Gem","runmodule.php?module=lostruins&op=freegems");
				addnav("`#Buff","runmodule.php?module=lostruins&op=freebuff");
				addnav("`&Charm","runmodule.php?module=lostruins&op=freecharm");
				addnav("`@Turns","runmodule.php?module=lostruins&op=freeturns");
			break;
			case 18:
				$case18g=get_module_setting("case18g");
				output("`7A strange man walks up to you and introduces himself.`n`n`Q'I am the mystical `&Lakinne the Great`Q. I recently came upon an extra `^%s gold`Q and I would like to give it away.",$case18g);
				output("You will choose who will receive the gold. Who do you choose?'");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				addnav("Choose","runmodule.php?module=lostruins&op=goldgift");
			break;
			case 19:
				output("`7You come upon a strange set of rocks with an inscription on them.");
				output("Will you read the following inscription `b`&outloud`b`7?`n`n");
				output("`#Elausac`$ Anosrep`% Anu`^ Id`& Osses`! Li`@ Ibmac!`n`n");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				addnav("Yes","runmodule.php?module=lostruins&op=readyes");
				addnav("No","runmodule.php?module=lostruins&op=readno");
			break;
			case 20:
				output("`7While wandering through the ruins, you get a little tired and sit down to take a break. You pick up a lovely flower and start playing`^ 'loves me/loves me not'`7.`n`nHow many petals do you pick?");
				rawoutput("<br><form action='runmodule.php?module=lostruins&op=pluck' method='post'>");
				$stuff = array("pluck"=>"How many?,range,1,8,1|1",);
				$b = array("pluck"=>1,);
				require_once("lib/showform.php");
				showform($stuff,$b,true);
				$b = translate_inline("Pluck!");
				rawoutput(" <input type='submit' class='button' value='$b'></form>");
				addnav("","runmodule.php?module=lostruins&op=pluck");
				addnav("Pluck!","runmodule.php?module=lostruins&op=pluck");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
			break;
			case 21:
				output("`7You sit down and pick a flower and start playing `^'loves me/loves me not'`7.");
				output("`n`n Each time you pick one of the petals, you think you hear a high pitched voice yell`$ 'OUCH!'");
				output("`7`n`n  Most people would probably stop upon hearing this, but it's been a rough day and you are still curious about whether that certain someone loves you or loves you not.");
				output("`n`nYou pluck the final petal to a triumphant`4 'Loves me!'");
				output("`7`n`n That is, it's triumphant until you realize that the`$ 'Ouch'`7 was coming from the rock you were sitting on.");
				output("Turns out it wasn't a rock after all but a`@ turtle`7!");
				output("`n`n`@What would you like to do?`n`n");
				addnav("Make Turtle Soup!","runmodule.php?module=lostruins&op=tsoup");
				addnav("Make Turtle Friend!","runmodule.php?module=lostruins&op=tfriend");
				addnav("Make like a Tree and Leave!","forest.php");
				blocknav("runmodule.php?module=lostruins&op=explore");
				if ($usedexpts<$exploreturns) addnav("Make another Exploration of the Ruins","runmodule.php?module=lostruins&op=explore");
			break;
			case 22:
				$oneday=0;
				if (is_module_active('ruinworld1') && get_module_setting("ruin1found")==0) {
					if (get_module_setting("ruin1count")>=get_module_setting("ruin1trig")) {
						set_module_setting("ruin1finder",$session['user']['name'],"ruinworld1");
						set_module_setting("ruin1loc",$session['user']['location'],"ruinworld1");
						set_module_setting("ruin1count",0);
						set_module_setting("ruin1found",1);
						output("`#While searching the ruins, you find something very strange...");
						addnav("Something Strange","runmodule.php?module=ruinworld1&op=enter");
						blocknav("village.php");
						blocknav("runmodule.php?module=lostruins&op=explore");
						$oneday=1;
					}else increment_module_setting("ruin1count",1);
				}
				if ($oneday==0){
					global $session;    
					output("`7It's `2One of Everything Day`7!");
					output("`n`nYou get `^one gold`7,`% one gem`7,`& one charm`7, and `@one forest fight`7.");
					output("`n`n Enjoy!");
					output("`n`n`6(Yeah, I know, one gold is really useless, isn't it?)");
					$session['user']['gold']++;
					$session['user']['gems']++;
					$session['user']['turns']++;
					$session['user']['charm']++;
				}
			break;
			case 23:
				$skipstone=0;
				if (is_module_active('ruinworld2') && get_module_setting("ruin2found")==0) {
					if (get_module_setting("ruin2count")>=get_module_setting("ruin2trig")) {
						set_module_setting("ruin2finder",$session['user']['name'],"ruinworld2");
						set_module_setting("ruin2loc",$session['user']['location'],"ruinworld2");
						set_module_setting("ruin2count",0);
						set_module_setting("ruin2found",1);
						output("`#While searching the ruins, you find something very strange...");
						addnav("Something Strange","runmodule.php?module=ruinworld2&op=enter");
						blocknav("village.php");
						blocknav("runmodule.php?module=lostruins&op=explore");
						$skipstone=1;
					}else increment_module_setting("ruin2count",1);
				}
				if ($skipstone==0){
					output("`7You find an amazingly clear lake... and what do you do with amazingly clear lakes?");
					output("`n`n  You skip stones!");
					output("`n`nAfter finding an amazingly perfect stone, you take a good fling and let it fly!`n`n");
					switch(e_rand(1,3)){
						case 1:
							output("`7The stone skips a nice`# 6 times`7.");
							output("`n`nNothing to write home about, but it makes you smile.");
						break;
						case 2:
							output("`7The stone skips a sad`# 1 time`7 and lands in the water with a loud `!plunk`7.");
							output("`n`nA bunch of villagers saw your pitiful attempt.");
							output("`n`n  You lose `&1 charm`7!");
							output("Perhaps you should practice a little more next time.");
							$session['user']['charm']-=1;
						break;
						case 3:
							output("`7The stone skips an amazing`# 18 times`7!!!");
							output("A large gathering of people across the shore hoots in applause!`n`n");
							output("You give a graceful bow and improve your `&charm by 1`7!");
							$session['user']['charm']+=1;
						break;
					}
				}
			break;
			case 24:
				$flubber=0;
				if (is_module_active('ruinworld3') && get_module_setting("ruin3found")==0) {
					if (get_module_setting("ruin3count")>=get_module_setting("ruin3trig")) {
						set_module_setting("ruin3finder",$session['user']['name'],"ruinworld3");
						set_module_setting("ruin3loc",$session['user']['location'],"ruinworld3");
						set_module_setting("ruin3count",0);
						set_module_setting("ruin3found",1);
						output("`#While searching the ruins, you find something very strange...");
						addnav("Something Strange","runmodule.php?module=ruinworld3&op=enter");
						blocknav("village.php");
						blocknav("runmodule.php?module=lostruins&op=explore");
						$flubber=1;
					}else increment_module_setting("ruin3count",1);
				}
				if ($flubber==0){
					output("`7You find the `6A`5ncient `6F`5libber `6F`5lobber `6o`5f `6C`5onstantinapolis`7.");
					output("`n`nYou have no clue what that means though.");
					output("You head back to the village and find an antique dealer who tells you a long and boring history.");
					output("You feign interest since you're just waiting to hear the bottom line...");
					output("how much it's worth.");
					output("Well, he takes another`@ turn `7explaining to you that it's hard to put a value on something so...");
					output("well, he tells you, so darn boring.");
					output("`n`nHe offers you `^75 gold`7 and tells you that honestly, that's all it's worth.");
					output("You shrug and wander off.`n`n");
					$session['user']['turns']--;
					$session['user']['gold']+=75;
				}
			break;
			case 25:
				if (is_module_active('quarry') && get_module_setting("quarryfound")==0) {
					if (get_module_setting("quarrycount")>=get_module_setting("quarrytrig")) {
						set_module_setting("quarrycount",0);
						set_module_setting("quarryfound",1);
						set_module_setting("sgcount",0,"quarry");
						set_module_setting("underatk",0,"quarry");
						$blocksleft=(e_rand(get_module_setting("blockmin","quarry"),get_module_setting("blockmax","quarry")));
						set_module_setting("blocksleft",$blocksleft,"quarry");
						set_module_setting("newsclosed",0,"quarry");
						$qgold=get_module_setting("qgold");
						$qgems=get_module_setting("qgems");
						set_module_setting("quarryfinder",$session['user']['name'],"quarry");
						output("`b`c`@Congratulations!`b`c`n");
						output("`6You've found something truly `#wonderful`6!");
						output("You recognize instantly that you are standing in front of some of the best bedrock in the kingdom.");
						output("This place will generate millions for the `&King`6 when it is converted into a quarry.");
						//Following code by Sixf00t4
						$qp=$session['user']['location'];
						$info = get_module_info("quarry");
						$version = $info['version'];
						if (is_module_active("cityprefs") && $info>=5.23){
							require_once("modules/cityprefs/lib.php");
							$cityid = get_cityprefs_cityid("location",$qp);
							if(get_module_objpref("city",$cityid,"quarryhere","quarry")!=1){
								$sql="select objid from ".db_prefix("module_objprefs")." where objtype='city' and modulename='quarry' and value=1 and setting='quarryhere' ORDER BY RAND(".e_rand().") Limit 1";
								$res=db_query($sql);
								$row=db_fetch_assoc($res);
								$qp=get_cityprefs_cityname("cityid",$row['objid']);
								//output("cityid-$cityid obj-".$row['objid']." qp-$qp");
								output("`n`nYou look around and realize that the main entrance to the Quarry is actually located in %s.",$qp);
								//$session['user']['location']=$qp;
							}
						}
						set_module_setting("quarryloc",$qp,"quarry");
						//end code by Sixf00t4
						output("You can return to`& %s`6 to find the quarry at any time.`n`n",$location);
						output("`&You are presented with a reward of`^ %s gold `&and`% %s gem%s`&.`n`n",$qgold,$qgems,translate_inline($qgems>1?"s":""));
						output("`&And perhaps the greatest gift, in your honor, the quarry will be named after you!");
						addnews("`&All Citizens `#Rejoice`&!  %s `&discovered a very lucrative `@Q`3uarry`& in`@ %s`&!",$session['user']['name'],$session['user']['location']);
						$session['user']['gold']+=$qgold;
						$session['user']['gems']+=$qgems;
					}else{
						$gemenum=get_module_setting("case25ge");
						if ($gemenum==1) $gemfind=(e_rand(1,2));
						elseif ($gemenum==2) $gemfind=(e_rand(2,3));
						elseif ($gemenum==3) $gemfind=(e_rand(3,5));
						elseif ($gemenum==4) $gemfind=(e_rand(5,10));
						elseif ($gemenum==5) $gemfind=(e_rand(0,1));
						increment_module_setting("quarrycount",1);
						output("`6The rugged terrain disorients you slightly.");
						output("You end up turned around and about, not sure where you are.");
						output("Upon sitting down to rest, you notice a large glimmering object in the ground.`n`n");
						output("`6You've discovered a `!H`%u`\$g`^e `@R`#a`!r`%e `\$G`^e`@m`6!");
						output("It must be worth `^thousands and thousands of gold`6!!!");
						output("However, you accidentally drop it and it shatters into lots of little`% gems`6.");
						output("You gather them together and do a quick count...`n`n ");
						if ($gemfind==1) output("It turns out only one of the gems is worth anything.");
						if ($gemfind>=1)output("You've found `%%s %s`6!",$gemfind,translate_inline($gemfind>1?"gems":"gem"));
						else output("Nope.  Nothing of value.");
						$session['user']['gems']+=$gemfind;
					}
				}else output("`7You find a`@ frog`7. Since you have no need for a`@ frog`7, you move along.`n`n");
			break;
		}
	}
}
?>