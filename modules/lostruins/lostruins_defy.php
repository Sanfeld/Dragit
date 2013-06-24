<?php
function lostruins_defy(){
	global $session;
	output("`n`c`b`5D`6efying `5t`6he `5F`6og`c`b`n");
	output("`7Not believing this fake fog could be the sign of divine intervention, you stand proudly and yell back `\$'NO!  I will NOT kneel!'`n`n");
	switch(e_rand(1,5)){
		case 1:
		case 2:
			output("`7You get hit in the head by a`^ broom`7 wielded by an elephant.");
			output("`n`nYou realize that the fog was actually dust and that in reality the local `5A`6ncient `5R`6uins`7 clean up crew was just trying to get you to watch out so you wouldn't get hit by the `^broom`7.`n`n");
			if ($session['user']['hitpoints']<=8){
				output("`7The `%'wack'`7 was fatal.");
				output("Perhaps next time you'll kneel.");
				output("The good news is that the crew makes sure you keep your gold.`n`n");
				$exploss = round($session['user']['experience']*.01);
				output("`b`4You lose `#%s experience`4.`b`n`n",$exploss);
				addnews("%s`^ died when a broom hit %s`^ while the clean-up crew was working in the `5A`6ncient `5R`6uins`^.",$session['user']['name'],translate_inline($session['user']['sex']?"her":"him"));
				addnav("Daily news","news.php");
				blocknav("village.php");
				blocknav("runmodule.php?module=lostruins&op=explore");
				$session['user']['experience']-=$exploss;
				$session['user']['alive'] = false;
				$session['user']['hitpoints'] = 0;
			}else{
				output("`7You rub your head and give the work crew an`$ evil`7 look.");
				output("The dust lifts and you see `bSix Elephants`b wander off to do more cleaning.");
				$session['user']['hitpoints']-= 8;
			}
		break;
		case 3:
		case 4:
			output("`&`b'Wow.");
			output("You are REALLY arrogant.");
			output("I mean, here I am, your Lord above, and you don't have the decency to kneel?'`b");
			output("`n`n`7You start to shiver in fear.");
			output("`n`n`&`b'You're lucky I have a play to see, otherwise I'd smite you.");
			output("You should check it out	if you ever get the chance.");
			output("It's called `%'Making Strangers Kneel'`&.");
			output("Alrighty then, Good bye!'`b`n`n");
			output("`7The fog lifts and the world returns to normal.");
			output("You realize you got VERY lucky there.");
		break;
		case 5:
			output("`7From out of the fog comes`% 25 wizards`7 smoking pipes furiously.");
			output("It turns out that this wasn't fog!");
			output("Just localized air pollution.");
			output("`n`nAfter a coughing fit, you look at the group and they apologize for bothering you with all the`& smoke`7.");
			output("`n`nTo make things a little better, they put on an amazing`& smoke`7 show that tells the story of the very first `@`bGreen Dragon`b`7 from years ago.");
			output("The wonderful tale enthralls and captures your imagination.`n`n ");
			$expbonus=$session['user']['dragonkills']*3;
			$expgain =($session['user']['level']*22+$expbonus);
			$session['user']['experience']+=$expgain;
			output("You gain `#%s experience`7!",$expgain);
		break;
	}
}
?>