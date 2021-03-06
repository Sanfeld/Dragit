<?php
function oceanquest_fishingexpedition(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$op4 = httpget('op4');
	$locale = httpget('loc');
	$allprefs=unserialize(get_module_pref('allprefs'));
	page_header("Fishing Expedition");
	output("`c`b`^Fishing Expedition`7`b`c");
	$misc= array ('leave','gauge','fish','gold','walkaway','damagepay','bodysearch');
	if (in_array($op2,$misc)){
		require_once("modules/oceanquest/oceanquest_fishmisc.php");
		oceanquest_fishmisc($op2);
	}
	if ($session['user']['hitpoints'] <= 0) redirect("shades.php");
	$umaze = get_module_pref('fishmaze');
	$umazeturn = $allprefs['mazeturn'];
	$upqtemp = get_module_pref('pqtemp');
	if ($op2 == "" && $locale == "") {
		if ($op3=="payturn") $session['user']['turns']--;
		$locale=13;
		$umazeturn = 0;
		$allprefs=unserialize(get_module_pref('allprefs'));
		$allprefs['mazeturn']=0;
		$allprefs['direction']=2;
		if (!isset($maze) && ($allprefs['fishmap']==""||$allprefs['fishmap']==0)){
			$randommaze=e_rand(1,4);
			$allprefs['fishmap']=$randommaze;
			if ($randommaze==1) $maze=array(20,1,12,2,2,3,21,4,5,15,5,6,10,5,5,5,5,6,22,4,5,15,5,6,20,7,8,8,8,19);
			elseif ($randommaze==2) $maze=array(20,1,2,12,2,3,21,4,5,5,5,6,10,5,5,15,15,6,22,4,5,5,5,6,20,17,8,8,8,9);
			elseif ($randommaze==3) $maze=array(20,1,2,12,2,3,21,14,5,5,5,6,10,5,5,5,5,6,22,4,5,5,5,16,20,7,8,18,8,9);
			else $maze=array(20,1,2,2,2,13,21,4,5,5,5,6,10,5,5,5,5,16,22,4,15,5,15,6,20,7,8,8,8,9);
			$umaze = implode($maze,",");
			set_module_pref("fishmaze", $umaze);
			$allprefs['wind1']=e_rand(1,30);
			$allprefs['wind2']=e_rand(1,30);
			$allprefs['wind3']=e_rand(1,30);
			$allprefs['wind4']=e_rand(1,30);
			$allprefs['depth1']=e_rand(50,125);
			$allprefs['depth2']=e_rand(50,125);
			$allprefs['depth3']=e_rand(50,125);
			$allprefs['depth4']=e_rand(50,125);
			$allprefs['temp1']=e_rand(60,90);
			$allprefs['temp2']=e_rand(60,90);
			$allprefs['temp3']=e_rand(60,90);
			$allprefs['temp4']=e_rand(60,90);
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op2 <> ""){
		if ($op2 == "n") {
			$locale+=6;
			redirect("runmodule.php?module=oceanquest&op=fishingexpedition&loc=$locale");
		}
		if ($op2 == "s"){
			$locale-=6;
			redirect("runmodule.php?module=oceanquest&op=fishingexpedition&loc=$locale");
		}
		if ($op2 == "w"){
			$locale-=1;
			if ($allprefs['direction']==2){
				$allprefs=unserialize(get_module_pref('allprefs'));
				$allprefs['direction']=1;
				set_module_pref('allprefs',serialize($allprefs));
			}
			redirect("runmodule.php?module=oceanquest&op=fishingexpedition&loc=$locale");
		}
		if ($op2 == "e"){
			$locale+=1;
			if ($allprefs['direction']==1){
				$allprefs=unserialize(get_module_pref('allprefs'));
				$allprefs['direction']=2;
				set_module_pref('allprefs',serialize($allprefs));
			}
			redirect("runmodule.php?module=oceanquest&op=fishingexpedition&loc=$locale");
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
				if ($locale=="13") addnav("Return to the Docks","runmodule.php?module=oceanquest&op=docks&op2=enter");
				if ($allprefs['fishingtoday']<5){
					$fishmap=$allprefs['fishmap'];
					if (($locale=="3" && $fishmap==1) || ($locale=="4" && ($fishmap==2||$fishmap==3)) || ($locale=="6" && $fishmap==4)){
						addnav("Go Fishing","runmodule.php?module=oceanquest&op=fishingexpedition&op2=fish&op3=1");
						addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpedition&op2=gauge&op3=1");
						$allprefs['quality']=1;
					}elseif (($locale=="10" && $fishmap==1) || ($locale=="16" && $fishmap==2) || ($locale=="8" && $fishmap==3) || ($locale=="18" && $fishmap==4)){
						addnav("Go Fishing","runmodule.php?module=oceanquest&op=fishingexpedition&op2=fish&op3=2");
						addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpedition&op2=gauge&op3=2");
						$allprefs['quality']=2;
					}elseif (($locale=="22" && $fishmap==1) || ($locale=="17" && $fishmap==2) || ($locale=="24" && $fishmap==3) || ($locale=="21" && $fishmap==4)){
						addnav("Go Fishing","runmodule.php?module=oceanquest&op=fishingexpedition&op2=fish&op3=3");
						addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpedition&op2=gauge&op3=3");
						$allprefs['quality']=3;
					}elseif (($locale=="30" && $fishmap==1) || ($locale=="26" && $fishmap==2) || ($locale=="28" && $fishmap==3) || ($locale=="23" && $fishmap==4)){
						addnav("Go Fishing","runmodule.php?module=oceanquest&op=fishingexpedition&op2=fish&op3=4");
						addnav("Check Gauges","runmodule.php?module=oceanquest&op=fishingexpedition&op2=gauge&op3=4");
						$allprefs['quality']=4;
					}
					set_module_pref('allprefs',serialize($allprefs));
					$allprefs=unserialize(get_module_pref('allprefs'));
					//output_notl("`n");
				}else{
					output("It's time to head back to the docks because you're out of fishing turns.");
				}
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
					addnav("North","runmodule.php?module=oceanquest&op=fishingexpedition&op2=n&loc=$locale");
					$directions.=" $north";
					$navcount++;
				}
				if ($navigate=="4" || $navigate=="5" || $navigate=="6" || $navigate=="7" || $navigate=="8" || $navigate=="9" || $navigate=="14" || $navigate=="15" || $navigate=="16" || $navigate=="17" || $navigate=="18" || $navigate=="19") {
					addnav("South","runmodule.php?module=oceanquest&op=fishingexpedition&op2=s&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $south";
				}
				if ($navigate=="2" || $navigate=="3" || $navigate=="5" || $navigate=="6" || $navigate=="8" || $navigate=="9" || $navigate=="12" || $navigate=="13" || $navigate=="15" || $navigate=="16" || $navigate=="18" || $navigate=="19") {
					addnav("West","runmodule.php?module=oceanquest&op=fishingexpedition&op2=w&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $west";
				}
				if ($navigate=="1" || $navigate=="2" || $navigate=="4" || $navigate=="5" || $navigate=="7" || $navigate=="8" || $navigate=="10" || $navigate=="11" || $navigate=="12" || $navigate=="14" || $navigate=="15" || $navigate=="17" || $navigate=="18") {
					addnav("East","runmodule.php?module=oceanquest&op=fishingexpedition&op2=e&loc=$locale");
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
			output("`7Fishing Zones:`\$ �");
			rawoutput("<table style=\"height: 300px; width: 360px; text-align: absmiddle; line-height: 60px; font-size: 8px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td colspan=\"6\"></td>");
			$mapkey="";
			for ($i=0;$i<30;$i++){
				$keymap=ltrim($maze[$i]);
				$mazemap=$keymap;
				$mazemap.=".gif";
				if ($i==$locale-1){
					if ($allprefs['direction']==1) $ship="ship2.gif";
					else $ship="ship1.gif";
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/$ship\" title=\"\" alt=\"\" style=\"width: 60px; height: 60px;\"></td>";					
				}else{
					$mapkey.="<td style=\"width: 50px; height: 50px; padding-right: 0px;\"><img src=\"./modules/oceanquest/images/$mazemap\" title=\"\" alt=\"\" style=\"width: 60px; height: 60px;\"></td>";					
				}
				if ($i==5 or $i==11 or $i==17 or $i==23 or $i==29){
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