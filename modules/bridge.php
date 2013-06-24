<?php 

function bridge_getmoduleinfo(){ 
    $info = array( 
        "name"=>"Bridge of Death", 
        "version" => "20070510", 
        "vertxtloc"=>"http://legendofsix.com/", 
        "description"=>"Monte Python and the Holy Grail spoof", 
        "author"=>"<a href='http://www.sixf00t4.com'>Sixf00t4</a>, Converted by David Brotman", 
        "category"=>"Forest Specials", 
        "download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1195", 
        "settings"=>array( 
            "Bridge Event Settings,title", 
            "xpperlv"=>"Xp per level gained for tossing the keeper,range,10, 200,1|75", 
            "hploss"=>"Percentage HP Lost on Blue Toss,range,0,100,1|50", 
            "ffloss"=>"Forest Fights lost on Assyria Toss, range, 0, 10,1|2", 
            "gems"=>"Gems found on correct answers,range,0,5,1|1", 
            ), 
        ); 
    return $info; 

    } 

function bridge_install(){ 
    module_addeventhook("forest", "return 100;"); 
    return true; 
} 
function bridge_uninstall(){ 
    return true; 
    } 
function bridge_dohook($hookname,$args){ 
    return args; 
    } 
function bridge_runevent($type){ 
    global $session; 

    $session['user']['specialinc'] = "module:bridge"; 
    $op = httpget('op'); 
    $from="forest.php?"; 
    $from = $from . ""; 
    page_header("Bridge of Death"); 
    if ($op == "" || $op == "search") { 
        output("`n`3`c`bBridge of Death`b`c `n `n"); 
        output(" `2You stumble through the forest, and have come upon the man from scene twenty-four!`n"); 
        output(" You found the `3Bridge of Death`2! `n"); 
        output(" `2The rope bridge is in terrible shape, but it is the only way to cross.`n"); 
        output(" The man says, \"`3Stop!`2\""); 
        output(" Who would cross the Bridge of Death must answer me these questions three, 'ere the other side he see.`2`n"); 
        addnav("Ask, I Am Not Afraid", $from."op=ask"); 
        addnav("RUN AWAY!", $from."op=leave"); 
    }else if($op=="ask"){ 
        $session['user']['specialinc'] = "module:bridge"; 
        output("WHAT... is your name?`n"); 
        addnav("Sir Lancelot", $from."op=lance"); 
        }else if ($op=="lance"){ 
        $session['user']['specialinc'] = "module:bridge"; 
        output("WHAT... is your quest?`n"); 
        addnav("Seek the Holy Grail",$from . "op=grail"); 
     }else if ($op=="grail"){ 
        $session['user']['specialinc'] = "module:bridge"; 
        switch(e_rand(1,10)){ 
            case 1: 
            case 2: 
            case 3: 
            case 4: 
            addnav("I Don't know that",$from . "op=know"); 
            output("WHAT ... is the capital of Assyria?`n"); 
            break; 
            case 5: 
            case 6: 
            case 7: 
            case 8: 
            addnav("Blue",$from . "op=blue"); 
            output("WHAT ... is your favorite color?`n"); 
            break; 
            case 9: 
            case 10: 
            addnav("African or European?",$from . "op=swallow"); 
            output("WHAT ... is the air-speed velocity of an unladen swallow?`n"); 
            break; 
            } 
    }elseif ($op=="leave"){ 
        $session['user']['specialinc']=""; 
        output("`#Scared, you head back to forest..."); 
    }else if ($op=="blue"){ 
		$hploss = get_module_setting("hploss"); 
        $session['user']['specialinc'] = "module:bridge"; 
        if (e_rand(0,1)==0){ 
            $loss = $session['user']['hitpoints']*$hploss/100; 
            output("You quickly change your mind, and in the middle of saying yellow, you are launched into the air screaming!`n"); 
            output("You lose %s hitpoints!",$loss); 
            $session['user']['hitpoints']-=$loss; 
            debug("lost $loss hitpoints from saying yellow"); 
            addnav("Leave",$from . "op=leave"); 
        }else{ 
			$gems = get_module_setting("gems"); 
            $session['user']['specialinc']=""; 
            output("Right. Off you go.`n"); 
            output("You gain one charm point!`n"); 
            $session['user']['charm']++; 
            addnav("Cross the bridge","forest.php"); 
            if (e_rand(0,1)==0){ 
                output("As you walk across the bridge, something sparkling falls from the pocket of a launched warrior`n"); 
                output("You find %s gem%s!",$gems,translate_inline($gems>1?"s":"")); 
                $session['user']['gems']+=$gems; 
                debug("gained $gems gems for knowing the color blue"); 
                } 
            } 
    }else if ($op=="know"){ 
	    $ffloss = get_module_setting("ffloss"); 
        output("Auuuuuuuugh!, you are launched into the air screaming!`n"); 
        output("As you wait to return back to earth, you lose %s %s!",$ffloss,$ffloss>1?"turns":"turn"); 
        $session['user']['turns']-=$ffloss; 
        $session['user']['specialinc']=""; 
        debug("lost $ffloss turns to assyria tower"); 
        addnav("RUN AWAY!","forest.php"); 
        } 
    else if ($op=="swallow"){ 
		$xpperlv = get_module_setting("xpperlv"); 
        output("Auuuuuuuugh!, `n"); 
        output("The Bridgekeeper is launched into the air screaming!`n"); 
        output("How do know so much about swallows?`n"); 
        $xp = $xpperlv + $session['user']['level']; 
        output("You gain %s experience!`n",$xp); 
        $session['user']['experience']+=$xp; 
        $session['user']['specialinc']=""; 
        debug("gained $xp experience from tossing the keeper"); 
        addnav("Cross the Bridge","forest.php"); 
        } 
    } 
function bridge_run(){ 
} 
?>