<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Fee enrolment plugin.
 *
 * This plugin allows you to set up paid courses.
 *
 * @package    enrol_yafee
 * @copyright 2024 Alex Orlov <snickser@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;

require_once(__DIR__ . '/../../config.php');
require_once('lib.php');

global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

require_login();

$id = required_param('id', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

$password = optional_param('password', null, PARAM_TEXT);
$groupkey = optional_param('groupkey', 0, PARAM_INT);

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);
$instance = $DB->get_record('enrol', ['enrol' => 'yafee', 'id' => $id], '*', MUST_EXIST);

// Check and save group password.
$url = $CFG->wwwroot . '/enrol/index.php?id=' . $course->id;
if (isset($password) && !empty($password) && $groupkey) {
    if ($groupid = enrol_yafee_check_group_enrolment_key($course->id, $password)) {
        $data = new \stdClass();
        $data->userid = $USER->id;
        $data->courseid = $courseid;
        $data->ingroupid = $groupid;
        $DB->insert_record('enrol_yafee_ext', $data);

        redirect($url, get_string('passwordmatchesgroupkey', 'enrol_self'), 0, 'success');
    }
    redirect($url, get_string('passwordinvalid', 'enrol_self'), 0, 'error');
} else {
    redirect($url);
}

// For enrolled users only.
if (!is_enrolled($context, $USER, '', false)) {
    redirect($CFG->wwwroot . '/enrol/index.php?id=' . $course->id);
}

// Set the context of the page.
$PAGE->set_course($course);
$PAGE->set_context($context->get_parent_context());
$PAGE->set_pagelayout('course');
$PAGE->set_url('/enrol/index.php', ['id' => $course->id]);
$PAGE->add_body_class('limitedwidth');
$PAGE->set_cacheable(false);

$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);

$PAGE->navbar->add(get_string('courses'));
$PAGE->navbar->add($course->fullname, '/enrol/index.php?id=' . $course->id);
$PAGE->navbar->add(get_string('pluginname', 'enrol_yafee'));

echo $OUTPUT->header();

$plugin = enrol_get_plugin('yafee');
$form = $plugin->enrol_page_force($instance);

if ($form) {
    echo $OUTPUT->heading(get_string('renewenrolment', 'enrol_yafee'));
}

$courserenderer = $PAGE->get_renderer('core', 'course');
echo $courserenderer->course_info_box($course);

if ($form) {
    echo $form;
} else {
    notice(get_string('notenrollable', 'enrol'), $CFG->wwwroot);
}

echo $OUTPUT->footer();
