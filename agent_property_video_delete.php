<?php
    include "./layout_config.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    if(
        $_SERVER["REQUEST_METHOD"] === "POST" && 
        isset($_POST["id"])
    ){
        try{
            $id = htmlspecialchars(trim($_POST["id"]));

            $stmt = $pdo->prepare("delete from property_videos where id=?");
            $stmt->execute([$id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("The video could not be deleted or does not exist!");

            unset($_POST["id"]);

            echo json_encode(["success"=>["message"=>"The video deleted successfully!"]]);
        }catch (PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }