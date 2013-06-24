<?php
	global $session;
	$op = httpget('op');
	$exploreturns=get_module_setting("exploreturns");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$usedexpts=$allprefs['usedexpts'];
	$uturns=$session['user']['turns'];
	page_header("Lost Ruins");
if ($op=="superuser"){
	require_once("modules/allprefseditor.php");
	allprefseditor_search();
	page_header("Allprefs Editor");
	$subop=httpget('subop');
	$id=httpget('userid');
	addnav("Navigation");
	addnav("Return to the Grotto","superuser.php");
	villagenav();
	addnav("Edit user","user.php?op=edit&userid=$id");
	modulehook('allprefnavs');
	$allprefse=unserialize(get_module_pref('allprefs',"lostruins",$id));
	if ($allprefse['usedexpts']=="") $allprefse['usedexpts']= 0;
	if ($allprefse['sexcount']=="")$allprefse['sexcount']= 0;
	set_module_pref('allprefs',serialize($allprefse),'lostruins',$id);
	if ($subop!='edit'){
		$allprefse=unserialize(get_module_pref('allprefs',"lostruins",$id));
		$allprefse['firstruin']= httppost('firstruin');
		$allprefse['usedexpts']= httppost('usedexpts');
		$allprefse['sexcount']= httppost('sexcount');
		set_module_pref('allprefs',serialize($allprefse),'lostruins',$id);
		output("Allprefs Updated`n");
		$subop="edit";
	}
	if ($subop=="edit"){
		require_once("lib/showform.php");
		$form = array(
			"Lost Ruins,title",
			"firstruin"=>"Has the player ever been to the ruins?,bool",
			"usedexpts"=>"How many times did they explore today?,int",
			"sexcount"=>"Number of days until sex change reverts back?,int",
		);
		$allprefse=unserialize(get_module_pref('allprefs',"lostruins",$id));
		rawoutput("<form action='runmodule.php?module=lostruins&op=superuser&userid=$id' method='POST'>");
		showform($form,$allprefse,true);
		$click = translate_inline("Save");
		rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=lostruins&op=superuser&userid=$id");
	}
}
if ($op=="enter") {
	output("`n`c`b`5L`6ost `5R`6uins`c`b`n");
	if ($allprefs['firstruin']==0){
		$allprefs['firstruin']=1;
		set_module_pref('allprefs',serialize($allprefs));
		$allprefs=unserialize(get_module_pref('allprefs'));
		output("`7You leave the safety of the`@ village`7 and wander around for a while. While stumbling over some uneven terrain, you notice peculiar shapes.");
		output("After a quick study of the landscape, you enter the `6R`5uins`7 of an `6A`5ncient `6C`5ity`7.`n`nThe area is uneven and looks a little dangerous, but who knows what awaits if you do some exploring.");
		output("`n`nIt will probably take you `@a turn`7 to find anything interesting. You may explore `# %s times a day`7.`n`n Are you ready to explore the `5L`6ost `5R`6uins`7? `n`n",$exploreturns);
	}else{
		output("`7After a little searching, you find the `5L`6ost `5R`6uins`7 again and hope for some grand adventures.`n`n");
		output("You may explore`# %s %s a day`7.`n`n",$exploreturns,translate_inline($exploreturns>1?"times":"time"));
		if ($usedexpts==0) output("You haven't been to the `5L`6ost `5R`6uins`7 yet today. ");
		else output("Today you have explored the `5L`6ost `5R`6uins`3 %s %s`7.",$usedexpts,translate_inline($usedexpts>1?"times":"time"));
		if ($usedexpts<$exploreturns && $uturns >0) output("`n`nEach exploration will only take you `@one turn`7.`n`n`^ Ready to go?");
		else output("You're done exploring the `5L`6ost `5R`6uins`7 for today.");
	}
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore `5L`6ost `5R`6uins","runmodule.php?module=lostruins&op=explore");
	elseif($uturns <=0) output("`n`n`7Unfortunately you don't have the energy to explore the Lost Ruins today.");
	addnav("V?(V) Return to Village","village.php");
}
if($op=="explore") {
	require_once("modules/lostruins/lostruins_explore.php");
	lostruins_explore();
}
if ($op=="welcome"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("lib/commentary.php");
	addcommentary();
	output("`n`c`b`5L`6ost `5R`6uins`c`b`n");
	output("`7At the entrance there are other explorers contemplating searching through the `5L`6ost `5R`6uins`7.`n`nThey await your input.`n`n");
	viewcommentary("ancientruins","Speak Freely",20,"says");
}
if ($op=="monkgold"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`qM`Qonkey's `qP`Qaw`c`b`n");
	output("`7The `qM`Qonkey's `qP`Qaw`7 is `\$c`^u`\$r`^s`\$e`^d`7.`n`n");
	$session['user']['gems']-=7;
	if ($session['user']['level']<get_module_setting("case2")){
		switch(e_rand(1,5)){
			case 1:
				output("You receive your `^3000 gold`7... in the form of a new title!!!`n`nBy the way, the `\$c`^u`\$r`^s`\$e`7 causes you to `%lose 7 gems`7.");
				require_once("lib/titles.php");
				require_once("lib/names.php");
				$newtitle = "3000Gold";
				$newname = change_player_title($newtitle);
				$session['user']['title'] = $newtitle;
				$session['user']['name'] = $newname;
			break;
			case 2:
				output("You receive `^300 gold`7... it looks like the `qM`Qonkey's `qP`Qaw`7 dropped a decimal place... oops!");
				output("`n`nBy the way, the `\$c`^u`\$r`^s`\$e`7 causes you to `%lose 7 gems`7.");
				$session['user']['gold']+=300;
			break;
			case 3:
				output("You receive `^3000 gold`7, but are taxed a `%gift tax`7 of 80 percent... yay! You get 600 gold!");
				output("`n`nBy the way, the `\$c`^u`\$r`^s`\$e`7 causes you to `%lose 7 gems`7.");
				$session['user']['gold']+=600;
			break;
			case 4:
				output("`qM`Qonkey's `qP`Qaw`7 feels that you don't deserve to be cursed.  Nothing happens!");
				$session['user']['gems']+=7;
			break;
			case 5:
				$session['user']['gold']+=3000;
				output("You receive your `^3000 gold`7, but the `\$c`^u`\$r`^s`\$e`7 causes you to `%lose 7 gems`7.");			
			break;
		}
	}else{
		$session['user']['gold']+=3000;
		output("You receive your `^3000 gold`7, but the `\$c`^u`\$r`^s`\$e`7 causes you to `%lose 7 gems`7.");
	}
}
if ($op=="monkhitpoints"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`qM`Qonkey's `qP`Qaw`c`b`n");
	output("`7The `qM`Qonkey's `qP`Qaw`7 is `\$c`^u`\$r`^s`\$e`^d`7.`n`nYou receive your `\$100 hitpoints`7, but the `\$c`^u`\$r`^s`\$e`7 causes you to `&lose 4 charm`7.");
	$session['user']['hitpoints']+=100;
	$session['user']['charm']-=4;
}
if ($op=="monkfavor"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`qM`Qonkey's `qP`Qaw`c`b`n");
	output("`7The `qM`Qonkey's `qP`Qaw`7 is `\$c`^u`\$r`^s`\$e`^d`7.`n`nYou receive your `4100 favor`7, but the `\$c`^u`\$r`^s`\$e`7 weakens you and causes you to `&lose 1 attack point`7.");
	$session['user']['deathpower']+=100;
	$session['user']['attack']--;
}
if ($op=="monkgems"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`qM`Qonkey's `qP`Qaw`c`b`n");
	output("`7The `qM`Qonkey's `qP`Qaw`7 is `\$c`^u`\$r`^s`\$e`^d`7.`n`nYou receive your `%6 gems`7, but the `\$c`^u`\$r`^s`\$e`7 weakens you and causes you to `&lose 1 defense point`7.");
	$session['user']['gems']+=6;
	$session['user']['defense']--;
}
if ($op=="monkexp"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`qM`Qonkey's `qP`Qaw`c`b`n");
	output("`7The `qM`Qonkey's `qP`Qaw`7 is `\$c`^u`\$r`^s`\$e`^d`7.`n`nYou receive your `#10% experience`7, but the `\$c`^u`\$r`^s`\$e`7 weakens you and causes you to `&lose 5 Permanent Hitpoints`7.");
	$session['user']['experience']+=$session['user']['experience']*.1;
	$session['user']['maxhitpoints']-=5;
}
if ($op=="monkcharm"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`qM`Qonkey's `qP`Qaw`c`b`n");
	output("`7The `qM`Qonkey's `qP`Qaw`7 is `\$c`^u`\$r`^s`\$e`^d`7.`n`nYou receive your `&1 Charm Point`7.Your modest wish allows you to receive your gift without being cursed!");
	$session['user']['charm']++;
}
if ($op=="lilgold") {
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`c`n`b`^Donation of Gold`c`b`n`7You take `^1 Gold`7 and hand drop it into the beggar's cup. The clinking sound is a pleasure to his ears and a joy to your soul.`n`nYou feel `@Good`7.");
	$session['user']['gold']--;
	if (is_module_active('alignment')) increment_module_pref("alignment",1,"alignment");
}
if ($op=="biggold") {
	output("`c`n`b`^Donation of Gold`c`b`n");
	output("`7You take `^1000 Gold`7 and hand drop it into the beggar's cup. His cup is filled, and he smiles with pleasure.`n`nYou feel `@Very Good`7.");
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	$session['user']['gold']-=1000;
	if (is_module_active('alignment')) increment_module_pref("alignment",6,"alignment");
}
if ($op=="lilgem") {
	output("`c`n`b`%Donation of a Gem`c`b`n");
	output("`7You take `%a gem`7 and drop it into the beggar's cup. He picks up the gem and bites it to confirm it's real. He smiles at you and tips his hat.`n`nYou feel `@Pretty Good`7.");
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	$session['user']['gems']--;
	if (is_module_active('alignment')) increment_module_pref("alignment",3,"alignment");
}
if ($op=="biggem") {
	output("`c`n`b`%Donation of Gems`c`b`n");
	output("`7You take `%5 gems`7 and drop them into the beggar's cup.`n`n");
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	if (is_module_active('alignment')) increment_module_pref("alignment",8,"alignment");
	switch(e_rand(1,5)){
		case 1: case 2: case 3: case 4:
			output("The beggar's eyes light up brightly and he bows low in respect to your generosity.`n`n`3'I will now be able to feed my family for many days and that you've restored my hope in humanity!'`7`n`nYou are truly an amazingly `@Good Person`7.");
			$session['user']['gems']-=5;
		break;
		//The reward here is 25 gems.  It may seem like a lot, but since a player has a 1 in 5 chance of getting 25 gems by spending 5 gems, it evens out.
		case 5:
			output("As the last `%gem`7 hits the bottom, the beggar looks up at you with a brilliant smile. He removes his tattered clothing revealing`5 beautiful regal clothing`7.");
			output("You are standing before `&the King`7.`n`n  `b`Q'Your generosity is something I cherish greatly in my kingdom.");
			output("Your gift will be returned to you 5 fold.'`b`n`n`7With a snap of his fingers, the `^R`%oyal `^T`%reasurer`7 steps out from the trees and hands you a bag.");
			output("`n`n You open it to find `%25 gems`7!`n`n`@All the kingdom learns of your generosity.`7");
			addnews("%s `%has proven %s worth through a generous act in the `5L`6ost `5R`6uins`%!!",$session['user']['name'],translate_inline($session['user']['sex']?"her":"his"));
			$session['user']['gems']+=25;
		break;
	}
}
if ($op=="nocash") {
	output("`c`n`b`)T`&wo `)B`&eggars`c`b`n");
	output("`7You explain that you'd like to try to help, but since you are also low on funds, you don't have anything you can give him.");
	output("`n`nThe beggar nods at you, digs into his cup, and hands you `^1 gold`7.`n`nHe pats you on the back and tells you `2 'We have to stick together in these lean times'`7 and then wanders off looking for someone who actually has something to give.");
	$session['user']['gold']++;
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
}
if ($op=="nogift") {
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`c`n`b`6D`5eclining `6t`5o `6G`5ive`c`b`n");
	output("`7You look at the scraggly beggar and decide that you don't really want to give anything.`n`n");
	switch(e_rand(1,5)){
		case 1: case 2: case 3: case 4:
			output("You tap your ");
			if ($session['user']['gold']>0) output("`^gold sac`7 to prove that it's empty, but the jingle");
			else output("`%gem sac`7 to prove that it's empty, but the clinking sound");
			output("gives away the fact that you're holding back.`n`nThe beggar gives you an`$ evil`7 look and wanders off.");
		break;
		case 5:
			output("The beggar looks at you with sad puppy dog eyes, shakes your hand, and wanders away. You smile and walk off.`n`nA little while later you realize that he stole ");
			if ($session['user']['gold']>0){
				output("`^all your gold`7!`n`n");
				$session['user']['gold']=0;
			}elseif($session['user']['gems']>0){
				output("`%a gem`7!`n`n");
				$session['user']['gems']--;
			}else output("your heart away.  That cute little scamp!`n`n");
			output("Maybe next time you'll be a little more generous.");
		break;
	}
}
if ($op=="begsteal") {
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_begsteal.php");
	lostruins_begsteal();
}
if ($op=="darkness"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`!Darkness`b`c`n");
	output("With some trepidation, you move closer to the darkness...`n`n `!Suddenly, everything goes completely black.");
	output("You wait for your eyes to adjust but they never do. A deep voice speaks to you...`n`n`1'You are brave indeed for coming so close to death.");
	switch(e_rand(1,4)){
		case 1:
			output("For this, you will be able to fight longer to regain life.'`n`n`!You gain `$ 4 torments `! just in case you need them.`n`n");
			$session['user']['gravefights']+=4;
		break;
		case 2:
			output("For this, you have pleased `$ Ramius`1 and are rewarded with his`$ favor`1.'`n`n`!You gain `$ 25 favor`! just in case you need it.`n`n");
			$session['user']['deathpower']+=25;
		break;
		case 3:
			output("For this, you will fight longer to regain life and you are more pleasing in the eyes of`$ Ramius`1.'`n`n`!You gain `$ 4 torments`! and `$ 25 favor`! just in case you need them.");
			$session['user']['deathpower']+=25;
			$session['user']['gravefights']+=4;
		break;
		case 4:
			output("I think I would like to chat further with you.'`n`n`n`b`c`$ Ramius`! has called you to talk to him!`b`c");
			addnav("`\$Ramius' Graveyard","graveyard.php");
			$session['user']['alive'] = false;
			$session['user']['hitpoints'] = 0;
			$session['user']['deathpower']+=100;
			blocknav("runmodule.php?module=lostruins&op=explore");
			blocknav("village.php");
		break;
	}
}
if ($op=="schroyes"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_schroyes.php");
	lostruins_schroyes();
}
if ($op=="schrono"){
	output("`c`b`n`QS`^c`Qh`^r`Qo`^e`Qd`^i`Qn`^g`Qe`^r`Q'`^s `QC`^a`Qt`7`c`b`n");
	output("`7You pass up a lesson in quantum physics in order to further your chances at perfecting the art of`@ Herpetological `\$Eviceration`7.`n`n You spot a`@ lizard`7 on the ground and study it.`n`n");
	addnav("V?(V) Return to Village","village.php");
    if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
    switch(e_rand(1,2)){
		case 1:
			output("`%Eureka!`7`n`n You see a weakness in the lizard's natural scales. You apply this trick to your future fights against the `@G`2reen `@D`2ragon`7 and you`& gain 1 attack`7!");
			$session['user']['attack']++;
		break;
		case 2:
			output("You become entranced by the little`@ lizard`7 and spend `@the rest of your day`7 staring at it in a never ending staring contest.`n`n");
			switch(e_rand(1,2)){
				case 1:
					output("You `#win`7 the staring contest!`n`n The `@lizard`7 gives you `%a gem`7 and a`$ kiss`7.`n`nYou `&lose a charm point`7.");
					$session['user']['gems']++;
					$session['user']['charm']--;
					$session['user']['turns'] = 0;
				break;
				case 2:
					output("You`# lose`7 the staring contest!`n`n Fortunately for you, it really doesn't matter. In a bold move, you eat the `@lizard`7.`n`nYou are left with `@2 turns`7.");
				$session['user']['turns'] = 2;
				break;
			}
		break;
	}
}
if ($op=="kneel"){
	addnav("V?(V) Return to Village","village.php");
    if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_kneel.php");
	lostruins_kneel();
}
if($op=="penitent"){
	require_once("lib/titles.php");
	require_once("lib/names.php");
	addnav("V?(V) Return to Village","village.php");
    if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`5K`6neeling `5B`6efore`c`b`n");
	$newtitle = "Penitent";
	$newname = change_player_title($newtitle);
	$session['user']['title'] = $newtitle;
	$session['user']['name'] = $newname;
	output("`#'Take care, and may all your adventures be successful!'`n`n");
}
if($op=="defy"){
	addnav("V?(V) Return to Village","village.php");
    if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_defy.php");
	lostruins_defy();
}
if($op=="donothing"){
	addnav("V?(V) Return to Village","village.php");
    if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_donothing.php");
	lostruins_donothing();
}	
if($op=="wave"){
	addnav("V?(V) Return to Village","village.php");
    if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
    output("`n`c`b`5W`6ave`c`b`n");
    switch(e_rand(1,5)){
       case 1:
			$case7g=get_module_setting("case7g");
			output("`&`b'You please me with your pleasant disposition,'`b`7 says the voice, `b`&'and I want you to buy yourself something nice. Take care!'`b`7`n`nYou find you have `^%s more gold`7!!!",$case7g);
			$session['user']['gold']+=$case7g;
		break;
		case 2: case 3:
			output("`7With a happy little `@wave`7 to the sky, you wait for the response.`n`n  After about 20 minutes, you start to feel a little silly. The fog lifts spontaneously and you wander off.");
		break;
		case 4: case 5:
			output("`7You start `@waving`7 at the voice happily and oblivious to everything happening around you.`n`nYou don't notice the band of thieves that surround you demanding that you give up `^all your gold`7.`n`n");
			if ($session['user']['gold']<350){
				output("You sadly turn over every `^gold piece`7 you have and hang your head sadly.");
				$session['user']['gold']=0;
			}else{
				output("You trick them and only give them `^350 of your precious gold`7 and hide your stash.");
				$session['user']['gold']-=350;
			}
		break;
	}
}
if ($op=="number") {
	addnav("V?(V) Return to Village","village.php");
    if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`5P`6icking `5a N`6umber`c`b`n");
	$number=(e_rand(1,5));
	if ($number==1){
		output("`7The little man looks at you and smiles.`n`n`#'That's it!!!'`n`n`7It seems like you've made his day just a little better by playing his game with him.`n`n`#'You know, I've asked over 2 dozen people to play that game with me and nobody ever would.");
		output("And wouldn't you know it, you picked my number!!'`n`n`7The little man pulls out some dust and sprinkles it over your foot.");
		output("`n`n`#'Now you'll have a little more energy for the rest of the day!'`n`n`7You've gained `@3 extra turns`7!");
		$session['user']['turns']+=3;
	}else{
		output("`#'Oh, I'm so sorry! I wasn't thinking of that number. But thank you so much for playing with me!'`n`n`7The little man smiles and hands you `%a gem`7 and wanders off into the `5L`6ost `5R`6uins`7.");
		$session['user']['gems']++;
	}
}
if ($op=="map") {
	addnav("V?(V) Return to Village","village.php");
    if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_map.php");
	lostruins_map();
}
if ($op=="ship"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`#Bottled Water Sales`c`b`n");
	output("`7You go to the center of town to try to sell `&'Dr. Mit's Revitalizing Tonic'`7.`n`nA huge crowd gathers as you 'introduce' this wonderful potion:`n`n");
	output("`c`%'For the minimal outlay of `^10 gold`%, you can take home a bottle of liquid lothario, distilled Don Juan, catalytically carbonated Casanova.");
	output("Lock old Rover in the shed, 'cause man has a new best friend in `&Dr. Mit's`% revitalizing tonic! Step right up, folks, and witness the magnificent medicinal miracle of `&Dr. Mit's`% patented revitalizing tonic.");
	output("Put some ardor in your larder with our energizing, moisturizing, tantalizing, romanticizing, surprising, her prizing, revitalizing tonic!'`c`n`n");
	output("`7Everyone tries to buy a bottle, and before you know it you've sold 14 bottles! Life is great!`n`n Then someone actually drinks it.");
	output("`n`n`6'HEY! This is just bottled water from that dumb spring in the `5L`6ost `5R`6uins`6! Also, I think he ripped off his spiel from someone!'`n`n");
	output("`7At this point, two things run through your mind. The first is that you realize that someone was put in jail for doing this exact thing last week.");
	output("The second thing is that you were hoping nobody would notice that you stole that spiel from your old friend Abraham S.`n`n");
	output("The mob attacks you and brands your forehead with a sign that everyone will recognize you for who you are.");
	output("Luckily, they don't find your stash of gold, including the extra`^ 140 gold`7 you made from selling the water.");
	require_once("lib/titles.php");
	require_once("lib/names.php");
	$newtitle = "ConArtist";
	$newname = change_player_title($newtitle);
	$session['user']['title'] = $newtitle;
	$session['user']['name'] = $newname;
	$session['user']['gold']+=140;
	addnews("%s`^ was caught trying to sell `5L`6ost `5R`6uins `5W`6ater`^ as `&'Dr. Mit's Revitalizing Tonic'`^!  BUSTED!",$session['user']['name']);
}
if ($op=="slip"){
	require_once("modules/lostruins/lostruins_slip.php");
	lostruins_slip();
}
if ($op=="trip"){
	addnav("V?(V) Return to Village","village.php");
    if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_trip.php");
	lostruins_trip();
}
if ($op=="sip"){
	output("`n`c`b`#Drinking from the Spring`c`b`n");	
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	switch(e_rand(1,3)){
		case 1:
			output("`#Sweet and pure, the water is completely revitalizing.`n`nYou `@gain 5 extra turns`#!`n`n");
			$session['user']['turns']+=5;
		break;
		case 2:
			output("`#Not too bad, the water is pretty tasty.`n`nYou `@gain 2 extra turns`#!`n`n");
			$session['user']['turns']+=2;
		break;
		case 3:
			output("`#Not as tasty as you had hoped.");
			if ($session['user']['turns']>2){
				output("`n`nYou`@ lose 2 turns`#!`n`n`");
				$session['user']['turns']-=2;
			}elseif ($session['user']['turns']>0){
				output("`n`nYou`@ lose all your turns`#!`n`n`");
				$session['user']['turns']=0;
			}else output("You have a nasty taste in your mouth for the rest of the day but that's it.");
		break;
	}
}
if ($op=="dip"){
	require_once("modules/lostruins/lostruins_dip.php");
	lostruins_dip();
}
if ($op=="lemonade"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`^Lemonade Stand`c`b`n");
	output("`7You watch the villagers walk by; some of them stop by and purchase `^lemonade`7.`n`n After a little while, you decide to close up shop and count your take...`n`n");
	$gold=get_module_setting("case11g3");
	$profit = round(e_rand($gold/2,$gold));
	switch(e_rand(1,3)){
		case 1: case 2:
			output("`7You make `^%s gold`7!",$profit);
			$session['user']['gold']+=$profit;
		break;
		case 3:
			output("`7You count all your money, but before you can get away, a bully takes half of it!");
			output("`n`nOh well, you still made `^%s gold`7!",round($profit/2));
			$session['user']['gold']+=round($profit/2);
		break;
	}
	output("Congratulations!");
}
if ($op=="spin"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`\$S`^p`@i`#n`!n`%i`\$n`^g `@W`#h`!e`%e`\$l`c`b`n");
	output("`#'Alright... Here we go... round and round the spinning wheel goes...'`n`n`&`cclick click click click`n`n click click click`n`n click click`n`n click`c`n`nThe wheel slows down and lands on");
	switch(e_rand(1,9)){
		case 1: case 2: case 3:
			$session['user']['gold']-=200;
			output("`7a big fat zero.`n`n`#'Oh, I'm so sorry. I guess this is not your lucky day. Better luck next time.'");
		break;
		case 4: case 5: case 6:
			output("`^ 200 gold`7.`n`n`#'Well well well, I guess I'm just going to call you Even Steven. Take care!'");
		break;
		case 7: case 8:
			$session['user']['gold']+=300;
			output("`^ 500 gold`7!`n`n`#'Look at that! We have a Winner!!! Here's your gold!'");
		break;
		case 9:
			$session['user']['gold']+=800;
			output("`^ 1000 gold`7!`n`n`#'Looks like you hit the `\$J`^a`@c`#k`!p`%o`\$t`#! Congratulations!'");
		break;
	}
}
if ($op=="freegold"){
	$case17g=get_module_setting("case17g");
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`^Free Gold`c`b`n");
	output("`@'Thank you for being so understanding.");
	output("Here's your `^%s gold`@!'`n`n",$case17g);
	$session['user']['gold']+=$case17g;
}
if ($op=="freegems"){
	$case17ge=get_module_setting("case17ge");
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`%Free Gems`c`b`n");
	output("`@'Thank you for being so understanding.");
	output("Here's your `%%s %s`@!'`n`n",$case17ge,translate_inline($case17ge>1?"gems":"gem"));
	$session['user']['gems']+=$case17ge;
}
if ($op=="freebuff"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`#Free Buff`c`b`n");
	output("`@'Thank you for being so understanding.");
	output("Here's your `#Buff`@!'`n`n");
	apply_buff('genericbuff',array(
		"name"=>"A Generic Buff",
		"rounds"=>10,
		"wearoff"=>"Your Generic Buff Wears Off",
		"atkmod"=>1.1,
		"defmod"=>1.1,
		"roundmsg"=>"Your Generic Buff helps you",
	));
}
if ($op=="freecharm"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`&Free Charm`c`b`n");
	output("`@'Thank you for being so understanding.");
	output("Here's your `&2 Charm`@!'`n`n");
	$session['user']['charm']+=2;
}
if ($op=="freeturns"){
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	output("`n`c`b`@Free Turns`c`b`n");
	output("`@'Thank you for being so understanding.");
	output("Here's your `b2 free turns`b!'`n`n");
	$session['user']['turns']+=2;
}
if($op=="goldgift"){
	require_once("modules/lostruins/lostruins_goldgift.php");
	lostruins_goldgift();
}
if ($op=="readyes") {
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_readyes.php");
	lostruins_readyes();
}
if ($op=="readno") {
	addnav("V?(V) Return to Village","village.php");
	output("`n`c`b`&The Strange Inscription`c`b`n");	
	output("Since nothing ever happens to boring people that won't read strange inscriptions, nothing else is going to happen to you today.");
	output("`n`nYou `@lose all your turns for the day`&.");
	$session['user']['turns']=0;
}
if ($op=="pluck") {
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_pluck.php");
	lostruins_pluck();
}
if ($op=="tsoup") {
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_tsoup.php");
	lostruins_tsoup();
}
if ($op=="tfriend") {
	addnav("V?(V) Return to Village","village.php");
	if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	require_once("modules/lostruins/lostruins_tfriend.php");
	lostruins_tfriend();
}
if ($op=="attack") {
	$level = $session['user']['level']+2;
	if ($level>=15) $level=15;
	$badguy = array(
		"creaturename"=>"`7Stone `QGolem",
		"creaturelevel"=>$level,
		"creatureweapon"=>" `7Boulder-Shaped Fists",
		"creatureattack"=>round($session['user']['attack']+2),
		"creaturedefense"=>round($session['user']['defense']+2),
		"creaturehealth"=>round($session['user']['maxhitpoints']*1.2,0),
		"diddamage"=>0,
		"type"=>"stonegolem");
	$session['user']['badguy']=createstring($badguy);
	$op="fight";
}
if ($op=="fight"){ $battle=true; }
if ($battle){
	include("battle.php");
	if ($victory){
		$expbonus=$session['user']['dragonkills']*7;
		$expgain =($session['user']['level']*50+$expbonus);
		$session['user']['experience']+=$expgain;
		$session['user']['gold']+=350;
		output("`b`@You reduce the `7Golem`@ to rubble!`n");
		output("`@You gain `^350 gold`@!`n");
		output("`n`@`bYou've gained `#%s experience`@.`b`n`n",$expgain);
		addnav("V?(V) Return to Village","village.php");
		if ($usedexpts<$exploreturns && $uturns >0) addnav("Explore Some More","runmodule.php?module=lostruins&op=explore");
	}elseif($defeat){
		require_once("lib/taunt.php");
		$taunt = select_taunt_array();
		$exploss = round($session['user']['experience']*.1);
		output("`n`n`b`7This was a great monster, and there is no shame to being defeated by it.`b`n");
		output("`b`7However, you still lose all `^gold `7on hand.`b`n");
		output("`b`7You lose `#%s experience`7.`b`n`n",$exploss);
		output("`b`@You may begin your adventures tomorrow.`b`n");
		addnews("%s `@was crushed by the earthly `7Stone `QGolem`@ in the `5L`6ost `5R`6uins`@.`n%s",$session['user']['name'],$taunt);
		addnav("Daily news","news.php");
		$session['user']['experience']-=$exploss;
		$session['user']['alive'] = false;
		$session['user']['hitpoints'] = 0;
		$session['user']['gold']=0;
	}else{
		require_once("lib/fightnav.php");
		fightnav(true,false,"runmodule.php?module=lostruins");
    }
}
page_footer();
?>