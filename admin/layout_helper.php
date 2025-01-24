<?php
    function setPageTitle ($pagename) {
        $patterns = [
            "setting" => "Setting - Admin Panel",
            "package" => "Package - Admin Panel",
            "location" => "Location - Admin Panel",
            "type" => "Types - Admin Panel",
            "amenit" => "Amenity - Admin Panel",
            "propert" => "Property - Admin Panel",
            "post" => "Blog - Admin Panel",
        ];

        foreach($patterns as $key=>$val) {
            if(strpos($pagename, $key) !== false)
                return $val;
        }
            
        return "Dashboard - Admin Panel";
    }

    function setForntendButtonLink ($pagename) {
        $patterns = [
            "location" => BASE_URL."locations",
            "propert" => BASE_URL."properties",
            "posts" => BASE_URL."posts",
        ];

        foreach($patterns as $key=>$val) {
            if(strpos($pagename,$key) !== false)
                return $val;
        }

        return BASE_URL;
    }
