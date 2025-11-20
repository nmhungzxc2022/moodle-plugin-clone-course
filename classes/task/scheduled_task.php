<?php

namespace local_pluginname\task;

defined('MOODLE_INTERNAL') || die();

class scheduled_task extends \core\task\scheduled_task
{

    public function get_name()
    {
        return get_string('scheduled_task', 'local_pluginname');
    }

    public function execute()
    {
        global $CFG;

        // Load course creation library.
        require_once($CFG->dirroot . '/course/lib.php');

        $course = new \stdClass();
        $course->fullname  = 'Khóa học tự tạo ' . date('m/Y');
        $course->shortname = 'auto_' . date('Ym');
        $course->category  = 1;
        $course->startdate = time();

        create_course($course);

        mtrace("Đã tạo khóa học tự động: {$course->shortname}");
    }
}
