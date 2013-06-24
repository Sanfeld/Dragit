<?php
function lostruins_dip(){
	require_once("lib/showform.php");
	output("`n`c`b`^Lemonade Stand`c`b`n");
	output("`7You decide to make`^ lemonade`7!");
	output("You grab a bucket of water and head back to the village.`n`n");
	if (is_module_active("weather")) output("`^Today's weather is: %s`n`n",get_module_setting("weather","weather"));
	output("`&Choose the following:");
	rawoutput("<br><form action='runmodule.php?module=lostruins&op=lemonade' method='post'>");
	$stuff1 = array("lemons"=>"How many lemons?,range,1,5,1|1",);
	$b1 = array("lemons"=>1,);
	showform($stuff1,$b1,true);
	$stuff2 = array("sugar"=>"How many pounds of sugar?,range,1,3,1|1",);
	$b2 = array("sugar"=>1,);
	showform($stuff2,$b2,true);
	$stuff3 = array("cups"=>"How many cups?,range,10,50,1|10",);
	$b3 = array("cups"=>1,);
	showform($stuff3,$b3,true);
	$stuff4 = array("signs"=>"How many signs?,range,1,4,1|1",);
	$b4 = array("signs"=>1,);
	showform($stuff4,$b4,true);
	$stuff5 = array("price"=>"Gold Price per Glass?,range,10,50,1|10",);
	$b5 = array("price"=>1,);
	showform($stuff5,$b5,true);
	$b5 = translate_inline("Purchase!");
	rawoutput(" <input type='submit' class='button' value='$b5'></form>");
	addnav("","runmodule.php?module=lostruins&op=lemonade");
	addnav("Purchase","runmodule.php?module=lostruins&op=lemonade");
}
?>