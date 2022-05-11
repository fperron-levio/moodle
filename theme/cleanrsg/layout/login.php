<?php
// 
// RSG CUSTOM LOGIN THEME.
// todo: Mettre lien vers documentation Redmine?
// Cette page contourne complètement les styles et l'inclusion de scripts de Moodle.
// Après discussion la duplication de certains fichiers (bootstrap et jquery) va nous donner une plus grande
// Flexibilité lors de la mise à jour des thèmes et aussi d'avoir une interface d'administration propre (sans styles RSG).
// Si themeByPass, ce sont des styles, images et scripts qui ne vont pas passer par la gestion de cache de Moodle 
// même si classé dans les dossiers standards de thème).

echo $OUTPUT->doctype(); 

?>

<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />

    <script>
        // Quick fix. M.cfg.wwwroot n'est pas accessible dans login.
        var wwwroot = "<?= $CFG->wwwroot ?>";
    </script>

    <!-- Chargement des fichiers css de bootstrap et mediaelement du dossier javascript pour ne pas avoir à modifer les path dans les librairies -->
	<link href="<?= $CFG->wwwroot ?>/theme/cleanrsg/javascript/themeByPass/bootstrap_2.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?= $CFG->wwwroot ?>/theme/cleanrsg/javascript/themeByPass/bootstrap_2.3.2/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
    <link href="<?= $CFG->wwwroot ?>/theme/cleanrsg/javascript/mediaelement_2.15.1.fix_CAD/mediaelementplayer.min.css" rel="stylesheet" type="text/css"> 
    <link href="<?= $CFG->wwwroot ?>/theme/cleanrsg/style/themeByPass/login.css" rel="stylesheet" type="text/css">
    <link href="<?= $CFG->wwwroot ?>/theme/cleanrsg/style/themeByPass/fonts.css" rel="stylesheet" type="text/css">   
     
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="" />
	<meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<script type="text/javascript">
//<![CDATA[
// Scénario #3090
if (navigator.userAgent.indexOf("MSIE") >= 0) {
  var navigateurie = "obsolete";
}
//]]>
</script>
<style type="text/css">
<!--
#browser_check { 
  display: none;
}
-->
 </style>
 
</head>

<body class="login"  <?php echo $OUTPUT->body_attributes(); ?>>

<div class="wrapper" >	

<?php echo $OUTPUT->standard_top_of_body_html() ?>
	
	<?php echo $OUTPUT->main_content(); ?>
   
	<div id="backgroundPersonnages" class="hidden-phone"><img id="personnages" src="<?php echo $OUTPUT->image_url('backgroundPersonnages_20170712-01', 'theme'); ?>" alt=""></div>
   
    <footer class="links" > 
        <!-- login_info(true) non requis, rsg_links seulement -->
        <?php echo $OUTPUT->rsg_links(); ?>
    </footer>

	<div style="background:#2b4652">    
    <div class="logos" style="width:768px;margin-left:auto;margin-right:auto">
		<div id="footer_mobile" class="visible-phone">
			<div class="footer_logo"><a href="http://www.crosemont.qc.ca/accueil"><img  class="cad-logo" src="<?php echo $OUTPUT->image_url('logo_college_rosemont', 'theme'); ?>" alt=""></a></div>
			<div class="footer_logo logo_qc" style="margin-right:34%;"><a href="https://www.mfa.gouv.qc.ca/fr/Pages/index.aspx"><img src="<?php echo $OUTPUT->image_url('logo_qc', 'theme'); ?>" alt=""></a></div>
		</div>
		<div id="footer_default" class="hidden-phone">
			<div class="footer_logo  logo_college_rosemont"><a href="http://www.crosemont.qc.ca/accueil"  target="_blank"><img class="cad-logo" src="<?php echo $OUTPUT->image_url('logo_college_rosemont', 'theme'); ?>" alt=""></a></div>
			<div class="footer_logo logo_qc"><a href="https://www.mfa.gouv.qc.ca/fr/Pages/index.aspx"  target="_blank"><img src="<?php echo $OUTPUT->image_url('logo_qc', 'theme'); ?>"  alt=""></a></div>
		</div>
	</div>
	</div>
	

	<div id="noticeContainer" class="visible-phone">
		<div id="notice">
			Cette formation est optimis&#233;e pour les tablettes num&#233;riques et les ordinateurs. Nous vous invitons &#224; utiliser un plus grand &#233;cran pour l’appr&#233;cier pleinement.
		</div>
	</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade " tabindex="-1" role="dialog" static  aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
  	&nbsp;
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="<?php echo $OUTPUT->image_url('ico_close', 'theme'); ?>"  alt=""></button>
  </div>
  <div class="modal-body">
    <p><div id="video_container"></div></p>
  </div>
</div>

<?php
    include(__DIR__."/statique/noscript.html");
?>
</div>

<script type="text/javascript">
//<![CDATA[
// Scénario #3090
if(navigateurie && navigateurie=="obsolete") {
  document.getElementById("browser_check").style.display = "block";
}
//]]>
</script>

</body>
</html>
