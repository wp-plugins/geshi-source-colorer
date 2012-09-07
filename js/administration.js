/** 
 * #########################################################################
 * # GPL License                                                           #
 * #                                                                       #
 * # This file is part of the Wordpress GeSHi Source Colorer plugin.       #
 * # Copyright (c) 2012, Philipp Kraus, <philipp.kraus@flashpixx.de>       #
 * # This program is free software: you can redistribute it and/or modify  #
 * # it under the terms of the GNU General Public License as published by  #
 * # the Free Software Foundation, either version 3 of the License, or     #
 * # (at your option) any later version.                                   #
 * #                                                                       #
 * # This program is distributed in the hope that it will be useful,       #
 * # but WITHOUT ANY WARRANTY; without even the implied warranty of        #
 * # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         #
 * # GNU General Public License for more details.                          #
 * #                                                                       #
 * # You should have received a copy of the GNU General Public License     #
 * # along with this program.  If not, see <http://www.gnu.org/licenses/>. #
 * #########################################################################
 **/


jQuery(document).ready( function() {

    // hide some paragraphs
    jQuery(".geshisourcecolorer-option .invisible .form-table").hide();
                   
                       
    // click and hover event on the header
    jQuery(".geshisourcecolorer-option .invisible h3").hover( 
        function() { 
            jQuery(this).data("bg-before", jQuery(this).css("background-color") ); 
            jQuery(this).css("background-color", "#bababa");
        },
        function() { jQuery(this).css("background-color", jQuery(this).data("bg-before") ); }
    );
                       
    jQuery(".geshisourcecolorer-option .invisible h3").click( function() { 
        jQuery(this).parent().find(".form-table:first").toggle("slow");
    });

                       
    // create select field with parsed JSON object
    jQuery("#geshisourcecolorer-styleselect").append( function() {
        jQuery("#geshisourcecolorer-styleselect").data("obj", jQuery.parseJSON( jQuery("#jsonstyle").val() ) );
                                  
        jQuery.each( jQuery("#geshisourcecolorer-styleselect").data("obj"), function(key, val) {
            jQuery("#geshisourcecolorer-styleselect").append( jQuery("<option></option>").val(key).html(key) );
        });
        
        // read "default" call
        jQuery.each( jQuery("#geshisourcecolorer-styleselect").data("obj")["default"], function(key, val) { jQuery("#geshisourcecolorer-"+key).val(val); });
        jQuery("#geshisourcecolorer-stylename").val("default");
    });
                       
    
    // change function for reading JSON data
    jQuery("#geshisourcecolorer-styleselect").change( function() {
        var name = "";
        jQuery("#geshisourcecolorer-styleselect option:selected").each(function () { name = jQuery(this).val(); });
        jQuery.each( jQuery(this).data("obj")[name], function(key, val) { jQuery("#geshisourcecolorer-"+key).val(val); });
        jQuery("#geshisourcecolorer-stylename").val(name);
    });
             
                       
    // delete style
    jQuery("#geshisourcecolorer-styledelete").click( function() {
        var name = "";
        jQuery("#geshisourcecolorer-styleselect option:selected").each(function () { name = jQuery(this).val(); });
        
        if (name == "default")
            alert( geshisourcecolorer_messages.defaultdelete );
        else {
            delete jQuery("#geshisourcecolorer-styleselect").data("obj")[name];
                                                    
            // refill the select options
            jQuery("#geshisourcecolorer-styleselect").empty();
            jQuery.each( jQuery("#geshisourcecolorer-styleselect").data("obj"), function(key) {
                jQuery("#geshisourcecolorer-styleselect").append( jQuery("<option></option>").val(key).html(key) );
            });
                                                                                   
            // serialize object data to JSON
            jQuery("#jsonstyle").val( JSON.stringify( jQuery("#geshisourcecolorer-styleselect").data("obj") ) );
        }
    });
     
                       
    // save / overwrite style
    jQuery("#geshisourcecolorer-stylesave").click( function() {
        var name = "";
        jQuery("#geshisourcecolorer-styleselect option:selected").each(function () { name = jQuery(this).val(); });
                                                  
        name = prompt( geshisourcecolorer_messages.save, name );
        if (name != "") {
                                     
            // names always lower case
            name = name.toLowerCase();
                                                  
            // add / overwrite the data of the fields
            var obj = new Object();
            jQuery.each( jQuery("#geshisourcecolorer-styleselect").data("obj")["default"], function(key, val) { obj[key] = jQuery("#geshisourcecolorer-"+key).val(); });
            jQuery("#geshisourcecolorer-styleselect").data("obj")[name] = obj;
                                                  
            // refill the select options
            jQuery("#geshisourcecolorer-styleselect").empty();
            jQuery.each( jQuery("#geshisourcecolorer-styleselect").data("obj"), function(key) {
                var opt = jQuery("<option></option>").val(key).html(key);
                if (key == name)
                       opt.attr("selected", "selected");  
                jQuery("#geshisourcecolorer-styleselect").append( opt );
            });
                                                  
            // serialize object data to JSON
            jQuery("#jsonstyle").val( JSON.stringify( jQuery("#geshisourcecolorer-styleselect").data("obj") ) );
        }
    });
                       
    
    // preview call
    jQuery("#geshisourcecolorer-stylepreview").click( function() {
        var source = jQuery("#geshisourcecolorer-previewcode").val();
        var lang   = "";
        jQuery("#geshisourcecolorer-previewlanguage option:selected").each(function () { lang = jQuery(this).val(); });

        if (source == "")
            alert( geshisourcecolorer_messages.previewemptycode );
        else {
                         
            // get the current styles
            var obj = new Object();
            jQuery.each( jQuery("#geshisourcecolorer-styleselect").data("obj")["default"], function(key, val) { obj[key] = jQuery("#geshisourcecolorer-"+key).val(); });
                   
            // create Ajax request and add return data into the preview div
            jQuery.post(ajaxurl, { "action" : "geshisourcecolorer-preview", "style" : obj, "source" : source, "lang" : lang }, function(response) {
                jQuery("#geshisourcecolorer-preview").html(response);
            });
        }
                                                     
    });
                   
});
