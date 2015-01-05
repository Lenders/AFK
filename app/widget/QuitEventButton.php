<?php

/*
 * The MIT License
 *
 * Copyright 2015 Vincent Quatrevieux <quatrevieux.vincent@gmail.com>.
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

namespace app\widget;

/**
 * Description of QuitEventButton
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class QuitEventButton extends \system\mvc\Widget {
    /**
     *
     * @var \app\model\Event
     */
    private $model;
    
    public function __construct(\system\Base $base, \app\model\Event $model) {
        parent::__construct($base);
        $this->model = $model;
    }
    
    public function __invoke($event) {
        if(!is_array($event))
            $event = $this->model->getEventById((int)$event);
        
        if(!$this->session->isLogged() 
                || $event['ORGANIZER'] == $this->session->id
                || !$this->model->isCompetitor($event['EVENT_ID'], $this->session->id))
            return '';
        
        return $this->output->render('widget/quit_event_button.php', array('event' => $event));
    }
}
