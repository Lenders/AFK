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
 * Login form
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Login extends \system\form\Form {
    /**
     *
     * @var \system\form\field\Input
     */
    private $pseudo;
    
    /**
     *
     * @var \system\form\field\Input
     */
    private $pass;
    
    public function __construct(\system\input\Input $input, \system\helper\Url $url) {
        parent::__construct($input, $url);
        $this->pseudo = new \system\form\field\Input($this, 'pseudo');
        $this->pass = new \system\form\field\Input($this, 'pass');
        $this->makeFields();
    }
    
    private function makeFields(){
        $this->pass->setAttribute('type', 'password');
        $this->pass->setAttribute('placeholder', 'Mot de passe');
        $required = new \system\form\rule\Required();
        $this->pass->addRule($required);
        $this->pseudo->addRule($required);
        $this->pseudo->setAttribute('placeholder', 'Pseudo');
        
        $this->addField($this->pass);
        $this->addField($this->pseudo);
    }

    public function getID() {
        return 'login_form';
    }

    public function getSubmitURL() {
        return 'login/submit/ajax.json';
    }

    public function getValidationURL() {
        return 'login/validate';
    }
    
    public function getPseudo() {
        return $this->pseudo;
    }

    public function getPass() {
        return $this->pass;
    }

}
