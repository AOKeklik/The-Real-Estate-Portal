<?php
    include "./layout_top.php";

    try{
        $search= isset($_GET["search"]) ? htmlspecialchars(trim($_GET["search"])) : "";
        $location_id= isset($_GET["location_id"]) ? htmlspecialchars(trim($_GET["location_id"])) : "";
        $type_id= isset($_GET["type_id"]) ? htmlspecialchars(trim($_GET["type_id"])) : "";
        $purpose= isset($_GET["purpose"]) ? htmlspecialchars(trim($_GET["purpose"])) : "";
        $amenity_id= isset($_GET["amenity_id"]) ? htmlspecialchars(trim($_GET["amenity_id"])) : "";
        $bedroom= isset($_GET["bedroom"]) ? htmlspecialchars(trim($_GET["bedroom"])) : "";
        $bathroom= isset($_GET["bathroom"]) ? htmlspecialchars(trim($_GET["bathroom"])) : "";
        $price= isset($_GET["price"]) ? htmlspecialchars(trim($_GET["price"])) : "";

        $sql="
            SELECT
                properties.*,
                types.name as type_name,
                locations.name as location_name,
                agents.full_name as agent_name,
                agents.photo
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
        ";
        $params = [];
        $condition = [];

        if(!empty($amenity_id)){
            $sql.=" inner join amenities on find_in_set(?,properties.amenities)";
            $params[]=$amenity_id;
        }

        if(!empty($location_id)) {
            $condition[] = " location_id=?";
            $params [] = $location_id;
        }

        if(!empty($type_id)){
            $condition[] = " type_id=?";
            $params [] = $type_id;
        }
        
        if(!empty($purpose)){
            $condition[] = " purpose=?";
            $params [] = $purpose;
        }

        if(!empty($search)){
            $condition[]= "properties.name like ?";
            $params [] = "%".$search."%";
        }

        if(!empty($amenities)){
            $condition[]= "properties.name like ?";
            $params [] = "%".$search."%";
        }

        if(!empty($bedroom)){
            $condition[] = "bedroom=?";
            $params [] = $bedroom;
        }

        if(!empty($bathroom)){
            $condition[] = "bathroom=?";
            $params [] = $bathroom;
        }

        if(!empty($price)){
            list($min,$max) = explode("-",$price);
            $condition[] = "properties.price>=? and properties.price<=?";
            $params [] = $min;
            $params [] = $max;
        }

        $condition[] = "now() BETWEEN orders.purchase_date AND orders.expire_date";
        $condition[] = "orders.currently_active=1";

        if(!empty($condition)) {
            $sql.= " WHERE " . implode(" AND ", $condition);
        }

        if(!empty($amenity_id))
            $sql.=" group by properties.id";

        if(!empty($price))
            $sql.= " order by properties.price asc";
        else
            $sql.= " order by properties.name asc";

        $stmtPro =  $pdo->prepare($sql);
        $stmtPro->execute($params);
        $properties=$stmtPro->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtLoc=$pdo->prepare("
            select DISTINCT
                locations.id,
                locations.name
            from
                properties
            left join
                locations on locations.id=properties.location_id
            order by 
                locations.name asc
        ");
        $stmtLoc->execute([]);
        $locations=$stmtLoc->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtTyp=$pdo->prepare("
            select DISTINCT
                types.id,
                types.name
            from
                properties
            left JOIN
                types on types.id=properties.type_id
            order by 
                types.name asc
        ");
        $stmtTyp->execute();
        $types=$stmtTyp->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtAme=$pdo->prepare("
            select 
                *
            from
                amenities
            order by 
                name asc
        ");
        $stmtAme->execute();
        $amenities=$stmtAme->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtBed=$pdo->prepare("
            select distinct
                bedroom
            from
                properties
            where 
                bedroom is not null
            order by 
                bedroom asc
        ");
        $stmtBed->execute();
        $bedrooms=$stmtBed->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtBat=$pdo->prepare("
            select distinct
                bathroom
            from
                properties
            where 
                bathroom is not null
            order by 
                bathroom asc
        ");
        $stmtBat->execute();
        $bathrooms=$stmtBat->fetchAll(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    if(isset($_SESSION["customer"])) {
        try{
            $stmtWishlist=$pdo->prepare("
                SELECT
                    *
                FROM
                    wishlists
                WHERE
                    customer_id=?
                ORDER BY
                    id ASC
            ");
            $stmtWishlist->execute([$_SESSION["customer"]["id"]]);
            $wishlists=$stmtWishlist->fetchAll(pdo::FETCH_ASSOC);
        }catch(PDOException $err){
            $error_message=$err->getMessage();
        }
    }
?>
<div class="page-top" style="background-image: url('')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Property Listing</h2>
            </div>
        </div>
    </div>
</div>

<div class="property-result">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <form class="property-filter" action="" method="get">
                    <div class="widget">
                        <h2>Find Anything</h2>
                        <input value="<?php if(isset($_GET["search"])) echo $_GET["search"]?>" type="text" name="search" class="form-control" placeholder="Search Titles ..." />
                    </div>

                    <div class="widget">
                        <h2>Location</h2>
                        <select name="location_id" class="form-control select2">
                            <option value="">All Locations</option>
                            <?php if($stmtLoc->rowCount() > 0): foreach($locations as $location):?>
                                <option value="<?php echo $location["id"]?>" <?php if(isset($_GET["location_id"]) && $_GET["location_id"] == $location["id"]) echo "selected"?>>
                                    <?php echo $location["name"]?>
                                </option>
                            <?php endforeach;endif?>
                        </select>
                    </div>

                    <div class="widget">
                        <h2>Type</h2>
                        <select name="type_id" class="form-control select2">
                            <option value="">All Types</option>
                            <?php if($stmtTyp->rowCount() > 0): foreach($types as $type):?>
                                <option value="<?php echo $type["id"]?>" <?php if(isset($_GET["type_id"]) && $_GET["type_id"] == $type["id"]) echo "selected"?>>
                                    <?php echo $type["name"]?>
                                </option>
                            <?php endforeach;endif?>
                        </select>
                    </div>

                    <div class="widget">
                        <h2>Purpose</h2>
                        <select name="purpose" class="form-control select2">
                            <option value="">All Purpose</option>
                            <option value="For Rent" <?php if(isset($_GET["purpose"]) && $_GET["purpose"] == "For Rent") echo "selected" ?>>For Rent</option>
                            <option value="For Sale" <?php if(isset($_GET["purpose"]) && $_GET["purpose"] == "For Sale") echo "selected" ?>>For Sale</option>
                        </select>
                    </div>

                    <div class="widget">
                        <h2>Amenities</h2>
                        <select name="amenity_id" class="form-control select2">
                            <option value="">All Amenities</option>
                            <?php if($stmtAme->rowCount() > 0): foreach($amenities as $amenity):?>
                                <option value="<?php echo $amenity["id"]?>" <?php if(isset($_GET["amenity_id"]) && $_GET["amenity_id"] == $amenity["id"]) echo "selected"?>>
                                    <?php echo $amenity["name"]?>
                                </option>
                            <?php endforeach;endif?>
                        </select>
                    </div>

                    <div class="widget">
                        <h2>Bedrooms</h2>
                        <select name="bedroom" class="form-control select2">
                            <option value="">All Bedrooms</option>
                            <?php if($stmtBed->rowCount() > 0): foreach($bedrooms as $bedroom):?>
                                <option value="<?php echo $bedroom["bedroom"]?>" <?php if(isset($_GET["bedroom"]) && $_GET["bedroom"] == $bedroom["bedroom"]) echo "selected"?>>
                                    <?php echo $bedroom["bedroom"]?>
                                </option>
                            <?php endforeach;endif?>
                        </select>
                    </div>

                    <div class="widget">
                        <h2>Bathrooms</h2>
                        <select name="bathroom" class="form-control select2">
                            <option value="">All Bathrooms</option>
                            <?php if($stmtBed->rowCount() > 0): foreach($bathrooms as $bathroom):?>
                                <option value="<?php echo $bathroom["bathroom"]?>" <?php if(isset($_GET["bathroom"]) && $_GET["bathroom"] == $bathroom["bathroom"]) echo "selected"?>>
                                    <?php echo $bathroom["bathroom"]?>
                                </option>
                            <?php endforeach;endif?>
                        </select>
                    </div>

                    <div class="widget">
                        <h2>Price</h2>
                        <select name="price" class="form-control select2">
                            <option value="">All Prices</option>
                            <option value="10-5000" <?php if(isset($_GET["price"]) && $_GET["price"] == "10-5000") echo "selected"?>>
                                10 PLN 5000 PLN
                            </option>
                            <option value="5000-30000" <?php if(isset($_GET["price"]) && $_GET["price"] == "5000-30000") echo "selected"?>>
                                5000 PLN 30000 PLN
                            </option>
                            <option value="30000-70000" <?php if(isset($_GET["price"]) && $_GET["price"] == "30000-70000") echo "selected"?>>
                                30000 PLN 70000 PLN
                            </option>
                            <option value="70000-130000" <?php if(isset($_GET["price"]) && $_GET["price"] == "70000-130000") echo "selected"?>>
                                70000 PLN 130000 PLN
                            </option>
                        </select>
                    </div>

                    <div class="filter-button">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="property">
                    <div class="container" id="pagination">
                        <div id="pagination-loader"></div>

                        <div id="pagination-body">
                            <div class="row" id="pagination-data">
                                <?php if($stmtPro->rowCount() > 0): foreach($properties as $property):?>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="item">
                                            <div class="photo">
                                                <img class="main" src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["featured_photo"]?>" alt="">
                                                <div class="top">
                                                    <?php if(preg_match("/sale/i",$property["purpose"])):?>
                                                        <div class="status-sale">For Sale</div>
                                                    <?php else:?>
                                                        <div class="status-rent">For Rent</div>
                                                    <?php endif?>
                                                    <?php if($property["is_featured"] == 1):?>
                                                        <div class="featured">Featured</div>
                                                    <?php endif?>
                                                </div>
                                                <div class="price"><?php echo $property["price"]?> PLN</div>
                                                <?php if(isset($_SESSION["customer"])):?>
                                                    <div 
                                                        data-property-id="<?php echo $property["id"]?>" 
                                                        data-customer-id="<?php echo $_SESSION["customer"]["id"]?>" 
                                                        class="wishlist <?php if(in_array($property["id"], array_column($wishlists,"property_id"))) echo "active"?>"
                                                    >
                                                        <div class="wishlist-loader"></div>
                                                        <a href="" class="wishlist-heart"><i class="far fa-heart"></i></a>
                                                        <a href="" class="wishlist-heart-full"><i class="fa fa-heart"></i></a>
                                                    </div>
                                                <?php endif?>
                                            </div>
                                            <div class="text">
                                                <h3>
                                                    <a href="<?php echo BASE_URL?>property/<?php echo $property["id"]?>/<?php echo $property["slug"]?>">
                                                        <?php echo $property["name"]?>
                                                    </a>
                                                </h3>
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
                                                            <img class="agent-photo" src="<?php echo PUBLIC_URL?>uploads/user.png" alt="">
                                                        <?php else:?>
                                                            <img class="agent-photo" src="<?php echo PUBLIC_URL?>uploads/agent/<?php echo $property["photo"]?>" alt="">
                                                        <?php endif?>
                                                        <a href="<?php echo BASE_URL?>">
                                                            <?php echo $property["agent_name"]?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;endif?>
                            </div>
                            
                            <div id="pagination-control">
                                <nav aria-label="Page navigation" class="d-flex justify-content-between align-items-center">
                                    <span></span>
                                    <ul class="pagination m-0">
                                        <!-- Pagination links will be generated here -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /* pagination */
    $(document).ready(function(){
        const itemsPerPage = Number("<?php echo MAX_POSTS_PER_PAGE?>")
        let currentPage = 1

        function hideLoaderAndShowBody () {
            setTimeout(() => {
                $("#pagination-loader").hide()
                $("#pagination-body").show()
            },1000)
        }
        hideLoaderAndShowBody ()

        function displayItems (page=1) {
            const start = (page - 1) * itemsPerPage
            const end = start + itemsPerPage

            $("#pagination-data").children().hide().slice(start,end).show()

            const totalItems = $("#pagination-data").children().length
            const totalPages = Math.ceil(totalItems/itemsPerPage)
            let paginationLink = ""

            if(totalPages>1)
                $("#pagination-control").show()
            else
                $("#pagination-control").hide()

            paginationLink += `
                <li class="page-item ${page === 1 ? "disabled" : ""}">
                    <a class="page-link" href="" data-page="${page-1}">
                        <span aria-hiddien="true">&laquo;</span>
                    </a>
                </li>
            `

            for(let i=1;i<=totalPages;i++){
                paginationLink +=`
                    <li class="page-item ${i === page ? "disabled" : ""}">
                        <a class="page-link" href="" data-page="${i}">${i}</a>
                    </li>
                `
            }

            paginationLink += `
                <li class="page-item ${page === totalPages ? "disabled" : ""}">
                    <a class="page-link" href="" data-page="${page+1}">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `

            $("#pagination-control span").html(`Total Items ${totalItems}`)
            $("#pagination-control ul").html(paginationLink)
        }
        displayItems (currentPage)

        $(document).on("click",".page-link",function(e){
            e.preventDefault()

            const totalItems = $("#pagination-data").children().length
            const itemsPerPage = "<?php echo MAX_POSTS_PER_PAGE?>"

            const totalPages = Math.ceil(totalItems/itemsPerPage)
            const nextPage = $(this).data("page")

            if(nextPage>=1 && nextPage<=totalPages){
                currentPage = nextPage
                displayItems(currentPage)
            }
        })
    })
    // $(document).ready(function(){
    //     function getQueryParam(param){
    //         const urlParams = new URLSearchParams(window.location.search)
    //         return urlParams.get(param)
    //     }

    //     const searchQuer = getQueryParam("search")

    //     if(searchQuer){
    //         $("#pagination-data").each(function(i,el){
    //             const content = $(el).html()
    //             const regex = new RegExp(`(${searchQuer})`,"gi")
    //             const highlighted = content.replace(regex,"<span class='highlight'>$1</span>")
    //             $(this).html(highlighted)
    //         })
    //     }
    // })

    /* wishlist */
    $(document).ready(function(){
        function handlerClickWishlistButton () {
            $(".wishlist").click( async function(e){
                e.preventDefault()
                    
                $(this).addClass("pending")
                $(this).closest(".item").css("pointer-events","none")
                
                await new Promise((resolve) => setTimeout(resolve,1000))

                const formData = new FormData()

                formData.append("property_id",btoa($(this).data("property-id")))
                formData.append("customer_id",btoa($(this).data("customer-id")))

                if($(this).hasClass("active"))
                    removeItemFromTheWishlist (formData, $(this))
                else
                    addItemToTheWishlist (formData, $(this)) 
            })
        }

        handlerClickWishlistButton()

        function addItemToTheWishlist(formData, el){
            $.ajax({
                url:"<?php echo BASE_URL?>customer_wishlist_add.php",
                type:"POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(responsive){
                    const res = JSON.parse(responsive)

                    iziToast.show({
                        title: res.error?.message ?? res.success.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
                    })

                    if(res.success) {
                        el.addClass("active")
                    }
                    
                    el.closest(".item").css("pointer-events","")
                    el.removeClass("pending")
                    // console.log(res)
                }
            }) 
        }

        function removeItemFromTheWishlist(formData, el){
            $.ajax({
                url:"<?php echo BASE_URL?>customer_wishlist_remove.php",
                type:"POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(responsive){
                    const res = JSON.parse(responsive)

                    iziToast.show({
                        title: res.error?.message ?? res.success.message,
                        position: "topRight",
                        color: res.error ? "red" : "green"
                    })

                    if(res.success){
                        el.removeClass("active")
                    }
                    
                    el.closest(".item").css("pointer-events","")
                    el.removeClass("pending")
                    // console.log(res)
                }
            })                
        }
    })
</script>
<?php include "./layout_footer.php"?>