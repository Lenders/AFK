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

namespace app\flux;

/**
 * Description of Flux
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
abstract class Flux implements \Iterator {
    /**
     *
     * @var \system\output\Output
     */
    private $output;
    
    /**
     *
     * @var \app\flux\parser\ParserHandler
     */
    private $parserHandler;
    
    /**
     *
     * @var \system\Database
     */
    protected $db;

    /**
     *
     * @var \PDOStatement
     */
    private $stmt;
    private $current = null;
    private $key = 0;
    
    function __construct(\system\output\Output $output, \app\flux\parser\ParserHandler $parserHandler, \system\Database $db) {
        $this->output = $output;
        $this->parserHandler = $parserHandler;
        $this->db = $db;
    }
    
    public function current() {
        return $this->current;
    }

    public function key() {
        return $this->key;
    }

    public function next() {
        $this->_fetch();
    }
    
    private function _fetch(){
        $row = $this->stmt->fetch();
        
        if($row){
            $parser = $this->parserHandler->getParser($row['FLUX_TYPE']);
            $data = $parser->parseRow($row);
            $this->current = new Article($this->output, $data);
            ++$this->key;
        }else{
            $this->key = null;
            $this->current = null;
        }
    }
    
    /**
     *  @return \PDOStatement
     */
    abstract protected function getStatement();

    public function rewind() {
        $this->stmt = $this->getStatement();
        $this->key = 0;
        $this->_fetch();
    }

    public function valid() {
        return $this->current !== null;
    }

}
