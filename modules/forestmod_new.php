<?php
// mod for quick fights in the wood
//note that this is for Version Dragonprime 1.1.1
// you *cannot* use it for lower version.

function forestmod_new_getmoduleinfo(){
	$info = array(
		"name"=>"Forst Modification inspired by XChrisX for 1.1.1 DP Version",
		"version"=>"1.0",
		"author"=>"`2Oliver Brendel",
		"category"=>"Forest",
		"download"=>"http://lotgd-downloads.com",

	);
	return $info;
}

function forestmod_new_install(){
	module_addhook("forest");
	return true;
}

function forestmod_new_uninstall(){
	return true;
}

function forestmod_new_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "forest":
		    //extracted from healer.php
		    $loglev = log($session['user']['level']);
			$cost = ($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10);
		    $cost = round($cost,0);
			$result=modulehook("healmultiply",array("alterpct"=>1.0));
            $cost*=$result['alterpct']+0.05;
            $cost=round($cost,0);
		    if (($session['user']['gold'] >= $cost && $session['user']['maxhitpoints']-$session['user']['hitpoints']>0) || $session['user']['turns']>0)
 			 {
			 	addnav("Actions");
		     	if ($session['user']['gold'] >= $cost && $session['user']['maxhitpoints']-$session['user']['hitpoints']>0)
			 	addnav(array("Complete Healing (%s gold)",$cost),"runmodule.php?module=forestmod_new&op=heal");
				if ($session['user']['turns']>0)
				{
					if ($session['user']['level']>1)
					addnav("Slumber (till the end)","forest.php?op=search&auto=full&type=slum");
					addnav("Seek out (till the end)","forest.php?op=search&auto=full");
					addnav("Thrillseeking (till the end)","forest.php?op=search&auto=full&type=thrill");
					//uncomment the next lines to let players seach suicidally till the end
					//if (getsetting("suicide", 0)) {
					//	if (getsetting("suicidedk", 10) <= $session['user']['dragonkills']) {
					//		//addnav("Suicide (till the end)","forest.php?op=search&auto=full&type=suicide");
					//	}
					//}
				}
			 }
			break;
	}
	return $args;
}

function forestmod_new_run() {
 	global $session;
 	$opt=httpget('op');
 	switch($opt) {
	    case "heal":	    //autohealing
	    //tynan is interfering
	    $loglev = log($session['user']['level']);
		$cost = ($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10);
	    $cost = round($cost,0);
	    $maxhit=$session['user']['maxhitpoints'] + round(get_module_pref("hitpoints","tynan"),0);
	    $session['user']['hitpoints'] =$maxhit;
	    $result=modulehook("healmultiply",array("alterpct"=>1.0));
	    $cost*=$result['alterpct']+0.05;
        $cost=round($cost,0);	    
	    $session['user']['gold'] -=$cost;
	    page_header("Marco, the flying healer");
	    output("`^`c`bMarco, the flying healer`b`c");
	    output("`n`nNow you're fully healed, my %s.",translate_inline($session['user']['sex']?"Heroine":"Hero"));
	    require_once("lib/forest.php");
		forest(true);
	    break;
	}
	output_notl("`0");
	page_footer();
}

?>