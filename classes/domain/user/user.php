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

namespace tool_mergeusers\domain\user;

defined('MOODLE_INTERNAL') || die();

class user  {

    private $data;

    public function __construct(\stdClass $data)
    {
        $this->data = $data;
    }

    public function id()
    {
        return $this->get_field('id');
    }

    private function get_field($name)
    {
        if (!isset($this->data->{$name})) {
            return null;
        }
        return $this->data->{$name};
    }

    public function username()
    {
        return $this->get_field('username');
    }

    public function idnumber()
    {
        return $this->get_field('idnumber');
    }
}
