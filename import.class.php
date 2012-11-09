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

/** class for different import versions **/
class import {
    
    /** run the import
     * @param $option input options (array)
     * @param $data array with import data
     * @param $pa settings for the import (array)
     * @return new option array (or null if nothing can be imported)
     **/
    static function run( $option, $data, $pa, $importversion, $pluginversion )
    {
        if (round($importversion,2) == round($pluginversion,2))
            return self::SelfVersion($option, $data, $pa);
        
        // version 0.12 & 0.13 handling (versions are equal)
        if (round($importversion,2) == 0.12)
            return self::SelfVersion($option, $data, $pa);
        
        // version 0.10 & 0.11 handling
        if ( (round($importversion,2) == 0.10) || (round($importversion,2) == 0.11) )
            return self::Version01($option, $data, $pa);
        
        return null;
    }
    
    
    
    /** imports the data of the same version
     * @param $option input options (array)
     * @param $data array with import data
     * @param $pa settings for the import (array)
     **/
    private static function SelfVersion($options, $data, $pa)
    {
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
            $options["visualeditor"]                        = $data["visualeditor"]; 
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
            $options["css"]["line"]                         = $data["css"]["line"];
            $options["css"]["block"]                        = $data["css"]["block"];
            $options["css"]["tab"]                          = $data["css"]["tab"];
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
        
        return $options;
    }
    
    
    /** imports the data of the version 0.1 & 0.11
     * @param $option input options (array)
     * @param $data array with import data
     * @param $pa settings for the import (array)
     **/
    private static function Version01($options, $data, $pa)
    {
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
        
        return $options;
    }
    
}

?>