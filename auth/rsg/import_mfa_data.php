<?php
/**
 * @author Marc-Etienne Leblanc <meleblanc@crosemont.qc.ca>
 * Date: 9/02/17
 * @author Mamadou Kane <mkane@crosemont.qc.ca>
 * @author Andrei Boris <aboris@crosemont.qc.ca>
 * Date: 2018-01-22
 * Rôles: 
 *    1. lire les enregistrements de RSG représentées dans le fichier CSV
 *    2. insérer ou mettre à jour les enregistrements existants dans la table de la BD (mdl_rsg_mfa_import)
 */

define('CLI_SCRIPT', true);
define('CSV_FICHIER', '/home/mfa_rsg/rsgdoc/rsg_mfa_import.csv');
define('CSV_SUCCESS', '/home/mfa_rsg/rsgdoc/rsg_mfa_import_PROCESSED.csv');
define('MIN_ALLOWED', 50); // le nombre minimal de paires soumis dans le fichier CSV
define('GRACELENGTH', 29*24*60*60); // le période de grâce en secondes
 
require_once(__DIR__ . '/../../config.php');

ini_set('memory_limit','256M');

GLOBAL $DB, $CFG;


// Configuration pour le debugging - NE PAS METTRE SUR LA PRODUCTION!
@ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);
$CFG->debug = 32767;
$CFG->debugdisplay = true;
error_reporting(E_ALL | E_STRICT);

/* ********************************************************************* */
/* Vu que la fonction 'email_to_user' de Moodle exige que l'usager soit  */
/* un utilisateur enregistré on utilisera la fonction PHP 'mail'         */
/* ********************************************************************* */
$bodyMessage = '';
$erroMessage = '';
// s'assurer que le message parvient aux personnes intéressés
if (isset($CFG->rsg_synch_contact) && !empty($CFG->rsg_synch_contact)) {
	$to = $CFG->rsg_synch_contact;
}
else {
	$to = 'aboris@crosemont.qc.ca, tnguyen@crosemont.qc.ca'; // "aboris@crosemont.qc.ca,tnguyen@cegepadistance.ca";
	$bodyMessage .= "Le fichier de configuration manque les adresses de courriel destinataires.\n";
}
$subject = 'Importation des RSG ' . date("Y-m-d") . ' : ';
$sender = 'RSG en ligne <nepasrepondre@rsgenligne.ca>';
// on tâche de mettre à jour les identifiants d'expéditeur 
$supportuser = core_user::get_support_user();
if(!empty($supportuser) && !empty($supportuser->firstname) && !empty($supportuser->email)) {
	$sender = $supportuser->firstname . ' <' . $supportuser->email . '>';	
}
$headers = 'From: ' . $sender . "\r\n" .
	'X-Mailer: PHP/' . phpversion() . "\r\n" .
	'Content-Type: text/html; charset=UTF-8';

function sendEmail($to, $subject, $bodyMessage, $headers) {
	if (!mail($to, $subject, $bodyMessage, $headers)) {
		echo("\nERREUR: Le courriel n'a pas été envoyé!\n");
	}
}

$ftpRSG = array();
$errRSG = array();

// on quitte en silence si le fichier manque...
if (!file_exists(CSV_FICHIER)) {
    // $bodyMessage .= "Le fichier " . CSV_FICHIER . " n'existe pas: aucune importation requise.\n"; 
	// $subject .= "Aucune importation requise";
	// sendEmail($to, $subject, nl2br($bodyMessage), $headers);
	exit(0);
}

// Mesurer le temps d'exécution (debut)
$importation_time_start = microtime(true);

/************************************************************************** */
/* Étape 1. Lecture du fichier et validation des données                    */
/************************************************************************** */

