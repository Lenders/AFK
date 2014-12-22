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

namespace app\controller;

/**
 * Description of Message
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Message extends \system\mvc\Controller {
    /**
     *
     * @var \app\model\Message
     */
    private $model;
    
    public function __construct(\system\Base $base, \app\model\Message $model) {
        parent::__construct($base);
        $this->model = $model;
        
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
    }
    
    public function indexAction(){
        $discussions = $this->model->getDiscussions($this->session->id);
        
        if(!empty($discussions))
            $discussion = $this->model->getDiscussion($discussions->getNext()['_id'], $this->session->id);
        
        if($discussion != null){
            return $this->output->render('message/discussion.php', array(
                'discussions' => $this->model->getDiscussions($this->session->id),
                'current_discussion' => $discussion
            ));
        }
        
        return $this->createAction();
    }
    
    public function discussionAction($id = ''){
        $discussion = $this->model->getDiscussion($id, $this->session->id);
        
        if($discussion === null)
            throw new \system\error\Http404Error('Discussion introuvable');

        if(!in_array($this->session->id, $discussion['users']))
            throw new \system\error\Http403Forbidden();
        
        return $this->output->render('message/discussion.php', array(
            'discussions' => $this->model->getDiscussions($this->session->id),
            'current_discussion' => $discussion,
        ));
    }

    public function postAction($id = '', $redirect = true){
        $users = $this->model->getDiscussionUsers($id);
        
        if(empty($this->input->post->message) || !$users || !in_array($this->session->id, $users))
            throw new \system\error\Http403Forbidden();
        
        $this->model->postMessage($id, $this->session->id, $this->input->post->message);
        
        if($redirect === true){
            $this->output->getHeader()->setLocation($this->helpers->secureUrl('message', 'discussion', $id) . '#last');
        }
    }
    
    public function createAction(){
        if(!empty($this->input->post->name) 
                && !empty($this->input->post->target) 
                && !empty($this->input->post->message)){
            
            if(strlen($this->input->post->name) >= 4 && strlen($this->input->post->name) <= 20){
                $targets = explode(',', $this->input->post->target);
                $ids = array();
                $ok = true;

                foreach($targets as $target){
                    $target = trim($target);

                    if(empty($target))
                        continue;

                    $id = $this->model->getUserIdByPseudo($target);

                    if($id === null){ //invalid target
                        $ok = false;
                        $error = 'Le destinataire ' . htmlentities($target) . ' n\'existe pas';
                        break;
                    }

                    $ids[] = $id;
                }

                if($ok){
                    $id = $this->model->createDiscussion(
                            $this->input->post->name, 
                            $this->input->post->message, 
                            $this->session->id, 
                            $ids
                    );

                    $this->output->getHeader()->setLocation($this->helpers->secureUrl('message', 'discussion', $id));
                    return;
                }
            }else{
                $error = 'Le nom doit faire plus de 4 caractères, et moins de 20';
            }
        }else{
            $error = 'Tout les champs sont requis';
        }
        
        return $this->output->render('message/create.php', array(
            'name' => $this->input->post->name,
            'target' => $this->input->post->target,
            'message' => $this->input->post->message,
            'error' => $error
        ));
    }
    
    public function discussionlistAction(){
        $this->output->setLayoutTemplate(null);
        $this->output->getHeader()->setMimeType('text/json');
        
        $data = $this->model->getDiscussions($this->session->id);
        $ret = array();
        
        foreach($data as $row){
            $view = in_array($this->session->id, $row['views']);
            $ret[] = array(
                'name' => htmlentities($row['name']),
                'view' => $view,
                'id' => $row['_id']->{'$id'},
            );
        }
        
        return json_encode($ret);
    }
}
