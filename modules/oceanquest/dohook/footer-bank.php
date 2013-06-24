<?php
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['healed']==1 && ($allprefs['notary']==""||$allprefs['notary']==0)){
		addnav("Notarize");
		addnav("Speak to Notary","runmodule.php?module=oceanquest&op=notarize");
	}
?>