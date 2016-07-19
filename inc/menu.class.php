<?php
/**

   Copyright (C) 2010-2016 by the FusionInventory Development Team.
   Copyright (C) 2016 Teclib'

   This file is part of Armadito Plugin for GLPI.

   Armadito Plugin for GLPI is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   Armadito Plugin for GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Armadito Plugin for GLPI. If not, see <http://www.gnu.org/licenses/>.

**/

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


class PluginArmaditoMenu extends CommonGLPI {

   /**
    * Name of the type
    *
    * @param $nb  integer  number of item in the type (default 0)
   **/
   static function getTypeName($nb=0) {
      return 'Armadito';
   }

   static function canView() {
      $can_display = false;
      $profile     = new PluginArmaditoProfile();

      foreach ($profile->getAllRights() as $right) {
         if (Session::haveRight($right['field'], READ)) {
            $can_display = true;
            break;
         }
      }

      $can_display = true;

      return $can_display;
   }

   static function canCreate() {
      return false;
   }

   static function getMenuName() {
      return self::getTypeName();
   }

   static function getAdditionalMenuOptions() {
      global $CFG_GLPI;

      $options = array();

      $options['menu']['title'] = self::getTypeName();
      $options['menu']['page']  = self::getSearchURL(false);

      if (Session::haveRight('plugin_armadito_configuration', READ)) {
         $options['menu']['links']['config']  = PluginArmaditoConfig::getFormURL(false);
      }

      if (Session::haveRight('plugin_armadito_configuration', READ)) {
         $options['agent']['links']['config']  = PluginArmaditoConfig::getFormURL(false);
      }

      return $options;
   }

   static function getAdditionalMenuContent() {
      global $CFG_GLPI;

      $menu = array();

      return $menu;
   }

   static function displayHeader() {
      global $CFG_GLPI;

      echo "<center>";
      echo "<a href='http://github.com/armadito'>";
      echo "<img src='".$CFG_GLPI['root_doc']."/plugins/armadito/pics/armadito_header_logo.png' height='96' />";
      echo "</a>";
   }

   /**
   * Display the menu of Plugin Armadito
   *
   *@param type value "big" or "mini"
   *
   *@return nothing
   **/
   static function displayMenu($type = "big") {
      global $CFG_GLPI;

      $width_status = 0;

      echo "<div align='center' style='height: 35px; display: inline-block; width: 100%; margin: 0 auto;'>";
      echo "<br \>";
      echo "<table width='100%'>";

      echo "<tr>";
      echo "<td align='center'>";

      echo "<table>";
      echo "<tr>";
      echo "<td>";

      /*
       * General
       */
      $a_menu = array();

      if (Session::haveRight('plugin_armadito_agents', READ)) {
         $a_menu[] =array(
            'name' => __('Agents', 'armadito'),
            'pic'  => $CFG_GLPI['root_doc']."/plugins/armadito/pics/menu_settings.png",
            'link' => $CFG_GLPI['root_doc']."/plugins/armadito/front/agent.php"
         );
      }

      if (Session::haveRight('config', UPDATE) || Session::haveRight('plugin_armadito_configuration', UPDATE)) {
         $a_menu[3]['name'] = __('Configuration', 'armadito');
         $a_menu[3]['pic']  = $CFG_GLPI['root_doc']."/plugins/armadito/pics/menu_settings.png";
         $a_menu[3]['link'] = $CFG_GLPI['root_doc']."/plugins/armadito/front/config.form.php";
      }


      if (!empty($a_menu)) {
         $width_status = PluginArmaditoMenu::htmlMenu(__('General', 'armadito'),
                                                             $a_menu,
                                                             $type,
                                                             $width_status);
      }

      /*
       * States
       */
      $a_menu = array();

      if (Session::haveRight('plugin_armadito_states', READ)) {
         $a_menu[] =array(
            'name' => __('Antiviruses', 'armadito'),
            'pic'  => $CFG_GLPI['root_doc']."/plugins/armadito/pics/menu_settings.png",
            'link' => $CFG_GLPI['root_doc']."/plugins/armadito/front/states_antiviruses.php"
         );
      }

      if (!empty($a_menu)) {
         $width_status = PluginArmaditoMenu::htmlMenu(__('State', 'armadto'),
                                                             $a_menu,
                                                             $type,
                                                             $width_status);
      }

      /*
       * Alerts
       */
      $a_menu = array();

      if (Session::haveRight('plugin_armadito_alerts', READ)) {
         $a_menu[] =array(
            'name' => __('Alerts', 'armadito'),
            'pic'  => $CFG_GLPI['root_doc']."/plugins/armadito/pics/menu_settings.png",
            'link' => $CFG_GLPI['root_doc']."/plugins/armadito/front/alerts.php"
         );
      }

      if (!empty($a_menu)) {
         $width_status = PluginArmaditoMenu::htmlMenu(__('Alerts', 'armadto'),
                                                             $a_menu,
                                                             $type,
                                                             $width_status);
      }

      /*
       * Jobs
       */
      $a_menu = array();

      if (Session::haveRight('plugin_armadito_jobs', READ)) {
         $a_menu[] =array(
            'name' => __('Jobs', 'armadito'),
            'pic'  => $CFG_GLPI['root_doc']."/plugins/armadito/pics/menu_settings.png",
            'link' => $CFG_GLPI['root_doc']."/plugins/armadito/front/jobs.php"
         );
      }

      if (!empty($a_menu)) {
         $width_status = PluginArmaditoMenu::htmlMenu(__('Jobs', 'armadto'),
                                                             $a_menu,
                                                             $type,
                                                             $width_status);
      }

      echo "</td>";
      echo "</tr>";
      echo "</table>";

      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</div><br/><br/><br/>";
   }

