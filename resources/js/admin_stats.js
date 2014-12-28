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

var AdminStats = {};

(function(){
    google.load("visualization", "1", {packages:["corechart"]});
    
    var _charts = [];
    
    AdminStats.Chart = function(typeName, target, dataUrl, options){
        if(options === undefined)
            options = {};
        
        options.backgroundColor = '#333';
        options.titleTextStyle = {
            color: '#EEE'
        };
        options.legend = {textStyle: {color: '#EEE'}};
        options.hAxis = options.legend;
        options.vAxis = options.legend;
        
        this.draw = function(){
            $.get(dataUrl, function(data){
                var array = [
                    ['Titre', 'Effectif']
                ];

                data.forEach(function(elem){
                    array.push([elem['TITLE'], parseInt(elem['EFFECTIVE'])]);
                });

                var chart = new google.visualization[typeName](target);
                chart.draw(google.visualization.arrayToDataTable(array), options);
            });
        };
    };
    
    AdminStats.addChart = function(chart){
        if(!(chart instanceof AdminStats.Chart))
            throw 'AdminStats.addChart() : the chart should be instance of AdminStats.Chart()';
        
        _charts.push(chart);
    };
    
    AdminStats.drawAll = function(){
        google.setOnLoadCallback(function(){
            _charts.forEach(function(chart){
                chart.draw();
            });
        });
    };
    
    AdminStats.loadAll = function(){
        $('[data-chart-type]').each(function(){
            var $this = $(this);
            var chart = new AdminStats.Chart($this.data('chart-type'), this, $this.data('data-url'), $this.data('chart-options'));
            
            AdminStats.addChart(chart);
        });
    };
})();

$(document).ready(function(){
    AdminStats.loadAll();
    AdminStats.drawAll();
});