<?php

/**
 * Description of RSG_Form
 *
 * @author Nelson Moller <nmoller at cegepadistance.ca>
 */

require_once ($CFG->dirroot.'/config.php');
require_once __DIR__.'/HTML_QuickForm_Renderer_RSG.php';

abstract class RSG_Form extends moodleform{
    //On a besoin de action si l'on veut envoyer le form vers autre chose que lui même
    function __construct($action=NULL,$options=array('class'=>'form-horizontal')) {
       // parent::moodleform($action, NULL, 'post', '', $options);
        parent::__construct($action, NULL, 'post', '', $options);
    }
    
    function display() {
        
        // Assigne rendered temporairement.
        $GLOBALS['_HTML_QuickForm_default_renderer'] =new HTML_QuickForm_Renderer_RSG();
        
        parent::display();
        
	// Reset renderer.
        unset($GLOBALS['_HTML_QuickForm_default_renderer']);
    }


     public function get_adm_region_data() {
        global $DB;

        // Aller chercher la liste des régions.
        $region_list = $DB->get_records(RSG_ADMINREGION, null, 'regionname ASC', 'id, regionname');

        // Remplir l'array pour utiliser dans le "select".
        $adm_region = array();

        // Ajouter entrée manuelle pour "aucune sélection".
        $adm_region[0] = get_string('auth_rsgregionselect', 'auth_rsg');

        foreach ($region_list as $regionitem) {
            $adm_region[($regionitem->id)] = $regionitem->regionname;
        }

        return $adm_region;
    }

    public function get_office_data($regiondata) {
        global $DB;

        $region_office = array();
        $office_select_string = get_string('auth_rsgofficeselect', 'auth_rsg');

        // Cette liste est conditionnelle à la sélection des régions et sera géré par au niveau du formulaire.
        // Aller chercher la liste des bureaux.

        foreach ($regiondata as $k => $v) {
            /*  echo "\$regiondata[$k] => $v.\n"; */

            // Skip clé 0 spéciale qui ne provient pas de la bd.
            if ($k != 0) { /* ATTENTION: Voir get_adm_region_data, $k est bien regionid et non pas id! */
                $office_list = $DB->get_records(RSG_COORDOFFICE, array('regionid' => $k), 'officename ASC', 'id, officename');

                // Remplir l'array pour utiliser plus dans le "select".
                $office = array();

                // Ajouter entrée manuelle pour "aucune sélection".
                $office[0] = $office_select_string;

                foreach ($office_list as $officeitem) {
                    $office[($officeitem->id)] = $officeitem->officename;
                }

                $region_office[$k] = $office;
            } else {
                // Aucun bureaux pour région 0.
                $region_office[0] = array();
            }
        }

        return $region_office;
    }

    public function get_office_data_by_region_id($region_id) {
        global $DB;

        $office_list = $DB->get_records(RSG_COORDOFFICE, array('regionid' => $region_id), 'officename ASC', 'id, officename');

        foreach ($office_list as $officeitem) {
            $office_array[($officeitem->id)] = $officeitem->officename;
        }
        return $office_array;
    }

    public function get_statusdata() {
        
        $statusdata = array();
        
        for ($i = 0; $i < 10; $i++) {
            $statusdata[] = get_string(('auth_rsg_form_status_' . $i), 'auth_rsg');
        }
        
        return $statusdata;
    }
}

