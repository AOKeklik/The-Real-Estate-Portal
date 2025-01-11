<?php 
    include "./layout_top.php";

    try {
        $stmtLocations = $pdo->prepare("
            SELECT
                locations.*,
                COUNT(properties.id) as property_count
            FROM
                locations
            INNER JOIN
                properties on properties.location_id=locations.id
            INNER JOIN
                orders on orders.agent_id=properties.agent_id
            WHERE
                now() between orders.purchase_date AND orders.expire_date 
            AND 
                orders.currently_active=?
            GROUP BY
                locations.id
            ORDER BY
                locations.name ASC
        ");
        $stmtLocations->execute([1]);
        $locations = $stmtLocations->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $err) {   
        $error_message = $err->getMessage();
    }

    try {
        $stmtTypes = $pdo->prepare("
            select 
                * 
            from 
                types 
            order by 
                name asc
        ");
        $stmtTypes->execute();
        $types = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $err) {
        $error_message = $err->getMessage();
    }

    try{
        $stmtProperties = $pdo->prepare("
            SELECT
                properties.*,
                locations.name as location_name,
                types.name as type_name,
                agents.full_name,
                agents.photo,
                agents.company
            FROM
                properties
            INNER JOIN
                orders ON orders.agent_id=properties.agent_id
            LEFT JOIN
                locations on locations.id=properties.location_id
            LEFT JOIN
                types on types.id=properties.type_id
            LEFT JOIN
                agents on agents.id=properties.agent_id
            WHERE
                now() BETWEEN orders.purchase_date AND orders.expire_date
            AND
                orders.currently_active=?
            AND
                properties.is_featured=?
            ORDER BY
                rand()
            LIMIT 
                6
        ");
        $stmtProperties->execute([1,1]);
        $properties=$stmtProperties->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtAgents=$pdo->prepare("
            SELECT
                agents.*
            FROM
                agents
            INNER JOIN
                orders ON orders.agent_id=agents.id
            WHERE
                agents.status=1 
            AND
                now() BETWEEN orders.purchase_date AND orders.expire_date
            AND
                orders.currently_active=?
            ORDER BY
                rand()
            LIMIT
            4
        ");
        $stmtAgents->execute([1]);
        $agents=$stmtAgents->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

    <div class="slider" style="background-image: url(https://placehold.co/1200x540)">
        <div class="bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="item">
                        <div class="text">
                            <h2>Discover Your New Home</h2>
                            <p>
                                You can get your desired awesome properties, homes, condos etc. here by name, category or location.
                            </p>
                        </div>
                        <div class="search-section">
                            <form action="<?php echo BASE_URL?>properties" method="GET">
                                <div class="inner">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <input type="text" name="search" class="form-control" placeholder="Find Anything ...">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select name="location_id" class="form-select select2">
                                                    <option value="">Select Location</option>
                                                    <?php if($stmtLocations->rowCount() > 0): foreach($locations as $loc):?>
                                                            <option value="<?php echo $loc["id"]?>"><?php echo $loc["name"]?></option>
                                                    <?php endforeach;endif?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <select name="type_id" class="form-select select2">
                                                    <option value="">Select Type</option>
                                                    <?php if($stmtTypes->rowCount() > 0): foreach($types as $type):?>
                                                            <option value="<?php echo $type["id"]?>"><?php echo $type["name"]?></option>
                                                    <?php endforeach;endif?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="property">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Featured Properties</h2>
                        <p>Find out the awesome properties that you must love</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if($stmtProperties->rowCount() > 0): foreach($properties as $property):?>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="item">
                            <div class="photo">
                                <img class="main" src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["featured_photo"]?>" alt="">
                                <div class="top">
                                    <?php if(preg_match("/sale/i", $property["purpose"])):?>
                                        <div class="status-sale">For Sale</div>
                                    <?php else:?>
                                        <div class="status-rent">For Rent</div>
                                    <?php endif?>
                                    <?php if($property["is_featured"] == 1):?>
                                        <div class="featured">Featured</div>
                                    <?php endif?>
                                </div>
                                <div class="price"><?php echo $property["price"]?> PLN</div>
                                <div class="wishlist"><a href=""><i class="far fa-heart"></i></a></div>
                            </div>
                            <div class="text">
                                <h3><a href="<?php echo BASE_URL?>property/<?php echo $property["id"]?>/<?php echo $property["slug"]?>"><?php echo $property["name"]?></a></h3>
                                <div class="detail">
                                    <div class="stat">
                                        <div class="i1"><?php echo $property["size"]?> sqft</div>
                                        <div class="i2"><?php echo $property["bedroom"]?> Bed</div>
                                        <div class="i3"><?php echo $property["bathroom"]?> Bath</div>
                                    </div>
                                    <div class="address">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo $property["address"]?>
                                    </div>
                                    <div class="type-location">
                                        <div class="i1">
                                            <i class="fas fa-edit"></i> <?php echo $property["type_name"]?>
                                        </div>
                                        <div class="i2">
                                            <i class="fas fa-location-arrow"></i> <?php echo $property["location_name"]?>
                                        </div>
                                    </div>
                                    <div class="agent-section">
                                        <?php if(is_null($property["photo"])):?>
                                            <img class="agent-photo" src="<?php echo PUBLIC_URL?>uploads/user.png?>" alt="">
                                        <?php else:?>
                                            <img class="agent-photo" src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $property["photo"]?>" alt="">
                                        <?php endif?>
                                        <a href="">
                                            <?php echo $property["full_name"]?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;endif?>
            </div>
        </div>
    </div>


    <div class="why-choose" style="background-image: url(https://placehold.co/1000x660)">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Why Choose Us</h2>
                        <p>
                            Describing why we are best in the property business
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="inner">
                        <div class="icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="text">
                            <h2>Years of Experience</h2>
                            <p>
                                With decades of combined experience in the industry, our agents have the expertise and knowledge to provide you with a seamless home-buying experience.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="inner">
                        <div class="icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="text">
                            <h2>Competitive Prices</h2>
                            <p>
                                We understand that buying a home is a significant investment, which is why we strive to offer competitive prices to our clients.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="inner">
                        <div class="icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div class="text">
                            <h2>Responsive Communication</h2>
                            <p>
                                Our responsive agents are here to answer your questions and address your concerns, ensuring a smooth and stress-free home-buying experience.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="agent">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Agents</h2>
                        <p>
                            Meet our expert property agents from the following list
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if($stmtAgents->rowCount() > 0): foreach($agents as $agent):?>
                    <div class="col-lg-3 col-md-3">
                        <div class="item">
                            <div class="photo">
                                <a href="<?php echo BASE_URL?>agent/<?php echo $agent["id"]?>/<?php echo $agent["slug"]?>">
                                    <?php if(is_null($agent["photo"])):?>
                                        <img src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                    <?php else:?>
                                        <img src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $agent["photo"]?>" alt="">
                                    <?php endif?>
                                </a>
                            </div>
                            <div class="text">
                                <h2>
                                    <a href="<?php echo BASE_URL?>agent/<?php echo $agent["id"]?>/<?php echo $agent["slug"]?>">
                                        <?php echo $agent["full_name"]?>
                                    </a>
                                </h2>
                            </div>
                        </div>
                    </div>
                <?php endforeach;endif?>
            </div>
        </div>
    </div>


    <div class="location pb_40">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Locations</h2>
                        <p>
                            Check out all the properties of important locations
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if($stmtLocations->rowCount() > 0): foreach($locations as $location):?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="item">
                                <div class="photo">
                                    <a href="<?php echo BASE_URL?>location/<?php echo $location["slug"]?>">
                                        <img src="<?php echo PUBLIC_URL?>uploads/location/<?php echo $location["photo"]?>" alt="<?php echo $location["name"]?>">
                                    </a>
                                </div>
                                <div class="text">
                                    <h2><a href="<?php echo BASE_URL?>location/<?php echo $location["slug"]?>"><?php echo $location["name"]?></a></h2>
                                    <h4>(<?php echo $location["property_count"]?> Properties)</h4>
                                </div>
                            </div>
                        </div>
                <?php endforeach;endif?>
            </div>
        </div>
    </div>


    <div class="testimonial" style="background-image: url(https://placehold.co/1000x660)">
        <div class="bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="main-header">Our Happy Clients</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="testimonial-carousel owl-carousel">
                        <div class="item">
                            <div class="photo">
                                <img src="url(https://placehold.co/500x500)" alt="" />
                            </div>
                            <div class="text">
                                <h4>Robert Krol</h4>
                                <p>CEO, ABC Company</p>
                            </div>
                            <div class="description">
                                <p>
                                    I recently worked with Patrick Johnson on purchasing my dream home and I couldn't have asked for a better experience. Patrick Johnson was knowledgeable, professional, and truly cared about finding me the perfect property. They were always available to answer my questions and made the entire process stress-free. I highly recommend Patrick Johnson to anyone looking to buy or sell a property!
                                </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="photo">
                                <img src="url(https://placehold.co/500x500)" alt="" />
                            </div>
                            <div class="text">
                                <h4>Sal Harvey</h4>
                                <p>Director, DEF Company</p>
                            </div>
                            <div class="description">
                                <p>
                                    I had the pleasure of working with Smith Brent during my recent home search and I can't speak highly enough of their services. Smith Brent listened to my needs and helped me find the perfect home that met all of my requirements. They were always there for me, from the initial search to closing, and made the process seamless and enjoyable. I would recommend Smith Brent to anyone looking for an experienced and dedicated real estate agent.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="blog">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>Latest News</h2>
                        <p>
                            Check our latest news from the following section
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="item">
                        <div class="photo">
                            <img src="https://placehold.co/1000x600" alt="" />
                        </div>
                        <div class="text">
                            <h2>
                                <a href="post.html">5 Tips for Finding Your Dream Home</a>
                            </h2>
                            <div class="short-des">
                                <p>
                                    Lorem ipsum dolor sit amet, nibh saperet
                                    te pri, at nam diceret disputationi. Quo
                                    an consul impedit, usu possim evertitur
                                    dissentiet ei.
                                </p>
                            </div>
                            <div class="button">
                                <a href="post.html" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="item">
                        <div class="photo">
                            <img src="https://placehold.co/1000x600" alt="" />
                        </div>
                        <div class="text">
                            <h2>
                                <a href="post.html">Pros & Cons of Renting vs. Buying</a>
                            </h2>
                            <div class="short-des">
                                <p>
                                    Nec in rebum primis causae. Affert
                                    iisque ex pri, vis utinam vivendo
                                    definitionem ad, nostrum omnes que per
                                    et. Omnium antiopam.
                                </p>
                            </div>
                            <div class="button">
                                <a href="post.html" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="item">
                        <div class="photo">
                            <img src="https://placehold.co/1000x600" alt="" />
                        </div>
                        <div class="text">
                            <h2>
                                <a href="post.html">Maximizing Your Investment in 2023</a>
                            </h2>
                            <div class="short-des">
                                <p>
                                    Id pri placerat voluptatum, vero dicunt
                                    dissentiunt eum et, adhuc iisque vis no.
                                    Eu suavitate conten tiones definitionem
                                    mel, ex vide.
                                </p>
                            </div>
                            <div class="button">
                                <a href="post.html" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include "./layout_footer.php"?>