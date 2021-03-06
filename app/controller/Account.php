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
    
    public function __construct(\system\Base $base, \app\model\Account $model) {
        parent::__construct($base);
        $this->model = $model;
    }
    
    public function indexAction(){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $this->output->setTitle('Mon compte');
        
        return $this->profileAction($this->session->id);
    }
    
    public function profileAction($id){
        $id = (int)$id;
        $account = $this->model->getAccountById($id);
        
        if(!$account)
            throw new \system\error\Http404Error('L\'utilisateur n\'existe pas.');
        
        $this->output->setTitle('Profil ' . $account['PSEUDO']);
        $this->output->addKeyword('profil', $account['PSEUDO'], $account['FIRST_NAME'], $account['LAST_NAME']);
        
        return $this->cache->storeCallback('account_profile_' . $id, function() use($id){
            $flux = $this->loader->load('\app\flux\AccountFlux');
            $flux->setUserId($id);
            
            return $this->output->render('account/profile.php', array(
                'user' => $this->model->getAccountById($id),
                'flux' => $flux
            ));
        }, 600);
    }
    
    public function logoutAction(){
        $this->session->clear();
        $this->output->getHeader()->setLocation($this->helpers->baseUrl());
    }
}
