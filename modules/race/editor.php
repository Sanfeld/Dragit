<?php
rawoutput("<table width='100%' border='0' cellpadding='0' cellspacing='0'>");
	rawoutput("<tr class='trhead'><td>");
		rawoutput("<table width='100%' border='0' cellpadding='2' cellspacing='2'>");
			rawoutput("<tr class='trhead'><td>");
				output("`bRaces`b");
			rawoutput("</td></tr>");
		rawoutput("</table>");
	rawoutput("</td></tr>");
	rawoutput("<tr class='trlight'><td>");
		rawoutput("<table width='100%' border='0' cellpadding='2' cellspacing='2'>");
			rawoutput("<tr class='trhead'>");
				rawoutput("<td>");
					output("Options");
				rawoutput("</td><td>");
					output("Formal Name");
				rawoutput("</td><td>");
					output("Location");
				rawoutput("</td><td>");
					output("Dragon Kills");
				rawoutput("</td>");
			rawoutput("</tr>");
		$result = db_query("SELECT * FROM " .
			db_prefix("races"));
		for ($i=0; $i<db_num_rows($result); $i++) 
		{
			$race = db_fetch_assoc($result);
			rawoutput("<tr class='trlight'>");
				rawoutput("<td>");
					rawoutput("<a href='runmodule.php?module=race&op=edit&race={$race['basename']}'>[ Edit ]</a>");
						addnav("","runmodule.php?module=race&op=edit&race={$race['basename']}");
					rawoutput("<a href='runmodule.php?module=race&op=del&race={$race['basename']}'>[ Delete ]</a>");
						addnav("","runmodule.php?module=race&op=del&race={$race['basename']}");
				rawoutput("</td><td>");
					output($race['formalname']);
				rawoutput("</td><td>");
					output($race['location']);
				rawoutput("</td><td>");
					output($race['dragonkills']);
				rawoutput("</td>");
			rawoutput("</tr>");
		}
		rawoutput("</table>");
	rawoutput("</td></tr>");
rawoutput("</table>");
?>