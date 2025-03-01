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
 * @copyright based on work by 2019 Shamim Rezaie <shamim@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Fee enrolment plugin implementation.
 *
 * @copyright 2024 Alex Orlov <snickser@gmail.com>
 * @copyright based on work by 2019 Shamim Rezaie <shamim@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_yafee_plugin extends enrol_plugin {
    /**
     * Returns the list of currencies that the payment subsystem supports and therefore we can work with.
     *
     * @return array[currencycode => currencyname]
     */
    public function get_possible_currencies(): array {
        $codes = \core_payment\helper::get_supported_currencies();

        $currencies = [];
        foreach ($codes as $c) {
            $currencies[$c] = new lang_string($c, 'core_currencies');
        }

        uasort($currencies, function ($a, $b) {
            return strcmp($a, $b);
        });

        return $currencies;
    }

    /**
     * Returns optional enrolment information icons.
     *
     * This is used in course list for quick overview of enrolment options.
     *
     * We are not using single instance parameter because sometimes
     * we might want to prevent icon repetition when multiple instances
     * of one type exist. One instance may also produce several icons.
     *
     * @param array $instances all enrol instances of this type in one course
     * @return array of pix_icon
     */
    public function get_info_icons(array $instances) {
        $found = false;
        foreach ($instances as $instance) {
            if ($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()) {
                continue;
            }
            if ($instance->enrolenddate != 0 && $instance->enrolenddate < time()) {
                continue;
            }
            $found = true;
            break;
        }
        if ($found) {
            return [new pix_icon('icon', get_string('pluginname', 'enrol_yafee'), 'enrol_yafee')];
        }
        return [];
    }

    /**
     *
     * @return boolean
     */
    public function roles_protected() {
        // Users with role assign cap may tweak the roles later.
        return false;
    }

    /**
     *
     * @param stdClass $instance
     * @return boolean
     */
    public function allow_unenrol(stdClass $instance) {
        // Users with unenrol cap may unenrol other users manually - requires enrol/fee:unenrol.
        return true;
    }

    /**
     *
     * @param stdClass $instance
     * @return boolean
     */
    public function allow_manage(stdClass $instance) {
        // Users with manage cap may tweak period and status - requires enrol/fee:manage.
        return true;
    }

    /**
     *
     * @param stdClass $instance
     * @return boolean
     */
    public function show_enrolme_link(stdClass $instance) {
        return ($instance->status == ENROL_INSTANCE_ENABLED);
    }

    /**
     * Returns true if the user can add a new instance in this course.
     * @param int $courseid
     * @return boolean
     */
    public function can_add_instance($courseid) {
        $context = context_course::instance($courseid, MUST_EXIST);

        if (empty(\core_payment\helper::get_supported_currencies())) {
            return false;
        }

        if (!has_capability('moodle/course:enrolconfig', $context) || !has_capability('enrol/fee:config', $context)) {
            return false;
        }

        // Multiple instances supported - different cost for different roles.
        return true;
    }

    /**
     * We are a good plugin and don't invent our own UI/validation code path.
     *
     * @return boolean
     */
    public function use_standard_editing_ui() {
        return true;
    }

    /**
     * Returns defaults for new instances.
     * @return array
     */
    public function get_instance_defaults() {
        $expirynotify = $this->get_config('expirynotify');
        if ($expirynotify == 2) {
            $expirynotify = 1;
            $notifyall = 1;
        } else {
            $notifyall = 0;
        }

        $fields = [];
        $fields['status']          = $this->get_config('status');
        $fields['roleid']          = $this->get_config('roleid');
        $fields['enrolperiod']     = $this->get_config('enrolperiod');
        $fields['expirynotify']    = $expirynotify;
        $fields['notifyall']       = $notifyall;
        $fields['expirythreshold'] = $this->get_config('expirythreshold');
        $fields['customint5']      = 0;
        $fields['customint6']      = 0;
        $fields['customint7']      = 0;
        $fields['customint8']      = 0;
        $fields['customchar1']     = 'minute';

        return $fields;
    }

    /**
     * Add new instance of enrol plugin.
     * @param object $course
     * @param ?array $fields instance fields
     * @return int id of new instance, null if can not be created
     */
    public function add_instance($course, ?array $fields = null) {
        if ($fields && !empty($fields['cost'])) {
            $fields['cost'] = unformat_float($fields['cost']);
        }
        // In the form we are representing 2 db columns with one field.
        if (!empty($fields) && !empty($fields['expirynotify'])) {
            if ($fields['expirynotify'] == 2) {
                $fields['expirynotify'] = 1;
                $fields['notifyall'] = 1;
            } else {
                $fields['notifyall'] = 0;
            }
        }
        return parent::add_instance($course, $fields);
    }

    /**
     * Update instance of enrol plugin.
     * @param stdClass $instance
     * @param stdClass $data modified instance fields
     * @return boolean
     */
    public function update_instance($instance, $data) {
        if ($data) {
            $data->cost = unformat_float($data->cost);
        }
        // In the form we are representing 2 db columns with one field.
        if ($data->expirynotify == 2) {
            $data->expirynotify = 1;
            $data->notifyall = 1;
        } else {
            $data->notifyall = 0;
        }
        // Keep previous/default value of disabled expirythreshold option.
        if (!$data->expirynotify) {
            $data->expirythreshold = $instance->expirythreshold;
        }
        // Check trial.
        if (!$data->trialenabled) {
            $data->customint5 = 0;
            $data->customint7 = 0;
        }
        // Make standard periods.
        switch ($data->customchar1) {
            case 'minute':
                $data->enrolperiod = 60 * $data->customint7;
                break;
            case 'hour':
                $data->enrolperiod = 3600 * $data->customint7;
                break;
            case 'day':
                $data->enrolperiod = 86400 * $data->customint7;
                break;
            case 'week':
                $data->enrolperiod = 86400 * 7 * $data->customint7;
                break;
            default:
                $data->enrolperiod = 0;
        }
        return parent::update_instance($instance, $data);
    }

    /**
     * Creates course enrol form, checks if form submitted
     * and enrols user if necessary. It can also redirect.
     *
     * @param stdClass $instance
     * @return string html text, usually a form in a text box
     */
    public function enrol_page_hook(stdClass $instance) {
        return $this->show_payment_info($instance);
    }

    /**
     * Returns optional enrolment instance description text.
     *
     * This is used in detailed course information.
     *
     *
     * @param object $instance
     * @return string short html text
     */
    public function get_description_text($instance) {
        return $this->show_payment_info($instance);
    }

    /**
     * Generates payment information to display on enrol/info page.
     *
     * @param stdClass $instance
     * @return false|string
     * @throws coding_exception
     * @throws dml_exception
     */
    private function show_payment_info(stdClass $instance) {
        global $USER, $OUTPUT, $DB;

        ob_start();

        if ($DB->record_exists('user_enrolments', ['userid' => $USER->id, 'enrolid' => $instance->id])) {
            $data = $DB->get_record('user_enrolments', ['userid' => $USER->id, 'enrolid' => $instance->id]);
            if ($data->status) {
                return ob_get_clean();
            }
        }

        if ($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()) {
            return ob_get_clean();
        }

        if ($instance->enrolenddate != 0 && $instance->enrolenddate < time()) {
            return ob_get_clean();
        }

        // Show enrolperiod.
        $enrolperiod = $instance->enrolperiod;
        $enrolperioddesc = '';
        $freetrial = false;
        if ($instance->customint8 || $instance->customint6) {
            // Check first time trial.
            if (
                $instance->customint6 && !$DB->record_exists('enrol_yafee', ['courseid' => $instance->courseid,
                    'userid' => $USER->id])
            ) {
                    $enrolperiod = $instance->customint6;
                    $freetrial = true;
            }
            // Check month and year.
            if ($instance->customchar1 == 'month' && $instance->customint7 > 0 && !$freetrial) {
                $enrolperiod = $instance->customint7;
                $enrolperioddesc = get_string('months');
            } else if ($instance->customchar1 == 'year' && $instance->customint7 > 0 && !$freetrial) {
                $enrolperiod = $instance->customint7;
                $enrolperioddesc = get_string('years');
            } else if ($enrolperiod > 0) {
                if ($enrolperiod >= 86400 * 7) {
                    $enrolperioddesc = get_string('weeks');
                    $enrolperiod = round($enrolperiod / (86400 * 7));
                } else if ($enrolperiod >= 86400) {
                    $enrolperioddesc = get_string('days');
                    $enrolperiod = round($enrolperiod / 86400);
                } else if ($enrolperiod >= 3600) {
                    $enrolperioddesc = get_string('hours');
                    $enrolperiod = round($enrolperiod / 3600);
                } else if ($enrolperiod >= 60) {
                    $enrolperioddesc = get_string('minutes');
                    $enrolperiod = round($enrolperiod / 60);
                }
            }
        }

        $course = $DB->get_record('course', ['id' => $instance->courseid]);
        $context = context_course::instance($course->id);

        if ((float) $instance->cost <= 0) {
            $cost = (float) $this->get_config('cost');
        } else {
            $cost = (float) $instance->cost;
        }

        // Check uninterrupted cost.
        if ($instance->customint5 && $instance->enrolperiod && isset($data) && $data->timeend < time()) {
            $price = $cost / $instance->enrolperiod;
            $cost += (time() - $data->timeend) * $price;
        } else {
            $instance->customint5 = 0;
        }

        if (abs($cost) < 0.01) { // No cost, other enrolment methods (instances) should be used.
            echo '<p>' . get_string('nocost', 'enrol_yafee') . '</p>';
        } else {
            $data = [
                'isguestuser' => isguestuser() || !isloggedin(),
                'cost' => \core_payment\helper::get_cost_as_string($cost, $instance->currency),
                'instanceid' => $instance->id,
                'uninterrupted' => $instance->customint5,
                'description' => get_string(
                    'purchasedescription',
                    'enrol_yafee',
                    format_string($course->fullname, true, ['context' => $context])
                ),
                'successurl' => \enrol_yafee\payment\service_provider::get_success_url('fee', $instance->id)->out(false),
                'enrolperiod' => $enrolperiod,
                'enrolperiod_desc' => $enrolperioddesc,
                'freetrial' => $freetrial,
                'sesskey' => sesskey(),
            ];
            echo $OUTPUT->render_from_template('enrol_yafee/payment_region', $data);
        }

        return $OUTPUT->box(ob_get_clean());
    }

    /**
     * Restore instance and map settings.
     *
     * @param restore_enrolments_structure_step $step
     * @param stdClass $data
     * @param stdClass $course
     * @param int $oldid
     */
    public function restore_instance(restore_enrolments_structure_step $step, stdClass $data, $course, $oldid) {
        global $DB;
        if ($step->get_task()->get_target() == backup::TARGET_NEW_COURSE) {
            $merge = false;
        } else {
            $merge = [
                'courseid'   => $data->courseid,
                'enrol'      => $this->get_name(),
                'roleid'     => $data->roleid,
                'cost'       => $data->cost,
                'currency'   => $data->currency,
            ];
        }
        if ($merge && $instances = $DB->get_records('enrol', $merge, 'id')) {
            $instance = reset($instances);
            $instanceid = $instance->id;
        } else {
            $instanceid = $this->add_instance($course, (array) $data);
        }
        $step->set_mapping('enrol', $oldid, $instanceid);
    }

    /**
     * Restore user enrolment.
     *
     * @param restore_enrolments_structure_step $step
     * @param stdClass $data
     * @param stdClass $instance
     * @param int $userid
     * @param int $oldinstancestatus
     */
    public function restore_user_enrolment(restore_enrolments_structure_step $step, $data, $instance, $userid, $oldinstancestatus) {
        $this->enrol_user($instance, $userid, null, $data->timestart, $data->timeend, $data->status);
    }

    /**
     * Return an array of valid options for the status.
     *
     * @return array
     */
    protected function get_status_options() {
        $options = [ENROL_INSTANCE_ENABLED  => get_string('yes'),
                         ENROL_INSTANCE_DISABLED => get_string('no')];
        return $options;
    }

    /**
     * Return an array of valid options for the roleid.
     *
     * @param stdClass $instance
     * @param context $context
     * @return array
     */
    protected function get_roleid_options($instance, $context) {
        if ($instance->id) {
            $roles = get_default_enrol_roles($context, $instance->roleid);
        } else {
            $roles = get_default_enrol_roles($context, $this->get_config('roleid'));
        }
        return $roles;
    }


    /**
     * Return an array of valid options for the expirynotify property.
     *
     * @return array
     */
    protected function get_expirynotify_options() {
        $options = [0 => get_string('no'),
                         1 => get_string('expirynotifyenroller', 'core_enrol'),
                         2 => get_string('expirynotifyall', 'core_enrol')];
        return $options;
    }


    /**
     * Add elements to the edit instance form.
     *
     * @param stdClass $instance
     * @param MoodleQuickForm $mform
     * @param context $context
     * @return bool
     */
    public function edit_instance_form($instance, MoodleQuickForm $mform, $context) {

        // Merge these two settings to one value for the single selection element.
        if ($instance->notifyall && $instance->expirynotify) {
            $instance->expirynotify = 2;
        }
        unset($instance->notifyall);

        $mform->addElement('text', 'name', get_string('custominstancename', 'enrol'));
        $mform->setType('name', PARAM_TEXT);

        $options = $this->get_status_options();
        $mform->addElement('select', 'status', get_string('status', 'enrol_yafee'), $options);
        $mform->setDefault('status', $this->get_config('status'));

        $accounts = \core_payment\helper::get_payment_accounts_menu($context);
        if ($accounts) {
            $accounts = ((count($accounts) > 1) ? ['' => ''] : []) + $accounts;
            $mform->addElement('select', 'customint1', get_string('paymentaccount', 'payment'), $accounts);
        } else {
            $mform->addElement(
                'static',
                'customint1_text',
                get_string('paymentaccount', 'payment'),
                html_writer::span(get_string('noaccountsavilable', 'payment'), 'alert alert-danger')
            );
            $mform->addElement('hidden', 'customint1');
            $mform->setType('customint1', PARAM_INT);
        }
        $mform->addHelpButton('customint1', 'paymentaccount', 'enrol_yafee');

        $mform->addElement('text', 'cost', get_string('cost', 'enrol_yafee'), ['size' => 4]);
        $mform->setType('cost', PARAM_RAW);
        $mform->setDefault('cost', format_float($this->get_config('cost'), 2, true));

        $supportedcurrencies = $this->get_possible_currencies();
        $mform->addElement('select', 'currency', get_string('currency', 'enrol_yafee'), $supportedcurrencies);
        $mform->setDefault('currency', $this->get_config('currency'));

        $roles = $this->get_roleid_options($instance, $context);
        $mform->addElement('select', 'roleid', get_string('assignrole', 'enrol_yafee'), $roles);
        $mform->setDefault('roleid', $this->get_config('roleid'));

        $mform->addElement('duration', 'customint6', get_string('freetrial', 'enrol_yafee'), ['optional' => true]);
        $mform->addHelpButton('customint6', 'freetrial', 'enrol_yafee');

        $trialarray = [];
        $trialarray[] =& $mform->createElement(
            'text',
            'customint7',
            '',
            ['size' => '3']
        );
        $mform->setType('customint7', PARAM_INT);
        $options = [
         'year' => get_string('years', 'moodle'),
         'month' => get_string('months', 'moodle'),
         'week' => get_string('weeks', 'moodle'),
         'day' => get_string('days', 'moodle'),
         'hour' => get_string('hours', 'moodle'),
         'minute' => get_string('mins', 'moodle'),
        ];
        $trialarray[] =& $mform->createElement(
            'select',
            'customchar1',
            '',
            $options,
        );
        $trialarray[] =& $mform->createElement('advcheckbox', 'trialenabled', '', get_string('enable'));
        if ($instance->customint7) {
            $mform->setDefault('trialenabled', 1);
        }
        $mform->addGroup($trialarray, 'duration', get_string('enrolperiod', 'enrol_yafee'), [' '], false);
        $mform->addHelpButton('duration', 'enrolperiod', 'enrol_yafee');
        $mform->DisabledIf('duration', 'trialenabled', "eq", 0);

        $plugininfo = \core_plugin_manager::instance()->get_plugin_info('paygw_bepaid');
        if ($plugininfo->versiondisk >= 2025023000) {
            $mform->addElement(
                'advcheckbox',
                'customint5',
                get_string('uninterrupted', 'enrol_yafee')
            );
            $mform->setType('customint5', PARAM_INT);
            $mform->addHelpButton('customint5', 'uninterrupted', 'enrol_yafee');
            $mform->DisabledIf('customint5', 'trialenabled', "eq", 0);
        }

        $mform->addElement(
            'advcheckbox',
            'customint8',
            get_string('showduration', 'enrol_yafee')
        );
        $mform->setType('customint8', PARAM_INT);

        $options = $this->get_expirynotify_options();
        $mform->addElement('select', 'expirynotify', get_string('expirynotify', 'core_enrol'), $options);
        $mform->addHelpButton('expirynotify', 'expirynotify', 'core_enrol');

        $options = ['optional' => false, 'defaultunit' => 86400];
        $mform->addElement('duration', 'expirythreshold', get_string('expirythreshold', 'core_enrol'), $options);
        $mform->addHelpButton('expirythreshold', 'expirythreshold', 'core_enrol');
        $mform->disabledIf('expirythreshold', 'expirynotify', 'eq', 0);

        $options = ['optional' => true];
        $mform->addElement('date_time_selector', 'enrolstartdate', get_string('enrolstartdate', 'enrol_yafee'), $options);
        $mform->setDefault('enrolstartdate', 0);
        $mform->addHelpButton('enrolstartdate', 'enrolstartdate', 'enrol_yafee');

        $options = ['optional' => true];
        $mform->addElement('date_time_selector', 'enrolenddate', get_string('enrolenddate', 'enrol_yafee'), $options);
        $mform->setDefault('enrolenddate', 0);
        $mform->addHelpButton('enrolenddate', 'enrolenddate', 'enrol_yafee');

        $plugininfo = \core_plugin_manager::instance()->get_plugin_info('enrol_yafee');
        $donate = get_string('donate', 'enrol_yafee', $plugininfo);
        $mform->addElement('html', $donate);

        if (enrol_accessing_via_instance($instance)) {
            $warningtext = get_string('instanceeditselfwarningtext', 'core_enrol');
            $mform->addElement('static', 'selfwarn', get_string('instanceeditselfwarning', 'core_enrol'), $warningtext);
        }
    }

    /**
     * Perform custom validation of the data used to edit the instance.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @param object $instance The instance loaded from the DB
     * @param context $context The context of the instance we are editing
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK.
     * @return void
     */
    public function edit_instance_validation($data, $files, $instance, $context) {
        $errors = [];

        if (!empty($data['enrolenddate']) && $data['enrolenddate'] < $data['enrolstartdate']) {
            $errors['enrolenddate'] = get_string('enrolenddaterror', 'enrol_yafee');
        }

        $cost = str_replace(get_string('decsep', 'langconfig'), '.', $data['cost']);
        if (!is_numeric($cost)) {
            $errors['cost'] = get_string('costerror', 'enrol_yafee');
        }

        if ($data['expirynotify'] > 0 && $data['expirythreshold'] < 86400) {
            $errors['expirythreshold'] = get_string('errorthresholdlow', 'core_enrol');
        }

        $validstatus = array_keys($this->get_status_options());
        $validcurrency = array_keys($this->get_possible_currencies());
        $validexpirynotify = array_keys($this->get_expirynotify_options());
        $validroles = array_keys($this->get_roleid_options($instance, $context));
        $tovalidate = [
            'name' => PARAM_TEXT,
            'status' => $validstatus,
            'currency' => $validcurrency,
            'roleid' => $validroles,
            'expirynotify' => $validexpirynotify,
            'enrolstartdate' => PARAM_INT,
            'enrolenddate' => PARAM_INT,
            'customint5' => PARAM_INT,
            'customint6' => PARAM_INT,
            'customint7' => PARAM_INT,
            'customint8' => PARAM_INT,
            'customchar1' => PARAM_TEXT,
        ];
        if ($data['expirynotify'] != 0) {
            $tovalidate['expirythreshold'] = PARAM_INT;
        }

        $typeerrors = $this->validate_param_types($data, $tovalidate);
        $errors = array_merge($errors, $typeerrors);

        if (
            $data['status'] == ENROL_INSTANCE_ENABLED &&
                (!$data['customint1'] ||
                    !array_key_exists($data['customint1'], \core_payment\helper::get_payment_accounts_menu($context)))
        ) {
            $errors['status'] = get_string('validationerror', 'enrol_yafee');
        }

        return $errors;
    }

    /**
     * Execute synchronisation.
     * @param progress_trace $trace
     * @return int exit code, 0 means ok
     */
    public function sync(progress_trace $trace) {
        $this->process_expirations($trace);
        return 0;
    }

    /**
     * Is it possible to delete enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_delete_instance($instance) {
        $context = context_course::instance($instance->courseid);
        return has_capability('enrol/fee:config', $context);
    }

    /**
     * Is it possible to hide/show enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_hide_show_instance($instance) {
        $context = context_course::instance($instance->courseid);
        return has_capability('enrol/fee:config', $context);
    }

    /**
     * Sets up navigation entries.
     *
     * @param navigation_node $instancesnode navigation node
     * @param stdClass $instance enrol record instance
     *
     * @throws coding_exception
     * @return void
     */
    public function add_course_navigation($instancesnode, stdClass $instance) {
        global $USER, $DB;
        if ($instance->enrol !== 'yafee') {
            throw new coding_exception('Invalid enrol instance type!');
        }
        $context = context_course::instance($instance->courseid);

        if ($data = $DB->get_record('user_enrolments', ['userid' => $USER->id, 'enrolid' => $instance->id])) {
            if ($instance->expirynotify && $data->timeend && $data->timeend - time() < $instance->expirythreshold) {
                // Now manipulate upwards, bail as quickly as possible if not appropriate.
                $navigation = $instancesnode;
                while ($navigation->parent !== null) {
                    $navigation = $navigation->parent;
                }
                if (!$courseadminnode = $navigation->get("courseadmin")) {
                    return;
                }
                // Locate or add our own node if appropriate.
                if (!$cayafeenode = $courseadminnode->get("cayafee")) {
                    $nodeproperties = [
                        'text'          => get_string('menuname', 'enrol_yafee'),
                        'shorttext'     => get_string('menunameshort', 'enrol_yafee'),
                        'type'          => navigation_node::TYPE_SETTING,
                        'key'           => 'cayafee',
                    ];
                    $cayafeenode = new navigation_node($nodeproperties);
                    $courseadminnode->add_node($cayafeenode, 'users');
                }
                // Add node.
                $cayafeenode->add(
                    get_string('menuname', 'enrol_yafee'),
                    new moodle_url('/enrol/yafee/pay.php', ['id' => $instance->id, 'sesskey' => sesskey()]),
                    navigation_node::TYPE_SETTING,
                    get_string('menuname', 'enrol_yafee'),
                    'yafee',
                    new pix_icon('icon', '', 'enrol_yafee')
                );
            }
        }
    }
}
