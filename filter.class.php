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

/** class for doing content filtering **/
class filter {

    /** hook for filtering the source code bevor any other filter catches
     * @param $pcContent content
     * @return modified HTML
     **/
    static function runCodeBefore($pcContent)
    {
        $option = get_option("fpx_geshisourcecolorer_option");
        return preg_replace_callback( self::createFilterRegEx($option["tag"]["code"]), "self::actionBefore", $pcContent );
    }
    
    
    /** hook for filtering the source code bevor any other filter catches
     * @param $pcContent content
     * @return modified HTML
     **/
    static function runCodeLineBefore($pcContent)
    {
        $option = get_option("fpx_geshisourcecolorer_option");
        return preg_replace_callback( self::createFilterRegEx($option["tag"]["line"]), "self::actionBefore", $pcContent );
    }
    
    
    /** replace hook function for the code blocks
     * @param $pcContent content
     * @return modified HTML
     **/
    static function runCode($pcContent)
    {
        $option = get_option("fpx_geshisourcecolorer_option");
        return preg_replace_callback( self::createFilterRegEx($option["tag"]["code"]), "self::actionCode", $pcContent );
    }

    
    /** replace hook function for setting the code line tag
     * @param $pcContent content
     * @return modified HTML
     **/
    static function runCodeLine($pcContent)
    {
        $option = get_option("fpx_geshisourcecolorer_option");
        return preg_replace_callback( self::createFilterRegEx($option["tag"]["line"]), "self::actionLine", $pcContent );
    }
    
    
    /** replace hook function for the table of listings
     * @param $pcContent content
     * @return modified HTML
     **/
    static function runListOfListings($pcContent)
    {
        $option = get_option("fpx_geshisourcecolorer_option");
        return str_replace("[lol]", "<div id=\"geshisourcecolorer-lol\"><div class=\"head\">".$option["lolheadtext"]."</div></div>", $pcContent);
    }
    
    
    /** function for handling the Ajax request **/
    static function preview()
    {
        if ( (current_user_can( "manage_options" )) && (!empty($_POST["source"])) && (!empty($_POST["lang"])) && (is_array($_POST["style"])) ) {
            $option = get_option("fpx_geshisourcecolorer_option");
            
            $geshi  = new \GeSHi( stripcslashes($_POST["source"]), $_POST["lang"] );
            
            $geshi->set_header_type(GESHI_HEADER_NONE);
            $geshi->enable_strict_mode(GESHI_MAYBE);
            $geshi->set_encoding(get_option("blog_charset"));
            $geshi->set_tab_width($option["tabsize"]);
            $geshi->enable_keyword_links($option["keywordref"]);
            
            self::setGeSHiStyle($geshi, $_POST["style"] );
            
            $lc = $geshi->error();
            if (empty($lc))
                echo $geshi->parse_code();
            echo $lc;
        }
        
        exit;
    }
    
    
    /** creates the filter regular expression
     * @param $pcTag tag expression
     * @return regular expression
     **/
    static function createFilterRegEx($pcTag)
    {
        // set regular expression for filtering (mask all meta character, and replace the fragments %c% (code), %s% (spaces) and %p% (parameter) )
        $regex  = str_replace("%c", "(.*)", quotemeta($pcTag));
        $regex  = str_replace("%s", "\s+", $regex);
        $regex  = str_replace("%p", "((\w+)\s*=\s*\"(.+)\")+", $regex);
        return "!".$regex."!isU";
    }
    


    
    /** create the action on each code line
     * @param $pa array with founded regular expressions
     * @return the formated content
     **/
    static function actionLine($pa)
    {
        if ( (empty($pa)) || (count($pa) < 3) )
            return "<strong>error during code parsing, please correct your tag definition</strong>";
        
        // get the options
        $option = self::getParameter($pa[1], "line");
        if (empty($option["language"]))
            return __("no source code language is set", "fpx_geshisourcecolorer");
        
        // disable different options hard
        $option["toolbar"]["linenumber"] = false;
        $option["linenumber"]            = false;
        $option["collapse"]              = false;
        $option["hoverhighlight"]        = array();
        $option["highlight"]             = array();
        $option["tab"]                   = array();
        
        // create GeSHi instance with code and remove an linebreaks of the source
        $source = str_replace( array("\r\n", "\r", "\n"), null, self::convertWordpress2Code($pa[4]) );      
        $id     = "geshisourcecolorer-line-" . (empty($option["id"]) ? md5($source) : $option["id"]);
        
        $geshi  = new \GeSHi( $source, $option["language"] );
        self::setGeSHiMainOptions($geshi, $option, $id, $option["css"]["line"]);
        self::setGeSHiStyle($geshi, $option["style"]);
        self::setGeSHiHeader($geshi, $option, $id, $source);
        
        $lc = $geshi->error();
        if (empty($lc))
            return " ".$geshi->parse_code()." ";
        return " <strong>".$lc."</strong> ";
        
    }
    
    
    /** create action and the block with code
     * @param $pa array with founded regular expressions
     * @return the formated content
     **/
    static function actionCode($pa)
    {
        if ( (empty($pa)) || (count($pa) < 3) )
            return "<strong>error during code parsing, please correct your tag definition</strong>";
        
        // get the options
        $option = self::getParameter($pa[1], "block");
        if (empty($option["language"]))
            return __("no source code language is set", "fpx_geshisourcecolorer");
        
        // create GeSHi instance with code
        $source = self::convertWordpress2Code($pa[4]);
        $id     = "geshisourcecolorer-" . (empty($option["id"]) ? md5($source) : $option["id"]);
        $class  = $option["css"]["block"];
        $prefix = null;
        $suffix = null;
        
        // set collapse data
        if ( ($option["collapse"]) && (empty($option["tab"])) )
        {
            $class  .= " geshisourcecolorer-collapse";
            $prefix .= "<span class=\"geshisourcecolorer-collapse-button\" id=\"".$id."-collapse-button\">".$option["collapsetext"]."</span>";
        }
        
        // set tab structure
        if (!empty($option["tab"]))
        {
            $prefix .= "<div class=\"geshisourcecolorer-tab ".$option["css"]["tab"]." ".$option["tab"][0]."\" rel=\"".$option["tab"][1]."\">";
            $suffix .= "</div>";
        }
        
        

        $geshi  = new \GeSHi( $source, $option["language"] );
        self::setGeSHiMainOptions($geshi, $option, $id, $class);
        self::setGeSHiStyle($geshi, $option["style"]);
        self::setGeSHiHeader($geshi, $option, $id, $source);

        $lc = $geshi->error();
        if (empty($lc))
            return " ".$prefix.$geshi->parse_code().$suffix." ";
        
        return " <strong>".$lc."</strong> ";
    }

