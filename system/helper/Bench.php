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

namespace system\helper;

/**
 * Description of Bench
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Bench implements Helper {
    private $entries = array();
    
    /**
     *
     * @var \system\Config
     */
    private $config;
    
    public function __construct(\system\Config $config) {
        $this->config = $config;
    }

    public function export() {
        return array('bench', 'benchEntries');
    }

    public function bench(){
        return (microtime(true) - START_TIME) * 1000;
    }
    
    public function start($label){
        if($this->config->enable){
            $id = count($this->entries);
            $this->entries[] = array($label, microtime(true));
            return $id;
        }
    }
    
    public function end($id = null){
        if(!$this->config->enable)
            return;
        
        if($id === null)
            $id = count($this->entries) - 1;
        
        $time = microtime(true);
        $this->entries[$id][] = $time;
        $this->entries[$id][] = ($time - $this->entries[$id][1]) * 1000;
    }
    
    public function benchEntries(){
        return $this->entries;
    }
}
