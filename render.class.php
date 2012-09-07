<?php
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

namespace de\flashpixx\geshisourcecolorer;


/** class for creating all visual options **/
class render {
    
    /** creates admin menu **/
    static function adminmenu()
    {
        add_options_page("GeSHi Source Colorer Optionen", "GeSHi Source Colorer", "manage_options", "fpx_geshisourcecolorer_option", get_class()."::renderMain");
    }


    /** shows the admin panel with actions **/
    static function optionfields()
    {
        register_setting("fpx_geshisourcecolorer_option", "fpx_geshisourcecolorer_option", get_class()."::validate");
        
        
        add_settings_section("fpx_geshisourcecolorer_option",  __("code tags", "fpx_geshisourcecolorer"),   get_class()."::render_tagsection",              "fpx_geshisourcecolorer_optionglobal");
        add_settings_field("codetag",            __("code tag", "fpx_geshisourcecolorer"),      get_class()."::render_codetag",              "fpx_geshisourcecolorer_optionglobal",      "fpx_geshisourcecolorer_option");
        add_settings_field("codelinetag",        __("code line tag", "fpx_geshisourcecolorer"), get_class()."::render_codelinetag",          "fpx_geshisourcecolorer_optionglobal",      "fpx_geshisourcecolorer_option");
        add_settings_field("languagetag",        __("language tag", "fpx_geshisourcecolorer"),  get_class()."::render_languagetag",          "fpx_geshisourcecolorer_optionglobal",      "fpx_geshisourcecolorer_option");
        
        
        add_settings_section("fpx_geshisourcecolorer_option",  __("main options", "fpx_geshisourcecolorer"),                               get_class()."::render_mainsection",   "fpx_geshisourcecolorer_optionmain");
        add_settings_field("maincss",           __("enable / disable plugin css", "fpx_geshisourcecolorer"),                                   get_class()."::render_maincss",           "fpx_geshisourcecolorer_optionmain",      "fpx_geshisourcecolorer_option");
        add_settings_field("geshicss",          __("enable / disable GeSHi css", "fpx_geshisourcecolorer")." <em>[geshicss]</em>",             get_class()."::render_geshicss",          "fpx_geshisourcecolorer_optionmain",      "fpx_geshisourcecolorer_option");
        add_settings_field("keywordref",        __("enable / disable keyword references", "fpx_geshisourcecolorer")." <em>[keywordref]</em>",  get_class()."::render_keywordref",        "fpx_geshisourcecolorer_optionmain",      "fpx_geshisourcecolorer_option");
        add_settings_field("linenumber",        __("enable / disable line numbers", "fpx_geshisourcecolorer")." <em>[linenumber]</em>",        get_class()."::render_linenumber",        "fpx_geshisourcecolorer_optionmain",      "fpx_geshisourcecolorer_option"); 
        add_settings_field("collapse",          __("enable / disable collapse code blocks", "fpx_geshisourcecolorer")." <em>[collapse]</em>",  get_class()."::render_collapse",          "fpx_geshisourcecolorer_optionmain",      "fpx_geshisourcecolorer_option");              
        add_settings_field("collapsetext",      __("collapse text", "fpx_geshisourcecolorer")." <em>[collapsetext]</em>",                      get_class()."::render_collapsetext",          "fpx_geshisourcecolorer_optionmain",      "fpx_geshisourcecolorer_option");         
        add_settings_field("tabsize",           __("tabulator size", "fpx_geshisourcecolorer")." <em>[tabsize]</em>",                          get_class()."::render_tabsize",           "fpx_geshisourcecolorer_optionmain",      "fpx_geshisourcecolorer_option");
        add_settings_field("copyclipboardtext", __("copy-clipboard message text", "fpx_geshisourcecolorer")." <em>[copytext]</em>",            get_class()."::render_clipboardcopytext", "fpx_geshisourcecolorer_optionmain",      "fpx_geshisourcecolorer_option");
        add_settings_field("lolheadtext",       __("header text of the list of listings", "fpx_geshisourcecolorer"),                           get_class()."::render_lolheadtext",       "fpx_geshisourcecolorer_optionmain",      "fpx_geshisourcecolorer_option");  
  
        
        add_settings_section("fpx_geshisourcecolorer_option",  __("code block toolbar", "fpx_geshisourcecolorer"),       get_class()."::render_codeblocktoolbarsection",          "fpx_geshisourcecolorer_optioncodeblocktoolbar");
        add_settings_field("toolbar_linenumber",   __("enable / disable button for toggeling line numbers", "fpx_geshisourcecolorer")." <em>[toolbar_linenumber]</em>",     get_class()."::render_blocktoolbarlinenumber",          "fpx_geshisourcecolorer_optioncodeblocktoolbar", "fpx_geshisourcecolorer_option");        
        add_settings_field("toolbar_clipboard",    __("enable / disable button for copy-to-clipboard", "fpx_geshisourcecolorer")." <em>[toolbar_copyclipboard]</em>",       get_class()."::render_blocktoolbarclipboard",          "fpx_geshisourcecolorer_optioncodeblocktoolbar", "fpx_geshisourcecolorer_option"); 
        add_settings_field("toolbar_sourcewindow",    __("enable / disable button for open blank code window", "fpx_geshisourcecolorer")." <em>[toolbar_blankwindow]</em>", get_class()."::render_blocktoolbarblankwindow",          "fpx_geshisourcecolorer_optioncodeblocktoolbar", "fpx_geshisourcecolorer_option"); 
        
        
        add_settings_section("fpx_geshisourcecolorer_option",  __("code line toolbar", "fpx_geshisourcecolorer"),       get_class()."::render_codelinetoolbarsection",          "fpx_geshisourcecolorer_optioncodelinetoolbar");   
        add_settings_field("toolbar_clipboard",    __("enable / disable button for copy-to-clipboard", "fpx_geshisourcecolorer")." <em>[toolbar_copyclipboard]</em>",       get_class()."::render_linetoolbarclipboard",          "fpx_geshisourcecolorer_optioncodelinetoolbar", "fpx_geshisourcecolorer_option"); 
        add_settings_field("toolbar_sourcewindow",    __("enable / disable button for open blank code window", "fpx_geshisourcecolorer")." <em>[toolbar_blankwindow]</em>", get_class()."::render_linetoolbarblankwindow",          "fpx_geshisourcecolorer_optioncodelinetoolbar", "fpx_geshisourcecolorer_option"); 
        
        
        
        add_settings_section("fpx_geshisourcecolorer_option",  __("code styles", "fpx_geshisourcecolorer"),       get_class()."::render_stylesection",          "fpx_geshisourcecolorer_optionstyles");
        add_settings_field("styles",    __("styles", "fpx_geshisourcecolorer")." <em>[style]</em>",       get_class()."::render_styles",          "fpx_geshisourcecolorer_optionstyles", "fpx_geshisourcecolorer_option"); 

        
        
        
        add_settings_section("fpx_geshisourcecolorer_option",  __("export / import", "fpx_geshisourcecolorer"),   get_class()."::render_eximportsection",       "fpx_geshisourcecolorer_optionexportimport");
        add_settings_field("export",  __("export value", "fpx_geshisourcecolorer"),                get_class()."::render_export",                "fpx_geshisourcecolorer_optionexportimport", "fpx_geshisourcecolorer_option");
        add_settings_field("import",  __("import value", "fpx_geshisourcecolorer"),                get_class()."::render_import",                "fpx_geshisourcecolorer_optionexportimport", "fpx_geshisourcecolorer_option");
        add_settings_field("importtags",    __("import code tags", "fpx_geshisourcecolorer"),      get_class()."::render_importcodetags",        "fpx_geshisourcecolorer_optionexportimport", "fpx_geshisourcecolorer_option");
        add_settings_field("importmain",    __("import main options", "fpx_geshisourcecolorer"),   get_class()."::render_importmain",            "fpx_geshisourcecolorer_optionexportimport", "fpx_geshisourcecolorer_option");
        add_settings_field("importstyles",  __("import code styles", "fpx_geshisourcecolorer"),    get_class()."::render_importstyles",           "fpx_geshisourcecolorer_optionexportimport", "fpx_geshisourcecolorer_option");
    }

    
    /** validate the form data
     * @pa input data
     * @return data
     **/
    static function validate($pa)
    {
        // check the input
        if ( (!isset($pa["tag_code"])) || (empty($pa["tag_code"])) || (preg_match("/\s+/", $pa["tag_code"])) )
            add_settings_error( "geshisourcecolorer", "option_validate_codetag", __("code tag need not to be empty or have any spaces", "fpx_geshisourcecolorer"), "error");
        if ( (!isset($pa["tag_line"])) || (empty($pa["tag_line"])) || (preg_match("/\s+/", $pa["tag_line"])) )
            add_settings_error( "geshisourcecolorer", "option_validate_codelinetag", __("code line tag need not to be empty or have any spaces", "fpx_geshisourcecolorer"), "error");
        if ( (!isset($pa["tag_language"])) ||(empty($pa["tag_language"])) || (preg_match("/\s+/", $pa["tag_language"])) )
            add_settings_error( "geshisourcecolorer", "option_validate_languagetag", __("language tag need not to be empty or have any spaces", "fpx_geshisourcecolorer"), "error");
        
        if ( (!isset($pa["tabsize"])) ||(empty($pa["tabsize"])) || (!is_numeric($pa["tabsize"])) || ($pa["tabsize"] < 0))
            add_settings_error( "geshisourcecolorer", "option_validate_tabsize", __("tabs size need not to be empty, must be numeric and must be equal and greater than zero", "fpx_geshisourcecolorer"), "error");
        if ( (!isset($pa["copytext"])) || (empty($pa["copytext"])) )
            add_settings_error( "geshisourcecolorer", "option_validate_copytext", __("copy-clipboard message text need not to be empty", "fpx_geshisourcecolorer"), "error");
        if ( (!isset($pa["lolheadtext"])) || (empty($pa["lolheadtext"])) )
            add_settings_error( "geshisourcecolorer", "option_validate_lolheadtext", __("list-of-listings header text need not to be empty", "fpx_geshisourcecolorer"), "error");
        
        // set the option values into the result array (return the default options, if an error occures)
        $options = $oldoptions = get_option("fpx_geshisourcecolorer_option");
        
        $options["tag"]["code"]                         = $pa["tag_code"];
        $options["tag"]["line"]                         = $pa["tag_line"];
        $options["tag"]["language"]                     = $pa["tag_language"];
        
        $options["toolbar"]["block"]["linenumber"]      = isset($pa["toolbar_block_linenumber"]) && (!empty($pa["toolbar_block_linenumber"]));
        $options["toolbar"]["block"]["copyclipboard"]   = isset($pa["toolbar_block_clipboard"]) && (!empty($pa["toolbar_block_clipboard"]));
        $options["toolbar"]["block"]["sourcewindow"]    = isset($pa["toolbar_block_sourcewindow"]) && (!empty($pa["toolbar_block_sourcewindow"]));
        
        $options["toolbar"]["line"]["copyclipboard"]    = isset($pa["toolbar_line_clipboard"]) && (!empty($pa["toolbar_line_clipboard"]));
        $options["toolbar"]["line"]["sourcewindow"]     = isset($pa["toolbar_line_sourcewindow"]) && (!empty($pa["toolbar_line_sourcewindow"]));
        
        $options["tabsize"]                             = intval($pa["tabsize"]);
        $options["copytext"]                            = $pa["copytext"];
        $options["lolheadtext"]                         = $pa["lolheadtext"];
        
        $options["geshicss"]                            = isset($pa["geshicss"]) && (!empty($pa["geshicss"]));
        $options["maincss"]                             = isset($pa["maincss"]) && (!empty($pa["maincss"]));
        $options["keywordref"]                          = isset($pa["keywordref"]) && (!empty($pa["keywordref"]));
        $options["linenumber"]                          = isset($pa["linenumber"]) && (!empty($pa["linenumber"]));
        $options["collapse"]                            = isset($pa["collapse"]) && (!empty($pa["collapse"]));
        $options["collapsetext"]                        = $pa["collapsetext"];
        
        $options["style"]                               = @json_decode($pa["styles"], true);
        
        // check the import settings and try to import
        if ( (isset($pa["import"])) && (!empty($pa["import"])) )
            $options = self::import($options, $pa);

        // on error we restore the old options
        $errors  = get_settings_errors("geshisourcecolorer");
        if (!empty($errors))
            return $oldoptions;
        
        return $options;
    }
    
    
    /** returns the plugin version
     * @return plugin version
     **/
    private static function getPluginVersion()
    {
        // get the plugin version
        $plugin_folder = get_plugins();
        $plugin_file   = basename(dirname(LOCALPLUGINFILE))."/".basename(LOCALPLUGINFILE);
        return floatval(isset($plugin_folder[$plugin_file]["Version"]) ? $plugin_folder[$plugin_file]["Version"] : 0);
    }
    
    
    /** runt the import
     * @param $options option array
     * @param $pa form data array
     * @return changed options
     **/
    private static function import($options, $pa)
    {
        // deserialize the array
        $data = @unserialize(trim($pa["import"]));
        if ( (empty($data)) || (!is_array($data)) )
        {
            add_settings_error( "geshisourcecolorer", "option_validate_import", __("import data is incorrect", "fpx_geshisourcecolorer"), "error");
            return $options;
        }
        
        // determine the current version, we can only import data <= current version
        $importversion = floatval( isset($data["pluginversion"]) ? $data["pluginversion"] : 0 );
        if ($importversion < self::getPluginVersion())
        {
            add_settings_error( "geshisourcecolorer", "option_validate_importversion", __("import data version is bigger than current version", "fpx_geshisourcecolorer"), "error");
            return $options;
        }
        
        // check which options should be imported
        $pa["importcodetags"]   = isset($pa["importcodetags"])    ? $pa["importcodetags"]   : false;
        $pa["importmainoption"] = isset($pa["importmainoption"])  ? $pa["importmainoption"] : false;
        $pa["importstyles"]     = isset($pa["importstyles"])      ? $pa["importstyles"] : null;
        if ( (!$pa["importcodetags"]) && (!$pa["importmainoption"]) && (empty($pa["importstyles"])) ) 
        {
            add_settings_error( "geshisourcecolorer", "option_validate_importoptions", __("no options for the import are selected, so there is nothing to do", "fpx_geshisourcecolorer"), "error");
            return $options;
        }      
        
        
        // do import on the version
        $isimport = false;
        
        // current version
        if ($importversion == self::getPluginVersion()) {
            $isimport = true;
            if ($pa["importcodetags"])
            {
                $options["tag"]["code"]      = $data["tag"]["code"];
                $options["tag"]["line"]      = $data["tag"]["line"];
                $options["tag"]["language"]  = $data["tag"]["language"];
            }
            if ($pa["importmainoption"])
            {
                $options["geshicss"]                            = $data["geshicss"]; 
                $options["maincss"]                             = $data["maincss"];
                $options["keywordref"]                          = $data["keywordref"];
                $options["tabsize"]                             = $data["tabsize"];    
                $options["linenumber"]                          = $data["linenumber"]; 
                $options["collapse"]                            = $data["collapse"];
                $options["collapsetext"]                        = $data["collapsetext"];
                $options["lolheadtext"]                         = $data["lolheadtext"];
                $options["copytext"]                            = $data["copytext"];                  
                $options["toolbar"]["line"]["sourcewindow"]     = $data["toolbar"]["line"]["sourcewindow"];
                $options["toolbar"]["line"]["copyclipboard"]    = $data["toolbar"]["line"]["copyclipboard"];
                $options["toolbar"]["block"]["linenumber"]      = $data["toolbar"]["block"]["linenumber"];
                $options["toolbar"]["block"]["sourcewindow"]    = $data["toolbar"]["block"]["sourcewindow"];
                $options["toolbar"]["block"]["copyclipboard"]   = $data["toolbar"]["block"]["copyclipboard"];
            }
            switch ($pa["importstyles"])
            {
                case "overwrite" :
                    $options["style"] = $data["style"];
                    break;
                    
                case "ignore";
                    $options["style"] = array_merge( $options["style"], array_diff_key( $data["style"], $options["style"] ) );
                    break;
                    
                case "rename" :
                    foreach($data["style"] as $key => $val)
                {
                    $newkey = $key;
                    while (array_key_exists($newkey, $options["style"]))
                        $newkey = $key."-".strval(mt_rand());
                    
                    $options["style"][$newkey] = $val;
                }
                    break;
            }
        }
        
        
        if (!$isimport)
            add_settings_error( "geshisourcecolorer", "option_validate_importversion", __("no data is imported, because the import version matches no import function", "fpx_geshisourcecolorer"), "error");
        return $options;
    }


