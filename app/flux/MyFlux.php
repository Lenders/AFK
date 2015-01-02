<?php

/*
 * The MIT License
 *
 * Copyright 2015 Vincent Quatrevieux <quatrevieux.vincent@gmail.com>.
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

namespace app\flux;

/**
 * Description of MyFlux
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class MyFlux extends Flux {
    private $userId;
    private $start = 0;
    
    public function setUserId($userId) {
        $this->userId = $userId;
    }
    
    public function setStart($start) {
        $this->start = $start;
    }

    protected function getStatement() {
        $stmt = $this->db->prepare(
            'SELECT DISTINCT * FROM ACCOUNT_FLUX '
          . 'WHERE SENDER_ID = :ID OR SENDER_ID IN ('
                . 'SELECT USER1 FROM FRIEND WHERE USER2 = :ID '
                . 'UNION SELECT USER2 FROM FRIEND WHERE USER1 = :ID '
         . ') UNION DISTINCT SELECT DISTINCT * FROM EVENT_FLUX WHERE TARGET_ID IN ('
                . 'SELECT E.EVENT_ID FROM EVENT E '
                . 'JOIN COMPETITOR C ON C.EVENT_ID = E.EVENT_ID '
                . 'WHERE C.USER_ID = :ID '
         . ') UNION DISTINCT SELECT EVENT_FLUX.* FROM EVENT_FLUX '
         . 'JOIN EVENT ON TARGET_ID = EVENT_ID '
         . 'WHERE FLUX_TYPE = \'CREATEEVENT\' AND ORGANIZER IN ('
                . 'SELECT USER1 FROM FRIEND WHERE USER2 = :ID '
                . 'UNION SELECT USER2 FROM FRIEND WHERE USER1 = :ID '
         . ') ORDER BY FLUX_DATE DESC LIMIT :START, 15'
        );
        
        $stmt->bindValue('ID', $this->userId, \PDO::PARAM_INT);
        $stmt->bindValue('START', $this->start, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
