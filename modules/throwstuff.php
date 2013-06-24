<?php
/**************
Name: Throw Produce
Author: Eth - ethstavern(at)gmail(dot)com
Version: 1.1
Release Date: 02-09-2005
Rerelease Date: 12-24-2005 (for 1.0.x)
About: Pay a peddler a gem or two to fling produce at people stuck in the stocks.
Translation ready!
*****************/
require_once("lib/villagenav.php");
function throwstuff_getmoduleinfo(){
	$info = array(
		"name"=>"Throw Produce",
		"version"=>"1.1",
		"author"=>"Eth",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/users/Eth/throwstuff.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"requires"=>array(
			"stocks"=>"Village Stocks|By Eric Stevens, part of the core download.",			
		),
		"settings"=>array(
			"Settings,title",
			"throwcost"=>"Cost in Gems to throw items?,int|1",
			"bucketcost"=>"Cost in Gems to dump bucket?,int|3",												
		),
		"prefs"=>array(
			"Preferences,title",
			"thrownyet"=>"Has user thrown something today?,bool|0",
			"thrownat"=>"Has user had something thrown at them?,bool|0",
		),
	);
	return $info;
}
function throwstuff_install(){	
	module_addhook("village-desc");	
	module_addhook("newday");	
	return true;
}
function throwstuff_uninstall(){
	return true;
}
function throwstuff_dohook($hookname,$args){
	global $session;
	$stocks = get_module_setting("victim", "stocks");
	$capital = getsetting("villagename", LOCATION_FIELDS);
	$thrownyet = get_module_pref("thrownyet");
	switch ($hookname) {
	case "village-desc":
	if ($session['user']['location']!=$capital) break;
		if (is_module_active("stocks")){
			if ($stocks>0 AND $stocks !=$session['user']['acctid'] AND $thrownyet==0){
			$link = translate_inline("take a look?");
			output("`n`3Near the stocks you see a fellow standing next to a cart full of rotten produce.");
			output(" Maybe you'd like to");
			rawoutput(" <a href='runmodule.php?module=throwstuff&op=look'>[$link]</a><br>");
			addnav("","runmodule.php?module=throwstuff&op=look");
			}
		}
	break;
	case "newday":	
	if (get_module_pref("thrownat") == 1){
		output("`n`3Washing off the smell of the rotten produce, you feel less attractive.`n");
		$session['user']['charm']--;
		set_module_pref("thrownat",0);
	}	
	set_module_pref("thrownyet",0);
	break;
	}
	return $args;
}
function throwstuff_runevent($type){	
}
function throwstuff_run(){
	global $session;
	page_header("Village Stocks");
	$op = httpget('op');
	$from = "runmodule.php?module=throwstuff&";
	$stocks = get_module_setting("victim", "stocks");
	$throwcost = get_module_setting("throwcost");	
	$bucketcost = get_module_setting("bucketcost");
	if ($op=="look"){
		output("`2The fellow seems to be selling the rotten vegetables to people, who in turn are throwing them at the poor soul stuck in the stocks!");
		output(" You think this sounds like a fun idea and inquire how much.`n`n");		
		output("\"`%%s gems`2, and ye get t' smack tha' poor soul with a nice `\$o'er-ripe tomato`2, `@soggy head o' cabbage`2, or wha' have you!\" replies the peddler.", $throwcost);
		if ($session['user']['gems']>=$bucketcost) {
			output(" \"'Course too, ye can pay `%%s gems `2and dump a whole bucket o' this fine stuff o'er their head.\"`n`n", $bucketcost);
		}
		if ($session['user']['gems']<$throwcost){
			output("`3It's a pity, because you don't seem to have enough gems to pay the peddler for this delightful thrill.`n`n");
		}else{
		$nav1 = translate_inline("Pay and Throw Tomato");
		$nav2 = translate_inline("Pay and Throw Cabbage");		
		$nav3 = translate_inline("Pay and Throw Squash");
		$nav4 = translate_inline("Pay and Throw Onion");
		$nav5 = translate_inline("Pay and Dump Bucket");		
		addnav("$nav1", $from."op=throw&what=tomato");
		addnav("$nav2", $from."op=throw&what=cabbage");
		addnav("$nav3", $from."op=throw&what=squash");
		addnav("$nav4", $from."op=throw&what=onion");					
		if ($session['user']['gems']>=$bucketcost){ addnav("$nav5", $from."op=throw&what=bucket"); }					
		}
		villagenav();
	}else if ($op=="throw"){
		$what = httpget('what');
		$sql = "SELECT name FROM " . db_prefix("accounts") . " WHERE acctid='$stocks'";
		$result = db_query_cached($sql, "stocks");
		$row = db_fetch_assoc($result);
		$victim = $row['name'];	
		if ($what == "tomato") $produce=translate_inline("`\$over-ripe tomato");
		if ($what == "cabbage") $produce=translate_inline("`@soggy head of cabbage");
		if ($what == "squash") $produce=translate_inline("`6decaying squash");
		if ($what == "onion") $produce=translate_inline("`&over-powering and squishy onion");						
		if ($what == "bucket") {
			output("`2Feeling particularly devious, you pay the peddler `%%s gems `2and fill up a bucket with rotten vegetables.`n`n", $bucketcost);
			output("`2Walking up to `^%s`2, you dump the bucket over their head!", $victim);
			output(" Everyone who is gathered around the stocks share a deep, long laugh with you.`n`n");
			addnews("`3%s `2dumped a bucket of rotten vegetables over `^%s's `2head!", $session['user']['name'],$victim);
			$session['user']['gems']-=$bucketcost;
		}else{		
			output("`2Handing the peddler his `%%s gems`2, you choose a nice %s `2and fling it at `^%s`2!", $throwcost, $produce, $victim);	
			output(" It hits with a resounding `isplat`i and you and the others gathered around have a good laugh.`n`n");	
			addnews("`3%s `2was seen throwing a %s `2at `^%s`2, who is stuck in the stocks.", $session['user']['name'],$produce,$victim);
			$session['user']['gems']-=$throwcost;
		}
		set_module_pref("thrownat",1,"throwstuff",$stocks);
		set_module_pref("thrownyet", 1);
		
		villagenav();
	}
	page_footer();
}
?>