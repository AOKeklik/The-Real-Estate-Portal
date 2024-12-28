<?php
    include "./layout_config.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }    

    if(
        $_SERVER["REQUEST_METHOD"] == "POST" && 
        isset($_POST["property_id"]) &&
        isset($_POST["code"])
    ){
        try{
            $property_id = htmlspecialchars(trim($_POST["property_id"]));
            $code = htmlspecialchars(trim($_POST["code"]));

            if(empty($code))
                throw new PDOException("The code field is required!");

            $stmt = $pdo->prepare("insert into property_videos (code,property_id) values (?,?)");
            $stmt->execute([$code,$property_id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("An error occurred while creating the video. Please try again later!");
    
            $id = $pdo->lastInsertId();
            
            $html = <<<HTML
                <div class="col-md-6 col-lg-3">
                    <div class="item item-delete">
                        <a class="video-button" href="http://www.youtube.com/watch?v=$code">
                            <img src="http://img.youtube.com/vi/$code/0.jpg" alt="" />
                            <div class="icon">
                                <i class="far fa-play-circle"></i>
                            </div>
                            <div class="bg"></div>
                        </a>
                    </div>
                    <a href="#" data-id="$id" class="badge bg-danger mb_20">Delete</a>
                </div>
            HTML;

            unset($_POST["property_id"]);
            unset($_POST["code"]);
    
            echo json_encode(["success"=>["message"=>"The type is added successfully!","html"=>$html]]);
        } catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }