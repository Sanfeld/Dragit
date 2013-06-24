<?php
function lostruins_case6(){
	global $session;
	output("`7While you search carefully, you suddenly find the world start to blur...`n`nYou've stepped into a `#T`@r`!a`%n`\$s`2p`4o`6r`^t`1a`5t`3i`Qo`qn `@V`&o`%r`\$t`!e`#x`7!");
	blocknav("village.php");
	blocknav("runmodule.php?module=lostruins&op=explore");
	switch(e_rand(1,6)){
		case 1:
			if (is_module_active("hiklit")){
				addnav("V`&o`%r`\$t`!e`#x","runmodule.php?module=hiklit&op=books","hiklit");
				$session['user']['location']=get_module_setting("comicloc","hiklit");
			}else addnav("V`&o`%r`\$t`!e`#x","forest.php");
		break;
		case 2:
			if (is_module_active("dianfall")) {
				addnav("V`&o`%r`\$t`!e`#x","runmodule.php?module=dianfall&op=enter","dianfall");
				$session['user']['location']=get_module_setting("waterloc","dianfall");
			}else addnav("V`&o`%r`\$t`!e`#x","forest.php");
		break;
		case 3:
			if (is_module_active("romanbath")){
				addnav("V`&o`%r`\$t`!e`#x","runmodule.php?module=romanbath","romanbath");
				$session['user']['location']=get_module_setting("bathloc","romanbath");
			}else addnav("V`&o`%r`\$t`!e`#x","forest.php");
		break;
		case 4:
			if (is_module_active("sweets")){
				addnav("V`&o`%r`\$t`!e`#x","runmodule.php?module=sweets&op=talk","sweets");
				$session['user']['location']=get_module_setting("sweetloc","sweets");
			}else addnav("V`&o`%r`\$t`!e`#x","forest.php");
		break;
		case 5:
			if (is_module_active("orchard")){
				addnav("V`&o`%r`\$t`!e`#x","runmodule.php?module=orchard&op=explore","orchard");
				$session['user']['location']=get_module_setting("orchardloc","orchard");
			}else addnav("V`&o`%r`\$t`!e`#x","forest.php");
		break;
		case 6:
			addnav("V`&o`%r`\$t`!e`#x","forest.php");
		break;
	}
}
?>