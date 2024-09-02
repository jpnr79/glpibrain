<?php

/**
 * -------------------------------------------------------------------------
 * Glpibrain plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Glpibrain.
 *
 * Glpibrain is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Glpibrain is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Glpibrain. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2006-2022 by Glpibrain plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/glpibrain
 * -------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Nicolas Sela Ramos
// Purpose of file: Glpibrain class
// ----------------------------------------------------------------------

require __DIR__ . '/../vendor/autoload.php';

include('../vendor/autoload.php');

// Avoid direct access to the file
if (!defined('GLPI_ROOT')) {
   die("You may not access this file directly");
}

class Glpibrain extends CommonDBTM
{

   static $tags = '[GLPIBRAIN_ID]';

   /**
    * Shows the glpibrain plugin in the menu
    * @see CommonGLPI::getMenuName()
    **/
   static function getMenuName()
   {
      return _n('GlpiBrain', 'GLPIBrain', 1, 'glpibrain');
   }

   /**
    * Link to the page of the plugin
    * @see CommonGLPI::getMenuContent()
    **/

   static function getMenuContent()
   {

      $plugin_page              = "/plugins/glpibrain/front/glpibrain.php";
      $menu                     = [];
      //Menu entry in tools
      $menu['title']            = self::getMenuName();
      $menu['page']             = $plugin_page;
      $menu['links']['search']  = $plugin_page;

      if (
         Session::haveRight(static::$rightname, UPDATE)
         || Session::haveRight("config", UPDATE)
      ) {
         $menu['icon'] = "ti ti-brain";
      }

      return $menu;
   }

   /**
    * This function retrieves all the incidents from the database and returns them
    * @return array $incidents
    */

   public function getIncidents()
   {
      // Fetch the tickets data from glpi database
      global $DB;
      $query = "SELECT ticket.id AS incident_id, ticket.name AS incident_title, ticket.date_creation AS incident_date, u.name AS assignee_name, ticket.status AS incident_status, IFNULL(ticket.itilcategories_id, 0) AS category_id, ticket.content AS incident_content
                  FROM glpi_tickets ticket
                  JOIN glpi_tickets_users tu ON ticket.id = tu.tickets_id AND tu.type = 2
                  JOIN glpi_users u ON tu.users_id = u.id
                  WHERE ticket.is_deleted = 0";

      $data = $DB->request($query);
      if ($data) {
         $dataArray = [];
         foreach ($data as $row) {
            $dataArray['incident_id'][] = $row['incident_id'];
            $dataArray['incident_title'][] = $row['incident_title'];
            #take off the &#60;/p&#62; string from the content that is the <p> tag in ascii
            $dataArray['incident_content'][] = str_replace(['&#60;p&#62;', '&#60;/p&#62;'], '', $row['incident_content']);
            $dataArray['incident_date'][] = $row['incident_date'];
            $dataArray['assignee_name'][] = $row['assignee_name'];
            $dataArray['incident_status'][] = $row['incident_status'];
            $dataArray['category_id'][] = $row['category_id'];
         }
         return $dataArray;
      } else {
         return [];
      }
   }

   /**
    * This function retrieves the incident data from the database
    * @param int $id
    * @return string $state
    */

   public function getIncidentStatus($sid)
   {
      $states = [
         1 => 'New',
         2 => 'Processing (Assigned)',
         3 => 'Processing (Planned)',
         4 => 'Pending',
         5 => 'Solved',
         6 => 'Closed'
      ];
      return $states[$sid] ?? 'Unknown';
   }

   /**
    * This function retrieves the incident data from the database
    * @param int $id
    * @return array $incident
    */

   private function getIncident($id)
   {
      // Fetch the ticket data from glpi database
      if ($id) {
         global $DB;
         $query = "SELECT ticket.id AS incident_id, ticket.name AS incident_title, ticket.date_creation AS incident_date, u.name AS assignee_name, ticket.status AS incident_status, IFNULL(ticket.itilcategories_id, 0) AS category_id, ticket.content AS incident_content, ticket.is_deleted AS incident_deleted
                     FROM glpi_tickets ticket
                     JOIN glpi_tickets_users tu ON ticket.id = tu.tickets_id AND tu.type = 2
                     JOIN glpi_users u ON tu.users_id = u.id
                     WHERE ticket.id = $id AND ticket.is_deleted = 0";

         $data = $DB->request($query);
         $dataArray = [];
         if ($data) {
            foreach ($data as $row) {
               $dataArray = [
                  'incident_id' => $row['incident_id'],
                  'incident_title' => $row['incident_title'],
                  'incident_date' => $row['incident_date'],
                  'assignee_name' => $row['assignee_name'],
                  'incident_status' => $row['incident_status'],
                  'category_id' => $row['category_id'],
                  'incident_content' => $row['incident_content']
               ];
            }
         } else {
            echo "No data found";
         }
         return $dataArray;
      } else {
         return [];
      }
   }

   /**
    * This function retrieves the incident category from the database if it exists
    * @param int $id
    * @return string $category
    */

   public function getIncidentCategory($id, $cid)
   {
      // Fetch the category data from glpi database
      global $DB;
      $query = "SELECT name FROM glpi_itilcategories WHERE id = $cid";
      $data = $DB->request($query);
      #if rows are returned, then the category exists
      if ($data->numRows() > 0) {
         foreach ($data as $row) {
            $category = $row['name'];
            return $category;
         }
      } else {
         $category = $this->classifyIncident($id);
         return $category;
      }
   }

   private function startOllama()
   {
      $output = shell_exec('docker start ollama');
      shell_exec('docker exec -it ollama ollama run llama3');
      while (str_contains("LISTEN", "") !== false) {
         return true;
      }
      sleep(10);
   }

   public function getOllamaStatus()
   {
      // check if something is running over port 11434
      $output = shell_exec("netstat -tulpn | grep ':11434'");
      if (str_contains("LISTEN", "") !== false) {
         return true;
      } else {
         $this->startOllama();
         return false;
      }
   }

   /**
    * This function retrieves the incident data from the database and process it using ollama to get the classification, then this is saved 
    * @param int $id
    * @return string $category                                                  
    */

   private function classifyIncident($id)
   {
      while ($this->getOllamaStatus() == false) {
         echo "Loading...";
         $this->startOllama();
      }
      $incident = $this->getIncident($id);
      $content = $incident['incident_content'];
      $content = str_replace(['&#60;p&#62;', '&#60;/p&#62;', '"'], '', $content);
      $url = "http://localhost:11434/api/generate";
      $data = '{
         "model": "llama3",
         "prompt": "Classify this incident, answer as you were an IT technician: ' . $content . '",
         "format": "json",
         "stream": false
       }';
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      if ($response == "") {
         trigger_error("Error: Start the ollama server and refresh the page");
      } else {
         $classification = json_decode($response, true);
         $f_category = str_replace(['{', '}', '"', '\n', '\t', '[', ']', ')'], '', $classification['response']);
         #update the category for the incident
         global $DB;
         if ($f_category == "") {
            $f_category = "Unknown";
         } else {
            $query_create = "INSERT INTO glpi_itilcategories (
                              name, `completename`, `comment`, level,
                              code, `ancestors_cache`, date_mod, date_creation
                              ) 
                              VALUES 
                              ('$f_category', '$f_category', '', '1', '', '[]', NOW(), NOW()
                              )";
            $query_update = "UPDATE glpi_tickets
                               SET itilcategories_id = (
                               SELECT id 
                               FROM glpi_itilcategories 
                               WHERE name = '$f_category') 
                               WHERE id = $id";

            $DB->query($query_create);
            $DB->query($query_update);
         }
         return $f_category;
      }
   }

   /**
    * This function retrieves the incident data from the database and process it using ollama to get a possible solution if it isn't already solved then this is saved to database
    * @param int $id
    * @return string $solution                                                  
    */

   public function getIncidentSolution($id)
   {
      // Fetch the category data from glpi database
      global $DB;
      $query = "SELECT solution 
                FROM glpibrain_solutions 
                WHERE ticket_id = $id";
      $data = $DB->request($query);
      #if rows are returned, then the category exists
      if ($data->numRows() > 0) {
         foreach ($data as $row) {
            $solution = $row['solution'];
            return $solution;
         }
      } else {
         $solution = $this->solveIncident($id);
         return $solution;
      }
   }

   /**
    * This function retrieves the incident data from the database and process it using ollama to get a possible solution then this is saved to database
    * @param int $id
    * @return string $solution                                                  
    */

   private function solveIncident($id)
   {
      $incident = $this->getIncident($id);
      $content = $incident['incident_content'];
      $content = str_replace(['&#60;p&#62;', '&#60;/p&#62;', '"'], '', $content);
      while ($this->getOllamaStatus() == false) {
         echo "Loading...";
         $this->startOllama();
      }
      $url = "http://localhost:11434/api/generate";
      $data = '{
         "model": "llama3",
         "prompt": "Solve this incident, answer as you were an IT technician: ' . $content . '",
         "format": "json",
         "stream": false
       }';
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      if ($response == "") {
         trigger_error("Error: Start the ollama server and refresh the page");
      } else {
         $solution = json_decode($response, true);
         $f_solution = str_replace(['{', '}', '"', '\n', '\t', '[', ']'], '', $solution['response']);
         if ($f_solution == "") {
            $f_solution = "Unknown";
         } else {
            global $DB;
            $query_create = "INSERT INTO glpibrain_solutions 
                              (ticket_id, `solution`) 
                              VALUES 
                              ($id, '$f_solution')";
            $DB->query($query_create);
            return $f_solution;
         }
      }
   }

   /**
    * This function tells llama3 if the solution is wrong to get it retrained
    * @param string $real_solution
    * @param int $id                                                  
    */

   public function retrainSolution($id, $real_solution)
   {
      $incident = $this->getIncident($id);
      $url = "http://localhost:11434/api/generate";
      $data = '{
            "model": "llama3",
            "prompt": "The solution for this incident: ' . $incident['incident_content'] . ' is wrong, the correct solution is: ' . $real_solution . ' please keep it in mind for next time.",
            "format": "json",
            "stream": false
            }';
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);

      global $DB;

      $query_create = "UPDATE glpibrain_solutions 
                        SET `solution` = '$real_solution' 
                        WHERE ticket_id = $id";
      $query_update = "INSERT INTO glpi_itilsolutions 
                        (ticket_id, itemtype, items_id, `content`, date_creation, date_mod, users_id, users_id_editor, users_id_approval, status) 
                        VALUES $id, Ticket, 0, '&#60;p&#62;$real_solution&#60;/p&#62;', NOW(), NOW(), 2, 0, 0, 2";
      $DB->query($query_create);
      $DB->query($query_update);
      return $real_solution;
   }
}
