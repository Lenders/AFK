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
 * Description of Message
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Message {
    /**
     *
     * @var \MongoCollection
     */
    private $mongo;
    
    /**
     *
     * @var \app\model\Account
     */
    private $account;
    
    public function __construct(\system\Database $db, \system\Mongo $mongo, \app\model\Account $account) {
        $this->mongo = $mongo->messages;
        $this->account = $account;
    }
    
    public function createDiscussion($name, $message, $sender, array $users){
        $users[] = $sender;
        
        foreach($users as &$user)
            $user = (int)$user;
        
        $contents = array(
            'name' => $name,
            'users' => $users,
            'last_message_date' => new \MongoDate(),
            'views' => array(),
            'messages' => array(
                array(
                    'sender' => (int)$sender,
                    'message' => new \MongoBinData($message, \MongoBinData::BYTE_ARRAY),
                    'date' => new \MongoDate()
                )
            )
        );
        
        $this->mongo->insert($contents);
        return $contents['_id']->{'$id'};
    }
    
    public function getDiscussions($user_id){
        return $this->mongo->find(array('users' => (int)$user_id), array('name', 'views'))
                           ->sort(array('last_message_date' => -1));
    }
    
    public function postMessage($discussion_id, $sender, $message){
        var_dump($this->mongo->update(
            array('_id' => new \MongoId($discussion_id)), 
            array(
                '$push' => array('messages' => array(
                    'sender' => (int)$sender,
                    'message' => new \MongoBinData($message, \MongoBinData::BYTE_ARRAY),
                    'date' => new \MongoDate()
                )),
                '$set' => array(
                    'last_message_date' => new \MongoDate(),
                    'views' => array((int)$sender)
                )
            )
        ));
    }
    
    public function getDiscussion($discussion_id, $user_id){
        $user_id = (int)$user_id;
        
        $discussion = $this->mongo->findAndModify(
            array('_id' => new \MongoId($discussion_id), 'users' => $user_id),
            array('$push' => array('views' => $user_id))
        );
        
        if(!$discussion)
            return null;
        
        $users = array();
        
        foreach($discussion['users'] as $user){
            $users[$user] = $this->account->getAccountById($user);
        }
        
        $discussion['real_users'] = $users;
        
        return $discussion;
    }
    
    public function getLastMessages($discussion_id, $user_id, \MongoDate $date){
        $user_id = (int)$user_id;
        
        $data = $this->mongo->findAndModify(
            array('_id' => new \MongoId($discussion_id), 'users' => $user_id),
            array('$push' => array('views' => $user_id)),
            array(
                'messages' => array(
                    '$elemMatch' => array('date' => array('$gt' => $date))
                ),
                '_id' => 0
            )
        );
        
        if(empty($data))
            return array();
        
        $messages = array();
        
        foreach($data['messages'] as $row){
            $messages[] = array(
                'date' => $row['date']->sec . ',' . $row['date']->usec,
                'date_str' => date('d/m/y à H:i:s', $row['date']->sec),
                'message' => htmlentities($row['message']->bin),
                'sender' => $this->account->getPseudoByUserId($row['sender']),
                'me' => $row['sender'] == $user_id
            );
        }
        
        return $messages;
    }
    
    public function getDiscussionUsers($id){
        $data = $this->mongo->findOne(array('_id' => new \MongoId($id)), array('users'));
        
        if(!$data)
            return null;
        
        return $data['users'];
    }
    
    public function getUserIdByPseudo($pseudo){
        return $this->account->getUserIdByPseudo($pseudo);
    }
    
    public function getPseudoByUserId($id){
        return $this->account->getPseudoByUserId($id);
    }
    
    public function getUnreadMessagesCount($user){
        return $this->mongo->count(array('users' => (int)$user, 'views' => array('$ne' => (int)$user)));
    }
    
    public function getPrivateDiscussionId($user1, $user2){
        $data = $this->mongo->find(
            array('users' => array('$in' => array(array((int)$user1, (int)$user2), array((int)$user2, (int)$user1)))), 
            array('_id')
        )->sort(array('last_message_date' => -1));
        
        
        if(!$data || $data->count() < 1)
            return null;
        
        $data = $data->getNext();
        
        return $data['_id']->{'$id'};
    }
}
