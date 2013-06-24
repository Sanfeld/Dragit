<?php
function lostruins_pluck(){
	output("`n`c`b`^P`&lucking `^F`&lowers`c`b`n");
	output("`7You pluck the last petal and you find out...`n`n");
	switch(e_rand(1,2)){
		case 1:
			output("`$ Oh No!`# Loves you Not!`n`n`7 This is just going to cast a shadow over the rest of your day. I'm sorry!");
			apply_buff('lovenot',array(
				"name"=>"`%F`^l`%o`^w`%e`^r `&No-`^So`&-Power",
				"rounds"=>50,
				"wearoff"=>"`%Why were you letting a flower get you down?  Move on with life!",
				"defmod"=>.97,
				"roundmsg"=>"`5You think back to the prophesy of the flower and it makes you sad.",
			));
		break;
		case 2:
			output("`@ YES!!");
			output("`\$Loves You!");
			output("`n`n `7 This is just going to brighten the rest of your day.");
			apply_buff('loveyou',array(
				"name"=>"`%F`^l`%o`^w`%e`^r `&Power",
				"rounds"=>50,
				"wearoff"=>"`%Yes... you'll never forget that love is forever if a flower tells you it's so!",
				"defmod"=>1.07,
				"roundmsg"=>"`5You think back to the prophesy of the flower and it makes you happy.",
			));
		break;
	}
}
?>