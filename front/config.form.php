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

include ('../../../inc/includes.php');
include ('../inc/display.class.php');

if ($_SESSION["glpiactiveprofile"]["interface"] == "central") {
    Html::header(PluginReportsDisplay::getTypeName(1), $_SERVER['PHP_SELF'], "plugins", "reports", "");
}else{
    Html::helpHeader(PluginReportsDisplay::getTypeName(1), $_SERVER['PHP_SELF']);
}
  
PluginReportsDisplay::displayTelaConfig();
 
Html::footer();