<?php
// translator ready
// addnews ready
// mail ready
require_once("common.php");
require_once("lib/commentary.php");


translator::tlschema("shades");

page_header("Land of the Shades");
addcommentary();
checkday();

if ($session['user']['alive']) redirect("village.php");
output::doOutput("`\$You walk among the dead now, you are a shade. ");
output::doOutput("Everywhere around you are the souls of those who have fallen in battle, in old age, and in grievous accidents. ");
output::doOutput("Each bears telltale signs of the means by which they met their end.`n`n");
output::doOutput("Their souls whisper their torments, haunting your mind with their despair:`n");

output::doOutput("`nA sepulchral voice intones, \"`QIt is now %s in the world above.`\$\"`n`n",getgametime());
modules::modulehook("shades", array());
commentdisplay("`n`QNearby, some lost souls lament:`n", "shade","Despair",25,"despairs");

output::addnav("Log out","login.php?op=logout");
output::addnav("Places");
output::addnav("The Graveyard","graveyard.php");

output::addnav("Return to the news","news.php");

translator::tlschema("nav");

// the mute module blocks players from speaking until they
// read the FAQs, and if they first try to speak when dead
// there is no way for them to unmute themselves without this link.
output::addnav("Other");
output::addnav("??F.A.Q. (Frequently Asked Questions)", "petition.php?op=faq",false,true);

if ($session['user']['superuser'] & SU_EDIT_COMMENTS){
	output::addnav("Superuser");
	output::addnav(",?Comment Moderation","moderate.php");
}
if ($session['user']['superuser']&~SU_DOESNT_GIVE_GROTTO){
	output::addnav("Superuser");
  output::addnav("X?Superuser Grotto","superuser.php");
}
if ($session['user']['superuser'] & SU_INFINITE_DAYS){
	output::addnav("Superuser");
  output::addnav("/?New Day","newday.php");
}

translator::tlschema();

page_footer();
