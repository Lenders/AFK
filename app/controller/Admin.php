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
 * Description of Admin
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Admin extends \system\mvc\Controller {
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
        
        if($this->session->isAdmin !== 'YES')
            throw new \system\error\Http403Forbidden();
    }
    
    public function indexAction(){
        $this->output->setTitle('Statistiques');
        return $this->output->render('admin/stats.php');
    }
    
    public function usersAction(){
        $this->output->setTitle('Gestion des utilisateurs');
        
        return $this->output->render('admin/users.php', array(
            'users' => $this->account->searchAccount($this->input->get->search),
            'query' => $this->input->get->search
        ));
    }
    
    public function eventsAction(){
        $this->output->setTitle('Gestion des évènements');
        
        return $this->output->render('admin/events.php', array(
            'events' => $this->event->searchEvents($this->input->get->search),
            'query' => $this->input->get->search
        ));
    }
    
    public function setadmAction($user_id = 0){
        $user_id = (int)$user_id;
        
        if($this->session->id == $user_id)
            throw new \system\error\Http403Forbidden();
        
        $this->account->setAdmin($user_id, true);
        $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function deladmAction($user_id = 0){
        $user_id = (int)$user_id;
        
        if($this->session->id == $user_id)
            throw new \system\error\Http403Forbidden();
        
        $this->account->setAdmin($user_id, false);
        $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function deluserAction($user_id = 0){
        $user_id = (int)$user_id;
        
        $user = $this->account->getAccountById($user_id);
        
        if(!$user)
            throw new \system\error\Http404Error('Utilisateur introuvable');


        if($this->session->id == $user_id || $user['IS_ADMIN'] == 'YES')
            throw new \system\error\Http403Forbidden();
        
        $this->event->deleteEventsByOrganizer($user_id);
        $this->account->delete($user_id);
        $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function deleventAction($event_id = 0){
        $this->event->deleteEvent((int)$event_id);
        $this->output->getHeader()->setLocation($this->input->getReferer());
    }
}
