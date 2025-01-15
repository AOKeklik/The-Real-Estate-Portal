<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["property_id"])) {
        try{
            if(!isset($_SESSION["admin"]))
                throw new PDOException("Unauthorized access. Permission denied.");
            
            $property_id=htmlspecialchars(trim(base64_decode($_POST["property_id"])));
            
            if(empty($property_id))
                throw new PDOException("The property you are trying to delete could not be found");    
    
            $stmt=$pdo->prepare("
                SELECT
                    *
                FROM 
                    properties
                WHERE
                    id=?
                LIMIT
                    1
            ");
            $stmt->execute([$property_id]);
            $property=$stmt->fetch(pdo::FETCH_ASSOC);
    
            if($stmt->rowCount() == 0)
                throw new PDOException("The property you are trying to delete could not be found");
    
            $stmt=$pdo->prepare("
                SELECT
                    *
                FROM 
                    property_photos
                WHERE
                    property_id=?
            ");
            $stmt->execute([$property_id]);
            $property_photos=$stmt->fetchAll(pdo::FETCH_ASSOC);
    
            $photos_path = "../public/uploads/property/photo/";
    
            foreach($property_photos as $photo)
                if(is_file($photos_path.$photo["photo"]))
                    unlink($photos_path.$photo["photo"]);
    
            $stmt=$pdo->prepare("
                DELETE FROM 
                    property_photos
                WHERE
                    property_id=?
            ");
            $stmt->execute([$property_id]);
    
            $stmt=$pdo->prepare("
                SELECT
                    *
                FROM 
                    property_videos
                WHERE
                    property_id=?
            ");
            $stmt->execute([$property_id]);
    
            $stmt=$pdo->prepare("
                DELETE FROM 
                    property_videos
                WHERE
                    property_id=?
            ");
            $stmt->execute([$property_id]);
            
            $photo_path = "../public/uploads/property/";
    
            if(is_file($photo_path.$property["featured_photo"]))
                unlink($photo_path.$property["featured_photo"]);
    
            $stmt=$pdo->prepare("
                DELETE FROM 
                    properties
                WHERE
                    id=?
            ");
            $stmt->execute([$property_id]);
    
            if($stmt->rowCount() == 0)
                throw new PDOException("An error occurred during the deletion process.");
            
            echo json_encode(["success"=>["message"=>"Property deleted successfully."]]);
        }catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }
?>