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
 * @package tool
 * @subpackage mergeusers
 * @author Jordi Pujol-Ahulló <jpahullo@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_mergeusers\domain\logger;

defined('MOODLE_INTERNAL') || die();


interface action_logger {

    /**
     * Marks the starting time of the logger.
     */
    public function start();
    public function register_error(string $message);
    public function register_action(string $message);
    /**
     * Marks the last time of the logged messages.
     */
    public function end();

    /**
     * @return bool true if there were errors.
     */
    public function with_errors();

    /**
     * @return array with the list of string logs.
     */
    public function to_array();
}
