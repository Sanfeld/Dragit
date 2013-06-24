<?php

function balrog_getmoduleinfo(){
	$info = array(
			"name"=>"The Balrog",
			"author"=>"John M",
			//based on Mad Monk by Ges;
			"version"=>"1.5",
			"category"=>"Forest Specials",
			"download"=>"http://dragonprime.net/users/ges/balrog.zip",
			"settings"=>array(
					"Balrogs Settings,title",
					"mindk"=>"How many DK's before a player encounters the Balrog?,int|10",

					"atk"=>"Multiplier of attacker's attack to get Creature Attack,floatrange,0,2,.02|1.3",
					"def"=>"Multiplier of attacker's defense to get Creature Defense,floatrange,0,2,.02|1.3",
					"hp"=>"Multiplier of attacker's max HP to get Creature Hitpoints,floatrange,0,2,.02|1.2",
					), 
		);
	return $info;
}
function balrog_install(){
	module_addeventhook("forest","return 15;");
	return true;
	}
function balrog_uninstall(){
	return true;
}

function balrog_runevent($type){
	global $session;
	require_once("lib/increment_specialty.php");
	$op = "";
	$op = httpget('op');
	$page = httpget('page');

	$atk = get_module_setting("atk");
	$def = get_module_setting("def");
	$hp = get_module_setting("hp");
	$aug = get_module_setting("aug");
	$mxs = get_module_setting("mxs");
	$rdm = get_module_setting("rdm");
	$mindk=get_module_setting("mindk");
		if ($session['user']['dragonkills'] <=$mindk)
			{
                          output("You sense an unearthly presence, and feel a cold chill down your spine, but nothing bad happens.");
                          output("You're not sure why, but you feel as if you've narrowly escaped death.");

			return;
}

	$session['user']['specialinc'] = "module:balrog";
		if ($op == "" || $op == "search")
			{
			output("`) As you are exploring the forest just outside of %s, your keen senses become aware of the smell of smoke.",($session['user']['location']));
			output(" `n`n Looking around, you see an orange glow in the distance.");
                        output("You ears pick up the sounds of something crashing through the trees, and wonder if the fire has created a stampede.");
			output("`n`nA frightened adventurer comes running from the direction of the fire. ");
                        output("His eyes are wide and his face is pale, he runs up to you and grabs you by the collar...");
			output("`n`n`^\"RUN! We must RUN! It's the BALROG!!!\"`) He cries.");
			output(" `n`nThe crashing sounds grow closer, you wonder if the panicky adventurer is correct.");
                        output("If so, then you are the only protection %s has from one of the fiercest monsters ever known.",($session['user']['location']));
				output("`n`n`)You see a large tree snap into pieces, as a growing aura of flame comes into view.");
                                output("In the heart of the flames, is a being about twenty feet tall, and at least nine feet wide at the shoulders.");
                                output("The aura of flames around it's body makes it hard for you to perceive it's full form, but it's demonic wings, and powerful whip are easily visible as it approaches.`n");
			addnav("The Balrog");
			addnav("Stand","forest.php?op=stand");
			addnav("Flee","forest.php?op=withdraw");
			$battle = false;

			}
		elseif ($op == "stand")
			{

		output("`)You draw your `!%s, `)and stand fast.",translate_inline($session['user']['weapon']));

			$badguy = array(
				"creaturename"=>"Balrog",
				"creatureweapon"=>"Flaming Whip",
				"creaturelevel"=>$session['user']['level']+1,
				"creatureattack"=>round($session['user']['attack']*$atk),
				"creaturedefense"=>round($session['user']['defense']*$def),
				"creaturehealth"=>round($session['user']['maxhitpoints']*$hp), 
				"diddamage"=>0,);

			apply_buff('balaura', array(
						"startmsg"=>"`4As you move towards the Balrog, you feel the heat of its flaming aura.",
						"name"=>"`4Flaming Aura",
						"rounds"=>15,
						"minioncount"=>1,
						"wearoff"=>"You've weakened the vile beast enough to extinguish it's flames.",
						"mingoodguydamage"=>0,
                                                "maxgoodguydamage"=>round(($session['user']['maxhitpoints']/4)),

                                                "effectmsg"=>"`4You are toasted by the searing heat of the Balrog for`Q {damage} `4points!",
                                                  "effectnodmgmsg"=>"You strike quickly and jump back before the heat can affect you.",
						"schema"=>"module-balrog",));
						$session['user']['badguy'] = createstring($badguy);
			$op = "fight";
			httpset('op',$op);
			}
		elseif ($op == "withdraw")
			{
			$session['user']['specialinc'] = "";
			output("`)You realize that despite being the only hope the town has, you have to look out for your own skin first. You swallow your pride and join the panicked adventurer in fleeing the scene.");
			output("`n`nYou feel deeply ashamed for fleeing and leaving all those innocent townspeople to certain deaths. You lose `!5 charm points`) for your cowardice!");
			$session ['user']['charm']-=5;
				addnews("%s fled like a coward, leaving the city of %s helpless before the Balrog.",$session['user']['name'],$session['user']['location']);
                                addnews("The city of %s was attacked by the Balrog, many innocents were killed and much of the town has been destroyed.");
			$battle = false;
			}

		if ($op == "fight")
			{
                          output("`)The panicked adventurer realizes that you're not joining him and screams...");
			switch (e_rand(1,5))
				{
				case 1:

					output("`c`i`@We're all going to die!`i`c");

					break;
				case 2:

					output("`c`i`@You're my HERO! (You push him away before he can kiss you.)`i`c");

					break;
				case 3:

					output("`c`i`@Are you MAD! It will snap you like a twig!`i`c");

					break;
				case 4:

					output("`c`i`@Don't be a FOOL! We must run for our lives!!?!`i`c");

					break;
				case 5:

					output("`c`i`@Your so BRAVE! I'm such a coward!`i`c");

					break;
				}
			$battle = true;
			}

		if ($battle)
			{
			include("battle.php");

			if ($victory)
				{
				$session['user']['specialinc'] = "";
				$expgain=$session['user']['experience']*.15;
				if ($session['user']['level'] > 9) $expgain *= 0.8;
			if ($session['user']['level'] < 6) $expgain *= 1.2;
			if ($session['user']['level'] == 1) $expgain += 20;
			$expgain=round($expgain);
                          //exp formula by Chris Vorndran from tatmonster.php
				$session['user']['experience']+=$expgain;
                                strip_buff('balaura');
				output("`)The Balrog screams, and shrinks in size. A beautiful white glow appears around your`! %s, `)and a circular vortex opens directly behind the unholy beast.",$session['user']['weapon']);
				output("`n`n Instinctively, you push forward, your glowing weapon forces him back into the vortex and out of this reality.");
				output(" `n`n`c`&The white glow surrounds your entire body, and you feel your wounds being healed.`c`n");
                               	output("`n`c`b`)You gained `^$expgain `)experience from the battle.`b`c");
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
				$gold=($session['user']['maxhitpoints']*3)+123;
				output("`)`n`n`cAs the energy fades, you find`^ %s gold `)on the ground in front of you.`c",translate_inline($gold));
				output("`n`n`cYour heroic actions make you feel very `!charming!`c");
				$session['user']['gold']+=$gold;
				$session['user']['charm']+=5;

					addnews("%s has saved the city of %s from the horrors of the Balrog.",$session['user']['name'],$session['user']['location']);

				if ($session['user']['charm'] <=10)
					{
					output("`)As you put out the brush fires started by the Balrog, a beautiful Dryad steps out from one of the trees.");
					output("`&\"Thank you for saving my tree, and my life.\"` She says, kissing you lightly on the cheek.");
					output("`n`n`cYou feel even more `!charming!`)`c");
					$session['user']['charm']=10;
					$session['user']['specialinc']="";
					return;
					}


				if ($session['user']['charm'] >=100)
					{   $giftgems= (rand(1, 3)+3);
					    output("`)The citizens of %s come out to put out the forest fire.",$session['user']['location']);
					    output("When they learn of your heroism, they decide to throw a celebration in your honor!");
					    output("`n`nYou are carried back to town on their shoulders, and right up the steps to City Hall.");
					    output("The local chefs bring their finest foods, and the barkeeps break out barrels of fresh ale!");
					    output("You receive several marriage proposals, and the Mayor gives you the keys to the city.");
					    output("`n`nYou are given an extra`! %s gems `)as a reward for your services!",translate_inline($giftgems));
                                            $session['user']['gems']+=$giftgems;

					    debuglog("received $giftgems gems from the grateful townspeople.");
					    return;
					}
				else	{   







						{

						}



					};

				}
			elseif($defeat)
				{
				$session['user']['specialinc'] = "";
				$exploss = round($session['user']['experience']*.05);
				output("`c`n`n`)`bThe Balrog's whip catches you in the head, knocking you unconscious.");
				output("`)The Balrog finishes you off effortlessly.");
				output ("`n`nThe panicked adventurer shouts `^\"I told you so!\"`) as he runs away (taking your gold with him).");
				output("`n`nYou lose `^$exploss `)experience.`b`c`0");
				$session['user']['experience']-=$exploss;
				$session['user']['alive'] = false;
				$session['user']['hitpoints'] = 0;
				$session['user']['gold'] = 0;
				strip_buff('balaura');
				debuglog("lost $exploss experience to the Balrog.");
				addnews("%s died heroically trying to save the city of %s from the Balrog.",$session['user']['name'],$session['user']['location']);
				addnav("Return");
				addnav("Return to the Shades","shades.php");
				}
			else
				{
				fightnav(true,false);



				}
			}
		}

?>
