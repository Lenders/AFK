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
 * Description of Account
 *
 * @author p13006720
 */
class Account extends \system\mvc\Model {
    /**
     *
     * @var \system\Cache
     */
    private $cache;
    
    public function __construct(\system\Database $db, \system\Cache $cache) {
        parent::__construct($db);
        $this->cache = $cache;
    }
    
    public function getAccountById($id){
        return $this->db->selectFirst('SELECT * FROM ACCOUNT WHERE USER_ID = ?', $id);
    }
    
    public function getAccountByPseudo($pseudo){
        return $this->db->selectFirst('SELECT * FROM ACCOUNT WHERE PSEUDO = ?', $pseudo);
    }
    
    public function pseudoExists($pseudo){
        return $this->db->selectFirst('SELECT COUNT(*) FROM ACCOUNT WHERE PSEUDO = ?', $pseudo)['COUNT(*)'] > 0;
    }
    
    public function mailExists($mail){
        return $this->db->selectFirst('SELECT COUNT(*) FROM ACCOUNT WHERE MAIL = ?', $mail)['COUNT(*)'] > 0;
    }
    
    public function create($pseudo, $pass, $salt, $firstName, $lastName, $gender, $mail){
        $this->db->executeUpdate(
                'INSERT INTO ACCOUNT(PSEUDO, PASS, SALT, FIRST_NAME, LAST_NAME, GENDER, MAIL) VALUES(?,?,?,?,?,?,?)',
                $pseudo, $pass, $salt, $firstName, $lastName, $gender, $mail
        );
    }
    
    public function accountExists($id){
        return $this->db->selectFirst('SELECT COUNT(*) FROM ACCOUNT WHERE USER_ID = ?', $id)['COUNT(*)'] > 0;
    }
    
    public function searchAccount($query, $limit = false){
        $query = str_replace('*', '%', $query);
        $query = '%' . $query . '%';
        
        return $this->db->selectAll(
            'SELECT * FROM ACCOUNT '
          . 'WHERE PSEUDO LIKE ? OR CONCAT(FIRST_NAME, " ", LAST_NAME) LIKE ? '
          . 'ORDER BY PSEUDO ASC' . ($limit ? ' LIMIT ' . (int)$limit : ''),
            $query, $query
        );
    }
    
    public function setAvatar($user, $avatar){
        $this->db->executeUpdate('UPDATE ACCOUNT SET AVATAR = ? WHERE USER_ID = ?', $avatar, $user);
    }
    
    public function getAvatar($user){
        return $this->db->selectFirst('SELECT AVATAR FROM ACCOUNT WHERE USER_ID = ?', $user)['AVATAR'];
    }
    
    public function getUserIdByPseudo($pseudo){
        $data = $this->db->selectFirst('SELECT USER_ID FROM ACCOUNT WHERE PSEUDO = ?', $pseudo);
        
        if($data == null)
            return null;
        
        return $data['USER_ID'];
    }
    
    public function getPseudoByUserId($id){
        $data = $this->db->selectFirst('SELECT PSEUDO FROM ACCOUNT WHERE USER_ID = ?', $id);
        
        if($data == null)
            return null;
        
        return $data['PSEUDO'];
    }
    
    public function setAdmin($user, $admin){
        $this->db->executeUpdate('UPDATE ACCOUNT SET IS_ADMIN = ? WHERE USER_ID = ?', $admin ? 'YES' : 'NO', $user);
    }
    
    public function delete($user){
        $this->db->beginTransaction();
        $this->db->executeUpdate('DELETE FROM FRIEND_REQUEST WHERE REQUESTER = ? OR TARGET = ?', $user, $user);
        $this->db->executeUpdate('DELETE FROM FRIEND WHERE USER1 = ? OR USER2 = ?', $user, $user);
        $this->db->executeUpdate('DELETE FROM EVENT_MESSAGE WHERE USER_ID = ?', $user);
        $this->db->executeUpdate('DELETE FROM COMPETITOR WHERE USER_ID = ?', $user);
        $this->db->executeUpdate('DELETE FROM ACCOUNT WHERE USER_ID = ?', $user);
        $this->db->commit();
    }
}
