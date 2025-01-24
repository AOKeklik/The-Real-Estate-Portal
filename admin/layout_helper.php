<?php
    function setPageTitle ($pagename) {
        $patterns = [
            "setting" => "Setting - Admin Panel",
            "order" => "Orders - Admin Panel",
            "message" => "Messages - Admin Panel",
            "customer" => "Users - Admin Panel",
            "agent" => "Users - Admin Panel",
            "package" => "Package - Admin Panel",
            "location" => "Location - Admin Panel",
            "type" => "Types - Admin Panel",
            "amenit" => "Amenity - Admin Panel",
            "propert" => "Property - Admin Panel",
            "why_choose" => "Why Choose Section - Admin Panel",
            "testimonial" => "Testimonials Section - Admin Panel",
            "post" => "Blog - Admin Panel",
            "faq" => "Faqs - Admin Panel",
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
            "faq" => BASE_URL."faqs",
        ];

        foreach($patterns as $key=>$val) {
            if(strpos($pagename,$key) !== false)
                return $val;
        }

        return BASE_URL;
    }
