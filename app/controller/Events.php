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
        
        return $this->output->render('event/index.php', array(
            'org_events' => $this->model->findEventsByOrganizer($this->session->id),
            'par_events' => $this->model->findParticipatedEvents($this->session->id)
        ));
    }
    
    public function showAction($event_id){
        $event_id = (int)$event_id;
        
        $event = $this->model->getEventById($event_id);
        
        if(!$event)
            throw new \system\error\Http404Error('Évènement introuvable');
        
        $flux = $this->loader->load('\app\flux\event\EventFlux');
        $flux->setEventId($event_id);

        $isCompetitor = $this->model->isCompetitor($event_id, $this->session->id);
        
        return $this->output->render('event/show.php', array(
            'properties' => $this->model->getPropertiesByEvent($event_id, !$isCompetitor),
            'event' => $event,
            'isCompetitor' => $isCompetitor,
            'competitors' => $this->model->getCompetitors($event_id),
            'flux' => $flux
        ));
    }
}
