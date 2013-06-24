<?php
	if ($session['user']['superuser'] & SU_EDIT_USERS){
		$id=httpget('userid');
		addnav("Village Modules");
		addnav("Lost Ruins","runmodule.php?module=lostruins&op=superuser&subop=edit&userid=$id");
	}
?>