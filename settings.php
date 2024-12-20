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
 * Settings for the Fee enrolment plugin
 *
 * @package    enrol_yafee
 * @copyright 2024 Alex Orlov <snickser@gmail.com>
 * @copyright based on work by 2019 Shamim Rezaie <shamim@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $currencies = enrol_get_plugin('yafee')->get_possible_currencies();

    if (empty($currencies)) {
        $notify = new \core\output\notification(
            get_string('nocurrencysupported', 'core_payment'),
            \core\output\notification::NOTIFY_WARNING
        );
        $settings->add(new admin_setting_heading('enrol_yafee_nocurrency', '', $OUTPUT->render($notify)));
    }

    $settings->add(new admin_setting_heading('enrol_yafee_settings', '', get_string('pluginname_desc', 'enrol_yafee')));

    // Note: let's reuse the ext sync constants and strings here, internally it is very similar,
    // it describes what should happen when users are not supposed to be enrolled any more.
    $options = [
        ENROL_EXT_REMOVED_KEEP           => get_string('extremovedkeep', 'enrol'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES => get_string('extremovedsuspendnoroles', 'enrol_yafee'),
        ENROL_EXT_REMOVED_UNENROL        => get_string('extremovedunenrol', 'enrol'),
    ];
    $settings->add(new admin_setting_configselect(
        'enrol_yafee/expiredaction',
        get_string('expiredaction', 'enrol_yafee'),
        get_string('expiredaction_help', 'enrol_yafee'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES,
        $options
    ));

    $options = [];
    for ($i = 0; $i < 24; $i++) {
        $options[$i] = $i;
    }
    $settings->add(new admin_setting_configselect(
        'enrol_yafee/expirynotifyhour',
        get_string('expirynotifyhour', 'core_enrol'),
        '',
        6,
        $options
    ));

    $settings->add(new admin_setting_heading(
        'enrol_yafee_defaults',
        get_string('enrolinstancedefaults', 'admin'),
        get_string('enrolinstancedefaults_desc', 'admin')
    ));

    $options = [ENROL_INSTANCE_ENABLED  => get_string('yes'),
                     ENROL_INSTANCE_DISABLED => get_string('no')];
    $settings->add(new admin_setting_configselect(
        'enrol_yafee/status',
        get_string('status', 'enrol_yafee'),
        get_string('status_desc', 'enrol_yafee'),
        ENROL_INSTANCE_DISABLED,
        $options
    ));

    if (!empty($currencies)) {
        $settings->add(new admin_setting_configtext('enrol_yafee/cost', get_string('cost', 'enrol_yafee'), '', 0, PARAM_FLOAT, 4));
        $settings->add(new admin_setting_configselect(
            'enrol_yafee/currency',
            get_string('currency', 'enrol_yafee'),
            '',
            'USD',
            $currencies
        ));
    }

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect(
            'enrol_yafee/roleid',
            get_string('defaultrole', 'enrol_yafee'),
            get_string('defaultrole_desc', 'enrol_yafee'),
            $student->id ?? null,
            $options
        ));
    }

    $settings->add(new admin_setting_configduration(
        'enrol_yafee/enrolperiod',
        get_string('enrolperiod', 'enrol_yafee'),
        get_string('enrolperiod_desc', 'enrol_yafee'),
        3600,
        3600
    ));

    $options = [0 => get_string('no'),
                     1 => get_string('expirynotifyenroller', 'core_enrol'),
                     2 => get_string('expirynotifyall', 'core_enrol')];
    $settings->add(new admin_setting_configselect(
        'enrol_yafee/expirynotify',
        get_string('expirynotify', 'core_enrol'),
        get_string('expirynotify_help', 'core_enrol'),
        0,
        $options
    ));

    $settings->add(new admin_setting_configduration(
        'enrol_yafee/expirythreshold',
        get_string('expirythreshold', 'core_enrol'),
        get_string('expirythreshold_help', 'core_enrol'),
        86400,
        86400
    ));
}
