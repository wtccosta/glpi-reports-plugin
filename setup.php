<?php

/*
 * @version $Id: setup.php 01 2021-11-25 walid $
 LICENSE

  This file is part of the reports plugin.

 Order plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 Wardes T. Costa; either version 2 of the License, or
 (at your option) any later version.

 Order plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; along with itilcategorygroups. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   reports
 @author    Wardes T. Costa
 @copyright Copyright (c) 2021 Wardes T. Costa
 @license   GPLv3
            http://www.gnu.org/licenses/gpl.txt
 @link      https://github.com/JulioAugustoS/glpireports
 @link      http://www.glpi-project.org/
 @since     2021
 --------------------------------------------------------------------------
 */

include ('inc/reports.class.php');

define("PLUGIN_REPORTS_VERSION", "2.0.0");

if(!defined("PLUGIN_REPORTS_DIR")){
    define("PLUGIN_REPORTS_DIR", GLPI_ROOT . "/plugins/reports");
}
class PluginReportsConfig extends PluginReports {

    static protected $notable = true;

    /**
     * @see CommonGLPI::getMenuName()
     */
    static function getMenuName(){

        return __('Laudos');

    }

    /**
     * @name getMenuContent
     * @return $menu
     */
    static function getMenuContent(){

        global $CFG_GLPI;

        $menu = array();
        $menu['title']  = __('Laudos', 'reports');
        $menu['page']   = "/plugins/reports/front/config.form.php";
       

        return $menu;

    }

    /*
    /**
     * @name showForTicket
     */
    static function showForTicket(){

        global $CFG_GLPI, $DB;
        $id = $_REQUEST['id'];

        $ticketOpen = new PluginReports();
        $ticketOpen->printOsTicketOpen($id, 2);

    }

    /**
     * @name getTabNameForItem
     * @param String $item
     * @param Int $withtemplate
     * @return array
     */
    function getTabNameForItem(CommonGLPI $item, $withtemplate = 0){

        if($item->getType() == 'Ticket'
            && $_SESSION['glpiactiveprofile']['interface'] == 'central'){
            return __('Imprimir Laudo', 'reports');
        }

        return '';

    }

    /**
     * @name displayTabContentForItem
     * @param String $item
     * @param Int $tabnum
     * @param Int $withtemplate
     * @return boolean
     */
    static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0){

        if($item->getType() == 'Ticket'){
            self::showForTicket($item);
        }

        return true;

    }
 
}   

/** FUNÇÕES OBRIGATÓRIAS */

/**
 * @name plugin_init_reports
 * @access public
 */
function plugin_init_reports(){
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Campo_Grande');

    global $PLUGIN_HOOKS, $LANG;

    $PLUGIN_HOOKS['menu_entry']['reports'] = true;
    $PLUGIN_HOOKS['csrf_compliant']['reports'] = true;

    Plugin::registerClass('PluginReportsConfig', 
                            ['addtabon' => ['Ticket']]);

    $PLUGIN_HOOKS["menu_toadd"]['reports'] = array('plugins' => 'PluginReportsConfig');
    $PLUGIN_HOOKS['config_page']['reports'] = 'front/config.form.php';
    $PLUGIN_HOOKS['add_javascript']['reports'] = 'assets/js/jquery.min.js';
    $PLUGIN_HOOKS['add_javascript']['reports'] = 'assets/js/jquery.maskedinput.js';
    $PLUGIN_HOOKS['add_javascript']['reports'] = 'assets/js/functions.js';
    $PLUGIN_HOOKS['add_css']['reports']        = 'assets/css/style.css';
    

}

/**
 * @name plugin_version_reports
 * @access public
 */
function plugin_version_reports(){

    global $DB, $LANG;
    
    return array(
        'name'                  => __('Laudos', 'reports'),    
        'version'               => PLUGIN_REPORTS_VERSION,    
        'author'                => '<a href="mailto:wardes.costa@gmail.com">Wardes T. Costa</a>',
        'license'               => 'GPLv3',
        'homepage'              => 'https://github.com/wtccosta/glpireports',
        'minGlpiVersion'        => '9.2'
    );

}

/**
 * @name plugin_reports_check_prerequisites
 * @access public
 */
function plugin_reports_check_prerequisites(){

    if(GLPI_VERSION >= 9.2):
        return true;
    else:
        echo "GLPI version NOT compatible. Requires GLPI 9.2";
    endif;

}

/**
 * @name plugin_reports_check_config
 * @access public  
 */
function plugin_reports_check_config($verbose = false){

    if($verbose):
        echo 'Installed / not configured';
    else:
        return true;
    endif;     
       
}