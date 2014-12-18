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
 * Description of Crypt
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Crypt implements Helper {
    private $config;
    
    public function __construct(\system\Config $config) {
        $this->config = $config;
    }
    
    public function export() {
        return array(); //do not export for views
    }

    /**
     * Hash a pass
     * @param string $pass password to hash
     * @param string $salt salt for hash
     */
    public function hashPass($pass, $salt){
        $hash = hash(
                $this->config->hash_algo, 
                $this->config->satic_salt . $pass . $salt
        );
        
        return substr($hash, 0, $this->config->hash_size);
    }
    
    /**
     * Generate a random salt (for hash)
     * @param int $size size of the salt, or 0 to use default salt size
     */
    public function generateSalt($size = 0){
        if($size < 1){
            $size = $this->config->salt_size;
        }
        
        $charset = $this->config->salt_charset;
        $len = strlen($charset);
        $salt = '';
        
        for(;$size > 0; --$size){
            $salt .= $charset{rand(0, $len)};
        }
        
        return $salt;
    }
}
