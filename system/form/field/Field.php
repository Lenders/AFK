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

namespace system\form\field;

/**
 * Description of Field
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
abstract class Field {
    private $error;
    private $name;
    private $rules = array();
    private $attributes = array();
    /**
     *
     * @var \system\form\Form
     */
    private $form;
    
    public function __construct(\system\form\Form $form, $name) {
        $this->name = $name;
        $this->form = $form;
        $this->attributes['name'] = $name;
        $this->attributes['id'] = $name;
    }
    
    public function setAttribute($name, $value){
        $this->attributes[$name] = $value;
    }
    
    public function isValid(){
        foreach($this->rules as $rule){
            if(!$rule->validate($this->getValue())){
                $this->error = $rule->getError();
                return false;
            }
        }
        
        return true;
    }
    
    public function getError() {
        return $this->error;
    }
    
    protected function setError($error){
        $this->error = $error;
    }

        public function getName() {
        return $this->name;
    }

    public function getValue(){
        return $this->form->getInput()->{$this->name};
    }

    abstract public function getHTML();
    
    public function getJS($varName){
        $js = '';
        
        foreach($this->rules as $rule){
            $js .= sprintf('%s.addRule($("#%s"), %s);', $varName, $this->name, $rule->getJS()) . PHP_EOL;
        }
        
        return $js;
    }
    
    protected function getHTMLAttributes(){ 
        $attrs = '';
        
        foreach($this->attributes as $attr => $value){
            $attrs .= ' ' . $attr . '=' . '"' . addslashes($value) . '"';
        }
        
        foreach($this->rules as $rule){
            $attrs .= ' ' . $rule->getHTMLAttribute();
        }
        
        return $attrs;
    }
    
    public function addRule(\system\form\rule\Rule $rule){
        $this->rules[] = $rule;
    }
}
