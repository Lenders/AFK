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

namespace app\model;

/**
 * Description of Autocomplete
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Autocomplete extends \system\mvc\Model {

    /**
     *
     * @var \system\Storage
     */
    private $storage;

    /**
     *
     * @var \system\Config
     */
    private $config;

    public function __construct(\system\Database $db, \system\Storage $storage, \system\Config $config) {
        parent::__construct($db);
        $this->storage = $storage;
        $this->config = $config;
    }

    private function _get($set, $term) {
        $it = null;
        $set = $this->_getSetName($set);
        $ret = array();

        while ($a = $this->storage->sscan($set, $it, '*' . $term . '*')) {
            foreach ($a as $data) {
                $ret[] = $data;
            }
        }

        return $ret;
    }

    private function _store($set, array $values) {
        $this->storage->multi();
        $set = $this->_getSetName($set);

        foreach ($values as $value) {
            $this->storage->sAdd($set, strtolower($value));
        }
        $this->storage->expire($set, $this->config->cache_expire);
        $this->storage->exec();
    }

    private function _exists($set) {
        return $this->storage->exists($this->_getSetName($set));
    }

    private function _getSetName($set) {
        return $this->config->prefix . $set;
    }

    public function queryUsers($query) {
        if (!$this->_exists('USER')) {
            $values = array();
            $stmt = $this->db->query('SELECT PSEUDO, CONCAT(FIRST_NAME, " ", LAST_NAME) AS NAME FROM ACCOUNT');
            while($a = $stmt->fetch()){
                array_push($values, $a['PSEUDO'], $a['NAME']);
            }
            $this->_store('USER', $values);
        }
        
        return $this->_get('USER', strtolower($query));
    }
    
    public function queryPseudo($pseudo){
        if (!$this->_exists('PSEUDO')) {
            $values = array();
            $stmt = $this->db->query('SELECT PSEUDO FROM ACCOUNT');
            while($a = $stmt->fetch()){
                array_push($values, $a['PSEUDO']);
            }
            $this->_store('PSEUDO', $values);
        }
        
        return $this->_get('PSEUDO', strtolower($pseudo));
    }
    
    public function queryEvents($query){
        if (!$this->_exists('EVENT')) {
            $values = array();
            
            $stmt = $this->db->query('SELECT DISTINCT EVENT_NAME FROM EVENT');
            
            while($a = $stmt->fetch()){
                $values[] = $a['EVENT_NAME'];
            }
            
            $stmt = $this->db->query('SELECT DISTINCT PROPERTY_VALUE FROM EVENT_PROPERTY P '
                    . 'JOIN EVENT_PROPERTY_CHECK C ON C.PROPERTY_ID = P.PROPERTY_ID '
                    . 'WHERE PROPERTY_PRIVACY = \'PUBLIC\'');
            
            while($a = $stmt->fetch()){
                $values[] = $a['PROPERTY_VALUE'];
            }
            
            $this->_store('EVENT', $values);
        }
        
        return $this->_get('EVENT', strtolower($query));
    }
    
}
