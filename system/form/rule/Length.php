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

namespace system\form\rule;

/**
 * Description of Length
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Length implements Rule {
    private $min;
    private $max;
    
    public function __construct($min, $max) {
        $this->min = $min;
        $this->max = $max;
    }
    
    public function getError() {
        return 'La taille du champ doit être comprise entre ' . $this->min . ' et ' . $this->max;
    }

    public function getHTMLAttribute() {
        return 'maxlength="' . $this->max . '"';
    }

    public function getJS() {
        return 'new FormValidator.LengthRule(' . $this->min . ', ' . $this->max . ')';
    }

    public function validate($value) {
        $len = strlen($value);
        
        return $len >= $this->min && $len <= $this->max;
    }

}
