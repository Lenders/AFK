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
 * Description of Register
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Register extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Account
     */
    private $model;
    
    /**
     *
     * @var \app\form\Register
     */
    private $form;
    
    /**
     *
     * @var \system\helper\Crypt
     */
    private $crypt;
    
    public function __construct(\system\Base $base, \app\model\Account $model, \app\form\Register $form, \system\helper\Crypt $crypt) {
        parent::__construct($base);
        $this->model = $model;
        $this->form = $form;
        $this->crypt = $crypt;
    }
    
    public function indexAction(){
        return $this->output->render('register/register.php', array('form' => $this->form));
    }
    
    public function scriptAction(){
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/javascript');
        return $this->form->getJS();
    }
    
    public function validateAction($input = ''){
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/json');
        $errors = array();
        $this->form->validate($input, $errors);
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
                return $this->output->render('register/register.php', array('form' => $this->form));
            }
        }
        
        $salt = $this->crypt->generateSalt();
        $pass = $this->crypt->hashPass($this->form->getPass()->getValue(), $salt);
        
        $this->model->create(
            $this->form->getPseudo()->getValue(), 
            $pass, 
            $salt, 
            $this->form->getFirstName()->getValue(), 
            $this->form->getLastName()->getValue(), 
            $this->form->getGender()->getValue(), 
            $this->form->getEmail()->getValue()
        );
        
        mail(
            $this->form->getEmail()->getValue(), 
            'AFK : Inscription', 
            'Vous êtes maintenant inscrits au site AFK sous le pseudo ' . $this->form->getPseudo()->getValue() . ' !'
        );
        
        if($method === 'ajax'){
            return $this->output->render('register/register_success.json.php');
        }else{
            $this->output->getHeader()->setLocation($this->helpers->url('page/registersuccess.html'));
        }
    }
}
