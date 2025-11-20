<?php

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\local_pluginname\event\subject_deleted',
        'callback' => '\local_pluginname\observer::subject_deleted',
        'includefile' => '/local/pluginname/classes/observer.php',
        'priority' => 9999,
    ],
];
