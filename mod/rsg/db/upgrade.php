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

/**
 * IMS CP module upgrade code
 *
 * @package    mod
 * @subpackage rsg
 * @copyright  2009 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_rsg_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2014082709) {

        // Define field to be added to assign.
        $table = new xmldb_table('rsg');

        $field = new xmldb_field('keywords', XMLDB_TYPE_CHAR, '1023', null,
        null, null, null, 'outil');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('duree', XMLDB_TYPE_CHAR, '255', null,
        true, null, null, null);

        // Drop obsolete duree, on va perdre des données mais ça va être facile à corriger.
        // Ou ajouter un update automatique (enlever h, string to int * conversion en minutes)?
        // Mettre un default? 120
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }
        
        $field = new xmldb_field('duration_capsule', XMLDB_TYPE_INTEGER, '10', null,
        null, null, 90, 'uec');

        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        $field = new xmldb_field('duration_autoevaluation', XMLDB_TYPE_INTEGER, '10', null,
        null, null, 30, 'duration_capsule');
        
        // Conditionally launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        // rsg savepoint reached.
        upgrade_mod_savepoint(true, 2014082709, 'rsg');
    }
    
    // Moodle v2.2.0 release upgrade line
    // Put any upgrade step following this

    // Moodle v2.3.0 release upgrade line
    // Put any upgrade step following this

    // Moodle v2.4.0 release upgrade line
    // Put any upgrade step following this

    // Moodle v2.5.0 release upgrade line.
    // Put any upgrade step following this.


    return true;
}