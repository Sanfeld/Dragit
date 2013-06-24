<?php
function oceanquest_fishingexpeditiona(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$op4 = httpget('op4');
	$allprefs=unserialize(get_module_pref('allprefs'));
	page_header("Fishing Expedition");
	output("`c`b`^Fishing Expedition`7`b`c");
	$misc= array ('leave','gauge','fish','gold','walkaway','damagepay','bodysearch');
	if (in_array($op2,$misc)){
		require_once("modules/oceanquest/oceanquest_fishmisc.php");
		oceanquest_fishmisc($op2);
	}
	if ($session['user']['hitpoints'] <= 0) redirect("shades.php");
	$upqtemp = get_module_pref('pqtemp');
	if ($op2 == "" || $op2=="gauge") {
		if ($op3=="payturn") $session['user']['turns']--;
		$allprefs=unserialize(get_module_pref('allprefs'));
		if ($allprefs['fishmap']==""||$allprefs['fishmap']==0){
			$randommaze=e_rand(1,4);
			$allprefs['fishmap']=$randommaze;
			$allprefs['wind1']=e_rand(1,30);
			$allprefs['wind2']=e_rand(1,30);
			$allprefs['wind3']=e_rand(1,30);
			$allprefs['wind4']=e_rand(1,30);
			$allprefs['depth1']=e_rand(50,125);
			$allprefs['depth2']=e_rand(50,125);
			$allprefs['depth3']=e_rand(50,125);
			$allprefs['depth4']=e_rand(50,125);
			$allprefs['temp1']=e_rand(60,90);
			$allprefs['temp2']=e_rand(60,90);
			$allprefs['temp3']=e_rand(60,90);
			$allprefs['temp4']=e_rand(60,90);
			set_module_pref('allprefs',serialize($allprefs));
		}
		$allprefs=unserialize(get_module_pref('allprefs'));
		addnav("Options");
		addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
		if ($allprefs['fishingtoday']<5){
			$ft=5-$allprefs['fishingtoday'];
			if ($op2==""){
				output("`n`7You settle in and discuss where you'd like to go fishing. Go ahead and try to catch some fish!");
				output("There are four locations you can go fishing at and each has a different wind speed, depth, and water temperature.`n");
			}
			output("`n`cYou have `^%s`7 fishing %s left.`c",$ft,translate_inline($ft>0?"turns":"turn"));
			addnav("Spot 1");
			addnav("`!1: Go Fishing","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=fish&op3=1");
			addnav("`!1: Check Gauges","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=gauge&op3=1");
			addnav("Spot 2");
			addnav("`@2. Go Fishing","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=fish&op3=2");
			addnav("`@2. Check Gauges","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=gauge&op3=2");
			addnav("Spot 3");
			addnav("`#3.Go Fishing","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=fish&op3=3");
			addnav("`#3. Check Gauges","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=gauge&op3=3");
			addnav("Spot 4");
			addnav("`\$4. Go Fishing","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=fish&op3=4");
			addnav("`\$4. Check Gauges","runmodule.php?module=oceanquest&op=fishingexpeditiona&op2=gauge&op3=4");
		}else{
			output("It's time to head back to the docks because you're out of fishing turns.");
		}
	}
}
?>