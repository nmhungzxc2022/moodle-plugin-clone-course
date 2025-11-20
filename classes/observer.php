<?php

namespace local_pluginname;

defined('MOODLE_INTERNAL') || die();

class observer
{

    public static function subject_deleted(\local_pluginname\event\subject_deleted $event)
    {
        global $CFG;

        $admins = get_admins();

        foreach ($admins as $admin) {
            email_to_user(
                $admin,
                $admin,
                "subject deleted",
                "a subject with id {$event->objectid} has been deleted"
            );
        }
    }
}
