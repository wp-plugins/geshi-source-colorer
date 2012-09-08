<?php
/**
Plugin Name: GeSHi Source Colorer
Plugin URI: http://wordpress.org/extend/plugins/geshi-source-colorer
Author URI: http://flashpixx.de
Description: The plugin can colorize any source in a post or page. Layout and tag names can be set dynamically, so a migration of other plugins is possible
Author: flashpixx
Version: 0.11
 
 
#########################################################################
# GPL License                                                           #
#                                                                       #
# This file is part of the Wordpress GeSHi Source Colorer plugin.       #
# Copyright (c) 2012, Philipp Kraus, <philipp.kraus@flashpixx.de>       #
# This program is free software: you can redistribute it and/or modify  #
# it under the terms of the GNU General Public License as published by  #
# the Free Software Foundation, either version 3 of the License, or     #
# (at your option) any later version.                                   #
#                                                                       #
# This program is distributed in the hope that it will be useful,       #
# but WITHOUT ANY WARRANTY; without even the implied warranty of        #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         #
# GNU General Public License for more details.                          #
#                                                                       #
# You should have received a copy of the GNU General Public License     #
# along with this program.  If not, see <http://www.gnu.org/licenses/>. #
#########################################################################
**/

namespace de\flashpixx\geshisourcecolorer;

// ==== constant for developing with the correct path of the plugin ================================================================================
define(__NAMESPACE__."\LOCALPLUGINFILE", __FILE__);
//define(__NAMESPACE__."\LOCALPLUGINFILE", WP_PLUGIN_DIR."/geshi-source-colorer/".basename(__FILE__));
define(__NAMESPACE__."\DEBUG", false);
// =================================================================================================================================================



// ==== plugin initialization ======================================================================================================================
@require_once("import.class.php");
@require_once("render.class.php");
@require_once("filter.class.php");
@require_once("external/geshi.php");
    
// stop direct call
if (preg_match("#" . basename(LOCALPLUGINFILE) . "#", $_SERVER["PHP_SELF"])) { die("You are not allowed to call this page directly."); }

// translation
if (function_exists("load_plugin_textdomain"))
    load_plugin_textdomain("fpx_geshisourcecolorer", false, dirname(plugin_basename(LOCALPLUGINFILE))."/lang");
// =================================================================================================================================================    


    
// ==== create Wordpress Hooks =====================================================================================================================
add_action("wp_head", "de\\flashpixx\\geshisourcecolorer\\initHead");
add_action("wp_enqueue_scripts", "de\\flashpixx\\geshisourcecolorer\\initScripts");
add_action("admin_enqueue_scripts", "de\\flashpixx\\geshisourcecolorer\\initAdminScripts");

add_action("wp_ajax_geshisourcecolorer-preview", "de\\flashpixx\\geshisourcecolorer\\filter::preview");
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runListOfListings");
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runCode");
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runCodeBefore", -1000);
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runCodeLine");
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runCodeLineBefore", -1000);
    
add_action("admin_menu", "de\\flashpixx\\geshisourcecolorer\\render::adminmenu");
add_action("admin_init", "de\\flashpixx\\geshisourcecolorer\\render::optionfields");
        
register_activation_hook(LOCALPLUGINFILE, "de\\flashpixx\\geshisourcecolorer\\install");
register_uninstall_hook(LOCALPLUGINFILE, "de\\flashpixx\\geshisourcecolorer\\uninstall");
// =================================================================================================================================================




// ==== administration function ====================================================================================================================
    
