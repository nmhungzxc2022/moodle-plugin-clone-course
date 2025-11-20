<?php
require_once('../../config.php');
require_once('classes/form/subject_form.php');

require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/pluginname/add.php');
$PAGE->set_title('Thêm môn học mới');
$PAGE->set_heading('Thêm môn học mới');

$mform = new \local_pluginname\form\subject_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/pluginname/index.php'));
} else if ($data = $mform->get_data()) {
    global $DB;

    $record = new stdClass();
    $record->name = $data->name;
    $record->startdate = $data->startdate;
    $record->timecreated = time();
    $record->timemodified = time();
    $record->description = $data->description; // thêm description

    $subjectid = $DB->insert_record('local_pluginname_subjects', $record);

    $event = \local_pluginname\event\subject_created::create([ // thêm
        'context' => \context_system::instance(),
        'objectid' => $subjectid,
    ]);
    $event->trigger();


    redirect(new moodle_url('/local/pluginname/index.php'), 'Thêm môn học thành công!', null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo '<div><a href="index.php">← Quay lại danh sách</a></div><br>';
$mform->display();
echo $OUTPUT->footer();
