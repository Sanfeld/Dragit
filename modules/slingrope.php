<?php
//addnews ready
// mail ready
// translator ready
function slingrope_getmoduleinfo(){
	$info = array(
		"name"=>"Sling Rope",
		"version"=>"0.1",
		"author"=>"Idea: Scott Langenfeld<br>Implementation: Sascha Kersken",
		"category"=>"Forest Specials",
		"download"=>"core_module",
		"settings"=>array(
			"Sling Rope Event Settings,title",
			"mingoldlost"=>"Minimum percentage of gold lost,range,0,30,5|10",
			"maxgoldlost"=>"Maximum percentage of gold lost,range,40,90,5|50",
                        "minhplost"=>"Minimum percentage of hit points lost,range,5,20,1|5",
                        "maxhplost"=>"Maximum percentage of hit points lost,range,30,80,1|40",
                        "chancetodie"=>"Chance of dying,range,1,20,1|10",
                        "mingoldfound"=>"Minimum percentage of lost gold found again,range,10,50,5|50",
                        "maxgoldfound"=>"Maximum percentage of lost gold found again,range,60,90,5|90"
		)
	);
	return $info;
}

function slingrope_install(){
	module_addeventhook("forest", "return 100;");
	return true;
}

function slingrope_uninstall(){
	return true;
}

function slingrope_dohook($hookname,$args){
	return $args;
}

function slingrope_runevent($type)
{
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:slingrope";

	$op = httpget('op');
        if ($op == "leave") {
                output("`#You walk away from the sling rope as fast as you can.");
	} elseif ($op=="" || $op=="search"){
                $goldlost = 0;
                if ($session['user']['gold'] > 0) {
                  $goldlost = (int)($session['user']['gold'] / 100 * rand(get_module_setting('mingoldlost'), get_module_setting('maxgoldlost')));
                }
                echo '<style type="text/css">
@-webkit-keyframes roll {
from { -webkit-transform: rotate(0deg) }
to   { -webkit-transform: rotate(180deg) }

}

@-moz-keyframes roll {
from { -moz-transform: rotate(0deg) }
to   { -moz-transform: rotate(180deg) }

}

@keyframes roll {
from { transform: rotate(0deg) }
to   { transform: rotate(180deg) }

}

body {
-moz-animation-name: roll;
-moz-animation-duration: 4s;
-moz-animation-iteration-count: 1;
-webkit-animation-name: roll;
-webkit-animation-duration: 4s;
-webkit-animation-iteration-count: 1;

-webkit-transform: rotate(180deg);
-moz-transform: rotate(180deg);
transform: rotate(180deg);

}
</style>';
		output("`#You walk the forest paths, whistling your favorite battle tune, when suddenly your world turns upside down!`n");
		output("Your foot got caught in a sling rope, and you are hanging down from a tree.`n");
                if ($goldlost > 0) {
                        $session['user']['gold'] -= $goldlost;
                        output("`^$goldlost`# pieces of gold fall out of your pocket.");
                }
                addnav("Cut the rope","forest.php?op=cutrope&goldlost=$goldlost");
	} elseif ($op == "cutrope") {
          echo '<style type="text/css">
@-webkit-keyframes roll {
from { -webkit-transform: rotate(180deg) }
to   { -webkit-transform: rotate(360deg) }

}

@-moz-keyframes roll {
from { -moz-transform: rotate(180deg) }
to   { -moz-transform: rotate(360deg) }

}

@keyframes roll {
from { transform: rotate(180deg) }
to   { transform: rotate(360deg) }

}

body {
-moz-animation-name: roll;
-moz-animation-duration: 4s;
-moz-animation-iteration-count: 1;
-webkit-animation-name: roll;
-webkit-animation-duration: 4s;
-webkit-animation-iteration-count: 1;

</style>';
                $goldlost = httpget('goldlost');
                output("`#You walk the forest paths, whistling your favorite battle tune, when suddenly your world turns upside down!`n");
                output("Your foot got caught in a sling rope, and you are hanging down from a tree.`n");
                if ($goldlost > 0) {
                        output("`^$goldlost`# pieces of gold fall out of your pocket.");
                }
                output("`n`n`#You manage to cut the rope,");
                $chancedead = rand(1, 100);
                if ($chancedead <= get_module_setting('chancetodie')) {
                        output("but you fall on your %s and suffer massive internal damage that kills you.`n", $session['user']['weapon']);
                        output("You lose 5% of your experience.`n");
                        output("You may begin playing again tomorrow.");
                        $session['user']['alive']=false;
                        $session['user']['hitpoints']=0;
                        $session['user']['experience']*=0.95;
                        addnav("Daily News","news.php");
                } else {
                  $hplost = (int)($session['user']['hitpoints'] / 100 * rand(get_module_setting('minhplost'), get_module_setting('maxhplost')));
                  $session['user']['hitpoints'] -= $hplost;
                  output("but by falling, you lose `^$hplost`# hit points.");
                  if ($goldlost > 0) {
                          addnav("Search for the lost gold","forest.php?op=searchgold&goldlost=$goldlost");
                  }
                  addnav("Return to the forest","forest.php?op=leave");
                }
        } elseif ($op == "searchgold") {
                $goldlost = httpget('goldlost');
                $goldfound = (int)($goldlost / 100 * rand(get_module_setting('mingoldfound'), get_module_setting('maxgoldfound')));
                output("`#With some effort, you manage to find `^$goldfound`# pieces of gold among the leaves and moss, but you `^lose one forest fight`# for the time spent searching.");
                $session['user']['gold'] += $goldfound;
                $session['user']['turns']--;
        }
}

function slingrope_run(){
}
?>
