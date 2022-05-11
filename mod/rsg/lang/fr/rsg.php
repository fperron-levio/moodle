<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'imscp', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    mod
 * @subpackage imscp
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Pour l'affichage des heures (ex. catalogue de capsule).
$string['hour_one'] = 'heure';
$string['hour_many'] = 'heures';
$string['hour_one_short'] = 'h';
$string['hour_many_short'] = 'h';
$string['minute_one'] = 'minute';
$string['minute_many'] = 'minutes';

$string['mes_infos'] 		= "Modifier mes renseignements";
$string['mon_mot_de_passe'] = "Modifier mon mot de passe";
$string['mon_courriel']     = "Modifier mon adresse de courriel";
$string['mes_certificats']  = "Mes certificats";
$string['mes_transactions'] = "Mes transactions";
$string['mon_abonnement']   = "Mon abonnement";
$string['recu']             = "Reçu";

$string['custommenuitems_accueil'] = 'Accueil';
$string['custommenuitems_catalogue'] = 'Catalogue <br />de&nbsp;capsules';
$string['custommenuitems_outils'] = 'Boite <br />à&nbsp;outils';
$string['custommenuitems_parcours'] = 'Mon <br />parcours';
$string['custommenuitems_profil'] = 'Mon <br />profil';
$string['custommenuitems_faq'] = 'FAQ';
$string['custommenuitems_contact'] = 'Nous <br />joindre';
$string['custommenuitems_logout'] = 'Déconnexion';     

