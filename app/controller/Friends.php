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
 * Description of Friends
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Friends extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Friend
     */
    private $model;
    
    /**
     *
     * @var \app\model\Account
     */
    private $account;
    
    public function __construct(\system\Base $base, \app\model\Friend $model, \app\model\Account $account) {
        parent::__construct($base);
        $this->model = $model;
        $this->account = $account;
    }
    
    public function indexAction(){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        return $this->output->render('friends/index.php', array(
            'friends' => $this->model->getFriendsByUserId($this->session->id),
            'requests' => $this->model->getFriendRequests($this->session->id)
        ));
    }
    
    public function addAction($id = 0, $redirect = true){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $id = (int)$id;
        
        if(!$this->account->accountExists($id)){
            throw new \system\error\Http404Error('Utilisateur invalide');
        }
        
        if($id == $this->session->id
                || $this->model->friendRequestExists($this->session->id, $id) 
                || $this->model->friendRequestExists($id, $this->session->id) 
                || $this->model->isFriends($this->session->id, $id)){
            throw new \system\error\Http403Forbidden();
        }
        
        $this->model->requestFriend($this->session->id, $id);
        
        if($redirect === true)
            $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function cancelAction($id = 0, $redirect = true){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $id = (int)$id;
        
        $this->model->removeRequest($this->session->id, $id);
        
        if($redirect === true)
            $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function rejectAction($id = 0, $redirect = true){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $id = (int)$id;
        
        $this->model->removeRequest($id, $this->session->id);
        
        if($redirect === true)
            $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function removeAction($id = 0, $redirect = true){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $id = (int)$id;
        
        $this->model->removeFriend($id, $this->session->id);
        
        if($redirect === true)
            $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function acceptAction($id = 0, $redirect = true){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $id = (int)$id;
        
        if(!$this->model->friendRequestExists($id, $this->session->id))
            throw new \system\error\Http403Forbidden();

        $this->model->acceptFriendRequest($id, $this->session->id);
        
        if($redirect === true)
            $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function listAction($user_id = 0){
        $user_id = (int)$user_id;
        
        if($this->session->isLogged() && $user_id == $this->session->id)
            return $this->indexAction();
        
        $user = $this->account->getAccountById($user_id);
        
        if(!$user)
            throw new \system\error\Http404Error('Utilisateur introuvable');
        
        return $this->output->render('friends/list.php', array(
            'user' => $user,
            'friends' => $this->model->getFriendsByUserId($user_id)
        ));
    }
}
