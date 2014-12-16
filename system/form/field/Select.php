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
 * Description of Select
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Select extends Field {
    private $options;
    
    public function __construct(\system\form\Form $form, $name, array $options) {
        parent::__construct($form, $name);
        $this->options = $options;
    }
    
    public function getHTML() {
        $html = '<select ' . $this->getHTMLAttributes() . '>';
        
        foreach($this->options as $key => $value){
            if(is_numeric($key))
                $key = $value;
            
            $html .= '<option value="' . addslashes($value) . '">' . $key . '</option>';
        }
        
        return $html . '</select>';
    }

    public function isValid() {
        if(!parent::isValid())
            return false;
        
        if(!in_array($this->getValue(), $this->options)){
            $this->setError('Valeur invalide');
            return false;
        }
        
        return true;
    }
}
