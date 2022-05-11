<?php
require_once __DIR__.'/../../../../auth/rsg/classes/RSGUser.php';
global $USER, $CFG;
$rsg= new \auth\rsg\RSGUser($USER,false);

//var_dump($data_certs);
//die();
?>

<link href="//cdn.datatables.net/1.10.0/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.min.js"></script>

<ul class="nav nav-tabs nav-justified" id="myTab">
  <li class="active"><a href="#home"><?= get_string('mes_infos', 'mod_rsg')?></a></li>
<?php 
// Tâche #3586 : déplacer le changement de mot de passe dans un deuxième onglet.
?>
  <li><a href="#mon_mot_de_passe"><?= get_string('mon_mot_de_passe', 'mod_rsg')?></a></li>
<?php 
// Tâche #3743 : créer un onglet supplémentaire dans la page Mon profil pour modifier le courriel
?>
  <li><a href="#mon_courriel"><?= get_string('mon_courriel', 'mod_rsg')?></a></li>
</ul>

<div class="tab-content">

  <div class="tab-pane active" id="home"><?php echo $data->content; ?></div>
  
  <div class="tab-pane" id="mon_mot_de_passe"><?php echo $data->content_mot_de_passe; ?></div>
  
  <div class="tab-pane" id="mon_courriel"><?php echo $data->content_courriel; ?></div>
  
</div>