    /** render the option page **/
    static function renderMain()
    {
        echo "<div class=\"wrap\"><h2>GeSHi Source Colorer ".__("Configuration", "fpx_geshisourcecolorer")."</h2>\n";
        echo "<p>".__("The options here define the global states of the plugin, all option, on which brackets [] are added, can be set to the tag call. Each source code can be configurated individual with the options. The plugin options can be exported and imported from another version. The values will be converted to the plugin, so you can use this option to copy your styles or backup the data on plugin updates. Options can be shown / hide by a click on the header. The installed GeSHi version is", "fpx_geshisourcecolorer")." ".GESHI_VERSION." ".__("the supported languages can be found on", "fpx_geshisourcecolorer")." <a href=\"http://qbnz.com/highlighter/\">".__("GeSHi's project page", "fpx_geshisourcecolorer")."</a></p>";
        echo "<form method=\"post\" action=\"options.php\">";
        settings_fields("fpx_geshisourcecolorer_option");
        
        echo "<div class=\"geshisourcecolorer-option\">";
        echo "<div style=\"padding-top: 3em\">";
        do_settings_sections("fpx_geshisourcecolorer_optionglobal");
        echo "</div><div style=\"padding-top: 3em\">";
        do_settings_sections("fpx_geshisourcecolorer_optionmain");
        echo "</div><div style=\"padding-top: 3em\" class=\"invisible\">";
        do_settings_sections("fpx_geshisourcecolorer_optioncodeblocktoolbar");
        echo "</div><div style=\"padding-top: 3em\" class=\"invisible\">";
        do_settings_sections("fpx_geshisourcecolorer_optioncodelinetoolbar");
        echo "</div><div style=\"padding-top: 3em\" class=\"invisible\">";
        do_settings_sections("fpx_geshisourcecolorer_optionstyles");
        echo "</div><div style=\"padding-top: 3em\" class=\"invisible\">";
        do_settings_sections("fpx_geshisourcecolorer_optionexportimport");
        echo "</div></div>";
        
        echo "<p class=\"submit\"><input type=\"submit\" name=\"submit\" class=\"button-primary\" value=\"".__("Save Changes")."\"/></p>\n";
        echo "</form></div>\n";
    }

    
    
