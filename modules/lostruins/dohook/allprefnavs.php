<?php
	$id=httpget('userid');
	addnav("Village Modules");
	addnav("Lost Ruins","runmodule.php?module=lostruins&op=superuser&subop=edit&userid=$id");
?>