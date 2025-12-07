<?php
require_once(__DIR__.'/config.php');
$shell = new COM("WScript.Shell");
$lnkFile=CNF['browser'].'.lnk';
$shortcut = $shell->CreateShortcut(realpath($lnkFile));
$shortcut->Arguments = ' -profile "'.realpath('.').'\\'.CNF['profile_path'].'" -CreateProfile "yt" -no-remote';
$shortcut->Save();
