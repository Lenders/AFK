<?php

/*
 * The MIT License
 *
 * Copyright 2014 p13006720.
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
 * Description of Event
 *
 * @author p13006720
 */
class Event extends \system\mvc\Model  {
    public function getEventById($id){
        return $this->db->selectFirst('SELECT * FROM EVENT WHERE EVENT_ID = ?', $id);
    }
    
    public function getEvenByName($name){
        return $this->db->selectFirst('SELECT * FROM EVENT WHERE EVENT_NAME = ?', $name);
    }
    
    public function findEventsByOrganizer($organizer){
        return $this->db->selectAll('SELECT * FROM EVENT WHERE ORGANIZER = ?', $organizer);
    }
    
    public function getPropertyChecks(){
        return $this->db->query('SELECT * FROM EVENT_PROPERTY_CHECK')->fetchAll();
    }
    
    public function getPropertyChecksByName(){
        $data = $this->getPropertyChecks();
        
        $ret = array();
        
        foreach($data as $check){
            $ret[$check['PROPERTY_NAME']] = $check;
        }
        
        return $ret;
    }
    
    /**
     * Create a new event and return its ID
     * @param type $name
     * @param type $organizer
     * @param type $privacy
     * @param type $start
     * @param type $end
     * @return int the generated ID of the event
     */
    public function createEvent($name, $organizer, $privacy, $start, $end){
        $this->db->executeUpdate('INSERT INTO EVENT(EVENT_NAME, ORGANIZER, EVENT_PRIVACY, EVENT_START, EVENT_END) VALUES(?,?,?,?,?)', $name, $organizer, $privacy, $start, $end);
        return $this->db->lastInsertId();
    }
    
    public function addEventProperty($event_id, $property, $value){
        $this->db->executeUpdate('INSERT INTO EVENT_PROPERTY(EVENT_ID, PROPERTY_ID, PROPERTY_VALUE) VALUES(?,?,?)', $event_id, $property, $value);
    }
    
    public function addEventProperties($event_id, array $properties){
        $checks = $this->getPropertyChecksByName();
        
        $this->db->beginTransaction();
        
        foreach($properties as $name => $value){
            $this->addEventProperty($event_id, $checks[$name]['PROPERTY_ID'], $value);
        }
        
        $this->db->commit();
    }
}
