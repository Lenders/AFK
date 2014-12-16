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
 * Description of GoogleMapAPI
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class GoogleMapAPI {
    /**
     *
     * @var \system\Config
     */
    private $config;
    
    /**
     *
     * @var \system\Database
     */
    private $db;
    
    public function __construct(\system\Config $config, \system\Database $db) {
        $this->config = $config;
        $this->db = $db;
    }
    
    private function get($url){
        if(!$this->config->cache){
            return $this->getByCUrl($url);
        }else{
            $b64 = base64_encode(strtolower($url));
            $data = $this->db->selectFirst('SELECT RET, UNIX_TIMESTAMP(LAST_UPDATE) AS LAST_UPDATE FROM GOOGLE_API_CACHE WHERE URL = ?', $b64);
            
            if($data == null){
                $ret = $this->getByCUrl($url);
                $this->db->executeUpdate('INSERT INTO GOOGLE_API_CACHE(URL, RET) VALUES(?,?)', $b64, json_encode($ret));
                
                if(DEBUG)
                    $ret['predictions'][] = array('description' => 'Debug : CREATE');
                
            }elseif($data['LAST_UPDATE'] < time() - $this->config->cache_duration){
                $ret = $this->getByCUrl($url);
                $this->db->executeUpdate('UPDATE GOOGLE_API_CACHE SET LAST_UPDATE = NOW(), RET = ? WHERE URL = ?', json_encode($ret), $b64);
                
                if(DEBUG)
                    $ret['predictions'][] = array('description' => 'Debug : UPDATE : ' . $data['LAST_UPDATE']);
                
            }else{
                $ret = json_decode($data['RET'], true);
                
                if(DEBUG)
                    $ret['predictions'][] = array('description' => 'Debug : SELECT');
                
            }
            
            return $ret;
        }
    }
    
    private function getByCUrl($url){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        curl_close($curl);
        
        $data = json_decode($data, true);
        
        if($data['status'] != 'OK' && $data['status'] != 'ZERO_RESULTS')
            throw new \system\error\GoogleMapAPIException($data['status']);

        return $data;
    }
    
    public function prediction($terms){
        /*$curl = curl_init('https://maps.googleapis.com/maps/api/place/autocomplete/json?language=fr&types=geocode&components=country:fr&key=' . $this->config->api_key . '&input=' . urlencode($terms));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        curl_close($curl);
        
        $data = json_decode($data, true);
        
        if($data['status'] != 'OK')
            return false;
        
        $results = array();
        
        foreach($data['predictions'] as $prediction){
            $results[] = $prediction['description'];
        }
        
        return $results;*/
        $terms = strtolower($terms);
        return $this->get('https://maps.googleapis.com/maps/api/place/autocomplete/json?language=fr&types=geocode&components=country:fr&key=' . $this->config->api_key . '&input=' . urlencode($terms));
    }
}
