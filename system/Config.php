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
 * OOP Config system
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 * @warning This class is a critical class because this class is loaded before having an error handler.
 */
class Config implements \Iterator {
    private $name;
    private $children = array();
    private $parent;
    private $array;

    private function __construct($name, array $children, Config $parent = null) {
        $this->name = $name;
        $this->parent = $parent;
        $this->array = $children;
        
        foreach ($children as $key => $value){
            if(is_array($value)){
                $value = new self($key, $value, $this);
            }
            
            $this->children[$key] = $value;
        }
    }

    public function __get($name) {
        return $this->get($name);
    }
    
    public function getArray($name){
        return isset($this->array[$name]) ? $this->array[$name] : array();
    }
    
    public function __isset($name) {
        return isset($this->children[$name]);
    }
    
    public function get($name){
        if(!isset($this->children[$name]))
            return new self($name, array(), $this);
        
        return $this->children[$name];
    }
    
    public function getName() {
        return $this->name;
    }

    public function getParent() {
        return $this->parent;
    }
    
    public function current() {
        return current($this->children);
    }

    public function key() {
        return key($this->children);
    }

    public function next() {
        return next($this->children);
    }

    public function rewind() {
        return reset($this->children);
    }

    public function valid() {
        return $this->key() !== null;
    }
    
    public function isEmpty(){
        return empty($this->children);
    }

    /**
     * Build a configuration using a file
     * @param string $file the configuration file. Must return an array !
     * @return \system\Config The builded Config
     */
    static public function build($file){
        if(!file_exists($file))
            throw new error\BadConfigFileException($file);
        
        $array = require $file;
        
        if(!is_array($array))
            throw new error\BadConfigFileException($file);
        
        return new self('ROOT', $array);
    }
}
