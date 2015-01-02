<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controller;

/**
 * Description of Home
 *
 * @author q13000412
 */
class Home extends \system\mvc\Controller {
    public function indexAction(){
        $this->output->setTitle('Accueil');
        
        if(!$this->session->isLogged()){
            return $this->cache->storeCallback('home', function(){
                return $this->output->render('home/public_flux.php', array('flux' => $this->loader->load('\app\flux\PublicFlux')));
            }, 600);
        }else{
            $flux = $this->loader->load('\app\flux\MyFlux');
            $flux->setUserId($this->session->id);
            return $this->output->render('home/my_flux.php', array(
                'flux'=> $flux
            ));
        }
    }
}
