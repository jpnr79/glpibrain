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

include('../../../inc/includes.php');

$forbidden_chars = ['&#60;p&#62;', '&#60;/p&#62;', '"', '=', '&#39;', '&#145;', '&#146;', '&#8216', '&#8217', '&#8220', '&#8221', '&#34;', '" ', '="" ', '."'];

Html::header(__('GLPIBrain Plugin'), $_SERVER['PHP_SELF'], "plugins", "GLPIBrain");
$glpibrain = new Glpibrain();

echo <<<HTML
  <div class='center'>
  <link rel='stylesheet' type='text/css' href='css/incident_table.css'>
  <h1>Incidents table</h1>
  <script type='text/javascript' src='js/incident_table.js'></script>
  <table id=incidentTable class='tab_cadre_fixes'>
    <thead>
      <tr>
        <th>ID</th>
        <th>Incident</th>
        <th>Date</th>
        <th>Assignee</th>
        <th>Status</th>
        <th>Category</th>
        <th>Expected Solution</th>
        <th>Real Solution/Category</th>
      </tr>
    </thead>
  <tbody>
HTML;

if ($glpibrain->getOllamaStatus()) {
  $data = $glpibrain->getIncidents();
  if (empty($data)) {
    echo <<<HTML
    <tr>
      <td colspan='8'>No incidents found</td>
    </tr>
    HTML;
  } else {
    echo <<<HTML
    <tr class='filters'>
      <th><input type='number' id='searchById' onkeyup='searchonTableId()' onchange='searchonTableId()' placeholder='Id..'></th>
      <th><input type='text' id='searchByTitle' onkeyup='searchonTableTitle()' onchange='searchonTableTitle()' placeholder='Title..'></th>
      <th><input type='date' id='searchByDate' onkeyup='searchonTableDate()' onchange='searchonTableDate()' placeholder='Date..'></th>
      <th><input type='text' id='searchByAsignee' onkeyup='searchonTableAsignee()' onchange='searchonTableAsignee()' placeholder='Assignee..'></th>
      <th><select id='searchByStatus' onchange='searchonTableStatus()'>
              <option value=''>All</option>
              <option value='Processing (Assigned)'>Processing (Assigned)</option>
              <option value='Processing (Planned)'>Processing (Planned)</option>
              <option value='Pending'>Pending</option>
              <option value='Solved'>Solved</option>
              <option value='Closed'>Closed</option>
              <option value='Unknown'>Unknown</option>
              </select></td>
      <th><input type='text' id='searchByCategory' onkeyup='searchonTableCategory()' placeholder='Category..'></th>
    </tr>
    HTML;

    // Populate the table rows
    for ($index = 0; $index < count($data['incident_id']); $index++) {
      echo "<tr>";

      #Add a link to the ticket on hover show the incident_content in a box
      echo "<td><a href='" . Ticket::getSearchURL() . "?id=" . $data['incident_id'][$index] . "'>" . $data['incident_id'][$index] . "</a></td>";
      echo "<td> 
                <a href='" . Ticket::getSearchURL() . "?id=" . $data['incident_id'][$index] . "' onmouseover='showDetail(\"" . str_replace($forbidden_chars, '', $data['incident_content'][$index]) . "\")' onmouseleave=closeDiv()>" . $data['incident_title'][$index] . "</a>
          </td>";
      echo "<td>" . $data['incident_date'][$index] . "</td>";
      echo "<td>" . $data['assignee_name'][$index] . "</td>";
      //if php gives warning show loading from javascript
      echo "<td>" . $glpibrain->getIncidentStatus($data['incident_status'][$index]) . "</td>";
      echo "<td>" . $glpibrain->getIncidentCategory($data['incident_id'][$index], $data['category_id'][$index]) . "</td>";
      echo "<td>" . $glpibrain->getIncidentSolution($data['incident_id'][$index]) . "</td>";
      #the button executes openWindow and send as arguments the incident_id, the incident content and, hidden, the csrf token
      echo "<td><button onclick='openWindow(" . $data['incident_id'][$index] . ",\"" . str_replace($forbidden_chars, '', $data['incident_content'][$index]) . "\",\"" . Session::getNewCSRFToken() . "\")'>" . __('S') . "</button>";
      echo "<button onclick='openWindowCategory(" . $data['incident_id'][$index] . ",\"" . str_replace($forbidden_chars, '', $data['incident_content'][$index]) . "\",\"" . Session::getNewCSRFToken() . "\")'>" . __('C') . "</button></td>";
      echo "</td>";
      echo "</tr>";
      Html::closeform();
    }

    echo <<<HTML
    </tbody>
    </table>
    </div>
    HTML;
  }
} else {
  echo <<<HTML
  <tr>
  <td colspan='8'>Ollama is not running</td>
  </tr>
  HTML;
}

// Register the display function to be called by GLPI
if (Session::getCurrentInterface() == 'helpdesk') {
  Html::footer();
} else {
  Html::helpFooter();
}
