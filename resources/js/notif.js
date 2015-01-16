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


(function(){
    var _base_title = document.title;
    function loadNotif(){
        
        $.get(Config.getBaseUrl() + 'json/notif', function(data){
            var total = 0;
            
            $.each(data, function(name, value){
                var $elem = $('[data-notif="' + name + '"]');
                if(value > 0){
                    $elem.html(value);
                    $elem.show();
                }else{
                    $elem.hide();
                }
                
                total += parseInt(value);
            });
            
            var title = _base_title;
            
            if(total > 0)
                title += ' (' + total + ')';
            
            document.title = title;
            setTimeout(loadNotif, 10000);
        }).fail(function(xhr){
            setTimeout(loadNotif, 30000);
        });
    }
    $(document).ready(function(){
        setTimeout(loadNotif, 10000);
    });
})();