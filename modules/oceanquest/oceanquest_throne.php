<?php
function oceanquest_throne(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$op4 = httpget('op4');
	$locale = httpget('loc');
	$allprefs=unserialize(get_module_pref('allprefs'));
	page_header("The Throne Room");
	output("`c`b`%`iThe Throne Room`i`7`b`c");
	$misc= array ('king','guard','envoy');
	if (in_array($op2,$misc)){
		require_once("modules/oceanquest/oceanquest_thronemisc.php");
		oceanquest_thronemisc($op2);
	}
	if ($session['user']['hitpoints'] <= 0) redirect("shades.php");
	$umaze = get_module_pref('thronemap');
	$umazeturn = $allprefs['mazeturn'];
	$upqtemp = get_module_pref('pqtemp');
	if ($op2 == "" && $locale == "") {
		$locale=2;
		$umazeturn = 0;
		if (!isset($maze)){
			$maze=array(60,51,61,54,52,55,60,52,61,54,52,55,60,52,61,54,52,55,60,52,61,54,53,55,60,56,61);
			$umaze = implode($maze,",");
			set_module_pref("thronemap", $umaze);
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op2 <> ""){
		if ($op2 == "n") {
			$locale+=3;
			redirect("runmodule.php?module=oceanquest&op=throne&loc=$locale");
		}
		if ($op2 == "s"){
			$locale-=3;
			redirect("runmodule.php?module=oceanquest&op=throne&loc=$locale");
		}

	}else{
		if ($locale <> ""){
			$maze=explode(",", $umaze);
			if ($locale=="") $locale = $upqtemp;
			$upqtemp = $locale;
			set_module_pref("pqtemp", $upqtemp);
			for ($i=0;$i<$locale-1;$i++){
			}
			$navigate=ltrim($maze[$i]);
			output("`7");
			if ($session['user']['hitpoints'] > 0){
				$allprefs=unserialize(get_module_pref('allprefs'));
				addnav("Options");
				if ($locale=="2") addnav("Leave the Throne Room","runmodule.php?module=oceanquest&op=pilinoria&op2=castle");
				elseif ($locale=="5"){
					addnav("L?Talk to Guard on Left","runmodule.php?module=oceanquest&op=throne&op2=guard&op3=1");
					addnav("R?Talk to Guard on Right","runmodule.php?module=oceanquest&op=throne&op2=guard&op3=2");
				}elseif ($locale=="11"){
					addnav("L?Talk to Guard on Left","runmodule.php?module=oceanquest&op=throne&op2=guard&op3=3");
					addnav("R?Talk to Guard on Right","runmodule.php?module=oceanquest&op=throne&op2=guard&op3=4");
				}elseif ($locale=="17"){
					addnav("L?Talk to Guard on Left","runmodule.php?module=oceanquest&op=throne&op2=guard&op3=5");
					addnav("R?Talk to Guard on Right","runmodule.php?module=oceanquest&op=throne&op2=guard&op3=6");
				}elseif ($locale=="23"){
					addnav("K?Talk to the `5King","runmodule.php?module=oceanquest&op=throne&op2=king");
					addnav("L?Talk to Guard on Left","runmodule.php?module=oceanquest&op=throne&op2=guard&op3=7");
					addnav("R?Talk to Guard on Right","runmodule.php?module=oceanquest&op=throne&op2=guard&op3=8");
				}
				$umazeturn++;
				$allprefs['mazeturn']=$umazeturn;
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				addnav("Directions");
				if ($navigate=="51" || $navigate=="52") addnav("North","runmodule.php?module=oceanquest&op=throne&op2=n&loc=$locale");
				if ($navigate=="52" || $navigate=="53") addnav("South","runmodule.php?module=oceanquest&op=throne&op2=s&loc=$locale");
			}else{
				addnav("Continue","shades.php");
			}
			$mazemap=$navigate;
			$mazemap.="maze.gif";
			output_notl("`n`c");
			rawoutput("<small>");
			rawoutput("<table style=\"height: 450px; width: 150px; text-align: absmiddle; line-height: 50px; font-size: 8px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td colspan=\"3\"></td>");
			$mapkey="";
			for ($i=0;$i<50;$i++){
				$keymap=ltrim($maze[$i]);
				$mazemap=$keymap;
				$mazemap.=".gif";
				if ($i==$locale-1){
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/player.gif\" title=\"\" alt=\"\" style=\"width: 50px; height: 50px;\"></td>";
				}elseif (($allprefs['freed']==2 ||$allprefs['freed']==1) && $i==25){
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/64.gif\" title=\"\" alt=\"\" style=\"width: 50px; height: 50px;\"></td>";
				}elseif (($allprefs['freed']==2 ||$allprefs['freed']==1) && ($i==0 || $i==6 || $i==12 || $i==18 || $i==24)){
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/67.gif\" title=\"\" alt=\"\" style=\"width: 50px; height: 50px;\"></td>";
				}elseif (($allprefs['freed']==2 ||$allprefs['freed']==1) && ($i==2 || $i==8 || $i==14 || $i==20 || $i==26)){
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/68.gif\" title=\"\" alt=\"\" style=\"width: 50px; height: 50px;\"></td>";
				}elseif (($allprefs['freed']==2 ||$allprefs['freed']==1) && ($i==3 || $i==9 || $i==15 || $i==21)){
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/65.gif\" title=\"\" alt=\"\" style=\"width: 50px; height: 50px;\"></td>";
				}elseif (($allprefs['freed']==2 ||$allprefs['freed']==1) && ($i==5 || $i==11 || $i==17 || $i==23)){
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/66.gif\" title=\"\" alt=\"\" style=\"width: 50px; height: 50px;\"></td>";
				}else{
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/$mazemap\" title=\"\" alt=\"\" style=\"width: 50px; height: 50px;\"></td>";
				}
				if ($i==2 or $i==5 or $i==8 or $i==11 or $i==14 or $i==17 or $i==20 or $i==23 or $i==26){
					$mapkey="</tr><tr>".$mapkey;
					$mapkey2=$mapkey.$mapkey2;
					$mapkey="";
				}
			}
			output_notl($mapkey2,true);
			output_notl("</table>",true);
			output_notl("`c");
		}
	}
}
?>