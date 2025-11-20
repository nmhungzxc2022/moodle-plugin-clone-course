<?php

namespace local_pluginname\external;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/course/lib.php');
// Nạp thư viện Backup & Restore (Quan trọng)
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use context_system;
use stdClass;

class clone_course extends external_api
{

    // 1. Khai báo tham số đầu vào (Input)
    public static function execute_parameters()
    {
        return new external_function_parameters(
            array(
                'shortname_clone' => new external_value(PARAM_TEXT, 'Shortname của khóa học gốc'),
                'fullname'        => new external_value(PARAM_TEXT, 'Fullname khóa học mới'),
                'shortname'       => new external_value(PARAM_TEXT, 'Shortname khóa học mới'),
                'startdate'       => new external_value(PARAM_INT, 'Ngày bắt đầu (timestamp)'),
                'enddate'         => new external_value(PARAM_INT, 'Ngày kết thúc (timestamp)', VALUE_DEFAULT, 0)
            )
        );
    }

    // 2. Hàm xử lý chính
    public static function execute($shortname_clone, $fullname, $shortname, $startdate, $enddate)
    {
        global $DB, $USER, $CFG;

        // Validate tham số
        $params = self::validate_parameters(self::execute_parameters(), array(
            'shortname_clone' => $shortname_clone,
            'fullname' => $fullname,
            'shortname' => $shortname,
            'startdate' => $startdate,
            'enddate' => $enddate
        ));

        require_capability('moodle/course:create', context_system::instance());

        $result = array(
            'status' => false,
            'id' => 0,
            'message' => ''
        );

        try {
            // A. Tìm khóa học gốc
            $sourcecourse = $DB->get_record('course', array('shortname' => $params['shortname_clone']), '*', MUST_EXIST);

            // B. Tạo vỏ khóa học mới trước
            $newcourse_data = new stdClass();
            $newcourse_data->fullname = $params['fullname'];
            $newcourse_data->shortname = $params['shortname'];
            $newcourse_data->category = $sourcecourse->category; // Cùng danh mục
            $newcourse_data->startdate = $params['startdate'];
            $newcourse_data->enddate = $params['enddate'];
            $newcourse_data->visible = 1;

            $newcourse = create_course($newcourse_data);

            // C. Thực hiện Backup khóa cũ (Vào vùng tạm)
            $bc = new \backup_controller(
                \backup::TYPE_1COURSE,
                $sourcecourse->id,
                \backup::FORMAT_MOODLE,
                \backup::INTERACTIVE_NO,
                \backup::MODE_IMPORT,
                $USER->id
            );
            $backupid = $bc->get_backupid();
            $bc->execute_plan();
            $bc->destroy();

            // D. Thực hiện Restore vào khóa mới (Đổ nội dung vào vỏ)
            $rc = new \restore_controller(
                $backupid,
                $newcourse->id,
                \backup::INTERACTIVE_NO,
                \backup::MODE_IMPORT,
                $USER->id,
                \backup::TARGET_EXISTING_ADDING
            );
            $rc->execute_precheck();
            $rc->execute_plan();
            $rc->destroy();

            // Trả về kết quả
            $result['status'] = true;
            $result['id'] = $newcourse->id;
            $result['message'] = 'Đã copy toàn bộ nội dung sang khóa mới!';
        } catch (\Exception $e) {
            $result['message'] = 'Lỗi: ' . $e->getMessage();
        }

        return $result;
    }

    // 3. Khai báo đầu ra (Output)
    public static function execute_returns()
    {
        return new external_single_structure(
            array(
                'status'  => new external_value(PARAM_BOOL, 'Trạng thái'),
                'id'      => new external_value(PARAM_INT, 'ID khóa học mới'),
                'message' => new external_value(PARAM_TEXT, 'Thông báo')
            )
        );
    }
}
