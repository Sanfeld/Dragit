<?php
			addnav("Back to the Vault","runmodule.php?module=clanvault&op=enter");
			if (httpget('action')=='on') {
				set_module_pref('showNot',1);
				output("`%`b`cNotifications are ON.`c`b`n");
			} elseif (httpget('action')=='off') {
				set_module_pref('showNot',0);
				output("`%`b`cNotifications are OFF.`c`b`n");
			}
			output("`@This is where you can choose to show `^Ye Olde Mail`@ notifications.`n");
			addnav("Actions");
			if (get_module_pref('showNot','clanvault')==1) {
				addnav("`@Toggle notifications `\$OFF","runmodule.php?module=clanvault&op=preference&action=off");
			} else {
				addnav("`@Toggle notifications `\$ON","runmodule.php?module=clanvault&op=preference&action=on");
			}
?>