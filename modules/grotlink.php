<?php
/*
Details:
 * This allows you to have a dropdown list of links
History Log:
 * v1.0:
  o Seems to be Stable
 * v1.1:
  o Added a few more options
 * v1.2:
  o Now choose the locations of the links
 * v1.3:
  o Bug fixed
 * v1.4:
  o Bug fixed
 * v1.5:
  o Bug fixed
  o Code has been toned down
  o Even easier for modules to add themselves
*/
require_once("lib/commentary.php");
require_once("lib/sanitize.php");
require_once("lib/http.php");
require_once("lib/villagenav.php");

function grotlink_getmoduleinfo(){
	$info = array(
		"name"=>"Grotto Links",
		"version"=>"1.5",
		"author"=>"`@CortalUX",
		"override_forced_nav"=>true,
		"category"=>"Administrative",
		"vertxtloc"=>"http://dragonprime.net/users/CortalUX/",
		"download"=>"http://dragonprime.net/users/CortalUX/grotlink.zip",
		"prefs"=>array(
			"Grotto Links,title",
			"check_linkloc"=>"Where should the links appear?,enum,0,Charstats,1,Footer,2,Header|0",
			"check_glink"=>"Show grotto links?,bool|1",
			"check_clink"=>"Show comment link?,bool|1",
			"check_plink"=>"Show preference link?,bool|1",
			"check_olink"=>"Show other links?,bool|1",
			"check_hide"=>"How should links show?,enum,0,Hidden when page loads,1,Visible when page loads|0",
			"grotlink"=>"Can see the above options?,bool|0",
		),
	);
	return $info;
}

function grotlink_install(){
	global $session;
	if (!is_module_active('grotlink')) {
		output("`n`c`b`QGrotto Links Module - Installed`0`b");
	}else{
		output("`n`c`b`QGrotto Links Module - Updated`0`b");
	}
	output("`n`n`#`bDON'T FORGET TO SET PREFERENCES IN THE GROTTO!`b`c");
	if (!is_module_active('grotlink')) {
		if ($session['user']['superuser']&~SU_DOESNT_GIVE_GROTTO) {
			set_module_pref('grotlink',1);
			output("`n`c`\$Your permissions have been set automatically.`c");
		}
	}
	module_addhook("charstats");
	module_addhook("checkuserpref");
	module_addhook("everyheader");
	module_addhook("everyfooter");
	return true;
}

function grotlink_uninstall(){
	output("`n`c`b`QGrotto Links Module - Uninstalled`0`b`c");
	return true;
}
	
function grotlink_dohook($hookname,$args){
	global $session,$SCRIPT_NAME;
	switch ($hookname) {
		case "charstats":
			if (get_module_pref('check_glink')==1||get_module_pref('check_clink')==1||get_module_pref('check_plink')==1||get_module_pref('check_olink')==1) {
				if (get_module_pref('grotlink')==1&&get_module_pref('check_linkloc')==0) {
					$code = grotlink_form();
					addcharstat("Click and use Items");
					addcharstat("Grotto Links", $code);
				}
			}
		break;
		case "checkuserpref":
			$args['allow']=false;
			if (get_module_pref('grotlink')==1) {
				if ($session['user']['superuser'] & SU_EDIT_COMMENTS&&$args['name']=='check_clink') {
					$args['allow']=true;
				}
				if ($args['name']=='check_linkloc'||$args['name']='check_hide') {
					$args['allow']=true;
				}
				if ($session['user']['superuser']&~SU_DOESNT_GIVE_GROTTO) {
					if ($args['name']=='check_olink'||$args['name']=='check_glink'||$args['name']=='check_plink') {
						$args['allow']=true;
					}
				}
			}
		break;
		case "everyheader":
			if (get_module_pref('check_glink')==1||get_module_pref('check_clink')==1||get_module_pref('check_plink')==1||get_module_pref('check_olink')==1) {
				if (get_module_pref('grotlink')==1&&get_module_pref('check_linkloc')==2) {
					output_notl("`c".grotlink_form("Show/Hide Grotto Links","`n")."`n`n`c",true);
				}
			}
		break;
		case "everyfooter":
			if (get_module_pref('check_glink')==1||get_module_pref('check_clink')==1||get_module_pref('check_plink')==1||get_module_pref('check_olink')==1) {
				if (get_module_pref('grotlink')==1&&get_module_pref('check_linkloc')==1) {
					output_notl("`c`n`n".grotlink_form("Show/Hide Grotto Links","`n")."`c",true);
				}
			}
		break;
	}
	return $args;
}

function grotlink_run(){
}

