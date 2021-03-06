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
 * Controller for Json requests
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Json extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Friend
     */
    private $friend;
    
    /**
     *
     * @var \app\model\Message
     */
    private $message;
    
    public function __construct(\system\Base $base, \app\model\Friend $friend, \app\model\Message $message) {
        parent::__construct($base);
        $this->friend = $friend;
        $this->message = $message;
        
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();

        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/json');
    }
    
    public function checkonlineAction(){
        if(!isset($this->input->post->who)){
            throw new \system\error\Http404Error('Missing who parametter');
        }
        
        $who = json_decode($this->input->post->who, true);
        $response = array();
        
        if(!is_array($who))
            return '{}';
        
        foreach($who as $user_id){
            $response[$user_id] = $this->session->isOnline($user_id) ? 'ONLINE' : 'OFFLINE';
        }
        
        return json_encode($response);
    }
    
    public function notifAction(){
        return json_encode(array(
            'friend' => $this->friend->getFriendRequestCount($this->session->id),
            'message' => $this->message->getUnreadMessagesCount($this->session->id)
        ));
    }
}
