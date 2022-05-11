<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 14-08-01
 * Time: 10:25
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
function xmldb_qtype_rsgtype_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2014070104) {

        // Define field to be added to assign.
        $table = new xmldb_table('qtype_rsgtype_options');

        $field = new xmldb_field('checkshow', XMLDB_TYPE_INTEGER, '1', null,
            XMLDB_NOTNULL, null, '1', 'responsefieldlines');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('responseformat', XMLDB_TYPE_CHAR, '16', null,
            null, null, 'editor', 'questionid');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }



        // qtype savepoint reached.
        upgrade_plugin_savepoint(true, 2014070104, 'qtype', 'rsgtype');
    }
    return true;
}