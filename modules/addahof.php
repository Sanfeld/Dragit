<?php
//V3.01 Fixes for additional pages
function addahof_getmoduleinfo(){
	$info = array(
		"name"=>"Add-A-HoF",
		"version"=>"3.01",
		"author"=>"DaveS",
		"category"=>"Administrative",
		"download"=>"",
		"vertxtloc"=>"",
		"settings"=>array(
			"Settings,title",
			"hof1"=>"1.What is the title page for the HoF?text|Hardest Workers In the Land",
			"You need the actual name of the module without the php here,note",
			"hofmod1"=>"1.What is the module that the Hof is from?text|jobs",
			"You need the preference from the code here,note",
			"hofpref1"=>"1.What preference is the Hof based on?,text|jobexp",
			"hofdesc1"=>"1. What does the preference represent?,text|Experience",
			"hofnav1"=>"1.What is the HoF navigation?,text|Hardest Workers",
			"hof2"=>"2.What is the title page for the HoF?text|",
			"hofmod2"=>"2.What is the module that the Hof is from?text|",
			"hofpref2"=>"2.What preference is the Hof based on?,text|",
			"hofnav2"=>"2.What is the HoF navigation?,text|",
			"hofdesc2"=>"2. What does the preference represent?,text|",
			"hof3"=>"3.What is the title page for the HoF?text|",
			"hofmod3"=>"3.What is the module that the Hof is from?text|",
			"hofpref3"=>"3.What preference is the Hof based on?,text|",
			"hofnav3"=>"3.What is the HoF navigation?,text|",
			"hofdesc3"=>"3. What does the preference represent?,text|",
			"hof4"=>"4.What is the title page for the HoF?text|",
			"hofmod4"=>"4.What is the module that the Hof is from?text|",
			"hofpref4"=>"4.What preference is the Hof based on?,text|",
			"hofnav4"=>"4.What is the HoF navigation?,text|",
			"hofdesc4"=>"4. What does the preference represent?,text|",
			"hof5"=>"5.What is the title page for the HoF?text|",
			"hofmod5"=>"5.What is the module that the Hof is from?text|",
			"hofpref5"=>"5.What preference is the Hof based on?,text|",
			"hofnav5"=>"5.What is the HoF navigation?,text|",
			"hofdesc5"=>"5. What does the preference represent?,text|",
			"hof6"=>"6.What is the title page for the HoF?text|",
			"hofmod6"=>"6.What is the module that the Hof is from?text|",
			"hofpref6"=>"6.What preference is the Hof based on?,text|",
			"hofnav6"=>"6.What is the HoF navigation?,text|",
			"hofdesc6"=>"6. What does the preference represent?,text|",
			"hof7"=>"7.What is the title page for the HoF?text|",
			"hofmod7"=>"7.What is the module that the Hof is from?text|",
			"hofpref7"=>"7.What preference is the Hof based on?,text|",
			"hofnav7"=>"7.What is the HoF navigation?,text|",
			"hofdesc7"=>"7. What does the preference represent?,text|",
			"hof8"=>"8.What is the title page for the HoF?text|",
			"hofmod8"=>"8.What is the module that the Hof is from?text|",
			"hofpref8"=>"8.What preference is the Hof based on?,text|",
			"hofnav8"=>"8.What is the HoF navigation?,text|",
			"hofdesc8"=>"8. What does the preference represent?,text|",
			"pp"=>"Listings per page in HoF?,int|40",
		),
	);
	return $info;
}
function addahof_install(){
	module_addhook("footer-hof");
	return true;
}
function addahof_uninstall(){
	return true;
}
function addahof_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "footer-hof":
			addnav("Warrior Rankings");
			for ($i=0;$i<=7;$i++) {
				$type=$i+1;
				if (get_module_setting("hofnav".$type)!="") addnav(array("%s", get_module_setting("hofnav".$type)),"runmodule.php?module=addahof&op=hof&type=$type");	
			}
		break;
	}
	return $args;
}

function addahof_run(){
	global $session;
	$op = httpget("op");
if ($op == "hof") {
	page_header("Hall of Fame");
	$page = httpget('page');
	$type = httpget("type");
	$pp = get_module_setting("pp");
	$module=get_module_setting("hofmod".$type);
	$pref=get_module_setting("hofpref".$type);
	$pageoffset = (int)$page;
	if ($pageoffset > 0) $pageoffset--;
	$pageoffset *= $pp;
	$limit = "LIMIT $pageoffset,$pp";
	$sql = "SELECT COUNT(*) AS c FROM " . db_prefix("module_userprefs") . " WHERE modulename = '".$module."' AND setting = '".$pref."' AND value > 0";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$total = $row['c'];
	$count = db_num_rows($result);
	if (($pageoffset + $pp) < $total){
		$cond = $pageoffset + $pp;
	}else{
		$cond = $total;
	}
	$sql = "SELECT ".db_prefix("module_userprefs").".value, ".db_prefix("accounts").".name FROM " . db_prefix("module_userprefs") . "," . db_prefix("accounts") . " WHERE acctid = userid AND modulename = '".$module."' AND setting = '".$pref."' AND value > 0 ORDER BY (value+0) DESC $limit";
	$result = db_query($sql);
	$rank = translate_inline("Rank");
	$name = translate_inline("Name");
	$hofdesc = get_module_setting("hofdesc".$type);
	$none = translate_inline("None");
	output("`n`b`c`@%s`n`c`b",get_module_setting("hof".$type));
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$hofdesc</td></tr>");
	if (db_num_rows($result)>0){
		for($i = $pageoffset; $i < $cond && $count; $i++) {
			$row = db_fetch_assoc($result);
			if ($row['name']==$session['user']['name']){
				rawoutput("<tr class='trhilight'><td>");
			}else{
				rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
			}
			$j=$i+1;
			output_notl("$j.");
			rawoutput("</td><td>");
			output_notl("`&%s`0",$row['name']);
			rawoutput("</td><td>");
			output_notl("`c`b`Q%s`c`b`0",$row['value']);
			rawoutput("</td></tr>");
        }
	}
	rawoutput("</table>");
	if ($total>$pp){
		addnav("Pages");
		for ($p=0;$p<$total;$p+=$pp){
			addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=addahof&op=hof&type=$type&page=".($p/$pp+1));
		}
	}
	addnav("Other");
	addnav("Back to HoF", "hof.php");
	villagenav();
}


page_footer();	
}
?>