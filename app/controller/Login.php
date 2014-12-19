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
 * Login controller
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Login extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Account
     */
    private $model;
    
    /**
     *
     * @var \system\helper\Crypt
     */
    private $crypt;
    
    /**
     *
     * @var \app\form\Login
     */
    private $form;
    
    public function __construct(\system\Base $base, \app\model\Account $model, \system\helper\Crypt $crypt, \app\form\Login $form) {
        parent::__construct($base);
        $this->model = $model;
        $this->crypt = $crypt;
        $this->form = $form;
        
        if($this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        var_dump($this->session);
    }
    
    public function indexAction(){
        return $this->output->render('login/index.php', array(
            'error' => false,
            'form' => $this->form
        ));
    }
    
    public function validateAction($input = ''){
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/json');
        $error = array();
        $this->form->validate($input, $error);
        return json_encode($error);
    }
    
    public function scriptAction(){
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/javascript');
        return $this->form->getJS();
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
                return $this->output->render('login/index.php', array('error' => false, 'form' => $this->form));
            }
        }
        
        $account = $this->model->getAccountByPseudo($this->input->post->pseudo);
        
        $valid = $account ? true : false;
        
        if($valid){
            $pass = $account['PASS'];
            $pass2 = $this->crypt->hashPass(
                $this->input->post->pass, 
                $account['SALT']
            );
            
            $valid = $pass === $pass2;
        }
        
        if($valid){
            $this->session->id = $account['USER_ID'];
            $this->session->pseudo = $account['PSEUDO'];
            $this->session->mail = $account['MAIL'];
            $this->session->firstName = $account['FIRST_NAME'];
            $this->session->lastName = $account['LAST_NAME'];
            $this->session->gender = $account['GENDER'];
            $this->session->avatar = $account['AVATAR'];
        }
        
        if($method === 'ajax'){
            if(!$valid)
                return $this->output->render('login/login_error.json');
            return $this->output->render('login/login_success.json.php');
        }else{
            if(!$valid)
                return $this->output->render('login/index.php', array('error' => false, 'form' => $this->form));
            $this->output->getHeader()->setLocation($this->helpers->baseUrl());
        }
    }
}
