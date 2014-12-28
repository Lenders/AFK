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
 * Description of Stats
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Stats extends \system\mvc\Model {
    /**
     *
     * @var \system\Statistics
     */
    private $statistics;
    
    public function __construct(\system\Database $db, \system\Statistics $statistics) {
        parent::__construct($db);
        $this->statistics = $statistics;
    }
    
    public function getMaleFemaleCounts(){
        return $this->db->query('SELECT COUNT(GENDER) AS EFFECTIVE, GENDER AS TITLE FROM ACCOUNT GROUP BY GENDER')->fetchAll();
    }
    
    public function getPagesCounts($max){
        $data = array();
        $exclude = array('#^json/.*$#i', '#^admin.*$#i', '#^message/lastmessages/.*$#i');
        
        foreach($this->statistics->getAllHistory() as $page){
            if(isset($data[$page['path']]))
                ++$data[$page['path']];
            else{
                $ok = true;
                foreach($exclude as $pattern){
                    if(preg_match($pattern, $page['path'])){
                        $ok = false;
                        break;
                    }
                }

                if(!$ok)
                    continue;

                $data[$page['path']] = 1;
            }
        }
        
        arsort($data);
        
        $ret = array();
        
        foreach($data as $title => $effective){
            if($max-- < 0)
                break;
            
            $ret[] = array(
                'TITLE' => '/' . $title,
                'EFFECTIVE' => $effective
            );
        }
        
        return $ret;
    }
    
    public function getBrowserCounts(){
        $browsers = array();
        
        foreach($this->statistics->getUserAgents() as $ua){
            $curl = curl_init('http://www.useragentstring.com/?uas=' . urlencode($ua) . '&getJSON=agent_name');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $data = json_decode(curl_exec($curl), true);
            $browser = $data['agent_name'];
            
            if(!isset($browsers[$browser]))
                $browsers[$browser] = 1;
            else
                ++$browsers[$browser];
        }
        
        $ret = array();
        
        foreach($browsers as $browser => $eff){
            $ret[] = array(
                'TITLE' => $browser,
                'EFFECTIVE' => $eff
            );
        }
        
        return $ret;
    }
}
