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


use tool_mergeusers\domain\date_formatter\date_formatter;

class generic_action_logger implements action_logger
{
    /**
     * @var array
     */
    private $actionlog = [];
    /**
     * @var array
     */
    private $errorlog = [];
    /**
     * @var int
     */
    private $starttime = 0;
    /**
     * @var int
     */
    private $endtime = 0;
    /**
     * @var date_formatter;
     */
    private $dateformatter;


    public function __construct(date_formatter $dateformatter)
    {
        $this->dateformatter = $dateformatter;
    }

    public function start()
    {
        $this->starttime = time();
    }

    public function register_error($message)
    {
        $this->errorlog[] = $message;
    }

    public function register_action($message)
    {
        $this->actionlog[] = $message;
    }

    public function end()
    {
        $this->endtime = time();
    }

    public function to_array()
    {
        $result = array();
        $result[] = $this->dateformatter->format($this->starttime);
        if (empty($this->errorlog)) {
            $result = $result + $this->actionlog;
        } else {
            $result = $result + $this->errorlog;
        }
        $result[] = $this->dateformatter->format($this->endtime);
        $result[] = $this->dateformatter->duration($this->endtime-$this->starttime);
        return $result;
    }

    /**
     * @return bool true if there were errors.
     */
    public function with_errors()
    {
        return !empty($this->errorlog);
    }
}
