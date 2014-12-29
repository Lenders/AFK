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
 * Description of Search
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Search extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Account
     */
    private $account;
    
    /**
     *
     * @var \app\model\Event
     */
    private $event;
    
    public function __construct(\system\Base $base, \app\model\Account $account, \app\model\Event $event) {
        parent::__construct($base);
        $this->account = $account;
        $this->event = $event;
    }
    
    public function indexAction(){
        $this->output->setTitle('Rechercher');
        $this->helpers->loadHelper('FriendButton');
        
        return $this->output->render('search/results.php', array(
            'users' => $this->account->searchAccount($this->input->get->search),
            'events' => $this->event->searchEvents($this->input->get->search)
        ));
    }
}
