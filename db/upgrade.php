<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_pluginname_upgrade($oldversion)
{
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2025111909) {

        // Define field description to be added to local_pluginname_subjects
        $table = new xmldb_table('local_pluginname_subjects');
        $field = new xmldb_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null, 'name');

        // Conditionally launch add field description
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Pluginname savepoint reached
        upgrade_plugin_savepoint(true, 2025111909, 'local', 'pluginname');
    }

    return true;
}
