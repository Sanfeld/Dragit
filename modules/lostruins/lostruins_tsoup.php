<?php
function lostruins_tsoup(){
	if (is_module_active('alignment')) increment_module_pref("alignment",-2,"alignment");
	output("`n`c`b`@T`2urtle `@S`2oup`c`b`n");
	output("`7You give the cute little `@turtle`7 an `$ evil look `7and lick your lips.");
	output("How hard can it be to cook a good`@ turtle soup`7?`n`n");
	switch(e_rand(1,2)){
		case 1:
			output("`7Turns out to be a little harder than you thought.");
			output("You take a sip of your `@turtle soup`7 and get `@turtle`$ poisoning`7!!");
			output("Owww, your aching stomach!`n`n");
			apply_buff('badsoup',array(
				"name"=>"`5Tummy Ache",
				"rounds"=>5,
				"maxgoodguydamage"=>$session['user']['level'],
				"wearoff"=>"`%You feel like the `@turtle `%has forgiven you and your stomach feels better.",
				"defmod"=>.93,
				"roundmsg"=>"`5The `@turtle's revenge`5 causes your attack to be less effective.",
				"activate"=>"offense"
			));
		break;
		case 2:
			output("`7Turns out to be as easy as you thought.");
			output("You eat some of the best `@turtle soup`7 you've ever had.");
			apply_buff('goodsoup',array(
				"name"=>"`@Turtle Soup Power",
				"rounds"=>10,
				"wearoff"=>"`%You will always remember how good that soup was!",
				"defmod"=>1.2,
				"activate"=>"offense"
			));
		break;
	}
}
?>