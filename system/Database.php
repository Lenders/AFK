<?php

/*
 * The MIT License
 *
 * Copyright 2014 loick.
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

namespace system;

/**
 * Description of Database
 *
 * @author Vincent Quatrevieux
 */
class Database extends \PDO{        
    /**
     * database configuration
     * @var \system\Config
     */
    private $config;
    
    /**
     *
     * @var \system\helper\Bench
     */
    private $bench;
    
    public function __construct(Config $config, \system\helper\Bench $bench) {
        $this->config = $config;
        $this->bench = $bench;
        
        $bench->start('Database connect');
        
        $options = array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                \PDO::ATTR_STATEMENT_CLASS => array('\system\BenchStatement', array($bench))
        );
        
        parent::__construct(
            $this->generateDSN(), 
            $config->user, 
            $config->pass,
            $options
        );
        $bench->end();
    }
    
    private function generateDSN(){
        $dsn = $this->config->driver . ':';
        
        foreach($this->config->vars as $key => $value){
            $dsn .= $key . '=' . $value . ';';
        }
        
        return $dsn;
    }
    
    public function executeUpdate($query, $_){
        $params = func_get_args();
        $stmt = $this->prepare(array_shift($params));
        return $stmt->execute($params);
    }
    
    public function selectAll($query, $_){
        $params = func_get_args();
        $stmt = $this->prepare(array_shift($params));
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function selectFirst($query, $_){
        $params = func_get_args();
        $stmt = $this->prepare(array_shift($params));
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    public function query($statement) {
        $this->bench->start($statement);
        $stmt = parent::query($statement);
        $this->bench->end();
        return $stmt;
    }

}

class BenchStatement extends \PDOStatement{
    /**
     *
     * @var \system\helper\Bench
     */
    private $bench;
    
    private function __construct(\system\helper\Bench $bench) {
        $this->bench = $bench;
    }

    public function execute($input_parameters = null) {
        $this->bench->start($this->queryString);
        $ret = parent::execute($input_parameters);
        $this->bench->end();
        return $ret;
    }

}