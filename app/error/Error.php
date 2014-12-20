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

namespace app\error;

/**
 * Description of Error
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 * @warning All helpers should be loaded manually !
 */
class Error extends \system\mvc\Controller {
    public function __construct(\system\Base $base) {
        parent::__construct($base);
        $this->output->setLayoutTemplate('layout/error.php');
        $this->helpers->loadHelper('debug');
        $this->helpers->loadHelper('config');
        $this->helpers->loadHelper('assets');
        $this->output->getHeader()->setHttpCode(500);
    }
    
    public function defaultError(\Exception $ex){
        if(!$this->output)
            die($ex);
        
        $this->output->getHeader()->setHttpCode(500); //Internal server error
        $this->output->setTitle('Erreur serveur');
        
        return $this->output->render('error/error.php', array(
            'code' => $ex->getCode(),
            'message' => $ex->getMessage(),
            'type' => substr(strrchr('\\' . get_class($ex), '\\'), 1),
            'trace' => $ex->getTrace(),
            'file' => $ex->getFile(),
            'line' => $ex->getLine()
        ));
    }
    
    public function errorException(\ErrorException $err){
        $this->output->getHeader()->setHttpCode(500); //Internal server error
        $this->output->setTitle('Erreur');
        $this->loader->load('\system\helper\HelpersManager')->loadHelper('debug');
        
        return $this->output->render('error/exc_err.php', array(
            'message' => $err->getMessage(),
            'level' => $err->getSeverity(),
            'trace' => $err->getTrace(),
            'file' => $err->getFile(),
            'line' => $err->getLine()
        ));
    }
}
