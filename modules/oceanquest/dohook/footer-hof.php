<?php
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['unlockdecree']==1 && ($allprefs['piece2']==""||$allprefs['piece2']==0)){
		addnav("Additional Documentation");
		addnav("Peruse Documents","runmodule.php?module=oceanquest&op=documents");
	}
	if (get_module_setting("tradehof")==1){
		addnav("Warrior Rankings");
		addnav("Traders","runmodule.php?module=oceanquest&op=hof&op2=2");
	}
?>