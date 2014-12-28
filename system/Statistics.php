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

namespace system;

/**
 * Statistics handler and client tracker
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Statistics {
    /**
     *
     * @var \MongoCollection
     */
    private $mongo;
    
    /**
     *
     * @var \system\Session
     */
    private $session;

    /**
     *
     * @var \system\input\Input
     */
    private $input;

    public function __construct(\system\Mongo $mongo, \system\Session $session, \system\input\Input $input) {
        $this->mongo = $mongo->statistics;
        $this->session = $session;
        $this->input = $input;
        $this->_track();
    }
    
    private function _track(){
        $data = $this->mongo->findOne(array('session' => $this->session->getSESSID()));
        
        if(!$data){
            $this->mongo->insert (array(
                'session' => $this->session->getSESSID (),
                'history' => array(),
                'user_agent' => $this->input->getUserAgent()
            ));
        }
        
        $this->mongo->update(
            array('session' => $this->session->getSESSID()), 
            array(
                '$push' => array(
                    'history' => array(
                        'path' => implode('/', $this->input->getPath()),
                        'time' => time()
                    )
                )
            )
        );
    }
    
    public function getAllHistory(){
        $data = $this->mongo->find(array(), array('history'));
        
        $history = array();
        
        foreach($data as $row){
            foreach($row['history'] as $page)
                $history[] = $page;
        }
        
        return $history;
    }
    
    public function getUserAgents(){
        $data = $this->mongo->find(array(), array('user_agent'));
        
        $agents = array();
        foreach($data as $row){
            $agents[] = $row['user_agent'];
        }
        
        return $agents;
    }
}
