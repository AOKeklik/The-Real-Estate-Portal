<?php
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    if(!isset($_GET["id"])){
        header("Location: ".BASE_URL."agetn-properties");
        exit();
    }

    $id=$_GET["id"];

    try{
        $stmtPrt = $pdo->prepare("select * from properties where id=? limit 1");
        $stmtPrt->execute([$id]);
        $property = $stmtPrt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtLoc = $pdo->prepare("select * from locations order by name asc");
        $stmtLoc->execute();
        $allLocations = $stmtLoc->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtTyp = $pdo->prepare("select * from types order by name asc");
        $stmtTyp->execute();
        $allTypes = $stmtTyp->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    try{
        $stmtAmt = $pdo->prepare("select * from amenities order by name asc");
        $stmtAmt->execute();
        $allAmenities = $stmtAmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $err){
        $error_message=$err->getMessage();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && $_POST["form"]) {
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

        if(!empty($_FILES["featured_photo"]["name"])){
            if(!in_array(pathinfo($_FILES["featured_photo"]["name"],PATHINFO_EXTENSION),["png","jpg","jpeg"]))
                $errors["featured_photo"][] = "<small class='form-text text-danger'>The file type is not allowed!</small>";

            if($_FILES["featured_photo"]["size"] > 1000*1000)
                $errors["featured_photo"][] = "<small class='form-text text-danger'>File size exceeds the 1MB limit!</small>";
        }

        if(empty($errors)){
            try{
                $path="./public/uploads/property/";
                $img_name = $_FILES["featured_photo"]["name"];
                $img_ext = pathinfo($_FILES["featured_photo"]["name"],PATHINFO_EXTENSION);
                $img_tmp = $_FILES["featured_photo"]["tmp_name"];

                if(empty($img_name))
                    $featured_photo = $property["featured_photo"];
                else
                    $featured_photo = uniqid().".".$img_ext;

                $stmt = $pdo->prepare("
                    update properties
                    set 
                    agent_id=:agent_id,
                    name=:name,
                    slug=:slug,
                    price=:price,
                    description=:description,
                    featured_photo=:featured_photo,
                    location_id=:location_id,
                    type_id=:type_id,
                    purpose=:purpose,
                    bedroom=:bedroom,
                    bathroom=:bathroom,
                    size=:size,
                    floor=:floor,
                    garage=:garage,
                    balcony=:balcony,
                    address=:address,
                    built_year=:built_year,
                    map=:map,
                    amenities=:amenities
                    where id=:id
                ");
                $stmt->bindValue(":id",$id);
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
                $stmt->execute();

                if(!$stmt->execute())
                    throw new PDOException("An error occurred while creating the property. Please try again later!");

                if(!empty($img_name)) {
                    if(!is_dir($path))
                        mkdir($path,0577,true);

                    if(is_file($path.$property["featured_photo"]))
                        unlink($path.$property["featured_photo"]);

                    list($width,$height)=getimagesize($img_tmp);
                    $thumbnail=imagecreatetruecolor($width,$height);

                    switch($img_ext){
                        case "jpg": case "jpeg": $sourceimage = imagecreatefromjpeg($img_tmp);break;
                        case "png": $sourceimage = imagecreatefrompng($img_tmp);break;
                        default: throw new PDOException("");
                    }

                    imagecopyresampled($thumbnail,$sourceimage,0,0,0,0,$width,$height,$width,$height);
                    imagejpeg($thumbnail,$path.$featured_photo,90);
                    imagedestroy($thumbnail);
                    imagedestroy($sourceimage);
                }

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

                $_SESSION["success"] = "The property is updated successfully!";
                header("Location: ".BASE_URL."agent-properties");
                exit();
            }catch(PDOException $err){
                $error_message=$err->getMessage();
            }
        }
    }
?>
<div class="page-top" style="background-image: url('uploads/banner.jpg')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit Property</h2>
            </div>
        </div>
    </div>
</div>

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
                                    <img style="width:100%" src="<?php echo PUBLIC_URL?>uploads/property/<?php echo $property["featured_photo"]?>" alt="">
                                </div>
                                <div class="col-md-9">
                                    <label for="name" class="form-label">Featured Photo *</label>
                                    <input type="file" name="featured_photo" class="form-control" value="<?php if(isset($_POST["featured_photo"])) echo $_POST["featured_photo"]?>">
                                    <?php if(isset($errors["featured_photo"])) echo $errors["featured_photo"][0]?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">Title *</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $property["name"]?>">
                            <?php if(isset($errors["name"])) echo $errors["name"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="slug" class="form-label">Slug *</label>
                            <input type="text" name="slug" class="form-control" value="<?php echo $property["slug"]?>">
                            <?php if(isset($errors["slug"])) echo $errors["slug"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Price *</label>
                            <input type="text" name="price" class="form-control" value="<?php echo $property["price"]?>">
                            <?php if(isset($errors["price"])) echo $errors["price"][0]?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control editor" cols="30" rows="10"><?php echo $property["description"]?></textarea>
                            <?php if(isset($errors["description"])) echo $errors["description"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="location_id" class="form-label">Location *</label>
                            <select name="location_id" class="form-control select2">
                                <option value="location_id">--- Select ---</option>
                                <?php if($stmtLoc->rowCount() > 0): foreach($allLocations as $location):?>
                                    <option value="<?php echo $location["id"]?>" <?php if($property["location_id"] == $location["id"]) echo "selected"?>>
                                        <?php echo $location["name"]?>
                                    </option>
                                <?php endforeach;endif?>
                            </select>
                            <?php if(isset($errors["location_id"])) echo $errors["location_id"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="type_id" class="form-label">Type *</label>
                            <select name="type_id" class="form-control select2">
                                <option value="type_id">--- Select ---</option>
                                <?php if($stmtTyp->rowCount() > 0): foreach($allTypes as $type):?>
                                    <option value="<?php echo $type["id"]?>" <?php if($type["id"] == $property["type_id"]) echo "selected"?>>
                                        <?php echo $type["name"]?>
                                    </option>
                                <?php endforeach;endif?>
                            </select>
                            <?php if(isset($errors["type_id"])) echo $errors["type_id"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="purpose" class="form-label">Purpose *</label>
                            <select name="purpose" class="form-control select2">
                                <option value="purpose">--- Select ---</option>
                                <option value="For Sale" <?php if($property["purpose"] == "For Sale") echo "selected"?>>For Sale</option>
                                <option value="For Rent" <?php if($property["purpose"] == "For Rent") echo "selected"?>>For Rent</option>
                            </select>
                            <?php if(isset($errors["purpose"])) echo $errors["purpose"][0]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="" class="form-label">Bedrooms *</label>
                            <input type="text" name="bedroom" class="form-control" value="<?php echo $property["bedroom"]?>">
                            <?php if(isset($errors["bedroom"])) echo $errors["bedroom"]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="" class="form-label">Bathrooms *</label>
                            <input type="text" name="bathroom" class="form-control" value="<?php echo $property["bathroom"]?>">
                            <?php if(isset($errors["bathroom"])) echo $errors["bathroom"]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="" class="form-label">Size (Sqft) *</label>
                            <input type="text" name="size" class="form-control" value="<?php echo $property["size"]?>">
                            <?php if(isset($errors["size"])) echo $errors["size"]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="" class="form-label">Floor</label>
                            <input type="text" name="floor" class="form-control" value="<?php echo $property["floor"]?>">
                            <?php if(isset($errors["floor"])) echo $errors["floor"]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="" class="form-label">Garage</label>
                            <input type="text" name="garage" class="form-control" value="<?php echo $property["garage"]?>">
                            <?php if(isset($errors["garage"])) echo $errors["garage"]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="" class="form-label">Balcony</label>
                            <input type="text" name="balcony" class="form-control" value="<?php echo $property["balcony"]?>">
                            <?php if(isset($errors["balcony"])) echo $errors["balcony"]?>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $property["address"]?>">
                            <?php if(isset($errors["address"])) echo $errors["address"]?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="datepicker" class="form-label">Built Year</label>
                            <input type="text" id="datepicker" name="built_year" class="form-control" value="<?php echo $property["built_year"]?>">
                            <?php if(isset($errors["built_year"])) echo $errors["built_year"]?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Location Map</label>
                            <textarea name="map" class="form-control editor h-150" cols="30" rows="10"><?php echo $property["map"]?></textarea>
                            <?php if(isset($errors["map"])) echo $errors["map"]?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Amenities</label>
                            <div class="row">
                                <?php if($stmtAmt->rowCount() > 0): foreach($allAmenities as $amenity):?>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input 
                                                <?php if(!is_null($property["amenities"]) && in_array($amenity["id"], explode(",", $property["amenities"]))) echo "checked='checked'"?>
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