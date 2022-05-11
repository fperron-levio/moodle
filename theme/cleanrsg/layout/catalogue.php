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
</head>

<body <?php echo $OUTPUT->body_attributes(); ?> ID="ng-app" ng-app="rsgApp" ng-controller="AppController">
    
<!-- Important: CatalogController need to be declared at a level that include the search widget -->    
<div id="wrapper" class="container" ng-controller="CatalogController">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

    <?php
        /* Le menu dans le catalogue requiert un ajustement particulier. */
        /* On pourrait alternativement passer en params les classes à assigner. */
        // $is_in_catalogue = true; #3710
        include(__DIR__."/statique/custommenu.php");
    ?>  

    <div id="page">
        <header id="page-header" class="clearfix">
			<?php /* Tâche #3870 */ ?>
			<div class="row-fluid">
				<div id="header" class="span12" style="margin-bottom:-30px;">
					<form class="navbar-search pull-right">
						<div class="right-inner-addon">
                            <div id="rsg-search-button" ng-click="closeTabletKeyboard($event)"><i class="icon-search icon-white"></i></div>
							<input id="search_capsule" name="searchinput" type="text" class="search-query" placeholder="Rechercher" ng-model="q" ng-change="my_search(q)" ng-keypress="closeTabletKeyboard($event)" style="margin-right:0;margin-top:0;">
						</div>
					</form>
				</div>
			</div>
			<?php /* fin Tâche #3870 */ ?>
		</header>
        <div id="page-content">
            <div id="region-bs-main-and-pre">
                <section id="region-main">
                    <?php
                    echo $OUTPUT->course_content_header();
                    echo $OUTPUT->main_content();
                    echo $OUTPUT->course_content_footer();
                    ?>
                </section>
            </div>
        </div>
    
        <footer id="page-footer">
            <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
            <p class="helplink"><?php echo $OUTPUT->page_doc_link(); ?></p>
            <?php
            echo $html->footnote;
            echo $OUTPUT->login_info(true);
            echo $OUTPUT->standard_footer_html();
            ?>
        </footer>
            
        <?php echo $OUTPUT->standard_end_of_body_html() ?>
    </div>
</div>
<?php
include(__DIR__."/rsg_notification_dialog.php");
?>
<?php
    //Bloque à ajouter dans les templates qui permettent accès aux capsules
    global $PAGE,$DB,$USER,$CFG;
    require_once $CFG->dirroot.'/mod/rsg/classes/rsg_access.php';
    $rsg=new \auth\rsg\RSGUser($USER,false);
    $acces=new \rsg_access($rsg);
    $data = $acces->getInitData();
    $PAGE->requires->js_init_call('M.mod_rsg.init', array($data['acl'], $data['visits']),true);
    //fin Bloque
    include(__DIR__."/statique/noscript.html");
    // Tâche #3892
    include(__DIR__."/statique/orientation-overlay.php");
    $PAGE->requires->js("/theme/cleanrsg/javascript/rsg_orientation_verif.js");
    // fin Tâche #3892
?>
</body>
</html>
