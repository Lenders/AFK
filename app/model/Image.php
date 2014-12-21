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
 * Description of Avatar
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Image {
    /**
     *
     * @var \MongoCollection
     */
    private $mongo;
    
    public function __construct(\system\Mongo $mongo) {
        $this->mongo = $mongo->image;
    }
    
    public function getImage($owner, $file){
        return $this->mongo->findOne(array('file' => $file, 'owner' => $owner));
    }
    
    public function getUserImages($user){
        return $this->mongo->find(array('owner' => $user), array('owner', 'file'));
    }
    
    public function storeImage($owner, $data, $mime){
        $this->mongo->insert(array(
            'owner' => $owner,
            'file' => md5(uniqid()),
            'data' => new \MongoBinData($data, \MongoBinData::GENERIC),
            'mime' => $mime
        ));
    }
    
    public function imageExists($owner, $file){
        return $this->mongo->findOne(array('file' => $file, 'owner' => $owner), array('owner')) != null;
    }
}
