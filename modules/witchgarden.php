<?php
// ver 1.1 added extra ingredient drops
function witchgarden_getmoduleinfo(){
	$info = array(
		"name" => "Witches Garden",
		"author" => "`b`&Ka`6laza`&ar`b",
		"version" => "1.1",
		"download" => "http:dragonprime.net/index.php?module=Downloads;catd=11",
		"category" => "Forest Specials",
		"description" => "Special to pick up ingredients for Magical Arena",
		"requires"=>array(
            "arena" => "1.3|`b`&Ka`6laza`&ar`b",
            ),
		"prefs"=>array(
		"Witch Garden Preferences,title",
		"user_showstats"=>"Would you like to see Ingredients?,bool|1",
		"monkhood"=>"how many of monkhood?,int|",
		"venom"=>"how many of basilisk venom?,int|",
		"hemlock"=>"how many of hemlock?,int|",
		"mandrake"=>"how many of mandrake?,int|",
		"scale"=>"how many of obsidian dragon scale?,int|",
		),
		);
		return $info;
}
function witchgarden_install(){
	module_addeventhook("forest", "return 100;");
	module_addhook("charstats");
	return true;
}
function witchgarden_uninstall(){
	return true;
}
function witchgarden_dohook($hookname,$args){
	global $session;
	$p1 = get_module_pref("monkhood");
	$p2 = get_module_pref("venom");
	$p3 = get_module_pref("hemlock");
	$p4 = get_module_pref("mandrake");
	$p5 = get_module_pref("scale");
	$op=httpget('op');
	switch ($hookname){
		case "charstats":
		if(get_module_pref("user_showstats")){
			if ($p1<>0){
				$title = "Ingredients";
                $name = "`^Monkhood:  ";
                $amt = $p1;
                setcharstat ($title,$name,$amt);
            }
            if ($p2<>0){
	            $title = "Ingredients";
                $name = "`#Basilisk Venom:  ";
                setcharstat ($title,$name,$p2);
            }
            if ($p3<>0){
	            $title = "Ingredients";
                $name = "`4Hemlock:  ";
                setcharstat ($title,$name,$p3);
            }
            if ($p4<>0){
	            $title = "Ingredients";
                $name = "`@Mandrake:  ";
               setcharstat ($title,$name,$p4);
            }
            if ($p5<>0){
	            $title = "Ingredients";
                $name = "`)Obsidian Dragon Scale:  ";
                setcharstat ($title,$name,$p5);
            }
        }
        break;
	}
	return $args;
}
				
