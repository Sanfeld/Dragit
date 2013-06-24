<?php
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['bait']>0) $allprefs['bait']=0;
	if ($allprefs['healed']==3) $allprefs['healed']=4;
	if ($allprefs['outhouse']==1) $allprefs['outhouse']=0;
	set_module_pref('allprefs',serialize($allprefs));
?>