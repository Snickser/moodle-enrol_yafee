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
global $USER, $DB;

defined('MOODLE_INTERNAL') || die();

require_login();
require_sesskey();

$instanceid = required_param('id', PARAM_INT);

$instance = $DB->get_record('enrol', ['enrol' => 'yafee', 'id' => $instanceid], '*', MUST_EXIST);
$course = $DB->get_record('course', ['id' => $instance->courseid], '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

// Set the context of the page.
$PAGE->set_course($course);
$PAGE->set_context($context->get_parent_context());
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/enrol/index.php', ['id' => $course->id]);
$PAGE->set_secondary_navigation(false);
$PAGE->add_body_class('limitedwidth');

$PAGE->set_cacheable(false);
$PAGE->navbar->add(get_string('pluginname', 'enrol_yafee'));
$PAGE->set_title($course->shortname);
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

$courserenderer = $PAGE->get_renderer('core', 'course');
echo $courserenderer->course_info_box($course);

$plugin = enrol_get_plugin('yafee');
echo $plugin->get_description_text($instance);

echo $OUTPUT->footer();
