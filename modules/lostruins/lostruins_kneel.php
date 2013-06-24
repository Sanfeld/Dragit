<?php
function lostruins_kneel(){
	global $session;
 	output("`n`c`b`5K`6neeling `5B`6efore`c`b`n");
	output("`7With reverence, you ask `@'What can I do for you, oh Lord?'`n`n");
	if (is_module_active('alignment')) increment_module_pref("alignment",2,"alignment");
	switch(e_rand(1,5)){
		case 1:
			output("`&`b'Oh stop kneeling. It's just like those miserable psalms, always so depressing.`n`nI am pleased with you.");
			output("I give you the ultimate power. The `@Holy Hand Grenade`&!!!'`b`n`n`7Although you know you only get to use it once, you know that you are blessed!");
			apply_buff('grenade',array(
				"name"=>"Holy Hand Grenade",
				"rounds"=>1,
				"wearoff"=>"Thou foe, who being naughty in My sight, shall snuff it!",
				"atkmod"=>100,
				"defmod"=>100,
				"roundmsg"=>"One... Two... Five! I mean Three!",
				"activate"=>"offense"
			));
		break;
		case 2: case 3:
			output("`7You hear laughter around you as the stage fog clears and a troup of performers dances around you.`n`n`#'We're so sorry!");
			output("We've been practicing a scene from our new play `%'Making Strangers Kneel'`# and we had to test it out on someone. Thank you for being so helpful!'`n`n");
			output("`7The performers help you to your feet and smile happily with you. You give a wry chuckle.`n`n`#'Because you've been such a good sport, we wanted to give you the title of `^'Penitent' `#if you are interested.`n`n`7Would you like to accept this title?");
			addnav("`^Penitent Title","runmodule.php?module=lostruins&op=penitent");
		break;
		case 4: case 5:
			output("`7The voice of God responds `&`b'Hello?  Is that you Douglas? Oh wait, I think I have the wrong number. Sorry!'`b");
			output("`n`n`7And with that, the fog lifts.`n`n You pause to reflect on what just happened and you and start to feel a little depressed. Your depression causes you to `@lose one turn`7.`n`n");
			$session['user']['turns']--;
		break;
	}
}
?>