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

/**
 * @name plugin_reports_install
 * @access public
 * @return boolean
 */
function plugin_reports_install(){

    global $DB, $LANG;

    $query_config = "CREATE TABLE IF NOT EXISTS `glpi_plugin_reports_config`
                    (
                        `id` int(1) unsigned NOT NULL default '1',
                        `empresa` varchar(255) NOT NULL default '0',
                        `endereco` varchar(255) NOT NULL default '0',
                        `telefone` varchar(50) NOT NULL default '0',
                        `cidade` varchar(255) NOT NULL default '0',
                        `cnpj` varchar(50) NOT NULL default '0',
                        `site` varchar(50) NOT NULL default '0',
                        `logo1` varchar(255) NOT NULL default '0',
                        `logo2` varchar(255) NOT NULL default '0',
                        PRIMARY KEY (`id`)  
                    ) 
                    ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
                    ";
    $DB->query($query_config) or die("Erro ao criar a tabela glpi_plugin_reports_config " . $DB->error());
    
    $query_data = "CREATE TABLE `glpi_plugin_reports_data` (
        `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
        `uuid` varchar(60) NOT NULL DEFAULT '0',
        `subject` varchar(255) NOT NULL DEFAULT '0',
        `ticket_id` varchar(50) NOT NULL DEFAULT '0',
        `date_creation` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
                    ";
    $DB->query($query_data) or die("Erro ao criar a tabela glpi_plugin_reports_daata " . $DB->error());   

    return true;                    
                            
}

/**
 * @name plugin_reports_uninstall
 * @access public
 * @return boolean
 */
function plugin_reports_uninstall(){

    global $DB;

    $drop_config = "DROP TABLE glpi_plugin_reports_config";
    $DB->query($drop_config);

    $drop_data = "DROP TABLE glpi_plugin_reports_data";
    $DB->query($drop_data);

    return true;

}