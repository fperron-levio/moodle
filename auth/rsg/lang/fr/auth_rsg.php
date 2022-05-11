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
 * Strings for component 'auth_db', language 'en'.
 *
 * @package   auth_db
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_rsgdescription'] = 'Ce module sert à l’authentification et à l’inscription d’un RSG dans la plateforme';
$string['auth_description'] = 'Ce module sert à l’authentification et à l’inscription d’un RSG dans la plateforme'; /* sert à quoi? */

// PAGE
$string['auth_rsgsignupformtitle'] = 'Inscription';
$string['auth_rsg_inscription_intro'] = '<strong>RSG en ligne</strong> est destiné à la RSG reconnue. Toutefois, toute personne travaillant dans le domaine des services de garde en milieu familial du Québec peut s’y inscrire pour se perfectionner.';
$string['auth_backtologin'] = 'Retour à la page de connexion';
$string['auth_gobacktologin'] = 'Veuillez retourner à la page de connexion.';
$string['auth_backtowelcomepage'] = 'Retour à la page d’accueil';

// FORMULAIRE

// Nom
$string['auth_rsglastname_rule_mustbefilled'] = 'Veuillez remplir cette case';

// Prénom
$string['auth_rsgfirstname_rule_mustbefilled'] = 'Veuillez remplir cette case';

// Téléphone
$string['auth_rsgphone_rule_must_have_format'] = 'Le numéro de téléphone doit avoir le format suivant : 999 999-9999';

// Adresse de courriel
$string['auth_confirmemail'] = 'Inscrivez de nouveau votre adresse de courriel';
$string['auth_rsgemail_rule_invalid'] = 'Adresse de courriel non valide';
$string['auth_rsgemail_rule_missing'] = 'Tapez votre adresse courriel une seconde fois';
$string['auth_rsgemailsdontmatch'] = 'Les deux cases d’adresse de courriel n’ont pas le même contenu ';

// Statut
$string['auth_rsgstatus'] = 'Statut';
$string['auth_rsgnotavailable'] = 'Désolé, l’inscription n’est actuellement offerte qu’aux RSG représentées!';
$string['auth_rsgaccessrevoked'] = 'La connexion n’a pas été établie. Veuillez consulter la <a href="/mod/rsg/faq/">Foire aux questions</a>.';
$string['auth_rsgechecconnexion'] = 'La connexion n’a pas été établie. Veuillez consultez la <a href="{$a->faq_url}">Foire aux questions</a>.';
$string['auth_rsg_recordnotfound_mfa_import'] = 'Attention : la combinaison de votre numéro d’identification et de bureau coordonnateur n’a pas été trouvée. Pour en savoir plus, veuillez consulter la  <a href="/mod/rsg/faq/">Foire aux questions</a>.';
$string['auth_rsgmoredetails'] = 'Pour en savoir plus...';
$string['auth_rsgstatus_rule_missing'] = 'Veuillez choisir votre statut parmi les choix du menu déroulant ci-dessous';

$string['auth_rsg_form_status_0'] = 'Choisir votre statut';
$string['auth_rsg_form_status_1'] =  'RSG représentée';
$string['auth_rsg_form_status_2'] =  'RSG subventionnée non représentée';
$string['auth_rsg_form_status_3'] =  'RSG non subventionnée non représentée';
$string['auth_rsg_form_status_4'] =  'Assistante d’une RSG';
$string['auth_rsg_form_status_5'] =  'Remplaçante d’une RSG';
$string['auth_rsg_form_status_6'] =  'Personne en attente de reconnaissance';
$string['auth_rsg_form_status_7'] =  'Personne offrant un service de garde en milieu familial non régi';
$string['auth_rsg_form_status_8'] =  'Agente-conseil en soutien pédagogique et technique';
$string['auth_rsg_form_status_9'] =  'Autre';

// Région administrative
$string['auth_rsgregionselect']='Choisir une région...';
$string['auth_rsgregion'] = 'Région administrative';
$string['auth_rsgregion_rule_missing'] = 'Veuillez choisir votre région administrative parmi les choix du menu déroulant ci-dessous.';

// Bureau coordinateur
$string['auth_rsgofficeselect']='Choisir un bureau...';
$string['auth_rsgoffice'] = 'Bureau coordonnateur';
$string['auth_rsgoffice_rule_missing'] = 'Sélectionnez votre région administrative dans le menu déroulant ci-dessous';
$string['auth_rsgoffice_rule_missing2'] = 'Sélectionnez votre bureau coordonnateur…';

// Numéro d'identification
$string['auth_numeroidentification']='Numéro d’identification';
$string['auth_rsgnumeroidentification_rule_missing']='Veuillez remplir cette case';

// Mot de passe
$string['auth_rsgcreateuserandpass'] = 'Inscription à la plateforme RSG en ligne';
$string['auth_rsgconfpass'] = 'Inscrivez de nouveau votre mot de passe';
$string['auth_rsgemptyconfpass'] = 'Inscrivez de nouveau votre mot de passe';
$string['auth_rsgpassword'] = 'Inscrivez votre mot de passe';
$string['auth_rsgpassworddontmatch'] = 'Les deux cases de mots de passe n’ont pas le même contenu';
$string['auth_rsgpassword_rule_must_have'] = 'Le mot de passe doit comporter :';

// SOUMISSION
$string['auth_rsgsignupformpolicytitle'] = 'Conditions d’utilisation';
$string['auth_rsgsignupformpolicyagreed'] = 'J’ai lu et j’accepte les <a id="auth_rsgsignupformpolicyagreed_link" href="/mod/rsg/conditionsutilisation/" title="Conditions d’utilisation" target="_self"><u>conditions d’utilisation</u></a> du site (vous devez cocher la case)';
$string['auth_rsgsignupformpolicyagreederror'] = 'Veuillez cocher la case afin de confirmer votre acceptation des conditions d’utilisation';
$string['auth_rsgsignupformsubmit'] = 'Soumettre';
$string['auth_inscriptioninfo'] = 'Veuillez cliquer sur « ENREGISTRER ». Vous recevrez sous peu un courriel de confirmation de votre inscription.<br>';

// INSCRIPTION (ERREURS)
$string['auth_rsg_userexists_error'] = 'Vous vous êtes déjà inscrite avec ce numéro d’identification auparavant; il est inutile de refaire le processus d’inscription. Retournez à la page d’accueil pour vous connecter.';

// RÉCUPÉRATION NUMÉRO D'IDENTIFICATION OUBLIÉ
$string['auth_usernameforgotten'] = 'Numéro d’identification oublié';
$string['auth_usernameshowsubject'] = '{$a} : récupération de votre numéro d’identification';
$string['auth_usernameshow'] = 'Bonjour {$a->firstname} {$a->lastname}, <br><br>Voici le rappel de votre numéro d’identification pour vous connecter à la plateforme RSG en ligne : {$a->username}.<br><br>Merci!<br><br>L’équipe de soutien technique';
$string['auth_mustbefilled'] = 'Veuillez remplir cette case';

$string['pluginname'] = 'Module RSG d\'authentification-inscription';

