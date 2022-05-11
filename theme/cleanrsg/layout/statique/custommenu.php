<?php ?>

<!-- Menu desktop -->
<!-- Le menu dans le catalogue requiert un ajustement particulier. -->
<nav role="navigation" class="navbar <?php echo (isset($is_in_catalogue) ? "" : "navbar-inner") ?>" id="topmenu">
    <h1 class="span3 logo-rsg">
      <a  href="<?php echo $CFG->wwwroot;?>"></a>
    </h1>
    <div class="nav-collapse collapse">
      <?php echo $OUTPUT->custom_menu(); ?>
    </div>
</nav>

<!-- Menu mobile : ATTENTION: COMPATIBLE COLLAPSE BOOTSTRAP 2.3.2 NON COMPATIBLE AVEC BOOTSTRAP COLLAPSE MOODLE. -->
<!-- Le menu dans le catalogue requiert un ajustement particulier. -->
<nav role="navigation" class="navbar <?php echo (isset($is_in_catalogue) ? "navbar-inner-catalogue" : "navbar-inner") ?>  adaptive-mobile" id="topmenu-mobile">
    <div class="row">
        <h1 class="span12 logo-rsg">
          <a  href="<?php echo $CFG->wwwroot;?>"></a>
        </h1>
    </div>    
	
	
	
	<!-- style='display:block; ajouter pour faire apparaitre le menu hamburger qui avait disparu - kane --> 
	
<!--	
    <a style='display:block; ' class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse-mobile">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>
    <div class="clear"></div>
    <div class="nav-collapse-mobile collapse">
      <!--?php echo $OUTPUT->custom_menu(NULL, true); ?>
    </div>
</nav>
-->

<!-- #4959  -->

	<!-- style='display:block; ajouter pour faire apparaitre le menu hamburger qui avait disparu - kane --> 
    <a   style='display:block; ' class="btn btn-navbar transitionrsgk" data-toggle="collapse" data-target=".nav-collapse-mobile">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>
    <div class="clear"></div>	
	
<div id="monidrsgk"  style="display:none"  >
 <?php echo $OUTPUT->custom_menu(NULL, true); ?>
  </div>
	
	
</nav>



<script>
$(document).ready(function(){
    $(".transitionrsgk").click(function(){
        $("#monidrsgk").slideToggle(450);
    });
});
</script>

<!-- #4959  -->