<?php
function oceanquest_sailing(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$op4 = httpget('op4');
	$locale = httpget('loc');
	$allprefs=unserialize(get_module_pref('allprefs'));
	page_header("The Luckstar");
	output("`c`b`^`iThe Luckstar`i`7`b`c");
	$misc= array ('captain','askfishing','askdinner','askexplore','gofishing','dinner2','goexplore','locations');
	if (in_array($op2,$misc)){
		require_once("modules/oceanquest/oceanquest_sailmisc.php");
		oceanquest_sailmisc($op2);
	}
	if ($session['user']['hitpoints'] <= 0) redirect("shades.php");
	$umaze = get_module_pref('travelmap');
	$umazeturn = $allprefs['mazeturn'];
	$upqtemp = get_module_pref('pqtemp');
	if ($op2 == "" && $locale == "") {
		if ($allprefs['pilinoria']==1)$locale=47;
		elseif ($allprefs['island']==1) $locale=8;
		else{
			$locale=21;
			$session['user']['turns']--;
			$allprefs['mazeturn'] = 0;
		}
		$allprefs['pilinoria']=0;
		$allprefs['island']=0;
		$umazeturn = 0;
		$allprefs['direction']=2;
		if (!isset($maze)){
			$maze=array(20,1,2,2,2,3,31,1,2,30,21,4,5,5,5,5,2,5,6,29,10,5,5,5,5,5,5,5,9,28,22,4,5,5,5,5,5,9,26,27,20,7,8,8,8,8,9,23,24,25);
			$umaze = implode($maze,",");
			set_module_pref("travelmap", $umaze);
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op2 <> ""){
		if ($op2 == "n") {
			$locale+=10;
			redirect("runmodule.php?module=oceanquest&op=sailing&loc=$locale");
		}
		if ($op2 == "s"){
			$locale-=10;
			redirect("runmodule.php?module=oceanquest&op=sailing&loc=$locale");
		}
		if ($op2 == "w"){
			$locale-=1;
			if ($allprefs['direction']==2){
				$allprefs=unserialize(get_module_pref('allprefs'));
				$allprefs['direction']=1;
				set_module_pref('allprefs',serialize($allprefs));
			}
			redirect("runmodule.php?module=oceanquest&op=sailing&loc=$locale");
		}
		if ($op2 == "e"){
			$locale+=1;
			if ($allprefs['direction']==1){
				$allprefs=unserialize(get_module_pref('allprefs'));
				$allprefs['direction']=2;
				set_module_pref('allprefs',serialize($allprefs));
			}
			redirect("runmodule.php?module=oceanquest&op=sailing&loc=$locale");
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
				if ($locale=="21"){
					addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
					blocknav("runmodule.php?module=oceanquest&op=sailing&op2=goexplore");
					blocknav("runmodule.php?module=oceanquest&op=sailing&op2=gofishing");
				}
				addnav("x?Explore the Ship","runmodule.php?module=oceanquest&op=sailing&op2=goexplore");
				if ($allprefs['pole']==1 && $allprefs['bait']==1 && $allprefs['sailfish']==0) addnav("Go Fishing","runmodule.php?module=oceanquest&op=sailing&op2=gofishing");
				if ($allprefs['mazeturn']>3 && $allprefs['captaintalk']==0 && ($allprefs['sailfish']==0 || $allprefs['captaindinner']==0 || $allprefs['okexplore']==0)){
					addnav("C?Speak with the Captain","runmodule.php?module=oceanquest&op=sailing&op2=captain");
				}
				if ($locale=="47" && $allprefs['shore']==0) addnav("Disembark to Pilinoria","runmodule.php?module=oceanquest&op=pilinoria&op2=landing");
				if ($locale=="8" && $allprefs['captaindinner']==1 && $allprefs['shore']==0) addnav("Explore the Island","runmodule.php?module=oceanquest&op=island&op2=landing");
				if ($allprefs['shore']==1) output("The captain informs you that the Kingdom Dock is the only port that will be open today and you should return there.");
				output("`n`cYou may sail");
				$umazeturn++;
				$allprefs['mazeturn']=$umazeturn;
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				$navcount = 0;
				$north=translate_inline("North");
				$south=translate_inline("South");
				$east=translate_inline("East");
				$west=translate_inline("West");
				$directions="";
				addnav("Directions");
				if ($navigate=="1" || $navigate=="2" || $navigate=="3" || $navigate=="4" || $navigate=="5" || $navigate=="6" || $navigate=="11" || $navigate=="12" || $navigate=="13" || $navigate=="14" || $navigate=="15" || $navigate=="16") {
					addnav("North","runmodule.php?module=oceanquest&op=sailing&op2=n&loc=$locale");
					$directions.=" $north";
					$navcount++;
				}
				if ($navigate=="4" || $navigate=="5" || $navigate=="6" || $navigate=="7" || $navigate=="8" || $navigate=="9" || $navigate=="14" || $navigate=="15" || $navigate=="16" || $navigate=="17" || $navigate=="18" || $navigate=="19") {
					addnav("South","runmodule.php?module=oceanquest&op=sailing&op2=s&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $south";
				}
				if ($navigate=="2" || $navigate=="3" || $navigate=="5" || $navigate=="6" || $navigate=="8" || $navigate=="9" || $navigate=="12" || $navigate=="13" || $navigate=="15" || $navigate=="16" || $navigate=="18" || $navigate=="19" || $navigate=="30") {
					addnav("West","runmodule.php?module=oceanquest&op=sailing&op2=w&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $west";
				}
				if ($navigate=="1" || $navigate=="2" || $navigate=="4" || $navigate=="5" || $navigate=="7" || $navigate=="8" || $navigate=="10" || $navigate=="11" || $navigate=="12" || $navigate=="14" || $navigate=="15" || $navigate=="17" || $navigate=="18") {
					addnav("East","runmodule.php?module=oceanquest&op=sailing&op2=e&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $east";
				}
				output_notl(" %s.`c",$directions);				
			}else{
				addnav("Continue","shades.php");
			}
			$mazemap=$navigate;
			$mazemap.="maze.gif";
			output_notl("`n`c");
			rawoutput("<small>");
			rawoutput("<table style=\"height: 300px; width: 500px; text-align: absmiddle; line-height: 60px; font-size: 8px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td colspan=\"6\"></td>");
			$mapkey="";
			for ($i=0;$i<50;$i++){
				$keymap=ltrim($maze[$i]);
				$mazemap=$keymap;
				$mazemap.=".gif";
				if ($i==$locale-1){
					if ($allprefs['direction']==1) $ship="sail2.gif";
					else $ship="sail1.gif";
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/$ship\" title=\"\" alt=\"\" style=\"width: 60px; height: 60px;\"></td>";					
				}elseif ($allprefs['sorcerer']==1 && $i==19){
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/62.gif\" title=\"\" alt=\"\" style=\"width: 60px; height: 60px;\"></td>";									
				}elseif ($allprefs['freed']==1 && $i==49){
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/63.gif\" title=\"\" alt=\"\" style=\"width: 60px; height: 60px;\"></td>";									
				}else{
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/$mazemap\" title=\"\" alt=\"\" style=\"width: 60px; height: 60px;\"></td>";					
				}
				if ($i==9 or $i==19 or $i==29 or $i==39 or $i==49){
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