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

namespace system\form;

/**
 * Description of Form
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
abstract class Form {
    /**
     *
     * @var \system\input\Input
     */
    private $input;
    
    private $fields = array();
    
    public function __construct(\system\input\Input $input) {
        $this->input = $input;
    }
    
    abstract public function getID();
    abstract public function getSubmitURL();
    abstract public function getValidationURL();
    
    public function validate($field = null){
        $errors = array();
        $error = false;
        
        if($field === null){
            foreach($this->fields as $field){
                if(!$field->isValid()){
                    $errors[$field->getName()] = $field->getError();
                    $error = true;
                }
            }
        }else{
            if(!isset($this->fields[$field]))
                return array($field => 'Champ inexistant');
            
            $field = $this->fields[$field];
            $error = !$field->isValid();
            $errors[$field->getName()] = $field->getError();
        }
        
        return $error ? $errors : true;
    }
    
    protected function addField(field\Field $field){
        $this->fields[$field->getName()] = $field;
    }

    /**
     * 
     * @return \system\input\HTTPInput
     */
    public function getInput(){
        return $this->input->post;
    }
    
    public function getJS(){
        $rules = '';
        
        foreach($this->fields as $field){
            $rules .= $field->getJS('validator');
        }
        
        return <<<JS
(function(){
    var validator = new FormValidator('#{$this->getID()}', '{$this->getValidationURL()}', '{$this->getSubmitURL()}');
    
    $rules
})();
JS;
    }
}
