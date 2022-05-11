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


/**************************************************************************************************************************/
/* Intégration de l'applicaition Angular pour l'affichage de capsule est plus complexe que pour catalogue.
*  Frontpage est directement dans le thème, pas de index.php?
* 
* À revoir.
* 
*/

/*require_once('../../../config.php');*/
require_once $CFG->dirroot . '/mod/rsg/locallib.php';
/* IMPORTANT: Angular doit être chargé dans le header afin que le directive ng-cloak puisque fonctionner correctement. */
/* todo: Faire un include du index.php de frontpage??? */

$PAGE->requires->js("/mod/rsg/javascript/angular-1.2.23.js", true);
$PAGE->requires->js("/mod/rsg/javascript/angular-animate-1.2.23.js", true);

// Source theme. Pourrait se trouver dans themeByPass mais uilise la version du niveau précédant pour ne pas dupliquer les fichiers inutilement.
$PAGE->requires->js("/theme/cleanrsg/javascript/pointer_events_polyfill.js", true);

// Login et mediaelement ne devraient pas être themeByPass mais plutôt "common".
$PAGE->requires->js("/theme/cleanrsg/javascript/mediaelement_2.15.1.fix_CAD/mediaelement-and-player-fix_CAD.js", true);
$PAGE->requires->js("/theme/cleanrsg/javascript/modal_video.js", true);

$PAGE->requires->js("/theme/cleanrsg/javascript/common_init.js", true);

// css: Ne devrait pas être themeByPass mais plutôt "common".
$PAGE->requires->css("/theme/cleanrsg/javascript/mediaelement_2.15.1.fix_CAD/mediaelementplayer.min.css");

// Strings:
$PAGE->requires->string_for_js('home_capsule_discover_recent', 'mod_rsg');
$PAGE->requires->string_for_js('popup_noaccess', 'mod_rsg');

/* Script application Angular affichage "capsule" (incluant capsule en version "texte" (mon parcours)) */
$PAGE->requires->js("/mod/rsg/javascript/rsgApp.js");
$PAGE->requires->js("/mod/rsg/javascript/rsgCatalog.js");

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

$capsule_data_catalogue = capsule::getCapsuleCatalog(capsule::RSG_HOME_CAPSULE_LIMIT, capsule::SORT_MOST_RECENT_TO_OLDEST);

$infoCategories = capsule::getInfoCategories(); /* Ordre "hardcodé" via constantes (à revoir éventuellement, l'ordre devrait etre défini du côté de la bd?). */

/* Initialisation */
// ATTENTION: Ce code est requis dans frontpage seulement. Dans le catalogue, mod_rsg.init est appelé automatiquement).
// Bloque à ajouter dans les templates qui permettent accès aux capsules
global $PAGE,$DB,$USER,$CFG;
require_once $CFG->dirroot.'/mod/rsg/classes/rsg_access.php';
$rsg=new \auth\rsg\RSGUser($USER,false);
$acces=new \rsg_access($rsg);
$data = $acces->getInitData();

$PAGE->requires->js_init_call('M.mod_rsg.init', array($data['acl'], $data['visits']),true);
//fin Bloque
$PAGE->requires->js_init_call("init_rsgApp", null, false);
$PAGE->requires->js_init_call("init_rsgCatalog", array(array("capsuleData"=>$capsule_data_catalogue, "capsuleDataType"=>"frontpage", "capsuleTemplateName"=> "capsule", "infoCategories"=>$infoCategories)), false);

/*****************************************************************************************************************************/

