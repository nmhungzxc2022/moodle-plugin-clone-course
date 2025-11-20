<?php

namespace local_pluginname\event;

defined('MOODLE_INTERNAL') || die();

class subject_created extends \core\event\base
{

    protected function init()
    {
        $this->data['crud'] = 'c'; //create
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'local_pluginname_subjects';
    }

    public static function get_name()
    {
        return get_string('eventsubjectcreated', 'local_pluginname');
    }

    public function get_description()
    {
        return "the user with id '{$this->userid}' created a subject with id '{$this->objectid}'";
    }
}
