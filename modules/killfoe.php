<?php
require_once("lib/systemmail.php");

function killfoe_getmoduleinfo()
{
	$info = array(
		"name"=>"Kill a Foe",
		"version"=>"1.0",
		"author"=>"Colin Harvie",
		"category"=>"Shades",
		"download"=>"",
		"settings"=>array(
			"Kill a Foe Settings,title",
			"favor"=>"Amount of favor needed to kill a foe.,int|500"),
	);
	return $info;
}

function killfoe_install(){
	debug("Adding Hooks");
	module_addhook("favors");
	return true;
}

function killfoe_uninstall(){
	output("Uninstalling this module.`n");
	return true;
}

function killfoe_dohook($hookname, $args){
global $session;
	$favor = get_module_setting("favor");
	if ($session['user']['deathpower'] >= $favor){
		addnav("Ramius Favors");
		addnav("K?Kill a foe (".$favor." favor)","runmodule.php?module=killfoe&op=kill");
	}
	return $args;
}

function killfoe_runevent($type){
}

function killfoe_run(){
	global $session;
	$op = httpget('op');
	if ($op == "kill"){
	page_header("Graveyard");
	output("`\$Ramius`) is very impressed with your actions, and as a reward, will kill a foe from your old life.`n`n");
		output("<form action='runmodule.php?module=killfoe&op=kill2' method='POST'>",true);
		addnav("","runmodule.php?module=killfoe&op=kill2");
		output("Who would you like `\$Ramius`) to drain the lifeforce from? <input name='name' id='name'> <input type='submit' class='button' value='Search'>",true);
		output("</form>",true);
		output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
		
		addnav("Places");
		addnav("S?Land of the Shades","shades.php");
		addnav("G?The Graveyard","graveyard.php");
		addnav("M?Return to the Mausoleum","graveyard.php?op=enter");
		output("`)");
	}elseif($op == "kill2"){
	page_header("Graveyard");
$string="%";
		$name = httppost('name');
		for ($x=0;$x<strlen($name);$x++){
			$string .= substr($name,$x,1)."%";
		}
		$sql = "SELECT login,name,level FROM " . db_prefix("accounts") . " WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY level,login";
		$result = db_query($sql);
		if (db_num_rows($result)<=0){
			output("`\$Ramius`) could find no one who matched the name you gave him.");
		}elseif(db_num_rows($result)>100){
			output("`\$Ramius`) thinks you should narrow down the number of people you wish to haunt.");
			output("<form action='runmodule.php?module=killfoe&op=kill2' method='POST'>",true);
			addnav("","runmodule.php?module=killfoe&op=kill2");
			output("Who would you like `\$Ramius`) to drain the lifeforce from? <input name='name' id='name'> <input type='submit' class='button' value='Search'>",true);
			output("</form>",true);
			output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
		}else{
			output("`\$Ramius`) will allow you to choose from these people to be killed:`n");
			output("<table cellpadding='3' cellspacing='0' border='0'>",true);
			output("<tr class='trhead'><td>Name</td><td>Level</td></tr>",true);
			for ($i=0;$i<db_num_rows($result);$i++){
				$row = db_fetch_assoc($result);
				output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='runmodule.php?module=killfoe&op=kill3&name=".HTMLEntities($row['login'])."'>",true);
				output($row['name']);
				output("</a></td><td>",true);
				output($row['level']);
				output("</td></tr>",true);
				addnav("","runmodule.php?module=killfoe&op=kill3&name=".HTMLEntities($row['login']));
			}
			output("</table>",true);
		}
		
		addnav("Question `\$Ramius`) about the worth of your soul","graveyard.php?op=question");
		addnav("Restore Your Soul ($favortoheal favor)","graveyard.php?op=restore");
		addnav("Places");
		addnav("S?Land of the Shades","shades.php");
		addnav("G?The Graveyard","graveyard.php");
		addnav("M?Return to the Mausoleum","graveyard.php?op=enter");
	}elseif ($op=="kill3"){
		page_header("Graveyard");
		$favor = get_module_setting("favor");
		output("`)`c`bThe Mausoleum`b`c");
		$name = httpget('name');
		$sql = "SELECT name,level,hauntedby,acctid,alive FROM " . db_prefix("accounts") . " WHERE login='$name'";
		$result = db_query($sql);
		if (db_num_rows($result)>0){
			$row = db_fetch_assoc($result);
			$session['user']['deathpower']-=$favor;
				output("`\$Ramius`) has successfully killed `7{$row['name']}`)!");
				$sql = "UPDATE " . db_prefix("accounts") . " SET alive='false' WHERE login='$name'";
				db_query($sql);
				$sql = "UPDATE " . db_prefix("accounts") . " SET hitpoints='0' WHERE login='$name'";
				db_query($sql);
				addnews("`\$Ramius`) killed `7{$row['name']}`) at the request of `7{$session['user']['name']}`)!");
				systemmail($row['acctid'],"`\$Ramius`) killed you","`)`\$Ramius`) killed you at the request of {$session['user']['name']}");
		}else{
			output("`\$Ramius`) has lost their concentration on this person, you cannot have them killed now.");
		}
		
		addnav("Question `\$Ramius`0 about the worth of your soul","graveyard.php?op=question");
		addnav("Restore Your Soul ($favortoheal favor)","graveyard.php?op=restore");
		addnav("Places");
		addnav("S?Land of the Shades","shades.php");
		addnav("G?The Graveyard","graveyard.php");
		addnav("M?Return to the Mausoleum","graveyard.php?op=enter");
		output("`)");
	}

	page_footer();
}
?>
