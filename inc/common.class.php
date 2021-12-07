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
 @author    Wardes T. Costa Fork Julio 
 @copyright Copyright (c) 2021 Wardes T. Costa
 @license   GPLv3
            http://www.gnu.org/licenses/gpl.txt
 @link      https://github.com/JulioAugustoS/glpireports
 @link      http://www.glpi-project.org/
 @since     2021
 --------------------------------------------------------------------------
 */
include('utils.php');
class CommonReports extends CommonDBTM
{

    /**
     * @name saveConfig
     * @access public
     * @param String $empresa, $endereco, $telefone, $cidade, $cnpj, $site
     * @return array
     */

    public static function saveConfig($empresa, $endereco, $telefone, $cidade, $cnpj, $site)
    {

        global $DB;

        $consulta = self::configPlugin();

        if ($consulta['total'] == 0) {

            $insertEmpresa = "INSERT INTO glpi_plugin_orderservice_config
                                SET empresa = '$empresa',
                                    endereco = '$endereco',
                                    telefone = '$telefone',
                                    cidade = '$cidade',
                                    cnpj = '$cnpj',
                                    site = '$site'
                            ";
            $save = $DB->query($insertEmpresa) or die('Erro ao salvar a empresa!');

            return $save;
        } else {

            $updateEmpresa = "UPDATE glpi_plugin_orderservice_config
                                SET empresa = '$empresa',
                                    endereco = '$endereco',
                                    telefone = '$telefone',
                                    cidade = '$cidade',
                                    cnpj = '$cnpj',
                                    site = '$site'
                                WHERE id = 1
                            ";
            $save = $DB->query($updateEmpresa) or die('Erro ao atualizar a empresa!');

            return $save;
        }
    }

    /**
     * @name saveReport
     * @access public
     * @param String $uuid, $subject, $ticket_id, $date_creation
     * @return array
     */

    public static function saveReport($subject, $ticket_id, $date_creation)
    {
        global $DB;

        $autoUuid = self::reportsUuid();

        $updateEmpresa = "INSERT INTO glpi_plugin_reports_data
                                SET uuid = '$autoUuid',
                                    subject = '$subject',
                                    ticket_id = '$ticket_id',
                                    date_creation = '$date_creation'
                            ";
        return $DB->query($updateEmpresa) or var_dump('Erro ao atualizar a empresa!');
    }

    /**
     * @name updateReport
     * @access public
     * @param String  $subject
     * @return array
     */
    public static function updateReport($id, $subject)
    {
        global $DB;
        $updateEmpresa = "UPDATE glpi_plugin_reports_data
                                SET subject = '$subject'
                                WHERE id = $id
                            ";
        return $DB->query($updateEmpresa) or var_dump('Erro ao atualizar a empresa!');
    }

    /**
     * @name configPlugin
     * @access public
     * @return array
     */
    public static function configPlugin()
    {

        global $DB;

        $selPlugin = "SELECT 
                        sum(id) AS total,
                        empresa,
                        endereco,
                        telefone,
                        cidade,
                        cnpj,
                        logo1,
                        logo2,
                        site
                        FROM glpi_plugin_reports_config
                    ";
        $resPlugin = $DB->query($selPlugin);
        $plugin = $DB->fetch_assoc($resPlugin);

        return $plugin;
    }

    public static function configReport($id)
    {
        global $DB;

        $selPlugin = "SELECT 
                        id,
                        `uuid`,
                        `subject`,
                        `ticket_id`,
                        `date_creation`
                        FROM glpi_plugin_reports_data
                        WHERE `ticket_id` = '$id'
                    ";
        $resPlugin = $DB->query($selPlugin);
        $plugin = $DB->fetch_assoc($resPlugin);

        return $plugin;
    }



