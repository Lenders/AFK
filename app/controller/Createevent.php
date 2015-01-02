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
 * Description of Createevent
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Createevent extends \system\mvc\Controller {
    /**
     *
     * @var \app\form\CreateEvent
     */
    private $form;
    
    /**
     *
     * @var \app\model\Event
     */
    private $model;
    
    public function __construct(\system\Base $base, \app\form\CreateEvent $form, \app\model\Event $model) {
        parent::__construct($base);
        $this->model = $model;
        $this->form = $form;
        
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
    }
    
    public function indexAction(){
        $this->output->setTitle('Création d`un évènement');
        return $this->output->render('event/createevent.php', array('form' => $this->form));
    }
    
    public function scriptAction(){
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/javascript');
        return $this->form->getJS();
    }
    
    public function validateAction($field){
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/json');
        $errors = array();
        $this->form->validate($field, $errors);
        return json_encode($errors);
    }
    
    public function submitAction($method = 'html'){
        if($method === 'ajax'){
            $this->output->setLayoutTemplate(null);
            $this->output->getHeader()->setMimeType('text/json');
        }
        
        $errors = array();
        
        if(!$this->form->validate(null, $errors)){
            if($method === 'ajax'){
                return json_encode($errors);
            }else{
                return $this->output->render('event/createevent.php', array('form' => $this->form));
            }
        }
        
        $properties = array();
        
        foreach($this->form->getProperties() as $property){
            $value = $property->getValue();
            
            if(empty($value))
                continue;
            
            $properties[$property->getName()] = $value;
        }
        
        $mysql_timestamp_format = 'Y-m-d H:i:s';
        
        $event_id = $this->model->createEvent(
            $this->form->getName()->getValue(), 
            $this->session->id, 
            $this->form->getPrivacy()->getValue(), 
            $this->form->strToTime($this->form->getStart()->getValue())->format($mysql_timestamp_format), 
            $this->form->strToTime($this->form->getEnd()->getValue())->format($mysql_timestamp_format),
            $properties
        );
        
        if($method === 'ajax')
            return $this->output->render ('event/createevent_success.json.php', array('event_id' => $event_id));
        
        $this->output->getHeader()->setLocation($this->helpers->secureUrl('events', 'show', $event_id));
    }
}
