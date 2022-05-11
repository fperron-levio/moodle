<?php

/**
 * Code commun affichage style liste / app angular(parcours, mon outil et catalogue).
 * Cela permet d'éviter le code en double mais la séparation de la partie commune n'est pas 100% propre (il y a encore du code similaire dans les pages).
 */
 
/* IMPORTANT: Angular doit être chargé dans le header afin que le directive ng-cloak puisque fonctionner correctement. */
$PAGE->requires->js("/mod/rsg/javascript/angular-1.2.23.js", true);
$PAGE->requires->js("/mod/rsg/javascript/angular-animate-1.2.23.js", true);

/* Script application Angular affichage "capsule" (incluant capsule en version "texte" (mon parcours)) */
$PAGE->requires->js("/mod/rsg/javascript/rsgApp.js");
$PAGE->requires->js("/mod/rsg/javascript/rsgCatalog.js");

// Strings:
$PAGE->requires->strings_for_js(array(
    'category_toggle_more',
    'category_toggle_less',
    'myjourney_empty',
    'search_no_result',
    'search_one_result',
    'search_multiple_result',
    'popup_noaccess',
    'affichage_liste_no_results',
    'category_empty_more_later',
    'rsg_autoevaluation_not_available',
), 'mod_rsg');

// Strings (tooltip)
$PAGE->requires->strings_for_js(array(
    'autoevaluation',
    'capsule',
    'description',
    'bonus_tool',
    'bonus_tool_tooltip',
    'subject',
    'subject_tooltip',
), 'mod_rsg');


$infoCategories = capsule::getInfoCategories();

echo $OUTPUT->header();