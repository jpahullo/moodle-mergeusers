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
 *
 * @package tool
 * @subpackage mergeusers
 * @author Jordi Pujol-Ahulló <jpahullo@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_mergeusers\di;

use tool_mergeusers\domain\date_formatter\date_formatter;
use tool_mergeusers\domain\logger\action_logger;
use tool_mergeusers\infrastructure\merge_request\merge_request;
use tool_mergeusers\domain\service\merge;
use tool_mergeusers\domain\user\user;

defined('MOODLE_INTERNAL') || die();

class dependency_container
{
    private static $container;
    private static $instances = array();

    /**
     * @return dependency_container
     */
    public static function instance()
    {
        if (is_null(self::$container)) {
            self::$container = new static();
        }
        return self::$container;
    }

    /**
     * @return date_formatter
     */
    public function date_formatter() {
        if (!isset(self::$instances['date_formatter'])) {
            self::$instances['date_formatter'] = new string_date_formatter();
        }
        return self::$instances['date_formatter'];
    }

    /**
     * @return action_logger
     */
    public function action_logger() {
        $dateformatter = self::date_formatter();
        return new generic_action_logger($dateformatter);
    }

    /**
     * @return merge_request
     */
    public function merge_request() {
        if (!isset(self::$instances['merge_request'])) {
            $actionlogger = self::action_logger();
            self::$instances['merge_request'] = new merge_request($actionlogger);
        }
        return self::$instances['merge_request'];
    }

    /**
     * @return merge
     */
    public function merge_service() {
        if (!isset(self::$instances['merge_service'])) {
            self::$instances['merge_service'] = new merge();
        }
        return self::$instances['merge_service'];
    }

    /**
     * @param \stdClass $user
     * @return user
     */
    public function user_from(\stdClass $user) {
        return new user($user);
    }
}
