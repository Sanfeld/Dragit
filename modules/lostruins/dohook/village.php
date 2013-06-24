<?php
	if (get_module_setting("limitloc")==0 || (get_module_setting("limitloc")==1 && $session['user']['location'] == get_module_setting("ruinsloc"))){
		tlschema($args["schemas"]["gatenav"]);
		addnav($args["gatenav"]);
		tlschema();
		addnav("Lost Ruins","runmodule.php?module=lostruins&op=enter");
	}
?>