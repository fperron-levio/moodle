<?php
header("Content-type:text/css"); 
chdir('..');
require_once('../../../config.php');
?>

/*<style type="text/css">*/

@font-face {
  font-family: 'Fjalla One';
  font-style: normal;
  font-weight: 400;
  src: local('Fjalla One'), local('FjallaOne-Regular'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/rxxXUYj4oZ6Q5oDJFtEd6hsxEYwM7FgeyaSgU71cLG0.woff) format('woff');
}

@font-face {
  font-family: 'Sanchez';
  font-style: normal;
  font-weight: 400;
  src: local('Sanchez'), local('Sanchez-Regular'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/mx466fsxfR1AA3OwUm3waQ.woff) format('woff');
}

@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 300;
  src: local('Open Sans Light'), local('OpenSans-Light'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/DXI1ORHCpsQm3Vp6mXoaTXhCUOGz7vYGh680lGh-uXM.woff) format('woff');
}

@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 400;
  src: local('Open Sans'), local('OpenSans'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.woff) format('woff');
}

@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 600;
  src: local('Open Sans Semibold'), local('OpenSans-Semibold'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/MTP_ySUJH_bn48VBG8sNSnhCUOGz7vYGh680lGh-uXM.woff) format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 700;
  src: local('Open Sans Bold'), local('OpenSans-Bold'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/k3k702ZOKiLJc3WVjuplzHhCUOGz7vYGh680lGh-uXM.woff) format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 800;
  src: local('Open Sans Extrabold'), local('OpenSans-Extrabold'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/EInbV5DfGHOiMmvb1Xr-hnhCUOGz7vYGh680lGh-uXM.woff) format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: italic;
  font-weight: 300;
  src: local('Open Sans Light Italic'), local('OpenSansLight-Italic'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/PRmiXeptR36kaC0GEAetxh_xHqYgAV9Bl_ZQbYUxnQU.woff) format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: italic;
  font-weight: 400;
  src: local('Open Sans Italic'), local('OpenSans-Italic'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/xjAJXh38I15wypJXxuGMBobN6UDyHWBl620a-IRfuBk.woff) format('woff');
}

@font-face {
  font-family: 'Maven Pro';
  font-style: normal;
  font-weight: 400;
  src: local('Maven Pro Regular'), local('MavenProRegular'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/MG9KbUZFchDs94Tbv9U-pT8E0i7KZn-EPnyo3HZu7kw.woff) format('woff');
}
@font-face {
  font-family: 'Maven Pro';
  font-style: normal;
  font-weight: 500;
  src: local('Maven Pro Medium'), local('MavenProMedium'), url(<?= $CFG->wwwroot ?>/theme/cleanrsg/fonts/SQVfzoJBbj9t3aVcmbspRnhCUOGz7vYGh680lGh-uXM.woff) format('woff');
}

/*</style>*/