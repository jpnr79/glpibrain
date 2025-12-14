<?php

/**
 * -------------------------------------------------------------------------
 * GLPIBrain plugin for GLPI
 * Copyright (C) 2024 by the GLPIBrain Development Team.
 * -------------------------------------------------------------------------
 *
 * MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * --------------------------------------------------------------------------
 */

/**
 * Plugin install process
 *
 * @return boolean
 */

 function plugin_change_profile_glpibrain() {
    // For glpibrain : same right of computer
    if (Session::haveRight('computer', UPDATE)) {
       $_SESSION["glpi_plugin_glpibrain_profile"] = ['glpibrain' => 'w'];
 
    } else if (Session::haveRight('computer', READ)) {
       $_SESSION["glpi_plugin_glpibrain_profile"] = ['glpibrain' => 'r'];
 
    } else {
       unset($_SESSION["glpi_plugin_glpibrain_profile"]);
    }
    return true;
 }

function plugin_glpibrain_install()
{
    global $DB;

    $config = new Config();
    $config->setConfigurationValues('plugin:glpibrain', ['configuration' => false]);
 
    // Check if profile rights already exist before adding them
    if (countElementsInTable('glpi_profilerights', ['name' => 'glpibrain:read']) == 0) {
        ProfileRight::addProfileRights(['glpibrain:read']);
    }
 
    // Table creation is now handled by migration files in sql/migration_1.0.0.sql
            // Table creation is now handled by migration files in sql/migration_1.0.0.sql
    return true;
}

/**
 * Plugin uninstall process
 *
 * @return boolean
 */
function plugin_glpibrain_uninstall()
{
    global $DB;
    $config = new Config();
    $config->deleteConfigurationValues('plugin:GlpiBrain', ['configuration'=> false]);

    ProfileRight::deleteProfileRights(['glpibrain:read']);

    // Table drop is now handled by migration files in sql/migration_1.0.0.sql

    return true;
}
