<?php
function lostruins_tfriend(){
	global $session;
	if (is_module_active('alignment'))  increment_module_pref("alignment",+4,"alignment");
	output("`n`c`b`@T`2urtle `@F`2riendship`c`b`n");
	output("`7That's very `&nice`7 of you! Of course, since it seems like the turtle can talk, you decide to see if it will be your friend.`n`n");
	switch(e_rand(1,3)){
		case 1:
			output("`7The turtle looks up at you with disbelief.");
			output("`n`n `@'Who are you kidding?");
			output("You just spent five minutes	sitting on my back and NOW you want to be my friend?!?!");
			output("I think not!'");
			output("`n`n `7The turtle bites you and wanders off.");
			output("You stand stunned in disbelief.");
			output("That bite cost you`$ 10 hitpoints`7!");
			output("Oh well!`n`n");
			$session['user']['hitpoints'] -= 10;
			if ($session['user']['hitpoints']<=0){
				output("`7Suddenly, you realize that the bite was`$ fatal`7.");
				output("`n`n The `@turtle`7 comes back and steals all your `^gold`7.`n`n");
				$exploss = round($session['user']['experience']*.05);
				output("`b`4You lose `#%s experience`4.`b`n`n",$exploss);
				addnews("%s`^ died from a `@turtle`^ bite.",$session['user']['name']);
				addnav("Daily news","news.php");
				blocknav("village.php");
				$session['user']['experience']-=$exploss;
				$session['user']['alive'] = false;
				$session['user']['hitpoints'] = 0;
				$session['user']['gold']=0;
			}
		break;
		case 2:
			output("`7The `@turtle`7 turns to you and smiles.");
			output("`@'Sure I'll be your friend'`7.");
			output("`n`nTogether you wander back to the village looking for adventure.");
			apply_buff('turtlepower',array(
				"name"=>"`@Turtle Power",
				"rounds"=>9,
				"invulnerable"=>9,
				"wearoff"=>"`%The `@turtle `% can't take anymore and wanders off!",
				"roundmsg"=>"`5The `@turtle`5 jumps in front of the attacker and absorbs all the damage!",
			));
		break;
		case 3:
			output("`@'Thank you for the invitation, but I have to go see my family.");
			output("Take care!'`n`n`7");
			output("As you watch the `@turtle`7 walk away, you notice that he was sitting on `%gem`7!");
			output("`n`n However, you `@lose one turn`7 waiting for the `@turtle`7 to leave.");
			$session['user']['gems']++;
			$session['user']['turns']--;
		break;
	}
}
?>