<?php
// addnews ready
// mail ready
// translator ready
require_once("common.php");
require_once("lib/http.php");
require_once("lib/showform.php");

$op = http::httpget('op');
$id = http::httpget('id');

$mount = new blackscorp\logd\Mount\Mount();
if ($id)
    $mount = $mountRepository->findMount($id);

if ($op=="xml") {
	header("Content-Type: text/xml");
	$sql = "select name from " . db_prefix("accounts") . " where hashorse=$id";
	$r = db_query($sql);
	echo("<xml>");
	while($row = db_fetch_assoc($r)) {
		echo("<name name=\"");
		echo(urlencode(appoencode("`0{$row['name']}")));
		echo("\"/>");
	}
	if (db_num_rows($r) == 0) {
		echo("<name name=\"" . translator::translate_inline("NONE") . "\"/>");
	}
	echo("</xml>");
	exit();
}


check_su_access(SU_EDIT_MOUNTS);

translator::tlschema("mounts");

page_header("Mount Editor");

require_once("lib/superusernav.php");
superusernav();

addnav("Mount Editor");
addnav("Add a mount","mounts.php?op=add");

$id = (int)$id;

$route =    [
                'deactivate'    => ['action' => 'deactivateMount', 'args' => $mount],
                'activate'      => ['action' => 'activateMount', 'args' => $mount],
                'del'           => ['action' => 'deleteMount', 'args' => $id],
                'give'          => ['action' => 'giveMountUser', 'args' => $id],
                'save'          => ['action' => 'saveMount', 'args' => $id],
                'add'           => ['action' => 'addMount', 'args' => null],
                'edit'          => ['action' => 'editMount', 'args' => $id],
                '/'             => ['action' => 'defaultAction', 'args' => null],
            ];
if ($op=="")
    $op ='/';

$mountController->{$route[$op]['action']}($route[$op]['args']);

page_footer();
