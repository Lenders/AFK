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

namespace app\controller;

/**
 * Description of Calendar
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Calendar extends \system\mvc\Controller {
    private $days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
    private $months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
    
    /**
     *
     * @var \app\model\Event
     */
    private $event;
    
    public function __construct(\system\Base $base, \app\model\Event $event) {
        parent::__construct($base);
        $this->event = $event;
    }
    
    public function indexAction(){
        return $this->monthAction(date('Y'), date('m'));
    }
    
    public function monthAction($year = 2015, $month = 1){
        if(!$this->session->isLogged())
            throw new \system\error\Http403Forbidden();
        
        $year = (int)$year;
        $month = (int)$month;
        
        if($month > 12 || $month < 1)
            throw new \system\error\Http404Error('Mois invalide');

        if(strlen($month) < 2)
            $month = '0' . $month;
        
        if(strlen($year) < 4)
            $year = '20' . $year;
        
        $firstTimeOfMonth = $this->_firstTimeOfMonth($month, $year);
        $lastTimeOfMonth = $this->_lastTimeOfMonth($month, $year);
        $monthName = $this->months[$month - 1] . ' ' . $year;
        
        $this->output->setTitle('Calendrier ' . $monthName);
        $this->output->addKeyword('calendrier', $monthName, $year);
        
        return $this->output->render('calendar/month.php', array(
            'weeksInMonth' => $this->_weeksInMonth($month, $year),
            'days' => $this->days,
            'firstTimeOfMonth' => $firstTimeOfMonth,
            'firstTimeOfWeek' => $this->_firstTimeOfWeek($firstTimeOfMonth),
            'lastTimeOfMonth' => $lastTimeOfMonth,
            'monthName' => $monthName,
            'agenda' => $this->event->getEventAgenda($firstTimeOfMonth, $lastTimeOfMonth, $this->session->id),
            'month' => $month,
            'year' => $year
        ));
    }
    
    private function _firstTimeOfMonth($month, $year){
        return strtotime($year . '-' . $month . '-01');
    }
    
    private function _lastTimeOfMonth($month, $year){
        return strtotime($year . '-' . $month . '-' . $this->_daysInMonth($month, $year));
    }
    
    private function _firstTimeOfWeek($time){
        $dayNum = date('N', $time) - 1;
        return $time - $dayNum * 24 * 3600;
    }
    
    private function _startDay($month, $year){
        return date('N', strtotime($year . '-' . $month . '-01'));
    }
    
    private function _endDay($month, $year){
        return date('N', strtotime($year . '-' . $month . '-' . $this->_daysInMonth($month, $year)));
    }
    
    private function _weeksInMonth($month, $year){
        $daysInMonth = $this->_daysInMonth($month,$year);
        $numOfweeks = ($daysInMonth%7==0?0:1) + (int)($daysInMonth/7);
         
        $monthEndingDay= $this->_endDay($month, $year);
        $monthStartDay = $this->_startDay($month, $year);
         
        if($monthEndingDay<$monthStartDay)
            ++$numOfweeks;
         
        return $numOfweeks;
    }
    
    private function _daysInMonth($month, $year){
        return (int)date('t', strtotime($year . '-' . $month . '-01'));
    }
}
