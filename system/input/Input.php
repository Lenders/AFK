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

namespace system\input;

/**
 * Input handler
 *
 * @author loick
 * @property-read \system\input\Get $get GET
 * @property-read \system\input\Post $post POST
 * @property-read \system\input\Cookie $cookie COOKIE
 */
class Input {
    /**
     * The parsed path info
     * @var array
     */
    private $path = null;
    
    private $vars;
    
    public function __construct() {
        $this->vars = array(
            'get' => new Get(),
            'post' => new Post(),
            'cookie' => new Cookie()
        );
    }
    
    public function __get($name) {
        return $this->vars[$name];
    }
    
    public function getPath(){
        if($this->path === null){
            $pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
            
            if(strpos($pathInfo, '.') !== false)
                $pathInfo = strstr($pathInfo, '.', true);
            
            $pathInfo = substr($pathInfo, 1); //remove the first /
            $array = explode('/', $pathInfo);
            
            $this->path = array();
            
            foreach($array as $param){
                $param = trim(filter_var(urldecode($param), FILTER_SANITIZE_STRING)); //clean the param
                
                if(strlen($param) > 0)
                    array_push ($this->path, $param);
            }
        }
        
        return $this->path;
    }

    public function getBaseUrl(){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }else{
            $protocol = 'http';
        }
        
        $dir = dirname($_SERVER['SCRIPT_NAME']);
        
        if(strlen($dir) > 0 && substr($dir, -1) != '/')
            $dir .= '/';
        
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . $dir;
    }
    
    public function getUserIP(){
        return $_SERVER['REMOTE_ADDR'];
    }
}
