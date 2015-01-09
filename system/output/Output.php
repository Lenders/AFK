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

namespace system\output;

/**
 * The Output manager
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 * @warning This class is a critical class because this class is loaded before having an error handler.
 */
class Output extends \system\Registry {
    private $layoutTemplate = null;
    private $title;
    private $contents;
    
    private $keywords = array('AFK', 'geek', 'tournois', 'iut');
    
    /**
     *
     * @var \system\output\Header
     */
    private $header;
    
    /**
     * The Helpers manager
     * @var \system\helper\HelpersManager
     */
    private $helpers;
    
    /**
     *
     * @var \system\Config
     */
    private $config;

    public function __construct(\system\Base $base, \system\helper\HelpersManager $helpers, \system\Config $config) {
        parent::__construct($base);
        $this->header = new Header();
        $this->helpers = $helpers;
        $this->config = $config;
        $this->layoutTemplate = $config->default_layout;
    }
    
    public function start(){
        ob_start();
    }
    
    public function clear(){
        $this->header->clear();
        ob_end_clean();
        $this->contents = '';
        $this->layoutTemplate = null;
        $this->title = '';
        ob_start();
    }

    public function setLayoutTemplate($layoutTemplate) {
        $this->layoutTemplate = $layoutTemplate;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function flush() {
        $this->header->send();
        $this->contents = ob_get_clean();
        
        if(empty($this->contents))
            return;
        
        $indent = $this->config->indent && in_array($this->header->getMime(), $this->config->getArray('indent_mimes'));
        
        if($indent){
            ob_start();
        }
        
        if($this->layoutTemplate != null)
            require ROOT . 'app' . DS . 'views' . DS . $this->layoutTemplate;
        else
            echo $this->contents;
        
        if($indent){
            $html = ob_get_clean();
            require __DIR__ . DS . 'dindent' . DS . 'src' . DS . 'Indenter' . EXT;
            $indenter = new \Gajus\Dindent\Indenter();
            echo $indenter->indent($html);
        }
    }
    
    /**
     * get the headers handler
     * @return \system\output\Header
     */
    public function getHeader() {
        return $this->header;
    }
    
    public function render($template, array $vars = array()){
        $view = new \system\mvc\View($template, $this->helpers);
        return $view->render($vars);
    }
    
    /**
     * Load a view object
     * @param string $template the view file
     * @return \system\mvc\View
     */
    public function loadView($template){
        return new \system\mvc\View($template, $this->helpers);
    }
    
    public function getKeywords() {
        return $this->keywords;
    }

    public function addKeyword($kw){
        foreach(func_get_args() as $a)
            $this->keywords[] = htmlentities($a);
    }

}
