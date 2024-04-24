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
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

// Class of the defined type


class Glpibrain extends CommonDBTM {

   static $tags = '[GLPIBRAIN_ID]';

   // Should return the localized name of the type


   /**
    * @see CommonGLPI::getMenuName()
   **/
   static function getMenuName() {
      return _n('GlpiBrain', 'GlpiBrains', 1, 'glpibrain');
   }

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

   static function processIncident($id) {
      #This function calls de neural network to process the incident and get trained to show a possible solution or classification

   }

}