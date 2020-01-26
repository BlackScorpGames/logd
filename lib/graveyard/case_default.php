<?php
if (!$skipgraveyardtext) {
	output::doOutput("`)`c`bThe Graveyard`b`c");
	output::doOutput("Your spirit wanders into a lonely graveyard, overgrown with sickly weeds which seem to grab at your spirit as you float past them.");
	output::doOutput("Around you are the remains of many broken tombstones, some lying on their faces, some shattered to pieces.");
	output::doOutput("You can almost hear the wails of the souls trapped within each plot lamenting their fates.`n`n");
	output::doOutput("In the center of the graveyard is an ancient looking mausoleum which has been worn by the effects of untold years.");
	output::doOutput("A sinister looking gargoyle adorns the apex of its roof; its eyes seem to follow  you, and its mouth gapes with sharp stone teeth.");
	output::doOutput("The plaque above the door reads `\$%s`), Overlord of Death`).",$deathoverlord);
	modules::modulehook("graveyard-desc");
}
modules::modulehook("graveyard");
	if ($session['user']['gravefights']) {
	output::addnav("Look for Something to Torment","graveyard.php?op=search");
}
output::addnav("Places");
output::addnav("W?List Warriors","list.php");
output::addnav("S?Return to the Shades","shades.php");
output::addnav("M?Enter the Mausoleum","graveyard.php?op=enter");
module_display_events("graveyard", "graveyard.php");
