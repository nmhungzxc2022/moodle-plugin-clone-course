<?php
require_once('../../config.php');

require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/pluginname/docs.php');
$PAGE->set_title('Tài liệu hướng dẫn');
$PAGE->set_heading('Tài liệu hướng dẫn sử dụng');

echo $OUTPUT->header();
echo '<div><a href="index.php">← Quay lại trang chính</a></div><hr>';

echo '<h2>Hướng dẫn sử dụng Plugin Quản lý Môn học</h2>';
echo '<h3>1. Thêm môn học mới</h3>';
echo '<ol>';
echo '<li>Click nút <strong>"Thêm môn học mới"</strong></li>';
echo '<li>Nhập tên môn học</li>';
echo '<li>Chọn ngày bắt đầu</li>';
echo '<li>Click <strong>"Lưu"</strong></li>';
echo '</ol>';

echo '<h3>2. Sửa môn học</h3>';
echo '<ol>';
echo '<li>Click <strong>"Sửa"</strong> ở cột Thao tác</li>';
echo '<li>Chỉnh sửa thông tin</li>';
echo '<li>Click <strong>"Lưu"</strong></li>';
echo '</ol>';

echo '<h3>3. Xóa môn học</h3>';
echo '<ol>';
echo '<li>Click <strong>"Xóa"</strong> ở cột Thao tác</li>';
echo '<li>Xác nhận xóa</li>';
echo '</ol>';

echo $OUTPUT->footer();