    /** actin that is run before, for replacing all character to HTML elements
     * @param $pa input array for format
     * @return replaced data
     **/
    static function actionBefore($pa)
    {
        if ( (empty($pa)) || (count($pa) < 3) )
            return "<strong>error during code parsing, please correct your tag definition</strong>";

        // replace content with HTML encoding
        return str_replace("%", htmlentities($pa[4], ENT_COMPAT, get_option("blog_charset")), str_replace($pa[4], "%", $pa[0]));
    }
    
    
    /** creates the toolbar, anchor and hightlight options
     * @param $geshi GeSHi object
     * @param $option option array
     * @param $id HTML id
     * @param $source unparsed source code
     **/
    private static function setGeSHiHeader(&$geshi, $option, $id, $source)
    {
        // if one of the options are true we create the toolbar div
        $toolbar = "<a name=\"".$id."\" class=\"lol\" ".(empty($option["lol"]) ? null : "rel=\"".$option["lol"]."\"").">".($option["lolhead"] ? $option["lol"] : null)."</a>";
        if ( ($option["toolbar"]["sourcewindow"]) || ($option["toolbar"]["copyclipboard"]) || ($option["toolbar"]["linenumber"]) )
        { 
            $toolbar .= "<div class=\"toolbar\" \">";
            
            if ($option["toolbar"]["linenumber"])
                $toolbar .= "<div class=\"togglelinenumber\"></div>";
            if ($option["toolbar"]["copyclipboard"])
                $toolbar .= "<div class=\"copyclipboard\" id=\"".$id."-copyclipboard\" rel=\"".$option["copytext"]."\"></div>";
            if ($option["toolbar"]["sourcewindow"])
                $toolbar .= "<div class=\"sourcewindow\"></div>";
            
            $toolbar .= "</div>";
            
            if ( ($option["toolbar"]["sourcewindow"]) || ($option["toolbar"]["copyclipboard"]) )
                $toolbar .= "<pre class=\"source\" style=\"position: absolute; visibility: hidden; z-index: -10;\">".htmlentities($source, ENT_COMPAT, get_option("blog_charset"))."</pre>";
        }
        
        // add hoverhighlight data, we create spans with hidden visibility, rel attribute for style and class "hoverhighlight"
        foreach($option["hoverhighlight"] as $info => $lines)
            $toolbar .= "<span class=\"hoverhighlight\" style=\"visibility: hidden;\" rel=\"".$info."[".$lines."]"."\"></span>";
        
        $geshi->set_header_content($toolbar);
    }
    
    
    /** convert Wordpress input data into correct code data, first we must revert any html codes
     * to their normal character, because we don't need other replace options, that has changed the code,
     * exspecially wie subsitute each "</p>" to "\n", because otherwise all space lines are removed
     * @param $pc Wordpress input
     * @return replaced code
     **/
    private static function convertWordpress2Code( $pc, $singleline = false )
    {
        // on a single line we remove all tags
        $source = $singleline ? $pc : str_replace("</p>", "\n", $pc);
        
        // remove any other HTML tags and deconvert the entities
        $source = trim( html_entity_decode(strip_tags($source), ENT_COMPAT, get_option("blog_charset")));
        
        // convert special characters (eg quotes)
        return str_replace(array("“", "”", "‘", "’", "´", "`", "′"), array("\"", "\"", "'", "'", "'", "'", "'"), $source);
    }
    
    
    
