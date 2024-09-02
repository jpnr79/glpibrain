<?php

/**
 * -------------------------------------------------------------------------
 * GLPIBrain plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPIBrain.
 *
 * GLPIBrain is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPIBrain is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPIBrain. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2006-2022 by GLPIBrain plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/glpibrain
 * -------------------------------------------------------------------------
 */

//This file is used by the javascript code to call php functions using AJAX
include('../../../inc/includes.php');

$glpibrain = new GlpiBrain();

if (isset($_POST['action']) && $_POST['action'] == 'retrainSolution') {
    
    // Check if the arguments are set
    if (!isset($_POST['arguments'])) {
        echo "Arguments not set";
        exit();
    }
    // Check if the arguments are an array
    if (!is_array($_POST['arguments'])) {
        echo "Arguments are not an array";
        exit();
    }
    // Check if the arguments are of the correct length
    if (count($_POST['arguments']) != 2) {
        echo "Incorrect number of arguments";
        exit();
    }
    // Check if the arguments are of the correct type
    if (!is_string($_POST['arguments'][0]) || !is_string($_POST['arguments'][1])) {
        echo "Incorrect argument type";
        exit();
    }
    // Call the retrainSolution function
    $glpibrain->retrainSolution($_POST['arguments'][0], $_POST['arguments'][1]);
    echo json_encode(['status' => 'success']);
    exit();
}
