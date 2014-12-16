#!/usr/bin/php
<?php
require_once('show.php');
$sync = null;
if ($argv[3] != "nosync")
	$sync = $argv[3];
$tvshow = \CFPropertyList\WatchShow::factory($argv[2], $sync);
switch ($argv[1]) {
	case 'watch':
		if ($argv[2]=="all")
			echo $tvshow->listInAlfred(true);
		else
			echo $tvshow->listInAlfred();
		break;
		
	case 'finish':
		echo $tvshow->listInAlfred();
		break;
		
	case 'watched':
		if ($argv[2]=="undo")
			echo $tvshow->undo();
		else
			echo $tvshow->watched();
		break;
		
	case 'finished':
		$ep = $tvshow->finished();
		echo $ep;
		break;
		
	default:
		break;
}