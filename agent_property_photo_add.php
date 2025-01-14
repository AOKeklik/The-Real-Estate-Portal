<?php
    include "./layout_config.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["property_id"])) {
        try {
            $property_id = htmlspecialchars(trim($_POST["property_id"]));

            if(empty($_FILES["photo"]["name"]))
                throw new PDOException("The photo field is required!");
            
            if($_FILES["photo"]["size"] > 1000*1000)
                throw new PDOException("File size exceeds the 1MB limit!");

            if(!in_array(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION),["jpg","jpeg","png"]))
                throw new PDOException("The file type is not allowed!");


            $stmt =$pdo->prepare("
                select 
                    count(property_photos.id) as total_photos, 
                    packages.allowed_photos
                from 
                    orders
                inner join 
                    packages ON packages.id = orders.package_id
                left join 
                    property_photos on property_photos.property_id=?
                where 
                    orders.agent_id=? and orders.currently_active=?
                group by
                    packages.allowed_videos
            ");
            $stmt->execute([$property_id,$_SESSION["agent"]["id"],1]);
            $query=$stmt->fetch(pdo::FETCH_ASSOC);

            if($stmt->rowCount() == 0)
                throw new PDOException("No active package found. Please select a package to create a property!");

            if($query["total_photos"] != -1 && $query["total_photos"] >= $query["allowed_photos"])
                throw new PDOException("You have already added your maximum number of allowed photos. <br>Please upgrade your package. <br> Or please remove any of the added photos in order to add a new one!");

            $path = "./public/uploads/property/photo/";
            $img_name = $_FILES["photo"]["name"];
            $img_tmp = $_FILES["photo"]["tmp_name"];
            $img_ext = pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION);
            $photo = uniqid().".".$img_ext;

            $stmt = $pdo->prepare("
                insert into property_photos 
                    (property_id,photo) 
                values 
                    (?,?)
            ");
            $stmt->execute([$property_id,$photo]);

            if($stmt->rowCount() == 0)
                throw new PDOException("An error occurred while creating the photo. Please try again later!");

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
            imagejpeg($thumbnail,$path.$photo,90);
            imagedestroy($thumbnail);
            imagedestroy($sourceimage);

            unset($_POST["form"]);
            unset($_POST["photo"]);
            unset($_POST["property_id"]);

            $id = $pdo->lastInsertId();

            $link = PUBLIC_URL."uploads/property/photo/".$photo;
            $html = <<<HTML
                <div class="col-md-6 col-lg-3">
                    <div class="item item-delete">
                        <a href="$link" class="magnific">
                            <img src=" $link" alt="" />
                            <div class="icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="bg"></div>
                        </a>
                    </div>
                    <a data-id="$id" href="#" class="badge bg-danger mb_20">Delete</a>
                </div>
            HTML;
            
            echo json_encode(["success"=>["message"=>"The photo is added successfully!","html"=>$html]]);
        }catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }