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
 * Description of Friend
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Friend extends \system\mvc\Model {
    public function __construct(\system\Database $db) {
        parent::__construct($db);
    }
    
    public function requestFriend($requester, $target){
        $this->db->executeUpdate('INSERT INTO FRIEND_REQUEST(REQUESTER, TARGET) VALUES(?,?)', $requester, $target);
    }
    
    public function acceptFriendRequest($requester, $target){
        $this->db->beginTransaction();
        $this->db->executeUpdate('DELETE FROM FRIEND_REQUEST WHERE REQUESTER = ? AND TARGET = ?', $requester, $target);
        $this->db->executeUpdate('INSERT INTO FRIEND(USER1, USER2) VALUES(?,?)', $requester, $target);
        $this->db->commit();
    }
    
    public function friendRequestExists($requester, $target){
        return $this->db->selectFirst('SELECT COUNT(*) FROM FRIEND_REQUEST WHERE REQUESTER = ? AND TARGET = ?', $requester, $target)['COUNT(*)'] > 0;
    }
    
    public function isFriends($user1, $user2){
        return $this->db->selectFirst('SELECT COUNT(*) FROM FRIEND WHERE (USER1 = ? AND USER2 = ?) OR (USER2 = ? AND USER1 = ?)', $user1, $user2, $user1, $user2)['COUNT(*)'] > 0;
    }
    
    public function getFriendRequests($user){
        return $this->db->selectAll('SELECT A.* FROM ACCOUNT A, FRIEND_REQUEST WHERE (REQUESTER = USER_ID AND TARGET = ?) OR (TARGET = USER_ID AND REQUESTER = ?)', $user, $user);
    }
    
    public function getFriendsByUserId($id){
        return $this->db->selectAll(
                'SELECT DISTINCT A.* FROM ACCOUNT A '
                . 'JOIN FRIEND F ON A.USER_ID IN(USER1, USER2) '
                . 'WHERE (USER1 = ? OR USER2 = ?) AND A.USER_ID <> ?',
                $id, $id, $id
        );
    }
    
    public function removeRequest($requester, $target){
        $this->db->executeUpdate('DELETE FROM FRIEND_REQUEST WHERE REQUESTER = ? AND TARGET = ?', $requester, $target);
    }
    
    public function removeFriend($user1, $user2){
        $this->db->executeUpdate('DELETE FROM FRIEND WHERE (USER1 = ? AND USER2 = ?) OR (USER2 = ? AND USER1 = ?)', $user1, $user2, $user1, $user2);
    }
}