    static function render_tagsection() {
        echo __("This main option sets tags, which can defined the code blocks. There are three tags, the first one defines code blocks, the second code lines and the third the parameter for setting the code language. You can change these tags to a fee defined non-empty value. There are some parameter which can be used for defining your own code tag. Use the following placeholders:", "fpx_geshisourcecolorer");
        echo "<ul>";
        echo "<li><em>%c</em> ".__("defines the code position", "fpx_geshisourcecolorer")."</li>";
        echo "<li><em>%s</em> ".__("defines any spaces (one or more spaces)", "fpx_geshisourcecolorer")."</li>";
        echo "<li><em>%p</em> ".__("defines the parameter in a key-value pair (key=\"value\")", "fpx_geshisourcecolorer")."</li>";
        echo "</ul>";
        echo __("The third parameter (lang) is only used within the", "fpx_geshisourcecolorer")." <em>%p</em> ".__("placeholder", "fpx_geshisourcecolorer");
    }
    
    static function render_codetag()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[tag_code]\" size=\"30\" type=\"text\" value=\"".$options["tag"]["code"]."\" />";
    }
    
    static function render_codelinetag()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[tag_line]\" size=\"30\" type=\"text\" value=\"".$options["tag"]["line"]."\" />";
    }
    
    static function render_languagetag()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[tag_language]\" size=\"30\" type=\"text\" value=\"".$options["tag"]["language"]."\" />";
    }
    
    
    
    static function render_mainsection()
    {
        echo __("This option section sets the default values for the code blocks. You can set each value also to the code tags and overload the default option.", "fpx_geshisourcecolorer");
    }
    
    static function render_geshicss()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[geshicss]\" type=\"checkbox\" value=\"1\" ".($options["geshicss"] ? "checked" : null)." />";
    }
    
    static function render_maincss()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[maincss]\" type=\"checkbox\" value=\"1\" ".($options["maincss"] ? "checked" : null)." />";
    }
    
    static function render_keywordref()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[keywordref]\" type=\"checkbox\" value=\"1\" ".($options["keywordref"] ? "checked" : null)." />";
    }
    
    static function render_linenumber()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[linenumber]\" type=\"checkbox\" value=\"1\" ".($options["linenumber"] ? "checked" : null)." />";
    }
    
    static function render_collapse()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[collapse]\" type=\"checkbox\" value=\"1\" ".($options["collapse"] ? "checked" : null)." />";
    }

    static function render_collapsetext()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[collapsetext]\" type=\"text\" size=\"30\" value=\"".$options["collapsetext"]."\" />";
    }
    
    static function render_tabsize()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[tabsize]\" size=\"30\" type=\"text\" value=\"".$options["tabsize"]."\" />";
    }
    
    static function render_clipboardcopytext()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[copytext]\" size=\"30\" type=\"text\" value=\"".$options["copytext"]."\" />";
    }
    
    static function render_lolheadtext()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[lolheadtext]\" size=\"30\" type=\"text\" value=\"".$options["lolheadtext"]."\" />";
    }
    
    
    
    static function render_codeblocktoolbarsection()
    {
        echo __("This subsection sets the default values of the code block hover toolbar.", "fpx_geshisourcecolorer");
    }
    
    static function render_blocktoolbarlinenumber()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[toolbar_block_linenumber]\" type=\"checkbox\" value=\"1\" ".($options["toolbar"]["block"]["linenumber"] ? "checked" : null)." />";
    }
    
    static function render_blocktoolbarclipboard()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[toolbar_block_clipboard]\" type=\"checkbox\" value=\"1\" ".($options["toolbar"]["block"]["copyclipboard"] ? "checked" : null)." />";
    }

    static function render_blocktoolbarblankwindow()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[toolbar_block_sourcewindow]\" type=\"checkbox\" value=\"1\" ".($options["toolbar"]["block"]["sourcewindow"] ? "checked" : null)." />";
    }
    
    
    
    static function render_codelinetoolbarsection()
    {
        echo __("This subsection sets the default values of the code line hover toolbar.", "fpx_geshisourcecolorer");
    }
    
    static function render_linetoolbarclipboard()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[toolbar_line_clipboard]\" type=\"checkbox\" value=\"1\" ".($options["toolbar"]["line"]["copyclipboard"] ? "checked" : null)." />";
    }
    
    static function render_linetoolbarblankwindow()
    {
        $options = get_option("fpx_geshisourcecolorer_option");
        echo "<input name=\"fpx_geshisourcecolorer_option[toolbar_line_sourcewindow]\" type=\"checkbox\" value=\"1\" ".($options["toolbar"]["line"]["sourcewindow"] ? "checked" : null)." />";
    }
    
    
    
    static function render_stylesection()
    {
        echo __("Within this subsection you can define your code styles. Each style definition must have a name. The plugin indentify the style, which is setup to a code block, with the name in lower-case. The plugin tries to get the style, that is set with the name first. If no style is found, the plugin tries to get a style with the name of the code language, if also no style is found, the default style is used. The default style can not be deleted, but it can be modified. The styles are saved when the plugin settings are stored, so a click to \"store style\" or \"delete style\" updates / removes the style on the list only. You can use any CSS element for descripe your style. You can take a preview of your style, add some example code into the field and choose a style definition.", "fpx_geshisourcecolorer");
        echo "<div id=\"geshisourcecolorer-examplecode\"></div>";
    }
    
    static function render_styles()
    {
        $geshi   = new \GeSHi();
        $options = get_option("fpx_geshisourcecolorer_option");
        
        // create a list box with names and a hidden field with all styles as a JSON object
        echo "<input name=\"fpx_geshisourcecolorer_option[styles]\" id=\"jsonstyle\" type=\"hidden\" value='".json_encode($options["style"], JSON_FORCE_OBJECT)."' />";
        echo "<div class=\"geshisourcecolorer-table\">";
        
        echo "<div class=\"geshisourcecolorer-tr\">";
        echo "<div class=\"geshisourcecolorer-td\"><select id=\"geshisourcecolorer-styleselect\" size=\"1\"></select></div>";
        echo "<div class=\"geshisourcecolorer-td\">";
        echo "<input id=\"geshisourcecolorer-stylesave\" type=\"button\" value=\"".__("store style", "fpx_geshisourcecolorer")."\" />";
        echo "<input id=\"geshisourcecolorer-styledelete\" type=\"button\" value=\"".__("delete style", "fpx_geshisourcecolorer")."\" /></div>";
        echo "<input id=\"geshisourcecolorer-stylepreview\" type=\"button\" value=\"".__("preview style", "fpx_geshisourcecolorer")."\" /></div>";
        echo "</div>";
         
        
        echo "<div class=\"geshisourcecolorer-tr\">";
        echo "<div class=\"geshisourcecolorer-td\">".__("keyword style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-keywords\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("comment style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-comments\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("escapecharacter style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-escapechars\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "</div>";
        
        echo "<div class=\"geshisourcecolorer-tr\">";
        echo "<div class=\"geshisourcecolorer-td\">".__("symbol style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-symbols\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("number style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-numbers\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("string style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-strings\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "</div>";
        
        echo "<div class=\"geshisourcecolorer-tr\">";
        echo "<div class=\"geshisourcecolorer-td\">".__("method style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-methods\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("regular expression style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-regex\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("highlight style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-highlight\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "</div>";
        
        echo "<div class=\"geshisourcecolorer-tr\">";
        echo "<div class=\"geshisourcecolorer-td\">".__("keyword hover style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-keywordhover\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("keyword link style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-keywordlink\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("keyword active style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-keywordactive\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "</div>";
        
        echo "<div class=\"geshisourcecolorer-tr\">";
        echo "<div class=\"geshisourcecolorer-td\">".__("keyword visited style", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-keywordvisited\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("preview test code", "fpx_geshisourcecolorer")."<textarea id=\"geshisourcecolorer-previewcode\" wrap=\"soft\" rows=\"2\" cols=\"20\"></textarea></div>";
        echo "<div class=\"geshisourcecolorer-td\">".__("preview language", "fpx_geshisourcecolorer")."<select id=\"geshisourcecolorer-previewlanguage\" size=\"1\">";
        foreach( $geshi->get_supported_languages(true) as $key => $val )
            echo "<option value=\"".$key."\">".$val."</option>";
        echo "</select></div>";
        echo "</div>";
        echo "</div>";
        
        #preview div
        echo "<div id=\"geshisourcecolorer-preview\"></div>";
    }
    
    
    
    static function render_eximportsection()
    {
        echo __("This section shows all option values of the plugin as a text representation. You can copy this value into another instance of the plugin. If you past a value to the import section, all plugin values will be repaced with the values of the import.", "fpx_geshisourcecolorer");
    }
    
    static function render_export()
    {
        $option                  = get_option("fpx_geshisourcecolorer_option");
        $option["pluginversion"] = self::getPluginVersion();
        echo "<textarea wrap=\"soft\" readonly=\"readonly\" rows=\"3\" cols=\"60\">".serialize($option)."</textarea>";
    }
    
    static function render_import()
    {
        echo "<textarea wrap=\"soft\" name=\"fpx_geshisourcecolorer_option[import]\" rows=\"3\" cols=\"60\"></textarea>";
    }
    
    static function render_importcodetags()
    {
        echo "<input name=\"fpx_geshisourcecolorer_option[importcodetags]\" type=\"checkbox\" value=\"1\" />";
    }
    
    static function render_importmain()
    {
        echo "<input name=\"fpx_geshisourcecolorer_option[importmainoption]\" type=\"checkbox\" value=\"1\" />";
    }
    
    static function render_importstyles()
    {
        echo "<select name=\"fpx_geshisourcecolorer_option[importstyles]\" size=\"1\">";
        echo "<option value=\"\">".__("not import", "fpx_geshisourcecolorer")."</option>";
        echo "<option value=\"overwrite\">".__("import and overwrite existing styles", "fpx_geshisourcecolorer")."</option>";
        echo "<option value=\"ignore\">".__("import and do not overwrite existing styles", "fpx_geshisourcecolorer")."</option>";
        echo "<option value=\"rename\">".__("import and rename existing styles", "fpx_geshisourcecolorer")."</option>";
        echo "</select>";
    }
}

?>