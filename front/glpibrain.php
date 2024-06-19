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
include ('../../../inc/includes.php');

// Include your plugin's main class
#include("src/Glpibrain.php");

Html::header(__('GLPIBrain Plugin'), $_SERVER['PHP_SELF'], "plugins", Glpibrain::class);

        // Begin HTML Output
        echo "<div class='center'>";
        echo "<link rel='stylesheet' type='text/css' href='css/incident_table.css'>";
        echo "<h1>" . __('Incidents table') . "</h1>";

        echo '<script>
            function RealSol() {
                var x;
                var site = prompt("Please enter your name:", "Write Here");
                if (site != null) {
                    x = "Welcome to " + Popupsmart + "! Have a great day";
                    document.getElementById("demo").innerHTML = x;
                }
            }
            </script>';
        

        // Display the table
        echo "<table class='tab_cadre_fixe s'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>" . __('ID') . "</th>";
        echo "<th>" . __('Incident') . "</th>";
        echo "<th>" . __('Description') . "</th>";
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

        // Populate the table rows
        for ($index = 0; $index < count($data['incident_id']); $index++) {
            
            echo "<tr>";
            echo "<td><a href='" . Ticket::getSearchURL() . "?id=" . $data['incident_id'][$index] . "'>" . $data['incident_id'][$index] . "</a></td>";
            echo "<td><a href='" . Ticket::getSearchURL() . "?id=" . $data['incident_id'][$index] . "'>" . $data['incident_title'][$index] . "</a></td>";
            echo "<td>" . $data['incident_content'][$index] . "</td>";
            echo "<td>" . $data['incident_date'][$index] . "</td>";
            echo "<td>" . $data['assignee_name'][$index] . "</td>";
            echo "<td>" . $glpibrain->getIncidentStatus($data['incident_status'][$index]) . "</td>";
            echo "<td>" . $glpibrain->getIncidentCategory($data['incident_id'][$index],$data['category_id'][$index]) . "</td>";
            echo "<td>" . $glpibrain->getIncidentSolution($data['incident_id'][$index]) . "</td>";
            #Add a button that displays a pop up window with a form to add a real solution and button to submit the form
            echo "<td><button onclick='RealSol()'>Add Solution</button>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";

// Register the display function to be called by GLPI
if (Session::getCurrentInterface() == 'central') {
    Html::footer();
 } else {
    Html::helpFooter();
 }
