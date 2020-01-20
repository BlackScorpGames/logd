<?php
	page_header("Clan Halls");
	output::addnav("Clan Options");
	output::doOutput("`b`c`&Clan Halls`c`b");
	output::doOutput("You stroll off to the side where there are some plush leather chairs, and take a seat.");
	output::doOutput("There are several other warriors sitting here talking amongst themselves.");
	output::doOutput("Some Ye Olde Muzak is coming from a fake rock sitting at the base of a potted bush.`n`n");
	commentdisplay("", "waiting","Speak",25);
	if ($session['user']['clanrank']==CLAN_APPLICANT) {
		output::addnav("Return to the Lobby","clan.php");
	} else {
		output::addnav("Return to your Clan Rooms","clan.php");
	}
