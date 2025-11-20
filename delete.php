<?php
require_once('../../config.php');

require_login();

$id = required_param('id', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/pluginname/delete.php', array('id' => $id));
$PAGE->set_title('Xóa môn học');
$PAGE->set_heading('Xóa môn học');

$subject = $DB->get_record('local_pluginname_subjects', array('id' => $id), '*', MUST_EXIST);

if ($confirm && confirm_sesskey()) {
    $DB->delete_records('local_pluginname_subjects', array('id' => $id));

    $event = \local_pluginname\event\subject_deleted::create([ //thêm
        'context' => \context_system::instance(),
        'objectid' => $id, // id của subject bị xóa
    ]);
    $event->trigger();

    redirect(new moodle_url('/local/pluginname/index.php'), 'Xóa môn học thành công!', null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo '<div><a href="index.php">← Quay lại danh sách</a></div><br>';

echo '<div class="alert alert-warning">';
echo '<h3>Bạn có chắc chắn muốn xóa môn học này?</h3>';
echo '<p><strong>Tên môn:</strong> ' . $subject->name . '</p>';
echo '<p><strong>Mô tả:</strong> ' . $subject->description . '</p>'; //thêm mô tả
echo '<p><strong>Ngày bắt đầu:</strong> ' . userdate($subject->startdate, '%d/%m/%Y') . '</p>';
echo '</div>';

$confirmurl = new moodle_url('/local/pluginname/delete.php', array('id' => $id, 'confirm' => 1, 'sesskey' => sesskey()));
$cancelurl = new moodle_url('/local/pluginname/index.php');

echo '<a href="' . $confirmurl . '" class="btn btn-danger">Xác nhận xóa</a> ';
echo '<a href="' . $cancelurl . '" class="btn btn-secondary">Hủy</a>';

echo $OUTPUT->footer();
