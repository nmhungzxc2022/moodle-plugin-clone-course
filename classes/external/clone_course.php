<?php

namespace local_pluginname\external;

defined('MOODLE_INTERNAL') || die();

// ← THÊM CÁC DÒNG NÀY
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
require_once($CFG->dirroot . '/course/lib.php');

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use course_summary_exporter;
use context_course;
use stdClass;

class clone_course extends external_api
{
    //dinh nghia tham so dau vao
    public static function execute_parameters(): external_function_parameters
    {
        return new external_function_parameters([
            'shortname_clone' => new external_value(PARAM_RAW, 'Shortname of course to clone'),
            'fullname' => new external_value(PARAM_TEXT, 'Fullname of new course'),
            'shortname' => new external_value(PARAM_TEXT, 'Shortname of new course'),
            'startdate' => new external_value(PARAM_INT, 'Start date timestamp'),
            'enddate' => new external_value(PARAM_INT, 'End date timestamp'),
        ]);
    }

    //logic clone course
    public static function execute($shortname_clone, $fullname, $shortname, $startdate, $enddate)
    {
        global $DB, $CFG;
        //validate parameters (xacs thuwcs cac tham so)
        $params = self::validate_parameters(self::execute_parameters(), [
            'shortname_clone' => $shortname_clone,
            'fullname' => $fullname,
            'shortname' => $shortname,
            'startdate' => $startdate,
            'enddate' => $enddate
        ]);

        //check course source
        $source = $DB->get_record('course', ['shortname' => $shortname_clone], '*', MUST_EXIST);
        /*if (!$source) {
            return [
                'status' => false,
                'id' => 0,
                'message' => "source course '$shortname_clone' not found"
            ];
        }*/

        //create new course record
        $newcourse = new stdClass;
        $newcourse->fullname = $fullname;
        $newcourse->shortname = $shortname;
        $newcourse->category = $source->category; //same category
        $newcourse->startdate = $startdate;
        $newcourse->enddate = $enddate;

        // Các thiết lập mặc định bắt buộc khác để tránh lỗi DB
        $newcourse->visible = 1;
        $newcourse->format = $source->format;

        require_capability('moodle/course:create', \context_system::instance());

        // Hàm này cần require_once('/course/lib.php') ở trên cùng
        $created_course = create_course($newcourse);
        $newcourseid = $created_course->id;

        //$newcourseid = create_course($newcourse)->id;
        /*// Kiểm tra quyền
        $context = \context_system::instance();
        if (!has_capability('moodle/course:create', $context)) {
            return [
                'status' => false,
                'id' => 0,
                'message' => 'You do not have permission to create courses'
            ];
        }

        try {
            $newcourseid = create_course($newcourse)->id;
        } catch (\Exception $e) {
            return [
                'status' => false,
                'id' => 0,
                'message' => 'Error creating course: ' . $e->getMessage()
            ];
        }*/

        //copy course content
        $backupcontroller = new \backup_controller(
            \backup::TYPE_1COURSE,
            $source->id,
            \backup::FORMAT_MOODLE,
            \backup::INTERACTIVE_NO,
            \backup::MODE_IMPORT,
            2
        );

        $backupid = $backupcontroller->get_backupid();
        $backupbasepath = $backupcontroller->get_plan()->get_basepath();
        $backupcontroller->execute_plan();

        $restorecontroller = new \restore_controller(
            $backupid,
            $newcourseid,
            \backup::INTERACTIVE_NO,
            \backup::MODE_IMPORT,
            2,
            \backup::TARGET_EXISTING_ADDING
        );

        $restorecontroller->execute_precheck();
        $restorecontroller->execute_plan();

        return [
            'status' => true,
            'id' => $newcourseid,
            'message' => "Course cloned successfully!"
        ];
    }

    //dinh nghia dau ra
    public static function execute_returns(): external_single_structure
    {
        return new external_single_structure([
            'status' => new external_value(PARAM_BOOL, 'True/False'),
            'id' => new external_value(PARAM_INT, 'New course ID'),
            'message' => new external_value(PARAM_TEXT, 'Success or error message')
        ]);
    }
}
