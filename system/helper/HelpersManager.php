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
 * Description of HelpersManager
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class HelpersManager{
    private $helpers = array();
    
    /**
     *
     * @var \system\Loader
     */
    private $loader;
    
    public function __construct(\system\Loader $loader) {
        $this->loader = $loader;
    }
    
    public function loadHelper($name){
        $class_name = '\system\helper\\' . ucfirst($name);
        $helper = $this->loader->load($class_name);
        
        if(!$helper instanceof Helper)
            throw new \system\error\InvalidHelperClassException('For helper "' . $name . '" : ' . $class_name . ' do not implements interface \system\helper\Helper');
        
        foreach($helper->export() as $function){
            $this->helpers[$function] = $helper;
        }
    }

    public function __call($name, $arguments) {
        if(!isset($this->helpers[$name]))
            throw new \system\error\HelperFunctionNotFoundException($name);
        
        $callback = array($this->helpers[$name], $name);
        return call_user_func_array($callback, $arguments);
    }
}
