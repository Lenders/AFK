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
 * Description of Notification
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Notification implements Helper {
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
    
    /**
     *
     * @var \system\Session
     */
    private $session;

    function __construct(\app\model\Friend $friend, \app\model\Message $message, \system\Session $session) {
        $this->friend = $friend;
        $this->message = $message;
        $this->session = $session;
    }

    public function export() {
        return array('getFriendNotif', 'getMessageNotif');
    }

    public function getFriendNotif(){
        return $this->_notifHTML($this->friend->getFriendRequestCount($this->session->id), 'friend');
    }
    
    public function getMessageNotif(){
        return $this->_notifHTML($this->message->getUnreadMessagesCount($this->session->id), 'message');
    }
    
    private function _notifHTML($count, $id){
        return '<span class="notif" data-notif="' . $id .'">' . ($count > 0 ? $count : '') . '</span>';
    }
}
