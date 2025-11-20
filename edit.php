<?php
require_once('../../config.php');
require_once('classes/form/subject_form.php');

require_login();

$id = required_param('id', PARAM_INT);

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/pluginname/edit.php', array('id' => $id));
$PAGE->set_title('Sửa môn học');
$PAGE->set_heading('Sửa môn học');

$subject = $DB->get_record('local_pluginname_subjects', array('id' => $id), '*', MUST_EXIST);

$mform = new \local_pluginname\form\subject_form();
$mform->set_data($subject);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/pluginname/index.php'));
} else if ($data = $mform->get_data()) {

    $record = new stdClass();
    $record->id = $data->id;
    $record->name = $data->name;
    $record->startdate = $data->startdate;
    $record->timemodified = time();
    $record->description = $data->description; //thêm description

    $subjectid = $DB->update_record('local_pluginname_subjects', $record);

    $event = \local_pluginname\event\subject_edited::create([ //thêm
        'context' => \context_system::instance(),
        'objectid' => $subject->id,
    ]);
    $event->trigger();


    redirect(new moodle_url('/local/pluginname/index.php'), 'Cập nhật môn học thành công!', null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo '<div><a href="index.php">← Quay lại danh sách</a></div><br>';
$mform->display();
echo $OUTPUT->footer();
