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

class Config implements Helper{
    /**
     *
     * @var \system\Base
     */
    private $base;
    
    public function __construct(\system\Base $base) {
        $this->base = $base;
    }
    
    public function export() {
        return array(
            'conf',
        );
    }
    
    public function conf($key){
        $array = explode('.', $key);
        $conf = $this->base->config;
        
        foreach($array as $item){
            if(empty($item))
                continue;
            
            if(!($conf instanceof \system\Config))
                throw new \system\error\BadConfigurationException('Try to access on invalid config item : ' . $key);

                $conf = $conf->$item;
            
            if($conf instanceof \system\Config && $conf->isEmpty())
                throw new \system\error\InexistantConfigItemException($conf);
        }
        
        return $conf;
    }
}