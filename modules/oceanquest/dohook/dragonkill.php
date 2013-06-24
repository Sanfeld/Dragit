<?php
	//resets on newday if set for that
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['pole']=0;
	$allprefs['dktrades']=0;
	set_module_pref('allprefs',serialize($allprefs));
?>