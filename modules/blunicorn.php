<?php

function blunicorn_getmoduleinfo(){
	$info = array(
		"name"=>"Black Unicorn",
		"version"=>"3.01",
		"author"=>"Lonnyl, converted by DaveS",
		"category"=>"Forest Specials",
		"download"=>"",
		"settings"=>array(
			"Black Unicorn,title",
			"mindk"=>"Minimum number of dks to encounter this event:,int|0",
			"forest"=>"Chance of encountering Black Unicorn in the forest:,range,0,100,1|100",
			"blunicorn"=>"ID for Black Unicorn:,viewonly|",
		),
	);
	return $info;
}
function blunicorn_chance() {
	global $session;
	$ret= get_module_setting('forest','blunicorn');
	if ($session['user']['dragonkills']<get_module_setting("mindk","blunicorn")) $ret=0;
	return $ret;
}
function blunicorn_install(){
	module_addeventhook("forest","require_once(\"modules/blunicorn.php\"); 
	return blunicorn_chance();");
	$sql = "SELECT mountname FROM ".db_prefix("mounts")." where mountname = 'Black Unicorn'";
	$result = mysql_query($sql);
	if (db_num_rows($result) < 1){
		$sql = "INSERT INTO ".db_prefix("mounts")."  (mountname,mountdesc,mountcategory,mountbuff,mountcostgems,mountcostgold,mountactive,mountforestfights,newday,recharge,partrecharge) VALUES ('Black Unicorn','Black Unicorn','Unicorns','a:7:{s:4:\"name\";s:13:\"Black Unicorn\";s:8:\"roundmsg\";s:39:\"Your Black Unicorn fights by your side!\";s:7:\"wearoff\";s:28:\"Your Black Unicorn is tired.\";s:6:\"rounds\";s:2:\"60\";s:6:\"atkmod\";s:3:\"1.8\";s:6:\"defmod\";s:3:\"1.0\";s:8:\"activate\";s:15:\"offense,defense\";}',19,0,0,3,'You strap your {weapon} to your Black Unicorn, and head out for some adventure!','`&Remembering that is has been quite some time since you last fed your Black Unicorn, you decide this is a perfect time to relax and allow it to graze in the field a bit. You doze off enjoying this peaceful serenity.`0','`&You dismount in the field to allow your Black Unicorn to graze for a moment even though it has recently been fully fed.  As you lean back in the grass to watch the clouds, your unicorn neighs softly and gallops off into the field.  You search for a while before returning to the fields hoping that it\'ll return.  A short time later, your black unicorn canters back into the clearing holding its head high, looking much more energized and with a very excited grin on its face.`0')";
		db_query($sql) or die(db_error(LINK));
		if (db_affected_rows(LINK)>0){
			output("`2Installed Black Unicorn Mount`n");
		}else{
			output("`4Black Unicorn Mount install failed!`n");
		}
		$sql = "SELECT mountid FROM ".db_prefix("mounts")." where mountname = 'Black Unicorn'";
		$result = db_query($sql) or die(db_error(LINK));
		$row = db_fetch_assoc($result);
		if ($row['mountid'] > 0){
			set_module_setting("blunicorn",$row['mountid']);
			output("`2Set ID for Black Unicorn to %s`n",$row['mountid']);
		}else{
			output("`4Failed to Set ID for Black Unicorn!`n");
		}
	}
	return true;
}
function blunicorn_uninstall(){
	$sql = "DELETE FROM ".db_prefix("mounts")." where mountname='Black Unicorn'";
	db_query($sql);
	return true;
}
function blunicorn_runevent($type) {
	global $session,$playermount;
	$session['user']['specialinc']="module:blunicorn";
	$op = httpget('op');
	$op2 = httpget('op2');
	$hp = httpget('hp');
if ($op==""){
	output("`4`n`cYou have encountered a `@Black Unicorn`4, the rarest of forest creatures!`c`b`n");
	$session['user']['turns']--;
	addnav("`\$Fight the `)Black Unicorn`\$!","forest.php?op=attack");
}
if ($op=="keep"){
	$unicorn=get_module_setting("blunicorn");
	$sql = "SELECT * FROM ".db_prefix("mounts")." where mountid=$unicorn";
	$result = db_query($sql);
	$mount = db_fetch_assoc($result);
	if ($session['user']['hashorse']>0) output("`#You let your `0%s`# go and wipe the lone tear from your eye as it runs off into the forest.`n`n",$playermount['mountname']);
	$session['user']['hashorse']=$mount['mountid'];
	$session['bufflist']['mount']=unserialize($mount['mountbuff']);
	output("`#You strap your %s`# to your `@Black Unicorn's`# Saddle and go on your way.`n`n`n",$session['user']['weapon']);
	$session['user']['specialinc']="";
}
if ($op=="letgo"){
	output("`n`n`#You watch as the magnificent `@Black Unicorn`# runs off into the forest.");
	$session['user']['specialinc']="";
}
if ($op=="attack") {
	$name=translate_inline("`@Black Unicorn`0");
	$weapon=translate_inline("Razor Sharp Horn");
	$userattack=$session['user']['attack']+e_rand(1,3);
	$userhealth=round($session['user']['hitpoints']*.78);
	$userdefense=$session['user']['defense']+e_rand(1,3);
	$badguy = array(
		"creaturename"=>$name,
		"creaturelevel"=>16,
		"creatureweapon"=>$weapon,
		"creatureattack"=>$userattack,
		"creaturedefense"=>$userdefense,
		"creaturehealth"=>$userhealth,
		"diddamage"=>0,
		"type"=>"blunicorn");
	$session['user']['badguy']=createstring($badguy);
	$op="fight";
}
if ($op=="fight" || $op=="run"){
	$battle=true;
	if ($op2=="capture"){
		$chance=round(25/$session['user']['level'])+3;
	 	if ($hp < 100 and $hp > 0 and e_rand(1,$chance) > ($chance-1)){
			output("`#Success!");
			output("You have captured the `@Black Unicorn`#!");
			addnews("`%%s`# has captured a `@Black Unicorn`# in the forest.",$session['user']['name']);
			$fight=false;
			$caught=1;
			$session['user']['badguy']="";
			$battle=false;
			if ($session['user']['hashorse']==0){
				addnav("Continue","forest.php?op=keep");
			}else{
				output("`n`n`#However, you already have a mount.  What would you like to do?`n`n");
				$playermount = getmount($session['user']['hashorse']);
				$mount=$playermount['mountname'];
				output("Keep your `0%s`# or keep the `@Black Unicorn`#?",$mount);
				addnav(array("Keep your %s",$mount),"forest.php?op=letgo");
				addnav("Keep the Black Unicorn","forest.php?op=keep");
			}
		}else{
			output("`4`c`n`bYou fail to capture the `@Black Unicorn`4.`b`c`n");
		}
	}
}
if ($battle){       
	include("battle.php");  
	if ($victory){
		output("`b`4You have slain the `@Black Unicorn`4!`b`n");
		addnews("`%%s`5 has defeated a `@Black Unicorn`5.",$session['user']['name']);
		$session['user']['specialinc']="";
		$gold=e_rand(100,500);
		$experience=$session[user][level]*e_rand(37,99);
		output("`#You recieve `^%s gold`#!`n",$gold);
		$session['user']['gold']+=$gold;
		output("`7You find a `%Gem`7!`n");
		$session['user']['gems']++;
		output("`#You recieve `6%s `#experience!`n",$experience);
		$session['user']['experience']+=$experience;
	}elseif($defeat){
		$session['user']['specialinc'] = "";
		require_once("lib/taunt.php");
		$taunt = select_taunt_array();
		$exploss = round($session['user']['experience']*.1);
		output("`n`n`#As you hit the ground `@Black Unicorn`# runs away.`n");
		output(" You lose `^%s `#experience.`n",$exploss);
		output("`n`c`bYou may begin fighting again tomorrow.`c`b");
		addnav("Daily news","news.php");
		$session['user']['experience']-=$exploss;
		$session['user']['alive'] = false;
		$session['user']['hitpoints'] = 0;
		$session['user']['gold']=0;     		
		addnews("`^%s`# has been slain after an encounter with a `@Black Unicorn`# in the forest.",$session['user']['name']);
	}else{
		fightnav(true,true);
		if ($badguy['creaturehealth'] > 0){
			addnav("Special");
			$hp=$badguy['creaturehealth'];
			addnav("Capture Black Unicorn","forest.php?op=fight&op2=capture&hp=$hp");
		}
	}
} 
}
?>