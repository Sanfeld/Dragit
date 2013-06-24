<?php
function lostruins_schroyes(){
	output("`c`b`n`QS`^c`Qh`^r`Qo`^e`Qd`^i`Qn`^g`Qe`^r`Q'`^s `QC`^a`Qt`7`c`b`n");
	output("You open the box slowly and peer inside...`n`n");
	switch(e_rand(1,3)){
		case 1:
			output("`7You find a small note attached to the bottom of the box.`n`n `Q'My cat was placed in this box together with a poison that is released when a glowing metal changes slightly...");
			output("The question for you to contemplate is this:`n`nIs my cat `@Alive`Q or`$ Dead`Q?");
			output("`n`nSigned,`n`n `QS`^c`Qh`^r`Qo`^e`Qd`^i`Qn`^g`Qe`^r`Q'`n`7`n`nYou slowly open the box to find that the cat turns out to be ");
			switch(e_rand(1,2)){
				case 1:
					output("`@ALIVE`7!`n`nHe purrs softly at you and wanders away. You contemplate this happy lesson in quantum physics and go on with your adventures.");
				break;
				case 2:
					output("`$ DEAD`7!`n`n You contemplate this sad lesson in quantum physics and go on with your adventures.");
				break;
			}
		break;
		case 2:
			output("`7There is a dead cat in the box. It appears as if a `2poison`7 was released that killed `QS`^c`Qh`^r`Qo`^e`Qd`^i`Qn`^g`Qe`^r`Q'`^s `QC`^a`Qt`7.");
			output("`n`nBefore you get a chance, you accidentally inhale and the`2 poison`7 weakens you.");
			apply_buff('schrocat',array(
				"name"=>"`QS`^c`Qh`^r`Qo`^e`Qd`^i`Qn`^g`Qe`^r`Q'`^s `2P`@o`2i`@s`2o`@n",
				"rounds"=>10,
				"wearoff"=>"`^The poison leaves your system and you feel better.",
				"atkmod"=>.7,
				"defmod"=>.7,
				"roundmsg"=>"`QThe poison slows your ability to fight and defend yourself.",
				"activate"=>"offense"
			));
		break;
		case 3:
			output("`7A happy little cat bounces out. It turns out he was`& alive`7!`n`n `QS`^c`Qh`^r`Qo`^e`Qd`^i`Qn`^g`Qe`^r`Q'`^s `QC`^a`Qt`7 will help you fight!");
			apply_buff('schrocat',array(
				"name"=>"`QS`^c`Qh`^r`Qo`^e`Qd`^i`Qn`^g`Qe`^r`Q'`^s `QC`^a`Qt",
				"rounds"=>10,
				"wearoff"=>"`^The cat disappears slowly, with a grin being the last you see of him.",
				"atkmod"=>2,
				"defmod"=>2,
				"roundmsg"=>"`QWith a style that is paralleled only in other dimensions, the cat fights by your side.",
				"activate"=>"offense"
			));
		break;
	}
}
?>