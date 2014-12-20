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
        var_dump($this->session);
        return 'Hello World !';
    }
}
