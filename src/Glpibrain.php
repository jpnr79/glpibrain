
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

// Avoid direct access to the file
if (!defined('GLPI_ROOT')) {
   die("You may not access this file directly");
}

class Glpibrain extends CommonDBTM {

   static $tags = '[GLPIBRAIN_ID]';

   /**
    * Shows the glpibrain plugin in the menu
    * @see CommonGLPI::getMenuName()
   **/
   static function getMenuName() {
      return _n('GlpiBrain', 'GlpiBrains', 1, 'glpibrain');
   }

   /**
    * Link to the page of the plugin
    * @see CommonGLPI::getMenuContent()
   **/

   static function getMenuContent() {

      $plugin_page              = "/plugins/glpibrain/front/glpibrain.php";
      $menu                     = [];
      //Menu entry in tools
      $menu['title']            = self::getMenuName();
      $menu['page']             = $plugin_page;
      $menu['links']['search']  = $plugin_page;

      if (Session::haveRight(static::$rightname, UPDATE)
            || Session::haveRight("config", UPDATE)) {
         $menu['icon']                                       = "ti ti-brain";
      }

      return $menu;
   }

   /**
    * This function retrieves all the incidents from the database and returns them
      * @return array $incidents
    */

   public function getIncidents() {
      // Fetch the tickets data from glpi database
      global $DB;
      // I need to create a procedure to get the incidents from the database with details like, id, name, date, assignee, category, status, expected solution, real solution
      $query = "SELECT ticket.id AS incident_id, ticket.name AS incident_title, ticket.date AS incident_date, u.name AS assignee_name, ticket.status AS incident_status, IFNULL(ticket.itilcategories_id, 0) AS category_id
                  FROM glpi_tickets ticket
                  JOIN glpi_tickets_users tu ON ticket.id = tu.tickets_id AND tu.type = 2
                  JOIN glpi_users u ON tu.users_id = u.id";
      
      $data = $DB->request($query);
      if($data) {
         return $data;
      } else {
         return [];
      }
   }

   /**
    * This function retrieves the incident data from the database
    * @param int $id
    * @return string $state
    */

   public function getIncidentStatus($sid) {
      switch ($sid) {
         case 1:
            $state = 'New';
            break;
         case 2:
            $state = 'Processing (Assigned) ';
            break;
         case 3:
            $state = 'Processing (Planned)';
            break;
         case 4:
            $state = 'Pending';
            break;
         case 5:
            $state = 'Solved';
            break;
         case 6:
            $state = 'Closed';
            break;
         default:
            $state = 'unknown';
            break;
      }

      return $state;

   }

   /**
    * This function retrieves the incident category from the database if it exists
    * @param int $id
    * @return string $category
    */

   public function getIncidentCategory($cid) {
      // Fetch the category data from glpi database
      
      if($cid) {
         global $DB;
         $query = "SELECT name FROM glpi_itilcategories WHERE id = $cid";
         $data = $DB->request($query);
         if($data) {
            return $data[0]['name'];
         } else {
            return 'Unknown';
         }
      } else {
         return 'Unknown';
      }
   }

   /**
    * This function retrieves the incident data from the database
    * @param int $id
    * @return array $incident
    */
    private function getIncident($id) {
      // Fetch the ticket data from glpi database
      if($id) {
         global $DB;
         $query = "SELECT ticket.id AS incident_id, ticket.name AS incident_title, ticket.date AS incident_date, u.name AS assignee_name, ticket.status AS incident_status, IFNULL(ticket.itilcategories_id, 0) AS category_id
               FROM glpi_tickets ticket
               JOIN glpi_tickets_users tu ON ticket.id = tu.tickets_id AND tu.type = 2
               JOIN glpi_users u ON tu.users_id = u.id
               WHERE ticket.id = $id";
         $data = $DB->request($query);
      } 

      if($data) {
         return $data;
      } else {
         return [];
      }
      
    }

   /**
    * This function retrieves the incident data from the database
      * @param int $id
      * @return array $incident
      */

   public function processIncident($id) {
      
   }

   /**
    * This function tokenizes the incident data
      * @param string $incident
      * @return array $tokens
      */

   private function tokenize($incident) {
      
   }

   /**
    * Learn from the real solution and train the neural network
    * @param array $tokens the incident data input
    * @param array $solution the real solution
    */

   private function trainNeuralNetwork($tokens, $solution) {
      
   }

}