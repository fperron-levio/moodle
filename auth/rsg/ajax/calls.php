<?php
/*
|	Manage request calls ajax related to "Monb profil form"
|	By: @ACM Mohamed Alami Chahboune -- 4710
*/
require_once('../../../config.php');
require_once ('../../../mod/rsg/monprofil/myprofile_form.php');
require_login();
$PAGE->set_context(context_system::instance());

global $USER,$DB;

if(isset($_POST))
{
	switch($_POST['name'])
	{
		case "rsgphone" : 
			// Server side check if the Phone is valid
			$pattern = '/^([0-9]{3})[ ]?([0-9]{3})[-]?([0-9]{4})$/';
			if(preg_match($pattern,$_POST['value']))
			{
				// Set the phone
				$USER->phone1 = $_POST['value'];
				$DB->update_record('user',$USER);	
			}
		break;
		case "rsgregion" : 
			// Get the offices list and return
			$mform = new rsg_myprofile_form();
			echo json_encode($mform->get_office_data_by_region_id($_POST['value']));
		break;
		case "rsgstatus" :
			// Update rsgstatus within inscription records 
        	$current_inscription = $DB->get_record(RSG_INSCRIPTION, array('userid' => $USER->id));
        	$current_inscription->status = $_POST['value'] ;
            $DB->update_record(RSG_INSCRIPTION,$current_inscription);
		break;
		case "rsgoffice" : 
			// Update rsgregion within inscription records 
        	$current_inscription = $DB->get_record(RSG_INSCRIPTION, array('userid' => $USER->id));
        	$current_inscription->coordofficeid = $_POST['value'] ;
            $DB->update_record(RSG_INSCRIPTION,$current_inscription);
		break;
	}
}