// Échec de lecture du fichier
if(($handle = fopen(CSV_FICHIER, 'r')) == FALSE) {
	
	$erroMessage = "Impossible d'ouvrir fichier";
    $bodyMessage .= "ERREUR - incapable d'ouvrir le fichier d'importation du MFA!\n";
	
}
// Tentative de lecture du fichier
else {

	// obtenir un tableau associatif avec les id des bureaux coordonnateur
	$bureauxCoordonnateurs = $DB->get_records_menu(RSG_COORDOFFICE, null, 'id', 'officeid, id');
	
	// numéro d'identification de 2 à 5 chiffres [11845]
	$patternNumeroIdentification = '/^\d{2,5}$/';
	// bureau coordonnateur [7000-6849]
	$patternCoordOfficeId = '/^700[0,1]-\d{4}$/';	
	
	$nbRed = 0; // compteur de lignes lues
	$nbNul = 0; // compteur de lignes vides
	
	while (($data = fgetcsv($handle, 100, ";")) !== FALSE) {
		$nbRed++;
		
		if (!empty(trim($data[0])) && !empty(trim($data[1]))) { // ignore blank lines
			
			// validation du numéro d\'identification
			if(!preg_match($patternNumeroIdentification, $data[0])) {
				$errRSG[$nbRed] = implode(';', $data) . ' [Échec de valalidation du numéro d\'identification]';
			}
			// validation du bureau coordonnateur
			else if (!preg_match($patternCoordOfficeId, $data[1])) {
				$errRSG[$nbRed] = implode(';', $data) . ' [Échec de valalidation du bureau coordonnateur]';
			}
			// validation d'éxistance du bureau coordonnateur
			else if (!array_key_exists($data[1], $bureauxCoordonnateurs)) {
				$errRSG[$nbRed] = implode(';', $data) . ' [Échec d\'éxistance du bureau coordonnateur]';
			}
			// validation de doublons
			else if (array_key_exists($data[0], $ftpRSG)) {
				$errRSG[$nbRed] = implode(';', $data) . ' [Doublon du numéro d\'identification]';
			}
			else {
				$ftpRSG[$data[0]] = $data[1];
			}
			
		}
		else {
			$nbNul++;
		}
		
	}
	fclose($handle);
		
	$bodyMessage .= "Lignes lues : " . $nbRed;
	$bodyMessage .= $nbNul ? "\nLignes vides : " . $nbNul : '';
}

/************************************************************************** */
/* Étape 2. Triages de données en trois tableaux :                          */
/*         - RSG non-existants à insérer                                    */
/*         - RSG existants à mettre à jour                                  */
/*         - RSG à désactiver                                               */
/************************************************************************** */
// S'il y les erreurs de lecture on prépare le message d'erreur
if(!empty($errRSG)) {
		
	$erroMessage .= (count($errRSG) > 1) ? "Erreurs dans le fichier" : "Erreur dans le fichier";
	$bodyMessage .= (count($errRSG) > 1) ? "\n\nLes lignes suivantes comportent l'information erronée :\n" : "\n\nLa ligne suivante comporte l'information erronée :\n";
	foreach ($errRSG as $NumeroLigne => $donnees) {
		$bodyMessage .= '..ligne ' . $NumeroLigne . ' ... ' . $donnees . "\n";
	}
		
}

else {
	
	// aucune données à traiter
	if(empty($ftpRSG)) {
		$bodyMessage .= 'Le fichier ne contient aucune donnée à traiter.';
	}
	// le nombre de RSG à mettre à jour est insuffisant; on présume que le fichier est incomplet
	else if(count($ftpRSG) < MIN_ALLOWED) {

		$erroMessage .= "Fichier incomplet\n";
		$bodyMessage .= "\n\nLe fichier comporte seulement " . count($ftpRSG) . " abonnements à mettre à jour.\n";
		$bodyMessage .= "On présume que le fichier est incomplet (il doit contenir au moins " . MIN_ALLOWED . " abonnements à mettre à jour).";
		
	}
	// sinon on procéde au triage
	else {
				
		$newRSG = array(); 			// les paires non existantes dans la DB et soumis par FTP; à insérer dans la DB
		$fixUpToDateRSG = array();	// les paires déjà existantes dans la DB dont le bureau coordonnateur est le même que celui soumis par FTP; à mettre à jour
		$fixToUpdateRSG = array();	// les paires déjà existantes dans la DB dont le bureau coordonnateur est différent que celui soumis par FTP; à mettre à jour
		$idlRSG = array();			// les paires déjà existantes dans la DB et non soumis par FTP; à désactiver

		// obtenir un tableau associatif des RSG existants dans la BD
		$bdExistants = $DB->get_records_menu(RSG_MFA_IMPORT, null, 'id', 'numeroidentification, coordofficeid');
		
		// chercher les paires qui n'existent pas dans la BD pour les mettre dans la BD
		foreach ($ftpRSG as $ftpNumero => $ftpCoordOfficeId) {
			if (!array_key_exists($ftpNumero, $bdExistants)) {
				$newRSG[$ftpNumero] = $ftpCoordOfficeId;
			}
		}	
		
		// on cherche les paires déjà existants dans la BD pour les mettre à jour dans la BD
		foreach ($bdExistants as $bdNumero => $bdCoordOfficeId) {
			if (array_key_exists($bdNumero, $ftpRSG)) {	
			    // le bureau coordonnateur n'a pas changé
				if($bdCoordOfficeId == $ftpRSG[$bdNumero]) {
					$fixUpToDateRSG[$bdNumero] = $bdCoordOfficeId;
				}
				// le bureau coordonnateur a changé
				else {
					$fixToUpdateRSG[$bdNumero] = $ftpRSG[$bdNumero]; 
				}	
			}
			else {
				$idlRSG[$bdNumero] = $bdCoordOfficeId;
			}
		}

	}

}


