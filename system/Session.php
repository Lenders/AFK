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

namespace system;

/**
 * Session handler
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 * @property string $pseudo
 * @property int $id
 * @property string $mail
 * @property string $firstName
 * @property string $lastName
 * @property string $avatar
 * @property string $gender
 */
class Session {
    /**
     *
     * @var \system\Storage
     */
    private $storage;
    
    /**
     *
     * @var array
     */
    private $data;
    
    private $config;
    
    /**
     *
     * @var \system\input\Input
     */
    private $input;
    
    private $SESSID = null;
    
    public function __construct(\system\Storage $storage, \system\Config $config, \system\input\Input $input) {
        $this->storage = $storage;
        $this->config = $config;
        $this->input = $input;
        $this->_loadData();
    }
    
    private function _loadData(){
        $data = $this->storage->hGetAll($this->_getSESSID());
        
        if($data === false)
            $data = array();
        else{
            $this->storage->setTimeout ($this->_getSESSID (), $this->config->expire);
        }
        
        if(!empty($data['id']))
            $this->storage->setex($this->config->session_prefix . 'online_' . $data['id'], $this->config->online_time, 1);
            
        $this->data = $data;
    }
    
    private function _getSESSID(){
        if($this->SESSID === null){
            if($this->input->cookie->get($this->config->cookie_name) === null){
                $this->SESSID = $this->_generateSESSID();
                $this->input->cookie->setCookie($this->config->cookie_name, $this->SESSID, time() + 30 * 3600 * 24);
            }else
                $this->SESSID = $this->input->cookie->get($this->config->cookie_name);
        }
        
        return $this->SESSID;
    }
    
    private function _generateSESSID(){
        return $this->config->session_prefix . md5(uniqid());
    }

    public function __get($name) {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
    
    public function __isset($name) {
        return isset($this->data[$name]);
    }
    
    public function __set($name, $value) {
        $this->data[$name] = $value;
        $this->storage->hSet($this->_getSESSID(), $name, $value);
    }
    
    public function clear(){
        $this->storage->del($this->_getSESSID(), $this->config->session_prefix . 'online_' . $this->id);
        $this->data = array();
    }
    
    public function isLogged(){
        return !empty($this->data);
    }
    
    public function isOnline($user){
        return $this->storage->exists($this->config->session_prefix . 'online_' . $user);
    }
}
