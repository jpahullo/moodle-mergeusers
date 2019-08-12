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

namespace tool_mergeusers\domain\date_formatter;

defined('MOODLE_INTERNAL') || die();


class string_date_formatter implements date_formatter
{

    /**
     * @param int $timestamp
     * @return string
     */
    public function format($timestamp)
    {
        return userdate($timestamp);
    }

    /**
     * @param int $timestampduration
     * @return string
     */
    public function duration($timestampduration)
    {
        return format_time($timestampduration);
    }
}
