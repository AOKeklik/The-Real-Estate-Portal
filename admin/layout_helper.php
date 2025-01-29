<?php
    function setPageTitle ($pagename) {
        $patterns = [
            "setting" => "Setting",
            "order" => "Orders",
            "message" => "Messages",
            "customer" => "Customers",
            "agent" => "Agents",
            "subscriber" => "Subscribers",
            "package" => "Package",
            "location" => "Location",
            "type" => "Types",
            "amenit" => "Amenity",
            "propert" => "Property",
            "why_choose" => "Why Choose",
            "testimonial" => "Testimonials",
            "post" => "Blog",
            "faq" => "Faqs",
            "privacy" => "Privacy Policy",
            "term" => "Terms of Use",
        ];

        foreach($patterns as $key=>$val) {
            if(strpos($pagename, $key) !== false)
                return $val /* . " - Admin Panel" */;
        }
            
        return "Dashboard - Admin Panel";
    }

    function setForntendButtonLink ($pagename) {
        $patterns = [
            "location" => BASE_URL."locations",
            "propert" => BASE_URL."properties",
            "posts" => BASE_URL."posts",
            "faq" => BASE_URL."faqs",
            "privacy" => BASE_URL."privacy-policy",
            "term" => BASE_URL."terms-of-use",
        ];

        foreach($patterns as $key=>$val) {
            if(strpos($pagename,$key) !== false)
                return $val;
        }

        return BASE_URL;
    }
