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
 * One Roster Enrolment Client.
 *
 * @package    enrol_oneroster
 * @copyright  Andrew Nicols <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_oneroster\local\exceptions;

use moodle_url;

/**
 * Entity cannot be processed - used where the server cannot validate an incoming entity.
 * Error 500.
 *
 * @package    enrol_oneroster
 * @copyright  Andrew Nicols <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class internal_server_error extends exception {

    /**
     * Constructor for a new IMSx Exception.
     *
     * @param   string $body
     * @param   array $curlinfo
     * @param   moodle_url $url
     */
    public function __construct(string $body, array $curlinfo, moodle_url $url) {
        parent::__construct(
            sprintf("Internal server failure (%s): %s", $url->out(false), $body),
            500
        );
    }
}
