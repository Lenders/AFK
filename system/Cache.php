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
 * Description of Cache
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Cache {
    /**
     *
     * @var \system\Storage
     */
    private $storage;
    
    /**
     *
     * @var \system\Config
     */
    private $config;
    
    /**
     *
     * @var \system\helper\Bench
     */
    private $bench;
    
    function __construct(\system\Storage $storage, \system\Config $config, \system\helper\Bench $bench) {
        $this->storage = $storage;
        $this->config = $config;
        $this->bench = $bench;
    }
    
    public function get($key){
        $this->bench->start('Cache->get(' . $key . ')');
        $data = $this->storage->get($this->_getKey($key));
        
        if($data && $this->config->enable){
            $data = unserialize($data);
        }
        
        $this->bench->end();
        return $data;
    }
    
    public function exists($key){
        $this->bench->start('Cache->exists(' . $key . ')');
        $b = $this->storage->exists($this->_getKey($key)) && $this->config->enable;
        $this->bench->end();
        return $b;
    }
    
    public function store($key, $value, $time = null){
        $this->bench->start('Cache->store(' . $key . ')');
        if($time === null || $time <= 0)
            $time = $this->config->default_time;
        
        $this->storage->set($this->_getKey($key), serialize($value), $time);
        $this->bench->end();
    }
    
    private function _getKey($key){
        return $this->config->prefix . $key;
    }
    
    public function storeCallback($key, callable $callback, $time = null){
        if($this->exists($key))
            return $this->get ($key);
        
        $ret = $callback();
        $this->store($key, $ret, $time);
        return $ret;
    }
}
