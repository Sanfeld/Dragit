<?php
//ver 1.1 added wiseman special to pack
function magicarena_getmoduleinfo(){
	$info = array(
		"name" => "Magical Arena",
		"author" => "`b`&Ka`6laza`&ar`b",
		"version" => "1.1",
		"Download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1070",
		"category" => "Village",
		"description" => "Magical Arena",
		"requires"=>array(
            "arena" => "1.4|`b`&Ka`6laza`&ar`b",
            ),
        "settings"=>array(
        "sellvalue"=>"value in gold per ingredient,int|1000",
        "potionvalue"=>"sell value in gems of potions,int|2",
        "potionsale"=>"buy value in gems of potions,int|125",
        "list"=>"how many on hof,int|25",
        ),
		"prefs"=>array(
		"Magic Arena Prefs,title",
		"user_showstats"=>"Would you like to see Potions?,bool|1",
		"tourreg"=>"Registered for tournament?,bool|",
		"for coding purposes only,note",
		"Vires" => "How many of Vires potion?,int",
		"level1" => "What level this potion?,int",
		"made1" => "Made how many this potion?,int",
		"ValdeVires" => "How many of ValdeVires potion?,int",
		"level2" => "What level this potion?,int",
		"made2" => "Made how many this potion?,int",
		"Tutaminis" => "How many of Tutaminis potion?,int",
		"level3" => "What level this potion?,int",
		"made3" => "Made how many this potion?,int",
		"Navitas" => "How many of Navitis potion?,int",
		"level4" => "What level this potion?,int",
		"made4" => "Made how many this potion?,int",
		"ConfutoNavitas" => "How many of Confuto potion?,int",
		"level5" => "What level this potion?,int",
		"made5" => "Made how many this potion?,int",
		"Navi" => "How many of Navi potion?,int",
		"level6" => "What level this potion?,int",
		"made6" => "Made how many this potion?,int",
		"Diligo" => "How many of Diligo potion?,int",
		"level7" => "What level this potion?,int",
		"made7" => "Made how many this potion?,int",
		"Abominor" => "How many of Abominor potion?,int",
		"level8" => "What level this potion?,int",
		"made8" => "Made how many this potion?,int",
		"Fragilitas" => "How many of Fragilitas potion?,int",
		"level9" => "What level this potion?,int",
		"made9" => "Made how many this potion?,int",
		"ParumNex" => "How many of Parum Nex potion?,int",
		"level10" => "What level this potion?,int",
		"made10" => "Made how many this potion?,int",
		"PropinquusutNex" => "How many of Propinquus ut Nex potion?,int",
		"level11" => "What level this potion?,int",
		"made11" => "Made how many this potion?,int",
		"LevoutSublimitas" => "How many of Levout Sublimitas potion?,int",
		"level12" => "What level this potion?,int",
		"made12" => "Made how many this potion?,int",
		"Umbra" => "How many of Umbra potion?,int",
		"level13" => "What level this potion?,int",
		"made13" => "Made how many this potion?,int",
		"FlammaofAbyssus"=> "How many of Flamma of Abyssus potion?,int",
		"level14" => "What level this potion?,int",
		"made14" => "Made how many this potion?,int",
		"love"=>"under effect of love potion?,int",
		"lastpotion"=>"last potion used in battle,int",
		),
		"prefs-clans"=>array(
		"i1"=>"how many of ingredient 1 for sale?,int",
		"i2"=>"how many of ingredient 2 for sale?,int",
		"i3"=>"how many of ingredient 3 for sale?,int",
		"i4"=>"how many of ingredient 4 for sale?,int",
		"i5"=>"how many of ingredient 5 for sale?,int",
		),
		);
		return $info;
}
function magicarena_install(){
	require_once("lib/tabledescriptor.php");
	$potions = array(
		'potionsid'=>array('name'=>'potionsid', 'type'=>'int unsigned',	'extra'=>'not null auto_increment'),
		'name'=>array('name'=>'name', 'type'=>'text',	'extra'=>'not null'),
		'amount'=>array('name'=>'amount', 'type'=>'int unsigned',	'extra'=>'not null'),
		'clanid'=>array('name'=>'clanid', 'type'=>'int unsigned',	'extra'=>'not null'),
		'key-PRIMARY'=>array('name'=>'PRIMARY', 'type'=>'primary key',	'unique'=>'1', 'columns'=>'potionsid'));
	synctable(db_prefix('potions'), $potions, true);
	module_addeventhook("forest", "return 100;");
	//module_addhook("charstats");
	module_addhook("clanforge-shop");
	module_addhook("clanforge");
	module_addhook("bioinfo");
	module_addhook("footer-hof");
	return true;
}
function magicarena_uninstall(){
	debug("Dropping potions table");
    $sql = "DROP TABLE IF EXISTS " . db_prefix("potions");
    db_query($sql);
	return true;
}
function magicarena_dohook($hookname,$args){
	global $session;
	$op=httpget('op');
	switch ($hookname){
		//potion book in bio
		case "bioinfo":
			$char = httpget("char");
			$sql = ("SELECT acctid FROM ".db_prefix("accounts")." WHERE login='$char'");
			$res = db_query($sql);
            $row = db_fetch_assoc($res);
            $acctid1 = $row['acctid'];
            $acctid = $session['user']['acctid'];
			if ($acctid1==$acctid){
				addnav("Potions Book","runmodule.php?module=magicarena&op=book&user=$argsid&username=$argsname&return=".URLencode($_SERVER['REQUEST_URI']));
			}
			break;
		case "clanforge-shop":
			addnav("Potions","runmodule.php?module=magicarena&op=shopsell");
			output_notl("`n`n");
			output("`4You may buy or sell ingredients or buy and sell potions in the potions shop");
			break;
		case "clanforge":
			addnav("Potions","runmodule.php?module=magicarena&op=mixp");
			break;

		case "footer-hof":
			addnav("Warrior Rankings");
			addnav("Magical Arena","runmodule.php?module=magicarena&op=hof");
			break;
	}
	return $args;
}
function magicarena_runevent(){
	$op==httpget('op');
	if ($op=="search" || $op==""){
		output("You find a old piece of paper, it appears to be half of a note");
		output_notl("`n`n");
		switch (e_rand(1,28)){
			case 1:
				output("Strength, take Monkhood and Basilisk Venom then add another Monkhood and ....");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 2:
				output(".... stir with these three another Basilisk Venom and Mandrake to make Vires");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 3:
				output("Super Strength, take three Monkhoods, then add....");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 4:
				output("....one Obsidian Dragon Scale and a Mandrake to make Valde Vires");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 5:
				output("Defence take one Basilisk Venom and a Mandrake	mix in.....");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 6:
				output("...Monkhood another Mandrake and Basilisk Venom to get Tutaminis");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 7:
				output("Energy, take one Mandrake and two Basilisk Venoms then add...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 8:
				output("... another Monkhood finish with Mandrake to get Navitas");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 9:
				output("Supreme Energy, mix Basilisk Venom and Mandrake take another...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 10:
				output("...Basilisk Venom and add a Obsidian Dragon Scale and Basilisk Venom to create Confuto Navitas");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 11:
				output("Flying, take one Obsidian Dragon Scale	and a Basilisk Venom with two Mandrakes...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 12:
				output("... mix in one Basilisk Venom to form Navi");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 13:
				output("Love, Hemlock, Monkhood and Hemlock	add to ...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 14:
				output(".... these Basilisk Venom and Hemlock to see Diligo");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 15:
				output("Hate, add Mandrake to Monkhood	and M...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 16:
				output("...andrake, Basilisk Venom and another Mandrake to form Abominor");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 17:
				output("Weakness, two Hemlocks and one... ");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 18:
				output("...Mandrake with two more Hemlocks to make Fragilitas");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 19:
				output("Little Death, take one Monkhood	 and a Mandrake, add to these a	Hemlock...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 20:
				output(".....with one Basilisk Venom and another Monkhood to create Parum Nex");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 21:
				output("Closer to Death, start with one Obsidian Dragon Scale and Mandrake with...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 22:
				output("... a Hemlock and Basilisk Venom, stir in another Obsidian Dragon Scale you will have Propinquus ut Nex");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 23:
				output("Lift to the Heights, three Monkhoods add to...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 24:
				output("this a Basilisk Venom and Obsidian Dragon Scale to get Levo ut Sublimitas");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 25:
				output("Shadows, take one Hemlock and one Basilisk Venom with two...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 26:
				output("...Mandrakes and another Hemlock to see Umbra");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			case 27:
				output("Flames of Hell, Mandrake and a rare Obsidian Dragon Scale with Hemlock...");
				output_notl("`n`n");
				output("You look around for the other half to this note, hoping you can figure out how to make this potion, unfortunately, its no where to be seen");
				break;
			case 28:
				output("...	and a single Monkhood add another Hemlock to create Flamma of Abyssus");
				output_notl("`n`n");
				output("You wonder where the first half of this note is... Unfortunately its nowhere to be seen");
				break;
			}
			addnav("Return to Forest","forest.php");
		}
}
function magicarena_run(){
	global $SCRIPT_NAME;
	if ($SCRIPT_NAME == "runmodule.php"){
		$module=httpget("module");
		if ($module == "magicarena") {
			include("modules/arena/magicarena.php");
		}
	}
}

?>