// Get the HTML for the settings bits.
$html = theme_cleanrsg_get_html_for_settings($OUTPUT, $PAGE);

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />

    <?php echo $OUTPUT->standard_head_html() ?>
    <?php echo $OUTPUT->rsg_head_html(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Fixe RSG caroussel by ACM*/
        @media (min-width: 1200px) {
            .carousel-inner .item #slide1 {
                color: #a4cd40;
                font-size: 56px !important;
                line-height: 60px !important;
            }
        }

        /* Portrait tablet to landscape and desktop */
        @media (min-width: 980px) and (max-width: 1200px) {
            .carousel-inner .item #slide1 {
                color: #a4cd40;
                font-size: 46px !important;
                line-height: 60px !important;
            }
        }

        @media (min-width: 768px) and (max-width: 980px) {
            .carousel-inner .item #slide1 {
                color: #a4cd40;
                font-size: 46px !important;
                line-height: 60px !important;
                top:30px;
                left:50px;
            }

            .carousel-inner .item a {
                top: 225px;
                left: 50px;
            }
        }

        /* Landscape phone to portrait tablet */
        @media (min-width: 560px) and (max-width: 767px)  {

            .carousel-inner .item #slide1 {
                color: #a4cd40;
                font-size: 40px !important;
                line-height: 40px !important;
                top:30px;
                left:50px;
            }

            .carousel-inner .item a {
                top: 175px;
                left: 50px;
            }
        }

        @media (min-width: 420px) and (max-width: 560px)  {

            .carousel-inner .item #slide1 {
                color: #a4cd40;
                font-size: 30px !important;
                line-height: 40px !important;
                top:20px;
                left:35px;
            }

            .carousel-inner .item a {
                top: 155px;
                left: 35px;
                padding : 4px 12px;
            }
        }

        /* Landscape phones and down */
        @media (min-width: 0px) and (max-width: 419px)  {

            .carousel-inner .item #slide1 {
                color: #a4cd40;
                font-size: 20px !important;
                line-height: 30px !important;
                top:20px;
                left:35px;
            }

            .carousel-inner .item a {
                top: 115px;
                left: 35px;
                padding : 4px 12px;
            }
        }

        .carousel-control {
            top:50%;
            font-size: 70px;
            font-weight: 100;
            line-height: 25px;
            left: -15px;
            color: #DADADC;
            background: white;
            opacity: 100;
            filter: alpha(opacity=100);
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3 );
        }

        .carousel-indicators {
            top:initial;
            bottom: 15px;
            right: 0;
            left:0px;
            margin-left: auto;
            margin-right: auto;
            width: 90px; /* requis pour centrage avec margin auto. Approx. */
        }



        .carousel-indicators li{
            width:10px;
            height:10px;
            -moz-border-radius:50%;
            -webkit-border-radius:50%;
            border-radius:50%;
            margin-right:5px;
        }
        /*
            .carousel-indicators li:hover {
                opacity:0.5;
            }
        */
        .carousel-indicators #switch {
            background-color: #fff;
            font-size:12px;
            width:16px;
            height:16px;
            margin-top:-3px; /* même axe que les petits points */
            opacity:0.6;
        }
        /*
            .carousel-indicators #switch:hover {
                opacity:0.3;
            }
         */
        .carousel-control.right {
            right: -23px;
        }

        .carousel-control.left {
            left: -23px;
        }

        .carousel-caption {
            background:none;
        }

        .icon-play {
            background-position: -263px -72px;
        }

        .icon-pause {
            background-position: -288px -72px;
        }

    </style>
</head>

<body <?php echo $OUTPUT->body_attributes(); ?> ID="ng-app" ng-app="rsgApp" ng-controller="AppController" >

