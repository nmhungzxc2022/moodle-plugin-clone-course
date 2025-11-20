<?php

namespace local_pluginname\event;

defined('MOODLE_INTERNAL') || die();

class subject_deleted extends \core\event\base
{

    protected function init()
    {
        $this->data['crud'] = 'd'; //delete
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'local_pluginname_subjects';
    }

    public static function get_name()
    {
        return get_string('eventsubjectdeleted', 'local_pluginname');
    }

    public function get_description()
    {
        return "the user with id '{$this->userid}' deleted subject id '{$this->objectid}'";
    }
}
