<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 14-08-01
 * Time: 10:25
 * @author Andrei Boris <aboris@crosemont.qc.ca>
 * Date: 2018-01-22
 */


/**
 * Rsg question type upgrade code.
 *
 * @package    qtype
 * @subpackage rsgtype
 * @copyright  2014
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Upgrade code for the rsgtype question type.
 * @param int $oldversion the version we are upgrading from.
 */
function xmldb_auth_rsg_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();
	
    if ($oldversion < 2018012200) {

        // ajouter unique index `numeroidentification`
        $table = new xmldb_table('rsg_inscription');
		$key1 = new xmldb_key('mdl_rsginsc_num_uix', XMLDB_KEY_UNIQUE, array('numeroidentification'), null, null);

        // Conditionally launch add key
        if (!$dbman->field_exists($table, $key1)) {
            $dbman->add_key($table, $key1);
        }
				
		// ajouter unique index `numeroidentification`
        $table = new xmldb_table('rsg_mfa_import');
		$key2 = new xmldb_key('mdl_rsgmfaimpo_num_uix', XMLDB_KEY_UNIQUE, array('numeroidentification'), null, null);
		
		// Conditionally launch add key
        if (!$dbman->field_exists($table, $key2)) {
            $dbman->add_key($table, $key2);
        }
		
		$field1 = new xmldb_field('graceend', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'active');
		// Conditionally launch add field.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
		
		$field2 = new xmldb_field('gracenotice', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'graceend');
		// Conditionally launch add field.
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        // Assign savepoint reached.
        upgrade_mod_savepoint(true, 2017011900, 'assign');
    }


    if ($oldversion < 2014080501) {

        // Define field to be added to assign.
        $table = new xmldb_table('rsg_inscription');

        $field = new xmldb_field('status', XMLDB_TYPE_INTEGER, '1', null,
            XMLDB_NOTNULL, null, '8', 'coordofficeid');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // qtype savepoint reached.
        upgrade_plugin_savepoint(true, 2014070104, 'auth', 'rsg');
    }
    return true;
}