   /**
    * htmlMenu
    *
    *@param $menu_name value of the menu
    *@param $a_menu array menu of each module
    *@param $type value "big" or "mini"
    *@param $width_status integer width of space before and after menu position
    *
    *@return $width_status integer total width used by menu
    **/
   static function htmlMenu($menu_name, $a_menu = array(), $type = "big", $width_status='300') {
      global $CFG_GLPI;

      $width_max = 1250;

      $width = 180;

      if (($width + $width_status) > $width_max) {
         $width_status = 0;
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         echo "<table>";
         echo "<tr>";
         echo "<td valign='top'>";
      } else {
         echo "</td>";
         echo "<td valign='top'>";
      }
      $width_status = ($width + $width_status);

      echo "<table class='tab_cadre' style='position: relative; z-index: 30;'
         onMouseOver='document.getElementById(\"menu".$menu_name."\").style.display=\"block\"'
         onMouseOut='document.getElementById(\"menu".$menu_name."\").style.display=\"none\"'>";

      echo "<tr>";
      echo "<th colspan='".count($a_menu)."' nowrap width='".$width."'>
         <img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' />
         &nbsp;".str_replace("Armadito ", "", $menu_name)."&nbsp;
         <img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' />
      </th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' id='menu".$menu_name."' style='display:none; position: relative; z-index: 30;'>";
      echo "<td>";
      echo "<table>";
      foreach ($a_menu as $menu_id) {
         echo "<tr>";
         $menu_id['pic'] = str_replace("/menu_", "/menu_mini_", $menu_id['pic']);
         echo "<th>";
         if (!empty($menu_id['pic'])) {
            echo "<img src='".$menu_id['pic']."' width='16' height='16'/>";
         }
         echo "</th>";
         echo "<th colspan='".(count($a_menu) - 1)."' width='".($width - 40)."' style='text-align: left'>
                  <a href='".$menu_id['link']."'>".$menu_id['name']."</a></th>";
         echo "</tr>";
      }
      echo "</table>";

      echo "</td>";
      echo "</tr>";
      echo "</table>";

      return $width_status;
   }

static function board() {
      global $DB;

      // Armadito Computers
      $armaditoComputers    = 0;
      $restrict_entity    = getEntitiesRestrictRequest(" AND", 'comp');


      $query_ao_computers = "SELECT COUNT(comp.`id`) as nb_computers
                             FROM glpi_computers comp
                             LEFT JOIN glpi_plugin_armadito_agents ao_comp
                               ON ao_comp.`computers_id` = comp.`id`
                             WHERE comp.`is_deleted`  = '0'
                               AND comp.`is_template` = '0'
                               AND ao_comp.`id` IS NOT NULL
                               $restrict_entity";

      $res_ao_computers = $DB->query($query_ao_computers);
      if ($data_ao_computers = $DB->fetch_assoc($res_ao_computers)) {
         $armaditoComputers = $data_ao_computers['nb_computers'];
      }

      // All Computers
      $allComputers    = countElementsInTableForMyEntities('glpi_computers',
                                              "`is_deleted`='0' AND `is_template`='0'");

      $dataComputer = array();
      $dataComputer[] = array(
          'key' => __('Armadito computers', 'armadito').' : '.$armaditoComputers,
          'y'   => $armaditoComputers,
          'color' => '#3dff7d'
      );
      $dataComputer[] = array(
          'key' => __('Other computers', 'armadito').' : '.($allComputers - $armaditoComputers),
          'y'   => ($allComputers - $armaditoComputers),
          'color' => "#dedede"
      );

      echo "<table align='center'>";
      echo "<tr height='280'>";
      echo "<td width='380'>";
      self::showChart('computers', $dataComputer);
      echo "</td>";
      echo "</tr>";
      echo "</table>";

   }

   static function showChart($name, $data, $title='') {

      echo '<svg style="background-color: #f3f3f3;" id="'.$name.'"></svg>';

      echo "<script>
         statHalfDonut('".$name."', '".json_encode($data)."');
</script>";
   }


   static function showChartBar($name, $data, $title='', $width=370) {
      echo '<svg style="background-color: #f3f3f3;" id="'.$name.'"></svg>';

      echo "<script>
         statBar('".$name."', '".json_encode($data)."', '".$title."', '".$width."');
</script>";
   }
}

?>
