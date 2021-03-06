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
    $(document).ready(function(){
        var speedSearch = new AutoCompletion('#speed_search input[name="search"]');
        
        var userAutocomplete = new AutoCompletion.Handler('user_autocomplete', function(value){
            return Config.getBaseUrl() + 'autocomplete/user/' + encodeURIComponent(value) + '.json';
        });
        
        var eventAutocomplete = new AutoCompletion.Handler('event_autocomplete', function(value){
            return Config.getBaseUrl() + 'autocomplete/event/' + encodeURIComponent(value) + '.json';
        });
        
        speedSearch.addHandler(userAutocomplete);
        speedSearch.addHandler(eventAutocomplete);
        
        var $glob = $('#glob_search');
        
        if($glob.size() > 0){
            var globSearch = new AutoCompletion($glob);
            globSearch.addHandler(userAutocomplete);
            globSearch.addHandler(eventAutocomplete);
        }
        
        var $user = $('#user_search');
        
        if($user.size() > 0){
            var userSearch = new AutoCompletion($user);
            userSearch.addHandler(userAutocomplete);
        }
        
        var $event = $('#evt_search');
        
        if($event.size() > 0){
            var userSearch = new AutoCompletion($event);
            userSearch.addHandler(eventAutocomplete);
        }
    });
})();
