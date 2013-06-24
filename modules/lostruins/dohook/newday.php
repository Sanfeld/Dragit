<?php
	//resets on newday if set for that
	$allprefs=unserialize(get_module_pref('allprefs'));
	if (!get_module_setting("runonce")) $allprefs['usedexpts']=0;
	if ($allprefs['sexcount']>0){
		$allprefs['sexcount']=$allprefs['sexcount']-1;
		if ($allprefs['sexcount']==0){
			output("`nYou feel yourself turning back into your original sex.`n");
			if ($session['user']['sex'] == 0) $session['user']['sex'] = 1;
			else $session['user']['sex'] = 0;
			debuglog("had their sex changed back to normal when the Ancient Ruins spell ended.");
		}else{
			output("`n`@Your sex will revert back to normal in`& %s %s`@.`n",$allprefs['sexcount'],translate_inline($allprefs['sexcount']>1?"days":"day"));
		}
	}
	set_module_pref('allprefs',serialize($allprefs));
?>