/** plugin page initialization **/
function initScripts()
{ 
    // jQuery and function script
    wp_register_script( "geshisourcecolorer_zeroclipboard", plugins_url("external/zeroclipboard/ZeroClipboard.min.js", LOCALPLUGINFILE) );
    wp_register_script( "geshisourcecolorer_function", plugins_url("js/function".(DEBUG ? null : ".min").".js", LOCALPLUGINFILE), array("jquery", "geshisourcecolorer_zeroclipboard") );
    
    wp_enqueue_script( "jquery" );
    wp_enqueue_script( "geshisourcecolorer_zeroclipboard" );
    wp_enqueue_script( "geshisourcecolorer_function" );

    
    // CSS script
    wp_register_style( "geshisourcecolorer_style", plugins_url("css/layout".(DEBUG ? null : ".min").".css", LOCALPLUGINFILE) );
    
    // include only this files, which are needed
    $option = get_option("fpx_geshisourcecolorer_option");
    if ($option["maincss"])
        wp_enqueue_style( "geshisourcecolorer_style" );
}
    
    
/** plugin administration page initialization **/
function initAdminScripts()
{
    // jQuery and function script
    wp_register_script( "geshisourcecolorer_administration", plugins_url("js/administration".(DEBUG ? null : ".min").".js", LOCALPLUGINFILE), array("jquery") );
        
    wp_enqueue_script( "geshisourcecolorer_administration" );

    wp_localize_script( "geshisourcecolorer_administration", "geshisourcecolorer_messages", array(
        "defaultdelete"      => __("default style can not be deleted", "fpx_geshisourcecolorer"),
        "save"               => __("save this style under a new name or use the same name for overriding", "fpx_geshisourcecolorer"),
        "previewemptycode"   => __("The preview code is empty. Please add some code lines", "fpx_geshisourcecolorer")
    ));
    
    
    
    // CSS script
    wp_register_style( "geshisourcecolorer_adminstyle", plugins_url("css/administration".(DEBUG ? null : ".min").".css", LOCALPLUGINFILE) );
    
    wp_enqueue_style( "geshisourcecolorer_adminstyle" );
}
    
    
/** plugin init head call **/
function initHead()
{
    echo "<script type=\"text/javascript\">";
    echo "ZeroClipboard.setMoviePath(\"".plugins_url("external/zeroclipboard/ZeroClipboard.swf", LOCALPLUGINFILE)."\");";
    echo "</script>";
}
   
    
/** create the default options **/
function install()
{
    $lxConfig = get_option("fpx_geshisourcecolorer_option");
    if (empty($lxConfig))
        update_option("fpx_geshisourcecolorer_option",
            
            array(
                
                "geshicss"          => true,
                "maincss"           => true,
                "keywordref"        => false,
                "tabsize"           => 4,
                "linenumber"        => true,
                "collapse"          => false,
                "collapsetext"      => __("view source code", "fpx_geshisourcecolorer"),
                "lolheadtext"       => __("source contents", "fpx_geshisourcecolorer"),
                "copytext"          => __("source is copied to clipboard", "fpx_geshisourcecolorer"),

                // options of the WP tags
                "tag"               => array(
                        "code"          => "[cc%s%p]%c[/cc]",
                        "line"          => "[cci%s%p]%c[/cci]",
                        "language"      => "lang"
                ),
                  
                // option of the toolbar
                "toolbar"           => array(
                                             
                        "block"         => array(
                            "sourcewindow"    => true,
                            "copyclipboard"   => true,
                            "linenumber"      => true
                        ),
                                             
                        "line"          => array(
                            "sourcewindow"    => false,
                            "copyclipboard"   => true,
                        )
                ),
                  
                // style options of the codes
                // like: array ("matlab" => array())
                "style"             => array(
                
                        "default"  => array(
                            "keywords"          => "color: #c858b5;",
                            "comments"          => "color: #148d14; font-style: italic",
                            "escapechars"       => "color: #3034d9;",
                            "symbols"           => "",
                            "numbers"           => "color: #5458e0;",
                            "strings"           => "color: #0080A0;",
                            "methods"           => "color: #017788",
                            "regex"             => "",
                            "highlight"         => "background-color: #c2deff",
                            "keywordhover"      => "",
                            "keywordlink"       => "",
                            "keywordactive"     => "",
                            "keywordvisited"    => ""
                        )
                  
                )
            )
                      
        );

}
    
    
/** uninstall functions **/
function uninstall()
{
    unregister_setting("fpx_geshisourcecolorer_option", "fpx_geshisourcecolorer_option");
    delete_option("fpx_geshisourcecolorer_option");
}

// =================================================================================================================================================
    
?>