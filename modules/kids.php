<?php
function kids_getmoduleinfo(){
        $info = array(
		"name"=>"12 year olds beat up players",
		"version" => "20070420",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=712",
		"vertxtloc"=>"http://legendofsix.com/",
		"author"=>"Sixf00t4 <br> Converted by Lurch",
		"category"=>"Forest Specials",
		"settings"=>array(
			"Kids Settings, title",
			"dks"=>"How many DKs until players get beat up?,int|6",
			"minm"=>"How many maxhitpoints are lost are lost at min?,int|1",
			"maxm"=>"How many maxhitpoints are lost are lost at max?,int|5",
			"hit"=>"Subtract hitpoints if they don't have enough max hitpoints?,bool|1",
			"minh"=>"How many hitpoints are lost are lost at min?,int|10",
			"maxh"=>"How many hitpoints are lost are lost at max?,int|25",
		),
	);

 return $info;
}

function kids_install(){
        module_addeventhook("forest", "return 100;");
        return true;
}

function kids_uninstall(){
        return true;
}

function kids_dohook($hookname,$args){
        return $args;
}

function kids_runevent($type){
	global $session;
    $from = "forest.php?";

	if($session['user']['dragonkills']<get_module_setting("dks")){
		output("`^As you walk through the forest, you find a familiar looking Warrior.  He looks like he was roughed up a bit.`n");
		output("What a pity for him.  You wave and carry on your way`0");
	}else{
		$ouch=e_rand(get_module_setting("minm"),get_module_setting("maxm"));
		$permdeath=10*$session['user']['level'];
		if($permdeath > ($session['user']['maxhitpoints']-$ouch)){
			if(get_module_setting("hit")){	
				$ouch=e_rand(get_module_setting("minh"),get_module_setting("maxh"));
				if(($session['user']['hitpoints']-$ouch)>0){
					output("As you stroll down the forest path with your nose high in the air, proud of your many dragon slays, you get mugged by a bunch of 12 year olds armed with bats!  you lost %s hitpoints!`0",$ouch);
					$session['user']['hitpoints']-=$ouch;			
				}else{
					output("As you stroll down the forest path with your nose high in the air, proud of your many dragon slays, you were killed by a bunch of 12 year olds armed with bats!`0");
					$session['user']['hitpoints']=0;
					$session['user']['alive']=0;
					blocknav("forest.php");
					villagenav();
				}			
			}else{
				output("`^As you walk through the forest, you find a familiar looking Warrior.  He looks like he was roughed up a bit.`n");
				output("What a pity for him.  You wave and carry on your way`0");
			}
		}else{
			output("As you stroll down the forest path with your nose high in the air, proud of your many dragon slays, you get mugged by a bunch of 12 year olds armed with bats!  you lost %s maxhitpoints!`0",$ouch);
			$session['user']['maxhitpoints']-=$ouch;
		}
	}
}
?>