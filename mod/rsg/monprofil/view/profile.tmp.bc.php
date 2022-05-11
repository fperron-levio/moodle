<?php
require_once __DIR__.'/../../../../auth/rsg/classes/RSGUser.php';
global $USER, $CFG;
$rsg= new \auth\rsg\RSGUser($USER,false);

//var_dump($data_certs);
//die();
echo "Vous n'avez pas accès à cette fonctionnalité.";
?>