<div id="wrapper" class="container" >
    <?php echo $OUTPUT->standard_top_of_body_html() ?>

    <?php
    include(__DIR__."/statique/custommenu.php");
    ?>

    <div id="myCarousel" class="carousel slide">
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
            <li data-target="#myCarousel" data-slide-to="3"></li>
            <!-- ajout -->
            <li data-target="#myCarousel" data-slide-to="4"></li>
            <li data-target="#myCarousel" data-slide-to="5"></li>
            <!-- Fin ajout  -->
            <li id="switch" class="icon-pause"></li>
        </ol>
        <!-- Carousel items -->
        <div class="carousel-inner">
            <div class="active item">
                <img src="<?php echo $CFG->wwwroot;?>/theme/image.php?theme=cleanrsg&component=theme&image=slider4" alt="s4" width="960" height="360">
                <!-- orthographe -->
            </div>
            <div class="item">
                <img src="<?php echo $CFG->wwwroot;?>/theme/image.php?theme=cleanrsg&component=theme&image=slider2" alt="s1" width="960" height="360">
                <!--  Un millieu... -->
            </div>
            <div class="item">
                <img src="<?php echo $CFG->wwwroot;?>/theme/image.php?theme=cleanrsg&component=theme&image=slider3" alt="s2" width="960" height="360">
                <!-- Un environnement... -->
            </div>
            <div class="item">
                <img src="<?php echo $CFG->wwwroot;?>/theme/image.php?theme=cleanrsg&component=theme&image=slider1" alt="s3" width="960" height="360">
                <!-- <p id="slide1">De nouvelles capsules<br>à venir durant<br>toute l’année !</p>
         <a class="btn-rsg" href="<?php //echo $CFG->wwwroot;?>/mod/rsg/nouvelles">Voir les nouvelles</a>  -->
                <!--  Des capsules... -->
            </div>

            <!-- ajout2 -->
            <div class="item">
                <img src="<?php echo $CFG->wwwroot;?>/theme/image.php?theme=cleanrsg&component=theme&image=slider5" alt="s5" width="960" height="360">
                <!--  Un millieu... -->
            </div>

            <div class="item">
                <img src="<?php echo $CFG->wwwroot;?>/theme/image.php?theme=cleanrsg&component=theme&image=slider6" alt="s6" width="960" height="360">
                <!--  Un millieu... -->
            </div>
            <!-- Fin ajout2 -->

        </div>
        <!-- Carousel nav -->
        <a class="carousel-control left" href="#myCarousel" data-slide="prev" style='color:#DADADC;'>&lsaquo;</a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next" style='color:#DADADC;'>&rsaquo;</a>
    </div>


    <script>
        $(document).ready(function() {
            $(".carousel").carousel("cycle");
            $("#switch").bind('click',function() {
                var switch_class_current = $(this).attr('class');

                if(switch_class_current == 'icon-play')
                {
                    $(".carousel").carousel("cycle");
                    $(this).removeClass("icon-play").addClass("icon-pause");
                }
                else
                {
                    $(".carousel").carousel("pause");
                    $(this).removeClass("icon-pause").addClass("icon-play");
                }
            });
        });
    </script>

    <div id="page">

        <header id="page-header" class="clearfix"></header>

        <div id="page-content">
            <div id="region-bs-main-and-pre">
                <section id="region-main">
                    <div id="page-content">
                        <?php
                        include(__DIR__."/rsg_notification_dialog.php");
                        ?>

                        <!-- SECTION VISITE GUIDÉE -->
                        <h2 class="sectionTitle" style="font-weight:bold;">Comment ça fonctionne&nbsp;?</h2>


                        <!-- Mise en place temporaire selon modèle de la page de login. -->

                        <div class="row-fluid">  <!-- k-->

                            <div id="colg" class="span6" style="margin-bottom:15px;">		<!-- k-->
                                <div class="span3 "></div>
                                <div class="span9">
                                    <div id="video_visite_container" style="text-align: center">
                                        <h2 class="sectionTitle" style="margin-bottom: 15px;">Visitez la plateforme!</h2>
                                        <a id="platform_visit"  href="#myModal" role="button" data-toggle="modal">
                                            <img src="<?php echo $OUTPUT->image_url('icone_video_promo_accueil', 'theme'); ?>">
                                        </a>

                                    </div>
                                </div>
                            </div><!-- k-->

                            <!-- --kane-->

                            <div id="colg" class="span6" style="margin-bottom:15px;">		<!-- k-->

                                <div class="span9">
                                    <!-- Mise en place temporaire selon modèle de la page de login. -->
                                    <div id="video_visite_container" style="text-align: center">
                                        <h2 class="sectionTitle" style="margin-bottom: 15px;">Visitez une capsule!</h2>
                                        <a id="capsule_visit"  href="#myModal" role="button" data-toggle="modal">
                                            <img src="<?php echo $OUTPUT->image_url('img_video_visite', 'theme'); ?>">
                                        </a>

                                    </div>
                                </div>
                                <div class="span3"></div>
                            </div><!-- k-->

                            <!-- kane-- -->
                        </div><!-- k-->


                        <div class="horizontal_separator"></div>

                        <?php
                        /*  AFFICHAGE DES NOUVELLES CAPSULES -*/
                        /* intégration est plus complexe que pour catalogue. Frontpage est directement dans le thème, pas de index.php? */
                        /*  voir dans le haut du fichier (avant le html) pour les initialisations requises. */
                        // Le client ne veut plus voir la liste des nouvelles capsules sur la page Accueil
                        // include($CFG->dirroot."/mod/rsg/statique/affichageCatalogueFrontpage.html");

                        echo $OUTPUT->course_content_header();
                        echo $OUTPUT->main_content();
                        echo $OUTPUT->course_content_footer();

                        ?>
                    </div>
                </section>
            </div>
        </div>

        <footer id="page-footer">
            <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
            <p class="helplink"><?php echo $OUTPUT->page_doc_link(); ?></p>
            <?php
            echo $html->footnote;
            echo $OUTPUT->login_info(true);
            //  echo $OUTPUT->home_link();
            echo $OUTPUT->standard_footer_html();
            ?>
        </footer>

        <?php echo $OUTPUT->standard_end_of_body_html() ?>
    </div>

    <!-- Modal pour affichage vidéo -->
    <div id="myModal" class="modal hide fade " tabindex="-1" role="dialog" static  aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            &nbsp;
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="<?php echo $OUTPUT->image_url('ico_close', 'theme'); ?>"  alt=""></button>
        </div>
        <div class="modal-body">
            <p><div id="video_container"></div></p>
        </div>
    </div>
</div>
<?php
include(__DIR__."/statique/noscript.html");
// Tâche #3892
include(__DIR__."/statique/orientation-overlay.php");
$PAGE->requires->js("/theme/cleanrsg/javascript/rsg_orientation_verif.js");
// fin Tâche #3892
?>
</body>
</html>
