<?php
//
// Layout minimal pour afficher form (contact par ex.).
// Le formulaire pourrait en principe s'afficher par ce layout pour affichage en pop-up
// Ou layout "front" pour affichage en page...
//
// Get the HTML for the settings bits.
$html = theme_cleanrsg_get_html_for_settings($OUTPUT, $PAGE);

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body <?php echo $OUTPUT->body_attributes(); ?> >

<div id="wrapper_form_popup" class="container">
    <div id="page" >
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
    </div>
</div>
</body>
</html>
