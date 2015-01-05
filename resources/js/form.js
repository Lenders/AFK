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

var FormValidator = function(form, validateInputUrl, submitFormUrl){
    /// Attributes
    var _rules = {};
    var _errorHandler = [];

    /// Contructor
    console.log('FormValidator.constructor (' + typeof(form) + ', ' + validateInputUrl + ', ' + submitFormUrl + ')');
    
    if(!(form instanceof jQuery))
        form = $(form);

    form.submit(function(){
        $.post(submitFormUrl, _getData(), function(data){
            if(data.status !== 'SUCCESS'){
                _errorHandler.forEach(function(handler){
                    handler(data);
                });
            }else{
                window.location.replace(data.redirect);
                console.log('form : redirection ' + data.redirect);
            }
        }).fail(function(xhr){
            var win = window.open();
            win.document.write(xhr.responseText);
        });
        return false;
    });

    form.find('[name]').blur(function(){
        var rules = [];
        var $this = $(this);
        var name = $this.attr('name');
        var value = $this.val().trim();
        
        console.log('blur() ' + name);

        $this.removeClass('error');
        $this.removeClass('valid');

        if(_rules[name] != undefined)
            rules = _rules[name];

        var ok = true;

        //test all rules for the input
        for(var i in rules){
            var rule = rules[i];
            var b = rule.validate(value);

            if(!b){
                var msg = {fields: {}};
                msg.fields[name] = rule.error;
                _errorHandler.forEach(function(handler){
                    handler(msg);
                });
                ok = false;
                break;
            }
        }

        //if there is no problems, check distant
        if(ok && validateInputUrl){
            $.post(FormValidator.getUrl(validateInputUrl, name), _getData(), function(data){
                _errorHandler.forEach(function(handler){
                    handler(data);
                });
            }).fail(function(xhr){
                if(xhr.status == 500){
                    var win = window.open();
                    win.document.write(xhr.responseText);
                }
            });
        }
    });
    
    _errorHandler.push(function(data){
        if(data.status !== 'SUCCESS' && data.message){
            $('#form_error').show();
            $('#form_error').html(data.message);
        }
        
        if(data.fields){
            $.each(data.fields, function(field, msg){
                var $field = $('[name="' + field + '"]');
                
                if(msg === 'SUCCESS'){
                    $field.removeClass('error');
                    $field.addClass('valid');
                    $field.attr('title', '');
                }else{
                    $field.removeClass('valid');
                    $field.addClass('error');
                    $field.attr('title', msg);
                }
            });
        }
    });
    
    /// Private methods
    function _getData(){
        var data = {};
        
        form.find('[name]').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        
        return data;
    }
    
    /// Public methods

    /**
     * @param {jQuery} input
     * @param {FormValidator.Rule} rule
     * @returns {undefined}
     */
    this.addRule = function(input, rule){
        if(!(rule instanceof FormValidator.Rule))
            throw "FormValidator.addRule() : rule should be type of FormValidator.Rule";

        if(!(input instanceof jQuery))
            input = $(input);
        
        console.log('FormValidator.addRule(' + input.attr('name') + ', ' + typeof(rule) + ')');

        if(_rules[input.attr('name')] == undefined)
            _rules[input.attr('name')] = [];

        _rules[input.attr('name')].push(rule);
    };
    
    this.errorHandler = function(handler){
        _errorHandler.push(handler);
        console.log(_errorHandler);
    };
};

(function(){    
    FormValidator.getUrl = function(base, input){
        return base + '/' + input;
    };
    
    /**
     * Get the related label of input field
     * @param {jQuery} input
     * @returns {jQuery}
     */
    FormValidator.getLabel = function(input){
        if(!(input instanceof jQuery))
            input = $(input);
        
        return $('label[for="' + input.attr('id') + '"]');
    };
    
    FormValidator.Rule = function(){};
    /**
     * Validate the value
     * @param {String} value
     * @returns {Boolean} true if is valid
     */
    FormValidator.Rule.prototype.validate = function(value){
        throw 'You should implements FormValidator.Rule.validate()';
    };
    FormValidator.Rule.prototype.error = '';
    
    FormValidator.DummyRule = function(){};
    FormValidator.DummyRule.prototype = Object.create(FormValidator.Rule.prototype);
    FormValidator.DummyRule.prototype.validate = function(){ return true; };
    
    FormValidator.RegexRule = function(regex, message){
        if(message == undefined)
            message = 'Champ invalide';
        
        this.validate = function(value){
            console.log('regex testing');
            var b = regex.test(value);
            
            if(!b)
                this.error = message;
            
            return b;
        };
    };
    FormValidator.RegexRule.prototype = Object.create(FormValidator.Rule.prototype);
    FormValidator.RegexRule.prototype.constructor = FormValidator.RegexRule;
    
    FormValidator.RequiredRule = function(){
        this.validate = function(value){
            this.error = 'Champ requis';
            return value.length > 0;
        };
    };
    FormValidator.RequiredRule.prototype = Object.create(FormValidator.Rule.prototype);
    FormValidator.RequiredRule.prototype.constructor = FormValidator.RequiredRule;
    
    FormValidator.LengthRule = function(min, max){
        this.validate = function(value){
            if(value.length < min){
                this.error = 'Le champ doit faire plus de ' + min + ' caractères';
                return false;
            }
            
            if(value.length > max){
                this.error = 'Le champ doit faire moins de ' + max + ' caractères';
                return false;
            }
            
            return true;
        };
    };
    FormValidator.LengthRule.prototype = Object.create(FormValidator.Rule.prototype);
    FormValidator.LengthRule.prototype.constructor = FormValidator.LengthRule;
    
    FormValidator.EqualOtherRule = function(other){
        if(!(other instanceof jQuery))
            other = $(other);
        
        this.validate = function(value){
            this.error = 'La champ doit être identique à ' + FormValidator.getLabel(other).html();
            return value == other.val().trim();
        };
    };
    FormValidator.EqualOtherRule.prototype = Object.create(FormValidator.Rule.prototype);
    FormValidator.EqualOtherRule.prototype.constructor = FormValidator.EqualOtherRule;
})();