<?php
	global $session;
	if (e_rand(1,100) <= get_module_setting("findperc")){
		if (get_module_pref("amulet") == ""){
			output("`@The land grows dim, you look above you and the sky fills with dark rolling clouds.`n");
			output("As you stand there the clouds part and a flash of light comes from the sky toward you!`n");
			output("`@You hear the voice of %s`@.  `2\"I am the God of Amulets, do you think you are worthy mortal?\"`n`@",get_module_setting("godname"));
			$which = e_rand(1,20);
			switch($which){
				case 1:
					output("`#The Shamrock Amulet Appears before you!`n");
					if (get_module_setting("shamrock") < 1){
						amulets_giveamulet("shamrock");
					}else{
						$name = amulets_getowner("shamrock");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("shamrock",$name);
						}else{
							amulets_nogive("shamrock",$name);
						}
					}
				break;
				case 2:
					output("`#The Triquetra Amulet Appears before you!`n");
					if (get_module_setting("triquetra") < 1){
						amulets_giveamulet("triquetra");
					}else{
						$name = amulets_getowner("triquetra");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("triquetra",$name);
						}else{
							amulets_nogive("triquetra",$name);
						}
					}
				break;
				case 3:
					output("`#The Heart Amulet Appears before you!`n");
					if (get_module_setting("heart") < 1){
						amulets_giveamulet("heart");
					}else{
						$name = amulets_getowner("heart");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("heart",$name);
						}else{
							amulets_nogive("heart",$name);
						}
					}
				break;
				case 4:
					output("`#The Cross Amulet Appears before you!`n");
					if (get_module_setting("cross") < 1){
						amulets_giveamulet("cross");
					}else{
						$name = amulets_getowner("cross");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("cross",$name);
						}else{
							amulets_nogive("cross",$name);
						}
					}
				break;
				case 5:
					output("`#The Ankh Amulet Appears before you!`n");
					if (get_module_setting("ankh") < 1){
						amulets_giveamulet("ankh");
					}else{
						$name = amulets_getowner("ankh");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("ankh",$name);
						}else{
							amulets_nogive("ankh",$name);
						}
					}
				break;
				case 6:
					output("`#The Pegasus Amulet Appears before you!`n");
					if (get_module_setting("pegasus") < 1){
						amulets_giveamulet("pegasus");
					}else{
						$name = amulets_getowner("pegasus");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("pegasus",$name);
						}else{
							amulets_nogive("pegasus",$name);
						}
					}
				break;
				case 7:
					output("`#The Unicorn Amulet Appears before you!`n");
					if (get_module_setting("unicorn") < 1){
						amulets_giveamulet("unicorn");
					}else{
						$name = amulets_getowner("unicorn");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("unicorn",$name);
						}else{
							amulets_nogive("unicorn",$name);
						}
					}
				break;
				case 8:
					output("`#The Phoenix Amulet Appears before you!`n");
					if (get_module_setting("phoenix") < 1){
						amulets_giveamulet("phoenix");
					}else{
						$name = amulets_getowner("phoenix");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("phoenix",$name);
						}else{
							amulets_nogive("phoenix",$name);
						}
					}
				break;
				case 9:
					output("`#The Flying Dragon Amulet Appears before you!`n");
					if (get_module_setting("dragon") < 1){
						amulets_giveamulet("dragon");
					}else{
						$name = amulets_getowner("dragon");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("dragon",$name);
						}else{
							amulets_nogive("dragon",$name);
						}
					}
				break;
				case 10:
					output("`#The YinYang Amulet Appears before you!`n");
					if (get_module_setting("yinyang") < 1){
						amulets_giveamulet("yinyang");
					}else{
						$name = amulets_getowner("yinyang");;
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("yinyang",$name);
						}else{
							amulets_nogive("yinyang",$name);
						}
					}
				break;
				case 11:
					output("`#The Artemis Amulet Appears before you!`n");
					if ($session['user']['sex'] == 1){
						if (get_module_setting("artemis") < 1){
							amulets_giveamulet("artemis");
						}else{
							$name = amulets_getowner("artemis");
							if (e_rand(1,100) <= get_module_setting("takeperc")){
								amulets_takeamulet("artemis",$name);
							}else{
								amulets_nogive("artemis",$name);
							}
						}
					}else{
						output("Ah, but the Artemis Amulet is for the feminine gender.  It fades as quickly as it appears.`n");
					}
				break;
				case 12:
					output("`#The Horace Amulet Appears before you!`n");
					if (get_module_setting("horace") < 1){
						amulets_giveamulet("horace");
					}else{
						$name = amulets_getowner("horace");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("horace",$name);
						}else{
							amulets_nogive("horace",$name);
						}
					}
				break;
				case 13:
					output("`#The Star of Solomon Amulet Appears before you!`n");
					if (get_module_setting("star") < 1){
						amulets_giveamulet("star");
					}else{
						$name = amulets_getowner("star");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("star",$name);
						}else{
							amulets_nogive("star",$name);
						}
					}
				break;
				case 14:
					output("`#The Salamander Amulet Appears before you!`n");
					if (get_module_setting("salamander") < 1){
						amulets_giveamulet("salamander");
					}else{
						$name = amulets_getowner("salamander");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("salamander",$name);
						}else{
							amulets_nogive("salamander",$name);
						}
					}
				break;
				case 15:
					output("`#The Bastet Amulet Appears before you!`n");
					if ($session['user']['sex'] == 1){
						if (get_module_setting("bastet") < 1){
							amulets_giveamulet("bastet");
						}else{
							$name = amulets_getowner("bastet");
							if (e_rand(1,100) <= get_module_setting("takeperc")){
								amulets_takeamulet("bastet",$name);
							}else{
								amulets_nogive("bastet",$name);
							}
						}
					}else{
						output("Ah, but the Bastet Amulet is for the feminine gender.  It fades as quickly as it appears.`n");
					}
				break;
				case 16:
					output("`#The Thor Amulet Appears before you!`n");
					if ($session['user']['sex'] == 0){
						if (get_module_setting("thor") < 1){
							amulets_giveamulet("thor");
						}else{
							$name = amulets_getowner("thor");
							if (e_rand(1,100) <= get_module_setting("takeperc")){
								amulets_takeamulet("thor",$name);
							}else{
								amulets_nogive("thor",$name);
							}
						}
					}else{
						output("Ah, but the Thor Amulet is for the masculan gender.  It fades as quickly as it appears.`n");
					}
				break;
				case 17:
					output("`#The Anubis Amulet Appears before you!`n");
					if (get_module_setting("anubis") < 1){
						amulets_giveamulet("anubis");
					}else{
						$name = amulets_getowner("anubis");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("anubis",$name);
						}else{
							amulets_nogive("anubis",$name);
						}
					}
				break;
				case 18:
					output("`#The Apollo Amulet Appears before you!`n");
					if (get_module_setting("apollo") < 1){
						amulets_giveamulet("apollo");
					}else{
						$name = amulets_getowner("apollo");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("apollo",$name);
						}else{
							amulets_nogive("apollo",$name);
						}
					}
				break;
				case 19:
					output("`#The Dionysos Amulet Appears before you!`n");
					if (get_module_setting("dionysos") < 1){
						amulets_giveamulet("dionysos");
					}else{
						$name = amulets_getowner("dionysos");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("dionysos",$name);
						}else{
							amulets_nogive("dionysos",$name);
						}
					}
				break;
				case 20:
					output("`#The Hermes Amulet Appears before you!`n");
					if (get_module_setting("hermes") < 1){
						amulets_giveamulet("hermes");
					}else{
						$name = amulets_getowner("hermes");
						if (e_rand(1,100) <= get_module_setting("takeperc")){
							amulets_takeamulet("hermes",$name);
						}else{
							amulets_nogive("hermes",$name);
						}
					}
				break;
			}
			amulets_dohook("newday",$args);
		}else{
			if ($type == "forest"){
				redirect("forest.php?op=search");
			}else{
				redirect("runmodule.php?module=cities&op=travel");
			}
		}
	}else{
		if ($type == "forest"){
			redirect("forest.php?op=search");
		}else{
			redirect("runmodule.php?module=cities&op=travel");
		}
	}
?>