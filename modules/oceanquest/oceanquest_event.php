<?php
global $session;
$op=httpget('op');
switch($type){
	case forest:
		$allprefs=unserialize(get_module_pref('allprefs'));
		if ($op==""){
			$session['user']['specialinc'] = "module:oceanquest";
			$allprefs=unserialize(get_module_pref('allprefs'));
			output("`7You search for something to kill but find yourself standing in a pile of very soft soil.");
			if ($allprefs['piece1']==""||$allprefs['piece1']==0){
				$rand=e_rand(1,5);
				if ((($allprefs['unlockdecree']==""||$allprefs['unlockdecree']==0) && $rand==1) || ($allprefs['unlockdecree']==1)){
					output("`n`nYou look down and see a scrap of paper.");
					if ($allprefs['unlockdecree']==1) {
						output("You realize it's part of the `^Royal Decree of Passage`7 on the `^`iLuck Star`i`7.");
						$total=$allprefs['piece2']+$allprefs['piece3']+$allprefs['piece4'] + 1;
						if ($total>=4) output("You've found all four pieces of the Decree. Congratulations! You should head back to the docks and see if you can get passage on the `^`iLuck Star`i`7.");
						elseif ($total>1) output("You now have %s pieces of the Decree.",$total);
						else output("You've found your first piece of the Decree!");
					}else output("Not knowing what to do with it, you tuck it away.");
					$allprefs['piece1']=1;
					set_module_pref('allprefs',serialize($allprefs));
				}
			}
			output("`n`nSuddenly, you feel something wiggling under your feet. Oh my! It's Night crawlers! If you want to spend a turn digging some up you can.");
			addnav("Dig 'em up","forest.php?op=digupworms");
			addnav("Leave","forest.php?op=oceanquestleave");
		}
		if ($op=="digupworms"){
			$session['user']['turns']--;
			output("`7You take your `^%s`7 and get ready to dig up some worms. `n`nYou find a good handful of Night Crawlers ",$session['user']['weapon']);
			if ($allprefs['bait']==""||$allprefs['bait']==0){
				output("and put them in your pocket.  If you don't do something with them, they'll probably die by the end of the day.");
				$allprefs['bait']=1;
				set_module_pref('allprefs',serialize($allprefs));
			}else{
				output("but you don't have anywhere to put them. That was poor planning!");
			}
			output("`n`nYou get up to leave when something sparkly catches your eye.");
			switch(e_rand(1,14)){
				case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8:
					output("You investigate to find that it's just a piece of useless tin foil.  Oh well!");
				break;
				case 9: case 10: case 11:
					output("You go over and find a bag of gold! You count out `^100 gold pieces`7!");
					$session['user']['gold']+=100;
				break;
				case 12: case 13:
					output("You go over and find a `%gem`7!");
					$session['user']['gems']++;
				break;
				case 14:
					output("You go over and find someone's money pouch! There's `^100 gold`7 and a `%gem`7 inside!");
					$session['user']['gems']++;
					$session['user']['gold']+=100;
				break;
			}
			addnav("Return to the Forest", "forest.php?php");
			$session['user']['specialinc']="";
		}
		if ($op=="oceanquestleave"){
			output("`2`nYou head back into the forest.");
			addnav("Return to the Forest", "forest.php?php");
			$session['user']['specialinc']="";
		}
	break;
}
?>