$string['username'] = 'Numéro d’identification';
$string['rsg:addinstance'] = 'Ajouter une nouvelle capsule RSG';
$string['rsg:view'] = 'Voir une capsule';
$string['modulename'] = 'Capsules RSG';
$string['modulename_help'] = 'Ce module permet de créer une capsule une fois que ses composantes ont été ajoutées
    (un cours, un package scorm, un quiz et une ressource';
$string['modulename_link'] = 'mod/rsg/view';
$string['modulenameplural'] = 'Capsules RSG';
$string['navigation'] = 'Navigation';
$string['page-mod-rsg-x'] = 'Any RSG content package module page';
$string['packagefile'] = 'Package file';
$string['pluginadministration'] = 'RSG administration';
$string['rsgname'] = 'RSG capsule';

// Form créaction d'activité capsule:
$string['capsulename'] = 'Nom de la capsule';
$string['rsgfieldset'] = 'RSG form field'; /* ? */
$string['uec'] = 'UEC';
$string['uec_help'] = '<p>Le nombre d\'UEC correspondant à cette capsule.</p>'
        . '<p>Exemples de valeur acceptées:<ul>'
        . '<li>0.5</li>'
        . '<li>1.5</li>'
        . '<li>2</li>'
        . '<li>...</li></ul></p>';
$string['duration_capsule'] = 'Durée de la capsule';
$string['duration_capsule_help'] = '<p>Temps estimé en <b>minutes</b> pour compléter la capsule (activité Scorm).</p>'
        . '<p>La durée de la capsule additionné à la durée de l’autoévaluation devrait normalement correspondre au nombre d’UEC selon la règle (1h = 0,1 UEC).</p>'
        . '<p>Exemples de valeurs acceptées:<ul>'
        . '<li>15</li>'
        . '<li>60</li>'
        . '<li>120</li></ul></p>';
$string['duration_autoevaluation'] = 'Durée de l’autoévaluation';
$string['duration_autoevaluation_help'] = '<p>Temps estimé en <b>minutes</b> pour compléter l’autoévaluation (activité Quiz).</p>'
        . '<p>La durée de la capsule additionné à la durée de l’autoévaluation devrait normalement correspondre au nombre d’UEC selon la règle (1h = 0,1 UEC).</p>'
        . '<p>Exemples de valeurs acceptées:<ul>'
        . '<li>15</li>'
        . '<li>60</li>'
        . '<li>120</li></ul></p>';
$string['keywords'] = "Mot-clés";
$string['keywords_help'] = '<p>Les mots clés associés à la capsule (pour fin de recherche).</p>'
        . '<p><ul><li>Un ou plusieurs mots séparés par point-virgule \';\'.</li>'
        . '<li>Idéalement en minuscules.</li></ul></p>'
        . '<p>Exemples de valeurs acceptées:<ul>'
        . '<li>accrochage</li>'
        . '<li>accrochage;chicane</li>'
        . '<li>accrochage; chicane; collaboration; communication avec le parent; conflit; différend; étapes du processus de résolution de conflits; gestion de conflits; parent; problème; processus de résolution de conflits; résolution de conflits</li></ul></p>';
$string['pluginname'] = 'mod RSG';
$string['generalconfig'] = ' General config mod RSG';
$string['explaingeneralconfig'] = 'Explain General config mod RSG';

$string['rose_cat'] = 'Catégorie Rose';
$string['green_cat'] = 'Catégorie Verte';
$string['violet_cat'] = 'Catégorie mauve';
$string['orange_cat'] = 'Catégorie orange';
$string['config_green_cat'] = 'Catégorie correspondante à vert';
$string['config_rose_cat'] = 'Catégorie correspondante à rose';
$string['config_violet_cat'] = 'Catégorie correspondante à mauve';
$string['config_orange_cat'] = 'Catégorie correspondante à orange';
$string['category_title_pink'] = 'Sécurité, santé et alimentation';
$string['category_title_green'] = 'Programme éducatif';
$string['category_title_violet'] = 'Développement de l’enfant';
$string['category_title_orange'] = 'Rôle de la RSG';
$string['rsg_no_configured'] = 'You must set the corresponding categories first in the RSG plugin configuration';
// Inscription
$string['numeroidentification'] = 'Numéro d’identification';
$string['numeroidentification_help'] = 'Composé d’une série de chiffres, il correspond au numéro d’identification inscrit sur le bordereau de paiement de la subvention transmis par votre bureau coordonnateur. Pour en savoir plus, veuillez consulter la <a href="/mod/rsg/faq/#identification" target="_blank"><u>Foire aux questions</u></a>.';
$string['inscription_rsgstatus'] = 'RSG représentée';
$string['inscription_rsgstatus_help'] = 'Correspond à la situation d’une RSG qui fait partie d’une association représentative (FIPEQ-CSQ, FSSS-CSN, RTTACPE ou AEMFQ). Pour en savoir plus, veuillez consulter la <a href="/mod/rsg/faq/#profil" target="_blank"><u>Foire aux questions</u></a>.';
$string['inscription_password'] = 'Mot de passe';
$string['inscription_password_help'] = 'Le mot de passe doit comporter: <ul><li>au moins 8 caractères.</li><li>au moins 1 chiffre.</li><li>au moins 1 lettre minuscule.</li><li>au moins 1 lettre majuscule.</li></ul></br>« Assurez-vous que la touche Verr. Maj./Caps Lock n’est pas activée. »';
// Mon profil
$string['myspace_title'] = 'Mon profil';
$string['rsg_changepassword'] = 'Changer mon mot de passe';
$string['profile_old_password'] = 'Inscrivez votre mot de passe actuel';
$string['profile_old_password_missing'] = 'Veuillez inscrire votre mot de passe actuel';
$string['profile_new_password'] = 'Inscrivez votre nouveau mot de passe';
$string['profile_new_password_missing'] = 'Veuillez inscrire votre nouveau mot de passe';
$string['profile_confirm_new_password'] = 'Inscrivez une seconde fois votre nouveau mot de passe';
$string['profile_confirm_new_password_missing'] = 'Veuillez inscrire une seconde fois votre nouveau mot de passe';
$string['profile_password_dont_match'] = 'Les deux cases de mot de passe n’ont pas le même contenu';
$string['profile_passwordchanged'] = 'Le mot de passe a été changé.';
$string['old_password'] = 'Tapez l\'ancien mot de passe' ;
$string['type_new_password'] = 'Tapez le nouveau mot de passe';
$string['retype_new_password'] = 'Tapez une seconde fois le nouveau mot de passe';
$string['rsgmyprofileformsubmit'] = 'Enregistrer';
$string['inscription_date'] = 'Date d\'inscription ';
$string['changepassword'] = 'Changer mon mot de passe';
$string['old_courriel'] = 'Inscrivez l’adresse de courriel utilisée lors de votre inscription';
$string['type_new_courriel'] = 'Inscrivez votre nouvelle adresse de courriel';
$string['retype_new_courriel'] = 'Inscrivez une seconde fois votre nouvelle adresse de courriel';
$string['missing_courriel'] = 'Veuillez remplir cette case';
$string['missing_new_courriel'] = 'Veuillez remplir cette case';
$string['wrong_old_courriel'] = 'L’information que vous avez entrée est incorrecte. Veuillez l’inscrire de nouveau.';
$string['courriel_changed'] = 'L’adresse de courriel a été changée.';
$string['courriel_not_changed'] = 'L’adresse de courriel n’a pas été changée.';
$string['renseignements_changed'] = 'Les renseignements ont été changés.';
$string['renseignements_not_changed'] = 'Les renseignements n’ont pas été changés.';
$string['wrong_courriels_match'] = 'Les deux cases d’adresse de courriel n’ont pas le même contenu ';
$string['wrong_courriel_content'] = 'La nouvelle adresse de courriel n’est pas valide';
$string['same_courriel_content'] = 'La nouvelle adresse de courriel doit être différente de l’adresse actuelle';
$string['wrong_courriel_exists'] = 'La nouvelle adresse de courriel est déjà prise par un autre utilisateur.';
// Contact
// Form:
$string['contact_form_title'] = 'Nous joindre';
$string['contact_form_title2'] = '<br /><br />Donnez votre opinion!';
//$string['contact_form_text'] = 'Avant de nous acheminer une question, veuillez vous assurer d’avoir consulté la <a href="/mod/rsg/faq/" target="_blank">Foire aux questions</a>; la réponse s’y trouve peut-être. Pour toute autre question technique, veuillez remplir le formulaire ci-dessous. En cliquant sur le bouton SOUMETTRE, un courriel sera automatiquement envoyé à notre équipe.';
$string['contact_form_text'] = '<ul><li>Consultez la <a href="/mod/rsg/faq/#soutien" target="_blank"><u>Foire aux questions</u></a>; votre réponse s’y trouve peut-être;</li><li>Remplissez le formulaire ci-dessous en <b>décrivant votre problème avec précision</b>;</li><li>Cliquez sur le bouton SOUMETTRE; un courriel sera automatiquement transmis à notre équipe.</li></ul>
Si vous ne recevez pas de réponse dans un délai de 2 jours ouvrables, vérifiez votre boite de courrier indésirable.';
$string['contact_form_text2'] = 'N’hésitez pas à partager avec nous votre expérience de formation, tant pédagogique que technique, en nous transmettant un commentaire.';
$string['contact_form_message_label'] = 'Description de la demande';
$string['contact_form_message_placeholder'] = 'Inscrivez votre message ici (500 caractères maximum).';
$string['contact_form_submit_btn_title'] = 'Envoyer';
// Contact validation
$string['contact_form_message_range_rule_text'] = 'Entrez un message valide (minimum 10 caractères, maximum 500).';
$string['contact_form_email_format_rule_text'] = 'Adresse de courriel non valide';
// Contact autre:
$string['contact_form_confirmation_ok_title'] = 'Confirmation d\'envoi';
$string['contact_form_confirmation_ok_text'] = 'Votre demande de soutien a été acheminée à notre équipe. Si vous ne recevez pas de réponse dans un délai de 2 jours ouvrables, vérifiez votre boite de courrier indésirable. <br /><br />Si le courriel s’y trouve, vous devez le déplacer dans votre boite de réception pour y recevoir par la suite les courriels provenant de <i>RSG en ligne<i/>.<br /><br />Merci!<br /><br />L\'équipe de soutien technique';
$string['contact_form_confirmation_ok_text2'] = 'Nous vous remercions pour vos commentaires. Ceux-ci ont été acheminés à notre équipe. Aucune autre réponse ne vous sera envoyée, mais soyez assurée que nous en tiendrons compte pour améliorer notre produit.<br /><br />Merci!<br /><br />L\'équipe d\'amélioration continue';
$string['contact_form_confirmation_error_title'] = 'Confirmation d\'envoi (erreur)';
$string['contact_form_confirmation_error_text'] = 'Problème indéterminé lors de l\'envoi du message.';
$string['contact_form_email_title'] = 'Demande de soutien technique';
$string['contact_form_email_title2'] = 'Commentaires d\'une utilisatrice de la plateforme';
$string['contact_form_email_content_loggedin'] = 'Bonjour, une utilisatrice connectée à la plateforme a envoyé cette demande de soutien :<br /><br />Nom : {$a->name};<br />Numéro d\'identification : {$a->noidentification};<br />Adresse de courriel : {$a->email};<br />Numéro de téléphone : {$a->phone};<br />Statut : {$a->rsgstatus};<br />Nature de la demande : {$a->rsgnature};<br /><br />Description de la demande : {$a->user_message}';
$string['contact_form_email_content_loggedin2'] = 'Bonjour, une utilisatrice connectée à la plateforme a envoyé ces commentaires :<br /><br />Nom : {$a->name};<br />Numéro d\'identification : {$a->noidentification};<br />Adresse de courriel : {$a->email};<br /><br />Commentaires : {$a->user_message}';
$string['contact_form_email_content_notloggedin'] = 'Bonjour, une utilisatrice non-connectée à la plateforme a envoyé cette demande de soutien :<br /><br />Nom : {$a->name};<br />Numéro d\'identification : {$a->noidentification};<br />Adresse de courriel : {$a->email};<br />Numéro de téléphone : {$a->phone};<br />Statut : {$a->rsgstatus};<br />Nature de la demande : {$a->rsgnature};<br /><br />Description de la demande : {$a->user_message}';
$string['contact_form_confirmemail'] = 'Inscrivez de nouveau votre adresse de courriel';
$string['contact_form_rsgemail_rule_invalid'] = 'Adresse de courriel non valide';
$string['contact_form_rsgemail_rule_missing'] = 'Inscrivez de nouveau votre adresse de courriel';
$string['contact_form_rsgemailsdontmatch'] = 'Les deux cases d’adresse de courriel n’ont pas le même contenu';
$string['contact_form_rsgphone_rule_must_have_format'] = 'Le numéro de téléphone doit avoir le format suivant : 999 999-9999';
$string['contact_form_rule_mustbefilled'] = 'Veuillez remplir cette case';
$string['contact_form_rsgstatus'] = 'Statut';
$string['contact_form_rsgstatus_rule_missing'] = 'Veuillez choisir votre statut parmi les choix du menu déroulant ci-dessous';
$string['contact_form_rsg_form_status_1'] =  'RSG représentée';
$string['contact_form_rsg_form_nature_demande'] = 'Nature de la demande';
$string['contact_form_rsg_form_nature_demande_0'] = 'Sélectionner un choix';
$string['contact_form_rsg_form_nature_demande_1'] = 'Numéro d’identification non valide';
$string['contact_form_rsg_form_nature_demande_2'] = 'Mot de passe non valide';
$string['contact_form_rsg_form_nature_demande_3'] = 'Problème d’inscription';
$string['contact_form_rsg_form_nature_demande_4'] = 'Problème d’affichage';
$string['contact_form_rsg_form_nature_demande_5'] = 'Problème dans une capsule';
$string['contact_form_rsg_form_nature_demande_6'] = 'Problème avec une autoévaluation';
$string['contact_form_rsg_form_nature_demande_7'] = 'Autre';
$string['contact_form_rsgnature_rule_missing'] = 'Veuillez choisir la nature de votre demande parmi les choix de la liste déroulante ci-dessous';
$string['contact_form_numero_identification'] = 'Numéro d’identification';
$string['contact_form_rsg_form_phone'] = 'Numéro de téléphone';
$string['contact_form_rsg_form_comment'] = 'Commentaires';
$string['contact_form_rsg_form_emailrequired'] = 'Veuillez inscrire votre adresse de courriel';
$string['contact_form_rsg_form_emailrequired_note'] = 'Note : si vous avez changé d\'adresse de courriel depuis votre inscription cliquez plutôt sur le lien « Nous joindre » situé au bas de la page de connexion.';
$string['contact_form_rsg_form_emailrequired_attention'] = 'Attention : la combinaison de votre numéro d’identification et de votre adresse de courriel n’a pas été trouvée. Vérifiez d’abord que vous avez inscrit correctement votre adresse de courriel. Sinon, avez-vous changé de courriel récemment?';
$string['contact_form_rsg_form_passwordagain'] = 'Veuillez inscrire une seconde fois votre nouveau mot de passe';
$string['contact_form_rsgpassworddontmatch'] = 'Les deux cases de mot de passe n’ont pas le même contenu';

// Catalogue:
$string['catalog_page_title'] = 'Catalogue de capsules';
$string['catalog_page_title2'] = 'Cliquez sur le titre d’une capsule pour en apprendre davantage sur celle-ci';
$string['catalog_page_message'] = 'Veuillez noter que le visionnement de TOUS les écrans de la capsule est essentiel pour tirer le plus de bénéfices de votre autoévaluation.';
$string['catalog_empty'] = '[Problème technique] Les capsules ne sont pas disponibles pour le moment. Veuillez réessayer plus tard.';
// Catalogue:Affichage capsules
$string['category_toggle_more'] = 'En afficher plus';
$string['category_toggle_less'] = 'Afficher moins';
// Catalogue:Recherche
$string['search_no_result'] = 'Aucune capsule ne correspond au(x) mot(s)-clé(s) recherché(s).';
$string['search_one_result'] = 'Une ou plusieurs capsule(s) correspond(ent) au(x) mot(s)-clé(s) recherché(s).';
$string['search_multiple_result'] = 'Une ou plusieurs capsule(s) correspond(ent) au(x) mot(s)-clé(s) recherché(s).';

// Affichage liste, commun:
$string['affichage_liste_no_results'] = 'Aucun résultat à afficher pour le moment.';
$string['autoevaluation'] = 'Autoévaluation';
$string['capsule'] = 'Capsule';
$string['description'] = 'Description';
$string['bonus_tool'] = 'Outil boni';
$string['bonus_tool_tooltip'] = '{$a}';
$string['subject'] = 'Sujet';
$string['subject_tooltip'] = 'Cette capsule s’inscrit dans le sujet {$a}.';

$string['category_empty_more_later'] = 'Capsules à venir';

// Boite à outils
$string['mytoolbox_page_title'] = 'Boite à outils';
$string['mytoolbox_click_on_tool_to_open_msg'] = '<span  style="text-align: left; display: block; margin-top: 8px; margin-bottom: -12px; " > 
<img style="float: left; height: 38px; margin-right: 10px;"  src="/theme/cleanrsg/pix/photo-warning-Logo-60.png" />
Vous avez terminé votre autoévaluation et vous avez obtenu votre outil boni? <br>
Enregistrez une copie sur votre ordinateur avant d’y inscrire vos données personnelles.</span><br>';

$string['mytoolbox_empty'] = 'Vous obtiendrez un outil boni dès que vous aurez terminé une autoévaluation.';

// Mon parcours:
$string['myjourney_page_title'] = 'Mon parcours';
// Les status sont associées à des règles. Utilise id numérique.
$string['myjourney_capsule_status_1'] = 'Capsule en cours (activités et autoévaluation non terminées)';
$string['myjourney_capsule_status_2'] = 'Capsule en cours (activités terminées, autoévaluation non terminée)';
$string['myjourney_capsule_status_3'] = 'Capsule et autoévaluation terminées le {$a->date}';
$string['myjourney_empty'] = 'Aucunes capsules en cours.';

//Accueil:
$string['home_capsule_discover_recent'] = 'Découvrez les plus récentes capsules';

//Commun:
$string['error_problem_detected'] = 'Problème détecté:';
$string['error_javascript_support_missing'] = 'Le support de javascript doit être activé pour accéder au site RSG en ligne.';

//popup acces abonement
$string['popup_noaccess'] = 'Vous avez uniquement accès aux capsules et aux outils bonus pour lesquels vous avez complété l’autoévaluation avant la date de fin de votre abonnement.';

//Certificat
$string['no_activity_period'] = 'Pas d\'activité dans cette période';

//Autoevaluations
$string['rsg_quiz_summary_notcompleted'] = '<span class="icon-warning-sign">&nbsp;</span>Vous devez faire un choix dans <b>tous</b> les menus déroulants et inscrire vos réponses dans <b>toutes</b> les boîtes de textes avant de pouvoir terminer et soumettre votre autoévaluation. Vous pouvez cliquer sur les questions dans le tableau suivant pour compléter ou modifier vos réponses.';
$string['rsg_quiz_summary_completed'] = '<span class="icon-check">&nbsp;</span> Toutes vos réponses sont enregistrées.<br>Vous pouvez maintenant soumettre votre autoévaluation en cliquant sur le bouton «&nbsp;Soumettre&nbsp;».';
$string['rsg_quiz_summary_previous_attempt'] = 'Informations sur l’autoévaluation';
$string['rsg_quiz_summary_questions'] = 'État des questions';
$string['rsg_quiz_attemptquiznow'] = 'Commencer l’autoévaluation';
$string['rsg_quiz_continueattempt'] = 'Continuer l’autoévaluation';
$string['rsg_quiz_autoevaluation_title'] = 'Autoévaluation';
$string['rsg_quiz_autoevaluation_title_review'] = 'Autoévaluation';
$string['rsg_quiz_submitallandfinish'] = 'Soumettre';
$string['rsg_quiz_confirmclose'] = 'Une fois vos réponses transmises, vous n’aurez plus la possibilité de les modifier.';
$string['rsg_quiz_statefinished'] = 'Terminée';
$string['rsg_quiz_statefinisheddetails'] = 'Transmise le {$a}';
$string['rsg_quiz_feedback_exemple_answer_title'] = 'Exemple de réponse&nbsp;:';
$string['rsg_quiz_bonus_tool_available_message'] = 'Félicitations! Vous avez terminé votre formation.';
$string['rsg_quiz_bonus_tool_available_message_outil_boni'] = '<p>Vous avez maintenant accès à votre outil boni.</p><p>Il est aussi disponible dans la page <b>boite à outils</b> de la plateforme.</p>


<img style="float: left; height: 24px; margin-right: 4px;"  src="/theme/cleanrsg/pix/photo-warning-Logo-60.png" />
<p  style="padding-bottom:7px; margin-top: 22px;"> Enregistrez une copie sur votre ordinateur avant d’y inscrire vos données personnelles.</p>';
$string['rsg_quiz_bonus_tool_available_message_certificat'] = '<p>Vous pouvez dès à présent accéder à votre certificat.</p><p>Il peut également être imprimé à partir de la page <b>Mon parcours</b> de la plateforme.</p>';
$string['rsg_quiz_bonus_tool_goto_button'] = 'Aller à l’outil boni';
$string['rsg_quiz_quit_button'] = 'Quitter l’autoévaluation';
$string['rsg_quiz_next_button'] = 'Valider';
$string['rsg_quiz_previous_button'] = 'Précédente';
$string['rsg_quiz_dialog_feedback_exemple_answer_message'] = 'Vos réponses seront conservées. La date de fin de l’autoévaluation est enregistrée.';
$string['rsg_quiz_dialog_feedback_exemple_answer_button'] = 'Voir les réponses possibles';
$string['rsg_quiz_dialog_feedback_exemple_answer_title'] = 'Autoévaluation terminée';
$string['rsg_quiz_dialog_quit_title'] = 'Voulez-vous quitter votre autoévaluation ?';
$string['rsg_quiz_dialog_quit_message'] = 'Toutes vos réponses seront conservées et vous pourrez reprendre votre autoévaluation plus tard.';
$string['rsg_quiz_dialog_quit_button_keep'] = 'Annuler';
$string['rsg_quiz_dialog_quit_message_finish'] = 'Quitter';
$string['rsg_quiz_dialog_quit_message_cancel'] = 'Annuler';
$string['rsg_quiz_attempt_autosave_notice'] = '<span class="icon-warning-sign">&nbsp;</span> Les réponses seront sauvegardées automatiquement lors d’un changement de page.';
$string['rsg_quiz_autoevaluation_instrution'] = "L'autoévaluation est une démarche qui permet de développer votre capacité d’autocritique en faisant ressortir vos points forts et ceux qui représentent un défi pour vous. S'autoévaluer est très important pour vérifier ses acquis et ses apprentissages. Prenez tout le temps nécessaire pour faire cet exercice. Une fois que vous aurez terminé votre autoévaluation, vous aurez accès à votre outil boni. Veuillez noter que le visionnement de TOUS les écrans de la capsule est essentiel pour tirer le plus de bénéfices de votre autoévaluation.";
$string['rsg_quiz_autoevaluation_instrution_time'] = "30 minutes (temps estimé)";
$string['rsg_quiz_autoevaluation_print'] = "Imprimer";

$string['rsg_error_missing_prerequisite'] = 'ERREUR RSG, pré-requis manquants. Les activités quiz, scorm, ressource doivent être créés dans le cours avant la création du module rsg.';

$string['politique_achat_page_title'] = 'Politique d’achat';
$string['privacypolicy_page_title'] = 'Politique de confidentialité';
$string['conditionsutilisation_page_title'] = 'Conditions d’utilisation';
$string['requirements_page_title'] = 'Exigences techniques';
$string['capsuleslist_page_title'] = 'Liste des capsules disponibles';
$string['news_page_title'] = 'Nouvelles';
$string['contact_page_title'] = 'Nous joindre';
$string['nous_joindre_page_title'] = 'Nous joindre';
$string['contactform_page_title'] = 'Formulaire de contact';
$string['faq_page_title'] = 'Foire aux questions';
$string['faq_short_page_title'] = 'FAQ';
$string['myprofile_page_title'] = 'Mon profil';
$string['about_page_title'] = 'À propos';

// 404
$string['error404_page_title'] = 'Erreur 404';
$string['error404_dialog_title'] = 'Page introuvable';
$string['error404_dialog_button_return_home'] = 'Retourner à l’accueil';

// Erreur catalogue / capsule.
$string['rsg_autoevaluation_not_available'] = 'Vous devez d’abord visualiser et parcourir la capsule avant d’aller faire l’autoévaluation.';

// Dialogue abonnement.
$string['rsg_dialog_subscription_button_renew'] = 'Cliquez ici pour le renouveler';
