<?php

namespace local_pluginname\event;

defined('MOODLE_INTERNAL') || die();

class subject_edited extends \core\event\base
{

    protected function init()
    {
        $this->data['crud'] = 'e'; //edit
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'local_pluginname_subjects';
    }

    public static function get_name()
    {
        return get_string('eventsubjectedited', 'local_pluginname');
    }

    public function get_description()
    {
        return "the user with id '{$this->userid}' edited subject id '{$this->objectid}'";
    }
}
