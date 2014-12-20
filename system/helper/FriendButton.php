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

namespace system\helper;

/**
 * Description of FriendButton
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class FriendButton implements Helper {
    /**
     *
     * @var \system\Session
     */
    private $session;
    
    /**
     *
     * @var \app\model\Friend
     */
    private $model;
    
    private $url;
    
    function __construct(\system\Session $session, \app\model\Friend $model, \system\helper\Url $url) {
        $this->session = $session;
        $this->model = $model;
        $this->url = $url;
    }
    
    public function export() {
        return array('friendButton');
    }

    public function friendButton($user_id){
        if(!$this->session->isLogged() || $this->session->id == $user_id)
            return;
        
        if($this->model->isFriends($this->session->id, $user_id)){
            return <<<EOD
            <a href="{$this->url->secureUrl('friends', 'remove', $user_id)}" class="button red" data-action="remove" data-friend-button data-user-id="{$user_id}">Retirer des amis</a>
EOD;
        }
        
        if($this->model->friendRequestExists($this->session->id, $user_id)){ //I'm the requester
            return <<<EOD
            <a href="{$this->url->secureUrl('friends', 'cancel', $user_id)}" class="button red" data-action="cancel" data-friend-button data-user-id="{$user_id}">Annuler</a>
EOD;
        }
        
        if($this->model->friendRequestExists($user_id, $this->session->id)){ //I'm the target
            return <<<EOD
            <a href="{$this->url->secureUrl('friends', 'accept', $user_id)}" class="button" data-action="accept" data-friend-button data-user-id="{$user_id}">Accepter</a>
            <a href="{$this->url->secureUrl('friends', 'reject', $user_id)}" class="button red" data-action="reject" data-friend-button data-user-id="{$user_id}">Refuser</a>
EOD;
        }
        
        return <<<EOD
        <a href="{$this->url->secureUrl('friends', 'add', $user_id)}" class="button" data-action="add" data-friend-button data-user-id="{$user_id}">Ajouter</a>
EOD;
    }
}
