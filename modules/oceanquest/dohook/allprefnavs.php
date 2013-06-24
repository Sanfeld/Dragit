<?php
	$id=httpget('userid');
	addnav("Forest Modules");
	addnav("Ocean Quest","runmodule.php?module=oceanquest&op=superuser&subop=edit&userid=$id");
?>