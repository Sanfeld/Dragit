<?php
function lostruins_donothing(){
	global $session;
	output("`n`c`b`5S`6tunned `5S`6ilence`c`b`n");
	switch(e_rand(1,5)){
		case 1:
			output("`7The fog uplifts your spirit and you take several moments to meditate on the peacefulness of the world.");
			output("You close your eyes and	enjoy the soothing sensation of feeling alive!`n`n");
			$expbonus=$session['user']['dragonkills']*2;
			$expgain =($session['user']['level']*30+$expbonus);
			$session['user']['experience']+=$expgain;
			output("You spent an `@extra turn`7 in the fog, but you `# gain %s experience`7.",$expgain);
		break;
		case 2:
		case 3:
			output("`7You `^stand for a couple of seconds `7patiently waiting for further directions but none come.");
			output("You start looking down at the ground kicking stones and you uproot a`^ S`\$trange `^T`\$riangle`7.");
			output("`n`n A small boy comes running up and kicks you between the legs and steals it from you.");
			output("Luckily, it didn't hurt.");
			output("As he runs away, a bag of `Qcheesy snacks`7 fall out his pocket.");
			output("You snatch them up and enjoy a quick bite.`n`n");
			output("You gain `@3 turns`7!!");
			$session['user']['turns']+=3;
		break;
		case 4:
		case 5:
			output("`7For what seems like an eternity, you stand confused by this unanticipated appearance of fog.");
			output("You inhale deep, and realize that it isn't fog, it's `@wacky tobacky`7!!");
			output("`n`n That's why you couldn't think of anything to do!");
			output("Well, now you're really hungry.`n`n");
			if (is_module_active("kitchen")) {
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				addnav("Raid the Kitchen","runmodule.php?module=kitchen&op=food","kitchen");
				set_module_pref("eatentoday",0,"kitchen");
			}else{
				output("You look around for something to eat, but you can't find anything.");
				output("This is SUCH a `b`3buzzkill`7`b!");
				output("`n`nYou lose `@2 turns`7 looking for food.");
				$session['user']['turns']-=2;
			}
		break;
	}
}
?>