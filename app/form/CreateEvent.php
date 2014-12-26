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

namespace app\form;

/**
 * Description of CreateEvent
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class CreateEvent extends \system\form\Form {
    /**
     *
     * @var \system\form\field\Field
     */
    private $name;
    
    /**
     *
     * @var \system\form\field\Field
     */
    private $privacy;
    
    /**
     *
     * @var \system\form\field\Field
     */
    private $start;
    
    /**
     *
     * @var \system\form\field\Field
     */
    private $end;
    
    /**
     *
     * @var array
     */
    private $properties = array();
    
    /**
     *
     * @var \app\model\Event
     */
    private $model;
    
    public function __construct(\system\input\Input $input, \system\helper\Url $url, \app\model\Event $model) {
        parent::__construct($input, $url);
        $this->model = $model;
        $this->_makeBaseFields();
        $this->_makePropertyFields();
    }
    
    private function _makeBaseFields(){
        $required = new \system\form\rule\Required();
        
        $this->name = new \system\form\field\Input($this, 'name');
        $this->name->addRule(new \system\form\rule\Length(4, 32));
        $this->name->addRule($required);
        $this->addField($this->name);
        
        $this->privacy = new \system\form\field\Select($this, 'privacy', array(
            'Publique' => 'PUBLIC',
            'Privé' => 'PRIVATE',
            'Uniquement les amis' => 'FRIEND'
        ));
        $this->privacy->addRule($required);
        $this->addField($this->privacy);
        
        $date_regex = new \system\form\rule\Regex('/([0-9]{2}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2})/i', 'Date invalide');
        $date_format = 'jj-mm-aa hh:mm';
        $check_time = new \system\form\rule\Callback(function($value, &$error){
            $r = $this->strToTime($value);
            
            if(!$r){
                $error = 'Date invalide : ' . array_values(\DateTime::getLastErrors()['errors'])[0];
                return false;
            }
            
            return true;
        });
        
        $this->start = new \system\form\field\Input($this, 'start');
        $this->start->setAttribute('placeholder', $date_format);
        $this->start->addRule($date_regex);
        $this->start->addRule($required);
        $this->start->addRule($check_time);
        $this->addField($this->start);
        
        $this->end = new \system\form\field\Input($this, 'end');
        $this->end->setAttribute('placeholder', $date_format);
        $this->end->addRule($date_regex);
        $this->end->addRule($required);
        $this->end->addRule($check_time);
        $this->end->addRule(new \system\form\rule\Callback(function($value){
            $start = $this->strToTime($this->start->getValue());
            $end = $this->strToTime($value);
            
            if(!$start || !$end)
                return false;
            
            return $end->getTimestamp() > $start->getTimestamp();
        }, 'La date de fin doit être supérieur à celle de début'));
        $this->addField($this->end);
    }
    
    /**
     * Convert a date string to DateTime
     * @param string $str_time The string representation of the date
     * @return \DateTime
     */
    public function strToTime($str_time){
        return \DateTime::createFromFormat('d-m-y G:i', $str_time);
    }
    
    private function _makePropertyFields(){
        foreach($this->model->getPropertyChecks() as $check){
            $field = new \system\form\field\Input($this, $check['PROPERTY_NAME']);
            $field->addRule(new \system\form\rule\Regex($check['PROPERTY_REGEXP']));
            $field->addRule(new \system\form\rule\Length(0, 128));
            
            if($check['REQUIRED'] == 'YES')
                $field->addRule (new \system\form\rule\Required());
            
            $this->properties[] = $field;
            $this->addField($field);
        }
    }
    
    public function getName() {
        return $this->name;
    }

    public function getPrivacy() {
        return $this->privacy;
    }

    public function getStart() {
        return $this->start;
    }

    public function getEnd() {
        return $this->end;
    }

    public function getProperties() {
        return $this->properties;
    }
        
    public function getID() {
        return 'create_event_form';
    }

    public function getSubmitURL() {
        return 'createevent/submit/ajax.json';
    }

    public function getValidationURL() {
        return 'createevent/validate';
    }

}
