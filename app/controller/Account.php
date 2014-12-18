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
 * Description of Account
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Account extends \system\mvc\Controller {
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
    
    public function __construct(\system\Base $base, \app\model\Account $model, \system\helper\Crypt $crypt) {
        parent::__construct($base);
        $this->model = $model;
        $this->crypt = $crypt;
    }
    
    public function loginAction($error = ''){
        return $this->output->render('account/login.php', array('error' => $error === 'error'));
    }
    
    public function performloginAction($method = 'html'){
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
            //TODO
        }
        
        if($method === 'ajax'){
            $this->output->setLayoutTemplate(null);
            $this->output->getHeader()->setMimeType('text/json');
            
            if(!$valid)
                return $this->output->render('account/login_error.json');
        }else{
            if(!$valid)
                $this->output->getHeader()->setLocation($this->helpers->url('account/login/error'));
        }
    }
}
