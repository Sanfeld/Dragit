<?php
function oceanquest_sailinga(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$op4 = httpget('op4');
	$locale = httpget('loc');
	$allprefs=unserialize(get_module_pref('allprefs'));
	page_header("The Luckstar");
	output("`c`b`^`iThe Luckstar`i`7`b`c`n");
	$misc= array ('captain','askfishing','askdinner','askexplore','gofishing','dinner2','goexplore','locations');
	if (in_array($op2,$misc)){
		require_once("modules/oceanquest/oceanquest_sailmisc.php");
		oceanquest_sailmisc($op2);
	}
	if ($session['user']['hitpoints'] <= 0) redirect("shades.php");
	if ($op2 == "") {
		if ($op3=="payturn") {
			$session['user']['turns']--;
			$allprefs['pilinoria']=0;
			$allprefs['island']=0;
			set_module_pref('allprefs',serialize($allprefs));
		}
		if ($allprefs['shore']==1) output("The captain informs you that the Kingdom Dock is the only port that will be open today and you should return there.");
		else output("`7The `^`iLuckstar`i`7 sails the seas under your direction. What would you like to do?");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
		addnav("Explore the Ship","runmodule.php?module=oceanquest&op=sailinga&op2=goexplore");
		if ($allprefs['pole']==1 && $allprefs['bait']==1 && $allprefs['sailfish']==0) addnav("Go Fishing","runmodule.php?module=oceanquest&op=sailinga&op2=gofishing");
		if ($allprefs['captaintalk']==0 && ($allprefs['sailfish']==0 || $allprefs['captaindinner']==0 || $allprefs['okexplore']==0)){
			addnav("C?Speak with the Captain","runmodule.php?module=oceanquest&op=sailinga&op2=captain");
		}
		if ($allprefs['captaindinner']==1 && $allprefs['shore']==0) addnav("Explore the Island","runmodule.php?module=oceanquest&op=island&op2=landing");
		if ($allprefs['shore']==0) addnav("Disembark to Pilinoria","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
	}
}
?>