function grotlink_form($text="Show/Hide Links",$nl="") {
	global $session;
	$code = "<script language=\"JavaScript\">\nfunction showAndHide(theId)\n{\n   var el = document.getElementById(theId)\n\n   if (el.style.display==\"none\")\n   {\n      el.style.display=\"block\"; //show element\n   }\n   else\n   {\n      el.style.display=\"none\"; //hide element\n   }\n}\n</script>";
	$text = appoencode("`^[`@".translate_inline($text)."`^]".$nl,true);
	$code .= "<a href=\"javascript:showAndHide('linkFormS');\">$text</a>";
	if (get_module_pref('check_hide')==0) {
		$code .= "<div id='linkFormS' style=\"display:none;\"><br>";
	} else {
		$code .= "<div id='linkFormS' style=\"display:block;\"><br>";
	}
	$code .= "";
	$links=array();
	$code .= "<form name='linkform'>";
	$code .= "<select name='links'>";
	$links['village.php']="The Village";
	if (get_module_pref('check_clink')==1&&$session['user']['superuser'] & SU_EDIT_COMMENTS) {
		$links['moderate.php']="Comment Moderation";
	}
	if (get_module_pref('check_plink')==1) {
		$links['prefs.php']="Preferences";
	}
	if (get_module_pref('check_olink')==1) {
		$links['forest.php']="The Forest";
		$links['bank.php']="The Bank";
		$links['inn.php']="The Inn";
		$links['clan.php']="Clan Halls";
	}
	if (get_module_pref('check_glink')==1) {
		if ($session['user']['superuser']&~SU_DOESNT_GIVE_GROTTO) $links['superuser.php']="The Grotto";
		if ($session['user']['superuser']&SU_INFINITE_DAYS) $links['newday.php']="A New Day";
		if ($session['user']['superuser'] & SU_EDIT_PETITIONS) $links['viewpetition.php']="Petition Viewer";
		if ($session['user']['superuser'] & SU_EDIT_COMMENTS) {
			if (get_module_pref('check_clink')==0) $links['moderate.php']="Comment Moderation";
			$links['bios.php']="Player Bios";
			$links['badword.php']="Nasty Word Editor";
		}
		if ($session['user']['superuser'] & SU_EDIT_DONATIONS) $links['donators.php']="Donator Page";
		if (file_exists("paylog.php")&&($session['user']['superuser'] & SU_EDIT_PAYLOG)) $links['paylog.php']="Payment Log";
		if ($session['user']['superuser'] & SU_RAW_SQL) $links['rawsql.php']="Run Raw SQL";
		if ($session['user']['superuser'] & SU_IS_TRANSLATOR) $links['untranslated.php']="Untranslated Texts";
		if ($session['user']['superuser'] & SU_EDIT_USERS) {
			$links['retitle.php']="Retitler";
			$links['user.php']="User Editor";
		}
		if ($session['user']['superuser'] & SU_EDIT_CREATURES) {
			$links['creatures.php']="Creature Editor";
			$links['taunt.php']="Taunt Editor";
		}
		if ($session['user']['superuser'] & SU_EDIT_MOUNTS) $links['mounts.php']="Mount Editor";
		if (file_exists("looteditor.php") && $session['user']['superuser'] & SU_EDIT_ITEMS) $links['looteditor.php']="Loot Editor";
		if ($session['user']['superuser'] & SU_EDIT_EQUIPMENT) {
			$links['weaponeditor.php']="Weapon Editor";
			$links['armoreditor.php']="Armor Editor";
		}
		if ($session['user']['superuser'] & SU_MANAGE_MODULES) $links['modules.php']="Manage Modules";
		if ($session['user']['superuser'] & SU_EDIT_CONFIG) {
			$links['configuration.php']="Game Settings";
			$links['referers.php']="Referring URLs";
			$links['stats.php']="Stats";
		}
		$modules = array("modloc"=>"Module Locations|","claneditor"=>"Clan Editor|","checkmodvers"=>"Check Module Versions","riddles"=>"Riddle Editor|&act=editor&admin=true","quotes"=>"Quotes Editor|&op=list","drinks"=>"Drinks Editor|&act=editor&admin=true");
		$modules = modulehook("grottolink-form", $modules);
		foreach ($modules as $name => $text) {
			if (is_module_active($name)) {
				$i = explode('|',$text);
				if (!isset($i[1])) $i[1]="";
				$name=$name.$i[1];
				$links['runmodule.php?module=$name']=$i[0];
			}
		}
		foreach ($links as $url => $text) {
			$g = translate_inline($text);
			$code .= "<option value='$url'>$g</option>";
			addnav("",$url);
		}
	}
	$code .= "</select><br>";
	$g = translate_inline("Go!");
	$code .= "<input type=\"button\" name=\"go\" value=\"$g\" onClick=\"window.location=document.linkform.links.options[document.linkform.links.selectedIndex].value\"> </form>";
	$code .="</div>";
	return $code;
}
?>