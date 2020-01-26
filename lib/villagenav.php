<?php
// translator ready
// addnews ready
// mail ready
function villagenav($extra=false)
{
	global $session;
	$loc = $session['user']['location'];
	if ($extra === false) $extra="";
	$args = modules::modulehook("villagenav");
	if (array_key_exists('handled', $args) && $args['handled']) return;
	translator::tlschema("nav");
	if ($session['user']['alive']) {
		output::addnav(array("V?Return to %s", $loc), "village.php$extra");
	} else {
		// user is dead
		output::addnav("S?Return to the Shades","shades.php");
	}
	translator::tlschema();
}
