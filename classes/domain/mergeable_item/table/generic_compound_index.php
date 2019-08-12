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

namespace tool_mergeusers\domain\mergeable_item\table;

defined('MOODLE_INTERNAL') || die();

class generic_compound_index implements compound_index
{
    /**
     * @var array of strings
     */
    private $userfields;
    /**
     * @var array of strings
     */
    private $nonuserfields;
    /**
     * @var string for the SQL concat of the nonuserfields
     */
    private $sqlconcatnonuserfields;

    /**
     * @param array $userfields
     * @param array $nonuserfields
     */
    private function __construct($userfields, $nonuserfields)
    {
        $this->userfields = $userfields;
        $this->nonuserfields = $nonuserfields;
    }

    public function get_userfields()
    {
        return $this->userfields;
    }

    public function get_nonuserfields()
    {
        return $this->nonuserfields;
    }

    public function get_sql_concat_for_nonuserfields()
    {
        //TODO falta rebre nom de columna per personalitzar la sql_concat.
        // REFER
        if (!is_null($this->sqlconcatnonuserfields)) {
            return $this->sqlconcatnonuserfields;
        }
        global $DB;
        $this->sqlconcatnonuserfields = call_user_func_array(array($DB, 'sql_concat'), $this->nonuserfields);

        return $this->sqlconcatnonuserfields;
    }

}
