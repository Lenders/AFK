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

namespace system\mvc;

/**
 * View class
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class View {
    private $template;
    /**
     *
     * @var \system\helper\HelpersManager
     */
    private $helpers;

    /**
     * Create a new View
     * @param string $template the template file (with extension)
     * @param \system\helper\HelpersManager $helpers The helper manager
     */
    public function __construct($template, \system\helper\HelpersManager $helpers) {
        $this->template = $this->getTemplateFile($template);
        $this->helpers = $helpers;
    }
    
    private function getTemplateFile($template){
        return ROOT . 'app' . DS . 'views' . DS . $template;
    }
    
    public function __call($name, $arguments) {
        return $this->helpers->__call($name, $arguments);
    }
    
    /**
     * load the view and render its content
     * @return string The rendered view
     */
    public function render(array $vars = array()){
        if(!file_exists($this->template))
            throw new \system\error\ViewNotFoundException($this->template);
        
        ob_start();
        extract($vars);
        include $this->template;
        $out = ob_get_clean();
        
        return $out;
    }
    
    private function loadView($template, array $vars = array()){
        $view = new self($template, $this->helpers);
        return $view->render($vars);
    }
    
    private function newView($template){
        return new self($template, $this->helpers);
    }
}
