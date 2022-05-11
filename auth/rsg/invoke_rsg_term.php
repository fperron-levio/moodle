<?php
/**
 * @author Andrei Boris <aboris@crosemont.qc.ca>
 * Date: 2018-01-22
 * Rôles: 
 *    1. désactiver les RSG avec le délais de grâce dépassé
 *    2. envoyer le courriel aux RSG pour leur signaler le début du délais de grâce
 */

define('CLI_SCRIPT', true);
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
/* pour envoyer le courriel aux personnes intéressés                     */
/* ********************************************************************* */
$bodyMessage = '';
$erroMessage = '';
// s'assurer que le message parvient aux personnes intéressés
if (isset($CFG->rsg_synch_contact) && !empty($CFG->rsg_synch_contact)) {
	$to = $CFG->rsg_synch_contact;
}
else {
	$to = 'aboris@crosemont.qc.ca, tnguyen@cegepadistance.ca';
	$bodyMessage .= "Le fichier de configuration manque les adresses de courriel destinataires.\n";
}
$subject = 'Désactivation des RSG ' . date("Y-m-d") . ' : ';
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

function sendRSGinfo($user) {
	
	$supportuser = core_user::get_support_user();
	$subject = "Suspension de votre accès à RSG en ligne";
	$message = 'Bonjour ' . $user->firstname . ',

Nos dossiers indiquent que votre statut a changé depuis votre inscription à RSG en ligne. Actuellement, seules les RSG représentées ont accès à cette plateforme. À compter d’aujourd’hui, vous disposez cependant d’un délai de 28 jours pour terminer, le cas échéant, la capsule que vous avez commencée, imprimer votre certificat et accéder à votre outil boni. Après ce délai, vous ne pourrez plus accéder à la plateforme.

Toutefois, si votre statut n’a pas changé et que, conséquemment, votre accès à RSG en ligne devrait demeurer actif, veuillez communiquer avec le soutien technique en cliquant sur la rubrique Nous joindre, au bas de la page.

RSG en ligne';

	$messagehtml = '<p>Bonjour ' . $user->firstname . ',<br/></p>

<p>Nos dossiers indiquent que votre statut a changé depuis votre inscription à RSG en ligne. Actuellement, seules les RSG représentées ont accès à cette plateforme. À compter d’aujourd’hui, vous disposez cependant d’un délai de 28 jours pour terminer, le cas échéant, la capsule que vous avez commencée, imprimer votre certificat et accéder à votre outil boni.
Après ce délai, vous ne pourrez plus accéder à la plateforme.<br/></p>

<p>Toutefois, si votre statut n’a pas changé et que, conséquemment, votre accès à RSG en ligne devrait demeurer actif, veuillez communiquer avec le soutien technique en cliquant sur la rubrique Nous joindre, au bas de la page.<br/></p>
<p style="font-style: italic;">RSG en ligne</p>';

	return email_to_user($user, $supportuser, $subject, $message);
}


// Mesurer le temps d'exécution (debut)
$importation_time_start = microtime(true);

/************************************************************************** */
/* Étape 1. Désactiver les RSG avec le délais de grâce dépassé              */
/************************************************************************** */

$bodyMessage .= "Désactivation des RSG avec le délais de grâce dépassé :\n";
$unixStamp = time();
$nbRSGDeactivated = 0;
// Pour la désactivation on dois chercher 
// - les abonnements actives
// - et avec le champ graceend plus grand que 0
// - et avec le champ graceend plus petit que la valeur actuel d'Unixstamp
// - et avec le champ gracenotice = 1 (courriel de grâce a été envoyé)
$select = 'active=1 AND graceend > 0 AND graceend < ' . $unixStamp .' AND gracenotice=1';
$rs1 = $DB->get_recordset_select(RSG_MFA_IMPORT, $select, null, '');
foreach ($rs1 as $record) {
	
	// il est important de ne pas supprimer l'information sur l'expiration du délais de grâce
	// et du flag du courriel envoyé 
	$record->active			= 0;
	$record->timemodified	= time();
	if($DB->update_record(RSG_MFA_IMPORT, $record)) {
		$nbRSGDeactivated++;
		// vérifier dans la table mdl_rsg_inscription si l'usager est déjà inscrit
		$usager = $DB->get_record(RSG_INSCRIPTION, array('numeroidentification'=>$record->numeroidentification));
		if(!empty($usager)) {
			$bodyMessage .= " > inscription du RSG " . $record->numeroidentification . " désactivé\n";
		}
		else {
			$bodyMessage .= " > abonnement du RSG " . $record->numeroidentification . " désactivé\n";
		}
	}
	else {
		$bodyMessage .= " > désactivation du RSG " . $record->numeroidentification . " est un échec hautement improbable\n";
	}
	
}
$rs1->close(); // Don't forget to close the recordset!
if(empty($nbRSGDeactivated)) {
	$bodyMessage .= " > aucune désactivation n'a été effectuée.\n";
}


