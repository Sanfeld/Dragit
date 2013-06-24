<?php
function oceanquest_thronea(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$op4 = httpget('op4');
	$locale = httpget('loc');
	$allprefs=unserialize(get_module_pref('allprefs'));
	page_header("The Throne Room");
	output("`c`b`%`iThe Throne Room`i`7`b`c");
	$misc= array ('king','guard','envoy');
	if (in_array($op2,$misc)){
		require_once("modules/oceanquest/oceanquest_thronemisc.php");
		oceanquest_thronemisc($op2);
	}
	if ($session['user']['hitpoints'] <= 0) redirect("shades.php");
	if ($op2 == "") {
		output("`nYou stand in amazing marble hall leading to a grand and ornate throne.  At the throne sits the king holding audience.");
		addnav("Options");
		addnav("Leave the Throne Room","runmodule.php?module=oceanquest&op=pilinoria&op2=castle");
		addnav("Talk to Guard 1","runmodule.php?module=oceanquest&op=thronea&op2=guard&op3=1");
		addnav("Talk to Guard 2","runmodule.php?module=oceanquest&op=thronea&op2=guard&op3=2");
		addnav("Talk to Guard 3","runmodule.php?module=oceanquest&op=thronea&op2=guard&op3=3");
		addnav("Talk to Guard 4","runmodule.php?module=oceanquest&op=thronea&op2=guard&op3=4");
		addnav("Talk to Guard 5","runmodule.php?module=oceanquest&op=thronea&op2=guard&op3=5");
		addnav("Talk to Guard 6","runmodule.php?module=oceanquest&op=thronea&op2=guard&op3=6");
		addnav("Talk to Guard 7","runmodule.php?module=oceanquest&op=thronea&op2=guard&op3=7");
		addnav("Talk to Guard 8","runmodule.php?module=oceanquest&op=thronea&op2=guard&op3=8");
		addnav("Talk to the `5King","runmodule.php?module=oceanquest&op=thronea&op2=king");
	}
}
?>