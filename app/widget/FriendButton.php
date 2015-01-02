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

namespace app\widget;

/**
 * Description of FriendButton
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class FriendButton extends \system\mvc\Widget {
    /**
     *
     * @var \app\model\Friend
     */
    private $model;
    
    private static $jsLoaded = false;
    
    public function __construct(\system\Base $base, \app\model\Friend $model) {
        parent::__construct($base);
        $this->model = $model;
    }
    
    public function __invoke($user_id) {
        if(!$this->session->isLogged() || $this->session->id == $user_id)
            return;
        
        if(!self::$jsLoaded)
            echo $this->helpers->js('friend_button');
        
        if($this->model->isFriends($this->session->id, $user_id))
            $tpl = 'remove';
        elseif($this->model->friendRequestExists($this->session->id, $user_id)) //I'm the requester
            $tpl = 'cancel';
        elseif($this->model->friendRequestExists($user_id, $this->session->id)) //I'm the target
            $tpl = 'accept';
        else
            $tpl = 'add';
        
        return $this->output->render('widget/friend_button_' . $tpl . '.php', array('user_id' => $user_id));
    }
}