function witchgarden_runevent(){
	global $session;
	page_header("Witches Garden");
	$op=httpget('op');
	$session['user']['specialinc'] = "module:witchgarden";
	if ($op=="" || $op=="search"){
		output("`4Searching the forest you come across a secluded clearing.  There is a small cottage surrounded by a very high wall.  You cannot see any way to get inside the wall.");
		output_notl("`n`n");
		output("You know it may take some time to look for a gate, but climbing the wall could be hazardous");
		addnav("What do you Do?");
		addnav("Climb the wall","runmodule.php?module=witchgarden&op=climb");
		addnav("Search for a Gate","runmodule.php?module=witchgarden&op=gate");
		addnav("Return to Forest","forest.php");
		$session['user']['specialinc']="";
	}
	page_footer;
}
function witchgarden_run(){
	global $session;
	page_header("Witches Garden");
	$id = $session['user']['acctid'];
	$i1 = "monkhood";
	$i2 = "venom";
	$i3 = "hemlock";
	$i4 = "mandrake";
	$i5 = "obsidian dragon scale";
	$p1 = get_module_pref("monkhood");
	$p2 = get_module_pref("venom");
	$p3 = get_module_pref("hemlock");
	$p4 = get_module_pref("mandrake");
	$p5 = get_module_pref("scale");
	$op=httpget('op');
	if ($op=="gate"){
		output("`4You slowly start to walk around the wall ");
		switch (e_rand(1,3)){
			case 1:
			output("after a few minutes, you come across a small break in the wall, good fortune is yours today.  What will you do");
			$fortune=get_module_pref("fortune","clanforge",$id)+1;
			set_module_pref("fortune",$fortune,"clanforge",$id);
			addnav("Slip Through","runmodule.php?module=witchgarden&op=slip");
			addnav("Keep Searching","runmodule.php?module=witchgarden&op=gate1");
			break;
			case 2:
			output("you eventually find a ivy enshrouded entrance to the garden you can now see beyond");
			addnav("Push Gate Open","runmodule.php?module=witchgarden&op=push");
			break;
			case 3:
			output("After walking around in circles for a while you realise that good fortune is not yours today, when finally you stumble upon a broken down piece of wall");
			$fortune=get_module_pref("fortune","clanforge",$id)-1;
			set_module_pref("fortune",$fortune,"clanforge",$id);
			addnav("Climb over","runmodule.php?module=witchgarden&op=climbover");
			addnav("Keep Searching","runmodule.php?module=witchgarden&op=gate1");
			break;
		}
		addnav("Leave","forest.php");
	}
	if ($op=="slip"){
		output("`4You slip through the gap in the wall, looking around you, you find that you're standing on the outskirts of a large garden.  In the centre of the garden you can see the cottage more clearly, and you notice some blue smoke coming from the chimney");
		output_notl("`n`n");
		output("It must be a witches cottage.  Oh no, what are you going to do?");
		addnav("Steal some herbs and Run","runmodule.php?module=witchgarden&op=steal");
		addnav("Knock and Ask","runmodule.php?module=witchgarden&op=ask");
	}
	if ($op=="climbover"){
		output("`4You climb over the gap in the wall, looking around you, you find that you're standing on the outskirts of a large garden.  In the centre of the garden you can see the cottage more clearly, and you notice some blue smoke coming from the chimney");
		output_notl("`n`n");
		output("It must be a witches cottage.  Oh no, what are you going to do?");
		addnav("Steal some herbs and Run","runmodule.php?module=witchgarden&op=steal");
		addnav("Knock and Ask","runmodule.php?module=witchgarden&op=ask");
	}
	if ($op=="gate1"){
		output("`4After searching for quite some time, you trip over and fall in a patch of poison ivy!! Not a good day! Even though its taken you a while, you know find yourself face to face (as in your head hit the gate on the way down) with a large wrought iron gate");
		$fortune=get_module_pref("fortune","clanforge",$id)-1;
		set_module_pref("fortune",$fortune,"clanforge",$id);
		addnav("Push Gate Open","runmodule.php?module=witchgarden&op=push");
	}
	if ($op=="push"){
		output("`4 Pushing the gate open, you find yourself on the outskirts of a large garden.  In the centre of the garden you can see the cottage more clearly, and you notice some blue smoke coming from the chimney");
		output_notl("`n`n");
		output("It must be a witches cottage.  Oh no, what are you going to do?");
		addnav("Steal some herbs and Run","runmodule.php?module=witchgarden&op=steal");
		addnav("Knock and Ask","runmodule.php?module=witchgarden&op=ask");
	}
	if ($op=="climb"){
		output("`4You scramble up the wall ");
		switch (e_rand(1,2)){
			case 1:
			output("scratching your hands and legs a little, finally you manage to scramble to the top and drop down on the other side.  Looking around you, you find that you're standing on the outskirts of a large garden.  In the centre of the garden you can see the cottage more clearly, and you notice some blue smoke coming from the chimney");
			output_notl("`n`n");
			output("It must be a witches cottage.  Oh no, what are you going to do?");
			addnav("Steal some herbs and Run","runmodule.php?module=witchgarden&op=steal");
			addnav("Knock and Ask","runmodule.php?module=witchgarden&op=ask");
			break;
			case 2:
			output("just as you near the top, you slide back down to the bottom");
			addnav("Try again","runmodule.php?module=witchgarden&op=climb");
			addnav("Look for a Gate","runmodule.php?module=witchgarden&op=gate1");
			addnav("Give Up","forest.php");
			break;
		}
	}
	if ($op=="steal"){
		output("`4Looking furtively around, you reach out and grab some herbs you've managed to grab ");
		switch (e_rand(1,5)){
			case 1:
			output("%s and %s ",$i1, $i4);
			$g1 = $p1+1;
			$g2 = $p4+1;
			set_module_pref($i1,$g1);
			set_module_pref($i4,$g2);
			break;
			case 2:
			output("%s and %s ",$i3,$i4);
			$g1 = $p3+1;
			$g2 = $p4+1;
			set_module_pref($i3,$g1);
			set_module_pref($i4,$g2);
			break;
			case 3:
			output("%s and %s ",$i4, $i1);
			$g1 = $p1+1;
			$g2 = $p4+1;
			set_module_pref($i1,$g1);
			set_module_pref($i4,$g2);
			break;
			case 4:
			output("%s and %s ",$i4, $i3);
			$g1 = $p3+1;
			$g2 = $p4+1;
			set_module_pref($i3,$g1);
			set_module_pref($i4,$g2);
			break;
			case 5:
			output("%s and %s ",$i1, $i3);
			$g1 = $p1+1;
			$g2 = $p3+1;
			set_module_pref($i1,$g1);
			set_module_pref($i3,$g2);
			break;
		}
		output("quickly you turn to run");
		switch (e_rand(1,5)){
			case 1:
			output(" only to find the witch standing right behind you.  She curses you for stealing from her.  Not your day is it!");
			$fortune=get_module_pref("fortune","clanforge",$id)-1;
			set_module_pref("fortune",$fortune,"clanforge",$id);
			break;
			case 2:
			case 3:
			output(" quickly clambering over the nearest wall, you look behind you before dropping to the other side, to see the Witch coming out of her cottage.  Phew that was close");
			$fortune=get_module_pref("fortune","clanforge",$id)+1;
			set_module_pref("fortune",$fortune,"clanforge",$id);
			break;
			case 4:
			output(" quickly you clamber over the nearest wall, just as you're about to climb down you feel a large rock connect with the back of your head.  Waking up you find that your body has been searched and you've lost all your herbs");
			set_module_pref("monkhood",0);
			set_module_pref("venom",0);
			set_module_pref("hemlock",0);
			set_module_pref("mandrake",0);
			set_module_pref("scale",0);
			if ($session['user']['turns']<5){
				$session['user']['turns']=0;
			}elseif ($session['user']['turns']>-5){
				$session['user']['turns']-=5;
			}
			break;
			case 5:
			output(" you make a clean getaway");
			break;
		}
		addnav("Return to Forest","forest.php");
	}
	if ($op=="ask"){
		output("Walking up to the door of the cottage, you knock.  When the door creaks open, you ask the Witch very politely if you can have some herbs from her garden ");
		output_notl("`n`n");
		output("She gestures to you to take some herbs, telling you to only take 2.  You carefully pick your way through the garden and collect ");
		switch (e_rand(1,5)){
			case 1:	
			case 1:
			output("%s and %s ",$i1, $i4);
			$g1 = $p1+1;
			$g2 = $p4+1;
			set_module_pref($i1,$g1);
			set_module_pref($i4,$g2);
			break;
			case 2:
			output("%s and %s ",$i3,$i4);
			$g1 = $p3+1;
			$g2 = $p4+1;
			set_module_pref($i3,$g1);
			set_module_pref($i4,$g2);
			break;
			case 3:
			output("%s and %s ",$i4, $i1);
			$g1 = $p1+1;
			$g2 = $p4+1;
			set_module_pref($i1,$g1);
			set_module_pref($i4,$g2);
			break;
			case 4:
			output("%s and %s ",$i4, $i3);
			$g1 = $p3+1;
			$g2 = $p4+1;
			set_module_pref($i3,$g1);
			set_module_pref($i4,$g2);
			break;
			case 5:
			output("%s and %s ",$i1, $i3);
			$g1 = $p1+1;
			$g2 = $p3+1;
			set_module_pref($i1,$g1);
			set_module_pref($i3,$g2);
			break;
		}
		output_notl("`n`n");
		output("You thank the witch kindly and turn to leave ");
		switch (e_rand(1,4)){
			case 1:
			case 2:
			case 3:
			break;
			case 4:
			output("the witch stops you and as a reward for your thoughtfulness gives you a %s or two",$i5);
			$hm=e_rand(1,5);
			$g3=$p5+$hm;
			set_module_pref("scale",$g3);
			break;
		}
		output("walking back to the entrance, you walk thoughtfully out of the gate, wondering what you could do with these new herbs");
		$v = (e_rand(1,15));
			if ($v==1){
				output_notl("`n`n");
				output("You find a small flask that someone has dropped.  It appears to be some Basilisk Venom");
				$g1=$p2+1;
				set_module_pref($i2,$g1);
			}
		addnav("Return to forest","forest.php");
	}
	$session['user']['specialinc']="";
	page_footer();
}

?>