/************************************************************************** */
/* Étape 2. Envoi des courriels aux RSG qui sont en grâce                   */
/************************************************************************** */

$bodyMessage .= "\n\nEnvoi des courriels aux RSG pour leur signaler le début de la période de gràce :\n";
$nbRSGGraced = 0;
$nbRSGIdle = 0;
// Pour l'envoie de courriels on dois chercher 
// - les abonnements actives
// - et avec le champ graceend plus grand que la valeur actuel d'Unixstamp
// - et avec le champ gracenotice = 0 (courriel de grâce n'a pas été envoyé)
$select = 'active=1 AND graceend > ' . $unixStamp .' AND gracenotice=0';
$rs2 = $DB->get_recordset_select(RSG_MFA_IMPORT, $select, null, '');
foreach ($rs2 as $record) {

	// chercher les identifiants du RSG s'il s'est déjà inscrit
	$rsgquery = "select u.id, u.firstname, u.lastname, u.email from {user} u
		join {rsg_inscription} ri on ri.userid=u.id
		where ri.numeroidentification = :numeroidentification";
	$rsg_data = $DB->get_record_sql($rsgquery, array('numeroidentification'=>$record->numeroidentification));
	
	if(!empty($rsg_data)) {
		
		if ($rsg_data->id && $rsg_data->firstname && $rsg_data->lastname && $rsg_data->email) {
			
			$usager_inscrit = $DB->get_record('user', array('id'=>$rsg_data->id));				
			if(sendRSGinfo($usager_inscrit)) {				
				
				$record->timemodified	= time();
				// il est important de ne pas supprimer l'information sur l'expiration du délais de grâce
				// et modifier le flag du courriel envoyé 
				$record->gracenotice	= 1;
				if($DB->update_record(RSG_MFA_IMPORT, $record)) {				
					$nbRSGGraced++;
					$bodyMessage .= " > courriel au RSG " . $record->numeroidentification . " envoyé; la BD mis à jour\n";
				}
				else {
					$bodyMessage .= " > courriel au RSG " . $record->numeroidentification . " envoyé; échec de la mise à jour de la BD (hautement improbable)\n";
				}
				
			}
			else {
				$bodyMessage .= " > envoi du courriel au RSG " . $record->numeroidentification . " a échoué (peu probable).\n";
			}
			
		}
		else {
			$bodyMessage .= " > données du RSG " . $record->numeroidentification . " manquent : " .
				"courriel (" . $rsg_data->email .
				"), nom de famille (" . $rsg_data->lastname . 
				"), prénom (" . $rsg_data->firstname . ")\n";
		}
	}
	else {
		
		// on désactive sur le champ le RSG non inscrit
		$record->active			= 0;
		$record->timemodified	= time();
		$record->gracenotice	= 0;
		if($DB->update_record(RSG_MFA_IMPORT, $record)) {
			$nbRSGIdle++;
			$bodyMessage .= " > RSG " . $record->numeroidentification . " non-inscrit désactivé; la BD mis à jour\n";
		}
		else {
			$bodyMessage .= " > RSG " . $record->numeroidentification . " non-inscrit non désactivé car l'échec de la mise à jour de la BD (hautement improbable)\n";
		}
		
	}
	
}
$rs2->close(); // Don't forget to close the recordset!

if(empty($nbRSGGraced)) {
	$bodyMessage .= " > aucun envoi du courriel pour les RSG incscrit(e)s n'a été effectuée.\n";
}
if(empty($nbRSGIdle)) {
	$bodyMessage .= " > aucune désactivation pour les RSG non-incscrit(e)s n'a été effectuée.\n";
}

// Mesurer le temps d'exécution (fin)
$importation_execution_time = microtime(true) - $importation_time_start;
$bodyMessage .= "\n\nTemps d'exécution total : " . $importation_execution_time . " secondes.\n";

$subject .= $erroMessage ? $erroMessage : "Opérations terminées sans erreur";
sendEmail($to, $subject, nl2br($bodyMessage), $headers);

exit(0);
