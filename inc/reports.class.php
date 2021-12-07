<?php

/*
 * @version $Id: orderserivce.class.php 19 2018-08-20 09:19:05Z walid $
 LICENSE

  This file is part of the orderservice plugin.

 Order plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 Julio Augusto; either version 2 of the License, or
 (at your option) any later version.

 Order plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; along with itilcategorygroups. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   orderservice
 @author    Julio Augusto
 @copyright Copyright (c) 2018 Julio Augusto
 @license   GPLv3
            http://www.gnu.org/licenses/gpl.txt
 @link      https://github.com/JulioAugustoS/glpiorderservice
 @link      http://www.glpi-project.org/
 @since     2018
 --------------------------------------------------------------------------
 */
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Campo_Grande');
global $CFG_GLPI;

include('common.class.php');

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

if (!defined("PLUGIN_REPORTS_DIR")) {
    define("PLUGIN_REPORTS_DIR", $CFG_GLPI['root_doc'] . "/plugins/reports");
}

class PluginReports extends CommonReports
{

    public function printOsTicketOpen($id, $status)
    {

        global $DB;


        $configPlugin   = CommonReports::configPlugin();
        $tickets        = CommonReports::selTicket($id);
        $dates          = CommonReports::selDates($id);
        $date = new DateTime($dates[2]);
        $solution       = CommonReports::selTicketSolution($id);
        $ticketsUser    = CommonReports::selTicketsUsers($id);
        $numPatrimonio =  (CommonReports::selNumPatrimonio($id) != null) ? CommonReports::selNumPatrimonio($id) : 'Sem Patrimônio';
        $location = CommonReports::selLocationName($id);
        $configReport   = CommonReports::configReport($id);
        $date_creation = ($configReport['date_creation'] == null) ? new DateTime('NOW') :  new DateTime($configReport['date_creation']);
        $netxtUuid = CommonReports::reportsUuid(); 
        $formatter = new IntlDateFormatter(
            'pt_BR',
             IntlDateFormatter::FULL,
             IntlDateFormatter::NONE,
             'America/Campo_Grande',          
             IntlDateFormatter::GREGORIAN
           );
        echo "<hr>";
        echo '<form id="report_content" class="form-empresa">;
        ';
       
        echo '<div class="form-group">
    <label>Destinatário:</label>
    <input type="text" class="form-control" name="subject" value="' . $configReport['subject'] . '"></div>';
        echo '<div class="form-group">
    <input type="hidden" class="form-control" name="report_id" value="' . $configReport['id'] . '" display="none"></div>';
        echo '<div class="form-group">
    <input type="hidden" class="form-control" name="ticket_id" value="' . $id . '" display="none"></div>
    <div class="form-group">
    <div class="form-group">
    <input type="hidden" class="form-control" name="date_creation" value="' .$date_creation->format('c') . '" display="none"></div>
    <div class="form-group">
   
    <button type="button" onclick="saveReportData()" class="btn btn-success">Atualizar Destinatário</button>
</div>';
        echo "</form><hr>";
        echo '<div class="success"></div></div></div>';

        echo '<div id="botoes">';
        echo '<a href="#" class="submit ali-center btn-danger" onclick="printDiv(\'printArea\');"> Imprimir Laudo </a>';
        echo '</div>';
        echo '<div id="printArea">';
        echo '
            <table class="table-pri" style="margin-bottom:20px;"><tr>
            <td class="padd-none">
            <table class="table-sec"><tr><td>
            ';

        echo ' 
            <table class="table-tre"><tr>
            <td class="td-logo-1">';

        if (empty($configPlugin['logo1']) && empty($configPlugin['logo2'])) {
            echo '<img src="' . PLUGIN_REPORTS_DIR . '/pics/default.png">';
        } elseif (!empty($configPlugin['logo1']) && empty($configPlugin['logo2'])) {
            echo '<img src="' . PLUGIN_REPORTS_DIR . '/pics/' . $configPlugin['logo1'] . '">';
        } else {
            echo '<img src="' . PLUGIN_REPORTS_DIR . '/pics/' . $configPlugin['logo1'] . '"><br>';
            echo '<img src="' . PLUGIN_REPORTS_DIR . '/pics/' . $configPlugin['logo2'] . '">';
        }

        echo '
            </td>
            <td class="ali-center" id="titulo">
            <h2 class="size-p">PREFEITURA MUNICIPAL DE CORUMBÁ</h3>  
            <p class="size-mp">Superintendência de Tecnologia da Informação e Comunicação</p>   
            <p class="size-mp">' . $configPlugin['cnpj'] . ' - ' . $configPlugin['site'] . '</p>
            </td>
            <td id="os" class="ali-center"">
            <h2>Laudo nº: <br><h3>' . $configReport['uuid'] . '</h3></h2>
            </td>  
            </tr>
            </table>
            <hr></td></tr>
            ';

        echo ' 
        <tr><td class="right"><h4>Corumbá, '.$formatter->format($date).'</h4></td></tr>
        <tr><td><p><strong>De</strong>: SUPERINTENDÊNCIA DE TECNOLOGIA DA INFORMAÇÃO E COMUNICAÇÃO</p>
        <p><strong>Para</strong>: '.$location['name'].'</p>
        <p><strong>A/C</strong>: '.$configReport['subject'].'</p></td></tr>
            <tr><td>&nbsp</td></tr>
            <tr><td class="ali-left header-td"><b>O presente laudo versa sobre a seguinte atendimento: </b></td></tr>
            <tr class="col-6 padd">
            <td class="col-12"><b>Chamado número (Protocolo STIC) nº </b>' . $tickets['id'] . '</td>
            <td class="col-12"><b>Técnico Responsável: </b>' . $ticketsUser[2] . '</td>
            ';

        echo '<td class="col-12"><b>Nº do Patrimônio: ' . $numPatrimonio  . '</b></td>';
        echo ' 
            </tr><tr class="col-6 padd">
            <td class="col-12"><b>Data/Hora Abertura: </b>' . $dates[0] . '</td>
            ';


        echo '<td class="col-12"><b>Data/Hora Fechamento: </b>' . $dates[2] . '</td>';

        echo ' 
            </tr>
            <tr><td>&nbsp</td></tr>
            <tr><td class="ali-left header-td"><b>Conclusão</b></td></tr>
            <tr>
            <td height="5" colspan="2" valign="top">
            ';

        if ($solution == null) :
            echo "";
        else :
            echo html_entity_decode($solution);
            echo '<p>Sendo só pelo momento, reitera-se a disposição para qualquer exclarescimento suplementar.</p>';
            echo '<br />';
        endif;

        echo ' 
            </td></tr>
            ';

        echo '<table width="700" style="margin-top:25px;margin-bottom:20px;" align="center" cellspacing="0">';
        echo '<tr class="ali-center">';
        echo '<td class="ali-center">____________________________________</td></tr>';

        echo ' 
                <tr class="ali-center">
                <td class="ali-center">
                ';

        if ($ticketsUser[2] === '') :
            echo '<b>Técnico Responsável</b>';
        else :
            echo '<b>Técnico</b><br> ' . (isset($ticketsUser[2]) ? $ticketsUser[2] : "").'<br> Matrícula '. (isset($ticketsUser[3]) ? $ticketsUser[3] : "");
        endif;

        echo '</td></tr></table>';
        echo '</tr></table></td></tr></table>';
        echo '</div>'; //fim printArea
        echo '<style media="print"></style>';
    }
}
