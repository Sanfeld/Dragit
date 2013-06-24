<?php
function lostruins_case8(){
	global $session;
	output("`7You come upon an archaeological team analyzing a special section of the `5A`6ncient `5R`6uins`7.");
	output("The lead archaeologist steps up and shows you around the site.`n`nAn excited murmur perks up as a huge sarcophagus is uncovered!`n`n");
	switch(e_rand(1,3)) {
		case 1:
			output("Your heart skips a beat as you realize that this is one of the`Q Cursed Mummies`7 of the `5A`6ncient `5R`6uins`7!!");
			output("Before you get a chance to stop them, they open the sarcophagus... and a terrible `$ evil `7  stench escapes.");
			output("You fall to the ground.`n`nYou awaken several hours later.`n`n You have been`&`b cursed`b`7 and are horribly weakened.`n`nYou lose `^all your gold`7.");
			$session['user']['gold']=0;
			apply_buff('curse',array(
				"name"=>"`&Mummy's Curse",
				"rounds"=>15,
				"wearoff"=>"`&The Curse of the Mummy is over!",
				"atkmod"=>.55,
				"defmod"=>.55,
				"roundmsg"=>"`&The curse prevents you from performing at your best",
			));
		break;
		case 2: case 3:
			$case8g=get_module_setting("case8g");
			output("Your heart lightens as you realize this is one of the `QBlessed Mummies`7 that causes everyone to be really happy and energetic.`n`nYou `@Gain 2 ");
			if ($case8g>0) output("turns`7 and `^%s gold`7!!`n`n",$case8g);
			else output("turns`7.");
			$session['user']['gold']+=$case8g;
			$session['user']['turns']+=2;
			break;
	}
}
?>