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
 * Description of Event
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Events extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Event
     */
    private $model;
    
    public function __construct(\system\Base $base, \app\model\Event $model) {
        parent::__construct($base);
        $this->model = $model;
    }
    
    public function indexAction(){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        return $this->listAction($this->session->id);
    }
    
    public function listAction($user_id = 0){
        $user_id = (int)$user_id;
        
        if($user_id == $this->session->id){
            $user_pseudo = $this->session->pseudo;
            $this->output->setTitle('Mes évènements');
        }else{
            $user_pseudo = $this->loader->load('\app\model\Account')->getPseudoByUserId($user_id);
            
            if(!$user_pseudo)
                throw new \system\error\Http404Error('Utilisateur introuvable');
            
            $this->output->setTitle('Évènements ' . $user_pseudo);
        }
        
        return $this->output->render('event/list.php', array(
            'org_events' => $this->model->findEventsByOrganizer($user_id),
            'par_events' => $this->model->findParticipatedEvents($user_id),
            'user_id' => $user_id,
            'user_pseudo' => $user_pseudo
        ));
    }
    
    public function showAction($event_id = 0){
        $event_id = (int)$event_id;
        
        $event = $this->model->getEventById($event_id);
        
        if(!$event)
            throw new \system\error\Http404Error('Évènement introuvable');
        
        $this->output->setTitle('Évènement ' . $event['EVENT_NAME']);

        $isCompetitor = $this->model->isCompetitor($event_id, $this->session->id);
        
        if($isCompetitor){
            $flux = $this->loader->load('\app\flux\EventFlux');
            $flux->setEventId($event_id);
            
            $vars = array(
                'properties' => $this->model->getPropertiesByEvent($event_id, !$isCompetitor),
                'event' => $event,
                'competitors' => $this->model->getCompetitors($event_id),
                'flux' => $flux
            );
            
            if($this->session->id == $event['ORGANIZER']){ //admin
                $vars['req_count'] = $this->model->requestCountByEvent($event_id);
            }
            
            return $this->output->render('event/show_part.php', $vars);
        }
        
        $canJoin = $this->_canJoinEvent($event);
        $pending = $canJoin ? false : $this->model->requestExists($event_id, $this->session->id);
        
        return $this->output->render('event/show_guest.php', array(
            'properties' => $this->model->getPropertiesByEvent($event_id, !$isCompetitor),
            'event' => $event,
            'competitors' => $this->model->getCompetitors($event_id),
            'canJoin' => $canJoin,
            'pending' => $pending
        ));
    }
    
    public function competitorsAction($event_id = 0){
        $event_id = (int)$event_id;
        
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $event = $this->model->getEventById($event_id);
        
        if($event['ORGANIZER'] != $this->session->id)
            throw new \system\error\Http403Forbidden();
        
        $this->output->setTitle('Liste des participants ' . $event['EVENT_NAME']);
        
        $vars = array(
            'properties' => $this->model->getPropertiesByEvent($event_id),
            'event' => $event,
            'competitors' => $this->model->getCompetitors($event_id),
            'req_count' => $this->model->requestCountByEvent($event_id),
            'requests' => $this->model->getRequestList($event_id)
        );

        return $this->output->render('event/competitors.php', $vars);
    }
    
    public function sendmessageAction($event_id = 0){
        $event_id = (int)$event_id;
        
        if(!$this->session->isLogged() || !$this->model->isCompetitor($event_id, $this->session->id))
            throw new \system\error\Http403Forbidden();
        
        if(!empty($this->input->post->message))
            $this->model->sendMessage ($event_id, $this->session->id, $this->input->post->message);
        
        $this->output->getHeader()->setLocation($this->helpers->secureUrl('events', 'show', $event_id));
    }
    
    public function joinAction($event_id = 0){
        $event_id = (int)$event_id;
        
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $event = $this->model->getEventById($event_id);
        
        if(!$event)
            throw new \system\error\Http404Error('Event introuvable');
        
        if($this->model->isCompetitor($event_id, $this->session->id) || !$this->_canJoinEvent($event))
            throw new \system\error\Http403Forbidden();
        
        $this->model->addCompetitor($event_id, $this->session->id);

        return $this->output->getHeader()->setLocation($this->helpers->secureUrl('events', 'show', $event_id));
    }
    
    public function requestAction($event_id = 0){
        $event_id = (int)$event_id;
        
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $event = $this->model->getEventById($event_id);
        
        if(!$event)
            throw new \system\error\Http404Error('Event introuvable');
        
        if($this->model->isCompetitor($event_id, $this->session->id) 
                || $this->_canJoinEvent($event)
                || $this->model->requestExists($event_id, $this->session->id)
                || time() > $event['EVENT_START'])
            throw new \system\error\Http403Forbidden();
        
        $this->model->addRequest($event_id, $this->session->id);
        return $this->output->getHeader()->setLocation($this->helpers->secureUrl('events', 'show', $event_id));
    }
    
    public function cancelAction($event_id = 0, $user_id = 0){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $event_id = (int)$event_id;
        $user_id = (int)$user_id;
        
        $event = $this->model->getEventById($event_id);
        
        if(!$event)
            throw new \system\error\Http404Error('Event introuvable');
        
        if($user_id != $this->session->id && $this->session->id != $event['ORGANIZER'])
            throw new \system\error\Http403Forbidden();
        
        $this->model->removeRequest($event_id, $user_id);
        return $this->output->getHeader()->setLocation($this->input->getReferer());
    }
    
    public function acceptAction($event_id = 0, $user_id = 0){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $event_id = (int)$event_id;
        $user_id = (int)$user_id;
        
        $event = $this->model->getEventById($event_id);
        
        if(!$event)
            throw new \system\error\Http404Error('Event introuvable');
        
        if($this->session->id != $event['ORGANIZER'] 
                || !$this->model->requestExists($event_id, $user_id)
                || time() > $event['EVENT_START'])
            throw new \system\error\Http403Forbidden();
        
        $this->model->acceptRequest($event_id, $user_id);
        return $this->output->getHeader()->setLocation($this->helpers->secureUrl('events', 'competitors', $event_id));
    }
    
    public function expelAction($event_id = 0, $user_id = 0){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $event_id = (int)$event_id;
        $user_id = (int)$user_id;
        
        $event = $this->model->getEventById($event_id);
        
        if(!$event)
            throw new \system\error\Http404Error('Event introuvable');
        
        if($this->session->id != $event['ORGANIZER'] 
                || $user_id == $event['ORGANIZER']
                || time() > $event['EVENT_START']) //event already started
            throw new \system\error\Http403Forbidden();
        
        $this->model->removeCompetitor($event_id, $user_id);
    }
    
    private function _canJoinEvent(array $event){
        if($event['EVENT_STATE'] === 'CLOSE' || time() > $event['EVENT_START'])
            return false;
        
        if($event['EVENT_PRIVACY'] === 'PUBLIC')
            return true;
        
        if($event['EVENT_PRIVACY'] === 'PRIVATE')
            return false;
        
        if($event['EVENT_PRIVACY'] === 'FRIEND')
            return $this->loader->load('\app\model\Friend')->isFriends($this->session->id, $event['ORGANIZER']);
    }
}