    /**
     * @name reportsUuid
     * @access public
     * @return string
     */
    public static function reportsUuid()
    {

        global $DB;

        $selCount = "SELECT count(id) as total
                        FROM glpi_plugin_reports_data
                    ";
        $resCountn = $DB->query($selCount);
        $total = $DB->fetch_assoc($resCountn)['total'];
        $current_date = (new DateTime('NOW'))->format('Y');
        if ($total > 0) {
            $query = "SELECT uuid, date_creation
                        FROM glpi_plugin_reports_data
                        ORDER BY id
                        DESC LIMIT 1
                    ";
            $res = $DB->query($query);
            $lastReport = $DB->fetch_assoc($res);
            $last_report_date = new DateTime($lastReport['date_creation']);
           
            if ($current_date ==  $last_report_date->format('Y')) {
                (int) $uuid = strtok($lastReport['uuid'], '/');
                 $uuid++;
                 $uuid = formatReportNumber($uuid).'/'.$current_date;
            }else{
               $uuid = formatReportNumber(1).'/'.$current_date;
            }
        }
        else {
            $uuid = formatReportNumber(1).'/'.$current_date;
        }

        return $uuid;
    }

    /**
     * @name selTicket
     * @access public
     * @param Int $idTicket
     * @return array
     */
    function selTicket($idTicket)
    {

        global $DB;

        $selTicket = "SELECT * FROM glpi_tickets WHERE id = '$idTicket'";
        $resTicket = $DB->query($selTicket);
        $ticket = $DB->fetch_assoc($resTicket);

        return $ticket;
    }

    static function selLocationName($idTicket)
    {
        global $DB;

        // Seleciona a solução do chamado
        $query = "SELECT name FROM .glpi_locations where id = 11
                            ";
        $resTicketLocation = $DB->query($query);
        return  $DB->fetch_assoc($resTicketLocation);

        //  return (isset($ticketSolution['content'])) ?  $osSolution = $ticketSolution['content'] : "";
    }

    /**
     * @name selTicketSolution
     * @access public
     * @param Int $idTicket
     * @return object 
     */
    static function selTicketSolution($idTicket)
    {

        global $DB;

        // Seleciona a solução do chamado
        $selTicketSolution = "SELECT content
                                FROM glpi_itilsolutions
                                WHERE itemtype = 'Ticket'
                                AND items_id = '$idTicket'
                                AND status = 3
                                ORDER BY id DESC
                                LIMIT 1
                            ";
        $resTicketSolution = $DB->query($selTicketSolution);
        $ticketSolution = $DB->fetch_assoc($resTicketSolution);

        return (isset($ticketSolution['content'])) ?  $osSolution = $ticketSolution['content'] : "";
    }

    /**
     * @name selDates
     * @access public
     * @param Int $idTicket
     * @return array
     */
    function selDates($idTicket)
    {

        global $DB;

        // Seleciona a data inicial 
        $selDateInitial = "SELECT date, date_format(date, '%d/%m/%Y %H:%i') AS DataInicio
                            FROM glpi_tickets
                            WHERE id = '$idTicket'
                        ";
        $resDateInitial = $DB->query($selDateInitial);
        $dateInitial = $DB->fetch_assoc($resDateInitial);

        // Seleciona a data final
        $selDateFinish = "SELECT closedate AS DataFinal
                            FROM glpi_tickets
                            WHERE id = '$idTicket'
                        ";
        $resDateFinish = $DB->query($selDateFinish);
        $dateFinish = $DB->result($resDateFinish, 0, 'DataFinal');

        $dataI          = $dateInitial['DataInicio'];
        $osDescricao    = self::selTicketSolution($idTicket);

        return array($dataI, $osDescricao, $dateFinish);
    }

