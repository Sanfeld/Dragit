<?php
global $session;
$op = httpget('op');
page_header("Amulets");
if ($op == "shades"){
	$session['user']['alive'] = 1;
	$session['user']['hitpoints'] = 1;
	output("You grab hold of the Triquetra Amulet Around your neck.  The warmth of it increases, as ");
	output("does the warmth of your body!  You are once again alive!`n");
	villagenav();
	page_footer();
}else{
	$amulet = array(1=>"shamrock",2=>"triquetra",3=>"heart",4=>"cross",5=>"ankh",6=>"pegasus",7=>"unicorn",8=>"phoenix",9=>"dragon",10=>"yinyang",11=>"artemis",12=>"horace",13=>"star",14=>"salamander",15=>"bastet",16=>"thor",17=>"anubis",18=>"apollo",19=>"dionysos",20=>"hermes");
	output("`c`b`3Amulet Holders`b`@`n`n");
	for($i=1;$i<21;$i++){
	if (get_module_setting($amulet[$i]) > 0){
	$sql = "SELECT name FROM ". db_prefix("accounts") . " WHERE acctid = '".get_module_setting($amulet[$i])."'";
	$result = db_query($sql);	
	$row = db_fetch_assoc($result);
	$owner = $row['name'];
	}else{
		$owner = "No One";
	}
		$amulet[$i] = ucfirst($amulet[$i]);
		if ($amulet[$i] == "Dragon") $amulet[$i] = "Flying Dragon";
		if ($amulet[$i] == "Star") $amulet[$i] = "Star of Solomon";
		output("`@%s Amulet - `2%s`n",$amulet[$i],$owner);
	}
	output("`c");
	addnav("Back to HOF","hof.php");
	villagenav();
	page_footer();
}
?>