<?php
require_once('../../config.php');

require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/pluginname/index.php');
$PAGE->set_title('Quản lý môn học');
$PAGE->set_heading('Quản lý môn học');

echo $OUTPUT->header();

echo '<div style="margin-bottom: 20px;">';
echo '<a href="add.php" class="btn btn-primary">Thêm môn học mới</a> ';
echo '<a href="docs.php" class="btn btn-secondary">Đọc tài liệu hướng dẫn</a>';
echo '</div>';

$subjects = $DB->get_records('local_pluginname_subjects', null, 'startdate DESC');

if (empty($subjects)) {
    echo '<div class="alert alert-info">Chưa có môn học nào. Hãy thêm môn học mới!</div>';
} else {
    echo '<h3>Danh sách môn học</h3>';
    echo '<table class="generaltable">';
    echo '<thead><tr>';
    echo '<th>ID</th>';
    echo '<th>Tên môn học</th>';
    echo '<th>Mô tả</th>'; //thêm mô tả
    echo '<th>Ngày bắt đầu</th>';
    echo '<th>Thao tác</th>';
    echo '</tr></thead><tbody>';

    foreach ($subjects as $subject) {
        echo '<tr>';
        echo '<td>' . $subject->id . '</td>';
        echo '<td>' . $subject->name . '</td>';
        echo '<td>' . $subject->description . '</td>'; //thêm mô tả
        echo '<td>' . userdate($subject->startdate, '%d/%m/%Y') . '</td>';
        echo '<td>';
        echo '<a href="edit.php?id=' . $subject->id . '">Sửa</a> | ';
        echo '<a href="delete.php?id=' . $subject->id . '" onclick="return confirm(\'Bạn có chắc muốn xóa?\')">Xóa</a>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}

echo $OUTPUT->footer();
