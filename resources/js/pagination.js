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

(function(){
    var NB_PER_PAGE = 7;
    
    var Pagination = {
        articles: null,
        pagination: null
    };
    
    function displayArticles(page){
        Pagination.articles.css('display', 'none');
        
        for(var i = 0; i < NB_PER_PAGE; ++i){
            $(Pagination.articles.get((page - 1) * NB_PER_PAGE + i)).css('display', 'block');
        }
    }
    
    function changePage(page){
        displayArticles(page);
        $('#pagination .pagi_button').removeClass('current');
        $(Pagination.pagination.children().get(page - 1)).addClass('current');
    }
    
    $(document).ready(function(){
        Pagination.articles = $('article');
        
        if(Pagination.articles.size() > NB_PER_PAGE){
            Pagination.pagination = $('<div>');
            Pagination.pagination.attr('id', 'pagination');
            
            for(var page = 1; page <= Math.ceil(Pagination.articles.size() / NB_PER_PAGE); ++page){
                var $a = $('<a>');
                $a.html(page);
                $a.attr('href', '#');
                $a.click(function(){
                    changePage($(this).html());
                });
                $a.addClass('pagi_button');
                Pagination.pagination.append($a);
            }
            
            changePage(1);
            
            $('#contents').append(Pagination.pagination);
        }
    });
})();