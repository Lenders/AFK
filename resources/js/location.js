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


var Location = {};

(function(){
    /**
     * The current location
     * @type Location.Coordinates
     */
    var _location = Cache.get('location');
    
    var _getLocationByIP = function(callback){
        console.log('Using IP to get geoloc');
        $.ajax({
            url: 'http://ipinfo.io/json'
        }).done(function(data){
            var loc = data.loc.split(',', 2);
            _location = {latitude:loc[0], longitude: loc[1]};
            Cache.store('location', _location);
            callback(_location);
        });
    };
    
    /**
     * get the current location
     * @param {function} callback action to do
     */
    Location.getLocation = function(callback){
        if(_location !== null)
            callback(_location);
        else{
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(function(loc){
                    _location = loc.coords
                    console.log('Geoloc found : lat:' + _location.latitude + ', long:' + _location.longitude);
                    Cache.store('location', _location);
                    callback(_location);
                }, function(error){
                    console.warn('Error using navigator.geolocation (' + error.code + ')');
                    _getLocationByIP(callback);
                });
            }else{
                console.warn('Navigator do not support HTML5 geoloc');
                _getLocationByIP(callback);
            }
        }
    };
    
    var _geoCode = Cache.get('geocode');
    
    var _loadGeoCode = function (callback){
        console.log('Loading GeoCode');
        Location.getLocation(function(loc){
            $.ajax({
                url: 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' + loc.latitude + ',' + loc.longitude
            }).done(function(data){
                console.log('GeoCode found !');
                _geoCode = data;
                Cache.store('geocode', data);
                callback(_geoCode);
            });
        });
    };
    
    Location.getGeoCode = function (callback){
        if(_geoCode === null)
            _loadGeoCode(callback);
        else
            callback(_geoCode);
    };
    
    Location.getLocality = function(callback){
        Location.getGeoCode(function(geocode){
            var components = geocode.results[0].address_components;
            
            var locality = null;
            
            for(var key in components){
                var component = components[key];
                if(component.types.indexOf("locality") != -1){
                    locality = component.long_name;
                    console.log('Locality found : ' + locality);
                    break;
                }
            }
            
            callback(locality);
        });
    };
    
    Location.clearCache = function(){
        _location = null;
        _geoCode = null;
        Cache.remove('location');
        Cache.remove('geocode');
    };

    $(document).ready(function(){
        Location.getLocality(function(locality){
            $('#loc_value').html(locality);
        });
        
        $('#loc_value').click(function(){
            Location.clearCache();
            $('#loc_value').html('inconnue');
            Location.getLocality(function(locality){
                $('#loc_value').html(locality);
            });
            return false;
        });
    });
})();