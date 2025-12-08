
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


define('PLUGIN_GLPIBRAIN_VERSION', '0.0.1');

// Minimal GLPI version, inclusive
define("PLUGIN_GLPIBRAIN_MIN_GLPI_VERSION", "10.0.0");
// Maximum GLPI version, exclusive
define("PLUGIN_GLPIBRAIN_MAX_GLPI_VERSION", "12.0.99");

include("src/Glpibrain.php");

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_glpibrain()
{
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['glpibrain'] = true;

    #Plugin::registerClass('Glpibrain', ['addtabon' => 'glpibrain']);
    
    
    $_SESSION["glpi_plugin_glpibrain_profile"]['glpibrain'] = 'w';
   if (isset($_SESSION["glpi_plugin_glpibrain_profile"])) { // Right set in change_profile hook
      $PLUGIN_HOOKS['menu_toadd']['glpibrain'] = ['plugins' => GlpiBrain::class];
}
}

/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array
 */
function plugin_version_glpibrain()
{
    return [
        'name'           => _n('GLPIBrain','glpibrain', 'Glpibrain'),
        'version'        => PLUGIN_GLPIBRAIN_VERSION,
        'author'         => '<a href="https://github.com/nselar/nselar">Nicolás Sela\'</a>',
        'license'        => 'GPLv2+',
        'homepage'       => '',
        'requirements'   => [
            'glpi' => [
                'min' => '11.0',
                'max' => '12.0',
            ]
        ]
    ];
}

/**
 * Check pre-requisites before install
 * OPTIONNAL, but recommanded
 *
 * @return boolean
 */
function plugin_glpibrain_check_prerequisites()
{

    return true;
}

/**
 * Check configuration process
 *
 * @param boolean $verbose Whether to display message on failure. Defaults to false
 *
 * @return boolean
 */
function plugin_glpibrain_check_config($verbose = false)
{
    if (true) { // Your configuration check
        return true;
    }

    if ($verbose) {
        echo __('Installed / not configured', 'glpibrain');
    }
    return false;
}
