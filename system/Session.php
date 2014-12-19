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
    
    private $SESSID = null;
    
    public function __construct(\system\Storage $storage, \system\Config $config) {
        $this->storage = $storage;
        $this->config = $config;
        $this->_loadData();
    }
    
    private function _loadData(){
        $data = $this->storage->hGetAll($this->_getSESSID());
        
        if($data === false)
            $data = array();
        else
            $this->storage->setTimeout ($this->_getSESSID (), $this->config->expire);
            
        $this->data = $data;
    }
    
    private function _getSESSID(){
        if($this->SESSID === null){
            if(!isset($_COOKIE[$this->config->cookie_name])){
                $this->SESSID = $this->_generateSESSID();
                setcookie($this->config->cookie_name, $this->SESSID);
            }else
                $this->SESSID = $_COOKIE[$this->config->cookie_name];
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
        $this->data = array();
        $this->storage->del($this->_getSESSID());
    }
    
    public function isLogged(){
        return !empty($this->data);
    }
}
