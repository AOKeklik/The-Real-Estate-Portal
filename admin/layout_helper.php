<?php
    function setPageTitle ($pagename) {
        $patterns = [
            "setting" => "Setting - Admin Panel",
            "package" => "Package - Admin Panel",
            "location" => "Location - Admin Panel",
            "type" => "Types - Admin Panel",
            "amenit" => "Amenity - Admin Panel",
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
        ];

        foreach($patterns as $key=>$val) {
            if(strpos($pagename,$key) !== false)
                return $val;
        }

        return BASE_URL;
    }
