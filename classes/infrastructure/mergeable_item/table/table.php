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

namespace tool_mergeusers\infrastructure\mergeable_item\table;

defined('MOODLE_INTERNAL') || die();

use tool_mergeusers\domain\mergeable_item\table\table as domain_table;
use tool_mergeusers\infrastructure\merge_request\merge_request;

class table extends domain_table
{
    /**
     * @var string
     */
    private $tablename;
    /**
     * @var array of strings
     */
    private $userfields;

    /**
     * @var string
     */
    private $userfieldsforsql;
    /**
     * @var array of compoundindex
     */
    private $compoundindexes;
    /**
     * @param string $tablename
     * @param array $userfields
     */
    public function __construct($tablename, $userfields, $compoundindexes)
    {
        $this->tablename = $tablename;
        $this->userfields = $userfields;
        $this->userfieldsforsql = implode(', ', $userfields);
        $this->compoundindexes = $compoundindexes;
    }

    /**
     * @param merge_request $request
     * @return boolean
     */
    protected function has_data_to_merge_for($request)
    {
        global $DB;

        list($sql, $params) = $this->build_sql_records_for($request->userremove_id());

        return $DB->record_exists_sql($sql, $params);

    }

    protected function build_sql_records_for($userremoveid)
    {
        $where = array();
        $params = array();
        foreach ($this->userfields as $userfield) {
            $where[] = "$userfield = ?";
            $params[] = $userremoveid;
        }
        $wherestring = implode(' OR ', $where);

        return array("
          SELECT id  
            FROM {" . $this->tablename . "}
           WHERE $wherestring", $params);
    }

    protected function merge_user_data_for($request)
    {
        //TODO
        $tableidsforuserremovetoupdate = $this->get_records_for_user($request->userremove_id());
        $tableidsforusersoncompoundindex = $this->get_records_for_compound_indexes($request->userremove_id(), $request->userkeep_id());
        $this->update_with($request, $tableidsforuserremovetoupdate, $tableidsforusersoncompoundindex);
    }

    protected function get_records_for_user($userid)
    {
        global $DB;

        list($sql, $params) = $this->build_sql_records_for($userid);

        return $DB->get_records_sql($sql, $params);
    }

    protected function get_records_for_compound_indexes($userremoveid, $userkeepid)
    {
        global $DB;
        //TODO falta usar els $this->compoundindexes per tal de processar tota la taula en cas que n'hi hagi.
        //TODO cal completar la funcionalitat del compound_index perquè sigui útil, estil ValueObjec
        $sql = 'SELECT id, ' . $userfield . ', ' . $otherfieldsstr .
        ' FROM ' . $CFG->prefix . $data['tableName'] .
        ' WHERE ' . $userfield . ' in (' . $data['fromid'] . ', ' . $data['toid'] . ')';
        $result = $DB->get_records_sql($sql);
    }

}
