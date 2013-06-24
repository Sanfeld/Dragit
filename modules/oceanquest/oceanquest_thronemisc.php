<?php
function oceanquest_thronemisc($op2){
	global $session;
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$temp=get_module_pref("pqtemp");
	page_header("Throne Room");
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($op2=='guard'){
		if ($allprefs['freed']>""|| $allprefs['freed']>0){
			output("`nThe guard gives a nod of thanks and respect.");
		}else{
			if ($op3<>6){
				output("`nYou try to speak to the guard but he does not respond.  You see a bit of fear in his eyes more than reverence or respect.");
			}else{
				output("`nYou try to speak to the guard and he looks at you with fear.");
				output("`n`n`4'Please.  The king is not himself. He won't listen to you. Please help!'`7 the guard whispers.");
				output("`n`nThe guard across the aisle whispers `3'Shut up you fool or the king will hear you!");
				output("`n`n`7The guard on the right disregards what the other said and continues.");
				output("`4'You have to break the spell.  Go to the village in the south and talk to the mystic there. Please hurry!'");
				output("`n`n`7After this, he doesn't speak anymore.");
			}
		}
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Continue","runmodule.php?module=oceanquest&op=throne&loc=".$temp);
		else addnav("Continue","runmodule.php?module=oceanquest&op=thronea");
	}
	if ($op2=='envoy'){
		output("The king rises and takes his sword. He gently places it on your left shoulder then on your right.");
		output("`n`n`&'I hereby proclaim that %s be henceforth known as",$session['user']['name']);
		require_once("lib/titles.php");
		require_once("lib/names.php");
		$newtitle = "`b`&Envoy`^`b";
		$newname = change_player_title($newtitle);
		$session['user']['title'] = $newtitle;
		$session['user']['name'] = $newname;
		output("%s`&!'",$session['user']['name']);
		output("`n`n`7With this royal pronouncement by the king you bow low and graciously.  Congratulations!");
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Take Your Leave","runmodule.php?module=oceanquest&op=throne&loc=".$temp);
		else addnav("Take Your Leave","runmodule.php?module=oceanquest&op=thronea");
	}
	if ($op2=='king'){
		output("You approach the king and bow low.");
		$tradeperdk=get_module_setting("tradeperdk");
		if ($allprefs['sorcerer']==1){
			output("`&'Thank you for your service to the country.  Feel free to trade at the Pier,' proclaims the king.");
		}elseif ($allprefs['sorcerer']==2){
			set_module_pref("pqtemp",23);
			$temp=get_module_pref("pqtemp");
			$allprefs['sorcerer']=1;
			set_module_pref('allprefs',serialize($allprefs));
			output("`n`nYou are greeted with a pleasant fanfare. The king rises to thank you.");
			output("`n`n`&'You have freed me from a deadly spell of control, rid the kingdom of a crushing evil, and saved Pilinoria.  Thank you,'`7 says the king.");
			output("`&'As a reward, I am going to make you a trade envoy and offer you the title of `bEnvoy`b. In addition, you will be able to come to Pilinoria for trade `^%s`& %s every time you kill the Dragon in your kingdom and you may trade once per day.'",$tradeperdk,translate_inline($tradeperdk>1?"times":"time"));
			output("`n`n'In addition,due to the difficult nature of trade you cannot trade until you've reached level `^%s`&. Trading occurs at the pier. There are several items you can choose from to trade.'",get_module_setting("tradelevel"));
			if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Accept Envoy Title","runmodule.php?module=oceanquest&op=throne&op2=envoy");
			else addnav("Accept Envoy Title","runmodule.php?module=oceanquest&op=thronea&op2=envoy");
			addnews("%s `^has become a special Envoy to a kingdom across the sea.",$session['user']['name']);
		}elseif ($allprefs['freed']==1){
			output("`n`n`&'Err, what are you doing back here? I thought you were going to kill that evil sorcerer in the south.  Remember?'");
			output("`n`n`7You remember your mission and mutter some formalities and apologies. Time to head south!");
		}elseif ($allprefs['freed']==2){
			output("`n`n`&'Arise! You have saved me from an evil spell.  I thank you for that,'`7 says the king.");
			output("`n`n`&'However, I cannot offer you a trade agreement because our land is still not safe.  The evil sorcerer that cast this spell on me is still free in our southern territories.");
			output("You must defeat him.  If you can accomplish this, I will grant free trade between our kingdoms and you will be greatly honored.'");
			output("`n`n`7You ponder the implications of these words.  Is it really worth fighting a sorcerer just for a trade agreement?");
			output("Then again, it seems like an excellent adventure.");
			output("`n`n`#'I accept the challenge!'`7 you hear yourself say.  The courtroom gives a bow of respect and reverence.");
			output("`n`n`&'Go forth and defeat the evil and free our land!' exclaims the king.");
			$allprefs['freed']=1;
			set_module_pref('allprefs',serialize($allprefs));
		}else{
			output("He barely notices you and just grunts.");
			output("`n`nYou try to describe your kingdom and how both nations could prosper with free trade but he isn't listening.");
			output("Frustrated, you realize there's no point in talking any more and you leave.");
		}
		if (get_module_setting("interface")==0 && get_module_pref("user_interface")==0) addnav("Leave the King","runmodule.php?module=oceanquest&op=throne&loc=".$temp);
		else addnav("Leave the King","runmodule.php?module=oceanquest&op=thronea");
	}
}
?>