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

/**
 * Library to load at the start
 * Format : 
 *  'registry name' => 'Library class name', //to load the library and store in the registry
 * or :
 *  'Library class name', //to only load the library
 * 
 * @warning don't load unsafe code in critical section !
 */
return array(
    //critical loading (no error handler)
    'output' => '\system\output\Output', //load the Output manager
    'helpers' => '\system\helper\HelpersManager',
    '\system\error\ErrorsHandler',
    
    //safe loading (with error handler)
    'input' => '\system\input\Input',
    '\system\helper\HelpersLoader',
    'session' => '\system\Session',
    '\system\Statistics',
    'cache' => '\system\Cache',
);

