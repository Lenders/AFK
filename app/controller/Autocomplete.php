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

namespace app\controller;

/**
 * Handle autocomplete queries
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Autocomplete extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Autocomplete
     */
    private $model;
    
    public function __construct(\system\Base $base, \app\model\Autocomplete $model) {
        parent::__construct($base);
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/json');
        $this->model = $model;
    }
    
    public function userAction($term = ''){
        return json_encode($this->model->queryUsers($term));
    }
    
    public function eventAction($term = ''){
        return json_encode($this->model->queryEvents($term));
    }
    
    public function pseudolistAction($list = ''){
        $clear_list = array();
        
        foreach(explode(',', $list) as $item){
            $item = trim($item);
            
            if(empty($item) || !preg_match('/^[a-z0-9-]{0,32}$/i', $item))
                continue;
            
            $clear_list[] = $item;
        }
        
        $pseudo = array_pop($clear_list);
        $list = implode(', ', $clear_list);
        
        $ret = $this->model->queryPseudo($pseudo);
        
        if(!empty($list)){
            foreach ($ret as &$r){
                $r = $list . ', ' . $r;
            }
        }
        
        return json_encode($ret);
    }
    
    public function eventpropertyAction($prop = '', $term = ''){
        if(empty($prop))
            return '[]';
        
        return json_encode($this->model->queryEventProperty($prop, $term));
    }
}
