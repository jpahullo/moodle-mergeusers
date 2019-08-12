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

namespace tool_mergeusers\domain\merge_request;

defined('MOODLE_INTERNAL') || die();

use tool_mergeusers\domain\logger\action_logger;
use tool_mergeusers\domain\user\user;


class generic_merge_request implements merge_request
{
    /**
     * @var user
     */
    private $userkeep;
    /**
     * @var user
     */
    private $userremove;
    /**
     * @var bool
     */
    private $processing;
    /**
     * @var action_logger
     */
    private $logger;

    public function __construct(action_logger $logger)
    {
        $this->logger = $logger;
    }

    public function configure(user $userkeep, user $userremove)
    {
        $this->userkeep = $userkeep;
        $this->userremove = $userremove;
        $this->processing = false;

        if ($this->userkeep->id() == $this->userremove->id() ||
            $this->userkeep->username() == $this->userremove->username()) {
            throw new sameuserexception();
        }
    }

    public function start_processing()
    {
        $this->processing = true;
        $this->logger->start();
    }

    public function register_error(string $message)
    {
        $this->logger->register_error($message);
    }

    public function register_action(string $message)
    {
        $this->logger->register_action($message);
    }

    public function validate_or_fail()
    {
        $this->logger->end();

        $userkeepid = $this->userkeep->id();
        $userremoveid = $this->userremove->id();
        $log = $this->logger->to_array();

        if ($this->logger->with_errors()) {
            tool_mergeusers\event\user_merged_failure::trigger_from($userkeepid, $userremoveid, $log);
        } else {
            tool_mergeusers\event\user_merged_success::trigger_from($userkeepid, $userremoveid, $log);
        }

    }

    protected function userkeep() {
        return $this->userkeep;
    }

    protected function userremove() {
        return $this->userremove;
    }
}
