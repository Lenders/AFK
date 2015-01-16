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

var Cache = {};

(function(){
    var _storage = sessionStorage ? sessionStorage : new (function(){
        console.warn('sessionStorage not found !');
        console.log('Using cookies');
        this.getItem = function(item){
            var cookies = document.cookie;
            var data = cookies.split('; ');
            
            for(var i in data){
                var cookie = data[i].split('=');
                
                if(cookie[0] == item)
                    return cookie[1];
            }
            
            return null;
        };
        
        this.setItem = function(item, value){
            document.cookie = item + '=' + value;
        };
        
        this.removeItem = function(item){
            document.cookie = item + '=';
        };
    })();
    
    Cache.store = function(key, value){
        console.log('Cache.store(' + key + ')');
        _storage.setItem(key, JSON.stringify(value));
    };
    
    Cache.get = function(key){
        console.log('Cache.get(' + key + ')');
        return JSON.parse(_storage.getItem(key));
    };
    
    Cache.remove = function(key){
        console.log('Cache.remove(' + key + ')');
        _storage.removeItem(key);
    };
})();

var AutoCompletion = function(input){
    var _handlers = [];
    
    if(!(input instanceof jQuery))
        input = $(input);
    
    input.attr('autocomplete', 'off');
    
    input.on('input', function(){
        var results = [];
        var waiting = _handlers.length;
        
        var $input = $(this);
        var terms = $input.val();
        
        _handlers.forEach(function(handler){
            
            var cache_key = handler.getName() + '_' + window.btoa(terms.toLowerCase());
            var data = Cache.get(cache_key);
            
            if(data == null){
                $.ajax({
                    url: handler.getUrl(terms)
                }).done(function(data){
                    data = handler.parseData(data);
                    Cache.store(cache_key, data);

                    for(var j in data){
                        results.push(data[j]);
                    }

                    --waiting;

                    if(waiting == 0){
                        AutoCompletion.displayData($input, results);
                    }
                }).fail(function(xhr){
                    if(xhr.statusCode == 500){
                        var win = window.open();
                        win.document.write(xhr.responseText);
                    }
                });
            }else{
                for(var j in data){
                    results.push(data[j]);
                }

                --waiting;

                if(waiting == 0){
                    AutoCompletion.displayData($input, results);
                }
            }
        });
    });
    
    /**
     * Add an handler to the AutoCompletion object
     * @param {AutoCompletion.Handler} handler
     * @returns {undefined}
     */
    this.addHandler = function(handler){
        if(!handler instanceof AutoCompletion.Handler)
            throw 'AutoCompletion.addHandler() : handler should be instance of AutoCompletion.Handler';
        
        _handlers.push(handler);
    };
};

(function(){
    var _list = null;
    
    AutoCompletion.removeList = function(){
        if(_list != null)
            _list.remove();
    };
    
    AutoCompletion.displayData = function(input, data){
        AutoCompletion.removeList();
        
        if(data.length < 1)
            return;
        
        _list = new ActiveList();
        
        $('*').not([_list.getjQuery(), input]).click(function(){
            AutoCompletion.removeList();
        });
        
        _list.onSelect(function(item){
            input.val(item.html());
            input.focus();
            AutoCompletion.removeList();
        });
        
        _list.onFocus(function(item){
            input.blur();
        });
        
        _list.onUnfocus(function(){
            input.focus();
        });
        
        data.forEach(function(item){
            _list.addItem(item);
        });
        
        var $list = _list.getjQuery();
        $list.attr('id', 'autocomplete_list');
        
        $('body').append($list);
        
        var offset = input.offset();
        offset.top += input.outerHeight();
        $list.width(input.outerWidth() + $list.innerWidth() - $list.outerWidth());
        $list.offset(offset);
    };
    
    AutoCompletion.Handler = function(name, urlParser, dataParser){
        if(dataParser == undefined){
            dataParser = function(data){
                return data;
            };
        }
        
        this.getName = function(){
            return name;
        };
        
        this.getUrl = function(terms){
            return urlParser(terms);
        };
        
        this.parseData = function(data){
            return dataParser(data);
        };
    };
})();

var ActiveList = {};

(function(){
    var __UID = 0;
    
    ActiveList = function(){
        console.log('ActiveList.constructor');
        var _$ = $('<ul>');
        var _items = [];
        var _onselect = [];
        var _onfocus = [];
        var _onunfocus = [];
        var _focused = null;
        var _ = this;

        var _id = __UID;

        ++__UID;

        /**
         * Get the jQuery object of the list
         * @returns {$}
         */
        this.getjQuery = function(){
            return _$;
        };

        this.addItem = function(item){
            var $item = $('<li>');
            $item.html(item);

            $item.hover(function(){
                setFocused($item);
            });

            $item.click(function(){
                select($(this));
            });

            _items.push($item);
            _$.append($item);
        };

        this.onSelect = function(callback){
            _onselect.push(callback);
        };

        this.onFocus = function(callback){
            _onfocus.push(callback);
        };

        function select(item){
            if(item == null)
                return;

            _onselect.forEach(function(elem){
                elem(item);
            });
        }

        this.onUnfocus = function(callback){
            _onunfocus.push(callback);
        };

        function setFocused(focused){
            _focused = focused;

            _items.forEach(function(item){
                item.removeClass('focus');
            });

            if(_focused != null){
                _focused.addClass('focus');
                _onfocus.forEach(function(callback){
                    callback(_focused);
                });
            }else{
                _onunfocus.forEach(function (callback){
                    callback();
                });
            }
        }

        this.focusNext = function(){
            var i = -1;

            if(_focused != null)
                i = _items.indexOf(_focused);

            ++i;

            if(i >= _items.length){
                setFocused(null);
            }else{
                setFocused(_items[i]);
            }
        };

        this.focusPrev = function(){
            var i = _items.length;

            if(_focused != null)
                i = _items.indexOf(_focused);

            --i;

            if(i < 0){
                setFocused(null)
            }else{
                setFocused(_items[i]);
            }
        };

        $(document).bind('keydown.activelist_' + _id, function(event){
            var key = event.keyCode ? event.keyCode : event.which;
            console.log('key event : ' + key);

            if(key == 40){ //down
                _.focusNext();
                return false;
            }else if(key == 38){ //up
                _.focusPrev();
                return false;
            }else{
                select(_focused);
            }
        });

        this.remove = function(){
            _items.forEach(function(item){
                item.remove();
            });
            _$.remove();
            $(document).unbind('keydown.activelist_' + _id);
        };
    };
})();

$(document).ready(function(){
    if(screen.width < 700){
        var $menu = $('#left_menu');

        if($menu.size() > 0){
            $menu.hide();

            var $button = $('<button>');
            $button.attr('id', 'button_menu');
            $button.addClass('open');

            var opened = false;

            $button.click(function(){
                if(!opened){
                    $menu.show();
                    $button.removeClass('open');
                    $button.addClass('close');
                    opened = true;
                }else{
                    $menu.hide();
                    $button.removeClass('close');
                    $button.addClass('open');
                    opened = false;
                }
            });

            $(body).append($button);
        }
    }else{
        $('#left_menu').css('display', 'table-cell');
        $('#button_menu').remove();
    }
    
    var $top = $('#button_top');
    $top.hide();
    $top.click(function(){
        $('body, html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
    
    $(window).scroll(function(){
        if($(window).scrollTop() > 150){
            $top.fadeIn(500);
        }else{
            $top.fadeOut(500);
        }
    });
});