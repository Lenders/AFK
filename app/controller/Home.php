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
 * Description of Home
 *
 * @author q13000412
 */
class Home extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Home
     */
    private $model;
    
    public function __construct(\system\Base $base, \app\model\Home $model) {
        parent::__construct($base);
        $this->model = $model;
    }
    
    public function indexAction(){
        $this->output->setTitle('Accueil');
        $this->output->addKeyword('acceuil');
        
        if(!$this->session->isLogged()){
            $this->output->addKeyword('bienvenue');
            return $this->cache->storeCallback('home', function(){
                return $this->output->render('home/public_flux.php', array(
                    'flux' => $this->loader->load('\app\flux\PublicFlux'),
                    'inscriptions' => $this->model->getInscriptionsCount(),
                    'events' => $this->model->getEventsCount(),
                    'online' => $this->session->getOnlineCount()
                ));
            }, 600);
        }else{
            $this->output->addKeyword('actualité');
            $flux = $this->loader->load('\app\flux\MyFlux');
            $flux->setUserId($this->session->id);
            return $this->output->render('home/my_flux.php', array(
                'flux'=> $flux
            ));
        }
    }
    
    public function rssAction($user_id = 0, $key = ''){
        $user_id = (int)$user_id;
        $this->output->setLayoutTemplate('layout/rss.xml.php');
        $this->output->getHeader()->setMimeType('application/rss+xml');
        
        if($user_id !== 0){
            $private_key = $this->model->getRssKey($user_id);
            
            if($private_key === null)
                throw new \system\error\Http404Error('Flux introuvable');
            
            if($private_key !== $key)
                throw new \system\error\Http403Forbidden();
        }
        
        $this->output->setTitle($user_id === 0 ? 'Public' : $this->loader->load('\app\model\Account')->getPseudoByUserId($user_id));
        
        return $this->cache->storeCallback('home-rss-' . $user_id, function() use($user_id){
            if($user_id === 0){
                $flux = $this->loader->load('\app\flux\PublicFlux');
            }else{
                $flux = $this->loader->load('\app\flux\MyFlux');
                $flux->setUserId($user_id);
            }
            
            $contents = '';

            foreach($this->loader->load('\app\flux\PublicFlux') as $article)
                $contents .= $article->getArticle('rss');

            return $contents;
        },900);
    }
}
