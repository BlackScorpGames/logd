<?php
output::doOutput("`\$%s`) is impressed with your actions, and grants you the power to haunt a foe.`n`n",$deathoverlord);
$search = translator::translate_inline("Search");
rawoutput("<form action='graveyard.php?op=haunt2' method='POST'>");
output::addnav("","graveyard.php?op=haunt2");
output::doOutput("Who would you like to haunt? ");
rawoutput("<input name='name' id='name'>");
rawoutput("<input type='submit' class='button' value='$search'>");
rawoutput("</form>");
rawoutput("<script language='JavaScript'>document.getElementById('name').focus()</script>");
output::addnav("Places");
output::addnav("S?Land of the Shades","shades.php");
output::addnav("G?The Graveyard","graveyard.php");
output::addnav("M?Return to the Mausoleum","graveyard.php?op=enter");