    /** sets the GeSHi style data 
     * @note this function must be public, because the preview class use this also
     * @param $geshi geshi object
     * @param $style array with style information
     **/
    private static function setGeSHiStyle(&$geshi, $style)
    {
        // remove any style options of GeSHi, so we can set it with CSS
        $geshi->set_overall_style(null);
        $geshi->set_code_style(null);
        $geshi->set_line_style(null);
        
        // set the style options (we set the value for all groups)
        $geshi->set_comments_style( "MULTI", $style["comments"] );
        if (isset($geshi->language_data["COMMENT_SINGLE"]))
            foreach(array_keys($geshi->language_data["COMMENT_SINGLE"]) as $i)
                $geshi->set_comments_style( $i, $style["comments"] );
        
        if (isset($geshi->language_data["KEYWORDS"]))
            foreach(array_keys($geshi->language_data["KEYWORDS"]) as $i)
                $geshi->set_keyword_group_style( $i, $style["keywords"] );
        
        if (isset($geshi->language_data["OBJECT_SPLITTERS"]))
            foreach(array_keys($geshi->language_data["OBJECT_SPLITTERS"]) as $i)
                $geshi->set_methods_style( $i, $style["methods"] );
        
        if (isset($geshi->language_data["REGEXPS"]))
            foreach(array_keys($geshi->language_data["REGEXPS"]) as $i)
                $geshi->set_regexps_style( $i, $style["regex"]);
        
        $geshi->set_strings_style( $style["strings"] );
        $geshi->set_escape_characters_style( $style["escapechars"] );
        $geshi->set_highlight_lines_extra_style( $style["highlight"] );
        $geshi->set_numbers_style( $style["numbers"] );
        $geshi->set_symbols_style( $style["symbols"] );
        
        $geshi->set_link_styles(GESHI_LINK, $style["keywordlink"]);
        $geshi->set_link_styles(GESHI_HOVER, $style["keywordlink"]);
        $geshi->set_link_styles(GESHI_ACTIVE, $style["keywordactive"]);
        $geshi->set_link_styles(GESHI_VISITED, $style["keywordvisited"]);
    }
    
    
    /** sets the GeSHi main options
     * @param $geshi geshi object
     * @param $option option array
     * @param $htmlid ID of the HTML code block
     * @param $class class of the HTML code block
     **/
    private static function setGeSHiMainOptions(&$geshi, $option, $htmlid, $class = null)
    {
        $geshi->enable_strict_mode(GESHI_MAYBE);
        
        // on different languages we must disable the strict mode (eg on PHP if there are the php include tags)
        if ( (strtolower($geshi->language) == "php") || (strtolower($geshi->language) == "php-brief") )
            $geshi->enable_strict_mode(GESHI_NEVER);

        
        
        $geshi->set_overall_class(trim("geshisourcecolorer ".$class));
        $geshi->enable_ids(true);
        $geshi->set_header_type(GESHI_HEADER_DIV);
        $geshi->set_encoding(get_option("blog_charset"));
        $geshi->set_tab_width($option["tabsize"]);
        $geshi->enable_classes(!$option["geshicss"]);
        $geshi->enable_keyword_links($option["keywordref"]);
        $geshi->set_overall_id($htmlid);
        
        if ($option["keywordcase"] == "lower")
            $geshi->set_case_keywords(GESHI_CAPS_LOWER);
        if ($option["keywordcase"] == "upper")
            $geshi->set_case_keywords(GESHI_CAPS_UPPER);
        
        
        if (!empty($option["highlight"]))         
            $geshi->highlight_lines_extra($option["highlight"]);
        
        if ($option["linenumber"])
            $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);   
    }
    
    
    /** reads the parameter of the tag and set it into the default values
     * @param $pc input parameter string
     * @return array with values
     **/
    private static function getParameter($pc, $toolbar)
    {
        // read the default options and remove not used values
        $param              = get_option("fpx_geshisourcecolorer_option");
        $langtag            = $param["tag"]["language"];
        $styles             = $param["style"];
        $param["toolbar"]   = $param["toolbar"][$toolbar];
        unset($param["tag"]);
        
        if (!isset($param["toolbar"]["linenumber"]))
            $param["toolbar"]["linenumber"] = false;
        
        
        // split the parameters
        $tagparam   = preg_split('/\G(?:"[^"]*"|\'[^\']*\'|[^"\'\s]+)*\K\s+/', $pc, -1, PREG_SPLIT_NO_EMPTY);

        foreach($tagparam as $val)
        {
            // remove double / single quotes
            $lcTag = str_replace("\"", null, $val);
            $lcTag = str_replace("'", null, $lcTag);
            
            // find first occurence of = and split the string
            $laTag = preg_split('/=/', $lcTag, 2);
            if (count($laTag) == 2) {
        
                // split the key on the underline, because so we can create suboptions
                $keypath = explode("_", trim($laTag[0]));
                
                // split the tag data on the comma, because this option has got more than one value
                if (strpos($laTag[1], ",") === false)
                    $data = trim($laTag[1]);
                else
                    $data = explode(",", trim($laTag[1]));

                
                switch (count($keypath))
                {
                    // tag without underscore "_"
                    case 1 :
                        // the option "hoverhighlight" can be exist more than once
                        if ($keypath[0] != "hoverhighlight")
                            $param[$keypath[0]] = $data;
                        else {
                            if (!isset($param["hoverhighlight"]))
                                $param["hoverhighlight"] = array();
                            
                            // hoverhighlight need 2 or 3 arguments (iterate over the line numbers and set the information for jQuery)
                            switch (count($data))
                            {
                                case 2 :
                                    $param["hoverhighlight"][trim($data[0])."[]"] = trim($data[1]);
                                    break;
                                    
                                case 3 :
                                    $param["hoverhighlight"][trim($data[0])."[".trim($data[2])."]"] = trim($data[1]);
                                    break;
                                    
                                default :
                                    continue;
                            }
                        }
                        break;
                        
                    // tags with underscore "_"
                    case 2 :
                        $param[$keypath[0]][$keypath[1]] = $data;
                        break;
                        
                }
            }
        }
        
        // convert data types
        if ( (!isset($param[$langtag])) || (!is_string($param[$langtag])) )
            $param["language"] = "";
        else {
            $param["language"] = $param[$langtag];
            unset($param[$langtag]);
        }
        
        
        // read the style information, set style to default, if the style option is set
        // check if the style exists, if not, try to get a style that named equal to the
        // language name
        if ( (isset($param["style"])) && (is_string($param["style"])) && (isset($styles[ strtolower($param["style"]) ])) )
            $param["style"] = $styles[ strtolower($param["style"]) ];
        elseif ( (isset($param["style"])) && (is_string($param["style"])) && (isset($styles[strtolower($param["language"]) ])) )
            $param["style"] = $styles[ strtolower($param["language"]) ];
        else
            $param["style"] = $styles["default"];
        
        
        // check the highlight options and create if needed an array with line data
        if ( (isset($param["highlight"])) && (is_string($param["highlight"])) )
            $param["highlight"] = explode(" ", $param["highlight"]);
        else
            $param["highlight"] = array();
        
        // hoverhighlight is set (if there is highlighting which style is a null value, we set the default highlight)
        if (isset($param["hoverhighlight"])) {
            $new = array();
            foreach($param["hoverhighlight"] as $key => $val)
                $new[ str_replace("[]", "[".$param["style"]["highlight"]."]", $key) ] = $val;
            $param["hoverhighlight"] = $new;
        } else
            $param["hoverhighlight"] = array();
        
        // check tabbed setting
        if ( (isset($param["tab"])) && (is_string($param["tab"])) )
            $param["tab"] = preg_split("/\s+/", $param["tab"], 2, PREG_SPLIT_NO_EMPTY);
        else
            $param["tab"] = array();
        
        // check other options
        if  ( (!isset($param["keywordcase"])) || (!is_string($param["keywordcase"])) )
            $param["keywordcase"] = "";
        $param["keywordcase"] = strtolower($param["keywordcase"]);

        if  (!isset($param["id"]))
            $param["id"] = "";
        
        if  ( (!isset($param["lol"])) || (!is_string($param["lol"])) )
            $param["lol"] = "";
        
        $param["lolhead"] = (isset($param["lolhead"])) && (is_string($param["lolhead"])) && ($param["lolhead"] == "true");
        
        // if a correct value (type correct) is set, the value is get from the default options, otherwise we must convert it
        if (!is_int($param["tabsize"]))
            $param["tabsize"] = abs(intval($param["tabsize"]));
        if (!is_bool($param["linenumber"]))
            $param["linenumber"] = $param["linenumber"] == "true";
        if (!is_bool($param["collapse"]))
            $param["collapse"] = $param["collapse"] == "true";
        if (!is_bool($param["geshicss"]))
            $param["geshicss"] = $param["geshicss"] == "true";
        if (!is_string($param["keywordref"]))
            $param["keywordref"] = $param["keywordref"] == "true";
        
        if (!is_bool($param["toolbar"]["sourcewindow"]))
            $param["toolbar"]["sourcewindow"]             = $param["toolbar"]["sourcewindow"] == "true";
        if (!is_bool($param["toolbar"]["copyclipboard"]))
            $param["toolbar"]["copyclipboard"]           = $param["toolbar"]["copyclipboard"] == "true";
        if (!is_bool($param["toolbar"]["linenumber"]))
            $param["toolbar"]["linenumber"]              = $param["toolbar"]["linenumber"] == "true";
        
        return $param;
    }

}

?>