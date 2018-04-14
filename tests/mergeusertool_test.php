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

use tool_mergeusers\mergeusertool;

/**
 * Merge User Tool tests.
 *
 * @package    tool
 * @subpackage mergeusers
 * @author     Jordi Pujol-Ahulló <jpahullo@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_mergeusers_mergeusertool_testcase extends advanced_testcase {

    /**
     * @group tool_mergeusers
     * @group tool_mergeusers_mut
     */
    public function test_successful_merge() {
        global $DB;
        $this->resetAfterTest(true);

        // Setup two users to merge.
        $user_remove = $this->getDataGenerator()->create_user();
        $user_keep = $this->getDataGenerator()->create_user();

        $starting_time_mark = time();
        $mut = new mergeusertool();
        $mut->merge($user_keep, $user_remove);

        // Check $user_remove is suspended.
        $user_remove = $DB->get_record('user', array('id' => $user_remove->id));
        $this->assertEquals(1, $user_remove->suspended);

        $user_keep = $DB->get_record('user', array('id' => $user_keep->id));
        $this->assertEquals(0, $user_keep->suspended);

        $log = $DB->get_record("tool_mergeusers",
            [
                'fromuserid' => $user_remove->id,
                'touserid' => $user_keep->id,
            ]
        );

        $this->assertEquals($user_remove->id, $log->fromuserid);
        $this->assertEquals($user_keep->id, $log->touserid);
        $this->assertEquals(1, $log->success);
        $this->assertGreaterThanOrEqual($starting_time_mark, $log->timemodified);
        $this->assertNotNull($log->log);
        $this->assertNotNull(json_decode($log->log));
    }

    /**
     * @group tool_mergeusers
     * @group tool_mergeusers_mut
     */
    public function test_failed_merge_due_to_same_user() {
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();

        $mut = new mergeusertool();
        $mergeresult = $mut->merge($user, $user);

        $this->assertFalse($mergeresult->success);
        $this->assertEquals('Trying to merge the same user', $mergeresult->log[0]);
    }

    /**
     * @group tool_mergeusers
     * @group tool_mergeusers_mut
     */
    public function test_failed_merge_due_not_supported_database() {
        $this->resetAfterTest(true);

        // Setup two users to merge.
        $user_remove = $this->getDataGenerator()->create_user();
        $user_keep = $this->getDataGenerator()->create_user();

        $mut = new muttest();
        $mut->makeNonSupportedDatabase();
        $mergeresult = $mut->merge($user_keep, $user_remove);

        $this->assertFalse($mergeresult->success);
        $this->assertRegExp('/Error: Database type (.+) not supported./', $mergeresult->log[0]);
    }
}

class muttest extends mergeusertool {

    public function makeNonSupportedDatabase() {
        $this->supportedDatabase = false;
    }

}
