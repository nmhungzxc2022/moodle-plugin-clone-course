<?php

namespace local_pluginname\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/user/lib.php'); // Thư viện để tạo user

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use context_system;
use stdClass;

class create_user extends external_api
{

    // 1. Khai báo tham số đầu vào (Input)
    public static function execute_parameters()
    {
        return new external_function_parameters(
            array(
                'username'        => new external_value(PARAM_USERNAME, 'Tên đăng nhập'),
                'firstname'       => new external_value(PARAM_TEXT, 'Họ đệm'),
                'lastname'        => new external_value(PARAM_TEXT, 'Tên'),
                'email'           => new external_value(PARAM_EMAIL, 'Email'),
                'createdpassword' => new external_value(PARAM_BOOL, 'Có bắt buộc đổi mật khẩu không?'),
                'password'        => new external_value(PARAM_RAW, 'Mật khẩu đăng nhập')
            )
        );
    }

    // 2. Hàm xử lý chính
    public static function execute($username, $firstname, $lastname, $email, $createdpassword, $password)
    {
        global $CFG;

        // Validate tham số
        $params = self::validate_parameters(self::execute_parameters(), array(
            'username' => $username,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'createdpassword' => $createdpassword,
            'password' => $password
        ));

        // Kiểm tra quyền (Admin mới được tạo user)
        require_capability('moodle/user:create', context_system::instance());

        $result = array(
            'status' => false,
            'id' => 0,
            'message' => ''
        );

        try {
            // Chuẩn bị dữ liệu user
            $user = new stdClass();
            $user->username = $params['username'];
            $user->firstname = $params['firstname'];
            $user->lastname = $params['lastname'];
            $user->email = $params['email'];
            $user->password = $params['password'];

            // Các thông số mặc định bắt buộc khác
            $user->auth = 'manual';
            $user->confirmed = 1;
            $user->mnethostid = $CFG->mnet_localhost_id;
            $user->lang = $CFG->lang;

            // Xử lý yêu cầu 'createdpassword' (Nếu true -> bắt đổi pass lần đầu)
            if ($params['createdpassword'] == true) {
                $user->preferences = array('auth_forcepasswordchange' => 1);
            }

            // Gọi hàm nội bộ Moodle để tạo user (An toàn hơn insert thẳng DB)
            $newuserid = user_create_user($user, false, false);

            $result['status'] = true;
            $result['id'] = $newuserid;
            $result['message'] = 'Tạo người dùng thành công!';
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
                'id'      => new external_value(PARAM_INT, 'ID người dùng mới'),
                'message' => new external_value(PARAM_TEXT, 'Thông báo')
            )
        );
    }
}
