<?php

/**
 * -------------------------------------------------------------------------
 * Example plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Example.
 *
 * Example is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Example is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Example. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2006-2022 by Example plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/example
 * -------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

// Avoid direct file access


// Reference necessary classes 
include ('../../../inc/includes.php');

// Include your plugin's main class
#include("src/Glpibrain.php");

Html::header(__('GLPIBrain Plugin'), $_SERVER['PHP_SELF'], "plugins", Glpibrain::class);


        // Check plugin permissions
        

        // Fetch the tickets data from glpi database
        global $DB;
        $query = "SELECT t.id AS incident_id, t.name AS incident_title, u.name AS assignee_name
                  FROM glpi_tickets t
                  JOIN glpi_tickets_users tu ON t.id = tu.tickets_id AND tu.type = 2
                  JOIN glpi_users u ON tu.users_id = u.id";
        $data = $DB->request($query);

        // Begin HTML Output
        echo "<div class='center'>";

        // Display the table
        echo "<table class='tab_cadre_fixe'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>" . __('ID') . "</th>";
        echo "<th>" . __('Incident') . "</th>";
        echo "<th>" . __('Assignee') . "</th>";
        echo "<th>" . __('Solution') . "</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Populate the table rows
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td><a href='" . Ticket::getSearchURL() . "?id=" . $row['incident_id'] . "'>" . $row['incident_id'] . "</td>";
            echo "<td><a href='" . Ticket::getSearchURL() . "?id=" . $row['incident_id'] . "'>" . $row['incident_title'] . "</a></td>";
            echo "<td>" . $row['assignee_name'] . "</td>";
            #echo "<td>" . $row['solution'] . "</td>";
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
