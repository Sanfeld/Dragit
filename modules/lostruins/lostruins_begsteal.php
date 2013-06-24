<?php
function lostruins_begsteal(){
	global $session;
	if (is_module_active('alignment')) increment_module_pref("alignment",-5,"alignment");
	output("`c`n`b`6S`5tealing `6f`5rom `6t`5he `6B`5eggar`c`b`n");
	output("`7Since honor means nothing to you, you decide to rob the beggar. You show him your`b`^ %s`b`7 and demand he turn over everything he's got.`n`n",$session['user']['weapon']);
	switch(e_rand(1,13)){
		case 1: case 2:
			output("The beggar looks at you with panic and desperation. He pulls out a magic pebble and swallows it.`n`nYou watch helplessly as the little beggar turns into a deadly `bStone `QGolem`b`7.");
			blocknav("runmodule.php?module=lostruins&op=explore");
			blocknav("village.php");
			addnav("`7Stone`Q Golem`$ Fight","runmodule.php?module=lostruins&op=attack");
		break;
		case 3:
			output("The beggar looks at you sad and forlorn. With a grace unbecoming of a beggar, he steps towards you and disarms you with a quickly drawn sword.");
			output("`n`n`b`Q'This is a great disappointment to me.'`b`Q`7`n`nThe beggar removes his clothing and you realize it is none other than `&the King`7 in disguise.`n`n");
			if (is_module_active("jail")) {
				set_module_pref("injail",1,"jail");
				blocknav("runmodule.php?module=lostruins&op=explore");
				blocknav("village.php");
				addnav("To your cell","runmodule.php?module=jail");
				output("`b`Q'Instead of generosity, I shall take away your freedom.`b`7");
				output("`n`nYou are suddenly surrounded by the`& King's Men`7 and escorted to jail.");
			}else{
				output("`b`Q'We shall take all your gold and give it to someone more deserving than yourself. And maybe a I'll teach you a little lesson to remember how much it hurts when you don't give.'`b`Q");
				output("`n`n`7The `&King's Men`7 rough you up quite a bit, almost leaving you for dead.");
				$session['user']['gold']=0;
				$session['user']['hitpoints']=1;
			}
			output("`n`n`$ Evil`7 does not always pay.");
		break;
		case 4: case 5: case 6: case 7:
			$case3g=get_module_setting("case3g");
			$case3ge=get_module_setting("case3ge");
			output("The beggar looks at you with desperate hopelessness. He then collapses at your feet and you hear a loud `#j`@i`!n`%g`\$l`^e`7.");
			output("`n`n It turns out that the beggar is actually a ruthless `3Con Man`7. You steal all his `^gold`7%s, give him a kick, and send him on his way.`n`n",translate_inline($case3ge>1?" and `%gems`7":""));
			$session['user']['gold']+=$case3g;
			$session['user']['gems']+=$case3ge;
			output("You collect `^%s gold`7%s",$case3g,translate_inline($case3ge<=0?".":""));
			if ($case3ge>0) output("and `%%s %s`7.",$case3ge,translate_inline($case3ge>1?"gems":"gem"));
			output("Who says that`$ Evil`7 doesn't pay?");
		break;
		case 8: case 9: case 10: case 11: case 12: case 13:
			output("`7You grab the cup and smile as you count out your`$ evil`7 haul.`n`nYou collect `^35 gold`7 and `%a gem`7.");
			$session['user']['gold']+=35;
			$session['user']['gems']++;
		break;
	}
}
?>