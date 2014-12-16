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
 * Assets compressor
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Compressor implements Helper {
    /**
     * The assets helpers
     * @var \system\helper\Assets
     */
    private $assets;
    
    /**
     * The compressor config
     * @var \system\Config
     */
    private $config;
    
    /**
     * List of css to load
     * @var array
     */
    private $css = array();

    public function __construct(Assets $assets, \system\Config $config) {
        $this->assets = $assets;
        $this->config = $config;
    }
    
    public function export() {
        return array(
            'compressCSS', 'loadCSS', 'getCSS'
        );
    }
    
    private function removeComments($code){
        $code = preg_replace('#/\*.*\*/#isU', '', $code);
        $code = preg_replace('#^(.*)//.*$#isU', '$1', $code);
        
        return $code;
    }
    
    private function removeLineFeeds($code){
        return str_replace(array("\n", "\r"), array('', ''), $code);
    }

    private function removeBlanks($code){
        return preg_replace('#([^\'"])\s+([^\'"])#isU', '$1$2', $code);
    }

    public function compressCSS($file){
        $code = file_get_contents($this->getPath('css', $file . '.css'));
        
        $code = $this->removeComments($code);
        $code = $this->removeLineFeeds($code);
        $code = $this->removeBlanks($code);
        
        return $code;
    }
    
    public function loadCSS($file){
        if($this->config->compress){
            $file2 = $this->config->folder . DS . md5_file($this->getPath('css', $file . '.css'));
            $compressed_file = $this->getPath('css', $file2 . '.css');

            if(!file_exists($compressed_file) || !$this->config->cache){
                $css = $this->compressCSS($file);
                $dir = dirname($compressed_file);
                
                if(!is_dir($dir))
                    mkdir($dir, 0777, true);
                
                file_put_contents($compressed_file, $css);
            }

            array_push($this->css, $file2);
        }else{
            array_push($this->css, $file); //do nothing, only store the css
        }
    }
    
    public function getCSS(){
        $ret = '';
        foreach ($this->css as $css)
            $ret .= $this->assets->css ($css);
        
        return $ret;
    }


    private function getPath($folder, $file){
        return ROOT . 'resources' . DS . $folder . DS . $file;
    }
}
