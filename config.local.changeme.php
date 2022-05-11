<?php  // Moodle configuration locale

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'Host de la base de données';
$CFG->dbname    = 'Nom de la base de données';
$CFG->dbuser    = 'Utilisateur de la base de données';
$CFG->dbpass    = 'Mot d epass de l\'utilisateur de la base de données';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
);

$CFG->wwwroot   = 'Url de l\'instance Moodle';
$CFG->dataroot  = 'Chemin vers le dossier du data de Moodle';

// server.http
/* le proxy doit être désactivé pour les sites sur les serveurs de la DRI */
$CFG->getremoteaddrconf = 0; /* x-forwarded-for */
$CFG->proxyhost = '';
$CFG->proxyport = '0';

// security.anti-virus
# $CFG->runclamonupload = 1;
# $CFG->pathtoclam = '';
# $CFG->quarantinedir = '';

// plugins.message outputs.email
$CFG->smtphosts = 'Smtp du serveur mail de rsgenligne';#'smtp.rsgenligne.ca:587';

// Rsg email contact - défini l'adresse de courriel de la personne contact pour les formulaire Nous joindre
$CFG->rsg_email_contact = 'info@rsgenligne.ca';
// RSG synchronisation contact - responsable(s) de la mise à jour des RSG abonné(e)s
$CFG->rsg_synch_contact = '';

/* Protection supplémentaire. Ne devrait jamais être activé en production. */
$CFG->themedesignermode = false; 

// Configuration pour le debugging - NE PAS METTRE SUR LA PRODUCTION!
//@ini_set('display_errors', '1');
//$CFG->debug = 32767;
//$CFG->debugdisplay = true;
//error_reporting(E_ALL | E_STRICT);
//$CFG->disableupdateautodeploy = true;


// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
