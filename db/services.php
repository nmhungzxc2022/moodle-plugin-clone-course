<?php

defined("MOODLE_INTERNAL") || die();

$functions = [
    'local_pluginname_clone_course' => [
        'classname' => 'local_pluginname\external\clone_course',
        'methodname'  => 'execute',  //BẠN ĐANG THIẾU DÒNG NÀY
        'description' => 'clone a moodle course',
        'type' => 'write',
        'ajax' => true,
        'services' => ['Myservice'],  //cho pheps goij qua REST
    ],

    // --- THÊM ĐOẠN NÀY VÀO ---
    'local_pluginname_create_user' => [
        'classname'   => 'local_pluginname\external\create_user',
        'methodname'  => 'execute',
        'description' => 'create a new user',
        'type'        => 'write',
        'ajax'        => true,
        'services'    => ['Myservice'],
    ],
];
