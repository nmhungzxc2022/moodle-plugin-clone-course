<?php

defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => 'local_pluginname\task\scheduled_task',
        'blocking'  => 0,
        'minute'    => '0',
        'hour'      => '5',
        'day'       => '1',
        'month'     => '*',
        'dayofweek' => '*',
    ]
];
