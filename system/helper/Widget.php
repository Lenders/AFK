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
 * Description of Widget
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Widget implements Helper {
    /**
     *
     * @var \system\Loader
     */
    private $loader;
    
    function __construct(\system\Loader $loader) {
        $this->loader = $loader;
    }

    public function export() {
        return array('widget');
    }
    
    public function widget($name, $_ = null){
        $w = $this->loader->load('\app\widget\\' . ucfirst($name));
        
        if(!($w instanceof \system\mvc\Widget))
            throw new \system\error\InvalidWidgetClassException('for widget ' . $name);

        $params = func_get_args();
        array_shift($params);
        return call_user_func_array($w, $params);
    }
}
