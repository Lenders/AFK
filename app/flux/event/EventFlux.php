<?php

/*
 * The MIT License
 *
 * Copyright 2014 Vincent Quatrevieux <quatrevieux.vincent@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace app\flux\event;

/**
 * Description of EventFlux
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class EventFlux extends \app\flux\Flux {
    private $eventId;
    
    public function __construct(\system\output\Output $output, \app\flux\parser\ParserHandler $parserHandler, \system\Database $db) {
        parent::__construct($output, $parserHandler, $db);
    }
    
    public function setEventId($eventId) {
        $this->eventId = $eventId;
    }
    
    protected function getStatement() {
        $stmt = $this->db->prepare('SELECT * FROM EVENT_FLUX WHERE EVENT_ID = :id');
        $stmt->bindValue('id', $this->eventId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
