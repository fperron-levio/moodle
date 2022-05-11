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
 * List of RSGS in course
 *
 * @package    mod
 * @subpackage rsg
 * @copyright  2014 onwards Cégep@distance
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once __DIR__ . '/locallib.php';

/* On ne devrait pas arriver ici sauf si l'usager explore en modifiant l'url. */
$catalog = new \moodle_url("/mod/rsg/catalogue");
redirect($catalog);