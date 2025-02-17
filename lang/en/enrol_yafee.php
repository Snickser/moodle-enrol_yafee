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
 * Strings for component 'enrol_yafee', language 'en'
 *
 * @package    enrol_yafee
 * @copyright 2025 Alex Orlov <snickser@gmail.com>
 * @copyright based on work by 2019 Shamim Rezaie <shamim@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['assignrole'] = 'Assign role';
$string['cost'] = 'Enrolment fee';
$string['costerror'] = 'The enrolment fee must be a number.';
$string['currency'] = 'Currency';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_desc'] = 'Select the role to assign to users after making a payment.';
$string['donate'] = '<div>Plugin version: {$a->release} ({$a->versiondisk})<br>
You can find new versions of the plugin at <a href=https://github.com/Snickser/moodle-enrol_yafee>GitHub.com</a>
<img src="https://img.shields.io/github/v/release/Snickser/moodle-enrol_yafee.svg"><br>
Please send me some <a href="https://yoomoney.ru/fundraise/143H2JO3LLE.240720">donate</a>😊</div>
BTC 1FHtZ82jLoBZ8ZsU7J2E9Cxy2xgUU7GJtD<br>
<iframe src="https://yoomoney.ru/quickpay/fundraise/button?billNumber=143H2JO3LLE.240720"
width="330" height="50" frameborder="0" allowtransparency="true" scrolling="no"></iframe><br>';
$string['enrolenddate'] = 'End date';
$string['enrolenddate_help'] = 'If enabled, users can be enrolled until this date only.';
$string['enrolenddaterror'] = 'The enrolment end date cannot be earlier than the start date.';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['enrolstartdate'] = 'Start date';
$string['enrolstartdate_help'] = 'If enabled, users can only be enrolled from this date onwards.';
$string['expiredaction'] = 'Enrolment expiry action';
$string['expiredaction_help'] = 'Select the action to be performed when a user\'s enrolment expires. Please note that some user data and settings are deleted when a user is unenrolled.';
$string['expirymessageenrolledbody'] = 'Dear {$a->user},

This is a notification that your enrolment in the course \'{$a->course}\' is due to expire on {$a->timeend}.

If you need help, please contact {$a->enroller}.';
$string['expirymessageenrolledsubject'] = 'Fee enrolment expiry notification';
$string['expirymessageenrollerbody'] = 'Fee enrolment in the course \'{$a->course}\' will expire within the next {$a->threshold} for the following users:

{$a->users}

To extend their enrolment, go to {$a->extendurl}';
$string['expirymessageenrollersubject'] = 'Fee enrolment expiry notification';
$string['expirynotifyall'] = 'Teacher and enrolled user';
$string['expirynotifyenroller'] = 'Teacher only';
$string['extremovedsuspendnoroles'] = 'Suspend course enrolment and remove roles';
$string['freetrial'] = 'Free trial of training';
$string['freetrialbutton'] = 'Trial enrol';
$string['freetrial_desc'] = 'You have a trial period to enrol';
$string['freetrial_help'] = 'Allows you to open a course once for a certain period of time without payment.';
$string['messageprovider:expiry_notification'] = 'Self enrolment expiry notifications';
$string['nocost'] = 'There is no cost to enrol in this course!';
$string['paymentaccount'] = 'Payment account';
$string['paymentaccount_help'] = 'Enrolment fees will be paid to this account.';
$string['pluginname'] = 'Yet another Enrolment on payment';
$string['pluginname_desc'] = 'The enrolment on payment enrolment method allows you to set up courses requiring a payment. If the fee for any course is set to zero, then students are not asked to pay for entry. There is a site-wide fee that you set here as a default for the whole site and then a course setting that you can set for each course individually. The course fee overrides the site fee.';
$string['privacy:metadata'] = 'The enrolment on payment enrolment plugin does not store any personal data.';
$string['purchasedescription'] = 'Enrolment in course {$a}';
$string['sendexpirynotificationstask'] = 'Fee enrolment send expiry notifications task';
$string['sendpaymentbutton'] = 'Select payment type';
$string['showduration'] = 'Show duration of training';
$string['status'] = 'Allow enrolment on payment enrolments';
$string['status_desc'] = 'Allow users to make a payment to enrol into a course by default.';
$string['syncenrolmentstask'] = 'Synchronise fee enrolments task';
$string['validationerror'] = 'Enrolments can not be enabled without specifying the payment account';
$string['yafee:config'] = 'Configure enrolment on payment enrol instances';
$string['yafee:manage'] = 'Manage enrolled users';
$string['yafee:unenrol'] = 'Unenrol users from course';
$string['yafee:unenrolself'] = 'Unenrol self from course';
