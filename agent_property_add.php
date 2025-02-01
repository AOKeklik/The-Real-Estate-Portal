<?php
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    try{
        $stmt = $pdo->prepare("
            SELECT 
                * 
            FROM
                orders
            WHERE
                agent_id=? AND purchase_date>expire_date AND currently_active=?
        ");
        $stmt->execute([$_SESSION["agent"]["id"],1]);

        if($stmt->rowCount() > 0) {
            $_SESSION["error"] = "Your active package has expired. Please select a package to create a property!";
            header("Location: ".BASE_URL."agent-payment");
            exit();
        }

        $stmt=$pdo->prepare("
            select 
                count(properties.id) as total_properties, 
                packages.allowed_properties
            from 
                orders
            join 
                packages ON orders.package_id = packages.id
            left join 
                properties on properties.agent_id = orders.agent_id
            where 
                orders.agent_id=? and orders.currently_active=?
            group by
                packages.id;
        ");
        $stmt->execute([$_SESSION["agent"]["id"],1]);
        $query = $stmt->fetch(pdo::FETCH_ASSOC);

        if($stmt->rowCount() == 0) {
            $_SESSION["error"] = "No active package found. Please select a package to create a property!";
            header("Location: ".BASE_URL."agent-payment");
            exit();
        }

        if($query["allowed_properties"] != -1 && $query["total_properties"] >= $query["allowed_properties"])
            throw new PDOException("You have already added your maximum number of allowed properties. <br>Please upgrade your package. <br> Or please remove any of the added properties in order to add a new one!");

    }catch(PDOException $err){
        $_SESSION["error"] = $err->getMessage();
        header("Location: ".BASE_URL."agent-properties");
        exit();
    }

    try{
        $stmt = $pdo->prepare("
            select
                count(properties.id) as featured_properties,
                packages.allowed_featured_properties
            from
                agents
            LEFT JOIN
                orders on orders.agent_id=agents.id
            left join
                packages on packages.id=orders.package_id
            left join 
                properties on properties.is_featured=1 and properties.agent_id=agents.id
            where
                agents.id=? and orders.currently_active=?
            group by 
                packages.allowed_featured_properties
            limit 
                1
        ");
        $stmt->execute([$_SESSION["agent"]["id"],1]);
        $package=$stmt->fetch(PDO::FETCH_ASSOC);
        
        $is_allow_featured = true;

        if(empty($package)) 
            $is_allow_featured = false;
        else {
            if($package["featured_properties"] >= $package["allowed_featured_properties"])
            $is_allow_featured = false;
        } 

    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }


    try {
        $stmtLoc = $pdo->prepare("
            select 
                * 
            from 
                locations 
            order by 
                name asc
            ");
        $stmtLoc->execute();
        $allLocations = $stmtLoc->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $err){
        $error_message = $err->getMessage();
    }

    try{
        $stmtTyp = $pdo->prepare("
            select 
                * 
            from 
                types 
            order by 
                name asc
            ");
        $stmtTyp->execute();
        $allTypes = $stmtTyp->fetchAll(PDO::FETCH_ASSOC);
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
        $allAmenities=$stmtAme->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    $errors = [];

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["form"])){
        $name = htmlspecialchars(trim($_POST["name"]));
        $slug = htmlspecialchars(trim($_POST["slug"]));
        $price = htmlspecialchars(trim($_POST["price"]));
        $description = htmlspecialchars(trim($_POST["description"]));
        $location_id = htmlspecialchars(trim($_POST["location_id"]));
        $type_id = htmlspecialchars(trim($_POST["type_id"]));
        $purpose = htmlspecialchars(trim($_POST["purpose"]));
        $bedroom = htmlspecialchars(trim($_POST["bedroom"]));
        $bathroom = htmlspecialchars(trim($_POST["bathroom"]));
        $size = htmlspecialchars(trim($_POST["size"]));
        $floor = htmlspecialchars(trim($_POST["floor"]));
        $garage = htmlspecialchars(trim($_POST["garage"]));
        $balcony = htmlspecialchars(trim($_POST["balcony"]));
        $address = htmlspecialchars(trim($_POST["address"]));
        $built_year = htmlspecialchars(trim($_POST["built_year"]));
        $map = htmlspecialchars(trim($_POST["map"]));
        $amenities = !empty($_POST["amenities"]) ? implode(",",$_POST["amenities"]) : null;
        $is_featured = isset($_POST["is_featured"]) && $_POST["is_featured"] == "Yes" ? 1 : 0;

        
        if($name === "")
            $errors["name"][] = "<small class='form-text text-danger'>The name field is required!</small>";

        if($slug === "")
            $errors["slug"][] = "<small class='form-text text-danger'>The slug field is required!</small>";

        if($price === "")
            $errors["price"][] = "<small class='form-text text-danger'>The price field is required!</small>";

        if($location_id === "")
            $errors["location_id"][] = "<small class='form-text text-danger'>The location field is required!</small>";

        if($type_id === "")
            $errors["type_id"][] = "<small class='form-text text-danger d-block'>The type field is required!</small>";

        if($purpose === "")
            $errors["purpose"][] = "<small class='form-text text-danger d-block'>The purpose field is required!</small>";

        if($size === "")
            $errors["size"][] = "<small class='form-text text-danger'>The size field is required!</small>";

        if($address === "")
            $errors["address"][] = "<small class='form-text text-danger'>The address field is required!</small>";

        if($map === "")
            $errors["map"][] = "<small class='form-text text-danger'>The map field is required!</small>";

        if($price !== "" && !is_numeric($price))
            $errors["price"][] = "<small class='form-text text-danger'>The price field must be numeric!</small>";

        if($bedroom !== "" && !is_numeric($bedroom))
            $errors["bedroom"][] = "<small class='form-text text-danger'>The bedroom field must be numeric!</small>";

        if($bathroom !== "" && !is_numeric($bathroom))
            $errors["bathroom"][] = "<small class='form-text text-danger'>The bathroom field must be numeric!</small>";

        if($size !== "" && !is_numeric($size))
            $errors["size"][] = "<small class='form-text text-danger'>The size field must be numeric!</small>";

        if($floor !== "" && !is_numeric($floor))
            $errors["floor"][] = "<small class='form-text text-danger'>The floor field must be numeric!</small>";

        if($garage !== "" && !is_numeric($garage))
            $errors["garage"][] = "<small class='form-text text-danger'>The garage field must be numeric!</small>";

        if($balcony !== "" && !is_numeric($balcony))
            $errors["balcony"][] = "<small class='form-text text-danger'>The balcony field must be numeric!</small>";

        if(!preg_match("/^[0-9a-z-]+$/",$slug))
            $errors["slug"][] = "<small class='form-text text-danger'>Invalid slug format. Slug should only contain lowercase letters, numbers, and hyphens!</small>";

        if(empty($_FILES["featured_photo"]["name"]))
            $errors["featured_photo"][] = "<small class='form-text text-danger'>The featured photo field is required!</small>";

        if(!in_array(pathinfo($_FILES["featured_photo"]["name"],PATHINFO_EXTENSION),["png","jpg","jpeg"]))
            $errors["featured_photo"][] = "<small class='form-text text-danger'>The file type is not allowed!</small>";

        if($_FILES["featured_photo"]["size"] > 1000*1000)
            $errors["featured_photo"][] = "<small class='form-text text-danger'>File size exceeds the 1MB limit!</small>";

        if(empty($errors)){
            try{
                $path = "./public/uploads/property/";
                $img_ext = pathinfo($_FILES["featured_photo"]["name"],PATHINFO_EXTENSION);
                $img_tmp = $_FILES["featured_photo"]["tmp_name"];
                $featured_photo = uniqid().".".$img_ext;

                $sql="
                    insert into 
                        properties
                        (agent_id,name,slug,price,description,featured_photo,location_id,type_id,purpose,bedroom,bathroom,size,floor,garage,balcony,address,built_year,map,amenities,is_featured)
                    values
                        (:agent_id,:name,:slug,:price,:description,:featured_photo,:location_id,:type_id,:purpose,:bedroom,:bathroom,:size,:floor,:garage,:balcony,:address,:built_year,:map,:amenities,:is_featured)
                ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":agent_id",$_SESSION["agent"]["id"]);
                $stmt->bindValue(":name",$name);
                $stmt->bindValue(":slug",$slug);
                $stmt->bindValue(":price",$price);
                $stmt->bindValue(":featured_photo",$featured_photo);
                $stmt->bindValue(":description",empty($description) ? null : $description);
                $stmt->bindValue(":location_id",$location_id);
                $stmt->bindValue(":type_id",$type_id);
                $stmt->bindValue(":purpose",$purpose);
                $stmt->bindValue(":bedroom",$bedroom === "" ? null : $bedroom);
                $stmt->bindValue(":bathroom",$bathroom === "" ? null : $bathroom);
                $stmt->bindValue(":size",$size);
                $stmt->bindValue(":floor",$floor === "" ? null : $floor);
                $stmt->bindValue(":garage",$garage === "" ? null : $garage);
                $stmt->bindValue(":balcony",$balcony === "" ? null : $balcony);
                $stmt->bindValue(":address",$address);
                $stmt->bindValue(":built_year",empty($built_year) ? null : $built_year);
                $stmt->bindValue(":map",$map);
                $stmt->bindValue(":amenities",empty($amenities) ? null : $amenities);
                $stmt->bindValue(":is_featured",$is_featured);
                $stmt->execute();

                if($stmt->rowCount() == 0)
                    throw new PDOException("An error occurred while creating the property. Please try again later!");

                if(!is_dir($path))
                    mkdir($path,0577,true);

                list($width,$height)=getimagesize($img_tmp);
                $thumbnail=imagecreatetruecolor($width,$height);

                switch($img_ext){
                    case "jpg": case "jpeg": $sourceimage = imagecreatefromjpeg($img_tmp);break;
                    case "png": $sourceimage = imagecreatefrompng($img_tmp);break;
                    default: throw new PDOException("Unsupported image type!");
                }

                imagecopyresampled($thumbnail,$sourceimage,0,0,0,0,$width,$height,$width,$height);
                imagejpeg($thumbnail,$path.$featured_photo,90);
                imagedestroy($thumbnail);
                imagedestroy($sourceimage);

                unset($_POST["form"]);
                unset($_POST["name"]);
                unset($_POST["slug"]);
                unset($_POST["price"]);
                unset($_POST["featured_photo"]);
                unset($_POST["description"]);
                unset($_POST["location_id"]);
                unset($_POST["type"]);
                unset($_POST["purpose"]);
                unset($_POST["bedroom"]);
                unset($_POST["bathroom"]);
                unset($_POST["size"]);
                unset($_POST["floor"]);
                unset($_POST["garage"]);
                unset($_POST["balcony"]);
                unset($_POST["address"]);
                unset($_POST["built_year"]);
                unset($_POST["map"]);
                unset($_POST["amenities"]);

                $_SESSION["success"] = "The property is added successfully!";
                header("Location: ".BASE_URL."agent-properties");
                exit();
            }catch(PDOException $err){
                $error_message=$err->getMessage();
            }
        }
    }
?>

<!-- ///////////////////////
            BANNER
 /////////////////////////// -->
 <?php 
    $page_title="Add Property";
    include "./section_banner.php"
?>
<!-- ///////////////////////
            BANNER
 /////////////////////////// -->

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_agent.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <img style="width:100%" src="https://placehold.co/1000x700" alt="">
                                </div>
                                <div class="col-md-9">
                                    <div>
                                        <div class="form-group mb-3">
                                            <label for="name" class="form-label">Featured Photo *</label>
                                            <input type="file" name="featured_photo" class="form-control" value="<?php if(isset($_POST["featured_photo"])) echo $_POST["featured_photo"]?>">
                                            <?php if(isset($errors["featured_photo"])) echo $errors["featured_photo"][0]?>
                                        </div>

                                        <?php if($is_allow_featured):?>
                                            <div class="form-group mb-3">
                                                <label class="mb-2">Featured *</label>
                                                <div class="toggle-container">
                                                    <input type="checkbox" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" name="is_featured" value="Yes" <?php if(isset($_POST["is_featured"]) && $_POST["is_featured"] == "Yes") echo "checked"?>>
                                                </div>
                                            </div>
                                        <?php endif?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" name="name" class="form-control" value="<?php if(isset($_POST["name"])) echo $_POST["name"]?>">
                            <?php if(isset($errors["name"])) echo $errors["name"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="slug" class="form-label">Slug *</label>
                            <input type="text" name="slug" class="form-control" value="<?php if(isset($_POST["slug"])) echo $_POST["slug"]?>">
                            <?php if(isset($errors["slug"])) echo $errors["slug"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Price *</label>
                            <input type="text" name="price" class="form-control" value="<?php if(isset($_POST["price"])) echo $_POST["price"]?>">
                            <?php if(isset($errors["price"])) echo $errors["price"][0]?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control editor" cols="30" rows="10"><?php if(isset($_POST["description"])) echo $_POST["description"]?></textarea>
                            <?php if(isset($errors["description"])) echo $errors["description"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="location_id" class="form-label">Location *</label>
                            <select name="location_id" class="form-control select2">
                                <option value="">--- Select ---</option>
                                <?php if($stmtLoc->rowCount() > 0): foreach($allLocations as $location):?>
                                        <option value="<?php echo $location["id"]?>" <?php if(isset($_POST["location_id"]) && $_POST["location_id"] == $location["id"]) echo "selected"?>>
                                            <?php echo $location["name"]?>
                                        </option>
                                <?php endforeach;endif?>
                            </select>
                            <?php if(isset($errors["location_id"])) echo $errors["location_id"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="type_id" class="form-label">Type *</label>
                            <select name="type_id" class="form-control select2">
                                <option value="">--- Select ---</option>
                                <?php if($stmtTyp->rowCount() > 0): foreach($allTypes as $type):?>
                                        <option value="<?php echo $type["id"]?>" <?php if(isset($_POST["type_id"]) && $_POST["type_id"] == $type["id"]) echo "selected"?>>
                                            <?php echo $type["name"]?>
                                        </option>
                                <?php endforeach;endif?>
                            </select>
                            <?php if(isset($errors["type_id"])) echo $errors["type_id"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="purpose" class="form-label">Purpose *</label>
                            <select name="purpose" class="form-control select2">
                                <option value="">--- Select ---</option>
                                <option value="For Sale" <?php if(isset($_POST["purpose"]) && $_POST["purpose"] == "For Sale") echo "selected"?>>For Sale</option>
                                <option value="For Rent" <?php if(isset($_POST["purpose"]) && $_POST["purpose"] == "For Rent") echo "selected"?>>For Rent</option>
                            </select>
                            <?php if(isset($errors["purpose"])) echo $errors["purpose"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="bedroom" class="form-label">Bedrooms</label>
                            <input type="text" min="0" name="bedroom" class="form-control" value="<?php if(isset($_POST["bedroom"])) echo $_POST["bedroom"]?>">
                            <?php if(isset($errors["bedroom"])) echo $errors["bedroom"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="bathroom" class="form-label">Bathrooms</label>
                            <input type="text" min="0" name="bathroom" class="form-control" value="<?php if(isset($_POST["bathroom"])) echo $_POST["bathroom"]?>">
                            <?php if(isset($errors["bathroom"])) echo $errors["bathroom"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="size" class="form-label">Size (Sqft) *</label>
                            <input type="text" min="0" name="size" class="form-control" value="<?php if(isset($_POST["size"])) echo $_POST["size"]?>">
                            <?php if(isset($errors["size"])) echo $errors["size"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="floor" class="form-label">Floor</label>
                            <input type="text" min="0" name="floor" class="form-control" value="<?php if(isset($_POST["floor"])) echo $_POST["floor"]?>">
                            <?php if(isset($errors["floor"])) echo $errors["floor"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="garage" class="form-label">Garage</label>
                            <input type="text" min="0" name="garage" class="form-control" value="<?php if(isset($_POST["garage"])) echo $_POST["garage"]?>">
                            <?php if(isset($errors["garage"])) echo $errors["garage"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="balcony" class="form-label">Balcony</label>
                            <input type="text" min="0" name="balcony" class="form-control" value="<?php if(isset($_POST["balcony"])) echo $_POST["balcony"]?>">
                            <?php if(isset($errors["balcony"])) echo $errors["balcony"][0]?>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <input type="text" name="address" class="form-control" value="<?php if(isset($_POST["address"])) echo $_POST["address"]?>">
                            <?php if(isset($errors["address"])) echo $errors["address"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="built_year" class="form-label">Built Year</label>
                            <input type="text"  id="datepicker" name="built_year" class="form-control" value="<?php if(isset($_POST["built_year"])) echo $_POST["built_year"]?>">
                            <?php if(isset($errors["built_year"])) echo $errors["built_year"][0]?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="map" class="form-label">Location Map *</label>
                            <textarea name="map" class="form-control editor h-150" cols="30" rows="10"><?php if(isset($_POST["map"])) echo $_POST["map"]?></textarea>
                            <?php if(isset($errors["map"])) echo $errors["map"][0]?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Amenities</label>
                            <div class="row">
                                <?php if($stmtAme->rowCount() > 0): foreach($allAmenities as $amenity):?>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                            <div class="form-check">
                                                <input 
                                                    <?php if(isset($_POST["amenities"]) && in_array($amenity["id"],$_POST["amenities"])) echo 'checked="checked"'?>
                                                    id="<?php echo $amenity["id"]?>"
                                                    value="<?php echo $amenity["id"]?>" 
                                                    name="amenities[]" 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                >
                                                <i class="<?php echo $amenity["icon"]?>"></i>
                                                <label class="form-check-label" for="<?php echo $amenity["id"]?>"><?php echo $amenity["name"]?></label>
                                            </div>
                                        </div>
                                <?php endforeach;endif?>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <input type="submit" name="form" class="btn btn-primary" value="Submit" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $("input[name=name]").change(function(){
        $("input[name=slug]").val(
            $(this)
            .val()
            .toLowerCase()
            .replace(/[^\w ]+/g,"")
            .replace(/[\s-]+/g,"-")
        )
    })
    $("input[name=featured_photo]").change(function(e){
        $("form img").attr("src",URL.createObjectURL(e.target.files[0]))
    })
</script>

<?php include "./layout_footer.php"?>