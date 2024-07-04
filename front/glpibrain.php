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


// Reference necessary classes 
include('../../../inc/includes.php');

//Session::checkRight('plugin_glpibrain', READ);

Html::header(__('GLPIBrain Plugin'), $_SERVER['PHP_SELF'], "plugins", "GLPIBrain");

echo "<div class='center'>";
echo "<link rel='stylesheet' type='text/css' href='css/incident_table.css'>";
echo "<h1>" . __('Incidents table') . "</h1>";
// Create the searchTable function
echo "<script type='text/javascript' src='js/incident_table.js'></script>";


// Display the table
echo "<table id=incidentTable class='tab_cadre_fixes'>";
echo "<thead>";
echo "<tr>";
echo "<th>" . __('ID') . "</th>";
echo "<th>" . __('Incident') . "</th>";
echo "<th>" . __('Date') . "</th>";
echo "<th>" . __('Assignee') . "</th>";
echo "<th>" . __('Status') . "</th>";
echo "<th>" . __('Category') . "</th>";
echo "<th>" . __('Expected Solution') . "</th>";
echo "<th>" . __('Real Solution') . "</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

// Create a new instance of the Glpibrain class and get the incidents
$glpibrain = new Glpibrain();
$data = $glpibrain->getIncidents();

if (empty($data)) {
    echo "<tr>";
    echo "<td colspan='8'>" . __('No incidents found') . "</td>";
    echo "</tr>";
} else {
    echo "<tr>";
    echo "<td><input type='number' id='searchInput' onkeyup='searchonTable(0)' onchange='searchonTable(0)' placeholder='Search by id..'></td>";
    echo "<td><input type='text' id='searchInput' onkeyup='searchonTable(1)' onchange='searchonTable(1)' placeholder='Search by title..'></td>";
    echo "<td><input type='date' id='searchInput' onkeyup='searchonTable(2)' onchange='searchonTable(2)' placeholder='Search by date..'></td>";
    echo "<td><input type='text' id='searchInput' onkeyup='searchonTable(3)' onchange='searchonTable(3)' placeholder='Search by assignee..'></td>";
    echo "<td><select id='searchSelect' onchange='searchonTable(4)'>
            <option value=''>" . __('All') . "</option>
            <option value='1'>" . __('Processing (Assigned)') . "</option>
            <option value='2'>" . __('Processing (Planned)') . "</option>
            <option value='3'>" . __('Pending') . "</option>
            <option value='4'>" . __('Solved') . "</option>
            <option value='5'>" . __('Closed') . "</option>
            <option value='6'>" . __('Unknown') . "</option>
            </select></td>";
    echo "<td><input type='text' id='searchInput' onkeyup='searchonTable(5)' placeholder='Search by category..'></td>";
    echo "</tr>";

    // Populate the table rows
    for ($index = 0; $index < count($data['incident_id']); $index++) {
        echo "<tr>";

        #Add a link to the ticket on hover show the incident_content in a box
        echo "<td><a href='" . Ticket::getSearchURL() . "?id=" . $data['incident_id'][$index] . "'>" . $data['incident_id'][$index] . "</a></td>";
        echo "<td> 
                <a href='" . Ticket::getSearchURL() . "?id=" . $data['incident_id'][$index] . "' onmouseover='showDetail(\"" . $data['incident_content'][$index] . "\")' onmouseleave=closeDiv()>" . $data['incident_title'][$index] . "</a>
          </td>";
        echo "<td>" . $data['incident_date'][$index] . "</td>";
        echo "<td>" . $data['assignee_name'][$index] . "</td>";
        echo "<td>" . $glpibrain->getIncidentStatus($data['incident_status'][$index]) . "</td>";
        echo "<td>" . $glpibrain->getIncidentCategory($data['incident_id'][$index], $data['category_id'][$index]) . "</td>";
        echo "<td>" . $glpibrain->getIncidentSolution($data['incident_id'][$index]) . "</td>";
        #the button executes openWindow and send as arguments the incident_id, the incident content and, hidden, the csrf token
        echo "<td><button onclick='openWindow(" . $data['incident_id'][$index] . ", \"" . $data['incident_content'][$index] . "\", \"" . Session::getNewCSRFToken() . "\")'>" . __('Retrain') . "</button></td>";
        echo "</td>";
        echo "</tr>";
        Html::closeform();
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
 
}

// Register the display function to be called by GLPI
if (Session::getCurrentInterface() == 'helpdesk') {
    Html::footer();
} else {
    Html::helpFooter();
}
