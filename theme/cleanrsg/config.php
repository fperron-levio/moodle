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


$THEME->name = 'cleanrsg';

$THEME->doctype = 'html5';
$THEME->parents = array('bootstrapbase');
$THEME->sheets = array('fonts','custom', 'angular_noScript');
$THEME->supportscssoptimisation = false;
$THEME->yuicssmodules = array();

$THEME->editor_sheets = array();

$THEME->plugins_exclude_sheets = array(
    'block' => array(
        'html',
    ),
    'gradereport' => array(
        'grader',
    ),
);

// Notes:
// 1) Deux fonctionnalités Bootstrap ne sont pas présente dans port YUI utilisé par Bootstrapbase.
//    J'ai ajouté ici les fichiers .js qui permettent au carrousel de fonctionner correctement.
//	  Important:
//		- Ne pas charger des versions antérieures à ces fichiers (fonctionnalités incomplètes dans le carrousel par ex.).
//		- Ces fichiers n'ont pas été portés à YUI et dépendent donc de Jquery, qui doit être chargé.
// 2) JQuery: Utilise la version intégré à Moodle (1.9.1 au moment d'écrire ces lignes).
//		- Voir theme_cleanrsg_page_init dans lib.php pour la méthode de chargement de Jquery (selon la méthode recommandée).
//	    - Ne pas charger de versions de Jquery avec $THEME->javascripts ou $THEME->javascripts_footer.
//      - Ne pas charger de version Jquery directement dans le html!
// pointer_events_polyfil: Certains images bloquaient les clicks. Le css "cursor-event" permet de corriger le problème mais n'est pas bien supporté
// par les anciennes version d'explorer. pointer_events_polyfil devrait permettre de corriger le problème.

$THEME->javascripts_footer = Array(
    'bootstrap-transition_2.3.2',
    'bootstrap-carousel_2.3.2_fix_CAD',
    'bootstrap-tooltip_2.3.2_fix_CAD',
    'bootstrap-modal_2.3.2',
    'bootstrap-collapse_2.3.2',
    'pointer_events_polyfill',
    'common_init',
    'device' // tâche #3892
);

$THEME->csspostprocess = 'cleanrsg_process_css';

$THEME->layouts = array(
    // Most backwards compatible layout without the blocks - this is the layout used by default.
	
	// todo: Eg: Le 'base' devrait pas avoir de region. Ajouté defaultregion puisque cela causait une erreur dans certains cas.
	// Base est habituellement utilisé par les formulaires mais on redirige les formulaires rsg vers le layout "front".
	// Ça serait éventuellement à corriger (risque de régression?).
    'base' => array(
        'file' => 'pages-statiques.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
		'options' => array('nonavbar'=>true,'langmenu'=>false)
    ),
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => array(
        'file' => 'col1rsg.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
//    // Main course page.
 /*   'course' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu'=>true),
    ),*/
//    'coursecategory' => array(
//        'file' => 'columns3.php',
//        'regions' => array('side-pre', 'side-post'),
//        'defaultregion' => 'side-pre',
//    ),
    // part of course, typical for modules - default page layout if $cm specified in require_login()
    'incourse' => array(
        'file' => 'pages-statiques.php',
        //'regions' => array('side-pre', 'side-post'),
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>true),
    ),

    'front' => array(
        'file' => 'pages-statiques-front.php',
        //'regions' => array('side-pre', 'side-post'),
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>true),
    ),

    'popupform' => array(
        'file' => 'popupform.php',
        //'regions' => array('side-pre', 'side-post'),
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>true),
    ),
    
    'catalogue' => array(
        'file' => 'catalogue.php',
        //'regions' => array('side-pre', 'side-post'),
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>true),
    ),

    // The site home page.
    'frontpage' => array(
        'file' => 'frontpage.php',
        'regions' => array(),
        'options' => array('nonavbar'=>true,'langmenu'=>false),
    ),
    // Server administration scripts.
    
    'admin' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>false,'langmenu'=>false),
    ),
     
//    // My dashboard page.
    'mydashboard' => array(
        'file' => 'col1rsg.php',
        'regions' => array(),
        'options' => array('nonavbar'=>true,'langmenu'=>false),
    ),
//    // My public page.
    'mypublic' => array(
        'file' => 'pages-statiques.php',
        //'regions' => array('side-pre', 'side-post'),
        'regions' => array(),
        //'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>true,'langmenu'=>false),
    ),
    //    // My public page.
    'mypubliccatalogue' => array(
        'file' => 'pages-statiques-catalogue.php',
        //'regions' => array('side-pre', 'side-post'),
        'regions' => array(),
        //'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>true,'langmenu'=>false),
    ),
    
//    // My blog page.
    'blog' => array(
        'file' => 'col1rsg.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>true,'langmenu'=>false),
    ),
    'login' => array(
        'file' => 'login.php',
        'regions' => array(),
        'options' => array('langmenu'=>true),
    ),

//    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
   'popup' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'nonavbar'=>true),
   ),
//    // No blocks and minimal footer - used for legacy frame layouts only!
//    'frametop' => array(
//        'file' => 'columns1.php',
//        'regions' => array(),
//        'options' => array('nofooter'=>true, 'nocoursefooter'=>true),
//    ),
//    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible
//    'embedded' => array(
//        'file' => 'embedded.php',
//        'regions' => array()
//    ),
//    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
//    // This must not have any blocks, and it is good idea if it does not have links to
//    // other places - for example there should not be a home link in the footer...
//    'maintenance' => array(
//        'file' => 'columns1.php',
//        'regions' => array(),
//        'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocoursefooter'=>true, 'nocourseheader'=>true),
//    ),
//    // Should display the content and basic headers only.
//    'print' => array(
//        'file' => 'columns1.php',
//        'regions' => array(),
//        'options' => array('nofooter'=>true, 'nonavbar'=>false),
//    ),
//    // The pagelayout used when a redirection is occuring.
//    'redirect' => array(
//        'file' => 'embedded.php',
//        'regions' => array(),
//    ),
//    // The pagelayout used for reports.
//    'report' => array(
//        'file' => 'columns2.php',
//        'regions' => array('side-pre'),
//        'defaultregion' => 'side-pre',
//    ),
//    // The pagelayout used for safebrowser and securewindow.
//    'secure' => array(
//        'file' => 'secure.php',
//        'regions' => array('side-pre', 'side-post'),
//        'defaultregion' => 'side-pre'
//    ),
);


$THEME->blockrtlmanipulations = array(
    'side-pre' => 'side-post',
    'side-post' => 'side-pre'
);

$THEME->rendererfactory = 'theme_overridden_renderer_factory';

//$THEME->rendererfactory = 'theme_cleanrsg_renderer';