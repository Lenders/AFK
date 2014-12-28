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

namespace app\controller;

/**
 * Description of Adminjson
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Adminjson extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Stats
     */
    private $stats;
    
    public function __construct(\system\Base $base, \app\model\Stats $stats) {
        parent::__construct($base);
        $this->stats = $stats;
        
        if($this->session->isAdmin !== 'YES')
            throw new \system\error\Http403Forbidden();
        
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/json');
    }
    
    public function malefemaleAction(){
        return json_encode($this->stats->getMaleFemaleCounts());
    }
    
    public function pagecountsAction($max = 10){
        return json_encode($this->stats->getPagesCounts($max));
    }
    
    public function browsercountsAction(){
        return json_encode($this->stats->getBrowserCounts());
    }
}
