<?php
// translator ready
// addnews ready
// mail ready
function superusernav()
{
	global $SCRIPT_NAME, $session;
	translator::tlschema("nav");
	output::addnav("Navigation");
	if ($session['user']['superuser'] &~ SU_DOESNT_GIVE_GROTTO) {
		$script = substr($SCRIPT_NAME,0,strpos($SCRIPT_NAME,"."));
		if ($script != "superuser") {
			$args = modules::modulehook("grottonav");
			if (!array_key_exists('handled',$args) || !$args['handled']) {
				output::addnav("G?Return to the Grotto", "superuser.php");
			}
		}
	}
	$args = modules::modulehook("mundanenav");
	if (!array_key_exists('handled',$args) || !$args['handled'])
		output::addnav("M?Return to the Mundane", "village.php");
	translator::tlschema();
}
