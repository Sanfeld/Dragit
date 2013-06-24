<?php
function lostruins_map(){
	global $session;
	output("`n`c`b`5S`6ecret `5M`6ap`c`b`n");
	$jackpot=(e_rand(1,6));
	if ($jackpot==6){
		$case10g=get_module_setting("case10g");
		$goldfind=round(e_rand($case10g/2,$case10g));
		$gemenum=get_module_setting("case10ge");
		if ($gemenum==1) $gemfind=(e_rand(1,2));
		if ($gemenum==2) $gemfind=(e_rand(2,3));
		if ($gemenum==3) $gemfind=(e_rand(3,5));
		if ($gemenum==4) $gemfind=(e_rand(5,10));
		$session['user']['gold']+=$goldfind;
		$session['user']['gems']+=$gemfind;
		output("`7You start to dig and soon enough you uncover a `b`!Pirate's Buried `^Treasure`7`b!!!!`n`n");
		output("You happily count the haul!");
		output("You find `^%s gold`7 and `%%s %s`7!!!",$goldfind,$gemfind,translate_inline($gemfind>1?"gems":"gem"));
	}elseif ($jackpot<=3){
		output("`7You do a quick little dig but you don't find anything of value.");
		if ($session['user']['turns']>1){
			$session['user']['turns']--;
			addnav("Dig Some more!","runmodule.php?module=lostruins&op=map");
			output("Maybe this wasn't the right spot.");
			output("Perhaps if you `@Spend another Turn`7 you'll find the treasure...");
		}else output("`n`nYou realize you don't have any more time to spend in the `5A`6ncient `5R`6uins`7.");
	}else{
		$case10g=get_module_setting("case10gs");
		$case10ge=get_module_setting("case10ges");
		$goldfind=round(e_rand($case10g/2,$case10g));
		$gemfind=round(e_rand($case10ge/2,$case10ge));
		$session['user']['gold']+=$goldfind;
		$session['user']['gems']+=$gemfind;
		output("`7You decipher the basics of the map and follow the directions up and over several strange looking mounds.");
		output("Finally, you see where the `&'X'`7 marks the spot.`n`n");
		output("`7You do a quick little dig and discover");
		output("`^%s gold`7%s",$goldfind,translate_inline($gemfind==0?".":""));
		if ($gemfind>0) output("and `%%s %s`7!!!",$gemfind,translate_inline($gemfind>1?"gems":"gems"));
		if ($session['user']['turns']>1){
			$session['user']['turns']--;
			addnav("Dig Some more!","runmodule.php?module=lostruins&op=map");
			output("You check the time and realize you could spend some more time searching.");
			output("Maybe this wasn't the right spot.");
			output("Perhaps if you `@Spend another Turn`7 you'll find the treasure...");
		}else{
			output("You realize you don't have any more time to spend in the `5A`6ncient `5R`6uins`7.");
		}
	}
}
?>