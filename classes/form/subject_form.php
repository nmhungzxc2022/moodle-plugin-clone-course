<?php

namespace local_pluginname\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class subject_form extends \moodleform
{

    public function definition()
    {
        $mform = $this->_form;

        $mform->addElement('text', 'name', 'Tên môn học', 'maxlength="255" size="50"');
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', 'Vui lòng nhập tên môn học', 'required', null, 'client');
        // Thêm field description
        $mform->addElement('textarea', 'description', 'Mô tả môn học', 'wrap="virtual" rows="5" cols="50"');
        $mform->setType('description', PARAM_TEXT);

        $mform->addElement('date_selector', 'startdate', 'Ngày bắt đầu');
        $mform->addRule('startdate', 'Vui lòng chọn ngày bắt đầu', 'required', null, 'client');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, 'Lưu');
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);

        if (empty(trim($data['name']))) {
            $errors['name'] = 'Tên môn học không được để trống';
        }

        return $errors;
    }
}
