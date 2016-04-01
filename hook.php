<?php 

/* This file is part of ArmaditoPlugin.

ArmaditoPlugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

ArmaditoPlugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with ArmaditoPlugin.  If not, see <http://www.gnu.org/licenses/>.

*/

// ----------------------------------------------------------------------
// Original Author of file: Valentin HAMON
// Purpose of file: 
// ----------------------------------------------------------------------

function setDefaultDisplayPreferences(){
   
    // Set preferences for search_options
   $query = "INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`,
                        `users_id`)
		      VALUES (NULL, 'PluginArmaditoArmadito', '1', '1', '0'),
			     (NULL, 'PluginArmaditoArmadito', '2', '2', '0'),
			     (NULL, 'PluginArmaditoArmadito', '3', '3', '0'),
			     (NULL, 'PluginArmaditoArmadito', '4', '4', '0'),
			     (NULL, 'PluginArmaditoArmadito', '5', '5', '0')";

   if(!DBtools::ExecQuery($query)){
      die();
   }
}

function cleanDefaultDisplayPreferences(){
   global $DB;

   $query = "DELETE FROM `glpi_displaypreferences`
      WHERE `itemtype`='PluginArmaditoArmadito'";

   DBtools::ExecQuery($query);
}

function plugin_armadito_install() {
   global $DB;

   ProfileRight::addProfileRights(array('armadito:read'));

   // Création de la table uniquement lors de la première installation
   if (!TableExists("glpi_plugin_armadito_config")) {

        // Création de la table config
        $query = "CREATE TABLE `glpi_plugin_armadito_config` (
        `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `status` char(32) NOT NULL default '',
        `enabled` char(1) NOT NULL default '1'
        )ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        if(!DBtools::ExecQuery($query)){
           die();
        }
   }

   // 
   // SELECT agent_id FROM glpi_plugin_fusioninventory_agents WHERE  

   if (!TableExists("glpi_plugin_armadito_armaditos")) {
      $query = "CREATE TABLE `glpi_plugin_armadito_armaditos` (
                  `id` int(11) NOT NULL auto_increment,
		  `computers_id` int(11) NOT NULL,
                  `name` varchar(255) collate utf8_unicode_ci default NULL,
                  `serial` varchar(255) collate utf8_unicode_ci NOT NULL,
                  `version_av` varchar(255) collate utf8_unicode_ci NOT NULL,
                  `version_agent` varchar(255) collate utf8_unicode_ci NOT NULL,
                PRIMARY KEY (`id`)
               ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

       if(!DBtools::ExecQuery($query)){
          die();
       }
   }

   // erase user display preferences and set default instead
   cleanDefaultDisplayPreferences();
   setDefaultDisplayPreferences();

   return true;
}

function plugin_armadito_uninstall() {
   global $DB;

   ProfileRight::deleteProfileRights(array('armadito:read'));

   // Current version tables
   if (TableExists("glpi_plugin_armadito_config")) {
      $query = "DROP TABLE `glpi_plugin_armadito_config`";
      if(!DBtools::ExecQuery($query)){
         die();
      }
   }

   // Current version tables
   if (TableExists("glpi_plugin_armadito_armaditos")) {
      $query = "DROP TABLE `glpi_plugin_armadito_armaditos`";
      if(!DBtools::ExecQuery($query)){
         die();
      }
   }

   // erase user display preferences
   cleanDefaultDisplayPreferences();

   return true;
}
?>
