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
    function _getNewRowButton(){
        var $row = $('<tr>');
        $row.append('<td><label for="select_hidden_row">Nouvelle ligne</label></td>');
        
        var $select = $('<select id="select_hidden_row">');
        $select.css('display', 'inline-block');
        $select.css('width', '70%');
        
        var rows = _getHiddenRows();
        
        rows.forEach(function(name){
            $select.append('<option value="' + name + '">' + name + '</option>');
        });
        
        var $button = $('<button>');
        $button.html('Ajouter');
        $button.addClass('button');
        $button.css('display', 'inline-block');
        $button.css('width', '25%');
        $button.css('float', 'right');
        
        $button.click(function(){
            $('[data-name="' + $select.val() + '"]').detach().insertBefore($select.parents('tr')).show(800);
            $select.find('[value="' + $select.val() + '"]').remove();
            
            if($select.children().size() == 0)
                $row.remove();
            
            return false;
        });
        
        var $td = $('<td>');
        $td.append($select);
        $td.append($button);
        $row.append($td);
        
        return $row;
    }
    
    function _getHiddenRows(){
        var rows = [];
        
        $('[data-name]').each(function(){
            var $this = $(this);
            
            if($this.css('display') === 'none'){
                rows.push($this.data('name'));
            }
        });
        
        return rows;
    }
    
    $(document).ready(function(){
        $('[data-name]').each(function(){
            var $this = $(this);

            if($this.find('input').attr('required') === undefined)
                $this.hide();
        });

        $('#create_event_form > table').append(_getNewRowButton());
        
        $('[data-name]').each(function(){
            var $this = $(this);
            
            var auto = new AutoCompletion($this.find('input'));
            auto.addHandler(
                new AutoCompletion.Handler(
                    'event_prop_' + $this.data('name'),
                    function(value){
                        return Config.getBaseUrl() + 'autocomplete/eventproperty/' + $this.data('name') + '/' + encodeURIComponent(value) + '.json';
                    }
            ));
        });
    });
})();