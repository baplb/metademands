alter table `glpi_plugin_metademands_tickettasks` add `users_id_validate` int(11) NOT NULL default '0';
alter table `glpi_plugin_metademands_tickettasks` add `_add_validation` int(11) NOT NULL default '0';
ALTER TABLE glpi_plugin_metademands_configs ADD `childs_parent_content` tinyint(1) default 0;