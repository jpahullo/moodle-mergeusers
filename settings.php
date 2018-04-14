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
 * Version information
 *
 * @package    tool
 * @subpackage mergeusers
 * @author     Nicolas Dunand <Nicolas.Dunand@unil.ch>
 * @author     Mike Holzer
 * @author     Forrest Gaston
 * @author     Juan Pablo Torres Herrera
 * @author     John Hoopes <hoopes@wisc.edu>, University of Wisconsin - Madison
 * @author     Jordi Pujol-Ahulló, SREd, Universitat Rovira i Virgili
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

use tool_mergeusers\config;
use tool_mergeusers\table\quizattemptsmerger;

if (has_capability('tool/mergeusers:mergeusers', context_system::instance())) {
    require_once($CFG->dirroot . '/'.$CFG->admin.'/tool/mergeusers/lib/autoload.php');
    require_once($CFG->dirroot . '/'.$CFG->admin.'/tool/mergeusers/lib.php');

    $ADMIN->add('accounts',
            new admin_category('tool_mergeusers', get_string('pluginname', 'tool_mergeusers')));
    $ADMIN->add('tool_mergeusers',
            new admin_externalpage('tool_mergeusers_merge', get_string('pluginname', 'tool_mergeusers'),
            $CFG->wwwroot.'/'.$CFG->admin.'/tool/mergeusers/index.php',
            'tool/mergeusers:mergeusers'));
    $ADMIN->add('tool_mergeusers',
            new admin_externalpage('tool_mergeusers_viewlog', get_string('viewlog', 'tool_mergeusers'),
            $CFG->wwwroot.'/'.$CFG->admin.'/tool/mergeusers/view.php',
            'tool/mergeusers:mergeusers'));
}

if ($hassiteconfig) {
    require_once($CFG->dirroot . '/'.$CFG->admin.'/tool/mergeusers/lib/autoload.php');
    require_once($CFG->dirroot . '/'.$CFG->admin.'/tool/mergeusers/lib.php');

    // Add configuration for making user suspension optional
    $settings = new admin_settingpage('mergeusers_settings',
        get_string('pluginname', 'tool_mergeusers'));

    $settings->add(new admin_setting_configcheckbox('tool_mergeusers/suspenduser',
        get_string('suspenduser_setting', 'tool_mergeusers'),
        get_string('suspenduser_setting_desc', 'tool_mergeusers'),
        1));

    $supporting_lang = (tool_mergeusers_transactionssupported()) ? 'transactions_supported' : 'transactions_not_supported';

    $settings->add(new admin_setting_configcheckbox('tool_mergeusers/transactions_only',
        get_string('transactions_setting', 'tool_mergeusers'),
        get_string('transactions_setting_desc', 'tool_mergeusers') . '<br /><br />' .
            get_string($supporting_lang, 'tool_mergeusers'),
        1));

    $config = config::instance();
    $none = get_string('none');
    $options = array('none' => $none);
    foreach ($config->exceptions as $exception) {
        $options[$exception] = $exception;
    }
    unset($options['my_pages']); //duplicated records make MyMoodle does not work.
    $settings->add(new admin_setting_configmultiselect('tool_mergeusers/excluded_exceptions',
        get_string('excluded_exceptions', 'tool_mergeusers'),
        get_string('excluded_exceptions_desc', 'tool_mergeusers', $none),
        array('none'), //default value: empty => apply all exceptions.
        $options));

    // quiz attempts
    $quizStrings = new stdClass();
    $quizStrings->{quizattemptsmerger::ACTION_RENUMBER} = get_string('qa_action_' . quizattemptsmerger::ACTION_RENUMBER, 'tool_mergeusers');
    $quizStrings->{quizattemptsmerger::ACTION_DELETE_FROM_SOURCE} = get_string('qa_action_' . quizattemptsmerger::ACTION_DELETE_FROM_SOURCE, 'tool_mergeusers');
    $quizStrings->{quizattemptsmerger::ACTION_DELETE_FROM_TARGET} = get_string('qa_action_' . quizattemptsmerger::ACTION_DELETE_FROM_TARGET, 'tool_mergeusers');
    $quizStrings->{quizattemptsmerger::ACTION_REMAIN} = get_string('qa_action_' . quizattemptsmerger::ACTION_REMAIN, 'tool_mergeusers');

    $quizOptions = array(
    quizattemptsmerger::ACTION_RENUMBER => $quizStrings->{quizattemptsmerger::ACTION_RENUMBER},
        quizattemptsmerger::ACTION_DELETE_FROM_SOURCE => $quizStrings->{quizattemptsmerger::ACTION_DELETE_FROM_SOURCE},
        quizattemptsmerger::ACTION_DELETE_FROM_TARGET => $quizStrings->{quizattemptsmerger::ACTION_DELETE_FROM_TARGET},
        quizattemptsmerger::ACTION_REMAIN => $quizStrings->{quizattemptsmerger::ACTION_REMAIN},
    );

    $settings->add(new admin_setting_configselect('tool_mergeusers/quizattemptsaction',
        get_string('quizattemptsaction', 'tool_mergeusers'),
        get_string('quizattemptsaction_desc', 'tool_mergeusers', $quizStrings),
        quizattemptsmerger::ACTION_REMAIN,
        $quizOptions)
    );

    $settings->add(new admin_setting_configcheckbox('tool_mergeusers/uniquekeynewidtomaintain',
        get_string('uniquekeynewidtomaintain', 'tool_mergeusers'),
        get_string('uniquekeynewidtomaintain_desc', 'tool_mergeusers'),
        1));

    // Add settings
    $ADMIN->add('tools', $settings);
}
