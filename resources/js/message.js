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

var Message = {};

(function(){
    Message.upateDiscussionList = function(data){
        var $list = $('#discussions_list');
        var current_url = $('#discussions_list .current a').attr('href');
        $list.empty();
        
        data.forEach(function(discu){
            console.log(discu);
            var url = Message.generateUrl(discu.id);
            
            var $li = $('<li>');
            
            if(url == current_url)
                $li.addClass('current');
            
            if(!discu.view)
                $li.addClass('unread');
            
            var $a = $('<a>');
            $a.attr('href', url);
            $a.html(discu.name);
            $li.html($a);
            $list.append($li);
        });
    };
    
    Message.checkDiscussionList = function(){
        $.get(Config.getBaseUrl() + 'message/discussionlist.json', function(data){
            Message.upateDiscussionList(data);
            setTimeout(Message.checkDiscussionList, 3000);
        }).fail(function(xhr){
            console.log(xhr.error);
            setTimeout(Message.checkDiscussionList, 10000);
        });
    };
    
    Message.generateUrl = function(id){
        return Config.getBaseUrl() + 'message/discussion/' + encodeURIComponent(id) + '#last';
    };
    
    $(document).ready(function(){
        setTimeout(Message.checkDiscussionList, 3000);
    });
})();