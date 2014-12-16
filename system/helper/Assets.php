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
 * Description of Assets
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Assets implements Helper {
    /**
     *
     * @var \system\input\Input
     */
    private $input;
    
    public function __construct(\system\input\Input $input) {
        $this->input = $input;
    }
    
    public function export() {
        return array(
            'css', 'js', 'img'
        );
    }
    
    private function getFile($file){
        return $this->input->getBaseUrl() . 'resources/' . $file;
    }

    public function css($file){
        $href = $this->getFile('css/' . $file . '.css');
        return '<link rel="stylesheet" type="text/css" href="' . $href . '"/>';
    }
    
    public function js($file){
        $src = $this->getFile('js/' . $file . '.js');
        return '<script type="text/javascript" src="' . $src . '"></script>';
    }
    
    public function img($file, $alt = '') {
        $src = $this->getFile('images/' . $file);
        return '<img src="' . $src . '" alt="' . $alt . '" />';
    }
}
