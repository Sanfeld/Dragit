<?php
// updated to 1.1 added clan and overall HoF's, added output for fortune when forging
// updated to 1.3 split module run to a func, changed gems payment from once at start to paying amt of gems per attempt
function clanforge_getmoduleinfo(){
	$info = array(
		"name" => "Clan Forge",
		"author" => "`b`&Ka`6laza`&ar`b",
		"version" => "1.3",
		"download" => "http://dragonprime.net/index.php?module=Downloads;catd=20",
		"category" => "Clan",
		"description" => "Clan Members can create custom weapons and armor",
		"settings"=>array(
			"Clan Forge,title",
			"cost"=>"cost per attempt to create in gems,int|5",
			"list"=>"how many on hof,int|25",
			),
		"prefs" => array(
			"level" => "What weapon level is the player up to?, int|0",
			"levela" => "What armor level is the player up to?, int|0",
			"tries" => "How many tries this weapon level, int|0",
			"atries" => "How many tries this armor level,int|0",
			"fortune" => "Amount of fortune,int|0",
			"name" => "name of weapon or armor,text|",
			"cost" => "cost to buy,int|0",
			"value" => "atk or def value,int|0",
		),
		);
	return $info;
}
function clanforge_install(){
	require_once("lib/tabledescriptor.php");
	 $clanshop = array(
		'shopid'=>array('name'=>'shopid', 'type'=>'int unsigned',	'extra'=>'not null auto_increment'),
		'type'=>array('name'=>'type', 'type'=>'int unsigned',	'extra'=>'not null'),
		'name'=>array('name'=>'name', 'type'=>'text',	'extra'=>'not null'),
		'value'=>array('name'=>'value', 'type'=>'int unsigned',	'extra'=>'not null'),
		'cost'=>array('name'=>'cost', 'type'=>'int unsigned',	'extra'=>'not null'),
		'clan'=>array('name'=>'clan', 'type'=>'int unsigned',	'extra'=>'not null'),
		'creator'=>array('name'=>'creator', 'type'=>'int unsigned',	'extra'=>'not null'),
		'buyer'=>array('name'=>'buyer', 'type'=>'int unsigned',	'extra'=>'not null'),
		'key-PRIMARY'=>array('name'=>'PRIMARY', 'type'=>'primary key',	'unique'=>'1', 'columns'=>'shopid'));
		synctable(db_prefix('clanshop'), $clanshop, true);
	module_addhook("footer-clan");
	module_addeventhook("forest", "return 100;");
	module_addhook("footer-hof");
	module_addhook("biostat");
	return true;
}
function clanforge_uninstall(){
	debug("Dropping clanshop table");
    $sql = "DROP TABLE IF EXISTS " . db_prefix("clanshop");
	return true;
}
function clanforge_dohook($hookname,$args){
	global $session;
	$op = httpget('op');
	switch ($hookname){
		case "footer-clan":
			if ($session['user']['clanrank'] >= CLAN_MEMBER){
				addnav("Forge");
				addnav("Clan Forge", "runmodule.php?module=clanforge&op=enter");
				addnav("Clan Shop","runmodule.php?module=clanforge&op=shop");
				addnav("Weapon Forge HoF","runmodule.php?module=clanforge&op=weaponhofc");
				addnav("Armor Forge HoF","runmodule.php?module=clanforge&op=armorhofc");
			}
			break;
		case "footer-hof":
			addnav("Warrior Rankings");
			addnav("Forge Weapon HoF", "runmodule.php?module=clanforge&op=weaponhof");
			addnav("Forge Armor HoF", "runmodule.php?module=clanforge&op=armorhof");
			break;
		case "biostat":
			$char = httpget("char");
			$sql = ("SELECT * FROM ".db_prefix("accounts")." WHERE login='$char'");
			$res = db_query($sql);
            $row = db_fetch_assoc($res);
            $acctid = $row['acctid'];
            $name=$row['name'];
            $wl = get_module_pref("level","clanforge", $acctid);
            $al = get_module_pref("levela","clanforge", $acctid);
            if ($wl>0){
	            output("`^Forge Weapons Level: `@%s",$wl);
	            output_notl("`n");
            }
            if ($al>0){
	            output("`^Forge Armor Level: `@%s",$al);
	            output_notl("`n");
            }
            break;
	}
	return $args;
}
function clanforge_runevent(){
	global $session;
	page_header("Your Fortune");
	$session['user']['specialinc'] = "module:clanforge";
	$op=httpget('op');
	if ($op=="" || $op=="search"){
		output("You come across a small delapidated hut deep in the forest, there is a thin trail of smoke coming from the chimney.  You see a curtain twitch, and get the feeling that someone is watching your every move.");
		addnav("What Will You do?");
		addnav("Knock on the Door","runmodule.php?module=clanforge&op=knock");
	}
	addnav("Return to the Forest","forest.php");
	$session['user']['specialinc']="";
	page_footer();
}
function clanforge_run(){
	global $SCRIPT_NAME;
	if ($SCRIPT_NAME == "runmodule.php"){
		$module=httpget("module");
		if ($module == "clanforge") {
			include("modules/clanforge/clanforge_func.php");
		}
	}
}
function clanforge_fortune(){
	switch (e_rand(1,6)){
		case 1:
		output("\"`$ I'm sorry, not much is in store for you today\" `0Your fortune is unaffected");
		$nf=get_module_pref("fortune");
		break;
		case 2:
		output("\"`$ Ooooooh, what a great day you are going to have\"`0 Your fortune is increased by `^2");
		$nf = get_module_pref("fortune")+2;
		set_module_pref("fortune",$nf);
		break;
		case 3:
		output("\"`$ Ooooooooh no, this is not a good day for you\"`0 Your fortune has decreased by `^1");
		$nf = get_module_pref("fortune")-1;
		set_module_pref("fortune",$nf);
		break;
		case 4:
		output("\"`$ Hmmmm, this will be a good day today\"`0 Your fortune has increased by `^1");
		$nf = get_module_pref("fortune")+1;
		set_module_pref("fortune",$nf);
		break;
		case 5:
		output("\"`$ Uh oh, this is not a good day today\"`0 Your fortune has decreased by `^2");
		$nf = get_module_pref("fortune")-2;
		set_module_pref("fortune",$nf);
		break;
		case 6:
		output("\"`$ Ooooh, What a Wonderful day today\"`0 Your fortune has increased by `^10");
		$nf = get_module_pref("fortune")+10;
		set_module_pref("fortune",$nf);
		break;
		case 6:
		output("\"`$ Ooooh, What a Fabulous day today, fortune is surely yours\"`0 Your fortune has increased by `^15");
		$nf = get_module_pref("fortune")+15;
		set_module_pref("fortune",$nf);
		break;
	}
	output_notl("`n`n");
	output("Your fortune is now %s",$nf);
}
?>