/************************************************************************** */
/* Étape 3. Insertion et mise à jour des données dans la BD                 */
/*          a - RSG non-existants à insérer                                 */
/*          b - RSG existants à mettre à jour                               */
/*          c - RSG à désactiver                                            */
/************************************************************************** */

try {
	
	$transaction = $DB->start_delegated_transaction();
	
	// a - RSG non-existants à insérer
	$bodyMessage .= "\n\nInsertion : ";
	if(isset($newRSG) && !empty($newRSG)) {
		
		foreach ($newRSG as $newNumero => $newCoordOfficeId) {
			$new = new stdClass();
			$new->numeroidentification	= $newNumero;
			$new->coordofficeid			= $newCoordOfficeId;
			$new->active				= 1;
			$new->timecreated			= time();
			// il serait intéressant de noter les ID des enregistrements mais ça polluera le message
			$lastinsertid = $DB->insert_record(RSG_MFA_IMPORT, $new, false);					
		}
		$bodyMessage .= count($newRSG) . (count($newRSG)==1 ? ' nouvel abonnement inséré' : ' nouveaux abonnements insérés');

	}
	else {
		$bodyMessage .= " aucun nouvel abonnement.";
	}
	
	// b - RSG existants à mettre à jour 
	$bodyMessage .= "\n\nMise à jour de RSG existants : \n";
	if((isset($fixUpToDateRSG) && !empty($fixUpToDateRSG)) || (isset($fixToUpdateRSG) && !empty($fixToUpdateRSG))) {
	
	    $nbUpdtd = 0;
		$nbToUpd = 0;
		$nbReact = 0;
		$rs = $DB->get_recordset(RSG_MFA_IMPORT, null, '', '*', 0, 0);
		foreach ($rs as $record) {
			
			// mettre à jour RSG actives (et les activer si désactivés) où le bureau coordonnateur est le même
			if (array_key_exists($record->numeroidentification, $fixUpToDateRSG)) {
				if ($record->active==0 || !empty($record->graceend)) {
					$nbReact++;
				}
				$record->active			= 1;
				$record->timemodified	= time();
				// réinitialiser les RSG en période de grâce 
				$record->graceend		= 0;
				$record->gracenotice	= 0;
				$DB->update_record(RSG_MFA_IMPORT, $record);
				$nbUpdtd++;
			}
			
			// mettre à jour RSG actives (et les activer si désactivés) où le bureau coordonnateur est différent
			if (array_key_exists($record->numeroidentification, $fixToUpdateRSG)) {
				if ($record->active == 0 || !empty($record->graceend)) {
					$nbReact++;
				}
				$record->active			= 1;
				$record->timemodified	= time();
				// réinitialiser les RSG en période de grâce 
				$record->graceend		= 0;
				$record->gracenotice	= 0;
				
				$record->coordofficeid	= $fixToUpdateRSG[$record->numeroidentification];
								
				// mettre à jour la table mdl_rsg_inscription si l'usager est déjà inscrit
				$usager = $DB->get_record(RSG_INSCRIPTION, array('numeroidentification'=>$record->numeroidentification));
				if(!empty($usager)) {				
					$usager->coordofficeid	= $bureauxCoordonnateurs[$fixToUpdateRSG[$record->numeroidentification]];				
					$usager->timemodified	= time();
					$DB->update_record(RSG_INSCRIPTION, $usager);
				}
				
				$DB->update_record(RSG_MFA_IMPORT, $record);
				$nbToUpd++;
			}

		}
		$rs->close(); // Don't forget to close the recordset!
		
		$bodyMessage .= ' > le bureau coordonnateur est le même > ' . count($fixUpToDateRSG) . " à mettre à jour ; " . $nbUpdtd . " mis à jour\n";	
		$bodyMessage .= ' > le bureau coordonnateur est différent > ' . count($fixToUpdateRSG) . " à mettre à jour ; " . $nbToUpd . " mis à jour";
		if(!empty($nbReact)) {
			$bodyMessage .= "\n > Réactivations : " . $nbReact . (($nbReact == 1) ? ' abonnement réactivé' : ' abonnements réactivés');
		}
	
	}
	else {
		$bodyMessage .= " > aucune mise à jour.";
	}
	
	// c - RSG à désactiver   
	$bodyMessage .= "\n\nMise en période de grâce : \n";
	if(isset($idlRSG) && !empty($idlRSG) ) {
		
		$nbIdlRSG = 0;
		// Pour la désactivation on dois chercher 
		// - les abonnements actives
		// - et non déjà en période de grace
		$conditions = array('active'=>1,'graceend'=>0); 
		$rs = $DB->get_recordset(RSG_MFA_IMPORT, $conditions, '', '*', 0, 0);
		foreach ($rs as $record) {
			if (array_key_exists($record->numeroidentification, $idlRSG)) {
				
				$bodyMessage .= " > appliquée au RSG " . $record->numeroidentification . "\n";		
				// $record->active			= 0;
				$record->timemodified	= time();
				$record->graceend		= time() + GRACELENGTH;
				if($DB->update_record(RSG_MFA_IMPORT, $record)) {
					$nbIdlRSG++;
				}
			}
		}
		$rs->close(); // Don't forget to close the recordset!
		
		if(!empty($nbIdlRSG)) {
			$bodyMessage .= $nbIdlRSG . (($nbIdlRSG == 1) ? ' abonnement' : ' abonnements');
			$bodyMessage .= " au total.\n";
		}
		else {
			$bodyMessage .= "Aucun abonnement mis en période de grace.\n";
		}
		$bodyMessage .= 'Au total ' . (count($idlRSG) - $nbIdlRSG) . " abonnements désactivés.\n";

	}
	else {
		$bodyMessage .= "Aucune mise en période de grâce. Aucun abonnement désactivé.";
	}

    // si on arrive jusq'au ici, on passe au commit
    $transaction->allow_commit();
	
} catch(Exception $e) {
	
    $transaction->rollback($e);	
	$erroMessage = "Échec de la mise à jour (BD)";
    $bodyMessage .= "ERREUR - La transaction de la BD a échouée.\nLa base de données est remise à son état initial.\n" . $e . "\n";
	
}

// Mesurer le temps d'exécution (fin)
$importation_execution_time = microtime(true) - $importation_time_start;
$bodyMessage .= "\n\nTemps d'exécution total : " . $importation_execution_time . " secondes.\n";

// Renommer le fichier (#3142-5) si les opérations terminées sans erreur
if(empty($erroMessage)) {
	if(rename(CSV_FICHIER, CSV_SUCCESS)) {
		$bodyMessage .= "Fichier source renommé : " . CSV_SUCCESS . ".\n";
		touch(CSV_SUCCESS);
	}
	else {
		$bodyMessage .= "Échec de la tentative de renommer le fichier source : " . CSV_FICHIER . ".\n";
	}
}

$subject .= $erroMessage ? $erroMessage : "Opérations terminées sans erreur";
sendEmail($to, $subject, nl2br($bodyMessage), $headers);

exit(0);
