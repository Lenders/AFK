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

namespace app\flux\parser;

/**
 * Description of Friend
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Friend implements Parser {
    /**
     *
     * @var \app\model\Account
     */
    private $account;
    
    /**
     *
     * @var \system\helper\Url
     */
    private $url;
    
    function __construct(\app\model\Account $account, \system\helper\Url $url) {
        $this->account = $account;
        $this->url = $url;
    }

    public function parseRow(array $row) {
        $senderPseudo = $this->account->getPseudoByUserId($row['SENDER']);
        $targetPseudo = $this->account->getPseudoByUserId($row['TARGET']);
        
        return array(
            'target' => $targetPseudo,
            'targetUrl' => $this->url->secureUrl('account', 'profile', $row['TARGET']),
            'sender' => $senderPseudo,
            'senderUrl' => $this->url->secureUrl('account', 'profile', $row['SENDER']),
            'date' => $row['FLUX_DATE'],
            'message' => <<<EOD
            <strong>$senderPseudo</strong> et <strong>$targetPseudo</strong> sont maintenant amis !
EOD
        );
    }

}
