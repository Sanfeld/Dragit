<?php
	if ($session['user']['superuser'] & SU_EDIT_USERS){
		$id=httpget('userid');
		addnav("Forest Modules");
		addnav("Ocean Quest","runmodule.php?module=oceanquest&op=superuser&subop=edit&userid=$id");
	}
?>