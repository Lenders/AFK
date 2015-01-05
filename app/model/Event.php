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
    /**
     *
     * @var \system\Cache
     */
    private $cache;
    
    public function __construct(\system\Database $db, \system\Cache $cache) {
        parent::__construct($db);
        $this->cache = $cache;
    }
    
    public function getEventById($id){
        return $this->db->selectFirst(
                'SELECT E.*, A.PSEUDO, UNIX_TIMESTAMP(EVENT_START) AS EVENT_START, UNIX_TIMESTAMP(EVENT_END) AS EVENT_END FROM EVENT E '
                . 'JOIN ACCOUNT A ON USER_ID = ORGANIZER '
                . 'WHERE EVENT_ID = ?', $id);
    }
    
    public function findEventsByOrganizer($organizer){
        $events = $this->db->selectAll('SELECT *, UNIX_TIMESTAMP(EVENT_START) AS START_TIME, UNIX_TIMESTAMP(EVENT_END) AS END_TIME '
                . 'FROM EVENT '
                . 'WHERE ORGANIZER = ? '
                . 'ORDER  BY EVENT_DATE DESC', $organizer);
        
        foreach($events as &$event){
            $properties = $this->getPropertiesByEvent($event['EVENT_ID']);
            $prop = array();
            
            foreach($properties as $property){
                $prop[strtoupper($property['PROPERTY_NAME'])] = $property;
            }
            
            $event['PROPERTIES'] = $prop;
        }
        
        return $events;
    }
    
    public function findParticipatedEvents($user_id){
        $events = $this->db->selectAll(
                'SELECT E.*, UNIX_TIMESTAMP(EVENT_START) AS START_TIME, UNIX_TIMESTAMP(EVENT_END) AS END_TIME '
                . 'FROM EVENT E '
                . 'JOIN COMPETITOR C ON C.EVENT_ID = E.EVENT_ID '
                . 'WHERE C.USER_ID = ? '
                . 'ORDER BY EVENT_DATE DESC',
                $user_id
        );
        
        foreach($events as &$event){
            $properties = $this->getPropertiesByEvent($event['EVENT_ID']);
            $prop = array();
            
            foreach($properties as $property){
                $prop[strtoupper($property['PROPERTY_NAME'])] = $property;
            }
            
            $event['PROPERTIES'] = $prop;
        }
        
        return $events;
    }
    
    public function getPropertiesByEvent($event_id, $only_public = false){
        $query = 'SELECT PROPERTY_VALUE, PROPERTY_NAME '
                . 'FROM EVENT_PROPERTY P '
                . 'JOIN EVENT_PROPERTY_CHECK C '
                . 'ON P.PROPERTY_ID = C.PROPERTY_ID '
                . 'WHERE EVENT_ID = ?';
        
        if($only_public)
            $query .= ' AND C.PROPERTY_PRIVACY = \'PUBLIC\'';
        
        return $this->db->selectAll($query, $event_id);
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
    public function createEvent($name, $organizer, $privacy, $start, $end, array $properties){
        $this->db->beginTransaction();
        
        $this->db->executeUpdate('INSERT INTO EVENT(EVENT_NAME, ORGANIZER, EVENT_PRIVACY, EVENT_START, EVENT_END) VALUES(?,?,?,?,?)', $name, $organizer, $privacy, $start, $end);
        $event_id = $this->db->lastInsertId();
        
        $this->addCompetitor($event_id, $organizer);
        
        $checks = $this->getPropertyChecksByName();
        
        foreach($properties as $name => $value){
            $this->addEventProperty($event_id, $checks[$name]['PROPERTY_ID'], $value);
        }
        
        $this->db->commit();
        
        return $event_id;
    }
    
    public function addEventProperty($event_id, $property, $value){
        $this->db->executeUpdate('INSERT INTO EVENT_PROPERTY(EVENT_ID, PROPERTY_ID, PROPERTY_VALUE) VALUES(?,?,?)', $event_id, $property, $value);
    }
    
    public function addCompetitor($event_id, $user_id){
        $this->db->executeUpdate('INSERT INTO COMPETITOR(EVENT_ID, USER_ID) VALUES(?,?)', $event_id, $user_id);
    }
    
    public function isCompetitor($event_id, $user_id){
        return $this->db->selectFirst('SELECT COUNT(*) FROM COMPETITOR WHERE EVENT_ID = ? AND USER_ID = ?', $event_id, $user_id)['COUNT(*)'] > 0;
    }
    
    public function getCompetitors($event_id){
        return $this->db->selectAll('SELECT A.USER_ID, A.PSEUDO, A.AVATAR, A.FIRST_NAME, A.LAST_NAME FROM COMPETITOR C JOIN ACCOUNT A ON A.USER_ID = C.USER_ID WHERE EVENT_ID = ?', $event_id);
    }
    
    public function getEventName($event_id){
        $data = $this->db->selectFirst('SELECT EVENT_NAME FROM EVENT WHERE EVENT_ID = ?', $event_id);
        
        if(!$data)
            return null;
        
        return $data['EVENT_NAME'];
    }
    
    public function sendMessage($event_id, $user_id, $message){
        $this->db->executeUpdate('INSERT INTO EVENT_MESSAGE(EVENT_ID, USER_ID, MESSAGE) VALUES(?,?,?)', $event_id, $user_id, $message);
    }
    
    public function searchEvents($query, $limit = false){
        $query = '%' . str_replace('*', '%', $query) . '%';
        
        $events = $this->db->selectAll(
                'SELECT DISTINCT E.*, UNIX_TIMESTAMP(EVENT_START) AS START_TIME, UNIX_TIMESTAMP(EVENT_END) AS END_TIME FROM EVENT E '
                . 'JOIN EVENT_PROPERTY P ON P.EVENT_ID = E.EVENT_ID '
                . 'JOIN EVENT_PROPERTY_CHECK C ON P.PROPERTY_ID = C.PROPERTY_ID '
                . 'WHERE PROPERTY_PRIVACY = \'PUBLIC\' AND (EVENT_NAME LIKE ? OR PROPERTY_VALUE LIKE ?) '
                . 'ORDER BY EVENT_DATE DESC' . ($limit ? ' LIMIT ' . (int)$limit : ''),
                $query, $query
        );

        foreach($events as &$event){
            $properties = $this->getPropertiesByEvent($event['EVENT_ID']);
            $prop = array();

            foreach($properties as $property){
                $prop[strtoupper($property['PROPERTY_NAME'])] = $property;
            }

            $event['PROPERTIES'] = $prop;
        }

        return $events;
    }
    
    public function requestExists($event_id, $user_id){
        return $this->db->selectFirst('SELECT COUNT(*) FROM JOIN_REQUEST WHERE EVENT_ID = ? AND USER_ID = ?', $event_id, $user_id)['COUNT(*)'] > 0;
    }
    
    public function addRequest($event_id, $user_id){
        return $this->db->executeUpdate('INSERT INTO JOIN_REQUEST(EVENT_ID, USER_ID) VALUES(?,?)', $event_id, $user_id);
    }
    
    public function removeRequest($event_id, $user_id){
        $this->db->executeUpdate('DELETE FROM JOIN_REQUEST WHERE USER_ID = ? AND EVENT_ID= ?', $user_id, $event_id);
    }
    
    public function requestCountByEvent($event_id){
        return $this->db->selectFirst('SELECT COUNT(*) FROM JOIN_REQUEST WHERE EVENT_ID = ?', $event_id)['COUNT(*)'];
    }
    
    public function getRequestList($event_id){
        return $this->db->selectAll('SELECT A.* FROM JOIN_REQUEST R JOIN ACCOUNT A ON R.USER_ID = A.USER_ID WHERE EVENT_ID = ?', $event_id);
    }
    
    public function acceptRequest($event_id, $user_id){
        $this->db->beginTransaction();
        $this->removeRequest($event_id, $user_id);
        $this->addCompetitor($event_id, $user_id);
        $this->db->commit();
    }
    
    public function removeCompetitor($event_id, $user_id){
        $this->db->executeUpdate('DELETE FROM COMPETITOR WHERE EVENT_ID = ? ND USER_ID = ?', $event_id, $user_id);
    }
    
    public function getEventAgenda($startTime, $endTime, $user){
        $agenda = array();
        
        $stmt = $this->db->prepare(
            'SELECT EVENT_NAME, E.EVENT_ID, UNIX_TIMESTAMP(EVENT_START) AS START_TIME, UNIX_TIMESTAMP(EVENT_END) AS END_TIME FROM EVENT E '
                . 'JOIN COMPETITOR C ON C.EVENT_ID = E.EVENT_ID '
                . 'WHERE (UNIX_TIMESTAMP(EVENT_START) BETWEEN :start AND :end '
                . 'OR UNIX_TIMESTAMP(EVENT_END) BETWEEN :start AND :end '
                . 'OR :start BETWEEN UNIX_TIMESTAMP(EVENT_START) AND UNIX_TIMESTAMP(EVENT_END) '
                . 'OR :end BETWEEN UNIX_TIMESTAMP(EVENT_START) AND UNIX_TIMESTAMP(EVENT_END)) AND USER_ID = :user '
        );
        
        $stmt->bindValue('start', $startTime);
        $stmt->bindValue('end', $endTime);
        $stmt->bindValue('user', $user);
        $stmt->execute();
        
        for(;$startTime <= $endTime; $startTime += 24 * 3600){
            $agenda[$startTime] = array();
        }
        
        while($row = $stmt->fetch()){
            foreach($agenda as $day => &$events){
                $next = $day + 24 * 3600;
                
                if($row['START_TIME'] >= $day && $row['START_TIME'] < $next)
                    $events[] = $row;
                elseif($row['END_TIME'] >= $day && $row['END_TIME'] < $next)
                    $events[] = $row;
                elseif($row['START_TIME'] <= $day && $row['END_TIME'] >= $day)
                    $events[] = $row;
            }
        }
        
        return $agenda;
    }
    
    public function isOrganizer($user_id, $event_id){
        return $this->db->selectFirst('SELECT COUNT(*) FROM EVENT WHERE EVENT_ID = ? AND ORGANIZER = ?', $event_id, $user_id)['COUNT(*)'] > 0;
    }
    
    public function setImage($event_id, $file){
        $this->db->executeUpdate('UPDATE EVENT SET IMAGE = ? WHERE EVENT_ID = ?', $file, $event_id);
    }
    
    public function deleteEventsByOrganizer($organizer){
        $this->db->beginTransaction();
        $this->db->executeUpdate('DELETE FROM COMPETITOR WHERE EVENT_ID IN (SELECT EVENT_ID FROM EVENT WHERE ORGANIZER = ?)', $organizer);
        $this->db->executeUpdate('DELETE FROM EVENT_MESSAGE WHERE EVENT_ID IN (SELECT EVENT_ID FROM EVENT WHERE ORGANIZER = ?)', $organizer);
        $this->db->executeUpdate('DELETE FROM EVENT_PROPERTY WHERE EVENT_ID IN (SELECT EVENT_ID FROM EVENT WHERE ORGANIZER = ?)', $organizer);
        $this->db->executeUpdate('DELETE FROM EVENT WHERE ORGANIZER = ?', $organizer);
        $this->db->commit();
    }
    
    public function deleteEvent($event_id){
        $this->db->beginTransaction();
        $this->db->executeUpdate('DELETE FROM COMPETITOR WHERE EVENT_ID = ?', $event_id);
        $this->db->executeUpdate('DELETE FROM EVENT_MESSAGE WHERE EVENT_ID = ?', $event_id);
        $this->db->executeUpdate('DELETE FROM EVENT_PROPERTY WHERE EVENT_ID = ?', $event_id);
        $this->db->executeUpdate('DELETE FROM EVENT WHERE EVENT_ID = ?', $event_id);
        $this->db->commit();
    }
}
