<?php
require_once("lib/villagenav.php");
if ($_GET['op']=="download"){ // this offers the module on every server for download
 $dl=join("",file("dwarvengemshop.php"));
 echo $dl;
}
/*
Modified by eph 18 Sep 2005.
- Stripped the dwarvenhold of all specials to make it a pure gemshop.
- Changed the buy/sell routine. Now selling gems actually takes into account how large the demand is. And the shop manager keeps a share for herself. :)
- Fixed a bug where gems in stock could reach a negative amount. Now the shopkeeper doesn't allow to buy more gems than are available.
---------------------------
Dwarven Hold - By Strider
27Jan2004 for the Legendgard (LoGD)
version 2.6

- - - - - Special thanks to Anpera - - - - - - 

 -Originally by: Strider
-Contributors: Strider
 Mar 2004  - Legendgard Script*/

function dwarvengemshop_getmoduleinfo(){
	$info = array(
		"name"=>"Dwarven Gem Shop",
		"author"=>"mod. by eph from dwarvenhold by Strider",
		"version"=>"1.1",
		"category"=>"Village",
		"download"=>"modules/dwarvengemshop.php?op=download",
		"settings"=>array(
			"Dwarven Gem Shop Settings,title",
			"selledgems"=>"Gems in the Treasury,int|1000",
			"totalgems"=>"Gems able to be held in the Treasury,int|1500",
			"gemprof"=>"Profit for selling a single gem,int|1000",
			"gemcost"=>"Cost for buying 1 gem,int|3000",
			"Dwarven Shop Location,title",
			"holdloc"=>"Where does the Dwarven Gem Shop appear,location|".getsetting("villagename", LOCATION_FIELDS)
			),
		);
	return $info;
}
function dwarvengemshop_install(){
	module_addhook("village");
	module_addhook("changesetting");
	return true;
}
function dwarvengemshop_uninstall(){
	return true;
}
function dwarvengemshop_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "changesetting":
    if ($args['setting'] == "villagename") {
    if ($args['old'] == get_module_setting("holdloc")) {
       set_module_setting("holdloc", $args['new']);
       }
    }
    break;
  	case "village":
    if ($session['user']['location'] == get_module_setting("holdloc")) {
		tlschema($args['schemas']['marketnav']);
        addnav($args['marketnav']);
		tlschema();
        addnav("Dwarven Gem Shop","runmodule.php?module=dwarvengemshop&op=enter");
    }
	break;
	}
	return $args;
}
function dwarvengemshop_run(){
	global $session;
	$id = 1;
	$selledgems = get_module_setting("selledgems");
	$totalgems = get_module_setting("totalgems");
	$gemprof = get_module_setting("gemprof");
	$gemcost = get_module_setting("gemcost");
	$gem = $gemcost-$selledgems+abs((int)(0.1*$selledgems));
	$scost = $gemprof+($totalgems-$selledgems)-abs((int)(0.1*($totalgems-$selledgems)));
	$op = httpget('op');
	$gemssell = httppost('gemssell');
	$gemsbuy = httppost('gemsbuy');

	page_header("The Dwarven Gem Shop");
	output("`@`c`bInside the Dwarven Gem Shop`b`c");

switch ($op){
case "enter":
	blocknav("runmodule.php?module=dwarvengemshop&op=enter");
	output(" The famed artistic skill of dwarven hands is well earned in the building surrounding you.");
	output(" Massive plates of iron are twisted with intricate shapes and design, and the roof is held by rune covered stone pillars.`n");
	output("`2`n`nA female dwarf wearing an ornamented robe eagerly watches you. Her long red braids softly brush the floor and indicate her high social rank. She must be the owner of this shop.`n`n");
	output(" As you approach her, the dwarf silently points to a masterfully crafted sign announcing there are `^%s gems`2 on the market.",$selledgems);
	// Gem Exchange Notices (too many or too few gems on the market)
	if ($selledgems>=$totalgems){ output("`2 `nA very elegant sign hangs on the Gem Shop Desk that says, `n`n`#\"`3Gem Market Surplus! We are not buying anymore gems right now!`#\"`7" ); 
	blocknav("runmodule.php?module=dwarvengemshop&op=sell");
	}
	if ($selledgems<=2 && $selledgems>0) output("`2`n`nA very elegant sign hangs on the Gem Shop Desk that says, `n`n`#\"`3Gem Market LOW! Dwarf miners, please supply the exchanges!`#\"`7" ); 
	if ($selledgems<=0){
		blocknav("runmodule.php?module=dwarvengemshop&op=buy"); 
	}	

//Dwarven Gem Shop buy Menu

addnav("Gem Exchange - Buy");
addnav(array("Buy Gems `^%s Gold",$gem),"runmodule.php?module=dwarvengemshop&op=buy");

addnav("Gem Exchange - Sell");
addnav(array("Sell Gems `^%s Gold",$scost) ,"runmodule.php?module=dwarvengemshop&op=sell");

addnav("Other");
break;

	case "buy":
		$gemcount = abs((int)$gemsbuy);
		if ($gemsbuy==""){
					output("`n`n\"`%So you would like to buy some gems?`0\" The dwarf says as she adjusts her glasses.  \"`%How many would you like to purchase?`%!`0\"");
					output("`n`n`\$Buy how many gems?");
					output("<form action='runmodule.php?module=dwarvengemshop&op=buy' method='POST'><input name='gemsbuy' value='0'><input type='submit' class='button' value='".translate_inline('Buy')."'>`n",true);
					addnav("","runmodule.php?module=dwarvengemshop&op=buy");
			}else{
			$take = ($gemcount*$gem);
				if ($session['user']['gold']<$take){
					  output("`2`n`nThe dwarf adjusts her glasses and sighs. `n \"`%You don't have that much gold, but you are about to get the wrong part of my impatience!`2\" she grumbles mildly.");
					}elseif ($gemcount>300){
					  output("`2`n`nYou see the dwarf get a bit flustered as you offer to buy`^%s gems`2.",$gemcount);
					  output("`n \"`%Now see here, we're not equipped. . . simply... hmph! I can't afford to sell that many gems to your grubby hands.`2\" she informs you.");
					}elseif ($selledgems<$gemcount){
						output("\"I'm sorry, we don't have that many gems in stock.\" the dwarf informs you with a sad look on her face. \"You can buy a maximum of %s gems at the moment, so please rethink your decision.\"",$selledgems);
						}else{
					  output("`2`n`nShe places `^%s `2gem%s on the scale to be examined.",$gemcount,translate_inline($gemcount>1?"s":""));
						if ($gemcount>0){
						$session['user']['gems']+=$gemcount;
						$take = ($gemcount*$gem);
						$session['user']['gold']-=$take;
						$newgems =  $selledgems - $gemcount;
						set_module_setting("selledgems", $newgems);
						debuglog("bought $gemcount gems for $take gold");
						output("`2The dwarf gives you your `^%s gem%s `2and takes `^%s gold `2.`n`^(Transaction Complete)",$gemcount,translate_inline($gemcount>1?"s":""), $take);
						}else{
							output("`2`n`nYou think you're clever and offer`^ ZERO gold`2 to the dwarf.");
							output(" Accordingly, the dwarf pays you `6zero`2 mind.");
							output(" She's obviously heard this stupid joke before.");
							output(" She casually pulls out a copy of \'`6Better Gnomes and Gardens `2\'while you stand there looking pleased with yourself and grinning like an idiot.");
							}
						}
					}
				break;
	case "sell":
		  if ($gemssell==""){
					output("`n`n\"`%You would like to sell some gems?`0\" The dwarf says as she adjusts her glasses.  \"`%How many would you like to sell?`%!`0\"");
					output("`n`n`\$Sell how many gems?");
					output("<form action='runmodule.php?module=dwarvengemshop&op=sell' method='POST'><input name='gemssell' value='0'><input type='submit' class='button' value='".translate_inline('Sell')."'>`n",true);
					addnav("","runmodule.php?module=dwarvengemshop&op=sell");
			}else{
			  $gemcount = abs((int)$gemssell);
					if ($gemcount>$session['user']['gems']){
					  output("`2`n`nThe dwarf adjusts her glasses and sighs. `n \"`%You don't have that many gems, but you are about to get the wrong part of my impatience!`2\" she grumbles mildly.");
					
					}elseif ($gemcount>300){
					  output("`2`n`nYou see the dwarf get a bit flustered as you offer `^%s gems`2 for sale.",$gemcount);
					  output("`n \"`%Now see here, we're not equipped. . . simply... hmph! I can't afford to take that many gems off your hands.`2\" she grumbles.");
					}else{
					  output("`2`n`nYou place `^%s `2gems on the scale to be examined.",$gemcount);
						if ($gemcount>0){
						$session['user']['gems']-=$gemcount;
						$profit = ($gemcount*$scost);
						$session['user']['gold']+=$profit;
						$newgems =  $selledgems + $gemcount;
						set_module_setting("selledgems", $newgems);
						debuglog("sold $gemcount gems for $goldcount gold");
						output("`2The dwarf looks through your `^%s gems `2and pays you `^%s gold `2.`n`^(Transaction Complete)",$gemcount, $profit);
						}else{
							output("`2`n`nYou think you're clever and offer`^ ZERO gems`2 to the dwarf.");
							output(" Accordingly, the dwarf pays you `6zero`2 mind.");
							output(" She's obviously heard this stupid joke before.");
							output(" She casually pulls out a copy of \'`6Better Gnomes and Gardens `2\'while you stand there looking pleased with yourself and grinning like an idiot.");
							}
						}
					}
				break;
		 }

		 addnav ("Dwarven Gem Shop","runmodule.php?module=dwarvengemshop&op=enter"); 
		 villagenav();
page_footer();
}
?>
