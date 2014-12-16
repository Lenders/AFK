<?php

/*
 * The MIT License
 *
 * Copyright 2014 loick.
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
 * Description of Router
 *
 * @author loick
 */
class Router{
    /**
     *
     * @var \system\output\Output
     */
    private $output;
    
    /**
     *
     * @var \system\input\Input
     */
    private $input;
    
    /**
     *
     * @var \system\Loader
     */
    private $loader;


    public function __construct(\system\output\Output $output, \system\input\Input $input, \system\Loader $loader) {
        $this->output = $output;
        $this->input = $input;
        $this->loader = $loader;
    }
    
    public function loadPage(Config $config){
        $path = $this->input->getPath();
        
        $page = array_shift($path);
        
        if(empty($page))
            $page = $config->default_page;
        
        $action = array_shift($path);
        
        if(empty($action))
            $action = $config->default_action;
        
        $controller = '\app\controller\\' . ucfirst(strtolower($page));
        $method = strtolower($action) . 'Action';
        
        try{
            $instance = $this->loader->load($controller);
        }catch(\system\error\ClassNotFoundException $e){
            throw new error\Http404Error('Page not found !');
        }
        
        if(!method_exists($instance, $method))
            throw new error\Http404Error('Action not found !');

        echo call_user_func_array(array($instance, $method), $path);
    }
}
