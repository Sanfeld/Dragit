<?php
function lostruins_trip(){
	global $session;
	output("`n`c`b`#Tours of the Fountain of Youth`c`b`n");
	output("`7You quickly set up a little tour guide business and get ready to reap the profits!`n`n");
	switch(e_rand(1,3)){
		case 1:
			output("`7You set up signs all over at local school yards and daycares.");
			output("However, you just can't get the people to come.");
			output("It's sad but true.");
			output("Your forgot the first rule of marketing:");
			output("Know your target audience.`n`n");
			output("The silly endeavor cost you");
			if ($session['user']['gold']<1000){
				output("`^ all your gold`7.");
				$session['user']['gold']=0;
			}elseif ($session['user']['gold']>=1000){
				output("`^ 1000 gold`7.");
				$session['user']['gold']-=1000;
			}
			output("Oh well, you've learned a lesson.`n`n");
		break;
		case 2:
			$gold=get_module_setting("case11g2");
			output("`7You set up signs all over at local retirement homes and senior centers.");
			output("You nailed the first rule of marketing - Know your target audience!");
			output("The profits come streaming in!");
			output("`n`nYou make `^%s gold`7!",$gold);
			$session['user']['gold']+=$gold;
		break;
		case 3:
			output("`7You bring a large tour group to the spring with your professionally painted `#'Fountain of Youth'`7 sign.");
			output("`n`nYou take a big gulp in order to show how wonderful the water tastes.");
			output("The people look at you and start laughing.");
			output("`n`nFinally, one of the group pipes up saying `%'You silly ninny! that's not the fountain of youth!");
			output("That's the sewer drain from the`& King's Castle`%!'`7");
			output("`n`nWell, isn't this embarrassing?");
			output("You refund all the money, close up shop, and sadly read all about it in the daily news.");
			addnews("`^Hear Ye! Hear Ye!  %s`^ was seen drinking sewer water in the `5A`6ncient `5R`6uins`^!!",$session['user']['name']);
		break;
	}
}
?>