    /**
     * @name selTicketsUsers
     * @access public
     * @param Int $idTicket
     * @return array
     */
    function selTicketsUsers($idTicket)
    {

        global $DB;

        // Seleciona o usuario do chamado

        $selTicketUsers = "SELECT users_id AS idUser 
                            FROM glpi_tickets_users 
                            WHERE tickets_id = '$idTicket'
							
                        ";

        $resTicketUsers = $DB->query($selTicketUsers);
        $ticketUsers = $DB->result($resTicketUsers, 0, 'idUser');

        // Seleciona o responsavel pelo chamado
        $selIdOsResponsavel = "SELECT users_id AS idRes 
                                FROM glpi_tickets_users 
                                WHERE tickets_id = '$idTicket'
                                AND type = 2
                            ";
        $resIdOsResponsavel = $DB->query($selIdOsResponsavel);
        $idOsResponsavel = $DB->result($resIdOsResponsavel, 0, 'idRes');


        // Seleciona o nome do responsavel do chamado
        $selOsResponsavelName = "SELECT * FROM glpi_users WHERE id = '$idOsResponsavel'";
        $resOsResponsavelName = $DB->query($selOsResponsavelName);
        $osResponsavelFull = $DB->fetch_assoc($resOsResponsavelName);

        $osResponsavelName  = $osResponsavelFull['firstname'] . "  " . $osResponsavelFull['realname'];
        $osResponsavelMat =  $osResponsavelFull['registration_number'];
        $entidadeId         =  self::selTicket($idTicket);

        return array(
            $ticketUsers,
            $idOsResponsavel,
            $osResponsavelName,
            $osResponsavelMat,
            $entidadeId['entities_id']
        );
    }

    /**
     * @name selUsers
     * @access public
     * @param Int $id
     * @return array
     */
    function selUsers($id)
    {

        global $DB;
        $osUserId = self::selTicketsUsers($id);

        $selUsers = "SELECT a.firstname AS Nome,
                        a.realname AS Sobrenome,
                        a.phone AS Fone,
                        b.email AS Email,
                        c.name AS Localidade
                        FROM glpi_users a 
                        LEFT JOIN glpi_useremails b ON (b.users_id = a.id)
                        LEFT JOIN glpi_locations c ON (c.id = a.locations_id)
                        WHERE b.is_default = 1 AND a.id = " . $osUserId[0] . "
                    ";
        $resUsers = $DB->query($selUsers);
        $users = $DB->fetch_assoc($resUsers);

        return $users;
    }

    //********************************************MY CUSTONS***********************************************//


    /*
	* Para casos em que o admin gera apenas 1 (uma) entidade e gerencia tudo por grupos e locais (locations)
	* Na verdade a função trás mais dados que apenas o requester, pensar em um nome melhor depois. Nesse caso lembrar de atualizar
	* o nome da função tb no arquivo common.class.php do plugin (mesmo diretório).
	*/
    function showRequesters($id)
    {

        global $DB;
        //Relaciona tudo, REQUERENTE, LOCAL DE ATENDIMENTO (LOCATIONS), E O TICKET (MAIS RESRITIVO)
        $select1 = "SELECT *
				   FROM glpi_tickets_users 
				   INNER JOIN	glpi_users ON glpi_users.id = glpi_tickets_users.users_id
				   LEFT JOIN	glpi_tickets ON glpi_tickets.id = glpi_tickets_users.tickets_id
				   INNER JOIN glpi_locations ON glpi_tickets.locations_id = glpi_locations.id
				   WHERE glpi_tickets_users.tickets_id = " . $id . " AND glpi_tickets_users.type = 1";
        //O mesmo do $select1, só que mais amplo, para o caso de chamados abertos sem indicar o REQUERENTE (requester)
        $select2 = "SELECT *
				   FROM glpi_tickets_users 
				   INNER JOIN	glpi_users ON glpi_users.id = glpi_tickets_users.users_id
				   LEFT JOIN	glpi_tickets ON glpi_tickets.id = glpi_tickets_users.tickets_id
				   INNER JOIN glpi_locations ON glpi_tickets.locations_id = glpi_locations.id
				   WHERE glpi_tickets_users.tickets_id = " . $id . " ";

        $resSel = $DB->query($select1);
        $requesters = $DB->fetch_assoc($resSel);
        if ($requesters != NULL) {
            return $requesters;
        } else {
            $resSel = $DB->query($select2);
            return $DB->fetch_assoc($resSel);
        }
    }



    function selNumPatrimonio($id)
    {
        global $DB;
        $selNumPatrimonio = "SELECT nmerodepatrimniofield FROM glpi_plugin_fields_ticketpatrimonios
                            WHERE $id = items_id";
        $resSel = $DB->query($selNumPatrimonio);
        $patrimonio = $DB->fetch_assoc($resSel);
        return (isset($patrimonio['nmerodepatrimniofield'])) ? $patrimonio['nmerodepatrimniofield'] : '';
    }
}
