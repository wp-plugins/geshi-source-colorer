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
                       
    // first we hide all toolbars (CSS style visibility:hidden does not work with fade)
    jQuery(".geshisourcecolorer .toolbar").hide();


    // hide all collapsed code blocks
    jQuery(".geshisourcecolorer-collapse").hide();


    // click function for hide / view collapsed source code
    jQuery(".geshisourcecolorer-collapse-button").click( function () {
           var id = "#"+jQuery(this).attr("id").replace("-collapse-button", "");
           
           if (!jQuery(this).data("view"))
           {
               jQuery(id).fadeIn("slow");
               jQuery(this).data("view", true);
           } else {
               jQuery(id).fadeOut("slow");
               jQuery(this).data("view", false);
           }
    });
              
                       
    // setup a hover to the code div and add the callback functions to the toolbar within
    jQuery(".geshisourcecolorer").hover(
        function() { 
            jQuery(this).find(".toolbar:first").fadeIn("slow");
            jQuery( "#"+jQuery(this).attr("id")+"-copyclipboard" ).data("clipboard").show();
        },
        
        function() { 
            jQuery( "#"+jQuery(this).attr("id")+"-copyclipboard" ).data("clipboard").hide();
            jQuery(this).find(".toolbar:first").fadeOut("slow");
        }
    );
              
                       
    // click function to toggle line numbers
    jQuery(".geshisourcecolorer .toolbar .togglelinenumber").click( function () { 
        var lines =  jQuery(this).parent().parent().parent().find("ol");
        if (lines.css("list-style-type") == "none") {
            lines.css("list-style-type", lines.data("list-style-type-before") );
            lines.find("li").each( function() {
                jQuery(this).css("margin-left", jQuery(this).data("margin-left-before") );
            });
        } else {
            lines.data("margin-left-before", lines.css("margin-left"));
            lines.data("list-style-type-before", lines.css("list-style-type"));
            lines.css("list-style-type", "none");
            lines.find("li").each( function() { 
                jQuery(this).data("margin-left-before", jQuery(this).css("margin-left") );
                jQuery(this).css("margin-left", 0);
            });
        }
    });
         
                       
    // copy clipboard (we create on each code block a clipboard access object on a hidden div,
    // because on an element, that is hovered the flash movie don't access the trigger click,
    // if now a click triggered on the copy button, we trigger the click event to the hidden div,
    // on which the clipboard is bounded
    jQuery(".geshisourcecolorer .toolbar .copyclipboard").each( function () {
        var id  = "#"+jQuery(this).attr("id");
        var msg = jQuery(this).attr("rel");
                                                               
        jQuery(id).data("clipboard", new ZeroClipboard.Client() );
        jQuery(id).data("clipboard").setText( jQuery(this).parent().parent().find(".source:first").text() );
        jQuery(id).data("clipboard").addEventListener( "onComplete", function() { alert(msg); });
        jQuery(id).data("clipboard").reposition();
                                                               
        jQuery(id).append( jQuery(id).data("clipboard").getHTML( jQuery(this).width(), jQuery(this).height() ) );
    });

                       
    // click function to create a new window with blank source code
    jQuery(".geshisourcecolorer .toolbar .sourcewindow").click( function () {
        var win   = window.open("", "GeSHi Source Colorer");
        win.document.writeln( "<html><body><pre>"+jQuery(this).parent().parent().find(".source").html()+"</pre></body></html>" );
    });
           
                       
    // load the "list of listings", grep all a name tags within the geshisourcecolorer blocks
    // and add the names to the list (create an "empty / span" because an empty value can not call append
    // we return only the content of the span tag)
    jQuery("<ul />").append( function(index, html){
        var ret = jQuery("<span />");
        jQuery(".geshisourcecolorer").find(".lol[rel]").each( function() { 
            ret.append( jQuery("<li />").html( jQuery("<a />").attr("href", "#"+jQuery(this).attr("name")).html(jQuery(this).attr("rel")) ));
        });
        return ret.html();
    }).appendTo("#geshisourcecolorer-lol");
                       
                       
    // add highlighthover functions (seperate the data of the code block, get the unhovered styles of the elements and set the hover function)
    jQuery(".geshisourcecolorer .hoverhighlight").each( function() {
        var id    = "#" + jQuery(this).parent().parent().attr("id")+ "-";
        var data  = jQuery(this).attr("rel").split("[").map( function(i) { return jQuery.trim(i.replace("]", "")); } );

        if (data.length == 3) 
        {
            data[2] = data[2].split(" ").map( function(i) { return id + jQuery.trim(i); } );
            jQuery(data[0]).hover(
                function () { data[2].forEach( function(i) { jQuery(i).data("style-before", jQuery(i).attr("style")); jQuery(i).attr("style", data[1]); }); },
                function () { data[2].forEach( function(i) { jQuery(i).attr("style", jQuery(i).data("style-before")); }); }
            );
        }
    });

});