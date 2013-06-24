<?php
	$op = httpget('op');
	$allprefs=unserialize(get_module_pref('allprefs'));
	if (($op=="" && $allprefs['piece1']==1 && $allprefs['piece2']==1 && $allprefs['piece3']==1 && $allprefs['piece4']==1 && $allprefs['healed']==2)||$allprefs['healed']==3||$allprefs['healed']==4){
		addnav("Specials");
		addnav("Paper Healing","runmodule.php?module=oceanquest&op=healpaper");
		if ($allprefs['healed']==""||$allprefs['healed']==0) output("`2Paper Healing Special! `3Paper Healing`7 costs `61000 gold`7. It takes a day to complete.`n`n");
	}
?>