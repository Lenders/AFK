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
 * Description of Debug
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Debug implements Helper {
    public function export() {
        return array(
            'pathToRelative', 
            'getFileUrl',
            'exportArgsArray',
            'getErrorLevelToString',
        );
    }
    
    public function pathToRelative($file){
        return substr($file, strlen(ROOT));
    }
    
    public function getFileUrl($file, $line){
        return 'https://github.com/loick111/webcovoiturage/blob/master/' . $this->pathToRelative($file) . '#L' . $line;
    }
    
    public function exportArgsArray(array $args){
        $out = '';
        $first = true;
        
        foreach($args as $arg){
            if(!$first){
                $out .= ', ';
            }
            
            $type = gettype($arg);
            $value = $arg;
            $color = 'red';
            
            if(is_array($arg)){
                $value = $this->exportArgsArray($arg);
                $color = null;
            }else if(is_object($arg)){
                $value = get_class($arg);
                $color = 'grey';
            }else if(is_string($arg)){
                $value = '"' . $arg . '"';
                $color = 'green';
            }
            
            $out .= '<strong>' . $type . '</strong>(<span style="' . (!empty($color) ? 'color:' . $color : '') . '">' . $value . '</span>)';
            
            $first = false;
        }
        
        return $out;
    }
    
    public function getErrorLevelToString($level){
        switch($level){
            case E_NOTICE:
            case E_USER_NOTICE:
                return 'Notice';
            case E_WARNING:
            case E_USER_WARNING:
                return 'Warning';
            case E_ERROR:
            case E_USER_ERROR:
                return 'Fatal error';
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return 'Deprecated';
            case E_STRICT:
                return 'Strict Standards';
            default:
                return 'Unknown error';
        }
    }
}
