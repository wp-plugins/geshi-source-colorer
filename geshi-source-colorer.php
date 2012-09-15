<?php
/**
Plugin Name: GeSHi Source Colorer
Plugin URI: http://wordpress.org/extend/plugins/geshi-source-colorer
Author URI: http://flashpixx.de
Description: The plugin can colorize any source in a post or page. There are a lot of possiblities to configurate your code designs.
Author: flashpixx
Version: 0.12
 
 
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
define(__NAMESPACE__."\MINIFY", true);
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
add_action("init", "de\\flashpixx\\geshisourcecolorer\\initScripts");
add_filter("the_posts", "de\\flashpixx\\geshisourcecolorer\\activateScripts");

add_action("wp_ajax_geshisourcecolorer-preview", "de\\flashpixx\\geshisourcecolorer\\filter::preview");
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runListOfListings");
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runCodeLineBefore", -1000);
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runCodeBefore", -1000);
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runCodeLine");
add_filter("the_content", "de\\flashpixx\\geshisourcecolorer\\filter::runCode");

add_action("admin_enqueue_scripts", "de\\flashpixx\\geshisourcecolorer\\initAdminScripts");
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
    wp_register_script( "geshisourcecolorer_function", plugins_url("js/function".(MINIFY ? ".min" : null).".js", LOCALPLUGINFILE), array("jquery", "geshisourcecolorer_zeroclipboard") );
    wp_register_style( "geshisourcecolorer_userstyle", get_template_directory_uri()."/geshi-source-colorer.css" );
    
    // CSS script
    wp_register_style( "geshisourcecolorer_style", plugins_url("css/layout".(MINIFY ? ".min" : null).".css", LOCALPLUGINFILE) );
    wp_register_script( "geshisourcecolorer_userfunction", get_template_directory_uri()."/geshi-source-colorer.js", array("geshisourcecolorer_function" ) );
}
    
    
/** set up the scripts only if needed
 * @param $posts post array
 **/
function activateScripts($posts)
{
    // disable scripts on admin pages or if post is empty
    if ( (is_admin()) || (empty($posts)) )
        return $posts;
    
    // check the posts if the filter shortcut it found
    $option = get_option("fpx_geshisourcecolorer_option");
    foreach($posts as $post)
        if ( (preg_match( filter::createFilterRegEx($option["tag"]["code"]), $post->post_content )) || (preg_match( filter::createFilterRegEx($option["tag"]["line"]), $post->post_content )) )
        {
            add_action("wp_head", "de\\flashpixx\\geshisourcecolorer\\initHead");
            
            wp_enqueue_script( "jquery" );
            wp_enqueue_script( "geshisourcecolorer_zeroclipboard" );
            wp_enqueue_script( "geshisourcecolorer_function" );
            
            if ($option["maincss"])
                wp_enqueue_style( "geshisourcecolorer_style" );
            
            // check theme files, if there is a "geshi-source-colorer.js" and/or a "geshi-source-colorer.css" within the theme directory, we include the files
            $css = get_theme_root()."/".get_template()."/geshi-source-colorer.css";
            if (file_exists($css) && is_file($css))
                wp_enqueue_style( "geshisourcecolorer_userstyle" );
            
            $js = get_theme_root()."/".get_template()."/geshi-source-colorer.js";
            if (file_exists($js) && is_file($js))
                wp_enqueue_script( "geshisourcecolorer_userfunction" );            
        
            break;
        }
    
    // return post array
    return $posts;
}
    
    
/** plugin administration page initialization
 * @param $hook hook name of the enquere call
 **/
function initAdminScripts($hook)
{
    // CSS script
    wp_register_style( "geshisourcecolorer_adminstyle", plugins_url("css/administration".(MINIFY ? ".min" : null).".css", LOCALPLUGINFILE) );
    
    // jQuery and function script
    wp_register_script( "geshisourcecolorer_administration", plugins_url("js/administration".(MINIFY ? ".min" : null).".js", LOCALPLUGINFILE), array("jquery") );

    // scripts are only load if needed (injection error supress)
    if ($hook == "settings_page_fpx_geshisourcecolorer_option") {
        wp_enqueue_script( "geshisourcecolorer_administration" );

        wp_localize_script( "geshisourcecolorer_administration", "geshisourcecolorer_messages", array(
            "defaultdelete"      => __("default style can not be deleted", "fpx_geshisourcecolorer"),
            "save"               => __("save this style under a new name or use the same name for overriding", "fpx_geshisourcecolorer"),
            "previewemptycode"   => __("The preview code is empty. Please add some code lines", "fpx_geshisourcecolorer")
        ));
        
        wp_enqueue_style( "geshisourcecolorer_adminstyle" );
    }
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
                "visualeditor"      => false,
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
                  
                // options of the default css class
                "css"             => array(
                  
                        "line"          => "geshisourcecolorer-line-default",
                        "block"         => "geshisourcecolorer-block-default",
                        "tab"           => "geshisourcecolorer-tab-default"
                
                ),
                  
                // style options of the codes
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