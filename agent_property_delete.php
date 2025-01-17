<?php
    include "./layout_config.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])){
        try{
            $id = htmlspecialchars(trim($_POST["id"]));

            $stmt = $pdo->prepare("
                select 
                    * 
                from 
                    properties 
                where 
                    id=? 
                and 
                    agent_id=? 
                limit 
                    1
            ");
            $stmt->execute([$id,$_SESSION["agent"]["id"]]);
            $property = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if($stmt->rowCount() == 0)
                throw new PDOException("You do not have permission to update this property!");

            $stmt = $pdo->prepare("
                select 
                    * 
                from 
                    property_photos 
                where 
                    property_id=?
            ");
            $stmt->execute([$property["id"]]);
            $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $path = "./public/uploads/property/photo/";
            foreach($photos as $photo) {
                if(is_file($path.$photo["photo"]))
                    unlink($path.$photo["photo"]);
            }

            $stmt = $pdo->prepare("
                delete from 
                    property_photos 
                where 
                    property_id=?
            ");
            $stmt->execute([$id]);
    
            $stmt = $pdo->prepare("
                delete from 
                    property_videos 
                where 
                    property_id=?
            ");
            $stmt->execute([$id]);
    
            $stmt = $pdo->prepare("
                delete from 
                    properties 
                where 
                    id=?
            ");
            $stmt->execute([$id]);
    
            if($stmt->rowCount() == 0)
                throw new PDOException("The package could not be deleted or does not exist!");
    
            $path = "./public/uploads/property/";
            if(is_file($path.$property["featured_photo"]))
                unlink($path.$property["featured_photo"]);
    
            echo json_encode(["success"=>["message"=>"The property deleted successfully!"]]);
        }catch (PDOException $err) {
            echo json_encode(["error" => ["message"=>$err->getMessage()]]);
        }
    }
?>